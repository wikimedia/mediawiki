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
Please move <code>LocalSettings.php</code> to somewhere safe in then run the installer again.",
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
	'config-env-php'                  => 'PHP $1 installed.',
	'config-env-latest-ok'            => 'You are installing the latest version of Mediawiki.',
	'config-env-latest-new'           => "'''Note:''' You are installing a development version of Mediawiki.",
	'config-env-latest-can-not-check' => "'''Note:''' The installer was unable to retrieve information about the latest MediaWiki release from [$1].",
	'config-env-latest-data-invalid'  => "'''Warning:''' When trying to check if this version was outdated invalid data was retrieved from [$1].",
	'config-env-latest-old'           => "'''Warning:''' You are installing an outdated version of Mediawiki.",
	'config-env-latest-help'          => 'You are installing version $1, but the latest version is $2.
You are advised to use the latest release, which can be downloaded from [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php'            => 'Using the slow PHP implementation for Unicode normalization.',
	'config-unicode-using-utf8'            => 'Using Brion Vibber\'s utf8_normalize.so for Unicode normalization.',
	'config-unicode-using-intl'            => 'Using the [http://pecl.php.net/intl intl PECL extension] for Unicode normalization.',
	'config-unicode-pure-php-warning' => "'''Warning''': The [http://pecl.php.net/intl intl PECL extension] is not available to handle Unicode normalization.  If you run a high-traffic site, you should read a little on [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode normalization].",
	'config-unicode-update-warning'   => "'''Warning''': The installed version of the Unicode normalization wrapper uses an older version of [http://site.icu-project.org/ the ICU project's] library.  You should [http://www.mediawiki.org/wiki/Unicode_normalization_considerations upgrade] if you are at all concerned about using Unicode.",
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
	'config-xcache'                   => '[http://trac.lighttpd.net/xcache/ XCache] installed',
	'config-apc'                      => '[http://www.php.net/apc APC] installed',
	'config-eaccel'                   => '[http://eaccelerator.sourceforge.net/ eAccelerator] installed',
	'config-wincache'                 => '[http://www.iis.net/download/WinCacheForPhp WinCache] installed',
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
That includes raw user data (E-Mails, hashed passwords) as well as deleted revisions and other restricted data on the wiki.

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

A '''traditional wiki''' allows anyone to edit, without even logging in.
A wiki with '''account creation required''' provides extra accountability, but may deter casual contributors.

The '''authorized editors only''' scenario allows approved users to edit, but the public can view the pages, including history.
A '''private wiki''' only allows approved users to view pages, with the same group allowed to edit.

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
If your server is behind a proxy, you may need to do some [http://www.mediawiki.org/wiki/Manual:$wgHTTPProxy additional configuration]\'\'',
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
	'config-install-alreadydone'      => "'''Warning: You seem to have already installed MediaWiki and are trying to install it again.
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

When that has been done, you can [$2 '''enter your wiki'''].", // $1 is the URL to LocalSettings download, $2 is link to wiki
);

/** Message documentation
 * @author Siebrand
 */
$messages['qqq'] = array(
	'config-title'                    => 'Parameters:
* $1 is the version of MediaWiki that is being installed.',
	'config-session-error'            => 'Parameters:
* $1 is the error that was encountered with the session.',
	'config-session-expired'          => 'Parameters:
* $1 is the configured session lifetime.',
	'config-session-path-bad'         => 'Parameters:
* $1 is the configured <code>session.save_path</code>.',
	'config-restart'                  => 'Button text to confirm the installation procedure has to be restarted.',
	'config-env-php'                  => 'Parameters:
* $1 is the version of PHP that has been installed.',
	'config-env-latest-data-invalid'  => 'Parameters:
* $1 is the URL from which invalid data was retrieved.',
	'config-env-latest-old'           => 'Parameters:
* $1 is the version of MediaWiki being installed.
* $2 is the latest available stable MediaWiki version.',
	'config-no-db-help'               => 'Parameters:
* $1 is comma separated list of supported database types by MediaWiki.',
	'config-have-db'                => 'Parameters:
* $1 is comma separated list of database drivers found in the application environment.',
	'config-memory-ok'                => 'Parameters:
* $1 is the configured <code>memory_limit</code>.',
	'config-memory-raised'            => 'Parameters:
* $1 is the configured <code>memory_limit</code>.
* $2 is the value to which <code>memory_limit</code> was raised.',
	'config-memory-bad'               => 'Parameters:
* $1 is the configured <code>memory_limit</code>.',
	'config-diff3-good'               => 'Parameters:
* $1 is the path to diff3.',
	'config-dir'                      => 'Parameters:
* $1 is the path of the installation directory.',
	'config-dir'                      => 'Parameters:
* $1 is script URI path.',
	'config-shell-locale'             => 'Parameters:
* $1 is the detected shell locale.',
	'config-show-table-status'        => '{{doc-important|"SHOW TABLE STATUS" is a MySQL command. Do not translate this.}}',
	'config-install-done'             => 'Parameters:
* $1 is the URL to LocalSettings download
* $2 is a link to the wiki.',
	'config-instantcommons-help'      => 'Parameters:
$1 is for indicating whether or not we should be able to use the feature.',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'config-title' => 'Installatie MediaWiki $1',
	'config-information' => 'Informatie',
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
	'config-page-upgrade' => 'Bestaande bijwerken',
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
	'config-authors' => 'MediaWiki is Copyright © 2001-2010 door Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe en anderen.',
	'config-env-good' => 'De omgeving is gecontroleerd.
U kunt MediaWiki installeren.',
	'config-env-bad' => 'De omgeving is gecontroleerd.
U kunt MediaWiki niet installeren.',
	'config-env-php' => 'PHP $1 is geïnstalleerd.',
	'config-env-latest-ok' => 'U bent bezig de meest recente versie van MediaWiki te installeren.',
	'config-env-latest-new' => "'''Let op:''' U bent bezig een ontwikkelversie van MediaWiki te installeren.",
	'config-env-latest-old' => "'''Waarschuwing:''' U bent bezig een verouderde versie van MediaWiki te installeren.",
	'config-env-latest-help' => 'U bent bezig versie $1 te installeren, maar de meest recente versie is $2.
U wordt aangeraden de meest recente versie te gebruiken die u kunt downloaden van [http://www.mediawiki.org/wiki/Download mediawiki.org].',
	'config-no-db' => 'Er kon geen geschikte databasedriver geladen worden!',
	'config-no-db-help' => 'U moet een databasedriver installeren voor PHP.
De volgende databases worden ondersteund: $1.

Als u op een gedeelde omgeving zit, vraag dan uw hostingprovider een geschikte databasedriver te installeren.
Als u PHP zelf hebt gecompileerd, wijzig dan uw instellingen zodat een databasedriver wordt geactiveerd, bijvoorbeeld via <code>./configure --with-mysql</code>.',
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
'''PHP's [http://www.php.net/features.safe-mode veilige modus] actief is.'''
Dit kan problemen veroorzaken, vooral bij het uploaden van bestanden en ondersteuning van <code>math</code>.",
	'config-xml-bad' => 'De XML-module van PHP ontbreekt.
MediaWiki heeft de functies van deze module nodig en werkt niet zonder deze module.',
	'config-pcre' => 'De ondersteuningsmodule PCRE lijkt te missen.
MediaWiki vereist dat de met Perl compatibele reguliere expressies werken.',
	'config-memory-none' => 'PHP is ingesteld zonder <code>memory_limit</code>',
	'config-memory-ok' => "PHP's <code>memory_limit</code> is $1. In orde.",
	'config-memory-raised' => "PHP's <code>memory_limit</code> is $1. Verhoogd tot $2.",
	'config-memory-bad' => "'''Waarschuwing:''' PHP's <code>memory_limit</code> is $1.
Dit is waarschijnlijk te laag.
De installatie kan mislukken!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] geïnstalleerd',
	'config-apc' => '[http://www.php.net/apc APC] geïnstalleerd',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] geïnstalleerd',
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
	'config-mysql-old' => 'U moet MySQL $1 of later gebruiken.
U gebruikt $2.',
	'config-db-port' => 'Databasepoort:',
	'config-db-schema' => 'Schema voor MediaWiki',
	'config-db-ts2-schema' => 'Schema voor tsearch2',
	'config-db-schema-help' => "De bovenstaande schema's kloppen meestal.
Wijzig ze alleen als u weet dat u ze nodig hebt.",
	'config-sqlite-dir' => 'Gegevensmap voor SQLite:',
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
	'config-sqlite-mkdir-error' => 'Er is een fout opgetreden bij het aanmaken van de gegevensmap "$1".
Controleer de locatie en probeer het opnieuw.',
	'config-sqlite-dir-unwritable' => 'Het was niet mogelijk in de map "$1" te schrijven.
Wijzig de rechten zodat de webserver erin kan schrijven en probeer het opnieuw.',
	'config-sqlite-connection-error' => '$1.

Controleer de map voor gegevens en de databasenaam hieronder en probeer het opnieuw.',
	'config-sqlite-readonly' => 'Het bestand <code>$1</code> kan niet geschreven worden.',
	'config-sqlite-cant-create-db' => 'Het was niet mogelijk het databasebestand <code>$1</code> aan te maken.',
	'config-can-upgrade' => "Er staan al tabellen voor MediaWiki in deze database.
Klik op '''Doorgaan''' om ze bij te werken naar MediaWiki $1.",
	'config-upgrade-done' => "Het bijwerken is afgerond.

Uw kunt [$1 uw wiki gebruiken].

Als u uw <code>LocalSettings.php</code> opnieuw wilt aanmaken, klik dan op de knop hieronder.
Dit is '''niet aan te raden''' tenzij u problemen hebt met uw wiki.",
	'config-regenerate' => 'LocalSettings.php opnieuw aanmaken →',
	'config-show-table-status' => 'Het uitvoeren van SHOW TABLE STATUS is mislukt!',
	'config-unknown-collation' => "'''Waarschiwing:''' de database gebruikt een collatie die niet wordt herkend.",
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
	'config-admin-box' => 'Beheerdersaccount',
	'config-admin-name' => 'Uw naam:',
	'config-admin-password' => 'Wachtwoord:',
	'config-admin-password-confirm' => 'Wachtwoord opnieuw:',
	'config-admin-help' => 'Voor de gebruikersnaam hier in, bijvoorbeeld "Jan Jansen".
Dit is de naam die wordt gebruikt om aan de melden bij de wiki.',
	'config-admin-name-blank' => 'Geef een gebruikersnaam op voor de beheerder.',
	'config-admin-name-invalid' => 'De opgegeven gebruikersnaam "<nowiki>$1</nowiki>" is ongeldig.
Kies een andere gebruikersnaam.',
	'config-admin-password-blank' => 'Voer een wachtwoord voor de beheerder in.',
	'config-admin-password-same' => 'Het wachtwoord mag niet hetzelfde zijn als de gebruikersnaam.',
	'config-admin-password-mismatch' => 'De twee door u ingevoerde wachtwoorden komen niet overeen.',
	'config-admin-email' => 'E-mailadres:',
	'config-admin-email-help' => "Voer hier een e-mailadres in om e-mail te kunnen ontvangen van andere gebruikers op de wiki, uw wachtwoord opnieuw in te stellen en op de hoogte te worden gehouden van wijzigingen van pagina's op uw volglijst.",
	'config-subscribe' => 'Abonneren op de [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce mailinglijst releaseaankondigen].',
	'config-subscribe-help' => 'Dit is een mailinglijst met een laag volume voor aankondigingen van nieuwe versies, inclusief belangrijke aankondigingen met betrekking tot beveiliging.
Abonneer uzelf erop en werk uw MediaWiki-installatie bij als er nieuwe versies uitkomen.',
	'config-almost-done' => 'U bent bijna klaar!
Als u wilt kunt u de overige instellingen overslaan en de wiki nu installeren.',
	'config-optional-continue' => 'Meer vragen',
	'config-optional-skip' => 'Installeer de wiki',
	'config-profile' => 'Gebruikersrechtenprofiel:',
	'config-profile-wiki' => 'Traditionele wiki',
	'config-profile-no-anon' => 'Gebruiker aanmaken verplicht',
	'config-profile-private' => 'Privéwiki',
	'config-license' => 'Auteursrechten en licentie:',
	'config-license-none' => 'Geen licentie in de voettekst',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2 of hoger',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 of hoger',
	'config-license-pd' => 'Publiek domein',
	'config-license-cc-choose' => 'Een Creative Commons-licentie',
	'config-email-settings' => 'E-mailinstellingen',
	'config-enable-email' => 'Uitgaande e-mail inschakelen',
	'config-enable-email-help' => "Als u wilt dat e-mailen mogelijk is, dan moeten [http://www.php.net/manual/en/mail.configuration.php PHP's e-mailinstellingen] correct zijn.
Als u niet wilt dat e-mailen mogelijk is, dan kunt u de instellingen hier uitschakelen.",
	'config-email-user' => 'E-mail tussen gebruikers inschakelen',
	'config-email-user-help' => 'Gebruikers toestaan e-mail aan elkaar te verzenden als dit in de voorkeuren is ingesteld',
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
	'config-upload-deleted' => 'Map voor verwijderde bestanden:',
	'config-upload-deleted-help' => 'Kies een map waarin verwijderde bestanden gearchiveerd kunnen worden.
Idealiter is deze map niet via het web te benaderen.',
	'config-logo' => 'URL voor logo:',
	'config-logo-help' => 'Het standaarduiterlijk van MediaWiki bevat ruimte voor een logo van 135x135 pixels in de linker bovenhoek.
Upload een afbeelding met de juiste afmetingen en voer de URL hier in.

Als u geen logo wilt gebruiken, kunt u dit veld leeg laten.',
	'config-cc-error' => 'De licentiekiezer van Creative Commons heeft geen resultaat opgeleverd.
Voer de licentie handmatig in.',
	'config-cc-not-chosen' => 'Kies alstublieft de Creative Commons-licentie die u wilt gebruiken en klik op "doorgaan".',
	'config-advanced-settings' => 'Gevorderde instellingen',
	'config-cache-options' => 'Instellingen voor het cachen van objecten:',
	'config-cache-help' => 'Het cachen van objecten wordt gebruikt om de snelheid van MediaWiki te verbeteren door vaak gebruikte gegevens te bewaren.
Middelgrote tot grote websites wordt geadviseerd dit in te schakelen en ook kleine sites merken de voordelen.',
	'config-cache-none' => 'Niets cachen.
Er gaat geen functionaliteit verloren, maar dit kan invloed hebben op de snelheid.',
	'config-cache-accel' => 'Cachen van objecten via PHP (APC, eAccelerator or XCache)',
	'config-cache-memcached' => 'Memcached gebruiken (dit vereist aanvullende instellingen)',
	'config-cache-db' => 'Gegevens cachen in de database',
	'config-cache-anything' => 'MediaWiki zal proberen de gegevens te cachen waar mogelijk, behalve in Memcached, tenzij expliciet aangegeven',
	'config-memcached-servers' => 'Memcachedservers:',
	'config-memcached-help' => "Lijst met IP-adressen te gebruiken voor Memcached.
Deze moeten worden gescheiden met komma's en geef de poort op die moet worden gebruikt (bijvoorbeeld: 1.2.3.4:56, 7.8.9.10:11).",
	'config-extensions' => 'Uitbreidingen',
	'config-extensions-help' => 'De bovenstaande uitbreidingen zijn aangetroffen in de map <code>./extensions</code>.

Mogelijk moet u aanvullende instellingen maken, maar u kunt deze uitbreidingen nu inschakelen.',
	'config-install-step-done' => 'Afgerond',
	'config-install-step-failed' => 'Mislukt',
	'config-install-extensions' => 'Inclusief uitbreidingen',
	'config-install-database' => 'Database inrichten',
	'config-install-pg-schema-failed' => 'Het aanmaken van de tabellen is mislukt.
Zorg dat de gebruiker "$1" in het schema "$2" mag schrijven.',
	'config-install-tables' => 'Tabellen aanmaken',
	'config-install-interwiki-sql' => 'Het bestand <code>interwiki.sql</code> is niet aangetroffen',
	'config-install-secretkey' => 'Geheime sleutel aanmaken',
	'config-insecure-secretkey' => 'Waarschuwing: het was niet mogelijk een veilige <code>$wgSecretKey</code> aan te maken.
Overweeg deze handmatig te wijzigen.',
	'config-install-sysop' => 'Gebruiker voor beheerder aanmaken',
	'config-install-localsettings' => '<code>LocalSettings.php</code> aanmaken',
	'config-install-localsettings-unwritable' => "'''Waarschuwing:''' het was niet mogelijk <code>LocalSettings.php</code> weg te schrijven.
Maak dit bestand zelf aan met de volgende inhoud:",
	'config-install-done-moved' => "'''Gefeliciteerd!'''
U hebt MediaWiki geïnstalleerd.

[$1 Naar uw wiki]",
);

/**
 * Russian (Русский)
 * @author MaxSem
 */
$messages['ru'] = array(
	'config-title'                  => 'Установка MediaWiki $1',
	'config-page-language'          => 'Язык',
);
