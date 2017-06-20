<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\Address;
use UserBundle\Form\AddressForm;

class UserController extends Controller
{
	public function getAddressAction($id=null)
	{
		$message='';
		$title='';

		if($id == null){
		$address = new Address();
		}
		

		$form = $this->container->get('form.factory')->create(new AddressForm(), $address);
		
		$request = $this->container->get('request');
		
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
		}
		
		
		
		
		return $this->render('UserBundle:Address:address.html.twig', array(
				'title' => $title,
				'message'=>$message,
				'form' => $form->createview(),
				'address'=>$address,
		));
	}
}
