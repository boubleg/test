<?php

namespace KhaibullinTest\Entities;

class Vendor extends EntityBase
{
	/** @var int  */
	protected $_id;

	/** @var string  */
	protected $_name;

	/** @var  array */
	protected $_schedules = [];

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
	 * @return Vendor
	 */
	public function setId($id) : Vendor
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
	 * @return Vendor
	 */
	public function setName($name) : Vendor
	{
		$this->_name = $name;
		return $this;
	}

	/**
	 * @return \SplFixedArray
	 */
	public function getSchedules()
	{
		return $this->_schedules;
	}

	/**
	 * @param array $schedules
	 * @return Vendor
	 */
	public function setSchedules(array $schedules) : Vendor
	{
		$this->_schedules = $schedules;
		return $this;
	}

	/**
	 * @param Schedule $schedule
	 * @return Vendor
	 */
	public function addSchedule(Schedule $schedule) : Vendor
	{
		$this->_schedules[]= $schedule;
		return $this;
	}
}