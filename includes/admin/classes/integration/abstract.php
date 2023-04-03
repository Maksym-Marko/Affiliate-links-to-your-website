<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

abstract class MXALFWPIntegration
{
    
    abstract public function addPurchase();

    abstract public function cancelPurchase();


}