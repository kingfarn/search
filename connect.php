<?php
require_once 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();
