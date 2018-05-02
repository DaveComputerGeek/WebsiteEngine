<?php

namespace DaveComputerGeek;

require_once 'Config.php';
require_once 'Database.php';
require_once 'Template.php';

class WebsiteEngine {
    
    private $config;
    
    private $DB = null;
    
    /**
     * Initialises our Website Engine.
     */
    public function __construct() {
        // Create instance of Config class.
        $this->config = new Config();
        
        // Fetch the database configuration.
        $configDB = $this->config->getKey("database");
        
        // Check that the database configuration has a "host", a "user", a "pass", and a "name", then create instance of Database class and establish a connection to the database.
        if (array_key_exists("host", $configDB) && array_key_exists("user", $configDB) && array_key_exists("pass", $configDB) && array_key_exists("name", $configDB))
            $this->DB = new Database($configDB['host'], $configDB['user'], $configDB['pass'], $configDB['name']);
    }
    
    /**
     * Returns an instance of Config class.
     * @return \DaveComputerGeek\Config
     */
    public function getConfig() { return $this->config; }
    /**
     * Returns an instance of Database class.
     * @return \DaveComputerGeek\Database
     */
    public function getDB() { return $this->DB; }
    
}