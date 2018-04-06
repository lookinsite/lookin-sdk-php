# Lookin SDK for PHP

Lookin is a simple-site-search engine.  
This is sdk for PHP.

[![CircleCI](https://circleci.com/gh/tomohiroukawa/lookin-sdk-php.svg?style=svg)](https://circleci.com/gh/tomohiroukawa/lookin-sdk-php)

# Requirements

PHP5.6+

# Installation

```bash
composer require lookin/lookin-sdk-php
```

# Usage

```PHP
<?php

require './vendor/autoload.php';

ini_set("display_errors", "On");

use Lookin\Client;
use Lookin\Request\ApiSearchRequest;

try {

    // initiate client
    $client = new Client("sk_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

    // build request
    $req = new ApiSearchRequest([
        "q" => "keyword",
        "device" => "desktop", // or mobile
        "size" => 40, // optional
        "page" => 1, // optional
    ]);

    // send search request
    $res = $client->search($req);

    $str = 'Page %d of %d, showing %d records out of %d total, starting on record %d, ending on %d<br>';
    echo sprintf($str, $res->current_page, $res->total_pages, $res->size, $res->total, $res->start, $res->end);

    foreach ($res->getIterator() as $hit) {
        echo $hit->title . "<br>";
        echo $hit->url . "<br>";
        echo $hit->score . "<br>";
        echo $hit->content;
        echo '<hr>';
    }
    
} catch (\Exception $ex) {

    echo $ex->getMessage();
}

```

# License

MIT License

# About

Please visit https://lookin.site/
