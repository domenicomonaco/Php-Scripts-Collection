<?php 
// this ist list with servers which are used with this class
// info about the values for the key "free":
// this is the string (answer) what the server returns if there is no match.

// the value's for the key 'param' is mostly empty. I you have some suggestions for diff. 
// whois servers, then let me know...

$servers['com']['address'] = "whois.crsnic.net";
$servers['com']['free'] = "No match for";
$servers['com']['param'] = "";

$servers['net']['address'] = "whois.crsnic.net";
$servers['net']['free'] = "No match for";
$servers['net']['param'] = "";

$servers['org']['address'] = "whois.pir.org";
$servers['org']['free'] = "NOT FOUND";
$servers['org']['param'] = "";

$servers['name']['address'] = "whois.nic.name";
$servers['name']['free'] = "No match";
$servers['name']['param'] = "";

$servers['biz']['address'] = "whois.nic.biz";
$servers['biz']['free'] = "Not found";
$servers['biz']['param'] = "";

$servers['info']['address'] = "whois.afilias.net";
$servers['info']['free'] = "NOT FOUND";
$servers['info']['param'] = "";

$servers['nl']['address'] = "whois.domain-registry.nl";
$servers['nl']['free'] = "is free";
$servers['nl']['param'] = "is "; // is used only for free check

$servers['be']['address'] = "whois.dns.be"; 
$servers['be']['free'] = "free";
$servers['be']['param'] = "";

$servers['de']['address'] = "whois.denic.de";
$servers['de']['free'] = "not found in database";
$servers['de']['param'] = "-T dn "; // required!!

$servers['fr']['address'] = "whois.nic.fr";
$servers['fr']['free'] = "No entries found";
$servers['fr']['param'] = ""; 

$servers['eu']['address'] = "whois.eu";
$servers['eu']['free'] = "FREE";
$servers['eu']['param'] = ""; 

$servers['us']['address'] = "whois.nic.us";
$servers['us']['free'] = "Not found";
$servers['us']['param'] = "";

// add here more servers if you like
?>
