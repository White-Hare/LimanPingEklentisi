<?php

const EXTENSION_ROOT = "/liman/extensions/ping";
const IPS_JSON = EXTENSION_ROOT . "/ips.json";


if (!function_exists('checkPort')) {
	function checkPort($ip, $port)
	{
		restoreHandler();
		if ($port == -1) {
			return true;
		}
		$fp = @fsockopen($ip, $port, $errno, $errstr, 0.1);
		setHandler();
		if (!$fp) {
			return false;
		} else {
			fclose($fp);
			return true;
		}
	}
}
