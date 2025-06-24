<?php
namespace basecamp\autoconfig;

class exception extends \exception {

public function __construct($message='', $code=400, ?Throwable $previous=null) {
	parent::__construct($message, $code, $previous);
	
	if(!headers_sent()) http_response_code($code);
	
	if(ini_get('display_errors')) {
		$message = [$this->getMessage()];
		$message[] = sprintf('%s, line %s', $this->getfile(), $this->getLine());	
		// get previous call (if any) 
		$stack = debug_backtrace();
		$line = $stack[1]['line'] ?? null;
		if($line) $message[] = "called from line {$line}";
		
		$format = '<div style="padding:.5em;border:#c00 1px solid">%s</div>';
		printf($format, implode('<br>&nbsp;', $message));	
	}
	// caught exceptions are not logged 
	die;
}

}