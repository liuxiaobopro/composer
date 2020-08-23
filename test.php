<?php

require 'vendor/autoload.php';

use liuxiaobo\curl as curlL;

$curlL = new curlL();
$res = $curlL->getHttp('https://api.apiopen.top/getJoke?page=1&count=2&type=video');
var_dump($res);