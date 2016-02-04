<?php

namespace KhaibullinTest\Entities;

class Schedule extends EntityBase
{
	const WEEKDAY_MONDAY = 1;
	const WEEKDAY_TUESDAY = 2;
	const WEEKDAY_WEDNESDAY = 3;
	const WEEKDAY_THURSDAY = 4;
	const WEEKDAY_FRIDAY = 5;
	const WEEKDAY_SATURDAY = 6;
	const WEEKDAY_SUNDAY = 7;

	/** @var  int */
	protected $_weekday;

	/** @var  bool */
	protected $_isAllDay;

	/** @var  int */
	protected $_startHour;

	/** @var  int */
	protected $_stopHour;

	public function __construct(int $weekday, bool $isAllDay = false, string $startHour = '', string $stopHour = '')
	{
		$this->setWeekday($weekday);
		$this->setIsAllDay($isAllDay);
		$this->setStartHour($startHour);
		$this->setStopHour($stopHour);
	}

	/**
	 * @return int
	 */
	public function getWeekday() : int
	{
		return $this->_weekday;
	}

	/**
	 * @param int $weekday
	 * @return Schedule
	 */
	public function setWeekday(int $weekday) : Schedule
	{
		$this->_weekday = $weekday;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isIsAllDay() : bool
	{
		return $this->_isAllDay;
	}

	/**
	 * @param boolean $isAllDay
	 * @return Schedule
	 */
	public function setIsAllDay(bool $isAllDay) : Schedule
	{
		$this->_isAllDay = $isAllDay;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStartHour() : int
	{
		return $this->_startHour;
	}

	/**
	 * @param string $startHour
	 * @return Schedule
	 */
	public function setStartHour(string $startHour) : Schedule
	{
		$this->_startHour = strtotime($startHour);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStopHour() : int
	{
		return $this->_stopHour;
	}

	/**
	 * @param string $stopHour
	 * @return Schedule
	 */
	public function setStopHour(string $stopHour) : Schedule
	{
		$this->_stopHour = strtotime($stopHour);
		return $this;
	}
}