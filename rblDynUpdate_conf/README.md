# rblDynUpdate_conf README

I set up my own mail server recently and, by default, it blocks everything without a static IP or something. The other scripts in here require using sendmail to report issues to me. I'd like to revamp them to use SMTP with authentication, but I don't have the time to handle that at the moment. As a workaround, I wrote this script to dynamically update the rbl whitelist with IPs based on dynamic dns values.

For the most part, just edit the conf file to point to a directory that it will keep a file noting the previous IP and what domain you want to keep it updated with.


### Usage
`php rblDynUpdate_conf -c /path/to/conf`


<!-- note to self: will need to get permalink in here before updating notes repo with latest commit -->
