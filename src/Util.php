<?php namespace OpenDroplet\Holiday;

use OpenDroplet\Holiday\Model\Holiday;

/**
 * Class Util
 */
class Util
{

    /**
     * Make class instance
     * 
     * @return Util
     */
    public static function make()
    {
        return new static;
    }

    /**
     * Instantiates a provider for a given iso code
     *
     * @param string $iso
     *
     * @return ProviderInterface
     */
    protected function getProvider($iso)
    {
        $class = '\\OpenDroplet\\Holiday\\Provider\\' . $iso;

        return ((class_exists($class)) ? new $class : null);
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

    /**
     * @param string $iso
     *
     * @return string
     */
    protected function getIsoCode($iso)
    {
        return strtoupper($iso);
    }

    /**
     * Checks wether a given date is a holiday
     *
     * This method can be used to check whether a specific date is a holiday
     * in a specified country and state
     *
     * @param string           $iso
     * @param \DateTime|string $date
     * @param string           $state
     *
     * @return bool
     */
    public function isHoliday($iso, $date = 'now', $state = null)
    {
        return ($this->getHoliday($iso, $date, $state) !== null);
    }

    /**
     * Provides detailed information about a specific holiday
     *
     * @param string           $iso
     * @param \DateTime|string $date
     * @param string           $state
     *
     * @return Holiday|null
     */
    public function getHoliday($iso, $date = 'now', $state = null)
    {
        $iso = $this->getIsoCode($iso);
        $date = $this->getDateTime($date);
        $provider = $this->getProvider($iso);

        if (!$provider)
            throw new \Exception($iso . " provider not found");

        return $provider->getHolidayByDate($date, $state);
    }

     /**
     * Provides detailed information about a year holidays
     *
     * @param string  $iso
     * @param int     $year
     *
     * @return mixed
     */
    public function getHolidays($iso, $year)
    {
        $iso = $this->getIsoCode($iso);
        $provider = $this->getProvider($iso);

        if (!$provider)
            throw new \Exception($iso . " provider not found");

        return $provider->getHolidaysByYear($year);
    }
}
