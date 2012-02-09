<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */

namespace Gum;

class InvalidRequestMethod extends Exception {}

/**
 * Stick functions to URLs.
 */
class Stick {

    public static function map($rules)
    {
        $route = isset($_GET['r']) ? trim($_GET['r'], '/\\') : '/';

        foreach ($rules as $rule => $callback) 
        {
            $rule = str_replace('/', '\/', $rule);
            $rule = '^' . $rule . '\/?$';

            if (preg_match("/$rule/i", $route, $matches)) 
            {
                return $callback;        
            }
        }  
    }

    public static function __callStatic($name, $arguments)
    {
        if ($name === strtolower($_SERVER['REQUEST_METHOD']))
        {
            Self::map($rules);
        }
        else
        {
            throw new InvalidRequestMethod("Error Processing Request", 1);
        }
    }
}