# Envoy
A simple environment configuration loader.

## Installation
You can install envoy using composer:

```
$ composer require osn/envoy
```

## Usage
Once you've installed envoy, you can include the composer-generated autoloader into your project and start writing code:

```php
<?php
  require __DIR__ . "/vendor/autoload.php";
  
  // ...
```

Okay, now you're ready to go!
Let's see how to invoke envoy.


```php
<?php
  require __DIR__ . "/vendor/autoload.php";
  
  use OSN\Envoy\ParseENV;
  
  $parser = new ParseENV();
  
  try {
    $env = $parser->parseFile(".env"); // path to you env file...
    print_r($env);
  }
  catch(OSN\Envoy\Exception $e){
    echo "Error while parsing the file: " . $e->getMessage();
  }
```

If our `.env` file is this:

```
DSN = mysql:host=localhost;port=3306;dbname=mydatabase
DB_USER = root
DB_PASSWORD = 
```

Then the above code should print:

```
Array (
  [DSN] => mysql:host=localhost;port=3306;dbname=mydatabase
  [DB_USER] => root
  [DB_PASSWORD] =>
)
```

## Support
Please contact us at [bug-envoy@onesoftnet.ml](mailto:bug-envoy@onesoftnet.ml).
