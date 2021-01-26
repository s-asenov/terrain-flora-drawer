<?php


namespace App\Controller;

use App\Service\DistributionZonesUploader;
use App\Util\FormHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/email", name="email_send", methods={"POST"})
     */
    public function sendMail(Request $request, MailerInterface $mailer, FormHelper $helper)
    {
        $form = $request->request->all();

        if (!$helper->checkFormData(['from', 'subject', 'text'], $form)) {
            return new JsonResponse([
                'status' => FormHelper::META_ERROR,
                'meta' => FormHelper::MISSING_CREDENTIALS
            ]);
        }

        $email = (new Email())
            ->from($form['from'])
            ->to('support@flora.noit.eu')
            ->subject($form['subject'])
            ->text($form['text']);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse([
                'status' => FormHelper::META_ERROR,
                'meta' => "not sent"
            ], 400);
        }

        return new JsonResponse([
            'status' => FormHelper::META_SUCCESS,
            'meta' => "sent"
        ]);
    }

    /**
     * @Route ("/register", name="app_register", methods={"GET"})
     */
    public function registerPage()
    {
        return $this->render('react/react.html.twig');
    }

    /**
     * @Route ("/login", name="app_login", methods={"GET"})
     */
    public function loginPage()
    {
        return $this->render('react/react.html.twig');
    }

    /**
     * @Route ("/", name="app_homepage", methods={"GET"})
     * @Route ("/admin/{reactRoute}", name="app_admin", methods={"GET"})
     * @Route("/{reactRoute}", name="app_react", methods={"GET"})
     */
    public function index()
    {
        return $this->render('react/react.html.twig');
    }
}