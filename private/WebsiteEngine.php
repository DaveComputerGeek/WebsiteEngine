<?php

namespace DaveComputerGeek;

require_once 'Config.php';
require_once 'Template.php';

class WebsiteEngine {
    
    private $config;
    
    public function __construct() {
        $this->config = new Config();
    }
    
    public function getConfig() { return $this->config; }
    
}