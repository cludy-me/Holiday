<?php

namespace OpenDroplet\Holiday;

use OpenDroplet\Holiday\Model\Holiday;

/**
 * Class Util
 */
class Util
{

    private $defaultIso = null;

    /**
     * Constructor
     * 
     * @param string $defaultIso
     */
    function __construct(string $defaultIso = 'LT')
    {
        $this->defaultIso = strtoupper($defaultIso);
    }

    /**
     * Make class instance
     * 
     * @param string $defaultIso
     * 
     * @return Util
     */
    public static function make(string $defaultIso = 'LT')
    {
        return new static($defaultIso);
    }

    /**
     * Checks wether a given date is a holiday
     *
     * @param \DateTime|string $date
     * @param string           $state
     *
     * @return bool
     */
    public function isHoliday($date, $state = null)
    {
        return ($this->getHoliday($date, $state) !== null);
    }

    /**
     * Checks wether a given date is a weekend
     *
     * @param \DateTime|string $date
     *
     * @return bool
     */
    public function isWeekend($date)
    {
        $date = $this->getDateTime($date);

        return ($date->format('N') >= 6);
    }

    /**
     * Checks wether a given date is a bussiness day
     *
     * @param \DateTime|string $date
     * @param string           $state
     *
     * @return bool
     */
    public function isBusinessDay($date, $state = null)
    {
        return (!$this->isHoliday($date, $state) && !$this->isWeekend($date));
    }

    /**
     * Provides detailed information about a specific holiday
     *
     * @param \DateTime|string $date
     * @param string           $state
     *
     * @return Holiday|null
     */
    public function getHoliday($date, $state = null)
    {
        $date = $this->getDateTime($date);
        $provider = $this->getProvider();

        return $provider->getHolidayByDate($date, $state);
    }

    /**
     * Provides detailed information about a year holidays
     *
     * @param int     $year
     *
     * @return mixed
     */
    public function getHolidays($year)
    {
        $provider = $this->getProvider();
        $holidays = $provider->getHolidaysByYear($year);

        ksort($holidays);

        return $holidays;
    }

    /** 
     * @param \DateTime|string $date
     * @param int              $days
     * @param string           $iso
     * @param string           $state
     * 
     * @return \DateTime
     */
    public function getNextBusinessDay($date, $days = 1, $state = null)
    {
        $date = $this->getDateTime($date);

        if ($days <= 0)
            return $date;

        while ($days--) {
            do {
                $date = $date->modify('+1 day');
            } while (!$this->isBusinessDay($date, $state));
        }

        return $date;
    }

    /** 
     * @param \DateTime|string $from
     * @param \DateTime|string $to
     * @param string           $state
     * 
     * @return \DateTime
     */
    public function getBusinessDays($from, $to, $state = null)
    {
        $from = $this->getDateTime($from);
        $to = $this->getDateTime($to);
        $bussinessDays = 0;

        while ($from != $to) {
            if ($this->isBusinessDay($from, $state))
                $bussinessDays++;

            $from = $from->modify((($from <= $to ? '+' : '-') . '1 day'));
        }

        if ($this->isBusinessDay($from, $state))
            $bussinessDays++;

        return $bussinessDays;
    }

    /**
     * Instantiates a provider for a given iso code
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        $iso = strtoupper($this->defaultIso);
        $class = '\\OpenDroplet\\Holiday\\Provider\\' . $iso;

        if (!class_exists($class))
            throw new \Exception("'{$iso}' provider not found");

        return new $class;
    }

    /**
     * @param \DateTime|string $date
     *
     * @return \DateTime
     */
    protected function getDateTime($date)
    {
        return (($date instanceof \DateTime) ? $date : new \DateTime($date));
    }
}
