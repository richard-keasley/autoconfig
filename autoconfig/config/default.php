<?php
isset($this) or die;

$config = [
	'EMAIL' => '{emailaddress}',
	
	'TEST' => [
		 // 'XML-POST' => 'request.xml',
	],
	
	'INFO' => [
		'DOMAIN' => '{domain}',
		'DOMAINS' => ['example.uk', 'example.org.uk', 'example.co.uk'],
		'NAME' => '{name}',
		'DOCS' => [
			'URL' => "https://example.co.uk/email",
			'DESCR' => [
				'EN' => 'Email help',
			],
		],
	],
	
	'SERVER' => [
		'IMAP' => [
			'HOST' => "imap.%INFO/DOMAIN%",
			'PORT' => 993,
			'SOCKET' => "SSL",
			'USERNAME' => '%EMAIL%',
			'AUTH' => "password-cleartext",
		],
		'POP3' => [
			'HOST' => "pop.%INFO/DOMAIN%",
			'PORT' => 995,
			'SOCKET' => "SSL",
			'USERNAME' => '%EMAIL%',
			'AUTH' => "password-cleartext",
		],
		'SMTP' => [
			'HOST' => "smtp.%INFO/DOMAIN%",
			'PORT' => 465,
			'SOCKET' => "SSL",
			'USERNAME' => '%EMAIL%',
			'AUTH' => "password-cleartext",
		],
	],
];
