# Lookin SDK for PHP

Lookin is a simple-site-search engine.  
This is sdk for PHP.

[![CircleCI](https://circleci.com/gh/tomohiroukawa/lookin-sdk-php/tree/master.svg?style=svg)](https://circleci.com/gh/tomohiroukawa/lookin-sdk-php/tree/master)

# Requirements

PHP5.6+

# Installation

```bash
composer require lookinsite/lookin-sdk-php
```

# Usage

```PHP
<?php

require './vendor/autoload.php';

ini_set("display_errors", "On");

use Lookin\Services\Search\SearchClient;
use Lookin\Services\Search\SearchRequest;

try {

    // initiate client
    $client = new SearchClient("sk_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

    // build request
    $req = new SearchRequest([
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
