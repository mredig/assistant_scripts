<?php

openConf();



function runJob($homeDir, $dnsRecord, $rbl_override, $rblEntries) {
	chdir($homeDir);

	$dnsUpdatePreviousValue = "dnsRBLUpdatePreviousValue-$dnsRecord";

	if (file_exists($dnsUpdatePreviousValue)) {
		$fileHandle = fopen($dnsUpdatePreviousValue, "r");
		$oldDnsIP = fread($fileHandle,filesize($dnsUpdatePreviousValue));
		fclose($fileHandle);
	} else {
		$oldDnsIP = '';
	}



	$digCommand = 'dig ' . $dnsRecord . ' +short @8.8.8.8';

	exec($digCommand, $digValue);

	if (isset($digValue) && is_array($digValue)) {
		$latestIP = $digValue[count($digValue) - 1];
		if (($latestIP != $oldDnsIP && !empty($latestIP))) { //if latestIP is not same as oldDnsIP and latest ip has contents, OR rblEntires dosn't have an entry for

			if (!isset($rblEntries[$latestIP])) {
				if (isset($rblEntries[$oldDnsIP]) && !empty($oldDnsIP)) {
					$command = "sed -i 's/^$oldDnsIP/$latestIP/' $rbl_override";
				} else {
					$command = "echo '\n$latestIP OK\n' >> $rbl_override";
				}
				print "$command\n";
				exec($command);
				$postmapCommand = "postmap $rbl_override";
				exec($postmapCommand);
				exec('service postfix restart');

			}

			$fileHandle = fopen($dnsUpdatePreviousValue, "w") or die("Unable to open file: $dnsUpdatePreviousValue!");
			$latestIP = fwrite($fileHandle,$latestIP);
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

	if (!isset($job['rbl_override'])) {
		$job['rbl_override'] = "/etc/postfix/rbl_override";
	}

	// print_r($job);

	$rblEntries = processRBL($job['rbl_override']);

	runJob($job['saveDir'], $job['dynamicDNS'], $job['rbl_override'], $rblEntries);
}


function processRBL($rbl_override) {

	if (!file_exists($rbl_override)) {
		return array();
	}

	$fileHandle = fopen($rbl_override, "r");
	$rblContents = fread($fileHandle,filesize($rbl_override)) or die("Can't open conf file: $rbl_override!");
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
