<?php namespace OpenDroplet\Holiday\Provider;

/**
 * Lithuanian non-working holidays provider
 *
 * @author Roman Agilov <agilovr@gmail.com>
 * @since 2018-03-27
 * @see https://en.wikipedia.org/wiki/Public_holidays_in_Lithuania
 */
class LT extends AbstractEaster
{
    /**
     * Getting non-working holidays
     *
     * @param int $year
     *
     * @return mixed
     */
    public function getHolidaysByYear(int $year)
    {
        $easter = $this->getEasterDates($year);

        $mothersDay = date('m-d', strtotime('first Sunday of May ' . $year));
        $fathersDay = date('m-d', strtotime('first Sunday of June ' . $year));

        return [
            '01-01' => $this->createData('Naujieji metai'),
            '02-16' => $this->createData('Valstybės atkūrimo diena'),
            '03-11' => $this->createData('Nepriklausomybės atkūrimo diena'),
            '05-01' => $this->createData('Tarptautinė darbo diena'),
            '06-24' => $this->createData('Joninės'),
            '07-06' => $this->createData('Karaliaus Mindaugo karūnavimo diena'),
            '08-15' => $this->createData('Žolinė'),
            '11-01' => $this->createData('Visų šventųjų diena'),
            '12-24' => $this->createData('Šv. Kūčios'),
            '12-25' => $this->createData('Šv. Kalėdos'),
            '12-26' => $this->createData('Šv. Kalėdų antroji diena'),

            // floating days
            $mothersDay => $this->createData('Motinos diena'),
            $fathersDay => $this->createData('Tėvo diena'),

            // Easter dates
            $easter['easterSunday']->format(self::DATE_FORMAT) => $this->createData('Velykos'),
            $easter['easterSunday']->modify('+1 day')->format(self::DATE_FORMAT) => $this->createData('Velykų antroji diena'),
        ];
    }
}
