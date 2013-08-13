<?php

namespace Ron\ConsumptionBundle\Model;

use Ron\ConsumptionBundle\Entity\Consumption;

/**
 * Class ConsumptionCalculation
 * @package Ron\ConsumptionBundle\Model
 */
class ConsumptionCalculation
{

    /**
     * @param $colConsumption
     * @return array
     */
    public function getConsumptionMonthly($colConsumption)
    {
        # var_dump($colConsumption[0]->getCreateDate());
        $startMonth = $this->getFirstDayInMonth($colConsumption[0]->getCreateDate());

        # echo $this->countMonth($colConsumption);
        $consumptionPeriod = $this->initKeys($startMonth, $this->countMonth($colConsumption));
        $allData = $this->calculateConsumptionPerDay($colConsumption);

        #        var_dump($allData);
        $tempMonth = null;
        $totalData = array();
        foreach ($allData as $date => $data) {
            $currentDate = new \DateTime(($date));
            $currentDate = $currentDate->format('m.Y');
            if ($tempMonth != $currentDate) {
                $tempMonth = $currentDate;
            }
            if (!isset($totalData[$tempMonth])) {

                $totalData[$tempMonth]['date'] = $tempMonth;
                $totalData[$tempMonth]['energy'] = $data['energy'];
                $totalData[$tempMonth]['water'] = $data['water'];
                $totalData[$tempMonth]['gas'] = $data['gas'];
            } else {
                $totalData[$tempMonth]['energy'] += $data['energy'];
                $totalData[$tempMonth]['water'] += $data['water'];
                $totalData[$tempMonth]['gas'] += $data['gas'];
            }


            #if($date == $month) {

            #}
            #   var_dump($month);
        }
        return $totalData;
    }

    /**
     * @param \DateTime $startMonth
     * @param $monthCount
     * @return array
     */
    protected function initKeys(\DateTime $startMonth, $monthCount)
    {
        $period = array();

        $startMonth = clone $startMonth;
        while ($monthCount > 0) {
            $period[$startMonth->modify('+1 month')->format('m.Y')] = null;
            --$monthCount;
        }

        return $period;
    }

    /**
     * @param $col
     * @return array
     */
    protected function getAverageByCollection($col)
    {

        $consumptionPeriods = array();
        for ($i = 0; $i < count($col); $i++) {
            if (!isset($col[$i + 1])) {
                break;
            }
            $consumptionPeriods[] = $this->getAverage($col[$i], $col[$i + 1]);
        }
#var_dump($consumptionPeriods);
        return $consumptionPeriods;
    }

    /**
     * @param Consumption $consumptionStart
     * @param Consumption $consumptionEnd
     * @return mixed
     * @throws \Exception
     */
    protected function getAverage(Consumption $consumptionStart, Consumption $consumptionEnd)
    {
        $dtStart = $consumptionStart->getCreateDate();
        $dtEnd = $consumptionEnd->getCreateDate();

        $interval = $dtStart->diff($dtEnd);
        $days = $interval->days;
#var_dump($dtStart->format('d.m.Y'));
#var_dump($dtEnd->format('d.m.Y'));
#var_dump($days);
#var_dump($interval);
        if ($days < 0) {
            throw new \Exception('days between timestamps to small.');
        }

        $consumptionPeriod = new ConsumptionPeriod();
        $consumptionPeriod->setStartDatetime($dtStart);
        $consumptionPeriod->setEndDatetime($dtEnd);

        foreach ($consumptionPeriod::getTypes() as $type) {
            $functionName = 'get' . ucfirst($type);
            $value = round(($consumptionEnd->$functionName() - $consumptionStart->$functionName()) / $days, 2);
            $consumptionPeriod->setConsumption($type, $value);
        }

        return $consumptionPeriod;
    }

    /**
     * @param $colConsumption
     * @return mixed
     */
    protected function countMonth($colConsumption)
    {

        $now = new \DateTime();
        /**
         * @var $consumption Consumption
         */
        $consumption = clone($colConsumption[0]);
        $diff = $consumption->getCreatedate()->diff($now);
        $countMonth = $diff->y * 12 + $diff->m;

        return $countMonth;
    }

    /**
     * @param \DateTime $datetime
     * @return \DateTime
     */
    protected function getFirstDayInMonth(\DateTime $datetime)
    {
        #var_dump($datetime->modify('first day of ' . $datetime->format('F') . ' ' . $datetime->format('Y')));
        return $datetime->modify('first day of ' . $datetime->format('F') . ' ' . $datetime->format('Y'));
    }

    /**
     * @param $colConsumption
     * @return array
     */
    protected function calculateConsumptionPerDay($colConsumption)
    {
        $totaldata = array();

        /**
         * @var $consumptionPeriod ConsumptionPeriod
         */
        foreach ($this->getAverageByCollection($colConsumption) as $consumptionPeriod) {
            $interval = new \DateInterval("P1D");
            $period = new \DatePeriod($consumptionPeriod->getStartDatetime(), $interval, $consumptionPeriod->getEndDatetime());
            foreach ($period as $dt) {
                $data = array(
                    'gas' => $consumptionPeriod->getGas(),
                    'energy' => $consumptionPeriod->getEnergy(),
                    'water' => $consumptionPeriod->getWater(),
                );

                $totaldata[$dt->format("d.m.Y")] = $data;
            }
        }

        return $totaldata;
    }

}