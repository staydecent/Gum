# Introduction

### Gum is a base for rapid prototyping with PHP, providing an HTTP Router and Events system. Paired with a few other PHP Libraries, Gum let's you *prototype your application, rapidly!* Whoa.

## Ignoring Best Practices

This ain't about purity, or convention or anything other than rapid-*ness*. So, you may not like the Singleton pattern or some other stuff&mdash;feel free to make an issue. But, just remember, rapid-*ness* takes priority.

## Requires PHP 5.4 or higher

PHP 5.4 is now production ready. Does this mean Gum is *ready for production?!* **No.**

# Hello&hellip;Universe?

```php
<?php
require 'path_to/Gum.php';
use Gum\Route as Route;
use Gum\Request as Req;
use Gum\Response as Resp;

// The actual URL does not need a trailing slash
Route::get('/', function() {
  echo "Home";
});

// Named route parameters
Route::get('archive/:year/:month/:day', function($year = NULL, $month = NULL, $day = NULL) {
  var_dump($year, $month, $day);
});

// Regex routes, params are passed in an array
Route::get('post/([\d]+)/([a-zA-Z0-9_]+)?', function($args) {
  // if just 'post/' is accessed, $args will be empty array
  var_dump($args);
});

// if '/thing' is accessed through any other request method
// other than 'post', this callback will never be called.
Route::post('thing', function() {
  echo "Thing";
});

// There's also simple helper methods for working with json
Route::post('/json', function() {
  // If you have an ajax form post json to '/json'
  $data = Req::json();
  // You can respond with json easily as well
  echo Res::json(array('success'=>true));
});

// If you need a route to be accessible to both GET and POST
// requests, use `request`. The callback will be fired in
// both cases.
Route::request('foobar', function() {
  echo "Foobar!";
});

// handle 404
if (Route::not_found()) {
  header('HTTP/1.0 404 Not Found');
  echo '404 Not Found';
  exit;
}
?>
```

## Docs/Guides

* [Using Gum with Composer](https://github.com/staydecent/Gum/wiki/Using-Gum-with-Composer)
