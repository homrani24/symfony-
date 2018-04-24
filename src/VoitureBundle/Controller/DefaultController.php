<?php

namespace VoitureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/voiture")
     */
    public function indexAction()
    {
        return $this->render('VoitureBundle:Default:index.html.twig');
    }
    /**
     * @Route("/testrole")
     */
    public function testAction()
    {
    // $user = $this->hasRole('ROLE_ADMIN');
    //test access by role
    if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) { 
        return $this->redirect($this->generateUrl('listeV'));

    }
    if (true === $this->get('security.authorization_checker')->isGranted('ROLE_MODERATOR')) { 
        return $this->redirect($this->generateUrl('jsonvoiture'));

    }





// $user=json_decode($user);
   die();
    }
}
