<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request) {
           $target = urlencode($this->getParameter('cas_login_target'));
           $url = 'https://'.$this->getParameter('cas_host') . ((($this->getParameter('cas_port')!=80) || ($this->getParameter('cas_port')!=443)) ? ":".$this->getParameter('cas_port') : "") . $this->getParameter('cas_path') . '/login?service=';
           return $this->redirect($url . $target . '/force');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request) {
        if (($this->getParameter('cas_logout_target') !== null) && (!empty($this->getParameter('cas_logout_target')))) {
            \phpCAS::logoutWithRedirectService($this->getParameter('cas_logout_target'));
        } else {
            \phpCAS::logout();
        }
    }

    /**
     * @Route("/force", name="force")
     */
    public function force(Request $request) {

            if ($this->getParameter("cas_gateway")) {
                if (!isset($_SESSION)) {
                        session_start();
                }

                session_destroy();
            }

            return $this->redirect($this->generateUrl('index'));
    }


    /**
     * @Route("/", name="index")
     */
    public function index(Request $request) : Response
    {
        dump($this->container->get('security.token_storage'));
        dump($this->getUser());

        return $this->render('base.html.twig', []);
    }
}
