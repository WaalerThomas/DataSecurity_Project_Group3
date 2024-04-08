<?php
session_start();

require __DIR__ . "/../vendor/autoload.php";

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

$dateFormat = "Y-m-d\TH:i:sP";
$formatTxt = "%level_name% | %datetime% | %channel% > %message% | %context% %extra%\n";
$formatter = new LineFormatter($formatTxt, $dateFormat, false, true);

$validationFileHandler = new RotatingFileHandler("/logs/validation.log", 30, Level::Warning);
$validationFileHandler->setFormatter($formatter);

$systemFileHandler = new RotatingFileHandler("/logs/system.log", 30, Level::Notice);
$systemFileHandler->setFormatter($formatter);

function getUserIpAddr() {
    if (! empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (! empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function createLogger($channel) {
    $ip = getUserIpAddr();

    $logger = new Logger($channel);
    $logger->pushProcessor(function ($record) use ($ip) {
        $record->extra["ip-address"] = $ip;
        return $record;
    });
    return $logger;
}