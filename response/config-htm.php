<?php
isset($this) or die;

$list = [
	['%INFO/NAME%', 'name'],
	['%INFO/DOMAIN%', 'domain'],
];
	
$config = $this->config['SERVER'] ?? [];
foreach($config as $skey=>$params) {
	$type = strtolower($skey);
	$list[] = $type;

	foreach($params as $pkey=>$val) {
		$pelname = match($pkey) {
			'HOST' => 'hostname',
			'SOCKET' => 'socketType',
			'AUTH' => 'authentication',
			default => strtolower($pkey)
		};
		if($pelname) {
			$list[] = [$val, $pelname];
		}
	}
}

$config = $this->config['INFO']['DOCS'] ?? null;
if($config) {
	$list[] = 'documentation';
	$val = $config['URL'] ?? null;
	if($val) $list[] = [$val, 'url'];
	$vals = $config['DESCR'] ?? null;
	if($vals) {
		foreach($vals as $lang=>$text) {
			$list[] = [$text, $lang];
		}
	}
}

echo '<ul>';
foreach($list as $item) {
	if(is_array($item)) {
		$val = $item[0] ?? null ;
		$key = $item[1] ?? null ;
	}
	else {
		$val = $item;
		$key = null ;
	}
	if(!$val) continue;
	echo $key ? 
		"<li><strong>{$key}:</strong> {$val}</li>" : 
		"</ul><h3>{$val}</h3><ul>" ;
}
echo '</ul>';
