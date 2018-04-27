<?php

namespace DaveComputerGeek;

class Template {
    
    private $dirname;
    private $config;
    private $tags = [], $ftags = [];
    
    private static $TEMPLATES = [];
    private static $REQUIRED_FILES = ["index.tpl"];
    private static $TEMPLATES_DIR_PATH = "../private/templates";
    
    public function __construct(String $dirname) {
        $this->dirname = $dirname;
        
        // Does the template have a configuration file? Parse the YAML into an array.
        if(file_exists($this->getDirPath() . "/config.yml")) { $this->config = yaml_parse_file($this->getDirPath() . "/config.yml"); }
        // Does our configuration have any tags defined? Loop through and register them.
        if(is_array($this->config) && array_key_exists("tags", $this->config)) {
            foreach ($this->config['tags'] as $tag => $value) {
                $this->registerTag($tag, $value);
            }
        }
        
        // Define function tags.
        $this->ftags['year'] = date("Y");
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
        
        $contents = "";
        
        // Check if template file exists and fetch its contents.
        if(file_exists($this->getDirPath() . "/" . $filename . ".tpl")) {
            $contents = file_get_contents($this->getDirPath() . "/" . $filename . ".tpl");
        }
        
        // Process all template tags {{ template_tag }}
        foreach ($this->tags as $id => $content) {
            if(is_string($content)) {
                $contents = str_replace("{{ " . $id . " }}", $content, $contents);
            } else if(is_array($content)) {
                $list = "<ul>";
                foreach ($content as $item) {
                    $list .= "<li>" . $item . "</li>";
                }
                $list .= "</ul>";
                $contents = str_replace("{{ " . $id . " }}", $list, $contents);
            }
        }
        
        // Process all function tags {% function_tag %}
        foreach ($this->ftags as $id => $content) {
            $contents = str_replace("{% " . $id . " %}", $content, $contents);
        }
        
        // Process include function tags (filename is relative to template's directory and is without extension) {% include="filename" %}
        if(preg_match_all("/{% include=\"(.*)\" %}/", $contents, $matches, PREG_PATTERN_ORDER)) {
            for ($m = 0; $m < count($matches[0]); $m++) {
                if(file_exists($this->getDirPath() . "/" . $matches[1][$m] . ".tpl")) {
                    $contents = str_replace($matches[0][$m], file_get_contents($this->getDirPath() . "/" . $matches[1][$m] . ".tpl"), $contents);
                }
            }
        }
        
        // Template file does not exist, return empty string.
        return $contents;
    }
    
    /**
     * Register a template tag for use within the template files.
     * @param String $id
     * @param String|array $content
     */
    public function registerTag(String $id, $content) {
        if(is_string($content) || is_array($content))
            $this->tags[$id] = $content;
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