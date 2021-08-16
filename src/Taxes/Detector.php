<?php

namespace App\Taxes;

class Detector
{
    public function detect(float $prix): bool
    {
        if ($prix >= 100) {
            return true;
        }
        return false;
    }
}
