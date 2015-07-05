<?php
namespace CodeExperts\Application\Entity;

use CodeExperts\Application\Entity\Entity;
use \PDO;

class EntityTest extends \PHPunit_Framework_Testcase
{
	private $entity;
	private $conn;

	public function setUp()
	{
		$this->conn = new PDO('sqlite::memory:');

		$this->conn->exec("
			CREATE TABLE IF NOT EXISTS 'users' (
				'id' INTEGER PRIMARY KEY,
				'name' TEXT,
				'email' TEXT,
				'created_at' TIMESTAMP,
				'updated_at' TIMESTAMP
			);
		");
		
		date_default_timezone_set('America/Sao_Paulo');

		$this->entity = new Entity($this->conn);
		$this->entity->setTable('users');
	}

	public function tearDown()
	{
		$this->conn->exec("DROP TABLE users");
	}

	public function testInsertOfNewRegister()
	{
		$data = array(
			'name'  => 'Joaozinho Sa',
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$insert = $this->entity->save($data);
		
		
		$this->assertTrue($insert);
	}

	public function testUpdateOfRegister()
	{
		$data = array(
			'name'  => 'Joãozinho Sá',
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$this->entity->save($data);
		
		$data = array(
			'id'    => 1,
			'name'  => 'Joãozinho Sá Updated',
			'updated_at' => date('Y-m-d')
		);

		$this->entity->save($data);

		$register = $this->entity->where(array('id' => 1));

		$this->assertEquals($register[0]['name'], 'Joãozinho Sá Updated');

	}

	public function testSelectAllRegisters()
	{
		$i = 0;

		$data = array(
			'name'  => 'Joãozinho Sá ' . ++$i,
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$this->entity->save($data);

		$data = array(
			'name'  => 'Joãozinho Sá ' . ++$i,
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$this->entity->save($data);

		$register = $this->entity->getAll();

		$this->assertEquals($this->entity->total(), count($register));
	}

	public function testGetpecificRegister()
	{

		$data = array(
			'name'  => 'Joãozinho Sá',
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$this->entity->save($data);

		$register = $this->entity->where(array('id' => 1));
		
		$this->assertEquals('Joãozinho Sá', $register[0]['name']);
		$this->assertEquals('sa@email.com.br', $register[0]['email']);
	}

	public function testDeleteRegister()
	{
		$data = array(
			'name'  => 'Joãozinho Sá',
			'email' => 'sa@email.com.br',
			'created_at' => date('Y-m-d'),
			'updated_at' => date('Y-m-d'),
		);

		$this->entity->save($data);

		$id = 1;

		$delete = $this->entity->delete($id);

		$this->assertTrue($delete);
		$this->assertEquals(0, $this->entity->total());
	}
}