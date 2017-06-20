<?php

namespace ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ClientBundle\Entity\Contact;
use ClientBundle\Form\ContactForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ClientBundle\Entity\Historic;

class ContactController extends Controller{
	
	public function contactAction() {
	
		$contact = new Contact();
		$form = $this->createForm(new ContactForm(), $contact);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			
			$leave= $request->request->get('leave');
			// c'est du javascript et de l'ajax en asynchrone
			if($leave){
				$this->get('session')->remove('leave'); 
				$this->get('session')->set('leave',$leave);
				return true;
			}
			
			
			$form->handleRequest($request);

			if ($form->isValid()) {
				
				$em=$this->get('doctrine')->getManager();
				$em->persist($contact);
				$em->flush();
				
				$client=$em->getRepository('\ClientBundle\Entity\Client')->findByEmail($contact->getEmail());
				
				if($client){
					$historic=new Historic();
					$historic->setClient($client);
					$historic->setType('Contact');
					$historic->setEventId($contact->getId());
					$historic->setDate(new \DateTime());
					$em->persist($historic);
					$em->flush();
				}
				
				// si besoin, on envoie un mail à l'utilisateur
			
				if($this->get('session')->get('leave')=="1"){
					$mailer = $this->get('mailer');
					$message = new \Swift_Message();
					$urlSite=$this->getParameter('app.URL');
					$imgUrl = $urlSite.'/images/logo.png';			
				

					$message->setFrom('no_reply@bmstation.fr')
					->setTo($contact->getEmail())
					->setSubject("Message à BMSTation")
					->setContentType('text/html')
					->setBody(
							$this->renderView('Emails/contact.html.twig',
									array(
											'url'=>$imgUrl,
											'contact'=>$contact,
											'team'=>'BMStation',
											'site'=>$urlSite,
						
									)
									));
					$mailer->send($message);
					$this->get('session')->remove('leave'); // pour le coups suivant
				}
										
				return new RedirectResponse($this->container->get('router')->generate('homepage'));
			}
		}

		return $this->render('ClientBundle:Vitrine:contact.html.twig', array(
			'form' => $form->createView()
	));
	}
}