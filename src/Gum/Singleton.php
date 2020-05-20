<?php
/**
 * Singleton
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
 * Singleton
 *
 * @category Singleton
 * @package  Gum
 * @author   Adrian Unger <dev@staydecent.ca>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @version  0.3.0
 * @link     http://staydecent.ca
 */
trait Singleton
{
    private static $_instance;

    /**
     * Get the instance.
     *
     * @return Object
     */
    public static function getInstance()
    {
        if (! isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
}
