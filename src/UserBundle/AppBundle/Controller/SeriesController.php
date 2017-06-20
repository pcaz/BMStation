<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Series;
use AppBundle\Form\SeriesForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeriesController extends Controller
{
	/**
	 * @Route("/series/", name="series_list")
	 */
	public function listAction()
	{
		$em= $this->container->get('doctrine')->getManager();
		$series = $em->getRepository('AppBundle:Series')->findAll();

		return $this->container->get('templating')->renderResponse(
				'AppBundle:Series:list.html.twig', array('series'=>$series));
	}
	/**
	 * @Route("/series/add", name="series_add")
	 * @Route("/series/modify", name="series_modify")
	 */
	public function editAction($id=null)
	{
	
		$message='';
		$title='';
	
		$em=$this->container->get('doctrine')->getManager();
	
		if(isset($id))
		{
			$title=$this->container->get('translator')->trans('modify_series');
			$series=$em->find('AppBundle:Series',$id);
	
			if(!$series)
			{
				$message= $this->container->get('translator')->trans('no_series');
			}
		}
		else
		{
			$title=$this->container->get('translator')->trans('add_series');
			$series = new Series();
		}
	
		$form = $this->container->get('form.factory')->create(new SeriesForm(), $series);
		$request = $this->container->get('request');
	
		if ($request->getMethod() == 'POST')
		{
			$form->bind($request);
	
			if ($form->isValid())
			{
				$em = $this->container->get('doctrine')->getManager();
				// si je suis en ajout, et que la série existe déjà, il y a erreur !
				if(!$id and $em->getRepository('AppBundle:Series')->findOneBy(
						array('name'=>$series->getName())))
				{
					$message=$this->container->get('translator')->trans('existing_series');
					return $this->container->get('templating')->renderResponse(
							'AppBundle:Series:edit.html.twig',
							array(
									'form' => $form->createView(),
									'message' => $message,
									'title' => $title));
				}
				// sinon, j'eregistre!
				else
				{
					$em->persist($series);
					$em->flush();
					$message=$this->container->get('translator')->trans('series_added');
					return new RedirectResponse($this->container->get('router')->generate('series_list'));
				}
				
			}
		}
		return $this->container->get('templating')->renderResponse(
				'AppBundle:Series:edit.html.twig',
				array(
						'form' => $form->createView(),
						'message' => $message,
						'title' => $title
				));
	}
	
	public function deleteAction($id=null) {
		
		if( $id==0)
			throw new InvalidArgumentException("inexistant_category");
		
			$em = $this->get('doctrine')->getEntityManager();
			$series = $em->find('AppBundle:Series', $id);
			$models=$em->getRepository('AppBundle:Model')->findBySeries($id);
			if(count($models)==0){
			$em->remove($series);
			$em->flush();
			}
			else{
				$request = $this->get('request');
				$request->getSession()->getFlashBag()->add('error', $this->get('translator')->trans('model_existant'));
			}
			
			return new RedirectResponse($this->get('router')->generate('series_list'));
	}
}