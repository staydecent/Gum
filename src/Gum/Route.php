<?php

/**
 * Gum is fun!
 *
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

/**
 * HTTP Router
 *
 * @package Gum
 */
class Route {

  use Singleton;

  private $rules = array(),
          $route;

  public $is_matched = FALSE;

  /**
   * Matches the current route to a rule, invoking the callback.
   *
   * @return void
   */
  public static function delegate() {
    $instance = self::get_instance();

    foreach ($instance->rules as $rule => $callback) {
      $param_keys = [];

      // Handle named params
      if (stristr($rule, ':') !== FALSE) {
        $re_pat = '([a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)?';

        if (preg_match_all("/:{$re_pat}/i", $rule, $matches)) {
          $named_params = $matches[1];
        }

        foreach ($named_params as $param) {
          // replace name in rule with regex
          $rule = str_replace(':'.$param, $re_pat, $rule);
        }
      }

      // Escape slashes, and ensure optional trailing slash
      $rule = str_replace('/', '\/', $rule);
      if (substr($rule, -1) === '/') {
        $rule = '^' . $rule . '?$';
      } else {
        $rule = '^' . $rule . '\/?$';
      }

      if (preg_match("/{$rule}/i", $instance->route, $matches)) {
        $instance->is_matched = TRUE;
        unset($matches[0]);

        Event::fire('before_callback', $callback);

        // Pass args as individual params
        if ( ! empty($named_params) && is_array($matches)) {
          // ensure equal number of elements
          $named_params = array_slice($named_params, 0, count($matches));
          $params = array_combine($named_params, $matches);

          call_user_func_array($callback, array_values($params));
        }
        // Pass args in array
        else {
          $callback($matches);
        }

        Event::fire('after_callback', $callback);
      }
    }
  }

  /**
   * is triggered when invoking inaccessible methods in a static context.
   *
   * @param  string $name name of the request method
   * @param  array  $args
   * @return bool
   */
  public static function __callStatic($name, $args) {
    $instance = self::get_instance();

    if ($instance->is_matched) {
      return FALSE;
    }

    if ($name === 'request' || $name === strtolower($_SERVER['REQUEST_METHOD'])) {
      // support for built-in php web server
      if (php_sapi_name() === 'cli-server') {
        $_GET['r'] = $_SERVER["REQUEST_URI"];
      }

      $route = isset($_GET['r']) ? trim($_GET['r']) : '/';

      // remove query params from our $route string
      if (strstr($route, '?') !== FALSE) {
        $parts = explode('?', $route);
        $route = $parts[0];
      }

      $instance->route = $route;
      $instance->rules[$args[0]] = $args[1];

      self::delegate();

      return TRUE;
    }
  }

  /**
   * See if we've got a match.
   *
   * @return bool
   */
  public static function not_found() {
    $instance = self::get_instance();
    return ($instance->is_matched) ? FALSE : TRUE;
  }
}
