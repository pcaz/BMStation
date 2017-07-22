<?php


namespace ClientBundle\Service;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;


/**
 * 
 * @author pascaz10
 * 
 */

class ClientGest extends Extension {

	private $em;
        private $directory;
	
	public function __construct(\Doctrine\ORM\EntityManager $entityManager, $directory)
	{
	$this->em=$entityManager;
        $this->directory = $directory;
	}
	
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('service.yml');
	}
	
        public function deleteClient(\ClientBundle\Entity\Client $client)
        {
            // on efface l'historique
             
            $historic = $this->em->getRepository('\ClientBundle\Entity\Historic')->findByClient($client);
            foreach($historic as $event)
            {
                $this->em->remove($event);
            }
            
            // le client
            
            $this->em->remove($client);
            
            // L'adresse
            
            $address = $client->getDeliveryAddress();
            $this->em->remove($address);
            
            // Le User
            
            $user = $client->getUser();
            $this->em->remove($user);
            
            // les paniers
            
            $basket = $this->em->getRepository('\ClientBundle\Entity\Basket')->findByClient($client);
            
            foreach($basket as $panier)
            {
              $lineOrder = $this->em->getRepository('\ClientBundle\Entity\LineOrder')->findByBasket($panier);
              foreach($lineOrder as $line)
              {
                  $this->em->remove($line);
              }
                
              $this->em->remove($panier);
            }
            
            // les annonces
            
            $annonces = $this->em->getRepository('\ClientBundle\Entity\Annonce')->findByClient($client);
            
            foreach($annonces as $annonce)
            {
                $photos = $this->em->getRepository('\ClientBundle\Entity\Photo')->findByAnnonce($annonce);
                foreach($photos as $photo)
                {
                    $val=$this->directory.'/'.$photo->getPicture();
                    if(is_file($val)) unlink($val);
                    $this->em->remove($photo);
                }
                $this->em->remove($annonce);
                
            }
            
            // enfin on flushe
            $this->em->flush();
            
            return true;
        }
        }
