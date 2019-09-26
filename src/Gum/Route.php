<?php
/**
 * Route
 *
 * @category Class
 * @package  Gum
 * @author   Adrian Unger <dev@staydecent.ca>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @version  0.3.0
 * @link     http://staydecent.ca
 */

namespace Gum;

/**
 * Route
 *
 * @category Route
 * @package  Gum
 * @author   Adrian Unger <dev@staydecent.ca>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @version  0.3.0
 * @link     http://staydecent.ca
 */
class Route
{
    use Singleton;

    private $_rules = array();
    private $_route;

    public $is_matched = false;

    /**
     * Matches the current route to a rule, invoking the callback.
     *
     * @return void
     */
    public static function delegate()
    {
        $instance = self::getInstance();

        foreach ($instance->_rules as $rule => $callback) {
            $param_keys = [];

            // Handle named params
            if (stristr($rule, ':') !== false) {
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

            if (preg_match("/{$rule}/i", $instance->_route, $matches)) {
                $instance->is_matched = true;

                unset($matches[0]);

                Event::fire('before_callback', ['route' => $instance->_route, 'matches' => $matches]);

                // Pass args as individual params or as an array
                if (! empty($named_params) && is_array($matches)) {
                    // ensure equal number of elements
                    $named_params = array_slice($named_params, 0, count($matches));
                    $params = array_combine($named_params, $matches);

                    call_user_func_array($callback, array_values($params));
                } else {
                    $callback($matches);
                }

                Event::fire('after_callback', $callback);
            }
        }
    }

    /**
     * Triggered when invoking inaccessible methods in a static context.
     *
     * @param string $name name of the request method
     * @param array  $args passed to handler
     *
     * @return bool
     */
    public static function __callStatic($name, $args)
    {
        $instance = self::getInstance();

        if ($instance->is_matched) {
            return false;
        }

        if ($name === 'request' || $name === strtolower($_SERVER['REQUEST_METHOD'])) {
            // support for built-in php web server
            if (php_sapi_name() === 'cli-server') {
                $_GET['r'] = $_SERVER["REQUEST_URI"];
            }

            $route = isset($_GET['r']) ? trim($_GET['r']) : '/';

            // remove query params from our $route string
            if (strstr($route, '?') !== false) {
                $parts = explode('?', $route);
                $route = $parts[0];
            }

            $instance->_route = $route;
            $instance->_rules[$args[0]] = $args[1];

            self::delegate();

            return true;
        }
    }

    /**
     * See if we've got a match.
     *
     * @return bool
     */
    public static function notFound()
    {
        $instance = self::getInstance();
        return ($instance->is_matched) ? false : true;
    }
}
