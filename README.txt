------------------------------------------------------------------------------------------
 - TODO -
----------------------------------------------
	ArgumentsHelper.py
		- parse all options, return single object
			- option is --style(=value)
		- nextArg should iterate only though options, remove argv iterate

	- where should i store username and passwords?

	- hot features
		- plugins page
			- Global options for source and compiled folder "names"
			- ability to create a new step using dropdowns and textboxes as options
		- sync core needed to support various host to destination uri schemes
	-  run remote command
		- run a script from jenkins on a server, source for script exists on either
			jenkins or the host
		- support same URI scheme
			- ssh://user@myhost.com/home/user/myScript.sh
			- telnet://user@myhost.com/C/Users/user/myScript.cmd

------------------------------------------------------------------------------------------
- Typical Job -
----------------------------------------------

$ jenkins build-assets pre
$ jenkins compile compass
$ jenkins compile yui
$ jenkins compile plovr
$ jenkins compile fake php
$ jenkins build-assets post
$ jenkins archive
$ jenkins deploy sftp://mydomain.com/home/www/ht_docs

------------------------------------------------------------------------------------------
- Workspace -
----------------------------------------------
/ <-- VCS root here
/config.rb (for compass)
	/build-assets
		/minify
			/plovr.json (for plovr)
			/js.txt (for closure)
			/css.txt (for yui)
		/post (case insensitive, merges into source on build-assets command)
			/dev
			/prd
		/pre
			/dev
			/prd
	/src
		/php (fake compile could copy this as /target)
	/target <-- IGNORED, created when compiling an app

------------------------------------------------------------------------------------------
- Job Config Vars -
----------------------------------------------
$job.env = dev/prd/qa
$job.name = myProject

------------------------------------------------------------------------------------------
- Command Line Interface -
----------------------------------------------
run like "jenkins" on the command line (name TBD)
$ alias jenkins="python ~/Development/BuildScripts/Jenkins/app.py"

- TODO: rethink for windows, they need a .cmd
- Can windows run .py yet if one exists in path?
- are paths customizations for the jenkins user going to be necessary?
- should be packaged into an installer? cross system? just windows?

$ jenkins clean
	- remove deploy folder

$ jenkins copy $src $destUri
	- src - the copy source
	- destUri - where this is going, can support a full URI scheme
	- note - only dest supports full URI

$ jenkins copy source deploy
$ jenkins copy deploy ftp://mydomain.com/www/ht_docs


+ jenkins fake-compile = jenkins copy $source $compiled

$ jenkins fake-compile
 	- options
		--source=source
		--deploy=compiled


+ jenkins build-assets $when $env = jenkins copy build-assets/$when/$env source
 	- $when - pre/post
 	- $env - can default to $job.env
	- options
		--root=source (default)

$ jenkins build-assets post
$ jenkins build-assets pre prd
$ jenkins build-assets pre dev --deploy=source/js


+ jenkins archive = jenkins copy deploy <archivePath>
	- notes
		- copy build somewhere with date/time
		- only keep X number of builds (options galore)

$ jenkins archive
	- options
		--label=$job.name

+ jenkins deploy = jenkins copy compiled <path>

$ jenkins deploy ftp://sourceserver/path/to/whatever
$ jenkins deploy sftp://sourceserver/path/to/whatever


$ jenkins minify $type $file
	- type, closure/sass/yui
	- file, some sort of config, or whatever it's gonna want
	- obviously module specific based on addon
