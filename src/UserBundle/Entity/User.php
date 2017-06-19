<?php

namespace UserBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	

	public function __construct()
	{
		parent::__construct();
	}

	public function toArray(){
		$result=array(
				'id'=>$this->id,
				'username'=> $this->username,
				'email'=>$this->email,
				'enabled'=>$this->enabled,
				'password'=>$this->password,
				'roles'=>$this->roles,
				'last_login'=>$this->getLastLogin(),
		);
		return $result;
	}
}