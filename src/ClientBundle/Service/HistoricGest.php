<?php


namespace ClientBundle\Service;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use ClientBundle\Entity\Historic;


/**
 * 
 * @author pascaz10
 * 
 */

class HistoricGest extends Extension {

	private $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $entityManager)
	{
	$this->em=$entityManager;
	}
	
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
	}
	
        public function addHistoric(\ClientBundle\Entity\Client $client,$type,$event_id)
        {
                $historic=new Historic();
		$historic->setClient($client);
		$historic->setType($type);
		$historic->setEventId($event_id);
		$this->em->persist($historic);
		$this->em->flush();
                return true;
        }
}