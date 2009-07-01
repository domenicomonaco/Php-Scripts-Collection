<?php 
include("server_list.php");
include("whois_class.php");
// error_reporting(E_ALL);
$whois_detail = new Whois_domain;

if (isset($_GET['det'])) {
	$dom_parts = explode(".", $_GET['det']);
	$whois_detail->tld = $dom_parts[1];
	$whois_detail->domain = $dom_parts[0];
	$whois_detail->free_string = $servers[$dom_parts[1]]['free'];
	$whois_detail->whois_server = $servers[$dom_parts[1]]['address'];
	$whois_detail->whois_param = $servers[$dom_parts[1]]['param'];
	$whois_detail->full_info = "yes";
	$whois_detail->process();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Whois class detail page (example)</title>
</head>

<body onLoad="javascript:window.resizeTo(380,440);return false;self.focus()">
  <?php if ($whois_detail->info != "") { ?>
  <h2><?php echo "Whois ".$whois_detail->compl_domain."?" ?></h2>
  <blockquote>
    <?php echo nl2br($whois_detail->info); ?>
  </blockquote>
  <?php } ?>
</body>
</html>
