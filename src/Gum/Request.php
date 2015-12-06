<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

/**
 * Request shorthands
 *
 * @package Gum
 */
class Request {

  public static function json() {
    return json_decode(file_get_contents('php://input'), true);
  }
}
