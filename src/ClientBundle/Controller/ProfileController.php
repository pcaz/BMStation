<?php
namespace ClientBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Form\Address;
use ClientBundle\Form\ProfileForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormError;
/**
 *
 *  ProfilController
 * 
 *  Classe de gestion des profiles utilisateurs
 * 
 */

class ProfileController extends Controller
{
    public function profileClientAction(Request $request)
    {
        
        $user = $this->getuser();
        
        if (!$user)
        {
            return $this->render('ClientBundle:Profile:denied.html.twig');
        }
     
        
        $em=$this->get('doctrine')->getManager();
        $cl = $em->getRepository("\ClientBundle\Entity\Client")->findOneByUser($user);
        $client=$em->getRepository("\ClientBundle\Entity\Client")->findAllValues($cl);
        if($client==null){
            // user mais pas client ??? administrateur ?
              return $this->render('ClientBundle:Profile:denied.html.twig');
        }
        $form = $this->createForm(new ProfileForm(), $client);
        
        return $this->get('templating')->renderResponse(
				'ClientBundle:Profile:clientProfile.html.twig', array(
						'form'=> $form->createView(),
				));
    }
    
    public function setProfileClientAction(Request $request, \ClientBundle\Entity\Client $client)
    {
        $form = $this->createForm(new ProfileForm());
        $form->handleRequest($request);
        if($form->isValid())
        {
            
        }
    }
}

