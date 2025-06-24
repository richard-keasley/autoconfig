<?php
namespace basecamp;

/**
* autoconfig responder
* 
* responds to any request 
* with a spec-compliant autodiscover or autoconfig XML response
*
* You'll need to set sub-domains to provide the correct URLs
*/

require_once __DIR__ . '/exception.php';
require_once __DIR__ . '/response.php';
use basecamp\autoconfig\exception;
use basecamp\autoconfig\response;

class autoconfig implements \stringable {
	
private $attributes = [];

function __construct($config='default') {
	// get config
	include $this->get_include("config/{$config}.php");
	$this->attributes['config'] = $config ?? null;
	if(!$this->config) throw new exception("No config");
		
	$test = $this->config['TEST'] ?? [];	
	/* find email address from input */
	$success = false;
	if(!$success) {
		// raw POST data (xml)
		$method = 'xml';
		$source = $test['XML-POST'] ?? null;
		$source = $source ?
			$this->get_include("test/{$source}") : 
			'php://input' ;
		$request = file_get_contents($source);
		preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/i", $request, $arr);
		$success = $arr[1] ?? null;
	}
	
	if(!$success) {
		$method = 'get';
		$success = filter_input(INPUT_GET, 'emailaddress');
	}
	
	if(!$success) throw new exception("No email address");
	$this->attributes['emailaddress'] = $success;
	$this->attributes['method'] = $method;
			
	// check email address is valid
	if(filter_var($this->emailaddress, FILTER_VALIDATE_EMAIL)===false) {
		throw new Exception('Invalid email address');
	}

	// get name / domain from email address
	$arr = explode('@', $this->emailaddress);
	$this->attributes['name'] = $arr[0] ?? '' ;
	$this->attributes['domain'] = $arr[1] ?? '' ;
}

function __tostring() {
	ob_start();
	$response = $this->get_response();
	if($response) $response->send();
	return ob_get_clean();
}

function __get($key) {
	return $this->attributes[$key] ?? null ;
}	

function get_response() {
	// calculate template for response
	$keys = ['SCRIPT_URL', 'REQUEST_URI', 'SCRIPT_URI'];
	foreach($keys as $key) { 
		$request = filter_input(INPUT_SERVER, $key);
		$path = $request ? parse_url($request, PHP_URL_PATH) : null ;
		$pathinfo = $path ? pathinfo($path) : null ;
		$basename = $pathinfo['filename'] ?? null ;
		$filetype = $pathinfo['extension'] ?? 'txt' ;
		if($basename) break;
	}
	if(!$basename) throw new Exception("Unsupported request");
	
	// check domain is supported
	$config = $this->config['INFO']['DOMAINS'] ?? null;
	if($config) {
		if(!in_array($this->domain, $config)) throw new Exception("Unsupported domain");
	}
	
	ob_start();
	include $this->get_include("response/{$basename}-{$filetype}.php");
	$content = $this->translate(ob_get_clean());
	
	return new response($content, $pathinfo['extension']);
}

private function get_include($basename) {
	$include = __DIR__ . "/{$basename}";
	if(is_file($include)) return $include;
	throw new exception("{$basename} not found", 404);
}

private function translate($string) {
	$translate = [];
	$pattern = '#\{(.+)\}#';
	
	foreach($this->translate_sub($this->config) as $key=>$val) {
		$placeholder = preg_match($pattern, $val, $matches);
		if($placeholder) {
			$phkey = $matches[1] ?? null;
			if($phkey) $val = $this->attributes[$phkey];
		}
		$translate["%{$key}%"] = $val;
	}
	
	return strtr($string, $translate);
}

private function translate_sub($array, $translate=[], $parent=null) {
	foreach($array as $key=>$val) {
		if($parent) $key = "{$parent}/{$key}";
		if(is_array($val)) $translate = $this->translate_sub($val, $translate, $key);
		else $translate[$key] = $val;
	}
	return $translate;
}
	
}
