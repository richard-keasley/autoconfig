<?php
namespace basecamp\autoconfig;

class response implements \stringable {
	
private $attributes = [];

public function __construct(string $content, string $extension='txt') {
	$this->attributes = [
		'content' => $content,
		'extension' => $extension,
	];
}

public function __toString() {
	return $this->content;
}

public function __get($key) {
	return $this->attributes[$key] ?? null ;
}

public function send() {
	if(headers_sent()) $content_type = null;
	else {
		$content_type = match($this->extension) {
			'xml' => 'application/xml',
			'htm' => 'text/html',
			default => 'text/txt',
		};
		header("Content-Type: {$content_type}; charset=utf-8");
		
		echo match($content_type) {
			'text/html' => '<!DOCTYPE html><html><body>',
			default => ''
		};
    }
	
	echo (string) $this;
	
	echo match($content_type) {
		'text/html' => '</body></html>',
		default => ''
	};
}

}
