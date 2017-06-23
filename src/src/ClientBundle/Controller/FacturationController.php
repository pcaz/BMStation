<?php

namespace ClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FacturationController extends Controller
{
	
	public function factureAction()
	{
			$articles=[
					[
							'title'=>'Article 1',
							'count'=>2,
							'price'=>15,
					],
					[
							'title'=>'Article 2',
							'count'=>1,
							'price'=>10,
					],
			];
			$template=$this->renderView(':PDF:facture.html.twig',array(
					'articles'=>$articles,
					'remise'=>25,
					'tva'=>20.6,
					'frais_de_port'=>10
			));
			
			$html2pdf=$this->get('app.html2pdf');
			$html2pdf->create();
			$doc= $html2pdf->generatePDF($template,'Facture');
			return $doc;
			
		}
}	
