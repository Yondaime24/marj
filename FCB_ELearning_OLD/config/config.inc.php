<?php
/**
 * Time Zone
 */
date_default_timezone_set("asia/manila");
/**
 * SERVER_IP
 */
define("NETLINKZ_URL", "http://172.30.6.194/netlinkz/index.php");
define("NETLINKZ_ROOT", "http://172.30.6.194/netlinkz/");
/**
 * Project Root
 */
define('SERVER_ROOT', '/FCB_ELearning/');
define("ROOT_PATH", SERVER_ROOT);

define("ADMIN_PATH", SERVER_ROOT.'FEED_ADMIN/');

/*  Constant Connection */
// feed connection
define("FEED17", [
    "constring" => "odbc:driver=SQL Anywhere 17;commlinks=tcpip;dbn=feed17;eng=netlinkz;",
    "uid" => "feedadmin",
    "pass" => "feedadmin" 
]);
// netlinkz connection
define("NETLINKZ", [
    "constring" => "odbc:driver=SQL Anywhere 17;commlinks=tcpip;dbn=netlinkz;eng=netlinkz;",
    "uid" => "root",
    "pass" => "Adm1nUs3r080412" 
]);
// categorized17
// memo
define("CAT", [
    "constring" => "odbc:driver=SQL Anywhere 17;commlinks=tcpip;dbn=categorize17;eng=netlinkz;",
    "uid" => "dba",
    "pass" => "Adm1nUs3r080412" 
]);
// CIF connection
define("CIF", [
    "constring" => "odbc:driver=SQL Anywhere 17;commlinks=tcpip;dbn=customer17;eng=customer17;",
    "uid" => "dba",
    "pass" => "Adm1nUs3r080412" 
]);
define("REQ", strtolower($_SERVER['REQUEST_METHOD']));