<?php

namespace VoitureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VoitureBundle\Entity\Marque;
use VoitureBundle\Entity\Voiture;
use VoitureBundle\Entity\Image;
use  Symfony\Component\Form\Extension\Core\Type\TextType;
use  Symfony\Component\Form\Extension\Core\Type\DateType;
use  Symfony\Bridge\Doctrine\Form\Type\EntityType;
use  Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;

class VoitureController extends Controller
{
/**
*@Route("/addMarque/{nom}")
*/
    public  function  addMarqueAction($nom)
    {
        $m=  new  Marque();
        $m->setNomMarque($nom);
        $em=$this->getDoctrine()->getManager();
        $em->persist($m);
        $em->flush();
        return  $this->render('VoitureBundle:Default:addMarque.html.twig',array('marque'  =>  $m));
    }
    /**
    *@Route("/addVoiture/")
    */
    public  function  addVoitureAction(Request  $request)
    {
    $voiture=new  Voiture();
    //générer  le  formulaire
    $form=$this->createFormBuilder($voiture)
            ->add('numSerie',TextType::class)
            ->add('dateMiseCircu',DateType::class)
            ->add('path',FileType::class,array('multiple' => true,'mapped' => false))
            ->add('marque',EntityType::class,array  ('class'  =>  'VoitureBundle:Marque','choice_label'  =>  'nomMarque','choice_value'  =>'id'))
            ->add('Add',SubmitType::class)->getForm();
    $form->handleRequest($request);
            //tester  si  le  formuaire  est  valide
            if($form->isValid())
            {
                $data= $form->getData(); 
                $files= $form->get('path')->getData();    
                $em=$this->getDoctrine()->getManager();
                $em->persist($voiture);
                $em->flush();            
                foreach($files as $file ){
                    $image=new Image;                    
                    $image->setVoiture($voiture);
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    $file->move(
                        $this->container->getParameter('brochures_directory'),
                        $fileName
                    );
                    $image->setPath($fileName);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($image);
                    $em->flush(); 
                }
            }
            return  $this->render('VoitureBundle:Default:formvoiture.html.twig',array('f'  =>  $form->createView()));
    }
    /**
    *@Route("/listeVoitures",  name="listeV")
    */
    public  function  listeVoitureAction()
    {
        $voitures  =  $this->getDoctrine()->getRepository("VoitureBundle:Voiture")->findAll();
        return  $this->render('VoitureBundle:Default:listevoiture.html.twig',array('voitures'  =>  $voitures));
    }
        /**
    *@Route("/testajax")
    */
    public  function  testVoitureAction()
    {
        return  $this->render('VoitureBundle:Default:testajax.html.twig');
    }
    /**
    *@Route("/jsonVoitures",  name="jsonvoiture")
    */
    public  function  jsonVoitureAction()
    {
        $voitures  =  $this->getDoctrine()->getRepository("VoitureBundle:Voiture")->findAll();
        $formatted = [];
        foreach ($voitures as $voiture) {
            $formatted[] = [
                'id' => $voiture->getId(),
                'serie'=> $voiture->getNumSerie(),
                'photo'=> $voiture->getPhoto(),                    
            ];
        }

        $response=new JsonResponse(json_encode($formatted));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
}