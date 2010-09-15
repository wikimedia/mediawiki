<?php
/**
 * Internationalization file for the install/upgrade process. None of the
 * messages used here are loaded during normal operations, only during
 * install and upgrade. So you should not put normal messages here.
 *
 * @file
 * @ingroup Deployment
 */

$messages = array();

/** English */
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
	'config-sidebar'                  => "* [http://www.mediawiki.org MediaWiki home]
* [http://www.mediawiki.org/wiki/Help:Contents User's Guide]
* [http://www.mediawiki.org/wiki/Manual:Contents Administrator's Guide]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]",
	'config-env-good'                 => '<span class="success-message">The environment has been checked.
You can install MediaWiki.</span>', // FIXME: take span out of message.
	'config-env-bad'                  => 'The environment has been checked.
You cannot install MediaWiki.',
	'config-env-php'                  => 'PHP $1 is installed.',
	'config-env-latest-ok'            => 'You are installing the latest version of MediaWiki.',
	'config-env-latest-new'           => "'''Note:''' You are installing a development version of MediaWiki.",
	'config-env-latest-can-not-check' => "'''Warning:''' The installer was unable to retrieve information about the latest MediaWiki release from [$1].",
	'config-env-latest-old'           => "'''Warning:''' You are installing an outdated version of MediaWiki.",
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
	'config-have-db'                  => 'Found database {{PLURAL:$2|driver|drivers}}: $1.',
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

If you are using shared web hosting, your hosting provider should give you the correct host name in their documentation.

If you are installing on a Windows server and using MySQL, using "localhost" may not work for the server name. If it does not, try "127.0.0.1" for the local IP address.',
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
	'config-charset-help'             => "'''Warning:''' If you use '''backwards-compatible UTF-8''' on MySQL 4.1+, and subsequently back up the database with <code>mysqldump</code>, it may destroy all non-ASCII characters, irreversibly corrupting your backups!

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

Consider putting the database somewhere else altogether, for example in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql'               => 'MySQL',
	'config-type-postgres'            => 'PostgreSQL',
	'config-type-sqlite'              => 'SQLite',
	'config-type-oracle'              => 'Oracle',
	'config-support-info'             => 'MediaWiki supports the following database systems:

$1

If you do not see the database system you are trying to use listed below, then follow the instructions linked above to enable support.',
	'config-support-mysql'            => '* $1 is the primary target for MediaWiki and is best supported ([http://www.php.net/manual/en/mysql.installation.php how to compile PHP with MySQL support])',
	'config-support-postgres'         => '* $1 is a popular open source database system as an alternative to MySQL ([http://www.php.net/manual/en/pgsql.installation.php how to compile PHP with PostgreSQL support])',
	'config-support-sqlite'           => '* $1 is a lightweight database system which is very well supported. ([http://www.php.net/manual/en/pdo.installation.php How to compile PHP with SQLite support], uses PDO)',
	'config-header-mysql'             => 'MySQL settings',
	'config-header-postgres'          => 'PostgreSQL settings',
	'config-header-sqlite'            => 'SQLite settings',
	'config-header-oracle'            => 'Oracle settings',
	'config-invalid-db-type'          => 'Invalid database type',
	'config-missing-db-name'          => 'You must enter a value for "Database name"',
	'config-invalid-db-name'          => 'Invalid database name "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).',
	'config-invalid-db-prefix'        => 'Invalid database prefix "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).',
	'config-connection-error'         => '$1.

Check the host, username and password below and try again.',
	'config-invalid-schema'           => 'Invalid schema for MediaWiki "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).',
	'config-invalid-ts2schema'        => 'Invalid schema for TSearch2 "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).',
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
	'config-can-upgrade'              => "There are MediaWiki tables in this database.
To upgrade them to MediaWiki $1, click '''Continue'''.",
	'config-upgrade-done'             => "Upgrade complete.

You can now [$1 start using your wiki].

If you want to regenerate your <code>LocalSettings.php</code> file, click the button below.
This is '''not recommended''' unless you are having problems with your wiki.",
	'config-regenerate'               => 'Regenerate LocalSettings.php →',
	'config-show-table-status'        => 'SHOW TABLE STATUS query failed!',
	'config-unknown-collation'        => "'''Warning:''' Database is using unrecognised collation.",
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
	'config-mysql-egine-mismatch'     => "'''Warning:''' you requested the $1 storage engine, but the existing database uses the $2 engine.
This upgrade script can't convert it, so it will remain $2.",
	'config-mysql-charset'            => 'Database character set:',
	'config-mysql-binary'             => 'Binary',
	'config-mysql-utf8'               => 'UTF-8',
	'config-mysql-charset-help'       => "In '''binary mode''', MediaWiki stores UTF-8 text to the database in binary fields.
This is more efficient than MySQL's UTF-8 mode, and allows you to use the full range of Unicode characters.

In '''UTF-8 mode''', MySQL will know what character set your data is in, and can present and convert it appropriately, but it will not let you store characters above the [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-mysql-charset-mismatch'   => "'''Warning:''' you requested the $1 schema, but the existing database has the $2 schema.
	This upgrade script can't convert it, so it will remain $2.",
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
So you have the choice.

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
	'config-logo-help'                => "MediaWiki's default skin includes space for a 135x160 pixel logo in the top left corner.
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
	'config-install-interwiki-exists' => "'''Warning''': The interwiki table seems to already have entries.
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
 * @author Nike
 * @author Raymond
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
	'config-page-copying' => 'This is a link to the full GPL text',
	'config-restart' => 'Button text to confirm the installation procedure has to be restarted.',
	'config-env-php' => 'Parameters:
* $1 is the version of PHP that has been installed.',
	'config-env-latest-old' => 'Parameters:
* $1 is the version of MediaWiki being installed.
* $2 is the latest available stable MediaWiki version.',
	'config-no-db-help' => 'Parameters:
* $1 is comma separated list of supported database types by MediaWiki.',
	'config-have-db' => 'Parameters:
* $1 is comma separated list of database drivers found in the application environment.
* $2 is the number of aforementioned drivers',
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
	'config-db-account-lock' => "It might be easier to translate ''normal operation'' as \"also after the installation process\"",
	'config-sqlite-dir-unwritable' => 'webserver refers to a software like Apache or Lighttpd.',
	'config-show-table-status' => '{{doc-important|"SHOW TABLE STATUS" is a MySQL command. Do not translate this.}}',
	'config-ns-generic' => '{{Identical|Project}}',
	'config-admin-name' => '{{Identical|Your name}}',
	'config-admin-password' => '{{Identical|Password}}',
	'config-admin-email' => '{{Identical|E-mail address}}',
	'config-profile-help' => 'Messages referenced:
* {{msg-mw|config-profile-wiki}}
* {{msg-mw|config-profile-no-anon}}
* {{msg-mw|config-profile-fishbowl}}
* {{msg-mw|config-profile-private}}',
	'config-instantcommons-help' => 'Parameters:
$1 is for indicating whether or not we should be able to use the feature.',
	'config-cc-not-chosen' => 'Should we use "int:key" here (and to which key)?',
	'config-extensions' => '{{Identical|Extension}}',
	'config-install-step-done' => '{{Identical|Done}}',
	'config-install-user' => 'Message indicates that the user is being created',
	'config-install-user-failed' => 'Parameters:
* $1 is the username for which granting rights failed
* $2 is the error message',
	'config-install-tables' => 'Message indicates that the tables are being created',
	'config-install-interwiki' => 'Message indicates that the interwikitables are being populated',
	'config-install-sysop' => 'Message indicates that the administrator user account is being created',
	'config-install-done' => 'Parameters:
* $1 is the URL to LocalSettings download
* $2 is a link to the wiki.',
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'config-ns-generic' => 'Projek',
);

/** Aragonese (Aragonés)
 * @author Juanpabl
 */
$messages['an'] = array(
	'config-show-help' => 'Aduya',
);

/** Belarusian (Taraškievica orthography) (Беларуская (тарашкевіца))
 * @author EugeneZelenko
 * @author Jim-by
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
	'config-copyright' => "== Аўтарскае права і ўмовы ==

$1

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but '''without any warranty'''; without even the implied warranty of '''merchantability''' or '''fitness for a particular purpose'''.
See the GNU General Public License for more details.

You should have received <doclink href=Copying>a copy of the GNU General Public License</doclink> along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. or [http://www.gnu.org/copyleft/gpl.html read it online].",
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
	'config-env-latest-can-not-check' => "'''Папярэджаньне:''' Праграма ўсталяваньня ня здолела атрымаць зьвесткі пра апошні выпуск MediaWiki з [$1].",
	'config-env-latest-old' => "'''Папярэджаньне:''' вы ўсталёўваеце састарэлую вэрсію MediaWiki.",
	'config-env-latest-help' => 'Вы ўсталёўваеце вэрсію $1, у той час як актуальнай зьяўляецца $2.
Пажадана ўсталяваць апошні выпуск, які можна загрузіць з [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Выкарыстоўваецца марудная рэалізацыя Unicode-нармалізацыі для PHP',
	'config-unicode-using-utf8' => 'Выкарыстоўваецца бібліятэка Unicode-нармалізацыі Браяна Вібэра',
	'config-unicode-using-intl' => 'Выкарыстоўваецца [http://pecl.php.net/intl intl пашырэньне з PECL] для Unicode-нармалізацыі',
	'config-unicode-pure-php-warning' => "'''Папярэджаньне''': [http://pecl.php.net/intl Пашырэньне intl з PECL] ня слушнае для Unicode-нармалізацыі.
Калі ў вас сайт з высокай наведваемасьцю, раім пачытаць пра [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-нармалізацыю].",
	'config-unicode-update-warning' => "'''Папярэджаньне''': усталяваная вэрсія бібліятэкі для Unicode-нармалізацыі выкарыстоўвае састарэлую вэрсію бібліятэкі з [http://site.icu-project.org/ праекту ICU].
Раім [http://www.mediawiki.org/wiki/Unicode_normalization_considerations абнавіць], калі ваш сайт будзе працаваць зь Unicode.",
	'config-no-db' => 'Немагчыма знайсьці слушны драйвэр базы зьвестак!',
	'config-no-db-help' => 'Вам трэба ўсталяваць драйвэр базы зьвестак для PHP.
Падтрымліваюцца наступныя тыпы базаў зьвестак: $1.

Калі вы выкарыстоўваеце агульны хостынг, запытайцеся ў свайго хостынг-правайдэра наконт усталяваньня патрабуемага драйвэр базы зьвестак.
Калі Вы кампілявалі PHP самастойна, пераканфігуруйце і сабярыце яго з уключаным кліентам базаў зьвестак, напрыклад, <code>./configure --with-mysql</code>.
Калі Вы ўсталёўвалі PHP з Debian/Ubuntu-рэпазытарыя, то вам трэба ўсталяваць дадаткова пакет <code>php5-mysql</code>',
	'config-have-db' => '{{PLURAL:$2|Знойдзены драйвэр|Знойдзеныя драйвэры}} базы зьвестак: $1.',
	'config-register-globals' => "'''Папярэджаньне: устаноўка PHP <code>[http://php.net/register_globals register_globals]</code> уключаная.'''
'''Адключыце яе, калі можаце.'''
MediaWiki будзе працаваць, але сэрвэр будзе ўтрымліваць прабалемы з бясьпекай.",
	'config-magic-quotes-runtime' => "'''Фатальная памылка: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] уключаны!'''
Гэтая устаноўка шкодзіць уводны паток зьвестак непрадказальным чынам.
Працяг усталяваньня альбо выкарыстаньне MediaWiki немагчымыя, пакуль устаноўка ня будзе выключаная.",
	'config-magic-quotes-sybase' => "'''Фатальная памылка: рэжым [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] уключаны!'''
Гэты рэжым шкодзіць уваходныя зьвесткі непрадказальным чынам.
Працяг усталяваньня альбо выкарыстаньне MediaWiki немагчымыя, пакуль рэжым ня будзе выключаны.",
	'config-mbstring' => "'''Фатальная памылка: рэжым [http://www.php.net/manual/en/ref.info.php#mbstring.overload mbstring.func_overload] уключаны!'''
Гэты рэжым выклікае памылкі і можа шкодзіць зьвесткі непрадказальным чынам.
Працяг усталяваньня альбо выкарыстаньне MediaWiki немагчымыя, пакуль рэжым ня будзе выключаны.",
	'config-ze1' => "'''Фатальная памылка: рэжым [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] уключаны!'''
Гэтая рэжым стварае вялікія праблемы ў працы MediaWiki.
Працяг усталяваньня альбо выкарыстаньне MediaWiki немагчымыя, пакуль рэжым ня будзе выключаны.",
	'config-safe-mode' => "'''Папярэджаньне:''' [http://www.php.net/features.safe-mode бясьпечны рэжым] PHP уключаны.
Гэта можа выклікаць праблемы, галоўным чынам падчас загрузак файлаў і ў падтрымцы <code>math</code>.",
	'config-xml-good' => 'Ёсьць падтрымка канвэртацыі XML / Latin1-UTF-8.',
	'config-xml-bad' => 'Ня знойдзены модуль XML для PHP.
MediaWiki патрэбныя функцыі з гэтага модулю, таму MediaWiki ня будзе працаваць у гэтай канфігурацыі.
Калі Вы выкарыстоўваеце Mandrake, усталюйце пакет php-xml.',
	'config-pcre' => 'Ня знойдзены модуль падтрымкі PCRE.
MediaWiki для працы патрабуюцца функцыі рэгулярных выразаў у стылі Perl.',
	'config-memory-none' => 'PHP сканфігураваны з устаноўкай <code>memory_limit</code>',
	'config-memory-ok' => 'Устаноўка <code>memory_limit</code> роўная $1.
Усё добра.',
	'config-memory-raised' => 'Устаноўка PHP <code>memory_limit</code> роўная $1, падвышаная да $2.',
	'config-memory-bad' => "'''Папярэджаньне:''' устаноўка PHP <code>memory_limit</code> роўная $1.
Верагодна, гэта вельмі замала.
Усталяваньне можа быць няўдалым!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] усталяваны',
	'config-apc' => '[http://www.php.net/apc APC] усталяваны',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] усталяваны',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] усталяваны',
	'config-no-cache' => "'''Папярэджаньне:''' немагчыма знайсьці [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] ці [http://www.iis.net/download/WinCacheForPhp WinCache].
Аб’ектнае кэшаваньне ня ўключанае.",
	'config-diff3-good' => 'Знойдзены GNU diff3: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 ня знойдзены.',
	'config-imagemagick' => 'Знойдзены ImageMagick: <code>$1</code>.
Пасьля ўключэньня загрузак будзе ўключанае маштабаваньне выяваў.',
	'config-gd' => 'GD падтрымліваецца ўбудавана.
Пасьля ўключэньня загрузак будзе ўключанае маштабаваньне выяваў.',
	'config-no-scaling' => 'Ні GD, ні ImageMagick ня знойдзеныя.
Маштабаваньне выяваў будзе адключанае.',
	'config-dir' => 'Дырэкторыя для усталяваньня: <code>$1</code>',
	'config-uri' => 'URI-шлях да скрыпта: <code>$1</code>.',
	'config-no-uri' => "'''Памылка:''' Не магчыма вызначыць цяперашні URI.
Усталяваньне спыненае.",
	'config-dir-not-writable-group' => "'''Памылка:''' немагчыма запісаць файл канфігурацыі.
Усталяваньне перарванае.

Праграма ўсталяваньня вызначыла імя карыстальніка, пад якім працуе вэб-сэрвэр.
Дайце дазвол на запіс у дырэкторыю <code><nowiki>config</nowiki></code> для працягненьня.
У Unix/Linux сыстэмах выканайце:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Памылка:''' немагчыма запісаць файл канфігурацыі.
Усталяваньне перарванае.

Не атрымалася вызначыць імя карыстальніка, пад якім працуе вэб-сэрвэр.
Дайце карыстальніку (і іншым) дазвол на запіс у дырэкторыю <code><nowiki>config</nowiki></code> для працягненьня.
У Unix/Linux выканайце:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'Усталяваньне MediaWiki з пашырэньнямі файлаў <code>$1</code>',
	'config-shell-locale' => 'Лякаль асяродзьдзя: «$1»',
	'config-uploads-safe' => 'У дырэкторыі для загрузак па змоўчваньні запуск скрыптоў забаронены.',
	'config-uploads-not-safe' => "'''Папярэджаньне:''' дырэкторыя для загрузак па змоўчваньні <code>$1</code> уразьлівая да выкананьня адвольнага коду.
Хоць MediaWiki і правярае ўсе файлы перад захаваньнем, вельмі рэкамэндуецца [http://www.mediawiki.org/wiki/Manual:Security#Upload_security закрыць гэтую ўразьлівасьць] перад уключэньнем магчымасьці загрузкі файлаў.",
	'config-db-type' => 'Тып базы зьвестак:',
	'config-db-host' => 'Хост базы зьвестак:',
	'config-db-host-help' => 'Калі Вашая база зьвестак знаходзіцца на іншым сэрвэры, увядзіце імя хоста ці IP-адрас.

Калі Вы набываеце shared-хостынг, Ваш хостынг-правайдэр мусіць даць Вам слушнае імя хоста базы зьвестак для выкарыстаньня.',
	'config-db-wiki-settings' => 'Ідэнтыфікацыя гэтай вікі',
	'config-db-name' => 'Назва базы зьвестак:',
	'config-db-name-help' => 'Выберыце імя, якое вызначыць Вашую вікі.
Яно ня мусіць зьмяшчаць прагалаў ці злучкоў.

Калі Вы набываеце shared-хостынг, Ваш хостынг-правайдэр мусіць надаць Вам ці пэўнае імя базы зьвестак для выкарыстаньня, ці магчымасьць ствараць базы зьвестак праз кантрольную панэль.',
	'config-db-install-account' => 'Імя карыстальніка для ўсталяваньня',
	'config-db-username' => 'Імя карыстальніка базы зьвестак:',
	'config-db-password' => 'Пароль базы зьвестак:',
	'config-db-install-help' => 'Увядзіце імя карыстальніка і пароль, якія будуць выкарыстаныя для далучэньня да базы зьвестак падчас працэсу ўсталяваньня.',
	'config-db-account-lock' => 'Выкарыстоўваць тыя ж імя карыстальніка і пароль пасьля ўсталяваньня',
	'config-db-wiki-account' => 'Імя карыстальніка для працы',
	'config-db-wiki-help' => 'Увядзіце імя карыстальніка і пароль, якія будуць выкарыстаныя для далучэньня да базы зьвестак падчас працы (пасьля ўсталяваньня).
Калі рахунак ня створаны, а рахунак для ўсталяваньня мае значныя правы, гэты рахунак будзе створаны зь мінімальна патрэбнымі для працы вікі правамі.',
	'config-db-prefix' => 'Прэфікс табліцаў базы зьвестак:',
	'config-db-prefix-help' => 'Калі Вы падзяляеце адну базу зьвестак паміж некалькімі вікі, ці паміж MediaWiki і іншым вэб-дастасаваньнем, можаце вызначыць прэфікс, які будзе выкарыстоўвацца ва ўсіх назваў табліцаў для пазьбяганьня канфліктаў.
Пазьбягайце прагалаў ці злучкоў.

Калі прэфікс не патрэбны, пакіньце поле пустым.',
	'config-db-charset' => 'Кадаваньне базы зьвестак',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binary',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 UTF-8 з адваротнай сумяшчальнасьцю',
	'config-charset-help' => "'''Папярэджаньне:''' калі Вы выкарыстоўваеце '''UTF-8 з адваротнай сумяшчальнасьцю''' на MySQL 4.1+ і зробіце рэзэрвовую копію праз <code>mysqldump</code>, ён можа зьнішчыць усе не-ASCII-сымбалі беспаваротна!

У '''бінарным (binary)''' рэжыме MediaWiki захоўвае тэксты ў UTF-8 у палёх тыпу binary.
Гэты рэжым болей эфэктыўны за рэжым MySQL UTF-8 і дазваляе выкарыстоўваць увесь абсяг сымбаляў Unicode.
У рэжыме '''UTF-8''' MySQL будзе ведаць, у якім кадаваньне Вы зьмяшчаеце зьвесткі, і будзе вяртаць іх у адпаведным кадаваньні,
але MySQL ня можа ўтрымліваць сымбалі па-за [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Стандартным шматмоўным пластом] сымбаляў Unicode.",
	'config-mysql-old' => 'Патрабуецца MySQL $1 ці навейшая, усталяваная вэрсія $2.',
	'config-db-port' => 'Порт базы зьвестак:',
	'config-db-schema' => 'Схема для MediaWiki',
	'config-db-ts2-schema' => 'Схема для tsearch2',
	'config-db-schema-help' => 'Вышэй пададзеныя схемы слушныя ў большасьці выпадкаў.
Зьмяняйце іх толькі тады, калі Вы ведаеце, што гэта неабходна.',
	'config-sqlite-dir' => 'Дырэкторыя зьвестак SQLite:',
	'config-sqlite-dir-help' => "SQLite захоўвае ўсе зьвесткі ў адзіным файле.

Пададзеная Вамі дырэкторыя павінна быць даступнай да запісу вэб-сэрвэрам падчас усталяваньня.

Яна '''ня''' мусіць быць даступнай праз Сеціва, вось чаму мы не захоўваем яе ў адным месцы з файламі PHP.

Праграма ўсталяваньня дадаткова створыць файл <code>.htaccess</code>, але калі ён не выкарыстоўваецца, хто заўгодна зможа атрымаць зьвесткі з базы зьвестак.
Гэта ўключае як прыватныя зьвесткі ўдзельнікаў (адрасы электроннай пошты, хэшы пароляў), гэтак і выдаленыя вэрсіі старонак і іншыя зьвесткі, доступ да якіх маецца абмежаваны.

Падумайце над тым, каб зьмяшчаць базу зьвестак у іншым месцы, напрыклад у <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki падтрымлівае наступныя сыстэмы базаў зьвестак:

$1

Калі Вы ня бачыце сыстэму базаў зьвестак, якую Вы спрабуеце выкарыстоўваць ў сьпісе ніжэй, перайдзіце па спасылцы інструкцыі, якая знаходзіцца ніжэй, каб уключыць падтрымку.',
	'config-support-mysql' => '* $1 зьяўляецца галоўнай мэтай MediaWiki і падтрымліваецца лепей за ўсё ([http://www.php.net/manual/en/mysql.installation.php як кампіляваць PHP з падтрымкай MySQL])',
	'config-support-postgres' => '* $1 — вядомая сыстэма базы зьвестак з адкрытым кодам, якая зьяўляецца альтэрнатывай MySQL ([http://www.php.net/manual/en/pgsql.installation.php як кампіляваць PHP з падтрымкай PostgreSQL])',
	'config-support-sqlite' => '* $1 — невялікая сыстэма базы зьвестак, якая мае вельмі добрую падтрымку. ([http://www.php.net/manual/en/pdo.installation.php як кампіляваць PHP з падтрымкай SQLite], выкарыстоўвае PDO)',
	'config-header-mysql' => 'Устаноўкі MySQL',
	'config-header-postgres' => 'Устаноўкі PostgreSQL',
	'config-header-sqlite' => 'Устаноўкі SQLite',
	'config-header-oracle' => 'Устаноўкі Oracle',
	'config-invalid-db-type' => 'Няслушны тып базы зьвестак',
	'config-missing-db-name' => 'Вы павінны ўвесьці значэньне парамэтру «Імя базы зьвестак»',
	'config-invalid-db-name' => 'Няслушная назва базы зьвестак «$1».
Назва можа ўтрымліваць толькі літары, лічбы і сымбалі падкрэсьліваньня.',
	'config-invalid-db-prefix' => 'Няслушны прэфікс табліцаў «$1».
Ён можа зьмяшчаць толькі літары, лічбы і сымбалі падкрэсьліваньня.',
	'config-connection-error' => '$1.

Праверце хост, імя карыстальніка і пароль ніжэй і паспрабуйце зноў.',
	'config-invalid-schema' => 'Няслушная схема «$1» для MediaWiki.
Выкарыстоўвайце толькі літары, лічбы і сымбалі падкрэсьліваньня.',
	'config-invalid-ts2schema' => 'Няслушная схема «$1» для TSearch2.
Выкарыстоўвайце толькі літары (a-z, A-Z), лічбы (0-9) і сымбалі падкрэсьліваньня (_).',
	'config-postgres-old' => 'Патрабуецца PostgreSQL $1 ці навейшая, усталяваная вэрсія $2.',
	'config-sqlite-name-help' => 'Выберыце назву, якая будзе ідэнтыфікаваць Вашую вікі.
Не выкарыстоўвайце прагалы ці злучкі.
Назва будзе выкарыстоўвацца ў назьве файла зьвестак SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Немагчыма стварыць дырэкторыю зьвестак <code><nowiki>$1</nowiki></code>, таму што бацькоўская дырэкторыя <code><nowiki>$2</nowiki></code> абароненая ад запісаў вэб-сэрвэра.

Праграма ўсталяваньня вызначыла карыстальніка, які запусьціў вэб-сэрвэр.
Дазвольце запісы ў дырэкторыю <code><nowiki>$3</nowiki></code> для працягу.
У сыстэме Unix/Linux зрабіце:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Немагчыма стварыць дырэкторыю зьвестак <code><nowiki>$1</nowiki></code>, таму што бацькоўская дырэкторыя <code><nowiki>$2</nowiki></code> абароненая ад запісаў вэб-сэрвэра.

Праграма ўсталяваньня вызначыла карыстальніка, які запусьціў вэб-сэрвэр.
Дазвольце яму (і іншым) запісы ў дырэкторыю <code><nowiki>$3</nowiki></code> для працягу.
У сыстэме Unix/Linux зрабіце:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Памылка падчас стварэньня дырэкторыі «$1».
Праверце шлях і паспрабуйце зноў.',
	'config-sqlite-dir-unwritable' => 'Немагчымы запіс у дырэкторыю «$1».
Зьмяніце ўстаноўкі доступу, каб вэб-сэрвэр мел правы на запіс, і паспрабуйце зноў.',
	'config-sqlite-connection-error' => '$1.

Праверце дырэкторыю для зьвестак, назву базы зьвестак і паспрабуйце зноў.',
	'config-sqlite-readonly' => 'Файл <code>$1</code> недаступны для запісу.',
	'config-sqlite-cant-create-db' => 'Немагчыма стварыць файл базы зьвестак <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP бракуе падтрымкі FTS3 — табліцы пагаршаюцца',
	'config-sqlite-fts3-add' => 'Дадаюцца пошукавыя магчымасьці FTS3',
	'config-can-upgrade' => "У гэтай базе зьвестак ёсьць табліцы MediaWiki.
Каб абнавіць іх да MediaWiki $1, націсьніце '''Працягнуць'''.",
	'config-upgrade-done' => "Абнаўленьне завершанае.

Цяпер Вы можаце [$1 пачаць выкарыстаньне вікі].

Калі Вы жадаеце рэгенэраваць <code>LocalSettings.php</code>, націсьніце кнопку ніжэй.
Гэтае дзеяньне '''не рэкамэндуецца''', калі Вы ня маеце праблемаў у працы вікі.",
	'config-regenerate' => 'Рэгенэраваць LocalSettings.php →',
	'config-show-table-status' => "Запыт 'SHOW TABLE STATUS' не атрымаўся!",
	'config-unknown-collation' => "'''Папярэджаньне:''' база зьвестак выкарыстоўвае нераспазнанае супастаўленьне.",
	'config-db-web-account' => 'Рахунак базы зьвестак для вэб-доступу',
	'config-db-web-help' => 'Выберыце імя карыстальніка і пароль, які выкарыстоўваецца вэб-сэрвэрам для злучэньня з сэрвэрам базы зьвестак, падчас звычайных апэрацыяў вікі.',
	'config-db-web-account-same' => 'Выкарыстоўваць той жа рахунак, што для ўсталяваньня',
	'config-db-web-create' => 'Стварыць рахунак, калі ён яшчэ не існуе',
	'config-db-web-no-create-privs' => 'Рахунак, які Вы пазначылі для ўсталяваньня ня мае правоў для стварэньня рахунку.
Рахунак, які Вы пазначылі тут, мусіць ужо існаваць.',
	'config-mysql-engine' => 'Рухавік сховішча:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' — звычайна найбольш слушны варыянт, таму што добра падтрымлівае паралелізм.

'''MyISAM''' можа быць хутчэйшай у вікі з адным удзельнікам, ці толькі для чытаньня.
Базы зьвестак на MyISAM вядомыя тым, што ў іх зьвесткі шкодзяцца нашмат часьцей за InnoDB.",
	'config-mysql-egine-mismatch' => "'''Папярэджаньне:''' Вы зрабілі запыт на рухавік сховішча $1, але існуючая база зьвестак выкарыстоўвае рухавік $2.
Гэтае абнаўленьне ня можа вырашыць гэтую праблему, рухавік сховішча застанецца $2.",
	'config-mysql-charset' => 'Кадаваньне базы зьвестак:',
	'config-mysql-binary' => 'Двайковае',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "У '''двайковым рэжыме''', MediaWiki захоўвае тэкст у кадаваньні UTF-8 у базе зьвестак у двайковых палях.
Гэта болей эфэктыўна за рэжым MySQL UTF-8, і дазваляе Вам выкарыстоўваць увесь дыяпазон сымбаляў Unicode.

У '''рэжыме UTF-8''', MySQL ведае, якая табліцы сымбаляў выкарыстоўваецца ў Вашых зьвестках, і можа адпаведна прадстаўляць і канвэртаваць іх, але гэта не дазволіць Вам захоўваць сымбалі па-за межамі [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Базавага шматмоўнага дыяпазону].",
	'config-mysql-charset-mismatch' => "'''Папярэджаньне:''' Вы зрабілі запыт на схему $1, але існуючая база зьвестак выкарыстоўвае схему $2.
Гэтае абнаўленьне ня можа вырашыць гэтую праблему, таму будзе пакінутая $2.",
	'config-site-name' => 'Назва вікі:',
	'config-site-name-help' => 'Назва будзе паказвацца ў загалоўку браўзэра і ў некаторых іншых месцах.',
	'config-site-name-blank' => 'Увядзіце назву сайта.',
	'config-project-namespace' => 'Прастора назваў праекту:',
	'config-ns-generic' => 'Праект',
	'config-ns-site-name' => 'Такая ж, як і назва вікі: $1',
	'config-ns-other' => 'Іншая (вызначце)',
	'config-ns-other-default' => 'MyWiki',
	'config-project-namespace-help' => "Па прыкладу Вікіпэдыі, шматлікія вікі трымаюць уласныя старонкі з правіламі асобна ад старонак са зьместам, у «'''прасторы назваў праекту'''».
Усе назвы старонак у гэтай прасторы назваў пачынаюцца з прыстаўкі, якую Вы можаце пазначыць тут.
Традыцыйна, гэтая прыстаўка вытворная ад назвы вікі, яле яна ня можа ўтрымліваць некаторыя сымбалі, такія як «#» ці «:».",
	'config-ns-invalid' => 'Пададзеная няслушная прастора назваў «<nowiki>$1</nowiki>».
Падайце іншую прастору назваў праекту.',
	'config-admin-box' => 'Рахунак адміністратара',
	'config-admin-name' => 'Вашае імя:',
	'config-admin-password' => 'Пароль:',
	'config-admin-password-confirm' => 'Пароль яшчэ раз:',
	'config-admin-help' => 'Увядзіце тут Вашае імя ўдзельніка, напрыклад «Янка Кавалевіч».
Гэтае імя будзе выкарыстоўвацца для ўваходу ў вікі.',
	'config-admin-name-blank' => 'Увядзіце імя адміністратара.',
	'config-admin-name-invalid' => 'Пададзенае няслушнае імя ўдзельніка «<nowiki>$1</nowiki>».
Падайце іншае імя ўдзельніка.',
	'config-admin-password-blank' => 'Увядзіце пароль рахунку адміністратара.',
	'config-admin-password-same' => 'Пароль ня можа быць аднолькавым зь іменем удзельніка.',
	'config-admin-password-mismatch' => 'Уведзеныя Вамі паролі не супадаюць.',
	'config-admin-email' => 'Адрас электроннай пошты:',
	'config-admin-email-help' => 'Увядзіце тут адрас электроннай пошты, каб атрымліваць электронныя лісты ад іншых удзельнікаў вікі, скідваць Ваш пароль і атрымліваць абвешчаньні пра зьмены старонак, якія знаходзяцца ў Вашым сьпісе назіраньня.',
	'config-admin-error-user' => 'Унутраная памылка падчас стварэньня рахунку адміністратара зь іменем «<nowiki>$1</nowiki>».',
	'config-admin-error-password' => 'Унутраная памылка падчас устаноўкі паролю для адміністратара «<nowiki>$1</nowiki>»: <pre>$2</pre>',
	'config-subscribe' => 'Падпісацца на [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce сьпіс распаўсюджаньня навінаў пра зьяўленьне новых вэрсіяў].',
	'config-subscribe-help' => 'Гэта ня вельмі актыўны сьпіс распаўсюджаньня навінаў пра зьяўленьне новых вэрсіяў, які ўключаючы важныя навіны пра бясьпеку.
Вам неабходна падпісацца на яго і абнавіць Вашае ўсталяваньне MediaWiki, калі зьявяцца новыя вэрсіі.',
	'config-almost-done' => 'Вы амаль што скончылі!
Цяпер Вы можаце прапусьціць астатнія ўстаноўкі і пачаць усталяваньне вікі.',
	'config-optional-continue' => 'Задаць болей пытаньняў.',
	'config-optional-skip' => 'Хопіць, проста ўсталяваць вікі.',
	'config-profile' => 'Профіль правоў удзельніка:',
	'config-profile-wiki' => 'Традыцыйная вікі',
	'config-profile-no-anon' => 'Патрэбнае стварэньне рахунку',
	'config-profile-fishbowl' => 'Толькі для аўтарызаваных рэдактараў',
	'config-profile-private' => 'Прыватная вікі',
	'config-profile-help' => "Вікі працуюць лепей, калі Вы дазваляеце як мага большай колькасьці людзей рэдагаваць яе.
У MediaWiki вельмі лёгка праглядаць апошнія зьмены і выпраўляць любыя пашкоджаньні зробленыя недасьведчанымі ўдзельнікамі альбо вандаламі.

Тым ня менш, многія лічаць, што MediaWiki можа быць карыснай ў шматлікіх іншых ролях, і часта вельмі нялёгка растлумачыць усім перавагі выкарыстаньня тэхналёгіяў вікі.
Таму Вы маеце выбар.

'''{{int:config-profile-wiki}}''' дазваляе рэдагаваць усім, нават без уваходу ў сыстэму.
Вікі з '''{{int:config-profile-no-anon}}''' дазваляе дадатковую адказнасьць, але можа адштурхнуць некаторых патэнцыйных удзельнікаў.

Сцэнар '''{{int:config-profile-fishbowl}}''' дазваляе рэдагаваць зацьверджаным удзельнікам, але ўсе могуць праглядаць старонкі іх гісторыю.
'''{{int:config-profile-private}}''' дазваляе праглядаць і рэдагаваць старонкі толькі зацьверджаным удзельнікам.

Больш складаныя правы ўдзельнікаў даступныя пасьля ўсталяваньня, глядзіце [http://www.mediawiki.org/wiki/Manual:User_rights адпаведную старонку дакумэнтацыі].",
	'config-license' => 'Аўтарскія правы і ліцэнзія:',
	'config-license-none' => 'Без інфармацыі пра ліцэнзію',
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike (сумяшчальная зь Вікіпэдыяй)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 ці болей позьняя',
	'config-license-pd' => 'Грамадзкі набытак',
	'config-license-cc-choose' => 'Выберыце іншую ліцэнзію Creative Commons',
	'config-license-help' => "Шматлікія адкрытыя вікі разьмяшчаюць унёскі на ўмовах ліцэнзіі [http://freedomdefined.org/Definition вольнай ліцэнзіі].
Гэта дазваляе ствараць сэнс супольнай уласнасьці і садзейнічае доўгатэрміновым унёскам.
Гэта не неабходна для прыватных і карпаратыўных вікі.

Калі Вы жадаеце выкарыстоўваць тэкст з Вікіпэдыі, і жадаеце каб Вікіпэдыя магла прынімаць тэкст скапіяваны з Вашай вікі, Вам неабходна выбраць ліцэнзію '''Creative Commons Attribution Share Alike'''.

Раней Вікіпэдыя выкарыстоўвала ліцэнзію GNU Free Documentation. Яна ўсё яшчэ дзейнічае, але яна ўтрымлівае некаторыя моманты, якія ўскладняюць паўторнае выкарыстоўваньне і інтэрпрэтацыю матэрыялаў.",
	'config-email-settings' => 'Устаноўкі электроннай пошты',
	'config-enable-email' => 'Дазволіць выходзячыя электронныя лісты',
	'config-enable-email-help' => 'Калі Вы жадаеце каб працавала электронная пошта, неабходна зрабіць [http://www.php.net/manual/en/mail.configuration.php адпаведныя ўстаноўкі PHP].
Калі Вы не жадаеце выкарыстоўваць магчымасьці электроннай пошты, тут Вы можаце яе адключыць.',
	'config-email-user' => 'Дазволіць электронную пошту для сувязі паміж удзельнікамі',
	'config-email-user-help' => 'Дазволіць усім удзельнікам дасылаць адзін аднаму электронныя лісты, калі ўключаная адпаведная магчымасьць ў іх ўстаноўках.',
	'config-email-usertalk' => 'Уключыць абвяшчэньні пра паведамленьні на старонцы абмеркаваньня',
	'config-email-usertalk-help' => 'Дазваляе ўдзельнікам атрымліваць абвяшчэньні пра зьмены на старонцы абмеркаваньня, калі гэтая магчымасьць уключаная ў іх устаноўках.',
	'config-email-watchlist' => 'Уключыць абвяшчэньні пра зьмены ў сьпісе назіраньня',
	'config-email-watchlist-help' => 'Дазваляе ўдзельнікам атрымліваць абвяшчэньні пра зьмены ў іх сьпісе назіраньня, калі гэтая магчымасьць уключаная ў іх устаноўках.',
	'config-email-auth' => 'Уключыць аўтэнтыфікацыю праз электронную пошту',
	'config-email-auth-help' => "Калі гэтая магчымасьць уключаная, удзельнікі павінны пацьвердзіць іх адрас электроннай пошты праз спасылку, якая дасылаецца ім праз электронную пошту. Яна дасылаецца і падчас зьмены адрасу электроннай пошты.
Толькі аўтэнтыфікаваныя адрасы электроннай пошты могуць атрымліваць электронныя лісты ад іншых удзельнікаў, ці зьмяняць абвяшчэньні дасылаемыя праз электронную пошту.
Уключэньне гэтай магчымасьці '''рэкамэндуецца'''  для адкрытых вікі, з-за магчымых злоўжываньняў магчымасьцямі электроннай пошты.",
	'config-email-sender' => 'Адрас электроннай пошты для вяртаньня:',
	'config-email-sender-help' => 'Увядзіце адрас электроннай пошты для вяртаньня ў якасьці адрасу дасылаемых электронных лістоў.
Сюды будуць дасылацца неатрыманыя электронныя лісты.
Шматлікія паштовыя сэрвэры патрабуюць, каб хаця б назва дамэну была слушнай.',
	'config-upload-settings' => 'Загрузкі выяваў і файлаў',
	'config-upload-enable' => 'Дазволіць загрузку файлаў',
	'config-upload-help' => 'Дазвол загрузкі файлаў можа патэнцыйна пагражаць бясьпекі сэрвэра.
Дадатковую інфармацыю можна атрымаць ў [http://www.mediawiki.org/wiki/Manual:Security разьдзеле бясьпекі].

Каб дазволіць загрузку файлаў, зьмяніце рэжым падкаталёга <code>images</code> у карэннай дырэкторыі MediaWiki так, каб ўэб-сэрвэр меў доступ на запіс.
Потым дазвольце гэтую магчымасьць.',
	'config-upload-deleted' => 'Дырэкторыя для выдаленых файлаў:',
	'config-upload-deleted-help' => 'Выберыце дырэкторыю, у якой будуць захоўвацца выдаленыя файлы.
У ідэальным выпадку, яна не павінна мець доступу з Інтэрнэту.',
	'config-logo' => 'URL-адрас лягатыпу:',
	'config-logo-help' => 'Афармленьне MediaWiki па змоўчваньні уключае прастору для лягатыпу памерам 135×160 піксэляў у верхнім левым куце.
Загрузіце выяву адпаведнага памеру, і увядзіце тут URL-адрас.

Калі Вы не жадаеце мець ніякага лягатыпу, пакіньце гэтае поле пустым.',
	'config-instantcommons' => 'Дазволіць Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] — магчымасьць, якая дазваляе вікі выкарыстоўваць выявы, гукі і іншыя мэдыя, якія знаходзяцца на сайце [http://commons.wikimedia.org/ Wikimedia Commons].
Каб гэта зрабіць, MediaWiki патрабуе доступу да Інтэрнэту. $1

Каб даведацца болей пра гэтую магчымасьць, уключаючы інструкцыю пра тое, як яе ўстанавіць ў любой вікі, акрамя Wikimedia Commons, глядзіце [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos дакумэнтацыю].',
	'config-instantcommons-good' => 'Праграма ўсталяваньня знайшла далучэньне да Інтэрнэту падчас праверкі асяродзьдзя.
Вы можаце дазволіць гэтую магчымасьць, калі жадаеце.',
	'config-instantcommons-bad' => "''На жаль, праграма ўсталяваньня не знайшла далучэньня да інтэрнэту, падчас праверкі асяродзьдзя, таму, магчыма, Вы ня зможаце выкарыстоўваць гэтую магчымасьць.
Калі Ваш сэрвэр даступны праз проксі-сэрвэр, верагодна Вам  патрэбна будзе зьмяніць [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy дадатковыя ўстаноўкі].''",
	'config-cc-error' => 'Выбар ліцэнзіі Creative Commons ня даў вынікаў.
Увядзіце назву ліцэнзіі ўручную.',
	'config-cc-again' => 'Выберыце яшчэ раз…',
	'config-cc-not-chosen' => 'Выберыце, якую ліцэнзію Creative Commons Вы жадаеце выкарыстоўваць і націсьніце «працягваць».',
	'config-advanced-settings' => 'Дадатковыя ўстаноўкі',
	'config-cache-options' => 'Устаноўкі кэшаваньня аб’ектаў:',
	'config-cache-help' => 'Кэшаваньне аб’ектаў павялічвае хуткасьць працы MediaWiki праз кэшаваньне зьвестак, якія часта выкарыстоўваюцца.
Вельмі рэкамэндуем уключыць гэта для сярэдніх і буйных сайтаў, таксама будзе карысна для дробных сайтаў.',
	'config-cache-none' => 'Без кэшаваньня (ніякія магчымасьці не страчваюцца, але хуткасьць працы буйных сайтаў можа зьнізіцца)',
	'config-cache-accel' => 'Кэшаваньне аб’ектаў PHP (APC, eAccelerator, XCache ці WinCache)',
	'config-cache-memcached' => 'Выкарыстоўваць Memcached (патрабуе дадатковай канфігурацыі)',
	'config-memcached-servers' => 'Сэрвэры memcached:',
	'config-memcached-help' => 'Сьпіс IP-адрасоў, якія будуць выкарыстоўвацца Memcached.
Адрасы павінны падзяляцца коскамі і пазначаць порт, які будзе выкарыстоўвацца (напрыклад: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Пашырэньні',
	'config-extensions-help' => 'Пашырэньні пададзеныя вышэй, былі знойдзеныя ў Вашай дырэкторыі <code>./extensions</code>.

Яны могуць патрабаваць дадатковых установак, але іх можна ўключыць зараз',
	'config-install-alreadydone' => "'''Папярэджаньне:''' здаецца, што Вы ўжо ўсталёўвалі MediaWiki і спрабуеце зрабіць гэтай зноў.
Калі ласка, перайдзіце на наступную старонку.",
	'config-install-step-done' => 'зроблена',
	'config-install-step-failed' => 'не атрымалася',
	'config-install-extensions' => 'Уключаючы пашырэньні',
	'config-install-database' => 'Устаноўка базы зьвестак',
	'config-install-pg-schema-failed' => 'Немагчыма стварыць табліцу.
Упэўніцеся, што карыстальнік «$1» можа пісаць у схему «$2».',
	'config-install-user' => 'Стварэньне карыстальніка базы зьвестак',
	'config-install-user-failed' => 'Немагчыма даць правы удзельніку «$1»: $2',
	'config-install-tables' => 'Стварэньне табліцаў',
	'config-install-tables-exist' => "'''Папярэджаньне''': Выглядае, што табліцы MediaWiki ужо існуюць.
Стварэньне прапушчанае.",
	'config-install-tables-failed' => "'''Памылка''': немагчыма стварыць табліцы з-за наступнай памылкі: $1",
	'config-install-interwiki' => 'Запаўненьне табліцы інтэрвікі па змоўчваньні',
	'config-install-interwiki-sql' => 'Немагчыма знайсьці файл <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Папярэджаньне''': выглядае, што табліца інтэрвікі ўжо запоўненая.
Сьпіс па змоўчваньні прапушчаны.",
	'config-install-secretkey' => 'Стварэньне сакрэтнага ключа',
	'config-insecure-secretkey' => "'''Папярэджаньне:''' немагчыма стварыць <code>\$wgSecretKey</code> бясьпекі.
Верагодна трэба зьмяніць яго ўручную.",
	'config-install-sysop' => 'Стварэньне рахунку адміністратара',
	'config-install-done' => "'''Віншуем!'''
Вы пасьпяхова ўсталявалі MediaWiki.

Праграма ўсталяваньня стварыла файл <code>LocalSettings.php</code>.
Ён утрымлівае ўсе Вашыя ўстаноўкі.

Вам неабходна [$1 загрузіць] яго і захаваць у карэнную дырэкторыю Вашай вікі (у тую ж самую дырэкторыю, дзе знаходзіцца index.php).
'''Заўвага''': калі Вы гэтага ня зробіце зараз, то створаны файл ня будзе даступны Вам потым, калі Вы выйдзеце з праграмы ўсталяваньня  без яго загрузкі.

Калі Вы гэта зробіце, Вы можаце '''[$2 ўвайсьці ў Вашую вікі]'''.",
);

/** Breton (Brezhoneg)
 * @author Gwendal
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
	'config-back' => '← Distreiñ',
	'config-continue' => "Kenderc'hel →",
	'config-page-language' => 'Yezh',
	'config-page-dbconnect' => "Kevreañ d'an diaz roadennoù",
	'config-page-dbsettings' => 'Arventennoù an diaz roadennoù',
	'config-page-name' => 'Anv',
	'config-page-options' => 'Dibarzhioù',
	'config-page-install' => 'Staliañ',
	'config-page-complete' => 'Graet !',
	'config-page-restart' => 'Adlañsañ ar staliadur',
	'config-page-readme' => 'Lennit-me',
	'config-page-releasenotes' => 'Notennoù stumm',
	'config-page-copying' => 'O eilañ',
	'config-page-upgradedoc' => 'O hizivaat',
	'config-restart' => "Ya, adloc'hañ anezhañ",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Degemer]
* [http://www.mediawiki.org/wiki/Help:Contents Pajenn-stur an implijer]
* [http://www.mediawiki.org/wiki/Manual:Contents Pajenn-stur ar merour]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAG]',
	'config-env-php' => 'Staliet eo PHP $1.',
	'config-env-latest-ok' => "O staliañ emaoc'h stumm diwezhañ Mediawiki.",
	'config-memory-none' => 'PHP zo kefluniet hep <code>memory_limit</code>',
	'config-memory-ok' => 'Arventenn PHP <code>memory_limit</code> zo $1.
OK.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] a zo staliet',
	'config-apc' => '[http://www.php.net/apc APC] a zo staliet',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] a zo staliet',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] zo staliet',
	'config-diff3-good' => 'GNU diff3 kavet: <code>$1</code>.',
	'config-diff3-bad' => "N'eo ket bet kavet GNU diff3.",
	'config-dir' => "Kavlec'h staliañ: <code>$1</code>.",
	'config-uri' => "Chomlec'h URI ar skript: <code>$1</code>.",
	'config-no-uri' => "'''Fazi:''' N'eus ket tu da gouzout URI ar skript.
Staliadur diforc'het.",
	'config-db-name' => 'Anv an diaz roadennoù:',
	'config-db-username' => 'Anv implijer an diaz roadennoù :',
	'config-db-password' => 'Ger-tremen an diaz roadennoù :',
	'config-db-install-help' => 'Lakaat anv an implijer hag ar ger-tremen a vo implijet evit kennaskañ ouzh an diaz roadennoù e-pad argerzh ar sterniadur.',
	'config-db-wiki-account' => 'Kont implijer evit oberiadurioù boutin',
	'config-db-prefix' => 'Rakrann taolennoù an diaz roadennoù :',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 daouredel',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-db-port' => 'Porzh an diaz roadennoù :',
	'config-db-schema' => 'Brastres evit MediaWiki',
	'config-sqlite-dir' => "Kavlec'h roadennoù SQLite:",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Arventennoù MySQL',
	'config-header-postgres' => 'Arventennoù PostgreSQL',
	'config-header-sqlite' => 'Arventennoù SQLite',
	'config-header-oracle' => 'Arventennoù Oracle',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Daouredel',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Anv ar wiki :',
	'config-site-name-blank' => "Lakait anv ul lec'hienn .",
	'config-project-namespace' => 'Esaouenn anv ar raktres :',
	'config-ns-generic' => 'Raktres',
	'config-ns-site-name' => 'Memes anv hag ar wiki : $1',
	'config-ns-other' => 'All (spisaat)',
	'config-ns-other-default' => 'MaWiki',
	'config-admin-box' => 'Kont merour',
	'config-admin-name' => "Hoc'h anv :",
	'config-admin-password' => 'Ger-tremen :',
	'config-admin-password-confirm' => 'Adskrivañ ar ger-tremen :',
	'config-admin-name-blank' => 'Lakait anv ur merour.',
	'config-admin-password-same' => "Ne c'hell ket ar ger-tremen bezañ heñvel ouzh hini ar gont.",
	'config-admin-email' => "Chomlec'h postel :",
	'config-almost-done' => "Kazi echu eo !
Gellout a rit tremen ar c'hefluniadur nevez ha staliañ ar wiki war-eeun.",
	'config-optional-continue' => "Sevel muioc'h a goulennoù ouzhin.",
	'config-optional-skip' => 'Aet on skuizh, staliañ ar wiki hepken.',
	'config-profile' => 'Profil ar gwirioù implijer :',
	'config-profile-wiki' => 'Wiki hengounel',
	'config-profile-no-anon' => 'Krouidigezh ur gont ret',
	'config-profile-fishbowl' => 'Embanner aotreet hepken',
	'config-profile-private' => 'Wiki prevez',
	'config-license' => 'Copyright hag aotre-implijout:',
	'config-license-pd' => 'Domani foran',
	'config-license-cc-choose' => 'Dibabit un aotre-implijout Creative Commons personelaet',
	'config-email-settings' => 'Arventennoù ar postel',
	'config-email-user' => 'Gweredekaat ar posteloù a implijer da implijer',
	'config-upload-deleted' => "Kavlec'h evit ar restroù dilamet :",
	'config-logo' => 'URL al logo :',
	'config-cc-again' => 'Dibabit adarre...',
	'config-advanced-settings' => 'Kefluniadur araokaet',
	'config-extensions' => 'Astennoù',
	'config-install-step-done' => 'graet',
	'config-install-step-failed' => "c'hwitet",
	'config-install-user' => 'O krouiñ an diaz roadennoù implijer',
	'config-install-tables' => 'Krouiñ taolennoù',
	'config-install-secretkey' => "Genel an alc'hwez kuzh",
);

/** Bosnian (Bosanski)
 * @author CERminator
 */
$messages['bs'] = array(
	'config-desc' => 'Instalacija za MediaWiki',
	'config-title' => 'MediaWiki $1 instalacija',
	'config-information' => 'Informacija',
	'config-localsettings-upgrade' => "'''Upozorenje''': Otkrivena je datoteka <code>LocalSettings.php</code>.
Vaš softver je moguće unaprijediti.
Molimo premjestite <code>LocalSettings.php</code> na sigurno mjesto a zatim ponovo pokrenite instalaciju.",
	'config-session-error' => 'Greška pri pokretanju sesije: $1',
	'config-show-help' => 'Pomoć',
	'config-hide-help' => 'Sakrij pomoć',
	'config-your-language' => 'Vaš jezik:',
	'config-your-language-help' => 'Odaberite jezik koji ćete koristiti tokom procesa instalacije.',
	'config-wiki-language' => 'Wiki jezik:',
	'config-wiki-language-help' => 'Odaberite jezik na kojem će wiki biti najvećim dijelim pisana.',
	'config-back' => '← Nazad',
	'config-continue' => 'Nastavi →',
	'config-page-language' => 'Jezik',
	'config-page-welcome' => 'Dobrodošli u MediaWiki!',
	'config-page-dbconnect' => 'Poveži sa bazom podataka',
	'config-page-upgrade' => 'Unaprijedi postojeću instalaciju',
	'config-page-dbsettings' => 'Postavke baze podataka',
	'config-page-name' => 'Naziv',
	'config-page-options' => 'Opcije',
	'config-page-install' => 'Instaliraj',
	'config-page-complete' => 'Završeno!',
	'config-page-restart' => 'Ponovi instalaciju ispočetka',
	'config-page-readme' => 'Pročitaj me',
	'config-page-releasenotes' => 'Bilješke izdanja',
	'config-page-copying' => 'Kopiram',
	'config-page-upgradedoc' => 'Nadograđujem',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Početna strana]
* [http://www.mediawiki.org/wiki/Help:Contents Vodič za korisnike]
* [http://www.mediawiki.org/wiki/Manual:Contents Vodič za administratore]
* [http://www.mediawiki.org/wiki/Manual:FAQ NPP]',
	'config-env-good' => '<span class="success-message">Okruženje je provjereno.
Možete instalirati MediaWiki.</span>',
	'config-env-php' => 'PHP $1 je instaliran.',
	'config-env-latest-ok' => 'Instalirate posljednju verziju MediaWiki.',
	'config-env-latest-new' => "'''Napomena:''' Instalirate razvojnu veziju MediaWiki.",
	'config-diff3-bad' => 'GNU diff3 nije pronađen.',
	'config-uri' => 'Putanja URI skripte: <code>$1</code>.',
	'config-db-name' => 'Naziv baze podataka:',
	'config-header-mysql' => 'Postavke MySQL',
	'config-header-postgres' => 'Postavke PostgreSQL',
	'config-header-sqlite' => 'Postavke SQLite',
	'config-header-oracle' => 'Postavke Oracle',
	'config-upgrade-done' => "Nadogradnja završena.

Sada možete [$1 početi koristiti vašu wiki].

Ako želite regenerisati vašu datoteku <code>LocalSettings.php</code>, kliknite na dugme ispod.
Ovo '''nije preporučeno''' osim ako nemate problema s vašom wiki.",
);

/** Czech (Česky) */
$messages['cs'] = array(
	'config-information' => 'Informace',
	'config-show-help' => 'Nápověda',
	'config-continue' => 'Pokračovat →',
	'config-page-language' => 'Jazyk',
	'config-page-name' => 'Název',
	'config-page-options' => 'Nastavení',
	'config-page-install' => 'Instalovat',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Věštba',
	'config-admin-name' => 'Vaše jméno:',
	'config-admin-email' => 'E-mailová adresa:',
	'config-email-settings' => 'Nastavení e-mailu',
	'config-install-step-failed' => 'selhaly',
);

/** German (Deutsch)
 * @author Kghbln
 * @author The Evil IP address
 */
$messages['de'] = array(
	'config-desc' => 'Das MediaWiki-Installationsprogramm',
	'config-title' => 'Installation von MediaWiki $1',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => "'''Warnung:''' Die Datei <code>LocalSettings.php</code> wurde gefunden.
Die vorhandene Installation kann aktualisiert werden.
Die Datei <code>LocalSettings.php</code> muss an einen sicheren Speicherort verschoben und dann das Installationsprogramm erneut ausgeführt werden.",
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
	'config-your-language' => 'Sprache:',
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
	'config-page-restart' => 'Installationsvorgang erneut starten',
	'config-page-readme' => 'Lies mich',
	'config-page-releasenotes' => 'Veröffentlichungsinformationen',
	'config-page-copying' => 'Kopie der Lizenz',
	'config-page-upgradedoc' => 'Aktualisiere',
	'config-help-restart' => 'Sollen alle angegebenen Daten gelöscht und der Installationsvorgang erneut gestartet werden?',
	'config-restart' => 'Ja, erneut starten',
	'config-welcome' => '=== Prüfung der Installationsumgebung ===
Basisprüfungen werden durchgeführt, um festzustellen, ob die Installationsumgebung für die Installation von MediaWiki geeignet ist.
Die Ergebnisse dieser Prüfung sollten angegeben werden, sofern während des Installationsvorgangs Hilfe benötigt und erfragt wird.',
	'config-copyright' => "=== Copyright und Nutzungsbedingungen ===

$1

Dieses Programm ist freie Software, d. h. es kann, gemäß den Bedingungen der von der Free Software Foundation veröffentlichten GNU General Public License, weiterverteilt und/oder modifiziert werden. Dabei kann die Version 2, oder nach eigenem Ermessen, jede neuere Version der Lizenz verwendet werden.

Dieses Programm wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings '''ohne jegliche Garantie''' und sogar ohne die implizierte Garantie einer '''Marktgängigkeit''' oder '''Eignung für einen bestimmten Zweck'''. Hierzu sind weitere Hinweise in der GNU General Public License enthalten.

Eine <doclink href=Copying>Kopie der GNU General Public License</doclink> sollte zusammen mit diesem Programm verteilt worden sein. Sofern dies nicht der Fall war, kann eine Kopie bei der Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftlich angefordert oder auf deren Website [http://www.gnu.org/copyleft/gpl.html online gelesen] werden.",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Website von MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nutzeranleitung zu MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratorenanleitung zu MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:FAQ Häufige Fragen zu MediaWiki]',
	'config-env-good' => '<span class="success-message">Die Installationsumgebung wurde geprüft.
MediaWiki kann installiert werden.</span>',
	'config-env-bad' => 'Die Installationsumgebung wurde geprüft.
MediaWiki kann nicht installiert werden.',
	'config-env-php' => 'PHP $1 ist installiert.',
	'config-env-latest-ok' => 'Die neueste Programmversion von MediaWiki wird installiert.',
	'config-env-latest-new' => "'''Hinweis:''' Eine Entwicklungsversion von MediaWiki wird installiert.",
	'config-env-latest-can-not-check' => "'''Warnung:''' Das Installationsprogramm konnte keine Informationen zur neuesten Programmversion von MediaWiki von [$1] abrufen.",
	'config-env-latest-old' => "'''Warnung:''' Es wird eine veraltete Programmversion von MediaWiki installiert.",
	'config-env-latest-help' => 'Die Programmversion $1 wird installiert, wohingegen die neueste Programmversion $2 ist.
Es wird empfohlen die neueste Programmversion zu verwenden, die bei [http://www.mediawiki.org/wiki/Download/de mediawiki.org] heruntergeladen werden kann.',
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
	'config-have-db' => '{{PLURAL:$2|Datenbanktreiber|Datenbanktreiber}} gefunden: $1.',
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
	'config-pcre' => 'Das Modul für die PCRE-Unterstützung wurde nicht gefunden.
MediaWiki benötigt allerdings perl-kompatible reguläre Ausdrücke, um lauffähig zu sein.',
	'config-memory-none' => 'PHP wurde ohne den Parameter <code>memory_limit</code> konfiguriert',
	'config-memory-ok' => 'Der PHP-Parameter <code>memory_limit</code> hat den Wert $1.
OK.',
	'config-memory-raised' => 'Der PHP-Parameter <code>memory_limit</code> betrug $1 und wurde auf $2 erhöht.',
	'config-memory-bad' => "'''Warnung:''' Der PHP-Parameter <code>memory_limit</code> beträgt $1.
Dieser Wert ist wahrscheinlich zu niedrig.
Der Installationsvorgang könnte daher scheitern!",
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
Der Installationsvorgang wurde daher abgebrochen.",
	'config-dir-not-writable-group' => "'''Fehler:''' Kein Schreibzugriff auf die Config-Datei möglich.
Der Installationsvorgang wurde abgebrochen.

Das Installationsprogramm konnte den Benutzer bestimmen, mit dem Webserver ausgeführt wird.
Schreibzugriff auf das <code><nowiki>./config</nowiki></code>-Verzeichnis muss für diesen ermöglicht werden, um den Installationsvorgang fortsetzen zu können..

Auf einem Unix- oder Linux-System:
<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Fehler:''' Kein Schreibzugriff auf die Config-Datei möglich.
Der Installationsvorgang wurde abgebrochen.

Das Installationsprogramm konnte nicht den Benutzer bestimmen, mit dem Webserver ausgeführt wird.
Schreibzugriff auf das <code><nowiki>./config</nowiki></code>-Verzeichnis muss global für diesen und andere Benutzer ermöglicht werden, um den Installationsvorgang fortsetzen zu können.

Auf einem Unix- oder Linux-System:
<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'MediaWiki wurde mit den Dateierweiterungen <code>$1</code> installiert.',
	'config-shell-locale' => 'Es wurde die Locale „$1“ für die Shell gefunden',
	'config-uploads-safe' => 'Das Standardverzeichnis für hochgeladene Dateien ist von der willkürlichen Ausführung von Skripten geschützt.',
	'config-uploads-not-safe' => "''Warnung:''' Das Standardverzeichnis für hochgeladene Dateien <code>$1</code> ist für die willkürliche Ausführung von Skripten anfällig.
Obzwar MediaWiki die hochgeladenen Dateien auf Sicherheitsrisiken überprüft, wird dennoch dringend empfohlen diese [http://www.mediawiki.org/wiki/Manual:Security#Upload_security Sicherheitslücke] zu schließen, bevor das Hochladen von Dateien aktiviert wird.",
	'config-db-type' => 'Datenbanksystem:',
	'config-db-host' => 'Datenbankserver:',
	'config-db-host-help' => 'Sofern sich die Datenbank auf einem anderen Server befindet, ist hier der Servername oder die entsprechende IP-Adresse anzugeben.

Sofern ein gemeinschaftlich genutzter Server verwendet wird, sollte der Hoster den zutreffenden Servernamen in seiner Dokumentation angegeben haben.

Sofern auf einem Windows-Server installiert und MySQL genutzt wird, funktioniert der Servername „localhost“ voraussichtlich nicht. Wenn nicht, sollte  „127.0.0.1“ oder die lokale IP-Adresse angegeben werden.',
	'config-db-wiki-settings' => 'Bitte identifiziere dieses Wiki',
	'config-db-name' => 'Datenbankname:',
	'config-db-name-help' => 'Bitten einen Namen angeben, mit dem das Wiki identifiziert werden kann.
Dabei bitte keine Leerzeichen oder Bindestriche verwenden.
 
Sofern ein gemeinschaftlich genutzter Server verwendet wird, sollte der Hoster den Datenbanknamen angegeben oder aber die Erstellung einer Datenbank über ein entsprechendes Interface gestattet haben.',
	'config-db-install-account' => 'Benutzerkonto für die Installation',
	'config-db-username' => 'Benutzername der Datenbank:',
	'config-db-password' => 'Passwort der Datenbank:',
	'config-db-install-help' => 'Benutzername und Passwort, die während des Installationsvorgangs, für die Verbindung mit der Datenbank, genutzt werden sollen, sind nun anzugeben.',
	'config-db-account-lock' => 'Derselbe Benutzername und das Passwort müssen während des Normalbetriebs des Wikis verwendet werden.',
	'config-db-wiki-account' => 'Benutzerkonto für den normalen Betrieb',
	'config-db-wiki-help' => 'Bitte Benutzernamen und Passwort angeben, die der Webserver während des Normalbetriebes dazu verwenden soll, eine Verbindung zum Datenbankserver herzustellen.
Sofern ein entsprechendes Benutzerkonto nicht vorhanden ist und das Benutzerkonto für den Installationsvorgang über ausreichende Berechtigungen verfügt, wird dieses Benutzerkonto automatisch mit den Mindestberechtigungen zum Normalbetrieb des Wikis angelegt.',
	'config-db-prefix' => 'Datenbanktabellenpräfix:',
	'config-db-prefix-help' => 'Sofern eine Datenbank für mehrere Wikiinstallationen oder eine Wikiinstallation und eine andere Programminstallation genutzt werden soll, muss ein weiterer Datenbanktabellenpräfix angegeben werden, um Datenbankprobleme zu vermeiden.
Dabei bitte keine Leerzeichen oder Bindestriche verwenden.

Gewöhnlich bleibt dieses Datenfeld leer.',
	'config-db-charset' => 'Datenbankzeichensatz',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binär',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 abwärtskompatibles UTF-8',
	'config-charset-help' => "'''Warnung:''' Sofern '''abwärtskompatibles UTF-8''' bei MySQL 4.1+ verwendet und anschließend die Datenbank mit <code>mysqldump</code> gesichert wird, könnten alle nicht mit ASCII-codierten Zeichen beschädigt werden, was zu irreversiblen Schäden der Datensicherung führt!

Im '''binären Modus''' speichert MediaWiki UTF-8 Texte in der Datenbank in binär kodierte Datenfelder.
Dies ist effizienter als der UTF-8-Modus von MySQL und ermöglicht so die Verwendung jeglicher Unicode-Zeichen.
Im '''UTF-8-Modus''' wird MySQL den Zeichensatz der Daten erkennen und sie richtig anzeigen und konvertieren,
allerdings können keine Zeichen außerhalb des [http://de.wikipedia.org/wiki/Basic_Multilingual_Plane#Gliederung_in_Ebenen_und_Bl.C3.B6cke ''Basic Multilingual Plane'' (BMP)] gespeichert werden.",
	'config-mysql-old' => 'MySQL $1 oder höher wird benötigt. MySQL $2 ist momentan vorhanden.',
	'config-db-port' => 'Datenbankport:',
	'config-db-schema' => 'Datenschema für MediaWiki',
	'config-db-ts2-schema' => 'Datenschema für tsearch2',
	'config-db-schema-help' => 'Die obigen Datenschemata sind in der Regel richtig.
Nur Änderungen vornehmen, sofern es Gründe dafür gibt.',
	'config-sqlite-dir' => 'SQLite-Datenverzeichnis:',
	'config-sqlite-dir-help' => "SQLite speichert alle Daten in einer einzigen Datei.

Das für sie vorgesehene Verzeichnis muss während des Installationsvorgangs beschreibbar sein.

Es sollte '''nicht'' über das Web zugänglich sein, was der Grund ist, warum die Datei nicht dort abgelegt wird, wo sich die PHP-Dateien befinden.

Das Installationsprogramm wird mit der Datei zusammen eine zusätzliche <code>.htaccess</code>-Datei erstellen. Sofern dies scheitert, können Dritte auf die Datendatei zugreifen.
Dies umfasst die Nutzerdaten (E-Mail-Adressen, Passwörter, etc.) wie auch gelöschte Seitenversionen und andere vertrauliche Daten, die im Wiki gespeichert sind.

Es ist daher zu erwägen die Datendatei an gänzlich anderer Stelle abzulegen, beispielsweise im Verzeichnis <code>./var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki unterstützt die folgenden Datenbanksysteme:

$1

Sofern nicht das Datenbanksystem angezeigt wird, das verwendet werden soll, gibt es oben einen Link zur Anleitung mit Informationen, wie dieses aktiviert werden kann.',
	'config-support-mysql' => '* $1 ist das von MediaWiki primär unterstützte Datenbanksystem ([http://www.php.net/manual/en/mysql.installation.php Anleitung zur Kompilierung von PHP mit MySQL-Unterstützung (en)])',
	'config-support-postgres' => '* $1 ist ein beliebtes Open-Source-Datenbanksystem und eine Alternative zu MySQL ([http://www.php.net/manual/en/pgsql.installation.php Anleitung zur Kompilierung von PHP mit PostgreSQL-Unterstützung (en)])',
	'config-support-sqlite' => '* $1 ist ein verschlanktes Datenbanksystem, das auch gut unterstützt wird ([http://www.php.net/manual/en/pdo.installation.php Anleitung zur Kompilierung von PHP mit SQLite-Unterstützung (en)], verwendet PHP Data Objects (PDO))',
	'config-header-mysql' => 'MySQL-Einstellungen',
	'config-header-postgres' => 'PostgreSQL-Einstellungen',
	'config-header-sqlite' => 'SQLite-Einstellungen',
	'config-header-oracle' => 'Oracle-Einstellungen',
	'config-invalid-db-type' => 'Unzulässiges Datenbanksystem',
	'config-missing-db-name' => 'Bei „Datenbankname“ muss ein Wert angegeben werden.',
	'config-invalid-db-name' => 'Ungültiger Datenbankname „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) verwendet werden.',
	'config-invalid-db-prefix' => 'Ungültiger Datenbanktabellenpräfix „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) verwendet werden.',
	'config-connection-error' => '$1.

Bitte unten angegebenen Servernamen, Benutzernamen sowie das Passwort überprüfen und es danach erneut versuchen.',
	'config-invalid-schema' => 'Ungültiges Datenschema für MediaWiki „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) verwendet werden.',
	'config-invalid-ts2schema' => 'Ungültiges Datenschema für TSearch2 „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) verwendet werden.',
	'config-postgres-old' => 'PostgreSQL $1 oder höher wird benötigt. PostgreSQL $2 ist momentan vorhanden.',
	'config-sqlite-name-help' => 'Bitten einen Namen angeben, mit dem das Wiki identifiziert werden kann.
Dabei bitte keine Leerzeichen oder Bindestriche verwenden.
Dieser Name wird für die SQLite-Datendateinamen genutzt.',
	'config-sqlite-parent-unwritable-group' => 'Das Datenverzeichnis <code><nowiki>$1</nowiki></code> kann nicht erzeugt werden, da das übergeordnete Verzeichnis <code><nowiki>$2</nowiki></code> nicht für den Webserver beschreibbar ist.

Das Installationsprogramm konnte den Benutzer bestimmen, mit dem Webserver ausgeführt wird.
Schreibzugriff auf das <code><nowiki>$3</nowiki></code>-Verzeichnis muss für diesen ermöglicht werden, um den Installationsvorgang fortsetzen zu können.

Auf einem Unix- oder Linux-System:
<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Das Datenverzeichnis <code><nowiki>$1</nowiki></code> kann nicht erzeugt werden, da das übergeordnete Verzeichnis <code><nowiki>$2</nowiki></code> nicht für den Webserver beschreibbar ist.

Das Installationsprogramm konnte den Benutzer bestimmen, mit dem Webserver ausgeführt wird.
Schreibzugriff auf das <code><nowiki>$3</nowiki></code>-Verzeichnis muss global für diesen und andere Benutzer ermöglicht werden, um den Installationsvorgang fortsetzen zu können.

Auf einem Unix- oder Linux-System:
<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Fehler beim Erstellen des Datenverzeichnisses „$1“.

Bitte den Speicherort überprüfen und es danach erneut versuchen.',
	'config-sqlite-dir-unwritable' => 'Das Verzeichnis „$1“  ist nicht beschreibbar.
Bitte die Zugriffsberechtigungen so ändern, dass dieses Verzeichnis für den Webserver beschreibbar ist und es danach erneut versuchen.',
	'config-sqlite-connection-error' => '$1.

Bitte unten angegebenes Datenverzeichnis sowie den Datenbanknamen überprüfen und es danach erneut versuchen.',
	'config-sqlite-readonly' => 'Die Datei <code>$1</code> ist nicht beschreibbar.',
	'config-sqlite-cant-create-db' => 'Die Datenbankdatei <code>$1</code> konnte nicht erzeugt werden.',
	'config-sqlite-fts3-downgrade' => 'PHP verfügt nicht über FTS3-Unterstützung. Die Tabellen wurden zurückgestuft.',
	'config-sqlite-fts3-add' => 'Hinzufügen der FTS3-Suchfunktionen',
	'config-can-upgrade' => "Es wurden MediaWiki-Tabellen in dieser Datenbank gefunden.
Um sie auf MediaWiki $1 zu aktualisieren, bitte auf '''Weiter''' klicken.",
	'config-upgrade-done' => "Die Aktualisierung ist abgeschlossen.

Das Wiki kann nun [$1 genutzt werden].

Sofern die Datei <code>LocalSettings.php</code> neu erzeugt werden soll, bitte auf die Schaltfläche unten klicken.
Dies wird '''nicht empfohlen''', es sei denn, es treten Probleme mit dem Wiki auf.",
	'config-regenerate' => '<code>LocalSettings.php</code> neu erstellen →',
	'config-show-table-status' => 'Die Abfrage SHOW TABLE STATUS ist gescheitert!',
	'config-unknown-collation' => "'''Warnung:''' Die Datenbank nutzt eine unbekannte Kollation.",
	'config-db-web-account' => 'Datenbankkonto für den Webzugriff',
	'config-db-web-help' => 'Bitte Benutzernamen und Passwort auswählen, die der Webserver während des Normalbetriebes dazu verwenden soll, eine Verbindung zum Datenbankserver herzustellen.',
	'config-db-web-account-same' => 'Dasselbe Konto wie während des Installationsvorgangs verwenden',
	'config-db-web-create' => 'Sofern nicht bereits vorhanden, muss nun das Konto erstellt werden',
	'config-db-web-no-create-privs' => 'Das angegebene und für den Installationsvorgang vorgesehene Konto verfügt nicht über ausreichend Berechtigungen, um ein Konto zu erstellen.
Das hier angegebene Konto muss bereits vorhanden sein.',
	'config-mysql-engine' => 'Speicher-Engine:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' ist fast immer die bessere Wahl, da es gleichzeitige Zugriffe gut unterstützt.

'''MyISAM''' ist in Einzelnutzerumgebungen sowie bei schreibgeschützten Wikis.
Bei MyISAM-Datenbanken treten tendenziell häufiger Fehler, auf als bei InnoDB-Datenbanken.",
	'config-mysql-egine-mismatch' => "'''Warnung:''' Als Speicher-Engine wurde $1 ausgewählt, während die Datenbank $2 verwendet.
Das Aktualisierungsskript kann die Speicher-Engine nicht konvertieren, so dass weiterhin $2 verwendet wird.",
	'config-mysql-charset' => 'Datenbankzeichensatz:',
	'config-mysql-binary' => 'binär',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Im '''binären Modus'' speichert MediaWiki UTF-8 Texte in der Datenbank in binär kodierte Datenfelder.
Dies ist effizienter als der UTF-8-Modus von MySQL und ermöglicht so die Verwendung jeglicher Unicode-Zeichen.

Im '''UTF-8-Modus''' wird MySQL den Zeichensatz der Daten erkennen und sie richtig anzeigen und konvertieren,
allerdings können keine Zeichen außerhalb des [http://de.wikipedia.org/wiki/Basic_Multilingual_Plane#Gliederung_in_Ebenen_und_Bl.C3.B6cke ''Basic Multilingual Plane'' (BMP)] gespeichert werden.",
	'config-mysql-charset-mismatch' => "'''Warnung:''' Als Datenbankzeichensatz wurde $1 ausgewählt, während die Datenbank $2 verwendet.
Das Aktualisierungsskript kann den Datenbankzeichensatz nicht konvertieren, so dass weiterhin $2 verwendet wird.",
	'config-site-name' => 'Name des Wikis:',
	'config-site-name-help' => 'Er wird in der Titelleiste des Browsers, wie auch verschiedenen anderen Stellen, genutzt.',
	'config-site-name-blank' => 'Sitenamen angeben.',
	'config-project-namespace' => 'Name des Projektnamensraums:',
	'config-ns-generic' => 'Projekt',
	'config-ns-site-name' => 'Entspricht dem Namen des Wikis: $1',
	'config-ns-other' => 'Sonstige (bitte angeben)',
	'config-ns-other-default' => 'MeinWiki',
	'config-project-namespace-help' => "Dem Beispiel von Wikipedia folgend, unterscheiden viele Wikis zwischen den Seiten für Inhalte und denen für Richtlinien. Letztere werden im „'''Projektnamensraum'''“ hinterlegt.
Alle Seiten dieses Namensraumes verfügen über einen Seitenpräfix, der nun an dieser Stelle angegeben werden kann.
Traditionell steht dieser Seitenpräfix mit dem Namen des Wikis in einem engen Zusammenhang. Dabei können bestimmte Sonderzeichen wie „#“ oder „:“ nicht verwendet werden.",
	'config-ns-invalid' => 'Der angegebene Namensraum „<nowiki>$1</nowiki>“ ist ungültig.
Bitte einen abweichenden Projektnamensraum angeben.',
	'config-admin-box' => 'Administratorkonto',
	'config-admin-name' => 'Name:',
	'config-admin-password' => 'Passwort:',
	'config-admin-password-confirm' => 'Passwort wiederholen:',
	'config-admin-help' => 'Bitte den bevorzugten Benutzernamen angeben, beispielsweise „Knut Wuchtig“.
Dies ist der Name, der benötigt wird, um sich im Wiki anzumelden.',
	'config-admin-name-blank' => 'Bitte den Benutzernamen für den Administratoren angeben.',
	'config-admin-name-invalid' => 'Der angegebene Benutzername „<nowiki>$1</nowiki>“ ist ungültig.
Bitte einen abweichenden Benutzernamen angeben.',
	'config-admin-password-blank' => 'Bitte das Passwort für das Administratorkonto angeben.',
	'config-admin-password-same' => 'Das Passwort darf nicht mit dem Benutzernamen übereinstimmen.',
	'config-admin-password-mismatch' => 'Die beiden Passwörter stimmen nicht überein.',
	'config-admin-email' => 'E-Mail-Adresse:',
	'config-admin-email-help' => 'Bitte hier eine E-Mail-Adresse angeben, die den E-Mail-Empfang von anderen Benutzern des Wikis, das Zurücksetzen des Passwortes sowie Benachrichtigungen zu Änderungen an beobachteten Seiten ermöglicht.',
	'config-admin-error-user' => 'Es ist beim Erstellen des Administrators mit dem Namen „<nowiki>$1</nowiki>“ ein interner Fehler aufgetreten.',
	'config-admin-error-password' => 'Es ist beim Setzen des Passworts für den Administrator „$1“ ein interner Fehler aufgetreten: <pre>$2</pre>',
	'config-subscribe' => 'Bitte die Mailingliste [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mitteilungen zu Versionsveröffentlichungen] abonnieren.',
	'config-subscribe-help' => 'Es handelt sich hierbei um eine Mailingliste mit wenigen Aussendungen, die für Mitteilungen zu Versionsveröffentlichungen, einschließlich wichtiger Sicherheitsveröffentlichungen, genutzt wird.
Diese Mailingliste sollte abonniert werden. Zudem sollte die MediaWiki-Installation stets aktualisiert werden, sobald eine neue Programmversion veröffentlicht wurde.',
	'config-almost-done' => 'Der Vorgang ist fast abgeschlossen!
Die verbliebenen Konfigurationseinstellungen können übersprungen und das Wiki umgehend installiert werden.',
	'config-optional-continue' => 'Sollen weitere Konfigurationseinstellungen vorgenommen werden?',
	'config-optional-skip' => 'Nein, das Wiki soll nun installiert werden.',
	'config-profile' => 'Profil der Benutzerberechtigungen:',
	'config-profile-wiki' => 'offenes Wiki',
	'config-profile-no-anon' => 'Erstellung eines Benutzerkontos erforderlich',
	'config-profile-fishbowl' => 'ausschließlich berechtigten Bearbeitern',
	'config-profile-private' => 'geschlossenes Wiki',
	'config-profile-help' => "Wikis sind am nützlichsten, wenn so viele Menschen als möglich Bearbeitungen vornehmen können.
Mit MediaWiki ist es einfach die letzten Änderungen nachzuvollziehen und unbrauchbare Bearbeitungen, beispielsweise von unbedarften oder böswilligen Benutzern, rückgängig zu machen.

Allerdings finden etliche Menschen Wikis auch mit anderen Bearbeitungskonzepten sinnvoll. Manchmal ist es auch nicht einfach alle Beteiligten vollständig von den Vorteilen des „Wiki-Prinzips” zu überzeugen. Darum ist eine Auswahl möglich.

Ein '''{{int:config-profile-wiki}}''' ermöglicht es jedermann, sogar ohne über ein Benutzerkonto zu verfügen, Bearbeitungen vorzunehmen.
Ein Wiki bei dem die '''{{int:config-profile-no-anon}}''' ist, bietet höhere Verantwortlichkeit des Einzelnen für seine Bearbeitungen, könnte allerdings Personen mit gelegentlichen Bearbeitungen abschrecken. Ein Wiki mit '''{{int:config-profile-fishbowl}}''' gestattet es nur ausgewählten Benutzern Bearbeitungen vorzunehmen. Allerdings kann dabei die Allgemeinheit die Seiten immer noch betrachten und Änderungen nachvollziehen. Ein '''{{int:config-profile-private}}''' gestattet es nur ausgewählten Benutzern, Seiten zu betrachten sowie zu bearbeiten.

Komplexere Konzepte zur Zugriffssteuerung können erst nach abgeschlossenem Installationsvorgang eingerichtet werden. Hierzu gibt es weitere Informationen auf der Website mit der [http://www.mediawiki.org/wiki/Manual:User_rights entsprechenden Anleitung].",
	'config-license' => 'Copyright und Lizenz:',
	'config-license-none' => 'Keine Lizenzangabe in der Fußzeile',
	'config-license-cc-by-sa' => 'Creative Commons „Namensnennung, Weitergabe unter gleichen Bedingungen“ (kompatibel mit Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons „Namensnennung, nicht kommerziell, Weitergabe unter gleichen Bedingungen“',
	'config-license-gfdl-old' => 'GNU-Lizenz für freie Dokumentation 1.2',
	'config-license-gfdl-current' => 'GNU-Lizenz für freie Dokumentation 1.3 oder höher',
	'config-license-pd' => 'Gemeinfreiheit',
	'config-license-cc-choose' => 'Bitte eine Creative Commons-Lizenz auswählen',
	'config-license-help' => 'Viele öffentliche Wikis publizieren alle Beiträge unter einer [http://freedomdefined.org/Definition/De freien Lizenz].
Dies trägt dazu bei ein Gefühl von Gemeinschaft zu schaffen und ermutigt zu längerfristiger Mitarbeit.
Dahingegen ist im Allgemeinen eine freie Lizenz auf geschlossenen Wikis nicht notwendig.

Sofern man Texte aus der Wikipedia verwenden möchte und umgekehrt, sollte die Creative Commons-Lizens „Namensnennung, Weitergabe unter gleichen Bedingungen“ gewählt werden.

Die GNU-Lizenz für freie Dokumentation ist die ehemalige Lizenz der Wikipedia.
Sie ist noch immer gültig, beinhaltet aber einige Bedingungen, welche die Wiederverwendung und deren Interpretation erschweren.',
	'config-email-settings' => 'E-Mail-Einstellungen',
	'config-enable-email' => 'Ausgehende E-Mails ermöglichen',
	'config-enable-email-help' => 'Sofern die E-Mail-Funktionen genutzt werden sollen, müssen die entsprechenden [http://www.php.net/manual/en/mail.configuration.php PHP-E-Mail-Einstellungen] richtig konfiguriert werden.
Für den Fall, dass die E-Mail-Funktionen nicht benötigt werden, können sie hier deaktiviert werden.',
	'config-email-user' => 'E-Mail-Versand von Benutzer zu Benutzer aktivieren',
	'config-email-user-help' => 'Allen Benutzern ermöglichen, sich gegenseitig E-Mails zu schicken, sofern sie es in ihren Einstellungen aktiviert haben.',
	'config-email-usertalk' => 'Benachrichtigungen zu Änderungen an Benutzerdiskussionsseiten ermöglichen',
	'config-email-usertalk-help' => 'Ermöglicht es Benutzern, Benachrichtigungen zu Änderungen an ihren Benutzerdiskussionsseiten zu erhalten, sofern sie dies in ihren Einstellungen aktiviert haben.',
	'config-email-watchlist' => 'Benachrichtigungen zu Änderungen an Seiten auf der Beobachtungsliste ermöglichen',
	'config-email-watchlist-help' => 'Ermöglicht es Benutzern, Benachrichtigungen zu Änderungen an Seiten auf ihrer Beobachtungsliste zu erhalten, sofern sie dies in ihren Einstellungen aktiviert haben.',
	'config-email-auth' => 'E-Mail-Authentifizierung ermöglichen',
	'config-email-auth-help' => "Sofern diese Funktion aktiviert ist, müssen Benutzer ihre E-Mail-Adresse bestätigen, indem sie den Bestätigungslink nutzen, der ihnen immer dann zugesandt wird, wenn sie ihre E-Mail-Adresse angeben oder ändern.
Nur bestätigte E-Mail-Adressen können Nachrichten von anderen Benutzer oder Benachrichtigungsmitteilungen erhalten.
Die Aktivierung dieser Funktion wird bei offenen Wikis, mit Hinblick auf möglichen Missbrauch der E-Mailfunktionen, '''empfohlen'''.",
	'config-email-sender' => 'E-Mail-Adresse für Antworten:',
	'config-email-sender-help' => 'Bitte hier die E-Mail-Adresse angeben, die als Absenderadresse bei ausgehenden E-Mails eingesetzt werden soll.
Rücklaufende E-Mails werden an diese E-Mail-Adresse gesandt.
Bei viele E-Mail-Servern muss der Teil der E-Mail-Adresse mit der Domainangabe korrekt sein.',
	'config-upload-settings' => 'Hochladen von Bildern und Dateien',
	'config-upload-enable' => 'Das Hochladen von Dateien ermöglichen',
	'config-upload-help' => 'Das Hochladen von Dateien macht den Server für potentielle Sicherheitsprobleme anfällig.
Weitere Informationen hierzu sollen im [http://www.mediawiki.org/wiki/Manual:Security Abschnitt Sicherheit] der Anleitung gelesen werden.

Um das Hochladen von Dateien zu ermöglichen, muss der Zugriff auf das Unterverzeichnis <code>./images</code> so geändert werden, das es für den Webserver beschreibbar ist.
Hernach kann diese Option aktiviert werden.',
	'config-upload-deleted' => 'Das Verzeichnis für gelöschte Dateien:',
	'config-upload-deleted-help' => 'Bitte ein Verzeichnis auswählen, in dem gelöschte Dateien archiviert werden sollen.
Idealerweise sollte es nicht über das Internet zugänglich sein.',
	'config-logo' => 'URL des Logos:',
	'config-logo-help' => 'Die Standardoberfläche von MediaWiki verfügt, in der oberen linken Ecke, über Platz für eine Logo mit den Maßen 135x160 Pixel.
Bitte ein Logo in entsprechender Größe hochladen und die zugehörige URL an dieser Stelle angeben.

Sofern kein Logo benötigt wird, kann dieses Datenfeld leer bleiben.',
	'config-instantcommons' => '„InstantCommons“ aktivieren',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons InstantCommons] ist eine Funktion, die es Wikis ermöglicht, Bild-, Klang- und andere Mediendateien zu nutzen, die auf der Website [http://commons.wikimedia.org/ Wikimedia Commons] verfügbar sind.
Um diese Funktion zu nutzen, muss MediaWiki eine Verbindung ins Internet herstellen können. $1

Weitere Informationen zu dieser Funktion, einschließlich der Anleitung, wie sie für das Wiki eingerichtet werden kann, gibt es auf folgender [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos Webseite].',
	'config-instantcommons-good' => 'Das Installationsprogramm konnte während der Prüfung der Installationsumgebung eine Verbindung zum Internet feststellen.
Diese Funktion kann daher, sofern gewünscht, aktiviert werden.',
	'config-instantcommons-bad' => "''Das Installationsprogramm konnte während der Prüfung der Installationsumgebung keine Verbindung zum Internet feststellen. Diese Funktion kann daher möglicherweise nicht genutzt werden.
Sofern sich der Webserver hinter einem Proxy befindet, müssen eventuell einige [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy weitere Einstellungen] vorgenommen werden.''",
	'config-cc-error' => 'Der Creativ-Commons-Lizenzassistent konnte keine Lizenz ermitteln.
Die Lizenz ist daher jetzt manuell einzugeben.',
	'config-cc-again' => 'Erneut auswählen…',
	'config-cc-not-chosen' => 'Die gewünschte Creative-Commons-Lizenz auswählen und dann auf „weiter“ klicken.',
	'config-advanced-settings' => 'Erweiterte Konfiguration',
	'config-cache-options' => 'Einstellungen für die Zwischenspeicherung von Objekten:',
	'config-cache-help' => 'Objektcaching wird dazu genutzt die Geschwindigkeit von MediaWiki zu verbessern, indem häufig genutzte Daten zwischengespeichert werden.
Mittelgroße bis große Wikis werden sehr ermutigt dies zu nutzen, aber auch für kleine Wikis ergeben sich erkennbare Vorteile.',
	'config-cache-none' => 'Kein Objektcaching (Es wurde keine Funktion entfernt, allerdings kann die Geschwindigkeit größerer Wikis beeinflusst werden.)',
	'config-cache-accel' => 'Objektcaching von PHP (APC, eAccelerator, XCache or WinCache)',
	'config-cache-memcached' => 'Memchached Cacheserver nutzen (erfordert einen zusätzliche Installationsvorgang mitsamt Konfiguration)',
	'config-memcached-servers' => 'Memcached Cacheserver',
	'config-memcached-help' => 'Liste der für Memcached nutzbaren IP-Adressen.
Sie sollten durch Kommata voneinander getrennt werden. zudem sollte der zu verwendende Port angegeben werden (z. B. 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Erweiterungen',
	'config-extensions-help' => 'Die obig angegebenen Erweiterungen wurden im Verzeichnis <code>./extensions</code> gefunden.

Sie könnten zusätzliche Konfigurierung erfordern, können aber bereits jetzt aktiviert werden.',
	'config-install-alreadydone' => "'''Warnung:''' Es wurde eine vorhandene MediaWiki-Installation gefunden.
Es muss daher mit den nächsten Seite weitergemacht werden.",
	'config-install-step-done' => 'erledigt',
	'config-install-step-failed' => 'gescheitert',
	'config-install-extensions' => 'Einschließlich Erweiterungen',
	'config-install-database' => 'Datenbank wird eingerichtet',
	'config-install-pg-schema-failed' => 'Das Erstellen der Datentabellen ist gescheitert.
Es muss sichergestellt sein, dass der Benutzer „$1“ kann, um in das Datenschema zu „$2“ zu schreiben.',
	'config-install-user' => 'Datenbankbenutzer wird erstellt',
	'config-install-user-failed' => 'Gewährung der Berechtigung für „$1“ ist gescheitert: $2',
	'config-install-tables' => 'Datentabellen werden erstellt',
	'config-install-tables-exist' => "'''Warnung:''' Es wurden MediaWiki-Datentabellen gefunden.
Die Erstellung wurde übersprungen.",
	'config-install-tables-failed' => "'''Fehler:''' Die Erstellung der Datentabellen ist aufgrund des folgenden Fehlers gescheitert: $1",
	'config-install-interwiki' => 'Interwikitabellen werden eingerichtet',
	'config-install-interwiki-sql' => 'Die Datei <code>interwiki.sql</code> konnte nicht gefunden werden.',
	'config-install-interwiki-exists' => "'''Warnung:'''  Es wurden Interwikitabellen mit Daten gefunden.
Die Standardliste wird übersprungen.",
	'config-install-secretkey' => 'Erstellung des Geheimschlüssels',
	'config-insecure-secretkey' => "'''Warnung:''' Die Erstellung des Geheimschlüssels <code>\$wgSecretKey</code> ist gescheitert.
Sie muss manuell nachgeholt werden.",
	'config-install-sysop' => 'Administratorkonto wird erstellt',
	'config-install-done' => "'''Herzlichen Glückwunsch!'''
MediaWiki wurde erfolgreich installiert.

Das Installationsprogramm hat die Datei <code>LocalSettings.php</code> erzeugt.
Sie enthält alle Konfigurationseinstellungen.

Diese Datei muss [$1 heruntergeladen] und in das Stammverzeichnis der MediaWiki-Installation hochgeladen werden. Dieses ist dasselbe Verzeichnis, in dem sich die Datei <code>index.php</code> befindet.

Sobald dies erledigt ist, kann auf das '''[$2 Wiki zugegriffen werden]'''.",
);

/** Spanish (Español)
 * @author Crazymadlover
 * @author Danke7
 * @author Platonides
 * @author Sanbec
 */
$messages['es'] = array(
	'config-desc' => 'El instalador para MediaWiki',
	'config-title' => 'MediaWiki $1 instalación',
	'config-information' => 'Información',
	'config-localsettings-upgrade' => "'''Atención''': Se ha encontrado un fichero de configuración <code>LocalSettings.php</code>.
Para actualizar MediaWiki mueva <code>LocalSettings.php</code> a un lugar seguro y ejecute de nuevo el instalador.",
	'config-localsettings-noupgrade' => "'''Error''': Se ha detectado un fichero de configuración <code>LocalSettings.php</code>.
No se puede actualizar MediaWiki en este momento.
El instalador ha sido deshabilitado por motivos de seguridad.",
	'config-session-error' => 'Error comenzando sesión: $1',
	'config-session-expired' => 'Tus datos de sesión parecen haber expirado.
Las sesiones están configuradas por una duración de $1.
Puedes incrementar esto configurando <code>session.gc_maxlifetime</code> en php.ini.
Reiniciar el proceso de instalación.',
	'config-no-session' => 'Se han perdido los datos de sesión.
Verifica tu php.ini y comprueba que <code>session.save_path</code> está establecido en un directorio apropiado.',
	'config-session-path-bad' => 'Parece que tu <code>session.save_path</code> (<code>$1</code>) es incorrecta o no se tienen permisos de escritura.',
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
	'config-page-dbsettings' => 'Configuración de la base de datos',
	'config-page-name' => 'Nombre',
	'config-page-options' => 'Opciones',
	'config-page-install' => 'Instalar',
	'config-page-complete' => 'Completo!',
	'config-page-restart' => 'Reiniciar instalación',
	'config-page-readme' => 'Léeme',
	'config-page-releasenotes' => 'Notas de la versión',
	'config-page-copying' => 'Copiando',
	'config-page-upgradedoc' => 'Actualizando',
	'config-help-restart' => '¿Deseas borrar todos los datos que has ingresado hasta ahora y reiniciar el proceso de instalación desde el principio?',
	'config-restart' => 'Sí, reiniciarlo',
	'config-env-bad' => 'El entorno ha sido comprobado.
No puedes instalar MediaWiki.',
	'config-env-php' => 'PHP $1 está instalado.',
	'config-env-latest-ok' => 'Estás instalando la última versión de MediaWiki',
	'config-env-latest-new' => "'''Nota:''' Estás instalando una versión en desarrollo de MediaWiki.",
	'config-env-latest-can-not-check' => "'''Aviso:''' El instalador no ha podido recuperar información sobre la última versión de MediaWiki de [$1].",
	'config-env-latest-old' => "'''Advertencia:''' Estás instalando una versión antigua de MediaWiki.",
	'config-db-type' => 'Tipo de base de datos',
	'config-db-wiki-settings' => 'Identifique este wiki',
	'config-db-name' => 'Nombre de base de datos:',
	'config-db-install-account' => 'Cuenta de usuario para instalación',
	'config-db-username' => 'Nombre de usuario de base de datos:',
	'config-db-password' => 'contraseña de base de datos:',
	'config-db-install-help' => 'Ingresar el nombre de usuario y la contraseña que será usada para conectar a la base de datos durante el proceso de instalación.',
	'config-db-account-lock' => 'Usar el mismo nombre de usuario y contraseña durante operación normal',
	'config-db-wiki-account' => 'Usar cuenta para operación normal',
	'config-db-schema' => 'Esquema para MediaWiki',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Configuración de MySQL',
	'config-header-postgres' => 'Configuración de PostgreSQL',
	'config-header-sqlite' => 'Configuración de SQLite',
	'config-header-oracle' => 'Configuración de Oracle',
	'config-invalid-db-type' => 'Tipo de base de datos inválida',
	'config-mysql-engine' => 'Motor de almacenamiento:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' es casi siempre la mejor opción, dado que soporta bien los accesos simultáneos.

'''MyISAM''' es más rápido en instalaciones de usuario único o de sólo lectura.
Las bases de datos MyISAM tienden a corromperse más a menudo que las bases de datos InnoDB.",
	'config-mysql-egine-mismatch' => "'''Atención:''' Solicitó el motor de almacenamento $1, pero el existente en la base de datos es el motor $2.
Este código de actualización no lo puede convertir, de modo que permanecerá como $2.",
	'config-mysql-charset' => 'Conjunto de caracteres de la base de datos:',
	'config-mysql-binary' => 'Binario',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Nombre del wiki:',
	'config-site-name-help' => 'Esto aparecerá en la barra de título del navegador y en varios otros lugares.',
	'config-site-name-blank' => 'Ingresar un nombre de sitio.',
	'config-project-namespace' => 'Espacio de nombre de proyecto:',
	'config-ns-generic' => 'Proyecto',
	'config-ns-site-name' => 'Igual como el nombre del wiki: $1',
	'config-ns-other' => 'Otro (especificar)',
	'config-admin-box' => 'Cuenta de administrador',
	'config-admin-name' => 'Tu nombre:',
	'config-admin-password' => 'Contraseña:',
	'config-admin-password-confirm' => 'Repita la contraseña:',
	'config-admin-name-invalid' => 'El nombre de usuario especificado "<nowiki>$1</nowiki>" no es válido.
Especifique un nombre de usuario diferente.',
	'config-admin-password-blank' => 'Introduzca una contraseña para la cuenta de administrador.',
	'config-admin-password-same' => 'La contraseña no debe ser la misma que el nombre de usuario.',
	'config-admin-password-mismatch' => 'Las dos contraseñas que ingresaste no coinciden.',
	'config-admin-email' => 'Dirección de correo electrónico:',
	'config-optional-continue' => 'Hazme más preguntas.',
	'config-optional-skip' => 'Ya estoy aburrido, sólo instala el wiki.',
	'config-profile' => 'Perfil de derechos de usuario:',
	'config-profile-wiki' => 'Wiki tradicional',
	'config-profile-no-anon' => 'Creación de cuenta requerida',
	'config-profile-fishbowl' => 'Sólo editores autorizados',
	'config-profile-private' => 'Wiki privado',
	'config-license' => 'Copyright and licencia:',
	'config-license-pd' => 'Dominio Público',
	'config-email-settings' => 'Configuración de correo electrónico',
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

/** Finnish (Suomi)
 * @author Nike
 * @author Str4nd
 */
$messages['fi'] = array(
	'config-desc' => 'MediaWiki-asennin',
	'config-title' => 'MediaWikin version $1 asennus',
	'config-information' => 'Tiedot',
	'config-session-error' => 'Istunnon aloittaminen epäonnistui: $1',
	'config-show-help' => 'Ohjet',
	'config-hide-help' => 'Piilota ohje',
	'config-your-language' => 'Asennuksen kieli',
	'config-your-language-help' => 'Valitse kieli, jota haluat käyttää asennuksen ajan.',
	'config-wiki-language' => 'Wikin kieli',
	'config-wiki-language-help' => 'Valitse kieli, jota wikissä tullaan etupäässä käyttämään.',
	'config-back' => '← Takaisin',
	'config-continue' => 'Jatka →',
	'config-page-language' => 'Kieli',
	'config-page-welcome' => 'Tervetuloa MediaWikiin!',
	'config-page-dbconnect' => 'Tietokantaan yhdistäminen',
	'config-page-upgrade' => 'Olemassa olevan asennuksen päivitys',
	'config-page-dbsettings' => 'Tietokannan asetukset',
	'config-page-name' => 'Nimi',
	'config-page-options' => 'Asetukset',
	'config-page-install' => 'Asenna',
	'config-page-complete' => 'Valmis!',
	'config-page-restart' => 'Aloita asennus alusta',
	'config-page-readme' => 'Lue minut',
	'config-page-releasenotes' => 'Julkaisun tiedot',
	'config-page-copying' => 'Kopiointi',
	'config-page-upgradedoc' => 'Päivittäminen',
	'config-help-restart' => 'Haluatko poistaa kaikki annetut tiedot ja aloittaa asennuksen alusta?',
	'config-restart' => 'Kyllä',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWikin kotisivu]
* [http://www.mediawiki.org/wiki/Help:Contents Käyttöopas]
* [http://www.mediawiki.org/wiki/Manual:Contents Hallintaopas]
* [http://www.mediawiki.org/wiki/Manual:FAQ UKK]',
	'config-env-good' => '<span class="success-message">Asennusympäristö on tarkastettu.
Voit asentaa MediaWikin.</span>',
	'config-env-bad' => 'Asennusympäristö on tarkastettu.
Et voi asentaa MediaWikiä.',
	'config-env-php' => 'PHP $1 on asennettu.',
	'config-env-latest-ok' => 'Olet asentamassa MediaWikin viimeisintä versiota.',
	'config-env-latest-new' => "'''Huomautus:''' Olet asentamassa MediaWikin kehitysversiota.",
	'config-env-latest-old' => "'''Varoitus:''' Olet asentamassa vanhentunut versiota MediaWikistä.",
	'config-no-db' => 'Sopivaa tietokanta-ajuria ei löytynyt!',
	'config-safe-mode' => "'''Varoitus:''' PHP:n [http://www.php.net/features.safe-mode safe mode]-tila on aktiivinen.
Se voi aiheuttaa ongelmia erityisesti tiedostojen tallentamisen ja matemaattisten kaavojen kanssa.",
	'config-pcre' => 'PCRE-tukimoduuli puuttuu.
MediaWiki vaatii toimiakseen Perl-yhteensopivat säännölliset lausekkeet.',
	'config-memory-none' => 'PHP-asetusta <code>memory_limit</code> ei ole asetettu.',
	'config-memory-ok' => 'PHP:n <code>memory_limit</code>-asetuksen arvo on $1.
OK.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] on asennettu.',
	'config-apc' => '[http://www.php.net/apc APC] on asennettu.',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] on asennettu.',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] on asennettu.',
	'config-diff3-good' => 'GNU diff3 löytyi: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3:a ei löytynyt.',
	'config-db-type' => 'Tietokannan tyyppi',
	'config-db-host' => 'Tietokantapalvelin',
	'config-db-name' => 'Tietokannan nimi',
	'config-db-username' => 'Tietokannan käyttäjätunnus',
	'config-db-password' => 'Tietokannan salasana',
	'config-db-install-help' => 'Anna käyttäjätunnus ja salasana, joita käytetään asennuksen aikana.',
	'config-db-account-lock' => 'Käytä samaa tunnusta ja salasanaa myös asennuksen jälkeen',
	'config-db-prefix' => 'Tietokantataulujen etuliite',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0, binääri',
	'config-charset-mysql5' => 'MySQL 4.1/5.0, UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0, taaksepäin yhteensopiva UTF-8',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-asetukset',
	'config-header-postgres' => 'PostgreSQL-asetukset',
	'config-header-sqlite' => 'SQLite-asetukset',
	'config-header-oracle' => 'Oracle-asetukset',
	'config-invalid-db-type' => 'Virheellinen tietokantatyyppi',
	'config-missing-db-name' => 'Kenttä »Tietokannan nimi» on pakollinen',
	'config-invalid-db-name' => '”$1” ei kelpaa tietokannan nimeksi.
Se voi sisältää vain numeroita, kirjaimia ja alaviivan.',
	'config-invalid-db-prefix' => '”$1” ei kelpaa tietokannan etuliitteeksi.
Se voi sisältää vain numeroita, kirjaimia ja alaviivan.',
	'config-postgres-old' => 'MediaWiki tarvitsee PostgreSQL:n version $1 tai uudemman. Nykyinen versio on $2.',
	'config-sqlite-name-help' => 'Valitse nimi joka yksilöi tämän wikin.
Älä käytä välilyöntejä tai viivoja.
Nimeä käytetään SQlite-tietokannan tiedostonimessä.',
	'config-sqlite-dir-unwritable' => 'Hakemistoon ”$1” kirjoittaminen epäonnistui.
Muuta hakemiston käyttöoikeuksia siten, että palvelinohjelmisto voi kirjoittaa siihen ja koita uudelleen.',
	'config-sqlite-readonly' => 'Tiedostoon <code>$1</code> ei voi kirjoittaa.',
	'config-sqlite-fts3-downgrade' => 'PHP:stä puuttuu FTS3-tuki. Poistetaan ominaisuus käytöstä tietokantatauluista.',
	'config-show-table-status' => 'Kysely SHOW TABLE STATUS epäonnistui!',
	'config-mysql-engine' => 'Tallennusmoottori',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Binääri',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Wikin nimi',
	'config-admin-name' => 'Nimesi',
	'config-admin-password' => 'Salasana',
	'config-admin-password-confirm' => 'Salasana uudelleen',
	'config-admin-name-blank' => 'Anna ylläpitäjän käyttäjänimi.',
	'config-admin-email' => 'Sähköpostiosoite',
	'config-profile-private' => 'Yksityinen wiki',
	'config-install-step-done' => 'tehty',
	'config-install-step-failed' => 'epäonnistui',
);

/** French (Français)
 * @author Aadri
 * @author Crochet.david
 * @author IAlex
 * @author Jean-Frédéric
 * @author McDutchie
 * @author Peter17
 * @author Sherbrooke
 * @author Yumeki
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
	'config-session-error' => 'Erreur lors du démarrage de la session : $1',
	'config-session-expired' => "↓Les données de votre session semblent avoir expiré.
Les sessions sont configurées pour une durée de $1.
Vous pouvez l'augmenter en configurant <code>session.gc_maxlifetime</code> dans le fichier php.ini.
Redémarrer le processus d'installation.",
	'config-no-session' => 'Les données de votre session ont été perdues !
Vérifiez votre fichier php.ini et assurez-vous que <code>session.save_path</code> contient le chemin d’un répertoire approprié.',
	'config-session-path-bad' => 'Votre <code>session.save_path</code> (<code>$1</code>) semble invalide ou en lecture seule.',
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
	'config-page-dbsettings' => 'Paramètres de la base de données',
	'config-page-name' => 'Nom',
	'config-page-options' => 'Options',
	'config-page-install' => 'Installer',
	'config-page-complete' => 'Terminé !',
	'config-page-restart' => 'Redémarrer l’installation',
	'config-page-readme' => 'Lisez-moi',
	'config-page-releasenotes' => 'Notes de version',
	'config-page-copying' => 'Copie',
	'config-page-upgradedoc' => 'Mise à jour',
	'config-help-restart' => "Voulez-vous effacer toutes les données enregistrées que vous avez entrées et relancer le processus d'installation ?",
	'config-restart' => 'Oui, le relancer',
	'config-welcome' => "=== Vérifications liées à l’environnement ===
Des vérifications de base sont effectuées pour voir si cet environnement est adapté à l'installation de MediaWiki.
Vous devriez indiquer les résultats de ces vérifications si vous avez besoin d’aide lors de l’installation.",
	'config-copyright' => "=== Droit d'auteur et conditions ===

$1

Ce programme est un logiciel libre : vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation (version 2 de la Licence, ou, à votre choix, toute version ultérieure).

Ce programme est distribué dans l’espoir qu’il sera utile, mais '''sans aucune garantie''' : sans même les garanties implicites de '''commerciabilité''' ou d’'''adéquation à un usage particulier'''.
Voir la Licence Publique Générale GNU pour plus de détails.

Vous devriez avoir reçu <doclink href=Copying>une copie de la Licence Publique Générale GNU</doclink> avec ce programme ; dans le cas contraire, écrivez à la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. ou [http://www.gnu.org/copyleft/gpl.html lisez-le en ligne].",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Accueil]
* [http://www.mediawiki.org/wiki/Help:Contents Guide de l’utilisateur]
* [http://www.mediawiki.org/wiki/Manual:Contents Guide de l’administrateur]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => '<span class="success-message">L’environnement a été vérifié.
Vous pouvez installer MediaWiki.</span>',
	'config-env-bad' => 'L’environnement a été vérifié.
vous ne pouvez pas installer MediaWiki.',
	'config-env-php' => 'PHP $1 est installé.',
	'config-env-latest-ok' => 'Vous installez la dernière version de MediaWiki.',
	'config-env-latest-new' => "'''Remarque :''' Vous êtes en train d’installer une version de développement de MediaWiki.",
	'config-env-latest-can-not-check' => "'''Attention :''' Le programme d’installation n’a pas pu récupérer les informations sur la dernière version de MediaWiki depuis [$1].",
	'config-env-latest-old' => "'''Attention :''' Vous installez une version obsolète de MediaWiki.",
	'config-env-latest-help' => 'Vous êtres en train d’installer la version $1, mais la dernière version est $2. 
Il est conseillé d’utiliser la dernière version, qui peut être téléchargée de [http://www.mediawiki.org/wiki/Download MediaWiki.org]',
	'config-unicode-using-php' => 'La version lente de PHP est utilisée pour la normalisation Unicode.',
	'config-unicode-using-utf8' => 'Utilisation de utf8_normalize.so par Brion Vibber pour la normalisation Unicode.',
	'config-unicode-using-intl' => "Utilisation de [http://pecl.php.net/intl l'extension PECL intl] pour la normalisation Unicode.",
	'config-unicode-pure-php-warning' => "'''Attention''': L'[http://pecl.php.net/intl extension PECL intl] n'est pas disponible pour la normalisation d'Unicode.
Si vous utilisez un site web très fréquenté, vous devriez lire un peu sur celle-ci : [http://www.mediawiki.org/wiki/Unicode_normalization_considerations ''Unicode normalization''] (en anglais).",
	'config-unicode-update-warning' => "'''Attention''': La version installée du ''wrapper'' de normalisation Unicode utilise une vieille version de la [http://site.icu-project.org/ bibliothèque logicielle ''ICU Project''].
Vous devriez faire une [http://www.mediawiki.org/wiki/Unicode_normalization_considerations mise à jour] (texte en anglais) si l'usage d'Unicode vous semble important.",
	'config-no-db' => 'Impossible de trouver un pilote de base de données approprié !',
	'config-no-db-help' => "Vous avez besoin d'installer un pilote de base de données pour PHP. 
Les types de base de données suivants sont supportés: $1. 

Si vous êtes en hébergement mutualisé, demandez à votre fournisseur d'hébergement pour installer un pilote de base de données appropriée. 
Si vous avez compilé PHP vous-même, reconfigurez-le en activant un client de base de données, par exemple en utilisant <code>./configure --with-mysql</code>. 
Si vous avez installé PHP à partir d'un paquet Debian ou Ubuntu, vous devez également installer le module php5-mysql.",
	'config-have-db' => '{{PLURAL:$2|Pilote|Pilotes}} de base de données {{PLURAL:$2|trouvé|trouvés}} : $1.',
	'config-register-globals' => "'''Attention : l'option <code>[http://php.net/register_globals register_globals]</code> de PHP est activée.'''
'''Désactivez-la si vous le pouvez.'''
MediaWiki fonctionnera, mais votre serveur sera exposé à de potentielles failles de sécurité.",
	'config-magic-quotes-runtime' => "'''Erreur fatale : [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] est activé !'''
Cette option corrompt les données de manière imprévisible.
Vous ne pouvez pas installer ou utiliser MediaWiki tant que cette option est activée.",
	'config-magic-quotes-sybase' => "'''Erreur fatale : [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybasee] est activé !'''
Cette option corrompt les données de manière imprévisible.
Vous ne pouvez pas installer ou utiliser MediaWiki tant que cette option est activée.",
	'config-mbstring' => "'''Erreur fatale : [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] est activé !'''
Cette option provoque des erreurs et peut corrompre les données de manière imprévisible.
Vous ne pouvez pas installer ou utiliser MediaWiki tant que cette option est activée.",
	'config-ze1' => "'''Erreur fatale : [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mod] est activé !'''
Cette option provoque des bugs horribles avec MediaWiki.
Vous ne pouvez pas installer ou utiliser MediaWiki tant que cette option est activée.",
	'config-safe-mode' => "'''Attention : le « [http://www.php.net/features.safe-mode safe mode] » est activé !'''
Ceci peut causer des problèmes, en particulier si vous utilisez le téléversement de fichiers et le support de <code>math</code>.",
	'config-xml-good' => 'Support de la conversion XML / Latin1-UTF-8.',
	'config-xml-bad' => 'Le module XML de PHP est manquant.
MediaWiki requiert des fonctions de ce module et ne fonctionnera pas avec cette configuration.
Si vous êtes sous Mandrake, installez le paquet php-xml.',
	'config-pcre' => "Le module de support PCRE semble manquer. 
MediaWiki requiert les fonctions d'expression régulière compatible avec Perl.",
	'config-memory-none' => 'PHP est configuré sans paramètre <code>memory_limit</code>',
	'config-memory-ok' => 'Le paramètre <code>memory_limit</code> de PHP est à $1.
OK.',
	'config-memory-raised' => 'Le paramètre <code>memory_limit</code> de PHP était à $1, porté à $2.',
	'config-memory-bad' => "'''Attention :''' Le paramètre <code>memory_limit</code> de PHP est à $1.
Cette valeur est probablement trop faible.
Il est possible que l’installation échoue !",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] est installé',
	'config-apc' => '[http://www.php.net/apc APC] est installé',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] est installé',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] est installé',
	'config-no-cache' => "'''Attention :''' Impossible de trouver [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] ou [http://www.iis.net/download/WinCacheForPhp WinCache].
La mise en cache d'objets n'est pas activée.",
	'config-diff3-good' => 'GNU diff3 trouvé : <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 introuvable.',
	'config-imagemagick' => "ImageMagick trouvé : <code>$1</code>. 
La miniaturisation d'images sera activée si vous activez le téléversement de fichiers.",
	'config-gd' => "La bibliothèque graphique GD intégrée a été trouvée. 
La miniaturisation d'images sera activée si vous activez le téléversement de fichiers.",
	'config-no-scaling' => "Impossible de trouver la bibliothèque GD ou ImageMagick. 
La miniaturisation d'images sera désactivé.",
	'config-dir' => 'Répertoire d’installation : <code>$1</code>.',
	'config-uri' => 'Adresse URI du script : <code>$1</code>.',
	'config-no-uri' => "'''Erreur :''' Impossible de déterminer l'URI du script actuel. 
Installation avortée.",
	'config-dir-not-writable-group' => "'''Erreur:''' Impossible d'écrire le fichier de configuration. 
Installation avortée. 

L'utilisateur du serveur web est connu.
Rendre le répertoire <code><nowiki>config</nowiki></code> accessible en écriture pour continuer. 
Sur un système d'exploitation UNIX/Linux :

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Erreur:''' Impossible d'écrire le fichier de configuration. 
Installation avortée. 

L'utilisateur du serveur web ne peut être déterminé.
Rendez le dossier/répertoire <code><nowiki>config</nowiki></code> accessible en écriture globale pour continuer.
Sur un système d'exploitation UNIX/Linux :

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => "Installation de MediaWiki avec l'extension de fichier <code>$1</code>.",
	'config-shell-locale' => 'Paramètres régionaux du shell détectés : « $1 ».',
	'config-uploads-safe' => "Le répertoire par défaut pour les téléversement est à l'abri de l'exécution de scripts arbitraires.",
	'config-uploads-not-safe' => "'''Attention:''' Votre répertoire par défaut pour les téléchargements, <code>$1</code>, est vulnérable, car il peut exécuter n'importe quel script. 
Bien que MediaWiki vérifie tous les fichiers téléchargés, il est fortement recommandé de [http://www.mediawiki.org/wiki/Manual:Security#Upload_security fermer cette vulnérabilité de sécurité] (texte en anglais) avant d'activer les téléchargements.",
	'config-db-type' => 'Type de base de données :',
	'config-db-host' => 'Nom d’hôte de la base de données :',
	'config-db-host-help' => "Si votre serveur de base de données est sur un serveur différent, saisissez ici son nom d’hôte ou son adresse IP.

Si vous utilisez un hébergement mutualisé, votre hébergeur doit vous avoir fourni le nom d’hôte correct dans sa documentation.

Si vous installez sur un serveur Windows et utilisez MySQL, « localhost » peut ne pas fonctionner comme nom de serveur. S'il ne fonctionne pas, essayez « 127.0.0.1 » comme adresse IP locale.",
	'config-db-wiki-settings' => 'Identifier ce wiki',
	'config-db-name' => 'Nom de la base de données :',
	'config-db-name-help' => "Choisissez un nom qui identifie votre wiki.
Il ne doit pas contenir d'espaces ou de traits d'union.

Si vous utilisez un hébergement web partagé, votre hébergeur vous fournira un nom spécifique de base de données à utiliser, ou bien vous permet de créer des bases de données via un panneau de contrôle.",
	'config-db-install-account' => "Compte d'utilisateur pour l'installation",
	'config-db-username' => 'Nom d’utilisateur de la base de données :',
	'config-db-password' => 'Mot de passe de la base de données :',
	'config-db-install-help' => "Entrez le nom d'utilisateur et le mot de passe qui seront utilisés pour se connecter à la base de données pendant le processus d'installation.",
	'config-db-account-lock' => "Utiliser le même nom d'utilisateur et le même mot de passe pendant le fonctionnement habituel",
	'config-db-wiki-account' => "Compte d'utilisateur pour le fonctionnement habituel",
	'config-db-wiki-help' => "Entrez le nom d'utilisateur et le mot de passe qui seront utilisés pour se connecter à la base de données pendant le fonctionnement habituel du wiki. 
Si le compte n'existe pas, et le compte d'installation dispose de privilèges suffisants, ce compte d'utilisateur sera créé avec les privilèges minimum requis pour faire fonctionner le wiki.",
	'config-db-prefix' => 'Préfixe des tables de la base de données :',
	'config-db-prefix-help' => "Si vous avez besoin de partager une base de données entre plusieurs wikis, ou entre MediaWiki et une autre application Web, vous pouvez choisir d'ajouter un préfixe à tous les noms de table pour éviter les conflits. 
Ne pas utiliser des espaces ou des traits d'union. 

Ce champ est généralement laissé vide.",
	'config-db-charset' => 'Jeu de caractères de la base de données',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binaire',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 rétrocompatible UTF-8',
	'config-charset-help' => "'''Attention:''' Si vous utilisez ''backwards-compatible UTF-8'' sur MySQL 4.1+, et ensuite sauvegardez la base de données avec <code>mysqldump</code>, cela peut détruire tous les caractères non-ASCII, ce qui rend inutilisable vos copies de sauvegarde de façon irréversible ! 

En ''mode binaire'', MediaWiki stocke le texte UTF-8 dans des champs binaires de la base de données. C'est plus efficace que le ''mode UTF-8'' de MySQL, et vous permet d'utiliser toute la gamme des caractères Unicode. 
En ''mode UTF-8'', MySQL connaîtra le jeu de caractères de vos données et pourra présenter et convertir les données de manière appropriée, mais il ne vous laissera pas stocker les caractères au-dessus du [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingue de base] (en anglais).",
	'config-mysql-old' => 'MySQL $1 ou version ultérieure est requis, vous avez $2.',
	'config-db-port' => 'Port de la base de données :',
	'config-db-schema' => 'Schéma pour MediaWiki',
	'config-db-ts2-schema' => 'Schéma pour tsearch2',
	'config-db-schema-help' => "Les schémas ci-dessus sont généralement corrects.
Ne les changez que si vous êtes sûr que c'est nécessaire.",
	'config-sqlite-dir' => 'Dossier des données SQLite :',
	'config-sqlite-dir-help' => "SQLite stocke toutes les données dans un fichier unique. 

Le répertoire que vous inscrivez doit être accessible en écriture par le serveur lors de l'installation. 

Il '''ne faut pas''' qu'il soit accessible via le web, c'est pourquoi il n'est pas à l'endroit où vos fichiers PHP sont. 

L'installateur écrira un fichier <code>.htaccess</code> en même temps, mais s'il y a échec, quelqu'un peut accéder à votre base de données.
Cela comprend les données des utilisateurs (adresses de courriel, mots de passe hachés) ainsi que des révisions supprimées et d'autres données confidentielles du wiki.

Envisagez de placer la base de données ailleurs, par exemple dans <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => "MediaWiki supporte ces systèmes de bases de données : 

 $1 

Si vous ne voyez pas le système de base de données que vous essayez d'utiliser ci-dessous, alors suivez les instructions ci-dessus (voir liens) pour activer le support.",
	'config-support-mysql' => '* $1 est le premier choix pour MediaWiki et est mieux pris en charge ([http://www.php.net/manual/en/mysql.installation.php how to compile PHP with MySQL support])',
	'config-support-postgres' => "* $1 est un système de base de données populaire et ''open source'' qui peut être une alternative à MySQL ([http://www.php.net/manual/en/pgsql.installation.php how to compile PHP with PostgreSQL support])",
	'config-support-sqlite' => '* $1 est un système de base de données léger qui est bien supporté. ([http://www.php.net/manual/en/pdo.installation.php How to compile PHP with SQLite support], utilise PDO)',
	'config-header-mysql' => 'Paramètres de MySQL',
	'config-header-postgres' => 'Paramètres de PostgreSQL',
	'config-header-sqlite' => 'Paramètres de SQLite',
	'config-header-oracle' => 'Paramètres d’Oracle',
	'config-invalid-db-type' => 'Type de base de données non valide',
	'config-missing-db-name' => 'Vous devez saisir une valeur pour « Nom de la base de données »',
	'config-invalid-db-name' => 'Nom de la base de données invalide (« $1 »).
Il ne peut contenir que des lettres latines (a-z, A-Z), des chiffres (0-9) et des caractères de soulignement (_).',
	'config-invalid-db-prefix' => 'Préfixe de la base de données non valide « $1 ». 
Il ne peut contenir que des lettres latines (a-z, A-Z), des chiffres (0-9) et des caractères de soulignement (_).',
	'config-connection-error' => '$1.

Vérifier le nom d’hôte, le nom d’utilisateur et le mot de passe ci-dessous puis réessayer.',
	'config-invalid-schema' => 'Schéma invalide pour MediaWiki « $1 ». 
Utilisez seulement des lettres latines (a-z, A-Z), des chiffres (0-9) et des caractères de soulignement (_).',
	'config-invalid-ts2schema' => 'Schéma non valide pour TSearch2 « $1 ». 
Utilisez seulement des lettres latines (a-z, A-Z), des chiffres (0-9) et des caractères de soulignement (_).',
	'config-postgres-old' => 'PostgreSQL $1 ou version ultérieure est requis, vous avez $2.',
	'config-sqlite-name-help' => "Choisir un nom qui identifie votre wiki. 
Ne pas utiliser des espaces ou des traits d'union. 
Il sera utilisé pour le fichier de données SQLite.",
	'config-sqlite-parent-unwritable-group' => "Impossible de créer le répertoire de données <nowiki><code>$1</code></nowiki>, parce que le répertoire parent <nowiki><code>$2</code></nowiki> n'est pas accessible en écriture par le serveur Web.

L'utilisateur du serveur web est connu. 
Rendre le répertoire <nowiki><code>$3</code></nowiki> accessible en écriture pour continuer. 
Sur un système UNIX/Linux, saisir : 

<pre>cd $2 
mkdir $3 
chgrp $4 $3 
chmod g+w $3</pre>",
	'config-sqlite-parent-unwritable-nogroup' => "Impossible de créer le répertoire de données <nowiki><code>$1</code></nowiki>, parce que le répertoire parent <nowiki><code>$2</code></nowiki> n'est pas accessible en écriture par le serveur Web. 

L'utilisateur du serveur web est inconnu.
Rendre le répertoire <nowiki><code>$3</code></nowiki> globalement accessible en écriture pour continuer. 
Sur un système UNIX/Linux, saisir : 

<pre>cd $2 
mkdir $3 
chmod a+w $3</pre>",
	'config-sqlite-mkdir-error' => "Erreur de création du répertoire de données « $1 ».
Vérifiez l'emplacement et essayez à nouveau.",
	'config-sqlite-dir-unwritable' => "Impossible d'écrire dans le répertoire « $1 ». 
Changer les permissions de sorte que le serveur peut y écrire et essayez à nouveau.",
	'config-sqlite-connection-error' => '$1.

Vérifier le répertoire des données et le nom de la base de données ci-dessous et réessayer.',
	'config-sqlite-readonly' => "Le fichier <code>$1</code> n'est pas accessible en écriture.",
	'config-sqlite-cant-create-db' => 'Impossible de créer le fichier de base de données <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP ne vient pas avec FTS3, les tables sont diminuées.',
	'config-sqlite-fts3-add' => 'Ajout des services de recherche FTS3',
	'config-can-upgrade' => "Il y a des tables MediaWiki dans cette base de données. 
Pour les mettre au niveau de MediaWiki $1, cliquez sur '''Continuer'''.",
	'config-upgrade-done' => "Mise à jour complétée. 

Vous pouvez maintenant [$1 commencer à utiliser votre wiki]. 

Si vous souhaitez régénérer votre fichier <code>LocalSettings.php</code>, cliquez sur le bouton ci-dessous. 
Ce '''n'est pas recommandé''' sauf si vous rencontrez des problèmes avec votre wiki.",
	'config-regenerate' => 'Regénérer LocalSettings.php →',
	'config-show-table-status' => 'Échec de la requête SHOW TABLE STATUS !',
	'config-unknown-collation' => "'''Attention:''' La base de données effectue un classement alphabétique (''collation'') inconnu.",
	'config-db-web-account' => "Compte de la base de données pour l'accès Web",
	'config-db-web-help' => "Sélectionnez le nom d'utilisateur et le mot de passe que le serveur web utilisera pour se connecter au serveur de base de données pendant le fonctionnement habituel du wiki.",
	'config-db-web-account-same' => "Utilisez le même compte que pour l'installation",
	'config-db-web-create' => "Créez le compte s'il n'existe pas déjà",
	'config-db-web-no-create-privs' => "Le compte que vous avez spécifié pour l'installation n'a pas de privilèges suffisants pour créer un compte. 
Le compte que vous spécifiez ici doit déjà exister.",
	'config-mysql-engine' => 'Moteur de stockage :',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' est presque toujours la meilleure option, car il supporte bien l'[http://fr.wikipedia.org/wiki/Ordonnancement_dans_les_syst%C3%A8mes_d%27exploitation ordonnancement]. 

'''MyISAM''' peut être plus rapide dans les installations monoposte ou en lecture seule. Les bases de données MyISAM ont tendance à se corrompre plus souvent que celles d'InnoDB.",
	'config-mysql-egine-mismatch' => "'''Attention:''' Vous avez demandé le moteur de stockage $1, mais la base de données existante utilise le moteur $2. 
Ce script de mise à niveau ne peut pas le convertir, il restera $2.",
	'config-mysql-charset' => 'Jeu de caractères de la base de données :',
	'config-mysql-binary' => 'Binaire',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "En ''mode binaire'', MediaWiki stocke le texte au format UTF-8 dans la base de données. C'est plus efficace que le ''UTF-8 mode'' de MySQL, et vous permet d'utiliser toute la gamme des caractères Unicode. 

En ''mode binaire'', MediaWiki stocke le texte UTF-8 dans des champs binaires de la base de données. C'est plus efficace que le ''mode UTF-8'' de MySQL, et vous permet d'utiliser toute la gamme des caractères Unicode. 
En ''mode UTF-8'', MySQL connaîtra le jeu de caractères de vos données et pourra présenter et convertir les données de manière appropriée, mais il ne vous laissera pas stocker les caractères au-dessus du [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingue de base] (en anglais).",
	'config-mysql-charset-mismatch' => "'''Attention:''' Vous avez demandé le schéma $1, mais la base de données existante a le schéma $2. 
Ce script de mise à niveau ne peut pas le convertir, il restera $2.",
	'config-site-name' => 'Nom du wiki :',
	'config-site-name-help' => 'Il apparaîtra dans la barre de titre du navigateur et en divers autres endroits.',
	'config-site-name-blank' => 'Entrez un nom de site.',
	'config-project-namespace' => 'Espace de noms du projet :',
	'config-ns-generic' => 'Projet',
	'config-ns-site-name' => 'Même nom que le wiki : $1',
	'config-ns-other' => 'Autre (préciser)',
	'config-ns-other-default' => 'MonWiki',
	'config-project-namespace-help' => "Suivant l'exemple de Wikipédia, plusieurs wikis gardent leurs pages de politique séparées de leurs pages de contenu, dans un ''espace de noms'' propre. 
Tous les titres de page de cet espace de noms commence par un préfixe défini, que vous pouvez spécifier ici. 
Traditionnellement, ce préfixe est dérivé du nom du wiki, mais il ne peut contenir des caractères de ponctuation tels que « # » ou « : ».",
	'config-ns-invalid' => "L'espace de noms spécifié « <nowiki>$1</nowiki> » n'est pas valide. 
Spécifiez un espace de noms pour le projet.",
	'config-admin-box' => 'Compte administrateur',
	'config-admin-name' => 'Votre nom :',
	'config-admin-password' => 'Mot de passe :',
	'config-admin-password-confirm' => 'Saisir à nouveau le mot de passe :',
	'config-admin-help' => "Entrez votre nom d'utilisateur préféré ici, par exemple « Jean Blogue ». 
C'est le nom que vous utiliserez pour vous connecter au wiki.",
	'config-admin-name-blank' => "Entrez un nom d'administrateur.",
	'config-admin-name-invalid' => "Le nom d'utilisateur spécifié « <nowiki>$1</nowiki> » n'est pas valide. 
Indiquez un nom d'utilisateur différent.",
	'config-admin-password-blank' => 'Entrez un mot de passe pour le compte administrateur.',
	'config-admin-password-same' => "Le mot de passe doit être différent du nom d'utilisateur.",
	'config-admin-password-mismatch' => 'Les deux mots de passe que vous avez saisis ne correspondent pas.',
	'config-admin-email' => 'Adresse de courriel :',
	'config-admin-email-help' => "Entrez une adresse de courriel ici pour vous permettre de recevoir des courriels d'autres utilisateurs du wiki, réinitialiser votre mot de passe, et être informé des modifications apportées aux pages de votre liste de suivi.",
	'config-admin-error-user' => "Erreur interne lors de la création d'un administrateur avec le nom « <nowiki>$1</nowiki> ».",
	'config-admin-error-password' => "Erreur interne lors de l'inscription d'un mot de passe pour l'administrateur « <nowiki>$1</nowiki> » : <pre>$2</pre>",
	'config-subscribe' => "Abonnez-vous à la [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce liste d'annonce des nouvelles versions] (la page peut afficher le texte en français).",
	'config-subscribe-help' => "Il s'agit d'une liste de diffusion à faible volume utilisée servant à annoncer les nouvelles versions, y compris les versions améliorant la sécurité du logiciel. 
Vous devriez y souscrire et mettre à jour votre version de MediaWiki lorsque de nouvelles versions sont publiées.",
	'config-almost-done' => 'Vous avez presque fini !
Vous pouvez passer la configuration restante et installer immédiatement le wiki.',
	'config-optional-continue' => 'Me poser davantage de questions.',
	'config-optional-skip' => 'J’en ai assez, installer simplement le wiki.',
	'config-profile' => 'Profil des droits d’utilisateurs :',
	'config-profile-wiki' => 'Wiki traditionnel',
	'config-profile-no-anon' => 'Création de comte requise',
	'config-profile-fishbowl' => 'Éditeurs autorisés seulement',
	'config-profile-private' => 'Wiki privé',
	'config-profile-help' => "Les wikis fonctionnent mieux lorsque vous laissez le plus de personnes possible le modifier.
Avec MediaWiki, il est facile de vérifier les modifications récentes et de révoquer tout dommage créé par des utilisateurs débutants ou mal intentionnés.

Cependant, de nombreuses autres utilisations ont été trouvées au logiciel et il n’est pas toujours facile de convaincre tout le monde des bénéfices de l’esprit wiki.
Vous avez donc le choix.

'''{{int:config-profile-wiki}}''' autorise quiconque à modifier, y compris sans s’identifier.
'''{{int:config-profile-no-anon}}''' fournit plus de contrôle, par l’identification, mais peut rebuter les contributeurs occasionnels.

'''{{int:config-profile-fishbowl}}''' autorise la modification par les utilisateurs approuvés, mais le public peut toujours lire les pages et leur historique.
'''{{int:config-profile-private}}''' n’autorise que les utilisateurs approuvés à voir et modifier les pages.

Des configurations de droits d’utilisateurs plus complexes sont disponibles après l'installation, voir la [http://www.mediawiki.org/wiki/Manual:User_rights page correspondante du manuel].",
	'config-license' => "Droits d'auteur et licence :",
	'config-license-none' => 'Aucune licence en bas de page',
	'config-license-cc-by-sa' => "Creative Commons attribution partage à l'identique (compatible avec Wikipédia)",
	'config-license-cc-by-nc-sa' => "Creative Commons attribution non commercial partage à l'identique",
	'config-license-gfdl-old' => 'Licence de documentation libre GNU 1.2',
	'config-license-gfdl-current' => 'Licence de documentation libre GNU 1.3 ou plus récent',
	'config-license-pd' => 'Domaine public',
	'config-license-cc-choose' => 'Sélectionner une licence Creative Commons personnalisée',
	'config-license-help' => "Beaucoup de wikis publics mettent l'ensemble des contributions sous [http://freedomdefined.org/Definition/Fr licence libre].
Cela contribue à créer un sentiment d'appartenance dans leur communauté et encourage les contributions sur le long terme.
Ce n'est généralement pas nécessaire pour un wiki privé ou d'entreprise. 

Si vous souhaitez utiliser des textes de Wikipédia, et souhaitez que Wikipédia réutilise des textes de votre wiki, vous devriez choisir la [http://creativecommons.org/licenses/by-sa/3.0/deed.fr licence ''Creative Commons Attribution Share Alike''] (CC-by-sa).

Wikipédia a déjà été publié selon les termes de la [http://fr.wikipedia.org/wiki/Licence_de_documentation_libre_GNU ''GNU Free Documentation License''] (GFDL). 
C'est encore une licence valide, mais elle possède des caractéristiques qui rendent difficiles la réutilisation et l'interprétation des textes.",
	'config-email-settings' => 'Paramètres de courriel',
	'config-enable-email' => 'Activer les courriels sortants',
	'config-enable-email-help' => 'Si vous souhaitez utiliser le courriel, vous devez [http://www.php.net/manual/en/mail.configuration.php configurer des paramètres PHP] (texte en anglais). 
Si vous ne voulez pas du service de courriel, vous pouvez le désactiver ici.',
	'config-email-user' => 'Activer les courriels de utilisateur à utilisateur',
	'config-email-user-help' => "Permet à tous les utilisateurs d'envoyer des courriels à d'autres utilisateurs si cela est activé dans leurs préférences.",
	'config-email-usertalk' => 'Activer la notification des pages de discussion des utilisateurs',
	'config-email-usertalk-help' => 'Permet aux utilisateurs de recevoir une notification en cas de modification de leurs pages de discussion, si cela est activé dans leurs préférences.',
	'config-email-watchlist' => 'Activer la notification de la liste de suivi',
	'config-email-watchlist-help' => "Permet aux utilisateurs de recevoir des notifications à propos des pages qu'ils ont en suivi (si cette préférence est activée).",
	'config-email-auth' => "Activer l'authentification par courriel",
	'config-email-auth-help' => "Si cette option est activée, les utilisateurs doivent confirmer leur adresse de courriel en utilisant l'hyperlien envoyé à chaque fois qu'ils la définissent ou la modifient. 
Seules les adresses authentifiées peuvent recevoir des courriels des autres utilisateurs ou lorsqu'il y a des notifications de modification.
L'activation de cette option est '''recommandée''' pour les wikis publics en raison d'abus potentiels des fonctionnalités de courriels.",
	'config-email-sender' => 'Adresse de courriel de retour :',
	'config-email-sender-help' => "Entrez l'adresse de courriel à utiliser comme adresse de retour des courriels sortant. 
Les courriels rejetés y seront envoyés.
De nombreux serveurs de courriels exigent au moins un [http://fr.wikipedia.org/wiki/Nom_de_domaine nom de domaine] valide.",
	'config-upload-settings' => 'Téléchargement des images et des fichiers',
	'config-upload-enable' => 'Activer le téléchargement des fichiers',
	'config-upload-help' => "Le téléchargement des fichiers expose votre serveur à des risques de sécurité. 
Pour plus d'informations, lire la section [http://www.mediawiki.org/wiki/Manual:Security ''Security''] du manuel d'installation (en anglais). 

Pour autoriser le téléchargement des fichiers, modifier le mode du sous-répertoire <code>images</code> qui se situe sous le répertoire racine de MediaWiki. 
Ensuite, activez cette option.",
	'config-upload-deleted' => 'Répertoire pour les fichiers supprimés :',
	'config-upload-deleted-help' => 'Choisissez un répertoire qui servira à archiver les fichiers supprimés. 
Idéalement, il ne devrait pas être accessible depuis le web.',
	'config-logo' => 'URL du logo :',
	'config-logo-help' => "L'habillage (''skin'') par défaut de MediaWiki comprend l'espace pour un logo de 135x160 pixels dans le coin supérieur gauche. 
Téléchargez une image de la taille appropriée, et entrez l'URL ici. 

Si vous ne voulez pas d'un logo, laissez cette case vide.",
	'config-instantcommons' => "Activer ''InstantCommons''",
	'config-instantcommons-help' => "[http://www.mediawiki.org/wiki/InstantCommons InstantCommons] est un service qui permet d'utiliser les images, les sons et les autres médias disponibles sur le site [http://commons.wikimedia.org/ Wikimedia Commons]. 
Pour se faire, il faut que MediaWiki accède à Internet. \$1

Pour plus d'informations sur ce service, y compris les instructions sur la façon de le configurer pour d'autres wikis que Wikimedia Commons, consultez le [http://mediawiki.org/wiki/Manual:\$wgForeignFileRepos manuel] (en anglais).",
	'config-instantcommons-good' => "Le programme d'installation a détecté une connexion à Internet au cours des contrôles de l'environnement. 
Vous pouvez activer cette fonction si vous le souhaitez.",
	'config-instantcommons-bad' => "''Malheureusement, le programme d'installation n'a pas pu détecter une connexion à Internet au cours des contrôles de l'environnement, vous ne pourrez peut-être pas utiliser cette fonctionnalité.''
''Si votre serveur est derrière un proxy, vous devrez peut-être faire quelques [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy configurations supplémentaires] (texte en anglais).''",
	'config-cc-error' => "Le sélection d'une licence ''Creative Commons'' n'a donné aucun résultat. 
Entrez le nom de la licence manuellement.",
	'config-cc-again' => 'Choisissez à nouveau...',
	'config-cc-not-chosen' => "Choisissez une licence ''Creative Commons'' et cliquez sur « Continuer ».",
	'config-advanced-settings' => 'Configuration avancée',
	'config-cache-options' => 'Paramètres pour la mise en cache des objets:',
	'config-cache-help' => "La mise en cache des objets améliore la vitesse de MediaWiki en mettant en cache les données fréquemment utilisées. 
Les sites de taille moyenne à grande sont fortement encouragés à l'activer. Les petits sites y verront également des avantages.",
	'config-cache-none' => 'Aucune mise en cache (aucune fonctionnalité supprimée, mais la vitesse peut changer sur les wikis importants)',
	'config-cache-accel' => 'Mise en cache des objets PHP (APC, eAccelerator, XCache ou WinCache)',
	'config-cache-memcached' => 'Utiliser Memcached (nécessite une installation et une configuration supplémentaires)',
	'config-memcached-servers' => 'serveurs pour Memcached :',
	'config-memcached-help' => 'Liste des adresses IP à utiliser pour Memcached. 
Elles doivent être séparés par des virgules et vous devez spécifier le port à utiliser (par exemple, 127.0.0.1:11211 et 192.168.1.25:11211).',
	'config-extensions' => 'Extensions',
	'config-extensions-help' => 'Les extensions énumérées ci-dessus ont été détectées dans votre répertoire <code>./extensions</code>. 

Elles peuvent nécessiter une configuration supplémentaire, mais vous pouvez les activer maintenant',
	'config-install-alreadydone' => "'''Attention''': Vous semblez avoir déjà installé MediaWiki et tentez de l'installer à nouveau. 
S'il vous plaît, allez à la page suivante.",
	'config-install-step-done' => 'fait',
	'config-install-step-failed' => 'échec',
	'config-install-extensions' => 'Inclusion des extensions',
	'config-install-database' => 'Création de la base de données',
	'config-install-pg-schema-failed' => "Échec lors de la création des tables. 
Assurez-vous que l'utilisateur « $1 » peut écrire selon le schéma « $2 ».",
	'config-install-user' => "Création d'un utilisateur de la base de données",
	'config-install-user-failed' => "Échec lors de l'ajout de permissions à l'utilisateur « $1 » : $2",
	'config-install-tables' => 'Création des tables',
	'config-install-tables-exist' => "'''Avertissement:''' Les tables MediaWiki semblent déjà exister. 
Création omise.",
	'config-install-tables-failed' => "'''Erreur:''' échec lors de la création de la table avec l'erreur suivante: $1",
	'config-install-interwiki' => 'Remplissage par défaut de la table des interwikis',
	'config-install-interwiki-sql' => 'Impossible de trouver le fichier <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Attention:''' La table des interwikis semble déjà contenir des entrées. 
La liste par défaut ne sera pas inscrite.",
	'config-install-secretkey' => 'Génération de la clé secrète',
	'config-insecure-secretkey' => "'''Attention:''' Impossible de créer un <code>\$wgSecretKey</code> sécurisé. 
Envisagez de le changer manuellement.",
	'config-install-sysop' => 'Création du compte administrateur',
	'config-install-done' => "'''Félicitations!''' 
Vous avez réussi à installer MediaWiki. 

Le programme d'installation a généré un fichier <code>LocalSettings.php</code>. 
Il contient tous les paramètres de configuration.

Vous devez le [télécharger $1] et le mettre dans le répertoire de base de l'installation (c'est le même répertoire que celui de <code>index.php</code>). 
'''Note''': Si vous ne le faites pas maintenant, ce fichier de configuration généré ne sera pas disponible plus tard si vous quittez l'installation sans le télécharger. 

Lorsque cela a été fait, vous pouvez '''[$2 accéder à votre wiki]'''.",
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'config-desc' => 'O programa de instalación de MediaWiki',
	'config-title' => 'Instalación de MediaWiki $1',
	'config-information' => 'Información',
	'config-localsettings-upgrade' => "'''Atención:''' Detectouse un ficheiro <code>LocalSettings.php</code>.
O seu software está listo para a actualización.
Traslade <code>LocalSettings.php</code> a un lugar seguro e execute o instalador de novo.",
	'config-localsettings-noupgrade' => "'''Erro:''' Detectouse un ficheiro <code>LocalSettings.php</code>.
O seu software non está listo para a actualización nestes intres.
O instalador foi desactivado por motivos de seguridade.",
	'config-session-error' => 'Erro ao iniciar a sesión: $1',
	'config-session-expired' => 'Semella que os seus datos da sesión caducaron.
As sesións están configuradas para unha duración de $1.
Pode incrementar isto fixando <code>session.gc_maxlifetime</code> en php.ini.
Reinicie o proceso de instalación.',
	'config-no-session' => 'Perdéronse os datos da súa sesión!
Comprobe o seu php.ini e asegúrese de que en <code>session.save_path</code> está definido un directorio correcto.',
	'config-session-path-bad' => 'O seu <code>session.save_path</code> (<code>$1</code>) semella incorrecto ou non se pode escribir nel.',
	'config-show-help' => 'Axuda',
	'config-hide-help' => 'Agochar a axuda',
	'config-your-language' => 'A súa lingua:',
	'config-your-language-help' => 'Seleccione a lingua que se empregará durante o proceso de instalación.',
	'config-wiki-language' => 'Lingua do wiki:',
	'config-wiki-language-help' => 'Seleccione a lingua que predominará no wiki.',
	'config-back' => '← Volver',
	'config-continue' => 'Continuar →',
	'config-page-language' => 'Lingua',
	'config-page-welcome' => 'Benvido a MediaWiki!',
	'config-page-dbconnect' => 'Conectarse á base de datos',
	'config-page-upgrade' => 'Actualizar a instalación actual',
	'config-page-dbsettings' => 'Configuración da base de datos',
	'config-page-name' => 'Nome',
	'config-page-options' => 'Opcións',
	'config-page-install' => 'Instalar',
	'config-page-complete' => 'Completo!',
	'config-page-restart' => 'Reiniciar a instalación',
	'config-page-readme' => 'Léame',
	'config-page-releasenotes' => 'Notas de lanzamento',
	'config-page-copying' => 'Copiar',
	'config-page-upgradedoc' => 'Actualizar',
	'config-help-restart' => 'Quere eliminar todos os datos gardados e reiniciar o proceso de instalación?',
	'config-restart' => 'Si, reiniciala',
	'config-welcome' => '=== Comprobación do entorno ===
Cómpre realizar unhas comprobacións básicas para ver se o entorno é axeitado para a instalación de MediaWiki.
Deberá proporcionar os resultados destas comprobacións se necesita axuda durante a instalación.',
	'config-copyright' => "=== Dereitos de autor e termos de uso ===

$1

Este programa é software libre; pode redistribuílo e/ou modificalo segundo os termos da licenza pública xeral GNU publicada pola Free Software Foundation; versión 2 ou (na súa escolla) calquera outra posterior.

Este programa distribúese coa esperanza de que poida ser útil, pero '''sen ningunha garantía'''; nin sequera a garantía implícita de '''comercialización''' ou '''adecuación a unha finalidade específica'''.
Olle a licenza pública xeral GNU para obter máis detalles.

Debería recibir <doclink href=Copying>unha copia da licenza pública xeral GNU</doclink> xunto ao programa; se non é así, escriba á Free Software Foundation, Inc., 51 da rúa Franklin, quinto andar, Boston, MA 02110-1301, Estados Unidos ou [http://www.gnu.org/copyleft/gpl.html lea a licenza en liña].",
	'config-sidebar' => '* [http://www.mediawiki.org/wiki/MediaWiki/gl Páxina principal de MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Guía de usuario]
* [http://www.mediawiki.org/wiki/Manual:Contents Guía de administrador]
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas máis frecuentes]',
	'config-env-good' => '<span class="success-message">Rematou a comprobación do entorno.
Pode instalar MediaWiki.</span>',
	'config-env-bad' => 'Rematou a comprobación do entorno.
Non pode instalar MediaWiki.',
	'config-env-php' => 'PHP $1 está instalado.',
	'config-env-latest-ok' => 'Está a instalar a última versión de MediaWiki.',
	'config-env-latest-new' => "'''Nota:''' Está a instalar unha versión en desenvolvemento de MediaWiki.",
	'config-env-latest-can-not-check' => "'''Atención:''' O instalador foi incapaz de recuperar a información sobre a última versión de MediaWiki de [$1].",
	'config-env-latest-old' => "'''Atención:''' Está a instalar unha versión vella de MediaWiki.",
	'config-env-latest-help' => 'Está a instalar a versión $1, pero a última versión é a $2.
Aconséllase empregar o último lanzamento. Pódeo descargar en [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Usando a implementación PHP lenta para a normalización Unicode.',
	'config-unicode-using-utf8' => 'Usando utf8_normalize.so de Brion Vibber para a normalización Unicode.',
	'config-unicode-using-intl' => 'Usando a [http://pecl.php.net/intl extensión intl PECL] para a normalización Unicode.',
	'config-unicode-pure-php-warning' => "'''Atención:''' A [http://pecl.php.net/intl extensión intl PECL] non está dispoñible para manexar a normalización Unicode.
Se o seu sitio posúe un alto tráfico de visitantes, debería ler un chisco sobre a [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalización Unicode].",
	'config-unicode-update-warning' => "'''Atención:''' A versión instalada da envoltura de normalización Unicode emprega unha versión vella da biblioteca [http://site.icu-project.org/ do proxecto ICU].
Debería [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualizar] se o uso de Unicode é importante para vostede.",
	'config-no-db' => 'Non se puido atopar un controlador axeitado para a base de datos!',
	'config-no-db-help' => 'Debe instalar un controlador de base de datos para PHP.
Os tipos de base de datos soportados son os seguintes: $1.

Se está nun aloxamento compartido, pregunte ao seu provedor de hospedaxe para instalar un controlador de base de datos axeitado.
Se compilou o PHP vostede mesmo, reconfigúreo activando un cliente de base de datos, por exemplo, usando <code>./configure --with-mysql</code>.
Se instalou o PHP desde un paquete Debian ou Ubuntu, entón tamén necesita instalar o módulo php5-mysql.',
	'config-have-db' => '{{PLURAL:$2|Controlador|Controladores}} da base de datos {{PLURAL:$2|atopado|atopados}}: $1.',
	'config-register-globals' => "'''Atención: A opción PHP <code>[http://php.net/register_globals register_globals]</code> está activada.'''
'''Desactívea se pode.'''
MediaWiki funcionará, pero o seu servidor está exposto a potenciais vulnerabilidades de seguridade.",
	'config-magic-quotes-runtime' => "'''Erro fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] está activado!'''
Esta opción corrompe os datos de entrada de xeito imprevisible.
Non pode instalar ou empregar MediaWiki a menos que esta opción estea desactivada.",
	'config-magic-quotes-sybase' => "'''Erro fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] está activado!'''
Esta opción corrompe os datos de entrada de xeito imprevisible.
Non pode instalar ou empregar MediaWiki a menos que esta opción estea desactivada.",
	'config-mbstring' => "'''Erro fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] está activado!'''
Esta opción causa erros e pode corromper os datos de xeito imprevisible.
Non pode instalar ou empregar MediaWiki a menos que esta opción estea desactivada.",
	'config-ze1' => "'''Erro fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] está activado!'''
Esta opción causa erros horribles en MediaWiki.
Non pode instalar ou empregar MediaWiki a menos que esta opción estea desactivada.",
	'config-safe-mode' => "'''Atención:''' O [http://www.php.net/features.safe-mode safe mode] do PHP está activado.
Isto pode causar problemas, particularmente se emprega cargas de ficheiros e soporte de <code>math</code>.",
	'config-xml-good' => 'Ten soporte para a conversión XML/Latin1-UTF-8.',
	'config-xml-bad' => 'Falta o módulo XML do PHP.
MediaWiki necesita funcións neste módulo e non funcionará con esta configuración.
Se está executando o Mandrake, instale o paquete php-xml.',
	'config-pcre' => 'Semella que falta o módulo de soporte PCRE.
MediaWiki necesita que funcionen as expresións regulares compatibles co Perl.',
	'config-memory-none' => 'PHP está configurado sen o parámetro <code>memory_limit</code>',
	'config-memory-ok' => 'O parámetro <code>memory_limit</code> do PHP é $1.
De acordo.',
	'config-memory-raised' => 'O parámetro <code>memory_limit</code> do PHP é $1. Aumentado a $2.',
	'config-memory-bad' => "'''Atención:''' O parámetro <code>memory_limit</code> do PHP é $1.
Probablemente é un valor baixo de máis.
A instalación pode fallar!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] está instalado',
	'config-apc' => '[http://www.php.net/apc APC] está instalado',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] está instalado',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] está instalado',
	'config-no-cache' => "'''Atención:''' Non se puido atopar [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] ou [http://www.iis.net/download/WinCacheForPhp WinCache].
A caché de obxectos está desactivada.",
	'config-diff3-good' => 'GNU diff3 atopado: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 non se atopou.',
	'config-imagemagick' => 'ImageMagick atopado: <code>$1</code>.
As miniaturas de imaxes estarán dispoñibles se activa as cargas.',
	'config-gd' => 'Atopouse a biblioteca gráfica GD integrada.
As miniaturas de imaxes estarán dispoñibles se activa as cargas.',
	'config-no-scaling' => 'Non se puido atopar a biblioteca GD ou ImageMagick.
As miniaturas de imaxes estarán desactivadas.',
	'config-dir' => 'Directorio de instalación: <code>$1</code>.',
	'config-uri' => 'Enderezo URI da escritura: <code>$1</code>.',
	'config-no-uri' => "'''Erro:''' Non se puido determinar o URI actual.
Instalación abortada.",
	'config-dir-not-writable-group' => "'''Erro:''' Non se puido escribir o ficheiro de configuración.
Instalación abortada.

O instalador determinou o usuario que o servidor usa para a execución.
Faga que o directorio <code><nowiki>config</nowiki></code> se poida escribir para cotinuar.
Nun sistema Unix/Linux:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Erro:''' Non se puido escribir o ficheiro de configuración.
Instalación abortada.

O instalador non puido determinar o usuario que o servidor usa para a execución.
Faga que o directorio <code><nowiki>config</nowiki></code> se poida escribir globalmente para cotinuar.
Nun sistema Unix/Linux:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'Instalando MediaWiki coas extensións de ficheiro <code>$1</code>.',
	'config-shell-locale' => 'Parámetros rexionais detectados do shell: "$1"',
	'config-uploads-safe' => 'O directorio por defecto para as cargas está a salvo da execución arbitraria de escrituras.',
	'config-uploads-not-safe' => "'''Atención:''' O seu directorio por defecto para as cargas, <code>$1</code>, é vulnerable a execucións arbitrarias de escrituras.
Aínda que MediaWiki comproba todos os ficheiros cargados por se houbese ameazas de seguridade, é amplamente recomendable [http://www.mediawiki.org/wiki/Manual:Security#Upload_security pechar esta vulnerabilidade de seguridade] antes de activar as cargas.",
	'config-db-type' => 'Tipo de base de datos:',
	'config-db-host' => 'Servidor da base de datos:',
	'config-db-host-help' => 'Se o servidor da súa base de datos está nun servidor diferente, escriba o nome do servidor ou o enderezo IP aquí.

Se está usando un aloxamento web compartido, o seu provedor de hospedaxe debe darlle o nome de servidor correcto na súa documentación.

Se está a realizar a instalación nun servidor de Windows con MySQL, o nome "localhost" pode non valer como servidor. Se non funcionase, inténteo con "127.0.0.1" como enderezo IP local.',
	'config-db-wiki-settings' => 'Identificar o wiki',
	'config-db-name' => 'Nome da base de datos:',
	'config-db-name-help' => 'Escolla un nome que identifique o seu wiki.
Non debe conter espazos ou guións.

Se está usando un aloxamento web compartido, o seu provedor de hospedaxe daralle un nome específico para a base de datos ou deixaralle crear unha a través do panel de control.',
	'config-db-install-account' => 'Conta de usuario para a instalación',
	'config-db-username' => 'Nome de usuario da base de datos:',
	'config-db-password' => 'Contrasinal da base de datos:',
	'config-db-install-help' => 'Introduza o nome de usuario e contrasinal que se usará para conectar á base de datos durante o proceso de instalación.',
	'config-db-account-lock' => 'Use o mesmo nome de usuario e contrasinal despois do proceso de instalación',
	'config-db-wiki-account' => 'Conta de usuario para despois do proceso de instalación',
	'config-db-wiki-help' => 'Introduza o nome de usuario e mais o contrasinal que se usarán para conectar á base de datos durante o funcionamento habitual do wiki.
Se a conta non existe e a conta de instalación ten privilexios suficientes, esa conta de usuario será creada cos privilexios mínimos necesarios para o funcionamento do wiki.',
	'config-db-prefix' => 'Prefixo das táboas da base de datos:',
	'config-db-prefix-help' => 'Se necesita compartir unha base de datos entre varios wikis ou entre MediaWiki e outra aplicación web, pode optar por engadir un prefixo a todos os nomes da táboa para evitar conflitos.
Non utilice espazos ou guións.

O normal é que este campo quede baleiro.',
	'config-db-charset' => 'Conxunto de caracteres da base de datos',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binario',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 retrocompatible UTF-8',
	'config-charset-help' => "'''Atención:''' Se emprega '''backwards-compatible UTF-8''' no MySQL 4.1+ e posteriormente realiza unha copia de seguridade da base de datos con <code>mysqldump</code>, pode destruír todos os caracteres que non sexan ASCII, corrompendo de xeito irreversible as súas copias!

No '''modo binario''', MediaWiki almacena texto UTF-8 na base de datos en campos binarios.
Isto é máis eficaz ca o modo UTF-8 de MySQL e permítelle usar o rango completo de caracteres Unicode.
No '''modo UTF-8''', MySQL saberá o xogo de caracteres dos seus datos e pode presentar e converter os datos de maneira axeitada,
pero non lle deixará gardar caracteres por riba do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingüe básico].",
	'config-mysql-old' => 'Necesítase MySQL $1 ou posterior; ten a versión $2.',
	'config-db-port' => 'Porto da base de datos:',
	'config-db-schema' => 'Esquema para MediaWiki',
	'config-db-ts2-schema' => 'Esquema para tsearch2',
	'config-db-schema-help' => 'O normal é que os esquemas anteriores sexan correctos.
Cámbieos soamente se sabe que é necesario.',
	'config-sqlite-dir' => 'Directorio de datos SQLite:',
	'config-sqlite-dir-help' => "SQLite recolle todos os datos nun ficheiro único.

O servidor web debe ter permisos sobre o directorio para que poida escribir nel durante a instalación.

Ademais, o servidor '''non''' debe ser accesible a través da web, motivo polo que non está no mesmo lugar ca os ficheiros PHP.

Asemade, o instalador escribirá un ficheiro <code>.htaccess</code>, pero se erra alguén pode obter acceso á súa base de datos.
Isto inclúe datos de usuario (enderezos de correo electrónico, contrasinais codificados), así como revisións borradas e outros datos restrinxidos no wiki.

Considere poñer a base de datos nun só lugar, por exemplo en <code>/var/lib/mediawiki/oseuwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki soporta os seguintes sistemas de bases de datos:

$1

Se non ve listado a continuación o sistema de base de datos que intenta usar, siga as instrucións ligadas enriba para activar o soporte.',
	'config-support-mysql' => '* $1 é o obxectivo principal para MediaWiki e está mellor soportado ([http://www.php.net/manual/en/mysql.installation.php como compilar o PHP con soporte MySQL])',
	'config-support-postgres' => '* $1 é un sistema de base de datos popular e de código aberto como alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php como compilar o PHP con soporte PostgreSQL])',
	'config-support-sqlite' => '* $1 é un sistema de base de datos lixeiro moi ben soportado. ([http://www.php.net/manual/en/pdo.installation.php Como compilar o PHP con soporte SQLite], emprega PDO)',
	'config-header-mysql' => 'Configuración do MySQL',
	'config-header-postgres' => 'Configuración do PostgreSQL',
	'config-header-sqlite' => 'Configuración do SQLite',
	'config-header-oracle' => 'Configuración do Oracle',
	'config-invalid-db-type' => 'Tipo de base de datos incorrecto',
	'config-missing-db-name' => 'Debe escribir un valor "Nome da base de datos"',
	'config-invalid-db-name' => 'O nome da base de datos, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9) e guións baixos (_).',
	'config-invalid-db-prefix' => 'O prefixo da base de datos, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9) e guións baixos (_).',
	'config-connection-error' => '$1.

Comprobe o servidor, nome de usuario e contrasinal que hai a continuación e inténteo de novo.',
	'config-invalid-schema' => 'O esquema de MediaWiki, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9) e guións baixos (_).',
	'config-invalid-ts2schema' => 'O esquema de TSearch2, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9) e guións baixos (_).',
	'config-postgres-old' => 'Necesítase PostgreSQL $1 ou posterior; ten a versión $2.',
	'config-sqlite-name-help' => 'Escolla un nome que identifique o seu wiki.
Non utilice espazos ou guións.
Este nome será utilizado para o ficheiro de datos SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Non se puido crear o directorio de datos <code><nowiki>$1</nowiki></code>, porque o servidor web non pode escribir no directorio pai <code><nowiki>$2</nowiki></code>.

O instalador determinou o usuario que executa o seu servidor web.
Para continuar, faga que se poida escribir no directorio <code><nowiki>$3</nowiki></code>.
Nun sistema Unix/Linux cómpre realizar:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Non se puido crear o directorio de datos <code><nowiki>$1</nowiki></code>, porque o servidor web non pode escribir no directorio pai <code><nowiki>$2</nowiki></code>.

O instalador non puido determinar o usuario que executa o seu servidor web.
Para continuar, faga que se poida escribir globalmente no directorio <code><nowiki>$3</nowiki></code>.
Nun sistema Unix/Linux cómpre realizar:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Erro ao crear o directorio de datos "$1".
Comprobe a localización e inténteo de novo.',
	'config-sqlite-dir-unwritable' => 'Non se puido escribir o directorio "$1".
Cambie os permisos para que o servidor poida escribir nel e inténteo de novo.',
	'config-sqlite-connection-error' => '$1.

Comprobe o directorio de datos e o nome da base de datos que hai a continuación e inténteo de novo.',
	'config-sqlite-readonly' => 'Non se pode escribir no ficheiro <code>$1</code>.',
	'config-sqlite-cant-create-db' => 'Non se puido crear o ficheiro da base de datos <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'Falta o soporte FTS3 para o PHP; diminuíndo as táboas',
	'config-sqlite-fts3-add' => 'Engadindo as capacidades de procura FTS3',
	'config-can-upgrade' => "Existen táboas MediaWiki nesta base de datos.
Para actualizalas a MediaWiki \$1, prema sobre \"'''Continuar'''\".",
	'config-upgrade-done' => "Actualización completada.

Agora pode [$1 comezar a utilizar o seu wiki].

Se quere rexenerar o seu ficheiro <code>LocalSettings.php</code>, prema no botón que aparece a continuación.
Isto '''non é recomendable''' a menos que estea a ter problemas co seu wiki.",
	'config-regenerate' => 'Rexenerar LocalSettings.php →',
	'config-show-table-status' => 'A pescuda SHOW TABLE STATUS fallou!',
	'config-unknown-collation' => "'''Atención:''' A base de datos está a empregar unha clasificación alfabética irrecoñecible.",
	'config-db-web-account' => 'Conta na base de datos para o acceso á internet',
	'config-db-web-help' => 'Seleccione o nome de usuario e contrasinal que o servidor web empregará para se conectar ao servidor da base de datos durante o funcionamento normal do wiki.',
	'config-db-web-account-same' => 'Empregar a mesma conta que para a instalación',
	'config-db-web-create' => 'Crear a conta se aínda non existe',
	'config-db-web-no-create-privs' => 'A conta que especificou para a instalación non ten os privilexios suficientes para crear unha conta.
A conta que se especifique aquí xa debe existir.',
	'config-mysql-engine' => 'Motor de almacenamento:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' é case sempre a mellor opción, dado que soporta ben os accesos simultáneos.

'''MyISAM''' é máis rápido en instalacións de usuario único e de só lectura.
As bases de datos MyISAM tenden a se corromper máis a miúdo ca as bases de datos InnoDB.",
	'config-mysql-egine-mismatch' => "'''Atención:''' Solicitou o motor de almacenamento $1, mais o existente na base de datos é o motor $2.
Esta escritura de actualización non o pode converter, de modo que permanecerá $2.",
	'config-mysql-charset' => 'Conxunto de caracteres da base de datos:',
	'config-mysql-binary' => 'Binario',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "No '''modo binario''', MediaWiki almacena texto UTF-8 na base de datos en campos binarios.
Isto é máis eficaz ca o modo UTF-8 de MySQL e permítelle usar o rango completo de caracteres Unicode.

No '''modo UTF-8''', MySQL saberá o xogo de caracteres dos seus datos e pode presentar e converter os datos de maneira axeitada,
pero non lle deixará gardar caracteres por riba do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingüe básico].",
	'config-mysql-charset-mismatch' => "'''Atención:''' Solicitou o esquema $1, mais o existente na base de datos é o esquema $2.
Esta escritura de actualización non o pode converter, de modo que permanecerá $2.",
	'config-site-name' => 'Nome do wiki:',
	'config-site-name-help' => 'Isto aparecerá na barra de títulos do navegador e noutros lugares.',
	'config-site-name-blank' => 'Escriba o nome do sitio.',
	'config-project-namespace' => 'Espazo de nomes do proxecto:',
	'config-ns-generic' => 'Proxecto',
	'config-ns-site-name' => 'O mesmo nome que o wiki: $1',
	'config-ns-other' => 'Outro (especificar)',
	'config-ns-other-default' => 'OMeuWiki',
	'config-project-namespace-help' => 'Seguindo o exemplo da Wikipedia, moitos wikis manteñen as súas páxinas de políticas separadas das súas páxinas de contido, nun "\'\'\'espazo de nomes do proxecto\'\'\'".
Todos os títulos presentes neste espazo de nomes comezan cun prefixo determinado, que pode especificar aquí.
Tradicionalmente, este prefixo deriva do nome do wiki, pero non pode conter caracteres de puntuación como "#" ou ":".',
	'config-ns-invalid' => 'O espazo de nomes especificado, "<nowiki>$1</nowiki>", é incorrecto.
Especifique un espazo de nomes do proxecto diferente.',
	'config-admin-box' => 'Conta de administrador',
	'config-admin-name' => 'O seu nome:',
	'config-admin-password' => 'Contrasinal:',
	'config-admin-password-confirm' => 'Repita o contrasinal:',
	'config-admin-help' => 'Escriba o nome de usuario que queira aquí, por exemplo, "Joe Bloggs".
Este é o nome que usará para acceder ao sistema do wiki.',
	'config-admin-name-blank' => 'Escriba un nome de usuario para o administrador.',
	'config-admin-name-invalid' => 'O nome de usuario especificado, "<nowiki>$1</nowiki>", é incorrecto.
Especifique un nome de usuario diferente.',
	'config-admin-password-blank' => 'Escriba un contrasinal para a conta de administrador.',
	'config-admin-password-same' => 'O contrasinal debe diferir do nome de usuario.',
	'config-admin-password-mismatch' => 'Os contrasinais non coinciden.',
	'config-admin-email' => 'Enderezo de correo electrónico:',
	'config-admin-email-help' => 'Escriba aquí un enderezo de correo electrónico para que poida recibir mensaxes doutros usuarios a través do wiki, restablecer o contrasinal e ser notificado das modificacións feitas nas páxinas presentes na súa lista de vixilancia.',
	'config-admin-error-user' => 'Erro interno ao crear un administrador co nome "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Erro interno ao establecer un contrasinal para o administrador "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => 'Subscríbase á [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce lista de correo de anuncios sobre lanzamentos].',
	'config-subscribe-help' => 'Esta é unha lista de correos de baixo volume usada para anuncios sobre lanzamentos de novas versións, incluíndo avisos de seguridade importantes.
Debería subscribirse a ela e actualizar a súa instalación MediaWiki cando saian as novas versións.',
	'config-almost-done' => 'Xa case rematou!
Neste paso pode saltar o resto da configuración e instalar o wiki agora mesmo.',
	'config-optional-continue' => 'Facédeme máis preguntas.',
	'config-optional-skip' => 'Xa estou canso. Instalade o wiki.',
	'config-profile' => 'Perfil dos dereitos de usuario:',
	'config-profile-wiki' => 'Wiki tradicional',
	'config-profile-no-anon' => 'Necesítase a creación dunha conta',
	'config-profile-fishbowl' => 'Só os editores autorizados',
	'config-profile-private' => 'Wiki privado',
	'config-profile-help' => "Os wikis funcionan mellor canta máis xente os edite.
En MediaWiki, é doado revisar os cambios recentes e reverter calquera dano feito por usuarios novatos ou con malas intencións.
Porén, moita xente atopa MediaWiki útil nunha ampla variedade de papeis, e ás veces non é fácil convencer a todos dos beneficios que leva consigo o estilo wiki.
Vostede decide.

O tipo '''{{int:config-profile-wiki}}''' permite a edición por parte de calquera, mesmo sen rexistro.
A opción '''{{int:config-profile-no-anon}}''' proporciona un control maior, pero pode desalentar os colaboradores casuais.

O escenario '''{{int:config-profile-fishbowl}}''' restrinxe a edición aos usuarios aprobados, pero o público pode ollar as páxinas, incluíndo os historiais.
O tipo '''{{int:config-profile-private}}''' só deixa que os usuarios aprobados vexan e editen as páxinas.

Hai dispoñibles configuracións de dereitos de usuario máis complexas despois da instalación; bótelle un ollo a [http://www.mediawiki.org/wiki/Manual:User_rights esta entrada no manual].",
	'config-license' => 'Dereitos de autor e licenza:',
	'config-license-none' => 'Sen licenza ao pé',
	'config-license-cc-by-sa' => 'Creative Commons recoñecemento compartir igual (compatible coa Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons recoñecemento non comercial compartir igual',
	'config-license-gfdl-old' => 'Licenza de documentación libre de GNU 1.2',
	'config-license-gfdl-current' => 'Licenza de documentación libre de GNU 1.3 ou posterior',
	'config-license-pd' => 'Dominio público',
	'config-license-cc-choose' => 'Seleccione unha licenza Creative Commons personalizada',
	'config-license-help' => "Moitos wikis públicos liberan todas as súas contribucións baixo unha [http://freedomdefined.org/Definition/Gl licenza libre].
Isto axuda a crear un sentido de propiedade na comunidade e anima a seguir contribuíndo durante moito tempo.
Xeralmente, non é necesario nos wikis privados ou de empresas.

Se quere poder empregar textos da Wikipedia, así como que a Wikipedia poida aceptar textos copiados do seu wiki, escolla a licenza '''Creative Commons recoñecemento compartir igual'''.

A licenza de documentación libre de GNU era a licenza anterior da Wikipedia.
Malia aínda ser unha licenza válida, esta ten algunhas características que poden facer o reuso e a interpretación difíciles.",
	'config-email-settings' => 'Configuración do correo electrónico',
	'config-enable-email' => 'Activar os correos electrónicos de saída',
	'config-enable-email-help' => 'Se quere que o correo electrónico funcione, cómpre configurar os [http://www.php.net/manual/en/mail.configuration.php parámetros PHP] correctamente.
Se non quere ningunha característica no correo, pode desactivalas aquí.',
	'config-email-user' => 'Activar o intercambio de correos electrónicos entre usuarios',
	'config-email-user-help' => 'Permitir que todos os usuarios intercambien correos electrónicos, se o teñen activado nas súas preferencias.',
	'config-email-usertalk' => 'Activar a notificación da páxina de conversa de usuario',
	'config-email-usertalk-help' => 'Permitir que os usuarios reciban notificacións cando a súa páxina de conversa de usuario sufra modificacións, se o teñen activado nas súas preferencias.',
	'config-email-watchlist' => 'Activar a notificación da lista de vixilancia',
	'config-email-watchlist-help' => 'Permitir que os usuarios reciban notificacións sobre modificacións nas páxinas que vixían, se o teñen activado nas súas preferencias.',
	'config-email-auth' => 'Activar a autenticación do correo electrónico',
	'config-email-auth-help' => "Se esta opción está activada, os usuarios teñen que confirmar o seu correo electrónico mediante unha ligazón enviada ao enderezo cando o definan ou o cambien.
Só os enderezos autenticados poden recibir correos doutros usuarios ou de notificación.
É '''recomendable''' establecer esta opción nos wikis públicos para evitar abusos potenciais das características do correo.",
	'config-email-sender' => 'Enderezo de correo electrónico de retorno:',
	'config-email-sender-help' => 'Introduza o enderezo de correo electrónico a usar como enderezo de retorno dos correos de saída.
Aquí é onde irán parar os correos rexeitados.
Moitos servidores de correo electrónico esixen que polo menos a parte do nome de dominio sexa válido.',
	'config-upload-settings' => 'Imaxes e carga de ficheiros',
	'config-upload-enable' => 'Activar a carga de ficheiros',
	'config-upload-help' => 'A subida de ficheiros expón potencialmente o servidor a riscos de seguridade.
Para obter máis información, lea a [http://www.mediawiki.org/wiki/Manual:Security sección de seguridade] no manual.

Para activar a carga de ficheiros, cambie o modo no subdirectorio <code>images</code> que está baixo o directorio raíz de MediaWiki, de xeito que o servidor web poida escribir nel.
A continuación, active esta opción.',
	'config-upload-deleted' => 'Directorio para os ficheiros borrados:',
	'config-upload-deleted-help' => 'Escolla un directorio no que arquivar os ficheiros borrados.
O ideal é que non sexa accesible desde a web.',
	'config-logo' => 'URL do logo:',
	'config-logo-help' => 'A aparencia de MediaWiki por defecto inclúe espazo para un logo de 135x160 píxeles no recuncho superior esquerdo.
Cargue unha imaxe do tamaño axeitado e introduza o URL aquí.

Se non quere un logo, deixe esta caixa en branco.',
	'config-instantcommons' => 'Activar Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons InstantCommons] é unha característica que permite aos wikis usar imaxes, sons e outros ficheiros multimedia atopados no sitio da [http://commons.wikimedia.org/wiki/Portada_galega Wikimedia Commons].
Para facer isto, MediaWiki necesita acceso á internet. $1

Para obter máis información sobre esta característica, incluíndo as instrucións sobre como configuralo para outros wikis que non sexan a Wikimedia Commons, consulte [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos o manual].',
	'config-instantcommons-good' => 'O instalador foi capaz de detectar a conectividade á internet durante as comprobacións do entorno.
Pode activar esta característica se quere.',
	'config-instantcommons-bad' => "''Por desgraza, o instalador foi incapaz de detectar ningunha conexión á internet durante a comprobación do entorno, de modo que vostede non poderá usar esta característica.
Se o seu servidor se atopa por detrás dun proxy, terá que realizar algunha [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy configuración adicional].''",
	'config-cc-error' => 'A escolla da licenza Creative Commons non deu resultados.
Escriba o nome da licenza manualmente.',
	'config-cc-again' => 'Escolla outra vez...',
	'config-cc-not-chosen' => 'Escolla a licenza Creative Commons que desexe e prema en "continuar".',
	'config-advanced-settings' => 'Configuración avanzada',
	'config-cache-options' => 'Configuración da caché de obxectos:',
	'config-cache-help' => 'A caché de obxectos emprégase para mellorar a velocidade de MediaWiki mediante a memorización de datos usados con frecuencia.
É amplamente recomendable a súa activación nos sitios de tamaño medio e grande; os sitios pequenos obterán tamén beneficios.',
	'config-cache-none' => 'Sen caché (non se elimina ningunha funcionalidade, pero pode afectar á velocidade en wikis grandes)',
	'config-cache-accel' => 'Caché de obxectos do PHP (APC, eAccelerator, XCache ou WinCache)',
	'config-cache-memcached' => 'Empregar o Memcached (necesita unha instalación e configuración adicional)',
	'config-memcached-servers' => 'Servidores da memoria caché:',
	'config-memcached-help' => 'Lista de enderezos IP para Memcached.
Deben separarse por comas e especificar o porto a usar (por exemplo: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Extensións',
	'config-extensions-help' => 'As extensións anteriores detectáronse no seu directorio <code>./extensions</code>.

Quizais necesite algunha configuración adicional, pero pode activalas agora',
	'config-install-alreadydone' => "'''Atención:''' Semella que xa instalou MediaWiki e que o está a instalar de novo.
Vaia ata a seguinte páxina.",
	'config-install-step-done' => 'feito',
	'config-install-step-failed' => 'erro',
	'config-install-extensions' => 'Incluíndo as extensións',
	'config-install-database' => 'Configurando a base de datos',
	'config-install-pg-schema-failed' => 'Fallou a creación de táboas.
Asegúrese de que o usuario "$1" pode escribir no esquema "$2".',
	'config-install-user' => 'Creando o usuario da base de datos',
	'config-install-user-failed' => 'Fallou a concesión de permisos ao usuario "$1": $2',
	'config-install-tables' => 'Creando as táboas',
	'config-install-tables-exist' => "'''Atención:''' Semella que as táboas de MediaWiki xa existen.
Saltando a creación.",
	'config-install-tables-failed' => "'''Erro:''' Fallou a creación da táboa. Descrición do erro: $1",
	'config-install-interwiki' => 'Enchendo a táboa de interwiki por defecto',
	'config-install-interwiki-sql' => 'Non se puido atopar o ficheiro <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Atención:''' Semella que a táboa de interwiki xa contén entradas.
Saltando a lista por defecto.",
	'config-install-secretkey' => 'Xerando a clave secreta',
	'config-insecure-secretkey' => "'''Atención:''' Non se puido crear a clave secreta <code>\$wgSecretKey</code>.
Considere cambiala manualmente.",
	'config-install-sysop' => 'Creando a conta de usuario de administrador',
	'config-install-done' => "'''Parabéns!'''
Instalou correctamente MediaWiki.

O instalador xerou un ficheiro <code>LocalSettings.php</code>.
Este contén toda a súa configuración.

Terá que [$1 descargalo] e poñelo na base da instalación do seu wiki (no mesmo directorio ca index.php).
'''Nota:''' Se non fai iso agora, este ficheiro de configuración xerado non estará dispoñible máis adiante se sae da instalación sen descargalo.

Cando faga todo isto, xa poderá  '''[$2 entrar no seu wiki]'''.",
);

/** Swiss German (Alemannisch)
 * @author Als-Holder
 */
$messages['gsw'] = array(
	'config-desc' => 'S MediaWiki-Inschtallationsprogramm',
	'config-title' => 'MediaWiki $1 inschtalliere',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => "'''Warnig:''' D Datei <code>LocalSettings.php</code> isch nit gfunde wore.
Dyy Software cha aktualisiert wäre.
D Datei <code>LocalSettings.php</code> mueß an e sichere Spycherort verschobe un derno s Inschtallationsprogramm nomol uusgfiert wäre.",
	'config-localsettings-noupgrade' => "'''Fähler''': D Datei <code>LocalSettings.php</code> isch it gfunde wore.
Dyy Software cha zurzyt nit aktualisiert wäre.
S Inschtallationsprogramm sich us Sicherheitsgrind deaktiviert wore.",
	'config-session-error' => 'Fähler bim Starte vu dr Sitzig: $1',
	'config-session-expired' => 'D Sitzigsdate sin schyns abgloffe.
Sitzige sin fir e Zytruum vu $1 konfiguriert.
Dää cha dur Aalupfe vum Parameter <code>session.gc_maxlifetime</code> in dr Datei <code>php.ini</code> greßer gmacht wäre.
Dr Inschtallationsvorgang nomol starte.',
	'config-no-session' => 'Dyyni Sitzigsdate sin verlore gange!
D Datei <code>php.ini</code> mueß prieft wäre un s mueß derby sichergstellt wäre, ass dr Parameter <code>session.save_path</code> uf s richtig Verzeichnis verwyyst.',
	'config-session-path-bad' => 'Dyy <code>session.save_path</code> (<code>$1</code>) isch schyns nit giltig oder nit bschryybbar.',
	'config-show-help' => 'Hilf',
	'config-hide-help' => 'Hilf uusblände',
	'config-your-language' => 'Dyy Sproch:',
	'config-your-language-help' => 'Bitte d Sproch uuswehle, wu bim Inschtallationsvorgang soll brucht wäre.',
	'config-wiki-language' => 'Wikisproch:',
	'config-wiki-language-help' => 'Bitte d Sproch uuswehle, wu s Wiki in dr Hauptsach din gschribe wird.',
	'config-back' => '← Zruck',
	'config-continue' => 'Wyter →',
	'config-page-language' => 'Sproch',
	'config-page-welcome' => 'Willchuu bi MediaWiki!',
	'config-page-dbconnect' => 'Mit dr Datebank verbinde',
	'config-page-upgrade' => 'E Inschtallition, wu s scho het, aktualisiere',
	'config-page-dbsettings' => 'Datebankyystellige',
	'config-page-name' => 'Name',
	'config-page-options' => 'Optione',
	'config-page-install' => 'Inschtalliere',
	'config-page-complete' => 'Fertig!',
	'config-page-restart' => 'Inschtallation nomol aafange',
	'config-page-readme' => 'Liis mi',
	'config-page-releasenotes' => 'Hiiwys fir d Vereffentlichung',
	'config-page-copying' => 'Am Kopiere',
	'config-page-upgradedoc' => 'Am Aktualisiere',
	'config-help-restart' => 'Witt alli Date, wu Du yygee hesch, lesche un d Inschtallation nomol aafange?',
	'config-restart' => 'Jo, nomol aafange',
	'config-welcome' => '=== Priefig vu dr Inschtallationsumgäbig ===
Basispriefige wäre durgfiert zum Feschtstelle, eb d Inschtallationsumgäbig fir d Inschtallation vu MediaWiki geignet isch.
Du sottsch d Ergebnis vu däre Priefig aagee, wänn Du bi dr Inschtallation Hilf bruchsch.',
	'config-copyright' => "=== Copyright un Nutzigsbedingige ===

$1

Des Programm isch e freji Software, d. h. s cha, no dr Bedingige vu dr GNU General Public-Lizänz, wu vu dr Free Software Foundation vereffentligt woren isch, wyterverteilt un/oder modifiziert wäre. Doderbyy cha d Version 2, oder no eigenem Ermässe, jedi nejeri Version vu dr Lizänz brucht wäre.

Des Programm wird in dr Hoffnig verteilt, ass es nitzli isch, aber '''ohni jedi Garanti''' un sogar ohni di impliziert Garanti vun ere '''Märtgängigkeit''' oder '''Eignig fir e bstimmte Zwäck'''. Doderzue git meh Hiiwys in dr GNU General Public-Lizänz.

E <doclink href=Copying>Kopi vu dr GNU General Public-Lizänz</doclink> sott zämme mit däm Programm verteilt wore syy. Wänn des nit eso isch, cha ne Kopi bi dr Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftli aagforderet oder [http://www.gnu.org/copyleft/gpl.html online gläse] wäre.",
	'config-authors' => 'MediaWiki – Copyright © 2001-2010 Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Kirche, Yuri Astrachan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe un anderi.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Websyte vu MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nutzeraaleitig zue MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:Contents Adminischtratoreaaleitig zue MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:FAQ Vilmol gstellti Froge zue MediaWiki]',
	'config-env-good' => '<span class="success-message">D Inschtallationsumgäbig isch prieft wore.
Du chasch MediaWiki inschtalliere.</span>',
	'config-env-bad' => 'D Inschtallationsumgäbigisch prieft wore.
Du chasch MediaWiki nit inschtalliere.',
	'config-env-php' => 'PHP $1 isch inschtalliert.',
	'config-env-latest-ok' => 'Du bisch am Inschtalliere vu dr nejschte Programmversion vu MediaWiki.',
	'config-env-latest-new' => "'''Hiiwys:''' Du bisch am Inschtalliere vun ere Entwickligsversion vu MediaWiki.",
	'config-env-latest-can-not-check' => "'''Hiiwyys:''' S Inschtallationsprogramm het kei Informatione vu [$1] chenne abruefe zue dr nejschte Programmversion vu MediaWiki.",
	'config-env-latest-old' => "'''Warnig:''' Du bisch am Ischtlaiiere vun ere veraltete Programmversion vu MediaWiki.",
	'config-env-latest-help' => 'Du bisch am Ischtlaiiere vu dr Version $1, di nejscht Version isch aber $2.
S wird empfohle die nejscht Version z verwände, wu vu [http://www.mediawiki.org/wiki/Download/de mediawiki.org] cha abeglade wäre.',
	'config-unicode-using-php' => 'Fir d Unicode-Normalisierig wird di langsam PHP-Implementierig yygsetzt.',
	'config-unicode-using-utf8' => 'Fir d Unicode-Normalisierig wird em Brion Vibber syy utf8_normalize.so yygsetzt.',
	'config-unicode-using-intl' => 'For d Unicode-Normalisierig wird d [http://pecl.php.net/intl PECL-Erwyterig intl] yygsetzt.',
	'config-unicode-pure-php-warning' => "'''Warnig:''' D [http://pecl.php.net/intl PECL-Erwyterig intl] isch fir d Unicode-Normalisierig nit verfiegbar.
Wänn Du ne Websyte mit ere große Bsuechrzahl bedrybsch, sottsch e weng ebis läse iber [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-Normalisierig (en)].",
	'config-unicode-update-warning' => "'''Warnig:''' Di inschtalliert Version vum Unicode-Normalisierigswrapper verwändet e elteri Version vu dr Bibliothek vum [http://site.icu-project.org/ ICU-Projäkt].
Du sottsch si [http://www.mediawiki.org/wiki/Unicode_normalization_considerations aktualisiere], wänn Dor d Verwändig vu Unicode wichtig isch.",
	'config-no-db' => 'S isch kei adäquate Datebanktryyber gfunde wore!',
);

/** Upper Sorbian (Hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'config-desc' => 'Instalaciski program za MediaWiki',
	'config-title' => 'Instalacija MediaWiki $1',
	'config-information' => 'Informacije',
	'config-localsettings-upgrade' => "'''Warnowanje''': Dataja <code>LocalSettings.php</code> je so wotkryła.
Twoja softwara da so aktualizować.
Prošu přesuń <code>LocalSettings.php</code> do wěsteho městna a startuj instalciski program znowa.",
	'config-localsettings-noupgrade' => "'''Zmylk''': Dataja <code>LocalSettings.php</code> je so namakała.
Twoja softwara njeda so tuchwilu aktualizować.
Instalaciski program je so z přičinow wěstosće znjemónił.",
	'config-session-error' => 'Zmylk při startowanju posedźenja: $1',
	'config-session-expired' => 'Zda so, zo twoje posedźenske daty su spadnjene.
Posedźenja su za čas žiwjenja $1 skonfigurowane.
Móžeš jón přez nastajenje <code>session.gc_maxlifetime</code> w php.ini powyšić.
Startuj instalaciski proces znowa.',
	'config-no-session' => 'Twoje posedźenske daty su so zhubili!
Skontroluj swój php.ini a zawěsć, zo <code>session.save_path</code> je na prawy zapis nastajeny.',
	'config-session-path-bad' => 'Zda so, zo twój parameter <code>session.save_path</code> (<code>$1</code>) je njepłaćiwy abo njepopisujomny.',
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
	'config-help-restart' => 'Chceš wšě składowane daty hašeć, kotrež sy zapodał a instalaciski proces znowa startować?',
	'config-restart' => 'Haj, znowa startować',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Startowa strona MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nawod za wužiwarjow]
* [http://www.mediawiki.org/wiki/Manual:Contents Nawod za administratorow]
* [http://www.mediawiki.org/wiki/Manual:FAQ Huste prašenja]',
	'config-env-good' => '<span class="success-message">Wokolina je so skontrolowała.
Móžeš MediaWiki instalować.</span>',
	'config-env-bad' => 'Wokolina je so skontrolowała.
Njemóžeš MediaWiki instalować.',
	'config-env-php' => 'PHP $1 je instalowany.',
	'config-env-latest-ok' => 'Instaluješ najnowšu wersiju MediaWiki.',
	'config-env-latest-new' => "'''Kedźbu:''' Instaluješ wuwiwansku wersiju MediaWiki.",
	'config-env-latest-can-not-check' => "'''Kedźbu:''' Instalaciski program njemóžeše žane informacije wo najnowšej wersiji MediaWiki wot [$1] wotwołać.",
	'config-env-latest-old' => "'''Warnowanje:''' Instaluješ zestarjenu wersiju MediaWiki.",
	'config-env-latest-help' => 'Instaluješ wersiju $1, ale najnowša wersija je $2.
Doporuča so, najnowšu wersiju wužiwać, kotruž móžeš wot [http://www.mediawiki.org/wiki/Download mediawiki.org] sćahnyć.',
	'config-unicode-using-php' => 'Za normalizaciju Unicode so pomała PHP-implementacija wužiwa.',
	'config-unicode-using-utf8' => 'Za normalizaciju Unicode so utf8_normalize.so Briona Vibbera wužiwa.',
	'config-unicode-using-intl' => 'Za normalizaciju Unicode so [http://pecl.php.net/intl PECL-rozšěrjenje intl] wužiwa.',
	'config-no-db' => 'Njeda so přihódny ćěrjak datoweje banki namakać!',
	'config-have-db' => 'Namakane ćěrjaki datoweje banki: $1.',
	'config-xml-good' => 'Konwersija XML/Latin1-UTF-8 steji k dispoziciji.',
	'config-memory-none' => 'PHP je bjez <code>memory_limit</code> skonfigurowany',
	'config-memory-ok' => 'PHP-parameter <code>memory_limit</code> ma hódnotu $1.
W porjadku.',
	'config-memory-raised' => 'PHP-parameter <code>memory_limit</code> je $1, je so na hódnotu $2 zwyšił.',
	'config-memory-bad' => "'''Warnowanje:''' PHP-parameter <code>memory_limit</code> ma hódnotu $1,
To je najskerje přeniske.
Instalacija móhła so njeporadźić!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] je instalowany',
	'config-apc' => '[http://www.php.net/apc APC] je instalowany',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] je instalowany',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] je instalowany',
	'config-diff3-good' => 'GNU diff3 namakany: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 njenamakany.',
	'config-dir' => 'Instalaciski zapis: <code>$1</code>.',
	'config-uri' => 'Šćežka URI skripta: <code>$1</code>.',
	'config-no-uri' => "'''Zmylk:''' Aktualny URI njeda so postajić.
Instalacija bu přetorhnjena.",
	'config-file-extension' => 'MediaWiki so z <code>$1</code> datajowymi rozšěrjenjemi instaluje.',
	'config-shell-locale' => 'Lokala "$1" za shell namakana.',
	'config-uploads-safe' => 'Standardny zapis za nahraćow je přećiwo samowólnemu přewjedźenju skriptow škitany.',
	'config-db-type' => 'Typ datoweje banki:',
	'config-db-host' => 'Serwer datoweje banki:',
	'config-db-wiki-settings' => 'Tutón wiki identifikować',
	'config-db-name' => 'Mjeno datoweje banki:',
	'config-db-install-account' => 'Wužiwarske konto za instalaciju',
	'config-db-username' => 'Wužiwarske mjeno datoweje banki:',
	'config-db-password' => 'Hesło datoweje banki:',
	'config-db-install-help' => 'Zapodaj wužiwarske mjeno a hesło, kotrejž měłoj so za zwisk z datowej banku za instalaciski proces wužiwać.',
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
	'config-db-schema-help' => 'Hornje šemy su zwjetša korektne.
Změń je jenož, jeli wěš, štož činiš.',
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
	'config-invalid-ts2schema' => 'Njepłaćiwe šema za TSearch2 "$1".
Wužij jenož pismiki, ličby a podsmužki.',
	'config-postgres-old' => 'PostgreSQL $1 abo nowši trěbny, maš $2.',
	'config-sqlite-name-help' => 'Wubjer mjeno, kotrež twój wiki identifikuje.
Njewužij mjezery abo wjazawki.
To budźe so za mjeno dataje SQLite-datow wužiwać.',
	'config-sqlite-mkdir-error' => 'Zmylk při wutworjenju datoweho zapisa "$1".
Skontroluj městno a spytaj hišće raz.',
	'config-sqlite-dir-unwritable' => 'Njeje móžno do zapisa "$1" pisać.
Změń jeho prawa, tak zo webserwer móže do njeho pisać a spytaj hišće raz.',
	'config-sqlite-connection-error' => '$1.

Skontroluj datowy zapis a mjeno datoweje banki kaj spytaj hišće raz.',
	'config-sqlite-readonly' => 'Do dataje <code>$1</code> njeda so pisać.',
	'config-sqlite-cant-create-db' => 'Dataja <code>$1</code> datoweje banki njeda so wutworić.',
	'config-sqlite-fts3-downgrade' => 'PHP wo podpěrje FTS3 k dispoziciji njesteji, table so znižuja',
	'config-sqlite-fts3-add' => 'Pytanske funkcije FTS3 přidać',
	'config-can-upgrade' => "Su tabele MediaWiki w tutej datowej bance.
Zo by je na MediaWiki $1 aktualizował, klikń na '''Dale'''.",
	'config-regenerate' => 'LocalSettings.php znowa wutworić →',
	'config-show-table-status' => 'Naprašowanje SHOW TABLE STATUS je so njeporadźiło!',
	'config-unknown-collation' => "'''Warnowanje:''' Datowa banka njeznatu kolaciju wužiwa.",
	'config-db-web-account' => 'Konto datoweje banki za webpřistup',
	'config-db-web-help' => 'wubjer wužiwarske mjeno a hesło, kotrejž webserwer budźe wužiwać, zo by z serwerom datoweje banki za wšědnu operaciju zwjazać',
	'config-db-web-account-same' => 'Samsne konto kaž za instalaciju wužiwać',
	'config-db-web-create' => 'Załož konto, jeli hišće njeeksistuje.',
	'config-mysql-engine' => 'Składowanska mašina:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Znamješkowa sadźba datoweje banki:',
	'config-mysql-binary' => 'Binarny',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Mjeno wikija:',
	'config-site-name-help' => 'To zjewi so w titulowej lejstwje wobhladaka kaž tež na wšelakich druhich městnach.',
	'config-site-name-blank' => 'Zapodaj sydłowe mjeno.',
	'config-project-namespace' => 'Mjenowy rum projekta:',
	'config-ns-generic' => 'Projekt',
	'config-ns-site-name' => 'Samsne kaž wikimjeno: $1',
	'config-ns-other' => 'Druhe (podać)',
	'config-ns-other-default' => 'MyWiki',
	'config-ns-invalid' => 'Podaty mjenowy rum "<nowiki>$1</nowiki>" je njepłaćiwy.
Podaj druhi projektowy mjenowy rum.',
	'config-admin-box' => 'Administratorowe konto',
	'config-admin-name' => 'Twoje mjeno:',
	'config-admin-password' => 'Hesło:',
	'config-admin-password-confirm' => 'Hesło wospjetować:',
	'config-admin-help' => 'Zapodaj swoje preferowane wužiwarske mjeno, na přikład "Jurij Serb".
To je mjeno, kotrež budźeš wužiwać, zo by so do wikija přizjewił.',
	'config-admin-name-blank' => 'Zapodaj administratorowe wužiwarske mjeno.',
	'config-admin-name-invalid' => 'Podate wužiwarske mjeno "<nowiki>$1</nowiki>" je njepłaćiwe.
Podaj druhe wužiwarske mjeno.',
	'config-admin-password-blank' => 'Zapodaj hesło za administratorowe konto.',
	'config-admin-password-same' => 'Hesło dyrbi so wot wužiwarskeho mjena rozeznać.',
	'config-admin-password-mismatch' => 'Wobě hesle, kotrejž sy zapodał, njejstej jenakej.',
	'config-admin-email' => 'E-mejlowa adresa:',
	'config-admin-error-user' => 'Interny zmylk při wutworjenju administratora z mjenom "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Interny zmylk při nastajenju hesła za administratora "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => '[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Rozesyłansku lisćinu wo připowědźenjach nowych wersijow ].abonować',
	'config-almost-done' => 'Sy skoro hotowy!
Móžeš nětko zbytnu konfiguraciju přeskočić a wiki hnydom instalować.',
	'config-optional-continue' => 'Dalše prašenja?',
	'config-optional-skip' => 'Instaluj nětko wiki.',
	'config-profile' => 'Profil wužiwarskich prawow:',
	'config-profile-wiki' => 'Tradicionelny wiki',
	'config-profile-no-anon' => 'Załoženje konto je trěbne',
	'config-profile-fishbowl' => 'Jenož awtorizowani wobdźěłarjo',
	'config-profile-private' => 'Priwatny wiki',
	'config-license' => 'Awtorske prawo a licenca:',
	'config-license-none' => 'Žane licencne podaća w nohowej lince',
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike (kompatibelny z Wikipediju)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 abo nowša',
	'config-license-pd' => 'Powšitkownosći přistupny',
	'config-license-cc-choose' => 'Swójsku licencu Creative Commons wubrać',
	'config-email-settings' => 'E-mejlowe nastajenja',
	'config-enable-email' => 'Wuchadźace e-mejlki zmóžnić',
	'config-email-user' => 'E-mejl mjez wužiwarjemi zmóžnić',
	'config-email-user-help' => 'Wšěm wužiwarjam dowolić, jednomu druhemu e-mejlki pósłać, jeli su tutu funkciju w swojich nastajenjach zmóžnili.',
	'config-email-usertalk' => 'Zdźělenja za wužiwarske diskusijne strony zmóžnić',
	'config-email-usertalk-help' => 'Wužiwarjam dowolić zdźělenki wo změnach na wužiwarskich diskusijnych stronach dóstać, jeli woni su to w swojich nastajenjach zmóžnili.',
	'config-email-watchlist' => 'Zdźělenja za wobkedźbowanki zmóžnić',
	'config-email-watchlist-help' => 'Wužiwarjam dowolić zdźělenki wo jich wobked´bowanych stronach dóstać, jeli woni su to w swojich nastajenjach zmóžnili.',
	'config-email-auth' => 'E-mejlowu awtentifikaciju zmóžnić',
	'config-email-sender' => 'E-mejlowa adresa za wotmołwy:',
	'config-upload-settings' => 'Wobrazy a nahraća datajow',
	'config-upload-enable' => 'Nahraće datajow zmóžnić',
	'config-upload-deleted' => 'Zapis za zhašane dataje:',
	'config-upload-deleted-help' => 'Wubjer zapis, w kotrymž zhašene dataje maja so archiwować.
Idealnje tón njeměł z weba přistupny być.',
	'config-logo' => 'URL loga:',
	'config-instantcommons' => 'Instant commons zmóžnić',
	'config-cc-again' => 'Zaso wubrać...',
	'config-cc-not-chosen' => 'Wubjer licencu Creative Commons a klikń na "dale".',
	'config-advanced-settings' => 'Rozšěrjena konfiguraćija',
	'config-cache-options' => 'Nastajenja za objektowe pufrowanje:',
	'config-cache-accel' => 'Objektowe pufrowanje PHP (APC, eAccelerator, XCache abo WinCache)',
	'config-memcached-servers' => 'Serwery memcached:',
	'config-extensions' => 'Rozšěrjenja',
	'config-install-alreadydone' => "'''Warnowanje:''' Zda so, zo sy hižo MediaWiki instalował a pospytuješ jón znowa instalować.
Prošu pokročuj z přichodnej stronu.",
	'config-install-step-done' => 'dokónčene',
	'config-install-step-failed' => 'njeporadźiło',
	'config-install-extensions' => 'Inkluziwnje rozšěrjenja',
	'config-install-database' => 'Datowa banka so připrawja',
	'config-install-pg-schema-failed' => 'Wutworjenje tabelow je so njeporadźiło.
Zawěsć, zo wužiwar "$1" móže do šemy "$2" pisać.',
	'config-install-user' => 'Tworjenje wužiwarja datoweje banki',
	'config-install-user-failed' => 'Prawo njeda so wužiwarjej "$1" dać: $2',
	'config-install-tables' => 'Tworjenje tabelow',
	'config-install-tables-exist' => "'''Warnowanje''': Zda so, zo tabele MediaWiki hižo eksistuja.
Wutworjenje so přeskakuje.",
	'config-install-tables-failed' => "'''Zmylk''': Wutworjenje tabele je so slědowaceho zmylka dla njeporadźiło: $1",
	'config-install-interwiki' => 'Standardna tabela interwikijow so pjelni',
	'config-install-interwiki-sql' => '<code>interwiki.sql</code> njeda so namakać.',
	'config-install-interwiki-exists' => "'''Warnowanje''': Zda so, zo tabela interwikjow hižo zapiski wobsahuje.
Standardna lisćina sp přeskakuje.",
	'config-install-secretkey' => 'Tworjenje tajneho kluča',
	'config-insecure-secretkey' => "'''Warnowanje:'''Wěsty kluč <code>\$wgSecretKey</code> njeda so wutworić.
Móžeš to manuelnje činić.",
	'config-install-sysop' => 'Tworjenje administratoroweho wužiwarskeho konta',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'config-desc' => 'Le installator de MediaWiki',
	'config-title' => 'Installation de MediaWiki $1',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => "'''Attention''': Un file <code>LocalSettings.php</code> ha essite detegite.
Es possibile actualisar le software.
Per favor displacia <code>LocalSettings.php</code> a un loco secur e postea re-executa le installator.",
	'config-localsettings-noupgrade' => "'''Error''': Un file <code>LocalSettings.php</code> ha essite detegite.
Non es possibile actualisar tu software a iste momento.
Le installator ha essite disactivate pro motivos de securitate.",
	'config-session-error' => 'Error al comenciamento del session: $1',
	'config-session-expired' => 'Le datos de tu session pare haber expirate.
Le sessiones es configurate pro un duration de $1.
Tu pote augmentar isto per definir <code>session.gc_maxlifetime</code> in php.ini.
Reinitia le processo de installation.',
	'config-no-session' => 'Le datos de tu session es perdite!
Verifica tu php.ini e assecura te que un directorio appropriate es definite in <code>session.save_path</code>.',
	'config-session-path-bad' => 'Le configuration <code>session.save_path</code> (<code>$1</code>) pare esser invalide o non permitte accesso de scriptura.',
	'config-show-help' => 'Adjuta',
	'config-hide-help' => 'Celar adjuta',
	'config-your-language' => 'Tu lingua:',
	'config-your-language-help' => 'Selige un lingua a usar durante le processo de installation.',
	'config-wiki-language' => 'Lingua del wiki:',
	'config-wiki-language-help' => 'Selige le lingua in que le wiki essera predominantemente scribite.',
	'config-back' => '← Retro',
	'config-continue' => 'Continuar →',
	'config-page-language' => 'Lingua',
	'config-page-welcome' => 'Benvenite a MediaWiki!',
	'config-page-dbconnect' => 'Connecter al base de datos',
	'config-page-upgrade' => 'Actualisar le installation existente',
	'config-page-dbsettings' => 'Configuration del base de datos',
	'config-page-name' => 'Nomine',
	'config-page-options' => 'Optiones',
	'config-page-install' => 'Installar',
	'config-page-complete' => 'Complete!',
	'config-page-restart' => 'Reinitiar installation',
	'config-page-readme' => 'Lege me',
	'config-page-releasenotes' => 'Notas del version',
	'config-page-copying' => 'Copiar',
	'config-page-upgradedoc' => 'Actualisar',
	'config-help-restart' => 'Vole tu rader tote le datos salveguardate que tu ha entrate e reinitiar le processo de installation?',
	'config-restart' => 'Si, reinitia lo',
	'config-welcome' => '=== Verificationes del ambiente ===
Verificationes de base es exequite pro determinar si iste ambiente es apte pro le installation de MediaWiki.
Tu deberea indicar le resultatos de iste verificationes si tu ha besonio de adjuta durante le installation.',
	'config-copyright' => "=== Copyright and Terms ===

$1

Iste programma es software libere; vos pote redistribuer lo e/o modificar lo sub le conditiones del Licentia Public General de GNU publicate per le Free Software Foundation; version 2 del Licentia, o (a vostre option) qualcunque version posterior.

Iste programma es distribuite in le sperantia que illo sia utile, ma '''sin garantia''', sin mesmo le implicite garantia de '''commercialisation''' o '''aptitude pro un proposito particular'''.
Vide le Licentia Public General de GNU pro plus detalios.

Vos deberea haber recipite <doclink href=Copying>un exemplar del Licentia Public General de GNU</doclink> con iste programma; si non, scribe al Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, o [http://www.gnu.org/copyleft/gpl.html lege lo in linea].",
	'config-sidebar' => '* [http://www.mediawiki.org Pagina principal de MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Guida pro usatores]
* [http://www.mediawiki.org/wiki/Manual:Contents Guida pro administratores]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => '<span class="success-message">Le ambiente ha essite verificate.
Tu pote installar MediaWiki.</span>',
	'config-env-bad' => 'Le ambiente ha essite verificate.
Tu non pote installar MediaWiki.',
	'config-env-php' => 'PHP $1 es installate.',
	'config-env-latest-ok' => 'Tu pote installar le version le plus recente de MediaWiki.',
	'config-env-latest-new' => "'''Nota:''' Tu installa un version in disveloppamento de MediaWiki.",
	'config-env-latest-can-not-check' => "'''Aviso:''' Le installator non poteva obtener information super le ultime version de MediaWiki de [$1].",
	'config-env-latest-old' => "'''Aviso:''' Tu installa un version obsolete de MediaWiki.",
	'config-env-latest-help' => 'Tu installa le version $1, ma le version le plus recente es $2.
Es consiliate usar le version le plus recente, que pote esser discargate de [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Le implementation lente de PHP es usate pro le normalisation Unicode.',
	'config-unicode-using-utf8' => 'utf8_normalize.so per Brion Vibber es usate pro le normalisation Unicode.',
	'config-unicode-using-intl' => 'Le [http://pecl.php.net/intl extension PECL intl] es usate pro le normalisation Unicode.',
	'config-unicode-pure-php-warning' => "'''Aviso''': Le [http://pecl.php.net/intl extension PECL intl] non es disponibile pro exequer le normalisation Unicode.
Si tu sito ha un alte volumine de traffico, tu deberea informar te un poco super le [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalisation Unicode].",
	'config-unicode-update-warning' => "'''Aviso''': Le version installate del bibliotheca inveloppante pro normalisation Unicode usa un version ancian del bibliotheca del [http://site.icu-project.org/ projecto ICU].
Tu deberea [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualisar lo] si le uso de Unicode importa a te.",
	'config-no-db' => 'Non poteva trovar un driver appropriate pro le base de datos!',
	'config-no-db-help' => 'Tu debe installar un driver de base de datos pro PHP.
Le sequente typos de base de datos es supportate: $1.

Si tu sito usa un servitor partite (shared hosting), demanda a tu providitor de installar un driver de base de datos appropriate.
Si tu compilava PHP tu mesme, reconfigura lo con un cliente de base de datos activate, per exemplo usante <code>./configure --with-mysql</code>.
Si tu installava PHP ex un pacchetto Debian o Ubuntu, tu debe installar equalmente le modulo php5-mysql.',
	'config-have-db' => '{{PLURAL:$2|Driver|Drivers}} de base de datos trovate: $1.',
	'config-register-globals' => "'''Attention: le option <code>[http://php.net/register_globals register_globals]</code> de PHP es activate.'''
'''Disactiva lo si tu pote.'''
MediaWiki functionara, ma tu servitor es exponite a potential vulnerabilitates de securitate.",
	'config-magic-quotes-runtime' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] es active!'''
Iste option corrumpe le entrata de datos imprevisibilemente.
Tu non pote installar o usar MediaWiki si iste option non es disactivate.",
	'config-magic-quotes-sybase' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] es active!'''
Iste option corrumpe le entrata de datos imprevisibilemente.
Tu non pote installar o usar MediaWiki si iste option non es disactivate.",
	'config-mbstring' => "'''Fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] es active!'''
Iste option causa errores e pote corrumper datos imprevisibilemente.
Tu non pote installar o usar MediaWiki si iste option non es disactivate.",
	'config-ze1' => "'''Fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] es active!'''
Iste option causa horribile defectos con MediaWiki.
Tu non pote installar o usar MediaWiki si iste option non es disactivate.",
	'config-safe-mode' => "'''Aviso:''' Le [http://www.php.net/features.safe-mode modo secur] de PHP es active.
Isto pote causar problemas, particularmente si es usate le incargamento de files e le supporto de <code>math</code>.",
	'config-xml-good' => 'Ha supporto de conversion XML / Latin1-UTF-8',
	'config-xml-bad' => 'Le modulo XML de PHP es mancante.
MediaWiki require functiones de iste modulo e non functionara in iste configuration.
Si tu usa Mandrake, installa le pacchetto php-xml.',
	'config-pcre' => 'Le modulo de supporto PCRE pare esser mancante.
MediaWiki require le functiones de expression regular compatibile con Perl pro poter functionar.',
	'config-memory-none' => 'PHP es configurate sin <code>memory_limit</code>',
	'config-memory-ok' => 'Le <code>memory_limit</code> de PHP es $1.
OK.',
	'config-memory-raised' => 'Le <code>memory_limit</code> de PHP es $1, elevate a $2.',
	'config-memory-bad' => "'''Aviso:''' Le <code>memory_limit</code> de PHP es $1.
Isto es probabilemente troppo basse.
Le installation pote faller!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] es installate',
	'config-apc' => '[http://www.php.net/apc APC] es installate',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] es installate',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] es installate',
	'config-no-cache' => "'''Aviso:''' Non poteva trovar [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] o [http://www.iis.net/download/WinCacheForPhp WinCache].
Le cache de objectos non es activate.",
	'config-diff3-good' => 'GNU diff3 trovate: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 non trovate.',
	'config-imagemagick' => 'ImageMagick trovate: <code>$1</code>.
Le miniaturas de imagines essera activate si tu activa le incargamento de files.',
	'config-gd' => 'Le bibliotheca graphic GD se trova integrate in le systema.
Le miniaturas de imagines essera activate si tu activa le incargamento de files.',
	'config-no-scaling' => 'Non poteva trovar le bibliotheca GD ni ImageMagick.
Le miniaturas de imagines essera disactivate.',
	'config-dir' => 'Directorio de installation: <code>$1</code>.',
	'config-uri' => 'Adresse URI del script: <code>$1</code>.',
	'config-no-uri' => "'''Error:''' Non poteva determinar le URI actual.
Installation abortate.",
	'config-dir-not-writable-group' => "'''Error:''' Nulle accesso de scriptura in file de configuration.
Installation abortate.

Le installator ha determinate le nomine de usator sub le qual le servitor web es executate.
Tu debe conceder a iste usator le accesso de scriptura in le directorio <code><nowiki>config</nowiki></code> pro poter continuar.
In un systema Unix/Linux:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Error:''' Nulle accesso de scriptura in file de configuration.
Installation abortate.

Le nomine de usator sub le qual le servitor web es executate non poteva esser determinate.
Tu debe conceder a iste usator (e alteres!) le accesso de scriptura in le directorio <code><nowiki>config</nowiki></code> pro poter continuar.
In un systema Unix/Linux:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'MediaWiki es installate con <code>$1</code> extensiones de file.',
	'config-shell-locale' => 'Region "$1" del shell detegite',
	'config-uploads-safe' => 'Le directorio predefinite pro files incargate es protegite contra le execution arbitrari de scripts.',
	'config-uploads-not-safe' => "'''Aviso:''' Le directorio predefinite pro files incargate <code>$1</code> es vulnerabile al execution arbitrari de scripts.
Ben que MediaWiki verifica tote le files incargate contra le menacias de securitate, il es altemente recommendate [http://www.mediawiki.org/wiki/Manual:Security#Upload_security remediar iste vulnerabilitate de securitate] ante de activar le incargamento de files.",
	'config-db-type' => 'Typo de base de datos:',
	'config-db-host' => 'Servitor de base de datos:',
	'config-db-host-help' => 'Si tu servitor de base de datos es in un altere servitor, entra hic le nomine o adresse IP del servitor.

Si tu usa un servitor web usate in commun, tu providitor deberea dar te le correcte nomine de servitor in su documentation.

Si tu face le installation in un servitor Windows e usa MySQL, le nomine "localhost" possibilemente non functiona como nomine de servitor. Si non, essaya "127.0.0.1", i.e. le adresse IP local.',
	'config-db-wiki-settings' => 'Identificar iste wiki',
	'config-db-name' => 'Nomine del base de datos:',
	'config-db-name-help' => 'Selige un nomine que identifica tu wiki.
Illo non debe continer spatios o tractos de union.

Si tu usa un servitor web partite, tu providitor te fornira le nomine specific de un base de datos a usar, o te permitte crear un base de datos via un pannello de controlo.',
	'config-db-install-account' => 'Conto de usator pro installation',
	'config-db-username' => 'Nomine de usator del base de datos:',
	'config-db-password' => 'Contrasigno del base de datos:',
	'config-db-install-help' => 'Entra le nomine de usator e contrasigno que essera usate pro connecter al base de datos durante le processo de installation.',
	'config-db-account-lock' => 'Usar le mesme nomine de usator e contrasigno durante le operation normal',
	'config-db-wiki-account' => 'Conto de usator pro operation normal',
	'config-db-wiki-help' => 'Entra le nomine de usator e contrasigno que essera usate pro connecter al base de datos durante le operation normal del wiki.
Si le conto non existe, e si le conto de installation possede sufficiente privilegios, iste conto de usator essera create con le minime privilegios necessari pro operar le wiki.',
	'config-db-prefix' => 'Prefixo de tabella del base de datos:',
	'config-db-prefix-help' => 'Si il es necessari usar un base de datos in commun inter multiple wikis, o inter MediaWiki e un altere application web, tu pote optar pro adder un prefixo a tote le nomines de tabella pro evitar conflictos.
Non usar spatios o tractos de union.

Iste campo usualmente resta vacue.',
	'config-db-charset' => 'Codification de characteres in le base de datos',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binari',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 retrocompatibile UTF-8',
	'config-charset-help' => "'''Aviso:''' Si tu usa '''UTF-8 retrocompatibile''' sur MySQL 4.1+, e postea face un copia de reserva del base de datos con <code>mysqldump</code>, tote le characteres non ASCII pote esser destruite, resultante in corruption irreversibile de tu copias de reserva!

In '''modo binari''', MediaWiki immagazina texto in UTF-8 in le base de datos in campos binari.
Isto es plus efficiente que le modo UTF-8 de MySQL, e permitte usar le rango complete de characteres de Unicode.
In '''modo UTF-8''', MySQL sapera in qual codification de characteres tu datos es, e pote presentar e converter lo appropriatemente,
ma non te permittera immagazinar characteres supra le [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilingue Basic].",
	'config-mysql-old' => 'MySQL $1 o plus recente es requirite, tu ha $2.',
	'config-db-port' => 'Porto de base de datos:',
	'config-db-schema' => 'Schema pro MediaWiki',
	'config-db-ts2-schema' => 'Schema pro tsearch2',
	'config-db-schema-help' => 'Le schemas hic supra es generalmente correcte.
Solmente cambia los si tu es secur que es necessari.',
	'config-sqlite-dir' => 'Directorio pro le datos de SQLite:',
	'config-sqlite-dir-help' => "SQLite immagazina tote le datos in un sol file.

Le directorio que tu forni debe permitter le accesso de scriptura al servitor web durante le installation.

Illo '''non''' debe esser accessibile via web. Pro isto, nos non lo pone ubi tu files PHP es.

Le installator scribera un file <code>.htaccess</code> insimul a illo, ma si isto falli, alcuno pote ganiar accesso directe a tu base de datos.
Isto include le crude datos de usator (adresses de e-mail, contrasignos codificate) assi como versiones delite e altere datos restringite super le wiki.

Considera poner le base de datos in un loco completemente differente, per exemplo in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki supporta le sequente systemas de base de datos:

$1

Si tu non vide hic infra le systema de base de datos que tu tenta usar, alora seque le instructiones ligate hic supra pro activar le supporto.',
	'config-support-mysql' => '* $1 es le systema primari pro MediaWiki e le melio supportate ([http://www.php.net/manual/en/mysql.installation.php como compilar PHP con supporto de MySQL])',
	'config-support-postgres' => '* $1 es un systema de base de datos popular e open source, alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php como compilar PHP con supporto de PostgreSQL])',
	'config-support-sqlite' => '* $1 es un systema de base de datos legier que es multo ben supportate. ([http://www.php.net/manual/en/pdo.installation.php Como compilar PHP con supporto de SQLite], usa PDO)',
	'config-header-mysql' => 'Configuration de MySQL',
	'config-header-postgres' => 'Configuration de PostgreSQL',
	'config-header-sqlite' => 'Configuration de SQLite',
	'config-header-oracle' => 'Configuration de Oracle',
	'config-invalid-db-type' => 'Typo de base de datos invalide',
	'config-missing-db-name' => 'Tu debe entrar un valor pro "Nomine de base de datos"',
	'config-invalid-db-name' => 'Nomine de base de datos "$1" invalide.
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9) e characteres de sublineamento (_).',
	'config-invalid-db-prefix' => 'Prefixo de base de datos "$1" invalide.
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9) e characteres de sublineamento (_).',
	'config-connection-error' => '$1.

Verifica le servitor, nomine de usator e contrasigno hic infra e reproba.',
	'config-invalid-schema' => 'Schema invalide pro MediaWiki "$1".
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9) e characteres de sublineamento (_).',
	'config-invalid-ts2schema' => 'Schema invalide pro TSearch2 "$1".
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9) e characteres de sublineamento (_).',
	'config-postgres-old' => 'PostgreSQL $1 o plus recente es requirite, tu ha $2.',
	'config-sqlite-name-help' => 'Selige un nomine que identifica tu wiki.
Non usar spatios o tractos de union.
Isto essera usate pro le nomine del file de datos de SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Impossibile crear le directorio de datos <code><nowiki>$1</nowiki></code>, proque le directorio superjacente <code><nowiki>$2</nowiki></code> non concede le accesso de scriptura al servitor web.

Le installator ha determinate le usator sub que le servitor web es executate.
Concede le accesso de scriptura in le directorio <code><nowiki>$3</nowiki></code> a iste usator pro continuar.
In un systema Unix/Linux:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Impossibile crear le directorio de datos <code><nowiki>$1</nowiki></code>, proque le directorio superjacente <code><nowiki>$2</nowiki></code> non concede le accesso de scriptura al servitor web.

Le installator non poteva determinar le usator sub que le servitor web es executate.
Concede le accesso de scriptura in le directorio <code><nowiki>$3</nowiki></code> a iste usator (e alteres!) pro continuar.
In un systema Unix/Linux:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Error al creation del directorio de datos "$1".
Verifica le loco e reproba.',
	'config-sqlite-dir-unwritable' => 'Impossibile scriber in le directorio "$1".
Cambia su permissiones de sorta que le servitor web pote scriber in illo, e reproba.',
	'config-sqlite-connection-error' => '$1.

Verifica le directorio de datos e le nomine de base de datos hic infra e reproba.',
	'config-sqlite-readonly' => 'Le file <code>$1</code> non es accessibile pro scriptura.',
	'config-sqlite-cant-create-db' => 'Non poteva crear le file de base de datos <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP non ha supporto pro FTS3. Le tabellas es retrogradate.',
	'config-sqlite-fts3-add' => 'Adde capabilitates de recerca de FTS3',
	'config-can-upgrade' => "Il ha tabellas MediaWiki in iste base de datos.
Pro actualisar los a MediaWiki $1, clicca super '''Continuar'''.",
	'config-upgrade-done' => "Actualisation complete.

Tu pote ora [$1 comenciar a usar tu wiki].

Si tu vole regenerar tu file <code>LocalSettings.php</code>, clicca super le button hic infra.
Isto '''non es recommendate''' si tu non ha problemas con tu wiki.",
	'config-regenerate' => 'Regenerar LocalSettings.php →',
	'config-show-table-status' => 'Le consulta SHOW TABLE STATUS falleva!',
	'config-unknown-collation' => "'''Aviso:''' Le base de datos usa un collation non recognoscite.",
	'config-db-web-account' => 'Conto de base de datos pro accesso via web',
	'config-db-web-help' => 'Selige le nomine de usator e contrasigno que le servitor web usara pro connecter al servitor de base de datos, durante le operation ordinari del wiki.',
	'config-db-web-account-same' => 'Usar le mesme conto que pro le installation',
	'config-db-web-create' => 'Crear le conto si illo non jam existe',
	'config-db-web-no-create-privs' => 'Le conto que tu specificava pro installation non ha sufficiente privilegios pro crear un conto.
Le conto que tu specifica hic debe jam exister.',
	'config-mysql-engine' => 'Motor de immagazinage:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' es quasi sempre le melior option, post que illo ha bon supporto pro simultaneitate.

'''MyISAM''' pote esser plus rapide in installationes a usator singule o a lectura solmente.
Le bases de datos MyISAM tende a esser corrumpite plus frequentemente que le base de datos InnoDB.",
	'config-mysql-egine-mismatch' => "'''Aviso:''' tu requestava le motor de immagazinage $1, ma le base de datos existente usa le motor $2.
Iste script de actualisation non pote converter lo, dunque illo remanera $2.",
	'config-mysql-charset' => 'Codification de characteres in le base de datos:',
	'config-mysql-binary' => 'Binari',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "In '''modo binari''', MediaWiki immagazina le texto UTF-8 in le base de datos in campos binari.
Isto es plus efficiente que le modo UTF-8 de MySQL, e permitte usar le rango complete de characteres Unicode.

In '''modo UTF-8''', MySQL cognoscera le codification de characteres usate pro tu dats, e pote presentar e converter lo appropriatemente, ma illo non permittera immagazinar characteres supra le [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilingue Basic].",
	'config-mysql-charset-mismatch' => "'''Aviso:''' tu requestava le schema $1, ma le base de datos existente ha le schema $2.
Iste script de actualisation non pote converter lo, dunque illo remanera $2.",
	'config-site-name' => 'Nomine del wiki:',
	'config-site-name-help' => 'Isto apparera in le barra de titulo del navigator e in varie altere locos.',
	'config-site-name-blank' => 'Entra un nomine de sito.',
	'config-project-namespace' => 'Spatio de nomines del projecto:',
	'config-ns-generic' => 'Projecto',
	'config-ns-site-name' => 'Mesme nomine que le wiki: $1',
	'config-ns-other' => 'Altere (specifica)',
	'config-ns-other-default' => 'MiWiki',
	'config-project-namespace-help' => 'Sequente le exemplo de Wikipedia, multe wikis tene lor paginas de politica separate de lor paginas de contento, in un "\'\'\'spatio de nomines de projecto\'\'\'".
Tote le titulos de pagina in iste spatio de nomines comencia con un certe prefixo, le qual tu pote specificar hic.
Traditionalmente, iste prefixo deriva del nomine del wiki, ma illo non pote continer characteres de punctuation como "#" o ":".',
	'config-ns-invalid' => 'Le spatio de nomines specificate "<nowiki>$1</nowiki>" es invalide.
Specifica un altere spatio de nomines de projecto.',
	'config-admin-box' => 'Conto de administrator',
	'config-admin-name' => 'Tu nomine:',
	'config-admin-password' => 'Contrasigno:',
	'config-admin-password-confirm' => 'Repete contrasigno:',
	'config-admin-help' => 'Entra hic tu nomine de usator preferite, per exemplo "Julio Cesare".
Isto es le nomine que tu usara pro aperir session in le wiki.',
	'config-admin-name-blank' => 'Entra un nomine de usator pro administrator.',
	'config-admin-name-invalid' => 'Le nomine de usator specificate "<nowiki>$1</nowiki>" es invalide.
Specifica un altere nomine de usator.',
	'config-admin-password-blank' => 'Entra un contrasigno pro le conto de administrator.',
	'config-admin-password-same' => 'Le contrasigno non pote esser le mesme que le nomine de usator.',
	'config-admin-password-mismatch' => 'Le duo contrasignos que tu scribeva non es identic.',
	'config-admin-email' => 'Adresse de e-mail:',
	'config-admin-email-help' => 'Entra un adresse de e-mail hic pro permitter le reception de e-mail ab altere usatores del wiki, pro poter reinitialisar tu contrasigno, e pro reciper notification de cambios a paginas in tu observatorio.',
	'config-admin-error-user' => 'Error interne durante le creation de un administrator con le nomine "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Error interne durante le definition de un contrasigno pro le administrator "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => 'Subscribe al [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce lista de diffusion pro annuncios de nove versiones].',
	'config-subscribe-help' => 'Isto es un lista de e-mail a basse volumine pro annuncios de nove versiones, includente importante annuncios de securitate.
Tu deberea subscriber a illo e actualisar tu installation de MediaWiki quando nove versiones es editate.',
	'config-almost-done' => 'Tu ha quasi finite!
Tu pote ora saltar le configuration remanente e installar le wiki immediatemente.',
	'config-optional-continue' => 'Pone me plus questiones.',
	'config-optional-skip' => 'Isto me es jam tediose. Simplemente installa le wiki.',
	'config-profile' => 'Profilo de derectos de usator:',
	'config-profile-wiki' => 'Wiki traditional',
	'config-profile-no-anon' => 'Creation de conto requirite',
	'config-profile-fishbowl' => 'Modificatores autorisate solmente',
	'config-profile-private' => 'Wiki private',
	'config-profile-help' => "Le wikis functiona melio si tu permitte a tante personas como possibile de modificar los.
In MediaWiki, il es facile revider le modificationes recente, e reverter omne damno facite per usatores naive o malitiose.

Nonobstante, multes ha trovate MediaWiki utile in un grande varietate de rolos, e alcun vices il non es facile convincer omnes del beneficios del principio wiki.
Dunque, a te le option.

Un '''{{int:config-profile-wiki}}''' permitte a omnes de modificar, sin mesmo aperir un session.
Un wiki con '''{{int:config-profile-no-anon}}''' attribue additional responsabilitate, ma pote dissuader contributores occasional.

Le scenario '''{{int:config-profile-fishbowl}}''' permitte al usatores approbate de modificar, ma le publico pote vider le paginas, includente lor historia.
Un '''{{int:config-profile-private}}''' permitte solmente al usatores approbate de vider le paginas e de modificar los.

Configurationes de derectos de usator plus complexe es disponibile post installation, vide le [http://www.mediawiki.org/wiki/Manual:User_rights pertinente section del manual].",
	'config-license' => 'Copyright e licentia:',
	'config-license-none' => 'Nulle licentia in pede de paginas',
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike (compatibile con Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'Licentia GNU pro Documentation Libere 1.2',
	'config-license-gfdl-current' => 'Licentia GNU pro Documentation Libere 1.3 o plus recente',
	'config-license-pd' => 'Dominio public',
	'config-license-cc-choose' => 'Seliger un licentia Creative Commons personalisate',
	'config-license-help' => "Multe wikis public pone tote le contributiones sub un [http://freedomdefined.org/Definition/Ia?uselang=ia licentia libere].
Isto adjuta a crear un senso de proprietate communitari e incoragia le contribution in longe termino.
Isto non es generalmente necessari pro un wiki private o de interprisa.

Si tu vole poter usar texto de Wikipedia, e si tu vole que Wikipedia pote acceptar texto copiate de tu wiki, tu debe seliger '''Creative Commons Attribution Share Alike'''.

Le Licentia GNU pro Documentation Libere esseva le ancian licentia de publication de Wikipedia.
Iste licentia continua a esser valide, ma illo ha alcun characteristicas que rende le re-uso e interpretation difficile.",
	'config-email-settings' => 'Configuration de e-mail',
	'config-enable-email' => 'Activar le e-mail sortiente',
	'config-enable-email-help' => 'Si tu vole que e-mail functiona, [http://www.php.net/manual/en/mail.configuration.php le optiones de e-mail de PHP] debe esser configurate correctemente.
Si tu non vole functiones de e-mail, tu pote disactivar los hic.',
	'config-email-user' => 'Activar le e-mail de usator a usator',
	'config-email-user-help' => 'Permitter a tote le usatores de inviar e-mail inter se, si illes lo ha activate in lor preferentias.',
	'config-email-usertalk' => 'Activar notification de cambios in paginas de discussion de usatores',
	'config-email-usertalk-help' => 'Permitter al usatores de reciper notification de modificationes in lor paginas de discussion personal, si illes lo ha activate in lor preferentias.',
	'config-email-watchlist' => 'Activar notification de observatorio',
	'config-email-watchlist-help' => 'Permitter al usatores de reciper notification super lor paginas sub observation, si illes lo ha activate in lor preferentias.',
	'config-email-auth' => 'Activar authentication de e-mail',
	'config-email-auth-help' => "Si iste option es activate, le usatores debe confirmar lor adresse de e-mail usante un ligamine inviate a illes, quandocunque illes lo defini o cambia.
Solmente le adresses de e-mail authenticate pote reciper e-mail de altere usatores o alterar le e-mails de notification.
Es '''recommendate''' activar iste option pro wikis public a causa de abuso potential del functionalitate de e-mail.",
	'config-email-sender' => 'Adresse de e-mail de retorno:',
	'config-email-sender-help' => 'Entra le adresse de e-mail a usar como adresse de retorno in e-mail sortiente.
Hic es recipite le notificationes de non-livration.
Multe servitores de e-mail require que al minus le parte de nomine de dominio sia valide.',
	'config-upload-settings' => 'Incargamento de imagines e files',
	'config-upload-enable' => 'Activar le incargamento de files',
	'config-upload-help' => 'Le incargamento de files potentialmente expone tu servitor a riscos de securitate.
Pro plus information, lege le [http://www.mediawiki.org/wiki/Manual:Security section de securitate] in le manual.

Pro activar le incargamento de files, cambia le modo in le subdirectorio <code>images</code> sub le directorio-radice de MediaWiki de sorta que le servitor web pote scriber in illo.
Postea activa iste option.',
	'config-upload-deleted' => 'Directorio pro files delite:',
	'config-upload-deleted-help' => 'Selige un directorio in le qual archivar le files delite.
Idealmente, isto non debe esser accessibile ab le web.',
	'config-logo' => 'URL del logotypo:',
	'config-logo-help' => 'Le apparentia predefinite de MediaWiki include spatio pro un logotypo de 135×160 pixeles in le angulo superior sinistre.
Incarga un imagine con le dimensiones appropriate, e entra le URL hic.

Si tu non vole un logotypo, lassa iste quadro vacue.',
	'config-instantcommons' => 'Activar "Instant Commons"',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] es un function que permitte a wikis de usar imagines, sonos e altere multimedia trovate in le sito [http://commons.wikimedia.org/ Wikimedia Commons].
Pro poter facer isto, MediaWiki require accesso a Internet. $1

Pro plus information super iste function, includente instructiones super como configurar lo pro wikis altere que Wikimedia Commons, consulta [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos le manual].',
	'config-instantcommons-good' => 'Le installator poteva deteger un connexion active a internet durante le verification del ambiente.
Tu pote activar iste function si tu vole.',
	'config-instantcommons-bad' => "''Infelicemente, le installator non poteva deteger un connexion active a internet durante le verification del ambiente, dunque il es forsan impossibile usar iste function.
Si tu servitor es detra un proxy, il pote esser necessari facer alcun [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy configuration additional].''",
	'config-cc-error' => 'Le selector de licentia Creative Commons non dava un resultato.
Entra le nomine del licentia manualmente.',
	'config-cc-again' => 'Selige de novo…',
	'config-cc-not-chosen' => 'Selige le licentia Creative Commons que tu prefere e clicca "proceder".',
	'config-advanced-settings' => 'Configuration avantiate',
	'config-cache-options' => 'Configuration del cache de objectos:',
	'config-cache-help' => 'Le cache de objectos es usate pro meliorar le rapiditate de MediaWiki per immagazinar le datos frequentemente usate.
Le sitos medie o grande es multo incoragiate de activar isto, ma anque le sitos parve percipera le beneficios.',
	'config-cache-none' => 'Nulle cache (nulle functionalitate es removite, ma le rapiditate pote diminuer in grande sitos wiki)',
	'config-cache-accel' => 'Cache de objectos de PHP (APC, eAccelerator, XCache o WinCache)',
	'config-cache-memcached' => 'Usar Memcached (require additional installation e configuration)',
	'config-memcached-servers' => 'Servitores Memcached:',
	'config-memcached-help' => 'Lista de adresses IP a usar pro Memcached.
Debe esser separate con commas e specificar le porto a usar (per exemplo: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Extensiones',
	'config-extensions-help' => 'Le extensiones listate hic supra esseva detegite in tu directorio <code>./extensions</code>.

Istes pote requirer additional configuration, ma tu pote activar los ora.',
	'config-install-alreadydone' => "'''Aviso:''' Il pare que tu ha jam installate MediaWiki e tenta installar lo de novo.
Per favor continua al proxime pagina.",
	'config-install-step-done' => 'finite',
	'config-install-step-failed' => 'fallite',
	'config-install-extensions' => 'Include le extensiones',
	'config-install-database' => 'Configura le base de datos',
	'config-install-pg-schema-failed' => 'Le creation del tabellas falleva.
Assecura te que le usator "$1" pote scriber in le schema "$2".',
	'config-install-user' => 'Crea usator pro base de datos',
	'config-install-user-failed' => 'Le concession de permission al usator "$1" falleva: $2',
	'config-install-tables' => 'Crea tabellas',
	'config-install-tables-exist' => "'''Aviso''': Il pare que le tabellas de MediaWiki jam existe.
Le creation es saltate.",
	'config-install-tables-failed' => "'''Error''': Le creation del tabellas falleva con le sequente error: $1",
	'config-install-interwiki' => 'Plena le tabella interwiki predefinite',
	'config-install-interwiki-sql' => 'Non poteva trovar le file <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Aviso''': Le tabella interwiki pare jam haber entratas.
Le lista predefinite es saltate.",
	'config-install-secretkey' => 'Genera clave secrete',
	'config-insecure-secretkey' => "'''Aviso:''' Impossibile crear le clave secrete <code>\$wgSecretKey</code>.
Considera cambiar lo manualmente.",
	'config-install-sysop' => 'Crea conto de usator pro administrator',
	'config-install-done' => "'''Felicitationes!'''
Tu ha installate MediaWiki con successo.

Le installator ha generate un file <code>LocalSettings.php</code>.
Iste contine tote le configuration.

Tu debe [$1 discargar] lo e poner lo in le base de tu installation wiki (le mesme directorio que index.php).
'''Nota''': Si tu non face isto ora, iste file de configuration generate non essera disponibile a te plus tarde si tu exi le installation sin discargar lo.

Post facer isto, tu pote '''[$2 entrar in tu wiki]'''.",
);

/** Indonesian (Bahasa Indonesia)
 * @author Farras
 */
$messages['id'] = array(
	'config-desc' => 'Penginstal untuk MediaWiki',
	'config-title' => 'Instalasi MediaWiki $1',
	'config-information' => 'Informasi',
	'config-session-error' => 'Kesalahan sesi mulai: $1',
	'config-no-session' => 'Data sesi Anda hilang!
Cek php.ini Anda dan pastikan bahwa <code>session.save_path</code> diatur ke direktori yang sesuai.',
	'config-session-path-bad' => '<code>session.save_path</code> (<code>$1</code>) Anda sepertinya tidak sah atau tidak dapat ditulis.',
	'config-show-help' => 'Bantuan',
	'config-hide-help' => 'Sembunyikan bantuan',
	'config-your-language' => 'Bahasa Anda:',
	'config-your-language-help' => 'Pilih bahasa yang akan digunakan selama proses instalasi.',
	'config-wiki-language' => 'Bahasa wiki:',
	'config-wiki-language-help' => 'Pilih bahasa yang akan digunakan tulisan-tulisan wiki.',
	'config-back' => '← Kembali',
	'config-continue' => 'Lanjut →',
	'config-page-language' => 'Bahasa',
	'config-page-welcome' => 'Selamat datang di MediaWiki',
	'config-page-dbconnect' => 'Hubungkan ke pusat data',
	'config-page-upgrade' => 'Perbarui instalasi yang ada',
	'config-page-dbsettings' => 'Pengaturan pusat data',
	'config-page-name' => 'Nama',
	'config-page-options' => 'Pilihan',
	'config-page-install' => 'Instal',
	'config-page-complete' => 'Selesai!',
	'config-page-restart' => 'Ulangi instalasi',
	'config-page-readme' => 'Baca saya',
	'config-page-releasenotes' => 'Catatan pelepasan',
	'config-page-copying' => 'Menyalin',
	'config-page-upgradedoc' => 'Memerbarui',
	'config-help-restart' => 'Apakah Anda ingin menghapus semua data tersimpan yang telah Anda masukkan dan mengulang proses instalasi?',
	'config-restart' => 'Ya, nyalakan ulang',
	'config-authors' => 'MediaWiki adalah Hak Cipta © 2001-2010 oleh Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe dan lainnya.',
	'config-sidebar' => '* [http://www.mediawiki.org Halaman utama MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Panduan Pengguna]
* [http://www.mediawiki.org/wiki/Manual:Contents Panduan Pengurus]
* [http://www.mediawiki.org/wiki/Manual:FAQ Pertanyaan yang Sering Diajukan]',
	'config-env-good' => '<span class="success-message">Kondisi telah diperiksa.
Anda dapat menginstal MediaWiki.</span>',
	'config-env-bad' => 'Kondisi telah diperiksa.
Anda tidak dapat menginstal MediaWiki.',
	'config-env-php' => 'PHP $1 diinstal.',
	'config-env-latest-ok' => 'Anda menginstal versi terbaru Mediawiki.',
	'config-env-latest-new' => "'''Catatan:''' Anda menginstal versi pengembangan MediaWiki.",
	'config-env-latest-can-not-check' => "'''Peringatan:''' Penginstal tidak dapat memeroleh informasi mengenai rilis MediaWiki terbaru dari [$1].",
	'config-env-latest-old' => "'''Peringatan:''' Anda menginstal versi kadaluwarsa MediaWiki.",
	'config-env-latest-help' => 'Anda menginstal versi $1, tetapi versi terbaru ialah $2.
Anda disarankan untuk menggunakan versi terbaru yang dapat diunduh dari [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Menggunakan implementasi PHP lambat untuk normalisasi Unicode.',
	'config-unicode-using-utf8' => 'Menggunakan utf8_normalize.so Brion Vibber untuk normalisasi Unicode.',
	'config-unicode-using-intl' => 'Menggunakan [http://pecl.php.net/intl ekstensi PECL intl] untuk normalisasi Unicode.',
	'config-xml-good' => 'Memiliki bantuan konversi XML / Latin1-UTF-8.',
	'config-xml-bad' => 'Modul XML PHP hilang.
MediaWiki membutuhkan fungsi dalam modul ini dan tidak akan bekerja dalam konfigurasi ini.
Jika Anda menggunakan Mandrake, instal paket php-xml.',
	'config-memory-none' => 'PHP dikonfigurasi tanpa <code>memory_limit</code>',
	'config-memory-ok' => '<code>memory_limit</code> PHP adalah $1.
OK.',
	'config-memory-raised' => '<code>memory_limit</codde> PHP adalah $1, dinaikkan ke $2.',
	'config-memory-bad' => "'''Peringatan:''' <code>memory_limit</code> PHP adalah $1.
Ini terlalu rendah.
Instalasi terancam gagal!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] telah diinstal',
	'config-apc' => '[http://www.php.net/apc APC] telah diinstal',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] telah diinstal',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] telah diinstal',
	'config-no-cache' => "'''Peringatan:''' Tidak dapat menemukan [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] atau [http://www.iis.net/download/WinCacheForPhp WinCache].
Penembolokan obyek tidak dinonaktifkan.",
	'config-diff3-good' => 'Ditemukan diff3 GNU: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 tidak ditemukan.',
	'config-dir' => 'Direktori instalasi: <code>$1</code>.',
	'config-file-extension' => 'Menginstal MediaWiki dengan ekstensi berkas <code>$1</code>.',
	'config-db-type' => 'Jenis basis data:',
	'config-db-wiki-settings' => 'Identifikasi wiki ini',
	'config-db-name' => 'Nama basis data:',
	'config-db-install-account' => 'Akun pengguna untuk instalasi',
	'config-db-username' => 'Nama pengguna basis data:',
	'config-db-password' => 'Kata sandi basis data:',
	'config-db-account-lock' => 'Gunakan nama pengguna dan kata sandi yang sama selama operasi normal',
	'config-db-wiki-account' => 'Akun pengguna untuk operasi normal',
	'config-db-prefix' => 'Prefiks tabel basis data:',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 biner',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'MySQL $1 atau versi terbaru diperlukan, Anda menggunakan $2.',
	'config-db-schema' => 'Skema untuk MediaWiki',
	'config-db-ts2-schema' => 'Skema untuk tsearch2',
	'config-sqlite-dir' => 'Direktori data SQLite:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Pengaturan MySQL',
	'config-header-postgres' => 'Pengaturan PostgreSQL',
	'config-header-sqlite' => 'Pengaturan SQLite',
	'config-header-oracle' => 'Pengaturan Oracle',
	'config-invalid-db-type' => 'Jenis basis data tidak sah',
	'config-missing-db-name' => 'Anda harus memasukkan nilai untuk "Nama basis data"',
	'config-postgres-old' => 'PostgreSQL $1 atau versi terbaru diperlukan, Anda menggunakan $2.',
	'config-sqlite-connection-error' => '$1.

Periksa direktori data dan nama basis data di bawah dan coba lagi.',
	'config-sqlite-readonly' => 'Berkas <code>$1</code> tidak dapat ditulisi.',
	'config-sqlite-cant-create-db' => 'Tidak dapat membuat berkas basis data <code>$1</code>.',
	'config-sqlite-fts3-add' => 'Menambahkan kemampuan pencarian FTS3',
	'config-can-upgrade' => "Ada tabel MediaWiki di basis dataini.
Untuk memperbaruinya ke MediaWiki $1, klik '''Lanjut'''.",
	'config-regenerate' => 'Regenerasi LocalSettings.php →',
	'config-show-table-status' => 'Kueri SHOW TABLE STATUS gagal!',
	'config-db-web-account' => 'Akun basis data untuk akses web',
	'config-db-web-account-same' => 'Gunakan akun yang sama seperti untuk instalasi',
	'config-db-web-create' => 'Buat akun jika belum ada',
	'config-mysql-engine' => 'Mesin penyimpanan:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Biner',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Nama wiki:',
	'config-site-name-blank' => 'Masukkan nama situs.',
	'config-project-namespace' => 'Ruang nama proyek:',
	'config-ns-generic' => 'Proyek',
	'config-ns-site-name' => 'Sama seperti nama wiki: $1',
	'config-ns-other' => 'Lainnya (sebutkan)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Akun pengurus',
	'config-admin-name' => 'Nama Anda:',
	'config-admin-password' => 'Kata sandi:',
	'config-admin-password-confirm' => 'Kata sandi lagi:',
	'config-admin-name-blank' => 'Masukkan nama pengguna pengurus.',
	'config-admin-password-blank' => 'Masukkan kata sandi untuk akun pengurus.',
	'config-admin-password-same' => 'Kata sandi harus tidak sama seperti nama pengguna.',
	'config-admin-password-mismatch' => 'Dua kata sandi yang Anda masukkan tidak cocok.',
	'config-admin-email' => 'Alamat surel:',
	'config-optional-continue' => 'Berikan saya pertanyaan lagi.',
	'config-optional-skip' => 'Saya sudah bosan, instal saja wikinya.',
	'config-profile' => 'Profil hak pengguna:',
	'config-profile-wiki' => 'Wiki tradisional',
	'config-profile-no-anon' => 'Pembuatan akun diperlukan',
	'config-profile-fishbowl' => 'Khusus penyunting terdaftar',
	'config-profile-private' => 'Wiki pribadi',
	'config-license' => 'Hak cipta dan lisensi:',
	'config-license-none' => 'Tidak ada lisensi',
	'config-license-cc-by-sa' => 'Creative Commons Atribusi Berbagi Serupa (cocok untuk Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Atribusi Non-Komersial Berbagi Serupa',
	'config-license-gfdl-old' => 'Lisensi Dokumentasi Bebas GNU 1.2',
	'config-license-gfdl-current' => 'Lisensi Dokumentasi Bebas GNU 1.3 atau versi terbaru',
	'config-license-pd' => 'Domain Umum',
	'config-license-cc-choose' => 'Pilih lisensi Creative Commons kustom',
	'config-email-settings' => 'Pengaturan surel',
	'config-email-watchlist' => 'Aktifkan pemberitahuan daftar pantau',
	'config-email-auth' => 'Aktifkan otentikasi surel',
	'config-upload-settings' => 'Pengunggahan gambar dan berkas',
	'config-upload-enable' => 'Aktifkan pengunggahan berkas',
	'config-upload-deleted' => 'Direktori untuk berkas terhapus:',
	'config-logo' => 'URL logo:',
	'config-instantcommons' => 'Aktifkan Instant Commons',
	'config-cc-error' => 'Pemilih lisensi Creative Commons tidak memberikan hasil.
Masukkan nama lisensi secara manual.',
	'config-cc-again' => 'Pilih lagi...',
	'config-cc-not-chosen' => 'Pilih lisensi Creative Commons yang Anda inginkan dan klik "lanjutkan".',
	'config-advanced-settings' => 'Konfigurasi lebih lanjut',
	'config-cache-options' => 'Pengaturan untuk penembolokan objek:',
	'config-cache-accel' => 'Penembolokan objek PHP (APC, eAccelerator, XCache atau WinCache)',
	'config-cache-memcached' => 'Gunakan Memcached (memerlukan setup dan konfigurasi tambahan)',
	'config-memcached-servers' => 'Server Memcached:',
	'config-memcached-help' => 'Daftar alamat IP yang digunakan untuk Memcached.
Harus dipisahkan dengan koma dan sebutkan port yang akan digunakan (contoh: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Ekstensi',
	'config-install-step-done' => 'selesai',
	'config-install-step-failed' => 'gagal',
	'config-install-extensions' => 'Termasuk ekstensi',
	'config-install-database' => 'Mendirikan basis data',
	'config-install-user' => 'Membuat pengguna basis data',
	'config-install-user-failed' => 'Memberikan izin untuk pengguna "$1" gagal: $2',
	'config-install-tables' => 'Membuat tabel',
	'config-install-tables-exist' => "'''Peringatan''': Tabel MediaWiki sepertinya sudah ada.
Melompati pembuatan.",
	'config-install-tables-failed' => "'''Kesalahan''': Pembuatan tabel gagal dengan kesalahan berikut: $1",
	'config-install-interwiki-sql' => 'Tidak dapat menemukan berkas <code>interwiki.sql</code>.',
	'config-install-secretkey' => 'Menciptakan kunci rahasia',
	'config-install-sysop' => 'Membuat akun pengguna pengurus',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'config-admin-password' => 'Okwúngáfè:',
	'config-admin-password-confirm' => 'Okwúngáfè mgbe ozor:',
);

/** Japanese (日本語)
 * @author Whym
 * @author Yanajin66
 * @author 青子守歌
 */
$messages['ja'] = array(
	'config-desc' => 'MediaWikiのためのインストーラー',
	'config-title' => 'MediaWiki $1のインストール',
	'config-information' => '情報',
	'config-localsettings-upgrade' => "'''警告'''：<code>LocalSettings.php</code>ファイルが検出されました。
ソフトウェアは更新できます。
<code>LocalSettings.php</code>を他の安全な場所へ移動させ、再度インストーラーを実行してください。",
	'config-localsettings-noupgrade' => "'''エラー'''：<code>LocalSettings.php</code>ファイルが検出されました。
現在、ソフトウェアを更新できません。
セキュリティ上の理由で、インストーラーは無効になっています。",
	'config-session-error' => 'セッションの開始エラー：$1',
	'config-session-expired' => 'セッションの有効期限が切れたようです。
セッションの有効期間は$1に設定されています。
php.iniの<code>session.gc_maxlifetime</code>を設定することで、この問題を改善できます。
インストール作業を再起動させてください。',
	'config-no-session' => 'セッションのデータが損失しました！
php.iniを確認し、<code>session.save_path</code>が適切なディレクトリに設定されていることを確かめて下さい。',
	'config-session-path-bad' => '<code>session.save_path</code>（<code>$1</code>）が、無効または書き込み不可となっています。',
	'config-show-help' => 'ヘルプ',
	'config-hide-help' => 'ヘルプを隠す',
	'config-your-language' => 'あなたの言語：',
	'config-your-language-help' => 'インストール作業中に利用する言語を選んで下さい。',
	'config-wiki-language' => 'ウィキの言語：',
	'config-wiki-language-help' => 'そのウィキで主に書き込まれる言語を選んで下さい。',
	'config-back' => '←戻る',
	'config-continue' => '続き→',
	'config-page-language' => '言語',
	'config-page-welcome' => 'MediaWikiへようこそ！',
	'config-page-dbconnect' => 'データベースへ接続',
	'config-page-upgrade' => '既存のインストールを更新',
	'config-page-dbsettings' => 'データベースの設定',
	'config-page-name' => '名前',
	'config-page-options' => 'オプション',
	'config-page-install' => 'インストール',
	'config-page-complete' => '完了！',
	'config-page-restart' => 'インストールを再起動',
	'config-page-readme' => 'リードミー',
	'config-page-releasenotes' => 'リリースノート',
	'config-page-copying' => 'コピー',
	'config-page-upgradedoc' => '更新',
	'config-help-restart' => '入力された全て保存データを消去し、インストール作業を再起動しますか？',
	'config-restart' => 'はい、再起動します',
	'config-welcome' => '=== 環境の確認 ===
基本的な確認では、この環境がMediaWikiの導入に適しているかを確認します。
インストール中に必要になったとき、この確認結果を利用して下さい。',
	'config-copyright' => '=== 著作権および規約 ===
$1

この作品はフリーソフトウェアです。あなたは、フリーソフトウェア財団の発行するGNU一般公衆利用許諾書 (GNU General Public License)（バージョン2、またはそれ以降のライセンス）の規約にもとづき、このライブラリの再配布や改変をすることができます。

この作品は、有用であることを期待して配布されていますが、商用あるいは特定の目的に適するかどうかも含めて、暗黙的にも、一切保証されません。
詳しくは、GNU一般公衆利用許諾書をご覧下さい。

あなたはこのプログラムと共に、<doclink href=Copying>GNU一般公衆利用許諾契約書の複製</doclink>を一部受け取ったはずです。もし受け取っていなければ、フリーソフトウェア財団(宛先は the Free Software Foundation, Inc., 59Temple Place, Suite 330, Boston, MA 02111-1307 USA)まで請求してください。',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWikiのホーム]
* [http://www.mediawiki.org/wiki/Help:Contents 利用者向け案内]
* [http://www.mediawiki.org/wiki/Manual:Contents 管理人向け案内]
* [http://www.mediawiki.org/wiki/Manual:FAQ よくある質問と回答]',
	'config-env-good' => '<span class="success-message">環境は確認されました。
MediaWikiをインストール出来ます。</span>',
	'config-env-bad' => '環境が確認されました。
MediaWikiをインストール出来ません。',
	'config-env-php' => 'PHP $1がインストールされています。',
	'config-env-latest-ok' => '最新バージョンのMediaWikiをインストールしています。',
	'config-env-latest-new' => "'''注意：'''MediaWikiの開発版をインストールしています。",
	'config-env-latest-can-not-check' => "'''警告：'''インストーラーは、[$1]から、MediaWikiの最新リリースに関する情報を取得できませんでした。",
	'config-env-latest-old' => "'''警告'''：MediaWikiの古いバージョンをインストールしようとしています。",
	'config-env-latest-help' => 'バージョン$1をインストールしようとしていますが、最新版は$2です。
最新のリリースを利用することが推奨されています。最新版は[http://www.mediawiki.org/wiki/Download mediawiki.org]からダウンロード可能です。',
	'config-unicode-using-php' => 'Unicode正規化が遅いPHP実装を利用。',
	'config-unicode-using-utf8' => 'Unicode正規化に、Brion Vibberのutf8_normalize.soを利用。',
	'config-unicode-using-intl' => 'Unicode正規化に[http://pecl.php.net/intl intl PECL 拡張機能]を利用。',
	'config-unicode-pure-php-warning' => "'''警告'''：[http://pecl.php.net/intl intl PECL 拡張機能]は、Unicode正規化の処理に利用されていません。
高トラフィックのサイトを運営する場合は、[http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode正規化に関するページ]をお読み下さい。",
	'config-unicode-update-warning' => "'''警告'''：Unicode正規化ラッパーのインストールされているバージョンは、[http://site.icu-project.org/ ICUプロジェクト]のライブラリの古いバージョンを使用しています。
Unicodeを少しでも利用する可能性があるなら、[http://www.mediawiki.org/wiki/Unicode_normalization_considerations 更新]する必要があります。",
	'config-no-db' => '適切なデータベースドライバを見つけられませんでした！',
	'config-no-db-help' => 'PHPのデータベースドライバーをインストールする必要があります。
以下のデータベースの種類がサポートされます：$1。

共有ホスト上の場合、ホスト元に適切なデータベースドライバをインストールするように依頼してください。
PHPを自分自身でコンパイルした場合、<code>./configure --with-mysql</code>などを利用して、データベースクライアントを有効化する設定をしてください。
DebianもしくはUbuntuパッケージからPHPをインストールした場合、php5-mysqlモジュールもインストールする必要があります。',
	'config-have-db' => '見つかったデータベース{{PLURAL:$2|ドライバ}}：$1。',
	'config-register-globals' => "'''警告：PHPの<code>[http://php.net/register_globals register_globals]</code>オプションが有効になっています。'''
'''可能なら無効化してください。'''
MediaWikiは動作しますが、サーバーは、潜在的なセキュリティ脆弱性を露呈します。",
	'config-magic-quotes-runtime' => "'''致命的エラー：[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]が動作しています！'''
このオプションは、予期せずデータ入力を破壊します。
このオプションが無効化されないかぎり、MediaWikiをインストールし利用することはできません。",
	'config-magic-quotes-sybase' => "'''致命的エラー：[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]が動作しています！'''
このオプションは、予期せずデータ入力を破壊します。
このオプションが無効化されないかぎり、MediaWikiをインストールし利用することはできません。",
	'config-mbstring' => "'''致命的エラー：[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]が動作しています！'''
このオプションは、エラーを引き起こし、予期せずデータ入力を破壊する可能性があります。
このオプションが無効化されないかぎり、MediaWikiをインストールし利用することはできません。",
	'config-ze1' => "'''致命的エラー：[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]が動作しています！'''
このオプションは、MediaWikiにおいて深刻なバグを引き起こします。
このオプションが無効化されないかぎり、MediaWikiをインストールし利用することはできません。",
	'config-safe-mode' => "'''警告：'''PHPの[http://www.php.net/features.safe-mode セーフモード]が有効です。
特にファイルのアップロード<code>math</code>のサポートにおいて、問題が発生する可能性があります。",
	'config-xml-good' => 'XML/Latin1-UTF-8変換のサポートあり',
	'config-xml-bad' => 'PHPのXMLモジュールが不足しています。
MediaWikiは、このモジュールの関数を必要としているため、この構成では動作しません。
Mandrakeを実行している場合、php-xmlパッケージをインストールしてください。',
	'config-pcre' => 'PCREをサポートしているモジュールが不足しているようです。
MediaWikiは、Perl互換の正規表現関数の動作が必要です。',
	'config-memory-none' => 'PHPは<code>memory_limit</code>を設定していません。',
	'config-memory-ok' => 'PHPの<code>memory_limit</code>は$1です。
OK。',
	'config-memory-raised' => 'PHPの<code>memory_limit</code>は$1で、$2に引き上げられました。',
	'config-memory-bad' => "'''警告：'''PHPの<code>memory_limit</code>は$1です。
これは、非常に遅い可能性があります。
インストールが失敗するかもしれません！",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache]がインストール済み',
	'config-apc' => '[http://www.php.net/apc APC]がインストール済み',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator]がインストール済み',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache]がインストール済み',
	'config-no-cache' => "'''警告：'''[http://eaccelerator.sourceforge.net eAccelerator]、[http://www.php.net/apc APC]、[http://trac.lighttpd.net/xcache/ XCache]あるいは[http://www.iis.net/download/WinCacheForPhp WinCache]のいずれも見つかりませんでした。
オブジェクトのキャッシュは有効化されません。",
	'config-diff3-good' => 'GNU diff3が見つかりました：<code>$1</code>。',
	'config-diff3-bad' => 'GNU diff3が見つかりません。',
	'config-imagemagick' => 'ImageMagickが見つかりました：<code>$1</code>。
アップロードが有効なら、画像のサムネイルが利用できます。',
	'config-gd' => 'GD画像ライブラリが内蔵されていることが確認されました。
アップロードが有効なら、画像のサムネイルが利用できます。',
	'config-no-scaling' => 'GDライブラリもImageMagickも見つかりませんでした。
画像のサムネイル生成は無効になります。',
	'config-dir' => 'インストールするディレクトリ：<code>$1</code>。',
	'config-uri' => 'スクリプトURIのパス：<code>$1</code>。',
	'config-no-uri' => "'''エラー：'''現在のURIを決定できませんでした。
インストールは中止されました。",
	'config-dir-not-writable-group' => "'''エラー：'''設定ファイルが書き込めませんでした。
インストールは中止されました。

インストーラーは、ウェブサーバーを実行している利用者を特定しました。
<code><nowiki>config</nowiki></code>ディレクトリを書き込み可能にしてください。
Unix/Linuxシステムの場合：

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''エラー：'''設定ファイルが書き込めません。
インストールは中止されました。

ウェブサーバーを実行している利用者を特定できません。
<code><nowiki>config</nowiki></code>ディレクトリーをグローバルに書き込み可能んしてください。
Unix/Linuxシステムの場合の方法：

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'MediaWikiをファイル拡張子<code>$1</code>でインストール中',
	'config-shell-locale' => 'シェルのロケールが「$1」であることを検出しました',
	'config-uploads-safe' => 'アップロードの既定ディレクトリは、任意のスクリプト実行に対して安全です。',
	'config-uploads-not-safe' => "'''警告：'''アップロードの既定ディレクトリ<code>$1</code>が、任意のスクリプト実行に関して脆弱性があります。
MediaWikiはアップロードされたファイルのセキュリティ上の脅威を確認しますが、アップロードを有効化するまえに、[http://www.mediawiki.org/wiki/Manual:Security#Upload_security このセキュリティ上の脆弱性を閉じる]ことが強く推奨されます。",
	'config-db-type' => 'データベースの種類：',
	'config-db-host' => 'データベースのホスト：',
	'config-db-host-help' => 'データベースサーバーが異なったサーバー上にある場合、ホスト名またはIPアドレスをここに入力してください。

もし、共有されたウェブホスティングを使用している場合、ホスティング・プロバイダは正確なホストネームを解説しているはずです。

WindowsでMySQLを使用している場合に、「localhost」は、サーバー名としてはうまく働かないでしょう。もしそのような場合は、ローカルIPアドレスとして「127.0.0.1」を試してみてください。',
	'config-db-wiki-settings' => 'このウィキを識別',
	'config-db-name' => 'データベース名：',
	'config-db-name-help' => 'このウィキを識別する名前を選んで下さい。
空白やハイフンは含められません。

共有ウェブホストを利用している場合、ホスト・プロバイダーは特定の利用可能なデータベース名を提供するか、あるいは管理パネルからデータベースを作成できるようにしているでしょう。',
	'config-db-install-account' => 'インストールのための利用者アカウント',
	'config-db-username' => 'データベースの利用者名：',
	'config-db-password' => 'データベースのパスワード：',
	'config-db-install-help' => 'インストール作業中にデータベースに接続するための利用者名とパスワードを入力してください。',
	'config-db-account-lock' => 'インストール作業終了後も同じ利用者名とパスワードを使用する',
	'config-db-wiki-account' => 'インストール作業終了後の利用者アカウント',
	'config-db-wiki-help' => '通常のウィキ操作中にデータベースへの接続する時に利用する利用者名とパスワードを入力してください。
アカウントがないが、インストールのアカウントに十分な権限があれば、このユーザーアカウントは、ウィキを操作するうえで最小限の権限を持った状態で作成されます。',
	'config-db-prefix' => 'データベーステーブルの接頭辞：',
	'config-db-prefix-help' => 'データベースを複数のウィキ間、もしくはMediaWikiと他のウェブアプリケーションで共有する必要がある場合、衝突を避けるために、すべてのテーブル名に接頭辞をつける必要があります。
スペースやハイフンは使用しないでください。

このフィールドは、通常は空のままです。',
	'config-db-charset' => 'データベースの文字セット',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0バイナリ',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'MySQLの$1以降が要求されています。あなたの所有のものは$2です。',
	'config-db-port' => 'データベースポート:',
	'config-db-schema' => 'メディアウィキの図式',
	'config-db-schema-help' => '上の図式は常に正確です。
必要である場合のみ、変更してください。',
	'config-sqlite-dir' => 'SQLiteのデータディレクトリ:',
	'config-sqlite-dir-help' => 'SQLiteは単一のファイル中に全てのデータを保持しています。

あなたが供給するディレクトリーはインストール時にウェブサーバーによって書き込み可能でなければならない。

ウェブを通してアクセス可能"不可能"でなければならない。これはあなたのPHPファイルのある所に配置不能な理由です。

インストーラーは共に<code>.htaccess</code>ファイルを書き込むことでしょう。しかし、例え失敗しても誰かがあなたの生のデータベースにアクセスすることが可能となるでしょう。

例えば<code>/var/lib/mediawiki/yourwiki</code>のように、全く違う場所にデータベースを配置することを考えてください。',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'メディアウィキは次のようなデータベースシステムをサポートする:

$1

もし、データベースシステムが不可視であるならば、以下のようにリスト化されたものを使用してみてください。可能なサポートの指示に従ってください。',
	'config-header-mysql' => 'MySQLの設定',
	'config-header-postgres' => 'PostgreSQLの設定',
	'config-header-sqlite' => 'SQLiteの設定',
	'config-header-oracle' => 'Oracleの設定',
	'config-invalid-db-type' => '不正なデータベースの種類',
	'config-missing-db-name' => '「データベース名」を入力する必要があります',
	'config-invalid-db-name' => '無効なデータベース名"$1"。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)のみを使用してください。',
	'config-invalid-db-prefix' => 'データベースの接頭語"$1"が無効です。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)のみを使用してください。',
	'config-connection-error' => '$1。

以下のホスト名、ユーザ名、パスワードをチェックして、再度試してみてください。',
	'config-invalid-schema' => 'メディアウィキ"$1"における無効な図式です。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)のみを使用してください。',
	'config-invalid-ts2schema' => 'TSearch2 "$1"における無効な図式です。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)のみを使用してください。',
	'config-sqlite-name-help' => 'あなたのウェキと同一性のある名前を選んでください。
空白およびハイフンは使用しないでください。
SQLiteのデータファイル名として使用されます。',
	'config-sqlite-mkdir-error' => 'データディレクトリー"$1"を作成したことによるエラー。
場所をチェックして、再度試してください。',
	'config-sqlite-dir-unwritable' => 'ディレクトリー"$1"を書き込むことができません。
パーミッションを変更すれば、ウェブサーバーが書き込み可能となります。再度試してください。',
	'config-sqlite-connection-error' => '$1。

以下のデータディレクトリーとデータベースをチェックし、再度試してみてください。',
	'config-sqlite-readonly' => 'ファイル<code>$1</code>は書き込み不能です。',
	'config-sqlite-cant-create-db' => 'データベースファイル<code>$1</code>を作成できませんでした。',
	'config-sqlite-fts3-downgrade' => 'PHPはFTS3のサポート、テーブルのダウングレードが無効です。',
	'config-sqlite-fts3-add' => 'FTS3の検索機能を追加する',
	'config-can-upgrade' => 'このデータベースにはメディアウィキテーブルが存在します。
それらをメディアウィキ$1にアップグレードするために「続行」をクリックしてください。',
	'config-db-web-account' => 'ウェブアクセスのためのデータベースアカウント',
	'config-db-web-help' => 'ウィキの元来の操作中、ウェブサーバーがデーターベースサーバーに接続できるように、ユーザ名とパスワードを選択してください。',
	'config-db-web-account-same' => 'インストールのために同じアカウントを使用してください',
	'config-db-web-create' => '既に存在していないのであれば、アカウントを作成してください',
	'config-db-web-no-create-privs' => 'あなたがインストールのために定義したアカウントは、アカウント作成のための特権としては不充分です。
あなたがここで特定したアカウントはすでに存在していなければなりません。',
	'config-mysql-engine' => 'ストレージエンジン:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'データベースの文字セット:',
	'config-mysql-binary' => 'バイナリ',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'ウィキの名前：',
	'config-site-name-help' => 'この事象はブラウザのタイトルバーと他の様々な場所において出現する。',
	'config-site-name-blank' => 'サイト名を入力してください。',
	'config-project-namespace' => 'プロジェクト名前空間：',
	'config-ns-generic' => 'プロジェクト',
	'config-ns-site-name' => 'ウィキ名と同じ：$1',
	'config-ns-other' => 'その他(特化されたもの)',
	'config-ns-other-default' => 'マイウィキ',
	'config-project-namespace-help' => "ウィキペディアの例に従えば、多くのウィキは「'''プロジェクトの名前空間'''」において、コンテンツのページとは分離した独自のポリシーページを持つ。
伝統的にはこの接頭辞はウィキのページから派生される。しかし、\"#\" や \":\"のような句切り記号は含んでいない。",
	'config-ns-invalid' => '"<nowiki>$1</nowiki>"のように指定された名前空間は無効です。
違うプロジェクト名前空間を指定してください。',
	'config-admin-box' => '管理アカウント',
	'config-admin-name' => '名前：',
	'config-admin-password' => 'パスワード：',
	'config-admin-password-confirm' => 'パスワードの再入力：',
	'config-admin-help' => 'ここにあなたの希望するユーザ名を入力してください（例えば"Joe Bloggs"など）。
この名前でこのウィキにログインすることになります。',
	'config-admin-name-blank' => '管理者のユーザ名を入力してください。',
	'config-admin-name-invalid' => '指定されたユーザ名 "<nowiki>$1</nowiki>" は無効です。
別のユーザ名を指定してください。',
	'config-admin-password-blank' => '管理者アカウントのパスワードを入力してください。',
	'config-admin-password-same' => 'ユーザ名と同じパスワードは使えません。',
	'config-admin-password-mismatch' => '入力された二つのパスワードが一致しません。',
	'config-admin-email' => 'Eメールアドレス：',
	'config-admin-email-help' => '電子メールアドレスを入力してください。他のユーザーからの電子メールの受け取りと、パスワードのリセット、ウォッチリストに登録したページの更新通知に用いられます。',
	'config-admin-error-user' => '"<nowiki>$1</nowiki>"という名前の管理者を作成する際に内部エラーが発生しました。',
	'config-admin-error-password' => '管理者"<nowiki>$1</nowiki>"のパスワードを設定する際に内部エラーが発生しました: <pre>$2</pre>',
	'config-almost-done' => 'あなたはほとんど完璧です！
設定を残すことをはぶいて、今すぐにウィキをインストールできます。',
	'config-optional-continue' => '私にもっと質問してください。',
	'config-optional-skip' => 'すでに飽きてしまった、ウィキをインストールするだけです。',
	'config-profile' => '正しいプロフィールのユーザ:',
	'config-profile-wiki' => '伝統的なウィキ',
	'config-profile-no-anon' => 'リクエストされたアカウント作成',
	'config-profile-fishbowl' => '承認された編集者のみ',
	'config-profile-private' => 'プライベートなウィキ',
	'config-license' => '著作権とライセンス:',
	'config-license-none' => 'ライセンスのフッターを付けない',
	'config-license-cc-by-sa' => 'クリエイティブ・コモンズ 表示-継承 (Wikipedia互換)',
	'config-license-cc-by-nc-sa' => 'クリエイティブ・コモンズ 表示-非営利-継承',
	'config-license-gfdl-old' => 'GNUフリー文書利用許諾契約書 1.2',
	'config-license-gfdl-current' => 'GNUフリー文書利用許諾契約書 1.3 またはそれ以降',
	'config-license-pd' => 'パブリック・ドメイン',
	'config-license-cc-choose' => 'その他のクリエイティブ・コモンズ・ライセンスを選択する',
	'config-license-help' => "多くの公開ウィキでは、すべての寄稿物が[http://freedomdefined.org/Definition フリーライセンス]の元に置かれています。
こうすることにより、コミュニティによる共有の感覚が生まれ、長期的な寄稿が促されます。
私的ウィキや企業のウィキでは、通常、フリーライセンスにする必要はありません。

ウィキペディアにあるテキストをあなたのウィキで利用し、逆にあなたのウィキにあるテキストをウィキペディアに複製することを許可したい場合には、'''クリエイティブ・コモンズ 表示-継承'''を選択するべきです。

GNUフリー文書利用許諾契約書はウィキペディアが採用していた古いライセンスです。
今も有効なライセンスではありますが、再利用や解釈を難しくする条項が含まれています。",
	'config-email-settings' => '電子メールの設定',
	'config-enable-email' => '電子メール送信の有効',
	'config-enable-email-help' => "もし、電子メールの作動を欲するならば、[http://www.php.net/manual/en/mail.configuration.php PHP's mail settings]のページが正確に設定されている必要がある。
もし、電子メールに関するいかなる機能を欲しないのであれば、ここで無効にできます。",
	'config-email-user' => 'ユーザ間同士の電子メールの許可',
	'config-email-user-help' => '設定において有効になっている場合、全てのユーザがお互いに電子メールのやりとりを行うことを許可する。',
	'config-email-usertalk' => 'ユーザのトークページにおける通知を有効にする',
	'config-email-usertalk-help' => '設定で有効にしているならば、ユーザのトークページの変更の通知を受けることをユーザに許可する。',
	'config-email-watchlist' => 'ウォッチリストの通知を有効にする',
	'config-email-watchlist-help' => '設定で有効にしているならば、閲覧されたページに関する通知を受け取ることをユーザに許可する。',
	'config-email-auth' => '電子メールの認証を有効にする',
	'config-email-sender' => '電子メールのアドレスを返す:',
	'config-upload-settings' => '画像およびファイルのアップロード',
	'config-upload-enable' => 'ファイルのアップロードを有効にする',
	'config-upload-help' => 'ファイルのアップロードは潜在的にあなたのサーバにセキュリティー上の危険をさらします。
更なる情報のために、マニュアルの[http://www.mediawiki.org/wiki/Manual:Security security section] を読むことをすすめます。

ファイルのアップロードを可能にするために、メディアウィキのルートディレクトリ下の<code>images</code>サブディレクトリのモードを変更します。そうすることにより、ウェブサーバはそこに書き込みが可能になります。
そして、このオプションを有効にしてください。',
	'config-upload-deleted' => '削除されたファイルのためのディレクトリ:',
	'config-upload-deleted-help' => '削除されるファイルを保存するためのディレクトリを選択してください。
これがウェブからアクセスできないことが理想です。',
	'config-logo' => 'ロゴのURL:',
	'config-logo-help' => 'メディアウィキの初期のスキンは最上部左角にある135x160ピクセルのロゴのためにスペースを含んでいます。
適切なサイズのイメージをアップロードし、ここにURLを入力してください。

もし、ロゴを望まないならば、このボックスを空白状態のままにしてください。',
	'config-instantcommons' => '瞬時のコモンズを有効にする',
	'config-instantcommons-good' => 'インストーラは環境チェック中にインターネット接続を検出できた。
もし、求めるならば、この機能を有効にできる。',
	'config-cc-error' => 'クリエイティブ・コモンズ・ライセンスの選択器から結果が得られませんでした。
ライセンスの名前を手動で入力してください。',
	'config-cc-again' => 'もう一度選択してください...',
	'config-cc-not-chosen' => 'あなたの求めるクリエイティブコモンズのライセンスを選んで、"続行"をクリックしてください。',
	'config-advanced-settings' => '高度な設定',
	'config-cache-options' => 'オブジェクトのキャッシュの設定:',
	'config-cache-help' => 'オブジェクトのキャッシュは、使用したデータを頻繁にキャッシングすることによって、メディアウィキのスピード改善に使用されます。
中〜大サイトにおいては、これを有効にするために大変望ましいことです。また小さなサイトにおいても同様な利点をもたらすと考えられます。',
	'config-cache-none' => 'キャッシングしない(機能は取り払われます、しかもより大きなウィキサイト上でスピードの問題が発生します)',
	'config-memcached-servers' => 'メモリをキャッシュされたサーバ:',
	'config-extensions' => '拡張子',
	'config-extensions-help' => '上記のリストにある拡張子は<code>./extensions</code>ディレクトリ上で検出された。

これらは更に多くの設定を要求しているかもしれない、しかし今あなたはそれらを有効にできる。',
	'config-install-step-done' => '実行',
	'config-install-step-failed' => '失敗した',
	'config-install-database' => 'データベースの構築',
	'config-install-pg-schema-failed' => 'テーブルの作成に失敗した。
ユーザ"$1"が図式"$2"に書き込みができるようにしてください。',
	'config-install-user' => 'データベースユーザを作成する',
	'config-install-tables' => 'テーブルの作成',
	'config-install-interwiki-sql' => 'ファイル<code>interwiki.sql</code>を見つけることができませんでした。',
	'config-install-secretkey' => '秘密鍵を生成する',
	'config-install-sysop' => '管理者のユーザーアカウントを作成する',
);

/** Khmer (ភាសាខ្មែរ)
 * @author គីមស៊្រុន
 */
$messages['km'] = array(
	'config-show-help' => 'ជំនួយ',
	'config-hide-help' => 'លាក់ជំនួយ',
	'config-your-language' => 'ភាសារបស់អ្នក៖',
	'config-your-language-help' => 'ជ្រើសយកភាសាដើម្បីប្រើក្នុងពេលតំលើង។',
	'config-wiki-language' => 'ភាសាវិគី៖',
	'config-wiki-language-help' => 'ជ្រើសរើសភាសាដែលវិគីនេះប្រើជាចំបង។',
	'config-back' => '← ត្រលប់ក្រោយ',
	'config-continue' => 'បន្ត →',
	'config-page-language' => 'ភាសា',
	'config-page-welcome' => 'មេឌាវិគីសូមស្វាគមន៍!',
	'config-page-dbconnect' => 'ភ្ជាប់ទៅមូលដ្ឋានទិន្នន័យ',
	'config-page-name' => 'ឈ្មោះ',
	'config-page-options' => 'ជំរើស',
	'config-page-install' => 'តំលើង',
	'config-page-complete' => 'បញ្ចប់!',
	'config-page-restart' => 'តំលើងឡើងវិញ',
);

/** Colognian (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'config-desc' => 'Et Projramm för Mediwiki opzesäze.',
	'config-title' => 'MediaWiki $1 opsäze',
	'config-information' => 'Enfomazjuhn',
	'config-localsettings-upgrade' => "'''Opjepaß''': De Dattei <code>LocalSettings.php</code> es ald doh.
Di Projramme, di ald doh sin, künne op der neuste Shtand jebraat wääde.
Donn de <code>LocalSettings.php</code> aan enem seshere Plaz aflääje, un dann dat Projramm för et Opsäze norr_ens aanschmiiße.",
	'config-localsettings-noupgrade' => "'''Fähler''': De Dattei <code>LocalSettings.php</code> es ald doh.
Di Projramme, di ald doh sin, künne ävver nit op der neue Shtand jebraat wääde.
Dat Projramm för et Opsäze es zor Sesherheit afjeschalldt.",
	'config-session-error' => 'Ene Fähler es opjetrodde beim Aanmelde för en Sezung: $1',
	'config-session-expired' => 'De Daate för Ding Setzung sinn wall övverholld of afjeloufe.
De Setzungunge sin esu enjeshtallt, nit mieh wi $1 ze doore.
Dat kanns De verlängere, endämm dat De de <code lang="en">session.gc_maxlifetime</code> en dä Dattei <code>php.ini</code> jrüüßer määß.
Don dat Projramm för et Opsäze norr_ens aanschmiiße.',
	'config-no-session' => 'De Daate för Ding Setzung sinn verschött jejange.
Donn en dä Dattei <code>php.ini</code> nohloore, ov dä <code lang="en">session.save_path</code> op e zopaß Verzeijschneß zeisch.',
	'config-session-path-bad' => 'De Dattei uß däm <code lang="en">session.save_path</code>, dat es <code>$1</code>, schingk onjöltesch udder kappott ze sin, udder mer künne nit dren schriive.',
	'config-show-help' => 'Hölp',
	'config-hide-help' => 'Hölp afschallde',
	'config-your-language' => 'Ding Shprooch:',
	'config-your-language-help' => 'Donn heh di Shprooch ußsöhke, di dat Enshtallzjuhnsprojramm kalle sull.',
	'config-wiki-language' => 'Dem Wiki sing Shprooch:',
	'config-wiki-language-help' => 'Donn heh di Shprooch ußsöhke, di et Wiki shtandattmääßesch kalle sull.',
	'config-back' => '← Retuur',
	'config-continue' => 'Wigger →',
	'config-page-language' => 'Shprooch',
	'config-page-welcome' => 'Wellkumme bei MediaWiki!',
	'config-page-dbconnect' => 'Donn en Verbindung met dä Daatebangk maache',
	'config-page-upgrade' => 'En Inshtallzjuhn op der neuste Shtand bränge, di ald doh es',
	'config-page-dbsettings' => 'Parrameeter för de Daatebangk',
	'config-page-name' => 'Name',
	'config-page-options' => 'Ennställunge',
	'config-page-install' => 'Opsäzze',
	'config-page-complete' => 'Jedonn!',
	'config-page-restart' => 'Dat Opsäze norr_ens aanfange',
	'config-page-readme' => 'Donn mesch lässe! (<i lang="en">read me</i>)',
	'config-page-releasenotes' => 'Henwiiß för de Ußjaav',
	'config-page-copying' => 'Ben aam Kopeere',
	'config-page-upgradedoc' => 'Ben op der neuste Stand aam bränge',
	'config-help-restart' => 'Wells De all Ding enjejovve Sachee fottjeschmesse han, un dä janze Vörjang vun fürre aan neu aanfange?',
	'config-restart' => 'Joh, neu aanfange!',
	'config-welcome' => '=== Ömjevong Prööfe ===
Mer maache en Aanzal jrundlääje Prövunge, öm erus ze fenge, dat di Ömjevong heh paß, för Mediawiki opzesäze.
Do sullts aanjävve, wat erus kohm, wann de Hölp bem Opsäze bruchs.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki sing Hompäjdsch]
* [http://www.mediawiki.org/wiki/Help:Contents Handbooch för Aanwender]
* [http://www.mediawiki.org/wiki/Manual:Contents Handbooch för Administratore un Köbesse]
* [http://www.mediawiki.org/wiki/Manual:FAQ Wat öff jefrooch weed, un de Antwoote]',
	'config-env-good' => '<span class="success-message">De Ömjävung es jeprööf.
Do kanns MediaWiki opsäze.</span>',
	'config-env-bad' => 'De Ömjävung es jeprööf.
Do kanns MediaWiki nit opsäze.',
	'config-env-php' => 'PHP $1 es doh.',
	'config-env-latest-ok' => 'Do kriß de neuste Version vun Mediawiki opjesaz.',
	'config-env-latest-new' => "'''Opjepaß:''' Do kriß de Änwecklungsversion vun Mediawiki opjesaz.",
	'config-env-latest-can-not-check' => "'''Opjepaß:''' Mer kunnte kein Enfommazjuhne vun [$1] krijje övver de neuste Version vun Mediawiki.",
	'config-env-latest-old' => "'''Opjepaß:''' Mer sin en övverhollte Version vun Mediawiki aam opsäze!",
	'config-env-latest-help' => 'Mer donn jraad de Version $1 opsäze, ävver de neuste Version es $2.
Mer dääte vörschlonn, de neuste Versoin ze nämme, wann müjjelisch.
Di kam_mer vun [http://www.mediawiki.org/wiki/Download/de mediawiki.org] erunger laade.',
	'config-unicode-using-php' => 'För et <i lang="en">Unicode</i>-Nommaliseere nämme mer de lahme Ömsäzung en PHP.',
	'config-unicode-using-utf8' => 'För et <i lang="en">Unicode</i>-Nommaliseere dom_mer däm <i lang="en">Brion Vibber</i> sing Projramm <code lang="en">utf8_normalize.so</code> nämme.',
	'config-unicode-using-intl' => 'För et <i lang="en">Unicode</i>-Nommaliseere dom_mer dä [http://pecl.php.net/intl Zohsaz <code lang="en">intl</code> uss em <code lang="en">PECL</code>] nämme.',
	'config-unicode-pure-php-warning' => '\'\'\'Opjepaß:\'\'\' Mer kunnte dä [http://pecl.php.net/intl Zohsaz <code lang="en">intl</code> uss em <code lang="en">PECL</code>] för et <i lang="en">Unicode</i>-Nommaliseere nit fenge.
För jruuße Wikis met vill Metmaachere doht Üsch die Sigg övver et [http://www.mediawiki.org/wiki/Unicode_normalization_considerations <i lang="en">Unicode</i>-Nommaliseere] (es op Änglesch) aanloore.',
	'config-unicode-update-warning' => "'''Opjepaß:''' Dat Projramm för der <i lang=\"en\">Unicode</i> zo normaliseere boud em Momang op en  ählter Version vun dä Bibliothek vum [http://site.icu-project.org/ ICU-Projäk] op.
Doht di [http://www.mediawiki.org/wiki/Unicode_normalization_considerations op der neuste Shtand bränge], wann auf dat Wiki em Äänz <i lang=\"en\">Unicode</i> bruche sull.",
	'config-no-db' => 'Mer kunnte kei zopaß Daatebangk-Driiverprojamm fenge.',
	'config-no-db-help' => 'Mer bruche e Daatebangk-Driiverprojamm för PHP. Dat moß enjeresht wääde.
Mer künne met heh dä Daatebangke ömjonn: $1.

Wann De nit om eijene Rääshner bes, moß De Dinge <i lang="en">provider</i> bedde, dat hä Der ene zopaß Driiver enresht.
Wann de PHP sellver övversaz häs, donn ene Zohjang för en Daatebangk enbenge, för e Beishpell met: <code  lang="en">./configure --with-mysql</code> op ene <i lang="en">command shell</i>.
Wann De PHP uss enem <i lang="en">Debian</i> udder <i lang="en">Ubuntu</i> Pakätt enjeresht häs, moß De dann och noch et <code lang="en">php5-mysql</code> op Dinge Räschner bränge.',
	'config-have-db' => '{{PLURAL:$2|Ei Daatebangk-Driiverprojamm|$2 Daatebangk-Driiverprojamme|Kei Daatebangk-Driiverprojamm}} jevonge: $1.',
	'config-register-globals' => "'''Opjepaß:''' dem PHP singe Schallder <code lang=\"en\">[http://php.net/register_globals register_globals]</code> es enjeschalldt. 
'''Donn dä ußmaache, wann De kann.'''
MediaWiki löp och esu, dä künnt ävver Sesherheitslöcke opmaache, di mer noch nit jefonge un eruß jemaat hät.",
	'config-magic-quotes-runtime' => "'''Dä!''' Dem PHP singe Schallder <code lang=\"en\">[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]</code> es enjeschalldt.
Dä määt enjejovve Daate kapott, un doh draan kam_mer dann nix mieh repareere.
Domet kam_mer MediaWiki nit ennreeshte un och nit loufe lohße.
Dat heiß, mer moß en affschallde, söns jeiht nix.",
	'config-magic-quotes-sybase' => "'''Dä!''' Dem PHP singe Schallder <code lang=\"en\">[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]</code> es enjeschalldt.
Dä määt enjejovve Daate kapott, un doh draan kam_mer dann nix mieh repareere.
Domet kam_mer MediaWiki nit ennreeshte un och nit loufe lohße.
Dat heiß, mer moß en affschallde, söns jeiht nix.",
	'config-mbstring' => "'''Dä!''' Dem PHP singe Schallder <code lang=\"en\">[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]</code> es enjeschalldt.
Dat sorresch för Fähler un kann enjejovve Daate esu kapott maach, dat doh draan nix mieh ze repareere es.
Domet kam_mer MediaWiki nit ennreeshte un och nit loufe lohße.
Dat heiß, mer moß en affschallde, söns jeiht nix.",
	'config-ze1' => "'''Dä!''' Dem PHP singe Schallder <code lang=\"en\">[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]</code> es enjeschalldt.
Dat sorresch för schräcklejje Fähler em MediaWiki.
Dat kam_mer domet nit ennreeshte un och nit loufe lohße.
Dat heiß, mer moß en affschallde, söns jeiht nix.",
	'config-memory-none' => 'PHP es ohne dä Parameeter <code lang="en">memory_limit</code> ennjeresht.',
	'config-memory-ok' => 'Dem PHP singe Parameeter <code lang="en">memory_limit</code> es $1.
Joot esu.',
	'config-memory-raised' => 'Dem PHP singe Parameeter <code lang="en">memory_limit</code> es $1.
Op $2 erop jesaz.',
	'config-memory-bad' => "'''Opjepaß:''' Dem PHP singe Parameeter <code lang=\"en\">memory_limit</code> es \$1.
Dat es wall ze winnisch.
Et Enreeschte kunnt doh draan kappott jon!",
	'config-xcache' => 'Dä <code lang="en">[http://trac.lighttpd.net/xcache/ XCache]</code> es ennjeresht.',
	'config-apc' => 'Dä <code lang="en">[http://www.php.net/apc APC]</code> es ennjeresht.',
	'config-eaccel' => 'Dä <code lang="en">[http://eaccelerator.sourceforge.net/ eAccelerator]</code> es ennjeresht.',
	'config-wincache' => 'Dä <code lang="en">[http://www.iis.net/download/WinCacheForPhp WinCache]</code> es ennjeresht.',
	'config-no-cache' => '\'\'\'Opjepaß:\'\'\' Mer kunnte dä <code lang="en">[http://eaccelerator.sourceforge.net eAccelerator]</code>, dä <code lang="en">[http://www.php.net/apc APC]</code>, dä <code lang="en">[http://trac.lighttpd.net/xcache/ XCache]</code> un dä <code lang="en">[http://www.iis.net/download/WinCacheForPhp WinCache]</code> nit fenge.
Et <i lang="en">object caching</i> es nit müjjelesh un ußjeschalldt.',
	'config-diff3-good' => 'Han <i lang="en">GNU</i> <code lang="en">diff3</code> jefonge: <code lang="en">$1</code>',
	'config-diff3-bad' => 'Han <i lang="en">GNU</i> <code lang="en">diff3</code> nit jefonge.',
	'config-shell-locale' => 'Mer han de <i lang="en">locale</i> „$1“ för de <i lang="en">shell</i> jefonge',
	'config-db-type' => 'Zoot Daatebangk:',
	'config-db-host' => 'Dä Name vun däm Rääschner met dä Daatebangk:',
	'config-db-host-help' => 'If your Zoot Daatebangk: server is on different server, enter the host name or IP address here.

If you are using shared web hosting, your hosting provider should give you the correct host name in their documentation.',
	'config-db-name' => 'Dä Name vun dä Daatebangk:',
	'config-db-name-help' => 'Jiff ene Name aan, dä för Ding Wiki passe deiht.
Doh sullte kei Zweschrereum un kein Stresche dren sin.

Wann De nit op Dingem eije Rääschner bes, künnt et sin, dat Dinge Provaider Der ene extra Name för de Daatebangk jejovve hät, udder dat De Daatebangke övver e Fommulaa selver enreeschte moß.',
	'config-db-install-account' => 'Der Zohjang för en Enreeschte',
	'config-db-username' => 'Dä Name vun däm Aanwender för dä Zohjref op de Daatebangk:',
	'config-db-password' => 'Et Paßwoot vun däm Aanwender för dä Zohjref op de Daatebangk:',
	'config-db-install-help' => 'Donn dä Name un et Paßwoot vun däm Aanwänder för der Zohjreff op de Daatebangk jäz för et Enreeshte aanjävve.',
	'config-db-account-lock' => 'Donn dersälve Name un et sälve Paßwoot för der nomaale Bedrief vum Wiki bruche',
	'config-db-wiki-account' => 'Dä Name vun däm Aanwender för dä Zohjref op de Daatebangk em nomaale Bedrief:',
	'config-db-prefix' => 'Vörsaz för de Name vun de Tabälle en de Daatebangk:',
	'config-db-charset' => 'Dä Daatebangk iere Zeishesaz',
	'config-charset-mysql5-binary' => 'MySQL (4.1 udder 5.0) binär',
	'config-charset-mysql5' => 'MySQL (4.1 udder 5.0) UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 röckwääts kompatibel UTF-8',
	'config-mysql-old' => 'Mer bruche MySQL $1 udder neuer. Em Momang es MySQL $2 aam loufe.',
	'config-db-port' => 'De Pooz-Nommer (<i lang="en">port</i>) för de Daatebangk:',
	'config-db-schema' => 'Et Schema en de Datebangk för MediaWiki:',
	'config-db-ts2-schema' => 'Daateschema för <code lang="en">tsearch2</code>',
	'config-db-schema-help' => 'För jewöhnlesch sin bovven de Schemas en Odenong.
Donn bloß jät draan ändere, wann dat nüüdesch es.',
	'config-sqlite-dir' => '<i lang="en">SQLite<i> sing Daateverzeishnes:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'Enshtällunge för <i lang="en">MySQL<i>',
	'config-header-postgres' => 'Enshtällunge för <i lang="en">PostgreSQL<i>',
	'config-header-sqlite' => 'Enshtällunge för <i lang="en">SQLite</i>',
	'config-header-oracle' => 'Enshtällunge för <i lang="en">Oracle<i>',
	'config-invalid-db-type' => 'Dat es en onjöltijje Zoot Daatebangk.',
	'config-missing-db-name' => 'Do moß jät enjävve för dä Name vun dä Daatebangk.',
	'config-sqlite-name-help' => 'Kies een naam die uw wiki identificeert.
Gebruik geen spaties of koppeltekens.
Deze naam wordt gebruikt voor het Datendateinamen för <i lang="en">SQLite</i>.',
	'config-db-web-create' => 'Donn dä Zohjang aanlääje, wann dä noch nit doh es.',
	'config-mysql-engine' => 'De Zoot udder et Fommaat vun de Tabälle:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' es fö jewöhnlesch et beß, weil vill Zohjreffe op eijmohl joot ongershtöz wääde.

'''MyISAM''' es flöcker op Rääschnere met bloß einem Minsch draan, un bei Wikis, di mer bloß lässe un nit schrieeve kann.
MyISAM-Daatebangke han em Schnett mieh Fähler un jon flöcker kappott, wi InnoDB-Daatebangke.",
	'config-mysql-charset' => 'Dä Daatebangk iere Zeishesaz:',
	'config-mysql-binary' => 'binär',
	'config-site-name' => 'Däm Wiki singe Name:',
	'config-site-name-help' => 'Dä douch em Tittel vun de Brauserfinstere un aan ätlije andere Shtälle op.',
	'config-site-name-blank' => 'Donn ene Name för di Sait aanjävve.',
	'config-project-namespace' => 'Dä Name för et Appachtemang övver et Projäk:',
	'config-ns-generic' => 'Projäk',
	'config-ns-site-name' => 'Et sällve wi däm Wiki singe Name: $1',
	'config-ns-other' => 'Andere (jiff aan wälshe)',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'config-desc' => 'Den Installatiounsprogramm vu MediaWiki',
	'config-title' => 'MediaWiki $1 Installatioun',
	'config-information' => 'Informatioun',
	'config-localsettings-upgrade' => "'''Opgepasst''': E Fichier  <code>LocalSettings.php</code> gouf fonnt.
Är Software kann aktualiséiert ginn.
Réckelt w.e.g. <code>LocalSettings.php</code> op eng sécher Plaz a loosst dann den Installatiounsprogramm net emol lafen.",
	'config-localsettings-noupgrade' => "'''Feeler''': E Fichier <code>LocalSettings.php</code> gouf fonnt.
Är Software kann elo net aktualiséiert ginn.
Den Installatiounsprogramm gouf aus Sécherheetsgrënn ausgeschalt.",
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
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Haaptsäit]
* [http://www.mediawiki.org/wiki/Help:Contents Benotzerguide]
* [http://www.mediawiki.org/wiki/Manual:Contents Guide fir Administrateuren]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => '<span class="success-message">Den Environement gouf nogekuckt.
Dir kënnt MediaWiki installéieren.</span>',
	'config-env-bad' => 'Den Environnement gouf iwwerpréift.
Dir kënnt MediWiki net installéieren.',
	'config-env-php' => 'PHP $1 ass installéiert.',
	'config-env-latest-ok' => 'Dir installéiert déi rezenst Versioun vu MediaWiki.',
	'config-env-latest-new' => "'''Hiweis:''' Dir installéiert eng Entwécklungsversioun vu MediaWiki.",
	'config-env-latest-can-not-check' => "'''Opgepasst:''' Den Installatiounsprogramm konnt keng Informatioun iwwer déi leschte Versioun vu MediaWiki op [$1] ofrufen.",
	'config-env-latest-old' => "'''Warnung:''' Dir installéiert eng vereelste Versioun vu MediaWiki.",
	'config-env-latest-help' => "Dir installéiert d'Versioun $1, awer déi lescht Versioun ass $2.
Et gëtt geroden déi lescht Release ze benotzen, déi Dir vun [http://www.mediawiki.org/wiki/Download mediawiki.org] erofluede kënnt.",
	'config-no-db' => 'Et konnt kee passenden Datebank-Driver fonnt ginn!',
	'config-memory-none' => 'PHP ass ouni <code>memory_limit</code> configuréiert.',
	'config-memory-ok' => 'De PHP-Parameter <code>memory_limit</code> huet de Wäert $1.
OK.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] ass installéiert',
	'config-apc' => '[http://www.php.net/apc APC] ass installéiert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] ass installéiert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] ass installéiert',
	'config-diff3-bad' => 'GNU diff3 gouf net fonnt.',
	'config-no-uri' => "'''Feeler:''' Déi aktuell URI konnt net festgestallt ginn.
Installatioun ofgebrach.",
	'config-file-extension' => 'MediaWiki mat <code>$1</code> Fichiers-Erweiderungen installéieren',
	'config-db-type' => 'Datebanktyp:',
	'config-db-wiki-settings' => 'Dës Wiki identifizéieren',
	'config-db-name' => 'Numm vun der Datebank:',
	'config-db-install-account' => "Benotzerkont fir d'Installatioun",
	'config-db-username' => 'Datebank-Benotzernumm:',
	'config-db-password' => 'Passwuert vun der Datebank:',
	'config-db-install-help' => 'Gitt de Benotzernumm an Passwuert an dat wàhrend der Installatioun benotzt gëtt fir sech mat der Datebank ze verbannen.',
	'config-db-account-lock' => 'De selwechte Benotzernumm a Passwuert fir déi normal Operatioune benotzen',
	'config-db-wiki-account' => 'Benotzerkont fir normal Operatiounen',
	'config-db-charset' => 'Zeechesaz (character set) vun der Datebank',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binair',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'MySQL $1 oder eng méi nei Versioun gëtt gebraucht, Dir hutt $2.',
	'config-db-port' => 'Port vun der Datebank:',
	'config-db-schema' => 'Schema fir MediaWiki',
	'config-db-ts2-schema' => 'Schema fir tsearch2',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-Astellungen',
	'config-header-postgres' => 'PostgreSQL-Astellungen',
	'config-header-sqlite' => 'SQLite-Astellungen',
	'config-header-oracle' => 'Oracle-Astellungen',
	'config-invalid-db-type' => 'Net valabelen Datebank-Typ',
	'config-missing-db-name' => 'Dir musst en Numm fir de Wäert "Numm vun der Datebank" uginn',
	'config-postgres-old' => 'PostgreSQL $1 oder eng méi nei Versioun gëtt gebraucht, Dir hutt $2.',
	'config-sqlite-readonly' => 'An de Fichier <code>$1</code> Kann net geschriwwe ginn.',
	'config-sqlite-cant-create-db' => 'Den Datebank-Fichier <code>$1</code> konnt net ugeluecht ginn.',
	'config-db-web-account' => 'Datebankkont fir den Accès iwwer de Web',
	'config-db-web-account-same' => 'Dee selwechte Kont wéi bei der Installatioun benotzen',
	'config-db-web-create' => 'De Kont uleeë wann et e net scho gëtt',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'binär',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Numm vun der Wiki:',
	'config-site-name-help' => 'Dësen daucht an der Titelleescht vum Browser an op verschiddenen anere Plazen op.',
	'config-site-name-blank' => 'Gitt den Numm vum Site un.',
	'config-project-namespace' => 'Projet Nummraum:',
	'config-ns-generic' => 'Projet',
	'config-ns-site-name' => 'Deeselwechte wéi den Numm vun der Wiki: $1',
	'config-ns-other' => 'Anerer (spezifizéieren)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Administrateurs-Kont',
	'config-admin-name' => 'Ären Numm:',
	'config-admin-password' => 'Passwuert:',
	'config-admin-password-confirm' => 'Passwuert confirméieren:',
	'config-admin-name-blank' => 'Gitt e Benotzernumm fir den Administrateur an.',
	'config-admin-name-invalid' => 'De spezifizéierte Benotzernumm "<nowiki>$1</nowiki>" ass net valabel.
Spezifizéiert en anere Benotzernumm.',
	'config-admin-password-blank' => 'Gitt e Passwuert fir den Adminstateur-Kont an.',
	'config-admin-password-same' => "D'Passwuert däerf net dat selwecht si wéi de Benotzernumm.",
	'config-admin-password-mismatch' => 'Déi zwee Passwierder Déi dir aginn stëmmen net iwwerteneen.',
	'config-admin-email' => 'E-Mailadress:',
	'config-admin-error-user' => 'Interne Feeler beim uleeë vun engem Administrateur mam Numm "<nowiki>$1</nowiki>".',
	'config-almost-done' => "Dir sidd bal fäerdeg!
Dir kënnt elo déi Astellungen déi nach iwwreg sinn iwwersprangen an d'Wiki elo direkt installéieren.",
	'config-optional-continue' => 'Stellt mir méi Froen.',
	'config-optional-skip' => "Ech hunn es genuch, installéier just d'Wiki.",
	'config-profile' => 'Profil vun de Benotzerrechter:',
	'config-profile-wiki' => 'Traditionell Wiki',
	'config-profile-no-anon' => 'Uleeë vun engem Benotzerkont verlaangt',
	'config-profile-private' => 'Privat Wiki',
	'config-license' => 'Copyright a Lizenz:',
	'config-license-none' => 'Keng Lizenz ënnen op der Säit',
	'config-email-settings' => 'E-Mail-Astellungen',
	'config-enable-email' => 'E-Mailen déi no bausse ginn aschalten',
	'config-email-user' => 'Benotzer-op-Benotzer E-Mail aschalten',
	'config-email-usertalk' => 'Benoriichtege bäi Ännerung vun der Benotzerdiskussiounssäit aschalten',
	'config-email-watchlist' => 'Benoriichtigung vun der Iwwerwaachungslëscht aschalten',
	'config-email-auth' => 'E-Mail-Authentifizéierung aschalten',
	'config-email-sender' => 'E-Mailadress fir Äntwerten:',
	'config-upload-settings' => 'Eropgeluede Biller a Fichieren',
	'config-upload-enable' => 'Eropluede vu Fichieren aschalten',
	'config-upload-deleted' => 'Repertoire fir geläschte Fichieren:',
	'config-logo' => 'URL vum Logo:',
	'config-cc-again' => 'Nach eng kéier eraussichen...',
	'config-advanced-settings' => 'Erweidert Astellungen',
	'config-extensions' => 'Erweiderungen',
	'config-install-step-done' => 'fäerdeg',
	'config-install-step-failed' => 'huet net fonctionnéiert',
	'config-install-extensions' => 'Mat den Ereiderungen',
	'config-install-database' => 'Datebank gëtt installéiert',
	'config-install-user' => 'Datebank Benotzer uleeën',
	'config-install-tables' => 'Tabelle ginn ugeluecht',
	'config-install-interwiki' => 'Standard Interwiki-Tabell gëtt ausgefëllt',
	'config-install-interwiki-sql' => 'De Fichier <code>interwiki.sql</code> gouf net fonnt.',
	'config-install-secretkey' => 'Generéiere vum Geheimschlëssel',
	'config-install-sysop' => 'Administrateur Benotzerkont gëtt ugeluecht',
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
	'config-env-latest-can-not-check' => "'''Предупредување:''' Инсталаторот не можеше да добие информации за најновото издание на МедијаВики од [$1].",
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
	'config-have-db' => '{{PLURAL:$2|Пронајден двигател|Пронајдени двигатели}} за базата: $1.',
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

Ако користите заедничко (споделено) вдомување, тогаш вашиот вдомител треба да го доде точното име на домаќинот и неговата документација.

Ако инсталирате на опслужувач на Windows и користите MySQL, можноста „localhost“ може да не функционира за опслужувачкото име. Во тој случај, обидете се со внесување на „127.0.0.1“ како локална IP-адреса',
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
	'config-support-info' => 'МедијаВики ги поддржува следниве системи на бази на податоци:

$1

Ако системот што сакате да го користите не е наведен подолу, тогаш проследете ја горенаведената врска со инструкции за да овозможите поддршка за тој систем.',
	'config-support-mysql' => '* $1 е главната цел на МедијаВики и најдобро се поддржува ([http://www.php.net/manual/en/mysql.installation.php како се составува PHP со поддршка за MySQL])',
	'config-support-postgres' => '* $1 е популарен систем на бази на податоци со отворен код кој претставува алтернатива за MySQL ([http://www.php.net/manual/en/pgsql.installation.php како да составите PHP со поддршка за PostgreSQL])',
	'config-support-sqlite' => '* $1 е лесен систем за бази на податоци кој е многу добро поддржан. ([http://www.php.net/manual/en/pdo.installation.php Како да составите PHP со поддршка за SQLite], користи PDO)',
	'config-header-mysql' => 'Нагодувања на MySQL',
	'config-header-postgres' => 'Нагодувања на PostgreSQL',
	'config-header-sqlite' => 'Нагодувања на SQLite',
	'config-header-oracle' => 'Нагодувања на Oracle',
	'config-invalid-db-type' => 'Неважечки тип на база',
	'config-missing-db-name' => 'Мора да внесете значење за параметарот „Име на базата“',
	'config-invalid-db-name' => 'Неважечко име на базата „$1“.
Може да содржи само бројки, букви и долни црти.',
	'config-invalid-db-prefix' => 'Неважечки префикс за база „$1“.
Може да содржи само бројки, букви и долни црти.',
	'config-connection-error' => '$1.

Проверете го долунаведениот домаќин, корисничко име и лозинка и обидете се повторно.',
	'config-invalid-schema' => 'Неважечка шема за МедијаВики „$1“.
Користете само букви, бројки и долни црти.',
	'config-invalid-ts2schema' => 'Неважечка шема за TSearch2 „$1“.
Користете само букви, бројки и долни црти.',
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
	'config-mysql-egine-mismatch' => "'''Предупредување:''' го побаравте складишниот погон $1, но постоечката база на податоци го користи погонот $2.
Оваа надградбена скрипта не може да го претвори, и затоа ќе остане на $2.",
	'config-mysql-charset' => 'Збир знаци за базата:',
	'config-mysql-binary' => 'Бинарен',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Во '''бинарен режим''', во базата на податоци МедијаВики складира UTF-8 текст во бинарни полиња.
Ова е поефикасно отколку  TF-8 режимот на MySQL, и ви овозможува да ја користите целата палета на уникодни знаци.

Во '''UTF-8 режим''', MySQL ќе знае на кој збир знаци припаѓаат вашите податоци, и може соодветно да ги претстави и претвори, но нема да ви дозволи да складиратезнаци над [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Основната повеќејазична рамнина].",
	'config-mysql-charset-mismatch' => "'''Предупредување:''' ја побаравте шемата $1, но постоечката база на податоци ја има шемата $2.
Оваа надградбена скрипта не може да ја претвори, па затоа ќе остане на $2.",
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

'''{{int:config-profile-wiki}}''' — секој може да го уредува, дури и без најавување.
Ако имате вики со '''задолжително отворање на сметка''', тогаш добивате повеќе контрола, но ова може даги одврати спонтаните учесници.

'''{{int:config-profile-fishbowl}}''' — може да уредуваат само уредници што имаат добиено дозвола за тоа, но јавноста може да ги гледа страниците, вклучувајќи ја нивната историја.
'''{{int:config-profile-private}}''' — страниците се видливи и уредливи само за овластени корисници.

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
	'config-logo-help' => 'Матичното руво на МедијаВики има простор за лого од 135x160 пиксели во горниот лев агол.
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
 * @author McDutchie
 * @author Siebrand
 */
$messages['nl'] = array(
	'config-desc' => 'Het installatieprogramma voor MediaWiki',
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
	'config-copyright' => "=== Auteursrechten en voorwaarden ===

$1

Dit programma is vrije software. U mag het verder verspreiden en/of aanpassen in overeenstemming met de voorwaarden van de GNU General Public License zoals uitgegeven door de Free Software Foundation; ofwel versie 2 van de Licentie of - naar uw keuze - enige latere versie.

Dit programma wordt verspreid in de hoop dat het nuttig is, maar '''zonder enige garantie''', zelfs zonder de impliciete garantie van '''verkoopbaarheid''' of '''geschiktheid voor een bepaald doel'''.
Zie de GNU General Public License voor meer informatie. 

Samen met dit programma hoort u een <doclink href=Copying>exemplaar van de GNU General Public License</doclink> ontvangen te hebben; zo niet, schrijf dan aan de Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, Verenigde Staten. Of [http://www.gnu.org/copyleft/gpl.html lees de licentie online].",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki thuispagina]
* [http://www.mediawiki.org/wiki/Help:Contents Gebruikershandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:Contents Beheerdershandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:FAQ Veel gestelde vragen] (Engelstalig)',
	'config-env-good' => '<span class="success-message">De omgeving is gecontroleerd.
U kunt MediaWiki installeren.</span>',
	'config-env-bad' => 'De omgeving is gecontroleerd.
U kunt MediaWiki niet installeren.',
	'config-env-php' => 'PHP $1 is op dit moment geïnstalleerd.',
	'config-env-latest-ok' => 'U bent bezig de meest recente versie van MediaWiki te installeren.',
	'config-env-latest-new' => "'''Let op:''' U bent bezig een ontwikkelversie van MediaWiki te installeren.",
	'config-env-latest-can-not-check' => "'''Waarschuwing:''' het installatieprogramma was niet in staat om informatie over de nieuwste release van MediaWiki op te halen van [$1].",
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
	'config-have-db' => 'Gevonden {{PLURAL:$2|databasedriver|databasedrivers}}: $1.',
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
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] is op dit moment geïnstalleerd',
	'config-apc' => '[http://www.php.net/apc APC] is op dit moment geïnstalleerd',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] is op dit moment  geïnstalleerd',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] is op dit moment geïnstalleerd',
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

Als u gebruik maakt van gedeelde webhosting, hoort uw provider u de juiste hostnaam te hebben verstrekt.

Als u MediaWiki op een Windowsserver installeert en MySQL gebruikt, dan werkt "localhost" mogelijk niet als servernaam.
Als het inderdaad niet werkt, probeer dan "127.0.0.1" te gebruiken als lokaal IP-adres.',
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
Dit is efficiënter dan de UTF-8-modus van MySQL en stelt u in staat de volledige reeks Unicode-tekens te gebruiken.
In '''UTF-8-modus''' kent MySQL de tekenset van uw gegevens en kan de databaseserver ze juist weergeven en converteren.
Het is dat niet mogelijk tekens op te slaan die de \"[http://nl.wikipedia.org/wiki/Lijst_van_Unicode-subbereiken#Basic_Multilingual_Plane Basic Multilingual Plane]\" te boven gaan.",
	'config-mysql-old' => 'U moet MySQL $1 of later gebruiken.
U gebruikt $2.',
	'config-db-port' => 'Databasepoort:',
	'config-db-schema' => 'Schema voor MediaWiki',
	'config-db-ts2-schema' => 'Schema voor tsearch2',
	'config-db-schema-help' => "De bovenstaande schema's kloppen meestal.
Wijzig ze alleen als u weet dat dit nodig is.",
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
	'config-support-info' => 'MediaWiki ondersteunt de volgende databasesystemen:

$1

Als u het databasesysteem dat u wilt gebruiken niet in de lijst terugvindt, volg dan de handleiding waarnaar hierboven wordt verwezen om ondersteuning toe te voegen.',
	'config-support-mysql' => '* $1 is het primaire databasesysteem voor voor MediaWiki en wordt het best ondersteund ([http://www.php.net/manual/en/mysql.installation.php hoe PHP gecompileerd moet zijn met ondersteuning voor MySQL])',
	'config-support-postgres' => '* $1 is een populair open source databasesysteem als alternatief voor MySQL ([http://www.php.net/manual/en/pgsql.installation.php hoe PHP gecompileerd moet zijn met ondersteuning voor PostgreSQL])',
	'config-support-sqlite' => '* $1 is een zeer goed ondersteund lichtgewicht databasesysteem ([http://www.php.net/manual/en/pdo.installation.php hoe PHP gecompileerd zijn met ondersteuning voor SQLite]; gebruikt PDO)',
	'config-header-mysql' => 'MySQL-instellingen',
	'config-header-postgres' => 'PostgreSQL-instellingen',
	'config-header-sqlite' => 'SQLite-instellingen',
	'config-header-oracle' => 'Oracle-instellingen',
	'config-invalid-db-type' => 'Ongeldig databasetype',
	'config-missing-db-name' => 'U moet een waarde ingeven voor "Databasenaam"',
	'config-invalid-db-name' => 'Ongeldige databasenaam "$1".
Gebruiker alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
	'config-invalid-db-prefix' => 'Ongeldig databasevoorvoegsel "$1".
Gebruiker alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
	'config-connection-error' => '$1.

Controleer de host, gebruikersnaam en wachtwoord hieronder in en probeer het opnieuw.',
	'config-invalid-schema' => 'Ongeldig schema voor MediaWiki "$1".
Gebruiker alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
	'config-invalid-ts2schema' => 'Ongeldig schema voor TSearch2 "$1".
Gebruiker alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
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
	'config-mysql-egine-mismatch' => "'''Waarschuwing:''' u wilt de opslagwijze $1 gebruiken, maar de bestaande database gebruikt de opslagwijze $2.
Dit upgradescript kan de opslagwijze niet converteren, dus het blijft $2.",
	'config-mysql-charset' => 'Tekenset voor de database:',
	'config-mysql-binary' => 'Binair',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "In '''binaire modus''' slaat MediaWiki tekst in UTF-8 op in binaire databasevelden.
Dit is efficiënter dan de UTF-8-modus van MySQL en stelt u in staat de volledige reeks Unicode-tekens te gebruiken.

In '''UTF-8-modus''' kent MySQL de tekenset van uw gegevens en kan de databaseserver ze juist weergeven en converteren.
Het is dat niet mogelijk tekens op te slaan die de \"[http://nl.wikipedia.org/wiki/Lijst_van_Unicode-subbereiken#Basic_Multilingual_Plane Basic Multilingual Plane]\" te boven gaan.",
	'config-mysql-charset-mismatch' => "'''Waarschuwing:''' u wilt het schema $1 gebruiken, maar de bestaande database gebruikt het schema $2.
Dit upgradescript kan het schema niet converteren, dus het blijft $2.",
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
	'config-optional-continue' => 'Stel me meer vragen.',
	'config-optional-skip' => 'Laat dat maar, installeer gewoon de wiki.',
	'config-profile' => 'Gebruikersrechtenprofiel:',
	'config-profile-wiki' => 'Traditionele wiki',
	'config-profile-no-anon' => 'Gebruiker aanmaken verplicht',
	'config-profile-fishbowl' => 'Alleen voor geautoriseerde bewerkers',
	'config-profile-private' => 'Privéwiki',
	'config-profile-help' => "Wiki's werken het beste als ze door zoveel mogelijk gebruikers worden bewerkt.
In MediaWiki is het eenvoudig om de recente wijzigingen te controleren en eventuele foutieve of kwaadwillende bewerkingen terug te draaien.

Daarnaast vinden velen MediaWiki goed inzetbaar in vele andere rollen, en soms is het niet handig om helemaal \"op de wikimanier\" te werken.
Daarom biedt dit installatieprogramma u de volgende keuzes voor de basisinstelling van gebruikersvrijheden:

Een '''{{int:config-profile-wiki}}''' staat iedereen toe te bewerken, zonder zelfs aan te melden.
Een wiki met '''{{int:config-profile-no-anon}}\" biedt extra verantwoordelijkheid, maar kan afschrikken toevallige gebruikers afschrikken.

Het scenario '''{{int:config-profile-fishbowl}}''' laat gebruikers waarvoor dat is ingesteld bewerkt, maar andere gebruikers kunnen alleen pagina's bekijken, inclusief de bewerkingsgeschiedenis.
In een '''{{int:config-profile-private}}''' kunnen alleen goedgekeurde gebruikers pagina's bekijken en bewerken.

Meer complexe instellingen voor gebruikersrechten zijn te maken na de installatie; hierover is meer te lezen in de [http://www.mediawiki.org/wiki/Manual:User_rights handleiding].",
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
	'config-logo-help' => 'Het standaarduiterlijk van MediaWiki bevat ruimte voor een logo van 135x160 pixels in de linker bovenhoek.
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

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'config-information' => 'Informasjon',
	'config-show-help' => 'Hjelp',
	'config-hide-help' => 'Skjul hjelp',
	'config-your-language' => 'Ditt språk:',
	'config-wiki-language' => 'Wikispråk:',
	'config-back' => '← Tilbake',
	'config-continue' => 'Fortsett →',
	'config-page-language' => 'Språk',
	'config-page-welcome' => 'Velkommen til MediaWiki!',
	'config-page-dbconnect' => 'Koble til database',
	'config-page-upgrade' => 'Oppgrader eksisterende innstallasjon',
	'config-page-dbsettings' => 'Databaseinnstillinger',
	'config-page-name' => 'Navn',
	'config-page-options' => 'Valg',
	'config-page-install' => 'Innstaller',
	'config-page-complete' => 'Ferdig!',
	'config-page-readme' => 'Les meg',
	'config-page-copying' => 'Kopiering',
	'config-page-upgradedoc' => 'Oppgradering',
	'config-authors' => 'MediaWiki er opphavsrettslig beskyttet © 2001-2010 av Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe og andre.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki hjem]
* [http://www.mediawiki.org/wiki/Help:Contents Brukerguide]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratorguide]
* [http://www.mediawiki.org/wiki/Manual:FAQ OSS]',
	'config-env-php' => 'PHP $1 er innstallert.',
	'config-env-latest-ok' => 'Du innstallerer den siste versjonen av MediaWiki.',
	'config-env-latest-new' => "'''Merk:''' Du innstallerer en utviklerversjon av MediaWiki.",
	'config-env-latest-old' => "'''Advarsel:''' Du innstallerer en utdatert versjon av MediaWiki.",
	'config-have-db' => 'Fant {{PLURAL:$2|en databasedriver|databasedrivere}}: $1.',
	'config-xml-good' => 'Har konverteringsstøtte for XML / Latin1-UTF-8.',
	'config-memory-ok' => 'PHPs <code>memory_limit</code> er $1.
OK.',
	'config-memory-raised' => 'PHPs <code>memory_limit</code> er $1, økt til $2.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] er innstallert',
	'config-apc' => '[http://www.php.net/apc APC] er innstallert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] er innstallert',
	'config-diff3-good' => 'Fant GNU diff3: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 ikke funnet.',
	'config-dir' => 'Installasjonsmappe: <code>$1</code>.',
	'config-db-name' => 'Databasenavn:',
	'config-db-username' => 'Databasebrukernavn:',
	'config-db-password' => 'Databasepassord:',
	'config-db-charset' => 'Databasetegnsett',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binær',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'MySQL $1 eller senere kreves, du har $2.',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-header-mysql' => 'MySQL-innstillinger',
	'config-header-postgres' => 'PostgreSQL-innstillinger',
	'config-header-sqlite' => 'SQLite-innstillinger',
	'config-header-oracle' => 'Oracle-innstillinger',
	'config-invalid-db-type' => 'Ugyldig databasetype',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Databasetegnsett:',
	'config-mysql-binary' => 'Binær',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name-blank' => 'Skriv inn et nettstedsnavn.',
	'config-project-namespace' => 'Prosjektnavnerom:',
	'config-ns-generic' => 'Prosjekt',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Administratorkonto',
	'config-admin-name' => 'Ditt navn:',
	'config-admin-password' => 'Passord:',
	'config-admin-password-confirm' => 'Passord igjen:',
	'config-admin-email' => 'E-postadresse:',
);

/** Polish (Polski)
 * @author Sp5uhe
 */
$messages['pl'] = array(
	'config-desc' => 'Instalator MediaWiki',
	'config-title' => 'Instalacja MediaWiki $1',
	'config-information' => 'Informacja',
	'config-localsettings-upgrade' => "'''Uwaga!''' Wykryto, że plik <code>LocalSettings.php</code> istnieje.
Oprogramowanie może zostać zaktualizowane.
Przenieś plik <code>LocalSettings.php</code> w bezpieczne miejsce i uruchom instalator ponownie.",
	'config-localsettings-noupgrade' => "'''Błąd!''' Wykryto, że plik <code>LocalSettings.php</code> istnieje.
Oprogramowanie w tym momencie nie może zostać zaktualizowane.
Instalator został wyłączony ze względów bezpieczeństwa.",
	'config-session-error' => 'Błąd uruchomienia sesji – $1',
	'config-session-expired' => 'Wygląda na to, że Twoja sesja wygasła.
Czas życia sesji został skonfigurowany na $1.
Możesz go wydłużyć zmieniając <code>session.gc_maxlifetime</code> w pliku php.ini.
Uruchom ponownie proces instalacji.',
	'config-no-session' => 'Dane sesji zostały utracone.
Sprawdź plik php.ini i upewnij się, że <code>session.save_path</code> wskazuje na odpowiedni katalog.',
	'config-session-path-bad' => 'Wartość <code>session.save_path</code> (<code>$1</code>) jest nieprawidłowa lub brak możliwości zapisu do tego katalogu,',
	'config-show-help' => 'Pomoc',
	'config-hide-help' => 'Ukryj pomoc',
	'config-your-language' => 'Język',
	'config-your-language-help' => 'Wybierz język używany podczas procesu instalacji.',
	'config-wiki-language' => 'Język wiki',
	'config-wiki-language-help' => 'Wybierz język, w którym będzie tworzona większość treści wiki',
	'config-back' => '← Wstecz',
	'config-continue' => 'Dalej →',
	'config-page-language' => 'Język',
	'config-page-welcome' => 'Witamy w MediaWiki!',
	'config-page-dbconnect' => 'Połączenie z bazą danych',
	'config-page-upgrade' => 'Uaktualnienie istniejącej instalacji',
	'config-page-dbsettings' => 'Ustawienia bazy danych',
	'config-page-name' => 'Nazwa',
	'config-page-options' => 'Opcje',
	'config-page-install' => 'Instaluj',
	'config-page-complete' => 'Zakończono!',
	'config-page-restart' => 'Ponowne uruchomienie instalacji',
	'config-page-readme' => 'Podstawowe informacje',
	'config-page-releasenotes' => 'Informacje o wersji',
	'config-page-copying' => 'Kopiowanie',
	'config-page-upgradedoc' => 'Uaktualnienie',
	'config-help-restart' => 'Czy chcesz usunąć wszystkie zapisane dane, które podałeś i uruchomić ponownie proces instalacji?',
	'config-restart' => 'Tak, zacznij od nowa',
	'config-welcome' => '=== Sprawdzenie środowiska instalacji ===
Wykonywane są podstawowe testy sprawdzające czy to środowisko jest odpowiednie dla instalacji MediaWiki. 
Jeśli potrzebujesz pomocy podczas instalacji załącz wyniki tych testów.',
	'config-authors' => 'Copyright © 2001‐2010 – autorskie prawa majątkowe do oprogramowania MediaWiki należą do: Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason, Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor, Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber, Siebrand Mazeland, Chad Horohoe oraz innych współautorów.',
	'config-sidebar' => '* [http://www.mediawiki.org Strona domowa MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Podręcznik użytkownika]
* [http://www.mediawiki.org/wiki/Manual:Contents Podręcznik administratora]
* [http://www.mediawiki.org/wiki/Manual:FAQ Odpowiedzi na często zadawane pytania]',
	'config-env-good' => '<span class="success-message">Środowisko oprogramowania zostało sprawdzone.
Możesz teraz zainstalować MediaWiki.</span>',
	'config-env-bad' => 'Środowisko oprogramowania zostało sprawdzone.
Nie możesz zainstalować MediaWiki.',
	'config-env-php' => 'Zainstalowane jest PHP w wersji $1.',
	'config-env-latest-ok' => 'Instalujesz najnowszą wersję oprogramowania MediaWiki.',
	'config-env-latest-new' => "'''Uwaga!''' Instalujesz roboczą wersję oprogramowania MediaWiki.",
	'config-env-latest-can-not-check' => "'''Uwaga –''' instalator nie może pobrać informacji o najnowszej wersji MediaWiki z [$1].",
	'config-env-latest-old' => "'''Uwaga!''' Instalujesz nieaktualną wersję MediaWiki.",
	'config-env-latest-help' => 'Instalujesz wersję $1, a najnowsza wersja to $2.
Zaleca się pobranie najnowszej wersji z [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Korzystanie z powolnej implementacji w PHP normalizacji Unicode.',
	'config-unicode-using-utf8' => 'Korzystanie z normalizacji Unicode utf8_normalize.so napisanej przez Brion Vibbera.',
	'config-unicode-using-intl' => 'Korzystanie z [http://pecl.php.net/intl rozszerzenia intl PECL] do normalizacji Unicode.',
	'config-unicode-pure-php-warning' => "'''Uwaga!''' [http://pecl.php.net/intl Rozszerzenie intl PECL] do obsługi normalizacji Unicode nie jest dostępne.
Jeśli prowadzisz stronę o dużym natężeniu ruchu, powinieneś zapoznać się z informacjami o [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalizacji Unicode].",
	'config-no-db' => 'Nie można odnaleźć właściwego sterownika bazy danych!',
	'config-no-db-help' => 'Należy zainstalować sterownik bazy danych dla PHP.
Obsługiwane są następujące typy baz danych: $1. 

Jeżeli korzystasz ze współdzielonego hostingu, zwróć się do administratora o zainstalowanie odpowiedniego sterownika bazy danych. 
Jeśli skompilowałeś PHP samodzielnie, skonfiguruj je ponownie z włączonym klientem bazy danych, na przykład za pomocą polecenia
<code>./configure --with-mysql</code>. 
Jeśli zainstalowałeś PHP jako pakiet Debiana lub Ubuntu, musisz również zainstalować moduł php5-mysql.',
	'config-have-db' => 'Odnaleziono {{PLURAL:$2|sterownik bazy danych|sterowniki bazy danych:}} $1.',
	'config-mysql-engine' => 'Silnik bazy danych',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Zestaw znaków bazy danych',
	'config-mysql-binary' => 'binarny',
	'config-mysql-utf8' => 'UTF‐8',
	'config-site-name' => 'Nazwa wiki',
);

/** Piedmontese (Piemontèis)
 * @author Dragonòt
 */
$messages['pms'] = array(
	'config-desc' => "L'instalador për mediaWiki",
	'config-title' => 'Anstalassion ëd MediaWiki $1',
	'config-information' => 'Anformassion',
	'config-localsettings-upgrade' => "'''Avis''': Un file <code>LocalSettings.php</code> a l'é stàit trovà.
Tò software a peul esse agiornà.
Për piasì tramuda <code>LocalSettings.php</code> an quaich pòst sigur e peui fà giré torna l'instalador.",
	'config-localsettings-noupgrade' => "'''Eror''': Un file <code>LocalSettings.php</code> a l'é stàit trovà.
Tò software a peul pa esse agiornà al moment.
L'instalador a l'é stàit disabilità për rason ëd sicurëssa.",
	'config-session-error' => 'Eror an fasend parte la session: $1',
	'config-session-expired' => "Ij tò dat ëd session a smijo spirà.
Le session a son configurà për na durà ëd $1.
It peule aumenté sossì an ampostand <code>session.gc_maxlifetime</code> an php.ini.
Fa riparte ël process d'instalassion.",
	'config-no-session' => 'Ij tò dat ëd session a son përdù!
Contròla tò php.ini e sigurte che <code>session.save_path</code> a sia ampostà ant la directory aproprià.',
	'config-session-path-bad' => 'Tò <code>session.save_path</code> (<code>$1</code>) a smija esse pa bon o pa scrivìbil.',
	'config-show-help' => 'Agiut',
	'config-hide-help' => 'Stërma agiut',
	'config-your-language' => 'Toa lenga:',
	'config-your-language-help' => "Selession-a na lenga da dovré an mente dël process d'instalassion.",
	'config-wiki-language' => 'Lenga dla Wiki:',
	'config-wiki-language-help' => 'Selession-a la lenga dont la wiki a sarà prevalentement scrivùa.',
	'config-back' => '← André',
	'config-continue' => 'Continua →',
	'config-page-language' => 'Lenga',
	'config-page-welcome' => 'Bin  ëvnù a MediaWiki!',
	'config-page-dbconnect' => 'Coleghte al database',
	'config-page-upgrade' => 'Agiorna instalassion esistente',
	'config-page-dbsettings' => 'Ampostassion dël database',
	'config-page-name' => 'Nòm',
	'config-page-options' => 'Opsion',
	'config-page-install' => 'Instala',
	'config-page-complete' => 'Completa!',
	'config-page-restart' => "Fa riparte l'instalassion",
	'config-page-readme' => 'Lesme',
	'config-page-releasenotes' => 'Nòte ëd publicassion',
	'config-page-copying' => 'Copié',
	'config-page-upgradedoc' => 'Agiorné',
	'config-help-restart' => "Veus-to scanselé tùit ij dat salvà ch'it l'has anserì e fé riparte ël process d'instalassion?",
	'config-restart' => 'É!, falo riparte',
	'config-welcome' => "=== Contròj d'ambient ===
A son fàit dij contròj base për vëdde se sto ambient a l'é pront për l'instalassion ëd MediaWiki.
It dovrìe dé j'arzultà dë sti contròj s'it l'has dabzògn d'agiut ant mente dl'instalassion.",
	'config-copyright' => "=== Drit d'Autor e Termo ===

$1

Sto program a l'é software lìber: it peule redistribuilo e/o modifichelo sota ij termo dla GNU General public License com publicà da la Free Software Foundation; sia la vërsion 2 dla Licensa, o (a toa sërnìa) minca vërsion pi tarda.

Sto program a l'é distribuì ant la speransa ch'a sia ùtil, ma '''sensa minca garansia'''; sensa ëdcò la garansia implicita ëd  '''comerciabilità''' o '''idoneità a un but particolar'''.

It dovrìe avèj arseivù <doclink href=Copying>na còpia ëd la GNU General Public License</doclink> ansema a sto program; dasnò, scriv a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. o [http://www.gnu.org/copyleft/gpl.html lesla an linia].",
	'config-sidebar' => "* [http://www.mediawiki.org MediaWiki home]
* [http://www.mediawiki.org/wiki/Help:Contents Guida dl'Utent]
* [http://www.mediawiki.org/wiki/Manual:Contents Giuda dl'Aministrador]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]",
	'config-env-good' => '<span class="success-message">L\'ambient a l\'é stàit controlà.
It peule instalé MediaWiki.</span>',
	'config-env-bad' => "L'ambient a l'é stàit controlà.
It peule pa instalé MediaWiki.",
	'config-env-php' => "PHP $1 a l'é instalà.",
	'config-env-latest-ok' => "It të stas instaland l'ùltima vërsion ëd MediaWiki.",
	'config-env-latest-new' => "'''Nòta:''' It të stas instaland na vërsion dë svilup ëd MediaWiki.",
	'config-env-latest-can-not-check' => "'''Avis:''' L'instalador a l'ha pa podù trové anformassion a propòsit ëd l'ùltima vërsion ëd MediaWiki da [$1].",
	'config-env-latest-old' => "'''Avis:''' It të stas instaland na vërsion veja ëd MediaWiki.",
	'config-env-latest-help' => "It të stas instaland la vërsion $1, ma l'ùltima vërsion a l'é $2.
It ses avisà ëd dovré l'ùltima vërsion, che a peul esse dëscarià da [http://www.mediawiki.org/wiki/Download mediawiki.org]",
	'config-unicode-using-php' => "Dovré l'implementassion PHP andormìa për la normalisassion Unicode.",
	'config-unicode-using-utf8' => 'Dovré utf8_normalize.so ëd Brion Vibber për la normalisassion Unicode.',
	'config-unicode-using-intl' => "Dovré l'[http://pecl.php.net/intl estension intl PECL] për la normalisassion Unicode.",
	'config-unicode-pure-php-warning' => "'''Avis:''' L'[http://pecl.php.net/intl estension intl PECL] a l'é pa disponibla për gestì la normalisassion Unicode.
S'it fas giré un sit a àut tràfich, it dovrìe lese un pòch an sla [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalisassion Unicode].",
	'config-unicode-update-warning' => "'''Avis:''' La vërsion instalà dël wrapper ëd normalisassion Unicode a dòvra na veja vërsion ëd la librerìa dël [http://site.icu-project.org/ proget ICU].
It dovrìe [http://www.mediawiki.org/wiki/Unicode_normalization_considerations agiorné] s'it ses antëressà a propòsit ëd dovré Unicode.",
	'config-no-db' => 'As peul pa trovesse un driver ëd database adat!',
	'config-no-db-help' => "It deuve instalé un driver ëd database për PHP.
A son apogià le sòrt ëd database ch'a ven-o: $1.

S'it ses su un host condivis, ciama al provider ëd tò host d'instalé un driver ëd database adat.
S'it l'has compilà ti midem PHP, reconfigurlo con un client ëd database abilità, për esempi an dovrand <code>./configure --with-mysql</code>.
S'it l'has instalà PHP da un pachet Debian o Ubuntu, antlora it deuve ëdcò instalé ël mòdul php5-mysql.",
	'config-have-db' => 'Trovà {{PLURAL:$2|driver|driver}} ëd database: $1.',
	'config-register-globals' => "'''Avis: L'opsion <code>[http://php.net/register_globals register_globals]</code> ëd PHP a l'é abilità.'''
'''Disabìlitlo s'it peule.'''
MediaWiki a travajerà, ma tò server a l'é espòst a possìbij vunerabilità ëd sicurëssa.",
	'config-magic-quotes-runtime' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] a l'é ativ!'''
Sta opsion a danegia ij dat d'input an manera pa prevedibla.
It peule pa instalé o dovré MediaWiki a men che sta opsion a sia disabilità.",
	'config-magic-quotes-sybase' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] a l'é ativ!'''
Sta opsion a danegia ij dat d'input an manera pa prevedibla.
It peule pa instalé o dovré MediaWiki a men che sta opsion a sia disabilità.",
	'config-mbstring' => "'''Fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] a l'é ativ!'''
Sta opsion a causa d'eror e a peul danegié ij dat d'input an manera pa prevedibla.
It peule pa instalé o dovré MediaWiki a men che sta opsion a sia disabilità.",
	'config-ze1' => "'''Fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] a l'é ativ!'''
Sta opsion a causa eror orìbij con MediaWiki.
It peule pa instalé o dovré MediaWiki a men che sta opsion a sia disabilità.",
	'config-safe-mode' => "'''Avis:''' [http://www.php.net/features.safe-mode Safe mode] ëd PHP a l'é ativ.
A peul causé ëd problem, dzortut s'as dòvra carie ëd file e apògg ëd <code>math</code>.",
	'config-xml-good' => "Pija l'apògg ëd conversion XML / Latin1-UTF-8.",
	'config-xml-bad' => "Mòdul XML ed PHP mancant.
MediaWiki a ciama le funsion an sto mòdul e a travajerà pa an sta configurassion.
S'it fas giré mandrake, instala ël pachet php-xml.",
	'config-pcre' => "A smija che ël mòdul d'apògg PCRE a sia mancant.
MediaWiki a ciama le funsion dle espression regolar Perl-compatìbij për travajé.",
	'config-memory-none' => "PHP a l'é configurà con gnun <code>memory_limit</code>",
	'config-memory-ok' => "<code>memory_limit</code> ëd PHP a l'é $1.
OK.",
	'config-memory-raised' => "<code>memory_limit</code> ëd PHP a l'é $1, aussà a $2.",
	'config-memory-bad' => "'''Avis:''' <code>memory_limit</code> ëd PHP a l'é $1.
Sossì a l'é probabilment tròp bass.
L'instalassion a peul falì!",
	'config-xcache' => "[http://trac.lighttpd.net/xcache/ XCache] a l'é instalà",
	'config-apc' => "[http://www.php.net/apc APC] a l'é instalà",
	'config-eaccel' => "[http://eaccelerator.sourceforge.net/ eAccelerator] a l'é instalà",
	'config-wincache' => "[http://www.iis.net/download/WinCacheForPhp WinCache]  a l'é instalà",
	'config-no-cache' => "'''Avis:''' As treuva pa [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] o [http://www.iis.net/download/WinCacheForPhp WinCache]. L'object caching a l'é pa abilità.",
	'config-diff3-good' => 'Trovà GNU diff3: <code>$1</code>.',
	'config-diff3-bad' => 'GNU diff3 pa trovà.',
	'config-imagemagick' => "Trovà ImageMagick: <code>$1</code>.
La miniaturisassion ëd figure a sarà abilità s'it abìlite le carie.",
	'config-gd' => "Trovà la librerìa gràfica built-in GD.
La miniaturisassion ëd figure a sarà abilità s'it abìlite le carie.",
	'config-no-scaling' => 'As treuva pa la librerìa GD o ImageMagick.
La miniaturisassion ëd figure a sarà disabilità.',
	'config-dir' => 'Instalassion directory: <code>$1</code>.',
	'config-uri' => "Path ëd l'URI dë script: <code>$1</code>.",
	'config-no-uri' => "'''Eror:''' As peul pa determiné l'URI corenta.
Instalassion abortìa.",
	'config-dir-not-writable-group' => "'''Eror:''' as peul pa scrivse ël file ëd configurassion.
Instalassion abortìa.

L'instalador a l'ha determinà l'utent sota ël qual tò webserver a gira.
Fa che la directory ëd <code><nowiki>config</nowiki></code> a sia scrivibla da chiel për continué.

Su un sistem Unix/Linus:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Eror:''' as peul pa scrivse ël file ëd configurassion.
Instalassion abortìa.

L'utent sota ël qual tò webserver a gira a peul pa esse determinà.
Fa che la directory ëd <code><nowiki>config</nowiki></code> a sia scrivibla globalment da chiel (e da d'àutri!) për continué.

Su un sistem Unix/Linus fà:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'Instalé MediaWiki con <code>$1</code> estension ëd file.',
	'config-shell-locale' => 'Trovà shell local "$1"',
	'config-uploads-safe' => "La directory ëd default për le carie a l'é sigura da l'esecussion arbitraria dë script.",
	'config-uploads-not-safe' => "'''Avis:''' Toa directory ëd default për le carie <code>$1</code> a l'é vulnerabla a l'esecussion arbitraria dë script.
Ëdcò se MediaWiki a contròla j'aspet ëd sicurëssa ëd tùit ij file carià, a l'é motobin arcomandà ëd [http://www.mediawiki.org/wiki/Manual:Security#Upload_security saré sta vulnerabilità ëd sicurëssa] prima d'abilité le carie.",
	'config-db-type' => 'Sòrt ëd database:',
	'config-db-host' => 'Host ëd database:',
	'config-db-host-help' => 'Se tò server ëd database a l\'é su server diferent, ansëriss ambelessì ël nòm host o l\'adrëssa IP.

Si të stas dovrand host wen condivis, tò provider d\'host a dovrìa dete ël nòm host giust ant soa documentassion.

Si të stas instaland su un server Windows e dovrand MySQL, dovré "localhost" a podrìa pa funsioné com nòm server. Dasnò, preuva "127.0.0.1" com adrëssa IP local.',
	'config-db-wiki-settings' => 'Identìfica sta wiki',
	'config-db-name' => 'Nòm dël database:',
	'config-db-name-help' => "Sern un nòm ch'a identìfica toa wiki.
A dovrìa pa conten-e spassi o tratin.

Si të stas dovrand un web host condivis, tò provider ëd l'host at darà un nòm ëd database specìfich da dovré, o a lassrà ch'it lo cree via un panel ëd contròl.",
	'config-db-install-account' => "Cont utent për l'instalassion.",
	'config-db-username' => 'Nòm utent dël database:',
	'config-db-password' => 'Ciav dël database:',
	'config-db-install-help' => "Ansëriss ël nòm utent e ciav che a saran dovrà për coleghesse al database an mente dël process d'instalassion.",
	'config-db-account-lock' => "Dòvra ij midem nòm utent e ciav an mente dj'operassion normaj",
	'config-db-wiki-account' => "Cont utent për j'operassion normaj",
	'config-db-wiki-help' => "Ansëriss ël nòm utent e ciav che a saran dovrà për coleghesse al database an mente dj'operassion normaj dla wiki.
S'ël cont a esist pa, e ël cont d'instalassion a l'ha basta privilegi, sto cont utent a sarà creà con ij privilegi mìnin për fé giré la wiki.",
	'config-db-prefix' => 'Prefiss dle tàule dël database:',
	'config-db-prefix-help' => "S'it l'has dabzògn ëd condivide un database an tra vàire wiki, o tr aMediaWiki e n'àutra web application, it peule serne ëd gionté un prefiss a tùit ij nòm ëd le tàule për evité ëd conflit.
Dovrà nen ni spassi ni tratin.

Sto camp a l'é lassà normalment veuid.",
	'config-db-charset' => 'Set ëd caràter dël database',
	'config-charset-mysql5-binary' => 'Binary ëd MySQL 4.1/5.0',
	'config-charset-mysql5' => 'UTF-8 ëd MySQL 4.1/5.0',
	'config-charset-mysql4' => "UTF-8 compatìbil a l'indré ëd MySQL 4.0",
	'config-charset-help' => "'''Avis:''' S'it deuvre '''UTF-8 compatìbil a l'indré''' su MySQL 4.1+, e peui if fas ël backup con <code>mysqldump</code>, a peul scanselé tùit ij caràter nen-ASCII, rovinand sensa speranse tò backup!

An '''manera binaria''', mediaWiki a memorisa ël test UTF-8 an camp binary ant ël database.
Soss' a l'é pi eficient che la manera UTF-8 ëd MySQL, e a përmëtt ëd dovré l'anter ansema ëd caràter Unicode.
An '''manera UTF-8''', MySQL a conòss an che ansema ëd caràter a son ij tò dat, e a peul presenteje e convertije apropriatament, ma at lasserà pa memorisé ij caràter an dzora al [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-mysql-old' => "A l'é ciamà MySQL $1 o pi recent, ti it l'has $2.",
	'config-db-port' => 'Porta dël database:',
	'config-db-schema' => 'Schema për MediaWiki',
	'config-db-ts2-schema' => 'Schema për tsearch2',
	'config-db-schema-help' => "Jë schema sota a son normalment giust.
Cang-je mach s'it sas ch'it deuve.",
	'config-sqlite-dir' => 'Directory ëd dat SQLite:',
	'config-sqlite-dir-help' => "SQLite a memorisa tùit ij dat ant un ùnich file.

La directory ch'it das a deuv esse scrivibla dal webserver an mente dl'instalassion.

A dovrìa '''pa''' esse acessibla via web, sossì a l'é ël përchè i l'oma pa butala andova a-i son ij tò file PHP.

L'instalador a scriverà un file <code>.htaccess</code> ansema con chiel, ma se lòn a faliss quaidun a peul pijé acess a tò raw file.
Lòn a comprend raw dat ëd l'utent (adrëssa e-mail, pciav critografà) parèj com revision scanselà e àutri dat segret ëd la wiki.

Consìdera ëd buté ël database ansema a quaidun d'àutr, për esempi an <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-support-info' => "MediaWiki a apògia i sistem ëd database ch'a ven-o:

$1

S'it vëdde pa listà sì sota ël sistem ëd database ch'it preuve a dovré,antlora va daré a le istrussion linkà sì sota për abilité l'apògg.",
	'config-support-mysql' => "* $1 e l'é l'obietiv primary për mediaWiki e a l'é ël mej apogià ([http://www.php.net/manual/en/mysql.installation.php com compilé PHP con l'apògg MySQL])",
	'config-support-postgres' => "* $1 e l'é un sistem ëd database popolar a sorziss doverta com alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php com compilé PHP con l'apògg PostgreSQL])",
	'config-support-sqlite' => "* $1 e l'é un sistem ëd database linger che a l'é motobin bin apogià ([http://www.php.net/manual/en/pdo.installation.php com compilé PHP con l'apògg SQLite], a dòvra PDO)",
	'config-header-mysql' => 'Ampostassion MySQL',
	'config-header-postgres' => 'Ampostassion PostgreSQL',
	'config-header-sqlite' => 'Ampostassion SQLite',
	'config-header-oracle' => 'Ampostassion Oracle',
	'config-invalid-db-type' => 'Sòrt ëd database pa bon',
	'config-missing-db-name' => 'It deuve anserì un valor për "Nòm database"',
	'config-invalid-db-name' => 'Nòm dël database pa bon "$1".
Dòvra mach litre ASCII (a-z, A-Z), nùmer (0-9) e underscore (_).',
	'config-invalid-db-prefix' => 'Prefiss dël database pa bon "$1".
Dòvra mach litre ASCII (a-z, A-Z), nùmer (0-9) e underscore (_).',
	'config-connection-error' => "$1.

Contròla l'host, nòm utent e ciav sota e preuva torna.",
	'config-invalid-schema' => 'Schema pa bon për MediaWiki "$1".
Dòvra mach litre ASCII (a-z, A-Z), nùmer (0-9) e underscore (_).',
	'config-invalid-ts2schema' => 'Schema pa bon për TSearch2 "$1".
Dòvra mach litre ASCII (a-z, A-Z), nùmer (0-9) e underscore (_).',
	'config-postgres-old' => "A l'é ciamà PostgreSQL $1 o pi recent, ti it l'has $2.",
	'config-sqlite-name-help' => "Sern un nòm ch'a identìfica toa wiki.
Dòvra nen spassi o tratin.
Sossì a sarà dovrà për ël nòm dël file ëd dat SQLite.",
	'config-sqlite-parent-unwritable-group' => "As peul pa creesse la directory ëd dat <code><nowiki>$1</nowiki></code>, përchè la directory dë dzora <code><nowiki>$2</nowiki></code> a l'é pa scrivibla dal webserver.

L'instalador a l'ha determinà sota che utent a gira tò webserver.
Fà che la directory <code><nowiki>$3</nowiki></code> a sia scrivibla da chiel për continué.
Su un sistem Unix/Linux fà:
<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>",
	'config-sqlite-parent-unwritable-nogroup' => "As peul pa creesse la directory ëd dat <code><nowiki>$1</nowiki></code>, përchè la directory dë dzora <code><nowiki>$2</nowiki></code> a l'é pa scrivibla dal webserver.

L'instalador a peul pa determiné l'utent sota ël qual a gira tò webserver.
Fà che la directory <code><nowiki>$3</nowiki></code> a sia scrivibla globalment da chiel (e da d'àutri) për continué.
Su un sistem Unix/Linux fà:
<pre>cd $2
mkdir $3
chmod a+w $3</pre>",
	'config-sqlite-mkdir-error' => 'Eror an creand la directory ëd dat "$1".
Contròla la locassion e preuva torna.',
	'config-sqlite-dir-unwritable' => 'As peul pa scrivse la directory "$1".
Cangia ij sò përmess an manera che ël webserver a peussa scrivje, e preuva torna.',
	'config-sqlite-connection-error' => '$1.

Contròla la directory ëd dat e ël nòm ëd database sota e preuva torna.',
	'config-sqlite-readonly' => "Ël file <code>$1</code> a l'é pa scrivìbil.",
	'config-sqlite-cant-create-db' => 'As peul pa cresse ël file ëd database <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => "PHP a l'ha pa l'apògg ëd FTS3, degradà le tàule",
	'config-sqlite-fts3-add' => "Gionté le capassità d'arserca FTS3",
	'config-can-upgrade' => "A-i é dle tàule MediaWiki an sto database.
Për agiorneje a MediaWiki $1, sgnaca '''Continua'''.",
	'config-upgrade-done' => "Agiornament complet.

Adess it peule [$1 ancaminé a dovré toa wiki].

S'it veule regeneré tò file <code>LocalSettings.php</code> file, sgnaca ël boton sota.
Sossì a l'è '''pa arcomandà''' a men ch'it të stìe avend dij problem con toa wiki.",
	'config-regenerate' => 'Regeneré LocalSettings.php →',
	'config-show-table-status' => 'Query SHOW TABLE STATUS falìa!',
	'config-unknown-collation' => "'''Avis:''' Ël database a dòvra un confront pa arconossù.",
	'config-db-web-account' => 'Cont dël database për acess web',
	'config-db-web-help' => 'Selession-a ël nòm utent e la ciav che ël web server a dovrerà për coleghesse al server dël database, an mente dle operassion ordinarie dla wiki.',
	'config-db-web-account-same' => "Dòvra ël midem cont com për l'istalassion",
	'config-db-web-create' => "Crea ël cont se a esist pa anco'",
	'config-db-web-no-create-privs' => "Ël cont ch'it l'has specificà për l'instalassion a l'ha pa basta privilegi për creé un cont.
Ël cont ch'it të specifiche ambelessì a deuv già esiste.",
	'config-mysql-engine' => 'Motor ëd memorisassion:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' a l'é almanch sempe la mej opsion, da già ch'a l'ha un bon apògg dla concorensa.

'''MyISAM''' a peul esse pi lest an instalassion ingle-user o read-only.
Ël database MyISAM a tira a corompse pi soens che ël database InnoDB.",
	'config-mysql-egine-mismatch' => "'''Avis:''' it l'has ciamà ël motor ëd memorisassion $1, ma ël database esistent a dòvra ël motor $2.
Sto script d'agiornament a peul pa convertilo, parèj a rëstrà $2.",
	'config-mysql-charset' => 'Set ëd caràter dël database:',
	'config-mysql-binary' => 'Binari',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "An '''manera binaria''', MediaWiki a memorisa test UTF-8 ant ël database an camp binari.
Sossì a l'é pi eficient che la manera UTF-8 ëd MySQL, e at përmëtt ëd dovré l'ansema anter ëd caràter Unicode.

An '''manera UTF-8''', MySQL a conosserà an che set ëd caràter a son ij tò dat, e a peul presenteje e convertije apropriatament, ma at lassa pa memorisé caràter an dzora al [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-mysql-charset-mismatch' => "'''Avis:''' it l'has ciamà lë schema $1, ma ël database esistent a l'ha lë schema $2.
Sto script d'agiornament a peul pa convertilo, parèj a rëstrà $2.",
	'config-site-name' => 'Nòm ëd la wiki:',
	'config-site-name-help' => 'Sossì a pararirà ant la bara dël tìtol dël browser e an vàire àutri pòst.',
	'config-site-name-blank' => 'Ansëriss un nòm ëd sit.',
	'config-project-namespace' => 'Spassi nominal dël proget:',
	'config-ns-generic' => 'Proget',
	'config-ns-site-name' => 'Midem com ël nom dla wiki: $1',
	'config-ns-other' => 'Àutr (specìfica)',
	'config-ns-other-default' => 'MyWiki',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'config-show-help' => 'لارښود',
	'config-hide-help' => 'لارښود پټول',
	'config-your-language' => 'ستاسې ژبه:',
	'config-wiki-language' => 'د ويکي ژبه:',
	'config-page-language' => 'ژبه',
	'config-page-welcome' => 'مېډياويکي ته ښه راغلاست!',
	'config-page-name' => 'نوم',
	'config-page-install' => 'لګول',
	'config-page-complete' => 'بشپړ!',
	'config-env-php' => 'د $1 PHP نصب شو.',
);

/** Portuguese (Português)
 * @author Crazymadlover
 * @author Hamilton Abreu
 */
$messages['pt'] = array(
	'config-desc' => 'O instalador do MediaWiki',
	'config-title' => 'Instalação MediaWiki $1',
	'config-information' => 'Informação',
	'config-localsettings-upgrade' => "'''Aviso''': Foi detectada a existência de um ficheiro <code>LocalSettings.php</code>.
É possível actualizar o seu software.
Mova o <code>LocalSettings.php</code> para um sítio seguro e execute o instalador novamente, por favor.",
	'config-localsettings-noupgrade' => "'''Erro''': Foi detectada a existência de um ficheiro <code>LocalSettings.php</code>.
Não é possível actualizar o seu software nesta altura.
Por razões de segurança, o instalador foi desactivado.",
	'config-session-error' => 'Erro ao iniciar a sessão: $1',
	'config-session-expired' => 'Os seus dados de sessão parecem ter expirado.
As sessões estão configuradas para uma duração de $1.
Pode aumentar esta duração configurando <code>session.gc_maxlifetime</code> no php.ini.
Reinicie o processo de instalação.',
	'config-no-session' => 'Os seus dados de sessão foram perdidos!
Verifique o seu php.ini e certifique-se de que em <code>session.save_path</code> está definido um directório apropriado.',
	'config-session-path-bad' => 'O directório em <code>session.save_path</code> (<code>$1</code>) parece ser inválido ou não permite acesso de escrita.',
	'config-show-help' => 'Ajuda',
	'config-hide-help' => 'Esconder ajuda',
	'config-your-language' => 'A sua língua:',
	'config-your-language-help' => 'Seleccione a língua que será usada durante o processo de instalação.',
	'config-wiki-language' => 'Língua da wiki:',
	'config-wiki-language-help' => 'Seleccione a língua que será predominante na wiki.',
	'config-back' => '← Voltar',
	'config-continue' => 'Continuar →',
	'config-page-language' => 'Língua',
	'config-page-welcome' => 'Bem-vindo(a) ao MediaWiki!',
	'config-page-dbconnect' => 'Ligar à base de dados',
	'config-page-upgrade' => 'Actualizar a instalação existente',
	'config-page-dbsettings' => 'Configurações da base de dados',
	'config-page-name' => 'Nome',
	'config-page-options' => 'Opções',
	'config-page-install' => 'Instalar',
	'config-page-complete' => 'Terminado!',
	'config-page-restart' => 'Reiniciar a instalação',
	'config-page-readme' => 'Leia-me',
	'config-page-releasenotes' => 'Notas de lançamento',
	'config-page-copying' => 'A copiar',
	'config-page-upgradedoc' => 'A actualizar',
	'config-help-restart' => 'Deseja limpar todos os dados gravados que introduziu e reiniciar o processo de instalação?',
	'config-restart' => 'Sim, reiniciar',
	'config-welcome' => '=== Verificações do ambiente ===
São realizadas verificações básicas para determinar se este ambiente é apropriado para instalação do MediaWiki.
Se necessitar de pedir ajuda durante a instalação, deve fornecer os resultados destas verificações.',
	'config-copyright' => "=== Direitos de autor e Termos de uso ===

$1

Este programa é software livre; pode redistribuí-lo e/ou modificá-lo nos termos da licença GNU General Public License, tal como publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (por opção sua) qualquer versão posterior.

Este programa é distribuído na esperança de que seja útil, mas '''sem qualquer garantia'''; inclusive, sem a garantia implícita da '''possibilidade de ser comercializado''' ou de '''adequação para qualquer finalidade específica'''.
Consulte a licença GNU General Public License para mais detalhes.

Em conjunto com este programa deve ter recebido <doclink href=Copying>uma cópia da licença GNU General Public License</doclink>; se não a recebeu, peça-a por escrito para Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ou [http://www.gnu.org/copyleft/gpl.html leia-a na internet].",
	'config-sidebar' => '* [http://www.mediawiki.org/wiki/MediaWiki/pt Página principal do MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents/pt Ajuda]
* [http://www.mediawiki.org/wiki/Manual:Contents/pt Manual técnico]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => '<span class="success-message">O ambiente foi verificado.
Pode instalar o MediaWiki.</span>',
	'config-env-bad' => 'O ambiente foi verificado.
Não pode instalar o MediaWiki.',
	'config-env-php' => 'O PHP $1 está instalado.',
	'config-env-latest-ok' => 'Está a instalar a versão mais recente do MediaWiki.',
	'config-env-latest-new' => "'''Nota:''' Está a instalar a versão de desenvolvimento do MediaWiki.",
	'config-env-latest-can-not-check' => "'''Aviso:''' O instalador não conseguiu obter informações sobre a versão mais recente do MediaWiki, de [$1].",
	'config-env-latest-old' => "'''Aviso:''' Está a instalar uma versão desactualizada do Mediawiki.",
	'config-env-latest-help' => 'Está a instalar a versão $1, mas a versão mais recente é a $2.
Aconselhamos que instale a versão mais recente. Pode fazer o download a partir da [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'A usar a implementação lenta do PHP para a normalização Unicode.',
	'config-unicode-using-utf8' => 'A usar o utf8_normalize.so, por Brian Viper, para a normalização Unicode.',
	'config-unicode-using-intl' => 'A usar a [http://pecl.php.net/intl extensão intl PECL] para a normalização Unicode.',
	'config-unicode-pure-php-warning' => "'''Aviso''': A [http://pecl.php.net/intl extensão intl PECL] não está disponível para efectuar a normalização Unicode.
Se o seu site tem um alto volume de tráfego, devia informar-se um pouco sobre a [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalização Unicode].",
	'config-unicode-update-warning' => "'''Aviso''': A versão instalada do wrapper de normalização Unicode usa uma versão mais antiga da biblioteca do [http://site.icu-project.org/ projecto ICU].
Devia [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualizá-la] se tem quaisquer preocupações sobre o uso do Unicode.",
	'config-no-db' => "Não foi possível encontrar um controlador ''(driver)'' apropriado para a base de dados!",
	'config-no-db-help' => "Precisa de instalar um controlador ''(driver)'' de base de dados para o PHP.
São suportadas as seguintes bases de dados: $1.

Se o seu site está alojado num servidor partilhado, peça ao fornecedor do alojamento para instalar um controlador de base de dados apropriado.
Se fez a compilação do PHP você mesmo, reconfigure-o com um cliente de base de dados activado, usando, por exemplo, <code>./configure --with-mysql</code>.
Se instalou o PHP a partir de um pacote Debian ou Ubuntu, então precisa de instalar também o módulo php5-mysql.",
	'config-have-db' => "{{PLURAL:$2|Controlador ''(driver)'' de base de dados encontrado|Controladores ''(drivers)'' de base de dados encontrados}}: $1.",
	'config-register-globals' => "'''Aviso: A opção <code>[http://php.net/register_globals register_globals]</code> do PHP está activada.'''
'''Desactive-a, se puder.'''
O MediaWiki funciona mesmo assim, mas o seu servidor está exposto a potenciais vulnerabilidades de segurança.",
	'config-magic-quotes-runtime' => "'''Fatal: A opção [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] está activada!'''
Esta opção causa corrupção dos dados de entrada, de uma forma imprevisível.
Não pode instalar ou usar o MediaWiki a menos que esta opção seja desactivada.",
	'config-magic-quotes-sybase' => "'''Fatal: A opção [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] está activada!'''
Esta opção causa corrupção dos dados de entrada, de uma forma imprevisível.
Não pode instalar ou usar o MediaWiki a menos que esta opção seja desactivada.",
	'config-mbstring' => "'''Fatal: A opção [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] está activada!'''
Esta opção causa erros e pode corromper os dados de uma forma imprevisível.
Não pode instalar ou usar o MediaWiki a menos que esta opção seja desactivada.",
	'config-ze1' => "'''Fatal: A opção [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] está activada!'''
Esta opção causa problemas significativos no MediaWiki.
Não pode instalar ou usar o MediaWiki a menos que esta opção seja desactivada.",
	'config-safe-mode' => "'''Aviso:''' O [http://www.php.net/features.safe-mode safe mode] do PHP está activo.
Este modo pode causar problemas, especialmente no upload de ficheiros e no suporte a <code>math</code>.",
	'config-xml-good' => 'Tem suporte de conversão XML / Latin1-UTF-8.',
	'config-xml-bad' => 'Falta o módulo XML do PHP.
O MediaWiki necessita de funções deste módulo e não funcionará com esta configuração.
Se está a executar o Mandrake, instale o pacote php-xml.',
	'config-pcre' => 'Parece faltar o módulo de suporte PCRE.
O MediaWiki necessita que as funções de expressões regulares compatíveis com Perl estejam a funcionar.',
	'config-memory-none' => 'O PHP está configurado sem <code>memory_limit</code>',
	'config-memory-ok' => 'A configuração <code>memory_limit</code> do PHP é $1.
OK.',
	'config-memory-raised' => 'A configuração <code>memory_limit</code> do PHP era $1; foi aumentada para $2.',
	'config-memory-bad' => "'''Aviso:''' A configuração <code>memory_limit</code> do PHP é $1.
Isto é provavelmente demasiado baixo.
A instalação poderá falhar!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] instalada',
	'config-apc' => '[http://www.php.net/apc APC] instalada',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] instalado',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] instalada',
	'config-no-cache' => "'''Aviso:''' Não foram encontrados [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] nem [http://www.iis.net/download/WinCacheForPhp WinCache].
A cache de objectos não será activada.",
	'config-diff3-good' => 'Foi encontrado o GNU diff3: <code>$1</code>.',
	'config-diff3-bad' => 'O GNU diff3 não foi encontrado.',
	'config-imagemagick' => 'Foi encontrado o ImageMagick: <code>$1</code>.
Se possibilitar uploads, a miniaturização de imagens será activada.',
	'config-gd' => 'Foi encontrada a biblioteca gráfica GD.
Se possibilitar uploads, a miniaturização de imagens será activada.',
	'config-no-scaling' => 'Não foi encontrada a biblioteca gráfica GD nem o ImageMagick.
A miniaturização de imagens será desactivada.',
	'config-dir' => 'Directório de instalação: <code>$1</code>.',
	'config-uri' => 'Localização URI do script: <code>$1</code>.',
	'config-no-uri' => "'''Erro:''' Não foi possível determinar a URI actual.
A instalação foi abortada.",
	'config-dir-not-writable-group' => "'''Erro:''' Não é possível gravar o ficheiro de configuração.
A instalação foi abortada.

O instalador determinou em que nome de utilizador o seu servidor de internet está a correr.
Para continuar, configure o directório <code><nowiki>config</nowiki></code> para poder ser escrito por este utilizador.
Para fazê-lo em sistemas Unix ou Linux, use:

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Erro:''' Não é possível gravar o ficheiro de configuração.
A instalação foi abortada.

Não foi possível determinar em que nome de utilizador o seu servidor de internet está a correr.
Para continuar, configure o directório <code><nowiki>config</nowiki></code> para que este possa ser globalmente escrito por esse utilizador (e por outros!).
Para fazê-lo em sistemas Unix ou Linux, use:

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'A instalar o MediaWiki com <code>$1</code> extensões de ficheiros.',
	'config-shell-locale' => 'Foi detectado o locale da shell "$1"',
	'config-uploads-safe' => 'O directório por omissão para uploads está protegido contra a execução arbitrária de scripts.',
	'config-uploads-not-safe' => "'''Aviso:''' O directório por omissão para uploads <code>$1</code>, está vulnerável à execução arbitrária de scripts.
Embora o MediaWiki verifique a existência de ameaças de segurança em todos os ficheiros enviados, é altamente recomendado que [http://www.mediawiki.org/wiki/Manual:Security#Upload_security vede esta vulnerabilidade de segurança] antes de possibilitar uploads.",
	'config-db-type' => 'Tipo da base de dados:',
	'config-db-host' => 'Servidor da base de dados:',
	'config-db-host-help' => 'Se a base de dados estiver num servidor separado, introduza aqui o nome ou o endereço IP desse servidor.

Se estiver a usar um servidor partilhado, o fornecedor do alojamento deve ter-lhe fornecido o nome do servidor na documentação.

Se está a fazer a instalação num servidor Windows com MySQL, usar como nome do servidor "localhost" poderá não funcionar. Se não funcionar, tente usar "127.0.0.1" como endereço IP local.',
	'config-db-wiki-settings' => 'Identifique esta wiki',
	'config-db-name' => 'Nome da base de dados:',
	'config-db-name-help' => 'Escolha um nome para identificar a sua wiki.
O nome não deve conter espaços nem hífens.

Se estiver a usar um servidor partilhado, o fornecedor do alojamento deve poder fornecer-lhe o nome de uma base de dados que possa usar, ou permite-lhe criar bases de dados através de um painel de controle.',
	'config-db-install-account' => 'Conta do utilizador para a instalação',
	'config-db-username' => 'Nome do utilizador da base de dados:',
	'config-db-password' => 'Palavra-chave do utilizador da base de dados:',
	'config-db-install-help' => 'Introduza o nome de utilizador e a palavra-chave que serão usados para aceder à base de dados durante o processo de instalação.',
	'config-db-account-lock' => 'Usar o mesmo nome de utilizador e palavra-chave durante a operação normal',
	'config-db-wiki-account' => 'Conta de utilizador para a operação normal',
	'config-db-wiki-help' => 'Introduza o nome de utilizador e a palavra-chave que serão usados para aceder à base de dados durante a operação normal da wiki.
Se esta conta não existir e se a conta de instalação tiver privilégios suficientes, esta conta será criada com os privilégios mínimos necessários para a operação normal da wiki.',
	'config-db-prefix' => 'Prefixo para as tabelas da base de dados:',
	'config-db-prefix-help' => 'Se necessitar de partilhar uma só base de dados entre várias wikis, ou entre o MediaWiki e outra aplicação, pode escolher adicionar um prefixo ao nome de todas as tabelas desta instalação para evitar conflitos.
O prefixo não pode conter espaços ou hífens.

Normalmente, este campo deve ficar vazio.',
	'config-db-charset' => 'Conjunto de caracteres da base de dados',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binary',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 backwards-compatible UTF-8',
	'config-charset-help' => "'''Aviso:''' Se usar '''backwards-compatible UTF-8''' (\"UTF-8 compatível com versões anteriores\") no MySQL 4.1+, e depois fizer cópias de segurança da base de dados usando <code>mysqldump</code>, poderá destruir todos os caracteres que não fazem parte do conjunto ASCII, corrompendo assim, de forma irreversível, as suas cópias de segurança!

No modo '''binary''' (\"binário\"), o MediaWiki armazena o texto UTF-8 na base de dados em campos binários.
Isto é mais eficiente do que o modo UTF-8 do MySQL e permite que sejam usados todos os caracteres Unicode.
No modo '''UTF-8''', o MySQL saberá em que conjunto de caracteres os seus dados estão e pode apresentá-los e convertê-los da forma mais adequada,
mas não lhe permitirá armazenar caracteres acima do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilinguístico Básico].",
	'config-mysql-old' => 'É necessário o MySQL $1 ou posterior; tem a versão $2.',
	'config-db-port' => 'Porta da base de dados:',
	'config-db-schema' => "Esquema ''(schema)'' do MediaWiki",
	'config-db-ts2-schema' => "Esquema ''(schema)'' do tsearch2",
	'config-db-schema-help' => 'Normalmente, os esquemas acima estão correctos.
Altere-os só se souber que precisa de alterá-los.',
	'config-sqlite-dir' => 'Directório de dados do SQLite:',
	'config-sqlite-dir-help' => "O SQLite armazena todos os dados num único ficheiro.

Durante a instalação, o servidor de internet precisa de ter permissão de escrita no directório que especificar.

Este directório '''não''' deve poder ser acedido directamente da internet, por isso está a ser colocado onde estão os seus ficheiros PHP.

Juntamente com o directório, o instalador irá criar um ficheiro <code>.htaccess</code>, mas se esta operação falhar é possível que alguém venha a ter acesso directo à base de dados.
Isto inclui acesso aos dados dos utilizadores (endereços de correio electrónico, palavras-chave encriptadas), às revisões eliminadas e a outros dados de acesso restrito na wiki.

Considere colocar a base de dados num local completamente diferente, como, por exemplo, em <code>/var/lib/mediawiki/asuawiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'O MediaWiki suporta as seguintes plataformas de base de dados:

$1

Se a plataforma que pretende usar não está listada abaixo, siga as instruções nos links acima para activar o suporte.',
	'config-support-mysql' => '* $1 é a plataforma primária do MediaWiki e a melhor suportada ([http://www.php.net/manual/en/mysql.installation.php como compilar PHP com suporte MySQL])',
	'config-support-postgres' => '* $1 é uma plataforma de base de dados comum, de fonte aberta, alternativa ao MySQL. ([http://www.php.net/manual/en/pgsql.installation.php como compilar PHP com suporte PostgreSQL])',
	'config-support-sqlite' => '* $1 é uma plataforma de base de dados ligeira muito bem suportada. ([http://www.php.net/manual/en/pdo.installation.php Como compilar PHP com suporte SQLite], usa PDO)',
	'config-header-mysql' => 'Definições MySQL',
	'config-header-postgres' => 'Definições PostgreSQL',
	'config-header-sqlite' => 'Definições SQLite',
	'config-header-oracle' => 'Definições Oracle',
	'config-invalid-db-type' => 'O tipo de base de dados é inválido',
	'config-missing-db-name' => 'Tem de introduzir um valor para "Nome da base de dados"',
	'config-invalid-db-name' => 'O nome da base de dados, "$1",  é inválido.
Use só letras (a-z, A-Z), algarismos (0-9) e sublinhados (_) dos caracteres ASCII.',
	'config-invalid-db-prefix' => 'O prefixo da base de dados, "$1",  é inválido.
Use só letras (a-z, A-Z), algarismos (0-9) e sublinhados (_) dos caracteres ASCII.',
	'config-connection-error' => '$1.

Verifique o servidor, o nome do utilizador e a palavra-chave abaixo e tente novamente.',
	'config-invalid-schema' => "O esquema ''(schema)'' do MediaWiki, \"\$1\", é inválido.
Use só letras (a-z, A-Z), algarismos (0-9) e sublinhados (_) dos caracteres ASCII.",
	'config-invalid-ts2schema' => "O esquema ''(schema)'' para o TSearch2, \"\$1\", é inválido.
Use só letras (a-z, A-Z), algarismos (0-9) e sublinhados (_) dos caracteres ASCII.",
	'config-postgres-old' => 'É necessário o PostgreSQL $1 ou posterior; tem a versão $2.',
	'config-sqlite-name-help' => 'Escolha o nome que identificará a sua wiki.
Não use espaços ou hífens.
Este nome será usado como nome do ficheiro de dados do SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Não é possível criar o directório de dados <code><nowiki>$1</nowiki></code>, porque o servidor de internet não tem permissão de escrita no directório que o contém <code><nowiki>$2</nowiki></code>.

O instalador determinou em que nome de utilizador o seu servidor de internet está a correr.
Para continuar, configure o directório <code><nowiki>$3</nowiki></code> para poder ser escrito por este utilizador.
Para fazê-lo em sistemas Unix ou Linux, use:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Não é possível criar o directório de dados <code><nowiki>$1</nowiki></code>, porque o servidor de internet não tem permissão de escrita no directório que o contém <code><nowiki>$2</nowiki></code>.

Não foi possível determinar em que nome de utilizador o seu servidor de internet está a correr.
Para continuar, configure o directório <code><nowiki>$3</nowiki></code> para que este possa ser globalmente escrito por esse utilizador (e por outros!).
Para fazê-lo em sistemas Unix ou Linux, use:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Ocorreu um erro ao criar o directório de dados "$1".
Verifique a localização e tente novamente.',
	'config-sqlite-dir-unwritable' => 'Não foi possível escrever no directório "$1".
Altere as permissões para que ele possa ser escrito pelo servidor de internet e tente novamente.',
	'config-sqlite-connection-error' => '$1.

Verifique o directório de dados e o nome da base de dados abaixo e tente novamente.',
	'config-sqlite-readonly' => 'Não é possivel escrever no ficheiro <code>$1</code>.',
	'config-sqlite-cant-create-db' => 'Não foi possível criar o ficheiro da base de dados <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'O PHP não tem suporte FTS3; a reverter o esquema das tabelas para o anterior',
	'config-sqlite-fts3-add' => 'A adicionar as capacidades de pesquisa FTS3',
	'config-can-upgrade' => "Esta base de dados contém tabelas do MediaWiki.
Para actualizá-las para o MediaWiki $1, clique '''Continuar'''.",
	'config-upgrade-done' => "Actualização terminada.

Agora pode [$1 começar a usar a sua wiki].

Se quiser regenerar o seu ficheiro <code>LocalSettings.php</code>, clique o botão abaixo.
Esta operação '''não é recomendada''' a menos que esteja a ter problemas com a sua wiki.",
	'config-regenerate' => 'Regenerar o LocalSettings.php →',
	'config-show-table-status' => 'A consulta SHOW TABLE STATUS falhou!',
	'config-unknown-collation' => "'''Aviso:''' A base de dados está a utilizar uma colação ''(collation)'' desconhecida.",
	'config-db-web-account' => 'Conta na base de dados para acesso pela internet',
	'config-db-web-help' => 'Seleccione o nome de utilizador e a palavra-chave que o servidor de internet irá utilizar para aceder ao servidor da base de dados, durante a operação normal da wiki.',
	'config-db-web-account-same' => 'Usar a mesma conta usada na instalação',
	'config-db-web-create' => 'Criar a conta se ainda não existir',
	'config-db-web-no-create-privs' => 'A conta que especificou para a instalação não tem privilégios suficientes para criar uma conta.
A conta que especificar aqui já tem de existir.',
	'config-mysql-engine' => 'Motor de armazenamento:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' é quase sempre a melhor opção, porque suporta bem acessos simultâneos ''(concurrency)''.

'''MyISAM''' pode ser mais rápido no modo de utilizador único ou em instalações somente para leitura.
As bases de dados MyISAM tendem a ficar corrompidas com maior frequência do que as bases de dados InnoDB.",
	'config-mysql-egine-mismatch' => "'''Aviso:''' pediu a plataforma de armazenamento $1, mas a base de dados existente usa a plataforma $2. Este código de actualização não pode fazer a conversão, por isso permanecerá como $2.",
	'config-mysql-charset' => 'Conjunto de caracteres da base de dados:',
	'config-mysql-binary' => 'Binary',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "No modo '''binary''' (\"binário\"), o MediaWiki armazena o texto UTF-8 na base de dados em campos binários.
Isto é mais eficiente do que o modo UTF-8 do MySQL e permite que sejam usados todos os caracteres Unicode.

No modo '''UTF-8''', o MySQL saberá em que conjunto de caracteres os seus dados estão e pode apresentá-los e convertê-los da forma mais adequada,
mas não lhe permitirá armazenar caracteres acima do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilinguístico Básico].",
	'config-mysql-charset-mismatch' => "'''Aviso:''' pediu o esquema ''(schema)'' $1, mas a base de dados existente usa o esquema $2. Este código de actualização não pode fazer a conversão, por isso permanecerá como $2.",
	'config-site-name' => 'Nome da wiki:',
	'config-site-name-help' => 'Este nome aparecerá no título da janela do seu browser e em vários outros sítios.',
	'config-site-name-blank' => 'Introduza o nome do site.',
	'config-project-namespace' => 'Espaço nominal do projecto:',
	'config-ns-generic' => 'Projecto',
	'config-ns-site-name' => 'O mesmo que o nome da wiki: $1',
	'config-ns-other' => 'Outro (especifique)',
	'config-ns-other-default' => 'AMinhaWiki',
	'config-project-namespace-help' => 'Seguindo o exemplo da Wikipedia, muitas wikis mantêm as páginas das suas normas e políticas, separadas das páginas de conteúdo, num "\'\'\'espaço nominal do projecto\'\'\'".
Todos os nomes das páginas neste espaço nominal começam com um determinado prefixo, que pode especificar aqui.
Tradicionalmente, este prefixo deriva do nome da wiki, mas não pode conter caracteres de pontuação, como "#" ou ":".',
	'config-ns-invalid' => 'O espaço nominal especificado "<nowiki>$1</nowiki>" é inválido.
Introduza um espaço nominal de projecto diferente.',
	'config-admin-box' => 'Conta de administrador',
	'config-admin-name' => 'O seu nome:',
	'config-admin-password' => 'Palavra-chave:',
	'config-admin-password-confirm' => 'Repita a palavra-chave:',
	'config-admin-help' => 'Introduza aqui o seu nome de utilizador preferido, por exemplo, "João Beltrão".
Este é o nome que irá utilizar para entrar na wiki.',
	'config-admin-name-blank' => 'Introduza um nome de utilizador para administrador.',
	'config-admin-name-invalid' => 'O nome de utilizador especificado "<nowiki>$1</nowiki>" é inválido.
Introduza um nome de utilizador diferente.',
	'config-admin-password-blank' => 'Introduza uma palavra-chave para a conta de administrador.',
	'config-admin-password-same' => 'A palavra-chave tem de ser diferente do nome de utilizador.',
	'config-admin-password-mismatch' => 'As duas palavras-chave que introduziu não coincidem.',
	'config-admin-email' => 'Correio electrónico:',
	'config-admin-email-help' => 'Introduza aqui um correio electrónico que lhe permita receber mensagens de outros utilizadores da wiki, reiniciar a sua palavra-chave e receber notificações de alterações às suas páginas vigiadas.',
	'config-admin-error-user' => 'Ocorreu um erro interno ao criar um administrador com o nome "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Ocorreu um erro interno ao definir uma palavra-chave para o administrador "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => 'Subscreva a [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce lista de divulgação de anúncios de lançamento].',
	'config-subscribe-help' => 'Esta é uma lista de divulgação de baixo volume para anúncios de lançamento de versões novas, incluindo anúncios de segurança importantes.
Deve subscrevê-la e actualizar a sua instalação MediaWiki quando são lançadas versões novas.',
	'config-almost-done' => 'Está quase a terminar!
Agora pode saltar as configurações restantes e instalar já a wiki.',
	'config-optional-continue' => 'Faz-me mais perguntas.',
	'config-optional-skip' => 'Já estou aborrecido, instala lá a wiki.',
	'config-profile' => 'Perfil de permissões:',
	'config-profile-wiki' => 'Wiki tradicional',
	'config-profile-no-anon' => 'Criação de conta exigida',
	'config-profile-fishbowl' => 'Somente utilizadores autorizados',
	'config-profile-private' => 'Wiki privada',
	'config-profile-help' => "As wikis funcionam melhor quando se deixa tantas pessoas editá-las quanto possível.
No MediaWiki, é fácil rever as alterações recentes e reverter quaisquer estragos causados por utilizadores novatos ou maliciosos.

No entanto, muitas pessoas consideram o MediaWiki útil de variadas formas e nem sempre é fácil convencer todas as pessoas dos benefícios desta filosofia wiki.
Por isso pode optar.

Uma '''{{int:config-profile-wiki}}''' permite que todos a editem, sem sequer necessitar de autenticação.
Uma wiki com '''{{int:config-profile-no-anon}}''' atribui mais responsabilidade, mas pode afastar os colaboradores ocasionais.

Um cenário '''{{int:config-profile-fishbowl}}''' permite que os utilizadores aprovados editem, mas que o público visione as páginas, incluindo o historial das mesmas.
Uma '''{{int:config-profile-private}}''' só permite que os utilizadores aprovados visionem as páginas e as editem.

Após a instalação, estarão disponíveis mais configurações de privilégios. Consulte [http://www.mediawiki.org/wiki/Manual:User_rights a entrada relevante no Manual].",
	'config-license' => 'Direitos de autor e licença:',
	'config-license-none' => 'Sem rodapé com a licença',
	'config-license-cc-by-sa' => 'Atribuição - Partilha nos Mesmos Termos, da Creative Commons (compatível com a Wikipédia)',
	'config-license-cc-by-nc-sa' => 'Atribuição - Uso Não-Comercial - Partilha nos Mesmos Termos, da Creative Commons',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 ou posterior',
	'config-license-pd' => 'Domínio Público',
	'config-license-cc-choose' => 'Seleccione uma licença personalizada da Creative Commons',
	'config-license-help' => 'Muitas wikis de acesso público licenciam todas as colaborações com uma [http://freedomdefined.org/Definition licença livre].
Isto ajuda a criar um sentido de propriedade da comunidade e encoraja as colaborações a longo prazo.
Tal não é geralmente necessário nas wikis privadas ou corporativas.

Se pretende que seja possível usar textos da Wikipédia na sua wiki e que seja possível a Wikipédia aceitar textos copiados da sua wiki, deve escolher a licença Atribuição - Partilha nos Mesmos Termos, da Creative Commons.

A licença GNU Free Documentation License era a anterior licença da Wikipédia.
Embora ainda seja uma licença válida, ela tem certas características que tornam o reuso e a interpretação difíceis.',
	'config-email-settings' => 'Definições do correio electrónico',
	'config-enable-email' => 'Activar mensagens electrónicas de saída',
	'config-enable-email-help' => 'Se quer que o correio electrónico funcione, as [http://www.php.net/manual/en/mail.configuration.php definições de correio electrónico do PHP] têm de estar configuradas correctamente.
Se não pretende viabilizar qualquer funcionalidade de correio electrónico, pode desactivá-lo aqui.',
	'config-email-user' => 'Activar mensagens electrónicas entre utilizadores',
	'config-email-user-help' => 'Permitir que todos os utilizadores troquem entre si mensagens de correio electrónico, se tiverem activado esta funcionalidade nas suas preferências.',
	'config-email-usertalk' => 'Activar notificações de alterações à página de discussão dos utilizadores',
	'config-email-usertalk-help' => 'Permitir que os utilizadores recebam notificações de alterações à sua página de discussão, se tiverem activado esta funcionalidade nas suas preferências.',
	'config-email-watchlist' => 'Activar notificação de alterações às páginas vigiadas',
	'config-email-watchlist-help' => 'Permitir que os utilizadores recebam notificações de alterações às suas páginas vigiadas, se tiverem activado esta funcionalidade nas suas preferências.',
	'config-email-auth' => 'Activar autenticação do correio electrónico',
	'config-email-auth-help' => "Se esta opção for activada, os utilizadores têm de confirmar o seu endereço de correio electrónico usando um link que lhes é enviado sempre que o definirem ou alterarem.
Só os endereços de correio electrónico autenticados podem receber mensagens electrónicas dos outros utilizadores ou alterar as mensagens de notificação.
É '''recomendado''' que esta opção seja activada nas wikis de acesso público para impedir o uso abusivo das funcionalidades de correio electrónico.",
	'config-email-sender' => 'Endereço de correio electrónico de retorno:',
	'config-email-sender-help' => 'Introduza o endereço de correio electrónico que será usado como endereço de retorno nas mensagens electrónicas de saída.
É para este endereço que serão enviadas as mensagens que não podem ser entregues.
Muitos servidores de correio electrónico exigem que pelo menos a parte do nome do domínio seja válida. \\',
	'config-upload-settings' => 'Upload de imagens e ficheiros',
	'config-upload-enable' => 'Possibilitar o upload de ficheiros',
	'config-upload-help' => 'O upload de ficheiros expõe o seu servidor a riscos de segurança.
Para mais informações, leia a [http://www.mediawiki.org/wiki/Manual:Security secção sobre segurança] do Manual Técnico.

Para permitir o upload de ficheiros, altere as permissões do subdirectório <code>images</code> no directório de raiz do MediaWik para que o servidor de internet possa escrever nele.
Depois active esta opção.',
	'config-upload-deleted' => 'Directório para os ficheiros apagados:',
	'config-upload-deleted-help' => 'Escolha um directório onde serão arquivados os ficheiros apagados.
O ideal é que este directório não possa ser directamente acedido a partir da internet.',
	'config-logo' => 'URL do logótipo:',
	'config-logo-help' => 'O tema padrão do MediaWiki inclui espaço para um logótipo de 135x160 pixels no canto superior esquerdo.
Faça o upload de uma imagem com estas dimensões e introduza aqui a URL dessa imagem.

Se não pretende usar um logótipo, deixe este campo em branco.',
	'config-instantcommons' => 'Activar a funcionalidade Instant Commons',
	'config-instantcommons-help' => 'O [http://www.mediawiki.org/wiki/InstantCommons Instant Commons] é uma funcionalidade que permite que as wikis usem imagens, áudio e outros ficheiros multimédia disponíveis no site [http://commons.wikimedia.org/ Wikimedia Commons].
Para poder usá-los, o MediaWiki necessita de acesso à internet. $1

Para mais informações sobre esta funcionalidade, incluindo instruções sobre como configurá-la para usar outras wikis em vez da Wikimedia Commons, consulte o [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos Manual Técnico].',
	'config-instantcommons-good' => 'Durante as verificações do ambiente, o instalador detectou a presença de uma ligação à internet.
Se quiser, pode activar esta funcionalidade.',
	'config-instantcommons-bad' => "''Infelizmente, durante as verificações do ambiente, o instalador não detectou a presença de uma ligação à internet. Por isso, poderá não ser possível activar esta funcionalidade.
Se o seu servidor está por detrás de um proxy, pode ter de fazer algumas [http://www.mediawiki.org/wiki/Manual:\$wgHTTPProxy configurações adicionais].''",
	'config-cc-error' => 'O auxiliar de escolha de licenças da Creative Commons não produziu resultados.
Introduza o nome da licença manualmente.',
	'config-cc-again' => 'Escolha outra vez...',
	'config-cc-not-chosen' => 'Escolha a licença da Creative Commons que pretende e clique "continuar".',
	'config-advanced-settings' => 'Configuração avançada',
	'config-cache-options' => 'Definições da cache de objectos:',
	'config-cache-help' => 'A cache de objectos é usada para melhorar o desempenho do MediaWiki. Armazena dados usados com frequência.
Sites de tamanho médio ou grande são altamente encorajados a activar esta funcionalidade e os sites pequenos também terão alguns benefícios em fazê-lo.',
	'config-cache-none' => 'Sem cache (não é removida nenhuma funcionalidade, mas a velocidade de operação pode ser afectada nas wikis grandes)',
	'config-cache-accel' => 'Cache de objectos do PHP (APC, eAccelerator, XCache ou WinCache)',
	'config-cache-memcached' => 'Usar Memcached (requer instalação e configurações adicionais)',
	'config-memcached-servers' => 'Servidores Memcached:',
	'config-memcached-help' => 'Lista de endereços IP que serão usados para Memcached.
Devem ser separados por vírgulas e especificar a porta a utilizar (por exemplo: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Extensões',
	'config-extensions-help' => 'Foi detectada a existência das extensões listadas acima, no seu directório <code>./extensions</code>.

Estas talvez necessitem de configurações adicionais, mas pode activá-las agora',
	'config-install-alreadydone' => "'''Aviso:''' Parece que já instalou o MediaWiki e está a tentar instalá-lo novamente.
Passe para a próxima página, por favor.",
	'config-install-step-done' => 'terminado',
	'config-install-step-failed' => 'falhou',
	'config-install-extensions' => 'A incluir as extensões',
	'config-install-database' => 'A preparar a base de dados',
	'config-install-pg-schema-failed' => 'A criação das tabelas falhou.
Certifique-se de que o utilizador "$1" pode escrever no esquema \'\'(schema)\'\' "$2".',
	'config-install-user' => 'A criar o utilizador da base de dados',
	'config-install-user-failed' => 'A atribuição das permissões ao utilizador "$1" falhou: $2',
	'config-install-tables' => 'A criar as tabelas',
	'config-install-tables-exist' => "'''Aviso''': As tabelas do MediaWiki parecem já existir.
A criação das tabelas será saltada.",
	'config-install-tables-failed' => "'''Erro''': A criação das tabelas falhou com o seguinte erro: $1",
	'config-install-interwiki' => 'A preencher a tabela padrão de interwikis',
	'config-install-interwiki-sql' => 'Não foi possível encontrar o ficheiro <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Aviso''': A tabela de interwikis parece já conter entradas.
O preenchimento padrão desta tabela será saltado.",
	'config-install-secretkey' => 'A gerar a chave secreta',
	'config-insecure-secretkey' => "'''Aviso:''' Não foi possível criar a chave secreta <code>\$wgSecretKey</code>.
Considere alterá-la manualmente.",
	'config-install-sysop' => 'A criar a conta de administrador',
	'config-install-done' => "'''Parabéns!'''
Terminou a instalação do MediaWiki.

O instalador gerou um ficheiro <code>LocalSettings.php</code>.
Este ficheiro contém todas as configurações.

Precisa de fazer o [$1 download] do ficheiro e colocá-lo no directório de raiz da sua instalação (o mesmo directório onde está o ficheiro index.php).
'''Nota''': Se não fizer isto agora, o ficheiro que foi gerado deixará de estar disponível quando sair do processo de instalação.

Depois de terminar o passo anterior, pode '''[$2 entrar na wiki]'''.",
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Giro720
 * @author Marcionunes
 */
$messages['pt-br'] = array(
	'config-desc' => 'O instalador do MediaWiki',
	'config-title' => 'Instalação MediaWiki $1',
	'config-information' => 'Informações',
	'config-localsettings-upgrade' => "'''Aviso''': Foi detetada a existência de um arquivo <code>LocalSettings.php</code>.
É possível atualizar o seu software.
Mova o <code>LocalSettings.php</code> para um lugar seguro e execute o instalador novamente, por favor.",
	'config-localsettings-noupgrade' => "'''Erro''': Foi detetada a existência de um arquivo <code>LocalSettings.php</code>.
Não é possível atualizar o seu software neste momento.
Por razões de segurança, o instalador foi desativado.",
	'config-session-error' => 'Erro ao iniciar a sessão: $1',
	'config-session-expired' => 'Os seus dados de sessão parecem ter expirado.
As sessões estão configuradas para uma duração de $1.
Você pode aumentar esta duração configurando <code>session.gc_maxlifetime</code> no php.ini.
Reinicie o processo de instalação.',
	'config-no-session' => 'Os seus dados de sessão foram perdidos!
Verifique o seu php.ini e certifique-se de que em <code>session.save_path</code> está definido um diretório apropriado.',
	'config-logo-help' => 'O tema padrão do MediaWiki inclui espaço para um logotipo de 135x160 pixels no canto superior esquerdo.
Faça o upload de uma imagem com estas dimensões e introduza aqui a URL dessa imagem.

Se você não pretende usar um logotipo, deixe este campo em branco.',
);

/** Russian (Русский)
 * @author DCamer
 * @author Eleferen
 * @author MaxSem
 * @author Александр Сигачёв
 * @author Сrower
 */
$messages['ru'] = array(
	'config-desc' => 'Инсталлятор MediaWiki',
	'config-title' => 'Установка MediaWiki $1',
	'config-information' => 'Информация',
	'config-localsettings-upgrade' => "'''Внимание''': был обнаружен файл <code>LocalSettings.php</code>. 
Ваше программное обеспечение может быть обновлено. 
Пожалуйста, переместите файл <code>LocalSettings.php</code> в другую безопасную директорию, а затем снова запустите программу установки.",
	'config-localsettings-noupgrade' => "'''Ошибка''': был обнаружен файл <code>LocalSettings.php</code>. 
Ваше программное обеспечение не может быть обновлено в данный момент.
Установки была приостановлена в целях безопасности.",
	'config-session-error' => 'Ошибка при запуске сессии: $1',
	'config-session-expired' => 'Ваша сессия истекла. 
Сессии настроены на длительность: $1. 
Вы можете увеличить, установив <code>session.gc_maxlifetime</code> в php.ini. 
Перезапустите процесс установки.',
	'config-no-session' => 'Данные сессии потеряны! 
Проверьте ваш php.ini и убедитесь, что <code>session.save_path</code> установлен в соответствующий каталог.',
	'config-session-path-bad' => 'Ваш <code>session.save_path</code> (<code>$1</code>), недействителен или не перезаписываемый.',
	'config-show-help' => 'Справка',
	'config-hide-help' => 'Скрыть справку',
	'config-your-language' => 'Ваш язык:',
	'config-your-language-help' => 'Выберите язык, на котором будет происходить процесс установки.',
	'config-wiki-language' => 'Язык, который будет использовать вики:',
	'config-wiki-language-help' => 'Выберите язык, на котором будут отображаться вики.',
	'config-back' => '← Назад',
	'config-continue' => 'Далее →',
	'config-page-language' => 'Язык',
	'config-page-welcome' => 'Добро пожаловать в MediaWiki!',
	'config-page-dbconnect' => 'Подключение к базе данных',
	'config-page-upgrade' => 'Обновление существующей установки',
	'config-page-dbsettings' => 'Настройки базы данных',
	'config-page-name' => 'Название',
	'config-page-options' => 'Настройки',
	'config-page-install' => 'Установка',
	'config-page-complete' => 'Готово!',
	'config-page-restart' => 'Начать установку заново',
	'config-page-readme' => 'Прочти меня',
	'config-page-releasenotes' => 'Информация о версии',
	'config-page-copying' => 'Копирование',
	'config-page-upgradedoc' => 'Обновление',
	'config-help-restart' => 'Вы хотите удалить все сохранённые данные, которые вы ввели, и запустить процесс установки заново?',
	'config-restart' => 'Да, начать заново',
	'config-welcome' => '=== Проверка окружения ===
Проводятся базовые проверки с целью определить, подходит ли данная система для установки MediaWiki.
Укажите результаты этих проверок при обращении за помощью с установкой.',
	'config-copyright' => "=== Авторские права и условия === 

$1

MediaWiki является свободным программным обеспечением, которое вы можете распространять и/или изменять в соответствии с условиями лицензии GNU General Public License, опубликованной фондом свободного программного обеспечения; второй версии, либо любой более поздней версии.

MediaWiki распространяется в надежде, что она будет полезной, но '''без каких-либо гарантий''', даже без подразумеваемых гарантий '''коммерческой ценности''' или '''пригодности для определённой цели'''. См. лицензию GNU General Public License для более подробной информации.

Вы должны были получить <doclink href=Copying>копию GNU General Public License</doclink> вместе с этой программой, если нет, то напишите Free Software Foundation, Inc., по адресу: 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA или [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html прочтите её онлайн].",
	'config-sidebar' => '* [http://www.mediawiki.org Сайт MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents/ru Справка для пользователей]
* [http://www.mediawiki.org/wiki/Manual:Contents/ru Справка для администраторов]
* [http://www.mediawiki.org/wiki/Manual:FAQ/ru FAQ]',
	'config-env-good' => '<span class="success-message">Проверка внешней среды была успешно проведена.
Вы можете установить MediaWiki.</span>',
	'config-env-bad' => 'Была проведена проверка внешней среды. 
Вы не можете установить MediaWiki.',
	'config-env-php' => 'Установленная версия PHP: $1.',
	'config-env-latest-ok' => 'Вы устанавливаете последнюю версию MediaWiki.',
	'config-env-latest-new' => "'''Внимание:''' Вы устанавливаете находящуюся в разработке версию MediaWiki.",
	'config-env-latest-can-not-check' => "'''Внимание:''' Инсталлятор не смог получить информацию о последней версии MediaWiki от [$1].",
	'config-env-latest-old' => "'''Внимание:''' Вы устанавливаете устаревшую версию MediaWiki.",
	'config-env-latest-help' => 'Вы устанавливаете версию $1, однако, последняя версия: $2.
Рекомендуем использовать последнюю версию MediaWiki, которую можно скачать с сайта: [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Использовать медленный вариант реализации PHP для нормализации Юникода.',
	'config-unicode-using-utf8' => 'Использовать Brion Vibber utf8_normalize.so для нормализации Юникода.',
	'config-unicode-using-intl' => 'Использовать [http://pecl.php.net/intl международный расширение PECL] для нормализации Юникода.',
	'config-unicode-pure-php-warning' => "'''Внимание!''': [http://pecl.php.net/intl международное расширение PECL] недоступно для нормализации Юникода.
Если Ваш сайт работает под высокой нагрузкой, Вам следует больше узнать о [http://www.mediawiki.org/wiki/Unicode_normalization_considerations нормализации Юникода].",
	'config-unicode-update-warning' => "'''Предупреждение''': установленная версия обёртки нормализации Юникода использует старую версию библиотеки [http://site.icu-project.org/ проекта ICU].
Вы должны [http://www.mediawiki.org/wiki/Unicode_normalization_considerations обновить версию], если хотите полноценно использовать Юникод.",
	'config-no-db' => 'Не найдено поддержки баз данных!',
	'config-no-db-help' => 'Вам необходимо установить драйвера базы данных для PHP. 
Поддерживаются следующие типы баз данных: $1. 

Если вы используете виртуальный хостинг, обратитесь к своему хостинг-провайдеру с просьбой установить подходящий драйвер базы данных. 
Если вы скомпилировали PHP сами, сконфигурируйте его снова с включенным клиентом базы данных, например, с помощью <code>./configure --with-mysql</code>. 
Если вы установили PHP из пакетов Debian или Ubuntu, то вам также необходимо установить модуль php5-mysql.',
	'config-have-db' => 'Обнаружена поддержка {{PLURAL:$2|базы|баз}} данных $1.',
	'config-register-globals' => "'''Внимание: PHP-опция <code>[http://php.net/register_globals register_globals] включена.'''
'''Отключите её, если это возможно.'''
MediaWiki будет работать, но это снизит безопасность сервера и увеличит риск проникновения извне.",
	'config-magic-quotes-runtime' => "'''Проблема: включена опция PHP [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]!'''
Это приводит к непредсказуемой порче вводимых данных.
Установка и использование MediaWiki без выключения этой опции невозможно.",
	'config-magic-quotes-sybase' => "'''Проблема: включена опция PHP [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]!'''
Это приводит к непредсказуемой порче вводимых данных.
Установка и использование MediaWiki без выключения этой опции невозможно.",
	'config-mbstring' => "'''Проблема: включена опция PHP [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]!'''
Это приводит к ошибкам и непредсказуемой порче вводимых данных.
Установка и использование MediaWiki без выключения этой опции невозможно.",
	'config-ze1' => "'''Проблема: включена опция PHP [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]!'''
Это приводит к катастрофическим сбоям в MediaWiki.
Установка и использование MediaWiki без выключения этой опции невозможно.",
	'config-safe-mode' => "'''Предупреждение:''' PHP работает в [http://www.php.net/features.safe-mode «безопасном режиме»].
Это может привести к проблемам, особенно с загрузкой файлов и вставкой математических формул.",
	'config-xml-good' => 'Поддержка преобразования XML / Latin1-UTF-8.',
	'config-xml-bad' => 'XML-модуль РНР отсутствует.
MediaWiki не будет работать в этой конфигурации, так как требуется функционал этого модуля.
Если вы работаете в Mandrake, установите PHP XML-пакет.',
	'config-pcre' => 'Модуль поддержки PCRE не найден. 
Для работы MediaWiki требуется поддержка Perl-совместимых регулярных выражений.',
	'config-memory-none' => 'PHP настроен без <code>memory_limit</code>',
	'config-memory-ok' => 'Конфигурация PHP <code>memory_limit</code>: $1. 
Всё хорошо.',
	'config-memory-raised' => 'Ограничение на доступную PHP память (<code>memory_limit</code>) поднято с $1 до $2.',
	'config-memory-bad' => "'''Внимание:''' размер PHP <code>memory_limit</code> составляет $1.
Вероятно, этого слишком мало.
Установка может потерпеть неудачу!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] установлен',
	'config-apc' => '[http://www.php.net/apc APC] установлен',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] установлен',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] установлен',
	'config-no-cache' => "'''Внимание:''' Не найдены [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] или [http://www.iis.net/download/WinCacheForPhp WinCache].
Кэширование объектов будет отключено.",
	'config-diff3-good' => 'Найден GNU diff3: <code>$1</code> .',
	'config-diff3-bad' => 'GNU diff3 не найден.',
	'config-imagemagick' => 'Был найден ImageMagick: <code>$1</code>.
Возможно отображение миниатюр изображений, если вы разрешите закачки файлов.',
	'config-gd' => 'Найдена встроенная графическая библиотека GD. 
Возможность использования миниатюр изображений будет включена, если вы включите их загрузку.',
	'config-no-scaling' => 'Не удалось найти встроенную библиотеку GD или ImageMagick.
Возможность использования миниатюр изображений будет отключена.',
	'config-dir' => 'Каталог установки: <code>$1</code>.',
	'config-uri' => 'URI скрипта: <code>$1</code>.',
	'config-no-uri' => "'''Ошибка:''' Не могу определить текущий URI. 
Установка прервана.",
	'config-dir-not-writable-group' => "'''Ошибка.''' Невозможно записать файл конфигурации. 
Установка прервана. 

Установщик определил пользователя, от имени которого работает веб-сервер.
Сделайте для него доступной на запись директорию <code><nowiki>config</nowiki></code>.
В Unix/Linux системах: 

<pre>cd $1
chgrp $2 config
chmod g+w config</pre>",
	'config-dir-not-writable-nogroup' => "'''Ошибка.''' Невозможно записать файл конфигурации. 
Установка прервана. 

Не удалось определить пользователя, от имени которого запущен веб-сервер.
Для продолжения сделайте директорию <code><nowiki>config</nowiki></code> доступной на запись для всех пользователей.
В Unix/Linux системах: 

<pre>cd $1
chmod a+w config</pre>",
	'config-file-extension' => 'Установка MediaWiki с расширениями файлов <code>$1</code>.',
	'config-shell-locale' => 'Определена локаль оболочки — $1',
	'config-uploads-safe' => 'Директория по умолчанию для загрузок безопасна от выполнения произвольных скриптов.',
	'config-uploads-not-safe' => "'''Внимание.''' Ваша директория по умолчанию для загрузок <code>$1</code> уязвима к выполнению произвольных скриптов. 
Хотя MediaWiki проверяет все загружаемые файлы на наличие угроз, настоятельно рекомендуется [http://www.mediawiki.org/wiki/Manual:Security#Upload_security закрыть данную уязвимость] перед включением загрузки файлов.",
	'config-db-type' => 'Тип базы данных:',
	'config-db-host' => 'Хост базы данных:',
	'config-db-host-help' => 'Если сервер базы данных находится на другом сервере, введите здесь его имя хоста или IP-адрес. 

Если вы используете виртуальный хостинг, ваш провайдер должен указать правильное имя хоста в своей документации.

Если вы устанавливаете систему на сервере под Windows и используете MySQL, имя сервера «localhost» может не работать. В этом случае попробуйте указать «127.0.0.1».',
	'config-db-wiki-settings' => 'Идентификация этой вики',
	'config-db-name' => 'Имя базы данных:',
	'config-db-name-help' => 'Выберите название-идентификатор для вашей вики. 
Оно не должно содержать пробелов и дефисов. 

Если вы используете виртуальный хостинг, провайдер или выдаст вам конкретное имя базы данных, или позволит создавать базы данных с помощью панели управления.',
	'config-db-install-account' => 'Учётная запись для установки',
	'config-db-username' => 'Имя пользователя базы данных:',
	'config-db-password' => 'Пароль базы данных:',
	'config-db-install-help' => 'Введите имя пользователя и пароль, которые будут использоваться для подключения к базе данных во время процесса установки.',
	'config-db-account-lock' => 'Использовать то же имя пользователя и пароль для обычной работы',
	'config-db-wiki-account' => 'Учётная запись для обычной работы',
	'config-db-wiki-help' => 'Введите имя пользователя и пароль, которые будут использоваться для подключения к базе данных во время обычной работы вики. 
Если такой учётной записи не существует, а установочная учётная запись имеет достаточно привилегий, то обычная учётная запись будет создана с минимально необходимыми для работы вики привилегиями.',
	'config-db-prefix' => 'Префикс таблиц базы данных:',
	'config-db-prefix-help' => 'Если вам нужно делить одну базу данных между несколькими вики, или между MediaWiki и другими веб-приложениями, вы можете добавить префикс для всех имён таблиц. 
Не используйте пробелы и дефисы. 

Это поле, как правило, остаётся пустым.',
	'config-db-charset' => 'Набор символов базы данных',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 бинарная',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 обратно совместимая с UTF-8',
	'config-charset-help' => "'''Внимание.''' Если вы используете '''обратно совместый UTF-8''' на MySQL 4.1+ и создаёте резервные копии базы данных с помощью <code>mysqldump</code>, то все не-ASCII символы могут быть искажены, а резервная копия окажется негодной! 

В '''бинарном режиме''' MediaWiki хранит юникодный текст в базе в виде двоичных полей. 
Это более эффективно, чем MySQL в режиме UTF-8, позволяет использовать полный набор символов Юникода. 
В '''режиме UTF-8''' MySQL будет знать к какому набору символу относятся ваши данные, сможет представлять и преобразовать их надлежащим образом (буква Ё окажется при сортировке после буквы Е, а не после буквы Я, как в бинарном режиме), 
но не позволит вам сохранять символы, выходящие за пределы [http://ru.wikipedia.org/wiki/Символы,_представленные_в_Юникоде#.D0.91.D0.B0.D0.B7.D0.BE.D0.B2.D0.B0.D1.8F_.D0.BC.D0.BD.D0.BE.D0.B3.D0.BE.D1.8F.D0.B7.D1.8B.D0.BA.D0.BE.D0.B2.D0.B0.D1.8F_.D0.BF.D0.BB.D0.BE.D1.81.D0.BA.D0.BE.D1.81.D1.82.D1.8C BMP].",
	'config-mysql-old' => 'Необходим MySQL $1 или более поздняя версия. У вас установлен MySQL $2.',
	'config-db-port' => 'Порт базы данных:',
	'config-db-schema' => 'Схема для MediaWiki',
	'config-db-ts2-schema' => 'Схема для tsearch2',
	'config-db-schema-help' => 'Вышеприведённые схемы, как правило, работают нормально. 
Изменяйте их только если знаете, зачем это необходимо.',
	'config-sqlite-dir' => 'Директория данных SQLite:',
	'config-sqlite-dir-help' => "SQLite хранит все данные в одном файле. 

Директория, которую вы должны указать, должна быть доступна для записи веб-сервером во время установки. 

Она '''не должна''' быть доступна через Интернет, поэтому не должна совпадать с той, где хранятся PHP файлы.

Установщик запишет в эту директорию файл <code>.htaccess</code>, но если это не сработает, кто-нибудь может получить доступ ко всей базе данных.
В этой базе находится в том числе и информация о пользователях (адреса электронной почты, хэши паролей), а также удалённые страницы и другие секретные данные о вики. 

По возможности, расположите базу данных где-нибудь в стороне, например, в <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki поддерживает следующие СУБД: 

$1 

Если вы не видите своей системы хранения данных в этом списке, следуйте инструкциям, на которые есть ссылка выше, чтобы получить поддержку.',
	'config-support-mysql' => '* $1 — основная база данных для MediaWiki, и лучше поддерживается ([http://www.php.net/manual/en/mysql.installation.php инструкция, как собрать PHP с поддержкой MySQL])',
	'config-support-postgres' => '* $1 — популярная открытая СУБД, альтернатива MySQL ([http://www.php.net/manual/en/pgsql.installation.php инструкция, как собрать PHP с поддержкой PostgreSQL])',
	'config-support-sqlite' => '* $1 — это лёгковесная система баз данных, имеющая очень хорошую поддержку. ([http://www.php.net/manual/en/pdo.installation.php Инструкция, как скомпилировать PHP с поддержкой SQLite], работающей посредством PDO)',
	'config-header-mysql' => 'Настройки MySQL',
	'config-header-postgres' => 'Настройки PostgreSQL',
	'config-header-sqlite' => 'Настройки SQLite',
	'config-header-oracle' => 'Настройки Oracle',
	'config-invalid-db-type' => 'Неверный тип базы данных',
	'config-missing-db-name' => 'Вы должны ввести значение параметра «Имя базы данных»',
	'config-invalid-db-name' => 'Неверное имя базы данных «$1».
Используйте только ASCII-символы (a-z, A-Z), цифры (0-9) и знак подчёркивания (_).',
	'config-invalid-db-prefix' => 'Неверный префикс базы данных «$1».
Используйте только ASCII-символы (a-z, A-Z), цифры(0-9) и знак подчёркивания(_).',
	'config-connection-error' => '$1.

Проверьте хост, имя пользователя и пароль и попробуйте ещё раз.',
	'config-invalid-schema' => 'Неправильная схема для MediaWiki «$1». 
Используйте только ASCII символы (a-z, A-Z), цифры(0-9) и знаки подчёркивания(_).',
	'config-invalid-ts2schema' => 'Неправильная схема для TSearch2 «$1». 
Используйте только ASCII символы (a-z, A-Z), цифры(0-9) и знаки подчёркивания(_).',
	'config-postgres-old' => 'Необходим PostgreSQL $1 или более поздняя версия. У вас установлен PostgreSQL $2.',
	'config-sqlite-name-help' => 'Выберите имя-идентификатор для вашей вики. 
Не используйте дефисы и пробелы.
Эта строка будет использоваться в имени файла SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Не удалось создать директорию данных <nowiki><code>$1</code></nowiki>, так как у веб-сервера нет прав записи в родительскую директорию <nowiki><code>$2</code></nowiki>. 

Установщик определил пользователя, под которым работает веб-сервер. 
Сделайте директорию <nowiki><code>$3</code></nowiki> доступной для записи и продолжите. 
В Unix/Linux системе выполните: 

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Не удалось создать директорию для данных <code><nowiki>$1</nowiki></code>, так как у веб-сервера нет прав на запись в родительскую директорию <code><nowiki>$2</nowiki></code>. 

Программа установки не смогла определить пользователя, под которым работает веб-сервер. 
Для продолжения сделайте каталог <code><nowiki>$3</nowiki></code> глобально доступным для записи серверу (и другим). 
В Unix/Linux сделайте: 

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Ошибка при создании каталога данных «$1».
Проверьте расположение и повторите попытку.',
	'config-sqlite-dir-unwritable' => 'Невозможно произвести запись в каталог «$1».
Измените настройки доступа так, чтобы веб-сервер мог записывать в этот каталог, и попробуйте ещё раз.',
	'config-sqlite-connection-error' => '$1. 

Проверьте название базы данных и директорию с данными и попробуйте ещё раз.',
	'config-sqlite-readonly' => 'Файл <code>$1</code> недоступен для записи.',
	'config-sqlite-cant-create-db' => 'Не удаётся создать файл базы данных <code>$1</code> .',
	'config-sqlite-fts3-downgrade' => 'У PHP отсутствует поддержка FTS3 — сбрасываем таблицы',
	'config-sqlite-fts3-add' => 'Добавление возможности поиска через FTS3',
	'config-can-upgrade' => "В базе данных найдены таблицы MediaWiki. 
Чтобы обновить их до MediaWiki $1, нажмите на кнопку '''«Продолжить»'''.",
	'config-upgrade-done' => "Обновление завершено. 

Теперь вы можете [$1 начать использовать вики]. 

Если вы хотите повторно создать файл <code>LocalSettings.php</code>, нажмите на кнопку ниже. 
Это действие '''не рекомендуется''', если у вас не возникло проблем при установке.",
	'config-regenerate' => 'Создать LocalSettings.php заново →',
	'config-show-table-status' => 'Запрос «SHOW TABLE STATUS» не выполнен!',
	'config-unknown-collation' => "'''Внимание:''' База данных использует нераспознанные правила сортировки.",
	'config-db-web-account' => 'Учётная запись для доступа к базе данных из веб-сервера',
	'config-db-web-help' => 'Выберите имя пользователя и пароль, которые веб-сервер будет использовать для подключения к серверу базы данных при обычной работе вики.',
	'config-db-web-account-same' => 'Использовать ту же учётную запись, что и для установки',
	'config-db-web-create' => 'Создать учётную запись, если она ещё не существует',
	'config-db-web-no-create-privs' => 'Учётная запись, указанная вами для установки, не обладает достаточными правами для создания учётной записи. 
Указанная здесь учётная запись уже должна существовать.',
	'config-mysql-engine' => 'Движок базы данных:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' почти всегда предпочтительнее, так как он лучше справляется с параллельным доступом.

'''MyISAM''' может оказаться быстрее для вики с одним пользователем или с минимальным количеством поступающих правок, однако базы данных на нём портятся чаще, чем на InnoDB.",
	'config-mysql-egine-mismatch' => "'''Внимание:''' Вы запросили метод хранения $1, однако существующая база данных использует $2. 
Этот сценарий обновления не может изменить преобразовать его и поэтому метод хранения останется $2.",
	'config-mysql-charset' => 'Набор символов (кодовая таблица) базы данных:',
	'config-mysql-binary' => 'Двоичный',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "В '''двоичном режиме''' MediaWiki хранит UTF-8 текст в бинарных полях базы данных. 
Это более эффективно, чем ''UTF-8 режим'' MySQL, и позволяет использовать полный набор символов Unicode. 

В '''режиме UTF-8''' MySQL будет знать в какой кодировке находятся Ваши данные и может отображать и преобразовывать их соответствующим образом, но это не позволит вам хранить символы выше [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Базовой Многоязыковой Плоскости].",
	'config-site-name' => 'Название вики:',
	'config-site-name-help' => 'Название будет отображаться в заголовке окна браузера и в некоторых других местах вики.',
	'config-site-name-blank' => 'Введите название сайта.',
	'config-project-namespace' => 'Пространство имён проекта:',
	'config-ns-generic' => 'Проект',
	'config-ns-site-name' => 'То же, что имя вики: $1',
	'config-ns-other' => 'Другие (укажите)',
	'config-ns-other-default' => 'MyWiki',
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
	'config-admin-error-user' => 'Внутренняя ошибка при создании учётной записи администратора с именем «<nowiki>$1</nowiki>».',
	'config-admin-error-password' => 'Внутренняя ошибка при установке пароля для учётной записи администратора «<nowiki>$1</nowiki>»: <pre>$2</pre>',
	'config-subscribe' => 'Подписаться на [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce рассылку новостей о появлении новых версий MediaWiki].',
	'config-almost-done' => 'Вы почти у цели! 
Остальные настройки можно пропустить и приступить к установке вики.',
	'config-optional-continue' => 'Произвести тонкую настройку',
	'config-optional-skip' => 'Хватит, установить вики',
	'config-profile-wiki' => 'Традиционная вики',
	'config-profile-no-anon' => 'Требуется создание учётной записи',
	'config-profile-fishbowl' => 'Только для авторизованых редакторов',
	'config-profile-private' => 'Частная wiki',
	'config-license' => 'Авторские права и лицензии:',
	'config-license-none' => 'Не указывать лицензию в колонтитуле внизу страницы',
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike (совместимая с Wikipedia)',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 или более поздней версии',
	'config-license-pd' => 'Общественное достояние',
	'config-license-cc-choose' => 'Выберите одну из лицензий Creative Commons',
	'config-email-settings' => 'Настройки электронной почты',
	'config-enable-email' => 'Включить исходящие e-mail',
	'config-enable-email-help' => 'Если вы хотите, чтобы электронная почта работала, необходимо выполнить [http://www.php.net/manual/en/mail.configuration.php соответствующие настройки PHP].
Если вы не хотите использовать возможности электронной почты в вики, вы можете её отключить.',
	'config-email-user' => 'Включить электронную почту от участника к участнику',
	'config-email-user-help' => 'Разрешить всем пользователям отправлять друг другу электронные письма, если выставлена соответствующая настройка в профиле.',
	'config-email-usertalk' => 'Включить уведомления пользователей о сообщениях на их странице обсуждения',
	'config-email-watchlist' => 'Включить уведомление на электронную почту об изменении списка наблюдения',
	'config-email-auth' => 'Включить аутентификацию через электронную почту',
	'config-email-auth-help' => "Если эта опция включена, пользователи должны подтвердить свой адрес электронной почты перейдя по ссылке, которая отправляется на e-mail. Подтверждение требуется каждый раз при смене электронного ящика в настройках пользователя.
Только прошедшие проверку подлинности адреса электронной почты, могут получать электронные письма от других пользователей или изменять уведомления, отправляемые по электронной почте.
Включение этой опции '''рекомендуется'''  для открытых вики в целях пресечения потенциальных злоупотреблений возможностями электронной почты.",
	'config-upload-settings' => 'Загрузка изображений и файлов',
	'config-upload-enable' => 'Разрешить загрузку файлов',
	'config-upload-help' => 'Разрешение загрузки файлов, потенциально, может привести к угрозе безопасности сервера.
Для получения дополнительной информации, прочтите в руководстве [http://www.mediawiki.org/wiki/Manual:Security раздел, посвящённый безопасности].

Чтобы разрешить загрузку файлов, необходимо изменить права на каталог <code>images</code>, в корневой директории MediaWiki так, чтобы веб-сервер мог записывать в него файлы.
Затем включите эту опцию.',
	'config-upload-deleted' => 'Директория для удалённых файлов:',
	'config-upload-deleted-help' => 'Выберите каталог, в котором будут храниться архивы удалённых файлов.
В идеальном случае, в этот каталог не должно быть доступа из сети Интернет.',
	'config-logo' => 'URL логотипа:',
	'config-instantcommons' => 'Включить Instant Commons',
	'config-cc-again' => 'Выберите ещё раз…',
	'config-cc-not-chosen' => 'Выберите, какую лицензию Creative Commons Вы хотите использовать, и нажмите кнопку "Продолжить".',
	'config-advanced-settings' => 'Дополнительные настройки',
	'config-cache-options' => 'Параметры кэширования объектов:',
	'config-cache-help' => 'Кэширование объектов используется для повышения скорости MediaWiki путем кэширования часто используемых данных. 
Для средних и больших сайтов кеширование настоятельно рекомендуется включать, а для небольших сайтов кеширование может показать преимущество.',
	'config-cache-none' => 'Без кэширования (никакой функционал не теряется, но крупные вики-сайты могут работать медленнее)',
	'config-cache-accel' => 'PHP кэширование объектов (APC, eAccelerator, XCache или WinCache)',
	'config-cache-memcached' => 'Использовать Memcached (требует дополнительной настройки)',
	'config-memcached-servers' => 'Сервера Memcached:',
	'config-memcached-help' => 'Список IP-адресов, используемых Memcached. 
Адреса должны быть разделены запятыми и указывать порт, который будет использоваться (например, 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Расширения',
	'config-extensions-help' => 'Расширения MediaWiki, перечисленные выше, были найдены в каталоге <code>./extensions</code>. 

Они могут потребовать дополнительные настройки, но их можно включить прямо сейчас',
	'config-install-alreadydone' => "'''Предупреждение:''' Вы, кажется, уже устанавливали MediaWiki и пытаетесь произвести повторную установку.
Пожалуйста, перейдите на следующую страницу.",
	'config-install-step-done' => 'выполнено',
	'config-install-step-failed' => 'не удалось',
	'config-install-extensions' => 'В том числе расширения',
	'config-install-database' => 'Настройка базы данных',
	'config-install-pg-schema-failed' => 'Не удалось создать таблицы.
Убедитесь в том, что пользователь «$1» может писать в схему «$2».',
	'config-install-user' => 'Создание базы данных пользователей',
	'config-install-user-failed' => 'Ошибка предоставления прав пользователю «$1»: $2',
	'config-install-tables' => 'Создание таблиц',
	'config-install-tables-exist' => "'''Предупреждение''': таблицы MediaWiki, возможно, уже существуют.
Пропуск повторного создания.",
	'config-install-tables-failed' => "'''Ошибка''': Таблица не может быть создана из-за ошибки: $1",
	'config-install-interwiki' => 'Заполнение таблицы интервики значениями по умолчанию',
	'config-install-interwiki-sql' => 'Не удалось найти файл <code>interwiki.sql</code>.',
	'config-install-interwiki-exists' => "'''Предупреждение''': в интервики-таблице, кажется, уже есть записи.
Создание стандартного списка, пропущено.",
	'config-install-secretkey' => 'Создание секретного ключа',
	'config-insecure-secretkey' => "'''Внимание:''' Не получилось создать безопасный секретный ключ (<code>\$wgSecretKey</code>).
По возможности, смените его вручную.",
	'config-install-sysop' => 'Создание учётной записи администратора',
	'config-install-done' => "'''Поздравляем!'''
Вы успешно установили MediaWiki.

Во время установки был создан файл: <code>LocalSettings.php</code>.
Он содержит всю конфигурации вики.

Вам необходимо [$1 скачать] его и положить в корневую директорию вашей вики (в ту же директорию, где находится файл index.php).
'''Примечание''': Если вы не сделаете этого сейчас, то сгенерированный файл конфигурации не будет доступен вам в дальнейшем, если вы выйдите из установки, не скачивая его.

По окончании действий, описанных выше, вы сможете '''[$2 войти в вашу вики]'''.",
);

/** Slovenian (Slovenščina)
 * @author Dbc334
 */
$messages['sl'] = array(
	'config-desc' => 'Namestitveni program za MediaWiki',
	'config-title' => 'Namestitev MediaWiki $1',
	'config-information' => 'Informacije',
	'config-show-help' => 'Pomoč',
	'config-hide-help' => 'Skrij pomoč',
	'config-your-language' => 'Vaš jezik:',
	'config-back' => '← Nazaj',
	'config-continue' => 'Nadaljuj →',
	'config-page-language' => 'Jezik',
	'config-page-welcome' => 'Dobrodošli na MediaWiki!',
	'config-page-name' => 'Ime',
	'config-page-options' => 'Možnosti',
	'config-page-install' => 'Namesti',
	'config-page-complete' => 'Končano!',
	'config-page-readme' => 'Beri me',
	'config-page-copying' => 'Kopiranje',
	'config-page-upgradedoc' => 'Nadgrajevanje',
	'config-db-name' => 'Ime zbirke podatkov:',
	'config-db-username' => 'Uporabniško ime zbirke podatkov:',
	'config-db-password' => 'Geslo zbirke podatkov:',
	'config-admin-password' => 'Geslo:',
);

/** Serbian Cyrillic ekavian (Српски (ћирилица)) */
$messages['sr-ec'] = array(
	'config-show-help' => 'Помоћ',
	'config-continue' => 'Настави →',
	'config-page-language' => 'Језик',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'config-title' => 'మీడియావికీ $1స్థాపన',
	'config-information' => 'సమాచారం',
	'config-show-help' => 'సహాయం',
	'config-your-language' => 'మీ భాష:',
	'config-wiki-language' => 'వికీ భాష:',
	'config-back' => '← వెనక్కి',
	'config-continue' => 'కొనసాగించు →',
	'config-page-language' => 'భాష',
	'config-page-welcome' => 'మీడియావికీకి స్వాగతం!',
	'config-page-name' => 'పేరు',
	'config-page-options' => 'ఎంపికలు',
	'config-page-install' => 'స్థాపించు',
	'config-page-complete' => 'పూర్తయ్యింది!',
	'config-page-readme' => 'నన్ను చదవండి',
	'config-site-name' => 'వికీ యొక్క పేరు:',
	'config-admin-name' => 'మీ పేరు:',
	'config-admin-password' => 'సంకేతపదం:',
	'config-admin-password-confirm' => 'సంకేతపదం మళ్ళీ:',
	'config-admin-email' => 'ఈ-మెయిలు చిరునామా:',
	'config-profile-private' => 'అంతరంగిక వికీ',
	'config-email-settings' => 'ఈ-మెయిల్ అమరికలు',
);

/** Ukrainian (Українська)
 * @author Ahonc
 * @author Alex Khimich
 * @author Diemon.ukr
 * @author Тест
 */
$messages['uk'] = array(
	'config-desc' => 'Інсталятор MediaWiki',
	'config-title' => 'Встановлення MediaWiki $1',
	'config-information' => 'Інформація',
	'config-localsettings-upgrade' => "'''Увага''': було виявлено файл <code>LocalSettings.php</code>. 
Ваше програмне забезпечення може бути оновлено. 
Будь-ласка, перемістіть файл <code>LocalSettings.php</code> в іншу безпечну директорію, а потім знову запустіть програму установки.",
	'config-session-error' => 'Помилка початку сесії: $1',
	'config-show-help' => 'Допомога',
	'config-hide-help' => 'Сховати допомогу',
	'config-your-language' => 'Ваша мова:',
	'config-your-language-help' => 'Оберіть мову для використання в процесі установки.',
	'config-wiki-language' => 'Мова для вікі:',
	'config-wiki-language-help' => 'Виберіть мову, якою буде відображатися вікі.',
	'config-back' => '← Назад',
	'config-continue' => 'Далі →',
	'config-page-language' => 'Мова',
	'config-page-welcome' => 'Ласкаво просимо на MediaWiki!',
	'config-page-dbconnect' => 'Підключення до бази даних',
	'config-page-upgrade' => 'Оновлення існуючої установки',
	'config-page-dbsettings' => 'Налаштування бази даних',
	'config-page-name' => 'Назва',
	'config-page-options' => 'Параметри',
	'config-page-install' => 'Установка',
	'config-page-complete' => 'Готово!',
	'config-page-restart' => 'Перезапустити установку',
	'config-page-readme' => 'Прочитай мене',
	'config-page-releasenotes' => 'Інформація про версію',
	'config-page-copying' => 'Копіювання',
	'config-page-upgradedoc' => 'Оновлення',
	'config-help-restart' => 'Ви бажаєте видалити всі введені та збережені вами дані і запустити процес установки спочатку?',
	'config-restart' => 'Так, перезапустити установку',
	'config-welcome' => '=== Перевірка оточення ===
Проводяться базові перевірки, щоб виявити, чи можлива установка MediaWiki у даній системі.
Вкажіть результати цих перевірок при зверненні за допомогою під час установки.',
	'config-sidebar' => '* [http://www.mediawiki.org Сайт MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents/uk Керівництво користувача]
* [http://www.mediawiki.org/wiki/Manual:Contents/uk Керівництво адміністратора]
* [http://www.mediawiki.org/wiki/Manual:FAQ/uk FAQ]',
	'config-env-good' => '<span class="success-message">Перевірку середовища успішно завершено.
Ви можете встановити MediaWiki.</span>',
	'config-env-bad' => 'Було проведено перевірку середовища. Ви не можете встановити MediaWiki.',
	'config-env-php' => 'Встановлено версію PHP: $1.',
	'config-env-latest-ok' => 'Ви встановлюєте останню версію MediaWiki.',
	'config-env-latest-new' => "'''Увага:''' Ви встановлюєте версію MediaWiki, яка ще знаходиться у розробці.",
	'config-env-latest-can-not-check' => "'''Увага:''' Програмі встановлення не вдалося отримати інформацію про останню версію MediaWiki починаючи від [$1].",
	'config-env-latest-old' => "'''Увага:''' Ви встановлюєте застарілу версію MediaWiki.",
	'config-env-latest-help' => 'Ви встановлюєте версію $1, але остання версія: $2. 
Рекомендуємо використовувати останню версію MediaWiki, яка може бути завантажена з сайту: [http://www.mediawiki.org/wiki/Download mediawiki.org]',
	'config-unicode-using-php' => 'Використовувати повільний варіант реалізації PHP для нормалізації Юнікоду.',
	'config-unicode-using-utf8' => 'Використовувати utf8_normalize.so Брайона Віббера для нормалізації Юнікоду.',
	'config-unicode-using-intl' => 'Використовувати [http://pecl.php.net/intl міжнародне розширення PECL] для нормалізації Юнікоду.',
	'config-unicode-pure-php-warning' => "'''Увага''': [http://pecl.php.net/intl міжнародне розширення PECL] не може провести нормалізацію Юнікоду. 
Якщо ваш сайт має високий трафік, вам варто почитати про [http://www.mediawiki.org/wiki/Unicode_normalization_considerations нормалізацію Юнікоду].",
);

/** Simplified Chinese (‪中文(简体)‬) */
$messages['zh-hans'] = array(
	'config-information' => '信息',
	'config-show-help' => '帮助',
	'config-continue' => '继续 →',
	'config-page-language' => '语言',
	'config-page-name' => '名称',
	'config-page-options' => '选项',
	'config-page-install' => '安装',
	'config-admin-email' => '电邮地址：',
	'config-email-settings' => 'Email 设置',
);

