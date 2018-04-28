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
    }
        /**
     * @Route("/mailer")
     */
    public function mailAction()
    {
        //transport
        $transport = $this->container->get('swiftmailer.transport.real');
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        // Create a message
        $message = (new \Swift_Message('Wonderful Subject'))
        ->setFrom(['john@doe.com' => 'John Doe'])
        ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
        ->setBody('Here is the message itself')
        ;
        // Send the message
        $result = $mailer->send($message);
        die($result);
    }
}
