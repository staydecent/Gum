<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

// TODO: Use this trait when you upgrade to PHP 5.4
// trait Singleton {
    
//     private static $instance;

//     private function __construct() {}

//     public function __clone() {}
//     public function __wakeup() {}

//     /**
//      * Get the instance.
//      *
//      * @return Object
//      */
//     public static function get_instance() 
//     {
//         if ( ! isset(self::$instance)) 
//         {
//             self::$instance = new self;
//         }

//         return self::$instance;
//     }
// }

/**
 * Provided by Danillo César de O. Melo
 * https://github.com/danillos/fire_event/blob/master/Event.php
 */
class Event {
  
    /**
     * Hooks are pairs of events => functions to call.
     *
     * @var array
     */
    private $hooks = array();

    private static $instance;

    private function __construct() {}

    public function __clone() {}
    public function __wakeup() {}

    /**
     * Get the instance.
     *
     * @return Object
     */
    public static function get_instance() 
    {
        if ( ! isset(self::$instance)) 
        {
            self::$instance = new self;
        }

        return self::$instance;
    }
  
    /**
     * Add a function to an event.
     *
     * @param  string $event_name the name of the event to hook into
     * @param  string $fn the function to call
     * @return void
     */
    public static function hook($event_name, $fn) 
    {
        $instance = self::get_instance();
        $instance->hooks[$event_name][] = $fn;
    }
  
    /**
     * Trigger/fire/invoke an event, calling all hooked functions.
     *
     * @param  string $event_name the name of the event to invoke
     * @param  mixed  $params Parameters to pass to the hooked functions
     * @return void
     */
    public static function fire($event_name, $params = NULL) 
    {
        $instance = self::get_instance();

        if (array_key_exists($event_name, $instance->hooks)) 
        {
            foreach ($instance->hooks[$event_name] as $fn) 
            {
                if (is_array($fn) || is_string($fn))
                {
                    call_user_func_array($fn, array(&$params));
                }
            }
        }
    }
}

/**
 * HTTP Router
 *
 * @package Gum
 */
class Route {

    private $rules = array(),
            $route;

    public $is_matched = FALSE;

    private static $instance;

    private function __construct() {}

    public function __clone() {}
    public function __wakeup() {}

    /**
     * Get the instance.
     *
     * @return Object
     */
    public static function get_instance() 
    {
        if ( ! isset(self::$instance)) 
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Matches the current route to a rule, invoking the callback.
     *
     * @return void
     */
    public static function delegate()
    {
        $instance = self::get_instance();

        foreach ($instance->rules as $rule => $callback) 
        {
            $rule = str_replace('/', '\/', $rule);
            $rule = '^' . $rule . '\/?$';

            if (preg_match("/{$rule}/i", $instance->route, $matches)) 
            {
                $instance->is_matched = TRUE;
                Event::fire('before_callback', $callback);
                $callback();
                Event::fire('after_callback', $callback);
            }
        }  
    }

    /**
     * is triggered when invoking inaccessible methods in a static context.
     *
     * @param  string $name name of the request method
     * @param  array  $args
     * @return void
     */
    public static function __callStatic($name, $args)
    {
        $instance = self::get_instance();

        if ($instance->is_matched)
        {
            return TRUE;
        }
        
        if ($name === strtolower($_SERVER['REQUEST_METHOD']))
        {
            $instance->route = isset($_GET['r']) ? trim($_GET['r'], '/\\') : '/';
            $instance->rules[$args[0]] = $args[1];

            self::delegate();
        }
    }

    /**
     * See if we've got a match.
     *
     * @return bool
     */
    public static function not_found()
    {
        $instance = self::get_instance();
        return ($instance->is_matched) ? FALSE : TRUE;
    }
}