<?php

namespace App\Services\Injections;

class  Payment 
{
    public function calculatePendingPayment($total_earn_with_out_this_month,$total_paids)
    {   
        return ($total_earn_with_out_this_month-$total_paids);
    }
}