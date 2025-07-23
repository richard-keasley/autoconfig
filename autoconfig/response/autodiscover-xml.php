<?php
isset($this) or die;

$xml = new SimpleXMLElement('<Autodiscover/>');

$xml->addAttribute('xmlns', "http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006");
$xml->addAttribute('xmlns:xsd', "http://www.w3.org/2001/XMLSchema");
$xml->addAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");

$response = $xml->addChild('Response');
$response->addAttribute('xmlns', "http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a");

$account = $response->addChild('Account');
$account->addChild('AccountType', 'email');
$account->addChild('Action', 'settings');

$config = $this->config['SERVER'] ?? [];
foreach($config as $skey=>$params) {
	$protocol = $account->addChild('Protocol');
	$protocol->addchild('Type', $skey);
	
	foreach($params as $pkey=>$val) {
		$pelname = match($pkey) {
			'HOST' => 'Server',
			'USERNAME' => 'LoginName',
			'SOCKET' => 'SSL',
			'AUTH' => 'Authentication',
			default => ucfirst(strtolower($pkey))
		};
		if(!$pelname) continue;
	
		$val = match($pelname) {
			'SSL' => $val=='SSL' ? 'on' : 'off',
			default => $val
		};
		$protocol->addchild($pelname, $val);
	}
}

$config = $this->config['INFO']['DOCS'] ?? null;
if($config) {
	$eldocs = $response->addchild('documentation');
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
<?xml version="1.0" encoding="utf-8" ?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
    <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
        <Account>
            <AccountType>email</AccountType>
            <Action>settings</Action>
            <Protocol>
                <Type>IMAP</Type>
                <Server>mx.freenet.de</Server>
                <Port>993</Port>
                <SSL>on</SSL>
                <SPA>on</SPA>
                <AuthRequired>on</AuthRequired>
                <DomainRequired>on</DomainRequired>
            </Protocol>
            <Protocol>
                <Type>POP3</Type>
                <Server>mx.freenet.de</Server>
                <Port>995</Port>
                <SSL>on</SSL>
                <SPA>on</SPA>
                <AuthRequired>on</AuthRequired>
                <DomainRequired>on</DomainRequired>
            </Protocol>
            <Protocol>
                <Type>SMTP</Type>
                <Server>mx.freenet.de</Server>
                <Port>465</Port>
                <SSL>on</SSL>
                <SPA>on</SPA>
                <AuthRequired>on</AuthRequired>
                <UsePOPAuth>on</UsePOPAuth>
                <DomainRequired>on</DomainRequired>
            </Protocol>
        </Account>
    </Response>
</Autodiscover>
*/