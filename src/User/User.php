<?php
namespace CodeExperts\Application\User;

use CodeExperts\Application\Entity\Entity; 
use CodeExperts\Application\Entity\EntityInterface; 

class User implements EntityInterface
{
	private $entity;

	public function __construct(Entity $entity)
	{
		$this->entity = $entity;
        
		$this->entity->setTable('users');
	}

	public function getEntity()
	{
		return $this->entity;
	}
}