<?php

use DaveComputerGeek\Template;

include_once '../private/WebsiteEngine.php';

$engine = new DaveComputerGeek\WebsiteEngine();

$routes = (array) $engine->getConfig()->getKey("routes");

// Check if the configured routes is an array.
if(is_array($routes)) {
    // Loop through all the routes in the configuration.
    foreach ($routes as $route => $route_info) {
        // Check if the route info is an array and that it contains at least the URI and the template.
        if(is_array($route_info) && array_key_exists("uri", $route_info) && array_key_exists("template", $route_info)) {
            // Match the route URI to the current request URI being accessed.
            if($route_info['uri'] == $_SERVER['REQUEST_URI']) {
                // Check if the route template variable has a colon to split the template name from the template file.
                if(strpos($route_info['template'], ":")) {
                    $template_variable = explode(":", $route_info['template']);
                    $template_name = $template_variable[0];
                    $template_file = $template_variable[1];
                    
                    $template = Template::register($template_name);
                    if($template instanceof Template)
                        echo $template->getFile($template_file);
                    else
                        echo $template_name . " / " . $template_file;
                    
                    exit;
                }
            }
        }
    }
}

header("Content-Type: text/plain");
echo "Sorry, the content you are looking for cannot be found. Please check for errors or try again later.";