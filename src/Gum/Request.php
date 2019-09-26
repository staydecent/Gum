<?php
/**
 * Request
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
 * Request
 *
 * @category Request
 * @package  Gum
 * @author   Adrian Unger <dev@staydecent.ca>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @version  0.3.0
 * @link     http://staydecent.ca
 */
class Request
{
    /**
     * Decode the incoming request body from JSON.
     *
     * @return json
     */
    public static function json()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
