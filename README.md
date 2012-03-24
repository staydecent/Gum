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

Gum\Route::get('/', function() {
    echo "Home";
});

Gum\Route::post('thing', function() {
    // if '/thing' is accessed through any other request method
    // other than 'post', this callback will never be called.
    echo "Thing";
});

// handle 404
if (Gum\Route::not_found())
{
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    exit;
}
?>
```

## Where's the Gumblog UI?

If you're looking for the Gumblog UI, see [this repo](https://github.com/staydecent/Gumblog-UI).