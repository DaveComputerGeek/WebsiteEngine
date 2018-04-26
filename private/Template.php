<?php

namespace DaveComputerGeek;

class Template {
    
    private $dirname;
    
    private static $TEMPLATES = [];
    private static $REQUIRED_FILES = ["index.tpl"];
    private static $TEMPLATES_DIR_PATH = "../private/templates";
    
    public function __construct(String $dirname) {
        $this->dirname = $dirname;
    }
    
    /**
     * Registers a template and returns its registered instance.
     * @param String $dirname
     * @return Template|boolean
     */
    public static function register(String $dirname) {
        // Ensure all required files exist.
        /*foreach (Template::$REQUIRED_FILES as $required_file) {
            // Stop here if a required file does not exist.
            if(!file_exists("../private/templates/" . $dirname . "/" . $required_file))
                return false;
        }*/
        
        // Check if template directory does not exist.
        if(!is_dir("../private/templates/" . $dirname)) return;
        
        // Register the template.
        Template::$TEMPLATES[$dirname] = new Template($dirname);
        
        // Return the registered instance of Template.
        return Template::$TEMPLATES[$dirname];
    }
    
    /**
     * Return the registered instance of Template based on its directory name or false if not registered.
     * @param String $dirname
     * @return Template|boolean
     */
    public static function get(String $dirname) {
        // Check if template is registered.
        if(array_key_exists($dirname, Template::$TEMPLATES))
            // Return Template Instance.
            return Template::$TEMPLATES[$dirname];
        // Return false.
        return false;
    }
    
    /**
     * Returns the contents of the requested template file if it exists or an empty string if not.
     * Only supports .tpl files.
     * @param String $filename
     * @return String
     */
    public function getFile(String $filename) {
        // Check if requested filename contains the .tpl extension and ommit it.
        if(substr($filename, -4) == ".tpl")
            $filename = substr($filename, 0, -4);
        
        // Check if template file exists and return its contents.
        if(file_exists($this->getDirPath() . "/" . $filename . ".tpl")) {
            return file_get_contents($this->getDirPath() . "/" . $filename . ".tpl");
        }
        
        // Template file does not exist, return empty string.
        return "";
    }
    
    /**
     * Returns the path to the directory containing all the templates without trailing slash.
     * @return String
     */
    public static function getTemplatesDirPath() { return Template::$TEMPLATES_DIR_PATH; }
    
    /**
     * Returns the name of the directory the template is stored in.
     * @return String
     */
    public function getDirName() { return $this->dirname; }
    
    /**
     * Returns the path to the template's directory.
     * @return String
     */
    public function getDirPath() { return Template::$TEMPLATES_DIR_PATH . "/" . $this->dirname; }
    
}