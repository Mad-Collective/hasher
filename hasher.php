#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3])) {
    echo 'usage: hasher <hash|check> <private_key> <payload> [<hash>]' . PHP_EOL;
    exit(1);
}

$command = strpos($argv[1], 'hash') !== false ? 0 : 1;
$secretKey = $argv[2];
$payload = $argv[3];
$hash = null;

if ($command == 1 && !isset($argv[4])) {
    echo 'need both payload and hash to check it'. PHP_EOL;
    exit(1);
} elseif ($command == 1 && isset($argv[4])) {
    $hash = $argv[4];
}

$hasher = new \Pluggit\Hasher\Hasher($secretKey);

if ($command == 0) {
    echo $hasher->hash($payload). PHP_EOL;
} else {
    echo $hasher->isValid($payload, $hash) ? 'valid'. PHP_EOL : 'invalid'. PHP_EOL;
}
