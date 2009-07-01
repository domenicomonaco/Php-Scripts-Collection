<?php
/*
Whois domain - version 1.22
Check domain names for different TLD's

Copyright (c) 2004 - 2006, Olaf Lederer
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the finalwebsites.com nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

_________________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

Updates:
version 1.10 - In this version is it possible to check domain names for all tld's which are configured inside the server list. 

New Files: "multi_whois_list.php" this file is an example for using the new functions and is also an extended version of the main class.
"multi_whois_detail.php" you need this file to get detail information ondomain names checked with the list file.

version 1.11 - I added the var $whois_param, use this var via the serverlist to use extra (required) parameters for some whois servers.

version 1.12 - I changed the get_whois_data() and the check_only() method, because the information was not right if there was no server connection. This class works now for PHP with the setting register_globals = off.

version 1.13 - I added a boolean to the check_only() method to use this information for domain register pages. You can use this "bool" to redirect to an other page (to sell the free name f.e.).

version 1.20 - I changed in this class the simple domain check and the method to obtain whois data, because there are several problems with timeouts by calling the whois servers. In this version there are two ways to get the whois data: the old method with fsockopen and (new) via the linux command line with the command "whois -h". The last one is much faster and uses features like re-directing. The class is modified to handle .nl queries while using the "is" parameter.

version 1.21 - There was small problem if the user requests data about .de domein (denic), the exec function will not work for this kind of tld. I put in the method get_whois_data() an if clause to use for this kind of tld always a socket connection.

version 1.22 - Added additional whois server setting (.eu, .us) and the regex pattern is a little bit improved now.
*/

class Whois_domain {
	
	var $possible_tlds;
	var $whois_server;
	var $free_string;
	var $whois_param;
	var $domain;
	var $tld;
	var $compl_domain;
	var $full_info;
	var $msg;
	var $info;
	var $os_system = "linux"; // switch between "linux" and "win"
	 
	function Whois_domain() {
		$this->info = "";
		$this->msg = "";
	}
	function process() {
		if ($this->create_domain()) {
			if ($this->full_info == "yes") {
				$this->get_domain_info();
			} else {
				if ($this->check_only() == 1) {
					$this->msg = "The domain name: <b>".$this->compl_domain."</b> is free.";
					return true;
				} elseif ($this->check_only() == 0) {
					$this->msg = "The domain name: <b>".$this->compl_domain."</b> is registered";
					return false;
				} else {
					$this->msg = "There was something wrong, try it again.";
				}
			}
		} else {
			$this->msg = "Only letters, numbers and hyphens (-) are valid!";
		}
	}
	function check_entry() {
		if (preg_match("/^([a-z0-9]+(\-?[a-z0-9]*)){2,63}$/i", $this->domain)) {
			return true;
		} else {
			return false;
		}
	}
	function create_tld_select() {
		$menu = "<select name=\"tld\" style=\"margin-left:0;\">\n";
		foreach ($this->possible_tlds as $val) {
			$menu .= "  <option value=\"".$val."\"";
			$menu .= (isset($_POST['tld']) && $_POST['tld'] == $val) ? " selected=\"selected\">" : ">";
			$menu .= $val."</option>\n";
		}
		$menu .= "</select>\n";
		return $menu;
	}
	function create_domain() {
		if ($this->check_entry()) {
			$this->domain = strtolower($this->domain);
			$this->compl_domain = $this->domain.".".$this->tld;
			return true;
		} else {
			return false;
		}
	}
	function check_only() {
		$data = $this->get_whois_data();
		if (is_array($data)) {
			$found = 0;
			foreach ($data as $val) {
				if (eregi($this->free_string, $val)) {
					$found = 1;
				} 
			}
			return $found;
		} else {
			$this->msg = "Error, please try it again.";
		}
	}
	function get_domain_info() {
		if ($this->create_domain()) {
			$data = ($this->tld == "nl") ? $this->get_whois_data(true) : $this->get_whois_data();
			if (is_array($data)) {
				foreach ($data as $val) {
					if (eregi($this->free_string, $val)) {
						$this->msg = "The domain name: <b>".$this->compl_domain."</b> is free.";
						$this->info = "";
						break;
					}
					$this->info .= $val;
				}
			} else {
				$this->msg = "Error, please try it again.";
			}
		} else {
			$this->msg = "Only letters, numbers and hyphens (-) are valid!";
		}
	}
	function get_whois_data($empty_param = false) { 
	// the parameter is new since version 1.20 and is used for .nl (dutch) domains only
		if ($empty_param) {
			$this->whois_param = "";
		}
		if ($this->tld == "de") $this->os_system = "win"; // this tld must be queried with fsock otherwise it will not work
		if ($this->os_system == "win") {
			$connection = @fsockopen($this->whois_server, 43);
			if (!$connection) {
				unset($connection);
				$this->msg = "Can't connect to the server!";
				return;
			} else {
				sleep(2);
				fputs($connection, $this->whois_param.$this->compl_domain."\r\n");
				while (!feof($connection)) {
					$buffer[] = fgets($connection, 4096);
				}
				fclose($connection);
			}
		} else {
			$string = "whois -h ".$this->whois_server." \"".$this->whois_param.$this->compl_domain."\""; 
			$string = str_replace (";", "", $string).";";
			exec($string, $buffer);
		}
		if (isset($buffer)) {
			//print_r($buffer);
			return $buffer;
		} else {
			$this->msg = "Can't retrieve data from the server!";
		}
	}
}
?>
