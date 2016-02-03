<?php

namespace Entities;

class Vendor extends EntityBase
{
	/** @var int  */
	protected $_id;

	/** @var string  */
	protected $_name;

	public function __construct(int $id, string $name = '')
	{
		$this->_id = $id;
		$this->_name = $name;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->_id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}
}