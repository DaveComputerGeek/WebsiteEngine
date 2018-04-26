<?php

namespace DaveComputerGeek;

class Config {
    
    private $config = [];
    
    public function __construct() {
        // Check if configuration file exists.
        if(file_exists("../private/Config.yml")) {
            // Load configuration file.
            $this->config = yaml_parse_file("../private/Config.yml");
        }
    }
    
    /**
     * Returns the value associated with the provided key if it exists or an empty string if not.
     * @param String $key
     * @return mixed
     */
    public function getKey(String $key) {
        if(is_array($this->config) && array_key_exists($key, $this->config))
            return $this->config[$key];
        return "";
    }
    
}