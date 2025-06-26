# autoconfig

Allows email clients (specifically Thunderbird) to get settings for an email client based upon the supplied email address.

## usage

Create sub-domains autoconfig.example.co.uk and autodiscover.example.co.uk.

Upload the entire project to the document root for both of these 2 sub-domains. 

cPanel allows you to use the same folder for more than one sub-domain; you may not need to duplicate anything.

### index.php and .htaccess

The files _\index.php_ and _\\.htaccess_ should be in the document root for both of these 2 sub-domains.

Ensure the `require_once` line in _index.php_ points to the autoconfig folder.

### Enter the required email settings 

Edit _\autoconfig\config\default.php_ according to your email server. Note: Ensure DOMAINS shows all email domains you wish to allow.

Any requests to 
_autoconfig.example.co.uk/xxx/config-v1.1.xml_ 
and 
_autodiscover.example.co.uk/xxx/autodiscover.xml_ 
will receive an appropriate reply, no matter what path is requested.

Requests to _autoconfig.example.co.uk/xxx/config.htm_ 
will reveal email settings.

## resources / further reading

### Documentation on building responses

https://wiki.mozilla.org/Thunderbird:Autoconfiguration

https://www.bucksch.org/1/projects/thunderbird/autoconfiguration/index.html

https://learn.microsoft.com/en-us/exchange/client-developer/web-service-reference/pox-autodiscover-web-service-reference-for-exchange

### useful related projects

https://github.com/Radiergummi/autodiscover

https://github.com/gronke/email-autodiscover

https://github.com/matejsmisek/php-mailserver-autodiscovery

### response examples

https://autoconfig.thunderbird.net/v1.1/freenet.de

https://autoconfig.freenet.de/?emailaddress=john@freenet.de

### Thunderbird 

TB checks these URLs. 

- https://example.co.uk/.well-known/autoconfig/mail/config-v1.1.xml?emailaddress=johndoe%40example.co.uk
- https://autoconfig.example.co.uk/mail/config-v1.1.xml?emailaddress=johndoe%40example.co.uk
- https://johndoe%40example.co.uk@autodiscover.example.co.uk/autodiscover/autodiscover.xml
- https://johndoe%40example.co.uk@example.co.uk/autodiscover/autodiscover.xml

Ctrl+Shift+I opens TB's Developer tools console. Then watch the network tab to see the TB's requests and server responses. 
