<?php
isset($this) or die;

$xml = new SimpleXMLElement('<clientConfig version="1.1"/>');

$provider = $xml->addChild('emailProvider');
$provider->addAttribute('id', '%INFO/DOMAIN%');
$provider->addChild('domain', '%INFO/DOMAIN%');
$provider->addChild('displayName', '%INFO/NAME%');
$provider->addChild('displayShortName', '%INFO/NAME%');

$config = $this->config['SERVER'] ?? [];
foreach($config as $skey=>$params) {
	$type = strtolower($skey);
	$selname = match($type) {
		'pop3' => "incomingServer",
		'imap' => "incomingServer",
		'smtp' => "outgoingServer",
		default => null // not supported
	};
	if($selname) {
		$server = $provider->addChild($selname);
		$server->addAttribute('type', $type);
		foreach($params as $pkey=>$val) {
			$pelname = match($pkey) {
				'HOST' => 'hostname',
				'SOCKET' => 'socketType',
				'AUTH' => 'authentication',
				default => strtolower($pkey)
			};
			if($pelname) {
				$server->addchild($pelname, $val);
			}
		}
	}
}

$config = $this->config['INFO']['DOCS'] ?? null;
if($config) {
	$eldocs = $provider->addchild('documentation');
	$val = $config['URL'] ?? null;
	if($val) $eldocs->addAttribute('url', $val);
	$vals = $config['DESCR'] ?? null;
	if($vals) {
		foreach($vals as $lang=>$text) {
			$el = $eldocs->addchild('descr', $text);
			$el->addAttribute('lang', $lang);
		}
	}
}

echo $xml->asXML();

/*

<?xml version="1.0" encoding="UTF-8"?>
<clientConfig version="1.1">
<emailProvider id="%INFO/DOMAIN%">
<domain>%INFO/DOMAIN%</domain>
<displayName>%INFO/NAME%</displayName>
<displayShortName>%INFO/NAME%</displayShortName>
<incomingServer type="imap">
	<hostname>%SERVER/IMAP/HOST%</hostname>
	<port>%SERVER/IMAP/PORT%</port>
	<socketType>%SERVER/IMAP/SOCKET%</socketType>
	<authentication>%SERVER/IMAP/AUTH%</authentication>
	<username>%EMAIL%</username>
</incomingServer>
<outgoingServer type="smtp">
	<hostname>%SERVER/SMTP/HOST%</hostname>
	<port>%SERVER/SMTP/PORT%</port>
	<socketType>%SERVER/SMTP/SOCKET%</socketType>
	<authentication>%SERVER/SMTP/AUTH%</authentication>
	<username>%EMAIL%</username>
</outgoingServer>
<documentation url="%INFO/DOCS/URL%">
	<descr lang="en">%INFO/DOCS/DESCR/EN%</descr>
</documentation>
</emailProvider>
</clientConfig>

*/