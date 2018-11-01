<?php

namespace App\Helpers;
use GuzzleHttp;

class MathHelper
{
	/**
     * Calculates term popularity based on formula: score = ( positive / ((positive + negative) * 10) )
     *
     * @param $positive - total positive count of term
     * @param $negative - total negative count of term
     * @return $score
     */
    public static function calculatePopularity($positive, $negative) 
    {
    	// Check for division by ZERO
        if( $negative == 0 )
        {
            $score = 'Infinite';
        }
        else
        {
            $score = ( $positive / ( $positive + $negative) ) * 10;
        }

        return $score;
    }
}