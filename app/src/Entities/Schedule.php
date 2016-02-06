<?php

namespace KhaibullinTest\Entities;

use KhaibullinTest\Repository\RepositoryBase;

class Schedule extends EntityBase
{
    const WEEKDAY_MONDAY = 1;
    const WEEKDAY_TUESDAY = 2;
    const WEEKDAY_WEDNESDAY = 3;
    const WEEKDAY_THURSDAY = 4;
    const WEEKDAY_FRIDAY = 5;
    const WEEKDAY_SATURDAY = 6;
    const WEEKDAY_SUNDAY = 7;

    const EVENT_TYPE_CLOSED = 'closed';
    const EVENT_TYPE_OPENED = 'opened';

    protected static $daysToNumbers = [
        'Monday' => self::WEEKDAY_MONDAY,
        'Tuesday' => self::WEEKDAY_TUESDAY,
        'Wednesday' => self::WEEKDAY_WEDNESDAY,
        'Thursday' => self::WEEKDAY_THURSDAY,
        'Friday' => self::WEEKDAY_FRIDAY,
        'Saturday' => self::WEEKDAY_SATURDAY,
        'Sunday' => self::WEEKDAY_SUNDAY
    ];

    /** @var  int */
    protected $_weekday;

    /** @var  bool */
    protected $_isAllDay;

    /**
     * I cannot see a reason here to store it not as a string
     *
     * @var  string
     */
    protected $_startHour;

    /**
     * Same here
     *
     * @var  string
     */
    protected $_stopHour;

	/**
	 * I would usually not have this field here, rather making Vendor object have multiple Schedules
	 * but since in that task I'm not using Vendor entities anywhere it would've lead to unnecessary complexity
	 *
	 * @var  int
	 */
    protected $_vendorID;

    /**
	 * @param int $vendorId
     * @param int $weekday
     * @param bool|false $isAllDay
     * @param string $startHour
     * @param string $stopHour
     */
    public function __construct(int $vendorId, int $weekday, bool $isAllDay = false, string $startHour = '', string $stopHour = '')
    {
		$this->setVendorID($vendorId);
        $this->setWeekday($weekday);
        $this->setIsAllDay($isAllDay);
        $this->setStartHour($startHour);
        $this->setStopHour($stopHour);
    }

    /**
     * Will create a Schedule object based on the data from vendor_special_day table or return null in case it is closed all day
     *
	 * @param int $vendorId
     * @param string $date
     * @param string $eventType
     * @param string $isAllDay
     * @param string $startHour
     * @param string $endHour
     * @return Schedule|null
     */
    public static function createFromSpecialDay(int $vendorId, string $date, string $eventType, string $isAllDay, string $startHour, string $endHour)
    {
        //if it is closed all day there simply will not be a schedule for that day
        if ((bool)$isAllDay && $eventType == self::EVENT_TYPE_CLOSED) {
            return null;
        }

        return new self(
			$vendorId,
            self::_dateToDayNumber($date),
            (bool)$isAllDay && $eventType == self::EVENT_TYPE_OPENED ? true : false,
            $startHour,
            $endHour
        );
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
    public function isAllDay() : bool
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
     * @return string
     */
    public function getStartHour() : string
    {
        return $this->_startHour;
    }

    /**
     * @param string $startHour
     * @return Schedule
     */
    public function setStartHour(string $startHour) : Schedule
    {
        $this->_startHour = $startHour;
        return $this;
    }

    /**
     * @return string
     */
    public function getStopHour() : string
    {
        return $this->_stopHour;
    }

    /**
     * @param string $stopHour
     * @return Schedule
     */
    public function setStopHour(string $stopHour) : Schedule
    {
        $this->_stopHour = $stopHour;
        return $this;
    }

	/**
	 * @return int
	 */
	public function getVendorID() : int
	{
		return $this->_vendorID;
	}

	/**
	 * @param int $vendorID
	 * @return Schedule
	 */
	public function setVendorID($vendorID) : Schedule
	{
		$this->_vendorID = $vendorID;
		return $this;
	}

    /**
     * @param string $date
     * @return int
     */
    protected static function _dateToDayNumber(string $date) : int
    {
        return self::$daysToNumbers[date('l', strtotime($date))];
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $startHourString = $this->getStartHour() ? RepositoryBase::encloseString($this->getStartHour()) : 'null';
        $stopHourString = $this->getStopHour() ?  RepositoryBase::encloseString($this->getStopHour()) : 'null';

        return
			'(' . $this->getVendorID() . ', ' .
            $this->getWeekday() . ', ' .
            (int)$this->isAllDay() . ', ' .
            $startHourString . ', ' .
            $stopHourString . '),';
    }
}