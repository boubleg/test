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
     * Will create a Schedule object based on the data from vendor_special_day table or return null in case it is closed all day
     *
     * @param string $date
     * @param string $eventType
     * @param string $isAllDay
     * @param string $startHour
     * @param string $endHour
     * @return Schedule|null
     */
    public static function createFromSpecialDay(string $date, string $eventType, string $isAllDay, string $startHour, string $endHour)
    {
        $isAllDayClosed = (bool)$isAllDay && $eventType == self::EVENT_TYPE_CLOSED ? true : false;

        //if it is closed all day there simply will not be a schedule for that day
        if ($isAllDayClosed) {
            return null;
        }

        return new self(
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

    /**
     * @param string $date
     * @return int
     */
    protected static function _dateToDayNumber(string $date) : int
    {
        return self::$daysToNumbers[date('l', strtotime($date))];
    }
}