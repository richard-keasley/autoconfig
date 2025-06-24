# autoconfig

## usage

### index.php and .htaccess

Create sub-domains autoconfig.example.co.uk and autodiscover.example.co.uk.

Place _index.php_ and _.htaccess_ in the document root for both of these 2 web spaces.

Ensure the `require_once` line in _index.php_ points to the autoconfig folder.

cPanel allows you to use the same folder for more than one sub-domain; you may not need to copy anything.

Edit _\autoconfig\config-default.php_ according to your email server.

Any request to _autoconfig.example.co.uk/xxx/config-v1.1.xml_ will receive an appropriate reply, no matter what path is requested.

## resources / further reading

### Documentation on building responses

https://wiki.mozilla.org/Thunderbird:Autoconfiguration

https://www.bucksch.org/1/projects/thunderbird/autoconfiguration/index.html

https://learn.microsoft.com/en-us/exchange/client-developer/web-service-reference/pox-autodiscover-web-service-reference-for-exchange

## useful related projects

https://github.com/Radiergummi/autodiscover

https://github.com/gronke/email-autodiscover

https://github.com/matejsmisek/php-mailserver-autodiscovery

### response examples

https://autoconfig.thunderbird.net/v1.1/freenet.de

https://autoconfig.freenet.de/?emailaddress=john@freenet.de

### Thunderbird 

TB checks these URLs. 

- https://example.co.uk/.well-known/autoconfig/mail/config-v1.1.xml?emailaddress=johndoe%40example.com
- https://autoconfig.example.co.uk/mail/config-v1.1.xml?emailaddress=johndoe%40example.com
- https://johndoe%40example.com@autodiscover.example.co.uk/autodiscover/autodiscover.xml
- https://johndoe%40example.com@example.co.uk/autodiscover/autodiscover.xml

Ctrl+Shift+I opens TB's Developer tools console. hen watch the network tab to see the requests  /responses made.
