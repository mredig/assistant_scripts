<?php

openConf();



function runJob($homeDir, $dnsRecord, $rblEntries) {
	chdir($homeDir);

	$dnsUpdatePreviousValue = "dnsRBLUpdatePreviousValue-$dnsRecord";

	$fileHandle = fopen($dnsUpdatePreviousValue, "r");
	$oldDns = fread($fileHandle,filesize($dnsUpdatePreviousValue));
	fclose($fileHandle);

	$digCommand = 'dig ' . $dnsRecord . ' +short @8.8.8.8';

	exec($digCommand, $digValue);
	$rbl_override = '/etc/postfix/rbl_override';

	if (isset($digValue) && is_array($digValue)) {
		$last = $digValue[count($digValue) - 1];
		if ($last != $oldDns && !empty($last)) {

			if (empty($oldDns)) {
				$command = "echo '\n$last OK\n' >> /etc/postfix/rbl_override";
			} else {
				$command = "sed -i 's/^$oldDns/$last/' $rbl_override";
			}
			print "$command\n";
			// exec($command);
			// exec('postmap /etc/postfix/rbl_override');
			// exec('service postfix restart');

			$fileHandle = fopen($dnsUpdatePreviousValue, "w") or die("Unable to open file: $dnsUpdatePreviousValue!");
			$last = fwrite($fileHandle,$last);
			fclose($fileHandle);

		}
	}
}

function openConf() {
	if (!empty(getopt('c:'))) { // php index.php -c confFile
		$opts = getopt('c:');
		$confFile = $opts['c'];
		// print "$confFile\n";
		processConf($confFile);
	}
}

function processConf($confFile) {
	$fileHandle = fopen($confFile, "r");
	$confContents = fread($fileHandle,filesize($confFile)) or die("Can't open conf file: $confFile!");
	fclose($fileHandle);

	$confLines = explode("\n", $confContents);

	$job = array();
	foreach ($confLines as $line) {
		preg_match("/^(\S+) = \"(.*)\"$/", $line, $options);
		if (count($options) > 2) {
			$job[$options[1]] = $options[2];
		}
	}

	// print_r($job);

	$rblEntries = processRBL();

	// $previousDNSValue = processPreviousValue();

	runJob($job['saveDir'], $job['dynamicDNS'], $rblEntries);
}


// function processPreviousValue() {
//
// }
//
// function saveNewPreviousValue() {
//
// }

function processRBL() {

	$fileHandle = fopen($confFile, "r");
	$rblContents = fread($fileHandle,filesize($confFile)) or die("Can't open conf file: $confFile!");
	fclose($fileHandle);

	$rblLines = explode("\n", $rblContents);

	$rblEntries = array();
	foreach ($rblLines as $line) {
		preg_match("/^(\S+)\s+\w+/", $line, $matches);
		if (isset($matches[1])) {
			$rblEntries[$matches[1]] = 0;
		}
	}

	return $rblEntries;

}


?>
