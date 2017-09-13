<!-- permalink: d5600d07a96c0459aa6dd3952e1b5caf DO NOT DELETE OR EDIT THIS LINE -->
# clam_run_conf

This is simply an interface for clamav that makes it a bit easier to use and set for scheduled tasks. The conf is configured with a sample conf that is easy to reconfigure and provides extra abilities for postflight scripts to run and email a log of successes or failures.

Arguments provided to the script are applied from left to right - `clam_run_conf -c [conf1] -d -c [conf2] -c [conf3]` conf1 is run, conf2 and 3 and run in debug mode

It's recommended to only `mailInfectionOnly = "YES"` AFTER you've confirmed that emails will send successfully and whitelisted the address!

Exclusions file must be formatted in the following way:

```
FILE EXCLUSIONS:
filename.ext
/path/to/filename2.ext

DIRECTORY EXCLUSIONS:
^/path/to/directory
```

Note that exclusions are passed directly to the command line, so be careful to watch for spaces and other special characters that throw off command line input. ALSO: BE CAREFUL FOR CODE INJECTION! **Be sure that only privileged people have write access to the conf file!** I would like to fix this, but I've had a little wine and would like to get things to work for now. This also means that you can take advantage of the regex power of clamscan exclusions.
