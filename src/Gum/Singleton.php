<?php 

/**
 * Gum is fun!
 * 
 * By Adrian Unger <http://staydecent.ca>
 * Public Domain or something.
 */
namespace Gum;

/**
 * All (both) Gum classes use the Singleton trait.
 */
trait Singleton {

  private static $instance;

  private function __construct() {}

  public function __clone() {}
  public function __wakeup() {}

  /**
   * Get the instance.
   *
   * @return Object
   */
  public static function get_instance() {
    if (! isset(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }
}
