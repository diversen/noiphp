<?php

// define a base path
$path = dirname(__FILE__);
$path = realpath($path);
define('_COS_PATH', $path);

include_once "coslib/config.php";
include_once "coslib/mycurl.php";
include_once "coslib/log.php";
include_once "coslib/IP.php";

log::createLog();

$config_file = _COS_PATH . '/config/config.php';
config::loadPHPConfigFile($config_file);

// simple api for getting ip.
$api_ip = config::getMainIni('api_ip');
if (!$api_ip) {
    $api_ip = 'https://api.10kilobyte.com/my_ip.php';
}
$my_ip = @file_get_contents($api_ip);
if ($my_ip === false) {
    log::error("Could not get your public IP. No check of current DNS settings");
    return;
}

if (!IP::isPublic($my_ip)) {
    log::error("IP $my_ip is not public");
}

$my_ip = trim($my_ip);

$my_hostnames = config::getMainIni('my_hostnames'); // if more hosts use a comma seperated list

$url = config::getMainIni('api_url');
$url.= "?hostname=$my_hostnames&myip=$my_ip";

$user_agent = "User-Agent: noiphp/0.0.1 dennis.iversen@gmail.com";

$curl = new mycurl($url);
$curl->useAuth(true);
//$curl->setCert(config::getMainIni('cert'));

$email = config::getMainIni('email');
$password = config::getMainIni('password');

$curl->setName($email);
$curl->setPass($password);
$curl->setUserAgent($user_agent);
$curl->createCurl();

$result = $curl->getWebPage();
log::error($result);
die;
