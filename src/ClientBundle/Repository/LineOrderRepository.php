<?php
namespace ClientBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LineOrderRepository extends EntityRepository
{
	public function findByBasket($basket){
		
		$qb = $this->createQueryBuilder('l')
		->where('l.basket=:basket')
		->setparameter('basket',$basket);
		$query=$qb->getQuery();
		$lineOrders=$query->getResult();
		return $lineOrders;
		
	}
	
	
}
	