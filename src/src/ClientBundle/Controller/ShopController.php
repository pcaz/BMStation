<?php

namespace ClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ShopController extends Controller
{
	public function shopAction(){

	return $this->container->get('templating')->renderResponse(
			'ClientBundle:Vitrine:shop.html.twig', array(
			));
	}
}