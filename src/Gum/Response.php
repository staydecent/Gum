<?php
/**
 * Response
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
 * Response
 *
 * @category Response
 * @package  Gum
 * @author   Adrian Unger <dev@staydecent.ca>
 * @license  http://opensource.org/licenses/mit-license.php MIT License
 * @version  0.3.0
 * @link     http://staydecent.ca
 */
class Response
{
    /**
     * Convert an array to JSON to respond with.
     *
     * @param array $data array to convert ot JSON
     *
     * @return json
     */
    public static function json($data = array())
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    /**
     * Render a template file, injecting data into it.
     *
     * @param string $file filename of template to render
     * @param array  $vars data to inject into the template
     *
     * @return html
     */
    public static function renderTemplate($file, $vars = array())
    {
        extract($vars);
        ob_start();
        include $file;
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }

    /**
     * Render a template file, including layout partials if not a PJAX request.
     *
     * @param string $file filename of template to render
     * @param array  $vars data to inject into the template
     *
     * @return html
     */
    public static function render($file, $vars = array())
    {
        $file = $file . '.html';
        $vars['layout'] = function ($name) {
            $isPJAX = !is_null($_SERVER['HTTP_X_PJAX']);
            if (!$isPJAX) {
                include 'templates/layout/' . $name . '.html';
            }
        };
        return Response::renderTemplate($file, $vars);
    }
}
