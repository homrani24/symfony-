<?php

namespace VoitureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


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
    
    /**
     * @Route("/pdfformat")
     */
    public function pdfAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        
        $html = $this->renderView('VoitureBundle:Default:pdf.html.twig', array(
            //..Send some data to your view if you need to //
        ));
        
        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
        

    }
        /**
     * @Route("/sessionmanager")
     */
    public function sessionManagerAction()
    {

        $session = new Session();
        // $session->start();
        
        // set and get session attributes
        $session->set('name', 'Drak');
        $session->get('name');
        
        // set flash messages
        $session->getFlashBag()->add('notice', 'Profile updated');
        
        // retrieve messages
        foreach ($session->getFlashBag()->get('notice', array()) as $message) {
            echo '<div class="flash-notice">'.$message.'</div>';
        }
        die();
        }

}
