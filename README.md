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

You need to create an `OSN\Envoy\Envoy` object first. The `Envoy` constructor accepts parameter 1 as the env file path, and the second argument is the configuration array.
Both of these are optional but note that argument 1 defaults to `./.env`.

Then you need to call the `load()` method on the instance to load all the configuration from the env file to super global `$_ENV`.

If there was an error while parsing the file, Envoy will throw `OSN\Envoy\EntityParseErrorException`.

```php
<?php
  require __DIR__ . "/vendor/autoload.php";
  
  use OSN\Envoy\Envoy;
  
  $envoy = new Envoy();
  
  try {
    $envoy->load();
    print_r($_ENV);
  }
  catch(\OSN\Envoy\EntityParseErrorException $e){
    echo "Error while parsing the file: " . $e->getMessage();
  }
```

If the `.env` file is this:

```
DSN=mysql:host=localhost;port=3306;dbname=mydatabase
DB_USER=root
DB_PASSWORD= 
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
