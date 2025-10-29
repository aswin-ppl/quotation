<?php

if (!function_exists('indian_currency')) {
    function indian_currency($amount)
    {
        $amount = (float) $amount;
        $isNegative = $amount < 0;
        $amount = abs($amount);
        
        $amountParts = explode('.', number_format($amount, 2, '.', ''));
        $intPart = $amountParts[0];
        $decPart = $amountParts[1] ?? '00';
        
        // Indian format: last 3 digits, then groups of 2
        $lastThree = substr($intPart, -3);
        $remaining = substr($intPart, 0, -3);
        
        if ($remaining != '') {
            $lastThree = ',' . $lastThree;
        }
        
        $formatted = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining) . $lastThree;
        
        return ($isNegative ? '-' : '') . '₹' . $formatted . '.' . $decPart;
    }
}