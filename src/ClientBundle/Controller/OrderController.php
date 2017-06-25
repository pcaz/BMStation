<?php
namespace ClientBundle\Controller;

use ClientBundle\Entity\Client;
use ClientBundle\Entity\Address;
use ClientBundle\Entity\Historic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ClientBundle\Form\NameBasketForm;
use Symfony\Component\HttpFoundation\RedirectResponse;


class OrderController extends Controller
{
	public function summaryAction($id=null){
		
		$request = $this->get('request');
		$em=$this->get('doctrine')->getManager();
		$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
		$lineOrders=$em->getRepository('ClientBundle\Entity\LineOrder')->findByBasket($id);
		
		
		foreach($lineOrders as $id=>$lineOrder) {
			$em->getRepository('AppBundle\Entity\Product')->find($lineOrder->getProduct()->getId());
			$products[$id]=new \ClientBundle\PseudoClasses\PseudoProduct($lineOrder->getProduct(),$em);
			$products[$id]->setCommand($lineOrder->getNumber());
		}
		
		
		$images=$this->getParameter('app.images_URL');
		
		return $this->get('templating')->renderResponse(
				'ClientBundle:Order:summary.html.twig', array(
						'images'=>$images,
						'products'=>$products,
						'basket'=> $basket,
				));
	}
	
	public function paymentAction($id=null){
		$em=$this->get('doctrine')->getManager();
		$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
		return $this->get('templating')->renderResponse(
				'ClientBundle:Order:payment.html.twig', array(
				'basket'=>$basket,		
						
				));
	}
	
	public function billAction($id=null){
		
		$request = $this->get('request');
		$em=$this->get('doctrine')->getManager();
		$basket=$em->getRepository('ClientBundle\Entity\Basket')->find($id);
		$lineOrders=$em->getRepository('ClientBundle\Entity\LineOrder')->findByBasket($id);
		
		
		foreach($lineOrders as $id=>$lineOrder) {
			$em->getRepository('AppBundle\Entity\Product')->find($lineOrder->getProduct()->getId());
			$products[$id]=new \ClientBundle\PseudoClasses\PseudoProduct($lineOrder->getProduct(),$em);
			$products[$id]->setCommand($lineOrder->getNumber());
		}
		
		$client=$em->getRepository('ClientBundle\Entity\Client')->find($basket->getClient()->getId());
		
		$template=$this->renderView('ClientBundle:Order:bill.html.twig',array(
				'client'=>$client,
				'date'=>new \DateTime(),
				'products'=>$products,
				'discount'=>0,
				'tva'=>20.6,
				'shipping'=>10
		));
		
		$html2pdf=$this->get('app.html2pdf');
		$html2pdf->create();
		$doc= $html2pdf->generatePDF($template,'Facture');
		return $doc;
	}
	
}