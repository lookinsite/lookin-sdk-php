# Lookin SDK for PHP

Lookin is a simple-site-search engine.  
This is sdk for PHP.

# Requirements

PHP5.6+

# Installation

```bash
composer require lookin/sdk
```

# Usage

```PHP
require "/path/to/vendor/autoload.php";

use Lookin\Cient;
use Lookin\Request\ApiSearchRequest;

// initiate client
$client = new Client("sk_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

// build request
$req = new ApiSearchRequest([
  "q" => "sample keyword",
  "device" => "desktop", // or mobile
  "size" => 40, // optional
  "page" => 1, // optional
]);

// send search request
$res = $client->search($req);

// get response
$res->getRecords();
```

# License

MIT License

# About

Please visit https://lookin.site/
