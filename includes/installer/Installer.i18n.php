<?php
/*
 * Internationalization file for the install/upgrade process. None of the
 * messages used here are loaded during normal operations, only during
 * install and upgrade. So you should not put normal messages here.
 */

$messages = array();

/**
 * English
 */
$messages['en'] = array(
	'config-desc'                     => 'The installer for MediaWiki',
	'config-title'                    => 'MediaWiki $1 installation',
	'config-information'              => 'Information',
	'config-localsettings-upgrade'    => "'''Warning''': A <code>LocalSettings.php</code> file has been detected.
Your software is able to upgrade.
Please move <code>LocalSettings.php</code> to somewhere safe and then run the installer again.",
	'config-localsettings-noupgrade'  => "'''Error''': A <code>LocalSettings.php</code> file has been detected.
Your software is not able to upgrade at this time.
The installer has been disabled for security reasons.",
	'config-session-error'            => 'Error starting session: $1',
	'config-session-expired'          => 'Your session data seems to have expired.
Sessions are configured for a lifetime of $1.
You can increase this by setting <code>session.gc_maxlifetime</code> in php.ini.
Restart the installation process.',
	'config-no-session'               => 'Your session data was lost!
Check your php.ini and make sure <code>session.save_path</code> is set to an appropriate directory.',
	'config-session-path-bad'         => 'Your <code>session.save_path</code> (<code>$1</code>) seems to be invalid or unwritable.',
	'config-show-help'                => 'Help',
	'config-hide-help'                => 'Hide help',
	'config-your-language'            => 'Your language:',
	'config-your-language-help'       => 'Select a language to use during the installation process.',
	'config-wiki-language'            => 'Wiki language:',
	'config-wiki-language-help'       => 'Select the language that the wiki will predominantly be written in.',
	'config-back'                     => '← Back',
	'config-continue'                 => 'Continue →',
	'config-page-language'            => 'Language',
	'config-page-welcome'             => 'Welcome to MediaWiki!',
	'config-page-dbconnect'           => 'Connect to database',
	'config-page-upgrade'             => 'Upgrade existing installation',
	'config-page-dbsettings'          => 'Database settings',
	'config-page-name'                => 'Name',
	'config-page-options'             => 'Options',
	'config-page-install'             => 'Install',
	'config-page-complete'            => 'Complete!',
	'config-page-restart'             => 'Restart installation',
	'config-page-readme'              => 'Read me',
	'config-page-releasenotes'        => 'Release notes',
	'config-page-copying'             => 'Copying',
	'config-page-upgradedoc'          => 'Upgrading',
	'config-help-restart'             => 'Do you want to clear all saved data that you have entered and restart the installation process?',
	'config-restart'                  => 'Yes, restart it',
	'config-welcome'                  => "=== Environmental checks ===
Basic checks are performed to see if this environment is suitable for MediaWiki installation.
You should provide the results of these checks if you need help during installation.",
	'config-copyright'                => "=== Copyright and Terms ===

$1

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but '''without any warranty'''; without even the implied warranty of '''merchantability''' or '''fitness for a particular purpose'''.
See the GNU General Public License for more details.

You should have received <doclink href=Copying>a copy of the GNU General Public License</doclink> along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. or [http://www.gnu.org/copyleft/gpl.html read it online].",
	'config-authors'                  => 'MediaWiki is Copyright © 2001-2010 by Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe and others.', // TODO: move this to core strings and use it in Special:Version, too?
	'config-sidebar'                  => "* [http://www.mediawiki.org MediaWiki home]
* [http://www.mediawiki.org/wiki/Help:Contents User's Guide]
* [http://www.mediawiki.org/wiki/Manual:Contents Administrator's Guide]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]",
	'config-env-good'                 => '<span class="success-message">The environment has been checked.
You can install MediaWiki.</span>', // FIXME: take span out of message.
	'config-env-bad'                  => 'The environment has been checked.
You cannot install MediaWiki.',
	'config-env-php'                  => 'PHP $1 is installed.',
	'config-env-latest-ok'            => 'You are installing the latest version of Mediawiki.',
	'config-env-latest-new'           => "'''Note:''' You are installing a development version of Mediawiki.",
	'config-env-latest-can-not-check' => "'''Note:''' The installer was unable to retrieve information about the latest MediaWiki release from [$1].",
	'config-env-latest-data-invalid'  => "'''Warning:''' When trying to check if this version was outdated invalid data was retrieved from [$1].",
	'config-env-latest-old'           => "'''Warning:''' You are installing an outdated version of Mediawiki.",
	'config-env-latest-help'          => 'You are installing version $1, but the latest version is $2.
You are advised to use the latest release, which can be downloaded from [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php'        => 'Using the slow PHP implementation for Unicode normalization.',
	'config-unicode-using-utf8'       => 'Using Brion Vibber\'s utf8_normalize.so for Unicode normalization.',
	'config-unicode-using-intl'       => 'Using the [http://pecl.php.net/intl intl PECL extension] for Unicode normalization.',
	'config-unicode-pure-php-warning' => "'''Warning''': The [http://pecl.php.net/intl intl PECL extension] is not available to handle Unicode normalization.
If you run a high-traffic site, you should read a little on [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode normalization].",
	'config-unicode-update-warning'   => "'''Warning''': The installed version of the Unicode normalization wrapper uses an older version of [http://site.icu-project.org/ the ICU project's] library.
You should [http://www.mediawiki.org/wiki/Unicode_normalization_considerations upgrade] if you are at all concerned about using Unicode.",
	'config-no-db'                    => 'Could not find a suitable database driver!',
	'config-no-db-help'               => 'You need to install a database driver for PHP.
The following database types are supported: $1.

If you are on shared hosting, ask your hosting provider to install a suitable database driver.
If you compiled PHP yourself, reconfigure it with a database client enabled, for example using <code>./configure --with-mysql</code>.
If you installed PHP from a Debian or Ubuntu package, then you also need install the php5-mysql module.',
	'config-have-db'                  => 'Found database drivers: $1.',
	'config-register-globals'         => "'''Warning: PHP's <code>[http://php.net/register_globals register_globals]</code> option is enabled.'''
'''Disable it if you can.'''
MediaWiki will work, but your server is exposed to potential security vulnerabilities.",
	'config-magic-quotes-runtime'     => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] is active!'''
This option corrupts data input unpredictably.
You cannot install or use MediaWiki unless this option is disabled.",
	'config-magic-quotes-sybase'      => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] is active!'''
This option corrupts data input unpredictably.
You cannot install or use MediaWiki unless this option is disabled.",
	'config-mbstring'                 => "'''Fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] is active!'''
This option causes errors and may corrupt data unpredictably.
You cannot install or use MediaWiki unless this option is disabled.",
	'config-ze1'                      => "'''Fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] is active!'''
This option causes horrible bugs with MediaWiki.
You cannot install or use MediaWiki unless this option is disabled.",
	'config-safe-mode'                => "'''Warning:''' PHP's [http://www.php.net/features.safe-mode safe mode] is active.
It may cause problems, particularly if using file uploads and <code>math</code> support.",
	'config-xml-good'                 => 'Have XML / Latin1-UTF-8 conversion support.',
	'config-xml-bad'                  => "PHP's XML module is missing.
MediaWiki requires functions in this module and will not work in this configuration.
If you're running Mandrake, install the php-xml package.",
	'config-pcre'                     => 'The PCRE support module appears to be missing.
MediaWiki requires the Perl-compatible regular expression functions to work.',
	'config-memory-none'              => 'PHP is configured with no <code>memory_limit</code>',
	'config-memory-ok'                => "PHP's <code>memory_limit</code> is $1.
OK.",
	'config-memory-raised'            => "PHP's <code>memory_limit</code> is $1, raised to $2.",
	'config-memory-bad'               => "'''Warning:''' PHP's <code>memory_limit</code> is $1.
This is probably too low.
The installation may fail!",
	'config-xcache'                   => '[http://trac.lighttpd.net/xcache/ XCache] is installed',
	'config-apc'                      => '[http://www.php.net/apc APC] is installed',
	'config-eaccel'                   => '[http://eaccelerator.sourceforge.net/ eAccelerator] is installed',
	'config-wincache'                 => '[http://www.iis.net/download/WinCacheForPhp WinCache] is installed',
	'config-no-cache'                 => "'''Warning:''' Could not find [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] or [http://www.iis.net/download/WinCacheForPhp WinCache].
Object caching is not enabled.",
	'config-diff3-good'               => 'Found GNU diff3: <code>$1</code>.',
	'config-diff3-bad'                => 'GNU diff3 not found.',
	'config-imagemagick'              => 'Found ImageMagick: <code>$1</code>.
Image thumbnailing will be enabled if you enable uploads.',
	'config-gd'                       => 'Found GD graphics library built-in.
Image thumbnailing will be enabled if you enable uploads.',
	'config-no-scaling'               => 'Could not find GD library or ImageMagick.
Image thumbnailing will be disabled.',
	'config-dir'                      => 'Installation directory: <code>$1</code>.',
	'config-uri'                      => 'Script URI path: <code>$1</code>.',
	'config-no-uri'                   => "'''Error:''' Could not determine the current URI.
Installation aborted.",
	'config-dir-not-writable-group'   => "'''Error:''' Cannot write config file.
Installation aborted.

The installer has determined the user your webserver is running as.
Make the <code><nowiki>config</nowiki></code> directory writable by it to continue.
On a Unix/Linux system:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup'   => "'''Error:''' Cannot write config file.
Installation aborted.

The user your webserver is running as could not be determined.
Make the <code><nowiki>config</nowiki></code> directory globally writable by it (and others!) to continue.
On a Unix/Linux system do:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension'           => 'Installing MediaWiki with <code>$1</code> file extensions.',
	'config-shell-locale'             => 'Detected shell locale "$1"',
	'config-uploads-safe'             => 'The default directory for uploads is safe from arbitrary scripts execution.',
	'config-uploads-not-safe'         => "'''Warning:''' Your default directory for uploads <code>$1</code> is vulnerable to arbitrary scripts execution.
Although MediaWiki checks all uploaded files for security threats, it is highly recommended to [http://www.mediawiki.org/wiki/Manual:Security#Upload_security close this security vulnerability] before enabling uploads.",
	'config-db-type'                  => 'Database type:',
	'config-db-host'                  => 'Database host:',
	'config-db-host-help'             => 'If your database server is on different server, enter the host name or IP address here.

If you are using shared web hosting, your hosting provider should give you the correct host name in their documentation.',
	'config-db-wiki-settings'         => 'Identify this wiki',
	'config-db-name'                  => 'Database name:',
	'config-db-name-help'             => 'Choose a name that identifies your wiki.
It should not contain spaces or hyphens.

If you are using shared web hosting, your hosting provider will either give you a specific database name to use, or lets you create databases via a control panel.',
	'config-db-install-account'       => 'User account for installation',
	'config-db-username'              => 'Database username:',
	'config-db-password'              => 'Database password:',
	'config-db-install-help'          => 'Enter the username and password that will be used to connect to the database during the installation process.',
	'config-db-account-lock'          => 'Use the same username and password during normal operation',
	'config-db-wiki-account'          => 'User account for normal operation',
	'config-db-wiki-help'             => 'Enter the username and password that will be used to connect to the database during normal wiki operation.
If the account does not exist, and the installation account has sufficient privileges, this user account will be created with the minimum privileges required to operate the wiki.',
	'config-db-prefix'                => 'Database table prefix:',
	'config-db-prefix-help'           => 'If you need to share one database between multiple wikis, or between MediaWiki and another web application, you may choose to add a prefix to all the table names to avoid conflicts.
Do not use spaces or hyphens.

This field is usually left empty.',
	'config-db-charset'               => 'Database character set',
	'config-charset-mysql5-binary'    => 'MySQL 4.1/5.0 binary',
	'config-charset-mysql5'           => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4'           => 'MySQL 4.0 backwards-compatible UTF-8',
	'config-charset-help'             => "'''WARNING:''' If you use '''backwards-compatible UTF-8''' on MySQL 4.1+, and subsequently back up the database with <code>mysqldump</code>, it may destroy all non-ASCII characters, irreversibly corrupting your backups!

In '''binary mode''', MediaWiki stores UTF-8 text to the database in binary fields.
This is more efficient than MySQL's UTF-8 mode, and allows you to use the full range of Unicode characters.
In '''UTF-8 mode''', MySQL will know what character set your data is in, and can present and convert it appropriately,
but it will not let you store characters above the [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-mysql-old'                => 'MySQL $1 or later is required, you have $2.',
	'config-db-port'                  => 'Database port:',
	'config-db-schema'                => 'Schema for MediaWiki',
	'config-db-ts2-schema'            => 'Schema for tsearch2',
	'config-db-schema-help'           => 'The above schemas are usually correct.
Only change them if you know you need to.',
	'config-sqlite-dir'               => 'SQLite data directory:',
	'config-sqlite-dir-help'          => "SQLite stores all data in a single file.

The directory you provide must be writable by the webserver during installation.

It should '''not''' be accessible via the web, this is why we're not putting it where your PHP files are.

The installer will write a <code>.htaccess</code> file along with it, but if that fails someone can gain access to your raw database.
That includes raw user data (e-mail addresses, hashed passwords) as well as deleted revisions and other restricted data on the wiki.

Consider putting the database somewhere altogether, for example in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql'               => 'MySQL',
	'config-type-postgres'            => 'PostgreSQL',
	'config-type-sqlite'              => 'SQLite',
	'config-type-oracle'              => 'Oracle',
	'config-header-mysql'             => 'MySQL settings',
	'config-header-postgres'          => 'PostgreSQL settings',
	'config-header-sqlite'            => 'SQLite settings',
	'config-header-oracle'            => 'Oracle settings',
	'config-invalid-db-type'          => 'Invalid database type',
	'config-missing-db-name'          => 'You must enter a value for "Database name"',
	'config-invalid-db-name'          => 'Invalid database name "$1".
It may only contain numbers, letters and underscores.',
	'config-invalid-db-prefix'        => 'Invalid database prefix "$1".
It may only contain numbers, letters and underscores.',
	'config-connection-error'         => '$1.

Check the host, username and password below and try again.',
	'config-invalid-schema'           => 'Invalid schema for MediaWiki "$1".
Use only letters, numbers and underscores.',
	'config-invalid-ts2schema'        => 'Invalid schema for tsearch2 "$1".
Use only letters, numbers and underscores.',
	'config-postgres-old'             => 'PostgreSQL $1 or later is required, you have $2.',
	'config-sqlite-name-help'         => 'Choose a name that identifies your wiki.
Do not use spaces or hyphens.
This will be used for the SQLite data file name.',
	'config-sqlite-parent-unwritable-group' => 'Cannot create the data directory <code><nowiki>$1</nowiki></code>, because the parent directory <code><nowiki>$2</nowiki></code> is not writable by the webserver.

The installer has determined the user your webserver is running as.
Make the <code><nowiki>$3</nowiki></code> directory writable by it to continue.
On a Unix/Linux system do:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Cannot create the data directory <code><nowiki>$1</nowiki></code>, because the parent directory <code><nowiki>$2</nowiki></code> is not writable by the webserver.

The installer could not determine the user your webserver is running as.
Make the <code><nowiki>$3</nowiki></code> directory globally writable by it (and others!) to continue.
On a Unix/Linux system do:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error'       => 'Error creating the data directory "$1".
Check the location and try again.',
	'config-sqlite-dir-unwritable'    => 'Unable to write to the directory "$1".
Change its permissions so that the webserver can write to it, and try again.',
	'config-sqlite-connection-error'  => '$1.

Check the data directory and database name below and try again.',
	'config-sqlite-readonly'          => 'The file <code>$1</code> is not writeable.',
	'config-sqlite-cant-create-db'    => 'Could not create database file <code>$1</code>.',
	'config-sqlite-fts3-downgrade'    => 'PHP is missing FTS3 support, downgrading tables',
	'config-sqlite-fts3-add'          => 'Adding FTS3 search capabilities',
	'config-sqlite-fts3-ok'           => 'Full text search table appears to be in order',
	'config-can-upgrade'              => "There are MediaWiki tables in this database.
To upgrade them to MediaWiki $1, click '''Continue'''.",
	'config-upgrade-done'             => "Upgrade complete.

You can now [$1 start using your wiki].

If you want to regenerate your <code>LocalSettings.php</code> file, click the button below.
This is '''not recommended''' unless you are having problems with your wiki.",
	'config-regenerate'               => 'Regenerate LocalSettings.php →',
	'config-show-table-status'        => 'SHOW TABLE STATUS query failed!',
	'config-unknown-collation'        => "'''Warning:''' Datbase is using unrecognised collation.",
	'config-db-web-account'           => 'Database account for web access',
	'config-db-web-help'              => 'Select the username and password that the web server will use to connect to the database server, during ordinary operation of the wiki.',
	'config-db-web-account-same'      => 'Use the same account as for installation',
	'config-db-web-create'            => 'Create the account if it does not already exist',
	'config-db-web-no-create-privs'   => 'The account you specified for installation does not have enough privileges to create an account.
The account you specify here must already exist.',
	'config-mysql-engine'             => 'Storage engine:',
	'config-mysql-innodb'             => 'InnoDB',
	'config-mysql-myisam'             => 'MyISAM',
	'config-mysql-engine-help'        => "'''InnoDB''' is almost always the best option, since it has good concurrency support.

'''MyISAM''' may be faster in single-user or read-only installations.
MyISAM databases tend to get corrupted more often than InnoDB databases.",
	'config-mysql-charset'            => 'Database character set:',
	'config-mysql-binary'             => 'Binary',
	'config-mysql-utf8'               => 'UTF-8',
	'config-mysql-charset-help'       => "In '''binary mode''', MediaWiki stores UTF-8 text to the database in binary fields.
This is more efficient than MySQL's UTF-8 mode, and allows you to use the full range of Unicode characters.

In '''UTF-8 mode''', MySQL will know what character set your data is in, and can present and convert it appropriately, but it will not let you store characters above the [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-site-name'                => 'Name of wiki:',
	'config-site-name-help'           => "This will appear in the title bar of the browser and in various other places.",
	'config-site-name-blank'          => 'Enter a site name.',
	'config-project-namespace'        => 'Project namespace:',
	'config-ns-generic'               => 'Project',
	'config-ns-site-name'             => 'Same as the wiki name: $1',
	'config-ns-other'                 => 'Other (specify)',
	'config-ns-other-default'         => 'MyWiki',
	'config-project-namespace-help'   => 'Following Wikipedia\'s example, many wikis keep their policy pages separate from their content pages, in a "\'\'\'project namespace\'\'\'".
All page titles in this namespace start with a certain prefix, which you can specify here.
Traditionally, this prefix is derived from the name of the wiki, but it cannot contain punctuation characters such as "#" or ":".',
	'config-ns-invalid'               => 'The specified namespace "<nowiki>$1</nowiki>" is invalid.
Specify a different project namespace.',
	'config-admin-box'                => 'Administrator account',
	'config-admin-name'               => 'Your name:',
	'config-admin-password'           => 'Password:',
	'config-admin-password-confirm'   => 'Password again:',
	'config-admin-help'               => 'Enter your preferred username here, for example "Joe Bloggs".
This is the name you will use to log in to the wiki.',
	'config-admin-name-blank'         => 'Enter an administrator username.',
	'config-admin-name-invalid'       => 'The specified username "<nowiki>$1</nowiki>" is invalid.
Specify a different username.',
	'config-admin-password-blank'     => 'Enter a password for the administrator account.',
	'config-admin-password-same'      => 'The password must not be the same as the username.',
	'config-admin-password-mismatch'  => 'The two passwords you entered do not match.',
	'config-admin-email'              => 'E-mail address:',
	'config-admin-email-help'         => 'Enter an e-mail address here to allow you to receive e-mail from other users on the wiki, reset your password, and be notified of changes to pages on your watchlist.',
	'config-admin-error-user'         => 'Internal error when creating an admin with the name "<nowiki>$1</nowiki>".',
	'config-admin-error-password'     => 'Internal error when setting a password for the admin "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe'                => 'Subscribe to the [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce release announcements mailing list].',
	'config-subscribe-help'           => 'This is a low-volume mailing list used for release announcements, including important security announcements.
You should subscribe to it and update your MediaWiki installation when new versions come out.',
	'config-almost-done'              => 'You are almost done!
You can now skip the remaining configuration and install the wiki right now.',
	'config-optional-continue'        => 'Ask me more questions.',
	'config-optional-skip'            => "I'm bored already, just install the wiki.",
	'config-profile'                  => 'User rights profile:',
	'config-profile-wiki'             => 'Traditional wiki',
	'config-profile-no-anon'          => 'Account creation required',
	'config-profile-fishbowl'         => 'Authorized editors only',
	'config-profile-private'          => 'Private wiki',
	'config-profile-help'             => "Wikis work best when you let as many people edit them as possible.
In MediaWiki, it is easy to review the recent changes, and to revert any damage that is done by naive or malicious users.

However, many have found MediaWiki to be useful in a wide variety of roles, and sometimes it is not easy to convince everyone of the benefits of the wiki way.
So you have the the choice.

A '''{{int:config-profile-wiki}}''' allows anyone to edit, without even logging in.
A wiki with '''{{int:config-profile-no-anon}}''' provides extra accountability, but may deter casual contributors.

The '''{{int:config-profile-fishbowl}}''' scenario allows approved users to edit, but the public can view the pages, including history.
A '''{{int:config-profile-private}}''' only allows approved users to view pages, with the same group allowed to edit.

More complex user rights configurations are available after installation, see the [http://www.mediawiki.org/wiki/Manual:User_rights relevant manual entry].",
	'config-license'                  => 'Copyright and license:',
	'config-license-none'             => 'No license footer',
	'config-license-cc-by-sa'         => 'Creative Commons Attribution Share Alike (Wikipedia-compatible)',
	'config-license-cc-by-nc-sa'      => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old'         => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current'     => 'GNU Free Documentation License 1.3 or later',
	'config-license-pd'               => 'Public Domain',
	'config-license-cc-choose'        => 'Select a custom Creative Commons license',
	'config-license-help'             => "Many public wikis put all contributions under a [http://freedomdefined.org/Definition free license].
This helps to create a sense of community ownership and encourages long-term contribution.
It is not generally necessary for a private or corporate wiki.

If you want to be able to use text from Wikipedia, and you want Wikipedia to be able to accept text copied from your wiki, you should choose '''Creative Commons Attribution Share Alike'''.

The GNU Free Documentation License was the old license Wikipedia was under.
It is still a valid license, however, this license has some features which make reuse and interpretation difficult.",
	'config-email-settings'           => 'E-mail settings',
	'config-enable-email'             => 'Enable outbound e-mail',
	'config-enable-email-help'        => "If you want e-mail to work, [http://www.php.net/manual/en/mail.configuration.php PHP's mail settings] need to be configured correctly.
If you do not want any e-mail features, you can disable them here.",
	'config-email-user'               => 'Enable user-to-user e-mail',
	'config-email-user-help'          => 'Allow all users to send each other e-mail if they have enabled it in their preferences.',
	'config-email-usertalk'           => 'Enable user talk page notification',
	'config-email-usertalk-help'      => 'Allow users to receive notifications on user talk page changes, if they have enabled it in their preferences.',
	'config-email-watchlist'          => 'Enable watchlist notification',
	'config-email-watchlist-help'     => 'Allow users to receive notifications about their watched pages if they have enabled it in their preferences.',
	'config-email-auth'               => 'Enable e-mail authentication',
	'config-email-auth-help'          => "If this option is enabled, users have to confirm their e-mail address using a link sent to them whenever they set or change it.
Only authenticated e-mail addresses can receive e-mails from other users or change notification e-mails.
Setting this option is '''recommended''' for public wikis because of potential abuse of the e-mail features.",
	'config-email-sender'             => 'Return e-mail address:',
	'config-email-sender-help'        => 'Enter the e-mail address to use as the return address on outbound e-mail.
This is where bounces will be sent.
Many mail servers require at least the domain name part to be valid.',
	'config-upload-settings'          => 'Images and file uploads',
	'config-upload-enable'            => 'Enable file uploads',
	'config-upload-help'              => "File uploads potentially expose your server to security risks.
For more information, read the [http://www.mediawiki.org/wiki/Manual:Security security section] in the manual.

To enable file uploads, change the mode on the <code>images</code> subdirectory under MediaWiki's root directory so that the web server can write to it.
Then enable this option.",
	'config-upload-deleted'           => 'Directory for deleted files:',
	'config-upload-deleted-help'      => 'Choose a directory in which to archive deleted files.
Ideally, this should not be accessible from the web.',
	'config-logo'                     => 'Logo URL:',
	'config-logo-help'                => "MediaWiki's default skin includes space for a 135x135 pixel logo in the top left corner.
Upload an image of the appropriate size, and enter the URL here.

If you do not want a logo, leave this box blank.",
	'config-instantcommons'           => 'Enable Instant Commons',
	'config-instantcommons-help'      => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] is a feature that allows wikis to use images, sounds and other media found on the [http://commons.wikimedia.org/ Wikimedia Commons] site.
In order to do this, MediaWiki requires access to the Internet. $1

For more information on this feature, including instructions on how to set it up for wikis other than the Wikimedia Commons, consult [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos the manual].', // $1 is for indicating whether or not we should be able to use the feature
	'config-instantcommons-good'      => 'The installer was able to detect internet connectivity during the environment checks.
You can enable this feature if you want to.',
	'config-instantcommons-bad'       => '\'\'Unfortunately, the installer was unable to detect internet connectivity during the environment checks, so you might be unable to use this feature.
If your server is behind a proxy, you may need to do some [http://www.mediawiki.org/wiki/Manual:$wgHTTPProxy additional configuration].\'\'',
	'config-cc-error'                 => 'The Creative Commons license chooser gave no result.
Enter the license name manually.',
	'config-cc-again'                 => 'Pick again...',
	'config-cc-not-chosen'            => 'Choose which Creative Commons license you want and click "proceed".',
	'config-advanced-settings'        => 'Advanced configuration',
	'config-cache-options'            => 'Settings for object caching:',
	'config-cache-help'               => 'Object caching is used to improve the speed of MediaWiki by caching frequently used data.
Medium to large sites are highly encouraged to enable this, and small sites will see benefits as well.',
	'config-cache-none'               => 'No caching (no functionality is removed, but speed may be impacted on larger wiki sites)',
	'config-cache-accel'              => 'PHP object caching (APC, eAccelerator, XCache or WinCache)',
	'config-cache-memcached'          => 'Use Memcached (requires additional setup and configuration)',
	'config-memcached-servers'        => 'Memcached servers:',
	'config-memcached-help'           => 'List of IP addresses to use for Memcached.
Should be separated with commas and specify the port to be used (for example: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions'               => 'Extensions',
	'config-extensions-help'          => 'The extensions listed above were detected in your <code>./extensions</code> directory.

They may require additional configuration, but you can enable them now',
	'config-install-alreadydone'      => "'''Warning:''' You seem to have already installed MediaWiki and are trying to install it again.
Please proceed to the next page.",
	'config-install-step-done'        => 'done',
	'config-install-step-failed'      => 'failed',
	'config-install-extensions'       => 'Including extensions',
	'config-install-database'         => 'Setting up database',
	'config-install-pg-schema-failed' => 'Tables creation failed.
Make sure that the user "$1" can write to the schema "$2".',
	'config-install-user'             => 'Creating database user',
	'config-install-user-failed'      => 'Granting permission to user "$1" failed: $2',
	'config-install-tables'           => 'Creating tables',
	'config-install-tables-exist'     => "'''Warning''': MediaWiki tables seem to already exist.
Skipping creation.",
	'config-install-tables-failed'    => "'''Error''': Table creation failed with the following error: $1",
	'config-install-interwiki'        => 'Populating default interwiki table',
	'config-install-interwiki-sql'    => 'Could not find file <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Warning''': Interwiki table seems to already have entires.
Skipping default list.",
	'config-install-secretkey'        => 'Generating secret key',
	'config-insecure-secretkey'       => "'''Warning:''' Unable to create secure <code>\$wgSecretKey</code>.
Consider changing it manually.",
	'config-install-sysop'            => 'Creating administrator user account',
	'config-install-done'             => "'''Congratulations!'''
You have successfully installed MediaWiki.

The installer has generated a <code>LocalSettings.php</code> file.
It contains all your configuration.

You will need to [$1 download] it and put it in the base of your wiki installation (the same directory as index.php).
'''Note''': If you do not do this now, this generated configuration file will not be available to you later if you exit the installation without downloading it.

When that has been done, you can '''[$2 enter your wiki]'''.", // $1 is the URL to LocalSettings download, $2 is link to wiki
);

/** Message documentation (Message documentation)
 * @author EugeneZelenko
 * @author Kghbln
 * @author Siebrand
 * @author Umherirrender
 */
$messages['qqq'] = array(
	'config-desc' => '{{desc}}',
	'config-title' => 'Parameters:
* $1 is the version of MediaWiki that is being installed.',
	'config-session-error' => 'Parameters:
* $1 is the error that was encountered with the session.',
	'config-session-expired' => 'Parameters:
* $1 is the configured session lifetime.',
	'config-session-path-bad' => 'Parameters:
* $1 is the configured <code>session.save_path</code>.',
	'config-show-help' => '{{Identical|Help}}',
	'config-back' => '{{Identical|Back}}',
	'config-continue' => '{{Identical|Continue}}',
	'config-page-language' => '{{Identical|Language}}',
	'config-page-name' => '{{Identical|Name}}',
	'config-page-options' => '{{Identical|Options}}',
	'config-page-install' => '{{Identical|Install}}',
	'config-restart' => 'Button text to confirm the installation procedure has to be restarted.',
	'config-env-php' => 'Parameters:
* $1 is the version of PHP that has been installed.',
	'config-env-latest-data-invalid' => 'Parameters:
* $1 is the URL from which invalid data was retrieved.',
	'config-env-latest-old' => 'Parameters:
* $1 is the version of MediaWiki being installed.
* $2 is the latest available stable MediaWiki version.',
	'config-no-db-help' => 'Parameters:
* $1 is comma separated list of supported database types by MediaWiki.',
	'config-have-db' => 'Parameters:
* $1 is comma separated list of database drivers found in the application environment.',
	'config-memory-ok' => 'Parameters:
* $1 is the configured <code>memory_limit</code>.',
	'config-memory-raised' => 'Parameters:
* $1 is the configured <code>memory_limit</code>.
* $2 is the value to which <code>memory_limit</code> was raised.',
	'config-memory-bad' => 'Parameters:
* $1 is the configured <code>memory_limit</code>.',
	'config-xcache' => 'Message indicates if this program is available',
	'config-apc' => 'Message indicates if this program is available',
	'config-eaccel' => 'Message indicates if this program is available',
	'config-wincache' => 'Message indicates if this program is available',
	'config-diff3-good' => 'Parameters:
* $1 is the path to diff3.',
	'config-dir' => 'Parameters:
* $1 is script URI path.',
	'config-shell-locale' => 'Parameters:
* $1 is the detected shell locale.',
	'config-show-table-status' => '{{doc-important|"SHOW TABLE STATUS" is a MySQL command. Do not translate this.}}',
	'config-instantcommons-help' => 'Parameters:
$1 is for indicating whether or not we should be able to use the feature.',
	'config-cc-not-chosen' => 'Should we use "int:key" here (and to which key)?',
	'config-install-user-failed' => 'Parameters:
* $1 is the username for which granting rights failed
* $2 is the error message',
	'config-install-done' => 'Parameters:
* $1 is the URL to LocalSettings download
* $2 is a link to the wiki.',
);

/** Belarusian (Taraškievica orthography) (Беларуская (тарашкевіца))
 * @author EugeneZelenko
 * @author Wizardist
 */
$messages['be-tarask'] = array(
	'config-desc' => 'Праграма ўсталяваньня MediaWiki',
	'config-title' => 'Усталяваньне MediaWiki $1',
	'config-information' => 'Інфармацыя',
	'config-localsettings-upgrade' => "'''Папярэджаньне''': знойдзены файл <code>LocalSettings.php</code>.
Магчыма абнавіць існуючую ўстаноўку.
Калі ласка, перамясьціце <code>LocalSettings.php</code> у іншае месца і запусьціце праграму ўсталяваньня зноў.",
	'config-localsettings-noupgrade' => "'''Памылка''': знойдзены файл <code>LocalSettings.php</code>.
Немагчыма абнавіць існуючую ўстаноўку.
Дзеля бясьпекі працэс ўсталяваньня быў перарваны.",
	'config-session-error' => 'Памылка стварэньня сэсіі: $1',
	'config-session-expired' => 'Скончыўся тэрмін дзеяньня зьвестак сэсіі.
Сэсія мае абмежаваны тэрмін у $1.
Вы можаце павялічыць яго, зьмяніўшы парамэтар <code>session.gc_maxlifetime</code> у php.ini.
Перазапусьціце праграму ўсталяваньня.',
	'config-no-session' => 'Зьвесткі сэсіі згубленыя!
Праверце php.ini і ўпэўніцеся, што ўстаноўлены слушны шлях у <code>session.save_path</code>.',
	'config-session-path-bad' => 'Шлях у <code>session.save_path</code> (<code>$1</code>) няслушны ці даступны толькі для чытаньня.',
	'config-show-help' => 'Дапамога',
	'config-hide-help' => 'Схаваць дапамогу',
	'config-your-language' => 'Вашая мова:',
	'config-your-language-help' => 'Выберыце мову для выкарыстаньня падчас усталяваньня.',
	'config-wiki-language' => 'Мова вікі:',
	'config-wiki-language-help' => 'Выберыце мову, на якой пераважна будзе пісацца зьмест у вікі.',
	'config-back' => '← Назад',
	'config-continue' => 'Далей →',
	'config-page-language' => 'Мова',
	'config-page-welcome' => 'Вітаем у MediaWiki!',
	'config-page-dbconnect' => 'Падключэньне да базы зьвестак',
	'config-page-upgrade' => 'Абнавіць існуючую ўстаноўку',
	'config-page-dbsettings' => 'Устаноўкі базы зьвестак',
	'config-page-name' => 'Назва',
	'config-page-options' => 'Устаноўкі',
	'config-page-install' => 'Усталяваць',
	'config-page-complete' => 'Зроблена!',
	'config-page-restart' => 'Пачаць усталяваньне зноў',
	'config-page-readme' => 'Дадатковыя зьвесткі',
	'config-page-releasenotes' => 'Заўвагі да выпуску',
	'config-page-copying' => 'Капіяваньне',
	'config-page-upgradedoc' => 'Абнаўленьне',
	'config-help-restart' => 'Ці жадаеце выдаліць усе ўведзеныя зьвесткі і пачаць працэс усталяваньня зноў?',
	'config-restart' => 'Так, пачаць зноў',
	'config-welcome' => '== Праверка асяродзьдзя ==
Праверка патрэбная для запэўніваньня, што гэтае асяродзьдзе слушнае для ўсталяваньня MediaWiki.
Вам патрэбна будзе падаць усе вынікі праверкі, калі спатрэбіцца дапамога падчас усталяваньня.',
	'config-sidebar' => '* [http://www.mediawiki.org Сайт MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Дапамога карыстальнікам]
* [http://www.mediawiki.org/wiki/Manual:Contents Дапамога адміністратарам]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => '<span class="success-message">Асяродзьдзе было праверанае.
Вы можаце ўсталёўваць MediaWiki.</span>',
	'config-env-bad' => 'Асяродзьдзе было праверанае.
Усталяваньне MediaWiki немагчымае.',
	'config-env-php' => 'Усталяваны PHP $1.',
	'config-env-latest-ok' => 'Вы ўсталёўваеце апошнюю вэрсію MediaWiki.',
	'config-env-latest-new' => "'''Заўвага:''' Вы ўсталёўваеце вэрсію MediaWiki для распрацоўшчыкаў.",
	'config-env-latest-can-not-check' => "'''Заўвага:''' праграма ўсталяваньня ня здолела атрымаць зьвесткі пра апошні выпуск MediaWiki з [$1].",
);

/** Breton (Brezhoneg)
 * @author Y-M D
 */
$messages['br'] = array(
	'config-desc' => 'Poellad staliañ MediaWIki',
	'config-title' => 'Staliadur MediaWiki $1',
	'config-information' => 'Titouroù',
	'config-show-help' => 'Skoazell',
	'config-hide-help' => 'Kuzhat ar skoazell',
	'config-your-language' => 'Ho yezh :',
	'config-your-language-help' => 'Dibabit ur yezh da implijout e-pad an argerzh staliañ.',
	'config-wiki-language' => 'Yezh ar wiki :',
	'config-continue' => "Kenderc'hel →",
	'config-page-language' => 'Yezh',
	'config-page-dbconnect' => "Kevreañ d'an diaz roadennoù",
	'config-page-name' => 'Anv',
);

/** German (Deutsch)
 * @author Kghbln
 */
$messages['de'] = array(
	'config-desc' => 'Das MediaWiki-Installationsprogramm',
	'config-title' => 'Installation von MediaWiki $1',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => "'''Warnung:''' Die Datei <code>LocalSettings.php</code> wurde gefunden.
Die vorhandene Installation kann aktualisiert werden.
Bitte verschiebe die Datei <code>LocalSettings.php</code> an einen sicheren Ort und führe dann das Installationsprogramm erneut aus.",
	'config-localsettings-noupgrade' => "'''Fehler''': Die Datei <code>LocalSettings.php</code> wurde gefunden.
Die vorhandene Installation kann momentan nicht aktualisiert werden.
Das Installationsprogramm wurde aus Sicherheitsgründen deaktiviert.",
	'config-session-error' => 'Fehler beim Starten der Sitzung: $1',
	'config-session-expired' => 'Die Sitzungsdaten scheinen abgelaufen zu sein.
Sitzungen sind für einen Zeitraum von $1 konfiguriert.
Dieser kann durch Anhebung des Parameters <code>session.gc_maxlifetime</code> in der Datei <code>php.ini</code> erhöht werden.
Den Installationsvorgang erneut starten.',
	'config-no-session' => 'Die Sitzungsdaten sind verloren gegangen!
Die Datei <code>php.ini</code> muss geprüft und es muss dabei sichergestellt werden, dass der Parameter <code>session.save_path</code> auf das richtige Verzeichnis verweist.',
	'config-session-path-bad' => 'Der Parameter <code>session.save_path</code> (<code>$1</code>) scheint ungültig zu sein oder das Verzeichnis ist nicht beschreibbar.',
	'config-show-help' => 'Hilfe',
	'config-hide-help' => 'Hilfe ausblenden',
	'config-your-language' => 'Deine Sprache:',
	'config-your-language-help' => 'Bitte die Sprache auswählen, die während des Installationsvorgangs verwendet werden soll.',
	'config-wiki-language' => 'Sprache des Wikis:',
	'config-wiki-language-help' => 'Bitte die Hauptbearbeitungssprache des Wikis auswählen',
	'config-back' => '← Zurück',
	'config-continue' => 'Weiter →',
	'config-page-language' => 'Sprache',
	'config-page-welcome' => 'Willkommen bei MediaWiki!',
	'config-page-dbconnect' => 'Mit der Datenbank verbinden',
	'config-page-upgrade' => 'Eine vorhandene Installation aktualisieren',
	'config-page-dbsettings' => 'Datenbankeinstellungen',
	'config-page-name' => 'Name',
	'config-page-options' => 'Optionen',
	'config-page-install' => 'Installieren',
	'config-page-complete' => 'Fertig!',
	'config-page-restart' => 'Installation erneut starten',
	'config-page-readme' => 'Lies mich',
	'config-page-releasenotes' => 'Veröffentlichungsinformationen',
	'config-page-copying' => 'Kopiere',
	'config-page-upgradedoc' => 'Aktualisiere',
	'config-help-restart' => 'Sollen alle angegebenen Daten gelöscht und der Installationsvorgang erneut gestartet werden?',
	'config-restart' => 'Ja, erneut starten',
	'config-welcome' => '=== Prüfung der Installationsumgebung ===
Basisprüfungen werden durchgeführt, um festzustellen, ob die Installationsumgebung für die Installation von MediaWiki geeignet ist.
Die Ergebnisse dieser Prüfung sollten angegeben werden, sofern während der Installation Hilfe benötigt und erfragt wird.',
	'config-authors' => 'MediaWiki – Copyright © 2001-2010 Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Kirche, Yuri Astrachan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe und andere.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Website von MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nutzeranleitung zu MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratorenanleitung zu MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:FAQ Häufige Fragen zu MediaWiki]',
	'config-env-good' => '<span class="success-message">Die Installationsumgebung wurde geprüft.
MediaWiki kann installiert werden.</span>',
	'config-env-bad' => 'Die Installationsumgebung wurde geprüft.
MediaWiki kann nicht installiert werden.',
	'config-env-php' => 'PHP $1 ist installiert.',
	'config-env-latest-ok' => 'Die neueste Version von MediaWiki wird installiert.',
	'config-env-latest-new' => "'''Hinweis:''' Eine Entwicklungsversion von MediaWiki wird installiert.",
	'config-env-latest-can-not-check' => "'''Hinweis:''' Das Installationsprogramm konnte keine Informationen zur neuesten Version von MediaWiki von [$1] abrufen.",
	'config-env-latest-data-invalid' => "'''Warnung:''' Während der Überprüfung, ob die vorliegende Version veraltet ist, wurden unbrauchbare Daten von [$1] abgerufen.",
	'config-env-latest-old' => "'''Warnung:''' Es wird eine veraltete Version von MediaWiki installiert.",
	'config-env-latest-help' => 'Die Version $1 wird installiert, wohingegen die neueste Version $2 ist.
Es wird empfohlen die neueste Version zu verwenden, die bei [http://www.mediawiki.org/wiki/Download/de mediawiki.org] heruntergeladen werden kann.',
	'config-unicode-using-php' => 'Zur Unicode-Normalisierung wird die langsame PHP-Implementierung eingesetzt.',
	'config-unicode-using-utf8' => 'Zur Unicode-Normalisierung wird Brion Vibbers <code>utf8_normalize.so</code> eingesetzt.',
	'config-unicode-using-intl' => 'Zur  Unicode-Normalisierung wird die [http://pecl.php.net/intl PECL-Erweiterung intl] eingesetzt.',
	'config-unicode-pure-php-warning' => "'''Warnung:''' Die [http://pecl.php.net/intl PECL-Erweiterung intl] ist für die Unicode-Normalisierung nicht verfügbar.
Sofern eine Website mit großer Benutzeranzahl betrieben wird, sollten weitere Informationen auf der Webseite [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-Normalisierung (en)] gelesen werden.",
	'config-unicode-update-warning' => "'''Warnung:''' Die installierte Version des Unicode-Normalisierungswrappers nutzt einer ältere Version der Bibliothek [http://site.icu-project.org/ des ICU-Projekts].
Diese sollte [http://www.mediawiki.org/wiki/Unicode_normalization_considerations aktualisiert] werden, sofern auf die Verwendung von Unicode Wert gelegt wird.",
	'config-no-db' => 'Es konnte kein adäquater Datenbanktreiber gefunden werden!',
	'config-no-db-help' => 'Es muss ein Datenbanktreiber für PHP installiert werden.
Die folgenden Datenbanksysteme werden unterstützt: $1

Sofern ein gemeinschaftlich genutzter Server für das Hosting verwendet wird, muss der Hoster gefragt werden einen adäquaten Datenbanktreiber zu installieren.
Sofern PHP selbst kompiliert wurde, muss es mit es neu konfiguriert werden, wobei der Datenbankclient zu aktivierten ist. Hierzu kann beispielsweise <code>./configure --with-mysql</code> ausgeführt werden.
Sofern PHP über die Paketverwaltung einer Debian- oder Ubuntu-Installation installiert wurde, muss das „php5-mysql“-Paket nachinstalliert werden.',
	'config-have-db' => 'Vorhandene Datenbanktreiber: $1.',
	'config-register-globals' => "'''Warnung: Der Parameter <code>[http://php.net/register_globals register_globals]</code> von PHP ist aktiviert.'''
'''Sie sollte deaktiviert werden, sofern dies möglich ist.'''
Die MediaWiki-Installation wird zwar laufen, wobei aber der Server für potentielle Sicherheitsprobleme anfällig ist.",
	'config-magic-quotes-runtime' => "'''Fatal: Der Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-magic-quotes-sybase' => "'''Fatal: Der Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-mbstring' => "'''Fatal: Der Parameter <code>[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]</code> von PHP ist aktiviert!'''
Diese Einstellung verursacht Fehler und führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-ze1' => "'''Fatal: Der Parameter <code>[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu großen Fehlern bei MediaWiki.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-safe-mode' => "'''Warnung:''' Der Funktion <code>[http://www.php.net/features.safe-mode Safe Mode]</code> von PHP ist aktiviert.
Dies kann zu Problemen führen, insbesondere wenn das Hochladen von Dateien möglich sein, bzw. der Auszeichner <code>math</code> genutzt werden soll.",
	'config-xml-good' => 'Die XML/Latin1-UTF-8 Umwandlung ist verfügbar.',
	'config-xml-bad' => 'Das XML-Modul von PHP fehlt.
MediaWiki benötigt Funktionen, die dieses Modul bereitstellt und wird in der bestehenden Konfiguration nicht funktionieren.
Sofern Mandriva genutzt wird, muss noch das „php-xml“-Paket installiert werden.',
	'config-memory-none' => 'PHP wurde ohne den Parameter <code>memory_limit</code> konfiguriert',
	'config-memory-ok' => 'Der PHP-Parameter <code>memory_limit</code> hat den Wert $1.
OK.',
	'config-memory-raised' => 'Der PHP-Parameter <code>memory_limit</code> betrug $1 und wurde auf $2 erhöht.',
	'config-memory-bad' => "'''Warnung:''' Der PHP-Parameter <code>memory_limit</code> beträgt $1.
Dieser Wert ist wahrscheinlich zu niedrig.
Die Installation Könnte daher fehlschlagen!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] ist installiert',
	'config-apc' => '[http://www.php.net/apc APC] ist installiert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] ist installiert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] ist installiert',
	'config-no-cache' => "'''Warnung:''' [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] oder [http://www.iis.net/download/WinCacheForPhp WinCache] konnten nicht gefunden werden.
Das Objektcaching ist daher nicht aktiviert.",
	'config-diff3-good' => 'GNU diff3 wurde gefunden: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 wurde nicht gefunden.',
	'config-imagemagick' => 'ImageMagick wurde gefunden: <code>$1</code>.
Miniaturansichten von Bildern werden möglich sein, sobald das Hochladen von Dateien aktiviert wurde.',
	'config-gd' => 'Die im System integrierte GD-Grafikbibliothek wurde gefunden.
Miniaturansichten von Bildern werden möglich sein, sobald das Hochladen von Dateien aktiviert wurde.',
	'config-no-scaling' => 'Weder die GD-Grafikbibliothek noch ImageMagick wurden gefunden.
Miniaturansichten von Bildern sind daher nicht möglich.',
	'config-dir' => 'Installationsverzeichnis: <code>$1</code>.',
	'config-uri' => 'Der URI-Pfad des Skripts: <code>$1</code>.',
	'config-no-uri' => "'''Fehler:''' Die aktuelle URL konnte nicht ermittelt werden.
Die Installation wurde daher abgebrochen.",
	'config-file-extension' => 'MediaWiki wurde mit den Dateierweiterungen <code>$1</code> installiert.',
	'config-uploads-safe' => 'Das Standardverzeichnis für hochgeladene Dateien ist von der willkürlichen Ausführung von Skripten geschützt.',
	'config-uploads-not-safe' => "''Warnung:''' Das Standardverzeichnis für hochgeladene Dateien <code>$1</code> ist für die willkürliche Ausführung von Skripten anfällig.
Obzwar MediaWiki die hochgeladenen Dateien auf Sicherheitsrisiken überprüft, wird dennoch dringend empfohlen diese [http://www.mediawiki.org/wiki/Manual:Security#Upload_security Sicherheitslücke] zu schließen, bevor das Hochladen von Dateien aktiviert wird.",
	'config-db-type' => 'Datenbanksystem:',
	'config-db-host' => 'Datenbankserver:',
	'config-db-host-help' => 'Sofern sich die Datenbank auf einem anderen Server befindet, ist der hier der Servername oder die entsprechende IP-Adresse anzugeben.

Sofern ein gemeinschaftlich genutzter Server verwendet wird, sollte der Hoster den zutreffenden Servernamen in seiner Dokumentation angegeben haben.',
	'config-db-wiki-settings' => 'Identifiziere dieses Wiki',
	'config-db-name' => 'Datenbankname:',
	'config-db-install-account' => 'Benutzerkonto für die Installation',
	'config-db-username' => 'Benutzername der Datenbank:',
	'config-db-password' => 'Kennwort der Datenbank:',
	'config-db-install-help' => 'Benutzername und Kennwort, die während der Installation für die Verbindung mit der Datenbank genutzt werden sollen, sind nun anzugeben.',
	'config-db-account-lock' => 'Der selbe Benutzernamen und das Kennwort müssen während des normalen Betriebs des Wikis verwendet werden.',
	'config-db-wiki-account' => 'Benutzerkonto für den normalen Betrieb',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-Einstellungen',
	'config-header-postgres' => 'PostgreSQL-Einstellungen',
	'config-header-sqlite' => 'SQLite-Einstellungen',
	'config-header-oracle' => 'Oracle-Einstellungen',
);

/** Spanish (Español)
 * @author Crazymadlover
 */
$messages['es'] = array(
	'config-show-help' => 'Ayuda',
	'config-hide-help' => 'Ocultar ayuda',
	'config-your-language' => 'Tu idioma:',
	'config-your-language-help' => 'Seleccionar un idioma a usar durante el proceso de instalación.',
	'config-wiki-language' => 'Idioma del wiki:',
	'config-wiki-language-help' => 'Seleccionar el idioma en el que el wiki será escrito predominantemente.',
	'config-back' => '← Atrás',
	'config-continue' => 'Continuar →',
	'config-page-language' => 'Idioma',
	'config-page-welcome' => 'Bienvenido a MediaWiki!',
	'config-page-dbconnect' => 'Conectar a la base de datos',
	'config-page-upgrade' => 'Actualizar instalación existente',
	'config-page-dbsettings' => 'Configuración de base de datos',
	'config-page-name' => 'Nombre',
	'config-page-options' => 'Opciones',
	'config-page-install' => 'Instalar',
	'config-page-complete' => 'Completo!',
	'config-page-restart' => 'Reiniciar instalación',
	'config-page-readme' => 'Léeme',
	'config-page-releasenotes' => 'Notas de la versión',
	'config-page-copying' => 'Copiando',
	'config-page-upgradedoc' => 'Actualizando',
);

/** Basque (Euskara)
 * @author An13sa
 */
$messages['eu'] = array(
	'config-desc' => 'MediaWiki instalatzailea',
	'config-title' => 'MediaWiki $1 instalazioa',
	'config-information' => 'Informazioa',
	'config-show-help' => 'Laguntza',
	'config-hide-help' => 'Laguntza ezkutatu',
	'config-your-language' => 'Zure hizkuntza:',
	'config-wiki-language' => 'Wiki hizkuntza:',
	'config-back' => '← Atzera',
	'config-continue' => 'Jarraitu →',
	'config-page-language' => 'Hizkuntza',
	'config-page-welcome' => 'Ongi etorri MediaWikira!',
	'config-page-dbconnect' => 'Datu-basera konektatu',
	'config-page-name' => 'Izena',
	'config-page-options' => 'Aukerak',
	'config-page-install' => 'Instalatu',
	'config-page-complete' => 'Bukatua!',
	'config-page-restart' => 'Instalazioa berriz hasi',
	'config-page-readme' => 'Irakur nazazu',
	'config-page-copying' => 'Kopiatzea',
	'config-page-upgradedoc' => 'Eguneratu',
	'config-restart' => 'Bai, berriz hasi',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki nagusia]
* [http://www.mediawiki.org/wiki/Help:Contents Erabiltzaileentzako Gida]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratzaileentzako Gida]
* [http://www.mediawiki.org/wiki/Manual:FAQ MEG]',
	'config-env-php' => 'PHP $1 instalatua.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] instalatua',
	'config-apc' => '[http://www.php.net/apc APC] instalatua',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] instalatua',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] instalatua',
	'config-diff3-bad' => 'GNU diff3 ez da aurkitu.',
	'config-db-type' => 'Datu-base mota:',
	'config-db-wiki-settings' => 'Wiki hau identifikatu',
	'config-db-name' => 'Datu-base izena:',
	'config-db-username' => 'Datu-base lankide izena:',
	'config-db-password' => 'Datu-base pasahitza:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL hobespenak',
	'config-header-postgres' => 'PostgreSQL hobespenak',
	'config-header-sqlite' => 'SQLite hobespenak',
	'config-header-oracle' => 'Oracle hobespenak',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Bitarra',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Wikiaren izena:',
	'config-ns-generic' => 'Proiektua',
	'config-ns-other' => 'Bestelakoa (zehaztu)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-name' => 'Zure izena:',
	'config-admin-password' => 'Pasahitza:',
	'config-admin-password-confirm' => 'Pasahitza berriz:',
	'config-admin-email' => 'E-posta helbidea:',
	'config-license' => 'Copyright eta lizentzia:',
	'config-license-pd' => 'Domeinu Askea',
	'config-email-settings' => 'E-posta hobespenak',
	'config-logo' => 'Logo URL:',
	'config-install-step-done' => 'egina',
);

/** French (Français)
 * @author Peter17
 */
$messages['fr'] = array(
	'config-desc' => 'Le programme d’installation de MediaWiki',
	'config-title' => 'Installation de MediaWiki $1',
	'config-information' => 'Informations',
	'config-localsettings-upgrade' => "'''Attention''' : Un fichier <code>LocalSettings.php</code> a été détecté.
Votre logiciel est capable de se mettre à jour.
Veuillez déplacer <code>LocalSettings.php</code> en lieu sûr puis relancer le programme d’installation.",
	'config-localsettings-noupgrade' => "'''Erreur''': Un fichier <code>LocalSettings.php</code> a été détecté.
Votre logiciel ne peut pas se mettre à jour actuellement.
Le programme d’installation a été désactivé pour des raisons de sécurité.",
	'config-show-help' => 'Aide',
	'config-hide-help' => 'Masquer l’aide',
	'config-your-language' => 'Votre langue :',
	'config-your-language-help' => "Sélectionnez la langue à utiliser pendant le processus d'installation.",
	'config-wiki-language' => 'Langue du wiki :',
	'config-wiki-language-help' => 'Sélectionner la langue dans laquelle le wiki sera principalement écrit.',
	'config-back' => '← Retour',
	'config-continue' => 'Continuer →',
	'config-page-language' => 'Langue',
	'config-page-welcome' => 'Bienvenue sur MediaWiki !',
	'config-page-dbconnect' => 'Se connecter à la base de données',
	'config-page-upgrade' => 'Mettre à jour l’installation existante',
	'config-page-dbsettings' => 'Paramètres de base de données',
	'config-page-name' => 'Nom',
	'config-page-options' => 'Options',
	'config-page-install' => 'Installer',
	'config-page-complete' => 'Terminé !',
	'config-page-restart' => 'Redémarrer l’installation',
	'config-page-readme' => 'Lisez-moi',
	'config-page-releasenotes' => 'Notes de version',
	'config-page-copying' => 'Copie',
	'config-page-upgradedoc' => 'Mise à jour',
	'config-memory-none' => 'PHP est configuré sans paramètre <code>memory_limit</code>',
	'config-memory-ok' => 'Le paramètre <code>memory_limit</code> de PHP est à $1.
OK.',
	'config-memory-raised' => 'Le paramètre <code>memory_limit</code> de PHP était à $1, porté à $2.',
	'config-memory-bad' => "'''Attention :''' Le paramètre <code>memory_limit</code> de PHP est à $1.
Cette valeur est probablement trop faible.
Il est possible que l’installation échoue !",
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binaire',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 rétrocompatible UTF-8',
	'config-db-port' => 'Port de la base de données :',
	'config-db-schema' => 'Schéma pour MediaWiki',
	'config-db-ts2-schema' => 'Schéma pour tsearch2',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Paramètres de MySQL',
	'config-header-postgres' => 'Paramètres de PostgreSQL',
	'config-header-sqlite' => 'Paramètres de SQLite',
	'config-header-oracle' => 'Paramètres d’Oracle',
	'config-invalid-db-type' => 'Type de base de données non valide',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Binaire',
	'config-mysql-utf8' => 'UTF-8',
	'config-admin-box' => 'Compte administrateur',
	'config-admin-name' => 'Votre nom :',
	'config-admin-password' => 'Mot de passe :',
	'config-admin-password-confirm' => 'Re-saisir le mot de passe :',
	'config-admin-email' => 'Adresse électronique :',
	'config-install-step-done' => 'fait',
	'config-install-step-failed' => 'échec',
);

/** Upper Sorbian (Hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'config-desc' => 'Instalaciski program za MediaWiki',
	'config-title' => 'Instalacija MediaWiki $1',
	'config-information' => 'Informacije',
	'config-session-error' => 'Zmylk při startowanju posedźenja: $1',
	'config-show-help' => 'Pomoc',
	'config-hide-help' => 'Pomoc schować',
	'config-your-language' => 'Twoja rěč:',
	'config-your-language-help' => 'Wubjer rěč, kotraž ma so za instalaciski proces wužiwać.',
	'config-wiki-language' => 'Wikirěč:',
	'config-wiki-language-help' => 'Wubjer rěč, w kotrejž wiki ma so zwjetša pisać.',
	'config-back' => '← Wróćo',
	'config-continue' => 'Dale →',
	'config-page-language' => 'Rěč',
	'config-page-welcome' => 'Witaj do MediaWiki!',
	'config-page-dbconnect' => 'Z datowej banku zwjazać',
	'config-page-upgrade' => 'Eksistowacu instalaciju aktualizować',
	'config-page-dbsettings' => 'Nastajenja datoweje banki',
	'config-page-name' => 'Mjeno',
	'config-page-options' => 'Opcije',
	'config-page-install' => 'Instalować',
	'config-page-complete' => 'Dokónčeny!',
	'config-page-restart' => 'Instalaciju znowa startować',
	'config-page-readme' => 'Čitaj mje',
	'config-page-releasenotes' => 'Wersijowe informacije',
	'config-page-copying' => 'Kopěrowanje',
	'config-page-upgradedoc' => 'Aktualizowanje',
	'config-restart' => 'Haj, znowa startować',
	'config-env-good' => '<span class="success-message">Wokolina je so skontrolowała.
Móžeš MediaWiki instalować.</span>',
	'config-env-bad' => 'Wokolina je so skontrolowała.
Njemóžeš MediaWiki instalować.',
	'config-env-php' => 'PHP $1 instalowany.',
	'config-env-latest-ok' => 'Instaluješ najnowšu wersiju MediaWiki.',
	'config-env-latest-new' => "'''Kedźbu:''' Instaluješ wuwiwansku wersiju MediaWiki.",
	'config-have-db' => 'Namakane ćěrjaki datoweje banki: $1.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] instalowany',
	'config-apc' => '[http://www.php.net/apc APC] instalowany',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] instalowany',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] instalowany',
	'config-diff3-good' => 'GNU diff3 namakany: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 njenamakany.',
	'config-dir' => 'Instalaciski zapis: <code>$1</code>.',
	'config-uri' => 'Šćežka URI skripta: <code>$1</code>.',
	'config-file-extension' => 'MediaWiki so z <code>$1</code> datajowymi rozšěrjenjemi instaluje.',
	'config-db-type' => 'Typ datoweje banki:',
	'config-db-host' => 'Serwer datoweje banki:',
	'config-db-wiki-settings' => 'Tutón wiki identifikować',
	'config-db-name' => 'Mjeno datoweje banki:',
	'config-db-install-account' => 'Wužiwarske konto za instalaciju',
	'config-db-username' => 'Wužiwarske mjeno datoweje banki:',
	'config-db-password' => 'Hesło datoweje banki:',
	'config-db-account-lock' => 'Samsne wužiwarske mjeno a hesło za normalnu operaciju wužiwać',
	'config-db-wiki-account' => 'Wužiwarske konto za normalnu operaciju',
	'config-db-prefix' => 'Tabelowy prefiks datoweje banki:',
	'config-db-charset' => 'Znamješkowa sadźba datoweje banki',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binarny',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 wróćokompatibelny UTF-8',
	'config-mysql-old' => 'MySQL $1 abo nowši trěbny, maš $2.',
	'config-db-port' => 'Port datoweje banki:',
	'config-db-schema' => 'Šema za MediaWiki',
	'config-db-ts2-schema' => 'Šema za tsearch2',
	'config-sqlite-dir' => 'Zapis SQLite-datow:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Nastajenja MySQL',
	'config-header-postgres' => 'Nastajenja PostgreSQL',
	'config-header-sqlite' => 'Nastajenja SQLite',
	'config-header-oracle' => 'Nastajenja Oracle',
	'config-invalid-db-type' => 'Njepłaćiwy typ datoweje banki',
	'config-missing-db-name' => 'Dyrbiš hódnotu za "Mjeno datoweje banki" zapodać',
	'config-invalid-db-name' => 'Njepłaćiwe mjeno "$1" datoweje banki.
Smě jenož ličby, pismiki a podsmužki wobsahować.',
	'config-invalid-db-prefix' => 'Njepłaćiwy prefiks "$1" datoweje banki.
Smě jenož ličby, pismiki a podsmužki wobsahować.',
	'config-connection-error' => '$1.

Skontroluj serwer, wužiwarske a hesło a spytaj hišće raz.',
	'config-invalid-schema' => 'Njepłaćiwe šema za MediaWiki "$1".
Wužij jenož pismiki, ličby a podsmužki.',
	'config-invalid-ts2schema' => 'Njepłaćiwe šema za tsearch2 "$1".
Wužij jenož pismiki, ličby a podsmužki.',
	'config-postgres-old' => 'PostgreSQL $1 abo nowši trěbny, maš $2.',
	'config-sqlite-connection-error' => '$1.

Skontroluj datowy zapis a mjeno datoweje banki kaj spytaj hišće raz.',
	'config-sqlite-readonly' => 'Do dataje <code>$1</code> njeda so pisać.',
	'config-sqlite-cant-create-db' => 'Dataja <code>$1</code> datoweje banki njeda so wutworić.',
	'config-db-web-account' => 'Konto datoweje banki za webpřistup',
	'config-db-web-account-same' => 'Samsne konto kaž za instalaciju wužiwać',
	'config-db-web-create' => 'Załož konto, jeli hišće njeeksistuje.',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Znamješkowa sadźba datoweje banki:',
	'config-mysql-binary' => 'Binarny',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Mjeno wikija:',
	'config-site-name-blank' => 'Zapodaj sydłowe mjeno.',
	'config-project-namespace' => 'Mjenowy rum projekta:',
	'config-ns-generic' => 'Projekt',
	'config-ns-site-name' => 'Samsne kaž wikimjeno: $1',
	'config-ns-other' => 'Druhe (podać)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Administratorowe konto',
	'config-admin-name' => 'Twoje mjeno:',
	'config-admin-password' => 'Hesło:',
	'config-admin-password-confirm' => 'Hesło wospjetować:',
	'config-admin-name-blank' => 'Zapodaj administratorowe wužiwarske mjeno.',
	'config-admin-password-blank' => 'Zapodaj hesło za administratorowe konto.',
	'config-admin-password-same' => 'Hesło dyrbi so wot wužiwarskeho mjena rozeznać.',
	'config-admin-password-mismatch' => 'Wobě hesle, kotrejž sy zapodał, njejstej jenakej.',
	'config-admin-email' => 'E-mejlowa adresa:',
	'config-profile' => 'Profil wužiwarskich prawow:',
	'config-profile-wiki' => 'Tradicionelny wiki',
	'config-profile-fishbowl' => 'Jenož awtorizowani wobdźěłarjo',
	'config-profile-private' => 'Priwatny wiki',
	'config-license' => 'Awtorske prawo a licenca:',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 abo nowša',
	'config-license-pd' => 'Powšitkownosći přistupny',
	'config-license-cc-choose' => 'Swójsku licencu Creative Commons wubrać',
	'config-email-settings' => 'E-mejlowe nastajenja',
	'config-email-user' => 'E-mejl mjez wužiwarjemi zmóžnić',
	'config-email-auth' => 'E-mejlowu awtentifikaciju zmóžnić',
	'config-upload-settings' => 'Wobrazy a nahraća datajow',
	'config-upload-enable' => 'Nahraće datajow zmóžnić',
	'config-upload-deleted' => 'Zapis za zhašane dataje:',
	'config-logo' => 'URL loga:',
	'config-advanced-settings' => 'Rozšěrjena konfiguraćija',
	'config-extensions' => 'Rozšěrjenja',
	'config-install-step-done' => 'dokónčene',
	'config-install-step-failed' => 'njeporadźiło',
	'config-install-database' => 'Datowa banka so připrawja',
	'config-install-user' => 'Tworjenje wužiwarja datoweje banki',
	'config-install-tables' => 'Tworjenje tabelow',
	'config-install-secretkey' => 'Tworjenje tajneho kluča',
	'config-install-sysop' => 'Tworjenje administratoroweho wužiwarskeho konta',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'config-admin-password' => 'Okwúngáfè:',
	'config-admin-password-confirm' => 'Okwúngáfè mgbe ozor:',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'config-desc' => 'Den Installatiounsprogramm vu MediaWiki',
	'config-title' => 'MediaWiki $1 Installatioun',
	'config-information' => 'Informatioun',
	'config-session-error' => 'Feeler beim Starte vun der Sessioun: $1',
	'config-show-help' => 'Hëllef',
	'config-hide-help' => 'Hëllef verstoppen',
	'config-your-language' => 'Är Sprooch',
	'config-your-language-help' => 'Sicht déi Sprooch eraus déi Dir während der Installatioun benotze wëllt',
	'config-wiki-language' => 'Sprooch vun der Wiki:',
	'config-wiki-language-help' => "Sicht d'Sprooch eraus an där d'Wiki haaptsächlech geschriwwe gëtt.",
	'config-back' => '← Zréck',
	'config-continue' => 'Weider →',
	'config-page-language' => 'Sprooch',
	'config-page-welcome' => 'Wëllkomm bäi MediaWiki!',
	'config-page-dbconnect' => 'Mat der Datebank verbannen',
	'config-page-upgrade' => 'Eng Installatioun déi besteet aktualiséieren',
	'config-page-dbsettings' => 'Astellunge vun der Datebank',
	'config-page-name' => 'Numm',
	'config-page-options' => 'Optiounen',
	'config-page-install' => 'Installéieren',
	'config-page-complete' => 'Fäerdeg!',
	'config-page-restart' => 'Installatioun neistarten',
	'config-page-readme' => 'Liest dëst',
	'config-page-releasenotes' => 'Informatiounen zur Versioun',
	'config-page-copying' => 'Kopéieren',
	'config-page-upgradedoc' => 'Aktualiséieren',
	'config-restart' => 'Jo, neistarten',
	'config-authors' => 'MediaWiki – Copyright © 2001-2010 Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Kirche, Yuri Astrachan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe an anerer.',
	'config-env-php' => 'PHP $1 ass installéiert.',
	'config-env-latest-ok' => 'Dir installéiert déi rezenst Versioun vu MediaWiki.',
	'config-env-latest-new' => "'''Hiweis:''' Dir installéiert eng Entwécklungsversioun vu MediaWiki.",
	'config-env-latest-old' => "'''Warnung:''' Dir installéiert eng vereelste Versioun vu MediaWiki.",
	'config-apc' => '[http://www.php.net/apc APC] ass installéiert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] ass installéiert',
	'config-db-type' => 'Datebanktyp:',
	'config-db-wiki-settings' => 'Dës Wiki identifizéieren',
	'config-db-name' => 'Numm vun der Datebank:',
	'config-db-install-account' => "Benotzerkont fir d'Installatioun",
	'config-db-username' => 'Datebank-Benotzernumm:',
	'config-db-password' => 'Passwuert vun der Datebank:',
	'config-db-install-help' => 'Gitt de Benotzernumm an Passwuert an dat wàhrend der Installatioun benotzt gëtt fir sech mat der Datebank ze verbannen.',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binair',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-db-port' => 'Port vun der Datebank:',
	'config-db-schema' => 'Schema fir MediaWiki',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-Astellungen',
	'config-header-postgres' => 'PostgreSQL-Astellungen',
	'config-header-sqlite' => 'SQLite-Astellungen',
	'config-header-oracle' => 'Oracle-Astellungen',
	'config-missing-db-name' => 'Dir musst en Numm fir de Wäert "Numm vun der Datebank" uginn',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Numm vun der Wiki:',
	'config-project-namespace' => 'Projet Nummraum:',
	'config-ns-generic' => 'Projet',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-name' => 'Ären Numm:',
	'config-admin-password' => 'Passwuert:',
	'config-admin-password-confirm' => 'Passwuert confirméieren:',
	'config-admin-email' => 'E-Mailadress:',
	'config-profile-private' => 'Privat Wiki',
	'config-advanced-settings' => 'Erweidert Astellungen',
	'config-extensions' => 'Erweiderungen',
	'config-install-step-done' => 'fäerdeg',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'config-desc' => 'Инсталатор на МедијаВики',
	'config-title' => 'Инсталатор на МедијаВики $1',
	'config-information' => 'Информации',
	'config-localsettings-upgrade' => "'''Предупредување''': Востановена е податотека <code>LocalSettings.php</code>.
Вашиот програм може да се надградува.
Преместете го <code>LocalSettings.php</code> на некое безбедно место и пуштете ја инсталацијата повторно.",
	'config-localsettings-noupgrade' => "'''Грешка''': Востановена е податотека <code>LocalSettings.php</code>.
Вашиот програм засега не може да се надгради.
Инсталаторот е оневозможен од безбедносни причини.",
	'config-session-error' => 'Грешка при започнување на сесијата: $1',
	'config-session-expired' => 'Вашите сесиски податоци истекоа.
Поставките на сесиите траат $1.
Нивниот рок можете да го зголемите со задавање на <code>session.gc_maxlifetime</code> во php.ini.
Почнете ја инсталацијата одново.',
	'config-no-session' => 'Вашите сесиски податоци се изгубени!
Погледајте во php.ini дали <code>session.save_path</code> е поставен во правилна папка.',
	'config-session-path-bad' => 'Вашиот <code>session.save_path</code> (<code>$1</code>) е неважечки или незапислив.',
	'config-show-help' => 'Помош',
	'config-hide-help' => 'Сокриј помош',
	'config-your-language' => 'Вашиот јазик:',
	'config-your-language-help' => 'Одберете на кој јазик да се одвива инсталацијата.',
	'config-wiki-language' => 'Јазик на викито:',
	'config-wiki-language-help' => 'Одберете на кој јазик ќе бидат содржините на викито.',
	'config-back' => '← Назад',
	'config-continue' => 'Продолжи →',
	'config-page-language' => 'Јазик',
	'config-page-welcome' => 'Добредојдовте на МедијаВики!',
	'config-page-dbconnect' => 'Поврзување со базата',
	'config-page-upgrade' => 'Надградба на постоечката инсталација',
	'config-page-dbsettings' => 'Нагодувања на базата',
	'config-page-name' => 'Назив',
	'config-page-options' => 'Поставки',
	'config-page-install' => 'Инсталирај',
	'config-page-complete' => 'Готово!',
	'config-page-restart' => 'Пушти ја инсталацијата одново',
	'config-page-readme' => 'Прочитај ме',
	'config-page-releasenotes' => 'Белешки за изданието',
	'config-page-copying' => 'Копирање',
	'config-page-upgradedoc' => 'Надградба',
	'config-help-restart' => 'Дали сакате да ги исчистите сите зачувани податоци што ги внесовте и да ја започнете инсталацијата одново?',
	'config-restart' => 'Да, почни одново',
	'config-welcome' => '=== Environmental checks ===
Се вршат основни проверки за да се востанови дали околината е погодна за инсталирање на МедијаВики.
Ако ви затреба помош при инсталацијата, ќе треба да ги наведете резултатите од овие проверки.',
	'config-copyright' => "=== Авторски права и услови ===

$1

Ова е слободна програмска опрема (free software); можете да го редистрибуирате и/или менувате согласно условите на ГНУ-овата општа јавна лиценца (GNU General Public License) на Фондацијата за слободна програмска опрема (Free Software Foundation); верзија 2 или било која понова верзија на лиценцата (по ваш избор).

Овој програм се нуди со надеж дека ќе биде корисен, но '''без никаква гаранција'''; дури ни подразбраната гаранција за '''продажна способност''' или '''погодност за определена цел'''.
Повеќе информации ќе најдете во текстот на ГНУ-овата општа јавна лиценца.

Би требало да имате добиено <doclink href=Copying>примерок од ГНУ-овата општа јавна лиценца</doclink> заедно со програмов; ако немате добиено, тогаш пишете ни на Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. или [http://www.gnu.org/copyleft/gpl.html прочитајте ја тука].",
	'config-authors' => 'МедијаВики (MediaWiki) е под Авторски права © 2001-2010 на Магнус Манске (Magnus Manske), Брајон Вибер (Brion Vibber), Ли Даниел Крокер (Lee Daniel Crocker), Тим Старлинг (Tim Starling), Ерик Мелер (Erik Möller), Габриел Вике (Gabriel Wicke), Ајвар Арнфјерд Бјармасон (Ævar Arnfjörð Bjarmason), Никлас Лакстрем (Niklas Laxström), Домас Митузас (Domas Mituzas), Роб Черч (Rob Church), Јуриј Астрахан (Yuri Astrakhan), Арје Грегор (Aryeh Gregor), Арон Шулц (Aaron Schulz), Ендрју Гарет (Andrew Garrett), Рајмонд Спекинг (Raimond Spekking), Александар Емзенхубер (Alexandre Emsenhuber), Сибранд Мазеланд (Siebrand Mazeland), Чед Хороху (Chad Horohoe) и други.',
	'config-sidebar' => '* [http://www.mediawiki.org Домашна страница на МедијаВики]
* [http://www.mediawiki.org/wiki/Help:Contents Водич закорисници]
* [http://www.mediawiki.org/wiki/Manual:Contents Водич за администратори]
* [http://www.mediawiki.org/wiki/Manual:FAQ ЧПП]',
	'config-env-good' => '<span class="success-message">Околината е проверена.
Можете да го инсталирате МедијаВики.</span>',
	'config-env-bad' => 'Околината е проверена.
Не можете да го инсталирате МедијаВики.',
	'config-env-php' => 'PHP $1 е инсталиран.',
	'config-env-latest-ok' => 'Ја инсталирате најновата верзија на МедијаВики.',
	'config-env-latest-new' => "'''Напомена:''' Инсталирате развојна верзија на МедијаВики.",
	'config-env-latest-can-not-check' => "'''Напомена:''' Инсталаторот не можеше да добие информации за најновото издание на МедијаВики од [$1].",
	'config-env-latest-data-invalid' => "'''Предупредување:''' Добив неважечки податоци од [$1] при обидот да проверам дали оваа верзија е застарена.",
	'config-env-latest-old' => "'''Предупредување:''' Инсталирате застарена верзија на МедијаВики.",
	'config-env-latest-help' => 'Ја инсталирате верзијата $1, но најнова е верзијата $2.
Ве советуваме да ја користите најновата верзија, која можете да ја преземете на [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Со бавното воспоставување на PHP за уникодна нормализација.',
	'config-unicode-using-utf8' => 'Со utf8_normalize.so за уникодна нормализација од Брајон Вибер (Brion Vibber).',
	'config-unicode-using-intl' => 'Со додатокот [http://pecl.php.net/intl intl PECL] за уникодна нормализација.',
	'config-unicode-pure-php-warning' => "'''Предупредување''': Додатокот [http://pecl.php.net/intl intl PECL] не е достапен за врши уникодна нормализација.
Ако имате високопрометно мрежно место, тогаш ќе треба да прочитате повеќе за [http://www.mediawiki.org/wiki/Unicode_normalization_considerations уникодната нормализација].",
	'config-unicode-update-warning' => "'''Предупредување''': Инсталираната верзија на обвивката за уникодна нормализација користи постара верзија на библиотеката на [http://site.icu-project.org/ проектот ICU].
За да користите Уникод, ќе треба да направите [http://www.mediawiki.org/wiki/Unicode_normalization_considerations надградба].",
	'config-no-db' => 'Не можев да пронајдам соодветен двигател за базата на податоци!',
	'config-no-db-help' => 'Ќе треба да инсталирате двигател за базата на податоци за PHP.
Поддржани се следниве типови на бази: $1.

Ако сте на заедничко (споделено) вдомување, побарајте му на вдомителот да инсталира соодветен двигател за базата.
Ако вие самите го составивте ова PHP, сменете ги поставките така што ќе овозможите клиент на базата - на пр. со кодот <code>./configure --with-mysql</code>.
Ако инсталиравте PHP од пакет на Debian или Ubuntu, тогаш ќе треба да го инсталирате и модулот php5-mysql.',
	'config-have-db' => 'Пронајдени двигатели за базата: $1.',
	'config-register-globals' => "'''Предупредување: Можноста <code>[http://php.net/register_globals register_globals]</code> за PHP е овозможена.'''
'''Оневозможете ја ако е можно.'''
МедијаВики ќе работи, но опслужувачот ви е изложен на безбедносни ризици.",
	'config-magic-quotes-runtime' => "'''Кобно: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] е активно!'''
Оваа можност непредвидливо го расипува вносот на податоци.
Оваа можност мора да е исклучена. Во спротивно нема да можете да го инсталирате и користите МедијаВики.",
	'config-magic-quotes-sybase' => "'''Кобно: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] е активно!'''
Оваа можност непредвидливо го расипува вносот на податоци.
Оваа можност мора да е исклучена. Во спротивно нема да можете да го инсталирате и користите МедијаВики.",
	'config-mbstring' => "'''Кобно: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] е активно!'''
Оваа можност предизвикува грешки и може непредвидиво да го расипува вносот на податоци.
Оваа можност мора да е исклучена. Во спротивно нема да можете да го инсталирате и користите МедијаВики.",
	'config-ze1' => "'''Кобно: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] е активно!'''
Оваа можност предизвикува ужасни грешки во МедијаВики.
Оваа можност мора да е исклучена. Во спротивно нема да можете да го инсталирате и користите МедијаВики.",
	'config-safe-mode' => "'''Предупредување:''' [http://www.php.net/features.safe-mode безбедниот режим] на PHP е активен.
Ова може да предизвика проблеми, особено ако користите подигања и поддршка за <code>math</code>.",
	'config-xml-good' => 'Поддршка за претворање на XML / Latin1-UTF-8.',
	'config-xml-bad' => 'XML-модулот за PHP недостасува.
МедијаВики има потреба од функции во овој модул и нема да работи со овие поставки.
Ако работите со Mandrake, инсталирајте го php-xml пакетот.',
	'config-pcre' => 'Недостасува модулот за поддршка на PCRE.
МедијаВики не може да работи без функции за регуларни изрази соодветни на Perl.',
	'config-memory-none' => 'PHP е поставен без <code>memory_limit</code>',
	'config-memory-ok' => '<code>memory_limit</code> за PHP изнесува $1.
ОК.',
	'config-memory-raised' => '<code>memory_limit</code> за PHP изнесува $1, зголемен на $2.',
	'config-memory-bad' => "'''Предупредување:''' <code>memory_limit</code> за PHP изнесува $1.
Ова е веројатно премалку.
Инсталацијата може да не успее!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] е инсталиран',
	'config-apc' => '[http://www.php.net/apc APC] е инсталиран',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] е инсталиран',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] е инсталиран',
	'config-no-cache' => "'''Предупредување:''' Не можев да го најдам [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] или [http://www.iis.net/download/WinCacheForPhp WinCache].
Кеширањето на објекти не е овозможено.",
	'config-diff3-good' => 'Пронајден е GNU diff3: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 не е пронајден.',
	'config-imagemagick' => 'Пронајден е ImageMagick: <code>$1</code>.
Ако овозможите подигање, тогаш ќе биде овозможена минијатуризација на сликите.',
	'config-gd' => 'Утврдив дека има вградена GD графичка библиотека.
Ако овозможите подигање, тогаш ќе биде овозможена минијатураизација на сликите.',
	'config-no-scaling' => 'Не можев да пронајдам GD-библиотека или ImageMagick.
Минијатуризацијата на сликите ќе биде оневозможена.',
	'config-dir' => 'Инсталациона папка: <code>$1</code>.',
	'config-uri' => 'URI-патека на скриптата: <code>$1</code>.',
	'config-no-uri' => "'''Грешка:''' Не можев да го утврдам тековниот URI.
Инсталацијата е откажана.",
	'config-dir-not-writable-group' => "'''Грешка:''' Не можам да запишам во податотеката за поставки (config).
Инсталацијата е откажана.

Инсталаторот го утврди корисникот под кој работи вашиот мрежен опслужувач.
Наместете да може да запишува во папката <code><nowiki>config</nowiki></code>.
На Unix/Linux систем:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Грешка:''' е можам да запишам во податотеката за поставки (config).
Инсталацијата е откажана.

Не може да се утврди корисникот под кој работи вашиот мрежен опслужувач.
За да продолжите, наместете тој (и други!) да може да запишува во папката <code><nowiki>config</nowiki></code>.
На Unix/Linux систем направете го следново:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'Инсталација на МедијаВики со <code>$1</code> податотечни наставки.',
	'config-shell-locale' => 'Пронајден е локал за школката „$1“',
	'config-uploads-safe' => 'Основата папка за подигања е безбедна од произволни извршувања (пуштања) на скрипти',
	'config-uploads-not-safe' => "'''Предупредување:''' Вашата матична папка за подигање <code>$1</code> е подложна на извршување (пуштање) на произволни скрипти.
Иако МедијаВики врши безбедносни проверки на сите подигнати податотеки, ве советуваме [http://www.mediawiki.org/wiki/Manual:Security#Upload_security да ја затворите оваа безбедносна дупка] пред да овозможите подигање.",
	'config-db-type' => 'Тип на база:',
	'config-db-host' => 'Домаќин на базата:',
	'config-db-host-help' => 'Ако вашата база е на друг опслужувач, тогаш тука внесете го името на домаќинот илиу IP-адресата.

Ако користите заедничко (споделено) вдомување, тогаш вашиот вдомител треба да го доде точното име на домаќинот и неговата документација.',
	'config-db-wiki-settings' => 'Идентификувај го викиво',
	'config-db-name' => 'Име на базата:',
	'config-db-name-help' => 'Одберете име што ќе го претставува вашето вики.
Името не смее да содржи празни простори или цртички.

Ако користите заедничко (споделено) вдомување, тогаш вашиот вдомител ќе ви даде конкретно име на база за користење, или пак ви дава да создавате бази преку контролната табла.',
	'config-db-install-account' => 'Корисничка смета за инсталација',
	'config-db-username' => 'Корисничко име за базата:',
	'config-db-password' => 'Лозинка за базата:',
	'config-db-install-help' => 'Внесете го корисничкото име и лозинката што ќе се користи за поврзување со базата на податоци во текот на инсталацијата.',
	'config-db-account-lock' => 'Користи го истото корисничко име и лозинка за редовна работа',
	'config-db-wiki-account' => 'Корисничко име за редовна работа',
	'config-db-wiki-help' => 'Внесете корисничко име и лозинка што ќе се користат за поврзување со базата на податоци во текот на редовната работа со викито.
Ако сметката не постои, а инсталационата сметка има доволно привилегии, тогаш оваа корисничка сметка ќе биде создадена со минималните привилегии потребни за работа со викито.',
	'config-db-prefix' => 'Префикс на табелата на базата:',
	'config-db-prefix-help' => 'Ако треба да делите една база на податоци со повеќе викија, или со МедијаВики и друг мрежен програм, тогаш можете да додадете префикс на сите називи на табелите за да спречите проблематични ситуации.
Не користете празни простори и цртички.

Ова поле обично се остава празно.',
	'config-db-charset' => 'Збир знаци за базата',
	'config-charset-mysql5-binary' => 'Бинарен за MySQL 4.1/5.0',
	'config-charset-mysql5' => 'UTF-8 за MySQL 4.1/5.0',
	'config-charset-mysql4' => 'Назадно-соодветен UTF-8 за MySQL 4.0',
	'config-charset-help' => "'''ПРЕДУПРЕДУВАЊЕ:''' Ако користите '''назадно-соодветен UTF-8''' во MySQL 4.1+, а потоа направите резервен примерок на базата со <code>mysqldump</code>, ова може да ги опустоши сите не-ASCII знаци, и со тоа неповратно да ја расипе целата зачувана резерва!

Во '''бинарен режим''', во базата МедијаВики го складира UTF-8 текстот во бинарни полиња.
Ова е поефикансно отколку  UTF-8 режимот на MySQL бидејќи ви овозможува да го користите целиот спектар на уникодни знаци.
Во '''UTF-8 режим''', MySQL ќе знае на кој збир знаци припаѓаат вашите податоци, и може соодветно да ги претстави и претвори,
но нема да ви дозволи да складирате знаци над [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Основната повеќејазична рамнина].",
	'config-mysql-old' => 'Се бара MySQL $1 или поново, а вие имате $2.',
	'config-db-port' => 'Порта на базата:',
	'config-db-schema' => 'Шема за МедијаВики',
	'config-db-ts2-schema' => 'Шема за tsearch2',
	'config-db-schema-help' => 'Горенаведените шеми обично се точни.
Менувајте ги само ако знаете дека треба да се сменат.',
	'config-sqlite-dir' => 'Папка на SQLite-податоци:',
	'config-sqlite-dir-help' => "SQLite ги складира сите податоци во една податотека.

Папката што ќе ја наведете мора да е запислива од мрежниот опслужувач во текот на инсталацијата.

Таа '''не''' смее да биде достапна преку интернет, и затоа не ја ставаме кајшто ви се наоѓаат PHP-податотеките.

Инсталаторот воедно ќе создаде податотека <code>.htaccess</code>, но ако таа не функционира како што треба, тогаш некој ќе може да ви влезе во вашата необработена (сирова) база на податоци.
Тука спаѓаат необработени кориснички податоци (е-поштенски адреси, хеширани лозинки) како и избришани ревизии и други податоци за викито до кои се има ограничен пристап.

Се препорачува целата база да ја сместите некаде, како на пр. <code>/var/lib/mediawiki/вашетовики</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Нагодувања на MySQL',
	'config-header-postgres' => 'Нагодувања на PostgreSQL',
	'config-header-sqlite' => 'Нагодувања на SQLite',
	'config-header-oracle' => 'Нагодувања на Oracle',
	'config-invalid-db-type' => 'Неважечки тип на база',
	'config-missing-db-name' => 'Мора да внесете значење за параметарот „Име на базата“',
	'config-invalid-db-name' => 'Неважечко име на базата „$1“.
Може да содржи само бројки, букви и долни црти.',
	'config-invalid-db-prefix' => 'Неважечки префикс за базата „$1“.
Може да содржи само бројки, букви и долни црти.',
	'config-connection-error' => '$1.

Проверете го долунаведениот домаќин, корисничко име и лозинка и обидете се повторно.',
	'config-invalid-schema' => 'Неважечка шема за МедијаВики „$1“.
Користете само бројки, букви и долни црти.',
	'config-invalid-ts2schema' => 'Неважечка шема за tsearch2 „$1“.
Користете само бројки, букви и долни црти.',
	'config-postgres-old' => 'Се бара PostgreSQL $1 или поново, а вие имате $2.',
	'config-sqlite-name-help' => 'Одберете име кое ќе го претставува вашето вики.
Не користете празни простори и црти.
Ова ќе се користи за податотечното име на SQLite-податоците.',
	'config-sqlite-parent-unwritable-group' => 'Не можам да ја создадам папката <code><nowiki>$1</nowiki></code> бидејќи мрежниот опслужувач не може да запише во матичната папка <code><nowiki>$2</nowiki></code>.

Инсталаторот го утврди корисникот под кој работи вашиот мрежен опслужувач.
За да продолжите, наместете да може да запишува во папката <code><nowiki>$3</nowiki></code>.
На Unix/Linux систем направете го следново:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Не можам да ја создадам папката <code><nowiki>$1</nowiki></code> бидејќи мрежниот опслужувач не може да запише во матичната папка <code><nowiki>$2</nowiki></code>.

Инсталаторот не можеше го утврди корисникот под кој работи вашиот мрежен опслужувач.
За да продолжите, наместете тој (и други!) да може глобално да запишува во папката <code><nowiki>$3</nowiki></code>
На Unix/Linux систем направете го следново:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Грешка при создавањето на податочната папка „$1“.
Проверете каде се наоѓа и обидете се повторно.',
	'config-sqlite-dir-unwritable' => 'Не можам да запишам во папката „$1“.
Во дозволите за неа, овозможете му на мрежниот опслужувач да запишува во неа и обидете се повторно.',
	'config-sqlite-connection-error' => '$1.

Проверете ја податочната папка и името на базата, и обидете се повторно.',
	'config-sqlite-readonly' => 'Податотеката <code>$1</code> е незапислива.',
	'config-sqlite-cant-create-db' => 'Не можев да ја создадам податотеката <code>$1</code> за базата.',
	'config-sqlite-fts3-downgrade' => 'PHP нема поддршка за FTS3 — ја поништувам надградбата за табелите',
	'config-sqlite-fts3-add' => 'Додавам пребарувачки способности FTS3',
	'config-sqlite-fts3-ok' => 'Табелата за пребарување по цел текст се чини сосема в ред',
	'config-can-upgrade' => "Во оваа база има табели на МедијаВики.
За да ги надградите на МедијаВики $1, кликнете на '''Продолжи'''.",
	'config-upgrade-done' => "Надградбата заврши.

Сега можете да [$1 почнете да го користите вашето вики].

Ако сакате да ја пресоздадете вашата податотека <code>LocalSettings.php</code>, тогаш кликнете на копчето подолу.
Ова '''не се препорачува''' освен во случај на проблеми со викито.",
	'config-regenerate' => 'Пресоздај LocalSettings.php →',
	'config-show-table-status' => 'Барањето SHOW TABLE STATUS не успеа!',
	'config-unknown-collation' => "'''Предупредување:''' Базата корисни непрепознаена упатна споредба.",
	'config-db-web-account' => 'Сметка на базата за мрежен пристап',
	'config-db-web-help' => 'Одберете корисничко име и лозинка што ќе ги користи мрежниот опслужувач за поврзување со опслужувачот на базта на податоци во текот на редовната работа со викито.',
	'config-db-web-account-same' => 'Користи ја истата сметка од инсталацијата',
	'config-db-web-create' => 'Создај ја сметката ако веќе не постои',
	'config-db-web-no-create-privs' => 'Сметката што ја назначивте за инсталација нема доволно привилегии за да може да создаде сметка.
Тука мора да назначите постоечка сметка.',
	'config-mysql-engine' => 'Складишен погон:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' речиси секогаш е најдобар избор, бидејќи има добра поддршка за едновременост.

'''MyISAM''' може да е побрз кај инсталациите наменети за само еден корисник или незаписни инсталации (само читање).
Базите на податоци од MyISAM почесто се расипуваат од базите на InnoDB.",
	'config-mysql-charset' => 'Збир знаци за базата:',
	'config-mysql-binary' => 'Бинарен',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Во '''бинарен режим''', во базата на податоци МедијаВики складира UTF-8 текст во бинарни полиња.
Ова е поефикасно отколку  TF-8 режимот на MySQL, и ви овозможува да ја користите целата палета на уникодни знаци.

Во '''UTF-8 режим''', MySQL ќе знае на кој збир знаци припаѓаат вашите податоци, и може соодветно да ги претстави и претвори, но нема да ви дозволи да складиратезнаци над [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Основната повеќејазична рамнина].",
	'config-site-name' => 'Име на викито:',
	'config-site-name-help' => 'Ова ќе се појавува во заглавната лента на прелистувачот и на разни други места.',
	'config-site-name-blank' => 'Внесете име на мрежното место.',
	'config-project-namespace' => 'Проектен именски простор:',
	'config-ns-generic' => 'Проект',
	'config-ns-site-name' => 'Исто име како викито: $1',
	'config-ns-other' => 'Друго (наведете)',
	'config-ns-other-default' => 'МоеВики',
	'config-project-namespace-help' => "По примерот на Википедија, многу викија ги чуваат страниците со правила на посебно место од самите содржини, т.е. во „'''проектен именски простор'''“.
Сите наслови на страниците во овој именски простор почнуваат со извесен префикс, којшто можете да го укажете тука.
По традиција префиксот произлегува од името на викито, но не смее да содржи интерпункциски знаци како „#“ или „:“.",
	'config-ns-invalid' => 'Назначениот именски простор „<nowiki>$1</nowiki>“ е неважечки.
Назначете друг проектен именски простор.',
	'config-admin-box' => 'Администратоска сметка',
	'config-admin-name' => 'Вашето име:',
	'config-admin-password' => 'Лозинка:',
	'config-admin-password-confirm' => 'Пак лозинката:',
	'config-admin-help' => 'Тука внесете го вашето корисничко име, на пр. „Петар Петровски“.
Ова име ќесе користи за најава во викито.',
	'config-admin-name-blank' => 'Внесете администраторско корисничко име.',
	'config-admin-name-invalid' => 'Назначенотго корисничко име „<nowiki>$1</nowiki>“ е неважечко.
Назначете друго.',
	'config-admin-password-blank' => 'Внесете лозинка за администраторската сметка',
	'config-admin-password-same' => 'Лозинката не може да биде иста со корисничкото име.',
	'config-admin-password-mismatch' => 'Лозинките што ги внесовте не се совпаѓаат.',
	'config-admin-email' => 'Е-поштенска адреса:',
	'config-admin-email-help' => 'Тука внесете е-поштанска адреса за да можете да добивате е-пошта оддруги корисници на викито, да ја менувате лозинката, и да бидете известувани за промени во страниците на вашиот список на набљудувања.',
	'config-admin-error-user' => 'Се појави внатрешна грешка при создавањето на администраторот со име „<nowiki>$1</nowiki>“.',
	'config-admin-error-password' => 'Се појави внатрешна грешка при задавање на лозинката за администраторот „<nowiki>$1</nowiki>“: <pre>$2</pre>',
	'config-subscribe' => 'Претплатете се на [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce release поштенскиот список за известувања].',
	'config-subscribe-help' => 'Ова е нископрометен поштенски список кој се користи за соопштувања во врска со изданија, вклучувајќи важни безбедносни соопштенија.
Треба да се претплатите и да ја надградувате вашата инсталација на МедијаВики кога излегуваат нови верзии.',
	'config-almost-done' => 'Уште малку сте готови!
Сега можете да ги прескокнете преостанатите поставувања и веднаш да го инсталирате викито.',
	'config-optional-continue' => 'Постави ми повеќе прашања.',
	'config-optional-skip' => 'Веќе ми здосади, дај само инсталирај го викито.',
	'config-profile' => 'Профил на кориснички права:',
	'config-profile-wiki' => 'Традиционално вики',
	'config-profile-no-anon' => 'Задолжително отворање сметка',
	'config-profile-fishbowl' => 'Само овластени уредници',
	'config-profile-private' => 'Приватно вики',
	'config-profile-help' => "Викијата функционираат најдобро кога имаат што повеќе уредници.
Во МедијаВики лесно се проверуваат скорешните промени, и лесно се исправа (технички: „враќа“) штетата направена од неупатени или злонамерни корисници.

Многумина имаат најдено најразлични полезни примени за МедијаВики, но понекогаш не е лесно да убедите некого во предностите на вики-концептот.
Значи имате избор.

'''Традиционално вики''' — секој може да го уредува, дури и без најавување.
Ако имате вики со '''задолжително отворање на сметка''', тогаш добивате повеќе контрола, но ова може даги одврати спонтаните учесници.

'''Само овластени уредници''' — може да уредуваат само уредници што имаат добиено дозвола за тоа, но јавноста може да ги гледа страниците, вклучувајќи ја нивната историја.
'''Приватно вики''' — страниците се видливи и уредливи само за овластени корисници.

По инсталацијата имате на избор и посложени кориснички права и поставки. Погледајте во [http://www.mediawiki.org/wiki/Manual:User_rights прирачникот].",
	'config-license' => 'Авторски права и лиценца:',
	'config-license-none' => 'Без подножје за лиценца',
	'config-license-cc-by-sa' => 'Creative Commons НаведиИзвор СподелиПодИстиУслови (како Википедија)',
	'config-license-cc-by-nc-sa' => 'Creative Commons НаведиИзвор-Некомерцијално-СподелиПодИстиУслови',
	'config-license-gfdl-old' => 'ГНУ-ова лиценца за слободна документација 1.2',
	'config-license-gfdl-current' => 'ГНУ-ова лиценца за слободна документација 1.3 или понова',
	'config-license-pd' => 'Јавен домен',
	'config-license-cc-choose' => 'Одберете друга Creative Commons лиценца по ваш избор',
	'config-license-help' => "Многу јавни викија ги ставаат сите придонеси под [http://freedomdefined.org/Definition слободна лиценца].
Со ова се создава атмосфера на општа сопственост и поттикнува долгорочно учество.
Ова не е неопходно за викија на поединечни физички или правни лица.

Ако сакате да користите текст од Википедија, и сакате Википедија да прифаќа текст прекопиран од вашето вики, тогаш треба да ја одберете лиценцата '''Creative Commons НаведиИзвор СподелиПодИстиУслови'''.

ГНУ-овата лиценца за слободна документација е старата лиценца на Википедија.
Оваа лиценца сè уште важи, но има некои особености што значително го отежнуваат толкувањето на искористувањето на содржините вон Викимедија.",
	'config-email-settings' => 'Нагодувања за е-пошта',
	'config-enable-email' => 'Овозможи излезна е-пошта',
	'config-enable-email-help' => 'Ако сакате да работи е-поштата, [http://www.php.net/manual/en/mail.configuration.php поштенските нагодувања на PHP] треба да се правилно наместени.
Ако воопшто не сакате никакви функции за е-пошта, тогаш можете да ги оневозможите тука.',
	'config-email-user' => 'Овозможи е-пошта од корисник до корисник',
	'config-email-user-help' => 'Дозволи сите корисници да можат да си праќаат е-пошта ако ја имаат овозможено во нагодувањата.',
	'config-email-usertalk' => 'Овозможи известувања за промени во кориснички страници за разговор',
	'config-email-usertalk-help' => 'Овозможи корисниците да добиваат известувања за промени во нивните кориснички страници за разговор ако ги имаат овозможено во нагодувањата.',
	'config-email-watchlist' => 'Овозможи известувања за список на набљудувања',
	'config-email-watchlist-help' => 'Овозможи корисниците да добиваат известувања за нивните набљудувани страници ако ги имаат овозможено во нагодувањата.',
	'config-email-auth' => 'Овозможи потврдување на е-пошта',
	'config-email-auth-help' => "Ако оваа можност е вклучена, тогаш корисниците ќе мора да ја потврдат нивната е-поштенска адреса преку врска испратена до нив кога ја укажуваат или менуваат е-поштенската адреса.
Само корисници со потврдена е-пошта можат да добиваат е-пошта од други корисници или да ги менуваат писмата за известување.
Оваа можност е '''препорачана''' за јавни викија поради можни злоупотреби на е-поштенската функција.",
	'config-email-sender' => 'Повратна е-поштенска адреса:',
	'config-email-sender-help' => 'Внесете ја е-поштенската адреса што ќе се користи како повратна адреса за излезна е-пошта.
Таму ќе се испраќаат вратените (непримени) писма.
Многу поштенски опслужувачи бараат барем делот за доменско име да биде важечки.',
	'config-upload-settings' => 'Подигање на слики и податотеки',
	'config-upload-enable' => 'Овозможи подигање на податотеки',
	'config-upload-help' => 'Подигањето на податотеки потенцијално го изложуваат вашиот опслужувач на безбедносни ризици.
За повеќе информации, прочитајте го [http://www.mediawiki.org/wiki/Manual:Security поглавието за безбедност] во прирачникот.

За да овозможите подигање на податотеки, сменете го режимот на потпапката <code>images</code> во основната папка на МедијаВики, за да му овозможите на мрежниот опслужувач да запишува во неа.
Потоа овозможете ја оваа функција.',
	'config-upload-deleted' => 'Папка за избришаните податотеки:',
	'config-upload-deleted-help' => 'Одберете во која папка да се архивираат избришаните податотеки.
Најдобро би било ако таа не е достапна преку интернет.',
	'config-logo' => 'URL за логото:',
	'config-logo-help' => 'Матичното руво на МедијаВики има простор за лого од 135x135 пиксели во горниот лев агол.
Подигнете слика со соодветна големина, и тука внесете ја URL-адресата.

Ако не сакате да имате лого, тогаш оставете го ова поле празно.',
	'config-instantcommons' => 'Овозможи Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] е функција која им овозможува на викијата да користат слики, звучни записи и други мултимедијални содржини од [http://commons.wikimedia.org/ Заедничката Ризница].
За да може ова да работи, МедијаВики бара пристап до интернет. $1

За повеќе информации за оваа функција и напатствија за нејзино поставување на вики (сите други освен Ризницата), коносултирајте го [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos прирачникот].',
	'config-instantcommons-good' => 'Инсталаторот утврди пристап до интернет во текот на проверките на околината.
Ако сакате можете да ја овозможите оваа функција.',
	'config-instantcommons-bad' => "''Нажалост, во текот на проверките на околината, инсталаторот не пронајде пристап до интернет, и затоа веројатно нема да можете да ја користите оваа функција.
Ако вашиот опслужувач има застапник (proxy), може да треба да направте извесни [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy дополнителни нагодувања].''",
	'config-cc-error' => 'Изборникот на Creative Commons лиценца не даде резултати.
Внесете го името на лиценцата рачно.',
	'config-cc-again' => 'Одберете повторно...',
	'config-cc-not-chosen' => 'Одберете ја саканата Creative Commons лиценца и кликнете на „продолжи“.',
	'config-advanced-settings' => 'Напредни нагодувања',
	'config-cache-options' => 'Нагодувања за кеширање на објекти:',
	'config-cache-help' => 'Кеширањето на објекти се користи за зголемување на брзината на МедијаВики со кеширање на често употребуваните податоци.
Ова многу се препорачува на средни до големи викија, но од тоа ќе имаат полза и малите викија.',
	'config-cache-none' => 'Без кеширање (не се остранува ниедна функција, но може да влијае на брзината кај поголеми викија)',
	'config-cache-accel' => 'Кеширање на PHP-објекти (APC, eAccelerator, XCache или WinCache)',
	'config-cache-memcached' => 'Користи Memcached (бара дополнително поставување и нагодување)',
	'config-memcached-servers' => 'Memcached-опслужувачи:',
	'config-memcached-help' => 'Список на IP-адреси што ќе се употребуваат за Memcached.
Треба да се одделени со запирки и треба да назначите која порта ќе ја користите (на пример: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Додатоци',
	'config-extensions-help' => 'Во вашата папка <code>./extensions</code> беа востановени горенаведените додатоци.

За ова може да треба дополнително нагодување, но можете да ги овозможите сега',
	'config-install-alreadydone' => "'''Предупредување:''' Изгледа дека веќе го имате инсталирано МедијаВики и сега сакате да го инсталирате повторно.
Продолжете на следната страница.",
	'config-install-step-done' => 'готово',
	'config-install-step-failed' => 'не успеа',
	'config-install-extensions' => 'Вклучувам додатоци',
	'config-install-database' => 'Ја поставувам базата на податоци',
	'config-install-pg-schema-failed' => 'Создавањето натабелите не успеа.
Проверете дали корисникот „$1“ може да запишува во шемата „$2“.',
	'config-install-user' => 'Создавам корисник за базата',
	'config-install-user-failed' => 'Доделувањето на дозвола на корисникот „$1“ не успеа: $2',
	'config-install-tables' => 'Создавам табели',
	'config-install-tables-exist' => "'''Предупредување''': Изгледа дека табелите за МедијаВики веќе постојат.
Го прескокнувам создавањето.",
	'config-install-tables-failed' => "'''Грешка''': Создавањето на табелата не успеа поради следнава грешка: $1",
	'config-install-interwiki' => 'Ги пополнувам основно-зададените интервики-табели',
	'config-install-interwiki-sql' => 'Не можев да ја пронајдам податотеката <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Предупредување''': Табелата со интервикија веќе содржи ставки.
Го прескокнувам основно-зададениот список.",
	'config-install-secretkey' => 'Создавам таен клуч',
	'config-insecure-secretkey' => "'''Предупредување:''' Не можам да создадам безбеден <code>\$wgSecretKey</code>.
Ви препорачуваме да го смените рачно.",
	'config-install-sysop' => 'Создавање на администраторска корисничка сметка',
	'config-install-done' => "'''Честитаме!'''
Успешно го инсталиравте МедијаВики.

Инсталаторот создаде податотека <code>LocalSettings.php</code>.
Таму се содржат сите ваши нагодувања.

Ќе треба да ја [$1 преземете] и да ја ставите во основата на инсталацијата (истата папка во која се наоѓа index.php).
'''Напомена''': Ако излезете од инсталацијата без да ја преземете сега, оваа создадена податотека со нагодувања повеќе нема да ви биде на достапна.

Откога ќе завршите со тоа, можете да '''[$2 влезете на вашето вики]'''.",
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'config-desc' => 'Niets van de installer vertalen, alsjeblieft! Siebrand moet de boel nog controleren.',
	'config-title' => 'Installatie MediaWiki $1',
	'config-information' => 'Informatie',
	'config-localsettings-upgrade' => "'''Waarschuwing''': er is een bestaand bestand <code>LocalSettings.php</code> gevonden.
Uw software kan bijgewerkt worden.
Verplaats alstublieft het bestand <code>LocalSettings.php</code> naar een veilige plaatst en voer het installatieprogramma opnieuw uit.",
	'config-localsettings-noupgrade' => "'''Fout''': er is een bestaand bestand <code>LocalSettings.php</code> aangetroffen.
Uw software kan op dit moment niet bijgewerkt worden.
Om veiligheidsredenen is het installatieprogramma uitgeschakeld.",
	'config-session-error' => 'Fout bij het begin van de sessie: $1',
	'config-session-expired' => 'Uw sessiegegevens zijn verlopen.
Sessies zijn ingesteld om een levensduur van $1 te hebben.
U kunt deze wijzigen via de instelling <code>session.gc_maxlifetime</code> in php.ini.
Begin het installatieproces opnieuw.',
	'config-no-session' => 'Uw sessiegegevens zijn verloren gegaan.
Controleer uw php.ini en zorg dat er een juiste map is ingesteld voor <code>session.save_path</code>.',
	'config-session-path-bad' => 'Uw <code>session.save_path</code> (<code>$1</code>) lijkt onjuist of er kan niet in geschreven worden.',
	'config-show-help' => 'Hulp',
	'config-hide-help' => 'Hulp verbergen',
	'config-your-language' => 'Uw taal:',
	'config-your-language-help' => 'Selecteer een taal om tijdens het installatieproces te gebruiken.',
	'config-wiki-language' => 'Wikitaal:',
	'config-wiki-language-help' => 'Selecteer de taal waar de wiki voornamelijk in wordt geschreven.',
	'config-back' => '← Terug',
	'config-continue' => 'Doorgaan →',
	'config-page-language' => 'Taal',
	'config-page-welcome' => 'Welkom bij MediaWiki!',
	'config-page-dbconnect' => 'Verbinding maken met database',
	'config-page-upgrade' => 'Bestaande installatie bijwerken',
	'config-page-dbsettings' => 'Databaseinstellingen',
	'config-page-name' => 'Naam',
	'config-page-options' => 'Opties',
	'config-page-install' => 'Installeren',
	'config-page-complete' => 'Afgerond!',
	'config-page-restart' => 'Installatie herstarten',
	'config-page-readme' => 'Lees mij',
	'config-page-releasenotes' => 'Release notes',
	'config-page-copying' => 'Kopiëren',
	'config-page-upgradedoc' => 'Bijwerken',
	'config-help-restart' => 'Wilt u alle opgeslagen gegevens die u hebt ingevoerd wissen en het installatieproces opnieuw starten?',
	'config-restart' => 'Ja, opnieuw starten',
	'config-welcome' => '=== Controle ongeving ===
Er worden een aantal basale controles uitgevoerd met als doel vast te stellen of deze omgeving geschikt is voor een installatie van MediaWiki.
Als u hulp nodig hebt bij de installatie, lever deze gegevens dan ook aan.',
	'config-copyright' => "=== Auterursrechten en voorwaarden ===

$1

Dit programma is vrije software. U mag het herdistribueren en/of aanpassen in overeenstemming met de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation; ofwel versie 2 van de Licentie of - naar uw keuze - enige later versie.

Dit programma wordt gedistribueerd in de hoop dat het nuttig is, maar '''zonder enige garantie''', zelfs zonder de impliciete garantie van'''verkoopbaarheid''' of '''geschiktheid voor een bepaald doel'''.
Zie de GNU General Public License voor meer informatie. 

Samen met dit programma hoort u een <doclink href=Copying>kopie van de GNU General Public License</doclink> ontvangen te hebben; zo niet, schrijf dan aan de Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, Verenigde Staten of[http://www.gnu.org/copyleft/gpl.html lees de licentie online].",
	'config-authors' => 'MediaWiki is Copyright © 2001-2010 door Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe en anderen.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki thuispagina]
* [http://www.mediawiki.org/wiki/Help:Contents Gebruikershandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:Contents Beheerdershandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:FAQ Veel gestelde vragen] (Engelstalig)',
	'config-env-good' => '<span class="success-message">De omgeving is gecontroleerd.
U kunt MediaWiki installeren.</span>',
	'config-env-bad' => 'De omgeving is gecontroleerd.
U kunt MediaWiki niet installeren.',
	'config-env-php' => 'PHP $1 is geïnstalleerd.',
	'config-env-latest-ok' => 'U bent bezig de meest recente versie van MediaWiki te installeren.',
	'config-env-latest-new' => "'''Let op:''' U bent bezig een ontwikkelversie van MediaWiki te installeren.",
	'config-env-latest-can-not-check' => "'''Let op:''' het installatieprogramma was niet in staat om informatie over de nieuwste release van MediaWiki op te halen van [$1].",
	'config-env-latest-data-invalid' => "'''Waarschuwing:''' tijdens de versiecontrole op een verouderde versie is ongeldige informatie ontvangen van [$1].",
	'config-env-latest-old' => "'''Waarschuwing:''' U bent bezig een verouderde versie van MediaWiki te installeren.",
	'config-env-latest-help' => 'U bent bezig versie $1 te installeren, maar de meest recente versie is $2.
U wordt aangeraden de meest recente versie te gebruiken die u kunt downloaden van [http://www.mediawiki.org/wiki/Download mediawiki.org].',
	'config-unicode-using-php' => 'Voor Unicode-normalisatie wordt de langzame PHP-implementatie gebruikt.',
	'config-unicode-using-utf8' => 'Voor Unicode-normalisatie wordt utf8_normalize.so van Brion Vibber gebruikt.',
	'config-unicode-using-intl' => 'Voor Unicode-normalisatie wordt de [http://pecl.php.net/intl PECL-extensie intl] gebruikt.',
	'config-unicode-pure-php-warning' => "'''Waarschuwing''': De [http://pecl.php.net/intl PECL-extensie intl] is niet beschikbaar om de Unicode-normalisatie af te handelen.
Als u een website met veel verkeer installeert, lees u dan in over [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-normalisatie].",
	'config-unicode-update-warning' => "'''Waarschuwing''': De geïnstalleerde versie van de Unicode-normalisatiewrapper maakt gebruik van een oudere versie van [http://site.icu-project.org/ de bibliotheek van het ICU-project].
U moet [http://www.mediawiki.org/wiki/Unicode_normalization_considerations bijwerken] als Unicode voor u van belang is.",
	'config-no-db' => 'Er kon geen geschikte databasedriver geladen worden!',
	'config-no-db-help' => 'U moet een databasedriver installeren voor PHP.
De volgende databases worden ondersteund: $1.

Als u op een gedeelde omgeving zit, vraag dan aan uw hostingprovider een geschikte databasedriver te installeren.
Als u PHP zelf hebt gecompileerd, wijzig dan uw instellingen zodat een databasedriver wordt geactiveerd, bijvoorbeeld via <code>./configure --with-mysql</code>.
Als u PHP hebt geïnstalleerd via een Debian- of Ubuntu-package, installeer dan ook de module php5-mysql.',
	'config-have-db' => 'Gevonden databasedrivers: $1.',
	'config-register-globals' => "'''Waarschuwing: De PHP-optie <code>[http://php.net/register_globals register_globals]</code> is ingeschakeld.'''
'''Schakel deze uit als dat mogelijk is.'''
MediaWiki kan ermee werken, maar uw server is dan meer kwetsbaar voor beveiligingslekken.",
	'config-magic-quotes-runtime' => "'''Onherstelbare fout: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] is actief!'''
Deze instelling zorgt voor gegevenscorruptie.
U kunt MediaWiki niet installeren tenzij deze instelling is uitgeschakeld.",
	'config-magic-quotes-sybase' => "'''Onherstelbare fout: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_sybase] is actief!'''
Deze instelling zorgt voor gegevenscorruptie.
U kunt MediaWiki niet installeren tenzij deze instelling is uitgeschakeld.",
	'config-mbstring' => "'''Onherstelbare fout: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] is actief!'''
Deze instelling zorgt voor gegevenscorruptie.
U kunt MediaWiki niet installeren tenzij deze instelling is uitgeschakeld.",
	'config-ze1' => "'''Onherstelbare fout: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] is actief!'''
Deze instelling zorgt voor grote problemen in MediaWiki.
U kunt MediaWiki niet installeren tenzij deze instelling is uitgeschakeld.",
	'config-safe-mode' => "'''Waarschuwing:'''
'''PHP's [http://www.php.net/features.safe-mode veilige modus] is actief.'''
Dit kan problemen veroorzaken, vooral bij het uploaden van bestanden en ondersteuning van <code>math</code>.",
	'config-xml-good' => 'Er is ondersteuning voor XML / Latin1-UTF-8-conversie.',
	'config-xml-bad' => 'De XML-module van PHP ontbreekt.
MediaWiki heeft de functies van deze module nodig en werkt niet zonder deze module.
Als u gebruik maakt van Mandrake, installeer dan het package php-xml.',
	'config-pcre' => 'De ondersteuningsmodule PCRE lijkt te missen.
MediaWiki vereist dat de met Perl compatibele reguliere expressies werken.',
	'config-memory-none' => 'PHP is ingesteld zonder <code>memory_limit</code>',
	'config-memory-ok' => "PHP's <code>memory_limit</code> is $1. In orde.",
	'config-memory-raised' => "PHP's <code>memory_limit</code> is $1 en is verhoogd tot $2.",
	'config-memory-bad' => "'''Waarschuwing:''' PHP's <code>memory_limit</code> is $1.
Dit is waarschijnlijk te laag.
De installatie kan mislukken!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] geïnstalleerd',
	'config-apc' => '[http://www.php.net/apc APC] geïnstalleerd',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] geïnstalleerd',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] is geïnstalleerd',
	'config-no-cache' => "'''Waarschuwing:''' [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC] of [http://trac.lighttpd.net/ xcache / XCache] is niet aangetroffen.
Het cachen van objecten is niet ingeschakeld.",
	'config-diff3-good' => 'GNU diff3 aangetroffen: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 niet aangetroffen.',
	'config-imagemagick' => 'ImageMagick aangetroffen: <code>$1</code>.
Het aanmaken van miniaturen van afbeeldingen wordt ingeschakeld als u uploaden inschakelt.',
	'config-gd' => 'Ingebouwde GD grafische bibliotheek aangetroffen.
Het aanmaken van miniaturen van afbeeldingen wordt ingeschakeld als u uploaden inschakelt.',
	'config-no-scaling' => 'De GD-bibliotheek en ImageMagick zijn niet aangetroffen.
Het maken van miniaturen van afbeeldingen wordt uitgeschakeld.',
	'config-dir' => 'Installatiemap: <code>$1</code>.',
	'config-uri' => 'Script URI-pad: <code>$1</code>.',
	'config-no-uri' => "'''Fout:''' de huidige URI kon niet vastgesteld worden.
De installatie is afgebroken.",
	'config-dir-not-writable-group' => "'''Fout:''' het instellingenbestand kan niet weggeschreven worden.
De installatie is afgebroken.

Het installatieprogramma heeft vastgesteld onder welke gebruiker uw webserver draait.
Zorg dat in de map <code><nowiki>config</nowiki></code> geschreven kan worden door die gebruiker om door te kunnen gaan.
Op een Linux-systeem:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Fout:''' het instellingenbestand kan niet weggeschreven worden.
De installatie is afgebroken.

Het installatieprogramma heeft niet vast kunnen stellen onder welke gebruiker uw webserver draait.
Zorg dat in de map <code><nowiki>config</nowiki></code> geschreven kan worden door de webservergebruiker (en anderen!) om door te kunnen gaan.
Op een Linux-systeem:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'MediaWiki wordt geinstalleerd met <code>$1</code> als bestandsextensie.',
	'config-shell-locale' => 'Als shelllocale is "$1" herkend',
	'config-uploads-safe' => 'De uploadmap is beveiligd tegen het arbitrair uitvoeren van scripts.',
	'config-uploads-not-safe' => "'''Waarschuwing:''' uw uploadmap <code>$1</code> kan gebruikt worden voor het arbitrair uitvoeren van scripts.
Hoewel MediaWiki alle toegevoegde bestanden  controleert op bedreigingen, is het zeer aan te bevelen het [http://www.mediawiki.org/wiki/Manual:Security#Upload_security beveiligingslek te verhelpen] alvorens uploads in te schakelen.",
	'config-db-type' => 'Databasetype:',
	'config-db-host' => 'Databasehost:',
	'config-db-host-help' => 'Als uw databaseserver een andere server is, voer dan de hostnaam of het IP-adres hier in.
Als u gebruik maakt van gedeelde webhosting, hoort uw provider u de juiste hostnaam te hebben verstrekt.',
	'config-db-wiki-settings' => 'Identificeer deze wiki',
	'config-db-name' => 'Databasenaam:',
	'config-db-name-help' => 'Kies een naam die uw wiki identificeert.
Er mogen geen spaties of koppeltekens gebruikt worden.
Als u gebruik maakt van gedeelde webhosting, dan hoort uw provider ofwel u een te gebruiken databasenaam gegeven te hebben, of u aangegeven te hebben hoe u databases kunt aanmaken.',
	'config-db-install-account' => 'Gebruiker voor installatie',
	'config-db-username' => 'Gebruikersnaam voor database:',
	'config-db-password' => 'Wachtwoord voor database:',
	'config-db-install-help' => 'Voer de gebruikersnaam en het wachtwoord in die worden gebruikt voor de databaseverbinding tijdens het installatieproces.',
	'config-db-account-lock' => 'Dezelfde gebruiker en wachwoord gebruiken na de installatie',
	'config-db-wiki-account' => 'Gebruiker voor na de installatie',
	'config-db-wiki-help' => 'Selecteer de gebruikersnaam en het wachtwoord die gebruikt worden om verbinding te maken met de database na de installatie.
Als de gebruiker niet bestaat en de gebruiker die tijdens de installatie gebruikt wordt voldoende rechten heeft, wordt deze gebruiker aangemaakt met de minimaal benodigde rechten voor het laten werken van de wiki.',
	'config-db-prefix' => 'Databasetabelvoorvoegsel:',
	'config-db-prefix-help' => "Als u een database moet gebruiken voor meerdere wiki's, of voor MediaWiki en een andere applicatie, dan kunt u ervoor kiezen om een voorvoegsel toe te voegen aan de tabelnamen om conflicten te voorkomen.
Gebruik geen spaties of koppeltekens.

Dit veld wordt meestal leeg gelaten.",
	'config-db-charset' => 'Tekenset voor de database',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binair',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 UTF-8-compatibel',
	'config-charset-help' => "'''Waarschuwing:''' als u '''achterwaarts compatibel met UTF-8''' gebruikt met MySQL 4.1+ en een back-up van de database maakt met <code>mysqldump</code>, dan kunnen alle niet-ASCII-tekens in uw back-ups onherstelbaar beschadigd raken.

In '''binaire modus''' slaat MediaWiki tekst in UTF-8 op in binaire databasevelden.
Dit is efficienter dan de UTF-8-modus van MySQL en stelt u in staat de volledige reeks Unicode-tekens te gebruiken.
In '''UTF-8-modus''' kent MySQL de tekenset van uw gegevens en kan de databaseserver ze juist weergeven en converteren.
Het is dat niet mogelijk tekens op te slaan die de \"[http://nl.wikipedia.org/wiki/Lijst_van_Unicode-subbereiken#Basic_Multilingual_Plane Basic Multilingual Plane]\" te boven gaan.",
	'config-mysql-old' => 'U moet MySQL $1 of later gebruiken.
U gebruikt $2.',
	'config-db-port' => 'Databasepoort:',
	'config-db-schema' => 'Schema voor MediaWiki',
	'config-db-ts2-schema' => 'Schema voor tsearch2',
	'config-db-schema-help' => "De bovenstaande schema's kloppen meestal.
Wijzig ze alleen als u weet dat u ze nodig hebt.",
	'config-sqlite-dir' => 'Gegevensmap voor SQLite:',
	'config-sqlite-dir-help' => "SQLite slaat alle gegevens op in een enkel bestand.

De map die u opgeeft moet schrijfbaar zijn voor de webserver tijdens de installatie.

Deze mag '''niet toegankelijk''' zijn via het web en het bestand mag dus niet tussen de PHP-bestanden staan.

Het installatieprogramma schrijft het bestand <code>.htaccess</code> weg met het databasebestand, maar als dat niet werkt kan iemand zich toegang tot het ruwe databasebestand verschaffen.
Ook de gebruikersgegevens (e-mailsadressen, wachtwoordhashes) en verwijderde versies en overige gegevens met beperkte toegang via MediaWiki zijn dan onbeschermd. 

Overweeg om de database op een totaal andere plaats neer te zetten, bijvoorbeeld in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-instellingen',
	'config-header-postgres' => 'PostgreSQL-instellingen',
	'config-header-sqlite' => 'SQLite-instellingen',
	'config-header-oracle' => 'Oracle-instellingen',
	'config-invalid-db-type' => 'Ongeldig databasetype',
	'config-missing-db-name' => 'U moet een waarde ingeven voor "Databasenaam"',
	'config-invalid-db-name' => 'Ongeldige database naam "$1".
Deze mag alleen cijfers, letters en liggende streepjes bevatten.',
	'config-invalid-db-prefix' => 'Ongeldig databasevoorvoegsel "$1".
Dit mag alleen cijfers, letters en liggende streepjes bevatten.',
	'config-connection-error' => '$1.

Controleer de host, gebruikersnaam en wachtwoord hieronder in en probeer het opnieuw.',
	'config-invalid-schema' => 'Ongeldige schema voor MediaWiki "$1".
Gebruik alleen letters, cijfers en liggende streepjes.',
	'config-invalid-ts2schema' => 'Ongeldig schema voor tsearch "$1".
Gebruik alleen letters, cijfers en liggende streepjes.',
	'config-postgres-old' => 'PostgreSQL $1 of hoger is vereist.
U gebruikt $2.',
	'config-sqlite-name-help' => 'Kies een naam die uw wiki identificeert.
Gebruik geen spaties of koppeltekens.
Deze naam wordt gebruikt voor het gegevensbestands van SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Het was niet mogelijk de gegevensmap <code><nowiki>$1</nowiki></code> te maken omdat in de bovenliggende map <code><nowiki>$2</nowiki></code> niet geschreven mag worden door de webserver.

Het installatieprogramma heeft vast kunnen stellen onder welke gebruiker de webserver draait.
Maak de map <code><nowiki>$3</nowiki></code> beschrijfbaar om door te kunnen gaan.
Voer op een Linux-systeem de volgende opdrachten uit:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Het was niet mogelijk de gegevensmap <code><nowiki>$1</nowiki></code> te maken omdat in de bovenliggende map <code><nowiki>$2</nowiki></code> niet geschreven mag worden door de webserver.

Het installatieprogramma heeft niet vast kunnen stellen onder welke gebruiker de webserver draait.
Maak de map <code><nowiki>$3</nowiki></code> beschrijfbaar voor de webserver (en anderen!) om door te kunnen gaan.
Voer op een Linux-systeem de volgende opdrachten uit:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Er is een fout opgetreden bij het aanmaken van de gegevensmap "$1".
Controleer de locatie en probeer het opnieuw.',
	'config-sqlite-dir-unwritable' => 'Het was niet mogelijk in de map "$1" te schrijven.
Wijzig de rechten zodat de webserver erin kan schrijven en probeer het opnieuw.',
	'config-sqlite-connection-error' => '$1.

Controleer de map voor gegevens en de databasenaam hieronder en probeer het opnieuw.',
	'config-sqlite-readonly' => 'Het bestand <code>$1</code> kan niet geschreven worden.',
	'config-sqlite-cant-create-db' => 'Het was niet mogelijk het databasebestand <code>$1</code> aan te maken.',
	'config-sqlite-fts3-downgrade' => 'PHP heeft geen ondersteuning voor FTS3.
De tabellen worden gedowngrade.',
	'config-sqlite-fts3-add' => 'FTS3 zoekmogelijkheden aan het toevoegen',
	'config-sqlite-fts3-ok' => 'De tabel voor "full text search" lijkt in orde',
	'config-can-upgrade' => "Er staan al tabellen voor MediaWiki in deze database.
Klik op '''Doorgaan''' om ze bij te werken naar MediaWiki $1.",
	'config-upgrade-done' => "Het bijwerken is afgerond.

Uw kunt [$1 uw wiki nu gebruiken].

Als u uw <code>LocalSettings.php</code> opnieuw wilt aanmaken, klik dan op de knop hieronder.
Dit is '''niet aan te raden''' tenzij u problemen hebt met uw wiki.",
	'config-regenerate' => 'LocalSettings.php opnieuw aanmaken →',
	'config-show-table-status' => 'Het uitvoeren van SHOW TABLE STATUS is mislukt!',
	'config-unknown-collation' => "'''Waarschuwing:''' de database gebruikt een collatie die niet wordt herkend.",
	'config-db-web-account' => 'Databasegebruiker voor webtoegang',
	'config-db-web-help' => 'Selecteer de gebruikersnaam en het wachtwoord die de webserver gebruikt om verbinding te maken met de databaseserver na de installatie.',
	'config-db-web-account-same' => 'Dezelfde gebruiker gebruiken als voor de installatie',
	'config-db-web-create' => 'Maak de gebruiker aan als deze nog niet bestaat',
	'config-db-web-no-create-privs' => 'De gebruiker die u hebt opgegeven voor de installatie heeft niet voldoende rechten om een gebruiker aan te maken.
De gebruiker die u hier opgeeft moet al bestaan.',
	'config-mysql-engine' => 'Opslagmethode:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' is vrijwel altijd de beste instelling, omdat deze goed omgaat met meerdere verzoeken tegelijkertijd.

'''MyISAM''' is bij een zeer beperkt aantal gebruikers mogelijk sneller, of als de wiki alleen-lezen is.
MyISAM-databases raken vaker corrupt dan InnoDB-databases.",
	'config-mysql-charset' => 'Tekenset voor de database:',
	'config-mysql-binary' => 'Binair',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Naam van de wiki:',
	'config-site-name-help' => 'Deze naam verschijnt in de titelbalk van browsers en op andere plaatsen.',
	'config-site-name-blank' => 'Geef een naam op voor de site.',
	'config-project-namespace' => 'Projectnaamruimte:',
	'config-ns-generic' => 'Project',
	'config-ns-site-name' => 'Zelfde als de wiki: $1',
	'config-ns-other' => 'Andere (geen aan welke)',
	'config-ns-other-default' => 'MijnWiki',
	'config-project-namespace-help' => "In het kielzog van Wikipedia beheren veel wiki's hun beleidspagina's apart van hun inhoudelijke pagina's in een \"'''projectnaamruimte'''\".
Alle paginanamen in deze naamruimte beginnen met een bepaald voorvoegsel dat u hier kunt aangeven.
Dit voorvoegsel wordt meestal afgeleid van de naam van de wiki, maar het kan geen bijzondere tekens bevatten als \"#\" of \":\".",
	'config-ns-invalid' => 'De aangegeven naamruimte "<nowiki>$1</nowiki>" is ongeldig.
Geef een andere naamruimte op.',
	'config-admin-box' => 'Beheerdersgebruiker',
	'config-admin-name' => 'Uw naam:',
	'config-admin-password' => 'Wachtwoord:',
	'config-admin-password-confirm' => 'Wachtwoord opnieuw:',
	'config-admin-help' => 'Voer de gebruikersnaam hier in, bijvoorbeeld "Jan Jansen".
Dit is de naam die wordt gebruikt om aan de melden bij de wiki.',
	'config-admin-name-blank' => 'Geef een gebruikersnaam op voor de beheerder.',
	'config-admin-name-invalid' => 'De opgegeven gebruikersnaam "<nowiki>$1</nowiki>" is ongeldig.
Kies een andere gebruikersnaam.',
	'config-admin-password-blank' => 'Voer een wachtwoord voor de beheerder in.',
	'config-admin-password-same' => 'Het wachtwoord mag niet hetzelfde zijn als de gebruikersnaam.',
	'config-admin-password-mismatch' => 'De twee door u ingevoerde wachtwoorden komen niet overeen.',
	'config-admin-email' => 'E-mailadres:',
	'config-admin-email-help' => "Voer hier een e-mailadres in om e-mail te kunnen ontvangen van andere gebruikers op de wiki, uw wachtwoord opnieuw in te kunnen stellen en op de hoogte te worden gehouden van wijzigingen van pagina's op uw volglijst.",
	'config-admin-error-user' => 'Interne fout bij het aanmaken van een beheerder met de naam "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Interne fout bij het instellen van een wachtwoord voor de bejeerder "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => 'Abonneren op de [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce mailinglijst releaseaankondigen].',
	'config-subscribe-help' => 'Dit is een mailinglijst met een laag volume voor aankondigingen van nieuwe versies, inclusief belangrijke aankondigingen met betrekking tot beveiliging.
Abonneer uzelf erop en werk uw MediaWiki-installatie bij als er nieuwe versies uitkomen.',
	'config-almost-done' => 'U bent bijna klaar!
Als u wilt kunt u de overige instellingen overslaan en de wiki nu installeren.',
	'config-optional-continue' => 'Meer vragen.',
	'config-optional-skip' => 'Installeer de wiki.',
	'config-profile' => 'Gebruikersrechtenprofiel:',
	'config-profile-wiki' => 'Traditionele wiki',
	'config-profile-no-anon' => 'Gebruiker aanmaken verplicht',
	'config-profile-fishbowl' => 'Alleen voor geautoriseerde bewerkers',
	'config-profile-private' => 'Privéwiki',
	'config-license' => 'Auteursrechten en licentie:',
	'config-license-none' => 'Geen licentie in de voettekst',
	'config-license-cc-by-sa' => 'Creative Commons Naamsvermelding-Gelijk delen (compatibel met Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Naamsvermelding-Niet Commercieel-Gelijk delen',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2 of hoger',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 of hoger',
	'config-license-pd' => 'Publiek domein',
	'config-license-cc-choose' => 'Een Creative Commons-licentie selecteren',
	'config-license-help' => "In veel openbare wiki's zijn alle bijdragen beschikbaar onder een [http://freedomdefined.org/Definition vrije licentie].
Dit helpt bij het creëren van een gevoel van gemeenschappelijk eigendom en stimuleert bijdragen op lange termijn.
Dit is over het algemeen niet nodig is voor een particuliere of zakelijke wiki.

Als u teksten uit Wikipedia wilt kunnen gebruiken en u wilt het mogelijk maken teksten uit uw wiki naar Wikipedia te kopiëren, kies dan de licentie '''Creative Commons Naamsvermelding-Gelijk delen'''.

De GNU Free Documentation License was de oude licentie voor inhoud uit Wikipedia.
Dit is nog steeds een geldige licentie, maar deze licentie heeft een aantal eigenschappen die hergebruik en interpretatie lastig kunnen maken.",
	'config-email-settings' => 'E-mailinstellingen',
	'config-enable-email' => 'Uitgaande e-mail inschakelen',
	'config-enable-email-help' => "Als u wilt dat e-mailen mogelijk is, dan moeten [http://www.php.net/manual/en/mail.configuration.php PHP's e-mailinstellingen] correct zijn.
Als u niet wilt dat e-mailen mogelijk is, dan kunt u de instellingen hier uitschakelen.",
	'config-email-user' => 'E-mail tussen gebruikers inschakelen',
	'config-email-user-help' => 'Gebruikers toestaan e-mail aan elkaar te verzenden als dit in de voorkeuren is ingesteld.',
	'config-email-usertalk' => 'Gebruikersoverlegnotificatie inschakelen',
	'config-email-usertalk-help' => 'Gebruikers toestaan notificaties te ontvangen bij wijzigingen op de eigen overlegpagina als dit in de voorkeuren is ingesteld',
	'config-email-watchlist' => 'Volglijstnotificatie inschakelen',
	'config-email-watchlist-help' => "Gebruikers toestaan notificaties te ontvangen bij wijzigingen van pagina's op hun volglijst als dit in de voorkeuren is ingesteld",
	'config-email-auth' => 'E-mailbevestiging inschakelen',
	'config-email-auth-help' => "Als deze instelling actief is, moeten gebruikers hun e-mailadres bevestigen via een verwijziging die ze per e-mail wordt toegezonden.
Alleen bevestigde e-mailadressen kunnen e-mail ontvangen van andere gebruikers of wijzigingsnotificaties ontvangen.
Het inschakelen van deze instelling is '''aan te raden''' voor openbare wiki's vanwege de mogelijkheden voor misbruik van e-mailmogelijkheden.",
	'config-email-sender' => 'E-mailadres voor antwoorden:',
	'config-email-sender-help' => 'Voer het e-mailadres in dat u wilt gebruiken als antwoordadres voor uitgaande e-mail.
Als een e-mail niet bezorgd kan worden, wordt dat op dit e-mailadres gemeld.
Veel mailservers vereisen dat tenminste het domein bestaat.',
	'config-upload-settings' => 'Afbeeldingen en bestanden uploaden',
	'config-upload-enable' => 'Uploaden van bestanden inschakelen',
	'config-upload-help' => "Het uploaden van bestanden stelt uw server mogelijk bloot aan beveiligingsrisico's.
Er is meer [http://www.mediawiki.org/wiki/Manual:Security informatie over beveiliging] beschikbaar in de handleiding. 

Om het bestandsuploads mogelijk te maken kunt u de rechten op de submap <code>images</code> onder de hoofdmap van MediaWiki aanpassen, zodat de webserver erin kan schrijven. 
Daarmee wordt deze functie ingeschakeld.",
	'config-upload-deleted' => 'Map voor verwijderde bestanden:',
	'config-upload-deleted-help' => 'Kies een map waarin verwijderde bestanden gearchiveerd kunnen worden.
Idealiter is deze map niet via het web te benaderen.',
	'config-logo' => 'URL voor logo:',
	'config-logo-help' => 'Het standaarduiterlijk van MediaWiki bevat ruimte voor een logo van 135x135 pixels in de linker bovenhoek.
Upload een afbeelding met de juiste afmetingen en voer de URL hier in.

Als u geen logo wilt gebruiken, kunt u dit veld leeg laten.',
	'config-instantcommons' => 'Instant Commons inschakelen',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] is functie die het mogelijk maakt om afbeeldingen, geluidsbestanden en andere mediabestanden te gebruiken van de website [http://commons.wikimedia.org/ Wikimedia Commons].
Hiervoor heeft MediaWiki toegang nodig tot Internet. $1

Meer informatie over deze functie en hoe deze in te stellen voor andere wiki\'s dan Wikimedia Commons is te vinden in de [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos handleiding].',
	'config-instantcommons-good' => 'Het installatieprogramma heeft internetconnectiviteit gedetecteerd tijdens het controleren van de omgeving.
U kunt deze functie inschakelen als u wilt.',
	'config-instantcommons-bad' => "''Helaas was het installatieprogramma tijdens de controle van de omgeving niet in staat te detecteren of er verbinding is met internet, dus u kunt deze functie mogelijk niet gebruiken.
Als uw server zich achter een proxy bevindt, moet u wellicht een aantal [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy extra instellingen maken].''",
	'config-cc-error' => 'De licentiekiezer van Creative Commons heeft geen resultaat opgeleverd.
Voer de licentie handmatig in.',
	'config-cc-again' => 'Opnieuw kiezen...',
	'config-cc-not-chosen' => 'Kies alstublieft de Creative Commons-licentie die u wilt gebruiken en klik op "doorgaan".',
	'config-advanced-settings' => 'Gevorderde instellingen',
	'config-cache-options' => 'Instellingen voor het cachen van objecten:',
	'config-cache-help' => 'Het cachen van objecten wordt gebruikt om de snelheid van MediaWiki te verbeteren door vaak gebruikte gegevens te bewaren.
Middelgrote tot grote websites wordt geadviseerd dit in te schakelen en ook kleine sites merken de voordelen.',
	'config-cache-none' => 'Niets cachen.
Er gaat geen functionaliteit verloren, maar dit kan invloed hebben op de snelheid.',
	'config-cache-accel' => 'Cachen van objecten via PHP (APC, eAccelerator, XCache of WinCache)',
	'config-cache-memcached' => 'Memcached gebruiken (dit vereist aanvullende instellingen)',
	'config-memcached-servers' => 'Memcachedservers:',
	'config-memcached-help' => "Lijst met IP-adressen te gebruiken voor Memcached.
Deze moeten worden gescheiden met komma's en geef de poort op die moet worden gebruikt (bijvoorbeeld: 127.0.0.1:11211, 192.168.1.25:11211).",
	'config-extensions' => 'Uitbreidingen',
	'config-extensions-help' => 'De bovenstaande uitbreidingen zijn aangetroffen in de map <code>./extensions</code>.

Mogelijk moet u aanvullende instellingen maken, maar u kunt deze uitbreidingen nu inschakelen.',
	'config-install-alreadydone' => "'''Waarschuwing:''' het lijkt alsof u MediaWiki al hebt geïnstalleerd en probeert het programma opnieuw te installeren.
Ga alstublieft door naar de volgende pagina.",
	'config-install-step-done' => 'Afgerond',
	'config-install-step-failed' => 'Mislukt',
	'config-install-extensions' => 'Inclusief uitbreidingen',
	'config-install-database' => 'Database inrichten',
	'config-install-pg-schema-failed' => 'Het aanmaken van de tabellen is mislukt.
Zorg dat de gebruiker "$1" in het schema "$2" mag schrijven.',
	'config-install-user' => 'Databasegebruiker aan het aanmaken',
	'config-install-user-failed' => 'Het geven van rechten aan gebruiker "$1" is mislukt: $2',
	'config-install-tables' => 'Tabellen aanmaken',
	'config-install-tables-exist' => "'''Waarschuwing''': de MediaWiki-tabellen lijken al te bestaan. 
Het aanmaken wordt overgeslagen.",
	'config-install-tables-failed' => "'''Fout''': het aanmaken van een tabel is mislukt met de volgende foutmelding: $1",
	'config-install-interwiki' => 'Bezig met het vullen van de interwikitabel',
	'config-install-interwiki-sql' => 'Het bestand <code>interwiki.sql</code> is niet aangetroffen',
	'config-install-interwiki-exists' => "'''Waarschuwing''': de interwikitabel heeft al inhoud. 
De standaardlijst wordt overgeslagen.",
	'config-install-secretkey' => 'Geheime sleutel aanmaken',
	'config-insecure-secretkey' => 'Waarschuwing: het was niet mogelijk een veilige <code>$wgSecretKey</code> aan te maken.
Overweeg deze handmatig te wijzigen.',
	'config-install-sysop' => 'Gebruiker voor beheerder aanmaken',
	'config-install-done' => "'''Gefeliciteerd!'''
U hebt MediaWiki met succes geïnstalleerd.

Het installatieprogramma heeft het bestand <code>LocalSettings.php</code> aangemaakt.
Dit bevat al uw instellingen.

U moet [$1 het bestand downloaden] en in de hoofdmap van uw wiki-installatie plaatsten; in dezelfde map als index.php.
'''Let op''': als u dit niet nu doet, dan het is bestand als u later de installatieprocedure afsluit zonder het bestand te downloaden niet meer beschikbaar.

Na het plaatsen van het bestand met instellingen kunt u '''[$2 uw wiki betreden]'''.",
);

/** Russian (Русский)
 * @author Eleferen
 * @author MaxSem
 */
$messages['ru'] = array(
	'config-desc' => 'Инсталлятор MediaWiki',
	'config-title' => 'Установка MediaWiki $1',
	'config-information' => 'Информация',
	'config-no-session' => 'Данные сессии потеряны! 
Проверьте ваш php.ini и убедитесь, что <code>session.save_path</code> установлен в соответствующий каталог.',
	'config-show-help' => 'Справка',
	'config-hide-help' => 'Скрыть справку',
	'config-your-language' => 'Ваш язык:',
	'config-your-language-help' => 'Выберите язык, на котором будет происходить процесс установки.',
	'config-wiki-language' => 'Язык, который будет использовать вики:',
	'config-back' => '← Назад',
	'config-continue' => 'Далее →',
	'config-page-language' => 'Язык',
	'config-page-welcome' => 'Добро пожаловать в MediaWiki!',
	'config-page-dbconnect' => 'Подключение к базе данных',
	'config-page-dbsettings' => 'Настройки базы данных',
	'config-page-name' => 'Название',
	'config-page-options' => 'Настройки',
	'config-page-install' => 'Установка',
	'config-page-complete' => 'Готово!',
	'config-page-restart' => 'Начать установку заново',
	'config-page-readme' => 'Прочти меня',
	'config-page-copying' => 'Копирование',
	'config-help-restart' => 'Вы хотите удалить все сохранённые данные, которые вы ввели, и запустить процесс установки заново?',
	'config-restart' => 'Да, начать заново',
	'config-sidebar' => '* [http://www.mediawiki.org Сайт MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents/ru Справка для пользователей]
* [http://www.mediawiki.org/wiki/Manual:Contents/ru Справка для администраторов]
* [http://www.mediawiki.org/wiki/Manual:FAQ/ru FAQ]',
	'config-env-latest-old' => "'''Внимание:''' Вы устанавливаете устаревшую версию MediaWiki.",
	'config-pcre' => 'Модуль поддержки PCRE не найден. 
Для работы MediaWiki требуется поддержка Perl-совместимых регулярных выражений.',
	'config-xcache' => 'Установлен [http://trac.lighttpd.net/xcache/ XCache]',
	'config-apc' => 'Установлен [http://www.php.net/apc APC]',
	'config-eaccel' => 'Установлен [http://eaccelerator.sourceforge.net/ eAccelerator]',
	'config-wincache' => 'Установлен [http://www.iis.net/download/WinCacheForPhp WinCache]',
	'config-no-cache' => "'''Внимание:''' Не найдены [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] или [http://www.iis.net/download/WinCacheForPhp WinCache].
Кэширование объектов будет отключено.",
	'config-diff3-good' => 'Найден GNU diff3: <code>$1</code> .',
	'config-diff3-bad' => 'GNU diff3 не найден.',
	'config-db-type' => 'Тип базы данных:',
	'config-db-name' => 'Имя базы данных:',
	'config-db-username' => 'Имя пользователя базы данных:',
	'config-db-password' => 'Пароль базы данных:',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-db-port' => 'Порт базы данных:',
	'config-sqlite-dir' => 'Директория данных SQLite:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Настройки MySQL',
	'config-header-postgres' => 'Настройки PostgreSQL',
	'config-header-sqlite' => 'Настройки SQLite',
	'config-header-oracle' => 'Настройки Oracle',
	'config-invalid-db-type' => 'Неверный тип базы данных',
	'config-missing-db-name' => 'Вы должны ввести значение параметра «Имя базы данных»',
	'config-invalid-db-name' => 'Неверное имя базы данных «$1».
Имя может содержать только цифры, буквы и знаки подчёркивания.',
	'config-invalid-db-prefix' => 'Неверный префикс базы данных «$1».
Префикс может содержать только цифры, буквы и знаки подчёркивания.',
	'config-sqlite-mkdir-error' => 'Ошибка при создании каталога данных «$1».
Проверьте расположение и повторите попытку.',
	'config-sqlite-cant-create-db' => 'Не удаётся создать файл базы данных <code>$1</code> .',
	'config-sqlite-fts3-downgrade' => 'У PHP отсутствует поддержка FTS3 — сбрасываем таблицы',
	'config-regenerate' => 'Создать LocalSettings.php заново →',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Название вики:',
	'config-site-name-help' => 'Название будет отображаться в заголовке окна браузера и в некоторых других местах вики.',
	'config-site-name-blank' => 'Введите название сайта.',
	'config-admin-box' => 'Учётная запись администратора',
	'config-admin-name' => 'Имя:',
	'config-admin-password' => 'Пароль:',
	'config-admin-password-confirm' => 'Пароль ещё раз:',
	'config-admin-help' => 'Введите ваше имя пользователя здесь, например, «Иван Иванов».
Это имя будет использоваться для входа в вики.',
	'config-admin-name-blank' => 'Введите имя пользователя администратора.',
	'config-admin-password-blank' => 'Введите пароль для учётной записи администратора.',
	'config-admin-password-same' => 'Пароль не должен быть таким же, как имя пользователя.',
	'config-admin-password-mismatch' => 'Введённые вами пароли не совпадают.',
	'config-admin-email' => 'Адрес электронной почты:',
	'config-optional-skip' => 'Хватит, установить вики',
	'config-license' => 'Авторские права и лицензии:',
	'config-email-settings' => 'Настройки электронной почты',
	'config-email-user' => 'Включить электронную почту от участника к участнику',
	'config-install-tables' => 'Создание таблиц',
	'config-install-interwiki-sql' => 'Не удалось найти файл <code>interwiki.sql</code>.',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'config-information' => 'సమాచారం',
	'config-show-help' => 'సహాయం',
	'config-your-language' => 'మీ భాష:',
	'config-wiki-language' => 'వికీ భాష:',
	'config-back' => '← వెనక్కి',
	'config-page-language' => 'భాష',
	'config-page-name' => 'పేరు',
	'config-page-install' => 'స్థాపించు',
	'config-page-complete' => 'పూర్తయ్యింది!',
	'config-site-name' => 'వికీ యొక్క పేరు:',
	'config-admin-name' => 'మీ పేరు:',
	'config-admin-password' => 'సంకేతపదం:',
	'config-admin-password-confirm' => 'సంకేతపదం మళ్ళీ:',
	'config-email-settings' => 'ఈ-మెయిల్ అమరికలు',
);

/** Ukrainian (Українська)
 * @author Тест
 */
$messages['uk'] = array(
	'config-desc' => 'Інсталятор MediaWiki',
	'config-title' => 'Встановлення MediaWiki $1',
	'config-information' => 'Інформація',
	'config-session-error' => 'Помилка початку сесії: $1',
	'config-show-help' => 'Допомога',
	'config-your-language' => 'Ваша мова:',
	'config-your-language-help' => 'Оберіть мову для використання в процесі установки.',
	'config-wiki-language' => 'Мова для вікі:',
	'config-back' => '← Назад',
	'config-continue' => 'Далі →',
	'config-page-language' => 'Мова',
	'config-page-name' => 'Назва',
	'config-page-options' => 'Параметри',
);

