#!/usr/bin/php  
<?php 

require('config.php');
require_once 'Exception.php';
require_once 'Twitter.php';
use \TijsVerkoyen\Twitter\Twitter;

$twitter = new Twitter($key, $secret);

$twitter->setOAuthToken('882538716-WjflQetgVDukfr7hZTOpy2qNjRGMaBmQORsNCPkD');
$twitter->setOAuthTokenSecret('c12XwHjEmfjrgzCJHClSMNyP5lH2MEZszsuFurhFAI');

$geo = '-33.67052978624721,-70.36337194999999,100';

$buscados = $twitter->searchTweets('Santiago', $geo, null, null, null, 5, null, null, null, null);

print_r($buscados);