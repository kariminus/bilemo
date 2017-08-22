<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);
        return $this->render(
            'default/login.html.twig',
            array(
                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }

    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }

    public function registerAction(Request $request)
    {
        $form = $this->createForm(RegistrationForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );

        }
        return $this->render('default/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
