import React, { useContext } from "react";
import "./Login.css";
import { useFormik } from "formik";
import Form from "react-bootstrap/Form";
import Button from "react-bootstrap/Button";
import axios from "axios";
import { NavLink, useHistory } from "react-router-dom";
import { setAuth } from "../../helpers/auth";
import FormInvalidFeedback from "../Utils/FormInvalidFeedback";
import httpService from "../../helpers/api/apiInterceptor";
import AuthContext from "../Utils/context/AuthContext";

const initialValues = {
  email: "",
  password: "",
};

const validate = (values, props) => {
  Object.keys(values).map((k) => (values[k] = values[k].trim()));

  const errors = {};

  if (!values.email) {
    errors.email = "Required";
  } else if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(values.email)) {
    errors.email = "Invalid email address";
  }

  if (!values.password) {
    errors.password = "Required";
  } else if (values.password.length < 6) {
    errors.password = "Too short";
  } else if (values.password.length > 30) {
    errors.password = "Too long";
  }

  return errors;
};

function Login() {
  const history = useHistory();
  const context = useContext(AuthContext);

  const formik = useFormik({
    initialValues,
    validate,
    onSubmit: (values, props) => {
      axios
        .post("/api/login", values)
        .then((response) => {
          const user = response.data.user;
          let admin = user.roles.includes("ROLE_SUPER_ADMIN");

          context.setUser(true, admin);
          httpService.setupInterceptors(history);

          if (!user.isVerified) {
            history.push("/verify");
          } else {
            history.push("/");
          }
        })
        .catch((error) => {
          const response = error.response;
          if (response.status === 400) {
            props.setErrors({
              password: response.data,
            });
          }
        });
    },
  });

  const { touched, errors, handleSubmit, values, handleChange } = formik;

  return (
    <Form className="security-form" onSubmit={handleSubmit}>
      <h2 className="font-weight-bold text-center">Вход</h2>
      <Form.Group controlId="email">
        <Form.Label>Имейл</Form.Label>
        <Form.Control
          type="email"
          value={values.email}
          placeholder="Въведете имейл"
          isInvalid={touched.email && errors.email}
          onChange={handleChange}
        />
        <FormInvalidFeedback error={errors.email} />
      </Form.Group>
      <Form.Group controlId="password">
        <Form.Label>Парола</Form.Label>
        <Form.Control
          type="password"
          value={values.password}
          onChange={handleChange}
          placeholder="Въведете парола"
          isInvalid={touched.password && errors.password}
        />
        <FormInvalidFeedback error={errors.password} />
        <p className="mb-2">
          Нямате профил? <NavLink to="/register">Регистрирай се!</NavLink>
        </p>
      </Form.Group>

      <Button variant="primary" type="submit">
        Вход
      </Button>
    </Form>
  );
}

export default Login;
