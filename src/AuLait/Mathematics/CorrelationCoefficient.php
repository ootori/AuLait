<?php
namespace AuLait\Mathematics;

class CorrelationCoefficient
{

    /**
     * @param array $vectorX
     * @param array $vectorY
     * @return bool|float
     */
    function execute($vectorX, $vectorY)
    {
        $length = count($vectorX);
        if ($length != count($vectorY)) {
            return false;
        }

        $aveX = $this->ave($vectorX);
        $aveY = $this->ave($vectorY);

        $numerator = 0;
        for ($i = 0; $i < $length; $i++) {
            $numerator += ($vectorX[$i] - $aveX) * ($vectorY[$i] - $aveY);
        }

        $denominatorX = 0;
        foreach ($vectorX as $value) {
            $denominatorX += ($value - $aveX) * ($value - $aveX);
        }
        $denominatorX = sqrt($denominatorX);


        $denominatorY = 0;
        foreach ($vectorY as $value) {
            $denominatorY += ($value - $aveY) * ($value - $aveY);
        }
        $denominatorY = sqrt($denominatorY);
        $denominator = $denominatorX * $denominatorY;
        if ($denominator == 0.0) {
            return false;
        }

        return $numerator / ($denominatorX * $denominatorY);
    }

    function ave($x)
    {
        return array_sum($x) / count($x);
    }
}

