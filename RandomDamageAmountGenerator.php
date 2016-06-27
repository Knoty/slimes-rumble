<?php
require_once ('./DamageAmountGenerator.php');

class RandomDamageAmountGenerator implements DamageAmountGenerator
{
    public function getDamageAmount()
    {
        return mt_rand(1, 11);
    }
}