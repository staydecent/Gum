<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

/**
 * Provided by Danillo César de O. Melo
 * https://github.com/danillos/fire_event/blob/master/Event.php
 */
class Event {

  use Singleton;

  /**
   * Hooks are pairs of events => functions to call.
   *
   * @var array
   */
  private $hooks = array();

  /**
   * Add a function to an event.
   *
   * @param  string $event_name the name of the event to hook into
   * @param  string $fn the function to call
   * @return void
   */
  public static function hook($event_name, $fn) {
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
  public static function fire($event_name, $params = NULL) {
    $instance = self::get_instance();

    if (array_key_exists($event_name, $instance->hooks)) {
      foreach ($instance->hooks[$event_name] as $fn) {
        if (is_array($fn) || is_string($fn)) { 
          call_user_func_array($fn, array(&$params));
        }
      }
    }
  }
}
