<?php

namespace DaveComputerGeek;

require_once 'Config.php';

class WebsiteEngine {
    
    private $config;
    
    public function __construct() {
        $this->config = new Config();
    }
    
    public function getConfig() { return $this->config; }
    
}