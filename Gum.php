<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */

namespace Gum;

class InvalidRequestMethod extends \Exception {}
class InvalidRequestRoute extends \Exception {}

class Stick {

    private static $instance;

    private $rules = array();
    private $route;

    public $is_matched = FALSE;

    public static function delegate()
    {
        $instance = self::get_instance();

        if ($instance->is_matched)
        {
            return TRUE;
        }

        foreach ($instance->rules as $rule => $callback) 
        {
            $rule = str_replace('/', '\/', $rule);
            $rule = '^' . $rule . '\/?$';

            if (preg_match("/{$rule}/i", $instance->route, $matches)) 
            {
                $instance->is_matched = TRUE;
                $callback();
            }
        }  
    }

    public static function __callStatic($name, $args)
    {
        if ($name === strtolower($_SERVER['REQUEST_METHOD']))
        {
            $instance = self::get_instance();
            $instance->route = isset($_GET['r']) ? trim($_GET['r'], '/\\') : '/';
            $instance->rules[$args[0]] = $args[1];

            self::delegate();
        }
    }

    public static function not_found()
    {
        $instance = self::get_instance();
        return ($instance->is_matched) ? FALSE : TRUE;
    }

    public static function get_instance() 
    {
        if ( ! isset(self::$instance)) 
        {
            self::$instance = new Stick();
        }

        return self::$instance;
    }
}