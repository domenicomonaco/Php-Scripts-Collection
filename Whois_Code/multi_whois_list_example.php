<?php 
include("server_list.php");
include("whois_class.php");
// error_reporting(E_ALL);
set_time_limit(120); // a higher time limit for slow server's
class Multi_whois_query extends Whois_domain {
	
	var $dom_str;
	
	function Multi_whois_query () {
		$this->dom_str = "";
	}
	function show_tlds() {
		$tld_str = "";
		foreach ($this->possible_tlds as $val) {
			$tld_str .= $val.", ";
		}
		$tlds = substr($tld_str, 0, -2);
		return $tlds;
	}
	// this is modified method to handle multiple domain requests
	function create_dom_list() {
		if ($this->create_domain()) {
			if ($this->check_only() == 1) {
				$this->dom_str .= "<b>".$this->compl_domain."</b> is free.<br>\n";
			} else {
				$this->dom_str .= "<b>".$this->compl_domain."</b> is registered";
				$this->dom_str .= " - <a href=\"multi_whois_detail.php?det=".$this->compl_domain."\" target=\"_blank\">Detail</a><br>\n";
			}
		} else {
			$this->msg = "Only letters, numbers and hyphens (-) are valid!";
		}
	}
}
$multi_whois = new Multi_whois_query;
$multi_whois->possible_tlds = array_keys($servers); // this is the array from the included server list

if (isset($_POST['submit'])) {
	$multi_whois->domain = $_POST['domain'];
	foreach ($multi_whois->possible_tlds as $val) {
		$multi_whois->tld = $val;
		$multi_whois->free_string = $servers[$val]['free'];
		$multi_whois->whois_server = $servers[$val]['address'];
		$multi_whois->whois_param = $servers[$val]['param'];
		$multi_whois->create_dom_list();
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>(Multiple) Whois class example</title>
</head>

<body>
  <h2>Whois domain name check (multiple tld's) </h2>
  <p>With this example is it possible to check domain names for all available tld's<br>
    in this class. If a domain is registered, there is  link which will show you all<br>
  information for this domain stored on the whois server.
  </p>
  <p><b>Try it: </b><br>
    Enter a name for a domain check and click &quot;OK&quot;.<br>
    The script will show you information for the tld's:<br>
	<b><?php echo $multi_whois->show_tlds(); ?></b></p>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="domain" size="14" maxlength="63" value="<?php echo (isset($domain)) ? $domain : ""; ?>">
    <input name="submit" type="submit" value="OK">		  
  </form>
  <p><?php echo ($multi_whois->msg != "") ? $multi_whois->msg : ""; ?></p>
  <?php if ($multi_whois->dom_str != "") { ?>
  <h2>Your request for <b><?php echo $_POST['domain'] ?>.*</b></h2>
  <p><?php echo $multi_whois->dom_str; ?></p>
  <?php } ?>
  
</body>
</html>
