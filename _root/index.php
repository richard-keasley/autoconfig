<?php 
/** 
* place this file and .htaccess
* in the root of the sub-domains 
* `autoconfig` and `autodiscover`
*/

// enable these lines to test
# error_reporting(E_ALL);
# ini_set('display_errors', 1); 

require_once __DIR__ . '/autoconfig/autoconfig.php';

echo new \basecamp\autoconfig();
