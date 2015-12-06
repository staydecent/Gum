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
}
