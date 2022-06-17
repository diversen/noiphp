PHP client for the no-ip.com API for auto update a no-ip DNS server to point to a dynamic IP.

Setup, and usage. See: 

https://blog.10kilobyte.com/blog/view/21/NO-IP-client-written-in-PHP

## Changelog: 

### v0.0.4

Fix log of error

### v0.0.3

    'api_ip' => 'http://www.os-cms.net/api/your_addr.php',
    // as we use a log with date we need a date format
    'date_format_long' => "%d-%b-%Y %T",
Add `date_format_long` to config in order to remove notices about missing timestamps
If upgrading to a newer version of php (e.g. 5.5) you should add this to your
config/config.php file

Add `api_ip` for allowing to set a different url where we can get your current IP, 
which is then used to send to the no-ip service. You can just leave this one empty
and the following will be used: 

    http://www.os-cms.net/api/your_addr.php

