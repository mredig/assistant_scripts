# secure_linux_setup_assistant

This is intended to be run very soon after an initial setup of linux. It will do the following to help facilitate a secure linux setup:
* install `unattended-upgrades`
	* install and setup `bsd-mailx` to report on upgrades
* create a new non-root, non-default sudo user (and remove the default `pi` user from raspbian)
* assist in `ssh-keygen` creation
* place a public key in the newly created user for more secure remote ssh access
* setup the ssh server with a few, specific and more secure settings
	* disables root remote login (which is also the best way to initially run this script so you can easily copy/paste the ssh public keys - the disable comes at the end of the script)
	* disables password access to ssh (key pairs ONLY)
	* limits ssh to only run on IP4 or IP6, whichever you prefer
	* installs `fail2ban` to blacklist individuals with failed ssh attempts
* sets up `ufw` for firewall

Here is a convenient direct link to the raw script that is easy enough to type into a console without copy/paste:
# [need to fix](http://bit.ly/2o2WQ03)

It is (ironically) best to enable root ssh access prior to running this script, but will disable that at the end.

There are a few reasons
* you need to access root directly (not via `sudo` or `su`)
	* elevating through either of those will keep the original user logged in - this might not matter if you're not removing the original user, but in the case of the Raspberry Pi, the pi user should be removed. It's too common and can be easily guessed to make attempts to connect via that user.
* ssh will allow you control this script via a computer you will be connecting from again in the future, so you will have easy, copy/paste access to your public ssh key, which the script will ask for

Ultimately, you are free to run it through elevation or on a console session, but some of the features won't work (and might not completely reflect the fact that they didn't)
