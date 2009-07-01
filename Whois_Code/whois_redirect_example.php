<?php 
include("server_list.php");
include("whois_class.php");
// error_reporting(E_ALL);
$my_whois = new Whois_domain;
$my_whois->possible_tlds = array_keys($servers); // this is the array from the included server list

if (isset($_POST['submit'])) {
	$my_whois->tld = $_POST['tld'];
	$my_whois->domain = $_POST['domain'];
	$my_whois->free_string = $servers[$_POST['tld']]['free'];
	$my_whois->whois_server = $servers[$_POST['tld']]['address'];
	$my_whois->whois_param = $servers[$_POST['tld']]['param'];
	if ($my_whois->process()) {
		header ("Location: http://www.kqzyfj.com/click-1701500-10299016");
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Whois class example with redirect</title>
</head>

<body>
  <h2>Whois domain name check with redirect </h2>
  <p>Check here only  the domain name availability, <br>
    if the domain name is free you will be redirected to a external page.<br>
  </p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
    <input type="text" name="domain" size="14" maxlength="63" value="<?php echo (isset($domain)) ? $domain : ""; ?>">
    <?php echo $my_whois->create_tld_select(); // this method generates the select menu woth all tld's ?>
    <input name="submit" type="submit" value="Check">		  
  </form>
  <p><?php echo ($my_whois->msg != "") ? $my_whois->msg : ""; ?></p>
  
</body>
</html>
