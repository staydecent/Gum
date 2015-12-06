<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

/**
 * Response shorthands
 *
 * @package Gum
 */
class Response {

  public static function json($data = array()) {
    header('Content-Type: application/json');
    return json_encode($data);
  }

  public static function render($file, $vars = array()) {
    extract($vars);
    ob_start();
    include $file;
    $out = ob_get_contents();
    ob_end_clean();
    return $out; 
  }
}
