<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Doctrine\DBAL\Types\TextType;
use ClientBundle\Entity\Contact;
use ClientBundle\Entity\Historic;



class ContactController extends Controller{
	

public function listContactAction() {
	$list=array();
	
	$em=$this->get('doctrine')->getManager();
	$contacts=$em->getRepository('ClientBundle:Contact')->findInOrder();


	for($i=0;$i<count($contacts);$i++){
		$list[$i]=new \AppBundle\PseudoClasses\PseudoContact($contacts[$i],$em);
	}

	$list = $this->get('knp_paginator')->paginate($list,$this->get('request')->query->get('page', 1),10);
	return $this->container->get('templating')->renderResponse(
			'AppBundle:Contact:listContact.html.twig',
			array(
					'title'=>$this->get('translator')->trans('listcontact'),
					'contacts' => $list,

			));

}

public function editContactAction($id=null){

	$list=array();

	$em=$this->get('doctrine')->getManager();
	$request=$this->get('request');
	$contact=$em->getRepository('ClientBundle:Contact')->find($id);

	if (!$contact) {
		throw new InvalidArgumentException("contact not existant");
	}
	if($request->getMethod()=="POST"){

		$validate = $request->request->get('validate');
		if($validate != null){
			// cela ne peut être qu'un changement de validation, on est en Ajax
			$this->get('session')->set('validate', $validate);
			return true;
		}

		$date=new \DateTime();
		$client=$em->getRepository('ClientBundle\Entity\Client')->findByEmail($contact->getEmail());
		if($this->get('session')->get('validate')=="1"){	
			// et on gère l'historic
			$historic=new Historic();
			$historic->setClient($client);
			$historic->setType('Close Contact');
			$historic->setEventId($contact->getId());
			$historic->setDate($date);
			$contact->setValidate($date);
			$em->persist($historic);
			$em->flush();
		}
		if(($this->get('session')->get('validate')=="O") || ($contact->getValidate()<> $date)){
			$historic=new Historic();
			$historic->setClient($client);
			$historic->setType('Reopen Contact');
			$historic->setEventId($contact->getId());
			$historic->setDate($date);
			$contact->setValidate(null);
			$em->persist($historic);
			$em->flush();
		}
		
		
		$em->persist($contact);
		$em->flush();

		$contacts=$em->getRepository('ClientBundle:Contact')->findInOrder();


		for($i=0;$i<count($contacts);$i++){
			$list[$i]=new \AppBundle\PseudoClasses\PseudoContact($contacts[$i],$em);
		}

		$list = $this->get('knp_paginator')->paginate($list,$this->get('request')->query->get('page', 1),10);

		return $this->container->get('templating')->renderResponse(
				'AppBundle:Contact:listContact.html.twig',
				array(
						'title'=>$this->get('translator')->trans('listcontact'),
						'contacts' => $list,
						

				));


	}
	else {
		$pseudo=new \AppBundle\PseudoClasses\PseudoContact($contact,$em);
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Contact:editContact.html.twig',
				array(
						'title'=>$this->get('translator')->trans('editcontact'),
						'contact' => $pseudo,
						'return'=>$this->get('router')->generate('listcontact'),

				));

	}

  
  }
  		
  
}


