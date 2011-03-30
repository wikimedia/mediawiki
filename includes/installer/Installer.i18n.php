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
	'config-localsettings-upgrade'    => "A <code>LocalSettings.php</code> file has been detected.
To upgrade this installation, please enter the value of <code>\$wgUpgradeKey</code> in the box below.
You will find it in LocalSettings.php.",
	'config-localsettings-cli-upgrade'    => 'A LocalSettings.php file has been detected.
To upgrade this installation, please run update.php instead',
	'config-localsettings-key'        => 'Upgrade key:',
	'config-localsettings-badkey'     => 'The key you provided is incorrect.',
	'config-upgrade-key-missing'      => 'An existing installation of MediaWiki has been detected.
To upgrade this installation, please put the following line at the bottom of your LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'The existing LocalSettings.php appears to be incomplete.
The $1 variable is not set.
Please change LocalSettings.php so that this variable is set, and click "Continue".',
	'config-localsettings-connection-error' => 'An error was encountered when connecting to the database using the settings specified in LocalSettings.php or AdminSettings.php. Please fix these settings and try again.

$1',
	'config-session-error'            => 'Error starting session: $1',
	'config-session-expired'          => 'Your session data seems to have expired.
Sessions are configured for a lifetime of $1.
You can increase this by setting <code>session.gc_maxlifetime</code> in php.ini.
Restart the installation process.',
	'config-no-session'               => 'Your session data was lost!
Check your php.ini and make sure <code>session.save_path</code> is set to an appropriate directory.',
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
	'config-page-existingwiki'        => 'Existing wiki',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>Read me</doclink>
* <doclink href=ReleaseNotes>Release notes</doclink>
* <doclink href=Copying>Copying</doclink>
* <doclink href=UpgradeDoc>Upgrading</doclink>",
	'config-env-good'                 => 'The environment has been checked.
You can install MediaWiki.',
	'config-env-bad'                  => 'The environment has been checked.
You cannot install MediaWiki.',
	'config-env-php'                  => 'PHP $1 is installed.',
	'config-env-php-toolow'           => 'PHP $1 is installed.
However, MediaWiki requires PHP $2 or higher.',
	'config-unicode-using-utf8'       => 'Using Brion Vibber\'s utf8_normalize.so for Unicode normalization.',
	'config-unicode-using-intl'       => 'Using the [http://pecl.php.net/intl intl PECL extension] for Unicode normalization.',
	'config-unicode-pure-php-warning' => "'''Warning''': The [http://pecl.php.net/intl intl PECL extension] is not available to handle Unicode normalization, falling back to slow pure-PHP implementation.
If you run a high-traffic site, you should read a little on [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode normalization].",
	'config-unicode-update-warning'   => "'''Warning''': The installed version of the Unicode normalization wrapper uses an older version of [http://site.icu-project.org/ the ICU project's] library.
You should [http://www.mediawiki.org/wiki/Unicode_normalization_considerations upgrade] if you are at all concerned about using Unicode.",
	'config-no-db'                    => 'Could not find a suitable database driver!',
	'config-no-db-help'               => 'You need to install a database driver for PHP.
The following database types are supported: $1.

If you are on shared hosting, ask your hosting provider to install a suitable database driver.
If you compiled PHP yourself, reconfigure it with a database client enabled, for example using <code>./configure --with-mysql</code>.
If you installed PHP from a Debian or Ubuntu package, then you also need install the php5-mysql module.',
	'config-no-fts3'                  => "'''Warning''': SQLite is compiled without the [http://sqlite.org/fts3.html FTS3 module], search features will be unavailable on this backend.",
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
	'config-xml-bad'                  => "PHP's XML module is missing.
MediaWiki requires functions in this module and will not work in this configuration.
If you're running Mandrake, install the php-xml package.",
	'config-pcre'                     => 'The PCRE support module appears to be missing.
MediaWiki requires the Perl-compatible regular expression functions to work.',
	'config-pcre-no-utf8'             => "'''Fatal''': PHP's PCRE module seems to be compiled without PCRE_UTF8 support.
MediaWiki requires UTF-8 support to function correctly.",
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
	'config-diff3-bad'                => 'GNU diff3 not found.',
	'config-imagemagick'              => 'Found ImageMagick: <code>$1</code>.
Image thumbnailing will be enabled if you enable uploads.',
	'config-gd'                       => 'Found GD graphics library built-in.
Image thumbnailing will be enabled if you enable uploads.',
	'config-no-scaling'               => 'Could not find GD library or ImageMagick.
Image thumbnailing will be disabled.',
	'config-no-uri'                   => "'''Error:''' Could not determine the current URI.
Installation aborted.",
	'config-uploads-not-safe'         => "'''Warning:''' Your default directory for uploads <code>$1</code> is vulnerable to arbitrary scripts execution.
Although MediaWiki checks all uploaded files for security threats, it is highly recommended to [http://www.mediawiki.org/wiki/Manual:Security#Upload_security close this security vulnerability] before enabling uploads.",
	'config-brokenlibxml'             => 'Your system has a combination of PHP and libxml2 versions which is buggy and can cause hidden data corruption in MediaWiki and other web applications.
Upgrade to PHP 5.2.9 or later and libxml2 2.7.3 or later ([http://bugs.php.net/bug.php?id=45996 bug filed with PHP]).
Installation aborted.',
	'config-using531'                 => 'MediaWiki cannot be used with PHP $1 due to a bug involving reference parameters to <code>__call()</code>.
Upgrade to PHP 5.3.2 or higher, or downgrade to PHP 5.3.0 to resolve this.
Installation aborted.',
	'config-db-type'                  => 'Database type:',
	'config-db-host'                  => 'Database host:',
	'config-db-host-help'             => 'If your database server is on different server, enter the host name or IP address here.

If you are using shared web hosting, your hosting provider should give you the correct host name in their documentation.

If you are installing on a Windows server and using MySQL, using "localhost" may not work for the server name. If it does not, try "127.0.0.1" for the local IP address.',
	'config-db-host-oracle'           => 'Database TNS:',
	'config-db-host-oracle-help'      => 'Enter a valid [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Local Connect Name]; a tnsnames.ora file must be visible to this installation.<br />If you are using client libraries 10g or newer you can also use the [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect] naming method.',
	'config-db-wiki-settings'         => 'Identify this wiki',
	'config-db-name'                  => 'Database name:',
	'config-db-name-help'             => 'Choose a name that identifies your wiki.
It should not contain spaces.

If you are using shared web hosting, your hosting provider will either give you a specific database name to use or let you create databases via a control panel.',
	'config-db-name-oracle'           => 'Database schema:',
	'config-db-account-oracle-warn' => "There are three supported scenarios for installing Oracle as database backend:

If you wish to create database account as part of the installation process, please supply an account with SYSDBA role as database account for installation and specify the desired credentials for the web-access account, otherwise you can either create the web-access account manually and supply only that account (if it has required permissions to create the schema objects) or supply two different accounts, one with create privileges and a restricted one for web access.

Script for creating an account with required privileges can be found in \"maintenance/oracle/\" directory of this installation. Keep in mind that using a restricted account will disable all maintenance capabilities with the default account.",
	'config-db-install-account'       => 'User account for installation',
	'config-db-username'              => 'Database username:',
	'config-db-password'              => 'Database password:',
	'config-db-password-empty'        => 'Please enter a password for the new database user: $1.
While it may be possible to create users with no passwords, it is not secure.',
	'config-db-install-username'	  => 'Enter the username that will be used to connect to the database during the installation process.
This is not the username of the MediaWiki account; this is the username for your database.',
	'config-db-install-password'	  => 'Enter the password that will be used to connect to the database during the installation process.
This is not the password for the MediaWiki account; this is the password for your database.',
	'config-db-install-help'          => 'Enter the username and password that will be used to connect to the database during the installation process.',
	'config-db-account-lock'          => 'Use the same username and password during normal operation',
	'config-db-wiki-account'          => 'User account for normal operation',
	'config-db-wiki-help'             => 'Enter the username and password that will be used to connect to the database during normal wiki operation.
If the account does not exist, and the installation account has sufficient privileges, this user account will be created with the minimum privileges required to operate the wiki.',
	'config-db-prefix'                => 'Database table prefix:',
	'config-db-prefix-help'           => 'If you need to share one database between multiple wikis, or between MediaWiki and another web application, you may choose to add a prefix to all the table names to avoid conflicts.
Do not use spaces.

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
	'config-db-schema-help'           => 'This schema will usually be fine.
Only change it if you know you need to.',
	'config-sqlite-dir'               => 'SQLite data directory:',
	'config-sqlite-dir-help'          => "SQLite stores all data in a single file.

The directory you provide must be writable by the webserver during installation.

It should '''not''' be accessible via the web, this is why we're not putting it where your PHP files are.

The installer will write a <code>.htaccess</code> file along with it, but if that fails someone can gain access to your raw database.
That includes raw user data (e-mail addresses, hashed passwords) as well as deleted revisions and other restricted data on the wiki.

Consider putting the database somewhere else altogether, for example in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts'            => 'Default tablespace:',
	'config-oracle-temp-ts'           => 'Temporary tablespace:',
	'config-type-mysql'               => 'MySQL',
	'config-type-postgres'            => 'PostgreSQL',
	'config-type-sqlite'              => 'SQLite',
	'config-type-oracle'              => 'Oracle',
	'config-support-info'             => 'MediaWiki supports the following database systems:

$1

If you do not see the database system you are trying to use listed below, then follow the instructions linked above to enable support.',
	'config-support-mysql'            => '* $1 is the primary target for MediaWiki and is best supported ([http://www.php.net/manual/en/mysql.installation.php how to compile PHP with MySQL support])',
	'config-support-postgres'         => '* $1 is a popular open source database system as an alternative to MySQL ([http://www.php.net/manual/en/pgsql.installation.php how to compile PHP with PostgreSQL support]). There may be some minor outstanding bugs, and it is not recommended for use in a production environment.',
	'config-support-sqlite'           => '* $1 is a lightweight database system which is very well supported. ([http://www.php.net/manual/en/pdo.installation.php How to compile PHP with SQLite support], uses PDO)',
	'config-support-oracle'           => '* $1 is a commercial enterprise database. ([http://www.php.net/manual/en/oci8.installation.php How to compile PHP with OCI8 support])',
	'config-header-mysql'             => 'MySQL settings',
	'config-header-postgres'          => 'PostgreSQL settings',
	'config-header-sqlite'            => 'SQLite settings',
	'config-header-oracle'            => 'Oracle settings',
	'config-invalid-db-type'          => 'Invalid database type',
	'config-missing-db-name'          => 'You must enter a value for "Database name"',
	'config-missing-db-host'          => 'You must enter a value for "Database host"',
	'config-missing-db-server-oracle' => 'You must enter a value for "Database TNS"',
	'config-invalid-db-server-oracle' => 'Invalid database TNS "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9), underscores (_) and dots (.).',
	'config-invalid-db-name'          => 'Invalid database name "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9), underscores (_) and hyphens (-).',
	'config-invalid-db-prefix'        => 'Invalid database prefix "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9), underscores (_) and hyphens (-).',
	'config-connection-error'         => '$1.

Check the host, username and password below and try again.',
	'config-invalid-schema'           => 'Invalid schema for MediaWiki "$1".
Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).',
	'config-db-sys-create-oracle' => 'Installer only supports using a SYSDBA account for creating a new account.',
	'config-db-sys-user-exists-oracle' => 'User account "$1" already exists. SYSDBA can only be used for creating of a new account!',
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
	'config-can-upgrade'              => "There are MediaWiki tables in this database.
To upgrade them to MediaWiki $1, click '''Continue'''.",
	'config-upgrade-done'             => "Upgrade complete.

You can now [$1 start using your wiki].

If you want to regenerate your <code>LocalSettings.php</code> file, click the button below.
This is '''not recommended''' unless you are having problems with your wiki.",
	'config-upgrade-done-no-regenerate' => "Upgrade complete.

You can now [$1 start using your wiki].",
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
	'config-ns-conflict'               => 'The specified namespace "<nowiki>$1</nowiki>" conflicts with a default MediaWiki namespace.
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
	'config-admin-email-help'         => 'Enter an e-mail address here to allow you to receive e-mail from other users on the wiki, reset your password, and be notified of changes to pages on your watchlist. You can leave this field empty.',
	'config-admin-error-user'         => 'Internal error when creating an admin with the name "<nowiki>$1</nowiki>".',
	'config-admin-error-password'     => 'Internal error when setting a password for the admin "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail'     => 'You have entered an invalid e-mail address.',
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
	'config-license-cc-by-sa'         => 'Creative Commons Attribution Share Alike',
	'config-license-cc-by-nc-sa'      => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-cc-0'             => 'Creative Commons Zero',
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
In order to do this, MediaWiki requires access to the Internet.

For more information on this feature, including instructions on how to set it up for wikis other than the Wikimedia Commons, consult [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos the manual].',
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
Should specify one per line and specify the port to be used. For example:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers'     => 'You selected Memcached as your cache type but did not specify any servers.',
	'config-memcache-badip'           => 'You have entered an invalid IP address for Memcached: $1.',
	'config-memcache-noport'          => 'You did not specify a port to use for Memcached server: $1.
If you do not know the port, the default is 11211.',
	'config-memcache-badport'         => 'Memcached port numbers should be between $1 and $2.',
	'config-extensions'               => 'Extensions',
	'config-extensions-help'          => 'The extensions listed above were detected in your <code>./extensions</code> directory.

They may require additional configuration, but you can enable them now',
	'config-install-alreadydone'      => "'''Warning:''' You seem to have already installed MediaWiki and are trying to install it again.
Please proceed to the next page.",
	'config-install-begin'            => 'By pressing "{{int:config-continue}}", you will begin the installation of MediaWiki.
If you still want to make changes, press back.',
	'config-install-step-done'        => 'done',
	'config-install-step-failed'      => 'failed',
	'config-install-extensions'       => 'Including extensions',
	'config-install-database'         => 'Setting up database',
	'config-install-pg-schema-not-exist' => 'PostgreSQL schema does not exist.',
	'config-install-pg-schema-failed' => 'Tables creation failed.
Make sure that the user "$1" can write to the schema "$2".',
	'config-install-pg-commit'        => 'Committing changes',
	'config-install-pg-plpgsql'       => 'Checking for language PL/pgSQL',
	'config-pg-no-plpgsql'            => 'You need to install the language PL/pgSQL in the database $1',
	'config-pg-no-create-privs'       => 'The account you specified for installation does not have enough privileges to create an account.',
	'config-install-user'             => 'Creating database user',
	'config-install-user-alreadyexists' => 'User "$1" already exists',
	'config-install-user-create-failed' => 'Creating user "$1" failed: $2',
	'config-install-user-grant-failed'  => 'Granting permission to user "$1" failed: $2',
	'config-install-tables'           => 'Creating tables',
	'config-install-tables-exist'     => "'''Warning''': MediaWiki tables seem to already exist.
Skipping creation.",
	'config-install-tables-failed'    => "'''Error''': Table creation failed with the following error: $1",
	'config-install-interwiki'        => 'Populating default interwiki table',
	'config-install-interwiki-list'   => 'Could not read file <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Warning''': The interwiki table seems to already have entries.
Skipping default list.",
	'config-install-stats'            => 'Initializing statistics',
	'config-install-keys'             => 'Generating secret keys',
	'config-insecure-keys'            => "'''Warning:''' {{PLURAL:$2|A secure key|Secure keys}} $1 generated during installation are not completely safe. Consider changing {{PLURAL:$2|it|them}} manually.",
	'config-install-sysop'            => 'Creating administrator user account',
	'config-install-subscribe-fail'   => 'Unable to subscribe to mediawiki-announce',
	'config-install-mainpage'         => 'Creating main page with default content',
	'config-install-extension-tables' => 'Creating tables for enabled extensions',
	'config-install-mainpage-failed'  => 'Could not insert main page: $1',
	'config-install-done'             => "'''Congratulations!'''
You have successfully installed MediaWiki.

The installer has generated a <code>LocalSettings.php</code> file.
It contains all your configuration.

You will need to download it and put it in the base of your wiki installation (the same directory as index.php). The download should have started automatically.

If the download was not offered, or if you cancelled it, you can restart the download by clicking the link below:

$3

'''Note''': If you do not do this now, this generated configuration file will not be available to you later if you exit the installation without downloading it.

When that has been done, you can '''[$2 enter your wiki]'''.",
	'config-download-localsettings' => 'Download LocalSettings.php',
	'config-help' => 'help',
);

/** Message documentation (Message documentation)
 * @author Dani
 * @author EugeneZelenko
 * @author Kghbln
 * @author McDutchie
 * @author Nike
 * @author Platonides
 * @author Purodha
 * @author Raymond
 * @author Siebrand
 * @author Umherirrender
 */
$messages['qqq'] = array(
	'config-desc' => '{{desc}}',
	'config-title' => 'Parameters:
* $1 is the version of MediaWiki that is being installed.',
	'config-information' => '{{Identical|Information}}',
	'config-localsettings-cli-upgrade' => 'Do not translate the <code>LocalSettings.php</code> and the <code>update.php</code> parts.',
	'config-session-error' => 'Parameters:
* $1 is the error that was encountered with the session.',
	'config-session-expired' => 'Parameters:
* $1 is the configured session lifetime.',
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
	'config-no-db-help' => 'Parameters:
* $1 is comma separated list of supported database types by MediaWiki.',
	'config-memory-raised' => 'Parameters:
* $1 is the configured <code>memory_limit</code>.
* $2 is the value to which <code>memory_limit</code> was raised.',
	'config-memory-bad' => 'Parameters:
* $1 is the configured <code>memory_limit</code>.',
	'config-xcache' => 'Message indicates if this program is available',
	'config-apc' => 'Message indicates if this program is available',
	'config-eaccel' => 'Message indicates if this program is available',
	'config-wincache' => 'Message indicates if this program is available',
	'config-db-host-oracle' => 'TNS = [[:wikipedia:Transparent Network Substrate|Transparent Network Substrate]] (<== wikipedia link)',
	'config-db-wiki-settings' => 'This is more acurate: "Enter identifying or distinguishing data for this wiki" since a MySQL database can host tables of several wikis.',
	'config-db-account-lock' => "It might be easier to translate ''normal operation'' as \"also after the installation process\"",
	'config-support-mysql' => 'Parameters:
* $1 - a link to the MySQL home page having the anchor text "MySQL".',
	'config-support-postgres' => 'Parameters:
* $1 - a link to the PostgreSQL home page having the anchor text "PostgreSQL".',
	'config-support-sqlite' => 'Parameters:
* $1 - a link to the SQLite home page having the anchor text "SQLite".',
	'config-support-oracle' => 'Parameters:
* $1 - a link to the Oracle home page, the anchor text of which is "Oracle".',
	'config-sqlite-dir-unwritable' => 'webserver refers to a software like Apache or Lighttpd.',
	'config-can-upgrade' => 'Should we no use an {{int:xxx}} construct for "continue" ?

Parameters:
* $1 - Version or Revision indicator.',
	'config-show-table-status' => '{{doc-important|"SHOW TABLE STATUS" is a MySQL command. Do not translate this.}}',
	'config-ns-generic' => '{{Identical|Project}}',
	'config-admin-name' => '{{Identical|Your name}}',
	'config-admin-password' => '{{Identical|Password}}',
	'config-admin-email' => '{{Identical|E-mail address}}',
	'config-subscribe' => 'Used as label for the installer checkbox',
	'config-profile-help' => 'Messages referenced:
* {{msg-mw|config-profile-wiki}}
* {{msg-mw|config-profile-no-anon}}
* {{msg-mw|config-profile-fishbowl}}
* {{msg-mw|config-profile-private}}',
	'config-upload-help' => 'The word "mode" here refers to the access rights given to various user groups when attempting to create and store files and/or subdiretories in the said directory on the server. It also refers to the <code>mode</code> command used to maipulate said right mask under Unix, Linux, and similar operating systems. A less operating-system-centric translation is fine.',
	'config-logo-help' => '{{doc-important|For languages with right-to-left script, translate "top left corner" as "top right corner".}}',
	'config-cc-not-chosen' => 'Do not translate the <code>"proceed".</code> part.
This message refers to a block of HTML being embedded into the installer page. It comes from the Creative Commons Web site. The block is in the English language. It is a scripted license chooser. When an individual license has been selected, it asks you to klick "proceed" so as to return to the MediaWiki installer page.',
	'config-extensions' => '{{Identical|Extension}}',
	'config-install-step-done' => '{{Identical|Done}}',
	'config-install-pg-schema-failed' => 'Parameters:
* $1 = database user name (usernames in the database are unrelated to wiki user names)
* $2 =',
	'config-install-user' => 'Message indicates that the user is being created',
	'config-install-user-grant-failed' => 'Parameters:
* $1 is the database username for which granting rights failed
* $2 is the error message',
	'config-install-tables' => 'Message indicates that the tables are being created',
	'config-install-interwiki' => 'Message indicates that the interwikitables are being populated',
	'config-insecure-keys' => 'Parameters:
* $1 - A list of names of the secret keys that were generated.
* $2 - the number of items in the list $1, to be used with PLURAL.',
	'config-install-sysop' => 'Message indicates that the administrator user account is being created',
	'config-install-subscribe-fail' => '{{doc-important|"mediawiki-announce" is the name of a mailing list and should not be translated.}}',
	'config-install-done' => 'Parameters:
* $1 is the URL to LocalSettings download
* $2 is a link to the wiki.
* $3 is a download link with attached download icon. The config-download-localsettings message will be used as the link text.',
	'config-download-localsettings' => 'The link text used in the download link in config-install-done.',
	'config-help' => 'This is used in help boxes.
{{Identical|Help}}',
);

/** Magyar (magázó) (Magyar (magázó))
 * @author Dani
 * @author Glanthor Reviol
 */
$messages['hu-formal'] = array(
	'config-localsettings-upgrade' => "'''Figyelmeztetés''': már létezik a <code>LocalSettings.php</code> fájl.
A szoftver frissíthető.
Adja meg a <code>\$wgUpgradeKey</code>-ben található kulcsot a beviteli mezőben",
	'config-session-expired' => 'Úgy tűnik, hogy a munkamenetadatok lejártak.
A munkamenetek élettartama a következőre van beállítva: $1.
Az érték növelhető a php.ini <code>session.gc_maxlifetime</code> beállításának módosításával.
Indítsa újra a telepítési folyamatot.',
	'config-no-session' => 'Elvesztek a munkamenetadatok!
Ellenőrizze, hogy a php.ini-ben a <code>session.save_path</code> beállítás a megfelelő könyvtárra mutat-e.',
	'config-your-language-help' => 'Válassza ki a telepítési folyamat során használandó nyelvet.',
	'config-wiki-language-help' => 'Az a nyelv, amin a wiki tartalmának legnagyobb része íródik.',
	'config-page-welcome' => 'Üdvözli a MediaWiki!',
	'config-help-restart' => 'Szeretné törölni az eddig megadott összes adatot és újraindítani a telepítési folyamatot?',
	'config-welcome' => '=== Környezet ellenőrzése ===
Alapvető ellenőrzés, ami megmondja, hogy a környezet alkalmas-e a MediaWiki számára.
Ha probléma merülne fel a telepítés során, meg kell adnia mások számára az alább megjelenő információkat.',
	'config-unicode-pure-php-warning' => "'''Figyelmeztetés''': Az [http://pecl.php.net/intl intl PECL kiterjesztés] nem érhető el Unicode normalizáláshoz.
Ha nagy látogatottságú oldalt üzemeltet, itt találhat információkat [http://www.mediawiki.org/wiki/Unicode_normalization_considerations a témáról].",
	'config-register-globals' => "'''Figyelmeztetés: A PHP <code>[http://php.net/register_globals register_globals]</code> beállítása engedélyezve van.'''
'''Tiltsa le, ha van rá lehetősége.'''
A MediaWiki működőképes a beállítás használata mellett, de a szerver biztonsági kockázatnak lesz kitéve.",
	'config-imagemagick' => 'Az ImageMagick megtalálható a rendszeren: <code>$1</code>.
A bélyegképek készítése engedélyezve lesz, ha engedélyezi a feltöltéseket.',
	'config-db-name-help' => 'Válassza ki a wikije azonosítására használt nevet.
Nem tartalmazhat szóközt vagy kötőjelet.

Ha megosztott webtárhelyet használ, a szolgáltatója vagy egy konkrét adatbázisnevet ad önnek használatra, vagy létrehozhat egyet a vezérlőpulton keresztül.',
	'config-db-install-help' => 'Adja meg a felhasználónevet és jelszót, amivel a telepítő csatlakozhat az adatbázishoz.',
	'config-db-wiki-help' => 'Adja meg azt a felhasználónevet és jelszót, amivel a wiki fog csatlakozni az adatbázishoz működés közben.
Ha a fiók nem létezik és a telepítést végző fiók rendelkezik megfelelő jogosultsággal, egy új fiók készül a megadott a névvel, azon minimális jogosultságkörrel, ami a wiki működéséhez szükséges.',
	'config-charset-help' => "'''Figyelmezetés:''' Ha a '''visszafelé kompatibilis UTF-8''' beállítást használja MySQL 4.1 vagy újabb verziók esetén, és utána a <code>mysqldump</code> programmal készít róla biztonsági másolatot, az tönkreteheti az összes nem ASCII-karaktert, visszafordíthatatlanul károsítva a másolatokban tárolt adatokat!

'''Bináris''' módban a MediaWiki az UTF-8-ban kódolt szöveget bináris mezőkben tárolja az adatbázisban.
Ez sokkal hatékonyabb a MySQL UTF-8-módjától, és lehetővé teszi, hogy a teljes Unicode-karakterkészletet használja.
'''UTF-8-módban''' MySQL tudja, hogy milyen karakterkészlettel van kódolva az adat, megfelelően van megjelenítve és konvertálva, de
nem használhatja a [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane] feletti karaktereket.",
	'config-db-schema-help' => 'A fenti sémák általában megfelelőek.
Csak akkor módosítson rajta, ha szükség van rá.',
	'config-sqlite-parent-unwritable-nogroup' => 'Nem lehet létrehozni az adatok tárolásához szükséges <code><nowiki>$1</nowiki></code> könyvtárat, mert a webszerver nem írhat a szülőkönyvtárba (<code><nowiki>$2</nowiki></code>).

A telepítő nem tudta megállapíteni, hogy melyik felhasználói fiókon fut a webszerver.
A folytatáshoz tegye írhatóvá ezen fiók (és más fiókok!) számára a következő könyvtárat: <code><nowiki>$3</nowiki></code>.
Unix/Linux rendszereken tedd a következőt:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-ns-other' => 'Más (adja meg)',
	'config-admin-name-blank' => 'Adja meg az adminisztrátor felhasználónevét!',
	'config-admin-name-invalid' => 'A megadott felhasználónév (<nowiki>$1</nowiki>) érvénytelen.
Adjon meg egy másik felhasználónevet.',
	'config-admin-password-blank' => 'Adja meg az adminisztrátori fiók jelszavát!',
	'config-instantcommons-help' => 'Az [http://www.mediawiki.org/wiki/InstantCommons Instant Commons] lehetővé teszi, hogy a wikin használhassák a [http://commons.wikimedia.org/ Wikimedia Commons] oldalon található képeket, hangokat és más médiafájlokat.
A használatához a MediaWikinek internethozzáférésre van szüksége. 

A funkcióról és hogy hogyan állítható be más wikik esetén [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos a kézikönyvben] találhat további információkat.',
	'config-install-done' => "'''Gratulálunk!'''
Sikeresen telepítette a MediaWikit.

A telepítő készített egy <code>LocalSettings.php</code> fájlt.
Ez tartalmazza az összes beállítást.

[$1 Le kell töltenie], és el kell helyeznie a MediaWiki telepítési könyvtárába (az a könyvtár, ahol az index.php van).
'''Megjegyzés''': Ha ezt most nem teszi meg, és kilép, a generált fájl nem lesz elérhető a későbbiekben.

Ha ezzel készen van, '''[$2 beléphet a wikibe]'''.",
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'config-desc' => 'Die Installasieprogram vir MediaWiki',
	'config-title' => 'Installasie MediaWiki $1',
	'config-information' => 'Inligting',
	'config-localsettings-key' => 'Opgradeer-sleutel:',
	'config-localsettings-badkey' => 'Die sleutel wat u verskaf het is verkeerd.',
	'config-session-error' => 'Fout met begin van sessie: $1',
	'config-no-session' => "U sessiedata is verlore!
Kontroleer u php.ini en maak seker dat <code>session.save_path</code> na 'n geldige gids wys.",
	'config-your-language' => 'U taal:',
	'config-your-language-help' => "Kies 'n taal om tydens die installasieproses te gebruik.",
	'config-wiki-language' => 'Wiki se taal:',
	'config-wiki-language-help' => 'Kies die taal waarin die wiki hoofsaaklik geskryf sal word.',
	'config-back' => '← Terug',
	'config-continue' => 'Gaan voort →',
	'config-page-language' => 'Taal',
	'config-page-welcome' => 'Welkom by MediaWiki!',
	'config-page-dbconnect' => 'Konnekteer na die databasis',
	'config-page-upgrade' => "Opgradeer 'n bestaande installasie",
	'config-page-dbsettings' => 'Databasis-instellings',
	'config-page-name' => 'Naam',
	'config-page-options' => 'Opsies',
	'config-page-install' => 'Installeer',
	'config-page-complete' => 'Voltooi!',
	'config-page-restart' => 'Herbegin installasie',
	'config-page-readme' => 'Lees my',
	'config-page-releasenotes' => 'Vrystellingsnotas',
	'config-page-copying' => 'Besig met kopiëring',
	'config-page-upgradedoc' => 'Besig met opgradering',
	'config-page-existingwiki' => 'Bestaande wiki',
	'config-restart' => 'Ja, herbegin dit',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki tuisblad]
* [http://www.mediawiki.org/wiki/Help:Contents Gebruikershandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:Contents Administrateurshandleiding] (Engelstalig)
* [http://www.mediawiki.org/wiki/Manual:FAQ Algemene vrae] (Engelstalig)
----
* <doclink href=Readme>Lees my</doclink>
* <doclink href=ReleaseNotes>Vrystellingsnotas</doclink>
* <doclink href=Copying>Kopiëring</doclink>
* <doclink href=UpgradeDoc>Opgradering</doclink>',
	'config-env-good' => 'Die omgewing is gekontroleer.
U kan MediaWiki installeer.',
	'config-env-bad' => 'Die omgewing is gekontroleer.
U kan nie MediaWiki installeer nie.</span>',
	'config-env-php' => 'PHP $1 is tans geïnstalleer.',
	'config-no-db' => "Kon nie 'n geskikte databasisdrywer vind nie!",
	'config-memory-raised' => 'PHP se <code>memory_limit</code> is $1, en is verhoog tot $2.',
	'config-memory-bad' => "'''Waarskuwing:''' PHP se <code>memory_limit</code> is $1. 
Dit is waarskynlik te laag.
Die installasie mag moontlik faal!",
	'config-xcache' => '[Http://trac.lighttpd.net/xcache/ XCache] is geïnstalleer',
	'config-apc' => '[Http://www.php.net/apc APC] is geïnstalleer',
	'config-eaccel' => '[Http://eaccelerator.sourceforge.net/ eAccelerator] is geïnstalleer',
	'config-wincache' => '[Http://www.iis.net/download/WinCacheForPhp WinCache] is geïnstalleer',
	'config-diff3-bad' => 'GNU diff3 nie gevind nie.',
	'config-db-type' => 'Databasistipe:',
	'config-db-host' => 'Databasisbediener:',
	'config-db-host-oracle' => 'Databasis-TNS:',
	'config-db-wiki-settings' => 'Identifiseer hierdie wiki',
	'config-db-name' => 'Databasisnaam:',
	'config-db-name-oracle' => 'Databasis-skema:',
	'config-db-install-account' => 'Gebruiker vir die installasie',
	'config-db-username' => 'Databasis gebruikersnaam:',
	'config-db-password' => 'Databasis wagwoord:',
	'config-db-prefix' => 'Voorvoegsel vir databasistabelle:',
	'config-db-charset' => 'Karakterstelsel vir databasis',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'U moet MySQL $1 of later gebruik.
U gebruik tans $2.',
	'config-db-port' => 'Databasispoort:',
	'config-db-schema' => 'Skema vir MediaWiki',
	'config-sqlite-dir' => 'Gids vir SQLite se data:',
	'config-oracle-def-ts' => 'Standaard tabelruimte:',
	'config-oracle-temp-ts' => 'Tydelike tabelruimte:',
	'config-header-mysql' => 'MySQL-instellings',
	'config-header-postgres' => 'PostgreSQL-instellings',
	'config-header-sqlite' => 'SQLite-instellings',
	'config-header-oracle' => 'Oracle-instellings',
	'config-invalid-db-type' => 'Ongeldige databasistipe',
	'config-missing-db-name' => 'U moet \'n waarde vir "Databasnaam" verskaf',
	'config-sqlite-readonly' => 'Die lêer <code>$1</code> kan nie geskryf word nie.',
	'config-sqlite-cant-create-db' => 'Kon nie databasislêer <code>$1</code> skep nie.',
	'config-regenerate' => 'Herskep LocalSettings.php →',
	'config-show-table-status' => 'Die uitvoer van SHOW TABLE STATUS het gefaal!',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-binary' => 'Binêr',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Naam van die wiki:',
	'config-site-name-blank' => "Verskaf 'n naam vir u webwerf.",
	'config-project-namespace' => 'Projeknaamruimte:',
	'config-ns-generic' => 'Projek',
	'config-ns-site-name' => 'Dieselfde as die wiki: $1',
	'config-ns-other' => 'Ander (spesifiseer)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Administrateur se gebruiker',
	'config-admin-name' => 'U naam:',
	'config-admin-password' => 'Wagwoord:',
	'config-admin-password-confirm' => 'Wagwoord weer:',
	'config-admin-password-blank' => "Verskaf 'n wagwoord vir die administrateur in.",
	'config-admin-password-same' => 'Die wagwoord mag nie dieselfde as die gebruikersnaam wees nie.',
	'config-admin-password-mismatch' => 'Die twee wagwoorde wat u ingetik het stem nie ooreen nie.',
	'config-admin-email' => 'E-posadres:',
	'config-optional-continue' => 'Vra my meer vrae.',
	'config-optional-skip' => 'Ek is reeds verveeld, installeer maar net die wiki.',
	'config-profile-wiki' => 'Tradisionele wiki',
	'config-profile-no-anon' => 'Skep van gebruiker is verpligtend',
	'config-profile-fishbowl' => 'Slegs vir gemagtigde redaksie',
	'config-profile-private' => 'Privaat wiki',
	'config-license' => 'Kopiereg en lisensie:',
	'config-license-none' => 'Geen lisensie in die onderskrif',
	'config-license-pd' => 'Publieke Domein',
	'config-license-cc-choose' => "Kies 'n Creative Commons-lisensie",
	'config-email-settings' => 'E-posinstellings',
	'config-email-sender' => 'E-posadres vir antwoorde:',
	'config-upload-settings' => 'Oplaai van beelde en lêer',
	'config-upload-enable' => 'Aktiveer die oplaai van lêers',
	'config-upload-deleted' => 'Gids vir verwyderde lêers:',
	'config-logo' => 'URL vir logo:',
	'config-cc-again' => 'Kies weer...',
	'config-advanced-settings' => 'Gevorderde konfigurasie',
	'config-memcached-servers' => 'Memcached-bedieners:',
	'config-extensions' => 'Uitbreidings',
	'config-install-step-done' => 'gedoen',
	'config-install-step-failed' => 'het misluk',
	'config-install-extensions' => 'Insluitende uitbreidings',
	'config-install-database' => 'Stel die databasis op',
	'config-install-pg-schema-failed' => 'Die skep van tabelle het gefaal.
Maak seker dat die gebruiker "$1" na skema "$2" mag skryf.',
	'config-install-pg-commit' => 'Wysigings word gestoor',
	'config-pg-no-plpgsql' => 'U moet die taal PL/pgSQL in die database $1 installeer',
	'config-install-user' => 'Besig om die databasisgebruiker te skep',
	'config-install-user-grant-failed' => 'Die toekenning van regte aan gebruiker "$1" het gefaal: $2',
	'config-install-tables' => 'Skep tabelle',
	'config-install-tables-exist' => "'''Waarskuwing''': Dit lyk of MediaWiki se tabelle reeds bestaan. 
Die skep van tabelle word oorgeslaan.",
	'config-install-tables-failed' => "'''Fout''': die skep van 'n tabel het gefaal met die volgende fout: $1",
	'config-install-interwiki' => 'Besig om data in die interwiki-tabel in te laai',
	'config-install-interwiki-list' => 'Kon nie die lêer <code>interwiki.list</code> vind nie.',
	'config-install-interwiki-exists' => "'''Waarskuwing''': Die interwiki-tabel bevat reeds inskrywings.
Die standaardlys word oorgeslaan.",
	'config-install-keys' => 'Genereer geheime sleutel',
	'config-install-sysop' => "Skep 'n gebruiker vir die administrateur",
	'config-install-mainpage' => 'Skep die hoofblad met standaard inhoud',
	'config-install-mainpage-failed' => 'Kon nie die hoofblad laai nie: $1',
	'config-install-done' => "'''Veels geluk!''' 
U het MediaWiki suksesvol geïnstalleer. 

Die installeerder het 'n <code>LocalSettings.php</code> lêer opgestel.
Dit bevat al u instellings. 

U sal dit moet [$1 aflaai] en dit in die hoofgids van u wiki-installasie plaas; in dieselfde gids as index.php.
'''Let wel''': As u dit nie nou doen nie, sal die gegenereerde konfigurasielêer nie later meer beskikbaar wees nadat u die installasie afgesluit het nie.

As dit gedoen is, kan u '''[u $2 wiki besoek]'''.",
	'config-download-localsettings' => 'Laai LocalSettings.php af',
	'config-help' => 'hulp',
);

/** Arabic (العربية)
 * @author Meno25
 */
$messages['ar'] = array(
	'config-type-mysql' => 'ماي إس كيو إل',
	'config-type-postgres' => 'بوستجر إس كيو إل',
	'config-type-sqlite' => 'إس كيو لايت',
	'config-type-oracle' => 'أوراكل',
);

/** Aramaic (ܐܪܡܝܐ)
 * @author Basharh
 */
$messages['arc'] = array(
	'config-information' => 'ܝܕ̈ܥܬܐ',
	'config-your-language' => 'ܠܫܢܐ ܕܝܠܟ:',
	'config-wiki-language' => 'ܠܫܢܐ ܕܘܝܩܝ:',
	'config-page-language' => 'ܠܫܢܐ',
	'config-page-name' => 'ܫܡܐ',
	'config-page-options' => 'ܓܒܝܬ̈ܐ',
	'config-page-install' => 'ܢܨܘܒ',
	'config-ns-other-default' => 'ܘܝܩܝ ܕܝܠܝ',
	'config-admin-box' => 'ܚܘܫܒܢܐ ܕܡܕܒܪܢܐ',
	'config-admin-name' => 'ܫܡܐ ܕܝܠܟ:',
	'config-admin-password' => 'ܡܠܬܐ ܕܥܠܠܐ:',
	'config-admin-password-confirm' => 'ܡܠܬܐ ܕܥܠܠܐ ܙܒܢܬܐ ܐܚܪܬܐ:',
	'config-admin-email' => 'ܦܪܫܓܢܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ:',
	'config-profile-private' => 'ܘܝܩܝ ܦܪܨܘܦܝܐ',
	'config-email-settings' => 'ܛܘܝܒ̈ܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
);

/** Belarusian (Taraškievica orthography) (‪Беларуская (тарашкевіца)‬)
 * @author EugeneZelenko
 * @author Jim-by
 * @author Wizardist
 * @author Zedlik
 */
$messages['be-tarask'] = array(
	'config-desc' => 'Праграма ўсталяваньня MediaWiki',
	'config-title' => 'Усталяваньне MediaWiki $1',
	'config-information' => 'Інфармацыя',
	'config-localsettings-upgrade' => 'Выяўлены файл <code>LocalSettings.php</code>.
Каб абнавіць гэтае усталяваньне, калі ласка, увядзіце значэньне <code>$wgUpgradeKey</code> у полі ніжэй.
Яго можна знайсьці ў LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Быў знойдзены файл LocalSettings.php.
Каб зьмяніць гэтае ўсталяваньне, калі ласка, запусьціце update.php',
	'config-localsettings-key' => 'Ключ паляпшэньня:',
	'config-localsettings-badkey' => 'Пададзены Вамі ключ зьяўляецца няслушным',
	'config-upgrade-key-missing' => 'Выяўленае існуючае ўсталяваньне MediaWiki.
Каб абнавіць гэтае ўсталяваньне, калі ласка, устаўце наступны радок у канец Вашага LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Выглядае, што існуючы LocalSettings.php зьяўляецца няпоўным.
Не ўстаноўленая пераменная $1.
Калі ласка, зьмяніце LocalSettings.php так, каб была ўстаноўленая гэтая пераменная, і націсьніце «Працягваць».',
	'config-localsettings-connection-error' => 'Адбылася памылка падчас злучэньня з базай зьвестак з выкарыстаньнем наладаў, пазначаных у LocalSettings.php ці AdminSettings.php. Калі ласка, выпраўце гэтыя налады і паспрабуйце зноў.

$1',
	'config-session-error' => 'Памылка стварэньня сэсіі: $1',
	'config-session-expired' => 'Скончыўся тэрмін дзеяньня зьвестак сэсіі.
Сэсія мае абмежаваны тэрмін у $1.
Вы можаце павялічыць яго, зьмяніўшы парамэтар <code>session.gc_maxlifetime</code> у php.ini.
Перазапусьціце праграму ўсталяваньня.',
	'config-no-session' => 'Зьвесткі сэсіі згубленыя!
Праверце php.ini і ўпэўніцеся, што ўстаноўлены слушны шлях у <code>session.save_path</code>.',
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
	'config-page-dbsettings' => 'Налады базы зьвестак',
	'config-page-name' => 'Назва',
	'config-page-options' => 'Налады',
	'config-page-install' => 'Усталяваць',
	'config-page-complete' => 'Зроблена!',
	'config-page-restart' => 'Пачаць усталяваньне зноў',
	'config-page-readme' => 'Дадатковыя зьвесткі',
	'config-page-releasenotes' => 'Заўвагі да выпуску',
	'config-page-copying' => 'Капіяваньне',
	'config-page-upgradedoc' => 'Абнаўленьне',
	'config-page-existingwiki' => 'Існуючая вікі',
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
	'config-sidebar' => '* [http://www.mediawiki.org Хатняя старонка MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Даведка для ўдзельнікаў]
* [http://www.mediawiki.org/wiki/Manual:Contents Даведка для адміністратараў]
* [http://www.mediawiki.org/wiki/Manual:FAQ Адказы на частыя пытаньні]
----
* <doclink href=Readme>Прачытайце</doclink>
* <doclink href=ReleaseNotes>Паляпшэньні ў вэрсіі</doclink>
* <doclink href=Copying>Капіяваньне</doclink>
* <doclink href=UpgradeDoc>Абнаўленьне</doclink>',
	'config-env-good' => 'Асяродзьдзе было праверанае.
Вы можаце ўсталёўваць MediaWiki.',
	'config-env-bad' => 'Асяродзьдзе было праверанае.
Усталяваньне MediaWiki немагчымае.',
	'config-env-php' => 'Усталяваны PHP $1.',
	'config-env-php-toolow' => 'Усталяваны PHP $1.
Але MediaWiki патрабуе PHP вэрсіі $2 ці навейшай.',
	'config-unicode-using-utf8' => 'Выкарыстоўваецца бібліятэка Unicode-нармалізацыі Браяна Вібэра',
	'config-unicode-using-intl' => 'Выкарыстоўваецца [http://pecl.php.net/intl intl пашырэньне з PECL] для Unicode-нармалізацыі',
	'config-unicode-pure-php-warning' => "'''Папярэджаньне''': [http://pecl.php.net/intl Пашырэньне intl з PECL] — ня слушнае для Unicode-нармалізацыі, цяпер выкарыстоўваецца марудная PHP-рэалізацыя.
Калі ў Вас сайт з высокай наведваемасьцю, раім пачытаць пра [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-нармалізацыю].",
	'config-unicode-update-warning' => "'''Папярэджаньне''': усталяваная вэрсія бібліятэкі для Unicode-нармалізацыі выкарыстоўвае састарэлую вэрсію бібліятэкі з [http://site.icu-project.org/ праекту ICU].
Раім [http://www.mediawiki.org/wiki/Unicode_normalization_considerations абнавіць], калі ваш сайт будзе працаваць зь Unicode.",
	'config-no-db' => 'Немагчыма знайсьці слушны драйвэр базы зьвестак!',
	'config-no-db-help' => 'Вам трэба ўсталяваць драйвэр базы зьвестак для PHP.
Падтрымліваюцца наступныя тыпы базаў зьвестак: $1.

Калі вы выкарыстоўваеце агульны хостынг, запытайцеся ў свайго хостынг-правайдэра наконт усталяваньня патрабуемага драйвэр базы зьвестак.
Калі Вы кампілявалі PHP самастойна, пераканфігуруйце і сабярыце яго з уключаным кліентам базаў зьвестак, напрыклад, <code>./configure --with-mysql</code>.
Калі Вы ўсталёўвалі PHP з Debian/Ubuntu-рэпазытарыя, то вам трэба ўсталяваць дадаткова пакет <code>php5-mysql</code>',
	'config-no-fts3' => "'''Папярэджаньне''': SQLite створаны без модуля [http://sqlite.org/fts3.html FTS3], для гэтага ўнутранага інтэрфэйсу ня будзе даступная магчымасьць пошуку.",
	'config-register-globals' => "'''Папярэджаньне: уключаная опцыя PHP <code>[http://php.net/register_globals register_globals]</code>.'''
'''Адключыце яе, калі можаце.'''
MediaWiki будзе працаваць, але гэта панізіць узровень бясьпекі сэрвэра.",
	'config-magic-quotes-runtime' => "'''Фатальная памылка: уключаная опцыя PHP [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]!'''
Гэтая опцыя псуе ўводны паток зьвестак непрадказальным чынам.
Працяг усталяваньня альбо выкарыстаньне MediaWiki без адключэньня гэтай опцыі немагчымыя.",
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
	'config-xml-bad' => 'Ня знойдзены модуль XML для PHP.
MediaWiki патрэбныя функцыі з гэтага модулю, таму MediaWiki ня будзе працаваць у гэтай канфігурацыі.
Калі Вы выкарыстоўваеце Mandrake, усталюйце пакет php-xml.',
	'config-pcre' => 'Ня знойдзены модуль падтрымкі PCRE.
MediaWiki для працы патрабуюцца функцыі рэгулярных выразаў у стылі Perl.',
	'config-pcre-no-utf8' => "'''Крытычная памылка''': модуль PCRE для PHP скампіляваны без падтрымкі PCRE_UTF8.
MediaWiki патрабуе падтрымкі UTF-8 для слушнай працы.",
	'config-memory-raised' => 'Абмежаваньне на даступную для PHP памяць <code>memory_limit</code> было падвышанае з $1 да $2.',
	'config-memory-bad' => "'''Папярэджаньне:''' памер PHP <code>memory_limit</code> складае $1.
Верагодна, гэта вельмі мала.
Усталяваньне можа быць няўдалым!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] усталяваны',
	'config-apc' => '[http://www.php.net/apc APC] усталяваны',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] усталяваны',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] усталяваны',
	'config-no-cache' => "'''Папярэджаньне:''' немагчыма знайсьці [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] ці [http://www.iis.net/download/WinCacheForPhp WinCache].
Аб’ектнае кэшаваньне ня ўключанае.",
	'config-diff3-bad' => 'GNU diff3 ня знойдзены.',
	'config-imagemagick' => 'Знойдзены ImageMagick: <code>$1</code>.
Пасьля ўключэньня загрузак будзе ўключанае маштабаваньне выяваў.',
	'config-gd' => 'GD падтрымліваецца ўбудавана.
Пасьля ўключэньня загрузак будзе ўключанае маштабаваньне выяваў.',
	'config-no-scaling' => 'Ні GD, ні ImageMagick ня знойдзеныя.
Маштабаваньне выяваў будзе адключанае.',
	'config-no-uri' => "'''Памылка:''' Не магчыма вызначыць цяперашні URI.
Усталяваньне спыненае.",
	'config-uploads-not-safe' => "'''Папярэджаньне:''' дырэкторыя для загрузак па змоўчваньні <code>$1</code> уразьлівая да выкананьня адвольнага коду.
Хоць MediaWiki і правярае ўсе файлы перад захаваньнем, вельмі рэкамэндуецца [http://www.mediawiki.org/wiki/Manual:Security#Upload_security закрыць гэтую ўразьлівасьць] перад уключэньнем магчымасьці загрузкі файлаў.",
	'config-brokenlibxml' => 'У Вашай сыстэме ўсталяваныя PHP і libxml2 зь несумяшчальнымі вэрсіямі, што можа прывесьці да пашкоджаньня зьвестак MediaWiki і іншых ўэб-дастасаваньняў.
Абнавіце PHP да вэрсіі 5.2.9 ці болей позьняй, а libxml2 да 2.7.3 ці болей позьняй ([http://bugs.php.net/bug.php?id=45996 паведамленьне пра памылку на сайце PHP]).
Усталяваньне перарванае.',
	'config-using531' => 'PHP $1 не сумяшчальнае з MediaWiki з-за памылкі ў перадачы парамэтраў па ўказальніку да <code>__call()</code>.
Абнавіце PHP да вэрсіі 5.3.2 ці болей позьняй, ці адкаціце да вэрсіі 5.3.0 каб гэта выправіць.
Усталяваньне перарванае.',
	'config-db-type' => 'Тып базы зьвестак:',
	'config-db-host' => 'Хост базы зьвестак:',
	'config-db-host-help' => 'Калі сэрвэр Вашай базы зьвестак знаходзіцца на іншым сэрвэры, увядзіце тут імя хоста ці IP-адрас.

Калі Вы набываеце shared-хостынг, Ваш хостынг-правайдэр мусіць даць Вам слушнае імя хоста базы зьвестак у сваёй дакумэнтацыі.

Калі Вы усталёўваеце сэрвэр Windows з выкарыстаньнем MySQL, выкарыстаньне «localhost» можа не працаваць для назвы сэрвэра. У гэтым выпадку паспрабуйце пазначыць «127.0.0.1»  для лякальнага IP-адраса.',
	'config-db-host-oracle' => 'TNS базы зьвестак:',
	'config-db-host-oracle-help' => 'Увядзіце слушнае [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm лякальнае імя злучэньня]; файл tnsnames.ora павінен быць бачным для гэтага ўсталяваньня.<br />Калі Вы выкарыстоўваеце кліенцкія бібліятэкі 10g ці больш новыя, Вы можаце таксама выкарыстоўваць мэтад наданьня назваў [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm лёгкае злучэньне].',
	'config-db-wiki-settings' => 'Ідэнтыфікацыя гэтай вікі',
	'config-db-name' => 'Назва базы зьвестак:',
	'config-db-name-help' => 'Выберыце імя для вызначэньня Вашай вікі.
Яно ня мусіць зьмяшчаць прагалаў.

Калі Вы набываеце shared-хостынг, Ваш хостынг-правайдэр мусіць надаць Вам ці пэўнае імя базы зьвестак для выкарыстаньня, ці магчымасьць ствараць базы зьвестак праз кантрольную панэль.',
	'config-db-name-oracle' => 'Схема базы зьвестак:',
	'config-db-account-oracle-warn' => 'Існуюць тры сцэнары ўсталяваньня Oracle як базы зьвестак для MediaWiki:

Калі Вы жадаеце стварыць рахунак базы зьвестак як частку працэсу ўсталяваньня, калі ласка, падайце рахунак з роляй SYSDBA як рахунак базы зьвестак для ўсталяваньня і пазначце пажаданыя правы рахунку з доступам да Інтэрнэту, у адваротным выпадку Вы можаце таксама стварыць рахунак з доступам да Інтэрнэту ўручную і падаць толькі гэты рахунак (калі патрабуюцца правы для стварэньня схемы аб’ектаў) ці падайце два розных рахункі, адзін з правамі на стварэньне і адзін з абмежаваньнямі для доступу да Інтэрнэту.

Скрыпт для стварэньня рахунку з патрабуемымі правамі можна знайсьці ў дырэкторыі гэтага ўсталяваньня «maintenance/oracle/». Памятайце, што выкарыстаньне рахунку з абмежаваньнямі адключыць усе падтрымліваемыя магчымасьці даступныя па змоўчваньні.',
	'config-db-install-account' => 'Імя карыстальніка для ўсталяваньня',
	'config-db-username' => 'Імя карыстальніка базы зьвестак:',
	'config-db-password' => 'Пароль базы зьвестак:',
	'config-db-password-empty' => 'Калі ласка, увядзіце пароль для новага карыстальніка базы зьвестак: $1.
Магчыма стварыць карыстальніка без паролю, але гэта небясьпечна.',
	'config-db-install-username' => 'Увядзіце імя карыстальніка, якое будзе выкарыстоўвацца для злучэньня з базай зьвестак падчас усталяваньня. Гэта не назва рахунку MediaWiki; гэта імя карыстальніка Вашай базы зьвестак.',
	'config-db-install-password' => 'Увядзіце пароль, які будзе выкарыстоўвацца для злучэньня з базай зьвестак падчас усталяваньня. Гэта не пароль рахунку MediaWiki; гэта пароль Вашай базы зьвестак.',
	'config-db-install-help' => 'Увядзіце імя карыстальніка і пароль, якія будуць выкарыстаныя для далучэньня да базы зьвестак падчас працэсу ўсталяваньня.',
	'config-db-account-lock' => 'Выкарыстоўваць тыя ж імя карыстальніка і пароль пасьля ўсталяваньня',
	'config-db-wiki-account' => 'Імя карыстальніка для працы',
	'config-db-wiki-help' => 'Увядзіце імя карыстальніка і пароль, якія будуць выкарыстаныя для далучэньня да базы зьвестак падчас працы (пасьля ўсталяваньня).
Калі рахунак ня створаны, а рахунак для ўсталяваньня мае значныя правы, гэты рахунак будзе створаны зь мінімальна патрэбнымі для працы вікі правамі.',
	'config-db-prefix' => 'Прэфікс табліцаў базы зьвестак:',
	'config-db-prefix-help' => 'Калі Вы разьдзяляеце адну базу зьвестак паміж некалькімі вікі, ці паміж MediaWiki і іншым вэб-дастасаваньнем, можаце вызначыць прэфікс назваў табліцаў для пазьбяганьня канфліктаў.
Пазьбягайце прагалаў.

Гэтае поле звычайна пакідаецца пустым.',
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
	'config-db-schema-help' => 'Гэтая схема слушная ў большасьці выпадкаў.
Зьмяняйце яе толькі тады, калі Вы ведаеце, што гэта неабходна.',
	'config-sqlite-dir' => 'Дырэкторыя зьвестак SQLite:',
	'config-sqlite-dir-help' => "SQLite захоўвае ўсе зьвесткі ў адзіным файле.

Пададзеная Вамі дырэкторыя павінна быць даступнай да запісу вэб-сэрвэрам падчас усталяваньня.

Яна '''ня''' мусіць быць даступнай праз Сеціва, вось чаму мы не захоўваем яе ў адным месцы з файламі PHP.

Праграма ўсталяваньня дадаткова створыць файл <code>.htaccess</code>, але калі ён не выкарыстоўваецца, хто заўгодна зможа атрымаць зьвесткі з базы зьвестак.
Гэта ўключае як прыватныя зьвесткі ўдзельнікаў (адрасы электроннай пошты, хэшы пароляў), гэтак і выдаленыя вэрсіі старонак і іншыя зьвесткі, доступ да якіх маецца абмежаваны.

Падумайце над тым, каб зьмяшчаць базу зьвестак у іншым месцы, напрыклад у <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Прастора табліцаў па змоўчваньні:',
	'config-oracle-temp-ts' => 'Часовая прастора табліцаў:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki падтрымлівае наступныя сыстэмы базаў зьвестак:

$1

Калі Вы ня бачыце сыстэму базаў зьвестак, якую Вы спрабуеце выкарыстоўваць ў сьпісе ніжэй, перайдзіце па спасылцы інструкцыі, якая знаходзіцца ніжэй, каб уключыць падтрымку.',
	'config-support-mysql' => '* $1 зьяўляецца галоўнай мэтай MediaWiki і падтрымліваецца лепей за ўсё ([http://www.php.net/manual/en/mysql.installation.php як кампіляваць PHP з падтрымкай MySQL])',
	'config-support-postgres' => '* $1 — вядомая сыстэма базы зьвестак з адкрытым кодам, якая зьяўляецца альтэрнатывай MySQL ([http://www.php.net/manual/en/pgsql.installation.php як кампіляваць PHP з падтрымкай PostgreSQL]). Яна можа ўтрымліваць дробныя памылкі, і не рэкамэндуецца выкарыстоўваць яе для працуючых праектаў.',
	'config-support-sqlite' => '* $1 — невялікая сыстэма базы зьвестак, якая мае вельмі добрую падтрымку. ([http://www.php.net/manual/en/pdo.installation.php як кампіляваць PHP з падтрымкай SQLite], выкарыстоўвае PDO)',
	'config-support-oracle' => '* $1 зьяўляецца камэрцыйнай прафэсійнай базай зьвестак. ([http://www.php.net/manual/en/oci8.installation.php Як скампіляваць PHP з падтрымкай OCI8])',
	'config-header-mysql' => 'Налады MySQL',
	'config-header-postgres' => 'Налады PostgreSQL',
	'config-header-sqlite' => 'Налады SQLite',
	'config-header-oracle' => 'Налады Oracle',
	'config-invalid-db-type' => 'Няслушны тып базы зьвестак',
	'config-missing-db-name' => 'Вы павінны ўвесьці значэньне парамэтру «Імя базы зьвестак»',
	'config-missing-db-host' => 'Вы павінны ўвесьці значэньне парамэтру «Хост базы зьвестак»',
	'config-missing-db-server-oracle' => 'Вы павінны ўвесьці значэньне парамэтру «TNS базы зьвестак»',
	'config-invalid-db-server-oracle' => 'Няслушнае TNS базы зьвестак «$1».
Назва можа ўтрымліваць толькі ASCII-літары (a-z, A-Z), лічбы (0-9), сымбалі падкрэсьліваньня(_) і кропкі (.).',
	'config-invalid-db-name' => 'Няслушная назва базы зьвестак «$1».
Назва можа ўтрымліваць толькі ASCII-літары (a-z, A-Z), лічбы (0-9), сымбалі падкрэсьліваньня(_) і працяжнікі (-).',
	'config-invalid-db-prefix' => 'Няслушны прэфікс базы зьвестак «$1».
Ён можа зьмяшчаць толькі ASCII-літары (a-z, A-Z), лічбы (0-9), сымбалі падкрэсьліваньня (_) і працяжнікі (-).',
	'config-connection-error' => '$1.

Праверце хост, імя карыстальніка і пароль ніжэй і паспрабуйце зноў.',
	'config-invalid-schema' => 'Няслушная схема для MediaWiki «$1».
Выкарыстоўвайце толькі ASCII-літары (a-z, A-Z), лічбы (0-9) і сымбалі падкрэсьліваньня (_).',
	'config-db-sys-create-oracle' => 'Праграма ўсталяваньня падтрымлівае толькі выкарыстаньне рахунку SYSDBA для стварэньня новага рахунку.',
	'config-db-sys-user-exists-oracle' => 'Рахунак карыстальніка «$1» ужо існуе. SYSDBA можа выкарыстоўвацца толькі для стварэньня новых рахункаў!',
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
	'config-sqlite-dir-unwritable' => 'Запіс у дырэкторыю «$1» немагчымы.
Зьмяніце налады доступу, каб вэб-сэрвэр меў правы на запіс, і паспрабуйце зноў.',
	'config-sqlite-connection-error' => '$1.

Праверце дырэкторыю для зьвестак, назву базы зьвестак і паспрабуйце зноў.',
	'config-sqlite-readonly' => 'Файл <code>$1</code> недаступны для запісу.',
	'config-sqlite-cant-create-db' => 'Немагчыма стварыць файл базы зьвестак <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP бракуе падтрымкі FTS3 — табліцы пагаршаюцца',
	'config-can-upgrade' => "У гэтай базе зьвестак ёсьць табліцы MediaWiki.
Каб абнавіць іх да MediaWiki $1, націсьніце '''Працягнуць'''.",
	'config-upgrade-done' => "Абнаўленьне завершанае.

Цяпер Вы можаце [$1 пачаць выкарыстаньне вікі].

Калі Вы жадаеце рэгенэраваць <code>LocalSettings.php</code>, націсьніце кнопку ніжэй.
Гэтае дзеяньне '''не рэкамэндуецца''', калі Вы ня маеце праблемаў у працы вікі.",
	'config-upgrade-done-no-regenerate' => 'Абнаўленьне скончанае.

Цяпер Вы можаце [$1 пачаць працу з вікі].',
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
	'config-mysql-charset' => 'Кадаваньне базы зьвестак:',
	'config-mysql-binary' => 'Двайковае',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "У '''двайковым рэжыме''', MediaWiki захоўвае тэкст у кадаваньні UTF-8 у базе зьвестак у двайковых палях.
Гэта болей эфэктыўна за рэжым MySQL UTF-8, і дазваляе Вам выкарыстоўваць увесь дыяпазон сымбаляў Unicode.

У '''рэжыме UTF-8''', MySQL ведае, якая табліцы сымбаляў выкарыстоўваецца ў Вашых зьвестках, і можа адпаведна прадстаўляць і канвэртаваць іх, але гэта не дазволіць Вам захоўваць сымбалі па-за межамі [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Базавага шматмоўнага дыяпазону].",
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
	'config-ns-conflict' => 'Пазначаная прастора назваў «<nowiki>$1</nowiki>» канфліктуе з прасторай назваў MediaWiki па змоўчваньні.
Пазначце іншую прастору назваў праекту.',
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
	'config-admin-email-help' => 'Увядзіце тут адрас электроннай пошты, каб атрымліваць электронныя лісты ад іншых удзельнікаў вікі, скідваць Ваш пароль і атрымліваць абвешчаньні пра зьмены старонак, якія знаходзяцца ў Вашым сьпісе назіраньня. Вы можаце пакінуць гэтае поле пустым.',
	'config-admin-error-user' => 'Унутраная памылка падчас стварэньня рахунку адміністратара зь іменем «<nowiki>$1</nowiki>».',
	'config-admin-error-password' => 'Унутраная памылка падчас устаноўкі паролю для адміністратара «<nowiki>$1</nowiki>»: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Вы ўвялі няслушны адрас электроннай пошты',
	'config-subscribe' => 'Падпісацца на [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce сьпіс распаўсюджаньня навінаў пра зьяўленьне новых вэрсіяў].',
	'config-subscribe-help' => 'Гэта ня вельмі актыўны сьпіс распаўсюджаньня навінаў пра зьяўленьне новых вэрсіяў, які ўключаючы важныя навіны пра бясьпеку.
Вам неабходна падпісацца на яго і абнавіць Вашае ўсталяваньне MediaWiki, калі зьявяцца новыя вэрсіі.',
	'config-almost-done' => 'Вы амаль што скончылі!
Астатнія налады можна прапусьціць і пачаць усталяваньне вікі.',
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
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-cc-0' => 'Creative Commons Zero',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 ці болей позьняя',
	'config-license-pd' => 'Грамадзкі набытак',
	'config-license-cc-choose' => 'Выберыце іншую ліцэнзію Creative Commons',
	'config-license-help' => "Шматлікія адкрытыя вікі разьмяшчаюць унёскі на ўмовах ліцэнзіі [http://freedomdefined.org/Definition вольнай ліцэнзіі].
Гэта дазваляе ствараць сэнс супольнай уласнасьці і садзейнічае доўгатэрміновым унёскам.
Гэта не неабходна для прыватных і карпаратыўных вікі.

Калі Вы жадаеце выкарыстоўваць тэкст з Вікіпэдыі, і жадаеце каб Вікіпэдыя магла прынімаць тэкст скапіяваны з Вашай вікі, Вам неабходна выбраць ліцэнзію '''Creative Commons Attribution Share Alike'''.

Раней Вікіпэдыя выкарыстоўвала ліцэнзію GNU Free Documentation. Яна ўсё яшчэ дзейнічае, але яна ўтрымлівае некаторыя моманты, якія ўскладняюць паўторнае выкарыстоўваньне і інтэрпрэтацыю матэрыялаў.",
	'config-email-settings' => 'Налады электроннай пошты',
	'config-enable-email' => 'Дазволіць выходзячыя электронныя лісты',
	'config-enable-email-help' => 'Калі Вы жадаеце, каб працавала электронная пошта, неабходна сканфігураваць PHP [http://www.php.net/manual/en/mail.configuration.php адпаведным чынам].
Калі Вы не жадаеце выкарыстоўваць магчымасьці электроннай пошты, Вы можаце яе адключыць.',
	'config-email-user' => 'Дазволіць электронную пошту для сувязі паміж удзельнікамі',
	'config-email-user-help' => 'Дазволіць усім удзельнікам дасылаць адзін аднаму электронныя лісты, калі ўключаная адпаведная магчымасьць ў іх наладах.',
	'config-email-usertalk' => 'Уключыць абвяшчэньні пра паведамленьні на старонцы абмеркаваньня',
	'config-email-usertalk-help' => 'Дазваляе ўдзельнікам атрымліваць абвяшчэньні пра зьмены на старонцы абмеркаваньня, калі гэтая магчымасьць уключаная ў іх наладах.',
	'config-email-watchlist' => 'Уключыць абвяшчэньні пра зьмены ў сьпісе назіраньня',
	'config-email-watchlist-help' => 'Дазваляе ўдзельнікам атрымліваць абвяшчэньні пра зьмены ў іх сьпісе назіраньня, калі гэтая магчымасьць уключаная ў іх наладах.',
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
Каб гэта зрабіць, MediaWiki патрабуе доступу да Інтэрнэту. 

Каб даведацца болей пра гэтую магчымасьць, уключаючы інструкцыю пра тое, як яе ўстанавіць ў любой вікі, акрамя Wikimedia Commons, глядзіце [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos дакумэнтацыю].',
	'config-cc-error' => 'Выбар ліцэнзіі Creative Commons ня даў вынікаў.
Увядзіце назву ліцэнзіі ўручную.',
	'config-cc-again' => 'Выберыце яшчэ раз…',
	'config-cc-not-chosen' => 'Выберыце, якую ліцэнзію Creative Commons Вы жадаеце выкарыстоўваць і націсьніце «працягваць».',
	'config-advanced-settings' => 'Дадатковыя налады',
	'config-cache-options' => 'Налады кэшаваньня аб’ектаў:',
	'config-cache-help' => 'Кэшаваньне аб’ектаў павялічвае хуткасьць працы MediaWiki праз кэшаваньне зьвестак, якія часта выкарыстоўваюцца.
Вельмі рэкамэндуем уключыць гэта для сярэдніх і буйных сайтаў, таксама будзе карысна для дробных сайтаў.',
	'config-cache-none' => 'Без кэшаваньня (ніякія магчымасьці не страчваюцца, але хуткасьць працы буйных сайтаў можа зьнізіцца)',
	'config-cache-accel' => 'Кэшаваньне аб’ектаў PHP (APC, eAccelerator, XCache ці WinCache)',
	'config-cache-memcached' => 'Выкарыстоўваць Memcached (патрабуе дадатковай канфігурацыі)',
	'config-memcached-servers' => 'Сэрвэры memcached:',
	'config-memcached-help' => 'Сьпіс IP-адрасоў, якія будуць выкарыстоўвацца Memcached.
Адрасы павінны быць у асобным радку з пазначэньнем порту, які будзе выкарыстоўвацца. Напрыклад:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Вы выбралі Memcached у якасьці тыпу Вашага кэша, але не пазначылі ніякага сэрвэра',
	'config-memcache-badip' => 'Вы ўвялі няслушны IP-адрас для Memcached: $1',
	'config-memcache-noport' => 'Вы не пазначылі порт для выкарыстаньня сэрвэрам Memcached: $1.
Калі Вы ня ведаеце порт, то па змоўчваньні выкарыстоўваецца 11211',
	'config-memcache-badport' => 'Нумар порту Memcached павінен быць паміж $1 і $2',
	'config-extensions' => 'Пашырэньні',
	'config-extensions-help' => 'Пашырэньні пададзеныя вышэй, былі знойдзеныя ў Вашай дырэкторыі <code>./extensions</code>.

Яны могуць патрабаваць дадатковых наладаў, але іх можна ўключыць зараз',
	'config-install-alreadydone' => "'''Папярэджаньне:''' здаецца, што Вы ўжо ўсталёўвалі MediaWiki і спрабуеце зрабіць гэтай зноў.
Калі ласка, перайдзіце на наступную старонку.",
	'config-install-begin' => 'Пасьля націску кнопкі «{{int:config-continue}}» пачнецца ўсталяваньне MediaWiki.
Калі Вы жадаеце што-небудзь зьмяніць, націсьніце кнопку «Вярнуцца».',
	'config-install-step-done' => 'зроблена',
	'config-install-step-failed' => 'не атрымалася',
	'config-install-extensions' => 'Уключаючы пашырэньні',
	'config-install-database' => 'Налада базы зьвестак',
	'config-install-pg-schema-not-exist' => 'Схема PostgreSQL не існуе',
	'config-install-pg-schema-failed' => 'Немагчыма стварыць табліцу.
Упэўніцеся, што карыстальнік «$1» можа пісаць у схему «$2».',
	'config-install-pg-commit' => 'Захаваньне зьменаў',
	'config-install-pg-plpgsql' => 'Праверка падтрымкі мовы PL/pgSQL',
	'config-pg-no-plpgsql' => 'Вам неабходна ўсталяваць падтрымку мовы PL/pgSQL у базе зьвестак $1',
	'config-pg-no-create-privs' => 'Рахунак, які Вы пазначылі для ўсталяваньня ня мае дастаткова правоў для стварэньня рахунку.',
	'config-install-user' => 'Стварэньне карыстальніка базы зьвестак',
	'config-install-user-alreadyexists' => 'Удзельнік «$1» ужо існуе',
	'config-install-user-create-failed' => 'Немагчыма стварыць ўдзельніка «$1»: $2',
	'config-install-user-grant-failed' => 'Немагчыма даць правы удзельніку «$1»: $2',
	'config-install-tables' => 'Стварэньне табліцаў',
	'config-install-tables-exist' => "'''Папярэджаньне''': Выглядае, што табліцы MediaWiki ужо існуюць.
Стварэньне прапушчанае.",
	'config-install-tables-failed' => "'''Памылка''': немагчыма стварыць табліцы з-за наступнай памылкі: $1",
	'config-install-interwiki' => 'Запаўненьне табліцы інтэрвікі па змоўчваньні',
	'config-install-interwiki-list' => 'Немагчыма знайсьці файл <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Папярэджаньне''': выглядае, што табліца інтэрвікі ўжо запоўненая.
Сьпіс па змоўчваньні прапушчаны.",
	'config-install-stats' => 'Ініцыялізацыі статыстыкі',
	'config-install-keys' => 'Стварэньне сакрэтнага ключа',
	'config-insecure-keys' => "'''Папярэджаньне:''' {{PLURAL:$2|Ключ бясьпекі $1 створаны|Ключы бясьпекі $1 створаныя}} падчас усталяваньня, не зьяўляюцца поўнасьцю бясьпечнымі. Рэкамэндуецца зьмяніць {{PLURAL:$2|яго ўручную|іх уручную}}.",
	'config-install-sysop' => 'Стварэньне рахунку адміністратара',
	'config-install-subscribe-fail' => 'Немагчыма падпісацца на «mediawiki-announce»',
	'config-install-mainpage' => 'Стварэньне галоўнай старонкі са зьместам па змоўчваньні',
	'config-install-extension-tables' => 'Стварэньне табліцаў для ўключаных пашырэньняў',
	'config-install-mainpage-failed' => 'Немагчыма ўставіць галоўную старонку: $1',
	'config-install-done' => "'''Віншуем!'''
Вы пасьпяхова ўсталявалі MediaWiki.

Праграма ўсталяваньня стварыла файл <code>LocalSettings.php</code>.
Ён утрымлівае ўсе Вашыя налады.

Вам неабходна загрузіць яго і захаваць у карэнную дырэкторыю Вашай вікі (у тую ж самую дырэкторыю, дзе знаходзіцца index.php). Загрузка павінна пачацца аўтаматычна.

Калі загрузка не пачалася, ці Вы яе адмянілі, Вы можаце перазапусьціць яе націснуўшы на спасылку ніжэй:

$3

'''Заўвага''': калі Вы гэтага ня зробіце зараз, то створаны файл ня будзе даступны Вам потым, калі Вы выйдзеце з праграмы ўсталяваньня  без яго загрузкі.

Калі Вы гэта зробіце, Вы можаце '''[$2 ўвайсьці ў Вашую вікі]'''.",
	'config-download-localsettings' => 'Загрузіць LocalSettings.php',
	'config-help' => 'дапамога',
);

/** Bulgarian (Български)
 * @author DCLXVI
 */
$messages['bg'] = array(
	'config-desc' => 'Инсталатор на МедияУики',
	'config-title' => 'Инсталиране на МедияУики $1',
	'config-information' => 'Информация',
	'config-localsettings-upgrade' => 'Беше открит файл <code>LocalSettings.php</code>.
За надграждане на съществуващата инсталация, необходимо е в кутията по-долу да се въведе стойността на <code>$wgUpgradeKey</code>.
Тази информация е налична в LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Беше открит файл LocalSettings.php.
За надграждане на наличната инсталация, необходимо е да се стартира update.php',
	'config-localsettings-key' => 'Ключ за надграждане:',
	'config-localsettings-badkey' => 'Предоставеният ключ е неправилен.',
	'config-upgrade-key-missing' => 'Беше открита съществуваща инсталация на МедияУики.
За надграждане на съществуващата инсталация, необходимо е да се постави следният ред в края на файла LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Съществуващият файл LocalSettings.php изглежда непълен.
Променливата $1 не е зададена.
Необходимо е да се редактира файлът LocalSettings.php и да се зададе променливата, след което да се натисне "Продължаване".',
	'config-localsettings-connection-error' => 'Възникна грешка при свързване с базата от данни чрез данните, посочени в LocalSettings.php или AdminSettings.php. Необходимо е да се коригират тези настройки преди повторен опит за свързване.

$1',
	'config-session-error' => 'Грешка при създаване на сесия: $1',
	'config-your-language' => 'Вашият език:',
	'config-your-language-help' => 'Избиране на език за използване по време на инсталацията.',
	'config-wiki-language' => 'Език на уикито:',
	'config-wiki-language-help' => 'Избиране на език, на който ще е основното съдържание на уикито.',
	'config-back' => '← Връщане',
	'config-continue' => 'Продължаване →',
	'config-page-language' => 'Език',
	'config-page-welcome' => 'Добре дошли в МедияУики!',
	'config-page-dbconnect' => 'Свързване с базата от данни',
	'config-page-upgrade' => 'Надграждане на съществуваща инсталация',
	'config-page-dbsettings' => 'Настройки на базата от данни',
	'config-page-name' => 'Име',
	'config-page-options' => 'Настройки',
	'config-page-install' => 'Инсталиране',
	'config-page-complete' => 'Готово!',
	'config-page-restart' => 'Рестартиране на инсталацията',
	'config-page-readme' => 'Информация за софтуера',
	'config-page-releasenotes' => 'Бележки за версията',
	'config-page-copying' => 'Лицензно споразумение',
	'config-page-upgradedoc' => 'Надграждане',
	'config-page-existingwiki' => 'Съществуващо уики',
	'config-help-restart' => 'Необходимо е потвърждение за изтриване на всички въведени и съхранени данни и започване отначало на процеса по инсталация.',
	'config-restart' => 'Да, започване отначало',
	'config-welcome' => '=== Проверка на средата ===
Извършени бяха основни проверки, за да се провери дали средата е подходяща за инсталиране на МедияУики.
Ако е необходима помощ по време на инсталацията, резултатите от направените проверки трябва също да бъдат предоставени.',
	'config-copyright' => "=== Авторски права и Условия ===

$1

Тази програма е свободен софтуер, който може да се променя и/или разпространява според Общия публичен лиценз на GNU, както е публикуван от Free Software Foundation във версия на Лиценза 2 или по-късна версия.

Тази програма се разпространява с надеждата, че ще е полезна, но '''без каквито и да е гаранции'''; без дори косвена гаранция за '''продаваемост''' или '''прогодност за конкретна употреба'''.
За повече подробности се препоръчва преглеждането на Общия публичен лиценз на GNU.

Към програмата трябва да е приложено <doclink href=Copying>копие на Общия публичен лиценз на GNU</doclink>; ако не, можете да пишете на Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. или да [http://www.gnu.org/copyleft/gpl.html го прочетете онлайн].",
	'config-sidebar' => '* [http://www.mediawiki.org Сайт на МедияУики]
* [http://www.mediawiki.org/wiki/Help:Contents Наръчник на потребителя]
* [http://www.mediawiki.org/wiki/Manual:Contents Наръчник на администратора]
* [http://www.mediawiki.org/wiki/Manual:FAQ ЧЗВ]
----
* <doclink href=Readme>Документация</doclink>
* <doclink href=ReleaseNotes>Бележки за версията</doclink>
* <doclink href=Copying>Авторски права</doclink>
* <doclink href=UpgradeDoc>Обновяване</doclink>',
	'config-env-good' => 'Средата беше проверена.
Инсталирането на МедияУики е възможно.',
	'config-env-bad' => 'Средата беше проверена.
Не е възможна инсталация на МедияУики.',
	'config-env-php' => 'Инсталирана е версия на PHP $1.',
	'config-env-php-toolow' => 'Инсталирана е версия на PHP $1.
МедияУики изисква версия PHP $2 или по-нова.',
	'config-unicode-using-utf8' => 'Използване на utf8_normalize.so от Brion Vibber за нормализация на Уникод.',
	'config-unicode-using-intl' => 'Използване на разширението [http://pecl.php.net/intl intl PECL] за нормализация на Уникод.',
	'config-unicode-pure-php-warning' => "'''Предупреждение''': [http://pecl.php.net/intl Разширението intl PECL] не е налично за справяне с нормализацията на Уникод, превключване към по-бавното изпълнение на чист PHP.
Ако сайтът е с голям трафик, препоръчително е запознаването с [http://www.mediawiki.org/wiki/Unicode_normalization_considerations нормализацията на Уникод].",
	'config-no-db' => 'Не може да бъде открит подходящ драйвер за база от данни!',
	'config-no-fts3' => "'''Предупреждение''': SQLite е компилирана без [http://sqlite.org/fts3.html модула FTS3], затова възможностите за търсене няма да са достъпни.",
	'config-register-globals' => "'''Предупреждение: Настройката на PHP <code>[http://php.net/register_globals register_globals]</code> е включена.'''
'''При възможност е препоръчително тя да бъде изключена.'''
МедияУики ще работи, но сървърът е изложен на евентуални пропуски в сигурността.",
	'config-magic-quotes-runtime' => "'''Фатално: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] е активирана!'''
Това може да повреди непредвидимо въвеждането на данните.
Инсталацията на МедияУики е невъзможна докато тази настройка не бъде изключена.",
	'config-magic-quotes-sybase' => "'''Фатално: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] е активирана!'''
Това може да повреди непредвидимо въвеждането на данните.
Инсталацията на МедияУики е невъзможна докато тази настройка не бъде изключена.",
	'config-mbstring' => "'''Фатално: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] е активирана!'''
Това може да повреди непредвидимо въвеждането на данните.
Инсталацията на МедияУики е невъзможна докато тази настройка не бъде изключена.",
	'config-ze1' => "'''Фатално: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] е активирана!'''
Тази настройка причинява ужасни грешки в МедияУики.
Невъзможно е инсталирането и използването на МедияУики докато тази настройка не бъде изключена.",
	'config-safe-mode' => "'''Предупреждение:''' PHP работи в [http://www.php.net/features.safe-mode безопасен режим].
Това може да създаде проблеми, особено ако качването на файлове е разрешено, както и при поддръжката на <code>math</code>.",
	'config-xml-bad' => 'Липсва XML модулът на PHP.
МедияУики се нуждае от някои функции от този модул и няма да работи при наличната конфигурация.
При Mandrake, необходимо е да се инсталира пакетът php-xml.',
	'config-pcre-no-utf8' => "'''Фатално''': Модулът PCRE на PHP изглежда е компилиран без поддръжка на PCRE_UTF8.
За да функционира правилно, МедияУики изисква поддръжка на UTF-8.",
	'config-memory-raised' => '<code>memory_limit</code> на PHP е $1, увеличаване до $2.',
	'config-memory-bad' => "'''Предупреждение:''' <code>memory_limit</code> на PHP е $1.
Стойността вероятно е твърде ниска.
Възможно е инсталацията да се провали!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] е инсталиран',
	'config-apc' => '[http://www.php.net/apc APC] е инсталиран',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] е инсталиран',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] е инсталиран',
	'config-no-cache' => "'''Предупреждение:''' Не бяха открити [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC] [http://trac.lighttpd.net/xcache/ XCache] или [http://www.iis.net/download/WinCacheForPhp WinCache].
Обектното кеширане не е включено.",
	'config-diff3-bad' => 'GNU diff3 не беше намерен.',
	'config-imagemagick' => 'Открит е ImageMagick: <code>$1</code>.
Преоразмеряването на картинки ще бъде включено ако качването на файлове бъде разрешено.',
	'config-gd' => 'Открита е вградена графичната библиотека GD.
Ако качването на файлове бъде включено, ще бъде включена възможността за преоразмеряване на картинки.',
	'config-no-scaling' => 'Не са открити библиотеките GD или ImageMagick.
Преоразмеряването на картинки ще бъде изключено.',
	'config-no-uri' => "'''Грешка:''' Не може да се определи текущия адрес. 
Инсталация беше прекратена.",
	'config-uploads-not-safe' => "'''Предупреждение:''' Папката по подразбиране за качване <code>$1</code> е уязвима от изпълнение на зловредни скриптове.
Въпреки че МедияУики извършва проверка за заплахи в сигурността на всички качени файлове, силно препоръчително е да се [http://www.mediawiki.org/wiki/Manual:Security#Upload_security затвори тази уязвимост в сигурността] преди разрешаване за качване на файлове.",
	'config-db-type' => 'Тип на базата от данни:',
	'config-db-host' => 'Хост на базата от данни:',
	'config-db-host-help' => 'Ако базата от данни е на друг сървър, в кутията се въвежда името на хоста или IP адреса.

Ако се използва споделен уеб хостинг, доставчикът на услугата би трябвало да е предоставил в документацията си коректния хост.

Ако инсталацията протича на Windows-сървър и се използва MySQL, използването на "localhost" може да е неприемливо. В такива случаи се използва "127.0.0.1" за локален IP адрес.',
	'config-db-wiki-settings' => 'Идентифициране на това уики',
	'config-db-name' => 'Име на базата от данни:',
	'config-db-name-help' => 'Избира се име, което да идентифицира уикито.
То не трябва да съдържа интервали.

Ако се използва споделен хостинг, доставчикът на услугата би трябвало да е предоставил или име на базата от данни, която да бъде използвана, или да позволява създаването на бази от данни чрез контролния панел.',
	'config-db-name-oracle' => 'Схема на базата от данни:',
	'config-db-install-account' => 'Потребителска сметка за инсталацията',
	'config-db-username' => 'Потребителско име за базата от данни:',
	'config-db-password' => 'Парола за базата от данни:',
	'config-db-install-username' => 'Въвежда се потребителско име, което ще се използва за свързване с базата от данни по време на процеса по инсталация.
Това не е потребителско име за сметка в МедияУики; това е потребителско име за базата от данни.',
	'config-db-install-password' => 'Въвежда се парола, която ще бъде използвана за свързване с базата от данни по време на инсталационния процес.
Това не е парола за сметка в МедияУики; това е парола за базата от данни.',
	'config-db-install-help' => 'Въвеждат се потребителско име и парола, които ще бъдат използвани за свързване с базата от данни по време на инсталационния процес.',
	'config-db-account-lock' => 'Използване на същото потребителско име и парола по време на нормална работа',
	'config-db-wiki-account' => 'Потребителска сметка за нормална работа',
	'config-db-wiki-help' => 'Въвежда се потребителско име и парола, които ще се използват при нормалното функциониране на уикито.
Ако сметката не съществува и използваната при инсталацията сметка има необходимите права, тази потребителска сметка ще бъде създадена с минималните необходими права за работа с уикито.',
	'config-db-prefix' => 'Представка за таблиците в базата от данни:',
	'config-db-prefix-help' => 'Ако е необходимо да се сподели базата от данни между няколко уикита или между МедияУики и друго уеб приложение, може да се добави представка пред имената на таблиците, за да се избегнат конфликти.
Не се използват интервали.

Това поле обикновено се оставя празно.',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 бинарно',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 с обратна съвестимост с UTF-8',
	'config-mysql-old' => 'Изисква се MySQL $1 или по-нова версия, наличната версия е $2.',
	'config-db-port' => 'Порт на базата от данни:',
	'config-db-schema' => 'Схема за МедияУики',
	'config-db-schema-help' => 'Схемата по-горе обикновено е коректна.
Промени се извършват ако наистина е необходимо.',
	'config-sqlite-dir' => 'Директория за данни на SQLite:',
	'config-sqlite-dir-help' => "SQLite съхранява всички данни в един файл.

По време на инсталацията уеб сървърът трябва да има права за писане в посочената директория.

Тя '''не трябва''' да е достъпна през уеб, затова не е там, където са PHP файловете.

Инсталаторът ще съхрани заедно с нея файл <code>.htaccess</code>, но ако този метод пропадне, някой може да придобие даостъп до суровите данни от базата от данни.
Това включва сурови данни за потребителите (адреси за е-поща, хеширани пароли), както и изтрити версии на страници и друга чувствителна и с ограничен достъп информация от и за уикито.

Базата от данни е препоръчително да се разположи на друго място, например в <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-support-info' => 'МедияУики поддържа следните системи за бази от данни:

$1

Ако не виждате желаната за използване система в списъка по-долу, следвайте инструкциите за активиране на поддръжка по-горе.',
	'config-support-mysql' => '* $1 е най-фобре поддържата система за база от данни, най-добре поддържана от МедияУики ([http://www.php.net/manual/en/mysql.installation.php Как се компилира PHP с поддръжка на MySQL])',
	'config-support-postgres' => '* $1 е популярна система за бази от данни с отворен изходен код, която е алтернатива на MySQL ([http://www.php.net/manual/en/pgsql.installation.php как се компилира PHP с поддръжка на PostgreSQL]). Възможно е все още да има грешки, затова не се препоръчва да се използва в общодостъпна среда.',
	'config-support-sqlite' => '* $1 е лека система за база от данни, която е много добре поддържана. ([http://www.php.net/manual/en/pdo.installation.php Как се компилира PHP с поддръжка на SQLite], използва PDO)',
	'config-support-oracle' => '* $1 е комерсиална корпоративна база от данни. ([http://www.php.net/manual/en/oci8.installation.php Как се компилира PHP с поддръжка на OCI8])',
	'config-header-mysql' => 'Настройки за MySQL',
	'config-header-postgres' => 'Настройки за PostgreSQL',
	'config-header-sqlite' => 'Настройки за SQLite',
	'config-header-oracle' => 'Настройки за Oracle',
	'config-invalid-db-type' => 'Невалиден тип база от данни',
	'config-missing-db-name' => 'Необходимо е да се въведе стойност за "Име на базата от данни"',
	'config-missing-db-host' => 'Необходимо е да се въведе стойност за "Хост на базата от данни"',
	'config-invalid-db-name' => 'Невалидно име на базата от данни "$1".
Използват се само ASCII букви (a-z, A-Z), цифри (0-9), долни черти (_) и тирета (-).',
	'config-invalid-db-prefix' => 'Невалидна представка за базата от данни "$1".
Позволени са само ASCII букви (a-z, A-Z), цифри (0-9), долни черти (_) и тирета (-).',
	'config-connection-error' => '$1.

Необходимо е да се проверят хостът, потребителското име и паролата, след което да се опита отново.',
	'config-invalid-schema' => 'Невалидна схема за МедияУики "$1".
Допустими са само ASCII букви (a-z, A-Z), цифри (0-9) и долни черти (_).',
	'config-db-sys-create-oracle' => 'Инсталаторът поддържа само сметка SYSDBA за създаване на нова сметка.',
	'config-db-sys-user-exists-oracle' => 'Потребителската сметка "$1" вече съществува. SYSDBA може да се използва само за създаване на нова сметка!',
	'config-postgres-old' => 'Изисква се PostgreSQL $1 или по-нова версия, наличната версия е $2.',
	'config-sqlite-name-help' => 'Избира се име, което да идентифицира уикито.
Не се използват интервали или тирета.
Това име ще се използва за име на файла за данни на SQLite.',
	'config-sqlite-mkdir-error' => 'Грешка при създаване на директорията за данни "$1".
Проверете местоположението ѝ и опитайте отново.',
	'config-sqlite-readonly' => 'Файлът <code>$1</code> няма права за писане.',
	'config-sqlite-cant-create-db' => 'Файлът за базата от данни <code>$1</code> не може да бъде създаден.',
	'config-sqlite-fts3-downgrade' => 'Липсва поддръжката на FTS3 за PHP, извършен беше downgradе на таблиците',
	'config-can-upgrade' => "В базата от данни има таблици за МедияУики.
За надграждането им за MediaWiki $1, натиска се '''Продължаване'''.",
	'config-upgrade-done' => "Обновяването приключи.

Вече е възможно [$1 да използвате уикито].

Ако е необходимо, възможно е файлът <code>LocalSettings.php</code> да бъде създаден отново чрез натискане на бутона по-долу.
Това '''не е препоръчително действие''', освен ако не срещате затруднения с уикито.",
	'config-upgrade-done-no-regenerate' => 'Обновяването приключи.

Вече е възможно [$1 да използвате уикито].',
	'config-regenerate' => 'Създаване на LocalSettings.php →',
	'config-show-table-status' => 'Заявката SHOW TABLE STATUS не сполучи!',
	'config-unknown-collation' => "'''Предупреждение:''' Базата от данни използва неразпозната колация.",
	'config-db-web-account' => 'Сметка за уеб достъп до базата от данни',
	'config-db-web-help' => 'Избиране на потребителско име и парола, които уеб сървърът ще използва да се свързва с базата от данни при обичайната работа на уикито.',
	'config-db-web-account-same' => 'Използване на същата сметка като при инсталацията.',
	'config-db-web-create' => 'Създаване на сметката ако все още не съществува',
	'config-db-web-no-create-privs' => 'Посочената сметка за инсталацията не разполага с достатъчно права за създаване на нова сметка.
Необходимо е посочената сметка вече да съществува.',
	'config-mysql-engine' => 'Хранилище на данни:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' почти винаги е най-добрата възможност заради навременната си поддръжка.

'''MyISAM''' може да е по-бърза при инсталации с един потребител или само за четене.
Базите от данни MyISAM се повреждат по-често от InnoDB.",
	'config-mysql-charset' => 'Набор от символи в базата от данни:',
	'config-mysql-binary' => 'Бинарен',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "В '''бинарен режим''' МедияУики съхранява текстовете в UTF-8 в бинарни полета в базата от данни.
Това е по-ефективно от UTF-8 режима на MySQL и позволява използването на пълния набор от символи в Уникод.

В '''UTF-8 режим''' MySQL ще знае в кой набор от символи са данните от уикито и ще може да ги показва и променя по подходящ начин, но няма да позволява складиране на символи извън [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Основния многоезичен набор].",
	'config-site-name' => 'Име на уикито:',
	'config-site-name-help' => 'Това име ще се показва в заглавната лента на браузъра и на различни други места.',
	'config-site-name-blank' => 'Необходимо е да се въведе име на уикито.',
	'config-project-namespace' => 'Именно пространство на проекта:',
	'config-ns-generic' => 'Проект',
	'config-ns-site-name' => 'Същото като името на уикито: $1',
	'config-ns-other' => 'Друго (уточняване)',
	'config-ns-other-default' => 'МоетоУики',
	'config-project-namespace-help' => 'Следвайки примера на Уикипедия, много уикита съхраняват страниците си с правила в "\'\'\'именно пространство на проекта\'\'\'", отделно от основното съдържание.
Всички заглавия на страниците в това именно пространство започват с определена представка, която може да бъде зададена тук.
Обикновено представката произлиза от името на уикито, но не може да съдържа символи като "#" или ":".',
	'config-ns-invalid' => 'Посоченото именно пространство "<nowiki>$1</nowiki>" е невалидно.
Необходимо е да бъде посочено друго.',
	'config-admin-box' => 'Администраторска сметка',
	'config-admin-name' => 'Потребителско име:',
	'config-admin-password' => 'Парола:',
	'config-admin-password-confirm' => 'Парола (повторно):',
	'config-admin-help' => 'Въвежда се предпочитаното потребителско име, например "Иванчо Иванчев".
Това ще е потребителското име, което администраторът ще използва за влизане в уикито.',
	'config-admin-name-blank' => 'Необходимо е да бъде въведено потребителско име на администратора.',
	'config-admin-name-invalid' => 'Посоченото потребителско име "<nowiki>$1</nowiki>" е невалидно.
Необходимо е да се посочи друго.',
	'config-admin-password-blank' => 'Неовходимо е да се въведе парола за администраторската сметка.',
	'config-admin-password-same' => 'Паролата не трябва да е същата като потребителското име.',
	'config-admin-password-mismatch' => 'Двете въведени пароли не съвпадат.',
	'config-admin-email' => 'Адрес за електронна поща:',
	'config-admin-email-help' => 'Въвеждането на адрес за е-поща позволява получаване на е-писма от другите потребители на уикито, възстановяване на изгубена или забравена парола, оповестяване при промени в страниците от списъка за наблюдение. Това поле може да бъде оставено празно.',
	'config-admin-error-user' => 'Възникна вътрешна грешка при създаване на администратор с името "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Възникна вътрешна грешка при задаване на парола за администратора "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Въведен е невалиден адрес за електронна поща',
	'config-subscribe' => 'Абониране за [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce пощенския списък за нови версии].',
	'config-subscribe-help' => 'Това е пощенски списък с малко трафик, който се използва за съобщения при излизане на нови версии, както и за важни проблеми със сигурността.
Абонирането е препоръчително, както и надграждането на инсталацията на МедияУики при излизането на нова версия.',
	'config-almost-done' => 'Инсталацията е почти готова!
Възможно е пропускане на оставащата конфигурация и моментално инсталиране на уикито.',
	'config-optional-continue' => 'Задаване на допълнителни въпроси.',
	'config-optional-skip' => 'Достатъчно, инсталиране на уикито.',
	'config-profile' => 'Профил на потребителските права:',
	'config-profile-wiki' => 'Традиционно уики',
	'config-profile-no-anon' => 'Необходимо е създаване на сметка',
	'config-profile-fishbowl' => 'Само одобрени редактори',
	'config-profile-private' => 'Затворено уики',
	'config-profile-help' => "Уикитата функционират най-добре, когато позволяват на възможно най-много хора да ги редактират.
В МедияУики лесно се преглеждат последните промени и се възстановяват пораженип от недобронамерени потребители.

Въпреки това мнозина смятат МедияУики за полезен софтуер по различни начини и често е трудно да се убедят всички от предимствата на уики модела.
Затова се предоставя възможност за избор.

Уикитата от типа '''{{int:config-profile-wiki}}''' позволяват на всички потребители да редактират, дори и без регистрация.
Уикитата от типа '''{{int:config-profile-no-anon}}''' позволяват достъп до страниците и редактирането им само след създаване на потребителска сметка.

Уики, което е '''{{int:config-profile-fishbowl}}''' позволява на всички да преглеждат страниците, но само предварително одобрени редактори могат да редактират съдържанието.
В '''{{int:config-profile-private}}''' само предварително одобрени потребители могат да четат и редактират съдържанието.

Детайлно обяснение на конфигурациите на потребителските права е достъпно след инсталацията в [http://www.mediawiki.org/wiki/Manual:User_rights Наръчника за потребителски права].",
	'config-license' => 'Авторски права и лиценз:',
	'config-license-none' => 'Без лиценз',
	'config-license-cc-by-sa' => 'Криейтив Комънс Признание-Споделяне на споделеното',
	'config-license-cc-by-nc-sa' => 'Криейтив Комънс Признание-Некомерсиално-Споделяне на споделеното',
	'config-license-gfdl-old' => 'Лиценз за свободна документация на GNU 1.2',
	'config-license-gfdl-current' => 'Лиценз за свободна документация на GNU 1.3 или по-нов',
	'config-license-pd' => 'Обществено достояние',
	'config-license-cc-choose' => 'Избиране на друг лиценз от Криейтив Комънс',
	'config-license-help' => "Много публични уикита поставят всички приноси под [http://freedomdefined.org/Definition/Bg свободен лиценз].
Това помага създаване на усещане за общност и насърчава дългосрочните приноси.
Това не е необходимо за частни или корпоративни уикита.

Ако е необходимо да се използват текстове от Уикипедия, както и Уикипедия да може да използва текстове от уикито, необходимо е да се избере лиценз '''Криейтив Комънс Признание-Споделяне на споделеното'''.

Лицензът за свободна документация на GNU е старият лиценз на съдържанието на Уикипедия.
Той все още е валиден лиценз, но някои негови условия правят по-сложни повторното използване и интерпретацията.",
	'config-email-settings' => 'Настройки за е-поща',
	'config-enable-email' => 'Разрешаване на изходящи е-писма',
	'config-enable-email-help' => 'За да работят възможностите за използване на е-поща, необходимо е [http://www.php.net/manual/en/mail.configuration.php настройките за поща на PHP] да бъдат конфигурирани правилно.
Ако няма да се използват услугите за е-поща в уикито, те могат да бъдат изключени тук.',
	'config-email-user' => 'Позволяване на потребителите да си изпращат е-писма през уикито',
	'config-email-user-help' => 'Позволяване на потребителите да си изпращат е-писма ако са разрешили това в настройките си.',
	'config-email-usertalk' => 'Оповестяване при промяна на потребителската беседа',
	'config-email-usertalk-help' => 'Позволява на потребителите да получават оповестяване при промяна на беседата им, ако това е разрешено в настройките им.',
	'config-email-watchlist' => 'Оповестяване за списъка за наблюдение',
	'config-email-watchlist-help' => 'Позволява на потребителите да получават оповестяване за техните наблюдавани страници, ако това е разрешено в настройките им.',
	'config-email-auth' => 'Потвърждаване на адреса за електронна поща',
	'config-email-auth-help' => "Ако тази настройка е включена, потребителите трябва да потвърдят адреса си за е-поща чрез препратка, която им се изпраща при настройване или промяна.
Само валидните адреси могат да получават е-писма от други потребители или да променят писмата за оповестяване.
Настройването на това е '''препоръчително''' за публични уикита заради потенциални злоупотреби с възможностите за електронна поща.",
	'config-email-sender' => 'Адрес за обратна връзка:',
	'config-email-sender-help' => 'Въвежда се адрес за електронна поща, който ще се използва за обратен адрес при изходящи е-писма.
Това е адресът, на който ще се получават върнатите и неполучени писма.
Много е-пощенски сървъри изискват поне домейн името да е валидно.',
	'config-upload-settings' => 'Картинки и качване на файлове',
	'config-upload-enable' => 'Позволяне качването на файлове',
	'config-upload-help' => 'Качването на файлове е възможно да доведе до пробели със сигурността на сървъра.
Повече информация по темата има в [http://www.mediawiki.org/wiki/Manual:Security раздела за сигурност] в Наръчника.

За позволяване качването на файлове, необходимо е уебсървърът да може да записва в поддиректорията на МедияУики <code>images</code>.
След като това условие е изпълнено, функционалността може да бъде активирана.',
	'config-upload-deleted' => 'Директория за изтритите файлове:',
	'config-upload-deleted-help' => 'Избиране на директория, в която ще се складират изтритите файлове.
В най-добрия случай тя не трябва да е достъпна през уеб.',
	'config-logo' => 'Адрес на логото:',
	'config-logo-help' => 'Обликът по подразбиране на МедияУики вклчва място с размери 135х160 пиксела за лого в горния ляв ъгъл.
Ако има наличен файл с подходящ размер, неговият адрес може да бъде посочен тук.

Ако не е необходимо лого, полето се оставя празно.',
	'config-instantcommons' => 'Включване на Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] е функционалност, която позволява на уикитата да използват картинки, звуци и друга медиа от сайта на Уикимедия [http://commons.wikimedia.org/ Общомедия].
За да е възможно това, МедияУики изисква достъп до Интернет.

Повече информация за тази функционалност, както и инструкции за настройване за други уикита, различни от Общомедия, е налична в [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos наръчника].',
	'config-cc-again' => 'Повторно избиране...',
	'config-advanced-settings' => 'Разширена конфигурация',
	'config-cache-options' => 'Настройки за обектното кеширане:',
	'config-cache-help' => 'Обектното кеширане се използва за подобряване на скоростта на МедияУики чрез кеширане на често използваните данни.
Силно препоръчително е на средните и големите сайтове да включат тази настройка, но малките също могат да се възползват от нея.',
	'config-cache-none' => 'Без кеширане (не се премахва от функционалността, но това влияе на скоростта на по-големи уикита)',
	'config-cache-accel' => 'PHP обектно кеширане (APC, eAccelerator, XCache или WinCache)',
	'config-cache-memcached' => 'Използване на Memcached (изисква допълнителни настройки и конфигуриране)',
	'config-memcached-servers' => 'Memcached сървъри:',
	'config-memcached-help' => 'Списък с IP адреси за използване за Memcached.
Необходимо е да бъдат разделени по един на ред, както и да е посочен порта. Пример: 
127.0.0.1:11211
192.168.1.25:1234',
	'config-memcache-needservers' => 'Избран е Memcached като складиращ тип, но не са посочени сървъри.',
	'config-memcache-badip' => 'Беше въведен невалиден IP адрес за Memcached: $1.',
	'config-extensions' => 'Разширения',
	'config-install-alreadydone' => "'''Предупреждение:''' Изглежда вече сте инсталирали МедияУики и се опитвате да го инсталирате отново.
Продължете към следващата страница.",
	'config-install-begin' => 'Инсталацията на МедияУики ще започне след натискане на бутона "{{int:config-continue}}".
Ако желаете да направите промени, натиснете Връщане.',
	'config-install-step-done' => 'готово',
	'config-install-step-failed' => 'неуспешно',
	'config-install-extensions' => 'Добавяне на разширенията',
	'config-install-database' => 'Създаване на базата от данни',
	'config-install-pg-schema-not-exist' => 'PostgreSQL схемата не съществува',
	'config-install-pg-schema-failed' => 'Създаването на таблиците пропадна.
Необходимо е потребител "$1" да има права за писане в схемата "$2".',
	'config-install-pg-plpgsql' => 'Проверяване за езика PL/pgSQL',
	'config-pg-no-plpgsql' => 'Необходимо е да се инсталира езикът PL/pgSQL в базата от данни $1',
	'config-pg-no-create-privs' => 'Посочената сметка за инсталацията не притежава достатъчно права за създаване на сметка.',
	'config-install-user' => 'Създаване на потребител за базата от данни',
	'config-install-user-alreadyexists' => 'Потребител „$1“ вече съществува',
	'config-install-user-create-failed' => 'Създаването на потребител „$1“ беше неуспешно: $2',
	'config-install-user-grant-failed' => 'Предоставянето на права на потребител "$1" беше неуспешно: $2',
	'config-install-tables' => 'Създаване на таблиците',
	'config-install-tables-exist' => "'''Предупреждение''': Таблиците за МедияУики изглежда вече съществуват.
Пропускане на създаването им.",
	'config-install-tables-failed' => "'''Грешка''': Създаването на таблиците пропадна и върна следната грешка: $1",
	'config-install-interwiki' => 'Попълване на таблицата с междууикитата по подразбиране',
	'config-install-interwiki-list' => 'Файлът <code>interwiki.list</code> не можа да бъде открит.',
	'config-install-interwiki-exists' => "'''Предупреждение''': Таблицата с междууикита изглежда вече съдържа данни.
Пропускане на списъка по подразбиране.",
	'config-install-stats' => 'Инициализиране на статистиките',
	'config-install-keys' => 'Генериране на таен ключ',
	'config-insecure-keys' => "'''Предупреждение:''' {{PLURAL:$2|Сигурният ключ, създаден по време на инсталацията, не е напълно надежден|Сигурните ключове, създадени по време на инсталацията, не са напълно надеждни}} $1 . Обмислете да {{PLURAL:$2|го|ги}} смените ръчно.",
	'config-install-sysop' => 'Създаване на администраторска сметка',
	'config-install-subscribe-fail' => 'Невъзможно беше абонирането за mediawiki-announce',
	'config-install-mainpage' => 'Създаване на Началната страница със съдържание по подразбиране',
	'config-install-extension-tables' => 'Създаване на таблици за включените разширения',
	'config-install-mainpage-failed' => 'Вмъкването на Началната страница беше невъзможно: $1',
	'config-install-done' => "'''Поздравления!'''
Инсталирането на МедияУики приключи успешно.

Инсталаторът създаде файл <code>LocalSettings.php</code>.
Той съдържа всичката необходима основна конфигурация на уикито.

Необходимо е той да бъде изтеглен и поставен в основната директория на уикито (директорията, в която е и index.php). Изтеглянето би трябвало да започне автоматично.

Ако изтеглянето не започне автоматично или е било прекратено, файлът може да бъде изтеглен чрез щракване на препратката по-долу:

$3

'''Забележка''': Ако това не бъде извършено сега, генерираният конфигурационен файл няма да е достъпен на по-късен етап ако не бъде изтеглен сега или инсталацията приключи без изтеглянето му.

Когато файлът вече е в основната директория, '''[$2 уикито ще е достъпно на този адрес]'''.",
	'config-download-localsettings' => 'Изтегляне на LocalSettings.php',
	'config-help' => 'помощ',
);

/** Breton (Brezhoneg)
 * @author Fohanno
 * @author Fulup
 * @author Gwendal
 * @author Y-M D
 */
$messages['br'] = array(
	'config-desc' => 'Poellad staliañ MediaWIki',
	'config-title' => 'Staliadur MediaWiki $1',
	'config-information' => 'Titouroù',
	'config-localsettings-upgrade' => 'Kavet ez eus bet ur restr <code>LocalSettings.php</code>.
Evit hizivaat ar staliadur-se, merkit an talvoud <code>$wgUpgradeKey</code> er voest dindan.
E gavout a rit e LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Dinoet ez eus bet ur restr LocalSettings.php.
Evit lakaat ar staliadur-mañ a-live, implijit --upgrade=yes, mar plij.',
	'config-localsettings-key' => "Alc'hwez hizivaat :",
	'config-localsettings-badkey' => "Direizh eo an alc'hwez merket ganeoc'h",
	'config-upgrade-key-missing' => 'Kavet ez eus bet ur staliadur kent eus MediaWiki.
Evit hizivaat ar staliadur-se, ouzhpennit al linenn da-heul e traoñ ho restr LocalSettings.php:

$1',
	'config-localsettings-incomplete' => "Diglok e seblant bezañ ar restr LocalSettings.php zo anezhi dija.
An argemmenn $1 n'eo ket termenet.
Kemmit LocalSettings.php evit ma vo termenet an argemmenn-se, ha klikit war « Kenderc'hel ».",
	'config-localsettings-connection-error' => "C'hoarvezet ez eus ur fazi en ur gevreañ ouzh an diaz roadennoù oc'h implijout an arventennoù diferet e LocalSettings.php pe AdminSettings.php. Reizhit an arventennoù-se hag esaeit en-dro.

$1",
	'config-session-error' => "Fazi e-ser loc'hañ an dalc'h : $1",
	'config-no-session' => "Kolle teo bet roadennoù ho talc'h !
Gwiriit ar restr php.ini ha bezit sur emañ staliet <code>session.save_path</code> en ur c'havlec'h a zere.",
	'config-your-language' => 'Ho yezh :',
	'config-your-language-help' => 'Dibabit ur yezh da implijout e-pad an argerzh staliañ.',
	'config-wiki-language' => 'Yezh ar wiki :',
	'config-wiki-language-help' => 'Diuzañ ar yezh a vo implijet ar muiañ er wiki.',
	'config-back' => '← Distreiñ',
	'config-continue' => "Kenderc'hel →",
	'config-page-language' => 'Yezh',
	'config-page-welcome' => 'Degemer mat e MediaWiki !',
	'config-page-dbconnect' => "Kevreañ d'an diaz roadennoù",
	'config-page-upgrade' => 'Hizivaat ar staliadur a zo dioutañ',
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
	'config-page-existingwiki' => 'Wiki zo anezhañ dija',
	'config-help-restart' => "Ha c'hoant hoc'h eus da ziverkañ an holl roadennoù hoc'h eus ebarzhet ha da adlañsañ an argerzh staliañ ?",
	'config-restart' => "Ya, adloc'hañ anezhañ",
	'config-welcome' => "=== Gwiriadennoù a denn d'an endro ===
Rekis eo un nebeud gwiriadennoù diazez da welet hag azas eo an endro evit gallout staliañ MediaWiki.
Dleout a rafec'h merkañ disoc'hoù ar gwiriadennoù-se m'hoc'h eus ezhomm skoazell e-pad ar staliadenn.",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Degemer]
* [http://www.mediawiki.org/wiki/Help:Contents Pajenn-stur an implijer]
* [http://www.mediawiki.org/wiki/Manual:Contents Pajenn-stur ar merour]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAG]',
	'config-env-good' => 'Gwiriet eo bet an endro.
Gallout a rit staliañ MediaWiki.',
	'config-env-bad' => "Gwiriet eo bet an endro.
Ne c'hallit ket staliañ MediaWiki.",
	'config-env-php' => 'Staliet eo PHP $1.',
	'config-env-php-toolow' => "Staliet eo PHP $1.
Nemet eo rekis PHP $2 pe nevesoc'h evit MediaWiki.",
	'config-unicode-using-utf8' => "Oc'h implijout utf8_normalize.so gant Brion Vibber evit ar reolata Unicode.",
	'config-unicode-using-intl' => "Oc'h implijout [http://pecl.php.net/intl an astenn PECL intl] evit ar reolata Unicode.",
	'config-no-db' => "Ne c'haller ket kavout ur sturier diaz roadennoù dereat !",
	'config-ze1' => "'''Fazi diremed : [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mod] zo gweredekaet !'''
An dibarzh-mañ zo kaoz da zrein euzhus gant MediaWiki.
Ne c'hallit ket staliañ nag implijout MediaWiki keit ha m'eo gweredekaet an dibarzh-mañ.",
	'config-memory-raised' => '<code>memory_limit</code> ar PHP zo $1, kemmet e $2.',
	'config-memory-bad' => "'''Diwallit :''' Da $1 emañ arventenn <code>memory_limit</code> PHP.
Re izel eo moarvat.
Marteze e c'hwito ar staliadenn !",
	'config-xcache' => 'Staliet eo [http://trac.lighttpd.net/xcache/ XCache]',
	'config-apc' => 'Staliet eo [http://www.php.net/apc APC]',
	'config-eaccel' => 'Staliet eo [http://eaccelerator.sourceforge.net/ eAccelerator]',
	'config-wincache' => 'Staliet eo [http://www.iis.net/download/WinCacheForPhp WinCache]',
	'config-diff3-bad' => "N'eo ket bet kavet GNU diff3.",
	'config-no-uri' => "'''Fazi :''' N'eus ket tu da anavezout URI ar skript red.
Staliadur nullet.",
	'config-db-type' => 'Doare an diaz roadennoù :',
	'config-db-host' => 'Anv implijer an diaz roadennoù :',
	'config-db-host-oracle' => 'TNS an diaz roadennoù :',
	'config-db-wiki-settings' => 'Anavezout ar wiki-mañ',
	'config-db-name' => 'Anv an diaz roadennoù :',
	'config-db-name-oracle' => 'Brastres diaz roadennoù :',
	'config-db-install-account' => 'Kont implijer evit ar staliadur',
	'config-db-username' => 'Anv implijer an diaz roadennoù :',
	'config-db-password' => 'Ger-tremen an diaz roadennoù :',
	'config-db-install-username' => "Ebarzhit an anv implijer a vo implijet da gevreañ ouzh an diaz roadennoù e-pad an argerzh staliañ.
N'eo ket anv implijer ar gont MediaWiki, an anv implijer evit ho tiaz roadennoù eo.",
	'config-db-install-password' => "Ebarzhit ar ger-tremen a vo implijet da gevreañ ouzh an diaz roadennoù e-pad an argerzh staliañ.
N'eo ket ar ger-tremen evit ar gont MediaWiki, ar ger-tremen evit ho tiaz roadennoù eo.",
	'config-db-install-help' => 'Merkañ anv an implijer hag ar ger-tremen a vo implijet evit kevreañ ouzh an diaz roadennoù e-pad an argerzh staliañ.',
	'config-db-account-lock' => 'Implijout ar memes anv implijer ha ger-tremen e-kerzh oberiadurioù boutin',
	'config-db-wiki-account' => 'Kont implijer evit oberiadurioù boutin',
	'config-db-prefix' => 'Rakrann taolennoù an diaz roadennoù :',
	'config-db-charset' => 'Strobad arouezennoù an diaz roadennoù',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binarel',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 kilkenglotus UTF-8',
	'config-mysql-old' => "Rekis eo MySQL $1 pe ur stumm nevesoc'h; ober a rit gant $2.",
	'config-db-port' => 'Porzh an diaz roadennoù :',
	'config-db-schema' => 'Brastres evit MediaWiki',
	'config-sqlite-dir' => "Kavlec'h roadennoù SQLite :",
	'config-oracle-def-ts' => 'Esaouenn stokañ ("tablespace") dre ziouer :',
	'config-oracle-temp-ts' => "Esaouenn stokañ (''tablespace'') da c'hortoz :",
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => "Skoret eo ar reizhiadoù diaz titouroù da-heul gant MediaWiki :

$1

Ma ne welit ket amañ dindan ar reizhiad diaz titouroù a fell deoc'h ober ganti, heuilhit an titouroù a-us (s.o. al liammoù) evit gweredekaat ar skorañ.",
	'config-support-mysql' => '* $1 eo an dibab kentañ evit MediaWiki hag an hini skoret ar gwellañ ([http://www.php.net/manual/en/mysql.installation.php penaos kempunañ PHP gant skor MySQL])',
	'config-support-postgres' => "* $1 zo ur reizhiad diaz titouroù brudet ha digor hag a c'hall ober evit MySQL ([http://www.php.net/manual/en/pgsql.installation.php penaos kempunañ PHP gant skor PostgreSQL])",
	'config-support-sqlite' => "* $1 zo ur reizhiad diaz titouroù skañv skoret eus ar c'hentañ. ([http://www.php.net/manual/en/pdo.installation.php Penaos kempunañ PHP gant skor SQLite], implijout a ra PDO)",
	'config-support-oracle' => '* $1 zo un diaz titouroù kenwerzhel. ([http://www.php.net/manual/en/oci8.installation.php Penaos kempunañ PHP gant skor OCI8])',
	'config-header-mysql' => 'Arventennoù MySQL',
	'config-header-postgres' => 'Arventennoù PostgreSQL',
	'config-header-sqlite' => 'Arventennoù SQLite',
	'config-header-oracle' => 'Arventennoù Oracle',
	'config-invalid-db-type' => 'Direizh eo ar seurt diaz roadennoù',
	'config-missing-db-name' => 'Ret eo deoc\'h merkañ un dalvoudenn evit "Anv an diaz titouroù"',
	'config-missing-db-host' => 'Ret eo deoc\'h merkañ un dalvoudenn evit "Ostiz an diaz titouroù"',
	'config-missing-db-server-oracle' => 'Ret eo deoc\'h merkañ un dalvoudenn evit "Anv TNS an diaz titouroù"',
	'config-invalid-db-server-oracle' => 'Direizh eo anv TNS an diaz titouroù "$1".
Ober hepken gant lizherennoù ASCII (a-z, A-Z), sifroù (0-9), arouezennoù islinennañ (_) ha pikoù (.).',
	'config-invalid-db-name' => 'Direizh eo anv an diaz titouroù "$1".
Ober hepken gant lizherennoù ASCII (a-z, A-Z), sifroù (0-9), arouezennoù islinennañ (_) ha tiredoù (-).',
	'config-invalid-db-prefix' => 'Direizh eo rakger an diaz titouroù "$1".
Ober hepken gant lizherennoù ASCII (a-z, A-Z), sifroù (0-9), arouezennoù islinennañ (_) ha tiredoù (-).',
	'config-connection-error' => '$1.

Gwiriit anv an ostiz, an anv implijer, ar ger-tremen ha klaskit en-dro.',
	'config-invalid-schema' => 'Chema direizh evit MediaWiki "$1".
Grit hepken gant lizherennoù ASCII (a-z, A-Z), sifroù (0-9) hag arouezennoù islinennañ (_).',
	'config-postgres-old' => "Rekis eo PostgreSQL $1 pe ur stumm nevesoc'h; ober a rit gant $2.",
	'config-sqlite-mkdir-error' => 'Ur fazi zo bet e-ser krouiñ ar c\'havlec\'h roadennoù "$1".
Gwiriañ al lec\'hiadur ha klask en-dro.',
	'config-sqlite-readonly' => "N'haller ket skrivañ er restr <code>$1</code>.",
	'config-sqlite-cant-create-db' => "N'haller ket krouiñ restr an diaz roadennoù <code>$1</code>.",
	'config-upgrade-done-no-regenerate' => 'Hizivadenn kaset da benn.

Gallout a rit [$1 kregiñ da implijout ho wiki].',
	'config-regenerate' => 'Adgenel LocalSettings.php →',
	'config-show-table-status' => "C'hwitet ar reked SHOW TABLE STATUS !",
	'config-db-web-account' => 'Kont an diaz roadennoù evit ar voned Kenrouedad',
	'config-db-web-account-same' => 'Ober gant an hevelep kont hag an hini implijet evit ar staliañ',
	'config-db-web-create' => "Krouiñ ar gont ma n'eus ket anezhi c'hoazh",
	'config-mysql-engine' => 'Lusker stokañ :',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Strobad arouezennoù an diaz roadennoù :',
	'config-mysql-binary' => 'Binarel',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Anv ar wiki :',
	'config-site-name-blank' => "Lakait anv ul lec'hienn .",
	'config-project-namespace' => 'Esaouenn anv ar raktres :',
	'config-ns-generic' => 'Raktres',
	'config-ns-site-name' => 'Hevelep anv hag hini ar wiki : $1',
	'config-ns-other' => 'All (spisaat)',
	'config-ns-other-default' => 'MaWiki',
	'config-admin-box' => 'Kont merour',
	'config-admin-name' => "Hoc'h anv :",
	'config-admin-password' => 'Ger-tremen :',
	'config-admin-password-confirm' => 'Adskrivañ ar ger-tremen :',
	'config-admin-help' => 'Merkit hoc\'h anv implijer amañ, da skouer "Yann Vlog".
Hemañ eo an anv a implijot evit kevreañ d\'ar wiki-mañ.',
	'config-admin-name-blank' => 'Lakait anv ur merour.',
	'config-admin-name-invalid' => 'Direizh eo an anv implijer diferet « <nowiki>$1</nowiki> ».
Diferit un anv implijer all.',
	'config-admin-password-blank' => 'Reiñ ur ger-tremen evit kont ar merour.',
	'config-admin-password-same' => "Ne c'hall ket ar ger-tremen bezañ heñvel ouzh anv ar gont.",
	'config-admin-password-mismatch' => "Ne glot ket ar gerioù-tremen hoc'h eus merket an eil gant egile.",
	'config-admin-email' => "Chomlec'h postel :",
	'config-admin-email-help' => "Merkit ur chomlec'h postel amañ evit gallout resev posteloù a-berzh implijerien all eus ar wiki, adderaouekaat ho ker-tremen ha bezañ kelaouet eus ar c'hemmoù degaset d'ar pajennoù zo en ho roll evezhiañ.",
	'config-admin-error-user' => 'Fazi diabarzh en ur grouiñ ur merer gant an anv "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Fazi diabarzh o lakaat ur ger-tremen evit ar merour « <nowiki>$1</nowiki> » : <pre>$2</pre>',
	'config-admin-error-bademail' => "Ebarzhet hoc'h eus ur chomlec'h postel direizh.",
	'config-subscribe' => 'Koumanantit da [https://lists.wikimedia.org/mailman/listinfo/mediawiki-listenn kemennadoù evit ar stummoù nevez].',
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
	'config-license-none' => 'Aotre ebet en traoñ pajenn',
	'config-license-gfdl-old' => 'Aotre implijout teuliaouiñ frank GNU 1.2',
	'config-license-gfdl-current' => "Aotre implijout teuliaouiñ frank GNU 1.3 pe nevesoc'h",
	'config-license-pd' => 'Domani foran',
	'config-license-cc-choose' => 'Dibabit un aotre-implijout Creative Commons personelaet',
	'config-email-settings' => 'Arventennoù ar postel',
	'config-enable-email' => 'Gweredekaat ar posteloù a ya kuit',
	'config-email-user' => 'Gweredekaat ar posteloù a implijer da implijer',
	'config-email-user-help' => "Aotren a ra an holl implijerien da gas posteloù an eil d'egile mard eo bet gweredekaet an arc'hwel ganto en ho penndibaboù.",
	'config-email-watchlist' => "Gweredekaat ar c'hemenn listenn evezhiañ",
	'config-email-auth' => 'Gweredekaat an dilesadur dre bostel',
	'config-email-sender' => "Chomlec'h postel respont :",
	'config-email-sender-help' => "Merkit ar chomlec'h postel da vezañ implijet da chomlec'h distreiñ ar posteloù a ya er-maez. 
Di e vo kaset ar posteloù distaolet.
Niverus eo ar servijerioù postel a c'houlenn da nebeutañ un [http://fr.wikipedia.org/wiki/Nom_de_domaine anv domani] reizh.",
	'config-upload-settings' => 'Pellgargañ skeudennoù ha restroù',
	'config-upload-enable' => 'Gweredekaat ar pellgargañ restroù',
	'config-upload-deleted' => "Kavlec'h evit ar restroù dilamet :",
	'config-logo' => 'URL al logo :',
	'config-instantcommons' => "Gweredekaat ''InstantCommons''",
	'config-cc-again' => 'Dibabit adarre...',
	'config-advanced-settings' => 'Kefluniadur araokaet',
	'config-cache-accel' => 'Krubuilhañ traezoù PHP (APC, eAccelerator, XCache pe WinCache)',
	'config-cache-memcached' => 'Implijout Memcached (en deus ezhomm bezañ staliet ha kefluniet)',
	'config-memcached-servers' => 'Servijerioù Memcached :',
	'config-memcached-help' => "Roll ar chomlec'hioù IP da implijout evit Memcached.
Ret eo dispartiañ anezho gant virgulennoù ha diferañ ar porzh da implijout (da skouer : 127.0.0.1:11211, 192.168.1.25:11211).",
	'config-extensions' => 'Astennoù',
	'config-install-alreadydone' => "'''Diwallit''': Staliet hoc'h eus MediaWiki dija war a seblant hag emaoc'h o klask e staliañ c'hoazh. 
Kit d'ar bajenn war-lerc'h, mar plij.",
	'config-install-step-done' => 'graet',
	'config-install-step-failed' => "c'hwitet",
	'config-install-extensions' => 'En ur gontañ an astennoù',
	'config-install-database' => 'Krouiñ an diaz roadennoù',
	'config-install-pg-schema-failed' => "C'hwitet eo krouidigezh an taolennoù.
Gwiriit hag-eñ e c'hall an implijer « $1 » skrivañ er brastres « $2 ».",
	'config-install-pg-commit' => "O wiriekaat ar c'hemmoù",
	'config-install-pg-plpgsql' => 'O wiriañ ar yezh PL/pgSQL',
	'config-pg-no-plpgsql' => "Ret eo deoc'h staliañ ar yezh PL/pgSQL en diaz roadennoù $1",
	'config-install-user' => 'O krouiñ an diaz roadennoù implijer',
	'config-install-tables' => 'Krouiñ taolennoù',
	'config-install-tables-failed' => "'''Fazi :''' c'hwitet eo krouidigezh an daolenn gant ar fazi-mañ : $1",
	'config-install-interwiki-list' => "Ne c'haller ket kavout ar restr <code>interwiki.list</code>.",
	'config-install-stats' => 'O sevel ar stadegoù',
	'config-install-keys' => "Genel an alc'hwez kuzh",
	'config-install-sysop' => 'Krouidigezh kont ar merour',
	'config-install-subscribe-fail' => "Ne c'haller ket koumanantiñ da mediawiki-announce",
	'config-install-mainpage' => "O krouiñ ar bajenn bennañ gant un endalc'had dre ziouer",
	'config-install-mainpage-failed' => "Ne c'haller ket ensoc'hañ ar bajenn bennañ: $1",
	'config-download-localsettings' => 'Pellgargañ LocalSettings.php',
	'config-help' => 'skoazell',
);

/** Bosnian (Bosanski)
 * @author CERminator
 */
$messages['bs'] = array(
	'config-desc' => 'Instalacija za MediaWiki',
	'config-title' => 'MediaWiki $1 instalacija',
	'config-information' => 'Informacija',
	'config-localsettings-upgrade' => 'Otkrivena je datoteka <code>LocalSettings.php</code>.
Da biste unaprijedili vaš softver, molimo vas upišite vrijednost od <code>$wgUpgradeKey</code> u okvir ispod.
Naći ćete ga u LocalSettings.php.',
	'config-localsettings-key' => 'Ključ za nadgradnju:',
	'config-session-error' => 'Greška pri pokretanju sesije: $1',
	'config-no-session' => 'Vaši podaci sesije su izgubljeni!
Provjerite vaš php.ini i provjerite da li je <code>session.save_path</code> postavljen na pravilni direktorijum.',
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
	'config-help-restart' => 'Da li želite očistiti sve spremljene podatke koje ste unijeli i da započnete ponovo proces instalacije?',
	'config-restart' => 'Da, pokreni ponovo',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Početna strana]
* [http://www.mediawiki.org/wiki/Help:Contents Vodič za korisnike]
* [http://www.mediawiki.org/wiki/Manual:Contents Vodič za administratore]
* [http://www.mediawiki.org/wiki/Manual:FAQ NPP]
----
* <doclink href=Readme>Pročitaj me</doclink>
* <doclink href=ReleaseNotes>Napomene izdanja</doclink>
* <doclink href=Copying>Kopiranje</doclink>
* <doclink href=UpgradeDoc>Poboljšavanje</doclink>',
	'config-env-good' => 'Okruženje je provjereno.
Možete instalirati MediaWiki.',
	'config-env-php' => 'PHP $1 je instaliran.',
	'config-no-db' => 'Nije mogao biti pronađen podgodan drajver za bazu podataka!',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] je instaliran',
	'config-apc' => '[http://www.php.net/apc APC] je instaliran',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] je instaliran',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] je instaliran',
	'config-diff3-bad' => 'GNU diff3 nije pronađen.',
	'config-db-name' => 'Naziv baze podataka:',
	'config-db-name-oracle' => 'Šema baze podataka:',
	'config-header-mysql' => 'Postavke MySQL',
	'config-header-postgres' => 'Postavke PostgreSQL',
	'config-header-sqlite' => 'Postavke SQLite',
	'config-header-oracle' => 'Postavke Oracle',
	'config-invalid-db-type' => 'Nevaljana vrsta baze podataka',
	'config-upgrade-done' => "Nadogradnja završena.

Sada možete [$1 početi koristiti vašu wiki].

Ako želite regenerisati vašu datoteku <code>LocalSettings.php</code>, kliknite na dugme ispod.
Ovo '''nije preporučeno''' osim ako nemate problema s vašom wiki.",
	'config-admin-name' => 'Vaše ime:',
	'config-admin-password' => 'Šifra:',
);

/** Chechen (Нохчийн)
 * @author Sasan700
 */
$messages['ce'] = array(
	'config-no-fts3' => "'''Тергам бе''': SQLite гулйина хуттург йоцуш [http://sqlite.org/fts3.html FTS3] — лахар болхбеш хир дац оцу бухца.",
);

/** Czech (Česky) */
$messages['cs'] = array(
	'config-information' => 'Informace',
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
 * @author LWChris
 * @author Purodha
 * @author The Evil IP address
 * @author Umherirrender
 */
$messages['de'] = array(
	'config-desc' => 'Das MediaWiki-Installationsprogramm',
	'config-title' => 'Installation von MediaWiki $1',
	'config-information' => 'Informationen',
	'config-localsettings-upgrade' => 'Eine Datei <code>LocalSettings.php</code> wurde gefunden.
Um die vorhandene Installation aktualisieren zu können, muss der Wert des Parameters <code>$wgUpgradeKey</code> im folgenden Eingabefeld angegeben werden.
Der Parameterwert befindet sich in der Datei LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Eine Datei <code>LocalSettings.php</code> wurde gefunden.
Um die vorhandene Installation zu aktualisieren, muss die Datei <code>update.php</code> ausgeführt werden.',
	'config-localsettings-key' => 'Aktualisierungsschlüssel:',
	'config-localsettings-badkey' => 'Der angegebene Aktualisierungsschlüssel ist falsch.',
	'config-upgrade-key-missing' => 'Eine MediaWiki-Installation wurde gefunden.
Um die vorhandene Installation aktualisieren zu können, muss die unten angegebene Codezeile in die Datei LocalSettings.php an deren Ende eingefügt werden:

$1',
	'config-localsettings-incomplete' => 'Die vorhandene Datei LocalSettings.php scheint unvollständig zu sein.
Die Variable <code>$1</code> wurde nicht definiert.
Die Datei LocalSettings.php muss entsprechend geändert werden, so dass sie definiert ist. Klicke danach auf „Weiter“.',
	'config-localsettings-connection-error' => 'Beim Verbindungsversuch zur Datenbank ist, unter Verwendung der in den Dateien LocalSettings.php oder AdminSettings.php hinterlegten Einstellungen, ein Fehler aufgetreten. Diese Einstellungen müssen korrigiert werden. Danach kann ein erneuter Versuch unternommen werden.

$1',
	'config-session-error' => 'Fehler beim Starten der Sitzung: $1',
	'config-session-expired' => 'Die Sitzungsdaten scheinen abgelaufen zu sein.
Sitzungen sind für einen Zeitraum von $1 konfiguriert.
Dieser kann durch Anhebung des Parameters <code>session.gc_maxlifetime</code> in der Datei <code>php.ini</code> erhöht werden.
Den Installationsvorgang erneut starten.',
	'config-no-session' => 'Die Sitzungsdaten sind verloren gegangen!
Die Datei <code>php.ini</code> muss geprüft und es muss dabei sichergestellt werden, dass der Parameter <code>session.save_path</code> auf das richtige Verzeichnis verweist.',
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
	'config-page-releasenotes' => 'Versionsinfos (en)',
	'config-page-copying' => 'Kopie der Lizenz',
	'config-page-upgradedoc' => 'Aktualisiere',
	'config-page-existingwiki' => 'Vorhandenes Wiki',
	'config-help-restart' => 'Sollen alle bereits eingegebene Daten gelöscht und der Installationsvorgang erneut gestartet werden?',
	'config-restart' => 'Ja, erneut starten',
	'config-welcome' => '=== Prüfung der Installationsumgebung ===
Basisprüfungen werden durchgeführt, um festzustellen, ob die Installationsumgebung für die Installation von MediaWiki geeignet ist.
Die Ergebnisse dieser Prüfung sollten angegeben werden, sofern während des Installationsvorgangs Hilfe benötigt und erfragt wird.',
	'config-copyright' => "=== Lizenz und Nutzungsbedingungen ===

$1

Dieses Programm ist freie Software, d. h. es kann, gemäß den Bedingungen der von der Free Software Foundation veröffentlichten ''GNU General Public License'', weiterverteilt und/ oder modifiziert werden. Dabei kann die Version 2, oder nach eigenem Ermessen, jede neuere Version der Lizenz verwendet werden.

Dieses Programm wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings '''ohne jegliche Garantie''' und sogar ohne die implizierte Garantie einer '''Marktgängigkeit''' oder '''Eignung für einen bestimmten Zweck'''. Hierzu sind weitere Hinweise in der ''GNU General Public License'' enthalten.

Eine <doclink href=Copying>Kopie der ''GNU General Public License''</doclink> sollte zusammen mit diesem Programm verteilt worden sein. Sofern dies nicht der Fall war, kann eine Kopie bei der Free Software Foundation Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, schriftlich angefordert oder auf deren Website [http://www.gnu.org/copyleft/gpl.html online gelesen] werden.",
	'config-sidebar' => '* [http://www.mediawiki.org Website von MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nutzeranleitung]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratorenanleitung]
* [http://www.mediawiki.org/wiki/Manual:FAQ Häufig gestellte Fragen]
----
* <doclink href=Readme>Lies mich</doclink>
* <doclink href=ReleaseNotes>Versionsinformationen</doclink>
* <doclink href=Copying>Lizenzbestimmungen</doclink>
* <doclink href=UpgradeDoc>Aktualisierung</doclink>',
	'config-env-good' => 'Die Installationsumgebung wurde geprüft.
MediaWiki kann installiert werden.',
	'config-env-bad' => 'Die Installationsumgebung wurde geprüft.
MediaWiki kann nicht installiert werden.',
	'config-env-php' => 'PHP $1 ist installiert.',
	'config-env-php-toolow' => 'PHP $1 ist installiert.
Allerdings benötigt MediaWiki PHP $2 oder höher.',
	'config-unicode-using-utf8' => 'Zur Unicode-Normalisierung wird Brion Vibbers <code>utf8_normalize.so</code> eingesetzt.',
	'config-unicode-using-intl' => 'Zur  Unicode-Normalisierung wird die [http://pecl.php.net/intl PECL-Erweiterung intl] eingesetzt.',
	'config-unicode-pure-php-warning' => "'''Warnung:''' Die [http://pecl.php.net/intl PECL-Erweiterung intl] ist für die Unicode-Normalisierung nicht verfügbar, so dass stattdessen die langsame pure-PHP-Implementierung genutzt wird.
Sofern eine Website mit großer Benutzeranzahl betrieben wird, sollten weitere Informationen auf der Webseite [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-Normalisierung (en)] gelesen werden.",
	'config-unicode-update-warning' => "'''Warnung:''' Die installierte Version des Unicode-Normalisierungswrappers nutzt einer ältere Version der Bibliothek [http://site.icu-project.org/ des ICU-Projekts].
Diese sollte [http://www.mediawiki.org/wiki/Unicode_normalization_considerations aktualisiert] werden, sofern auf die Verwendung von Unicode Wert gelegt wird.",
	'config-no-db' => 'Es konnte kein adäquater Datenbanktreiber gefunden werden!',
	'config-no-db-help' => 'Es muss ein Datenbanktreiber für PHP installiert werden.
Die folgenden Datenbanksysteme werden unterstützt: $1

Sofern ein gemeinschaftlich genutzter Server für das Hosting verwendet wird, muss der Hoster gefragt werden einen adäquaten Datenbanktreiber zu installieren.
Sofern PHP selbst kompiliert wurde, muss es mit es neu konfiguriert werden, wobei der Datenbankclient zu aktivierten ist. Hierzu kann beispielsweise <code>./configure --with-mysql</code> ausgeführt werden.
Sofern PHP über die Paketverwaltung einer Debian- oder Ubuntu-Installation installiert wurde, muss das „php5-mysql“-Paket nachinstalliert werden.',
	'config-no-fts3' => "'''Warnung:''' SQLite wurde ohne das [http://sqlite.org/fts3.html FTS3-Modul] kompiliert, so dass keine Suchfunktionen zur Verfügung stehen.",
	'config-register-globals' => "'''Warnung: Der Parameter <code>[http://php.net/register_globals register_globals]</code> von PHP ist aktiviert.'''
'''Sie sollte deaktiviert werden, sofern dies möglich ist.'''
Die MediaWiki-Installation wird zwar laufen, wobei aber der Server für potentielle Sicherheitsprobleme anfällig ist.",
	'config-magic-quotes-runtime' => "'''Fataler Fehler: Der Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-magic-quotes-sybase' => "'''Fataler Fehler: Der Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-mbstring' => "'''Fataler Fehler: Der Parameter <code>[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]</code> von PHP ist aktiviert!'''
Diese Einstellung verursacht Fehler und führt zu unvorhersehbaren Problemen bei der Dateneingabe.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-ze1' => "'''Fataler Fehler: Der Parameter <code>[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]</code> von PHP ist aktiviert!'''
Diese Einstellung führt zu großen Fehlern bei MediaWiki.
MediaWiki kann nicht installiert werden, solange dieser Parameter nicht deaktiviert wurde.",
	'config-safe-mode' => "'''Warnung:''' Der Funktion <code>[http://www.php.net/features.safe-mode Safe Mode]</code> von PHP ist aktiviert.
Dies kann zu Problemen führen, insbesondere wenn das Hochladen von Dateien möglich sein, bzw. der Auszeichner <code>math</code> genutzt werden soll.",
	'config-xml-bad' => 'Das XML-Modul von PHP fehlt.
MediaWiki benötigt Funktionen, die dieses Modul bereitstellt und wird in der bestehenden Konfiguration nicht funktionieren.
Sofern Mandriva genutzt wird, muss noch das „php-xml“-Paket installiert werden.',
	'config-pcre' => 'Das PHP-Modul für die PCRE-Unterstützung wurde nicht gefunden.
MediaWiki benötigt allerdings perl-kompatible reguläre Ausdrücke, um lauffähig zu sein.',
	'config-pcre-no-utf8' => "'''Fataler Fehler: Das PHP-Modul PCRE scheint ohne PCRE_UTF8-Unterstützung kompiliert worden zu sein.'''
MediaWiki benötigt die UTF-8-Unterstützung, um fehlerfrei lauffähig zu sein.",
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
	'config-diff3-bad' => 'GNU diff3 wurde nicht gefunden.',
	'config-imagemagick' => 'ImageMagick wurde gefunden: <code>$1</code>.
Miniaturansichten von Bildern werden möglich sein, sobald das Hochladen von Dateien aktiviert wurde.',
	'config-gd' => 'Die im System integrierte GD-Grafikbibliothek wurde gefunden.
Miniaturansichten von Bildern werden möglich sein, sobald das Hochladen von Dateien aktiviert wurde.',
	'config-no-scaling' => 'Weder die GD-Grafikbibliothek noch ImageMagick wurden gefunden.
Miniaturansichten von Bildern sind daher nicht möglich.',
	'config-no-uri' => "'''Fehler:''' Die aktuelle URL konnte nicht ermittelt werden.
Der Installationsvorgang wurde daher abgebrochen.",
	'config-uploads-not-safe' => "'''Warnung:''' Das Standardverzeichnis für hochgeladene Dateien <code>$1</code> ist für die willkürliche Ausführung von Skripten anfällig.
Obwohl MediaWiki die hochgeladenen Dateien auf Sicherheitsrisiken überprüft, wird dennoch dringend empfohlen diese [http://www.mediawiki.org/wiki/Manual:Security#Upload_security Sicherheitslücke] zu schließen, bevor das Hochladen von Dateien aktiviert wird.",
	'config-brokenlibxml' => 'Das System nutzt eine Kombination aus PHP- und libxml2-Versionen, die fehleranfällig ist und versteckte Datenfehler bei MediaWiki und anderen Webanwendungen verursachen kann.
PHP muss auf Version 5.2.9 oder später sowie libxml2 auf die Version 2.7.3 oder später aktualisiert werden, um das Problem zu lösen. Installationsabbruch ([http://bugs.php.net/bug.php?id=45996 siehe hierzu die Fehlermeldung bei PHP]).',
	'config-using531' => 'MediaWiki kann nicht zusammen mit PHP $1 verwendet werden. Grund hierfür ist ein Fehler im Zusammenhang mit den Verweisparametern zu <code>__call()</code>.
PHP muss auf Version 5.3.2 oder höher oder 5.3.0 oder niedriger aktualisiert werden, um das Problem zu beheben.
Die Installation wurde abgebrochen.',
	'config-db-type' => 'Datenbanksystem:',
	'config-db-host' => 'Datenbankserver:',
	'config-db-host-help' => 'Sofern sich die Datenbank auf einem anderen Server befindet, ist hier der Servername oder die entsprechende IP-Adresse anzugeben.

Sofern ein gemeinschaftlich genutzter Server verwendet wird, sollte der Hoster den zutreffenden Servernamen in seiner Dokumentation angegeben haben.

Sofern auf einem Windows-Server installiert und MySQL genutzt wird, funktioniert der Servername „localhost“ voraussichtlich nicht. Wenn nicht, sollte  „127.0.0.1“ oder die lokale IP-Adresse angegeben werden.',
	'config-db-host-oracle' => 'Datenbank-TNS:',
	'config-db-host-oracle-help' => 'Einen gültigen [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm „Local Connect“-Namen] angeben. Die „tnsnames.ora“-Datei muss von dieser Installation erkannt werden können.<br />Sofern die Client-Bibliotheken für Version 10g oder neuer verwendet werden, kann auch [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm „Easy Connect“] zur Namensgebung genutzt werden.',
	'config-db-wiki-settings' => 'Bitte identifiziere dieses Wiki',
	'config-db-name' => 'Datenbankname:',
	'config-db-name-help' => 'Bitte einen Namen angeben, mit dem das Wiki identifiziert werden kann.
Dabei sollten keine Leerzeichen verwendet werden.
 
Sofern ein gemeinschaftlich genutzter Server verwendet wird, sollte der Hoster den Datenbanknamen angegeben oder aber die Erstellung einer Datenbank über ein entsprechendes Interface gestattet haben.',
	'config-db-name-oracle' => 'Datenbankschema:',
	'config-db-account-oracle-warn' => 'Es gibt drei von MediaWiki unterstützte Möglichkeiten Oracle als Datenbank einzurichten:

Sofern das Datenbankbenutzerkonto während des Installationsvorgangs erstellt werden soll, muss ein Datenbankbenutzerkonto mit der SYSDBA-Berechtigung zusammen mit den entsprechenden Anmeldeinformationen angegeben werden, mit dem dann über das Web auf die Datenbank zugegriffen werden kann. Alternativ kann man auch lediglich ein einzelnes manuell angelegtes Datenbankbenutzerkonto angeben, mit dem über das Web auf die Datenbank zugegriffen werden kann, sofern dieses über die Berechtigung zur Erstellung von Datenbankschemen verfügt. Zudem ist es möglich zwei Datenbankbenutzerkonten anzugeben von denen eines die Berechtigung zur Erstellung von Datenbankschemen hat und das andere, um mit ihm über das Web auf die Datenbank zuzugreifen.

Ein Skript zum Anlegen eines Datenbankbenutzerkontos mit den notwendigen Berechtigungen findet man unter dem Pfad „…/maintenance/oracle/“ dieser MediaWiki-Installation. Es ist dabei zu bedenken, dass die Verwendung eines Datenbankbenutzerkontos mit beschränkten Berechtigungen die Nutzung der Wartungsfunktionen für das Standarddatenbankbenutzerkonto deaktiviert.',
	'config-db-install-account' => 'Benutzerkonto für die Installation',
	'config-db-username' => 'Name des Datenbankbenutzers:',
	'config-db-password' => 'Passwort des Datenbankbenutzers:',
	'config-db-password-empty' => 'Bitte ein Passwort für den neuen Datenbankbenutzer angeben: $1
Obzwar es möglich ist Datenbankbenutzer ohne Passwort anzulegen, so ist dies aber nicht sicher.',
	'config-db-install-username' => 'Den Benutzernamen angeben, der für die Verbindung mit der Datenbank während des Installationsvorgangs genutzt werden soll. Es handelt sich dabei nicht um den Benutzernamen für das MediaWiki-Konto, sondern um den Benutzernamen der vorgesehenen Datenbank.',
	'config-db-install-password' => 'Das Passwort angeben, das für die Verbindung mit der Datenbank während des Installationsvorgangs genutzt werden soll. Es handelt sich dabei nicht um das Passwort für das MediaWiki-Konto, sondern um das Passwort der vorgesehenen Datenbank.',
	'config-db-install-help' => 'Benutzername und Passwort, die während des Installationsvorgangs, für die Verbindung mit der Datenbank, genutzt werden sollen, sind nun anzugeben.',
	'config-db-account-lock' => 'Derselbe Benutzername und das Passwort müssen während des Normalbetriebs des Wikis verwendet werden.',
	'config-db-wiki-account' => 'Benutzerkonto für den normalen Betrieb',
	'config-db-wiki-help' => 'Bitte Benutzernamen und Passwort angeben, die der Webserver während des Normalbetriebes dazu verwenden soll, eine Verbindung zum Datenbankserver herzustellen.
Sofern ein entsprechendes Benutzerkonto nicht vorhanden ist und das Benutzerkonto für den Installationsvorgang über ausreichende Berechtigungen verfügt, wird dieses Benutzerkonto automatisch mit den Mindestberechtigungen zum Normalbetrieb des Wikis angelegt.',
	'config-db-prefix' => 'Datenbanktabellenpräfix:',
	'config-db-prefix-help' => 'Sofern eine Datenbank für mehrere Wikiinstallationen oder eine Wikiinstallation und eine andere Programminstallation genutzt werden soll, muss ein weiterer Datenbanktabellenpräfix angegeben werden, um Datenbankprobleme zu vermeiden.
Es können keine Leerzeichen verwendet werden.

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
	'config-db-schema-help' => 'Dieses Datenschema ist in der Regel allgemein verwendbar.
Nur Änderungen daran vornehmen, sofern es gute Gründe dafür gibt.',
	'config-sqlite-dir' => 'SQLite-Datenverzeichnis:',
	'config-sqlite-dir-help' => "SQLite speichert alle Daten in einer einzigen Datei.

Das für sie vorgesehene Verzeichnis muss während des Installationsvorgangs beschreibbar sein.

Es sollte '''nicht'' über das Web zugänglich sein, was der Grund ist, warum die Datei nicht dort abgelegt wird, wo sich die PHP-Dateien befinden.

Das Installationsprogramm wird mit der Datei zusammen eine zusätzliche <code>.htaccess</code>-Datei erstellen. Sofern dies scheitert, können Dritte auf die Datendatei zugreifen.
Dies umfasst die Nutzerdaten (E-Mail-Adressen, Passwörter, etc.) wie auch gelöschte Seitenversionen und andere vertrauliche Daten, die im Wiki gespeichert sind.

Es ist daher zu erwägen die Datendatei an gänzlich anderer Stelle abzulegen, beispielsweise im Verzeichnis <code>./var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Standardtabellenraum:',
	'config-oracle-temp-ts' => 'Temporärer Tabellenraum:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki unterstützt die folgenden Datenbanksysteme:

$1

Sofern nicht das Datenbanksystem angezeigt wird, das verwendet werden soll, gibt es oben einen Link zur Anleitung mit Informationen, wie dieses aktiviert werden kann.',
	'config-support-mysql' => '* $1 ist das von MediaWiki primär unterstützte Datenbanksystem ([http://www.php.net/manual/en/mysql.installation.php Anleitung zur Kompilierung von PHP mit MySQL-Unterstützung (en)])',
	'config-support-postgres' => '* $1 ist ein beliebtes Open-Source-Datenbanksystem und eine Alternative zu MySQL ([http://www.php.net/manual/de/pgsql.installation.php Anleitung zur Kompilierung von PHP mit PostgreSQL-Unterstützung]). Es gibt allerdings einige kleinere Implementierungsfehler, so dass von der Nutzung in einer Produktivumgebung abgeraten wird.',
	'config-support-sqlite' => '* $1 ist ein verschlanktes Datenbanksystem, das auch gut unterstützt wird ([http://www.php.net/manual/de/pdo.installation.php Anleitung zur Kompilierung von PHP mit SQLite-Unterstützung], verwendet PHP Data Objects (PDO))',
	'config-support-oracle' => '* $1 ist eine kommerzielle Unternehmensdatenbank ([http://www.php.net/manual/en/oci8.installation.php Anleitung zur Kompilierung von PHP mit OCI8-Unterstützung (en)])',
	'config-header-mysql' => 'MySQL-Einstellungen',
	'config-header-postgres' => 'PostgreSQL-Einstellungen',
	'config-header-sqlite' => 'SQLite-Einstellungen',
	'config-header-oracle' => 'Oracle-Einstellungen',
	'config-invalid-db-type' => 'Unzulässiges Datenbanksystem',
	'config-missing-db-name' => 'Bei „Datenbankname“ muss ein Wert angegeben werden.',
	'config-missing-db-host' => 'Bei „Datenbankhost“ muss ein Wert angegeben werden.',
	'config-missing-db-server-oracle' => 'Für das „Datenbank-TNS“ muss ein Wert eingegeben werden',
	'config-invalid-db-server-oracle' => 'Ungültiges Datenbank-TNS „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) und Punkte (.) verwendet werden.',
	'config-invalid-db-name' => 'Ungültiger Datenbankname „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9), Unter- (_) sowie Bindestriche (-) verwendet werden.',
	'config-invalid-db-prefix' => 'Ungültiger Datenbanktabellenpräfix „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9), Unter- (_) sowie Bindestriche (-) verwendet werden.',
	'config-connection-error' => '$1.

Bitte unten angegebenen Servernamen, Benutzernamen sowie das Passwort überprüfen und es danach erneut versuchen.',
	'config-invalid-schema' => 'Ungültiges Datenschema für MediaWiki „$1“.
Es dürfen nur ASCII-codierte Buchstaben (a-z, A-Z), Zahlen (0-9) und Unterstriche (_) verwendet werden.',
	'config-db-sys-create-oracle' => 'Das Installationsprogramm unterstützt nur die Verwendung eines Datenbankbenutzerkontos mit SYSDBA-Berechtigung zum Anlegen eines neuen Datenbankbenutzerkontos.',
	'config-db-sys-user-exists-oracle' => 'Das Datenbankbenutzerkonto „$1“ ist bereits vorhanden. Ein Datenbankbenutzerkontos mit SYSDBA-Berechtigung kann nur zum Anlegen eines neuen Datenbankbenutzerkontos genutzt werden.',
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
	'config-can-upgrade' => "Es wurden MediaWiki-Tabellen in dieser Datenbank gefunden.
Um sie auf MediaWiki $1 zu aktualisieren, bitte auf '''Weiter''' klicken.",
	'config-upgrade-done' => "Die Aktualisierung ist abgeschlossen.

Das Wiki kann nun [$1 genutzt werden].

Sofern die Datei <code>LocalSettings.php</code> neu erzeugt werden soll, bitte auf die Schaltfläche unten klicken.
Dies wird '''nicht empfohlen''', es sei denn, es treten Probleme mit dem Wiki auf.",
	'config-upgrade-done-no-regenerate' => 'Die Aktualisierung ist abgeschlossen.

Das Wiki kann nun [$1 genutzt werden].',
	'config-regenerate' => 'LocalSettings.php neu erstellen →',
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

'''MyISAM''' ist in Einzelnutzerumgebungen sowie bei schreibgeschützten Wikis schneller.
Bei MyISAM-Datenbanken treten tendenziell häufiger Fehler auf als bei InnoDB-Datenbanken.",
	'config-mysql-charset' => 'Datenbankzeichensatz:',
	'config-mysql-binary' => 'binär',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Im '''binären Modus''' speichert MediaWiki UTF-8 Texte in der Datenbank in binär kodierte Datenfelder.
Dies ist effizienter als der UTF-8-Modus von MySQL und ermöglicht so die Verwendung jeglicher Unicode-Zeichen.

Im '''UTF-8-Modus''' wird MySQL den Zeichensatz der Daten erkennen und sie richtig anzeigen und konvertieren,
allerdings können keine Zeichen außerhalb des [http://de.wikipedia.org/wiki/Basic_Multilingual_Plane#Gliederung_in_Ebenen_und_Bl.C3.B6cke ''Basic Multilingual Plane'' (BMP)] gespeichert werden.",
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
	'config-ns-conflict' => 'Der angegebene Namensraum „<nowiki>$1</nowiki>“ verursacht Problem mit dem Standardnamensraum von MediaWiki.
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
	'config-admin-email-help' => 'Bitte hier eine E-Mail-Adresse angeben, die den E-Mail-Empfang von anderen Benutzern des Wikis, das Zurücksetzen des Passwortes sowie Benachrichtigungen zu Änderungen an beobachteten Seiten ermöglicht. Diese Feld kann leer gelassen werden.',
	'config-admin-error-user' => 'Es ist beim Erstellen des Administrators mit dem Namen „<nowiki>$1</nowiki>“ ein interner Fehler aufgetreten.',
	'config-admin-error-password' => 'Es ist beim Setzen des Passworts für den Administrator „<nowiki>$1</nowiki>“ ein interner Fehler aufgetreten: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Es wurde eine ungültige E-Mail-Adresse angegeben',
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
	'config-profile-fishbowl' => 'ausschließlich berechtigte Bearbeiter',
	'config-profile-private' => 'geschlossenes Wiki',
	'config-profile-help' => "Wikis sind am nützlichsten, wenn so viele Menschen als möglich Bearbeitungen vornehmen können.
Mit MediaWiki ist es einfach die letzten Änderungen nachzuvollziehen und unbrauchbare Bearbeitungen, beispielsweise von unbedarften oder böswilligen Benutzern, rückgängig zu machen.

Allerdings finden etliche Menschen Wikis auch mit anderen Bearbeitungskonzepten sinnvoll. Manchmal ist es auch nicht einfach alle Beteiligten vollständig von den Vorteilen des „Wiki-Prinzips” zu überzeugen. Darum ist eine Auswahl möglich.

Ein '''{{int:config-profile-wiki}}''' ermöglicht es jedermann, sogar ohne über ein Benutzerkonto zu verfügen, Bearbeitungen vorzunehmen.
Ein Wiki bei dem die '''{{int:config-profile-no-anon}}''' ist, bietet höhere Verantwortlichkeit des Einzelnen für seine Bearbeitungen, könnte allerdings Personen mit gelegentlichen Bearbeitungen abschrecken. Ein Wiki mit '''{{int:config-profile-fishbowl}}''' gestattet es nur ausgewählten Benutzern Bearbeitungen vorzunehmen. Allerdings kann dabei die Allgemeinheit die Seiten immer noch betrachten und Änderungen nachvollziehen. Ein '''{{int:config-profile-private}}''' gestattet es nur ausgewählten Benutzern, Seiten zu betrachten sowie zu bearbeiten.

Komplexere Konzepte zur Zugriffssteuerung können erst nach abgeschlossenem Installationsvorgang eingerichtet werden. Hierzu gibt es weitere Informationen auf der Website mit der [http://www.mediawiki.org/wiki/Manual:User_rights entsprechenden Anleitung].",
	'config-license' => 'Lizenz:',
	'config-license-none' => 'Keine Lizenzangabe in der Fußzeile',
	'config-license-cc-by-sa' => 'Creative Commons „Namensnennung, Weitergabe unter gleichen Bedingungen“',
	'config-license-cc-by-nc-sa' => 'Creative Commons „Namensnennung, nicht kommerziell, Weitergabe unter gleichen Bedingungen“',
	'config-license-cc-0' => 'Creative Commons „Zero“',
	'config-license-gfdl-old' => 'GNU-Lizenz für freie Dokumentation 1.2',
	'config-license-gfdl-current' => 'GNU-Lizenz für freie Dokumentation 1.3 oder höher',
	'config-license-pd' => 'Gemeinfreiheit',
	'config-license-cc-choose' => 'Eine benutzerdefinierte Creative-Commons-Lizenz auswählen',
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
	'config-upload-deleted' => 'Verzeichnis für gelöschte Dateien:',
	'config-upload-deleted-help' => 'Bitte ein Verzeichnis auswählen, in dem gelöschte Dateien archiviert werden sollen.
Idealerweise sollte es nicht über das Internet zugänglich sein.',
	'config-logo' => 'URL des Logos:',
	'config-logo-help' => 'Die Standardoberfläche von MediaWiki verfügt, in der oberen linken Ecke, über Platz für eine Logo mit den Maßen 135x160 Pixel.
Bitte ein Logo in entsprechender Größe hochladen und die zugehörige URL an dieser Stelle angeben.

Sofern kein Logo benötigt wird, kann dieses Datenfeld leer bleiben.',
	'config-instantcommons' => '„InstantCommons“ aktivieren',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons InstantCommons] ist eine Funktion, die es Wikis ermöglicht, Bild-, Klang- und andere Mediendateien zu nutzen, die auf der Website [http://commons.wikimedia.org/ Wikimedia Commons] verfügbar sind.
Um diese Funktion zu nutzen, muss MediaWiki eine Verbindung ins Internet herstellen können. 

Weitere Informationen zu dieser Funktion, einschließlich der Anleitung, wie andere Wikis als Wikimedia Commons eingerichtet werden können, gibt es im [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos Handbuch].',
	'config-cc-error' => 'Der Creativ-Commons-Lizenzassistent konnte keine Lizenz ermitteln.
Die Lizenz ist daher jetzt manuell einzugeben.',
	'config-cc-again' => 'Erneut auswählen…',
	'config-cc-not-chosen' => 'Die gewünschte Creative-Commons-Lizenz auswählen und dann auf „weiter“ klicken.',
	'config-advanced-settings' => 'Erweiterte Konfiguration',
	'config-cache-options' => 'Einstellungen für die Zwischenspeicherung von Objekten:',
	'config-cache-help' => 'Objektcaching wird dazu genutzt die Geschwindigkeit von MediaWiki zu verbessern, indem häufig genutzte Daten zwischengespeichert werden.
Mittelgroße bis große Wikis werden sehr ermutigt dies zu nutzen, aber auch für kleine Wikis ergeben sich erkennbare Vorteile.',
	'config-cache-none' => 'Kein Objektcaching (es wird keine Funktion entfernt, allerdings kann die Geschwindigkeit größerer Wikis beeinflusst werden)',
	'config-cache-accel' => 'Objektcaching von PHP (APC, eAccelerator, XCache or WinCache)',
	'config-cache-memcached' => 'Memchached Cacheserver nutzen (erfordert einen zusätzliche Installationsvorgang mitsamt Konfiguration)',
	'config-memcached-servers' => 'Memcached Cacheserver',
	'config-memcached-help' => 'Liste der für Memcached nutzbaren IP-Adressen.
Es sollte eine je Zeile mitsamt des vorgesehenen Ports angegeben werden. Beispiele:
127.0.0.1:11211
192.168.1.25:1234',
	'config-memcache-needservers' => 'Memcached wurde als Cacheserver ausgewählt. Dabei wurde allerdings kein Server angegeben.',
	'config-memcache-badip' => 'Es wurde für Memcached eine ungültige IP-Adresse angegeben: $1',
	'config-memcache-noport' => 'Es wurde kein Port zur Nutzung durch den Memcached Cacheserver angegeben: $1
Sofern der Port unbekannt ist, ist 11211 die Standardangabe.',
	'config-memcache-badport' => 'Der Ports für den Memcached Cacheserver sollten zwischen $1 und $2 liegen',
	'config-extensions' => 'Erweiterungen',
	'config-extensions-help' => 'Die obig angegebenen Erweiterungen wurden im Verzeichnis <code>./extensions</code> gefunden.

Sie könnten zusätzliche Konfigurierung erfordern, können aber bereits jetzt aktiviert werden.',
	'config-install-alreadydone' => "'''Warnung:''' Es wurde eine vorhandene MediaWiki-Installation gefunden.
Es muss daher mit den nächsten Seite weitergemacht werden.",
	'config-install-begin' => 'Durch Drücken von „{{int:config-continue}}“ wird die Installation von MediaWiki gestartet.
Sofern Änderungen vorgenommen werden sollen, kann man auf „Zurück“ klicken.',
	'config-install-step-done' => 'erledigt',
	'config-install-step-failed' => 'gescheitert',
	'config-install-extensions' => 'Einschließlich Erweiterungen',
	'config-install-database' => 'Datenbank wird eingerichtet',
	'config-install-pg-schema-not-exist' => 'Das PostgesSQL-Schema ist nicht vorhanden',
	'config-install-pg-schema-failed' => 'Das Erstellen der Datentabellen ist gescheitert.
Es muss sichergestellt sein, dass der Benutzer „$1“ Schreibzugriff auf das Datenschema „$2“ hat.',
	'config-install-pg-commit' => 'Änderungen anwenden',
	'config-install-pg-plpgsql' => 'Suche nach der Datenbanksprache PL/pgSQL',
	'config-pg-no-plpgsql' => 'Für Datenbank $1 muss die Datenbanksprache PL/pgSQL installiert werden',
	'config-pg-no-create-privs' => 'Das für die Installation angegeben Konto verfügt nicht über ausreichende Berechtigungen, um ein Datenbanknutzerkonto zu erstellen.',
	'config-install-user' => 'Datenbankbenutzer wird erstellt',
	'config-install-user-alreadyexists' => 'Datenbankbenutzer „$1“ ist bereits vorhanden',
	'config-install-user-create-failed' => 'Das Anlegen des Datenbankbenutzers „$1“ ist gescheitert: $2',
	'config-install-user-grant-failed' => 'Die Gewährung der Berechtigung für Datenbankbenutzer „$1“ ist gescheitert: $2',
	'config-install-tables' => 'Datentabellen werden erstellt',
	'config-install-tables-exist' => "'''Warnung:''' Es wurden MediaWiki-Datentabellen gefunden.
Die Erstellung wurde übersprungen.",
	'config-install-tables-failed' => "'''Fehler:''' Die Erstellung der Datentabellen ist aufgrund des folgenden Fehlers gescheitert: $1",
	'config-install-interwiki' => 'Interwikitabellen werden eingerichtet',
	'config-install-interwiki-list' => 'Die Datei <code>interwiki.list</code> konnte nicht gefunden werden.',
	'config-install-interwiki-exists' => "'''Warnung:'''  Es wurden Interwikitabellen mit Daten gefunden.
Die Standardliste wird übersprungen.",
	'config-install-stats' => 'Initialisierung der Statistiken',
	'config-install-keys' => 'Erstellung der Geheimschlüssel',
	'config-insecure-keys' => "'''Warnung:''' {{PLURAL:$2|Der Geheimschlüssel|Die Geheimschlüssel}} $1 {{PLURAL:$2|der|die}} während des Installationsvorgangs generiert wurde, ist nicht sehr sicher. {{PLURAL:$2|Er sollte|Sie sollten}} manuell geändert werden.",
	'config-install-sysop' => 'Administratorkonto wird erstellt',
	'config-install-subscribe-fail' => 'Abonnierung von „mediawiki-announce“ ist gescheitert',
	'config-install-mainpage' => 'Erstellung der Hauptseite mit Standardinhalten',
	'config-install-extension-tables' => 'Erstellung der Tabellen für die aktivierten Erweiterungen',
	'config-install-mainpage-failed' => 'Die Hauptseite konnte nicht erstellt werden: $1',
	'config-install-done' => "'''Herzlichen Glückwunsch!'''
MediaWiki wurde erfolgreich installiert.

Das Installationsprogramm hat die Datei <code>LocalSettings.php</code> erzeugt.
Sie enthält alle Konfigurationseinstellungen.

Diese Datei muss nun heruntergeladen und anschließend in das Stammverzeichnis der MediaWiki-Installation hochgeladen werden. Dies ist dasselbe Verzeichnis, in dem sich auch die Datei <code>index.php</code> befindet. Das Herunterladen sollte automatisch gestartet worden sein.

Sofern dies nicht der Fall war, oder das Herunterladen unterbrochen wurde, kann der Vorgang durch einen Klick auf untenstehenden Link erneut gestartet werden:

$3

'''Hinweis:''' Sofern das Herunterladen der Konfigurationsdatei nicht jetzt durchgeführt wird, wird sie zu einem späteren Zeitpunkt nach dem Beenden des Installationsprogramms nicht mehr zur Verfügung stehen.

Sobald dies alles erledigt wurde, kann auf das '''[$2 Wiki zugegriffen werden]'''.",
	'config-download-localsettings' => 'LocalSettings.php herunterladen',
	'config-help' => 'Hilfe',
);

/** Esperanto (Esperanto)
 * @author Yekrats
 */
$messages['eo'] = array(
	'config-your-language' => 'Via lingvo:',
	'config-your-language-help' => 'Elekti lingvon uzi dum la instalada procezo.',
	'config-wiki-language' => 'Lingvo de la vikio:',
	'config-wiki-language-help' => 'Elekti la ĉefe skribotan lingvon de la vikio.',
	'config-page-welcome' => 'Bonvenon al MediaWiki!',
	'config-page-dbsettings' => 'Agordoj de la datumbazo',
	'config-page-name' => 'Nomo',
	'config-page-options' => 'Agordoj',
	'config-page-install' => 'Instali',
	'config-page-complete' => 'Farita!',
);

/** Spanish (Español)
 * @author Crazymadlover
 * @author Danke7
 * @author Platonides
 * @author Sanbec
 * @author Translationista
 */
$messages['es'] = array(
	'config-desc' => 'El instalador para MediaWiki',
	'config-title' => 'MediaWiki $1 instalación',
	'config-information' => 'Información',
	'config-localsettings-upgrade' => "'''Atención''': Se ha encontrado un fichero de configuración <code>LocalSettings.php</code>.
Para actualizar MediaWiki mueva <code>LocalSettings.php</code> a un lugar seguro y ejecute de nuevo el instalador.",
	'config-session-error' => 'Error comenzando sesión: $1',
	'config-session-expired' => 'Tus datos de sesión parecen haber expirado.
Las sesiones están configuradas por una duración de $1.
Puedes incrementar esto configurando <code>session.gc_maxlifetime</code> en php.ini.
Reiniciar el proceso de instalación.',
	'config-no-session' => 'Se han perdido los datos de sesión.
Verifica tu php.ini y comprueba que <code>session.save_path</code> está establecido en un directorio apropiado.',
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
	'config-welcome' => '=== Comprobación del entorno ===
Se realiza comprobacioens básicas para ver si el entorno es adecuado para la instalación de MediaWiki.
Deberás suministrar los resultados de tales comprobaciones si necesitas ayuda durante la instalación.',
	'config-copyright' => "=== Derechos de autor y Términos de uso ===

$1

Este programa es software libre; puedes redistribuirlo y/o modificarlo en los términos de la Licencia Pública General de GNU, tal como aparece publicada por la Fundación para el Software Libre, tanto la versión 2 de la Licencia, como cualquier versión posterior (según prefiera).

Este programa es distribuido en la esperanza de que sea útil, pero '''sin cualquier garantía'''; inclusive, sin la garantía implícita de la '''posibilidad de ser comercializado''' o de '''idoneidad para cualquier finalidad específica'''.
Consulte la licencia *GNU General *Public *License para más detalles.

En conjunto con este programa debe haber recibido <doclink href=Copying>una copia de la Licencia Pública General de GNU</doclink>; si no la recibió, pídala por escrito a Fundación para el Software Libre, Inc., 51 Franklin Street, Fifth Floor, Boston, ME La 02110-1301, USA o [http://www.gnu.org/copyleft/gpl.html léala en internet].",
	'config-sidebar' => '* [http://www.mediawiki.org Página principal de MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Guía del usuario]
* [http://www.mediawiki.org/wiki/Manual:Contents Guía del administrador]
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas frecuentes]',
	'config-env-good' => 'El entorno ha sido comprobado.
Puedes instalar MediaWiki.',
	'config-env-bad' => 'El entorno ha sido comprobado.
No puedes instalar MediaWiki.',
	'config-env-php' => 'PHP $1 está instalado.',
	'config-unicode-using-utf8' => 'Usando utf8_normalize.so de Brion Vibber para la normalización Unicode.',
	'config-unicode-using-intl' => 'Usando la [http://pecl.php.net/intl extensión intl PECL] para la normalización Unicode.',
	'config-unicode-pure-php-warning' => "'''Advertencia''': La [http://*pecl.*php.*net/*intl extensión intl PECL] no está disponible para efectuar la normalización Unicode.
Si tu web tiene un alto volumen de tráfico, te recomendamos leer acerca de  [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalización Unicode].",
	'config-unicode-update-warning' => "'''Warning''': La versión instalada del contenedor de normalización Unicode usa una versión anterior de la biblioteca del [http://site.icu-project.org/ proyecto ICU].
Deberás [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualizar] si realmente deseas usar Unicode.",
	'config-no-db' => 'No fue posible encontrar un controlador adecuado para la base de datos.',
	'config-no-db-help' => 'Necesitará instalar un controlador de base de datos para PHP.
Estos son los tipos de base de datos compatibles: $1.

Si tu web está en un alojamiento compartido, solicita a tu proveedor la instalación de un controlador de base de datos ocmpatible.
Si has compilado tú mismo(a) el PHP, reconfigúralo con un cliente de base de datos habilitado, por ejemplo mediante <code>./configure --with-mysql</code>.
Si instalaste el PHP a partir de un paquete Debian o Ubuntu, entonces necesitarás instalar también el módulo php5-mysql.',
	'config-no-fts3' => "'''Advertencia''': SQLite está compilado sin el [http://sqlite.org/fts3.html módulo FTS3]. Las funcionalidades de búsqueda no estarán disponibles en esta instalación.",
	'config-register-globals' => "'''Advertencia: La opción de <code>[http://php.net/register_globals register_globals]</code> de PHP está habilitada.'''
'''Desactívela si puede.'''
MediaWiki funcionará, pero tu servidor quedará expuesto a vulnerabilidades de seguridad potenciales.",
	'config-magic-quotes-runtime' => "'''Fatal: ¡[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] está activada!'''
Esta opción causa la imprevisible corrupción de la entrada de datos.
No puedes instalar o utilizar MediaWiki a menos que esta opción esté inhabilitada.",
	'config-magic-quotes-sybase' => "'''Fatal: ¡[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] está activada!'''
Esta opción causa la imprevisible corrupción de la entrada de datos.
No puedes instalar o utilizar MediaWiki a menos que esta opción esté inhabilitada.",
	'config-mbstring' => "'''Fatal: La opción [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] está activada!'''
Esta opción causa errores y puede corromper los datos de una forma imprevisible.
No se puede instalar o usar MediaWiki a menos que esta opción sea desactivada.",
	'config-ze1' => "'''Fatal: ¡La opción [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] está activada!'''
Esta opción causa problemas significativos en MediaWiki.
No se puede instalar o usar MediaWiki a menos que esta opción sea desactivada.",
	'config-safe-mode' => "'''Advertencia:''' El [http://www.php.net/features.safe-mode modo seguro] de PHP está activado.
Este modo puede causar problemas, especialmente en la carga de archivosy en compatibilidad con <code>math</code>.",
	'config-xml-bad' => 'Falta el módulo XML de PHP.
MediaWiki necesita funciones en este módulo y no funcionará con esta configuración.
Si está ejecutando Mandrake, instale el paquete php-xml.',
	'config-pcre' => 'Parece faltar el módulo de compatibilidad PCRE.
MediaWiki necesita que las funciones de expresiones regulares compatibles con Perl estén funcionando.',
	'config-memory-raised' => 'el parámetro <code>memory_limit</code> de PHP es $1, aumentada a $2.',
	'config-memory-bad' => "'''Advertencia:''' El parámetro <code>memory_limit</code> de PHP es $1.
Probablemente este valor es demasiado bajo.
¡La instalación podrá fallar!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] está instalado',
	'config-apc' => '[http://www.php.net/apc APC] está instalado',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] está instalado',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] está instalado',
	'config-no-cache' => "'''Advertencia:''' No pudo encontrarse [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] o [http://www.iis.net/download/WinCacheForPhp WinCache].
El caché de objetos no está habilitado.",
	'config-diff3-bad' => 'GNU diff3 no se encuentra.',
	'config-imagemagick' => 'ImageMagick encontrado: <code>$1</code>.
La miniaturización de imágenes se habilitará si habilitas las cargas.',
	'config-gd' => 'Se ha encontrado una biblioteca de gráficos GD integrada.
La miniaturización de imágenes se habilitará si habilitas las subidas.',
	'config-no-scaling' => 'No se ha encontrado ninguma biblioteca GD o ImageMagik.
Se inhabilitará la miniaturización de imágenes.',
	'config-no-uri' => "'''Error:''' No se pudo determinar el URI actual.
Instalación abortada.",
	'config-db-type' => 'Tipo de base de datos',
	'config-db-host' => 'Servidor de la base de datos:',
	'config-db-wiki-settings' => 'Identifique este wiki',
	'config-db-name' => 'Nombre de base de datos:',
	'config-db-install-account' => 'Cuenta de usuario para instalación',
	'config-db-username' => 'Nombre de usuario de base de datos:',
	'config-db-password' => 'contraseña de base de datos:',
	'config-db-install-help' => 'Ingresar el nombre de usuario y la contraseña que será usada para conectar a la base de datos durante el proceso de instalación.',
	'config-db-account-lock' => 'Usar el mismo nombre de usuario y contraseña durante operación normal',
	'config-db-wiki-account' => 'Usar cuenta para operación normal',
	'config-db-wiki-help' => 'Introduce el nombre de usuario y la contraseña que serán usados para acceder a la base de datos durante la operación normal del wiki.
Si esta cuenta no existe y la cuenta de instalación tiene suficientes privilegios, se creará esta cuenta de usuario con los privilegios mínimos necesarios para la operación normal del wiki.',
	'config-db-prefix' => 'Prefijo para las tablas de la base de datos:',
	'config-db-charset' => 'Conjunto de caracteres de la base de datos',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binario',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 retrocompatible UTF-8',
	'config-mysql-old' => 'Se necesita MySQL $1 o una versión más reciente. Tienes la versión $2.',
	'config-db-port' => 'Puerto de la base de datos:',
	'config-db-schema' => 'Esquema para MediaWiki',
	'config-db-schema-help' => 'Normalmente, los esquemas arriba son los correctos.
Altéralos sólo si tienes la seguridad de que necesitas alterarlos.',
	'config-sqlite-dir' => 'Directorio de datos SQLite:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki es compatible con los siguientes sistemas de bases de datos: 

$1 

Si no encuentras en el listado el sistema de base de datos que estás intentando utilizar, sigue las instrucciones vinculadas arriba para habilitar la compatibilidad.',
	'config-support-mysql' => '* $1 es la base de datos mayoritaria para MediaWiki y la que goza de mayor compatibilidad ([http://www.php.net/manual/es/mysql.installation.php cómo compilar PHP con compatibilidad MySQL])',
	'config-support-postgres' => '* $1 es una popular base de datos de código abierto, alternativa a MySQL. ([http://www.php.net/manual/es/pgsql.installation.php cómo compilar PHP con compatibilidad PostgreSQL])',
	'config-support-sqlite' => '* $1 es una base de datos ligera con gran compatibilidad con MediaWiki. ([http://www.php.net/manual/es/pdo.installation.php Cómo compilar PHP con compatibilidad SQLite], usa PDO)',
	'config-support-oracle' => '* $1 es una base de datos comercial a nivel empresarial ([http://www.php.net/manual/es/oci8.installation.php cómo compilar PHP con compatibilidad con OCI8])',
	'config-header-mysql' => 'Configuración de MySQL',
	'config-header-postgres' => 'Configuración de PostgreSQL',
	'config-header-sqlite' => 'Configuración de SQLite',
	'config-header-oracle' => 'Configuración de Oracle',
	'config-invalid-db-type' => 'Tipo de base de datos inválida',
	'config-missing-db-name' => 'Debes introducir un valor para "Nombre de la base de datos"',
	'config-invalid-db-name' => 'El nombre de la base de datos "$1"  es inválido.
Usa sólo caracteres ASCII: letras (a-z, A-Z), guarismos (0-9) y guiones bajos (_).',
	'config-invalid-db-prefix' => 'El prefijo de la base de datos "$1"  es inválido.
Use sólo carateres ASCII: letras (a-z, A-Z), guarismos (0-9) y guiones bajos (_).',
	'config-connection-error' => '$1.

Verifique el servidor, el nombre de usuario y la contraseña, e intente de nuevo.',
	'config-invalid-schema' => 'El esquema de la base de datos "$1"  es inválido.
Use sólo carateres ASCII: letras (a-z, A-Z), guarismos (0-9) y guiones bajos (_).',
	'config-postgres-old' => 'Se necesita PostgreSQL $1 o una versión más reciente; tienes la versión $2.',
	'config-sqlite-name-help' => 'Elige el nombre que identificará tu wiki.
No uses espacios o guiones.
Este nombre será usado como nombre del archivo de datos de SQLite.',
	'config-sqlite-mkdir-error' => 'Error al crear el directorio de datos "$1".
Comprueba la ubicación e inténtalo de nuevo.',
	'config-sqlite-dir-unwritable' => 'No se puede escribir en el directorio "$1". 
Modifica los permisos para que el servidor web pueda escribir en él y vuelve a intentarlo.',
	'config-sqlite-connection-error' => '$1.

Verifique el directório de datos y el nombre de la base de datos mostrada a continuación e inténtalo nuevamente.',
	'config-sqlite-readonly' => 'El archivo <code>$1</code> no se puede escribir.',
	'config-sqlite-cant-create-db' => 'No fue posible crear el archivo de la base de datos <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'El PHP no tiene compatibilidad FTS3. actualizando tablas a una versión anterior',
	'config-can-upgrade' => "Esta base de datos contiene tablas de MediaWiki.
Para actualizarlas a MediaWiki $1, haz clic en '''Continuar'''.",
	'config-regenerate' => 'Regenerar LocalSettings.php →',
	'config-show-table-status' => 'SHOW TABLE STATUS ha fallado!',
	'config-unknown-collation' => "'''Advertencia:''' La base de datos está utilizando una intercalación no reconocida.",
	'config-db-web-account' => 'Cuenta de base de datos para acceso Web',
	'config-db-web-help' => 'Elige el usuario y contraseña que el servidor Web usará para conectarse al servidor de la base de datos durante el fincionamiento normal del wiki.',
	'config-db-web-account-same' => 'Utilizar la misma cuenta que en la instalación',
	'config-db-web-create' => 'Crear la cuenta si no existe',
	'config-db-web-no-create-privs' => 'La cuenta que has especificado para la instalación no tiene privilegios suficientes para crear una cuenta. 
La cuenta que especifiques aquí debe existir.',
	'config-mysql-engine' => 'Motor de almacenamiento:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' es casi siempre la mejor opción, dado que soporta bien los accesos simultáneos.

'''MyISAM''' es más rápido en instalaciones de usuario único o de sólo lectura.
Las bases de datos MyISAM tienden a corromperse más a menudo que las bases de datos InnoDB.",
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
	'config-ns-other-default' => 'MiWiki',
	'config-ns-invalid' => 'El espacio de nombre especificado "<nowiki>$1</nowiki>" no es válido.
Especifica un espacio de nombre de proyecto diferente.',
	'config-admin-box' => 'Cuenta de administrador',
	'config-admin-name' => 'Tu nombre:',
	'config-admin-password' => 'Contraseña:',
	'config-admin-password-confirm' => 'Repita la contraseña:',
	'config-admin-help' => 'Escribe aquí el nombre de usuario que desees, como por ejemplo "Pedro Bloggs".
Este es el nombre que usarás para entrar al wiki.',
	'config-admin-name-blank' => 'Introduce un nombre de usuario de administrador.',
	'config-admin-name-invalid' => 'El nombre de usuario especificado "<nowiki>$1</nowiki>" no es válido.
Especifique un nombre de usuario diferente.',
	'config-admin-password-blank' => 'Introduzca una contraseña para la cuenta de administrador.',
	'config-admin-password-same' => 'La contraseña no debe ser la misma que el nombre de usuario.',
	'config-admin-password-mismatch' => 'Las dos contraseñas que ingresaste no coinciden.',
	'config-admin-email' => 'Dirección de correo electrónico:',
	'config-admin-email-help' => 'Introduce aquí un correo electrónico que te permita recibir mensajes de otros usuarios del wiki, vuelve a configurar tu contraseña y recibe notificaciones de cambios realizados a tus páginas vigiladas.',
	'config-admin-error-user' => 'Error interno al crear un administrador con el nombre "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Error interno al establecer una contraseña para el administrador " <nowiki>$1</nowiki> ": <pre>$2</pre>',
	'config-subscribe' => 'Suscribirse para recibir [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce avisos de nuevas versiones].',
	'config-subscribe-help' => 'Esta es una lista de divulgación de bajo volumen para anuncios de lanzamiento de versiones nuevas, incluyendo anuncios de seguridad importantes.
Te recomendamos suscribirte y actualizar tu instalación MediaWiki cada vez que se lance una nueva versión.',
	'config-almost-done' => '¡Ya casi has terminado!
Ahora puedes saltarte el resto de pasos e instalar el wiki con valores predeterminados.',
	'config-optional-continue' => 'Hazme más preguntas.',
	'config-optional-skip' => 'Ya estoy aburrido, sólo instala el wiki.',
	'config-profile' => 'Perfil de derechos de usuario:',
	'config-profile-wiki' => 'Wiki tradicional',
	'config-profile-no-anon' => 'Creación de cuenta requerida',
	'config-profile-fishbowl' => 'Sólo editores autorizados',
	'config-profile-private' => 'Wiki privado',
	'config-license' => 'Copyright and licencia:',
	'config-license-none' => 'Pie sin licencia',
	'config-license-cc-by-sa' => 'Creative Commons Reconocimiento Compartir Igual',
	'config-license-cc-by-nc-sa' => 'Creative Commons Reconocimiento Compartir Igual no comercial',
	'config-license-gfdl-old' => 'GNU Licencia de Documentación Libre 1.2',
	'config-license-gfdl-current' => 'Licencia de documentación libre GNU 1.3 o más reciente',
	'config-license-pd' => 'Dominio Público',
	'config-license-cc-choose' => 'Selecciona una licencia personalizada de Creative Commons',
	'config-email-settings' => 'Configuración de correo electrónico',
	'config-enable-email' => 'Activar el envío de e-mails',
	'config-enable-email-help' => 'Si quieres que el correo electrónico funcione, la [http://www.php.net/manual/en/mail.configuration.php configuración PHP de correo electrónico] debe ser la correcta.
Si no quieres la funcionalidad de correo electrónico, puedes desactivarla aquí.',
	'config-email-user' => 'Habilitar correo electrónico de usuario a usuario',
	'config-email-user-help' => 'Permitir que todos los usuarios intercambien correos electrónicos si lo han activado en sus preferencias.',
	'config-email-usertalk' => 'Activar notificaciones de páginas de discusión de usuarios',
	'config-email-usertalk-help' => 'Permitir a los usuarios recibir notificaciones de cambios en la página de discusión de usuario, si lo han activado en sus preferencias.',
	'config-email-watchlist' => 'Activar notificación de alteraciones a la páginas vigiladas',
	'config-email-watchlist-help' => 'Permitir a los usuarios recibir notificaciones de cambios en la páginas que vigilan, si lo han activado en sus preferencias.',
	'config-email-auth' => 'Activar autenticación del correo electrónico',
	'config-email-sender' => 'Dirección de correo electrónico de retorno:',
	'config-email-sender-help' => 'Introduce la dirección de correo electrónico que será usada como dirección de retorno en los mensajes electrónicos de salida.
Aquí llegarán los correos electrónicos que no lleguen a su destino.
Muchos servidores de correo electrónico exigen que por lo menos la parte del nombre del dominio sea válida.',
	'config-upload-settings' => 'Cargas de imágenes y archivos',
	'config-upload-enable' => 'Habilitar la subida de archivos',
	'config-upload-deleted' => '*Directório para los archivos eliminados:',
	'config-upload-deleted-help' => 'Elige un directorio en el que guardar los archivos eliminados. 
Lo ideal es una carpeta no accesible desde la red.',
	'config-logo' => 'URL del logo :',
	'config-instantcommons' => 'Habilitar Instant Commons',
	'config-cc-error' => 'El selector de licencia de Creative Commons no dio resultado. 
Escribe el nombre de la licencia manualmente.',
	'config-cc-again' => 'Elegir otra vez...',
	'config-cc-not-chosen' => 'Elige la licencia Creative Commons que desees y haz clic en "continuar".',
	'config-advanced-settings' => 'Configuración avanzada',
	'config-cache-options' => 'Configuración de la caché de objetos:',
	'config-cache-help' => 'El almacenamiento en caché de objetos se utiliza para mejorar la velocidad de MediaWiki mediante el almacenamiento en caché los datos usados más frecuentemente. 
A los sitios medianos y grandes se les recomienda que permitirlo. También es beneficioso para los sitios pequeños.',
	'config-cache-none' => 'Sin almacenamiento en caché (no se pierde ninguna funcionalidad, pero la velocidad puede resentirse en sitios grandes)',
	'config-cache-accel' => 'Almacenamiento en caché de objetos PHP (APC, eAccelerator, XCache o WinCache)',
	'config-cache-memcached' => 'Utilizar Memcached (necesita ser instalado y configurado aparte)',
	'config-memcached-servers' => 'Servidores Memcached:',
	'config-memcached-help' => 'Lista de direcciones IP que serán usadas para Memcached.
Deben ser separadas por comas y especificar el puerto a utilizar (por ejemplo: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Extensiones',
	'config-extensions-help' => 'Se ha detectado en tu directorio <code>./extensions</code>  las extensiones listadas arriba.

Puede que necesiten configuraciones adicionales, pero puedes habilitarlas ahora.',
	'config-install-alreadydone' => "'''Aviso:''' Parece que ya habías instalado MediaWiki y estás intentando instalarlo nuevamente.
Pasa a la próxima página, por favor.",
	'config-install-step-done' => 'hecho',
	'config-install-step-failed' => 'falló',
	'config-install-extensions' => 'Extensiones inclusive',
	'config-install-database' => 'Configurando la base de datos',
	'config-install-pg-schema-failed' => 'La creación de las tablas ha fallado.
Asegúrate de que el usuario "$1" puede escribir en el esquema "$2".',
	'config-install-user' => 'Creando el usuario de la base de datos',
	'config-install-user-grant-failed' => 'La concesión de permisos para el usuario "$1" ha fallado: $2',
	'config-install-tables' => 'Creando tablas',
	'config-install-tables-exist' => "'''Advertencia''': Al parecer, las tablas de MediaWiki ya existen. Saltándose su creación.",
	'config-install-tables-failed' => "'''Error''': La creación de las tablas falló con el siguiente error: $1",
	'config-install-interwiki' => 'Llenando la tabla interwiki predeterminada',
	'config-install-interwiki-list' => 'No se pudo encontrar el archivo <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Advertencia''': La tabla de interwikis parece ya contener entradas.
Se omitirá la lista predeterminada.",
	'config-install-keys' => 'Generación de clave secreta',
	'config-install-sysop' => 'Creando cuenta de usuario del administrador',
);

/** Basque (Euskara)
 * @author An13sa
 */
$messages['eu'] = array(
	'config-desc' => 'MediaWiki instalatzailea',
	'config-title' => 'MediaWiki $1 instalazioa',
	'config-information' => 'Informazioa',
	'config-session-error' => 'Saio hasierako errorea: $1',
	'config-your-language' => 'Zure hizkuntza:',
	'config-your-language-help' => 'Aukeratu instalazio prozesuan erabiliko den hizkuntza',
	'config-wiki-language' => 'Wiki hizkuntza:',
	'config-back' => '← Atzera',
	'config-continue' => 'Jarraitu →',
	'config-page-language' => 'Hizkuntza',
	'config-page-welcome' => 'Ongi etorri MediaWikira!',
	'config-page-dbconnect' => 'Datu-basera konektatu',
	'config-page-dbsettings' => 'Datu-basearen ezarpenak',
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
	'config-env-php' => 'PHP $1 instalatuta dago.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] instalatuta dago',
	'config-apc' => '[http://www.php.net/apc APC] instalatuta dago',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] instalatuta dago',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] instalatuta dago',
	'config-diff3-bad' => 'GNU diff3 ez da aurkitu.',
	'config-db-type' => 'Datu-base mota:',
	'config-db-wiki-settings' => 'Wiki hau identifikatu',
	'config-db-name' => 'Datu-base izena:',
	'config-db-username' => 'Datu-base lankide izena:',
	'config-db-password' => 'Datu-base pasahitza:',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 bitarra',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
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
	'config-project-namespace' => 'Proiektuaren izen-tartea:',
	'config-ns-generic' => 'Proiektua',
	'config-ns-other' => 'Bestelakoa (zehaztu)',
	'config-ns-other-default' => 'MyWiki',
	'config-admin-box' => 'Administratzaile kontua',
	'config-admin-name' => 'Zure izena:',
	'config-admin-password' => 'Pasahitza:',
	'config-admin-password-confirm' => 'Pasahitza berriz:',
	'config-admin-email' => 'E-posta helbidea:',
	'config-profile-wiki' => 'Wiki tradizionala',
	'config-profile-private' => 'Wiki pribatua',
	'config-license' => 'Copyright eta lizentzia:',
	'config-license-pd' => 'Domeinu Askea',
	'config-email-settings' => 'E-posta hobespenak',
	'config-logo' => 'Logo URL:',
	'config-install-step-done' => 'egina',
);

/** Persian (فارسی)
 * @author Mjbmr
 */
$messages['fa'] = array(
	'config-your-language' => 'زبان شما:',
	'config-wiki-language' => 'زبان ویکی:',
	'config-page-language' => 'زبان',
);

/** Finnish (Suomi)
 * @author Centerlink
 * @author Crt
 * @author Nike
 * @author Olli
 * @author Str4nd
 */
$messages['fi'] = array(
	'config-desc' => 'MediaWiki-asennin',
	'config-title' => 'MediaWikin version $1 asennus',
	'config-information' => 'Tiedot',
	'config-localsettings-upgrade' => '<code>LocalSettings.php</code>-tiedosto on havaittu.
Syötä kohdan <code>$wgUpgradeKey</code> arvo alla olevaan kenttään päivittääksesi asennuksen.
Löydät sen LocalSettings.php-tiedostosta.',
	'config-session-error' => 'Istunnon aloittaminen epäonnistui: $1',
	'config-session-expired' => 'Istuntotietosi näyttävät olevan vanhentuneita.
Istuntojen elinajaksi on määritelty $1.
Voit muuttaa tätä asetusta vaihtamalla kohtaa <code>session.gc_maxlifetime</code> php.ini -tiedostossa.
Käynnistä asennusprosessi uudelleen.',
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
	'config-welcome' => '=== Ympäristön tarkistukset ===
Varmistetaan MediaWikin asennettavuus tähän ympäristöön.
Sinun pitäisi antaa näiden tarkistusten tulokset, jos tarvitset apua asennuksen aikana.',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWikin kotisivu]
* [http://www.mediawiki.org/wiki/Help:Contents Käyttöopas]
* [http://www.mediawiki.org/wiki/Manual:Contents Hallintaopas]
* [http://www.mediawiki.org/wiki/Manual:FAQ UKK]',
	'config-env-good' => 'Asennusympäristö on tarkastettu.
Voit asentaa MediaWikin.',
	'config-env-bad' => 'Asennusympäristö on tarkastettu.
Et voi asentaa MediaWikiä.',
	'config-env-php' => 'PHP $1 on asennettu.',
	'config-no-db' => 'Sopivaa tietokanta-ajuria ei löytynyt!',
	'config-no-db-help' => 'Sinun täytyy asentaa tietokanta-ajuri PHP:lle.
Seuraavat tietokantatyypit on tuettu: $1.

Jos käytät jaettua sivutilaa, kysy palveluntarjoajalta, josko se voisi asentaa sopivan tietokanta-ajurin.
Jos olet kääntänyt PHP:n itse, asenna se tietokantaohjelman kanssa, esimerkiksi käyttäen koodia <code>./configure --with-mysql</code>.
Jos olet asentanut PHP:n Debian tai Ubuntu-paketista, sinun täytyy asentaa myös php5-mysql-moduuli.',
	'config-safe-mode' => "'''Varoitus:''' PHP:n [http://www.php.net/features.safe-mode safe mode] -tila on aktiivinen.
Se voi aiheuttaa ongelmia erityisesti tiedostojen tallentamisen ja matemaattisten kaavojen kanssa.",
	'config-pcre' => 'PCRE-tukimoduuli puuttuu.
MediaWiki vaatii toimiakseen Perl-yhteensopivat säännölliset lausekkeet.',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] on asennettu',
	'config-apc' => '[http://www.php.net/apc APC] on asennettu.',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] on asennettu',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] on asennettu',
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
Se voi sisältää vain kirjaimia (a-z, A-Z), numeroita (0-9) ja alaviivan (_).',
	'config-invalid-db-prefix' => '”$1” ei kelpaa tietokannan etuliitteeksi.
Se voi sisältää vain kirjaimia (a-z, A-Z), numeroita (0-9) ja alaviivan (_).',
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
	'config-project-namespace' => 'Projektinimiavaruus:',
	'config-ns-generic' => 'Projekti',
	'config-admin-name' => 'Nimesi',
	'config-admin-password' => 'Salasana',
	'config-admin-password-confirm' => 'Salasana uudelleen',
	'config-admin-name-blank' => 'Anna ylläpitäjän käyttäjänimi.',
	'config-admin-email' => 'Sähköpostiosoite',
	'config-profile-private' => 'Yksityinen wiki',
	'config-install-step-done' => 'tehty',
	'config-install-step-failed' => 'epäonnistui',
	'config-help' => 'ohje',
);

/** French (Français)
 * @author Aadri
 * @author Crochet.david
 * @author Hashar
 * @author IAlex
 * @author Jean-Frédéric
 * @author McDutchie
 * @author Peter17
 * @author Sherbrooke
 * @author Verdy p
 * @author Yumeki
 */
$messages['fr'] = array(
	'config-desc' => 'Le programme d’installation de MediaWiki',
	'config-title' => 'Installation de MediaWiki $1',
	'config-information' => 'Informations',
	'config-localsettings-upgrade' => 'Un fichier <code>LocalSettings.php</code> a été détecté.
Pour mettre à jour cette installation, veuillez saisir la valeur de <code>$wgUpgradeKey</code> dans le champ ci-dessous.
Vous la trouverez dans LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Un fichier LocalSettings.php a été détecté. 
Pour mettre à niveau cette installation, veuillez exécuter update.php',
	'config-localsettings-key' => 'Clé de mise à jour :',
	'config-localsettings-badkey' => 'La clé que vous avez fournie est incorrecte',
	'config-upgrade-key-missing' => 'Une installation existante de MediaWiki a été détectée.

Pour mettre à jour cette installation, veuillez ajouter la ligne suivante à la fin de votre fichier LocalSettings.php

$1',
	'config-localsettings-incomplete' => 'Le fichier LocalSettings.php existant semble être incomplet.
La variable $1 n’est pas définie.
Veuillez modifier LocalSettings.php de sorte que cette variable soit définie, puis cliquer sur « Continuer ».',
	'config-localsettings-connection-error' => 'Une erreur est survenue lors de la connexion à la base de données en utilisant la configuration spécifiée dans LocalSettings.php ou AdminSettings.php. Veuillez corriger cette configuration puis réessayer.

$1',
	'config-session-error' => 'Erreur lors du démarrage de la session : $1',
	'config-session-expired' => "↓Les données de votre session semblent avoir expiré.
Les sessions sont configurées pour une durée de $1.
Vous pouvez l'augmenter en configurant <code>session.gc_maxlifetime</code> dans le fichier php.ini.
Redémarrer le processus d'installation.",
	'config-no-session' => 'Les données de votre session ont été perdues !
Vérifiez votre fichier php.ini et assurez-vous que <code>session.save_path</code> contient le chemin d’un répertoire approprié.',
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
	'config-page-existingwiki' => 'Wiki existant',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>Lisez-moi</doclink>
* <doclink href=ReleaseNotes>Notes de pblication</doclink>
* <doclink href=Copying>Copie</doclink>
* <doclink href=UpgradeDoc>Mise à jour</doclink>',
	'config-env-good' => 'L’environnement a été vérifié.
Vous pouvez installer MediaWiki.',
	'config-env-bad' => 'L’environnement a été vérifié.
vous ne pouvez pas installer MediaWiki.',
	'config-env-php' => 'PHP $1 est installé.',
	'config-env-php-toolow' => 'PHP $1 est installé. 
Cependant, MediaWiki requiert PHP $2 ou plus haut.',
	'config-unicode-using-utf8' => 'Utilisation de utf8_normalize.so par Brion Vibber pour la normalisation Unicode.',
	'config-unicode-using-intl' => "Utilisation de [http://pecl.php.net/intl l'extension PECL intl] pour la normalisation Unicode.",
	'config-unicode-pure-php-warning' => "'''Attention''': L'[http://pecl.php.net/intl extension PECL intl] n'est pas disponible pour la normalisation d’Unicode, retour à la version lente implémentée en PHP.
Si vous utilisez un site web très fréquenté, vous devriez lire ceci : [http://www.mediawiki.org/wiki/Unicode_normalization_considerations ''Unicode normalization''] (en anglais).",
	'config-unicode-update-warning' => "'''Attention''': La version installée du ''wrapper'' de normalisation Unicode utilise une vieille version de la [http://site.icu-project.org/ bibliothèque logicielle ''ICU Project''].
Vous devriez faire une [http://www.mediawiki.org/wiki/Unicode_normalization_considerations mise à jour] (texte en anglais) si l'usage d'Unicode vous semble important.",
	'config-no-db' => 'Impossible de trouver un pilote de base de données approprié !',
	'config-no-db-help' => "Vous avez besoin d'installer un pilote de base de données pour PHP. 
Les types de base de données suivants sont supportés: $1. 

Si vous êtes en hébergement mutualisé, demandez à votre fournisseur d'hébergement pour installer un pilote de base de données appropriée. 
Si vous avez compilé PHP vous-même, reconfigurez-le en activant un client de base de données, par exemple en utilisant <code>./configure --with-mysql</code>. 
Si vous avez installé PHP à partir d'un paquet Debian ou Ubuntu, vous devez également installer le module php5-mysql.",
	'config-no-fts3' => "'''Attention :''' SQLite est compilé sans le module [http://sqlite.org/fts3.html FTS3] ; les fonctions de recherche ne seront pas disponibles sur ce moteur.",
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
	'config-xml-bad' => 'Le module XML de PHP est manquant.
MediaWiki requiert des fonctions de ce module et ne fonctionnera pas avec cette configuration.
Si vous êtes sous Mandrake, installez le paquet php-xml.',
	'config-pcre' => "Le module de support PCRE semble manquer. 
MediaWiki requiert les fonctions d'expression régulière compatible avec Perl.",
	'config-pcre-no-utf8' => "'''Erreur fatale''': Le module PCRE de PHP semble être compilé sans le support PCRE_UTF8.
MédiaWiki nécessite la gestion d’UTF-8 pour fonctionner correctement.",
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
	'config-diff3-bad' => 'GNU diff3 introuvable.',
	'config-imagemagick' => "ImageMagick trouvé : <code>$1</code>. 
La miniaturisation d'images sera activée si vous activez le téléversement de fichiers.",
	'config-gd' => "La bibliothèque graphique GD intégrée a été trouvée. 
La miniaturisation d'images sera activée si vous activez le téléversement de fichiers.",
	'config-no-scaling' => "Impossible de trouver la bibliothèque GD ou ImageMagick. 
La miniaturisation d'images sera désactivé.",
	'config-no-uri' => "'''Erreur :''' Impossible de déterminer l'URI du script actuel. 
Installation avortée.",
	'config-uploads-not-safe' => "'''Attention:''' Votre répertoire par défaut pour les téléchargements, <code>$1</code>, est vulnérable, car il peut exécuter n'importe quel script. 
Bien que MediaWiki vérifie tous les fichiers téléchargés, il est fortement recommandé de [http://www.mediawiki.org/wiki/Manual:Security#Upload_security fermer cette vulnérabilité de sécurité] (texte en anglais) avant d'activer les téléchargements.",
	'config-brokenlibxml' => 'Votre système utilise une combinaison de versions de PHP et libxml2 qui est boguée et peut engendrer des corruptions cachées de données dans MediaWiki et d’autres applications web.
Veuillez mettre à jour votre système vers PHP 5.2.9 ou plus récent et libxml2 2.7.3 ou plus récent ([http://bugs.php.net/bug.php?id=45996 bogue déposé auprès de PHP]).
Installation interrompue.',
	'config-using531' => 'MediaWiki ne peut pas être utilisé avec PHP $1 à cause d’un bogue affectant les paramètres passés par référence à <code>__call()</code>.
Veuillez mettre à jour votre système vers PHP 5.3.2 ou plus récent ou revenir à PHP 5.3.0 pour résoudre ce problème.
Installation interrompue.',
	'config-db-type' => 'Type de base de données :',
	'config-db-host' => 'Nom d’hôte de la base de données :',
	'config-db-host-help' => "Si votre serveur de base de données est sur un serveur différent, saisissez ici son nom d’hôte ou son adresse IP.

Si vous utilisez un hébergement mutualisé, votre hébergeur doit vous avoir fourni le nom d’hôte correct dans sa documentation.

Si vous installez sur un serveur Windows et utilisez MySQL, « localhost » peut ne pas fonctionner comme nom de serveur. S'il ne fonctionne pas, essayez « 127.0.0.1 » comme adresse IP locale.",
	'config-db-host-oracle' => 'Nom TNS de la base de données :',
	'config-db-host-oracle-help' => 'Entrez un [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm nom de connexion locale] valide ; un fichier tnsnames.ora doit être visible par cette installation.<br /> Si vous utilisez les bibliothèques clientes version 10g ou plus récentes, vous pouvez également utiliser la méthode de nommage [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identifier ce wiki',
	'config-db-name' => 'Nom de la base de données :',
	'config-db-name-help' => "Choisissez un nom qui identifie votre wiki.
Il ne doit pas contenir d'espaces.

Si vous utilisez un hébergement web partagé, votre hébergeur vous fournira un nom spécifique de base de données à utiliser, ou bien vous permet de créer des bases de données via un panneau de contrôle.",
	'config-db-name-oracle' => 'Schéma de base de données :',
	'config-db-account-oracle-warn' => "Il existe trois scénarios pris en charge pour l’installation d'Oracle comme backend de base : 

Si vous souhaitez créer un compte de base de données dans le cadre de la procédure d’installation, veuillez fournir un compte avec le rôle de SYSDBA comme compte de base de données pour l’installation et spécifiez les informations d’identification souhaitées pour le compte d'accès au web, sinon vous pouvez créer le compte d’accès web manuellement et fournir uniquement ce compte (si elle a exigé des autorisations nécessaires pour créer les objets de schéma) ou fournir deux comptes différents, l’un avec les privilèges de créer et une restreinte pour l’accès web. 

Un script pour créer un compte avec des privilèges requis peut être trouvé dans le répertoire « entretien/oracle/ » de cette installation. N’oubliez pas que le fait de l’utilisation d’un compte limité désactive toutes les fonctionnalités d’entretien avec le compte par défaut.",
	'config-db-install-account' => "Compte d'utilisateur pour l'installation",
	'config-db-username' => 'Nom d’utilisateur de la base de données :',
	'config-db-password' => 'Mot de passe de la base de données :',
	'config-db-password-empty' => "Veuillez entrer un mot de passe pour le nouvel compte de la base de données : $1.
Bien qu'il soit possible de créer un compte sans mot de passe, ce n'est pas recommandé pour des questions de sécurité.",
	'config-db-install-username' => "Entrez le nom d’utilisateur qui sera utilisé pour se connecter à la base de données pendant le processus d'installation. Il ne s’agit pas du nom d’utilisateur du compte MediaWiki, mais du nom d’utilisateur pour votre base de données.",
	'config-db-install-password' => "Entrez le mot de passe qui sera utilisé pour se connecter à la base de données pendant le processus d'installation. Il ne s’agit pas du mot de passe du compte MediaWiki, mais du mot de passe pour votre base de données.",
	'config-db-install-help' => "Entrez le nom d'utilisateur et le mot de passe qui seront utilisés pour se connecter à la base de données pendant le processus d'installation.",
	'config-db-account-lock' => "Utiliser le même nom d'utilisateur et le même mot de passe pendant le fonctionnement habituel",
	'config-db-wiki-account' => "Compte d'utilisateur pour le fonctionnement habituel",
	'config-db-wiki-help' => "Entrez le nom d'utilisateur et le mot de passe qui seront utilisés pour se connecter à la base de données pendant le fonctionnement habituel du wiki. 
Si le compte n'existe pas, et le compte d'installation dispose de privilèges suffisants, ce compte d'utilisateur sera créé avec les privilèges minimum requis pour faire fonctionner le wiki.",
	'config-db-prefix' => 'Préfixe des tables de la base de données :',
	'config-db-prefix-help' => "Si vous avez besoin de partager une base de données entre plusieurs wikis, ou entre MediaWiki et une autre application Web, vous pouvez choisir d'ajouter un préfixe à tous les noms de table pour éviter les conflits. 
Ne pas utiliser des espaces. 

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
	'config-db-schema-help' => "Les schémas ci-dessus sont généralement corrects.
Ne les changez que si vous êtes sûr que c'est nécessaire.",
	'config-sqlite-dir' => 'Dossier des données SQLite :',
	'config-sqlite-dir-help' => "SQLite stocke toutes les données dans un fichier unique. 

Le répertoire que vous inscrivez doit être accessible en écriture par le serveur lors de l'installation. 

Il '''ne faut pas''' qu'il soit accessible via le web, c'est pourquoi il n'est pas à l'endroit où vos fichiers PHP sont. 

L'installateur écrira un fichier <code>.htaccess</code> en même temps, mais s'il y a échec, quelqu'un peut accéder à votre base de données.
Cela comprend les données des utilisateurs (adresses de courriel, mots de passe hachés) ainsi que des révisions supprimées et d'autres données confidentielles du wiki.

Envisagez de placer la base de données ailleurs, par exemple dans <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => "Espace de stockage (''tablespace'') par défaut :",
	'config-oracle-temp-ts' => "Espace de stockage (''tablespace'') temporaire :",
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
	'config-support-oracle' => '* $1 est un système commercial de gestion de base de données d’entreprise. ([Http://www.php.net/manual/en/oci8.installation.php Comment compiler PHP avec le support OCI8])',
	'config-header-mysql' => 'Paramètres de MySQL',
	'config-header-postgres' => 'Paramètres de PostgreSQL',
	'config-header-sqlite' => 'Paramètres de SQLite',
	'config-header-oracle' => 'Paramètres d’Oracle',
	'config-invalid-db-type' => 'Type de base de données non valide',
	'config-missing-db-name' => 'Vous devez saisir une valeur pour « Nom de la base de données »',
	'config-missing-db-host' => "Vous devez entrer une valeur pour « l'hôte de la base de données »",
	'config-missing-db-server-oracle' => 'Vous devez saisir une valeur pour le « Nom TNS de la base de données »',
	'config-invalid-db-server-oracle' => 'Le nom TNS de la base de données (« $1 ») est invalide.
Il ne peut contenir que des lettres latines de base (a-z, A-Z), des chiffres (0-9), des caractères de soulignement (_) et des points (.).',
	'config-invalid-db-name' => 'Nom de la base de données invalide (« $1 »).
Il ne peut contenir que des lettres latines (a-z, A-Z), des chiffres (0-9), des caractères de soulignement (_) et des tirets (-).',
	'config-invalid-db-prefix' => 'Préfixe de la base de données non valide « $1 ». 
Il ne peut contenir que des lettres latines (a-z, A-Z), des chiffres (0-9), des caractères de soulignement (_) et des tirets (-).',
	'config-connection-error' => '$1.

Vérifier le nom d’hôte, le nom d’utilisateur et le mot de passe ci-dessous puis réessayer.',
	'config-invalid-schema' => 'Schéma invalide pour MediaWiki « $1 ». 
Utilisez seulement des lettres latines (a-z, A-Z), des chiffres (0-9) et des caractères de soulignement (_).',
	'config-db-sys-create-oracle' => "L'installateur ne reconnaît que les compte SYSDBA lors de la création d'un nouveau compte.",
	'config-db-sys-user-exists-oracle' => 'Le compte « $1 » existe déjà. Un SYSDBA peut seulement servir à créer un nouveau comtpe.',
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
	'config-can-upgrade' => "Il y a des tables MediaWiki dans cette base de données. 
Pour les mettre au niveau de MediaWiki $1, cliquez sur '''Continuer'''.",
	'config-upgrade-done' => "Mise à jour complétée. 

Vous pouvez maintenant [$1 commencer à utiliser votre wiki]. 

Si vous souhaitez régénérer votre fichier <code>LocalSettings.php</code>, cliquez sur le bouton ci-dessous. 
Ce '''n'est pas recommandé''' sauf si vous rencontrez des problèmes avec votre wiki.",
	'config-upgrade-done-no-regenerate' => 'Mise à jour terminée.

Vous pouvez maintenant [$1 commencer à utiliser votre wiki].',
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
	'config-mysql-charset' => 'Jeu de caractères de la base de données :',
	'config-mysql-binary' => 'Binaire',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "En ''mode binaire'', MediaWiki stocke le texte au format UTF-8 dans la base de données. C'est plus efficace que le ''UTF-8 mode'' de MySQL, et vous permet d'utiliser toute la gamme des caractères Unicode. 

En ''mode binaire'', MediaWiki stocke le texte UTF-8 dans des champs binaires de la base de données. C'est plus efficace que le ''mode UTF-8'' de MySQL, et vous permet d'utiliser toute la gamme des caractères Unicode. 
En ''mode UTF-8'', MySQL connaîtra le jeu de caractères de vos données et pourra présenter et convertir les données de manière appropriée, mais il ne vous laissera pas stocker les caractères au-dessus du [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingue de base] (en anglais).",
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
	'config-ns-conflict' => "L'espace de noms spécifié « <nowiki>$1</nowiki> » est en conflit avec un espace de noms par défaut de MediaWiki. 
Choisir un autre espace de noms.",
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
	'config-admin-email-help' => "Entrez une adresse de courriel ici pour vous permettre de recevoir des courriels d'autres utilisateurs du wiki, réinitialiser votre mot de passe, et être informé des modifications apportées aux pages de votre liste de suivi. Vous pouvez laisser ce champ vide.",
	'config-admin-error-user' => "Erreur interne lors de la création d'un administrateur avec le nom « <nowiki>$1</nowiki> ».",
	'config-admin-error-password' => "Erreur interne lors de l'inscription d'un mot de passe pour l'administrateur « <nowiki>$1</nowiki> » : <pre>$2</pre>",
	'config-admin-error-bademail' => 'Vous avez entré une adresse de courriel invalide',
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
	'config-license-cc-by-sa' => "Creative Commons attribution partage à l'identique",
	'config-license-cc-by-nc-sa' => "Creative Commons attribution non commercial partage à l'identique",
	'config-license-cc-0' => 'Creative Commons Zero',
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
Pour se faire, il faut que MediaWiki accède à Internet. 

Pour plus d'informations sur ce service, y compris les instructions sur la façon de le configurer pour d'autres wikis que Wikimedia Commons, consultez le [http://mediawiki.org/wiki/Manual:\$wgForeignFileRepos manuel] (en anglais).",
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
Elles doivent être séparés par des virgules et vous devez spécifier le port à utiliser. Par exemple : 
  127.0.0.1:11211 
  192.168.1.25:1234',
	'config-memcache-needservers' => 'Vous avez sélectionné Memcached comme type de cache, mais ne précisez pas de serveur.',
	'config-memcache-badip' => 'Vous avez entré une adresse IP invalide pour Memcached: $1.',
	'config-memcache-noport' => "Vous n'avez pas entré un port pour le serveur Memcached : $1. 
Si vous ne le connaissez pas, la valeur par défaut est 11211.",
	'config-memcache-badport' => 'Les numéros de port de Memcached sont situés entre $1 et $2.',
	'config-extensions' => 'Extensions',
	'config-extensions-help' => 'Les extensions énumérées ci-dessus ont été détectées dans votre répertoire <code>./extensions</code>. 

Elles peuvent nécessiter une configuration supplémentaire, mais vous pouvez les activer maintenant',
	'config-install-alreadydone' => "'''Attention''': Vous semblez avoir déjà installé MediaWiki et tentez de l'installer à nouveau. 
S'il vous plaît, allez à la page suivante.",
	'config-install-begin' => "En appuyant sur {{int:config-continue}}, vous commencerez l'installation de MediaWiki.
Si vous voulez apporter des modifications, appuyez sur Retour.",
	'config-install-step-done' => 'fait',
	'config-install-step-failed' => 'échec',
	'config-install-extensions' => 'Inclusion des extensions',
	'config-install-database' => 'Création de la base de données',
	'config-install-pg-schema-not-exist' => "Le schéma PostgreSQL n'existe pas",
	'config-install-pg-schema-failed' => "Échec lors de la création des tables. 
Assurez-vous que l'utilisateur « $1 » peut écrire selon le schéma « $2 ».",
	'config-install-pg-commit' => 'Validation des modifications',
	'config-install-pg-plpgsql' => 'Vérification du language PL/pgSQL',
	'config-pg-no-plpgsql' => 'Vous devez installer le langage PL/pgSQL dans la base de données $1',
	'config-pg-no-create-privs' => "Le compte que vous avez spécifié pour l'installation n'a pas suffisamment de privilèges pour créer un compte.",
	'config-install-user' => "Création d'un utilisateur de la base de données",
	'config-install-user-alreadyexists' => "L'utilisateur « $1 » existe déjà.",
	'config-install-user-create-failed' => "Échec lors de la création de l'utilisateur « $1 » : $2",
	'config-install-user-grant-failed' => "Échec lors de l'ajout de permissions à l'utilisateur « $1 » : $2",
	'config-install-tables' => 'Création des tables',
	'config-install-tables-exist' => "'''Avertissement:''' Les tables MediaWiki semblent déjà exister. 
Création omise.",
	'config-install-tables-failed' => "'''Erreur:''' échec lors de la création de la table avec l'erreur suivante: $1",
	'config-install-interwiki' => 'Remplissage par défaut de la table des interwikis',
	'config-install-interwiki-list' => 'Impossible de trouver le fichier <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Attention:''' La table des interwikis semble déjà contenir des entrées. 
La liste par défaut ne sera pas inscrite.",
	'config-install-stats' => 'Initialisation des statistiques',
	'config-install-keys' => 'Génération de la clé secrète',
	'config-install-sysop' => 'Création du compte administrateur',
	'config-install-subscribe-fail' => "Impossible de s'abonner à mediawiki-announce",
	'config-install-mainpage' => 'Création de la page principale avec un contenu par défaut',
	'config-install-extension-tables' => 'Création de tables pour les extensions activées',
	'config-install-mainpage-failed' => 'Impossible d’insérer la page principale: $1',
	'config-install-done' => "'''Félicitations!''' 
Vous avez réussi à installer MediaWiki. 

Le programme d'installation a généré <code>LocalSettings.php</code>, un fichier qui contient tous les paramètres de configuration.

Si le téléchargement n'a pas été offert, ou que vous l'avez annulé, vous pouvez démarrer à nouveau le téléchargement en cliquant ce lien :

$3 

'''Note''': Si vous ne le faites pas maintenant, ce fichier de configuration généré ne sera pas disponible plus tard si vous quittez l'installation sans le télécharger. 

Lorsque c'est fait, vous pouvez '''[$2 accéder à votre wiki]'''.",
	'config-download-localsettings' => 'Télécharger LocalSettings.php',
	'config-help' => 'aide',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'config-desc' => 'O programa de instalación de MediaWiki',
	'config-title' => 'Instalación de MediaWiki $1',
	'config-information' => 'Información',
	'config-localsettings-upgrade' => 'Detectouse un ficheiro <code>LocalSettings.php</code>.
Para actualizar esta instalación, introduza o valor de <code>$wgUpgradeKey</code> na caixa.
Pode atopalo en LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Detectouse un ficheiro LocalSettings.php.
Para actualizar esta instalación, execute update.php',
	'config-localsettings-key' => 'Clave de actualización:',
	'config-localsettings-badkey' => 'A clave dada é incorrecta',
	'config-upgrade-key-missing' => 'Detectouse unha instalación existente de MediaWiki.
Para actualizar esta instalación, inclúa esta liña ao final do ficheiro LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Semella que o ficheiro LocalSettings.php existente está incompleto.
A variable $1 non está establecida.
Modifique o ficheiro LocalSettings.php de xeito que a variable quede establecida e prema en "Continuar".',
	'config-localsettings-connection-error' => 'Atopouse un erro ao conectar coa base de datos empregando a configuración especificada no ficheiro LocalSettings.php ou no ficheiro AdminSettings.php. Corrixa esta configuración e inténteo de novo. 

$1',
	'config-session-error' => 'Erro ao iniciar a sesión: $1',
	'config-session-expired' => 'Semella que os seus datos da sesión caducaron.
As sesións están configuradas para unha duración de $1.
Pode incrementar isto fixando <code>session.gc_maxlifetime</code> en php.ini.
Reinicie o proceso de instalación.',
	'config-no-session' => 'Perdéronse os datos da súa sesión!
Comprobe o seu php.ini e asegúrese de que en <code>session.save_path</code> está definido un directorio correcto.',
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
	'config-page-existingwiki' => 'Wiki existente',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas máis frecuentes]
----
* <doclink href=Readme>Léame</doclink>
* <doclink href=ReleaseNotes>Notas de lanzamento</doclink>
* <doclink href=Copying>Copia</doclink>
* <doclink href=UpgradeDoc>Actualización</doclink>',
	'config-env-good' => 'Rematou a comprobación do entorno.
Pode instalar MediaWiki.',
	'config-env-bad' => 'Rematou a comprobación do entorno.
Non pode instalar MediaWiki.',
	'config-env-php' => 'Está instalado o PHP $1.',
	'config-env-php-toolow' => 'Está instalado o PHP $1.
Porén, MediaWiki necesita o PHP $2 ou superior.',
	'config-unicode-using-utf8' => 'Usando utf8_normalize.so de Brion Vibber para a normalización Unicode.',
	'config-unicode-using-intl' => 'Usando a [http://pecl.php.net/intl extensión intl PECL] para a normalización Unicode.',
	'config-unicode-pure-php-warning' => "'''Atención:''' A [http://pecl.php.net/intl extensión intl PECL] non está dispoñible para manexar a normalización Unicode; volvendo á implementación lenta de PHP puro.
Se o seu sitio posúe un alto tráfico de visitantes, debería ler un chisco sobre a [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalización Unicode].",
	'config-unicode-update-warning' => "'''Atención:''' A versión instalada da envoltura de normalización Unicode emprega unha versión vella da biblioteca [http://site.icu-project.org/ do proxecto ICU].
Debería [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualizar] se o uso de Unicode é importante para vostede.",
	'config-no-db' => 'Non se puido atopar un controlador axeitado para a base de datos!',
	'config-no-db-help' => 'Debe instalar un controlador de base de datos para PHP.
Os tipos de base de datos soportados son os seguintes: $1.

Se está nun aloxamento compartido, pregunte ao seu provedor de hospedaxe para instalar un controlador de base de datos axeitado.
Se compilou o PHP vostede mesmo, reconfigúreo activando un cliente de base de datos, por exemplo, usando <code>./configure --with-mysql</code>.
Se instalou o PHP desde un paquete Debian ou Ubuntu, entón tamén necesita instalar o módulo php5-mysql.',
	'config-no-fts3' => "'''Atención:''' O SQLite está compilado sen o [http://sqlite.org/fts3.html módulo FTS3]; as características de procura non estarán dispoñibles nesta instalación.",
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
	'config-xml-bad' => 'Falta o módulo XML do PHP.
MediaWiki necesita funcións neste módulo e non funcionará con esta configuración.
Se está executando o Mandrake, instale o paquete php-xml.',
	'config-pcre' => 'Semella que falta o módulo de soporte PCRE.
MediaWiki necesita que funcionen as expresións regulares compatibles co Perl.',
	'config-pcre-no-utf8' => "'''Erro fatal:''' Semella que o módulo PCRE do PHP foi compilado sen o soporte PCRE_UTF8.
MediaWiki necesita soporte UTF-8 para funcionar correctamente.",
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
	'config-diff3-bad' => 'GNU diff3 non se atopou.',
	'config-imagemagick' => 'ImageMagick atopado: <code>$1</code>.
As miniaturas de imaxes estarán dispoñibles se activa as cargas.',
	'config-gd' => 'Atopouse a biblioteca gráfica GD integrada.
As miniaturas de imaxes estarán dispoñibles se activa as cargas.',
	'config-no-scaling' => 'Non se puido atopar a biblioteca GD ou ImageMagick.
As miniaturas de imaxes estarán desactivadas.',
	'config-no-uri' => "'''Erro:''' Non se puido determinar o URI actual.
Instalación abortada.",
	'config-uploads-not-safe' => "'''Atención:''' O seu directorio por defecto para as cargas, <code>$1</code>, é vulnerable a execucións arbitrarias de escrituras.
Aínda que MediaWiki comproba todos os ficheiros cargados por se houbese ameazas de seguridade, é amplamente recomendable [http://www.mediawiki.org/wiki/Manual:Security#Upload_security pechar esta vulnerabilidade de seguridade] antes de activar as cargas.",
	'config-brokenlibxml' => 'O seu sistema ten unha combinación de versións de PHP e libxml2 que pode ser problemático e causar corrupción de datos en MediaWiki e outras aplicacións web.
Actualice o sistema á versión 5.2.9 ou posterior do PHP e á 2.7.3 ou posterior de libxml2 ([http://bugs.php.net/bug.php?id=45996 erro presentado co PHP]).
Instalación abortada.',
	'config-using531' => 'O PHP $1 non é compatible con MediaWiki debido a un erro que afecta aos parámetros de referencia de <code>__call()</code>.
Actualice o sistema á versión 5.3.2 ou posterior do PHP ou volva á versión 5.3.0 do PHP para arranxar o problema.
Instalación abortada.',
	'config-db-type' => 'Tipo de base de datos:',
	'config-db-host' => 'Servidor da base de datos:',
	'config-db-host-help' => 'Se o servidor da súa base de datos está nun servidor diferente, escriba o nome do servidor ou o enderezo IP aquí.

Se está usando un aloxamento web compartido, o seu provedor de hospedaxe debe darlle o nome de servidor correcto na súa documentación.

Se está a realizar a instalación nun servidor de Windows con MySQL, o nome "localhost" pode non valer como servidor. Se non funcionase, inténteo con "127.0.0.1" como enderezo IP local.',
	'config-db-host-oracle' => 'TNS da base de datos:',
	'config-db-host-oracle-help' => 'Insira un [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm nome de conexión local] válido; cómpre que haxa visible un ficheiro tnsnames.ora para esta instalación.<br />Se está a empregar bibliotecas cliente versión 10g ou máis recentes, tamén pode usar o método de atribución de nomes [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identificar o wiki',
	'config-db-name' => 'Nome da base de datos:',
	'config-db-name-help' => 'Escolla un nome que identifique o seu wiki.
Non debe conter espazos.

Se está usando un aloxamento web compartido, o seu provedor de hospedaxe daralle un nome específico para a base de datos ou deixaralle crear unha a través do panel de control.',
	'config-db-name-oracle' => 'Esquema da base de datos:',
	'config-db-account-oracle-warn' => 'Existen tres escenarios soportados para a instalación de Oracle como fin da base de datos:

Se quere crear unha conta para a base de datos como parte do proceso de instalación, proporcione unha conta co papel SYSDBA e especifique as credenciais desexadas para a conta; senón pode crear a conta manualmente e dar só esa conta (se ten os permisos necesarios para crear os obxectos do esquema) ou fornecer dous contas diferentes, unha con privilexios de creación e outra restrinxida para o acceso á web.

A escritura para crear unha conta cos privilexios necesarios atópase no directorio "maintenance/oracle/" desta instalación. Teña en conta que o emprego de contas restrinxidas desactivará todas as operacións de mantemento da conta predeterminada.',
	'config-db-install-account' => 'Conta de usuario para a instalación',
	'config-db-username' => 'Nome de usuario da base de datos:',
	'config-db-password' => 'Contrasinal da base de datos:',
	'config-db-password-empty' => 'Introduza un contrasinal para o novo usuario da base de datos: $1.
Malia que é posible crear usuarios sen contrasinal, esta práctica non é segura.',
	'config-db-install-username' => 'Escriba o nome de usuario que empregará para conectarse á base de datos durante o proceso de instalación. Este non é o nome de usuario da conta de MediaWiki, trátase do nome de usuario para a súa base de datos.',
	'config-db-install-password' => 'Escriba o contrasinal que empregará para conectarse á base de datos durante o proceso de instalación. Este non é o contrasinal da conta de MediaWiki, trátase do contrasinal para a súa base de datos.',
	'config-db-install-help' => 'Introduza o nome de usuario e contrasinal que se usará para conectar á base de datos durante o proceso de instalación.',
	'config-db-account-lock' => 'Use o mesmo nome de usuario e contrasinal despois do proceso de instalación',
	'config-db-wiki-account' => 'Conta de usuario para despois do proceso de instalación',
	'config-db-wiki-help' => 'Introduza o nome de usuario e mais o contrasinal que se usarán para conectar á base de datos durante o funcionamento habitual do wiki.
Se a conta non existe e a conta de instalación ten privilexios suficientes, esa conta de usuario será creada cos privilexios mínimos necesarios para o funcionamento do wiki.',
	'config-db-prefix' => 'Prefixo das táboas da base de datos:',
	'config-db-prefix-help' => 'Se necesita compartir unha base de datos entre varios wikis ou entre MediaWiki e outra aplicación web, pode optar por engadir un prefixo a todos os nomes da táboa para evitar conflitos.
Non utilice espazos.

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
	'config-db-schema-help' => 'O normal é que este esquema sexa correcto.
Cámbieo soamente se sabe que é necesario.',
	'config-sqlite-dir' => 'Directorio de datos SQLite:',
	'config-sqlite-dir-help' => "SQLite recolle todos os datos nun ficheiro único.

O servidor web debe ter permisos sobre o directorio para que poida escribir nel durante a instalación.

Ademais, o servidor '''non''' debe ser accesible a través da web, motivo polo que non está no mesmo lugar ca os ficheiros PHP.

Asemade, o programa de instalación escribirá un ficheiro <code>.htaccess</code>, pero se erra alguén pode obter acceso á súa base de datos.
Isto inclúe datos de usuario (enderezos de correo electrónico, contrasinais codificados), así como revisións borradas e outros datos restrinxidos no wiki.

Considere poñer a base de datos nun só lugar, por exemplo en <code>/var/lib/mediawiki/oseuwiki</code>.",
	'config-oracle-def-ts' => 'Espazo de táboas por defecto:',
	'config-oracle-temp-ts' => 'Espazo de táboas temporal:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki soporta os seguintes sistemas de bases de datos:

$1

Se non ve listado a continuación o sistema de base de datos que intenta usar, siga as instrucións ligadas enriba para activar o soporte.',
	'config-support-mysql' => '* $1 é o obxectivo principal para MediaWiki e está mellor soportado ([http://www.php.net/manual/en/mysql.installation.php como compilar o PHP con soporte MySQL])',
	'config-support-postgres' => '* $1 é un sistema de base de datos popular e de código aberto como alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php como compilar o PHP con soporte PostgreSQL]). É posible que haxa algúns pequenos erros e non se recomenda o seu uso nun entorno de produción.',
	'config-support-sqlite' => '* $1 é un sistema de base de datos lixeiro moi ben soportado. ([http://www.php.net/manual/en/pdo.installation.php Como compilar o PHP con soporte SQLite], emprega PDO)',
	'config-support-oracle' => '* $1 é un sistema comercial de xestión de base de datos de empresa. ([http://www.php.net/manual/en/oci8.installation.php Como compilar PHP con soporte OCI8])',
	'config-header-mysql' => 'Configuración do MySQL',
	'config-header-postgres' => 'Configuración do PostgreSQL',
	'config-header-sqlite' => 'Configuración do SQLite',
	'config-header-oracle' => 'Configuración do Oracle',
	'config-invalid-db-type' => 'Tipo de base de datos incorrecto',
	'config-missing-db-name' => 'Debe escribir un valor "Nome da base de datos"',
	'config-missing-db-host' => 'Debe escribir un valor "Servidor da base de datos"',
	'config-missing-db-server-oracle' => 'Debe escribir un valor "TNS da base de datos"',
	'config-invalid-db-server-oracle' => 'O TNS da base de datos, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9), guións baixos (_) e puntos (.).',
	'config-invalid-db-name' => 'O nome da base de datos, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9), guións baixos (_) e guións (-).',
	'config-invalid-db-prefix' => 'O prefixo da base de datos, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9), guións baixos (_) e guións (-).',
	'config-connection-error' => '$1.

Comprobe o servidor, nome de usuario e contrasinal que hai a continuación e inténteo de novo.',
	'config-invalid-schema' => 'O esquema de MediaWiki, "$1", é incorrecto.
Só pode conter letras ASCII (a-z, A-Z), números (0-9) e guións baixos (_).',
	'config-db-sys-create-oracle' => 'O programa de instalación soamente soporta o emprego de contas SYSDBA como método para crear unha nova conta.',
	'config-db-sys-user-exists-oracle' => 'A conta de usuario "$1" xa existe. SYSDBA soamente se pode empregar para a creación dunha nova conta!',
	'config-postgres-old' => 'Necesítase PostgreSQL $1 ou posterior; ten a versión $2.',
	'config-sqlite-name-help' => 'Escolla un nome que identifique o seu wiki.
Non utilice espazos ou guións.
Este nome será utilizado para o ficheiro de datos SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Non se puido crear o directorio de datos <code><nowiki>$1</nowiki></code>, porque o servidor web non pode escribir no directorio pai <code><nowiki>$2</nowiki></code>.

O programa de instalación determinou o usuario que executa o seu servidor web.
Para continuar, faga que se poida escribir no directorio <code><nowiki>$3</nowiki></code>.
Nun sistema Unix/Linux cómpre realizar:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Non se puido crear o directorio de datos <code><nowiki>$1</nowiki></code>, porque o servidor web non pode escribir no directorio pai <code><nowiki>$2</nowiki></code>.

O programa de instalación non puido determinar o usuario que executa o seu servidor web.
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
	'config-can-upgrade' => "Existen táboas MediaWiki nesta base de datos.
Para actualizalas a MediaWiki \$1, prema sobre \"'''Continuar'''\".",
	'config-upgrade-done' => "Actualización completada.

Agora pode [$1 comezar a utilizar o seu wiki].

Se quere rexenerar o seu ficheiro <code>LocalSettings.php</code>, prema no botón que aparece a continuación.
Isto '''non é recomendable''' a menos que estea a ter problemas co seu wiki.",
	'config-upgrade-done-no-regenerate' => 'Actualización completada.

Xa pode [$1 comezar a usar o seu wiki].',
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
	'config-mysql-charset' => 'Conxunto de caracteres da base de datos:',
	'config-mysql-binary' => 'Binario',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "No '''modo binario''', MediaWiki almacena texto UTF-8 na base de datos en campos binarios.
Isto é máis eficaz ca o modo UTF-8 de MySQL e permítelle usar o rango completo de caracteres Unicode.

No '''modo UTF-8''', MySQL saberá o xogo de caracteres dos seus datos e pode presentar e converter os datos de maneira axeitada,
pero non lle deixará gardar caracteres por riba do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes plan multilingüe básico].",
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
	'config-ns-conflict' => 'O espazo de nomes especificado, "<nowiki>$1</nowiki>", entra en conflito co espazo de nomes MediaWiki por defecto.
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
	'config-admin-email-help' => 'Escriba aquí un enderezo de correo electrónico para que poida recibir mensaxes doutros usuarios a través do wiki, restablecer o contrasinal e ser notificado das modificacións feitas nas páxinas presentes na súa lista de vixilancia. Pode deixar este campo en branco.',
	'config-admin-error-user' => 'Erro interno ao crear un administrador co nome "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Erro interno ao establecer un contrasinal para o administrador "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Escribiu un enderezo de correo electrónico non válido.',
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
	'config-license-cc-by-sa' => 'Creative Commons recoñecemento compartir igual',
	'config-license-cc-by-nc-sa' => 'Creative Commons recoñecemento non comercial compartir igual',
	'config-license-cc-0' => 'Creative Commons Zero',
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
Para facer isto, MediaWiki necesita acceso á internet. 

Para obter máis información sobre esta característica, incluíndo as instrucións sobre como configuralo para outros wikis que non sexan a Wikimedia Commons, consulte [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos o manual].',
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
Debe especificarse un por liña, así como o porto a usar. Por exemplo:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Seleccionou Memcached como o seu tipo de caché, pero non especificou ningún servidor.',
	'config-memcache-badip' => 'Escribiu un enderezo IP inválido para Memcached: $1.',
	'config-memcache-noport' => 'Non especificou o porto a usar no servidor Memcached: $1. 
Se non sabe o porto, o predeterminado é 11211.',
	'config-memcache-badport' => 'Os números de porto Memcached deben estar entre $1 e $2.',
	'config-extensions' => 'Extensións',
	'config-extensions-help' => 'As extensións anteriores detectáronse no seu directorio <code>./extensions</code>.

Quizais necesite algunha configuración adicional, pero pode activalas agora',
	'config-install-alreadydone' => "'''Atención:''' Semella que xa instalou MediaWiki e que o está a instalar de novo.
Vaia ata a seguinte páxina.",
	'config-install-begin' => 'Ao premer en "{{int:config-continue}}", comezará a instalación de MediaWiki.
Se aínda quere facer algún cambio, volva atrás.',
	'config-install-step-done' => 'feito',
	'config-install-step-failed' => 'erro',
	'config-install-extensions' => 'Incluíndo as extensións',
	'config-install-database' => 'Configurando a base de datos',
	'config-install-pg-schema-not-exist' => 'O esquema PostgreSQL non existe.',
	'config-install-pg-schema-failed' => 'Fallou a creación de táboas.
Asegúrese de que o usuario "$1" pode escribir no esquema "$2".',
	'config-install-pg-commit' => 'Validando os cambios',
	'config-install-pg-plpgsql' => 'Comprobación da lingua PL/pgSQL',
	'config-pg-no-plpgsql' => 'Cómpre instalar a lingua PL/pgSQL na base de datos $1',
	'config-pg-no-create-privs' => 'A conta especificada para a instalación non ten os privilexios necesarios para crear unha conta.',
	'config-install-user' => 'Creando o usuario da base de datos',
	'config-install-user-alreadyexists' => 'O usuario "$1" xa existe',
	'config-install-user-create-failed' => 'A creación do usuario "$1" fallou: $2',
	'config-install-user-grant-failed' => 'Fallou a concesión de permisos ao usuario "$1": $2',
	'config-install-tables' => 'Creando as táboas',
	'config-install-tables-exist' => "'''Atención:''' Semella que as táboas de MediaWiki xa existen.
Saltando a creación.",
	'config-install-tables-failed' => "'''Erro:''' Fallou a creación da táboa. Descrición do erro: $1",
	'config-install-interwiki' => 'Enchendo a táboa de interwiki por defecto',
	'config-install-interwiki-list' => 'Non se puido atopar o ficheiro <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Atención:''' Semella que a táboa de interwiki xa contén entradas.
Saltando a lista por defecto.",
	'config-install-stats' => 'Iniciando as estatísticas',
	'config-install-keys' => 'Xerando a clave secreta',
	'config-install-sysop' => 'Creando a conta de usuario de administrador',
	'config-install-subscribe-fail' => 'Non se puido subscribir á lista mediawiki-announce',
	'config-install-mainpage' => 'Creando a páxina principal co contido por defecto',
	'config-install-extension-tables' => 'Creando as táboas para as extensións activadas',
	'config-install-mainpage-failed' => 'Non se puido inserir a páxina principal: $1',
	'config-install-done' => "'''Parabéns!'''
Instalou correctamente MediaWiki.

O programa de instalación xerou un ficheiro <code>LocalSettings.php</code>.
Este ficheiro contén toda a súa configuración.

Terá que descargalo e poñelo na base da instalación do seu wiki (no mesmo directorio ca index.php). A descarga debería comezar automaticamente.

Se non comezou a descarga ou se a cancelou, pode facer que comece de novo premendo na ligazón que aparece a continuación:

$3

'''Nota:''' Se non fai iso agora, este ficheiro de configuración xerado non estará dispoñible máis adiante se sae da instalación sen descargalo.

Cando faga todo isto, xa poderá '''[$2 entrar no seu wiki]'''.",
	'config-download-localsettings' => 'Descargar o LocalSettings.php',
	'config-help' => 'axuda',
);

/** Swiss German (Alemannisch)
 * @author Als-Holder
 */
$messages['gsw'] = array(
	'config-desc' => 'S MediaWiki-Inschtallationsprogramm',
	'config-title' => 'MediaWiki $1 inschtalliere',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => "'''Warnig:''' E Datei <code>LocalSettings.php</code> isch gfunde wore.
Fir d Aktualisierig vu dr däre Inschtallation, gib bitte dr Wärt vum Parameter <code>\$wgUpgradeKey</code> im Fäld unten yy.
Du findsch dr Wärt in dr Datei LocalSettings.php.",
	'config-localsettings-key' => 'Aktualisierigsschlissel:',
	'config-localsettings-badkey' => 'Dr Aktualisierigsschlissel, wu du aagee hesch, isch falsch.',
	'config-session-error' => 'Fähler bim Starte vu dr Sitzig: $1',
	'config-session-expired' => 'D Sitzigsdate sin schyns abgloffe.
Sitzige sin fir e Zytruum vu $1 konfiguriert.
Dää cha dur Aalupfe vum Parameter <code>session.gc_maxlifetime</code> in dr Datei <code>php.ini</code> greßer gmacht wäre.
Dr Inschtallationsvorgang nomol starte.',
	'config-no-session' => 'Dyyni Sitzigsdate sin verlore gange!
D Datei <code>php.ini</code> mueß prieft wäre un s mueß derby sichergstellt wäre, ass dr Parameter <code>session.save_path</code> uf s richtig Verzeichnis verwyyst.',
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
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Websyte vu MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nutzeraaleitig zue MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:Contents Adminischtratoreaaleitig zue MediaWiki]
* [http://www.mediawiki.org/wiki/Manual:FAQ Vilmol gstellti Froge zue MediaWiki]',
	'config-env-good' => 'D Inschtallationsumgäbig isch prieft wore.
Du chasch MediaWiki inschtalliere.',
	'config-env-bad' => 'D Inschtallationsumgäbigisch prieft wore.
Du chasch MediaWiki nit inschtalliere.',
	'config-env-php' => 'PHP $1 isch inschtalliert.',
	'config-unicode-using-utf8' => 'Fir d Unicode-Normalisierig wird em Brion Vibber syy utf8_normalize.so yygsetzt.',
	'config-unicode-using-intl' => 'For d Unicode-Normalisierig wird d [http://pecl.php.net/intl PECL-Erwyterig intl] yygsetzt.',
	'config-unicode-pure-php-warning' => "'''Warnig:''' D [http://pecl.php.net/intl PECL-Erwyterig intl] isch fir d Unicode-Normalisierig nit verfiegbar. Wäge däm wird di langsam pure-PHP-Implementierig brucht.
Wänn Du ne Websyte mit ere große Bsuechrzahl bedrybsch, sottsch e weng ebis läse iber [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-Normalisierig (en)].",
	'config-unicode-update-warning' => "'''Warnig:''' Di inschtalliert Version vum Unicode-Normalisierigswrapper verwändet e elteri Version vu dr Bibliothek vum [http://site.icu-project.org/ ICU-Projäkt].
Du sottsch si [http://www.mediawiki.org/wiki/Unicode_normalization_considerations aktualisiere], wänn Dor d Verwändig vu Unicode wichtig isch.",
	'config-no-db' => 'S isch kei adäquate Datebanktryyber gfunde wore!',
	'config-no-db-help' => 'S mueß e Datebanktryyber fir PHP inschtalliert wäre.
Die Datebanksyschtem wäre unterstitzt: $1

Wänn Du ne gmeinschaftli gnutzte Server fir s Hosting bruchsch, muesch dr Hoster froge go ne adäquate Datebanktryyber inschtalliere.
Wänn Du PHP sälber kumpiliert hesch, muesch s nej konfiguriere, dr Datebankclient mueß aktiviert wäre. Doderzue chasch zem Byschpel <code>./configure --with-mysql</code> uusfiere.
Wänn Du PHP iber d Paketverwaltig vun ere Debian- oder Ubuntu-Inschtallation inschtalliert hesch, muesch s „php5-mysql“-Paket nooinschtalliere.',
	'config-no-fts3' => "'''Warnig:''' SQLite isch ohni s [http://sqlite.org/fts3.html FTS3-Modul] kumpiliert wore, s stehn kei Suechfunktione z Verfiegig.",
	'config-register-globals' => "'''Warnig: Dr Parameter <code>[http://php.net/register_globals register_globals]</code> vu PHP isch aktiviert.'''
'''Är sott deaktiviert wäre, wänn des megli isch.'''
D MediaWiki-Inschtallation lauft einwäg, aber dr Server isch aafällig fi megligi Sicherheitsprobläm.",
	'config-magic-quotes-runtime' => "'''Fatal: Dr Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]</code> vu PHP isch aktiviert!'''
Die Yystellig fiert zue nit vorhärsähbare Probläm bi dr Datenyygab.
MediaWiki cha nit inschtalliert wäre, solang dää Parameter nit deaktiviert woren isch.",
	'config-magic-quotes-sybase' => "'''Fatal: Dr Parameter <code>[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]</code> vu PHP isch aktiviert!'''
Die Yystellig fiert zue nit vorhärsähbare Probläm bi dr Datenyygab.
MediaWiki cha nit inschtalliert wäre, solang dää Parameter nit deaktiviert woren isch.",
	'config-mbstring' => "'''Fatal: Dr Parameter <code>[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]</code> vu PHP isch aktiviert!'''
Die Yystellig verursacht Fähler un fiert zue nit vorhärsähbare Probläm bi dr Datenyygab.
MediaWiki cha nit inschtalliert wäre, solang dää Parameter nit deaktiviert woren isch.",
	'config-ze1' => "'''Fatal: Dr Parameter <code>[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]</code> vu PHP isch aktiviert!'''
Die Yystellig fiert zue große Fähler bi MediaWiki.
MediaWiki cha nit inschtalliert wäre, solang dää Parameter nit deaktiviert woren isch.",
	'config-safe-mode' => "'''Warnig:''' D Funktion <code>[http://www.php.net/features.safe-mode Safe Mode]</code> vu PHP isch aktiviert.
Des cha zue Probläm fiere, vor allem wänn s Uffelade vu Dateie soll megli syy bzw. dr Uuszeichner <code>math</code> soll brucht wäre.",
	'config-xml-bad' => 'S XML-Modul vu PHP fählt.
MediaWiki brucht Funktione, wu au des Modul z Verfiegig stellt, un funktioniert in däre Konfiguration nit.
Wänn Mandriva brucht wird, mueß no s „php-xml“-Paket inschtalliert wäre.',
	'config-pcre' => 'S PHP-Modul fir d PCRE-Unterstitzig isch nit gfunde wore.
MediaWiki brucht aber perl-kompatibli reguläri Uusdruck zum lauffähig syy.',
	'config-pcre-no-utf8' => "'''Fatale Fähler: S PHP-Modul PCRE isch schyns ohni PCRE_UTF8-Unterstitzig kompiliert wore.'''
MediaWiki brucht d UTF-8-Unterstitzi zum fählerfrej lauffähig syy.",
	'config-memory-raised' => 'Dr PHP-Parameter <code>memory_limit</code> lyt bi $1 un isch uf $2 uffegsetzt wore.',
	'config-memory-bad' => "'''Warnig:''' Dr PHP-Parameter <code>memory_limit</code> lyt bi $1.
Dää Wärt isch wahrschyns z nider.
Dr Inschtallationsvorgang chennt wäge däm fählschlaa!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] isch inschtalliert',
	'config-apc' => '[http://www.php.net/apc APC] isch inschtalliert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] isch inschtalliert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] isch inschtalliert',
	'config-no-cache' => "'''Warnig:''' [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] oder [http://www.iis.net/download/WinCacheForPhp WinCache] hän nit chenne gfunde wäre.
S Objäktcaching isch wäge däm nit aktiviert.",
	'config-diff3-bad' => 'GNU diff3 isch nit gfunde wore.',
	'config-imagemagick' => 'ImageMagick isch gfunde wore: <code>$1</code>.
Miniaturaasichte vu Bilder sin megli, sobald s Uffelade vu Dateie aktiviert isch.',
	'config-help' => 'Hilf',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author YaronSh
 */
$messages['he'] = array(
	'config-desc' => 'תכנית ההתקנה של מדיה־ויקי',
	'config-title' => 'התקנת מדיה־ויקי $1',
	'config-information' => 'פרטים',
	'config-localsettings-upgrade' => 'זוהה קובץ <code>LocalSettings.php</code>.
כדי לשדרג את ההתקנה הזאת, נא להקליד את הערך של <code>$wgUpgradeKey</code> בתיבה להלן.
אפשר למצוא אותו בקובץ LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'זוהה קובץ LocalSettings.php.
כדי לשדרג את ההתקנה הזאת, הריצו את update.php ולא את הקובץ הזה.',
	'config-localsettings-key' => 'מפתח השדרוג:',
	'config-localsettings-badkey' => 'המפתח שהקלדתם שגוי',
	'config-upgrade-key-missing' => 'זוהתה התקנה קיימת של מדיה־ויקי.
כדי לשדרג את ההתקנה הזאת, אנא כתבו את השורה הבא בתחתית קובץ LocalSettings.php שלכם:

$1',
	'config-localsettings-incomplete' => 'נראה שקובץ LocalSettings.php הקיים אינו שלם.
המשתנה $1 אינו מוגדר.
נו לשנות את קובץ LocalSettings.php שלכם כך שהמשתנה הזה יהיה מוגדר וללחוץ "המשך".',
	'config-localsettings-connection-error' => 'אירעה שגיאה בעת חיבור למסד נתונים עם הגדרות ב־LocalSettings.php או ב־AdminSettings.php. נא לתקן את ההגדרות האלו ולנסות שוב.

$1',
	'config-session-error' => 'שגיאה באתחול שיחה: $1',
	'config-session-expired' => 'נראה שנתוני השיחה שלכם פגו.
השיחות מוגדרות להיות תקפות לזמן של $1.
אפשר להגדיל את זה ב־<code>session.gc_maxlifetime</code> בקובץ php.ini.
יש להתחיל מחדש את תהליך ההתקנה.',
	'config-no-session' => 'נתוני השיחה שלכם אבדו!
יש לבדוק את קובץ php.ini שלכם ולוודא שתיקייה נכונה מוגדרת ב־<code>session.save_path</code>.',
	'config-your-language' => 'השפה שלכם:',
	'config-your-language-help' => 'נא לבחור את השפה שתשמש במהלך ההתקנה.',
	'config-wiki-language' => 'שפת הוויקי:',
	'config-wiki-language-help' => 'נא לבחור את השפה העיקרית שבה ייכתב ויקי זה.',
	'config-back' => '→ חזרה',
	'config-continue' => 'המשך ←',
	'config-page-language' => 'שפה',
	'config-page-welcome' => 'ברוכים הבאים למדיה־ויקי!',
	'config-page-dbconnect' => 'התחברות למסד הנתונים',
	'config-page-upgrade' => 'שדרוג התקנה קיימת',
	'config-page-dbsettings' => 'הגדרות מסד הנתונים',
	'config-page-name' => 'שם',
	'config-page-options' => 'אפשרויות',
	'config-page-install' => 'התקנה',
	'config-page-complete' => 'הושלמה!',
	'config-page-restart' => 'הפעלת ההתקנה מחדש',
	'config-page-readme' => 'קרא־אותי',
	'config-page-releasenotes' => 'הערות גרסה',
	'config-page-copying' => 'העתקה',
	'config-page-upgradedoc' => 'שדרוג',
	'config-page-existingwiki' => 'ויקי קיים',
	'config-help-restart' => 'האם ברצונך לפנות את כל הנתונים שנשמרו שהוזנו על ידיך ולהתחיל מחדש את תהליך ההתקנה?',
	'config-restart' => 'כן, להפעיל מחדש',
	'config-welcome' => '=== בדיקות סביבה ===
בדיקות בסיסיות מתבצעות כדי לבדוק שהסביבה מתאימה להתקנת מדיה־ויקי.
יש לתת את תוצאות הבדיקות האלו אם תזדקקו לעזרה בזמן ההתקנה.',
	'config-copyright' => "=== זכויות יוצרים ותנאים ===

$1

תכנית זו היא תכנה חופשית; באפשרותך להפיצה מחדש ו/או לשנות אותה על פי תנאי הרישיון הציבורי הכללי של GNU כפי שפורסם על ידי קרן התכנה החופשית; בין אם גרסה 2 של הרישיון, ובין אם (לפי בחירתך) כל גרסה מאוחרת שלו.

תכנית זו מופצת בתקווה שתהיה מועילה, אבל '''בלא אחריות כלשהי'''; ואפילו ללא האחריות המשתמעת בדבר '''מסחריותה''' או '''התאמתה למטרה '''מסוימת'''. לפרטים נוספים, ניתן לעיין ברישיון הציבורי הכללי של GNU.

לתכנית זו אמור היה להיות מצורף <doclink href=Copying>עותק של הרישיון הציבורי הכללי של GNU</doclink>; אם לא, עליך לכתוב ל־Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, , MA 02111-1307, USA. או [http://www.gnu.org/copyleft/gpl.html לקרוא אותו דרך האינטרנט].",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki אתר הבית של מדיה־ויקי]
* [http://www.mediawiki.org/wiki/Help:Contents המדריך למשתמשים]
* [http://www.mediawiki.org/wiki/Manual:Contents המדריך למנהלים]
* [http://www.mediawiki.org/wiki/Manual:FAQ שו״ת]
----
* <doclink href=Readme>קרא אותי</doclink>
* <doclink href=ReleaseNotes>הערות גרסה</doclink>
* <doclink href=Copying>העתקה</doclink>
* <doclink href=UpgradeDoc>שדרוג</doclink>',
	'config-env-good' => 'הסביבה שלכם נבדקה.
אפשר להתקין מדיה־ויקי.',
	'config-env-bad' => 'הסביבה שלכם נבדקה.
אי־אפשר להתקין מדיה־ויקי.',
	'config-env-php' => 'מותקנת PHP $1.',
	'config-env-php-toolow' => 'מותקנת PHP $1.
למדיה־ויקי נדרשת PHP $2 או גרסה גבוהה יותר.',
	'config-unicode-using-utf8' => 'משתמש ב־normalize.so של בריון ויבר לנרמול יוניקוד.',
	'config-unicode-using-intl' => 'משתמש בהרחבת [http://pecl.php.net/intl הרחבת intl PECL] לנרמול יוניקוד',
	'config-unicode-pure-php-warning' => "'''אזהרה''': [http://pecl.php.net/intl הרחבת intl PECL] אינה זמינה לטיפול בנרמול יוניקוד. משתמש ביישום PHP טהור ואטי יותר.
אם זה אתר בעל תעבורה גבוהה, כדאי לקרוא את המסמך הבא: [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode normalization].",
	'config-unicode-update-warning' => "'''אזהרה''': הגרסה המותקנת של מעטפת נרמול יוניקוד משתמשת בגרסה ישנה של הספרייה של [http://site.icu-project.org/ פרויקט ICU].
כדאי [http://www.mediawiki.org/wiki/Unicode_normalization_considerations לעדכן] אם יש חשוב לכם הטיפול ביוניקוד.",
	'config-no-db' => 'לא נמצא דרייבר מסד נתונים מתאים.',
	'config-no-db-help' => 'יש להתקין דרייבר מסד נתונים ל־PHP.
נתמכים הסוגים הבאים של מסדי נתונים: $1.

אם אתם משתמשים באירוח משותף, בקשו מספק האירוח שלכם להתקין דרייבר מסד נתונים מתאים.
אם קמפלתם את PHP בעצמכם, הגדירו אותו מחדש והפעילו את לקוח מסד נתונים (database client), למשל בעזרת <code>./configure --with-mysql</code>.
אם התקנתם את PHP מחבילה של דביאן או אובונטו, יש להתקין את המודול php5-mysql.',
	'config-no-fts3' => "'''אזהרה''': SQLite מקומפל ללא [http://sqlite.org/fts3.html מודול FTS]. יכולות חיפוש לא יהיו זמינות בהתקנה הזאת.",
	'config-register-globals' => "'''אזהרה: האפשרות <code>[http://php.net/register_globals register_globals]</code> של PHP מופעלת.'''
'''כבו אותה אם אתם יכולים.'''
מדיה־ויקי תעבוד, אבל השרת שלכם חשוף לפגיעות אבטחה.",
	'config-magic-quotes-runtime' => "'''שגיאה סופנית: האפשרות [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] פעילה!'''
האפשרות הזאת מעוותת את נתוני הקלט באופן בלתי־צפוי.
לא ניתן להתקין את מדיה־ויקי אלא אם האפשרות הזאת תכובה.",
	'config-magic-quotes-sybase' => "'''שגיאה סופנית''': האפשרות [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] פעילה!'''
האפשרות הזאת מעוותת את נתוני הקלט באופן בלתי־צפוי.
לא ניתן להתקין את מדיה־ויקי או להשתמש בה אלא אם האפשרות הזאת תכובה.",
	'config-mbstring' => "'''שגיאה סופנית''': האפשרות [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] פעילה!'''
האפשרות הזאת גורמת לשגיאות ומעוותת את נתוני הקלט באופן בלתי־צפוי.
לא ניתן להתקין את מדיה־ויקי או להשתמש בה אלא אם האפשרות הזאת תכובה.",
	'config-ze1' => "'''שגיאה סופנית''': האפשרות [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] פעילה!'''
האפשרות הזאת גורמת לתקלות מזעזעות במדיה־ויקי.
לא ניתן להתקין את מדיה־ויקי או להשתמש בה אלא אם האפשרות הזאת תכובה.",
	'config-safe-mode' => "'''אזהרה:''' האפשרות [http://www.php.net/features.safe-mode safe mode] של PHP פעילה.
היא יכולה לגרום לבעיות, במיוחד אם אתם משתמשים בהעלאת קבצים או ב־<code>math</code>.",
	'config-xml-bad' => 'מודול XML של PHP חסר.
מדיה־ויקי דורשת פונקציות של המודול ולא תעבוד עם הגדרות כאלו.
אם מערכת ההפעלה שלהם היא Mandrake, התקינו את החבילה php-xml.',
	'config-pcre' => 'נראה שחסרה תמיכה במודול PCRE.
כדי שמדיה־ויקי תעבוד, נדרשת תמיכה בביטויים רגולריים תואמי Perl.',
	'config-pcre-no-utf8' => "'''שגיאה סופנית:''': נראה שמודול PCRE של PHP מקומפל ללא תמיכה ב־PCRE_UTF8.
מדיה־ויקי דורשת תמיכה ב־UTF-8 לפעילות נכונה.",
	'config-memory-raised' => 'ערך האפשרות <code>memory_limit</code> של PHP הוא $1, הועלה ל־$2.',
	'config-memory-bad' => "'''אזהרה:''' ערך האפשרות <code>memory_limit</code> של PHP הוא $1.
זה כנראה נמוך מדי.
ההתקנה עשויה להיכשל!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] מותקן',
	'config-apc' => '[http://www.php.net/apc APC] מותקן',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] מותקן',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] מותקן',
	'config-no-cache' => "'''אזהרה:''' אחת מהתוכנות הבאות לא נמצאה: [http://eaccelerator.sourceforge.net eAccelerator]&rlm;, [http://www.php.net/apc APC]&rlm;, [http://trac.lighttpd.net/xcache/ XCache] או [http://www.iis.net/download/WinCacheForPhp WinCache].
מטמון עצמים לא מופעל.",
	'config-diff3-bad' => 'GNU diff3 לא נמצא.',
	'config-imagemagick' => 'נמצא ImageMagick&rlm;: <code>$1</code>.
מזעור תמונות יופעל, אם תפעילו את האפשרות להעלות קבצים.',
	'config-gd' => 'נמצאה ספריית הגרפיקה GD המובנית.
מזעור תמונות יופעל, אם תפעילו את האפשרות להעלות קבצים.',
	'config-no-scaling' => 'ספריית GD או ImageMagick לא נמצאו.
מזעור תמונות לא יופעל.',
	'config-no-uri' => "'''שגיאה:''' אי־אפשר לזהות את הכתובת הנוכחית.
ההתקנה בוטלה.",
	'config-uploads-not-safe' => "'''אזהרה:''' התיקייה ההתחלתית להעלות <code>$1</code> חשופה להרצת סקריפטים.
מדיה־ויקי בודקת את כל הקבצים המוּעלים לאיומי אבטחה, מומלץ מאוד למנוע את [http://www.mediawiki.org/wiki/Manual:Security#Upload_security פרצת האבטחה] הזאת לפני שאתם מפעילים את ההעלאות.",
	'config-brokenlibxml' => 'במערכת שלכם יש שילוב של גרסאות של PHP ושל libxml2 שחשוף לבאגים ויכול לגרום לעיוות נתונים נסתר במדיה־ויקי וביישומי רשת אחרים.
שדרגו ל־PHP 5.2.9 או לגרסה חדשה יותר ול־libxml2 2.7.3 או גרסה חדשה יותר ([http://bugs.php.net/bug.php?id=45996 באג מתויק ב־PHP]).
ההתקנה בוטלה.',
	'config-using531' => 'אי־אפשר להשתמש במדיה־ויקי עם PHP $1 בגלל באג בפרמטרים של הפניות (reference parameters) ל־<span dir="ltr"><code>__call()</code></span>.
שדרגו ל־PHP 5.3.2 או לגרסה גבוהה יותר כדי לתקן את זה ([http://bugs.php.net/bug.php?id=50394 bug filed with PHP]) או שנמכו ל־PHP 5.3.0 כדי לפתור את הבעיה הזאת.
ההתקנה בוטלה.',
	'config-db-type' => 'סוג מסד הנתונים:',
	'config-db-host' => 'שרת מסד הנתונים:',
	'config-db-host-help' => 'אם שרת מסד הנתונים שלכם נמצא על שרת מחשב אחר, הקלידו את שם המחשב או כתובת ה־IP כאן.

אם אתם משתמשים באירוח משותף, ספק האירוח שלכם אמור לתת לכם את שם השרת הנכון במסמכים.

אם אתם מתקינים בשרת חלונות ומשתמשים ב־MySQL, השימוש ב־localhost עשוי לא לעבוד. אם הוא לא עובד, נסו את "127.0.0.1" בתור כתובת ה־IP המקומית.',
	'config-db-host-oracle' => 'TNS של מסד הנתונים:',
	'config-db-host-oracle-help' => 'הקלידו [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm שם חיבור מקומי (Local Connect Name)] תקין; הקובץ tnsnames.ora צריך להיות זמין להתקנה הזאת.<br />
אם אתם משתמשים ב־client libraries 10g או בגרסה חדשה יותר, אתם יכולים גם להשתמש בשיטת מתן השמות [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'זיהוי ויקי זה',
	'config-db-name' => 'שם מסד הנתונים:',
	'config-db-name-help' => 'בחרו שם שמזהה את הוויקי שלכם.
לא צריכים להיות בו רווחים.

אם אתם משתמשים באירוח משותף, ספק האירוח שלכם ייתן לכם שם מסד נתונים מסוים שתוכלו להשתמש בו או יאפשר לכם ליצור מסד נתונים דרך לוח בקרה.',
	'config-db-name-oracle' => 'סכמה של מסד נתונים:',
	'config-db-account-oracle-warn' => 'קיימים שלושה תרחישים נתמכים עבור התקנת אורקל בתור מסד הנתונים:

אם אתם רוצים ליצור חשבון מסד נתונים כחלק מתהליך ההתקנה, נא לספק חשבון בעל תפקיד SYSDBA בתור חשבון מסד הנתונים עבור ההתקנה ולציין את האישורים המבוקשים עבור חשבון הגישה לאינטרנט, אחרת ניתן ליצור באופן ידני את חשבון הגישה לאינטרנט, ולספק חשבון זה בלבד (אם יש לו ההרשאות הדרושות ליצירת עצמי סכמה) או לספק שני חשבונות שונים, אחד עם הרשאות יצירה ואחד מוגבלת עבור גישה לאינטרנט.

סקריפט ליצירת חשבון עם ההרשאות הנדרשות ניתן למצוא בתיקייה "<span dir="ltr">maintenance/oracle/</span>" של ההתקנה זו. זכרו כי שימוש בחשבון מוגבל יגרום להשבתת כל יכולות תחזוקה עם חשבון בררת המחדל.',
	'config-db-install-account' => 'חשבון משתמש להתקנה',
	'config-db-username' => 'שם המשתמש במסד הנתונים:',
	'config-db-password' => 'הססמה במסד הנתונים:',
	'config-db-password-empty' => 'נא להזין ססמה למשתמש מסד הנתונים החדש: $1.
אף־על־פי שאפשר ליצור חשבונות ללא ססמה, זה לא מאובטח.',
	'config-db-install-username' => 'הכניסו שם משתמש שישמש אתכם לחיבור למסד נתונים במהלך ההתקנה.
זהו לא שם משתמש לחשבון במדיה־ויקי; זהו שם משתמש בשרת מסד נתונים.',
	'config-db-install-password' => 'הקלידו ססמה שתשמש אתכם לצורך חיבור למסד נתונים במהלך ההתקנה.
זוהי לא ססמה של חשבון במדיה־ויקי; זוהי ססמה לשרת מסד נתונים.',
	'config-db-install-help' => 'הקלידו את שם המשתמש ואת הססמה להתחברות למסד הנתונים במהלך ההתקנה.',
	'config-db-account-lock' => 'להשתמש באותו שם המשתמש ובאותה ססמה בזמן הפעלה רגילה',
	'config-db-wiki-account' => 'חשבון משתמש להפעלה רגילה',
	'config-db-wiki-help' => 'הקלידו את שם המשתמש והססמה לחיבור למסד הנתונים במהלך פעילות רגילה של הוויקי.
אם החשבון אינו קיים ולחשבון שבו מתבצעת ההתקנה יש הרשאות מספיקות, החשבון הזה ייווצר עם ההרשאות המזעריות הנחוצות להפעלת הוויקי.',
	'config-db-prefix' => 'תחילית לטבלאות של מסד נתונים (database table prefix):',
	'config-db-prefix-help' => 'אם אתם צריכים לשתף מסד נתונים אחד בין אתרי ויקי שונים או בין מדיה־ויקי ויישום וב אחר, תוכלו לבחור להוסיף תחילית וכל שמות הטבלאות כדי להימנע מהתנגשויות.
אל תשתמשו ברווחים.

השדה הזה בדרך כלל אמור להיות ריק.',
	'config-db-charset' => 'קבוצת התווים (character set) של מסד הנתונים',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binary',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 backwards-compatible UTF-8',
	'config-charset-help' => "'''אזהרה:''' אם אתם משתמשים ב־'''backwards-compatible UTF-8''' ב־<span dir=\"ltr\">MySQL 4.1+</span>, ומגבים את מסד הנתונים באמצעות <code>mysqldump</code>, זה יכול להרוס את כל תווי ה־ASCII ויהרוס באופן בלתי־הפיך את הגיבויים שלכם!

ב'''מצב בינרי''' (binary mode) מדיה־ויקי שומרת טקסט UTF-8 במסד הנתונים בשדות בינריים.
זה יעיל יותר ממצב UTF-8 של MySQL ומאפשר לכם להשתמש בכל הטווח של תווי יוניקוד.
ב'''מצב UTF-8'''&rlm; (UTF-8 mode)&rlm; MySQL יֵדַע מה קבוצת התווים (character set) של הטקסט שלכם ויציג וימיר אותו בהתאם, אבל לא יאפשר לכם לשמור תווים שאינם נמצאים בטווח הרב־לשוני הבסיסי ([http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane]).",
	'config-mysql-old' => 'נדרשת גרסה $1 של MySQL או גרסה חדשה יותר. הגרסה הנוכחית שלכם היא $2.',
	'config-db-port' => 'פִּתְחַת מסד הנתונים (database port):',
	'config-db-schema' => 'סכמה למדיה־ויקי',
	'config-db-schema-help' => 'הסְכֵמָה הבאה בדרך כלל מתאימה.
שנו אותה רק אם אתם יודעים שאתם חייבים.',
	'config-sqlite-dir' => 'תיקיית נתונים (data directory) של SQLite:',
	'config-sqlite-dir-help' => 'SQLite שומר את כל הנתונים בקובץ אחד.

לשרת הווב צריכה להיות הרשאה לכתוב לתיקייה שאתם מגדירים.

היא לא צריכה נגישה לכולם דרך האינטרנט – בגלל זה איננו שמים אותה באותו מקום עם קובצי ה־PHP.

תוכנת ההתקנה תכתוב קובץ <span dir="ltr"><code>.htaccess</code></span> יחד אִתו, אבל אם זה ייכשל, מישהו יוכל להשיג גישה למסד הנתונים שלכם. שם נמצא מידע מפורש של משתמשים (כתובות דוא״ל, ססמאות מגובבות) וגם גרסאות מחוקות של דפים ומידע מוגבל אחר.

כדאי לשקול לשים את מסד הנתונים במקום אחר לגמרי, למשל ב־<span dir="ltr"><code>/var/lib/mediawiki/yourwik</code></span>.',
	'config-oracle-def-ts' => 'מרחב טבלאות לפי בררת מחדל (default tablespace):',
	'config-oracle-temp-ts' => 'מרחב טבלאות זמני (temporary tablespace):',
	'config-support-info' => 'מדיה־ויקי תומכת במערכות מסדי הנתונים הבאות:

$1

אם אינכם רואים את מסד הנתונים שלכם ברשימה, עקבו אחר ההוראות המקושרות לעיל כדי להפעיל את התמיכה.',
	'config-support-mysql' => '* $1 הוא היעד העיקרי עבור מדיה־ויקי ולו התמיכה הטובה ביותר (ר׳ [http://www.php.net/manual/en/mysql.installation.php how to compile PHP with MySQL support])',
	'config-support-postgres' => '$1 הוא מסד נתונים נפוץ בקוד פתוח והוא נפוץ בתור חלופה ל־MySQL (ר׳ [http://www.php.net/manual/en/pgsql.installation.php how to compile PHP with PostgreSQL support]). ייתכן שיש בתצורה הזאת באגים מסוימים והיא לא מומלצת לסביבות מבצעיות.',
	'config-support-sqlite' => '* $1 הוא מסד נתונים קליל עם תמיכה טובה מאוד. (ר׳ [http://www.php.net/manual/en/pdo.installation.php How to compile PHP with SQLite support], משתמש ב־PDO)',
	'config-support-oracle' => '* $1 הוא מסד נתונים עסקי מסחרי. (ר׳ [http://www.php.net/manual/en/oci8.installation.php How to compile PHP with OCI8 support])',
	'config-header-mysql' => 'הגדרות MySQL',
	'config-header-postgres' => 'הגדרות PostgreSQL',
	'config-header-sqlite' => 'הגדרות SQLite',
	'config-header-oracle' => 'הגדרות Oracle',
	'config-invalid-db-type' => 'סוג מסד הנתונים שגוי',
	'config-missing-db-name' => 'עליך להזין ערך עבור "שם מסד הנתונים"',
	'config-missing-db-host' => 'יש להכניס ערך לשדה "שרת מסד הנתונים"',
	'config-missing-db-server-oracle' => 'יש להכניס ערך לשדה "TNS של מסד הנתונים"',
	'config-invalid-db-server-oracle' => '"$1" הוא TNS בלתי תקין.
יש להשתמש רק באותיות ASCII&rlm; (a עד z&rlm;, A עד Z), סְפָרוֹת (0 עד 9), קווים תחתיים (_) ונקודות (.).',
	'config-invalid-db-name' => '"$1" הוא שם מסד נתונים בלתי תקין.
יש להשתמש רק באותיות ASCII&rlm; (a עד z&rlm;, A עד Z), סְפָרוֹת (0 עד 9), קווים תחתיים (_) ומינוסים (-).',
	'config-invalid-db-prefix' => '"$1" היא תחילית מסד נתונים בלתי תקינה.
יש להשתמש רק באותיות ASCII&rlm; (a עד z&rlm;, A עד Z), סְפָרוֹת (0 עד 9), קווים תחתיים (_) ומינוסים (-).',
	'config-connection-error' => '$1.

בדקו את שם השרת, את שם המשתמש ואת הססמה ונסו שוב.',
	'config-invalid-schema' => '"$1" היא סכמה לא תקינה עבור מדיה־ויקי.
יש להשתמש רק באותיות ASCII&rlm; (a עד z&rlm;, A עד Z), סְפָרוֹת (0 עד 9) וקווים תחתיים (_).',
	'config-db-sys-create-oracle' => 'תוכנית ההתקנה תומכת רק בשימוש בחשבון SYSDBA ליצירת חשבון חדש.',
	'config-db-sys-user-exists-oracle' => 'חשבון המשתמש "$1" כבר קיים. SYSDBA יכול לשמש רק ליצירת חשבון חדש!',
	'config-postgres-old' => 'נדרש PostgreSQL $1 או גרסה חדשה יותר, הגרסה הנוכחית שלכם היא $2.',
	'config-sqlite-name-help' => 'בחרו בשם שמזהה את הוויקי שלכם.
אל תשתמשו ברווחים או במינוסים.
זה יהיה שם קובץ הנתונים ל־SQLite.',
	'config-sqlite-parent-unwritable-group' => 'לא ניתן ליצור את תיקיית הנתונים <code><nowiki>$1</nowiki></code>, כי לשָׁרַת הווב אין הרשאות לכתוב לתיקיית האם <code><nowiki>$2</nowiki></code> .

תוכנת ההתקנה זיהתה את החשבון שתחתיו רץ שרת הווב שלכם.
אפשרו לשָׁרַת הווב לכתוב לתיקייה <code><nowiki>$3</nowiki></code>.
במערכת Unix/Linux כִתבו:

<div dir="ltr"><pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre></div>',
	'config-sqlite-parent-unwritable-nogroup' => 'לא ניתן ליצור את תיקיית הנתונים <code><nowiki>$1</nowiki></code>, כי לשָׁרַת הווב אין הרשאות לכתוב לתיקיית האם <code><nowiki>$2</nowiki></code> .

תוכנת ההתקנה לא זיהתה את החשבון שתחתיו רץ שרת הווב שלכם.
אפשרו לכל החשבונות לכתוב לתיקייה <code><nowiki>$3</nowiki></code> כדי להמשיך.
במערכת Unix/Linux כִתבו:

<div dir="ltr"><pre>cd $2
mkdir $3
chmod a+w $3</pre></div>',
	'config-sqlite-mkdir-error' => 'אירעה שגיאה בעת יצירת תיקיית הנתונים "$1".
נא לבדוק את המיקום ולנסות שוב.',
	'config-sqlite-dir-unwritable' => 'אי־אפשר לכתוב לתיקייה "$1".
שנו את ההרשאות שלה כך ששרת הווב יוכל לכתוב אליה ונסו שוב.',
	'config-sqlite-connection-error' => '$1.

בִדקו את תיקיית הנתונים את שם מסת הנתונים להלן ונסו שוב.',
	'config-sqlite-readonly' => 'לא ניתן לכתוב אל הקובץ <code>$1</code>.',
	'config-sqlite-cant-create-db' => 'לא ניתן ליצור את קובץ מסד הנתונים <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'ב־PHP חסרה תמיכה ב־FTS3, יבתצע שנמוך טבלאות',
	'config-can-upgrade' => "יש טבלאות מדיה־ויקי במסד הנתונים.
כדי לשדרג אותן למדיה־ויקי $1, לחצו '''המשך'''.",
	'config-upgrade-done' => "השדרוג הושלם.

עכשיו אפשר [$1 להשתמש בוויקי שלכם].

אם תרצו ליצור מחדש את קובץ ה־<code>LocalSettings.php</code> שלכם, לחצו על הכפתור להלן.
זה '''לא מומלץ''', אלא אם כן יש לכם בעיות עם הוויקי שלכם.",
	'config-upgrade-done-no-regenerate' => 'השדרוג הושלם.

עכשיו אפשר [$1 להתחיל להשתמש בוויקי שלכם].',
	'config-regenerate' => 'לחולל מחדש את LocalSettings.php ←',
	'config-show-table-status' => 'שאילתת SHOW TABLE STATUS נכשלה!',
	'config-unknown-collation' => "'''אזהרה:''' מסד הנתונים משתמש בשיטת מיון שאינה מוּכּרת.",
	'config-db-web-account' => 'חשבון במסד הנתונים לגישה מהרשת',
	'config-db-web-help' => 'לבחור את שם המשתמש ואת הססמה ששרת הווב ישתמש בו להתחברות לשרת מסד הנתונים בזמן פעילות רגילה של הוויקי.',
	'config-db-web-account-same' => 'להשתמש באותו חשבון כמו עבור ההתקנה',
	'config-db-web-create' => 'ליצור חשבון אם הוא אינו קיים כבר.',
	'config-db-web-no-create-privs' => 'לחשבון שהקלדתם להתקנה אין מספיק הרשאות ליצירת חשבות.
החשבון שאתם מקלידים כאן צריך להיות קיים.',
	'config-mysql-engine' => 'מנגנון האחסון:',
	'config-mysql-innodb' => '',
	'config-mysql-myisam' => '',
	'config-mysql-engine-help' => "'''InnoDB''' הוא כמעט תמיד האפשרות הטובה ביותר, כי במנוע הזה יש תמיכה טובה ביותר בעיבוד מקבילי.

'''MyISAM''' עשוי להיות בהתקנות שמיועדות למשתמש אחד ולהתקנות לקריאה בלבד.
מסדי נתונים עם MyISAM נוטים להיהרס לעתים קרובות יותר מאשר מסדי נתונים עם InnoDB.",
	'config-mysql-charset' => 'ערכת הקידוד של מסד הנתונים:',
	'config-mysql-binary' => 'בינרי',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "ב'''מצב בינרי''' (binary mode) מדיה־ויקי שומרת טקסט UTF-8 במסד הנתונים בשדות בינריים.
זה יעיל יותר ממצב UTF-8 של MySQL ומאפשר לכם להשתמש בכל הטווח של תווי יוניקוד.

ב'''מצב UTF-8'''&rlm; (UTF-8 mode)&rlm; MySQL יֵדַע מה קבוצת התווים (character set) של הטקסט שלכם ויציג וימיר אותו בהתאם, אבל לא יאפשר לכם לשמור תווים שאינם נמצאים בטווח הרב־לשוני הבסיסי ([http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane]).",
	'config-site-name' => 'שם הוויקי:',
	'config-site-name-help' => 'זה יופיע בשורת הכותרת של הדפדפן ובמקומות רבים אחרים.',
	'config-site-name-blank' => 'נא להזין שם לאתר.',
	'config-project-namespace' => 'מרחב שמות לדפי מיזם:',
	'config-ns-generic' => 'מיזם',
	'config-ns-site-name' => 'זהה לשם הוויקי: $1',
	'config-ns-other' => 'אחר (לציין)',
	'config-ns-other-default' => 'הוויקי־שלי',
	'config-project-namespace-help' => "בהתאם לדוגמה של ויקיפדיה, אתרי ויקי רבים שומרים על דפי המדיניות שלהם בנפרד מדפי התוכן שלהם ב\"'''מרחב השמות של המיזם'''\" (\"'''project namespace'''\").
כל שמות הדפים במרחב השמות הזה נפתחים בתחילית מסוימת שאתם יכולים להגדיר כאן.
באופן מסורתי התחילית הזאת מבוססת על שם הוויקי, אבל אינו יכול להכיל תווי פיסוק כגון \"#\" או \":\".",
	'config-ns-invalid' => 'מרחב השמות "<nowiki>$1</nowiki>" אינו תקין.
הקלידו שם אחר למרחב השמות של המיזם.',
	'config-ns-conflict' => 'מרחב השמות שהגדרתם "<nowiki>$1</nowiki>" מתנגש עם מרחב שמות מובנה של מדיה־ויקי.
הגדירו מרחב שמות מיזם שונה.',
	'config-admin-box' => 'חשבון מפעיל',
	'config-admin-name' => 'שמכם:',
	'config-admin-password' => 'ססמה:',
	'config-admin-password-confirm' => 'הססמה שוב:',
	'config-admin-help' => 'הקלידו כאן את שם המשתמש, למשל "שקד לוי" או "Joe Bloggs".
זה השם שישמש אתכם כדי להיכנס לוויקי.',
	'config-admin-name-blank' => 'נא להזין את שם המשתמש של המפעיל.',
	'config-admin-name-invalid' => 'שם המשתמש שהוקלד "<nowiki>$1</nowiki>" אינו תקין.
הקלידו שם משתמש אחר.',
	'config-admin-password-blank' => 'הקלידו ססמה לחשבון המפעיל.',
	'config-admin-password-same' => 'הססמה לא יכולה להיות זהה לשם המשתמש.',
	'config-admin-password-mismatch' => 'שתי הססמאות שהוזנו אינן מתאימות.',
	'config-admin-email' => 'כתובת הדוא״ל:',
	'config-admin-email-help' => 'הקלידו כתובת דוא״ל שתאפשר לכם לקבל מכתבים ממשתמשים אחרים בוויקי, לאתחל את הססמה, ולקבל הודעות על שינויים בדפים ברשימת המעקב שלכם. אפשר להשאיר את השדה הזה ריק.',
	'config-admin-error-user' => 'שגיאה פנימית ביצירת מפעיל בשם "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'שגיאה פנימית בהגדרת ססמה עבור המפעיל "<nowiki>$1</nowiki>"&rlm;: <pre>$2</pre>',
	'config-admin-error-bademail' => 'הכנסתם כתובת דוא״ל לא תקינה.',
	'config-subscribe' => 'להירשם ל[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce רשימת התפוצה עם הודעות על גרסאות חדשות].',
	'config-subscribe-help' => 'זוהי רשימת תפוצה עם הודעות מעטות שמשמשת להודעות על הוצאת גרסאות, כולל עדכוני אבטחה חשובים.
מומלץ להירשם אליה ולעדכן את מדיה־ויקי כאשר יוצאות גרסאות חדשות.',
	'config-almost-done' => 'כמעט סיימתם!
אפשר לדלג על שאר ההגדרות ולהתקין את הוויקי כבר עכשיו.',
	'config-optional-continue' => 'הצגת שאלות נוספות.',
	'config-optional-skip' => 'משעמם לי, תתקינו לי כבר את הוויקי הזה.',
	'config-profile' => 'תסריט הרשאות משתמשים:',
	'config-profile-wiki' => 'ויקי מסורתי',
	'config-profile-no-anon' => 'נדרשת יצירת חשבון',
	'config-profile-fishbowl' => 'עורכים מורשים בלבד',
	'config-profile-private' => 'ויקי פרטי',
	'config-profile-help' => "אתרי ויקי עובדים הכי טוב כאשר אתם מאפשרים לכמה שיותר אנשים לערוך אותם.
במדיה־ויקי קל לסקור את השינויים האחרונים ולשחזר כל נזק שעושים משתמשים תמימים או משחיתים.

עם זאת, אנשים שונים מצאו למדיה־ויקי שימושים מגוּונים ולעתים לא קל לשכנע את כולם ביתרונות של \"דרך הוויקי\" המסורתית. ולכן יש לכם בררה.

באתר '''{{int:config-profile-wiki}}''' – לכולם יש הרשאה לערוך, אפילו בלי להיכנס לחשבון.
באתר וויקי מסוג '''{{int:config-profile-no-anon}}''' יש ביטחון גדול יותר, אבל הגדרה כזאת יכולה להרתיע תורמים מזדמנים.

בתסריט '''{{int:config-profile-fishbowl}}''' רק משתמשים שקיבלו אישור יכולים לערוך, אבל כל הגולשים יכולים לקרוא את הדפים ואת גרסאותיהם הקודמות.
ב'''{{int:config-profile-private}}''' רק משתמשים שקיבלו אישור יכולים לקרוא ולערוך דפים.

הגדרות מורכבות של הרשאות אפשריות אחרי ההתקנה, ר׳ את [http://www.mediawiki.org/wiki/Manual:User_rights הפרק על הנושא הזה בספר ההדרכה].",
	'config-license' => 'זכויות יוצרים ורישיון:',
	'config-license-none' => 'ללא כותרת תחתית עם רישיון',
	'config-license-cc-by-sa' => 'קריאייטיב קומונז–ייחוס–שיתוף זהה',
	'config-license-cc-by-nc-sa' => 'קריאייטיב קומונז ייחוס–ללא שימוש מסחרי–שיתוף זהה',
	'config-license-cc-0' => 'Creative Commons אפס',
	'config-license-gfdl-old' => 'רישיון חופשי למסמכים של גנו, גרסה 1.2',
	'config-license-gfdl-current' => 'רישיון חופשי למסמכים של גנו, גרסה 1.3 או גרסה מאוחרת יותר',
	'config-license-pd' => 'נחלת הכלל',
	'config-license-cc-choose' => 'בחרו רישיון קריאייטיב קומונז מותאם אישית',
	'config-license-help' => "אתרי ויקי ציבוריים רבים מפרסמים את כל התרומות תחת [http://freedomdefined.org/Definition רישיון חופשי].
זה עוזר ליצור תחושה של בעלות קהילתית ומעודד תרומה לאורך זמן.
זה בדרך כלל לא נחוץ לאתר ויקי פרטי או בחברה מסחרית.

אם אתם רוצים אפשרות להשתמש בטקסט מוויקיפדיה ואתם רוצים שוויקיפדיה תוכל לקבל עותקים של טקסטים מהוויקי שלכם, כדאי לכם לבחור ב'''רישיון קריאייטיב קומונז ייחוס–שיתוף זהה''' (CC-BY-SA).

הרישיון החופשי למסמכים של גנו הוא הרישיון שבו ויקיפדיה השתמשה בעבר (GNU FDL או GFDL).
הוא עדיין תקין, אבל יש בו תכונות מסוימות שמקשות על שימוש חוזר ועל פרשנות.",
	'config-email-settings' => 'הגדרות דוא״ל',
	'config-enable-email' => 'להפעיל דוא״ל יוצא',
	'config-enable-email-help' => 'אם אתם רוצים שדוא״ל יעבוד, [http://www.php.net/manual/en/mail.configuration.php אפשרויות הדוא״ל של PHP] צריכות להיות מוגדרות נכון.
אם אינכם רוצים להפעיל שום אפשרויות דוא״ל, כבו אותן כאן ועכשיו.',
	'config-email-user' => 'לאפשר שליחת דוא״ל ממשתמש למשתמש',
	'config-email-user-help' => 'לאפשר לכל המשתמשים לשלוח אחד לשני דוא״ל אם הם הפעילו את זה בהעדפות שלהם.',
	'config-email-usertalk' => 'לאפשר הודעות על דף שיחת משתמש',
	'config-email-usertalk-help' => 'לאפשר למשתמשים לקבל הודעות על שינויים בדפי המשתמש שלהם, אם הם הפעילו את זה בהעדפות שלהם.',
	'config-email-watchlist' => 'הפעלת התרעה על רשימת המעקב',
	'config-email-watchlist-help' => 'לאפשר למשתמשים לקבל הודעות על הדפים ברשימת המעקב שלהם אם הם הפעילו את זה בהעדפות שלהם.',
	'config-email-auth' => 'הפעלת התרעה בדוא״ל',
	'config-email-auth-help' => "אם האפשרות הזאת מופעלת, משתמשים יצטרכו לאשר את כתובת הדוא״ל שלהם באמצעות קישור שיישלח אליהם בכל פעם שהם יגדירו או ישנו אותה.
רק כתובות דוא״ל מאושרות יכולות לקבלת דוא״ל ממשתמשים אחרים או מכתבים עם הודעות על שינויים.
'''מומלץ''' להגדיר את האפשרות הזאת לאתרי ויקי ציבוריים כי אפשר לעשות שימוש לרעה בתכונות הדוא״ל.",
	'config-email-sender' => 'כתובת דוא״ל לתשובות:',
	'config-email-sender-help' => 'הכניסו את כתובת הדוא״ל שתשמש ככתובת לתשובה לכל הדואר היוצא.
לשם יישלחו תגובות שגיאה (bounce).
שרתי דוא״ל רבים דורשים שלפחות החלק של המתחם יהיה תקין.',
	'config-upload-settings' => 'העלאת קבצים ותמונות',
	'config-upload-enable' => 'אפשור העלאת קבצים',
	'config-upload-help' => 'העלאות קבצים חושפות את השרת שלכם לסיכוני אבטחה.
למידע נוסף, קִראו את [http://www.mediawiki.org/wiki/Manual:Security חלק האבטחה] בספר ההדרכה.

כדי להפעיל העלאת קבצים שנו את ההרשאות של התיקייה <code>images</code> תחת תיקיית השורש של מדיה־ויקי כך ששרת הווב יוכל לכתוב אליה.
זה מפעיל את האפשרות הזאת.',
	'config-upload-deleted' => 'תיקיית הקבצים שנמחקו:',
	'config-upload-deleted-help' => 'בחרו את התיקייה לארכוב קבצים מחוקים.
כדאי שזה לא יהיה נגיש לכל העולם דרך הרשת.',
	'config-logo' => 'כתובת הסמל:',
	'config-logo-help' => 'המראה ההתחלתי של מדיה־ויקי מכיל מקום לסמל של 135 על 160 פיקסלים בפינה השמאלית העליונה (ימנית עבור שפות שנכתבות מימין לשמאל).
יש להעלות תמונה בגודל מתאים ולהכניס את הכתובת כאן.

אם אינכם רוצים סמל, השאירו את התיבה הזאת ריקה.',
	'config-instantcommons' => 'להפעיל את Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] היא תכונה שמאפשרת לאתרי ויקי להשתמש בתמונות, בצלילים ובמדיה אחרת שנמצאת באתר [http://commons.wikimedia.org/ ויקישיתוף] (Wikimedia Commons).
כדי לעשות את זה, מדיה־ויקי צריך לגשת לאינטרנט.

למידע נוסף על התכונה הזאת, כולל הוראות איך להפעיל את זה לאתרי ויקי שאינם ויקישיתוף, ר׳ [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos את ספר ההדרכה].',
	'config-cc-error' => 'בורר רישיונות קריאייטיב קומונז לא החזיר שום תוצאה.
הקלידו את שם הרישיון ידנית.',
	'config-cc-again' => 'נא לבחור שוב...',
	'config-cc-not-chosen' => 'בחרו באיזה רישיון קריאייטיב קומונז להשתמש ולחצו "המשך".',
	'config-advanced-settings' => 'הגדרות מתקדמות',
	'config-cache-options' => 'הגדרות למטמון עצמים (object caching):',
	'config-cache-help' => 'מטמון עצמים משמש לשיפור המהירות של מדיה־ויקי על־ידי שמירה של נתונים שהשימוש בהם נפוץ במטמון.
לאתרים בינוניים וגדולים כדאי מאוד להפעיל את זה, וגם אתרים קטנים ייהנו מזה.',
	'config-cache-none' => 'ללא מטמון (שום יכולת אינה מוסרת, אבל הביצועים באתרים גדולים ייפגעו)',
	'config-cache-accel' => 'מטמון עצמים (object caching) של PHP&rlm; (APC&rlm;, eAccelerator&rlm;, XCache או WinCache)',
	'config-cache-memcached' => 'להשתמש ב־Memcached (דורש התקנות והגדרות נוספות)',
	'config-memcached-servers' => 'שרתי Memcached:',
	'config-memcached-help' => 'רשימת כתובות IP ש־Memcached ישתמש בהן.
יש לרשום כתובת אחת בכל שורה ולציין את הפִּתְחָה (port), למשל:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'בחרת ב־Memcached בתתור סוג המטמון שלכם, אבל לא הגדרתם שום שרת.',
	'config-memcache-badip' => 'הקלדתם כתובת IP בלתי תקינה ל־Memcached&lrm;: $1.',
	'config-memcache-noport' => 'לא הגדרתם פתחה לשימוש שרת Memcached&rlm;: $1.
אם אינכם יודעים את מספר הפתחה, בררת המחדל היא 11211.',
	'config-memcache-badport' => 'מספרי פתחה של Memcached צריכים להיות בין $1 ל־$2',
	'config-extensions' => 'הרחבות',
	'config-extensions-help' => 'ההרחבות ברשימה לעיל התגלו בתיקיית <span dir="ltr"><code>./extensions</code></span> שלכם.

ייתכן שזה ידרוש הגדרות נוספות, אבל תוכלו להפעיל אותן עכשיו.',
	'config-install-alreadydone' => "'''אזהרה:''' נראה שכבר התקנתם את מדיה־ויקי ואתם מנסים להתקין אותה שוב.
אנה התקדמו לדף הבא.",
	'config-install-begin' => 'כשתלחצו על "{{int:config-continue}}", תתחילו את ההתקנה של מדיה־ויקי.
אם אתם עדיין רוצים לשנות משהו, לחצו על "הקודם".',
	'config-install-step-done' => 'בוצע',
	'config-install-step-failed' => 'נכשל',
	'config-install-extensions' => 'כולל הרחבות',
	'config-install-database' => 'הקמת מסד נתונים',
	'config-install-pg-schema-not-exist' => 'סכמה של PostgreSQL אינה קיימת',
	'config-install-pg-schema-failed' => 'יצירת טבלאות נכשלה.
ודאו כי המשתמש "$1" יכול לכתוב לסכמה "$2".',
	'config-install-pg-commit' => 'שמירת שינויים',
	'config-install-pg-plpgsql' => 'בדיקת שפת PL/pgSQL',
	'config-pg-no-plpgsql' => 'צריך להתקין את שפת PL/pgSQL במסד הנתונים $1',
	'config-pg-no-create-privs' => 'לחשבון שהגדרתם להתקנה אין מספיק הרשאות ליצירת חשבון.',
	'config-install-user' => 'יצירת חשבון במסד נתונים',
	'config-install-user-alreadyexists' => 'המשתמש "$1" כבר קיים',
	'config-install-user-create-failed' => 'יצירת משתמש "$1" נכשלה: $2',
	'config-install-user-grant-failed' => 'מתן הרשאות למשתמש "$1" נכשל: $2',
	'config-install-tables' => 'יצירת טבלאות',
	'config-install-tables-exist' => "'''אזהרה:''' נראה שטבלאות מדיה־ויקי כבר קיימות.
מדלג על יצירתן.",
	'config-install-tables-failed' => "'''שגיאה:''' יצירת הטבלה נכשלה עם השגיאה הבאה: $1",
	'config-install-interwiki' => 'אכלוס טבלת בינוויקי התחלתית',
	'config-install-interwiki-list' => 'קריאת הקובץ <code>interwiki.list</code> לא הצליחה.',
	'config-install-interwiki-exists' => "'''אזהרה:''': נראה שבטבלת הבינוויקי כבר יש רשומות.
מדלג על הרשומה ההתחלתית.",
	'config-install-stats' => 'אתחול סטטיסטיקות',
	'config-install-keys' => 'יצירת מפתחות סודיים',
	'config-install-sysop' => 'יצירת חשבון מפעיל',
	'config-install-subscribe-fail' => 'הרישום ל־mediawiki-announce לא הצליח',
	'config-install-mainpage' => 'יצירת דף ראשי עם תוכן לפי בררת מחדל.',
	'config-install-extension-tables' => 'יצירת טבלאות להרחבות מופעלות',
	'config-install-mainpage-failed' => 'לא הצליחה הכנסת דף ראשי: $1.',
	'config-install-done' => "'''מזל טוב!'''
התקנתם בהצלחה את מדיה־ויקי.

תוכנת ההתקנה יצרה את הקובץ <code>LocalSettings.php</code>.
הוא מכיל את כל ההגדרות שלכם.

תצטרכו להוריד אותו ולשים אותו בבסיס ההתקנה של הוויקי שלכם (אות התיקייה שבה נמצא הקובץ index.php). ההורדה הייתה אמורה להתחיל באופן אוטומטי.

אם ההורדה לא התחילה, אם אם ביטלתם אותה, אפשר להתחיל אותה מחדש בלחיצה על הקישור הבא:

$3

'''שימו לב''': אם לא תעשו זאת עכשיו, קובץ ההגדרות המחולל לא יהיה זמין לכם שוב.

אחרי שתעשו את זה, תוכלו '''[$2 להיכנס לוויקי שלכם]'''.",
	'config-download-localsettings' => 'הורדת LocalSettings.php',
	'config-help' => 'עזרה',
);

/** Upper Sorbian (Hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'config-desc' => 'Instalaciski program za MediaWiki',
	'config-title' => 'Instalacija MediaWiki $1',
	'config-information' => 'Informacije',
	'config-localsettings-upgrade' => 'Dataja <code>LocalSettings.php</code> je so wotkryła.
Zo by tutu instalaciju aktualizował, zapodaj prošu hódnotu za parameter <code>$wgUpgradeKey</code> do slědowaceho pola.
Namakaš tón parameter w dataji LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Dataja LocalSettings.php bu wotkryta.
Zo by tutu instalaciju aktualizował, wuwjedźće update.php',
	'config-localsettings-key' => 'Aktualizaciski kluč:',
	'config-localsettings-badkey' => 'Kluč, kotryž sy podał, je wopak',
	'config-upgrade-key-missing' => 'Eksistowaca instalacija MediaWiki je so wotkryła.
Zo by tutu instalaciju aktualizował, staj prošu slědowacu linku deleka w dataji LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Zda so, zo eksistwoaca dataja LocalSettings.php je njedospołna.
Wariabla $1 njeje nastajena.
Prošu změń dataju LocalSettings.php, zo by so tuta wariabla nastajiła a klikń na "Dale".',
	'config-localsettings-connection-error' => 'Při zwjazowanju z datowej banku z pomocu nastajenjow podatych w LocalSettings.php abo AdminSettings.php je zmylk wustupił. Prošu skoriguj tute nastajenja a spytaj hišće raz.

$1',
	'config-session-error' => 'Zmylk při startowanju posedźenja: $1',
	'config-session-expired' => 'Zda so, zo twoje posedźenske daty su spadnjene.
Posedźenja su za čas žiwjenja $1 skonfigurowane.
Móžeš jón přez nastajenje <code>session.gc_maxlifetime</code> w php.ini powyšić.
Startuj instalaciski proces znowa.',
	'config-no-session' => 'Twoje posedźenske daty su so zhubili!
Skontroluj swój php.ini a zawěsć, zo <code>session.save_path</code> je na prawy zapis nastajeny.',
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
	'config-page-existingwiki' => 'Eksistowacy wiki',
	'config-help-restart' => 'Chceš wšě składowane daty hašeć, kotrež sy zapodał a instalaciski proces znowa startować?',
	'config-restart' => 'Haj, znowa startować',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Startowa strona MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Nawod za wužiwarjow]
* [http://www.mediawiki.org/wiki/Manual:Contents Nawod za administratorow]
* [http://www.mediawiki.org/wiki/Manual:FAQ Huste prašenja]
----
* <doclink href=Readme>Čitaj mje</doclink>
* <doclink href=ReleaseNotes>Wersijowe informacije</doclink>
* <doclink href=Copying>Licencne postajenja</doclink>
* <doclink href=UpgradeDoc>Aktualizacija</doclink>',
	'config-env-good' => 'Wokolina je so skontrolowała.
Móžeš MediaWiki instalować.',
	'config-env-bad' => 'Wokolina je so skontrolowała.
Njemóžeš MediaWiki instalować.',
	'config-env-php' => 'PHP $1 je instalowany.',
	'config-env-php-toolow' => 'PHP $1 je instalowany.
Ale MediaWiki wužaduje sej PHP $2 abo wyši.',
	'config-unicode-using-utf8' => 'Za normalizaciju Unicode so utf8_normalize.so Briona Vibbera wužiwa.',
	'config-unicode-using-intl' => 'Za normalizaciju Unicode so [http://pecl.php.net/intl PECL-rozšěrjenje intl] wužiwa.',
	'config-no-db' => 'Njeda so přihódny ćěrjak datoweje banki namakać!',
	'config-no-fts3' => "'''Warnowanje''': SQLite je so bjez [http://sqlite.org/fts3.html FTS3-modula] kompilował, pytanske funkcije njebudu k dispoziciji stać.",
	'config-register-globals' => "'''Warnowanje: Funkcija <code>[http://php.net/register_globals register_globals]</code> PHP je zmóžnjena.'''
'''Znjemóžń ju, jeli móžeš.'''
MediaWiki budźe fungować, ale twój serwer je potencielnym wěstotnym njedostatkam wustajeny.",
	'config-ze1' => "'''Chutny zmylk: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] je aktiwny!'''
Tuta opcija zawinuje grawěrowace zmylki při MediaWiki.
Njemóžeš MediaWiki instalować abo wužiwać, chibazo tuta opcija je znjemóžnjena.",
	'config-safe-mode' => "'''Warnowanje:''' [http://www.php.net/features.safe-mode wěsty modus] PHP je aktiwny.
To móže problemy zawinować, předewšěm, jeli so datajowe nahraća a podpěra <code>math</code> wužiwaja.",
	'config-xml-bad' => 'XML-modul za PHP faluje.
MediaWiki trjeba funkcije w tutym modulu a njebudźe w tutej konfiguraciji fungować.
Jeli wužiwaš Mandrake, instaluj paket php-xml.',
	'config-memory-raised' => 'PHP-parameter <code>memory_limit</code> je $1, je so na hódnotu $2 zwyšił.',
	'config-memory-bad' => "'''Warnowanje:''' PHP-parameter <code>memory_limit</code> ma hódnotu $1,
To je najskerje přeniske.
Instalacija móhła so njeporadźić!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] je instalowany',
	'config-apc' => '[http://www.php.net/apc APC] je instalowany',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] je instalowany',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] je instalowany',
	'config-diff3-bad' => 'GNU diff3 njenamakany.',
	'config-no-uri' => "'''Zmylk:''' Aktualny URI njeda so postajić.
Instalacija bu přetorhnjena.",
	'config-db-type' => 'Typ datoweje banki:',
	'config-db-host' => 'Serwer datoweje banki:',
	'config-db-host-oracle' => 'Datowa banka TNS:',
	'config-db-wiki-settings' => 'Tutón wiki identifikować',
	'config-db-name' => 'Mjeno datoweje banki:',
	'config-db-name-oracle' => 'Šema datoweje banki:',
	'config-db-install-account' => 'Wužiwarske konto za instalaciju',
	'config-db-username' => 'Wužiwarske mjeno datoweje banki:',
	'config-db-password' => 'Hesło datoweje banki:',
	'config-db-password-empty' => 'Prošu zapodaj hesło za noweho wužiwarja datoweje banki: $1.
Byrnjež było móžno wužiwarjow bjez hesłow wutworić, njeje to wěste.',
	'config-db-install-username' => 'Zapodaj wužiwarske mjeno, kotrež budźe so za zwisk z datowej banku za instalaciski proces wužiwać. 
To njeje wužiwarske mjeno konta MediaWiki; to je wužiwarske mjeno za twoju datowu banku.',
	'config-db-install-password' => 'Zapodaj hesło, kotrež budźe so za zwisk z datowej banku za instalaciski proces wužiwać.
To njeje hesło konta MediaWiki; to je hesło za twoju datowu banku.',
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
	'config-db-schema-help' => 'Tuta šema da so zwjetša derje wužiwać.
Změń ju jenož, jeli su přeswědčiwe přičiny za to.',
	'config-sqlite-dir' => 'Zapis SQLite-datow:',
	'config-oracle-def-ts' => 'Standardny tabelowy rum:',
	'config-oracle-temp-ts' => 'Nachwilny tabelowy rum:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-mysql' => '* $1 je primarny cil za MediaWiki a podpěruje so najlěpje ([http://www.php.net/manual/en/mysql.installation.php Nawod ke kompilowanju  PHP z  MySQL-podpěru])',
	'config-support-postgres' => '* $1 je popularny system datoweje banki zjawneho žórła jako alternatiwa k MySQL ([http://www.php.net/manual/en/pgsql.installation.php nawod za kompilowanje PHP z podpěru PostgreSQL])',
	'config-header-mysql' => 'Nastajenja MySQL',
	'config-header-postgres' => 'Nastajenja PostgreSQL',
	'config-header-sqlite' => 'Nastajenja SQLite',
	'config-header-oracle' => 'Nastajenja Oracle',
	'config-invalid-db-type' => 'Njepłaćiwy typ datoweje banki',
	'config-missing-db-name' => 'Dyrbiš hódnotu za "Mjeno datoweje banki" zapodać',
	'config-missing-db-host' => 'Dyrbiš hódnotu za "Database host" zapodać',
	'config-missing-db-server-oracle' => 'Dyrbiš hódnotu za "Database TNS" zapodać',
	'config-invalid-db-server-oracle' => 'Njepłaćiwa datowa banka TNS "$1".
Wužij jenož pismiki ASCII (a-z, A-Z), ličby (0-9), podsmužki (_) a dypki (.).',
	'config-invalid-db-name' => 'Njepłaćiwe mjeno "$1" datoweje banki.
Wužij jenož pismiki ASCII (a-z, A-Z), ličby (0-9),a podsmužki (_) a wjazawki (-).',
	'config-invalid-db-prefix' => 'Njepłaćiwy prefiks "$1" datoweje banki.
Wužij jenož pismiki ASCII (a-z, A-Z), ličby (0-9), podsmužki (_) a wjazawki (-).',
	'config-connection-error' => '$1.

Skontroluj serwer, wužiwarske a hesło a spytaj hišće raz.',
	'config-invalid-schema' => 'Njepłaćiwe šema za MediaWiki "$1".
Wužij jenož pismiki ASCII (a-z, A-Z), ličby (0-9) a podsmužki (_).',
	'config-db-sys-create-oracle' => 'Instalaciski program podpěruje jenož wužiwanje SYSDBA-konta za zakoženje noweho konta.',
	'config-db-sys-user-exists-oracle' => 'Wužiwarske konto "$1" hižo eksistuje. SYSDBA hodźi so jenož za załoženje noweho konta wužiwać!',
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
	'config-can-upgrade' => "Su tabele MediaWiki w tutej datowej bance.
Zo by je na MediaWiki $1 aktualizował, klikń na '''Dale'''.",
	'config-upgrade-done-no-regenerate' => 'Aktualizacija dokónčena.

Móžeš nětko [$1 swój wiki wužiwać].',
	'config-regenerate' => 'LocalSettings.php znowa wutworić →',
	'config-show-table-status' => 'Naprašowanje SHOW TABLE STATUS je so njeporadźiło!',
	'config-unknown-collation' => "'''Warnowanje:''' Datowa banka njeznatu kolaciju wužiwa.",
	'config-db-web-account' => 'Konto datoweje banki za webpřistup',
	'config-db-web-help' => 'wubjer wužiwarske mjeno a hesło, kotrejž webserwer budźe wužiwać, zo by z serwerom datoweje banki za wšědnu operaciju zwjazać',
	'config-db-web-account-same' => 'Samsne konto kaž za instalaciju wužiwać',
	'config-db-web-create' => 'Załož konto, jeli hišće njeeksistuje.',
	'config-db-web-no-create-privs' => 'Konto, kotrež sy za instalaciju podał, nima dosć woprawnjenjow, zo by konto wutworiło.
Konto, kotrež tu podawaće, dyrbi hižo eksistować.',
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
	'config-ns-conflict' => 'Podaty mjenowy rum "<nowiki>$1</nowiki>" je w konflikće ze standardnym mjenjowym rumom MediaWiki.
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
	'config-admin-email-help' => 'Zapodaj tu e-mejlowu adresu, zo by přijimanje e-mejlow wot druhich wužiwarjow w tutym wikiju zmóžnił, swoje hesło wróćo stajił a zdźělenki wo změnach na swojich wobkedźbowanych stronach  dostał.',
	'config-admin-error-user' => 'Interny zmylk při wutworjenju administratora z mjenom "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Interny zmylk při nastajenju hesła za administratora "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Sy njepłaćiwu e-mejlowu adresu zapodał.',
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
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-cc-0' => 'Creative Commons "Zero"',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 abo nowša',
	'config-license-pd' => 'Powšitkownosći přistupny',
	'config-license-cc-choose' => 'Swójsku licencu Creative Commons wubrać',
	'config-email-settings' => 'E-mejlowe nastajenja',
	'config-enable-email' => 'Wuchadźace e-mejlki zmóžnić',
	'config-enable-email-help' => 'Jeli chceš e-mejl wužiwać, dyrbja so [http://www.php.net/manual/en/mail.configuration.php e-mejlowe nastajenja PHP] prawje konfigurować.
Jeli nochceš e-mejlowe funkcije wužiwać, móžeš je tu znjemóžnić.',
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
	'config-cc-error' => 'Pytanje za licencu Creative Commons njeje žadyn wuslědk přinjesło.
Zapodaj licencne mjeno manuelnje.',
	'config-cc-again' => 'Zaso wubrać...',
	'config-cc-not-chosen' => 'Wubjer licencu Creative Commons a klikń na "dale".',
	'config-advanced-settings' => 'Rozšěrjena konfiguraćija',
	'config-cache-options' => 'Nastajenja za objektowe pufrowanje:',
	'config-cache-none' => 'Žane pufrowanje (žana funkcionalnosć so njewotstronja, ale spěšnosć móže so na wjetšich wikijowych sydłach wobwliwować)',
	'config-cache-accel' => 'Objektowe pufrowanje PHP (APC, eAccelerator, XCache abo WinCache)',
	'config-cache-memcached' => 'Memcached wužiwać (wužaduje sej přidatnu instalaciju a konfiguraciju)',
	'config-memcached-servers' => 'Serwery memcached:',
	'config-memcached-help' => 'Lisćina IP-adresow, kotrež maja so za Memcached wužiwać.
Kóžda linka měła jenož jednu IP-adresu a port, kotryž ma so wužiwać, wobsahować. Na přikład:
127.0.0.1:11211
192.168.1.25:1234',
	'config-memcache-needservers' => 'Sy Memcached jako swój pufrowakowy typ wubrał, ale njejsy žane serwery podał',
	'config-memcache-badip' => 'Sy njepłaćiwu IP-adresu za Memcached zapodał: $1',
	'config-memcache-noport' => 'Njejsy žadyn port za wužiwanje serwera Memcached podał: $1.
Jeli port njewěš, standard je 11211.',
	'config-memcache-badport' => 'Portowe čisła za Memcached měli mjez $1 a $2 być',
	'config-extensions' => 'Rozšěrjenja',
	'config-extensions-help' => 'Rozšěrjenja podate horjeka buchu w twojim zapisu <code>./extensions</code> namakane.

To móže sej přidatnu konfiguraciju wužadać, ale móžeš je nětko zmóžnić.',
	'config-install-alreadydone' => "'''Warnowanje:''' Zda so, zo sy hižo MediaWiki instalował a pospytuješ jón znowa instalować.
Prošu pokročuj z přichodnej stronu.",
	'config-install-begin' => 'Přez kliknjenje na "{{int:config-continue}}" budźe so instalacija MediaWiki startować.
Jeli hišće chceš něšto změnić, klikń na "Wróćo".',
	'config-install-step-done' => 'dokónčene',
	'config-install-step-failed' => 'njeporadźiło',
	'config-install-extensions' => 'Inkluziwnje rozšěrjenja',
	'config-install-database' => 'Datowa banka so připrawja',
	'config-install-pg-schema-not-exist' => 'Šema PostgreSQL njeeksistuje',
	'config-install-pg-schema-failed' => 'Wutworjenje tabelow je so njeporadźiło.
Zawěsć, zo wužiwar "$1" móže do šemy "$2" pisać.',
	'config-install-pg-commit' => 'Změny so wotesyłaja',
	'config-install-pg-plpgsql' => 'Pruwowanje za rěču PL/pgSQL',
	'config-pg-no-plpgsql' => 'Dyrbiš rěč PL/pgSQL w datowej bance $1 instalować',
	'config-pg-no-create-privs' => 'Konto, kotrež sy za instalaciju podał, nima dosahace prawa za wutworjenje konta.',
	'config-install-user' => 'Tworjenje wužiwarja datoweje banki',
	'config-install-user-alreadyexists' => 'Wužiwar "$1" hižo eksistuje',
	'config-install-user-create-failed' => 'Wutworjenje wužiwarja "$1" je so njeporadźiło: $2',
	'config-install-user-grant-failed' => 'Prawo njeda so wužiwarjej "$1" dać: $2',
	'config-install-tables' => 'Tworjenje tabelow',
	'config-install-tables-exist' => "'''Warnowanje''': Zda so, zo tabele MediaWiki hižo eksistuja.
Wutworjenje so přeskakuje.",
	'config-install-tables-failed' => "'''Zmylk''': Wutworjenje tabele je so slědowaceho zmylka dla njeporadźiło: $1",
	'config-install-interwiki' => 'Standardna tabela interwikijow so pjelni',
	'config-install-interwiki-list' => '<code>interwiki.list</code> njeda so namakać.',
	'config-install-interwiki-exists' => "'''Warnowanje''': Zda so, zo tabela interwikjow hižo zapiski wobsahuje.
Standardna lisćina sp přeskakuje.",
	'config-install-stats' => 'Statistika so inicializuje',
	'config-install-keys' => 'Tworjenje tajneho kluča',
	'config-install-sysop' => 'Tworjenje administratoroweho wužiwarskeho konta',
	'config-install-subscribe-fail' => 'Abonowanje "mediawiki-announce" njemóžno',
	'config-install-mainpage' => 'Hłowna strona so ze standardnym wobsahom wutworja',
	'config-install-extension-tables' => 'Tabele za zmóžnjene rozšěrjenja so tworja',
	'config-install-mainpage-failed' => 'Powěsć njeda so zasunyć: $1',
	'config-download-localsettings' => 'LocalSettings.php sćahnyć',
	'config-help' => 'pomoc',
);

/** Hungarian (Magyar)
 * @author Dani
 * @author Glanthor Reviol
 */
$messages['hu'] = array(
	'config-desc' => 'A MediaWiki telepítője',
	'config-title' => 'A MediaWiki $1 telepítése',
	'config-information' => 'Információ',
	'config-localsettings-upgrade' => 'Már létezik a <code>LocalSettings.php</code> fájl.
A telepített szoftver frissítéséhez írd be az alábbi mezőbe a <code>$wgUpgradeKey</code> beállítás értékét, melyet a LocalSettings.php nevű fájlban találhatsz meg.',
	'config-localsettings-key' => 'Frissítési kulcs:',
	'config-localsettings-badkey' => 'A megadott kulcs érvénytelen.',
	'config-localsettings-connection-error' => 'Nem sikerült csatlakozni az adatbázishoz a LocalSettings.php-ben vagy az AdminSettings.php-ben megadott adatokkal. Ellenőrizd a beállításokat, majd próbáld újra.

$1',
	'config-session-error' => 'Nem sikerült elindítani a munkamenetet: $1',
	'config-session-expired' => 'Úgy tűnik, hogy a munkamenetadatok lejártak.
A munkamenetek élettartama a következőre van beállítva: $1.
Az érték növelhető a php.ini <code>session.gc_maxlifetime</code> beállításának módosításával.
Indítsd újra a telepítési folyamatot.',
	'config-no-session' => 'Elvesztek a munkamenetadatok!
Ellenőrizd, hogy a php.ini-ben a <code>session.save_path</code> a megfelelő könyvtárra mutat-e.',
	'config-your-language' => 'Nyelv:',
	'config-your-language-help' => 'A telepítési folyamat során használandó nyelv.',
	'config-wiki-language' => 'A wiki nyelve:',
	'config-wiki-language-help' => 'Az a nyelv, amin a wiki tartalmának legnagyobb része íródik.',
	'config-back' => '← Vissza',
	'config-continue' => 'Folytatás →',
	'config-page-language' => 'Nyelv',
	'config-page-welcome' => 'Üdvözöl a MediaWiki!',
	'config-page-dbconnect' => 'Kapcsolódás az adatbázishoz',
	'config-page-upgrade' => 'Telepített változat frissítése',
	'config-page-dbsettings' => 'Adatbázis-beállítások',
	'config-page-name' => 'Név',
	'config-page-options' => 'Beállítások',
	'config-page-install' => 'Telepítés',
	'config-page-complete' => 'Kész!',
	'config-page-restart' => 'Telepítés újraindítása',
	'config-page-readme' => 'Tudnivalók',
	'config-page-releasenotes' => 'Kiadási megjegyzések',
	'config-page-copying' => 'Másolás',
	'config-page-upgradedoc' => 'Frissítés',
	'config-page-existingwiki' => 'Létező wiki',
	'config-help-restart' => 'Szeretnéd törölni az eddig megadott összes adatot és újraindítani a telepítési folyamatot?',
	'config-restart' => 'Igen, újraindítás',
	'config-welcome' => '=== A környezet ellenőrzése ===
Néhány alapvető ellenőrzés lett végrehajtva, ami meghatározza, hogy ez a környezet alkalmas-e a MediaWiki telepítésére.
Ha telepítéssel kapcsolatos segítségre van szükséged, add meg ezen ellenőrzések eredményét.',
	'config-copyright' => "=== Licenc és feltételek ===

$1

Ez a program szabad szoftver; terjeszthető illetve módosítható a Free Software Foundation által kiadott GNU General Public License dokumentumában leírtak; akár a licenc 2-es, akár (tetszőleges) későbbi változata szerint.

Ez a program abban a reményben kerül közreadásra, hogy hasznos lesz, de minden egyéb '''garancia nélkül''', az '''eladhatóságra''' vagy '''valamely célra való alkalmazhatóságra''' való származtatott garanciát is beleértve. További részleteket a GNU General Public License tartalmaz.

A felhasználónak a programmal együtt meg kell kapnia a <doclink href=Copying>GNU General Public License egy példányát</doclink>; ha mégsem kapta meg, akkor írjon a Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. címre, vagy [http://www.gnu.org/copyleft/gpl.html tekintse meg online].",
	'config-sidebar' => '* [http://www.mediawiki.org A MediaWiki honlapja]
* [http://www.mediawiki.org/wiki/Help:Contents Felhasználói kézikönyv]
* [http://www.mediawiki.org/wiki/Manual:Contents Útmutató adminisztrátoroknak]
* [http://www.mediawiki.org/wiki/Manual:FAQ GyIK]
----
* <doclink href=Readme>Ismertető</doclink>
* <doclink href=ReleaseNotes>Kiadási megjegyzések</doclink>
* <doclink href=Copying>Másolás</doclink>
* <doclink href=UpgradeDoc>Frissítés</doclink>',
	'config-env-good' => 'A környezet ellenőrzése befejeződött.
A MediaWiki telepíthető.',
	'config-env-bad' => 'A környezet ellenőrzése befejeződött.
A MediaWiki nem telepíthető.',
	'config-env-php' => 'A PHP verziója: $1',
	'config-env-php-toolow' => 'PHP $1 van telepítve,
azonban a MediaWikinek PHP $2, vagy újabb szükséges.',
	'config-unicode-using-utf8' => 'A rendszer Unicode normalizálására Brion Vibber utf8_normalize.so könyvtárát használja.',
	'config-unicode-using-intl' => 'A rendszer Unicode normalizálására az [http://pecl.php.net/intl intl PECL kiterjesztést] használja.',
	'config-unicode-pure-php-warning' => "'''Figyelmeztetés''': Az Unicode normalizáláshoz szükséges [http://pecl.php.net/intl intl PECL kiterjesztés] nem érhető el, helyette a lassú, PHP alapú implementáció lesz használva.
Ha nagy látogatottságú oldalt üzemeltetsz, itt találhatsz további információkat [http://www.mediawiki.org/wiki/Unicode_normalization_considerations a témáról].",
	'config-no-db' => 'Nem sikerült egyetlen használható adatbázismeghajtót sem találni.',
	'config-no-fts3' => "'''Figyelmeztetés''': Az SQLite [http://sqlite.org/fts3.html FTS3 modul] nélkül lett fordítva, a keresési funkciók nem fognak működni ezen a rendszeren.",
	'config-register-globals' => "'''Figyelmeztetés: A PHP <code>[http://php.net/register_globals register_globals]</code> beállítása engedélyezve van.'''
'''Tiltsd le, ha van rá lehetőséged.'''
A MediaWiki működőképes a beállítás használata mellett, de a szerver biztonsági kockázatnak lesz kitéve.",
	'config-magic-quotes-runtime' => "'''Kritikus hiba: a [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] aktív!'''
Ez a beállítás kiszámíthatatlan károkat okoz a bevitt adatokban.
A MediaWiki csak akkor telepíthető, ha ki van kapcsolva.",
	'config-magic-quotes-sybase' => "'''Kritikus hiba: a [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_sybase] aktív!'''
Ez a beállítás kiszámíthatatlan károkat okoz a bevitt adatokban.
A MediaWiki csak akkor telepíthető, ha ki van kapcsolva.",
	'config-mbstring' => "'''Kritikus hiba: az [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime mbstring.func_overload] aktív!'''
Ez a beállítás hibákat okoz és kiszámíthatatlanul károsíthatja bevitt adatokat.
A MediaWiki csak akkor telepíthető, ha ki van kapcsolva.",
	'config-ze1' => "'''Kritikus hiba: a [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_sybase] aktív!'''
Ez a beállítás borzalmas hibákat okoz a MediaWiki futása során.
A MediaWiki csak akkor telepíthető, ha ki van kapcsolva.",
	'config-xml-bad' => 'A PHP XML-modulja hiányzik.
Egyes MediaWiki-funkciók, melyek ezt a modult igénylik, nem fognak működni ilyen beállítások mellett.
Ha Madrake-et futtatsz, telepítsd a php-xml csomagot.',
	'config-pcre' => 'Úgy tűnik, hogy a PCRE támogató modul hiányzik.
A MediaWikinek Perl-kompatibilis reguláriskifejezés-függvényekre van szüksége a működéshez.',
	'config-memory-raised' => 'A PHP <code>memory_limit</code> beállításának értéke: $1. Meg lett növelve a következő értékre: $2.',
	'config-memory-bad' => "'''Figyelmeztetés:''' A PHP <code>memory_limit</code> beállításának értéke $1.
Ez az érték valószínűleg túl kevés, a telepítés sikertelen lehet.",
	'config-xcache' => 'Az [http://trac.lighttpd.net/xcache/ XCache] telepítve van',
	'config-apc' => 'Az [http://www.php.net/apc APC] telepítve van',
	'config-eaccel' => 'Az [http://eaccelerator.sourceforge.net/ eAccelerator] telepítve van',
	'config-wincache' => 'A [http://www.iis.net/download/WinCacheForPhp WinCache] telepítve van',
	'config-no-cache' => "'''Figyelmeztetés:''' Nem található [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] és [http://www.iis.net/download/WinCacheForPhp WinCache] sem.
Az objektum-gyorsítótárazás nem lesz engedélyezve.",
	'config-diff3-bad' => 'GNU diff3 nem található.',
	'config-imagemagick' => 'Az ImageMagick megtalálható a rendszeren: <code>$1</code>.
A bélyegképek készítése engedélyezve lesz a feltöltések engedélyezése esetén.',
	'config-gd' => 'A GD grafikai könyvtár elérhető.
Bélyegképek készítése működni fog, miután engedélyezted a fájlfeltöltést.',
	'config-no-scaling' => 'Nem található a GD könyvtár és az ImageMagick.
A bélyegképek készítése le lesz tiltva.',
	'config-no-uri' => "'''Hiba:''' Nem sikerült megállapítani a jelenlegi URI-t.
Telepítés megszakítva.",
	'config-uploads-not-safe' => "'''Figyelmeztetés:''' a feltöltésekhez használt alapértelmezett könyvtárban (<code>$1</code>) tetszőleges külső szkript futtatható.
Habár a MediaWiki ellenőrzi a feltöltött fájlokat az efféle biztonsági veszélyek megtalálása érdekében, a feltöltés engedélyezése előtt erősen ajánlott a [http://www.mediawiki.org/wiki/Manual:Security#Upload_security a sérülékenység megszüntetése].",
	'config-db-type' => 'Adatbázis típusa:',
	'config-db-host' => 'Adatbázis hosztneve:',
	'config-db-host-help' => 'Ha az adatbázisszerver másik szerveren található, add meg a hosztnevét vagy az IP-címét.

Ha megosztott webtárhelyet használsz, a szolgáltató dokumentációjában megtalálható a helyes hosztnév.

Ha Windows-alapú szerverre telepítesz, és MySQL-t használsz, a „localhost” nem biztos, hogy működni fog. Ha így van, próbáld meg a „127.0.0.1” helyi IP-cím használatát.',
	'config-db-host-oracle' => 'Adatbázis TNS:',
	'config-db-wiki-settings' => 'A wiki azonosítása',
	'config-db-name' => 'Adatbázisnév:',
	'config-db-name-help' => 'Válassz egy nevet a wiki azonosítására.
Ne tartalmazzon szóközt.

Ha megosztott webtárhelyet használsz, a szolgáltatód vagy egy konkrét adatbázisnevet ad neked használatra, vagy te magad hozhatsz létre adatbázisokat a vezérlőpulton keresztül.',
	'config-db-name-oracle' => 'Adatbázisséma:',
	'config-db-install-account' => 'A telepítéshez használt felhasználói fiók adatai',
	'config-db-username' => 'Felhasználónév:',
	'config-db-password' => 'Jelszó:',
	'config-db-install-username' => 'Írd be az adatbázisrendszerhez való csatlakozáshoz használt felhasználónevet.
Ez nem a MediaWiki fiók felhasználóneve; ez az adatbázisrendszeren használt felhasználóneved.',
	'config-db-install-password' => 'Írd be az adatbázisrendszerhez való csatlakozáshoz használt jelszót.
Ez nem a MediaWiki-fiók jelszava; ez az adatbázisrendszeren használt jelszavad.',
	'config-db-install-help' => 'Add meg a felhasználónevet és jelszót, amivel a telepítő csatlakozhat az adatbázishoz.',
	'config-db-account-lock' => 'Általános működés során is ezen információk használata',
	'config-db-wiki-account' => 'Általános működéshez használt felhasználói adatok',
	'config-db-wiki-help' => 'Add meg azt a felhasználónevet és jelszót, amivel a wiki fog csatlakozni az adatbázishoz működés közben.
Ha a fiók nem létezik és a telepítést végző fiók rendelkezik megfelelő jogosultsággal, egy új fiók készül a megadott a névvel, azon minimális jogosultságkörrel, ami a wiki működéséhez szükséges.',
	'config-db-prefix' => 'Adatbázistáblák nevének előtagja:',
	'config-db-prefix-help' => 'Ha egyetlen adatbázison osztozik több wiki, vagy a MediaWiki és más webalkalmazás, választhatsz egy előtagot a táblaneveknek, hogy megelőzd a konfliktusokat.
Ne használj szóközöket.

A mezőt általában üresen kell hagyni.',
	'config-db-charset' => 'Az adatbázis karakterkészlete',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0, bináris',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0, visszafelé kompatibilis UTF-8',
	'config-charset-help' => "'''Figyelmezetés:''' Ha a '''visszafelé kompatibilis UTF-8''' beállítást használod MySQL 4.1 vagy újabb verziók esetén, és utána a <code>mysqldump</code> programmal készítesz róla biztonsági másolatot, az tönkreteheti az összes nem ASCII-karaktert, visszafordíthatatlanul károsítva a másolatokban tárolt adatokat!

'''Bináris''' módban a MediaWiki az UTF-8-ban kódolt szöveget bináris mezőkben tárolja az adatbázisban.
Ez sokkal hatékonyabb a MySQL UTF-8-módjától, és lehetővé teszi, hogy a teljes Unicode-karakterkészletet használd.
'''UTF-8-módban''' MySQL tudja, hogy milyen karakterkészlettel van kódolva az adat, és megfelelően tárolja és konvertálja, de
nem használhatod a [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane] feletti karaktereket.",
	'config-mysql-old' => 'A MySQL $1 vagy újabb verziója szükséges, a rendszeren $2 van.',
	'config-db-port' => 'Adatbázisport:',
	'config-db-schema' => 'MediaWiki-séma',
	'config-db-schema-help' => 'A fenti sémák általában megfelelőek.
Csak akkor módosíts rajtuk, ha tudod, hogy szükséges.',
	'config-sqlite-dir' => 'SQLite-adatkönyvtár:',
	'config-oracle-def-ts' => 'Alapértelmezett táblatér:',
	'config-oracle-temp-ts' => 'Ideiglenes táblatér:',
	'config-support-info' => 'A MediaWiki a következő adatbázisrendszereket támogatja:

$1

Ha az alábbi listán nem találod azt a rendszert, melyet használni szeretnél, a fenti linken található instrukciókat követve engedélyezheted a támogatását.',
	'config-support-mysql' => '* A $1 a MediaWiki elsődleges célpontja, így a legjobban támogatott ([http://www.php.net/manual/en/mysql.installation.php Hogyan fordítható a PHP MySQL-támogatással])',
	'config-support-postgres' => '* A $1 népszerű, nyílt forráskódú adatbázisrendszer, a MySQL alternatívája ([http://www.php.net/manual/en/pgsql.installation.php Hogyan fordítható a PHP PostgreSQL-támogatással]). Több apró, javítatlan hiba is előfordulhat, így nem ajánlott éles környezetben használni.',
	'config-support-sqlite' => '* Az $1 egy könnyű, nagyon jól támogatott adatbázisrendszer. ([http://www.php.net/manual/en/pdo.installation.php Hogyan fordítható a PHP SQLite-támogatással], PDO-t használ)',
	'config-support-oracle' => '* Az $1 kereskedelmi, vállalati adatbázisrendszer. ([http://www.php.net/manual/en/oci8.installation.php Hogyan fordítható a PHP OCI8-támogatással])',
	'config-header-mysql' => 'MySQL-beállítások',
	'config-header-postgres' => 'PostgreSQL-beállítások',
	'config-header-sqlite' => 'SQLite-beállítások',
	'config-header-oracle' => 'Oracle-beállítások',
	'config-invalid-db-type' => 'Érvénytelen adatbázistípus',
	'config-missing-db-name' => 'Meg kell adnod az „adatbázis nevét”',
	'config-missing-db-server-oracle' => 'Meg kell adnod az „Adatbázis TNS” értékét',
	'config-invalid-db-server-oracle' => 'Érvénytelen adatbázis TNS: „$1”
Csak ASCII betűk (a-z, A-Z), számok (0-9), alulvonás (_) és pont (.) használható.',
	'config-invalid-db-name' => 'Érvénytelen adatbázisnév: „$1”.
Csak ASCII-karakterek (a-z, A-Z), számok (0-9), alulvonás (_) és kötőjel (-) használható.',
	'config-invalid-db-prefix' => 'Érvénytelen adatbázisnév-előtag: „$1”.
Csak ASCII-karakterek (a-z, A-Z), számok (0-9), alulvonás (_) és kötőjel (-) használható.',
	'config-connection-error' => '$1.

Ellenőrizd a hosztot, felhasználónevet és jelszót, majd próbáld újra.',
	'config-invalid-schema' => 'Érvénytelen MediaWiki-séma: „$1”.
Csak ASCII-karakterek (a-z, A-Z), számok (0-9) és alulvonás (_) használható.',
	'config-db-sys-create-oracle' => 'A telepítő csak a SYSDBA fiókkal tud új felhasználói fiókot létrehozni.',
	'config-db-sys-user-exists-oracle' => 'Már létezik „$1” nevű felhasználói fiók. A SYSDBA csak új fiók létrehozására használható!',
	'config-postgres-old' => 'A PostgreSQL $1 vagy újabb verziója szükséges, a rendszeren $2 van.',
	'config-sqlite-parent-unwritable-nogroup' => 'Nem lehet létrehozni az adatok tárolásához szükséges <code><nowiki>$1</nowiki></code> könyvtárat, mert a webszerver nem írhat a szülőkönyvtárba (<code><nowiki>$2</nowiki></code>).

A telepítő nem tudta megállapíteni, hogy melyik felhasználói fiókon fut a webszerver.
A folytatáshoz tedd írhatóvá ezen fiók (és más fiókok!) számára a következő könyvtárat: <code><nowiki>$3</nowiki></code>.
Unix/Linux rendszereken tedd a következőt:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Nem sikerült létrehozni a következő adatkönyvtárat: „$1”.
Ellenőrizd a helyet, majd próbáld újra.',
	'config-sqlite-dir-unwritable' => 'Nem sikerült írni a következő könyvtárba: „$1”.
Módosítsd a jogosultságokat úgy, hogy a webszerver tudjon oda írni, majd próbáld újra.',
	'config-sqlite-connection-error' => '$1.

Ellenőrizd az adatkönyvtárat és az adatbázisnevet, majd próbáld újra.',
	'config-sqlite-readonly' => 'A következő fájl nem írható: <code>$1</code>.',
	'config-regenerate' => 'LocalSettings.php elkészítése újra →',
	'config-show-table-status' => 'A SHOW TABLE STATUS lekérdezés nem sikerült!',
	'config-unknown-collation' => "'''Figyelmeztetés:''' az adatbázis ismeretlen egybevetést használ.",
	'config-db-web-account' => 'A webes hozzáférésnél használt adatbázisfiók',
	'config-db-web-help' => 'Add meg azt a felhasználónevet és jelszót, amit a webszerver a wiki általános működése során használ a csatlakozáshoz.',
	'config-db-web-account-same' => 'Ezen fiók használata a telepítéshez is',
	'config-db-web-create' => 'Fiók létrehozása, ha még nem létezik.',
	'config-mysql-engine' => 'Tárolómotor:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "A legtöbb esetben az '''InnoDB''' a legjobb választás, mivel megfelelően támogatja a párhuzamosságot.

A '''MyISAM''' gyorsabb megoldás lehet egyfelhasználós vagy csak olvasható környezetekben, azonban a MyISAM-adatbázisok sokkal gyakrabban sérülnek meg, mint az InnoDB-adatbázisok.",
	'config-mysql-charset' => 'Adatbázis karakterkészlete:',
	'config-mysql-binary' => 'Bináris',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "'''Bináris módban''' a MediaWiki az UTF-8-as szövegeket bináris mezőkben tárolja az adatbázisban.
Ez sokkal hatékonyabb a MySQL UTF-8-as módjánál, és lehetővé teszi a teljes Unicode-karakterkészlet használatát.

'''UTF-8-as módban''' a MySQL tudni fogja,hogy az adatok milyen karakterkészlettel rendelkeznek, és megfelelően átalakítja őket, azonban nem tárolhatóak olyan karakterek, melyek a [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane] felett vannak.",
	'config-site-name' => 'A wiki neve:',
	'config-site-name-help' => 'A böngésző címsorában és még számos más helyen jelenik meg.',
	'config-site-name-blank' => 'Add meg az oldal nevét.',
	'config-project-namespace' => 'Projektnévtér:',
	'config-ns-generic' => 'Projekt',
	'config-ns-site-name' => 'Ugyanaz, mint a wiki neve: $1',
	'config-ns-other' => 'Más (meg kell adni)',
	'config-ns-other-default' => 'SajátWiki',
	'config-project-namespace-help' => "A Wikipédia példáját követve számos wiki elkülöníti egy '''projekt névtérbe''' az irányelveit a tartalommal rendelkező lapoktól
Az ebben a névtérben található lapok nevei egy előtaggal kezdődnek, amit itt adhatsz meg.
Általában az előtag a wiki nevéből származik, de nem tartalmazhat írásjeleket, például „#”-t vagy „:”-t.",
	'config-admin-box' => 'Adminisztrátori fiók',
	'config-admin-name' => 'Név:',
	'config-admin-password' => 'Jelszó:',
	'config-admin-password-confirm' => 'Jelszó újra:',
	'config-admin-help' => 'Írd be a kívánt felhasználónevet, például „Kovács János”.
Ezzel a névvel fogsz majd bejelentkezni a wikibe.',
	'config-admin-name-blank' => 'Add meg az adminisztrátor felhasználónevét!',
	'config-admin-name-invalid' => 'A megadott felhasználónév (<nowiki>$1</nowiki>) érvénytelen.
Adj meg egy másik felhasználónevet.',
	'config-admin-password-blank' => 'Add meg az adminisztrátori fiók jelszavát!',
	'config-admin-password-same' => 'A jelszó nem lehet ugyanaz, mint a felhasználónév.',
	'config-admin-password-mismatch' => 'A megadott jelszavak nem egyeznek.',
	'config-admin-email' => 'E-mail cím:',
	'config-admin-email-help' => 'Add meg az e-mail címedet, hogy más felhasználók küldhessenek e-maileket a wikin keresztül, új jelszót tudj kérni, és értesülhess a figyelőlistádon lévő lapokon történt változásokról. Üresen is hagyhatod ezt a mezőt.',
	'config-subscribe-help' => 'Ez egy alacsony forgalmú levelezőlista, ahol a kiadásokkal kapcsolatos bejelentések jelennek meg, a fontos biztonsági javításokkal együtt.
Ajánlott feliratkozni rá, és frissíteni a MediaWikit, ha új verzió jön ki.',
	'config-almost-done' => 'Már majdnem kész!
A további konfigurációt kihagyhatod, és most azonnal elindíthatod a wiki telepítését.',
	'config-optional-continue' => 'További információk megadása.',
	'config-optional-skip' => 'Épp elég volt, települjön a wiki!',
	'config-profile' => 'Felhasználói jogosultságok profilja:',
	'config-profile-wiki' => 'Hagyományos wiki',
	'config-profile-no-anon' => 'Felhasználói fiók létrehozása szükséges',
	'config-profile-fishbowl' => 'Csak engedélyezett szerkesztők',
	'config-profile-private' => 'Privát wiki',
	'config-profile-help' => "A wikik akkor működnek a legjobban, ha minél több felhasználó számára engedélyezett a szerkesztés.
A MediaWikiben könnyű ellenőrizni a legutóbbi változtatásokat,és visszaállítani a naiv vagy káros felhasználók által okozott károkat.

A MediaWiki azonban számos helyzetben hasznos lehet, és néha nem könnyű mindenkit meggyőzni a wiki előnyeiről.
Választhatsz!

'''{{int:config-profile-wiki}}kben''' bárki szerkeszthet, akár bejelentkezés nélkül is. A '''{{int:config-profile-no-anon}}''' beállítás további biztonságot nyújt, azonban elijesztheti az alkalmi szerkesztőket.

Lehetőség van arra is, hogy '''{{lc:{{int:config-profile-fishbowl}}}}''' módosíthassák a lapokat, de a nyilvánosság ekkor megtekintheti a lapokat és azok laptörténetét is. '''{{int:config-profile-private}}''' esetén csak az engedélyezett szerkesztők tekinthetik meg a lapokat, és ugyanez a csoport szerkeszthet.

Telepítés után jóval összetettebb jogosultságrendszer állítható össze, további információ a [http://www.mediawiki.org/wiki/Manual:User_rights kézikönyv kapcsolódó bejegyzésében].",
	'config-license' => 'Szerzői jog és licenc:',
	'config-license-none' => 'Nincs licencjelzés',
	'config-license-cc-by-sa' => 'Creative Commons Nevezd meg! - Így add tovább!',
	'config-license-cc-by-nc-sa' => 'Creative Commons Nevezd meg! - Ne add el! - Így add tovább!',
	'config-license-cc-0' => 'Creative Commons Zero',
	'config-license-gfdl-old' => 'GNU Szabad Dokumentációs Licenc 1.2',
	'config-license-gfdl-current' => 'GNU Szabad Dokumentációs Licenc 1.3 vagy újabb',
	'config-license-pd' => 'Közkincs',
	'config-license-cc-choose' => 'Creative Commons-licenc választása',
	'config-license-help' => "A legtöbb wiki [http://freedomdefined.org/Definition szabad licenc] alatt teszi közzé a szerkesztéseit.
Ez erősíti a közösségi tulajdon érzését, és elősegíti a hosszú távú közreműködést.
Általában szükségtelen magán- vagy vállalati wiki esetén.

Ha a Wikipédiáról szeretnél szövegeket másolni, és a Wikipédián felhasználhassák a wikidben található szöveget, akkor a '''Creative Commons Nevezd meg! - Így add tovább!''' lehetőséget válaszd.

A GNU Szabad Dokumentációs Licenc a Wikipédia korábbi licence.
Még ma is érvényes, azonban van néhány tulajdonsága, amely nehezíti az újrafelhasználást és az értelmezését.",
	'config-email-settings' => 'E-mail beállítások',
	'config-enable-email' => 'Kimenő e-mailek engedélyezése',
	'config-enable-email-help' => 'E-mailek küldéséhez [http://www.php.net/manual/en/mail.configuration.php a PHP mail beállításait] megfelelően meg kell adni.
Ha nem akarsz semmilyen e-mailes funkciót használni, itt tilthatod le őket.',
	'config-email-sender' => 'Válaszcím:',
	'config-upload-settings' => 'Képek és fájlok feltöltése',
	'config-upload-enable' => 'Fájlfeltöltés engedélyezése',
	'config-upload-deleted' => 'Törölt fájlok könyvtára:',
	'config-logo' => 'A logó URL-címe:',
	'config-instantcommons' => 'Instant Commons engedélyezése',
	'config-instantcommons-help' => 'Az [http://www.mediawiki.org/wiki/InstantCommons Instant Commons] lehetővé teszi, hogy a wikin használhassák a [http://commons.wikimedia.org/ Wikimedia Commons] oldalon található képeket, hangokat és más médiafájlokat.
A használatához a MediaWikinek internethozzáférésre van szüksége. 

A funkcióról és hogy hogyan állítható be más wikik esetén [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos a kézikönyvben] találhatsz további információkat.',
	'config-cc-again' => 'Válassz újra…',
	'config-advanced-settings' => 'Haladó beállítások',
	'config-cache-options' => 'Objektum-gyorsítótárazás beállításai:',
	'config-cache-none' => 'Nincs gyorsítótárazás (minden funkció működik, de nagyobb wiki esetében lassabb működést eredményezhet)',
	'config-cache-accel' => 'PHP-objektumok gyorsítótárazása (APC, eAccelerator, XCache or WinCache)',
	'config-cache-memcached' => 'Memcached használata (további telepítés és konfigurálás szükséges)',
	'config-memcached-servers' => 'Memcached-szerverek:',
	'config-memcached-help' => 'Azon IP-címek listája, melyeket a Memcached használhat.
Vesszővel kell elválasztani őket, és meg kell adni a portot is. Például:
 127.0.0.1:11211
 192.168.1.25:11211',
	'config-extensions' => 'Kiterjesztések',
	'config-install-step-done' => 'kész',
	'config-install-step-failed' => 'sikertelen',
	'config-install-extensions' => 'Kiterjesztések beillesztése',
	'config-install-database' => 'Adatbázis felállítása',
	'config-install-user' => 'Adatbázis-felhasználó létrehozása',
	'config-install-tables' => 'Táblák létrehozása',
	'config-install-tables-exist' => "'''Figyelmeztetés''': úgy tűnik, hogy a MediaWiki táblái már léteznek.
Létrehozás kihagyása.",
	'config-install-tables-failed' => "'''Hiba''': a tábla létrehozása nem sikerült a következő miatt: $1",
	'config-install-interwiki' => 'Alapértelmezett nyelvközihivatkozás-tábla feltöltése',
	'config-install-interwiki-list' => 'Az <code>interwiki.list</code> fájl nem található.',
	'config-install-stats' => 'Statisztika inicializálása',
	'config-install-keys' => 'Titkos kulcsok generálása',
	'config-insecure-keys' => "'''Figyelmeztetés:''' A telepítés során generált $1 {{PLURAL:$2|biztonsági kulcs|biztonsági kulcsok}} nem teljesen $1 {{PLURAL:$2|biztonságos|biztonságosak}}. Érdemes {{PLURAL:$2||őket}} manuálisan megváltoztatni.",
	'config-install-sysop' => 'Az adminisztrátor felhasználói fiókjának létrehozása',
	'config-install-subscribe-fail' => 'Nem sikerült feliratkozni a mediawiki-announce levelezőlistára',
	'config-install-mainpage' => 'Kezdőlap létrehozása az alapértelmezett tartalommal',
	'config-install-extension-tables' => 'Táblák létrehozása az engedélyezett kiterjesztésekhez',
	'config-install-mainpage-failed' => 'Nemsikerült létrehozni a kezdőlapot: $1',
	'config-install-done' => "'''Gratulálunk!'''
A MediaWiki telepítése sikeresen befejeződött.

A telepítő elkészítette a <code>LocalSettings.php</code> fájlt, amely tartalmazza az összes beállítást.

Ezt le kell tölteni, majd elhelyezni a wiki telepítési könyvtárába (az a könyvtár, ahol az index.php is található).

A letöltés automatikusan elindul. Ha mégsem indulna el, vagy megszakítottad, az alábbi linkre kattintva újra letöltheted:

$3

'''Megjegyzés''': Ha ezt most nem teszed meg, és kilépsz a telepítésből, az elkészített konfigurációs fájlt nem tudod elérni a későbbiekben.

Ha végeztél a fájl elhelyezésével, '''[$2 beléphetsz a wikibe]'''.",
	'config-download-localsettings' => 'LocalSettings.php letöltése',
	'config-help' => 'segítség',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'config-desc' => 'Le installator de MediaWiki',
	'config-title' => 'Installation de MediaWiki $1',
	'config-information' => 'Information',
	'config-localsettings-upgrade' => 'Un file <code>LocalSettings.php</code> ha essite detegite.
Pro actualisar iste installation, per favor entra le valor de <code>$wgUpgradeKey</code> in le quadro hic infra.
Iste se trova in LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Un file LocalSettings.php file ha essite detegite.
Pro actualisar iste installation, per favor executa upgrade.php.',
	'config-localsettings-key' => 'Clave de actualisation:',
	'config-localsettings-badkey' => 'Le clave que tu forniva es incorrecte',
	'config-upgrade-key-missing' => 'Un installation existente de MediaWiki ha essite detegite.
Pro actualisar iste installation, es necessari adjunger le sequente linea al fin del file LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Le file LocalSettings.php existente pare esser incomplete.
Le variabile $1 non es definite.
Per favor cambia LocalSettings.php de sorta que iste variabile es definite, e clicca "Continuar".',
	'config-localsettings-connection-error' => 'Un error esseva incontrate durante le connexion al base de datos usante le configurationes specificate in LocalSettings.php o AdminSettings.php. Per favor repara iste configurationes e tenta lo de novo.

$1',
	'config-session-error' => 'Error al comenciamento del session: $1',
	'config-session-expired' => 'Le datos de tu session pare haber expirate.
Le sessiones es configurate pro un duration de $1.
Tu pote augmentar isto per definir <code>session.gc_maxlifetime</code> in php.ini.
Reinitia le processo de installation.',
	'config-no-session' => 'Le datos de tu session es perdite!
Verifica tu php.ini e assecura te que un directorio appropriate es definite in <code>session.save_path</code>.',
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
	'config-page-existingwiki' => 'Wiki existente',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>Lege me</doclink>
* <doclink href=ReleaseNotes>Notas de iste version</doclink>
* <doclink href=Copying>Conditiones de copia</doclink>
* <doclink href=UpgradeDoc>Actualisation</doclink>',
	'config-env-good' => 'Le ambiente ha essite verificate.
Tu pote installar MediaWiki.',
	'config-env-bad' => 'Le ambiente ha essite verificate.
Tu non pote installar MediaWiki.',
	'config-env-php' => 'PHP $1 es installate.',
	'config-env-php-toolow' => 'PHP $1 es installate.
Nonobstante, MediaWiki require PHP $2 o plus recente.',
	'config-unicode-using-utf8' => 'utf8_normalize.so per Brion Vibber es usate pro le normalisation Unicode.',
	'config-unicode-using-intl' => 'Le [http://pecl.php.net/intl extension PECL intl] es usate pro le normalisation Unicode.',
	'config-unicode-pure-php-warning' => "'''Aviso''': Le [http://pecl.php.net/intl extension PECL intl] non es disponibile pro exequer le normalisation Unicode; le systema recurre al implementation lente in PHP pur.
Si tu sito ha un alte volumine de traffico, tu deberea informar te un poco super le [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalisation Unicode].",
	'config-unicode-update-warning' => "'''Aviso''': Le version installate del bibliotheca inveloppante pro normalisation Unicode usa un version ancian del bibliotheca del [http://site.icu-project.org/ projecto ICU].
Tu deberea [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualisar lo] si le uso de Unicode importa a te.",
	'config-no-db' => 'Non poteva trovar un driver appropriate pro le base de datos!',
	'config-no-db-help' => 'Tu debe installar un driver de base de datos pro PHP.
Le sequente typos de base de datos es supportate: $1.

Si tu sito usa un servitor partite (shared hosting), demanda a tu providitor de installar un driver de base de datos appropriate.
Si tu compilava PHP tu mesme, reconfigura lo con un cliente de base de datos activate, per exemplo usante <code>./configure --with-mysql</code>.
Si tu installava PHP ex un pacchetto Debian o Ubuntu, tu debe installar equalmente le modulo php5-mysql.',
	'config-no-fts3' => "'''Attention''': SQLite es compilate sin [http://sqlite.org/fts3.html modulo FTS3]; functionalitate de recerca non essera disponibile in iste back-end.",
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
	'config-xml-bad' => 'Le modulo XML de PHP es mancante.
MediaWiki require functiones de iste modulo e non functionara in iste configuration.
Si tu usa Mandrake, installa le pacchetto php-xml.',
	'config-pcre' => 'Le modulo de supporto PCRE pare esser mancante.
MediaWiki require le functiones de expression regular compatibile con Perl pro poter functionar.',
	'config-pcre-no-utf8' => "'''Fatal''': Le modulo PCRE de PHP pare haber essite compilate sin supporto de PCRE_UTF8.
MediaWiki require supporto de UTF-8 pro functionar correctemente.",
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
	'config-diff3-bad' => 'GNU diff3 non trovate.',
	'config-imagemagick' => 'ImageMagick trovate: <code>$1</code>.
Le miniaturas de imagines essera activate si tu activa le incargamento de files.',
	'config-gd' => 'Le bibliotheca graphic GD se trova integrate in le systema.
Le miniaturas de imagines essera activate si tu activa le incargamento de files.',
	'config-no-scaling' => 'Non poteva trovar le bibliotheca GD ni ImageMagick.
Le miniaturas de imagines essera disactivate.',
	'config-no-uri' => "'''Error:''' Non poteva determinar le URI actual.
Installation abortate.",
	'config-uploads-not-safe' => "'''Aviso:''' Le directorio predefinite pro files incargate <code>$1</code> es vulnerabile al execution arbitrari de scripts.
Ben que MediaWiki verifica tote le files incargate contra le menacias de securitate, il es altemente recommendate [http://www.mediawiki.org/wiki/Manual:Security#Upload_security remediar iste vulnerabilitate de securitate] ante de activar le incargamento de files.",
	'config-brokenlibxml' => 'Vostre systema ha un combination de versiones de PHP e libxml2 que es defectuose e pote causar corruption celate de datos in MediaWiki e altere applicationes web.
Actualisa a PHP 5.2.9 o plus recente e libxml2 2.7.3 o plus recente ([http://bugs.php.net/bug.php?id=45996 problema reportate presso PHP]).
Installation abortate.',
	'config-using531' => 'MediaWiki non pote esser usate con PHP $1 a causa de un defecto concernente parametros de referentia a <code>__call()</code>.
Actualisa a PHP 5.3.2 o plus recente, o retrograda a PHP 5.3.0 pro remediar isto.
Installation abortate.',
	'config-db-type' => 'Typo de base de datos:',
	'config-db-host' => 'Servitor de base de datos:',
	'config-db-host-help' => 'Si tu servitor de base de datos es in un altere servitor, entra hic le nomine o adresse IP del servitor.

Si tu usa un servitor web usate in commun, tu providitor deberea dar te le correcte nomine de servitor in su documentation.

Si tu face le installation in un servitor Windows e usa MySQL, le nomine "localhost" possibilemente non functiona como nomine de servitor. Si non, essaya "127.0.0.1", i.e. le adresse IP local.',
	'config-db-host-oracle' => 'TNS del base de datos:',
	'config-db-host-oracle-help' => 'Entra un [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm nomine Local Connect] valide; un file tnsnames.ora debe esser visibile a iste installation.<br />Si tu usa bibliothecas de cliente 10g o plus recente, tu pote anque usar le methodo de nomination [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identificar iste wiki',
	'config-db-name' => 'Nomine del base de datos:',
	'config-db-name-help' => 'Selige un nomine que identifica tu wiki.
Illo non pote continer spatios.

Si tu usa un servitor web usate in commun, tu providitor te fornira le nomine specific de un base de datos a usar, o te permitte crear un base de datos via un pannello de controlo.',
	'config-db-name-oracle' => 'Schema del base de datos:',
	'config-db-account-oracle-warn' => 'Il ha tres scenarios supportate pro le installation de Oracle como le base de datos de iste systema:

Si tu vole crear un conto del base de datos como parte del processo de installation, per favor specifica un conto con le rolo SYSDBA como le conto del base de datos pro installation, e specifica le nomine e contrasigno desirate pro le conto de accesso per web. Alteremente tu pote crear le conto de accesso per web manualmente e specificar solmente iste conto (si illo ha le permissiones requisite pro crear le objectos de schema) o specifica duo contos differente, un con privilegios de creation e un conto restringite pro accesso per web.

Un script pro crear un conto con le privilegios requisite se trova in le directorio "maintenance/oracle/" de iste installation. Non oblida que le uso de un conto restringite disactiva tote le capacitates de mantenentia in le conto predefinite.',
	'config-db-install-account' => 'Conto de usator pro installation',
	'config-db-username' => 'Nomine de usator del base de datos:',
	'config-db-password' => 'Contrasigno del base de datos:',
	'config-db-password-empty' => 'Per favor entra un contrasigno pro le nove usator del base de datos: $1.
Ben que il es possibile crear usatores sin contrasigno, isto non es secur.',
	'config-db-install-username' => 'Entra le nomine de usator que essera usate pro connecter al base de datos durante le processo de installation. Isto non es le nomine de usator del conto MediaWiki; isto es le nomine de usator pro tu base de datos.',
	'config-db-install-password' => 'Entra le contrasigno que essera usate pro connecter al base de datos durante le processo de installation. Isto non es le contrasigno del conto MediaWiki; isto es le contrasigno pro tu base de datos.',
	'config-db-install-help' => 'Entra le nomine de usator e contrasigno que essera usate pro connecter al base de datos durante le processo de installation.',
	'config-db-account-lock' => 'Usar le mesme nomine de usator e contrasigno durante le operation normal',
	'config-db-wiki-account' => 'Conto de usator pro operation normal',
	'config-db-wiki-help' => 'Entra le nomine de usator e contrasigno que essera usate pro connecter al base de datos durante le operation normal del wiki.
Si le conto non existe, e si le conto de installation possede sufficiente privilegios, iste conto de usator essera create con le minime privilegios necessari pro operar le wiki.',
	'config-db-prefix' => 'Prefixo de tabella del base de datos:',
	'config-db-prefix-help' => 'Si il es necessari usar un base de datos in commun inter multiple wikis, o inter MediaWiki e un altere application web, tu pote optar pro adder un prefixo a tote le nomines de tabella pro evitar conflictos.
Non usa spatios.

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
	'config-db-schema-help' => 'Iste schema es generalmente correcte.
Solmente cambia lo si tu es secur que es necessari.',
	'config-sqlite-dir' => 'Directorio pro le datos de SQLite:',
	'config-sqlite-dir-help' => "SQLite immagazina tote le datos in un sol file.

Le directorio que tu forni debe permitter le accesso de scriptura al servitor web durante le installation.

Illo '''non''' debe esser accessibile via web. Pro isto, nos non lo pone ubi tu files PHP es.

Le installator scribera un file <code>.htaccess</code> insimul a illo, ma si isto falli, alcuno pote ganiar accesso directe a tu base de datos.
Isto include le crude datos de usator (adresses de e-mail, contrasignos codificate) assi como versiones delite e altere datos restringite super le wiki.

Considera poner le base de datos in un loco completemente differente, per exemplo in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Spatio de tabellas predefinite:',
	'config-oracle-temp-ts' => 'Spatio de tabellas temporari:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki supporta le sequente systemas de base de datos:

$1

Si tu non vide hic infra le systema de base de datos que tu tenta usar, alora seque le instructiones ligate hic supra pro activar le supporto.',
	'config-support-mysql' => '* $1 es le systema primari pro MediaWiki e le melio supportate ([http://www.php.net/manual/en/mysql.installation.php como compilar PHP con supporto de MySQL])',
	'config-support-postgres' => '* $1 es un systema de base de datos popular e open source, alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php como compilar PHP con supporto de PostgreSQL]). Es possibile que resta alcun minor defectos non resolvite, dunque illo non es recommendate pro uso in un ambiente de production.',
	'config-support-sqlite' => '* $1 es un systema de base de datos legier que es multo ben supportate. ([http://www.php.net/manual/en/pdo.installation.php Como compilar PHP con supporto de SQLite], usa PDO)',
	'config-support-oracle' => '* $1 es un banca de datos commercial pro interprisas. ([http://www.php.net/manual/en/oci8.installation.php Como compilar PHP con supporto de OCI8])',
	'config-header-mysql' => 'Configuration de MySQL',
	'config-header-postgres' => 'Configuration de PostgreSQL',
	'config-header-sqlite' => 'Configuration de SQLite',
	'config-header-oracle' => 'Configuration de Oracle',
	'config-invalid-db-type' => 'Typo de base de datos invalide',
	'config-missing-db-name' => 'Tu debe entrar un valor pro "Nomine de base de datos"',
	'config-missing-db-host' => 'Tu debe entrar un valor pro "Host del base de datos"',
	'config-missing-db-server-oracle' => 'You must enter a value for "TNS del base de datos"',
	'config-invalid-db-server-oracle' => 'TNS de base de datos "$1" invalide.
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9), characteres de sublineamento (_) e punctos (.).',
	'config-invalid-db-name' => 'Nomine de base de datos "$1" invalide.
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9), characteres de sublineamento (_) e tractos de union (-).',
	'config-invalid-db-prefix' => 'Prefixo de base de datos "$1" invalide.
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9), characteres de sublineamento (_) e tractos de union (-).',
	'config-connection-error' => '$1.

Verifica le servitor, nomine de usator e contrasigno hic infra e reproba.',
	'config-invalid-schema' => 'Schema invalide pro MediaWiki "$1".
Usa solmente litteras ASCII (a-z, A-Z), numeros (0-9) e characteres de sublineamento (_).',
	'config-db-sys-create-oracle' => 'Le installator supporta solmente le uso de un conto SYSDBA pro le creation de un nove conto.',
	'config-db-sys-user-exists-oracle' => 'Le conto de usator "$1" ja existe. SYSDBA pote solmente esser usate pro le creation de un nove conto!',
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
	'config-can-upgrade' => "Il ha tabellas MediaWiki in iste base de datos.
Pro actualisar los a MediaWiki $1, clicca super '''Continuar'''.",
	'config-upgrade-done' => "Actualisation complete.

Tu pote ora [$1 comenciar a usar tu wiki].

Si tu vole regenerar tu file <code>LocalSettings.php</code>, clicca super le button hic infra.
Isto '''non es recommendate''' si tu non ha problemas con tu wiki.",
	'config-upgrade-done-no-regenerate' => 'Actualisation complete.

Tu pote ora [$1 comenciar a usar tu wiki].',
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
	'config-mysql-charset' => 'Codification de characteres in le base de datos:',
	'config-mysql-binary' => 'Binari',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "In '''modo binari''', MediaWiki immagazina le texto UTF-8 in le base de datos in campos binari.
Isto es plus efficiente que le modo UTF-8 de MySQL, e permitte usar le rango complete de characteres Unicode.

In '''modo UTF-8''', MySQL cognoscera le codification de characteres usate pro tu dats, e pote presentar e converter lo appropriatemente, ma illo non permittera immagazinar characteres supra le [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilingue Basic].",
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
	'config-ns-conflict' => 'Le spatio de nomines specificate "<nowiki>$1</nowiki>" conflige con un spatio de nomines predefinite de MediaWiki.
Specifica un altere spatio de nomines pro le projecto.',
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
	'config-admin-email-help' => 'Entra un adresse de e-mail hic pro permitter le reception de e-mail ab altere usatores del wiki, pro poter reinitialisar tu contrasigno, e pro reciper notification de cambios a paginas in tu observatorio. Iste campo pote esser lassate vacue.',
	'config-admin-error-user' => 'Error interne durante le creation de un administrator con le nomine "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Error interne durante le definition de un contrasigno pro le administrator "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Tu ha entrate un adresse de e-mail invalide',
	'config-subscribe' => 'Subscribe al [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce lista de diffusion pro annuncios de nove versiones].',
	'config-subscribe-help' => 'Isto es un lista de e-mail a basse volumine pro annuncios de nove versiones, includente importante annuncios de securitate.
Tu deberea subscriber a illo e actualisar tu installation de MediaWiki quando nove versiones es editate.',
	'config-almost-done' => 'Tu ha quasi finite!
Tu pote ora saltar le configuration remanente e installar le wiki immediatemente.',
	'config-optional-continue' => 'Pone me plus questiones.',
	'config-optional-skip' => 'Isto me es jam tediose. Simplemente installa le wiki.',
	'config-profile' => 'Profilo de derectos de usator:',
	'config-profile-wiki' => 'Wiki traditional',
	'config-profile-no-anon' => 'Creation de conto obligatori',
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
	'config-license-cc-by-sa' => 'Creative Commons Attribution Share Alike',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-cc-0' => 'Creative Commons Zero',
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
Pro poter facer isto, MediaWiki require accesso a Internet. 

Pro plus information super iste function, includente instructiones super como configurar lo pro wikis altere que Wikimedia Commons, consulta [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos le manual].',
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
Debe specificar un per linea e specificar le porto a usar. Per exemplo:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Tu seligeva Memcached como typo de cache ma non specificava alcun servitores',
	'config-memcache-badip' => 'Tu ha entrate un adresse IP invalide pro Memcached: $1',
	'config-memcache-noport' => 'Tu non specificava un porto a usar pro le servitor Memcached: $1.
Si tu non cognosce le porto, le standard es 11211',
	'config-memcache-badport' => 'Le numeros de porto de Memcached debe esser inter $1 e $2',
	'config-extensions' => 'Extensiones',
	'config-extensions-help' => 'Le extensiones listate hic supra esseva detegite in tu directorio <code>./extensions</code>.

Istes pote requirer additional configuration, ma tu pote activar los ora.',
	'config-install-alreadydone' => "'''Aviso:''' Il pare que tu ha jam installate MediaWiki e tenta installar lo de novo.
Per favor continua al proxime pagina.",
	'config-install-begin' => 'Un clic sur "{{int:config-continue}}" comencia le installation de MediaWiki.
Pro facer alterationes, clicca sur "Retro".',
	'config-install-step-done' => 'finite',
	'config-install-step-failed' => 'fallite',
	'config-install-extensions' => 'Include le extensiones',
	'config-install-database' => 'Configura le base de datos',
	'config-install-pg-schema-not-exist' => 'Iste schema de PostgreSQL non existe',
	'config-install-pg-schema-failed' => 'Le creation del tabellas falleva.
Assecura te que le usator "$1" pote scriber in le schema "$2".',
	'config-install-pg-commit' => 'Committer cambiamentos',
	'config-install-pg-plpgsql' => 'Verifica le presentia del linguage PL/pgSQL',
	'config-pg-no-plpgsql' => 'Es necessari installar le linguage PL/pgSQL in le base de datos $1',
	'config-pg-no-create-privs' => 'Le conto que tu specificava pro installation non ha sufficiente privilegios pro crear un conto.',
	'config-install-user' => 'Crea usator pro base de datos',
	'config-install-user-alreadyexists' => 'Le usator "$1" ja existe',
	'config-install-user-create-failed' => 'Le creation del usator "$1" ha fallite: $2',
	'config-install-user-grant-failed' => 'Le concession de permission al usator "$1" falleva: $2',
	'config-install-tables' => 'Crea tabellas',
	'config-install-tables-exist' => "'''Aviso''': Il pare que le tabellas de MediaWiki jam existe.
Le creation es saltate.",
	'config-install-tables-failed' => "'''Error''': Le creation del tabellas falleva con le sequente error: $1",
	'config-install-interwiki' => 'Plena le tabella interwiki predefinite',
	'config-install-interwiki-list' => 'Non poteva trovar le file <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Aviso''': Le tabella interwiki pare jam haber entratas.
Le lista predefinite es saltate.",
	'config-install-stats' => 'Initialisation del statisticas',
	'config-install-keys' => 'Genera clave secrete',
	'config-install-sysop' => 'Crea conto de usator pro administrator',
	'config-install-subscribe-fail' => 'Impossibile subscriber a mediawiki-announce',
	'config-install-mainpage' => 'Crea pagina principal con contento predefinite',
	'config-install-extension-tables' => 'Creation de tabellas pro le extensiones activate',
	'config-install-mainpage-failed' => 'Non poteva inserer le pagina principal: $1',
	'config-install-done' => "'''Felicitationes!'''
Tu ha installate MediaWiki con successo.

Le installator ha generate un file <code>LocalSettings.php</code>.
Iste contine tote le configuration.

Es necessari discargar lo e poner lo in le base del installation wiki (le mesme directorio que index.php).
Le discargamento debe haber comenciate automaticamente.

Si le discargamento non ha comenciate, o si illo esseva cancellate, es possibile recomenciar le discargamento con un clic sur le ligamine sequente:

$3

'''Nota''': Si tu non discarga iste file de configuration ora, illo non essera disponibile plus tarde.

Post facer isto, tu pote '''[$2 entrar in tu wiki]'''.",
	'config-download-localsettings' => 'Discargar LocalSettings.php',
	'config-help' => 'adjuta',
);

/** Indonesian (Bahasa Indonesia)
 * @author Farras
 * @author IvanLanin
 * @author Reedy
 */
$messages['id'] = array(
	'config-desc' => 'Penginstal untuk MediaWiki',
	'config-title' => 'Instalasi MediaWiki $1',
	'config-information' => 'Informasi',
	'config-localsettings-upgrade' => 'Berkas <code>LocalSettings.php</code> sudah ada.
Untuk memutakhirkan instalasi ini, masukkan nilai <code>$wgUpgradeKey</code> dalam kotak yang tersedia di bawah ini.
Anda dapat menemukan nilai tersebut dalam LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Berkas LocalSettings.php terdeteksi.
Untuk meningkatkan versi, sertakan pilihan --upgrade=yes.',
	'config-localsettings-key' => 'Kunci pemutakhiran:',
	'config-localsettings-badkey' => 'Kunci yang Anda berikan tidak benar',
	'config-upgrade-key-missing' => 'Suatu instalasi MediaWiki telah terdeteksi.
Untuk memutakhirkan instalasi ini, silakan masukkan baris berikut di bagian bawah LocalSettings.php Anda:

$1',
	'config-localsettings-incomplete' => 'LocalSettings.php yang ada tampaknya tidak lengkap.
Variabel $1 tidak diatur.
Silakan ubah LocalSettings.php untuk mengatur variabel ini dan klik "Lanjutkan".',
	'config-localsettings-connection-error' => 'Timbul galat saat menghubungkan ke basis data dengan menggunakan setelan yang ditentukan di LocalSettings.php atau AdminSettings.php. Harap perbaiki setelan ini dan coba lagi.

$1',
	'config-session-error' => 'Kesalahan sesi mulai: $1',
	'config-session-expired' => 'Data sesi tampaknya telah kedaluwarsa.
Sesi dikonfigurasi untuk berlaku selama $1.
Anda dapat menaikkannya dengan menetapkan <code>session.gc_maxlifetime</code> dalam php.ini.
Ulangi proses instalasi.',
	'config-no-session' => 'Data sesi Anda hilang!
Cek php.ini Anda dan pastikan bahwa <code>session.save_path</code> diatur ke direktori yang sesuai.',
	'config-your-language' => 'Bahasa Anda:',
	'config-your-language-help' => 'Pilih bahasa yang akan digunakan selama proses instalasi.',
	'config-wiki-language' => 'Bahasa wiki:',
	'config-wiki-language-help' => 'Pilih bahasa yang akan digunakan tulisan-tulisan wiki.',
	'config-back' => '← Kembali',
	'config-continue' => 'Lanjut →',
	'config-page-language' => 'Bahasa',
	'config-page-welcome' => 'Selamat datang di MediaWiki',
	'config-page-dbconnect' => 'Hubungkan ke basis data',
	'config-page-upgrade' => 'Perbarui instalasi yang ada',
	'config-page-dbsettings' => 'Pengaturan basis data',
	'config-page-name' => 'Nama',
	'config-page-options' => 'Pilihan',
	'config-page-install' => 'Instal',
	'config-page-complete' => 'Selesai!',
	'config-page-restart' => 'Ulangi instalasi',
	'config-page-readme' => 'Baca saya',
	'config-page-releasenotes' => 'Catatan pelepasan',
	'config-page-copying' => 'Menyalin',
	'config-page-upgradedoc' => 'Memerbarui',
	'config-page-existingwiki' => 'Wiki yang ada',
	'config-help-restart' => 'Apakah Anda ingin menghapus semua data tersimpan yang telah Anda masukkan dan mengulang proses instalasi?',
	'config-restart' => 'Ya, nyalakan ulang',
	'config-welcome' => '=== Pengecekan lingkungan ===
Pengecekan dasar dilakukan untuk melihat apakah lingkungan ini memadai untuk instalasi MediaWiki.
Anda harus memberikan hasil pemeriksaan ini jika Anda memerlukan bantuan selama instalasi.',
	'config-copyright' => "=== Hak cipta dan persyaratan ===

\$1

Program ini adalah perangkat lunak bebas; Anda dapat mendistribusikan dan/atau memodifikasi di bawah persyaratan GNU General Public License seperti yang diterbitkan oleh Free Software Foundation; baik versi 2 lisensi, atau (sesuai pilihan Anda) versi yang lebih baru.

Program ini didistribusikan dengan harapan bahwa itu akan berguna, tetapi '''tanpa jaminan apa pun'''; bahkan tanpa jaminan tersirat untuk '''dapat diperjualbelikan ''' atau '''sesuai untuk tujuan tertentu'''.
Lihat GNU General Public License untuk lebih jelasnya.

Anda seharusnya telah menerima <doclink href=\"Copying\">salinan dari GNU General Public License</doclink> bersama dengan program ini; jika tidak, kirimkan surat untuk Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. atau [http://www.gnu.org/copyleft/gpl.html baca versi daring].",
	'config-sidebar' => '* [http://www.mediawiki.org Halaman utama MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Panduan Pengguna]
* [http://www.mediawiki.org/wiki/Manual:Contents Panduan Pengurus]
* [http://www.mediawiki.org/wiki/Manual:FAQ Pertanyaan yang Sering Diajukan]',
	'config-env-good' => 'Kondisi telah diperiksa.
Anda dapat menginstal MediaWiki.',
	'config-env-bad' => 'Kondisi telah diperiksa.
Anda tidak dapat menginstal MediaWiki.',
	'config-env-php' => 'PHP $1 diinstal.',
	'config-unicode-using-utf8' => 'Menggunakan utf8_normalize.so Brion Vibber untuk normalisasi Unicode.',
	'config-unicode-using-intl' => 'Menggunakan [http://pecl.php.net/intl ekstensi PECL intl] untuk normalisasi Unicode.',
	'config-unicode-pure-php-warning' => "'''Peringatan''': [http://pecl.php.net/intl Ekstensi intl PECL] untuk menangani normalisasi Unicode tidak tersedia, kembali menggunakan implementasi murni PHP yang lambat. 
Jika Anda menjalankan situs berlalu lintas tinggi, Anda harus sedikit membaca [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalisasi Unicode].",
	'config-unicode-update-warning' => "'''Peringatan''': Versi terinstal dari pembungkus normalisasi Unicode menggunakan versi lama pustaka [http://site.icu-project.org/ proyek ICU].
Anda harus [http://www.mediawiki.org/wiki/Unicode_normalization_considerations memutakhirkannya] jika Anda ingin menggunakan Unicode.",
	'config-no-db' => 'Tidak dapat menemukan pengandar basis data yang sesuai!',
	'config-no-db-help' => 'Anda perlu menginstal pengandar basis data untuk PHP.
Jenis basis data yang didukung: $1.

Jika Anda menggunakan inang bersama, mintalah penyedia inang Anda untuk menginstal pengandar basis data yang cocok.
Jika Anda mengompilasi sendiri PHP, ubahlah konfigurasinya dengan mengaktifkan klien basis data, misalnya menggunakan <code>./configure --with-mysql</code>.
Jika Anda menginstal PHP dari paket Debian atau Ubuntu, maka Anda juga perlu menginstal modul php5-mysql.',
	'config-no-fts3' => "'''Peringatan''': SQLite dikompilasi tanpa [http://sqlite.org/fts3.html modul FTS3], fitur pencarian tidak akan tersedia pada konfigurasi ini.",
	'config-register-globals' => "'''Peringatan: Opsi <code>[http://php.net/register_globals register_globals]</code> PHP diaktifkan.'''
'''Nonaktifkan kalau bisa.'''
MediaWiki akan bekerja, tetapi server Anda memiliki potensi kerentanan keamanan.",
	'config-magic-quotes-runtime' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] aktif!'''
Pilihan ini dapat merusak masukan data secara tidak terduga.
Anda tidak dapat menginstal atau menggunakan MediaWiki kecuali pilihan ini dinonaktifkan.",
	'config-magic-quotes-sybase' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic_quotes_sybase magic_quotes_sybase] aktif!'''
Pilihan ini dapat merusak masukan data secara tidak terduga.
Anda tidak dapat menginstal atau menggunakan MediaWiki kecuali pilihan ini dinonaktifkan.",
	'config-mbstring' => "'''Fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] aktif!'' '
Pilihan ini dapat menyebabkan kesalahan dan kerusakan data yang tidak terduga.
Anda tidak dapat menginstal atau menggunakan MediaWiki kecuali pilihan ini dinonaktifkan.",
	'config-ze1' => "'''Fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] aktif!'''
Pilihan ini dapat menyebabkan bug yang mengerikan pada MediaWiki.
Anda tidak dapat menginstal atau menggunakan MediaWiki kecuali pilihan ini dinonaktifkan.",
	'config-safe-mode' => "''' Peringatan:''' [http://www.php.net/features.safe-mode Mode aman] PHP aktif.
Hal ini akan menyebabkan masalah, terutama jika menggunakan pengunggahan berkas dan dukungan <code>math</code>.",
	'config-xml-bad' => 'Modul XML PHP hilang.
MediaWiki membutuhkan fungsi dalam modul ini dan tidak akan bekerja dalam konfigurasi ini.
Jika Anda menggunakan Mandrake, instal paket php-xml.',
	'config-pcre' => 'Modul pendukung PCRE tampaknya hilang.
MediaWiki memerlukan fungsi persamaan reguler kompatibel Perl untuk bekerja.',
	'config-pcre-no-utf8' => "'''Fatal''': Modul PCRE PHP tampaknya dikompilasi tanpa dukungan PCRE_UTF8.
MediaWiki memerlukan dukungan UTF-8 untuk berfungsi dengan benar.",
	'config-memory-raised' => '<code>memory_limit</code> PHP adalah $1, dinaikkan ke $2.',
	'config-memory-bad' => "'''Peringatan:''' <code>memory_limit</code> PHP adalah $1.
Ini terlalu rendah.
Instalasi terancam gagal!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] telah diinstal',
	'config-apc' => '[http://www.php.net/apc APC] telah diinstal',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] telah diinstal',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] telah diinstal',
	'config-no-cache' => "'''Peringatan:''' Tidak dapat menemukan [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache], atau [http://www.iis.net/download/WinCacheForPhp WinCache]. Pinggahan obyek tidak dinonaktifkan.",
	'config-diff3-bad' => 'GNU diff3 tidak ditemukan.',
	'config-imagemagick' => 'ImageMagick ditemukan: <code>$1</code> . 
Pembuatan gambar mini akan diaktifkan jika Anda mengaktifkan pengunggahan.',
	'config-gd' => 'Pustaka grafis GD terpasang ditemukan.
Pembuatan gambar mini akan diaktifkan jika Anda mengaktifkan pengunggahan.',
	'config-no-scaling' => 'Pustaka GD atau ImageMagick tidak ditemukan.
Pembuatan gambar mini dinonaktifkan.',
	'config-no-uri' => "'''Kesalahan:''' URI saat ini tidak dapat ditentukan.
Instalasi dibatalkan.",
	'config-uploads-not-safe' => "'''Peringatan:''' Direktori bawaan pengunggahan <code>$1</code> Anda rentan terhadap eksekusi skrip yang sewenang-wenang.
Meskipun MediaWiki memeriksa semua berkas unggahan untuk ancaman keamanan, sangat dianjurkan untuk [http://www.mediawiki.org/wiki/Manual:Security#Upload_security menutup kerentanan keamanan ini] sebelum mengaktifkan pengunggahan.",
	'config-brokenlibxml' => 'Sistem Anda memiliki kombinasi versi PHP dan libxml2 yang memiliki bug dan dapat menyebabkan kerusakan data tersembunyi pada MediaWiki dan aplikasi web lain.
Mutakhirkan ke PHP 5.2.9 atau yang lebih baru dan libxml2 2.7.3 atau yang lebih baru ([http://bugs.php.net/bug.php?id=45996 arsip bug di PHP]).
Instalasi dibatalkan.',
	'config-using531' => 'PHP $1 tidak kompatibel dengan MediaWiki karena bug yang melibatkan parameter referensi untuk <code>__call()</code> . 
Tingkatkan ke PHP 5.3.2 atau yang lebih baru, atau turunkan ke PHP versi 5.3.0 untuk memperbaiki ini ([http://bugs.php.net/bug.php?id=50394 arsip bug di PHP]). 
Instalasi dibatalkan.',
	'config-db-type' => 'Jenis basis data:',
	'config-db-host' => 'Inang basis data:',
	'config-db-host-help' => 'Jika server basis data Anda berada di server yang berbeda, masukkan nama inang atau alamat IP di sini. 

Jika Anda menggunakan inang web bersama, penyedia inang Anda harus memberikan nama inang yang benar di dokumentasi mereka. 

Jika Anda menginstal pada server Windows dan menggunakan MySQL, "localhost" mungkin tidak dapat digunakan sebagai nama server. Jika demikian, coba "127.0.0.1" untuk alamat IP lokal.',
	'config-db-host-oracle' => 'TNS basis data:',
	'config-db-host-oracle-help' => 'Masukkan [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Local Connect Name] yang sah; berkas tnsnames.ora harus dapat diakses oleh instalasi ini.<br />Jika Anda menggunakan pustaka klien 10g atau lebih baru, Anda juga dapat menggunakan metode penamaan [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identifikasi wiki ini',
	'config-db-name' => 'Nama basis data:',
	'config-db-name-help' => 'Pilih nama yang mengidentifikasikan wiki Anda. 
Nama tersebut tidak boleh mengandung spasi. 

Jika Anda menggunakan inang web bersama, penyedia inang Anda dapat memberikan Anda nama basis data khusus untuk digunakan atau mengizinkan Anda membuat basis data melalui panel kontrol.',
	'config-db-name-oracle' => 'Skema basis data:',
	'config-db-install-account' => 'Akun pengguna untuk instalasi',
	'config-db-username' => 'Nama pengguna basis data:',
	'config-db-password' => 'Kata sandi basis data:',
	'config-db-install-username' => 'Masukkan nama pengguna yang akan digunakan untuk terhubung ke basis data selama proses instalasi. 
Ini bukan nama pengguna akun MediaWiki, melainkan nama pengguna untuk basis data Anda.',
	'config-db-install-password' => 'Masukkan sandi yang akan digunakan untuk terhubung ke basis data selama proses instalasi. 
Ini bukan sandi untuk akun MediaWiki, melainkan sandi untuk basis data Anda.',
	'config-db-install-help' => 'Masukkan nama pengguna dan sandi yang akan digunakan untuk terhubung ke basis data pada saat proses instalasi.',
	'config-db-account-lock' => 'Gunakan nama pengguna dan kata sandi yang sama selama operasi normal',
	'config-db-wiki-account' => 'Akun pengguna untuk operasi normal',
	'config-db-wiki-help' => 'Masukkan nama pengguna dan sandi yang akan digunakan untuk terhubung ke basis data wiki selama operasi normal. 
Jika akun tidak ada, akun instalasi memiliki hak yang memadai, akun pengguna ini akan dibuat dengan hak akses minimum yang diperlukan untuk mengoperasikan wiki.',
	'config-db-prefix' => 'Prefiks tabel basis data:',
	'config-db-prefix-help' => 'Jika Anda perlu berbagi satu basis data di antara beberapa wiki, atau antara MediaWiki dan aplikasi web lain, Anda dapat memilih untuk menambahkan prefiks terhadap semua nama tabel demi menghindari konflik.
Jangan gunakan spasi.

Prefiks ini biasanya dibiarkan kosong.',
	'config-db-charset' => 'Set karakter basis data',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 biner',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'UTF-8 yang kompatibel balik dengan MySQL 4.0',
	'config-charset-help' => "'''Peringatan:''' Jika Anda menggunakan '''UTF-8 kompatibel balik''' pada MySQL 4.1+, dan kemudian mencadangkan basis data dengan <code>mysqldump</code>, proses itu mungkin menghancurkan semua karakter non-ASCII dan merusak cadangan Anda tanpa dapat dikembalikan! 

Dalam '''modus biner''', MediaWiki menyimpan teks UTF-8 ke basis data dalam bidang biner. 
Ini lebih efisien dibandingkan modus UTF-8 MySQL dan memungkinkan Anda untuk menggunakan berbagai karakter Unicode.
Dalam '''modus UTF-8''', MySQL akan tahu apa set karakter data anda dan dapat menyajikan dan mengubahnya denga tepat, namun tidak akan mengizinkan Anda menyimpan karakter di atas [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-mysql-old' => 'MySQL $1 atau versi terbaru diperlukan, Anda menggunakan $2.',
	'config-db-port' => 'Porta basis data:',
	'config-db-schema' => 'Skema untuk MediaWiki',
	'config-db-schema-help' => 'Skema di atas biasanya benar. 
Ubah hanya jika Anda tahu Anda perlu mengubahnya.',
	'config-sqlite-dir' => 'Direktori data SQLite:',
	'config-sqlite-dir-help' => "SQLite menyimpan semua data dalam satu berkas.

Direktori yang Anda berikan harus dapat ditulisi oleh server web selama instalasi.

Direktori itu '''tidak''' boleh dapat diakses melalui web, inilah sebabnya kami tidak menempatkannya bersama dengan berkas PHP lain.

Penginstal akan membuat berkas <code>.htaccess</code> bersamaan dengan itu, tetapi jika gagal, orang dapat memperoleh akses ke basis data mentah Anda.
Itu termasuk data mentah pengguna (alamat surel, hash sandi) serta revisi yang dihapus dan data lainnya yang dibatasi pada wiki.

Pertimbangkan untuk menempatkan basis data di tempat lain, misalnya di <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Tablespace bawaan:',
	'config-oracle-temp-ts' => 'Tablespace sementara:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki mendukung sistem basis data berikut: 

$1

Jika Anda tidak melihat sistem basis data yang Anda gunakan tercantum di bawah ini, ikuti petunjuk terkait di atas untuk mengaktifkan dukungan.',
	'config-support-mysql' => '* $1 adalah target utama MediaWiki dan memiliki dukungan terbaik ([http://www.php.net/manual/en/mysql.installation.php cara mengompilasi PHP dengan dukungan MySQL])',
	'config-support-postgres' => '* $1 adalah sistem basis data sumber terbuka populer sebagai alternatif untuk MySQL ([http://www.php.net/manual/en/pgsql.installation.php cara mengompilasi PHP dengan dukungan PostgreSQL])',
	'config-support-sqlite' => '* $1 adalah sistem basis data yang ringan yang sangat baik dukungannya. ([http://www.php.net/manual/en/pdo.installation.php cara mengompilasi PHP dengan dukungan SQLite], menggunakan PDO)',
	'config-support-oracle' => '* $1 adalah basis data komersial untuka perusahaan. ([http://www.php.net/manual/en/oci8.installation.php cara mengompilasi PHP dengan dukungan OCI8])',
	'config-header-mysql' => 'Pengaturan MySQL',
	'config-header-postgres' => 'Pengaturan PostgreSQL',
	'config-header-sqlite' => 'Pengaturan SQLite',
	'config-header-oracle' => 'Pengaturan Oracle',
	'config-invalid-db-type' => 'Jenis basis data tidak sah',
	'config-missing-db-name' => 'Anda harus memasukkan nilai untuk "Nama basis data"',
	'config-missing-db-host' => 'Anda harus memasukkan nilai untuk "Inang basis data"',
	'config-missing-db-server-oracle' => 'Anda harus memasukkan nilai untuk "TNS basis data"',
	'config-invalid-db-server-oracle' => 'TNS basis data "$1" tidak sah.
Gunakan hanya huruf ASCII (a-z, A-Z), angka (0-9), garis bawah (_), dan titik (.).',
	'config-invalid-db-name' => 'Nama basis data "$1" tidak sah.
Gunakan hanya huruf ASCII (a-z, A-Z), angka (0-9), garis bawah (_), dan tanda hubung (-).',
	'config-invalid-db-prefix' => 'Prefiks basis data "$1" tidak sah.
Gunakan hanya huruf ASCII (a-z, A-Z), angka (0-9), garis bawah (_), dan tanda hubung (-).',
	'config-connection-error' => '$1.

Periksa nama inang, pengguna, dan sandi di bawah ini dan coba lagi.',
	'config-invalid-schema' => 'Skema MediaWiki "$1" tidak sah.
Gunakan hanya huruf ASCII (a-z, A-Z), angka (0-9), dan garis bawah (_).',
	'config-postgres-old' => 'PostgreSQL $1 atau versi terbaru diperlukan, Anda menggunakan $2.',
	'config-sqlite-name-help' => 'Pilih nama yang mengidentifikasi wiki Anda. 
Jangan gunakan spasi atau tanda hubung. 
Nama ini akan digunakan untuk nama berkas data SQLite.',
	'config-sqlite-parent-unwritable-group' => 'Tidak dapat membuat direktori data <code><nowiki>$1</nowiki></code>, karena direktori induk <code><nowiki>$2</nowiki></code> tidak bisa ditulisi oleh server web.

Penginstal telah menentukan pengguna yang menjalankan server web Anda.
Buat direktori <code><nowiki>$3</nowiki></code> menjadi dapat ditulisi olehnya.
Pada sistem Unix/Linux lakukan hal berikut:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Tidak dapat membuat direktori data <code><nowiki>$1</nowiki></code>, karena direktori induk <code><nowiki>$2</nowiki></code> tidak bisa ditulisi oleh server web.

Penginstal tidak dapat menentukan pengguna yang menjalankan server web Anda.
Buat direktori <code><nowiki>$3</nowiki></code> menjadi dapat ditulisi oleh semua orang.
Pada sistem Unix/Linux lakukan hal berikut:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Kesalahan saat membuat direktori data "$1". 
Periksa lokasi dan coba lagi.',
	'config-sqlite-dir-unwritable' => 'Tidak dapat menulisi direktori "$1".
Ubah hak akses direktori sehingga server web dapat menulis ke sana, dan coba lagi.',
	'config-sqlite-connection-error' => '$1.

Periksa direktori data dan nama basis data di bawah dan coba lagi.',
	'config-sqlite-readonly' => 'Berkas <code>$1</code> tidak dapat ditulisi.',
	'config-sqlite-cant-create-db' => 'Tidak dapat membuat berkas basis data <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP tidak memiliki dukungan FTS3, tabel dituruntarafkan.',
	'config-can-upgrade' => "Ada tabel MediaWiki di basis dataini.
Untuk memperbaruinya ke MediaWiki $1, klik '''Lanjut'''.",
	'config-upgrade-done' => "Pemutakhiran selesai.

Anda sekarang dapat [$1 mulai menggunakan wiki Anda].

Jika Anda ingin membuat ulang berkas <code>LocalSettings.php</code>, klik tombol di bawah ini.
Tindakan ini '''tidak dianjurkan''' kecuali jika Anda mengalami masalah dengan wiki Anda.",
	'config-upgrade-done-no-regenerate' => 'Pemutakhiran selesai.

Anda sekarang dapat [$1 mulai menggunakan wiki Anda].',
	'config-regenerate' => 'Regenerasi LocalSettings.php →',
	'config-show-table-status' => 'Kueri SHOW TABLE STATUS gagal!',
	'config-unknown-collation' => "'''Peringatan:''' basis data menggunakan kolasi yang tidak dikenal.",
	'config-db-web-account' => 'Akun basis data untuk akses web',
	'config-db-web-help' => 'Masukkan nama pengguna dan sandi yang akan digunakan server web untuk terhubung ke server basis data saat operasi normal wiki.',
	'config-db-web-account-same' => 'Gunakan akun yang sama seperti untuk instalasi',
	'config-db-web-create' => 'Buat akun jika belum ada',
	'config-db-web-no-create-privs' => 'Akun Anda berikan untuk instalasi tidak memiliki hak yang cukup untuk membuat akun. 
Akun yang Anda berikan harus sudah ada.',
	'config-mysql-engine' => 'Mesin penyimpanan:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' hampir selalu merupakan pilihan terbaik karena memiliki dukungan konkurensi yang baik.

'''MyISAM''' mungkin lebih cepat dalam instalasi pengguna-tunggal atau hanya-baca.
Basis data MyISAM cenderung lebih sering rusak daripada basis data InnoDB.",
	'config-mysql-charset' => 'Set karakter basis data:',
	'config-mysql-binary' => 'Biner',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Dalam '''modus biner''', MediaWiki menyimpan teks UTF-8 untuk basis data dalam bidang biner.
Ini lebih efisien daripada modus UTF-8 MySQL dan memungkinkan Anda untuk menggunakan ragam penuh karakter Unicode.

Dalam '''modus UTF-8''', MySQL akan tahu apa set karakter data dan dapat menampilkan dan mengubahnya sesuai keperluan, tetapi tidak akan mengizinkan Anda menyimpan karakter di atas [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Basic Multilingual Plane].",
	'config-site-name' => 'Nama wiki:',
	'config-site-name-help' => 'Ini akan muncul di bilah judul peramban dan di berbagai tempat lainnya.',
	'config-site-name-blank' => 'Masukkan nama situs.',
	'config-project-namespace' => 'Ruang nama proyek:',
	'config-ns-generic' => 'Proyek',
	'config-ns-site-name' => 'Sama seperti nama wiki: $1',
	'config-ns-other' => 'Lainnya (sebutkan)',
	'config-ns-other-default' => 'MyWiki',
	'config-project-namespace-help' => 'Mengikuti contoh Wikipedia, banyak wiki menyimpan halaman kebijakan mereka terpisah dari halaman konten mereka, dalam "\'\'\'ruang nama proyek\'\'\'". 
Semua judul halaman dalam ruang nama ini diawali dengan prefiks tertentu yang dapat Anda tetapkan di sini. 
Biasanya, prefiks ini berasal dari nama wiki, tetapi tidak dapat berisi karakter tanda baca seperti "#" atau ":".',
	'config-ns-invalid' => 'Ruang nama "<nowiki>$1</nowiki>" yang ditentukan tidak sah. 
Berikan ruang nama proyek lain.',
	'config-admin-box' => 'Akun pengurus',
	'config-admin-name' => 'Nama Anda:',
	'config-admin-password' => 'Kata sandi:',
	'config-admin-password-confirm' => 'Kata sandi lagi:',
	'config-admin-help' => 'Masukkan nama pengguna pilihan Anda di sini, misalnya "Udin Wiki".
Ini adalah nama yang akan Anda gunakan untuk masuk ke wiki.',
	'config-admin-name-blank' => 'Masukkan nama pengguna pengurus.',
	'config-admin-name-invalid' => 'Nama pengguna "<nowiki>$1</nowiki>" yang diberikan tidak sah. 
Berikan nama pengguna lain.',
	'config-admin-password-blank' => 'Masukkan kata sandi untuk akun pengurus.',
	'config-admin-password-same' => 'Kata sandi harus tidak sama seperti nama pengguna.',
	'config-admin-password-mismatch' => 'Dua kata sandi yang Anda masukkan tidak cocok.',
	'config-admin-email' => 'Alamat surel:',
	'config-admin-email-help' => 'Masukkan alamat surel untuk memungkinkan Anda menerima surel dari pengguna lain, menyetel ulang sandi, dan mendapat pemberitahuan tentang perubahan atas daftar pantauan Anda.',
	'config-admin-error-user' => 'Kesalahan internal saat membuat admin dengan nama "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Kesalahan internal saat membuat sandi untuk admin "<nowiki>$1</nowiki>":<pre>$2</pre>',
	'config-admin-error-bademail' => 'Anda memasukkan alamat surel yang tidak sah',
	'config-subscribe' => 'Berlangganan ke [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce milis pengumuman rilis].',
	'config-subscribe-help' => 'Ini adalah milis bervolume rendah yang digunakan untuk pengumuman rilis, termasuk pengumuman keamanan penting.
Anda sebaiknya berlangganan dan memperbarui instalasi MediaWiki saat versi baru keluar.',
	'config-almost-done' => 'Anda hampir selesai! 
Anda sekarang dapat melewati sisa konfigurasi dan menginstal wiki sekarang.',
	'config-optional-continue' => 'Berikan saya pertanyaan lagi.',
	'config-optional-skip' => 'Saya sudah bosan, instal saja wikinya.',
	'config-profile' => 'Profil hak pengguna:',
	'config-profile-wiki' => 'Wiki tradisional',
	'config-profile-no-anon' => 'Pembuatan akun diperlukan',
	'config-profile-fishbowl' => 'Khusus penyunting terdaftar',
	'config-profile-private' => 'Wiki pribadi',
	'config-profile-help' => "Wiki paling baik bekerja jika Anda membiarkan sebanyak mungkin orang untuk menyunting.
Dengan MediaWiki, sangat mudah meninjau perubahan terbaru dan mengembalikan kerusakan yang dilakukan oleh pengguna naif atau berbahaya.

Namun, berbagai kegunaan lain dari MediaWiki telah ditemukan, dan kadang tidak mudah untuk meyakinkan semua orang manfaat dari cara wiki.
Jadi, Anda yang menentukan.

'''{{int:config-profil-wiki}}''' memungkinkan setiap orang untuk menyunting, bahkan tanpa masuk.
'''{{int:config-profil-no-anon}}''' menyediakan akuntabilitas tambahan, tetapi dapat mencegah kontributor biasa.

'''{{int:config-profil-fishbowl}}''' memungkinkan pengguna yang disetujui untuk menyunting, tetapi publik dapat melihat halaman, termasuk riwayatnya.
'''{{int:config-profil-private}}''' hanya memungkinkan pengguna yang disetujui untuk melihat dan menyunting halaman.

Konfigurasi hak pengguna yang lebih kompleks tersedia setelah instalasi. Lihat [http://www.mediawiki.org/wiki/Manual:User_rights/id entri manual terkait].",
	'config-license' => 'Hak cipta dan lisensi:',
	'config-license-none' => 'Tidak ada lisensi',
	'config-license-cc-by-sa' => 'Creative Commons Atribusi Berbagi Serupa',
	'config-license-cc-by-nc-sa' => 'Creative Commons Atribusi Non-Komersial Berbagi Serupa',
	'config-license-gfdl-old' => 'Lisensi Dokumentasi Bebas GNU 1.2',
	'config-license-gfdl-current' => 'Lisensi Dokumentasi Bebas GNU 1.3 atau versi terbaru',
	'config-license-pd' => 'Domain Umum',
	'config-license-cc-choose' => 'Pilih lisensi Creative Commons kustom',
	'config-license-help' => "Banyak wiki publik meletakkan semua kontribusi di bawah [http://freedomdefined.org/Definition lisensi bebas]. 
Hal ini membantu untuk menciptakan rasa kepemilikan komunitas dan mendorong kontribusi jangka panjang.
Ini umumnya tidak diperlukan untuk wiki pribadi atau perusahaan.

Jika Anda ingin dapat menggunakan teks dari Wikipedia dan Anda ingin Wikipedia untuk dapat menerima teks yang disalin dari wiki Anda, Anda harus memilih'''Creative Commons Attribution Share Alike'''.

GNU Free Documentation License adalah lisensi sebelumnya dari Wikipedia.
Lisensi ini masih sah, namun memiliki beberapa fitur yang menyulitkan pemakaian ulang dan interpretasi.",
	'config-email-settings' => 'Pengaturan surel',
	'config-enable-email' => 'Aktifkan surel keluar',
	'config-enable-email-help' => 'Jika Anda ingin mengaktifkan surel, [http://www.php.net/manual/en/mail.configuration.php setelah surel PHP] perlu dikonfigurasi dengan benar.
Jika Anda tidak perlu fitur surel, Anda dapat menonaktifkannya di sini.',
	'config-email-user' => 'Aktifkan surel antarpengguna',
	'config-email-user-help' => 'Memungkinkan semua pengguna untuk saling berkirim surel jika mereka mengaktifkan pilihan tersebut dalam preferensi mereka.',
	'config-email-usertalk' => 'Aktifkan pemberitahuan perubahan halaman pembicaraan pengguna',
	'config-email-usertalk-help' => 'Memungkinkan pengguna untuk menerima pemberitahuan tentang perubahan halaman pembicaraan pengguna, jika pilihan tersebut telah diaktifkan dalam preferensi mereka.',
	'config-email-watchlist' => 'Aktifkan pemberitahuan daftar pantau',
	'config-email-watchlist-help' => 'Memungkinkan pengguna untuk menerima pemberitahuan tentang perubahan halaman yang ada dalam daftar pantauan mereka, jika pilihan tersebut telah diaktifkan dalam preferensi mereka.',
	'config-email-auth' => 'Aktifkan otentikasi surel',
	'config-email-auth-help' => "Jika opsi ini diaktifkan, pengguna harus mengonfirmasi alamat surel dengan menggunakan pranala yang dikirim kepadanya setiap kali mereka mengatur atau mengubahnya.
Hanya alamat surel yang dikonfirmasi yang dapat menerima surel dari pengguna lain atau surel pemberitahuan perubahan.
Penetapan opsi ini '''direkomendasikan''' untuk wiki publik karena adanya potensi penyalahgunaan fitur surel.",
	'config-email-sender' => 'Alamat surel balasan:',
	'config-email-sender-help' => 'Masukkan alamat surel untuk digunakan sebagai alamat pengirim pada surel keluar.
Alamat ini akan menerima pentalan.
Banyak server surel mensyaratkan paling tidak bagian nama domain yang sah.',
	'config-upload-settings' => 'Pengunggahan gambar dan berkas',
	'config-upload-enable' => 'Aktifkan pengunggahan berkas',
	'config-upload-help' => 'Pengunggahan berkas berpotensi memaparkan server Anda dengan risiko keamanan.
Untuk informasi lebih lanjut, baca [http://www.mediawiki.org/wiki/Manual:Security/id manual keamanan].

Untuk mengaktifkan pengunggahan berkas, ubah modus subdirektori <code>images</code> di bawah direktori akar MediaWiki agar server web dapat menulis ke sana.
Kemudian aktifkan opsi ini.',
	'config-upload-deleted' => 'Direktori untuk berkas terhapus:',
	'config-upload-deleted-help' => 'Pilih direktori tempat mengarsipkan berkas yang dihapus.
Idealnya, direktori ini tidak boleh dapat diakses dari web.',
	'config-logo' => 'URL logo:',
	'config-logo-help' => 'Kulit bawaan MediaWiki memberikan ruang untuk logo ukuran 135x160 pixel di sudut kiri atas. 
Unggah gambar dengan ukuran yang sesuai, lalu masukkan URL di sini. 

Jika Anda tidak ingin menyertakan logo, biarkan kotak ini kosong.',
	'config-instantcommons' => 'Aktifkan Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] adalah fitur yang memungkinkan wiki untuk menggunakan gambar, suara, dan media lain dari [http://commons.wikimedia.org/ Wikimedia Commons].
Untuk melakukannya, MediaWiki memerlukan akses ke Internet. 

Untuk informasi lebih lanjut tentang fitur ini, termasuk petunjuk tentang cara untuk mengatur untuk wiki selain Wikimedia Commons, baca [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos manual].',
	'config-cc-error' => 'Pemilih lisensi Creative Commons tidak memberikan hasil.
Masukkan nama lisensi secara manual.',
	'config-cc-again' => 'Pilih lagi...',
	'config-cc-not-chosen' => 'Pilih lisensi Creative Commons yang Anda inginkan dan klik "lanjutkan".',
	'config-advanced-settings' => 'Konfigurasi lebih lanjut',
	'config-cache-options' => 'Pengaturan untuk penyinggahan objek:',
	'config-cache-help' => 'Penyinggahan objek digunakan untuk meningkatkan kecepatan MediaWiki dengan menyinggahkan data yang sering digunakan.
Situs berukuran sedang hingga besar sangat dianjurkan untuk mengaktifkan fitur ini, dan situs kecil juga akan merasakan manfaatnya.',
	'config-cache-none' => 'Tidak ada penyinggahan (tidak ada fungsi yang dibuang, tetapi kecepatan dapat terpengaruh pada situs wiki yang besar)',
	'config-cache-accel' => 'Penyinggahan objek PHP (APC, eAccelerator, XCache atau WinCache)',
	'config-cache-memcached' => 'Gunakan Memcached (memerlukan setup dan konfigurasi tambahan)',
	'config-memcached-servers' => 'Server Memcached:',
	'config-memcached-help' => 'Daftar alamat IP yang digunakan untuk Memcached.
Harus dipisahkan dengan koma dan sebutkan port yang akan digunakan (contoh: 127.0.0.1:11211, 192.168.1.25:11211).',
	'config-extensions' => 'Ekstensi',
	'config-extensions-help' => 'Ekstensi yang tercantum di atas terdeteksi di direktori <code>./extensions</code>.

Ekstensi tersebut mungkin memerlukan konfigurasi tambahan, tetapi Anda dapat mengaktifkannya sekarang.',
	'config-install-alreadydone' => "'''Peringatan:''' Anda tampaknya telah menginstal MediaWiki dan mencoba untuk menginstalnya lagi.
Lanjutkan ke halaman berikutnya.",
	'config-install-step-done' => 'selesai',
	'config-install-step-failed' => 'gagal',
	'config-install-extensions' => 'Termasuk ekstensi',
	'config-install-database' => 'Mendirikan basis data',
	'config-install-pg-schema-failed' => 'Pembuatan tabel gagal. 
Pastikan bahwa pengguna "$1" dapat menulis ke skema "$2".',
	'config-install-pg-commit' => 'Melakukan perubahan',
	'config-install-pg-plpgsql' => 'Memeriksa bahasa PL / pgSQL',
	'config-pg-no-plpgsql' => 'Anda perlu menginstal bahasa PL/pgSQL pada basis data $1',
	'config-install-user' => 'Membuat pengguna basis data',
	'config-install-user-grant-failed' => 'Memberikan izin untuk pengguna "$1" gagal: $2',
	'config-install-tables' => 'Membuat tabel',
	'config-install-tables-exist' => "'''Peringatan''': Tabel MediaWiki sepertinya sudah ada.
Melompati pembuatan.",
	'config-install-tables-failed' => "'''Kesalahan''': Pembuatan tabel gagal dengan kesalahan berikut: $1",
	'config-install-interwiki' => 'Mengisi tabel bawaan antarwiki',
	'config-install-interwiki-list' => 'Tidak dapat menemukan berkas <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Peringatan''': Tabel antarwiki tampaknya sudah memiliki entri.
Mengabaikan daftar bawaan.",
	'config-install-keys' => 'Menciptakan kunci rahasia',
	'config-install-sysop' => 'Membuat akun pengguna pengurus',
	'config-install-subscribe-fail' => 'Tidak dapat berlangganan mediawiki-announce',
	'config-install-mainpage' => 'Membuat halaman utama dengan konten bawaan',
	'config-install-mainpage-failed' => 'Tidak dapat membuat halaman utama: $1',
	'config-install-done' => "'''Selamat!'''
Anda telah berhasil menginstal MediaWiki.

Penginstal telah membuat berkas <code>LocalSettings.php</code>.
Berkas itu berisi semua konfigurasi Anda.

Anda perlu [$1 mengunduhnya] dan meletakkannya di basis instalasi wiki (direktori yang sama dengan index.php).
'''Catatan''': Jika Anda tidak melakukannya sekarang, berkas konfigurasi yang dihasilkan ini tidak akan tersedia lagi setelah Anda keluar instalasi tanpa mengunduhnya.

Setelah melakukannya, Anda dapat '''[$2 memasuki wiki Anda]'''.",
	'config-download-localsettings' => 'Unduh LocalSettings.php',
	'config-help' => 'bantuan',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'config-admin-password' => 'Okwúngáfè:',
	'config-admin-password-confirm' => 'Okwúngáfè mgbe ozor:',
);

/** Italian (Italiano)
 * @author Beta16
 */
$messages['it'] = array(
	'config-information' => 'Informazioni',
	'config-back' => '← Indietro',
	'config-continue' => 'Continua →',
	'config-page-language' => 'Lingua',
	'config-page-name' => 'Nome',
	'config-page-options' => 'Opzioni',
	'config-page-install' => 'Installa',
	'config-page-complete' => 'Completa!',
	'config-page-readme' => 'Leggimi',
	'config-page-releasenotes' => 'Note di versione',
);

/** Japanese (日本語)
 * @author Aphaia
 * @author Iwai.masaharu
 * @author Mizusumashi
 * @author Ohgi
 * @author Whym
 * @author Yanajin66
 * @author 青子守歌
 */
$messages['ja'] = array(
	'config-desc' => 'MediaWikiのためのインストーラー',
	'config-title' => 'MediaWiki $1のインストール',
	'config-information' => '情報',
	'config-localsettings-upgrade' => '<code>LocalSettings.php</code>ファイルが検出されました。
アップグレードするため、ボックス中の<code>$wgUpgradeKey</code>の値を入力してください。
LocalSettings.phpの中にそれはあるでしょう。',
	'config-localsettings-key' => 'アップグレードキー：',
	'config-localsettings-badkey' => '与えられたキーが間違っています',
	'config-localsettings-incomplete' => '現在のLocalSettings.phpは不完全であるようです。
変数$1が設定されていません。
LocalSettings.phpを変更してこの変数を設定して、『{{int:Config-continue}}』を押してください。',
	'config-session-error' => 'セッションの開始エラー：$1',
	'config-session-expired' => 'セッションの有効期限が切れたようです。
セッションの有効期間は$1に設定されています。
php.iniの<code>session.gc_maxlifetime</code>を設定することで、この問題を改善できます。
インストール作業を再起動させてください。',
	'config-no-session' => 'セッションのデータが損失しました！
php.iniを確認し、<code>session.save_path</code>が適切なディレクトリに設定されていることを確かめて下さい。',
	'config-your-language' => 'あなたの言語：',
	'config-your-language-help' => 'インストール作業中に利用する言語を選んで下さい。',
	'config-wiki-language' => 'ウィキの言語：',
	'config-wiki-language-help' => 'そのウィキで主に書き込まれる言語を選んで下さい。',
	'config-back' => '←戻る',
	'config-continue' => '続行→',
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
	'config-page-existingwiki' => '既存のウィキ',
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
* [http://www.mediawiki.org/wiki/Manual:Contents 管理者向け案内]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>お読みください</doclink>
* <doclink href=ReleaseNotes>リリースノート</doclink>
* <doclink href=Copying>コピー</doclink>
* <doclink href=UpgradeDoc>アップグレード</doclink>',
	'config-env-good' => '環境は確認されました。
MediaWikiをインストール出来ます。',
	'config-env-bad' => '環境が確認されました。
MediaWikiをインストール出来ません。',
	'config-env-php' => 'PHP $1がインストールされています。',
	'config-unicode-using-utf8' => 'Unicode正規化に、Brion Vibberのutf8_normalize.soを利用。',
	'config-unicode-using-intl' => 'Unicode正規化に[http://pecl.php.net/intl intl PECL 拡張機能]を利用。',
	'config-unicode-pure-php-warning' => "'''警告'''：Unicode正規化の処理に [http://pecl.php.net/intl intl PECL 拡張機能]ではなく、ピュア PHP な実装を用いています。この処理は遅いです。
高トラフィックのサイトを運営する場合は、[http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode正規化に関するページ]をお読み下さい。",
	'config-unicode-update-warning' => "'''警告'''：Unicode正規化ラッパーのインストールされているバージョンは、[http://site.icu-project.org/ ICUプロジェクト]のライブラリの古いバージョンを使用しています。
Unicodeを少しでも利用する可能性があるなら、[http://www.mediawiki.org/wiki/Unicode_normalization_considerations 更新]する必要があります。",
	'config-no-db' => '適切なデータベースドライバを見つけられませんでした！',
	'config-no-db-help' => 'PHPのデータベースドライバーをインストールする必要があります。
以下のデータベースの種類がサポートされます：$1。

共有ホスト上の場合、ホスト元に適切なデータベースドライバをインストールするように依頼してください。
PHPを自分自身でコンパイルした場合、<code>./configure --with-mysql</code>などを利用して、データベースクライアントを有効化する設定をしてください。
DebianもしくはUbuntuパッケージからPHPをインストールした場合、php5-mysqlモジュールもインストールする必要があります。',
	'config-no-fts3' => "'''警告'''：SQLiteは[http://sqlite.org/fts3.html FTS3]モジュール以外でコンパイルされており、検索機能はこのバックエンドで利用不可能になります。",
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
	'config-xml-bad' => 'PHPのXMLモジュールが不足しています。
MediaWikiは、このモジュールの関数を必要としているため、この構成では動作しません。
Mandrakeを実行している場合、php-xmlパッケージをインストールしてください。',
	'config-pcre' => 'PCREをサポートしているモジュールが不足しているようです。
MediaWikiは、Perl互換の正規表現関数の動作が必要です。',
	'config-pcre-no-utf8' => "'''致命的エラー''': PHPのPCREがPCRE_UTF8サポート無しでコンパイルされています。
MediaWikiにはUTF-8サポートの関数が必要です。",
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
	'config-diff3-bad' => 'GNU diff3が見つかりません。',
	'config-imagemagick' => 'ImageMagickが見つかりました：<code>$1</code>。
アップロードが有効なら、画像のサムネイルが利用できます。',
	'config-gd' => 'GD画像ライブラリが内蔵されていることが確認されました。
アップロードが有効なら、画像のサムネイルが利用できます。',
	'config-no-scaling' => 'GDライブラリもImageMagickも見つかりませんでした。
画像のサムネイル生成は無効になります。',
	'config-no-uri' => "'''エラー：'''現在のURIを決定できませんでした。
インストールは中止されました。",
	'config-uploads-not-safe' => "'''警告：'''アップロードの既定ディレクトリ<code>$1</code>が、任意のスクリプト実行に関して脆弱性があります。
MediaWikiはアップロードされたファイルのセキュリティ上の脅威を確認しますが、アップロードを有効化するまえに、[http://www.mediawiki.org/wiki/Manual:Security#Upload_security このセキュリティ上の脆弱性を閉じる]ことが強く推奨されます。",
	'config-brokenlibxml' => 'このシステムで使われているPHPとlibxml2のバージョンのこの組み合わせにはバグがあります。具体的には、MediaWikiやその他のウェブアプリケーションでhiddenデータが破損する可能性があります。
PHPを5.2.9かそれ以降のバージョンに、libxml2を2.7.3かそれ以降のバージョンにアップグレードしてください([http://bugs.php.net/bug.php?id=45996 PHPでのバグ情報])。
インストールを終了します。',
	'config-using531' => 'PHP$1は<code>__call()</code>の引数参照に関するバグのため、MediaWikiと互換性がありません。
PHP5.3.2以降に更新するか、この([http://bugs.php.net/bug.php?id=50394 PHPに提出されたバグ])を修正するためにPHP5.3.0へ戻してください。
インストールは中止されました。',
	'config-db-type' => 'データベースの種類：',
	'config-db-host' => 'データベースのホスト：',
	'config-db-host-help' => 'データベースサーバーが異なったサーバー上にある場合、ホスト名またはIPアドレスをここに入力してください。

もし、共有されたウェブホスティングを使用している場合、ホスティング・プロバイダは正確なホストネームを解説しているはずです。

WindowsでMySQLを使用している場合に、「localhost」は、サーバー名としてはうまく働かないでしょう。もしそのような場合は、ローカルIPアドレスとして「127.0.0.1」を試してみてください。',
	'config-db-host-oracle' => 'データベースTNS：',
	'config-db-host-oracle-help' => '有効な[http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm ローカル接続名]を入力してください。tnsnames.oraファイルは、このインストールに対して表示されてなければなりません、<br />もしクライアントライブラリ10gもしくはそれ以上を使用している場合、メソッドの名前を[http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm 簡易接続]で利用できます。',
	'config-db-wiki-settings' => 'このウィキを識別',
	'config-db-name' => 'データベース名：',
	'config-db-name-help' => 'このウィキを識別する名前を選んで下さい。
スペースを含めることはできません。

共有ウェブホストを利用している場合、ホスト・プロバイダーは特定の利用可能なデータベース名を提供するか、あるいは管理パネルからデータベースを作成できるようにしているでしょう。',
	'config-db-name-oracle' => 'データベースのスキーマ：',
	'config-db-install-account' => 'インストールのための利用者アカウント',
	'config-db-username' => 'データベースの利用者名：',
	'config-db-password' => 'データベースのパスワード：',
	'config-db-install-username' => 'インストール中にデータベースに接続するために使うユーザ名を入力してください。これは MediaWiki アカウントのユーザ名 (利用者名) のことではありません。あなたのデータベースでのユーザ名です。',
	'config-db-install-password' => 'インストール中にデータベースに接続するために使うパスワードを入力してください。これは MediaWiki アカウントパスワードのことではありません。あなたのデータベースでのパスワードです。',
	'config-db-install-help' => 'インストール作業中にデータベースに接続するための利用者名とパスワードを入力してください。',
	'config-db-account-lock' => 'インストール作業終了後も同じ利用者名とパスワードを使用する',
	'config-db-wiki-account' => 'インストール作業終了後の利用者アカウント',
	'config-db-wiki-help' => '通常のウィキ操作中にデータベースへの接続する時に利用する利用者名とパスワードを入力してください。
アカウントがないが、インストールのアカウントに十分な権限があれば、このユーザーアカウントは、ウィキを操作するうえで最小限の権限を持った状態で作成されます。',
	'config-db-prefix' => 'データベーステーブルの接頭辞：',
	'config-db-prefix-help' => 'データベースを複数のウィキ間、もしくはMediaWikiと他のウェブアプリケーションで共有する必要がある場合、衝突を避けるために、すべてのテーブル名に接頭辞をつける必要があります。
スペースは使用できません。

このフィールドは、通常は空のままです。',
	'config-db-charset' => 'データベースの文字セット',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0バイナリ',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 下位互換UTF-8',
	'config-charset-help' => "'''警告：'''MySQL 4.1+で'''下位互換UTF-8'''を使用し、その後<code>mysqldump</code>でデータベースをバックアップすると、すべての非ASCII文字が破壊され、不可逆的にバップアップが壊れるかもしれません。

'''バイナリー系式'''では、MediaWikiは、UTF-8テキストを、データベースのバイナリーフィールドに格納します。
これは、MySQLのUTF-8形式より効率的で、ユニコード文字の全範囲を利用することが出来るようになります。
'''UTF-8形式'''では、MySQLは、なんの文字集合がデータのなかに含まれているかを知り、それに対して適切な提示と変換をするでしょうが、
[http://ja.wikipedia.org/wiki/%E5%9F%BA%E6%9C%AC%E5%A4%9A%E8%A8%80%E8%AA%9E%E9%9D%A2 基本多言語面]の外にある文字を格納できるようにはなりません。",
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
	'config-oracle-def-ts' => '既定のテーブル領域：',
	'config-oracle-temp-ts' => '一時的なテーブル領域：',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'メディアウィキは次のようなデータベースシステムをサポートする:

$1

もし、データベースシステムが不可視であるならば、以下のようにリスト化されたものを使用してみてください。可能なサポートの指示に従ってください。',
	'config-support-mysql' => '* $1はMediaWikiの主要な対象で、もっともサポートされています（[http://www.php.net/manual/en/mysql.installation.php MySQLのサポート下でPHPをコンパイルする方法]）',
	'config-support-postgres' => '* $1は、MySQLの代替として、人気のあるオープンソースデータベースシステムです（[http://www.php.net/manual/en/pgsql.installation.php PostgreSQLのサポート下でPHPをコンパイルする方法]）',
	'config-support-sqlite' => '* $1は、良くサポートされている、軽量データベースシステムです。（[http://www.php.net/manual/en/pdo.installation.php SQLiteのサポート下でPHPをコンパイルする方法]、PDOを使用）',
	'config-support-oracle' => '* $1は商業企業のデータベースです。（[http://www.php.net/manual/en/oci8.installation.php OCI8サポートなPHPをコンパイルする方法]）',
	'config-header-mysql' => 'MySQLの設定',
	'config-header-postgres' => 'PostgreSQLの設定',
	'config-header-sqlite' => 'SQLiteの設定',
	'config-header-oracle' => 'Oracleの設定',
	'config-invalid-db-type' => '不正なデータベースの種類',
	'config-missing-db-name' => '「データベース名」を入力する必要があります',
	'config-missing-db-server-oracle' => '「データベースTNS」に値を入力する必要があります',
	'config-invalid-db-server-oracle' => '不正なデータベースTNS「$1」です。
アスキー文字(a-z, A-Z)、数字(0-9)およびアンダーバー(_)とドット(.)のみを使用してください。',
	'config-invalid-db-name' => '無効なデータベース名 "$1"。
アスキー文字(a-z, A-Z)、数字(0-9)、アンダーバー(_)、ハイフン(-)のみを使用してください。',
	'config-invalid-db-prefix' => 'データベースの接頭語 "$1" が無効です。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)、ハイフン(-)のみを使用してください。',
	'config-connection-error' => '$1。

以下のホスト名、ユーザ名、パスワードをチェックして、再度試してみてください。',
	'config-invalid-schema' => 'メディアウィキ"$1"における無効な図式です。
アスキー文字(a-z, A-Z)、数字(0-9)、下線(_)のみを使用してください。',
	'config-postgres-old' => 'PostgreSQLの$1あるいはそれ以降が必要で、いまのバージョンは$2です。',
	'config-sqlite-name-help' => 'あなたのウェキと同一性のある名前を選んでください。
空白およびハイフンは使用しないでください。
SQLiteのデータファイル名として使用されます。',
	'config-sqlite-parent-unwritable-group' => 'データディレクトリ<code><nowiki>$1</nowiki></code>を作成できません。親ディレクトリ<code><nowiki>$2</nowiki></code>は、ウェブサーバから書き込みできませんでした。

インストール機能は、実行しているウェブサーバのユーザーを特定しました。
続行するには、<code><nowiki>$3</nowiki></code>ディレクトリを書き込み可能にしてください。
UnixあるいはLinux上では、以下を実行してください:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'データディレクトリ<code><nowiki>$1</nowiki></code>を作成できません。親ディレクトリ<code><nowiki>$2</nowiki></code>は、ウェブサーバから書き込みできませんでした。

インストール機能は、実行しているウェブサーバのユーザーを特定できませんでした。
続行するには、<code><nowiki>$3</nowiki></code>ディレクトリを、ウェブサーバ（と他のユーザ！）からグローバルに書き込み出来るようにしてください。
UnixあるいはLinux上では、以下を実行してください：

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'データディレクトリー"$1"を作成したことによるエラー。
場所をチェックして、再度試してください。',
	'config-sqlite-dir-unwritable' => 'ディレクトリー"$1"を書き込むことができません。
パーミッションを変更すれば、ウェブサーバーが書き込み可能となります。再度試してください。',
	'config-sqlite-connection-error' => '$1。

以下のデータディレクトリーとデータベースをチェックし、再度試してみてください。',
	'config-sqlite-readonly' => 'ファイル<code>$1</code>は書き込み不能です。',
	'config-sqlite-cant-create-db' => 'データベースファイル<code>$1</code>を作成できませんでした。',
	'config-sqlite-fts3-downgrade' => 'PHPはFTS3のサポート、テーブルのダウングレードが無効です。',
	'config-can-upgrade' => 'このデータベースにはメディアウィキテーブルが存在します。
それらをメディアウィキ$1にアップグレードするために「続行」をクリックしてください。',
	'config-upgrade-done' => "更新は完了しました。

[$1 ウィキを使い始める]ことができます。

もし、<code>LocalSettings.php</code>ファイルを再生成したいのならば、下のボタンを押してください。
ウィキに問題がないのであれば、これは'''推奨されません'''。",
	'config-upgrade-done-no-regenerate' => 'アップグレードが完了しました。

[$1 ウィキの使用を開始]することができます。',
	'config-regenerate' => 'LocalSettings.phpを再生成→',
	'config-show-table-status' => 'SHOW TABLE STATUSクエリーが失敗しました！',
	'config-unknown-collation' => "'''警告:''' データベースは認識されない照合を使用しています。",
	'config-db-web-account' => 'ウェブアクセスのためのデータベースアカウント',
	'config-db-web-help' => 'ウィキの元来の操作中、ウェブサーバーがデーターベースサーバーに接続できるように、ユーザ名とパスワードを選択してください。',
	'config-db-web-account-same' => 'インストールのために同じアカウントを使用してください',
	'config-db-web-create' => '既に存在していないのであれば、アカウントを作成してください',
	'config-db-web-no-create-privs' => 'あなたがインストールのために定義したアカウントは、アカウント作成のための特権としては不充分です。
あなたがここで特定したアカウントはすでに存在していなければなりません。',
	'config-mysql-engine' => 'ストレージエンジン:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB'''は、並行処理のサポートに優れているので、ほとんどの場合において最良の選択肢です。

'''MyISAM'''は、利用者が1人の場合、あるいは読み込み専用でインストールする場合に、より処理が早くなるでしょう。
ただし、MyISAMのデータベースは、InnoDBより高頻度で破損する傾向があります。",
	'config-mysql-charset' => 'データベースの文字セット:',
	'config-mysql-binary' => 'バイナリ',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "'''バイナリー系式'''では、MediaWikiは、UTF-8テキストを、データベースのバイナリーフィールドに格納します。
これは、MySQLのUTF-8形式より効率的で、ユニコード文字の全範囲を利用することが出来るようになります。

'''UTF-8形式'''では、MySQLは、なんの文字集合がデータのなかに含まれているかを知り、それに対して適切な提示と変換をするでしょうが、
[http://ja.wikipedia.org/wiki/%E5%9F%BA%E6%9C%AC%E5%A4%9A%E8%A8%80%E8%AA%9E%E9%9D%A2 基本多言語面]の外にある文字を格納できるようにはなりません。",
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
	'config-subscribe' => '[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce リリース告知のメーリングリスト]を購読する。',
	'config-subscribe-help' => 'これは、リリースの告知（重要なセキュリティに関する案内を含む）に使われる、低容量のメーリングリストです。
このメーリングリストを購読して、新しいバージョンが出た場合にMediaWikiを更新してください。',
	'config-almost-done' => 'あなたはほとんど完璧です！
設定を残すことをはぶいて、今すぐにウィキをインストールできます。',
	'config-optional-continue' => '私にもっと質問してください。',
	'config-optional-skip' => 'すでに飽きてしまった、ウィキをインストールするだけです。',
	'config-profile' => '正しいプロフィールのユーザ:',
	'config-profile-wiki' => '伝統的なウィキ',
	'config-profile-no-anon' => 'アカウントの作成が必要',
	'config-profile-fishbowl' => '承認された編集者のみ',
	'config-profile-private' => '非公開ウィキ',
	'config-profile-help' => "ウィキは、たくさんの人が可能な限りそのウィキを編集できるとき、最も優れた働きをします。
MediaWikiでは、最近の更新を確認し、神経質な、もしくは悪意を持った利用者からの損害を差し戻すことが、簡単にできます。

しかし一方で、MediaWikiは、さらに様々な形態でもの利用も優れていると言われています。また、時には、すべての人にウィキ手法の利点を説得させるのは容易ではないかもしれません。
そこで、選択肢があります。

'''{{int:config-profile-wiki}}'''は、ログインをせずとも、誰でも編集が可能なものです。
'''{{int:config-profile-no-anon}}'''なウィキは、各編集に対してより強い説明責任を付与しますが、気軽な投稿を阻害するかもしれません。

'''{{int:config-profile-fishbowl}}'''のウィキは、承認された利用者は編集でき、一方、一般の人はページ（とその履歴）の閲覧が可能です。
'''{{int:config-profile-private}}'''は、承認された利用者がページを閲覧可能で、そのグループが編集可能です。

より複雑な利用者権限の設定は、インストール後に設定可能です。詳細は[http://www.mediawiki.org/wiki/Manual:User_rights 関連するマニュアル]をご覧ください。",
	'config-license' => '著作権とライセンス:',
	'config-license-none' => 'ライセンスのフッターを付けない',
	'config-license-cc-by-sa' => 'クリエイティブ・コモンズ 表示-継承',
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
	'config-email-auth-help' => "この選択肢が有効化されると、利用者が電子メールのアドレスを設定あるいは変更したときに送信されるリンクにより、そのアドレスを確認しなければならなくなります。
認証済みのアドレスだけが、他の利用者からのメールや、変更通知のメールを受け取ることができます。
公開ウィキでは、メール機能による潜在的な不正利用の防止のため、この選択肢を設定することが'''推奨'''されます。",
	'config-email-sender' => '電子メールのアドレスを返す:',
	'config-email-sender-help' => '送信メールの返信アドレスとして利用するメールアドレスを入力してください。
宛先不明の場合、このアドレスにその通知が送信されます。
多くのメールサーバーでは、少なくともドメイン名の一部が有効であることが必要になっています。',
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
	'config-instantcommons' => 'InstantCommons機能を有効にする',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons InstantCommons]は、[http://commons.wikimedia.org/ ウィキメディア・コモンズ]のサイトで見つかった画像や音声、その他のメディアをウィキ上で利用することができるようになる機能です。
これを有効化するには、MediaWikiはインターネットに接続できなければなりません。

ウィキメディアコモンズ以外のウィキを同じように設定する方法など、この機能に関する詳細な情報は、[http://mediawiki.org/wiki/Manual:$wgForeignFileRepos マニュアル]をご覧ください。',
	'config-cc-error' => 'クリエイティブ・コモンズ・ライセンスの選択器から結果が得られませんでした。
ライセンスの名前を手動で入力してください。',
	'config-cc-again' => 'もう一度選択してください...',
	'config-cc-not-chosen' => 'あなたの求めるクリエイティブコモンズのライセンスを選んで、"続行"をクリックしてください。',
	'config-advanced-settings' => '高度な設定',
	'config-cache-options' => 'オブジェクトのキャッシュの設定:',
	'config-cache-help' => 'オブジェクトのキャッシュは、使用したデータを頻繁にキャッシングすることによって、メディアウィキのスピード改善に使用されます。
中〜大サイトにおいては、これを有効にするために大変望ましいことです。また小さなサイトにおいても同様な利点をもたらすと考えられます。',
	'config-cache-none' => 'キャッシングしない(機能は取り払われます、しかもより大きなウィキサイト上でスピードの問題が発生します)',
	'config-cache-accel' => 'PHPオブジェクトキャッシング（APC、eAccelerator、XCacheあるいはWinCache）',
	'config-cache-memcached' => 'Memcachedを使用（追加の設定が必要です）',
	'config-memcached-servers' => 'メモリをキャッシュされたサーバ:',
	'config-memcached-help' => 'Memcachedを使用するIPアドレスの一覧。
カンマ区切りで、利用する特定のポートの指定が必要です。例:
127.0.0.1:11211
192.168.1.25:1234',
	'config-extensions' => '拡張機能',
	'config-extensions-help' => '<code>./extensions</code>ディレクトリ内で、上記リストの拡張機能が発見されました。

これらは更に多くの設定を要求するかもしれませんが、今これらを有効にすることができます。',
	'config-install-alreadydone' => "'''警告:''' 既にMediaWikiがインストール済みで、再びインストールし直そうとしています。
次のページへ進んでください。",
	'config-install-begin' => '「{{int:config-continue}}」を押すと、MediaWikiのインストールを開始することができます。
変更したい設定があれば、「{{int:Config-back}}」を押してください。',
	'config-install-step-done' => '実行',
	'config-install-step-failed' => '失敗した',
	'config-install-extensions' => '拡張機能を含む',
	'config-install-database' => 'データベースの構築',
	'config-install-pg-schema-failed' => 'テーブルの作成に失敗した。
ユーザ"$1"が図式"$2"に書き込みができるようにしてください。',
	'config-install-pg-commit' => '変更を送信',
	'config-install-user' => 'データベースユーザを作成する',
	'config-install-user-grant-failed' => 'ユーザー「$1」に許可を与えることに失敗しました。：$2',
	'config-install-tables' => 'テーブルの作成',
	'config-install-tables-exist' => "'''警告'''：MediaWikiテーブルが、すでに存在しているようです。
作成を飛ばします。",
	'config-install-tables-failed' => "'''エラー'''：テーブルの作成が、次のエラーにより失敗しました：$1",
	'config-install-interwiki' => '既定のウィキ間テーブルを導入しています',
	'config-install-interwiki-list' => 'ファイル<code>interwiki.list</code>を見つけることができませんでした。',
	'config-install-interwiki-exists' => "'''警告'''：ウィキ間テーブルはすでに登録されているようです。
既定のテーブルを無視します。",
	'config-install-keys' => '秘密鍵を生成する',
	'config-install-sysop' => '管理者のユーザーアカウントを作成する',
	'config-install-mainpage' => '既定の接続でメインページを作成',
	'config-install-mainpage-failed' => 'メインページを挿入できませんでした:$1',
	'config-install-done' => "'''おめでとうございます！''' 
MediaWikiのインストールに成功しました。

<code>LocalSettings.php</code>ファイルが生成されました。
すべての設定がそのファイルに含まれています。

それをダウンロードし、ウィキをインストールした基準ディレクトリー（index.phpと同じディレクトリー）に置く必要があります。ダウンロードは自動的に開始しているはずです。

ダウンロードが開始していない場合、またダウンロードをキャンセルした場合は、以下のリンクからダウンロードを再開することができます：

$3

'''注意''': もし、これを今しなければ、つまり、このファイルをダウンロードせずインストールを終了した場合、この生成された設定ファイルは利用されません。

それを完了すれば、'''[$2 ウィキに入る]'''ことができます。",
	'config-download-localsettings' => 'LocalSettings.phpをダウンロード',
	'config-help' => 'ヘルプ',
);

/** Khmer (ភាសាខ្មែរ)
 * @author គីមស៊្រុន
 */
$messages['km'] = array(
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
	'config-help' => 'ជំនួយ',
);

/** Colognian (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'config-desc' => 'Et Projramm för Mediwiki opzesäze.',
	'config-title' => 'MediaWiki $1 opsäze',
	'config-information' => 'Enfomazjuhn',
	'config-localsettings-upgrade' => 'De Dattei <code lang="en">LocalSettings.php</code> es ald doh.
De Projramme vum Wiki künne op der neußte Shtand jebraat wääde:
Donn doför dä Wäät vum <code lang="en">$wgUpgradeKey</code> en dat heh Feld enjävve.
Do fenggs_et en dä Dattei <code lang="en">LocalSettings.php</code> om ẞööver.',
	'config-localsettings-cli-upgrade' => 'En Dattei <code lang="en">LocalSettings.php</code> es jefonge woode.
Öm et Wiki_Projramm op ene neue Shtand ze bränge, donn <code lang="en">update.php</code> oproofe.',
	'config-localsettings-key' => 'Der Schlößel för et Projramm op ene neue Schtand ze bränge:',
	'config-localsettings-badkey' => 'Dinge Schlößel paß nit.',
	'config-upgrade-key-missing' => 'Mer han jefonge, dat MediaWiki ald enschtalleed es.
Üm de Projramme un Daate o der neue Schtand bränge ze künne, dunn aan et Engk vun dä Dattei <code lang="en">LocalSettings.php</code> op dämm ẞööver:

$1

aanhange.',
	'config-localsettings-incomplete' => 'Mer han en Dattei <code lang="en">LocalSettings.php:</code> jefonge, ävver di schingk nit kumplätt ze sin.
De Varijable <code lang="en">$1</code> es nit jesatz.
Bes esu joot, un donn di Dattei esu aanpaße, dat se jesaz ea, un dann donn op „{{int:config-continue}}“ klecke.',
	'config-localsettings-connection-error' => 'Ene Fähler es opjetrodde wi mer en Verbendung noh de Datebangk opmaache wullte met dä Enshtellunge uß dä Dattei <code lang="en">LocalSettings</code> udder uß dä Dattei <code lang="en">LocalSettings</code> un et hät nit jeflupp. Bes esu joot un dat repareere un versöhg et dann norr_ens.

$1',
	'config-session-error' => 'Ene Fähler es opjetrodde beim Aanmelde för en Sezung: $1',
	'config-session-expired' => 'De Daate för Ding Setzung sinn wall övverholld of afjeloufe.
De Setzungunge sin esu enjeshtallt, nit mieh wi $1 ze doore.
Dat kanns De verlängere, endämm dat De de <code lang="en">session.gc_maxlifetime</code> en dä Dattei <code>php.ini</code> jrüüßer määß.
Don dat Projramm för et Opsäze norr_ens aanschmiiße.',
	'config-no-session' => 'De Daate för Ding Setzung sinn verschött jejange.
Donn en dä Dattei <code>php.ini</code> nohloore, ov dä <code lang="en">session.save_path</code> op e zopaß Verzeijschneß zeisch.',
	'config-your-language' => 'Ding Shprooch:',
	'config-your-language-help' => 'Donn heh di Shprooch ußsöhke, di dat Enshtallzjuhnsprojramm kalle sull.',
	'config-wiki-language' => 'Dem Wiki sing Shprooch:',
	'config-wiki-language-help' => 'Donn heh di Shprooch ußsöhke, di et Wiki shtandattmääßesch kalle sull.',
	'config-back' => '← Retuur',
	'config-continue' => 'Wigger →',
	'config-page-language' => 'Shprooch',
	'config-page-welcome' => 'Wellkumme beim MediaWiki!',
	'config-page-dbconnect' => 'Met dä Daatebangk Verbenge',
	'config-page-upgrade' => 'En Inshtallzjuhn op der neuste Shtand bränge',
	'config-page-dbsettings' => 'Parrameeter för de Daatebangk',
	'config-page-name' => 'Name',
	'config-page-options' => 'Ennställunge',
	'config-page-install' => 'Opsäzze',
	'config-page-complete' => 'Fäädesch!',
	'config-page-restart' => 'Et Opsäze norr_ens neu aanfange',
	'config-page-readme' => 'Donn mesch lässe! (<i lang="en">read me</i>)',
	'config-page-releasenotes' => 'Henwies för heh di Version vum Projramm (<i lang="en">Release notes</i>)',
	'config-page-copying' => 'Ben aam Kopeere',
	'config-page-upgradedoc' => 'Ben op der neuste Stand aam bränge',
	'config-page-existingwiki' => 'Mer han ald e Wiki!',
	'config-help-restart' => 'Wells De all Ding enjejovve Sachee fottjeschmesse han, un dä janze Vörjang vun fürre aan neu aanfange?',
	'config-restart' => 'Joh, neu aanfange!',
	'config-welcome' => '=== Ömjevong Prööfe ===
Mer maache en Aanzal jrundlääje Prövunge, öm erus ze fenge, ov di Ömjevong heh paß, för Mediawiki opzesäze.
Wann de Hölp bem Opsäze bruchs, donn wigger ssare, wat erus kohm, wat heh shteiht.',
	'config-copyright' => "=== Urhävverrääsch un Lizänzbedengunge ===

\$1

Dat  Projramm heh es frei, mer kann et wiggerjävve un verdeijle un och verändere ungger dä Bedengunge vun de  GNU <i lang=\"en\">General Public License</i> (Alljemeine öffentlesche Lizänz) wi se vun de <i lang=\"en\">Free Software Foundation</i> (de Shteftung för frei Projramme) veröffentlesch woode es. Dobei kanns De Der de Version 2 vun dä Lizanz ußsöhke, udder jeede Version donoh, wi et Der jefällt.

Dat Projramm weed wigger jejovve met dä Hoffnung, dat et jät nöz, ävver '''ohne Jarrantie''', sujaa ohne de onußjeshproche Jarantie, '''verkoufbaa''' ze sin, udder '''för öhnds_ene beshtemmpte Zweck ze bruche''' ze sin.
Liß de GNU <i lang=\"en\">General Public License</i> sellver, öm mieh ze erfahre.

Do sullts en <doclink href=Copying>Kopie vun dä alljemene öffentlesche Lizänz vun dä GNU</doclink> (<i lang=\"en\">GNU General Public License</i>) zosamme met heh däm Projramm krääje han. Wann dat nit esu es, schrief aan de <i lang=\"en\">Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA</i>. udder [http://www.gnu.org/copyleft/gpl.html liß se online övver et Internet].",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki sing Hompäjdsch]
* [http://www.mediawiki.org/wiki/Help:Contents Handbooch för Aanwender]
* [http://www.mediawiki.org/wiki/Manual:Contents Handbooch för Administratore un Wiki_Köbesse]
* [http://www.mediawiki.org/wiki/Manual:FAQ Öff jeshtallte Froore met Antwoote]
----
* <doclink href=Readme>Liß Mesch! (<i lang="en">Read me</i>)</doclink>
* <doclink href=ReleaseNotes><i lang="en">Release notes</i> Övver heh di Projrammversion</doclink>
* <doclink href=Copying><i lang="en">Copying</i> — Lizänzbeshtemmunge</doclink>
* <doclink href=UpgradeDoc><i lang="en">Upgrading</i> — Ob en neu Projrammversion jonn</doclink>',
	'config-env-good' => 'De Ömjävung es jeprööf.
Do kanns MediaWiki opsäze.',
	'config-env-bad' => 'De Ömjävung es jeprööf.
Do kanns MediaWiki nit opsäze.',
	'config-env-php' => 'PHP $1 es doh.',
	'config-env-php-toolow' => 'PHP $1 es enshtalleert.
Ävver MediaWiki bruch PHP $2 udder hühter.',
	'config-unicode-using-utf8' => 'För et <i lang="en">Unicode</i>-Nommaliseere dom_mer däm <i lang="en">Brion Vibber</i> sing Projramm <code lang="en">utf8_normalize.so</code> nämme.',
	'config-unicode-using-intl' => 'För et <i lang="en">Unicode</i>-Nommaliseere dom_mer dä [http://pecl.php.net/intl Zohsaz <code lang="en">intl</code> uss em <code lang="en">PECL</code>] nämme.',
	'config-unicode-pure-php-warning' => '\'\'\'Opjepaß:\'\'\' Mer kunnte dä [http://pecl.php.net/intl Zohsaz <code lang="en">intl</code> uss em <code lang="en">PECL</code>] för et <i lang="en">Unicode</i>-Nommaliseere nit fenge. Dröm nämme mer dat eijfache, ävver ärsh lahme, <i lang="en">PHP</i>-Projrammshtöck doför.
För jruuße Wikis met vill Metmaachere doht Üsch die Sigg övver et [http://www.mediawiki.org/wiki/Unicode_normalization_considerations <i lang="en">Unicode</i>-Nommaliseere] (es op Änglesch) aanloore.',
	'config-unicode-update-warning' => "'''Opjepaß:''' Dat Projramm för der <i lang=\"en\">Unicode</i> zo normaliseere boud em Momang op en  ählter Version vun dä Bibliothek vum [http://site.icu-project.org/ ICU-Projäk] op.
Doht di [http://www.mediawiki.org/wiki/Unicode_normalization_considerations op der neuste Shtand bränge], wann auf dat Wiki em Äänz <i lang=\"en\">Unicode</i> bruche sull.",
	'config-no-db' => 'Mer kunnte kei zopaß Daatebangk-Driiverprojamm fenge.',
	'config-no-db-help' => 'Mer bruche e Daatebangk-Driiverprojamm för PHP. Dat moß enjeresht wääde.
Mer künne met heh dä Daatebangke ömjonn: $1.

Wann De nit om eijene Rääshner bes, moß De Dinge <i lang="en">provider</i> bedde, dat hä Der ene zopaß Driiver enresht.
Wann de PHP sellver övversaz häs, donn ene Zohjang för en Daatebangk enbenge, för e Beishpell met: <code  lang="en">./configure --with-mysql</code> op ene <i lang="en">command shell</i>.
Wann De PHP uss enem <i lang="en">Debian</i> udder <i lang="en">Ubuntu</i> Pakätt enjeresht häs, moß De dann och noch et <code lang="en">php5-mysql</code> op Dinge Räschner bränge.',
	'config-no-fts3' => "'''Opjepaß:''' De Projramme vum <i lang=\"en\">SQLite</i> sin der ohne et [http://sqlite.org/fts3.html FTS3-Modul] övversaz, dröm wääde de Funxjohne för et Söhke fähle.",
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
	'config-safe-mode' => "'''Opjepaß:''' Dem PHP singe <code lang=\"en\">[http://www.php.net/features.safe-mode safe mode]</code> es aanjeschalldt. Dat kann Ärjer maache, besönders beim Datteie Huhlaade bei de Ongershtözung för <code lang=\"en\">math</code>-Befähle.",
	'config-xml-bad' => 'Dem PHP sing XML-Modul es nit ze fenge.
MediaWiki bruch Funxjohne en däm Modul un deiht et esu nit.
Wann De <i lang="en">Mandrake</i> aam loufehäs, donn dat Pakätt <code lang="en">php-xml</code> enstalleere.',
	'config-pcre' => 'Dem PHP sing Modul för <i lang="en">PCRE</i> schingk ze fähle.
MediaWiki deiht et nit ohne de Funxjohne för de <i lang="en">Perl-compatible regular expressions</i>.',
	'config-pcre-no-utf8' => "'''Dä:''' Et PHP-Modul <i lang=\"en\">PCRE</i> schingk ohne de <i lang=\"en\">PCRE_UTF8</i>-Aandeile övversaz ze sin.
MediaWiki bruch dä UTF-8-Krohm ävver, öm ohne Fähler loufe ze künne.",
	'config-memory-raised' => 'Der jrühzte zohjelasse Shpeisherbedarf vum PHP, et <code lang="en">memory_limit</code>, shtund op $1 un es op $2 erop jesaz woode.',
	'config-memory-bad' => "'''Opjepaß:''' Dem PHP singe Parameeter <code lang=\"en\">memory_limit</code> es \$1.
Dat es wall ze winnisch.
Et Enreeschte kunnt doh draan kappott jon!",
	'config-xcache' => 'Dä <code lang="en">[http://trac.lighttpd.net/xcache/ XCache]</code> es ennjeresht.',
	'config-apc' => 'Dä <code lang="en">[http://www.php.net/apc APC]</code> es ennjeresht.',
	'config-eaccel' => 'Dä <code lang="en">[http://eaccelerator.sourceforge.net/ eAccelerator]</code> es ennjeresht.',
	'config-wincache' => 'Dä <code lang="en">[http://www.iis.net/download/WinCacheForPhp WinCache]</code> es ennjeresht.',
	'config-no-cache' => '\'\'\'Opjepaß:\'\'\' Mer kunnte dä <code lang="en">[http://eaccelerator.sourceforge.net eAccelerator]</code>, dä <code lang="en">[http://www.php.net/apc APC]</code>, dä <code lang="en">[http://trac.lighttpd.net/xcache/ XCache]</code> un dä <code lang="en">[http://www.iis.net/download/WinCacheForPhp WinCache]</code> nit fenge.
Et <i lang="en">object caching</i> es nit müjjelesh un ußjeschalldt.',
	'config-diff3-bad' => 'Mer han <i lang="en">GNU</i> <code lang="en">diff3</code> nit jefonge.',
	'config-imagemagick' => 'Mer han <i lang="en">ImageMagick</i> jefonge: <code>$1</code>.
Et Ömrääschne en Minni-Beldsche weed müjjelesch sin, wann De et Belder Huhlaade zohlöhß.',
	'config-gd' => 'Mer han de ennjeboute GD-Jrafik-Projramm-Biblijotheek jefonge.
Et Ömrääschne en Minni-Beldsche weed müjjelesch sin, wann De et Belder Huhlaade zohlöhß.',
	'config-no-scaling' => 'Mer han weeder de GD-Jrafik-Projramm-Biblijotheek, noch <i lang="en">ImageMagick</i> jefonge.
Et Ömrääschne en Minni-Beldsche weed ußjeschalldt.',
	'config-no-uri' => "'''Fähler:''' Mer kunnte der aktoälle <i lang=\"en\">URI</i> nit erusfenge.
Et Enreeschte es domet heh aam Engk.",
	'config-uploads-not-safe' => "'''Opjepaß:''' Uß däm jewöhnlijje Verzeichnes för de huhjelaade Datteie, dat es <code>$1</code>, künnte öhnzwällsche Skrepte un Projramme ußjeföhrt wääde. Och wann MediaWiki de huhjelaade Datteie prööf, dat kein bekannte Risike dren sin, sullt mer doch dat [http://www.mediawiki.org/wiki/Manual:Security#Upload_security Sesherheitsloch] zoh maache, ih dat mer et Dattei Huhlaade zohlöht.",
	'config-brokenlibxml' => 'Op Dingem Rääschner loufe Versione vun PHP un <code lang="en">libxml2</code> zosamme, di ävver nit zosamme paßße, un de Daate em MediaWiki un ander Web_Aanwändunge [http://bugs.php.net/bug.php?id=45996 bug kapott maache].
Jangk op PHP 5.2.9 udder dohnoh un op <code lang="en">libxml2</code> 2.7.3 udder dohnoh.
Heh jeihd et nit wigger.',
	'config-using531' => 'MediaWiki läuf nit met PHP $1 zosamme wääje enem [http://bugs.php.net/bug.php?id=50394 Fähler em Zosammehang met Parrameetere för <code lang="en">__call()</code>].
Jangk op de Version 5.3.2 vum <i lang="en">PHP</i> ov dohnoh, udder op de Version 5.3.0 udder dovöör, öm dat Problem ze ömjonn.
Heh jeiht et nit wigger.',
	'config-db-type' => 'De Zoot Daatebangk:',
	'config-db-host' => 'Dä Name vun däm Rääschner met dä Daatebangk:',
	'config-db-host-help' => 'Wann Dinge ẞööver för de Daatebangk ob enem andere Rääschner es, donn heh dämm singe Name udder <i lang="en">IP</i>-Addräß enjävve.

Wann De ob enem Meetẞööver beß, weet Der Dinge Provaider odder däm sing Dokemäntazjuhn saare, wat De endraare moß.

Wann De ob enem ẞööver onger <i lang="en">Windows</i> am enshtalleere bes un en <i lang="en">MySQL</i>-Daatebangk häs, künnd_et sin, dat „<code lang="en">localhost</code>“ nit douch för der Name vum ẞööver. Wann dad-esu es, versöhg et ens met „<code lang="en">127.0.0.1</code>“ als <i lang="en">IP</i>-Addräß vum eije Rääschner.',
	'config-db-host-oracle' => 'Dä Daatebangk ier <i lang="en" title="Transparent Network Substrate">TNS</i>:',
	'config-db-host-oracle-help' => 'Donn ene jöltije [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm „<i lang="en">Local Connect</i>“-Name] aanjävve. De Dattei „<code lang="en">tnsnames.ora</code>“ moß för heh dat Projamm seschbaa un ze Lässe sin.<br />Wann heh de Projamm_Biblijoteeke für de Aanwänderprojramme för de Version 10g udder neuer enjesaz wääde, kam_mer och et [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm „<i lang="en">Easy Connect</i>“] jenumme wääde för der Name ze verjävve.',
	'config-db-wiki-settings' => 'De Daate vum Wiki',
	'config-db-name' => 'Dä Name vun dä Daatebangk:',
	'config-db-name-help' => 'Jiff ene Name aan, dä för Ding Wiki passe deiht.
Doh sullte kei Zweschrereum un kein Stresche dren sin.

Wann De nit op Dingem eije Rääschner bes, künnt et sin, dat Dinge Provaider Der extra ene beshtemmpte Name för de Daatebangk jejovve hät, uffr dat de dä drom froore moß udder dat De de Daatebangke övver e Fommulaa selver enreeschte moß.',
	'config-db-name-oracle' => 'Schema för de Daatebangk:',
	'config-db-account-oracle-warn' => 'Mer han drei Aate, wi mer <i lang="en">Oracle</i> als Daatebangk aanbenge künne.

Wann De ene neue Zohjang op de Daatenbangk met Naame un Paßwoot mem Projramm för et Opsäze aanlääje wells, dann jif ene Zohjang met däm Rääsch „<i lang="en">SYSDBA</i>“ aan, dä et alld jitt, un jif däm di Daate aan för dä neue Zohjang aanzelääje.
Do kanns och dä neue Zohjang vun Hand aanlääje un heh beim Opsäze nur dää aanjävve — wann dä dat Rääsch hät, en de Daatebangk Schema_Objäkte aanzelääje.
Udder De jiß zwei ongerscheidlijje Zohjäng op de Daatenbangk aan, woh eine vun dat Rääsch zom Aanlääje hät un dä andere moß dat nit un es för der nomaale Bedrief zohshtändesch.

En Skrep, wat ene Zohjang op de Daatenbangk aanlääsch met all dä nüüdejje Rääschde, fengks De em Verzeishneß <code lang="en">maintenance/oracle/</code> vun Dingem MediaWiki. Donn draan dengke, dat ene Zohjang met beschrängkte Rääschde all di Müjjeleschkeite för et Waade un Repareere nit hät, di de jewöhnlejje Zoot Zohjang met sesh brängk.',
	'config-db-install-account' => 'Der Zohjang för en Enreeschte',
	'config-db-username' => 'Dä Name vun däm Aanwender för dä Zohjref op de Daatebangk:',
	'config-db-password' => 'Et Paßwoot vun däm Aanwender för dä Zohjref op de Daatebangk:',
	'config-db-password-empty' => 'Jiv e Paßwoot aan, för dä neue Aanwender för dä Zohjref op de Daatebangk, $1.
Ed es zwa müjjelesch, Aanwender för dä Zohjref op de Daatebangk der ohne e Paßwoot aanzelääje,
ävver dat wöhr en schwere Jevah för de Sescherheit vum Wiki.',
	'config-db-install-username' => 'Jiv ene Name aan för dä Aanwender för dä Zohjref op de Daatebangk beim Enshtalleere.
Dat es keine Metmaacher_Name em Wiki — heh dä Name es alleins en der Daatebangk bikannt.',
	'config-db-install-password' => 'Jiv e Paßwoot aan för dä Aanwender för dä Zohjref op de Daatebangk beim Enshtalleere.
Dat es kei Paßwoot för ene Metmaacher em Wiki — et es alleins en der Daatebangk bikannt.',
	'config-db-install-help' => 'Donn dä Name un et Paßwoot vun däm Aanwänder för der Zohjreff op de Daatebangk jäz för et Enreeshte aanjävve.',
	'config-db-account-lock' => 'Donn dersälve Name un et sälve Paßwoot för der nomaale Bedrief vum Wiki bruche',
	'config-db-wiki-account' => 'Dä Name vun däm Aanwender för dä Zohjref op de Daatebangk em nomaale Bedrief:',
	'config-db-wiki-help' => 'Jiv ene Name un e Paßwoot aan, för dä Aanwender för dä Zohjref op de Daatebangk, wann et wiki nommaal aam Loufe es.
Wan et dä Name en der Daatebangk noch it jit, un dä Aanwender för dä Zohjref op de Daatebangk beim Enshtalleere
jenooch Beräschtijunge hät, läät dä heh dä Aanwender en der Daatebangk aan un jidd_em di Rääschde, di dä nüüdesch hät, ävver nit mieh.',
	'config-db-prefix' => 'Vörsaz för de Name vun de Tabälle en de Daatebangk:',
	'config-db-prefix-help' => 'Wann ein Daatebangk för mieh wi ein Wiki udder e Wiki uns söns jät zosamme jebruch weed, dann kam_mer noch jet vör de Tabälle ier Name säze. Esu ene Vörsaz sull dubblte Tabällename vermeide hälfe.
Donn kein Zwescheräum enjävve!

Jewöhnlesch bliev dat Feld heh ävver läddesch.',
	'config-db-charset' => 'Dä Daatebangk iere Zeishesaz',
	'config-charset-mysql5-binary' => 'MySQL (4.1 udder 5.0) binär',
	'config-charset-mysql5' => 'MySQL (4.1 udder 5.0) UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 röckwääts kompatibel UTF-8',
	'config-charset-help' => "''' Opjepaß:'''
Wann De et '''röckwääts kompatibel UTF-8 Fommaat''' nemmps, met dem <i lang=\"en\">MySQL</i> singe Version4.1 udder hüüter, dann künnt dat all di Zeishe kappott maache, die nit em <i lang=\"en\" title=\"American Standard Code for Information Interchange\">ASCII</i> sen, un domet all ding Sesherungskopieje kapott maache, wat mer nieh mieh retuur krijje kann.

Beim Shpeishere em '''binäre Fomaat''' deiht MediaWiki de Täxte, di em UTF-8 Fommaat kumme, en dä Daatebangk en binär kodeerte Daatefälder faßhallde.
Dat es flöcker un spaasaamer wi et UTF-8 Fommaat vum <i lang=\"en\">MySQL</i> un määd et müjjelesch, all un jeedes <i lang=\"en\">Unicode</i>-Zeishe met faßzehallde.

Beim Shpeishere em '''UTF-8 Fomaat''' deiht et <i lang=\"en\">MySQL</i> der Zeishesaz un de Kodeerung vun dä Daate känne, un kann se akeraat aanzeije un ömwandelle,
allerdengs künne kein Zeishe ußerhalv vum [http://de.wikipedia.org/wiki/Basic_Multilingual_Plane#Gliederung_in_Ebenen_und_Bl.C3.B6cke jrundlääje Knubbel för vill Shprooche (<i lang=\"en\">Basic Multilingual Plane — BMP</i>)] afjeshpeishert wääde.",
	'config-mysql-old' => 'Mer bruche <i lang="en">MySQL</i> $1 udder neuer. Em Momang es <i lang="en">MySQL</i> $2 aam Loufe.',
	'config-db-port' => 'De Pooz-Nommer (<i lang="en">port</i>) för de Daatebangk:',
	'config-db-schema' => 'Et Schema en de Datebangk för MediaWiki:',
	'config-db-schema-help' => 'För jewöhnlesch es dat Schema en Odenong.
Donn bloß jät draan ändere, wann De sescher weiß, dat dat nüüdesch es.',
	'config-sqlite-dir' => 'Dem <i lang="en">SQLite</i> sing Daateverzeishnes:',
	'config-sqlite-dir-help' => '<i lang="en">SQLite</i> hät all sing Daate zosamme en en einzel Dattei.

En dat Verzeishneß, wat De aanjiß, moß dat Web_ẞööver_Projramm beim Opsäze eren schriive dörrve.

Dat Verzeishneß sullt \'\'\'nit\'\'\' övver et Web zohjänglesch sin, dröm dom_mer et nit dohen, woh de <i lang="en">PHP</i>-Datteije sin.

Mer donn beim Opsäze zwa uß Vöörssh en <code lang="en">.htaccess</code> Dattei dobei, ävver wann di nit werrek, künnte Lück vun ußerhallef aan Ding Daatebangk_Dattei eraan kumme.
Doh shtonn Saache dren, wi de Addräße för de Metmaacher ier <i lang="en">e-mail</i> un de verschlößelte Paßwööter un de vershtoche un de fottjeschmeße Sigge un ander Saache ussem Wiki, di mer nit öffentlesch maache darref.

Donn Ding Daatebangk et beß janz woh anders hen, noh <code lang="en">/var/lib/mediawiki/\'\'wikiname\'\'</code> för e Beishpell.',
	'config-oracle-def-ts' => 'Tabälleroum för der Shtandattjebruch:',
	'config-oracle-temp-ts' => 'Tabälleroum för der Jebruch zweschedorsh:',
	'config-type-mysql' => '<i lang="en">MySQL</i>',
	'config-type-postgres' => '<i lang="en">PostgreSQL</i>',
	'config-type-sqlite' => '<i lang="en">SQLite</i>',
	'config-type-oracle' => '<i lang="en">Oracle</i>',
	'config-support-info' => 'MediaWiki kann met heh dä Daatebangk_Süßteeme zosamme jonn:

$1

Wann dat Daatebangk_Süßteem, wat De nämme wells, onge nit dobei es, dann donn desch aan di Aanleidonge hallde, di bovve verlengk sen, öm et op Dingem ẞööver singem Süßteem müjjelesh ze maache, se aan et Loufe ze krijje.',
	'config-support-mysql' => '* <i lang="en">$1</i> es dat vum MediaWiki et eets ongershtöz Daatebangksüßteem ([http://www.php.net/manual/de/mysql.installation.php Aanleidung för et Övversäze un Enreeschte von PHP met <i lang="en">MySQL</i> dobei, op Deutsch])',
	'config-support-postgres' => '* <i lang="en">$1</i> es e bikannt Daatebangksüßteem met offe Quälltäxde, un en och en Wahl nävve <i lang="en">MySQL</i> ([http://www.php.net/manual/de/pgsql.installation.php Aanleidung för et Övversäze un Enreeschte von PHP met <i lang="en">PostgreSQL</i> dobei, op Deutsch]) Et sinn_er ävver paa klein Fählershe bekannt, um kunne dat em Momang för et reschtijje Werke nit emfähle.',
	'config-support-sqlite' => '* <i lang="en">$1</i> es e eijfach Daatebangksüßteem, wat joot ongershtöz weed. ([http://www.php.net/manual/de/pdo.installation.php Aanleidong för et Övversäze un Enreeschte von PHP met <i lang="en">SQLite</i> dobei, op Deutsch])',
	'config-support-oracle' => '* <i lang="en">$1</i> es e jeschäfflesch Daatebangksüßteem för Ferme. ([http://www.php.net/manual/de/oci8.installation.php Aanleidong för et Övversäze un Enreeschte von PHP met <i lang="en">OCI8</i> dobei, op Deutsch])',
	'config-header-mysql' => 'De Enshtällunge för de <i lang="en">MySQL</i> Daatebangk',
	'config-header-postgres' => 'De Enshtällunge för de <i lang="en">PostgreSQL</i> Daatebangk',
	'config-header-sqlite' => 'De Enshtällunge för de <i lang="en">SQLite</i> Daatebangk',
	'config-header-oracle' => 'De Enshtällunge för de <i lang="en">Oracle</i> Daatebangk',
	'config-invalid-db-type' => 'Dat es en onjöltijje Zoot Daatebangk.',
	'config-missing-db-name' => 'Do moß jät enjävve för dä Name vun dä Daatebangk.',
	'config-missing-db-host' => 'Do moß jät enjävve för dä Name vun däm Rääschner met dä Daatebangk.',
	'config-missing-db-server-oracle' => 'Do moß jät enjävve för dä Daatebangk ier <i lang="en" title="Transparent Network Substrate">TNS</i>.',
	'config-invalid-db-server-oracle' => 'Dä Daatebangk ier <i lang="en" title="Transparent Network Substrate">TNS</i> kann nit „$1“ sin, dat es esu nit jöltesch.
Döh dörve bloß <i lang="en" title="American Standard Code for Information Interchange">ASCII</i> Boochshtaabe (a-z, A-Z), Zahle (0-9), Ongerstreshe (_), un Punkte (.) dren vörkumme.',
	'config-invalid-db-name' => 'Dä Daatebangk iere Name kann nit „$1“ sin, dä es esu nit jöltesch.
Döh dörve bloß <i lang="en" title="American Standard Code for Information Interchange">ASCII</i> Boochshtaabe (a-z, A-Z), Zahle (0-9), Ongerstresh (_), un Bendeshtresh (-) dren vörkumme.',
	'config-invalid-db-prefix' => 'Dä Vörsaz för de Name vun de Tabälle en de Daatebangk kann nit „$1“ sin, dä es esu nit jöltesch.
Döh dörve bloß <i lang="en" title="American Standard Code for Information Interchange">ASCII</i> Boochshtaabe (a-z, A-Z), Zahle (0-9), Ongerstreshe (_), un Bendeshtreshe (-) dren vörkumme.',
	'config-connection-error' => '$1.

Donn de Name för dä Rääschner, vun däm Aanwender för dä Zohjref op de Daatebangk, un et Paßwoot prööfe, repareere, un dann versöhg et norr_ens.',
	'config-invalid-schema' => 'Dat Schema för MediaWiki kann nit „$1“ sin, dä Name wöhr esu nit jöltesch.
Döh dörve bloß <i lang="en" title="American Standard Code for Information Interchange">ASCII</i> Boochshtaabe (a-z, A-Z), Zahle (0-9), un Ongerstreshe (_) dren vörkumme.',
	'config-db-sys-create-oracle' => 'Dat Projramm för MediaWiki opzesäze kann bloß <i lang="en">SYSDBA</i> bruche för ene neue Zohjang zor Daatebangk enzereeschte!',
	'config-db-sys-user-exists-oracle' => 'Dä Aanwender „$1“ för dä Zohjref op de Daatebangk jidd_et ald. <i lang="en">SYSDBA</i> kam_mer bloß bruche, för ene neue Zohjang enzereeschte!',
	'config-postgres-old' => 'Mer bruche <i lang="en">PostgreSQL</i> $1 udder neuer. Em Momang es <i lang="en">PostgreSQL</i> $2 aam Loufe.',
	'config-sqlite-name-help' => 'Söhk enen Name uß, dä Ding Wiki beschrief.
Donn kein Bendeschresch un Zweschräum en däm Name bruche.
Dä Name weed för der Dateiname för de <i lang="en">SQLite</i> Daatebangk jenumme.',
	'config-sqlite-parent-unwritable-group' => 'Mer kunnte dat Verzeischneß för de Daate, <code lang="en"><nowiki>$1</nowiki></code>, nit enreeschte, weil dat Projramm fö dä Web_ẞööver en dat Verzeischneß doh drövver, <code><nowiki>$2</nowiki></code>, nix erin donn darref.

Mer han dä Name vun däm Zohjang op et Süßteem eruß jefonge, onger dämm dat Web_ẞööver_Projramm läuf. Jez moß De bloß doför sorrje, dat dä en dat Verzeischneß  <code><nowiki>$3</nowiki></code> schrieve kann, öm heh wigger maache ze künne.
Ob enem Süßteem met <i lang="en">Unix</i>- oder <i lang="en">Linux</i> jeiht dat esu:
<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Mer kunnte dat Verzeischneß för de Daate, <code lang="en"><nowiki>$1</nowiki></code>, nit enreeschte, weil dat Projramm fö dä Web_ẞööver en dat Verzeischneß doh drövver, <code><nowiki>$2</nowiki></code>, nix erin donn darref.

Mer han dä Name vun däm Zohjang op et Süßteem nit eruß fenge künne, onger dämm dat Web_ẞööver_Projramm läuf. Jez moß De bloß doför sorrje, dat dä en dat Verzeischneß  <code><nowiki>$3</nowiki></code> schrieve kann, öm heh wigger maache ze künne. Wann De dä Name och nit weiß, maach, dat jeeder_ein doh schrieve kann.
Ob enem Süßteem met <i lang="en">Unix</i>- oder <i lang="en">Linux</i> jeiht dat esu:
<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Ene Fähler es opjetrodde beim Aanlääje vum Daate_Verzeishneß „$1“.
Don dä Plaz för et Shpeishere prööfe un Repareere, dann versöhg et norr_ens.',
	'config-sqlite-dir-unwritable' => 'Mer künne nit en dat Verzeishneß „$1“ schrieeve
Donn dohvun de Zohjreffs_Rääschde esu verändere, dat der Webßööver doh dren schrieeve kann, un dann versöhg et norr_ens.',
	'config-sqlite-connection-error' => '$1.

Donn onge dat Verzeishnes un der Name vun der Daatebangk prööfe un repareere, un dann versöhg_et norr-ens.',
	'config-sqlite-readonly' => 'En di Dattei <code lang="en">$1</code> künne mer nit schrieve.',
	'config-sqlite-cant-create-db' => 'Mer kunnte di Dattei <code lang="en">$1</code> för de Daatebangk nit aanlääje.',
	'config-sqlite-fts3-downgrade' => 'Dat PHP heh hät kein Ongershtözong för FTS3, dröm donn mer de Daatebangktabälle eronger shtoofe.',
	'config-can-upgrade' => 'Et sinn-er ald Daatebangktabelle vum MediaWiki en dä Daatebangk.
Öm di op der Shtand vum MediaWiki $1 ze bränge, donn jäz op „{{int:config-continue}}“ klecke.',
	'config-upgrade-done' => "Alles es jäz om neue Shtand.

Mer kann dat Wiki jäz [\$1 bruche].

Wann De Ding Dattei <code lang=\"en\">LocalSettings.php</code> neu schrieve wells, donn onge op dä Knopp klicke.
Dat dom_mer ävver '''nit vörschlonn'''em Jääjedeil, ußer, wann et Probleme mem Wiki jitt.",
	'config-upgrade-done-no-regenerate' => 'Alles es jäz om neue Shtand.

Mer kann dat Wiki jäz [$1 bruche].',
	'config-regenerate' => 'Donn de Dattei <code lang="en">LocalSettings.php</code> neu opsäze →',
	'config-show-table-status' => 'Et Kommando <code lang="en">SHOW TABLE STATUS</code> aan de Daatebangk es donävve jejange!',
	'config-unknown-collation' => "'''Opjepaß:''' De Daatabangk deiht en onbikannte Reijefollsch bruche, för Booshtaabe un Zeishe ze verjliishe un ze zotteere.",
	'config-db-web-account' => 'Dä Zohjang zor Daatebangk fö et Wiki',
	'config-db-web-help' => 'Donn ene Name un e Paßwoot för der Zohjang zor Daatebangk för et Wiki em nomaale Bedrief aanjävve.',
	'config-db-web-account-same' => 'Donn dersällve Zohjang nämme, wi heh beim Opsäze.',
	'config-db-web-create' => 'Donn dä Zohjang aanlääje, wann dä noch nit doh es.',
	'config-db-web-no-create-privs' => 'Dä Zohjang för et Opsäze es nit berääschtesch, ene ander Zohjan enzereeschte.
Dä aanjejovve Zohjang för der Nomaalbedrief moß dröm schunn enjersht sen!',
	'config-mysql-engine' => 'De Zoot udder et Fommaat vun de Tabälle:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' es fö jewöhnlesch et beß, weil vill Zohjreffe op eijmohl joot ongershtöz wääde.

'''MyISAM''' es flöcker op Rääschnere met bloß einem Minsch draan, un bei Wikis, di mer bloß lässe un nit schrieeve kann.
MyISAM-Daatebangke han em Schnett mieh Fähler un jon flöcker kappott, wi InnoDB-Daatebangke.",
	'config-mysql-charset' => 'Dä Daatebangk iere Zeishesaz:',
	'config-mysql-binary' => 'binär',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "Beim Shpeishere em '''binäre Fomaat''' deiht MediaWiki de Täxte, di em UTF-8 Fommaat kumme, en dä Daatebangk en binär kodeerte Daatefälder faßhallde.
Dat es flöcker un spaasaamer wi et UTF-8 Fommaat vum <i lang=\"en\">MySQL</i> un määd et müjjelesch, all un jeedes <i lang=\"en\">Unicode</i>-Zeishe met faßzehallde.

Beim Shpeishere em '''UTF-8 Fomaat''' deiht et <i lang=\"en\">MySQL</i> der Zeishesaz un de Kodeerung vun dä Daate känne, un kann se akeraat aanzeije un ömwandelle,
allerdengs künne kein Zeishe ußerhalv vum [http://de.wikipedia.org/wiki/Basic_Multilingual_Plane#Gliederung_in_Ebenen_und_Bl.C3.B6cke jrundlääje Knubbel för vill Shprooche (<i lang=\"en\">Basic Multilingual Plane — BMP</i>)] afjeshpeishert wääde.",
	'config-site-name' => 'Däm Wiki singe Name:',
	'config-site-name-help' => 'Dä douch em Tittel vun de Brauserfinstere un aan ätlije andere Shtälle op.',
	'config-site-name-blank' => 'Donn ene Name för di Sait aanjävve.',
	'config-project-namespace' => 'Dä Name för et Appachtemang övver et Projäk:',
	'config-ns-generic' => 'Projäk',
	'config-ns-site-name' => 'Et sällve wi däm Wiki singe Name: $1',
	'config-ns-other' => 'Andere (jiff aan wälshe)',
	'config-ns-other-default' => 'MingWiki',
	'config-project-namespace-help' => "Noh dämm Vörbeld vun de Wikipeedija, donn vill Wikis dänne ier Sigge övver et Wiki un sing Rääjelle vun dä Sigge mem Enhald vum Wiki tränne, un en enem extra Appachtemang för et „'''Projäk'''“ afflääje.
Sigge en däm Appachtemang fange all med enem beshtemmpte Vörsaz aan, däm Name vum Appachtemang, un dä moß De heh faßlääje.
Dä Name kann beshtemmpte Zeiche nit enthallde, wi „#“ un „:“ un et es Tradizjuhn, dat hä vum Name vum Wiki her kütt.",
	'config-ns-invalid' => 'Dat aanjejovve Appachtemang „<nowiki>$1</nowiki>“ es nit jöltesch.
Nemm ene andere Name för däm Wiki sing eije Appachtemang.',
	'config-ns-conflict' => 'Dat aanjejovve Appachtemang „<nowiki>$1</nowiki>“ kütt ald als Standatt-Appachtemang em MediaWiki vör.
Nemm ene andere Name för däm Wiki sing eije Appachtemang.',
	'config-admin-box' => 'Der Zohjang för der eezte Wiki_Köbes',
	'config-admin-name' => 'Metmaacher_Name:',
	'config-admin-password' => 'Et Paßwoot:',
	'config-admin-password-confirm' => 'Norrens dat Paßwoot:',
	'config-admin-help' => 'Jif Dinge leevste Name als Metmaacher för Desch aan, för e Beishpell „Schmitzens Pitter“
— Dat weed dä Name wääde, met dämm De Desch enlogge deihs.',
	'config-admin-name-blank' => 'Jiv ene Metmaacher_Name en för dä Wiki-Köbes.',
	'config-admin-name-invalid' => '„<nowiki>$1</nowiki>“ es keine jöltijje Metmaacher_Name.
Jiv ene joode Name en!',
	'config-admin-password-blank' => 'Do mos_e Paßwoot för dä Wiki_Köbes aanjävve!',
	'config-admin-password-same' => 'Dat Paßwoot un dä Name dörve nit ejaal sin!',
	'config-admin-password-mismatch' => 'Di Paßwööter sin ongerscheidlesh!',
	'config-admin-email' => 'Addräß för de <i lang="en">e-mail</i>:',
	'config-admin-email-help' => 'Jiv heh di Adräß för de <i lang="en">e-mail</i> aan, woh De <i lang="en">e-mail</i> vun ander Metmaacher uss_em Wiki hen krijje wells, di et Der müjjelesh määt, Ding Paßwoot automatetsch truusche ze lohße, un woh Nohreeshte övver veränderte Sigge op Dinge Oppaßleß hen jescheck wääde sulle.
De kanns dat Fäld ävver och läddesch lohße.',
	'config-admin-error-user' => 'Beim Enreeshte vum Zohjang för dä Wiki_Köbes „<nowiki>$1</nowiki>“ es ene Fähler em Wiki opjetrodde.',
	'config-admin-error-password' => 'Beim Paßwoot-Säze för dä Wiki_Köbes „<nowiki>$1</nowiki>“ es ene Fähler em Wiki opjetrodde.: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Do häs_en onjöltijje Addräß för de <i lang="en">e-mail</i> aanjejovve.',
	'config-subscribe' => 'Donn de [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce <i lang="en">e-mail</i>-Leß met de Aanköndijunge vum MediaWiki] abonnere.',
	'config-subscribe-help' => 'Do kumme bloß winnish Meddeilunge un di jonn övver neu Versiohne vom MediaWiki un weeshtejje Saache vun däm sing Sesherheit.
Do sullts se abbonneere, un Ding MediWiki_Projramme op der neue Shtand bränge, wann neu Version eruß kumme.',
	'config-almost-done' => 'Do bes beinah dorsh!
Do künnts jez der Räß vun de einzel Enshtellunge övverjonn, un et Wiki tiräktemang fäädesch opsäze.',
	'config-optional-continue' => 'Wells De noch mieh Frore jeshtallt krijje un noch mieh Enshtällunge maache?',
	'config-optional-skip' => 'Nä, lohß dä Ömshtand, donn eifarr_et Wiki opsäze.',
	'config-profile' => 'Enshtällunge för de Metmaacher ier Rääschte:',
	'config-profile-wiki' => 'E tradizjonäll offe Wiki',
	'config-profile-no-anon' => 'Schriever möße enlogge',
	'config-profile-fishbowl' => 'Bloß ußdröcklesch zohjelohße Schriever',
	'config-profile-private' => 'E jeschloße Privat_Wiki',
	'config-profile-help' => "Wikis loufe et beß, wam_mer esu vill Lück wi müjjelesch draan metmaache un schrieve löht.
Met MediaWiki es et ejfach, de neuste Änderunge ze beloore un wat ahnungslose udder fiese Lück kapott jemaat han wider retuur ze maache.

Bloß, mänsh eine häd_eruß jefonge, dat mer MediaWiki jood en en jruuße Zahl ongerscheidlijje Rolle bruche kann, un nit emmer es et leish, ene vum onverfälschte Wiki_Wääsch ze övverzeuje.
Esu häß De de Wahl:

'''{{int:config-profile-wiki}}''' löht jeder_ein metschrieve, och ohne enzelogge.

'''{{int:config-profile-no-anon}}''', dat sorsh för mieh seeshbaa Verantwootlishkeite, künnt ävver zohfällije Methellefer verschrecke.

'''{{int:config-profile-fishbowl}}''' löht nor de ußjesöhk Metmaacher schrieve, ävver de janze Öffentleshkeit kann et lässe un süht och de ällder Versione, un wat wää wann draan jedonn hät.

'''{{int:config-profile-private}}''' kann nur lässe, wäh en et Wiki zohjelohße es, un desellve Jropp kann uch schrieve.

Noch ander un un opwändijere Enshtellunge för de Rääschte sin müjjelesch, wann et Wiki ens aam Loufe es. Loor Der doför de [http://www.mediawiki.org/wiki/Manual:User_rights zopaß Hölp em Handbooch] aan.",
	'config-license' => 'Urhävverrääsch un Lizänz:',
	'config-license-none' => 'Kein Fooßreih övver de Lizänz',
	'config-license-cc-by-sa' => '<i lang="en">Creative Commons</i> Der Name moß jenannt sin, et Wiggerjävve es zohjelohße onger dersellve Bedengunge',
	'config-license-cc-by-nc-sa' => '<i lang="en">Creative Commons</i> Nit för e Jeschäff ze maache, et Wiggerjävve es zohjelohße unger dersellve Bedengunge',
	'config-license-cc-0' => '<i lang="en">Creative Commons</i> „Noll“',
	'config-license-gfdl-old' => 'De <i lang="en">GNU</i>-Lizänz för frei Dokemäntazjuhne Version 1.2',
	'config-license-gfdl-current' => 'De <i lang="en">GNU</i>-Lizänz för frei Dokemäntazjuhne, Version 1.3 udder en späädere',
	'config-license-pd' => 'Allmende (jemeinfrei, <i lang="en">public domain</i>)',
	'config-license-cc-choose' => 'En <i lang="en">Creative Commons</i> Lizänz, sellver ußjesöhk:',
	'config-license-help' => "Ättlijje öffentleje Wikis donn iehr Beidrääsh onger en [http://freedomdefined.org/Definition frei Lizänz] shtelle.
Dat hellef, e Jeföhl vun Jemeinsamkeid opzeboue, un op lange Seesh emmer wider Beidrääsch ze krijje.
Dat es nit onbedengk nüüdesh för e Jeschäffs- udder Privaat_Wiki.

Wä Stöcke uß de Wikipedia bruche well, un han well, dat de Wikipedia uss_em eije Wiki jät övvernämme kann, sullt mer „'''<i lang=\"en\">Creative Commons</i>, dem Schriever singe Name moß jenannt wääde, un Wiggerjävve zoh dersellve Bedengunge es zohjelohße'''“ ußwähle.

De su jenannte '''<i lang=\"en\">GNU Free Documentation License</i>''' (de freije Lizänz för Dokemäntazjuhne vun dä GNU) sen de ahle Lizänzbedenonge vun de Wikipedia. Se es emmer noch in Odenong un jöltesch, ävver se hädd e paa Eijeschaffte, die et Wiggerjävve, et widder Verwände un et Ußlääje schwieeresch maache.",
	'config-email-settings' => 'Enschtellunge för de <i lang="en">e-mail</i>',
	'config-enable-email' => 'De <i lang="en">e-mail</i> noh druße zohlohße',
	'config-enable-email-help' => 'Sulle <i lang="en">e-mails</i> zohjelohße sin, moß mer, domet et noher flupp, de [http://www.php.net/manual/en/mail.configuration.php Enschtellunge em PHP för de <i lang="en">e-mails</i>] zopaß jemaat han.
Wann kein <i lang="en">e-mails</i> nüüdesch sin, kam_mer se heh afschallde.',
	'config-email-user' => '<i lang="en">e-mails</i> zwesche de Metmaacher zohlohße',
	'config-email-user-help' => 'Määt et müjjelesch, dat sesch de Metmaacher jääjesiggesch <i lang="en">e-mails</i> schecke künne, wann se dat en iehre eije Enschtellunge och enjeschalldt han.',
	'config-email-usertalk' => '<i lang="en">e-mails</i> mem Bescheid zohlohße, dat einem sing Klaafsigg verändert woodt',
	'config-email-usertalk-help' => 'Maach et müjjelesch, dat Metmaaacher en iere Enstellunge <i lang="en">e-mails</i> mem Bescheid zohlohße, dat einem sing Klaafsigg verändert woodt.',
	'config-email-watchlist' => 'Nohreeschte övver Änderonge aan Sigg op de Opaßleßte zohlohße',
	'config-email-watchlist-help' => 'Lohß Metmaacher Nohreeshte övver de Sigge op dänne iehr Oppaßleß krijje, wann se et en iehre Enschtellonge ußjewählt han.',
	'config-email-auth' => 'Donn de Övverprööfung för Zohjangsberääschtejunge övver de <i lang="en">e-mail</i> zohlohße',
	'config-email-auth-help' => 'Wann dat aanjeschald es, möße Metmaacher, di iehr Adräß för de <i lang="en">e-mail</i> neu aanjävve udder ändere, di Addräß övver ene Lengk beschtäätejje, dä se met de <i lang="en">e-mail</i> jescheck krijje.
Bloß aan esu beschtääteschte Adräße deiht et Wiki <i lang="en">e-mails</i> schecke, Di künne vun annder Metmaachere kumme, udder vum Wiki sellver, wann en Sigg en däm Metmaacher singe Oppaßleß verändert woode es.
Mer \'\'\'schlonn vör, dat aanzeschallde\'\'\' för öffentlesch Wikis, weil sönß zoh leisch Driß mem Wiki singe <i lang="en">e-mail</i> jemaat wääde künnt.',
	'config-email-sender' => 'De Adräß för de Antwoote op <i lang="en">e-mails</i>:',
	'config-email-sender-help' => 'Jiff de Adräß för de <i lang="en">e-mail</i> en, woh Antwoote ob em Wiki singe <i lang="en">e-mails</i> hen jonn sulle.
Dat es och de Adräß, woh de <i lang="en">e-mails</i> met Fählermäldonge hen jon.
Vill ẞöövere för de <i lang="en">e-mail</i> welle winnischßdens ene jöltijje Domain en dä Adräß han.',
	'config-upload-settings' => 'Belder un Datteie huh laade',
	'config-upload-enable' => 'Belder un Datteie huh laade zohlohße',
	'config-upload-help' => 'Datteije huh ze laade künnt e Risiko för dem ẞööver singe Sescherheit sin.
Mieh doh drövver kam_mer em [http://www.mediawiki.org/wiki/Manual:Security Kapitel övver de Sescherheit] em Handbooch lässe.

Öm et Huhlaade zohzelohße donn de Rääschde för der Zohjreff op dat Ongerverzeischneß <code lang="en">images</code> em MediaWiki singem Houpverzeischneß esu enshtälle, dat et Webßööverprojramm doh Datteije un Verzeischneße eren schrieve kann.
Donoh donn heh di Saach zohlohße.',
	'config-upload-deleted' => 'Dat Verzeishneß för fottjeschmeße Datteije:',
	'config-upload-deleted-help' => 'Söhk e Verzeijschneß uß för de fottjeschmeße Datteije vum Wiki dren afzelääje.
Et bäß es, wam_mer vum <i lang="en">world wide web</i> doh nit drahn kumme kann.',
	'config-logo' => 'Dem Wiki singem Logo sing <i lang="en">URL</i>:',
	'config-logo-help' => 'De Schtandart_Bedeen_Bovverfläsch vum MediaWiki hät e Logo bovve en der Eck met 135x160 Pixele.
Donn e zopaß Logo huh laade, un donn däm sing URL heh endraare.

Wells De kei Logo han, draach heh nix en.',
	'config-instantcommons' => 'Donn <i lang="en">InstantCommons</i> zohlohße.',
	'config-instantcommons-help' => '<i lang="en">[http://www.mediawiki.org/wiki/InstantCommons InstantCommons]</i> es en Eijeschaff, di et för Wikis müjjelesch määt, Belder, Tondatteie un ander Meedijedatteie enzebenge, di op dä Webßait vun de <i lang="en">[http://commons.wikimedia.org/ Wikimedia Commons]</i> ongerjebraat sin. Öm dat noze ze künne, moß dä ẞööver vum MediaWiki en Verbendung nohm Internet opnämme künne.

Mieh Aanjaabe doh drövver un en Aanleidung, wi mer och ander Wikis ußer de <i lang="en">Wikimedia Commons</i> doför enreeschte kann, fengk mer em [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos Handbooch].',
	'config-cc-error' => 'Et Ußsöhke övver de <i lang="en">Creative Commons</i> iehr Projramm zum Lizänzbeshtemme hät nix jebraat.
Donn de Lizänz sellver beshtemme.',
	'config-cc-again' => 'Noch ens neu ußsöhke&nbsp;…',
	'config-cc-not-chosen' => 'Söhk uß, wat för en Lizänz vun de <i lang="en">Creative Commons</i> De han wells, un donn dann op „<i lang="en">proceed</i>“ klecke.',
	'config-advanced-settings' => 'Fottjeschredde Enshtellunge',
	'config-cache-options' => 'Enshtällunge för et Faßhallde vun Objäkte em Zweschsheisher:',
	'config-cache-help' => 'Objäkte em Zwescheshpeisher faßhallde, dat heiß öff jebruchte Daate en der <i lang="en">cache</i> donn, bruche mer, öm MediaWiki flöcker ze maache, 
Meddlere un jruuße Wiki-ẞaits sullte dat onbedengk ußnoze, un och bei klein Wikis weed mer et jood merke.',
	'config-cache-none' => 'Keine Zweschshpeijsher (Et jeid_em Wiki nix verloore, ußer velleish Schnälleshkeid wann vill loss es)',
	'config-cache-accel' => 'Ene Objäk<i lang="en">cache</i> vum PHP (<i lang="en">APC</i>, <i lang="en">eAccelerator</i>, <i lang="en">XCache</i>, udder <i lang="en">WinCache</i>)',
	'config-cache-memcached' => 'Donn der <code lang="en">memcached</code> ẞööver nämme (Määt extra Enshtellunge un Opsäze nüüdesch)',
	'config-memcached-servers' => 'De <code lang="en">memcached</code> ßöövere:',
	'config-memcached-help' => 'Donn de Leß aanhjävve, met de <i lang="en">IP</i>-Addräße för der <code lang="en">memcached</code> ẞööver ze bruche.
Se sullte ein pro Reih opjeschrevve sin, un en Pooz (<i lang="en">port</i>) ier Nommer han, För e Beishpell, esu:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Do häss der <code lang="en">memcached</code> als Dinge Zoot vun Zwescheshpeijscher aanjejovve, ävver nit eine ẞööver doför.',
	'config-memcache-badip' => 'Do häss en onjöltijje <i lang="en">IP</i>-Addräß för der <code lang="en">memcached</code> ẞööver aanjejovve: $1.',
	'config-memcache-noport' => 'Do has kein Pooz (<code lang="en">port</code>) Nommer aanjejovve för mem <code lang="en">memcached</code> ẞööver ze bruche: $1.
Wann De di Nommer nit weiß, der Shtandatt es 11211.',
	'config-memcache-badport' => 'Dem <code lang="en">memcached</code> ẞööver singe Pooz (<code lang="en">port</code>) Nommere sullte zwesche $1 un $2 sin.',
	'config-extensions' => 'Projramm-Zosätz (<i lang="en">extensions</i>)',
	'config-extensions-help' => 'Di bovve opjeleß Zohsazprojramme för et MediaWiki sin em Verzeischneß <code lang="en">./extensions</code> ald ze fenge.

Do kann se heh un jez aanschallde, ävver se künnte noch zohsäzlesch Enshtellunge bruche.',
	'config-install-alreadydone' => "'''Opjepaß:'''
Et sühd esu uß, wi wann De MediaWiki ald enshtalleet hätß, un wöhrs aam Versöhke, dat norr_ens ze donn.
Jang wigger op de näähßte Sigg.",
	'config-install-begin' => 'Wann De op „{{int:config-continue}}“ klecks, jeiht de Enshtallazjuhn vum MediaWiki loßß.
Wann De noch Änderonge maache wells, dann kleck op „{{int:config-back}}“.',
	'config-install-step-done' => 'jedonn',
	'config-install-step-failed' => 'donävve jejange',
	'config-install-extensions' => 'Zohsazprojramme enjeschloße',
	'config-install-database' => 'Ben de Daatebangk aam ennreeschte.',
	'config-install-pg-schema-not-exist' => 'Dat Scheema för <i lang="en">PostgreSQL</i> es nit doh.',
	'config-install-pg-schema-failed' => 'Et Tabälle-Opsäze es donävve jejange.
Donn doför sorrje, dat dä Daatebangk-Aanwänder „$1“ en dämm Daatebangkscheema „$2“ schrieve kann.',
	'config-install-pg-commit' => 'Ben de Änderonge aam ennbränge.',
	'config-install-pg-plpgsql' => 'Ben noh dä Daatebangkshprooch <code lang="en">PL/pgSQL</code> aam söhke.',
	'config-pg-no-plpgsql' => 'Do moß de Daatebangkshprooch <code lang="en">PL/pgSQL</code> en dä Daatebangk $1 enreeschte.',
	'config-pg-no-create-privs' => 'Dä Daatebangk-Aanwänder för et Enreeschte hät nit jenooch Rääschde, öm ene andere Daatebangk-Aanwänder en dä Daatebangk aanzelääje.',
	'config-install-user' => 'Ben unse Daatebangk-Aanwänder en de Daatebangk am aanlääje.',
	'config-install-user-alreadyexists' => 'Dä Aanwender „$1“ för dä Zohjref op de Daatebangk kann nit aanjelaat wääde, et jidd_en alld.',
	'config-install-user-create-failed' => 'Dä Aanwender „$1“ för dä Zohjref op de Daatebangk kunnt nit aanjelaat wääde, wäje: <code lang="en">$2</code>',
	'config-install-user-grant-failed' => 'Däm Daatebangk-Aanwänder sing Beräschtijunge ze säze däät nit fluppe wääje: $2',
	'config-install-tables' => 'Ben de Daatebangk-Tabälle aam aanlääje.',
	'config-install-tables-exist' => "'''Opjepaß''': Et schingk, dem MediaWiki sing Tabälle sin alt doh.
Doh dom_mer nix aanlääje.",
	'config-install-tables-failed' => "'''Fähler''': De Tabälle kunnte nit aanjelaat wääde, wääje: $1",
	'config-install-interwiki' => 'Ben de Engerwiki-Tabäll met de shtandattmääßejje Daate aam fölle.',
	'config-install-interwiki-list' => 'Mer kunnte de Dattei <code lang="en">interwiki.list</code> nit fenge.',
	'config-install-interwiki-exists' => "'''Opjepaß''': En der Engewiki-Tabäll schingk alt jät dren ze shtonn.
Doh dom_mer nix dobei.",
	'config-install-stats' => 'De Shtatestek-Zahle wääde op Aanfang jeshtallt.',
	'config-install-keys' => 'Jeheime Schlößel wääde opjebout.',
	'config-insecure-keys' => "'''Opjepaß:''' {{PLURAL:$2|Ene jeheime Schlößel|Jeheim Schlößele|Keine jeheime Schlößel}} ($1) {{PLURAL:$2|es|sin|es}} automattesch aanjelaat woode. {{PLURAL:$2|Dä es|Di sin|Hä es}} ävver nit onbedengk janz sescher. Övverlääsch Der, {{PLURAL:$2|dä|di|en}} norr_ens vun Hand ze ändere.",
	'config-install-sysop' => 'Dä Zohjang för der Wiki-Köbes weed aanjelaat.',
	'config-install-subscribe-fail' => 'Mer künne de <i lang="en">e-mail</i>-Leß <code lang="en">mediawiki-announce</code> nit abonneere.',
	'config-install-mainpage' => 'Ben de Houpsigg med enem shtandatmääßeje Enhald aam aanlääje',
	'config-install-extension-tables' => 'Ben Datebangk-Tabälle för de Zohsazprojramme aam ennreschte',
	'config-install-mainpage-failed' => 'Kunnt de Houpsigg nit afshpeishere: $1',
	'config-install-done' => "'''Jlöckwonsch!'''
MediaWiki es jetz enstalleet.

Et Projramm zom Enreeschte hät en Dattei <code lang=\"en\">LocalSettings.php</code> aanjelaat.
Doh sin de Enstellunge vum Wiki dren.

Do weeß se eronge laade möße un dann en dem Wiki sing Aanfangsverzeishnes donn möße, et sellve Verzeisneß, woh di Dattei <code lang=\"en\">index.php</code> dren litt. Dat Erongerlaade sullt automattesch aanjefange han.

Wann domet jet nit jeflupp hät, udder De di Dattei norr_ens han wells, donn op dä Lengk heh dronger klecke:

\$3

'''Opjepaß''': Wann De dat jez nit deihß es Alles verschött wat De jemaat häs, weil di Dattei fott es en däm Momang, woh heh dat Projamm aam Engk es.

Wann De mem Ronger- un widder Huhlaade fäädesh bes, kanns De '''[\$2 en Ding Wiki jonn]'''.",
	'config-download-localsettings' => 'Donn de Dattei <code lang="en">LocalSettings.php</code> eronger laade',
	'config-help' => 'Hölp',
);

/** Kurdish (Latin) (Kurdî (Latin))
 * @author George Animal
 */
$messages['ku-latn'] = array(
	'config-page-language' => 'Ziman',
	'config-page-name' => 'Nav',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'config-desc' => 'Den Installatiounsprogramm vu MediaWiki',
	'config-title' => 'MediaWiki $1 Installatioun',
	'config-information' => 'Informatioun',
	'config-localsettings-upgrade' => "'''Opgepasst''': E Fichier <code>LocalSettings.php</code> gouf fonnt.
Är Software kann aktualiséiert ginn, setzt w.e.g. de Wäert vum <code>\$wgUpgradeKey</code> an d'Këscht.
Dir fannt en am LocalSettings.php.",
	'config-localsettings-key' => 'Aktualisatiounsschlëssel:',
	'config-localsettings-badkey' => 'De Schlëssel deen Dir aginn hutt ass net korrekt',
	'config-session-error' => 'Feeler beim Starte vun der Sessioun: $1',
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
	'config-page-existingwiki' => 'Wiki déi et gëtt',
	'config-help-restart' => 'Wëllt dir all gespäichert Donnéeë läschen déi dir bis elo aginn hutt an den Installatiounsprozess nei starten?',
	'config-restart' => 'Jo, neistarten',
	'config-welcome' => "=== Iwwerpréifung vum Installatiounsenvironnement ===
Et gi grondsätzlech Iwwerpréifunge gemaach fir ze kucken ob den Environnment gëeegent ass fir MediaWiki z'installéieren.
Dir sollt d'Resultater vun dëser Iwwerpréifung ugi wann Dir während der Installatioun Hëllef braucht.",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki Haaptsäit]
* [http://www.mediawiki.org/wiki/Help:Contents Benotzerguide]
* [http://www.mediawiki.org/wiki/Manual:Contents Guide fir Administrateuren]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>Liest dëst</doclink>
* <doclink href=ReleaseNotes>Informatioune vun der aktueller Versioun</doclink>
* <doclink href=Copying>Lizenzbedingungen</doclink>
* <doclink href=UpgradeDoc>Aktualisatioun</doclink>',
	'config-env-good' => 'Den Environement gouf nogekuckt.
Dir kënnt MediaWiki installéieren.',
	'config-env-bad' => 'Den Environnement gouf iwwerpréift.
Dir kënnt MediWiki net installéieren.',
	'config-env-php' => 'PHP $1 ass installéiert.',
	'config-unicode-using-utf8' => "Fir d'Unicode-Normalisatioun gëtt dem Brion Vibber säin <code>utf8_normalize.so</code> benotzt.",
	'config-no-db' => 'Et konnt kee passenden Datebank-Driver fonnt ginn!',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] ass installéiert',
	'config-apc' => '[http://www.php.net/apc APC] ass installéiert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] ass installéiert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] ass installéiert',
	'config-diff3-bad' => 'GNU diff3 gouf net fonnt.',
	'config-no-uri' => "'''Feeler:''' Déi aktuell URI konnt net festgestallt ginn.
Installatioun ofgebrach.",
	'config-db-type' => 'Datebanktyp:',
	'config-db-host-oracle' => 'Datebank-TNS:',
	'config-db-wiki-settings' => 'Dës Wiki identifizéieren',
	'config-db-name' => 'Numm vun der Datebank:',
	'config-db-name-oracle' => 'Datebankschema:',
	'config-db-install-account' => "Benotzerkont fir d'Installatioun",
	'config-db-username' => 'Datebank-Benotzernumm:',
	'config-db-password' => 'Passwuert vun der Datebank:',
	'config-db-install-help' => 'Gitt de Benotzernumm an Passwuert an dat wàhrend der Installatioun benotzt gëtt fir sech mat der Datebank ze verbannen.',
	'config-db-account-lock' => 'De selwechte Benotzernumm a Passwuert fir déi normal Operatioune benotzen',
	'config-db-wiki-account' => 'Benotzerkont fir normal Operatiounen',
	'config-db-wiki-help' => "Gitt de Benotzernumm an d'Passwuert an dat benotzt wäert gi fir sech bei den normale Wiki-Operatiounen mat der Datebank ze connectéieren.
Wann et de Kont net gëtt, a wann den Installatiouns-Kont genuch Rechter huet, gëtt dëse Benotzerkont opgemaach mat dem Minimum vu Rechter déi gebraucht gi fir dës Wiki bedreiwen ze kënnen.",
	'config-db-charset' => 'Zeechesaz (character set) vun der Datebank',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binair',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-mysql-old' => 'MySQL $1 oder eng méi nei Versioun gëtt gebraucht, Dir hutt $2.',
	'config-db-port' => 'Port vun der Datebank:',
	'config-db-schema' => 'Schema fir MediaWiki',
	'config-db-schema-help' => "D'Schemaen hei driwwer si gewéinlech korrekt.
Ännert se nëmme wann Dir wësst datt et néideg ass.",
	'config-sqlite-dir' => 'Repertoire vun den SQLite-Donnéeën',
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
	'config-missing-db-server-oracle' => 'Dir musst e Wäert fir "Datebank-TNS" uginn',
	'config-db-sys-user-exists-oracle' => 'De Benotzerkont "$1" gëtt et schonn. SYSDBA kann nëmme benotzt gi fir en neie Benotzerkont opzemaachen.',
	'config-postgres-old' => 'PostgreSQL $1 oder eng méi nei Versioun gëtt gebraucht, Dir hutt $2.',
	'config-sqlite-name-help' => 'Sicht en Numm deen Är wiki identifizéiert.
Benotzt keng Espacen a Bindestrécher.
E gëtt fir den Numm vum SQLite Date-Fichier benotzt.',
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
	'config-admin-error-password' => 'Interne Feeler beim Setze vum Passwuert fir den Admin "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Dir hutt eng E-Mailadress aginn déi net valabel ass',
	'config-subscribe' => "Sech op d'[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Ukënnegunge vun neie Versiounen] abonnéieren.",
	'config-almost-done' => "Dir sidd bal fäerdeg!
Dir kënnt elo déi Astellungen déi nach iwwreg sinn iwwersprangen an d'Wiki elo direkt installéieren.",
	'config-optional-continue' => 'Stellt mir méi Froen.',
	'config-optional-skip' => "Ech hunn es genuch, installéier just d'Wiki.",
	'config-profile' => 'Profil vun de Benotzerrechter:',
	'config-profile-wiki' => 'Traditionell Wiki',
	'config-profile-no-anon' => 'Uleeë vun engem Benotzerkont verlaangt',
	'config-profile-fishbowl' => 'Nëmmen autoriséiert Editeuren',
	'config-profile-private' => 'Privat Wiki',
	'config-license' => 'Copyright a Lizenz:',
	'config-license-none' => 'Keng Lizenz ënnen op der Säit',
	'config-license-pd' => 'Ëffentlechen Domaine',
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
	'config-pg-no-plpgsql' => "Fir d'Datebank $1 muss d'Datebanksprooch PL/pgSQL installéiert ginn",
	'config-install-user' => 'Datebank Benotzer uleeën',
	'config-install-user-alreadyexists' => 'De Benotzer "$1" gëtt et schonn!',
	'config-install-user-create-failed' => 'D\'Opmaache vum Benotzer "$1" huet net fonctionnéiert: $2',
	'config-install-tables' => 'Tabelle ginn ugeluecht',
	'config-install-interwiki' => 'Standard Interwiki-Tabell gëtt ausgefëllt',
	'config-install-interwiki-list' => 'De Fichier <code>interwiki.list</code> gouf net fonnt.',
	'config-install-stats' => 'Initialisatioun vun de Statistiken',
	'config-install-keys' => 'Generéiere vum Geheimschlëssel',
	'config-install-sysop' => 'Administrateur Benotzerkont gëtt ugeluecht',
);

/** Malagasy (Malagasy)
 * @author Jagwar
 */
$messages['mg'] = array(
	'config-session-error' => 'Hadisoana teo am-panombohana ny fidirana : $1',
	'config-your-language' => 'Ny fiteninao :',
	'config-wiki-language' => "Fiteny ho ampiasain'ny wiki :",
	'config-back' => '← Miverina',
	'config-continue' => 'Manohy →',
	'config-page-language' => 'Fiteny',
	'config-page-welcome' => "Tonga soa eto amin'i MediaWiki !",
	'config-page-dbconnect' => "Hiditra eo amin'i banky angona",
	'config-page-name' => 'Anarana',
	'config-page-readme' => 'Vakio aho',
	'config-page-copying' => 'Hala-tahaka',
	'config-page-upgradedoc' => 'Fanavaozina',
	'config-page-existingwiki' => 'Wiki efa misy',
	'config-help-restart' => "Tianao hofafana avokoa ve ny data voaangona natsofokao ary hamerina ny fizotran'ny fametrahana ?",
	'config-restart' => 'Eny, avereno atao',
	'config-db-username' => "Anaram-pikamban'ny banky angona :",
	'config-db-password' => "Tenimiafin'ny banky angona :",
	'config-header-mysql' => "Parametatr'i MySQL",
	'config-header-sqlite' => "Parametatr'i SQLite",
	'config-header-oracle' => "Parametatr'i Oracle",
	'config-mysql-innodb' => 'innoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-ns-generic' => 'Tetikasa',
	'config-ns-other' => 'Hafa (lazao)',
	'config-admin-name' => 'Ny anaranao :',
	'config-admin-password' => 'Tenimiafina :',
	'config-admin-email' => 'Adiresy imailaka :',
	'config-profile-wiki' => 'Wiki tsotra',
	'config-profile-no-anon' => 'Mila mamorona kaonty',
	'config-profile-fishbowl' => 'Mpanova mahazo alalana ihany',
	'config-profile-private' => 'Wiki tsy sarababem-bahoaka',
	'config-license' => 'Zom-pamorona ary lisansa :',
	'config-license-none' => 'Tsy misy lisansa any an-tongom-pejy',
	'config-email-user' => 'Avela mifandefa imailaka ny mpikambana',
	'config-email-user-help' => "Hahafahan'ny mpikambana mifandefa imailaka raha omen'ny mpikambana alalana ao amin'ny safidiny.",
	'config-upload-deleted' => "Petra-drakitra ho an'ny rakitra voafafa :",
	'config-extensions' => 'Fanitarana',
	'config-install-step-done' => 'vita',
	'config-install-step-failed' => 'hadisoana',
	'config-install-user' => "Famoronana mpapiasan'ny banky angona",
	'config-install-tables' => 'Famoronana tabilao',
	'config-install-stats' => 'Fanombohana ny statistika',
	'config-install-keys' => 'Fanamboarana lakile miafina$',
	'config-help' => 'fanoroana',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'config-desc' => 'Инсталатор на МедијаВики',
	'config-title' => 'Инсталатор на МедијаВики $1',
	'config-information' => 'Информации',
	'config-localsettings-upgrade' => 'Востановена е податотека <code>LocalSettings.php</code>.
За да ја надградите инсталцијава, внесете ја вредноста на <code>$wgUpgradeKey</code> во полето подолу.
Тоа е го најдете во LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Утврдено е присуството на податотеката „LocalSettings.php“.
За да ја надградите инсталацијата, пуштете ја „update.php“ наместо горенаведената.',
	'config-localsettings-key' => 'Надградбен клуч:',
	'config-localsettings-badkey' => 'Клучот што го наведовте е погрешен',
	'config-upgrade-key-missing' => 'Востановена е постоечка инсталација на МедијаВики.
За да ја надградите, вметнете го следниов ред на дното од вашата страница LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Постоечката страница LocalSettings.php е нецелосна.
Не е поставена променливата $1.
Изменете ја страницата LocalSettings.php така што ќе ѝ зададете вредност на променливата, па стиснете на „Продолжи“.',
	'config-localsettings-connection-error' => 'Се појави грешка при поврзувањето со базата користејќи ги поставките назначени во LocalSettings.php или AdminSettings.php. Исправете ги овие поставки и обидете се повторно.

$1',
	'config-session-error' => 'Грешка при започнување на сесијата: $1',
	'config-session-expired' => 'Вашите сесиски податоци истекоа.
Поставките на сесиите траат $1.
Нивниот рок можете да го зголемите со задавање на <code>session.gc_maxlifetime</code> во php.ini.
Почнете ја инсталацијата одново.',
	'config-no-session' => 'Вашите сесиски податоци се изгубени!
Погледајте во php.ini дали <code>session.save_path</code> е поставен во правилна папка.',
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
	'config-page-existingwiki' => 'Постоечко вики',
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
* [http://www.mediawiki.org/wiki/Help:Contents Водич за корисници]
* [http://www.mediawiki.org/wiki/Manual:Contents Водич за администратори]
* [http://www.mediawiki.org/wiki/Manual:FAQ ЧПП]
----
* <doclink href=Readme>Прочитај ме</doclink>
* <doclink href=ReleaseNotes>Белешки за изданието</doclink>
* <doclink href=Copying>Копирање</doclink>
* <doclink href=UpgradeDoc>Надградување</doclink>',
	'config-env-good' => 'Околината е проверена.
Можете да го инсталирате МедијаВики.',
	'config-env-bad' => 'Околината е проверена.
Не можете да го инсталирате МедијаВики.',
	'config-env-php' => 'PHP $1 е инсталиран.',
	'config-env-php-toolow' => 'PHP $1 е инсталиран.
Меѓутоа, МедијаВики бара PHP $2 или поново.',
	'config-unicode-using-utf8' => 'Со utf8_normalize.so за уникодна нормализација од Брајон Вибер (Brion Vibber).',
	'config-unicode-using-intl' => 'Со додатокот [http://pecl.php.net/intl intl PECL] за уникодна нормализација.',
	'config-unicode-pure-php-warning' => "'''Предупредување''': Додатокот [http://pecl.php.net/intl intl PECL] не е достапен за врши уникодна нормализација, враќајќи се на бавна примена на чист PHP.

Ако имате високопрометно мрежно место, тогаш ќе треба да прочитате повеќе за [http://www.mediawiki.org/wiki/Unicode_normalization_considerations уникодната нормализација].",
	'config-unicode-update-warning' => "'''Предупредување''': Инсталираната верзија на обвивката за уникодна нормализација користи постара верзија на библиотеката на [http://site.icu-project.org/ проектот ICU].
За да користите Уникод, ќе треба да направите [http://www.mediawiki.org/wiki/Unicode_normalization_considerations надградба].",
	'config-no-db' => 'Не можев да пронајдам соодветен двигател за базата на податоци!',
	'config-no-db-help' => 'Ќе треба да инсталирате двигател за базата на податоци за PHP.
Поддржани се следниве типови на бази: $1.

Ако сте на заедничко (споделено) вдомување, побарајте му на вдомителот да инсталира соодветен двигател за базата.
Ако вие самите го составивте ова PHP, сменете ги поставките така што ќе овозможите клиент на базата - на пр. со кодот <code>./configure --with-mysql</code>.
Ако инсталиравте PHP од пакет на Debian или Ubuntu, тогаш ќе треба да го инсталирате и модулот php5-mysql.',
	'config-no-fts3' => "'''Предупредување''': SQLite iе составен без модулот [http://sqlite.org/fts3.html FTS3] - за оваа база нема да има можност за пребарување.",
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
	'config-xml-bad' => 'XML-модулот за PHP недостасува.
МедијаВики има потреба од функции во овој модул и нема да работи со овие поставки.
Ако работите со Mandrake, инсталирајте го php-xml пакетот.',
	'config-pcre' => 'Недостасува модулот за поддршка на PCRE.
МедијаВики не може да работи без функции за регуларни изрази соодветни на Perl.',
	'config-pcre-no-utf8' => "'''Фатално''': PCRE-модулот на PHP е составен без поддршка за PCRE_UTF8.
МедијаВики бара поддршка за UTF-8 за да може да работи правилно.",
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
	'config-diff3-bad' => 'GNU diff3 не е пронајден.',
	'config-imagemagick' => 'Пронајден е ImageMagick: <code>$1</code>.
Ако овозможите подигање, тогаш ќе биде овозможена минијатуризација на сликите.',
	'config-gd' => 'Утврдив дека има вградена GD графичка библиотека.
Ако овозможите подигање, тогаш ќе биде овозможена минијатураизација на сликите.',
	'config-no-scaling' => 'Не можев да пронајдам GD-библиотека или ImageMagick.
Минијатуризацијата на сликите ќе биде оневозможена.',
	'config-no-uri' => "'''Грешка:''' Не можев да го утврдам тековниот URI.
Инсталацијата е откажана.",
	'config-uploads-not-safe' => "'''Предупредување:''' Вашата матична папка за подигање <code>$1</code> е подложна на извршување (пуштање) на произволни скрипти.
Иако МедијаВики врши безбедносни проверки на сите подигнати податотеки, ве советуваме [http://www.mediawiki.org/wiki/Manual:Security#Upload_security да ја затворите оваа безбедносна дупка] пред да овозможите подигање.",
	'config-brokenlibxml' => 'Вашиот систем има комбинација од PHP и libxml2 верзии и затоа има грешки и може да предизвика скриено расипување на податоците кај МедијаВики и други мрежни програми.
Надградете го на PHP 5.2.9 и libxml2 2.7.3 или нивни понови верзии! ПРЕКИНУВАМ ([http://bugs.php.net/bug.php?id=45996 грешката е заведена во PHP]).',
	'config-using531' => 'МедијаВики не може да се користи со PHP $1 поради грешка кај упатните параметри за <code>__call()</code>.
За да го решите проблемот, надградете го на PHP 5.3.2 или понова верзија, или пак користете го постариот PHP 5.3.0.',
	'config-db-type' => 'Тип на база:',
	'config-db-host' => 'Домаќин на базата:',
	'config-db-host-help' => 'Ако вашата база е на друг опслужувач, тогаш тука внесете го името на домаќинот илиу IP-адресата.

Ако користите заедничко (споделено) вдомување, тогаш вашиот вдомител треба да го доде точното име на домаќинот и неговата документација.

Ако инсталирате на опслужувач на Windows и користите MySQL, можноста „localhost“ може да не функционира за опслужувачкото име. Во тој случај, обидете се со внесување на „127.0.0.1“ како локална IP-адреса',
	'config-db-host-oracle' => 'TNS на базата:',
	'config-db-host-oracle-help' => 'Внесете важечко [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm локално име за поврзување]. На оваа инсталација мора да ѝ биде видлива податотеката tnsnames.ora.<br />Ако користите клиентски библиотеки 10g или понови, тогаш можете да го користите и методот на иметнување на [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Идентификувај го викиво',
	'config-db-name' => 'Име на базата:',
	'config-db-name-help' => 'Одберете име што ќе го претставува вашето вики.
Името не смее да содржи празни места.

Ако користите заедничко (споделено) вдомување, тогаш вашиот вдомител ќе ви даде конкретно име на база за користење, или пак ќе ви даде да создавате бази преку контролната табла.',
	'config-db-name-oracle' => 'Шема на базата:',
	'config-db-account-oracle-warn' => 'Постојат три поддржани сценарија за инсталирање на Oracle како базен услужник:

Ако сакате да создадете сметка на базата како дел од постапката за инсталација, наведете сметка со SYSDBA-улога како сметка за базата што ќе се инсталира и наведете ги саканите податоци за сметката за мрежен пристап. Во друг случај, можете да создадете сметка за мрежен пристап рачно и да ја наведете само таа сметка (ако има дозволи за создавање на шематски објекти) или пак да наведете две различни сметки, една со привилегии за создавање, а друга (ограничена) за мрежен пристап.

Скриптата за создавање сметка со задолжителни привилегии ќе ја најдете во папката „maintenance/oracle/“ од оваа инсталација. Имајте на ум дека ако користите ограничена сметка ќе ги оневозможите сите функции за одржување со основната сметка.',
	'config-db-install-account' => 'Корисничка смета за инсталација',
	'config-db-username' => 'Корисничко име за базата:',
	'config-db-password' => 'Лозинка за базата:',
	'config-db-password-empty' => 'Внесете лозинка за новиот корисник на базата: $1.
Иако може да се создаваат корисници без лозинка, тоа не е безбедно.',
	'config-db-install-username' => 'Внесете корисничко име што ќе се користи за поврзување со базата во текот на инсталацијата. Ова не е корисничкото име од сметката на МедијаВики, туку посебно корисничко име за вашата база на податоци.',
	'config-db-install-password' => 'Внесете клозинка што ќе се користи за поврзување со базата во текот на инсталацијата. Ова не е лозинката од сметката на МедијаВики, туку посебна лозинка за вашата база на податоци.',
	'config-db-install-help' => 'Внесете го корисничкото име и лозинката што ќе се користи за поврзување со базата на податоци во текот на инсталацијата.',
	'config-db-account-lock' => 'Користи го истото корисничко име и лозинка за редовна работа',
	'config-db-wiki-account' => 'Корисничко име за редовна работа',
	'config-db-wiki-help' => 'Внесете корисничко име и лозинка што ќе се користат за поврзување со базата на податоци во текот на редовната работа со викито.
Ако сметката не постои, а инсталационата сметка има доволно привилегии, тогаш оваа корисничка сметка ќе биде создадена со минималните привилегии потребни за работа со викито.',
	'config-db-prefix' => 'Префикс на табелата на базата:',
	'config-db-prefix-help' => 'Ако треба да делите една база на податоци со повеќе викија, или со МедијаВики и друг мрежен програм, тогаш можете да додадете префикс на сите називи на табелите за да спречите проблематични ситуации.
Не користете празни простори.

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
	'config-db-schema-help' => 'Оваа шема обично по правило ќе работи нормално.
Сменете ја само ако знаете дека треба да се смени.',
	'config-sqlite-dir' => 'Папка на SQLite-податоци:',
	'config-sqlite-dir-help' => "SQLite ги складира сите податоци во една податотека.

Папката што ќе ја наведете мора да е запислива од мрежниот опслужувач во текот на инсталацијата.

Таа '''не''' смее да биде достапна преку интернет, и затоа не ја ставаме кајшто ви се наоѓаат PHP-податотеките.

Инсталаторот воедно ќе создаде податотека <code>.htaccess</code>, но ако таа не функционира како што треба, тогаш некој ќе може да ви влезе во вашата необработена (сирова) база на податоци.
Тука спаѓаат необработени кориснички податоци (е-поштенски адреси, хеширани лозинки) како и избришани ревизии и други податоци за викито до кои се има ограничен пристап.

Се препорачува целата база да ја сместите некаде, како на пр. <code>/var/lib/mediawiki/вашетовики</code>.",
	'config-oracle-def-ts' => 'Стандарден таблеарен простор:',
	'config-oracle-temp-ts' => 'Привремен табеларен простор:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'МедијаВики ги поддржува следниве системи на бази на податоци:

$1

Ако системот што сакате да го користите не е наведен подолу, тогаш проследете ја горенаведената врска со инструкции за да овозможите поддршка за тој систем.',
	'config-support-mysql' => '* $1 е главната цел на МедијаВики и најдобро се поддржува ([http://www.php.net/manual/en/mysql.installation.php како се составува PHP со поддршка за MySQL])',
	'config-support-postgres' => '* $1 е популарен систем на бази на податоци со отворен код кој претставува алтернатива на MySQL ([http://www.php.net/manual/en/pgsql.installation.php како да составите PHP со поддршка за PostgreSQL]). Може сè уште да има некои грешки. па затоа не се препорачува за употреба во производна средина.',
	'config-support-sqlite' => '* $1 е лесен систем за бази на податоци кој е многу добро поддржан. ([http://www.php.net/manual/en/pdo.installation.php Како да составите PHP со поддршка за SQLite], користи PDO)',
	'config-support-oracle' => '* $1 е база на податоци на комерцијално претпријатие. ([http://www.php.net/manual/en/oci8.installation.php Како да составите PHP со поддршка за OCI8])',
	'config-header-mysql' => 'Нагодувања на MySQL',
	'config-header-postgres' => 'Нагодувања на PostgreSQL',
	'config-header-sqlite' => 'Нагодувања на SQLite',
	'config-header-oracle' => 'Нагодувања на Oracle',
	'config-invalid-db-type' => 'Неважечки тип на база',
	'config-missing-db-name' => 'Мора да внесете значење за параметарот „Име на базата“',
	'config-missing-db-host' => 'Мора да внесете вредност за „Домаќин на базата на податоци“',
	'config-missing-db-server-oracle' => 'Мора да внесете вредност за „TNS на базата“',
	'config-invalid-db-server-oracle' => 'Неважечки TNS „$1“ за базата.
Користете само знаци по ASCII - букви (a-z, A-Z), бројки (0-9), долни црти (_) и точки (.).',
	'config-invalid-db-name' => 'Неважечко име на базата „$1“.
Користете само ASCII-букви (a-z, A-Z), бројки (0-9), долни црти (_) и цртички (-).',
	'config-invalid-db-prefix' => 'Неважечки префикс за базата „$1“.
Користете само ASCII-букви (a-z, A-Z), бројки (0-9), долни црти (_) и цртички (-).',
	'config-connection-error' => '$1.

Проверете го долунаведениот домаќин, корисничко име и лозинка и обидете се повторно.',
	'config-invalid-schema' => 'Неважечка шема за МедијаВики „$1“.
Користете само букви, бројки и долни црти.',
	'config-db-sys-create-oracle' => 'Инсталаторот поддржува само употреба на SYSDBA-сметка за создавање на нова сметка.',
	'config-db-sys-user-exists-oracle' => 'Корисничката сметка „$1“ веќе постои. SYSDBA служи само за создавање на нова сметка!',
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
	'config-can-upgrade' => "Во оваа база има табели на МедијаВики.
За да ги надградите на МедијаВики $1, кликнете на '''Продолжи'''.",
	'config-upgrade-done' => "Надградбата заврши.

Сега можете да [$1 почнете да го користите вашето вики].

Ако сакате да ја пресоздадете вашата податотека <code>LocalSettings.php</code>, тогаш кликнете на копчето подолу.
Ова '''не се препорачува''' освен во случај на проблеми со викито.",
	'config-upgrade-done-no-regenerate' => 'Надградбата заврши.

Сега можете да [$1 почнете да го користите викито].',
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
	'config-ns-conflict' => 'Наведениот именски простор „<nowiki>$1</nowiki>“ се коси со основниот именски простор на МедијаВики.
Наведете друг именски простор за проектот.',
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
	'config-admin-email-help' => 'Тука внесете е-поштенска адреса за да можете да добивате е-пошта од други корисници на викито, да ја менувате лозинката, и да бидете известувани за промени во страниците на вашиот список на набљудувања. Можете и да го оставите празно.',
	'config-admin-error-user' => 'Се појави внатрешна грешка при создавањето на администраторот со име „<nowiki>$1</nowiki>“.',
	'config-admin-error-password' => 'Се појави внатрешна грешка при задавање на лозинката за администраторот „<nowiki>$1</nowiki>“: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Внесовте неважечка е-поштенска адреса',
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
	'config-license-cc-by-sa' => 'Creative Commons НаведиИзвор СподелиПодИстиУслови',
	'config-license-cc-by-nc-sa' => 'Creative Commons НаведиИзвор-Некомерцијално-СподелиПодИстиУслови',
	'config-license-cc-0' => 'Криејтив комонс Нула',
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
За да може ова да работи, МедијаВики бара пристап до интернет. 

За повеќе информации за оваа функција и напатствија за нејзино поставување на вики (сите други освен Ризницата), коносултирајте го [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos прирачникот].',
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
	'config-memcached-help' => 'Список на IP-адреси за употреба во Memcached.
Треба да се наведе по една во секој ред, како и портата што ќе се користи. На пример:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Го одбравте Memcached како ваш ваш тип на скришно памтење (кеш), но не наведовте опслужувач(и)',
	'config-memcache-badip' => 'Внесовте неважечка IP-адреса за Memcached: $1',
	'config-memcache-noport' => 'Не ја наведовте портата за опслужувачот на Memcached: $1.
Ако не знаете која порта треба да се користи, основната е 11211',
	'config-memcache-badport' => 'Бројките за портата на Memcached треба да бидат помеѓу $1 и $2',
	'config-extensions' => 'Додатоци',
	'config-extensions-help' => 'Во вашата папка <code>./extensions</code> беа востановени горенаведените додатоци.

За ова може да треба дополнително нагодување, но можете да ги овозможите сега',
	'config-install-alreadydone' => "'''Предупредување:''' Изгледа дека веќе го имате инсталирано МедијаВики и сега сакате да го инсталирате повторно.
Продолжете на следната страница.",
	'config-install-begin' => 'Стискајќи на „{{int:config-continue}}“ ќе ја започнете инсталацијата на МедијаВики.
Ако сакате да направите измени во досегашното, стиснете на „Назад“.',
	'config-install-step-done' => 'готово',
	'config-install-step-failed' => 'не успеа',
	'config-install-extensions' => 'Вклучувам додатоци',
	'config-install-database' => 'Ја поставувам базата на податоци',
	'config-install-pg-schema-not-exist' => 'PostgreSQL-шемата не постои',
	'config-install-pg-schema-failed' => 'Создавањето натабелите не успеа.
Проверете дали корисникот „$1“ може да запишува во шемата „$2“.',
	'config-install-pg-commit' => 'Спроведување на промени',
	'config-install-pg-plpgsql' => 'Проверувам јазик PL/pgSQL',
	'config-pg-no-plpgsql' => 'Ќе треба да го инсталирате јазикот PL/pgSQL во базата $1',
	'config-pg-no-create-privs' => 'Сметката што ја наведовте за инсталацијата нема доволно привилегии за да создаде друга сметка.',
	'config-install-user' => 'Создавам корисник за базата',
	'config-install-user-alreadyexists' => 'Корисникот „$1“ веќе постои',
	'config-install-user-create-failed' => 'Создавањето на корисникот „$1“ не успеа: $2',
	'config-install-user-grant-failed' => 'Доделувањето на дозвола на корисникот „$1“ не успеа: $2',
	'config-install-tables' => 'Создавам табели',
	'config-install-tables-exist' => "'''Предупредување''': Изгледа дека табелите за МедијаВики веќе постојат.
Го прескокнувам создавањето.",
	'config-install-tables-failed' => "'''Грешка''': Создавањето на табелата не успеа поради следнава грешка: $1",
	'config-install-interwiki' => 'Ги пополнувам основно-зададените интервики-табели',
	'config-install-interwiki-list' => 'Не можев да ја пронајдам податотеката <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Предупредување''': Табелата со интервикија веќе содржи ставки.
Го прескокнувам основно-зададениот список.",
	'config-install-stats' => 'Ги подготвувам статистиките',
	'config-install-keys' => 'Создавам таен клуч',
	'config-insecure-keys' => "'''Предупредување:''' {{PLURAL:$2|Безбедносниот клуч $1 создаден во текот на инсталацијата не е сосем безбеден|Безбедносните клучеви $1 создадени во текот на инсталацијата не се сосем безбедни}}. Ви препорачуваме да {{PLURAL:$2|го|ги}} смените рачно.",
	'config-install-sysop' => 'Создавање на администраторска корисничка сметка',
	'config-install-subscribe-fail' => 'Не можам да ве претплатам на објавите на МедијаВики',
	'config-install-mainpage' => 'Создавам главна страница со стандардна содржина',
	'config-install-extension-tables' => 'Изработка на табели за овозможени додатоци',
	'config-install-mainpage-failed' => 'Не можев да вметнам главна страница: $1',
	'config-install-done' => "'''Честитаме!'''
Успешно го инсталиравте МедијаВики.

Инсталаторот создаде податотека <code>LocalSettings.php</code>.
Таму се содржат сите ваши нагодувања.

Ќе треба да ја преземете и да ја ставите во основата на инсталацијата (истата папка во која се наоѓа index.php). Преземањето треба да е започнато автоматски.

Ако не ви е понудено преземање, или пак ако сте го откажале, можете да го почнете одново стискајќи на следнава врска:

$3

'''Напомена''': Ако ова не го направите сега, податотеката со поставки повеќе нема да биде на достапна.

Откога ќе завршите со тоа, можете да '''[$2 влезете на вашето вики]'''.",
	'config-download-localsettings' => 'Преземи го LocalSettings.php',
	'config-help' => 'помош',
);

/** Malayalam (മലയാളം)
 * @author Praveenp
 */
$messages['ml'] = array(
	'config-desc' => 'മീഡിയവിക്കി ഇൻസ്റ്റോളർ',
	'config-title' => 'മീഡിയവിക്കി $1 ഇൻസ്റ്റലേഷൻ',
	'config-information' => 'വിവരങ്ങൾ',
	'config-localsettings-upgrade' => "'''അറിയിപ്പ്''': ഒരു <code>LocalSettings.php</code> ഫയൽ കാണുന്നു.
സോഫ്റ്റ്‌വേർ അപ്‌ഗ്രേഡ് ചെയ്യുക സാദ്ധ്യമാണ്.
ദയവായി പെട്ടിയിൽ <code>\$wgUpgradeKey</code> എന്നതിന്റെ വില നൽകുക.",
	'config-localsettings-key' => 'അപ്‌ഗ്രേഡ് ചാവി:',
	'config-localsettings-badkey' => 'താങ്കൾ നൽകിയ ചാവി തെറ്റാണ്',
	'config-session-error' => 'സെഷൻ തുടങ്ങുന്നതിൽ പിഴവ്: $1',
	'config-your-language' => 'താങ്കളുടെ ഭാഷ:',
	'config-your-language-help' => 'ഇൻസ്റ്റലേഷൻ പ്രക്രിയയിൽ ഉപയോഗിക്കേണ്ട ഭാഷ തിരഞ്ഞെടുക്കുക.',
	'config-wiki-language' => 'വിക്കി ഭാഷ:',
	'config-wiki-language-help' => 'വിക്കിയിൽ പ്രധാനമായി ഉപയോഗിക്കേണ്ട ഭാഷ തിരഞ്ഞെടുക്കുക.',
	'config-back' => '← പിന്നിലേയ്ക്ക്',
	'config-continue' => 'തുടരുക →',
	'config-page-language' => 'ഭാഷ',
	'config-page-welcome' => 'മീഡിയവിക്കിയിലേയ്ക്ക് സ്വാഗതം!',
	'config-page-dbconnect' => 'ഡേറ്റാബേസുമായി ബന്ധപ്പെടുക',
	'config-page-upgrade' => 'നിലവിലുള്ള ഇൻസ്റ്റലേഷൻ അപ്‌ഗ്രേഡ് ചെയ്യുക',
	'config-page-dbsettings' => 'ഡേറ്റാബേസ് സജ്ജീകരണങ്ങൾ',
	'config-page-name' => 'പേര്',
	'config-page-options' => 'ഐച്ഛികങ്ങൾ',
	'config-page-install' => 'ഇൻസ്റ്റോൾ',
	'config-page-complete' => 'സമ്പൂർണ്ണം!',
	'config-page-restart' => 'ഇൻസ്റ്റലേഷൻ അടച്ച ശേഷം പുനർപ്രവർത്തിപ്പിക്കുക',
	'config-page-readme' => 'ഇത് വായിക്കൂ',
	'config-page-releasenotes' => 'പ്രകാശന കുറിപ്പുകൾ',
	'config-page-copying' => 'പകർത്തൽ',
	'config-page-upgradedoc' => 'അപ്‌ഗ്രേഡിങ്',
	'config-help-restart' => 'ഇതുവരെ ഉൾപ്പെടുത്തിയ എല്ലാവിവരങ്ങളും ഒഴിവാക്കാനും ഇൻസ്റ്റലേഷൻ പ്രക്രിയ നിർത്തി-വീണ്ടുമാരംഭിക്കാനും താങ്കളാഗ്രഹിക്കുന്നുണ്ടോ?',
	'config-restart' => 'അതെ, പുനർപ്രവർത്തിപ്പിക്കുക',
	'config-sidebar' => '* [http://www.mediawiki.org മീഡിയവിക്കി പ്രധാനതാൾ]
* [http://www.mediawiki.org/wiki/Help:Contents ഉപയോക്തൃസഹായി]
* [http://www.mediawiki.org/wiki/Manual:Contents കാര്യനിർവഹണസഹായി]
* [http://www.mediawiki.org/wiki/Manual:FAQ പതിവുചോദ്യങ്ങൾ]',
	'config-env-php' => 'പി.എച്ച്.പി. $1 ഇൻസ്റ്റോൾ ചെയ്തിട്ടുണ്ട്.',
	'config-no-db' => 'അനുയോജ്യമായ ഡേറ്റാബേസ് ഡ്രൈവർ കണ്ടെത്താനായില്ല!',
	'config-memory-raised' => 'പി.എച്ച്.പി.യുടെ <code>memory_limit</code> $1 ആണ്, $2 ആയി ഉയർത്തിയിരിക്കുന്നു.',
	'config-memory-bad' => "'''മുന്നറിയിപ്പ്:''' പി.എച്ച്.പി.യുടെ <code>memory_limit</code> $1 ആണ്.
ഇത് മിക്കവാറും വളരെ കുറവാണ്.
ഇൻസ്റ്റലേഷൻ പരാജയപ്പെട്ടേക്കാം!",
	'config-db-type' => 'ഡേറ്റാബേസ് തരം:',
	'config-db-host' => 'ഡേറ്റാബേസ് ഹോസ്റ്റ്:',
	'config-db-name' => 'ഡേറ്റാബേസിന്റെ പേര്:',
	'config-db-name-oracle' => 'ഡേറ്റാബേസ് സ്കീമ:',
	'config-db-install-account' => 'ഇൻസ്റ്റലേഷനുള്ള ഉപയോക്തൃ അംഗത്വം',
	'config-db-username' => 'ഡേറ്റാബേസ് ഉപയോക്തൃനാമം:',
	'config-db-password' => 'ഡേറ്റാബേസ് രഹസ്യവാക്ക്:',
	'config-mysql-old' => 'മൈഎസ്‌ക്യൂഎൽ $1 അഥവാ അതിലും പുതിയത് ആവശ്യമാണ്, താങ്കളുടെ പക്കൽ ഉള്ളത് $2 ആണ്.',
	'config-db-port' => 'ഡേറ്റാബേസ് പോർട്ട്:',
	'config-db-schema' => 'മീഡിയവിക്കിയ്ക്കായുള്ള സ്കീമ',
	'config-support-info' => 'മീഡിയവിക്കി താഴെ പറയുന്ന ഡേറ്റാബേസ് സിസ്റ്റംസ് പിന്തുണയ്ക്കുന്നു:

$1

താങ്കൾ ഉപയോഗിക്കാനാഗ്രഹിക്കുന്ന ഡേറ്റാബേസ് സിസ്റ്റം പട്ടികയിലില്ലെങ്കിൽ, ദയവായി പിന്തുണ സജ്ജമാക്കാനായി മുകളിൽ നൽകിയിട്ടുള്ള ലിങ്കിലെ നിർദ്ദേശങ്ങൾ ചെയ്യുക.',
	'config-header-mysql' => 'മൈഎസ്‌ക്യൂഎൽ സജ്ജീകരണങ്ങൾ',
	'config-invalid-db-type' => 'അസാധുവായ ഡേറ്റാബേസ് തരം',
	'config-missing-db-name' => '"ഡേറ്റാബേസിന്റെ പേരി"ന് ഒരു വില നിർബന്ധമായും നൽകിയിരിക്കണം',
	'config-connection-error' => '$1.

താഴെ നൽകിയിരിക്കുന്ന ഹോസ്റ്റ്, ഉപയോക്തൃനാമം, രഹസ്യവാക്ക് എന്നിവ പരിശോധിച്ച് വീണ്ടും ശ്രമിക്കുക.',
	'config-regenerate' => 'LocalSettings.php പുനഃസൃഷ്ടിക്കുക →',
	'config-mysql-engine' => 'സ്റ്റോറേജ് എൻജിൻ:',
	'config-site-name' => 'വിക്കിയുടെ പേര്:',
	'config-site-name-help' => 'ഇത് ബ്രൗസറിന്റെ ടൈറ്റിൽ ബാറിലും മറ്റനേകം ഇടങ്ങളിലും പ്രദർശിപ്പിക്കപ്പെടും.',
	'config-site-name-blank' => 'സൈറ്റിന്റെ പേര് നൽകുക.',
	'config-project-namespace' => 'പദ്ധതി നാമമേഖല:',
	'config-ns-generic' => 'പദ്ധതി',
	'config-ns-site-name' => 'വിക്കിയുടെ പേര് തന്നെ: $1',
	'config-ns-other' => 'ഇതരം (വ്യക്തമാക്കുക)',
	'config-ns-other-default' => 'എന്റെ‌വിക്കി',
	'config-admin-box' => 'കാര്യനിർവാഹക അംഗത്വം',
	'config-admin-name' => 'താങ്കളുടെ പേര്:',
	'config-admin-password' => 'രഹസ്യവാക്ക്:',
	'config-admin-password-confirm' => 'രഹസ്യവാക്ക് ഒരിക്കൽക്കൂടി:',
	'config-admin-help' => 'ഇവിടെ താങ്കളുടെ ഇച്ഛാനുസരണമുള്ള ഉപയോക്തൃനാമം നൽകുക, ഉദാഹരണം "ശശി കൊട്ടാരത്തിൽ".
ഈ പേരായിരിക്കണം വിക്കിയിൽ പ്രവേശിക്കാൻ താങ്കൾ ഉപയോഗിക്കേണ്ടത്.',
	'config-admin-name-blank' => 'ഒരു കാര്യനിർവാഹക ഉപയോക്തൃനാമം നൽകുക.',
	'config-admin-name-invalid' => 'നൽകിയിട്ടുള്ള ഉപയോക്തൃനാമം "<nowiki>$1</nowiki>" അസാധുവാണ്.
മറ്റൊരു ഉപയോക്തൃനാമം നൽകുക.',
	'config-admin-password-blank' => 'കാര്യനിർവാഹക അംഗത്വത്തിനുള്ള രഹസ്യവാക്ക് നൽകുക.',
	'config-admin-password-same' => 'രഹസ്യവാക്കും ഉപയോക്തൃനാമവും ഒന്നാകരുത്.',
	'config-admin-password-mismatch' => 'താങ്കൾ നൽകിയ രഹസ്യവാക്കുകൾ രണ്ടും തമ്മിൽ യോജിക്കുന്നില്ല.',
	'config-admin-email' => 'ഇമെയിൽ വിലാസം:',
	'config-admin-error-user' => '"<nowiki>$1</nowiki>" എന്ന പേരിലുള്ള കാര്യനിർവഹണ അംഗത്വ നിർമ്മിതിയ്ക്കിടെ ആന്തരികമായ പിഴവുണ്ടായി.',
	'config-admin-error-password' => '"<nowiki>$1</nowiki>" എന്ന പേരിലുള്ള കാര്യനിർവാഹക അംഗത്വത്തിനു രഹസ്യവാക്ക് സജ്ജീകരിച്ചപ്പോൾ ആന്തരികമായ പിഴവുണ്ടായി: <pre>$2</pre>',
	'config-subscribe' => '[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce പ്രകാശന അറിയിപ്പ് മെയിലിങ് ലിസ്റ്റിൽ] വരിക്കാരാകുക.',
	'config-subscribe-help' => 'പുറത്തിറക്കൽ അറിയിപ്പുകളും, പ്രധാന സുരക്ഷാ അറിയിപ്പുകളും പ്രസിദ്ധീകരിക്കുന്ന വളരെ എഴുത്തുകളൊന്നും ഉണ്ടാകാറില്ലാത്ത മെയിലിങ് ലിസ്റ്റ് ആണിത്.
പുതിയ പതിപ്പുകൾ പുറത്ത് വരുന്നതനുസരിച്ച് അവയെക്കുറിച്ചറിയാനും മീഡിയവിക്കി ഇൻസ്റ്റലേഷൻ പുതുക്കാനും ഇതിന്റെ വരിക്കാരൻ/വരിക്കാരി ആവുക.',
	'config-almost-done' => 'മിക്കവാറും പൂർത്തിയായിരിക്കുന്നു!
ബാക്കിയുള്ളവ അവഗണിച്ച് വിക്കി ഇൻസ്റ്റോൾ ചെയ്യാവുന്നതാണ്.',
	'config-optional-continue' => 'കൂടുതൽ ചോദ്യങ്ങൾ ചോദിക്കൂ.',
	'config-optional-skip' => 'ഞാൻ മടുത്തു, ഇൻസ്റ്റോൾ ചെയ്ത് തീർക്ക്.',
	'config-profile-wiki' => 'പരമ്പരാഗത വിക്കി',
	'config-profile-no-anon' => 'അംഗത്വ സൃഷ്ടി ചെയ്യേണ്ടതുണ്ട്',
	'config-profile-fishbowl' => 'അനുവാദമുള്ളവർ മാത്രം തിരുത്തുക',
	'config-profile-private' => 'സ്വകാര്യ വിക്കി',
	'config-license' => 'പകർപ്പവകാശവും അനുമതിയും:',
	'config-license-cc-by-sa' => 'ക്രിയേറ്റീവ് കോമൺസ് ആട്രിബ്യൂഷൻ ഷെയർ എലൈക്',
	'config-license-cc-by-nc-sa' => 'ക്രിയേറ്റീവ് കോമൺസ് ആട്രിബ്യൂഷൻ നോൺ-കൊമേഴ്സ്യൽ ഷെയർ എലൈക്',
	'config-license-gfdl-old' => 'ഗ്നൂ സ്വതന്ത്ര പ്രസിദ്ധീകരണാനുമതി 1.2',
	'config-license-gfdl-current' => 'ഗ്നൂ സ്വതന്ത്ര പ്രസിദ്ധീകരണാനുമതി 1.3 അഥവാ പുതിയത്',
	'config-license-pd' => 'പൊതു സഞ്ചയം',
	'config-email-settings' => 'ഇമെയിൽ സജ്ജീകരണങ്ങൾ',
	'config-enable-email-help' => "ഇമെയിൽ പ്രവർത്തിക്കണമെങ്കിൽ, [http://www.php.net/manual/en/mail.configuration.php PHP's മെയിൽ സജ്ജീകരണങ്ങൾ] ശരിയായി ക്രമീകരിക്കേണ്ടതുണ്ട്.
ഇമെയിൽ സൗകര്യം ആവശ്യമില്ലെങ്കിൽ, ഇവിടെത്തന്നെ അത് നിർജ്ജീവമാക്കാം.",
	'config-email-user' => 'ഉപയോക്താക്കൾ തമ്മിലുള്ള ഇമെയിൽ പ്രവർത്തനസജ്ജമാക്കുക',
	'config-email-user-help' => 'സ്വന്തം ക്രമീകരണങ്ങളിൽ ഇമെയിൽ സജ്ജമാക്കിയിട്ടുണ്ടെങ്കിൽ ഉപയോക്താക്കളെ മറ്റുള്ളവർക്ക് ഇമെയിൽ അയയ്ക്കാൻ അനുവദിക്കുക.',
	'config-email-usertalk' => 'ഉപയോക്തൃസംവാദം താളിൽ മാറ്റങ്ങളുണ്ടായാൽ അറിയിക്കുക',
	'config-email-watchlist' => 'ശ്രദ്ധിക്കുന്നവയിൽ മാറ്റം വന്നാൽ അറിയിക്കുക',
	'config-email-auth' => 'ഇമെയിലിന്റെ സാധുതാപരിശോധന സജ്ജമാക്കുക',
	'config-email-sender' => 'മറുപടിയ്ക്കുള്ള ഇമെയിൽ വിലാസം:',
	'config-upload-settings' => 'ചിത്രങ്ങളും പ്രമാണങ്ങളും അപ്‌ലോഡ് ചെയ്യൽ',
	'config-upload-enable' => 'പ്രമാണ അപ്‌ലോഡുകൾ സജ്ജമാക്കുക',
	'config-upload-deleted' => 'മായ്ക്കപ്പെട്ട ഫയലുകൾക്കുള്ള ഡയറക്റ്ററി:',
	'config-logo' => 'ലോഗോയുടെ യൂ.ആർ.എൽ.:',
	'config-logo-help' => 'മീഡിയവിക്കിയിൽ സ്വതേയുള്ള ദൃശ്യരൂപത്തിൽ 135x160 പിക്സലുള്ള ലോഗോ മുകളിൽ ഇടത് മൂലയിൽ കാണാം.
അനുയോജ്യമായ വലിപ്പമുള്ള ഒരു ചിത്രം അപ്‌ലോഡ് ചെയ്തിട്ട്, അതിന്റെ യൂ.ആർ.എൽ. ഇവിടെ നൽകുക.

താങ്കൾക്ക് ലോഗോ ആവശ്യമില്ലെങ്കിൽ, ഈ പെട്ടി ശൂന്യമായിടുക.',
	'config-cc-again' => 'ഒന്നുകൂടി എടുക്കൂ...',
	'config-advanced-settings' => 'വിപുലീകൃത ക്രമീകരണങ്ങൾ',
	'config-extensions' => 'അനുബന്ധങ്ങൾ',
	'config-install-step-done' => 'ചെയ്തു കഴിഞ്ഞു',
	'config-install-step-failed' => 'പരാജയപ്പെട്ടു',
	'config-install-extensions' => 'അനുബന്ധങ്ങൾ ഉൾപ്പെടുത്തുന്നു',
	'config-install-database' => 'ഡേറ്റാബേസ് സജ്ജമാക്കുന്നു',
	'config-install-pg-commit' => 'മാറ്റങ്ങൾ സ്വീകരിക്കുന്നു',
	'config-install-user' => 'ഡേറ്റാബേസ് ഉപയോക്താവിനെ സൃഷ്ടിക്കുന്നു',
	'config-install-sysop' => 'കാര്യനിർവാഹക അംഗത്വം സൃഷ്ടിക്കുന്നു',
	'config-install-mainpage' => 'സ്വാഭാവിക ഉള്ളടക്കത്തോടുകൂടി പ്രധാനതാൾ സൃഷ്ടിക്കുന്നു',
	'config-install-mainpage-failed' => 'പ്രധാന താൾ ഉൾപ്പെടുത്താൻ കഴിഞ്ഞില്ല: $1',
	'config-install-done' => "'''അഭിനന്ദനങ്ങൾ!'''
താങ്കൾ വിജയകരമായി മീഡിയവിക്കി ഇൻസ്റ്റോൾ ചെയ്തിരിക്കുന്നു.

ഇൻസ്റ്റോളർ ഒരു <code>LocalSettings.php</code> ഫയൽ സൃഷ്ടിച്ചിട്ടുണ്ട്.
അതിൽ താങ്കളുടെ എല്ലാ ക്രമീകരണങ്ങളുമുണ്ട്.

താങ്കൾ അത് [$1 എടുത്ത്] താങ്കളുടെ വിക്കി ഇൻസ്റ്റലേഷന്റെ അടിസ്ഥാന ഡയറക്റ്ററിയിൽ ഇടുക (index.php കിടക്കുന്ന അതേ ഡയറക്റ്ററി).
'''ശ്രദ്ധിക്കുക''': ഇത് ഇപ്പോൾ ചെയ്തില്ലെങ്കിൽ, സൃഷ്ടിക്കപ്പെട്ട കോൺഫിഗറേഷൻ ഫയൽ എടുക്കാതെ ഇൻസ്റ്റലേഷൻ പ്രക്രിയയിൽ നിന്ന് പുറത്തിറങ്ങിയാൽ പിന്നീട് ലഭ്യമായിരിക്കില്ല.

ചെയ്തശേഷം, താങ്കൾക്ക് '''[$2 വിക്കിയിൽ പ്രവേശിക്കാം]'''.",
);

/** Mongolian (Монгол)
 * @author Chinneeb
 */
$messages['mn'] = array(
	'config-page-language' => 'Хэл',
);

/** Erzya (Эрзянь)
 * @author Botuzhaleny-sodamo
 */
$messages['myv'] = array(
	'config-page-language' => 'Кель',
	'config-page-name' => 'Лемезэ',
	'config-page-readme' => 'Ловномак',
	'config-admin-name' => 'Леметь:',
	'config-admin-password' => 'Совамо валот:',
	'config-admin-password-confirm' => 'Совамо валот одов:',
	'config-admin-email' => 'Е-сёрма паргот:',
	'config-install-step-done' => 'теезь',
);

/** Dutch (Nederlands)
 * @author Catrope
 * @author McDutchie
 * @author Purodha
 * @author SPQRobin
 * @author Siebrand
 */
$messages['nl'] = array(
	'config-desc' => 'Het installatieprogramma voor MediaWiki',
	'config-title' => 'Installatie MediaWiki $1',
	'config-information' => 'Informatie',
	'config-localsettings-upgrade' => 'Er is een bestaand instellingenbestand <code>LocalSettings.php</code> gevonden.
Voer de waarde van <code>$wgUpgradeKey</code> in in onderstaande invoerveld om deze installatie bij te werken.
De instelling is terug te vinden in LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Het bestand LocalSettings.php is al aanwezig.
Voer update.php uit om deze installatie bij te werken.',
	'config-localsettings-key' => 'Upgradesleutel:',
	'config-localsettings-badkey' => 'De sleutel die u hebt opgegeven is onjuist',
	'config-upgrade-key-missing' => 'Er is een bestaande installatie van MediaWiki aangetroffen.
Plaats de volgende regel onderaan uw LocalSettings.php om deze installatie bij te werken:

$1',
	'config-localsettings-incomplete' => 'De bestaande inhoud van LocalSettings.php lijkt incompleet.
De variabele $1 is niet ingesteld.
Wijzig LocalSettings.php zodat deze variabele is ingesteld en klik op "Doorgaan".',
	'config-localsettings-connection-error' => 'Er is een fout opgetreden tijdens het verbinden van de database met de instellingen uit LocalSettings.php of AdminSettings.php. Los het probleem met de instellingen op en probeer het daarna opnieuw.

$1',
	'config-session-error' => 'Fout bij het begin van de sessie: $1',
	'config-session-expired' => 'Uw sessiegegevens zijn verlopen.
Sessies zijn ingesteld om een levensduur van $1 te hebben.
U kunt deze wijzigen via de instelling <code>session.gc_maxlifetime</code> in php.ini.
Begin het installatieproces opnieuw.',
	'config-no-session' => 'Uw sessiegegevens zijn verloren gegaan.
Controleer uw php.ini en zorg dat er een juiste map is ingesteld voor <code>session.save_path</code>.',
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
	'config-page-existingwiki' => 'Bestaande wiki',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ Veel gestelde vragen] (Engelstalig)
----
* <doclink href=Readme>Leesmij</doclink> (Engelstalig)
* <doclink href=ReleaseNotes>Release notes</doclink> (Engelstalig)
* <doclink href=Copying>Kopiëren</doclink> (Engelstalig)
* <doclink href=UpgradeDoc>Versie bijwerken</doclink> (Engelstalig)',
	'config-env-good' => 'De omgeving is gecontroleerd.
U kunt MediaWiki installeren.',
	'config-env-bad' => 'De omgeving is gecontroleerd.
U kunt MediaWiki niet installeren.',
	'config-env-php' => 'PHP $1 is op dit moment geïnstalleerd.',
	'config-env-php-toolow' => 'PHP $1 is geïnstalleerd.
MediaWiki heeft PHP $2 of hoger nodig om correct te kunnen werken.',
	'config-unicode-using-utf8' => 'Voor Unicode-normalisatie wordt utf8_normalize.so van Brion Vibber gebruikt.',
	'config-unicode-using-intl' => 'Voor Unicode-normalisatie wordt de [http://pecl.php.net/intl PECL-extensie intl] gebruikt.',
	'config-unicode-pure-php-warning' => "'''Waarschuwing''': De [http://pecl.php.net/intl PECL-extensie intl] is niet beschikbaar om de Unicode-normalisatie af te handelen en daarom wordt de langzame PHP-implementatie gebruikt.
Als u MediaWiki voor een website met veel verkeer installeert, lees u dan in over [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-normalisatie].",
	'config-unicode-update-warning' => "'''Waarschuwing''': De geïnstalleerde versie van de Unicode-normalisatiewrapper maakt gebruik van een oudere versie van [http://site.icu-project.org/ de bibliotheek van het ICU-project].
U moet [http://www.mediawiki.org/wiki/Unicode_normalization_considerations bijwerken] als Unicode voor u van belang is.",
	'config-no-db' => 'Er kon geen geschikte databasedriver geladen worden!',
	'config-no-db-help' => 'U moet een databasedriver installeren voor PHP.
De volgende databases worden ondersteund: $1.

Als u op een gedeelde omgeving zit, vraag dan aan uw hostingprovider een geschikte databasedriver te installeren.
Als u PHP zelf hebt gecompileerd, wijzig dan uw instellingen zodat een databasedriver wordt geactiveerd, bijvoorbeeld via <code>./configure --with-mysql</code>.
Als u PHP hebt geïnstalleerd via een Debian- of Ubuntu-package, installeer dan ook de module php5-mysql.',
	'config-no-fts3' => "'''Waarschuwing''': SQLite is gecompileerd zonder de module [http://sqlite.org/fts3.html FTS3]; er zijn geen zoekfuncties niet beschikbaar.",
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
	'config-xml-bad' => 'De XML-module van PHP ontbreekt.
MediaWiki heeft de functies van deze module nodig en werkt niet zonder deze module.
Als u gebruik maakt van Mandrake, installeer dan het package php-xml.',
	'config-pcre' => 'De ondersteuningsmodule PCRE lijkt te missen.
MediaWiki vereist dat de met Perl compatibele reguliere expressies werken.',
	'config-pcre-no-utf8' => "'''Fataal:''' de module PRCE van PHP lijkt te zijn gecompileerd zonder ondersteuning voor PCRE_UTF8.
MediaWiki heeft ondersteuning voor UTF-8 nodig om correct te kunnen werken.",
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
	'config-diff3-bad' => 'GNU diff3 niet aangetroffen.',
	'config-imagemagick' => 'ImageMagick aangetroffen: <code>$1</code>.
Het aanmaken van miniaturen van afbeeldingen wordt ingeschakeld als u uploaden inschakelt.',
	'config-gd' => 'Ingebouwde GD grafische bibliotheek aangetroffen.
Het aanmaken van miniaturen van afbeeldingen wordt ingeschakeld als u uploaden inschakelt.',
	'config-no-scaling' => 'De GD-bibliotheek en ImageMagick zijn niet aangetroffen.
Het maken van miniaturen van afbeeldingen wordt uitgeschakeld.',
	'config-no-uri' => "'''Fout:''' de huidige URI kon niet vastgesteld worden.
De installatie is afgebroken.",
	'config-uploads-not-safe' => "'''Waarschuwing:''' uw uploadmap <code>$1</code> kan gebruikt worden voor het arbitrair uitvoeren van scripts.
Hoewel MediaWiki alle toegevoegde bestanden  controleert op bedreigingen, is het zeer aan te bevelen het [http://www.mediawiki.org/wiki/Manual:Security#Upload_security beveiligingslek te verhelpen] alvorens uploads in te schakelen.",
	'config-brokenlibxml' => 'Uw systeem heeft een combinatie van PHP- en libxml2-versies geïnstalleerd die is foutgevoelig is en kan leiden tot onzichtbare beschadiging van gegevens in MediaWiki en andere webapplicaties.
Upgrade naar PHP 5.2.9 of hoger en libxml2 2.7.3 of hoger! De installatie wordt afgebroken ([http://bugs.php.net/bug.php?id=45996 bij PHP gerapporteerde fout]).',
	'config-using531' => 'PHP $1 is niet compatibel met MediaWiki vanwege een fout met betrekking tot referentieparameters met <code>__call()</code>.
Werk uw PHP bij naar PHP 5.3.2 of hoger of werk bij naar de lagere versie PHP 5.3.0 om dit op te lossen.
De installatie wordt afgebroken.',
	'config-db-type' => 'Databasetype:',
	'config-db-host' => 'Databasehost:',
	'config-db-host-help' => 'Als uw databaseserver een andere server is, voer dan de hostnaam of het IP-adres hier in.

Als u gebruik maakt van gedeelde webhosting, hoort uw provider u de juiste hostnaam te hebben verstrekt.

Als u MediaWiki op een Windowsserver installeert en MySQL gebruikt, dan werkt "localhost" mogelijk niet als servernaam.
Als het inderdaad niet werkt, probeer dan "127.0.0.1" te gebruiken als lokaal IP-adres.',
	'config-db-host-oracle' => 'Database-TNS:',
	'config-db-host-oracle-help' => 'Voer een geldige [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Local Connect Name] in; een tnsnames.ora-bestand moet zichtbaar zijn voor deze installatie.<br />Als u gebruik maakt van clientlibraries 10g of een latere versie, kunt u ook gebruik maken van de naamgevingsmethode [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identificeer deze wiki',
	'config-db-name' => 'Databasenaam:',
	'config-db-name-help' => 'Kies een naam die uw wiki identificeert.
Er mogen geen spaties gebruikt worden.
Als u gebruik maakt van gedeelde webhosting, dan hoort uw provider ofwel u een te gebruiken databasenaam gegeven te hebben, of u aangegeven te hebben hoe u databases kunt aanmaken.',
	'config-db-name-oracle' => 'Databaseschema:',
	'config-db-account-oracle-warn' => 'Er zijn drie ondersteunde scenario\'s voor het installeren van Oracle als databasebackend:

Als u een databasegebruiker wilt aanmaken als onderdeel van het installatieproces, geef dan de gegevens op van een databasegebruiker in met de rol SYSDBA voor de installatie en voer de gewenste aanmeldgegevens in voor de gebruiker met webtoegang. U kunt ook de gebruiker met webtoegang handmatig aanmaken en alleen van die gebruiker de aanmeldgegevens opgeven als deze de vereiste rechten heeft om schemaobjecten aan te maken. Als laatste is het mogelijk om aanmeldgegevens van twee verschillende gebruikers op te geven; een met de rechten om schemaobjecten aan te maken, en een met alleen webtoegang.

Een script voor het aanmaken van een gebruiker met de vereiste rechten is te vinden in de map "maintenance/oracle/" van deze installatie. Onthoud dat het gebruiken van een gebruiker met beperkte rechten alle mogelijkheden om beheerscripts uit te voeren met de standaard gebruiker onmogelijk maakt.',
	'config-db-install-account' => 'Gebruiker voor installatie',
	'config-db-username' => 'Gebruikersnaam voor database:',
	'config-db-password' => 'Wachtwoord voor database:',
	'config-db-password-empty' => 'Voer een wachtwoord in voor de nieuwe databasegebruiker: $1.
Hoewel het wellicht mogelijk is gebruikers aan te maken zonder wachtwoord, is dit niet veilig.',
	'config-db-install-username' => 'Voer de gebruikersnaam in die gebruikt moet worden om te verbinden met de database tijdens het installatieproces. Dit is niet de gebruikersnaam van de MediaWikigebruiker. Dit is de gebruikersnaam voor de database.',
	'config-db-install-password' => 'Voer het wachtwoord in dat gebruikt moet worden om te verbinden met de database tijdens het installatieproces. Dit is niet het wachtwoord van de MediaWikigebruiker. Dit is het wachtwoord voor de database.',
	'config-db-install-help' => 'Voer de gebruikersnaam en het wachtwoord in die worden gebruikt voor de databaseverbinding tijdens het installatieproces.',
	'config-db-account-lock' => 'Dezelfde gebruiker en wachwoord gebruiken na de installatie',
	'config-db-wiki-account' => 'Gebruiker voor na de installatie',
	'config-db-wiki-help' => 'Selecteer de gebruikersnaam en het wachtwoord die gebruikt worden om verbinding te maken met de database na de installatie.
Als de gebruiker niet bestaat en de gebruiker die tijdens de installatie gebruikt wordt voldoende rechten heeft, wordt deze gebruiker aangemaakt met de minimaal benodigde rechten voor het laten werken van de wiki.',
	'config-db-prefix' => 'Databasetabelvoorvoegsel:',
	'config-db-prefix-help' => "Als u een database moet gebruiken voor meerdere wiki's, of voor MediaWiki en een andere applicatie, dan kunt u ervoor kiezen om een voorvoegsel toe te voegen aan de tabelnamen om conflicten te voorkomen.
Gebruik geen spaties.

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
	'config-db-schema-help' => 'Dit schema klopt meestal.
Wijzig het alleen als u weet dat dit nodig is.',
	'config-sqlite-dir' => 'Gegevensmap voor SQLite:',
	'config-sqlite-dir-help' => "SQLite slaat alle gegevens op in een enkel bestand.

De map die u opgeeft moet schrijfbaar zijn voor de webserver tijdens de installatie.

Deze mag '''niet toegankelijk''' zijn via het web en het bestand mag dus niet tussen de PHP-bestanden staan.

Het installatieprogramma schrijft het bestand <code>.htaccess</code> weg met het databasebestand, maar als dat niet werkt kan iemand zich toegang tot het ruwe databasebestand verschaffen.
Ook de gebruikersgegevens (e-mailsadressen, wachtwoordhashes) en verwijderde versies en overige gegevens met beperkte toegang via MediaWiki zijn dan onbeschermd. 

Overweeg om de database op een totaal andere plaats neer te zetten, bijvoorbeeld in <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Standaard tablespace:',
	'config-oracle-temp-ts' => 'Tijdelijke tablespace:',
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
	'config-support-oracle' => '* $1 is een commerciële data voor grote bedrijven ([http://www.php.net/manual/en/oci8.installation.php PHP compileren met ondersteuning voor OCI8]).',
	'config-header-mysql' => 'MySQL-instellingen',
	'config-header-postgres' => 'PostgreSQL-instellingen',
	'config-header-sqlite' => 'SQLite-instellingen',
	'config-header-oracle' => 'Oracle-instellingen',
	'config-invalid-db-type' => 'Ongeldig databasetype',
	'config-missing-db-name' => 'U moet een waarde ingeven voor "Databasenaam"',
	'config-missing-db-host' => 'U moet een waarde invoeren voor "Databaseserver"',
	'config-missing-db-server-oracle' => 'U moet een waarde voor "Database-TNS" ingeven',
	'config-invalid-db-server-oracle' => 'Ongeldige database-TMS "$1".
Gebruik alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
	'config-invalid-db-name' => 'Ongeldige databasenaam "$1".
Gebruik alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_) en streepjes (-).',
	'config-invalid-db-prefix' => 'Ongeldig databasevoorvoegsel "$1".
Gebruik alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_) en streepjes (-).',
	'config-connection-error' => '$1.

Controleer de host, gebruikersnaam en wachtwoord hieronder in en probeer het opnieuw.',
	'config-invalid-schema' => 'Ongeldig schema voor MediaWiki "$1".
Gebruik alleen letters (a-z, A-Z), cijfers (0-9) en liggende streepjes (_).',
	'config-db-sys-create-oracle' => 'Het installatieprogramma biedt alleen de mogelijkheid een nieuwe gebruiker aan te maken met de SYSDBA-gebruiker.',
	'config-db-sys-user-exists-oracle' => 'De gebruiker "$1" bestaat al. SYSDBA kan alleen gebruikt worden voor het aanmaken van een nieuwe gebruiker!',
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
	'config-can-upgrade' => "Er staan al tabellen voor MediaWiki in deze database.
Klik op '''Doorgaan''' om ze bij te werken naar MediaWiki $1.",
	'config-upgrade-done' => "Het bijwerken is afgerond.

Uw kunt [$1 uw wiki nu gebruiken].

Als u uw <code>LocalSettings.php</code> opnieuw wilt aanmaken, klik dan op de knop hieronder.
Dit is '''niet aan te raden''' tenzij u problemen hebt met uw wiki.",
	'config-upgrade-done-no-regenerate' => 'Het bijwerken is afgerond.

U kunt u [$1 uw wiki gebruiken].',
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
	'config-mysql-charset-help' => "In '''binaire modus''' slaat MediaWiki tekst in UTF-8 op in binaire databasevelden.
Dit is efficiënter dan de UTF-8-modus van MySQL en stelt u in staat de volledige reeks Unicode-tekens te gebruiken.

In '''UTF-8-modus''' kent MySQL de tekenset van uw gegevens en kan de databaseserver ze juist weergeven en converteren.
Het is dat niet mogelijk tekens op te slaan die de \"[http://nl.wikipedia.org/wiki/Lijst_van_Unicode-subbereiken#Basic_Multilingual_Plane Basic Multilingual Plane]\" te boven gaan.",
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
	'config-ns-conflict' => 'De aangegeven naamruimte "<nowiki>$1</nowiki>" conflicteert met een standaard naamruimte in MediaWiki.
Geef een andere naam op voor de projectnaamruimte.',
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
	'config-admin-error-bademail' => 'U hebt een ongeldig e-mailadres opgegeven',
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
	'config-license-cc-by-sa' => 'Creative Commons Naamsvermelding-Gelijk delen',
	'config-license-cc-by-nc-sa' => 'Creative Commons Naamsvermelding-Niet Commercieel-Gelijk delen',
	'config-license-cc-0' => 'Creative Commons Zero',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
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
Hiervoor heeft MediaWiki toegang nodig tot Internet. 

Meer informatie over deze functie en hoe deze in te stellen voor andere wiki\'s dan Wikimedia Commons is te vinden in de [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos handleiding].',
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
	'config-memcached-help' => 'Lijst met IP-adressen te gebruiken voor Memcached.
Eén IP-adres per regel met een poortnummer.
Bijvoorbeeld:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'U hebt Memcached geselecteerd als uw cache, maar u hebt geen servers opgegeven.',
	'config-memcache-badip' => 'U hebt een ongeldig IP-adres ingevoerd voor Memcached: $1.',
	'config-memcache-noport' => 'U hebt geen poort opgegeven voor de Memcachedserver: $1.
De standaardpoort is 11211.',
	'config-memcache-badport' => 'Poortnummers voor Memcached moeten tussen $1 en $2 liggen.',
	'config-extensions' => 'Uitbreidingen',
	'config-extensions-help' => 'De bovenstaande uitbreidingen zijn aangetroffen in de map <code>./extensions</code>.

Mogelijk moet u aanvullende instellingen maken, maar u kunt deze uitbreidingen nu inschakelen.',
	'config-install-alreadydone' => "'''Waarschuwing:''' het lijkt alsof u MediaWiki al hebt geïnstalleerd en probeert het programma opnieuw te installeren.
Ga alstublieft door naar de volgende pagina.",
	'config-install-begin' => 'Als u nu op "{{int:config-continue}}" klikt, begint de installatie van MediaWiki.
Als u nog wijzigingen wilt maken, klik dan op "Terug".',
	'config-install-step-done' => 'Afgerond',
	'config-install-step-failed' => 'Mislukt',
	'config-install-extensions' => 'Inclusief uitbreidingen',
	'config-install-database' => 'Database inrichten',
	'config-install-pg-schema-not-exist' => 'Het schema voor PostgreSQL bestaat niet',
	'config-install-pg-schema-failed' => 'Het aanmaken van de tabellen is mislukt.
Zorg dat de gebruiker "$1" in het schema "$2" mag schrijven.',
	'config-install-pg-commit' => 'Wijzigingen worden doorgevoerd',
	'config-install-pg-plpgsql' => 'Controle op de taal PL/pgSQL',
	'config-pg-no-plpgsql' => 'U moet de taal PL/pgSQL installeren in de database $1',
	'config-pg-no-create-privs' => 'De gebruiker die u hebt opgegeven door de installatie heeft niet voldoende rechten om een gebruiker aan te maken.',
	'config-install-user' => 'Databasegebruiker aan het aanmaken',
	'config-install-user-alreadyexists' => 'Gebruiker "$1" bestaat al',
	'config-install-user-create-failed' => 'Het aanmaken van de gebruiker "$1" is mislukt: $2',
	'config-install-user-grant-failed' => 'Het geven van rechten aan gebruiker "$1" is mislukt: $2',
	'config-install-tables' => 'Tabellen aanmaken',
	'config-install-tables-exist' => "'''Waarschuwing''': de MediaWiki-tabellen lijken al te bestaan. 
Het aanmaken wordt overgeslagen.",
	'config-install-tables-failed' => "'''Fout''': het aanmaken van een tabel is mislukt met de volgende foutmelding: $1",
	'config-install-interwiki' => 'Bezig met het vullen van de interwikitabel',
	'config-install-interwiki-list' => 'Het bestand <code>interwiki.list</code> is niet aangetroffen',
	'config-install-interwiki-exists' => "'''Waarschuwing''': de interwikitabel heeft al inhoud. 
De standaardlijst wordt overgeslagen.",
	'config-install-stats' => 'Statistieken initialiseren',
	'config-install-keys' => 'Geheime sleutel aanmaken',
	'config-install-sysop' => 'Gebruiker voor beheerder aanmaken',
	'config-install-subscribe-fail' => 'Het is niet mogelijk te abonneren op mediawiki-announce',
	'config-install-mainpage' => 'Hoofdpagina aanmaken met standaard inhoud',
	'config-install-extension-tables' => 'Tabellen voor ingeschakelde uitbreidingen worden aangemaakt',
	'config-install-mainpage-failed' => 'Het was niet mogelijk de hoofdpagina in te voegen: $1',
	'config-install-done' => "'''Gefeliciteerd!'''
U hebt MediaWiki met geïnstalleerd.

Het installatieprogramma heeft het bestand <code>LocalSettings.php</code> aangemaakt.
Dit bevat al uw instellingen.

U moet het bestand downloaden en in de hoofdmap van uw wiki-installatie plaatsten; in dezelfde map als index.php.
De download moet u automatisch zijn aangeboden.

Als de download niet is aangeboden of als u de download hebt geannuleerd, dan kunt u de download opnieuw starten door op de onderstaande verwijzing te klikken:

$3

'''Let op''': als u dit niet nu doet, dan het is bestand als u later de installatieprocedure afsluit zonder het bestand te downloaden niet meer beschikbaar.

Na het plaatsen van het bestand met instellingen kunt u '''[$2 uw wiki betreden]'''.",
	'config-download-localsettings' => 'LocalSettings.php downloaden',
	'config-help' => 'hulp',
);

/** Norwegian Nynorsk (‪Norsk (nynorsk)‬)
 * @author Nghtwlkr
 */
$messages['nn'] = array(
	'config-your-language' => 'Språket ditt:',
	'config-wiki-language' => 'Wikispråk:',
	'config-back' => '← Attende',
	'config-continue' => 'Hald fram →',
	'config-page-language' => 'Språk',
	'config-memory-raised' => 'PHPs <code>memory_limit</code> er $1, auka til $2.',
	'config-memory-bad' => "'''Advarsel:''' PHPs <code>memory_limit</code> er $1.
Dette er sannsynlegvis for lågt.
Installasjonen kan mislukkast!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] er innstallert',
	'config-apc' => '[http://www.php.net/apc APC] er innstallert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] er innstallert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] er installert',
	'config-db-name' => 'Databasenamn:',
	'config-db-username' => 'Databasebrukarnamn:',
	'config-db-password' => 'Databasepassord:',
	'config-db-charset' => 'Databaseteiknsett',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binær',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 bakoverkompatibel UTF-8',
	'config-mysql-old' => 'MySQL $1 eller seinare krevst, du har $2.',
	'config-db-port' => 'Databaseport:',
	'config-db-schema' => 'Skjema for MediaWiki',
	'config-header-mysql' => 'MySQL-innstillingar',
	'config-header-postgres' => 'PostgreSQL-innstillingar',
	'config-header-sqlite' => 'SQLite-innstillingar',
	'config-header-oracle' => 'Oracle-innstillingar',
	'config-invalid-db-type' => 'Ugyldig databasetype',
	'config-invalid-db-name' => 'Ugyldig databasenamn «$1».
Berre bruk ASCII-bokstavar (a-z, A-Z), tal (0-9) og undestrekar (_).',
	'config-invalid-db-prefix' => 'Ugyldig databaseprefiks «$1».
Berre bruk ASCII-bokstavar (a-z, A-Z), tal (0-9) og undestrekar (_).',
	'config-invalid-schema' => 'Ugyldig skjema for MediaWiki «$1».
Berre bruk ASCII-bokstavar (a-z, A-Z), tal (0-9) og undestrekar (_).',
	'config-postgres-old' => 'PostgreSQL $1 eller seinare krevst, du har $2.',
	'config-email-settings' => 'E-postinnstillingar',
	'config-logo' => 'Logo-URL:',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Jon Harald Søby
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'config-desc' => 'Installasjonsprogrammet for MediaWiki',
	'config-title' => 'Installasjon av MediaWiki $1',
	'config-information' => 'Informasjon',
	'config-localsettings-upgrade' => "'''Advarsel''': En <code>LocalSettings.php</code>-fil har blitt oppdaget.
Programvaren kan oppgraderes.
Flytt <code>LocalSettings.php</code> til et trygt sted og kjør installasjonsprogrammet på nytt.",
	'config-session-error' => 'Feil under oppstart av økt: $1',
	'config-session-expired' => 'Dine øktdata ser ut til å ha utløpt.
Økter er konfigurert for en levetid på $1.
Du kan øke dette ved å sette <code>session.gc_maxlifetime</code> i php.ini.
Start installasjonsprosessen på nytt.',
	'config-no-session' => 'Dine øktdata ble tapt!
Sjekk din php.ini og sørg for at <code>session.save_path</code> er satt til en passende mappe.',
	'config-your-language' => 'Ditt språk:',
	'config-your-language-help' => 'Velg et språk å bruke under installasjonsprosessen.',
	'config-wiki-language' => 'Wikispråk:',
	'config-wiki-language-help' => 'Velg språket som wikien hovedsakelig vil bli skrevet i.',
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
	'config-page-restart' => 'Start installasjonen på nytt',
	'config-page-readme' => 'Les meg',
	'config-page-releasenotes' => 'Utgivelsesnotat',
	'config-page-copying' => 'Kopiering',
	'config-page-upgradedoc' => 'Oppgradering',
	'config-help-restart' => 'Ønsker du å fjerne alle lagrede data som du har skrevet inn og starte installasjonsprosessen på nytt?',
	'config-restart' => 'Ja, start på nytt',
	'config-welcome' => '=== Miljøsjekker ===
Grunnleggende sjekker utføres for å se om dette miljøet er egnet for en MediaWiki-installasjon.
Du bør oppgi resultatene fra disse sjekkene om du trenger hjelp under installasjonen.',
	'config-copyright' => "=== Opphavsrett og vilkår ===

$1

MediaWiki er fri programvare; du kan redistribuere det og/eller modifisere det under betingelsene i GNU General Public License som publisert av Free Software Foundation; enten versjon 2 av lisensen, eller (etter eget valg) enhver senere versjon.

Dette programmet er distribuert i håp om at det vil være nyttig, men '''uten noen garanti'''; ikke engang implisitt garanti av '''salgbarhet''' eller '''egnethet for et bestemt formål'''.
Se GNU General Public License for flere detaljer.

Du skal ha mottatt <doclink href=Copying>en kopi av GNU General Public License</doclink> sammen med dette programmet; hvis ikke, skriv til Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA eller [http://www.gnu.org/copyleft/gpl.html les det på nettet].",
	'config-sidebar' => '* [http://www.mediawiki.org MediaWiki hjem]
* [http://www.mediawiki.org/wiki/Help:Contents Brukerguide]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratorguide]
* [http://www.mediawiki.org/wiki/Manual:FAQ OSS]',
	'config-env-good' => 'Miljøet har blitt sjekket.
Du kan installere MediaWiki.',
	'config-env-bad' => 'Miljøet har blitt sjekket.
Du kan installere MediaWiki.',
	'config-env-php' => 'PHP $1 er innstallert.',
	'config-unicode-using-utf8' => 'Bruker Brion Vibbers utf8_normalize.so for Unicode-normalisering.',
	'config-unicode-using-intl' => 'Bruker [http://pecl.php.net/intl intl PECL-utvidelsen] for Unicode-normalisering.',
	'config-unicode-pure-php-warning' => "'''Advarsel''': [http://pecl.php.net/intl intl PECL-utvidelsen] er ikke tilgjengelig for å håndtere Unicode-normaliseringen.
Om du kjører et høy trafikksnettsted bør du lese litt om [http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode-normalisering].",
	'config-unicode-update-warning' => "'''Advarsel''': Den installerte versjonen av Unicode-normalisereren bruker en eldre versjon av [http://site.icu-project.org/ ICU-prosjektets] bibliotek.
Du bør [http://www.mediawiki.org/wiki/Unicode_normalization_considerations oppgradere] om du er bekymret for å bruke Unicode.",
	'config-no-db' => 'Fant ikke en passende databasedriver!',
	'config-no-db-help' => 'Du må installere en databasedriver for PHP.
Følgende databasetyper er støttet: $1.

Om du er på delt tjener, spør din tjenerleverandør om å installere en passende databasedriver.
Om du kompilerte PHP selv, rekonfigirer den med en aktivert databaseklient, for eksempel ved å bruke <code>./configure --with-mysql</code>.
Om du installerte PHP fra en Debian eller Ubuntu-pakke må du også installere modulen php5-mysql.',
	'config-no-fts3' => "'''Advarsel''': SQLite er kompilert uten [http://sqlite.org/fts3.html FTS3-modulen], søkefunksjoner vil ikke være tilgjengelig på dette bakstykket.",
	'config-register-globals' => "'''Advarsel: PHPs <code>[http://php.net/register_globals register_globals]</code>-alternativ er aktivert.'''
'''Deaktiver det om du kan.'''
MediaWiki vil fungere, men tjeneren din er utsatt for potensielle sikkerhetssårbarheter.",
	'config-magic-quotes-runtime' => "'''Kritisk: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] er aktiv!'''
Dette alternativet ødelegger inndata på en uforutsigbar måte.
Du kan ikke installere eller bruke MediaWiki med mindre dette alternativet deaktiveres.",
	'config-magic-quotes-sybase' => "'''Kritisk: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] er aktiv!'''
Dette alternativet ødelegger inndata på en uforutsigbar måte.
Du kan ikke installere eller bruke MediaWiki med mindre dette alternativet deaktiveres.",
	'config-mbstring' => "'''Kritisk: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] er aktiv!'''
Dette alternativet fører til feil og kan ødelegge data på en uforutsigbar måte.
Du kan ikke installere eller bruke MediaWiki med mindre dette alternativet deaktiveres.",
	'config-ze1' => "'''Kritisk: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] er aktiv!'''
Dette alternativet fører til horrible feil med MediaWiki.
Du kan ikke installere eller bruke MediaWiki med mindre dette alternativet deaktiveres.",
	'config-safe-mode' => "'''Advarsel:''' PHPs [http://www.php.net/features.safe-mode safe mode] er aktiv.
Det kan føre til problem, spesielt hvis du bruker støtte for filopplastinger og <code>math</code>.",
	'config-xml-bad' => 'PHPs XML-modul mangler.
MediaWiki krever funksjonene i denne modulen og vil ikke virke i denne konfigurasjonen.
Hvis du kjører Mandrak, installer pakken php-xml.',
	'config-pcre' => 'PCRE-støttemodulen ser ut til å mangle.
MediaWiki krever funksjonene for de Perl-kompatible regulære uttrykkene for å virke.',
	'config-memory-raised' => 'PHPs <code>memory_limit</code> er $1, økt til $2.',
	'config-memory-bad' => "'''Advarsel:''' PHPs <code>memory_limit</code> er $1.
Dette er sannsynligvis for lavt.
Installasjonen kan mislykkes!",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] er innstallert',
	'config-apc' => '[http://www.php.net/apc APC] er innstallert',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] er innstallert',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] er installert',
	'config-no-cache' => "'''Advarsel:''' Kunne ikke finne [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] eller [http://www.iis.net/download/WinCacheForPhp WinCache].
Objekthurtiglagring er ikke aktivert.",
	'config-diff3-bad' => 'GNU diff3 ikke funnet.',
	'config-imagemagick' => 'Fant ImageMagick: <code>$1</code>.
Bildeminiatyrisering vil aktiveres om du aktiverer opplastinger.',
	'config-gd' => 'Fant innebygd GD-grafikkbibliotek.
Bildeminiatyrisering vil aktiveres om du aktiverer opplastinger.',
	'config-no-scaling' => 'Kunne ikke finne GD-bibliotek eller ImageMagick.
Bildeminiatyrisering vil være deaktivert.',
	'config-no-uri' => "'''Feil:''' Kunne ikke bestemme gjeldende URI.
Installasjon avbrutt.",
	'config-uploads-not-safe' => "'''Advarsel:''' Din standardmappe for opplastinger <code>$1</code> er sårbar for kjøring av vilkårlige skript.
Selv om MediaWiki sjekker alle opplastede filer for sikkerhetstrusler er det sterkt anbefalt å [http://www.mediawiki.org/wiki/Manual:Security#Upload_security lukke denne sikkerhetssårbarheten] før du aktiverer opplastinger.",
	'config-db-type' => 'Databasetype:',
	'config-db-host' => 'Databasevert:',
	'config-db-host-help' => 'Hvis databasetjeneren er på en annen tjener, skriv inn vertsnavnet eller IP-adressen her.

Hvis du bruker en delt nettvert bør verten din oppgi det korrekte vertsnavnet i deres dokumentasjon.

Hvis du installerer på en Windowstjener og bruker MySQL kan det hende at «localhost» ikke virker som tjenernavnet. Hvis ikke, prøv «127.0.0.1» for den lokale IP-adressen.',
	'config-db-host-oracle' => 'Database TNS:',
	'config-db-host-oracle-help' => 'Skriv inn et gyldig [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Local Connect Name]; en tnsnames.ora-fil må være synlig for installasjonsprosessen.<br />Hvis du bruker klientbibliotek 10g eller nyere kan du også bruke navngivingsmetoden [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Identifiser denne wikien',
	'config-db-name' => 'Databasenavn:',
	'config-db-name-help' => 'Velg et navn som identifiserer wikien din.
Det bør ikke inneholde mellomrom eller bindestreker.

Hvis du bruker en delt nettvert vil verten din enten gi deg et spesifikt databasenavn å bruke, eller la deg opprette databaser via kontrollpanelet.',
	'config-db-name-oracle' => 'Databaseskjema:',
	'config-db-install-account' => 'Brukerkonto for installasjon',
	'config-db-username' => 'Databasebrukernavn:',
	'config-db-password' => 'Databasepassord:',
	'config-db-install-help' => 'Skriv inn brukernavnet og passordet som vil bli brukt for å koble til databasen under installasjonsprosessen.',
	'config-db-account-lock' => 'Bruk det samme brukernavnet og passordet under normal drift',
	'config-db-wiki-account' => 'Brukerkonto for normal drift',
	'config-db-wiki-help' => 'Skriv inn brukernavnet og passordet som vil bli brukt til å koble til databasen under normal wikidrift.
Hvis kontoen ikke finnes, og installasjonskontoen har tilstrekkelige privilegier, vil denne brukerkontoen bli opprettet med et minimum av privilegier, tilstrekkelig for å operere wikien.',
	'config-db-prefix' => 'Databasetabellprefiks:',
	'config-db-prefix-help' => 'Hvis du trenger å dele en database mellom flere wikier, eller mellom MediaWiki og andre nettapplikasjoner, kan du velge å legge til et prefiks til alle tabellnavnene for å unngå konflikter.
Ikke bruk mellomrom eller bindestreker.

Dette feltet er vanligvis tomt.',
	'config-db-charset' => 'Databasetegnsett',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binær',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 bakoverkompatibel UTF-8',
	'config-charset-help' => "'''Advarsel:''' Hvis du bruker '''bakoverkompatibel UTF-8''' på MySQL 4.1+, og deretter sikkerhetskopierer databasen med <code>mysqldump</code> kan det ødelegge alle ikke-ASCII tegn og irreversibelt ødelegge dine sikkerhetskopier!

I '''binary mode''' lagrer MediaWiki UTF-8 tekst til databasen i binærfelt.
Dette er mer effektivt enn MySQLs UTF-8 modus og tillater deg å bruke hele spekteret av Unicode-tegn.
I '''UTF-8 mode''' vil MySQL vite hvilket tegnsett dataene dine er i og kan presentere og konvertere det på en riktig måte,
men det vil ikke la deg lagre tegn over «[http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes the Basic Multilingual Plane]».",
	'config-mysql-old' => 'MySQL $1 eller senere kreves, du har $2.',
	'config-db-port' => 'Databaseport:',
	'config-db-schema' => 'Skjema for MediaWiki',
	'config-db-schema-help' => 'Ovennevnte skjema er som regel riktig.
Bare endre dem hvis du vet at du trenger det.',
	'config-sqlite-dir' => 'SQLite datamappe:',
	'config-sqlite-dir-help' => "SQLite lagrer alle data i en enkelt fil.

Mappen du oppgir må være skrivbar for nettjeneren under installasjonen.

Den bør '''ikke''' være tilgjengelig fra nettet, dette er grunnen til at vi ikke legger det der PHP-filene dine er.

Installasjonsprogrammet vil skrive en <code>.htaccess</code>-fil sammen med det, men om det mislykkes kan noen få tilgang til din råe database. Dette inkluderer rå brukerdata (e-postadresser, hashede passord) samt slettede revisjoner og andre begrensede data på wikien.

Vurder å plassere databasen et helt annet sted, for eksempel i <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Standard tabellrom:',
	'config-oracle-temp-ts' => 'Midlertidig tabellrom:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki støtter følgende databasesystem:

$1

Hvis du ikke ser databasesystemet du prøver å bruke i listen nedenfor, følg instruksjonene det er lenket til over for å aktivere støtte.',
	'config-support-mysql' => '* $1 er det primære målet for MediaWiki og er best støttet ([http://www.php.net/manual/en/mysql.installation.php hvordan kompilere PHP med MySQL-støtte])',
	'config-support-postgres' => '* $1 er et populært åpen kildekode-databasesystem som er et alternativ til MySQL ([http://www.php.net/manual/en/pgsql.installation.php hvordan kompilere PHP med PostgreSQL-støtte])',
	'config-support-sqlite' => '* $1 er et lettvekts-databasesystem som er veldig godt støttet. ([http://www.php.net/manual/en/pdo.installation.php hvordan kompilere PHP med SQLite-støtte], bruker PDO)',
	'config-support-oracle' => '* $1 er en kommersiell bedriftsdatabase. ([http://www.php.net/manual/en/oci8.installation.php Hvordan kompilere PHP med OCI8-støtte])',
	'config-header-mysql' => 'MySQL-innstillinger',
	'config-header-postgres' => 'PostgreSQL-innstillinger',
	'config-header-sqlite' => 'SQLite-innstillinger',
	'config-header-oracle' => 'Oracle-innstillinger',
	'config-invalid-db-type' => 'Ugyldig databasetype',
	'config-missing-db-name' => 'Du må skrive inn en verdi for «Databasenavn»',
	'config-missing-db-server-oracle' => 'Du må skrive inn en verdi for «Database TNS»',
	'config-invalid-db-server-oracle' => 'Ugyldig database-TNS «$1».
Bruk bare ASCII-bokstaver (a-z, A-Z), tall (0-9) og undestreker (_) og punktum (.).',
	'config-invalid-db-name' => 'Ugyldig databasenavn «$1».
Bruk bare ASCII-bokstaver (a-z, A-Z), tall (0-9) og undestreker (_).',
	'config-invalid-db-prefix' => 'Ugyldig databaseprefiks «$1».
Bruk bare ASCII-bokstaver (a-z, A-Z), tall (0-9) og undestreker (_).',
	'config-connection-error' => '$1.

Sjekk verten, brukernavnet og passordet nedenfor og prøv igjen.',
	'config-invalid-schema' => 'Ugyldig skjema for MediaWiki «$1».
Bruk bare ASCII-bokstaver (a-z, A-Z), tall (0-9) og undestreker (_).',
	'config-postgres-old' => 'PostgreSQL $1 eller senere kreves, du har $2.',
	'config-sqlite-name-help' => 'Velg et navn som identifiserer wikien din.
Ikke bruk mellomrom eller bindestreker.
Dette vil bli brukt til SQLite-datafilnavnet.',
	'config-sqlite-parent-unwritable-group' => 'Kan ikke opprette datamappen <code><nowiki>$1</nowiki></code> fordi foreldremappen <code><nowiki>$2</nowiki></code> ikke er skrivbar for nettjeneren.

Installasjonsprogrammet har bestemt brukeren nettjeneren din kjører som.
Gjør <code><nowiki>$3</nowiki></code>-mappen skrivbar for denne for å fortsette.
På et Unix/Linux-system, gjør:

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => 'Kan ikke opprette datamappen <code><nowiki>$1</nowiki></code> fordi foreldremappen <code><nowiki>$2</nowiki></code> ikke er skrivbar for nettjeneren.

Installasjonsprogrammet kunne ikke bestemme brukeren nettjeneren din kjører som.
Gjør <code><nowiki>$3</nowiki></code>-mappen globalt skrivbar for denne (og andre!) for å fortsette.
På et Unix/Linux-system, gjør:

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => 'Feil under oppretting av datamappen «$1».
Sjekk plasseringen og prøv igjen.',
	'config-sqlite-dir-unwritable' => 'Kan ikke skrive til mappen «$1».
Endre dens tilganger slik at nettjeneren kan skrive til den og prøv igjen.',
	'config-sqlite-connection-error' => '$1.

Sjekk datamappen og databasenavnet nedenfor og prøv igjen.',
	'config-sqlite-readonly' => 'Filen <code>$1</code> er ikke skrivbar.',
	'config-sqlite-cant-create-db' => 'Kunne ikke opprette databasefilen <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'PHP mangler FTS3-støtte, nedgraderer tabeller',
	'config-can-upgrade' => "Det er MediaWiki-tabeller i denne databasen.
For å oppgradere dem til MediaWiki $1, klikk '''Fortsett'''.",
	'config-upgrade-done' => "Oppgradering fullført.

Du kan nå [$1 begynne å bruke wikien din].

Hvis du ønsker å regenerere <code>LocalSettings.php</code>-filen din, klikk på knappen nedenfor.
Dette er '''ikke anbefalt''' med mindre du har problemer med wikien din.",
	'config-regenerate' => 'Regenerer LocalSettings.php →',
	'config-show-table-status' => 'SHOW TABLE STATUS etterspørselen mislyktes!',
	'config-unknown-collation' => "'''Advarsel:''' Databasen bruker en ukjent sortering.",
	'config-db-web-account' => 'Databasekonto for nettilgang',
	'config-db-web-help' => 'Velg brukernavnet og passordet som nettjeneren skal bruke for å koble til databasetjeneren under ordinær drift av wikien.',
	'config-db-web-account-same' => 'Bruk samme konto som for installasjonen',
	'config-db-web-create' => 'Opprett kontoen om den ikke finnes allerede',
	'config-db-web-no-create-privs' => 'Kontoen du oppga for installasjonen har ikke nok privilegier til å opprette en konto.
Kontoen du oppgir her må finnes allerede.',
	'config-mysql-engine' => 'Lagringsmotor:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' er nesten alltid det beste alternativet siden den har god støtte for samtidighet («concurrency»).

'''MyISAM''' kan være raskere i enbruker- eller les-bare-installasjoner.
MyISAM-databaser har en tendens til å bli ødelagt oftere enn InnoDB-databaser.",
	'config-mysql-charset' => 'Databasetegnsett:',
	'config-mysql-binary' => 'Binær',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "I '''binary mode''' lagrer MediaWiki UTF-8 tekst til databasen i binærfelt.
Dette er mer effektivt enn MySQLs UTF-8 modus og tillater deg å bruke hele spekteret av Unicode-tegn.

I '''UTF-8 mode''' vil MySQL vite hvilket tegnsett dataene dine er i og kan presentere og konvertere det på en riktig måte,
men det vil ikke la deg lagre tegn over «[http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes the Basic Multilingual Plane]».",
	'config-site-name' => 'Navn på wiki:',
	'config-site-name-help' => 'Dette vil vises i tittellinjen i nettleseren og diverse andre steder.',
	'config-site-name-blank' => 'Skriv inn et nettstedsnavn.',
	'config-project-namespace' => 'Prosjektnavnerom:',
	'config-ns-generic' => 'Prosjekt',
	'config-ns-site-name' => 'Samme som wikinavnet: $1',
	'config-ns-other' => 'Annet (spesifiser)',
	'config-ns-other-default' => 'MyWiki',
	'config-project-namespace-help' => "Etter Wikipedias eksempel holder mange wikier deres sider med retningslinjer atskilt fra sine innholdssider, i et «'''prosjektnavnerom'''».
Alle sidetitler i dette navnerommet starter med et gitt prefiks som du kan angi her.
Tradisjonelt er dette prefikset avledet fra navnet på wikien, men det kan ikke innholde punkttegn som «#» eller «:».",
	'config-ns-invalid' => 'Det angitte navnerommet «<nowiki>$1</nowiki>» er ugyldig.
Angi et annet prosjektnavnerom.',
	'config-admin-box' => 'Administratorkonto',
	'config-admin-name' => 'Ditt navn:',
	'config-admin-password' => 'Passord:',
	'config-admin-password-confirm' => 'Passord igjen:',
	'config-admin-help' => 'Skriv inn ditt ønskede brukernavn her, for eksempel «Joe Bloggs».
Dette er navnet du vil bruke for å logge inn på denne wikien.',
	'config-admin-name-blank' => 'Skriv inn et administratorbrukernavn.',
	'config-admin-name-invalid' => 'Det angitte brukernavnet «<nowiki>$1</nowiki>» er ugyldig.
Angi et annet brukernavn.',
	'config-admin-password-blank' => 'Skriv inn et passord for administratorkontoen.',
	'config-admin-password-same' => 'Passordet skal ikke være det samme som brukernavnet.',
	'config-admin-password-mismatch' => 'De to passordene du skrev inn samsvarte ikke.',
	'config-admin-email' => 'E-postadresse:',
	'config-admin-email-help' => 'Skriv inn en e-postadresse her for at du skal kunne motta e-post fra andre brukere på wikien, tilbakestille passordet ditt, og bli varslet om endringer på sider på overvåkningslisten din.',
	'config-admin-error-user' => 'Intern feil ved opprettelse av en admin med navnet «<nowiki>$1</nowiki>».',
	'config-admin-error-password' => 'Intern feil ved opprettelse av passord for admin «<nowiki>$1</nowiki>»: <pre>$2</pre>',
	'config-subscribe' => 'Abonner på [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce e-postlisten for utgivelsesannonseringer].',
	'config-subscribe-help' => 'Dette er en lav-volums e-postliste brukt til utgivelsesannonseringer, herunder viktige sikkerhetsannonseringer.
Du bør abonnere på den og oppdatere MediaWikiinstallasjonen din når nye versjoner kommer ut.',
	'config-almost-done' => 'Du er nesten ferdig!
Du kan hoppe over de resterende konfigurasjonene og installere wikien nå.',
	'config-optional-continue' => 'Spør meg flere spørsmål.',
	'config-optional-skip' => 'Jeg er lei, bare installer wikien.',
	'config-profile' => 'Brukerrettighetsprofil:',
	'config-profile-wiki' => 'Tradisjonell wiki',
	'config-profile-no-anon' => 'Kontoopprettelse påkrevd',
	'config-profile-fishbowl' => 'Kun autoriserte bidragsytere',
	'config-profile-private' => 'Privat wiki',
	'config-profile-help' => "Wikier fungerer best når du lar så mange mennesker som mulig redigere den.
I MediaWiki er det lett å revidere siste endringer og tilbakestille eventuell skade som er gjort av naive eller ondsinnede brukere.

Imidlertid har mange funnet at MediaWiki er nyttig i mange roller, og av og til er det ikke lett å overbevise alle om fordelene med wikimåten.
Så du har valget.

En '''{{int:config-profile-wiki}}''' tillater alle å redigere, selv uten å logge inn.
En wiki med '''{{int:config-profile-no-anon}}''' tilbyr ekstra ansvarlighet, men kan avskrekke tilfeldige bidragsytere.

'''{{int:config-profile-fishbowl}}'''-scenariet tillater godkjente brukere å redigere, mens publikum kan se sider, og også historikken.
En '''{{int:config-profile-private}}''' tillater kun godkjente brukere å se sider, den samme gruppen som får lov til å redigere dem.

Mer komplekse konfigurasjoner av brukerrettigheter er tilgjengelig etter installasjon, se det [http://www.mediawiki.org/wiki/Manual:User_rights relevante manualavsnittet].",
	'config-license' => 'Opphavsrett og lisens:',
	'config-license-cc-by-sa' => 'Creative Commons Navngivelse Del på samme vilkår',
	'config-license-cc-by-nc-sa' => 'Creative Commons Navngivelse Ikke-kommersiell Del på samme vilkår',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 eller nyere',
	'config-license-pd' => 'Offentlig rom',
	'config-license-cc-choose' => 'Velg en egendefinert Creative Commons-lisens',
	'config-email-settings' => 'E-postinnstillinger',
	'config-enable-email' => 'Aktiver utgående e-post',
	'config-enable-email-help' => 'Hvis du vil at e-post skal virke må [http://www.php.net/manual/en/mail.configuration.php PHPs e-postinnstillinger] bli konfigurert riktig.
Hvis du ikke ønsker noen e-postfunksjoner kan du deaktivere dem her.',
	'config-email-user' => 'Aktiver e-post mellom brukere',
	'config-email-user-help' => 'Tillat alle brukere å sende hverandre e-post hvis de har aktivert det i deres innstillinger.',
	'config-email-usertalk' => 'Aktiver brukerdiskusjonssidevarsler',
	'config-email-usertalk-help' => 'Tillat brukere å motta varsler ved endringer på deres brukerdiskusjonsside hvis de har aktivert dette i deres innstillinger.',
	'config-email-watchlist' => 'Aktiver overvåkningslistevarsler',
	'config-email-watchlist-help' => 'Tillat brukere å motta varsler ved endringer på deres overvåkede sider hvis de har aktivert dette i deres innstillinger.',
	'config-email-auth' => 'Aktiver e-postautentisering',
	'config-email-auth-help' => "Om dette alternativet er aktivert må brukere bekrefte sin e-postadresse ved å bruke en lenke som blir sendt til dem når de setter eller endrer adressen sin.
Kun autentiserte e-postadresser kan motta e-post fra andre brukere eller endringsvarsel.
Å sette dette valget er '''anbefalt''' for offentlige wikier på grunn av potensiell misbruk av e-postfunksjonene.",
	'config-email-sender' => 'Svar-e-postadresse:',
	'config-email-sender-help' => 'Skriv inn e-postadressen som skal brukes som svar-adresse ved utgående e-post.
Det er hit returmeldinger vil bli sendt.
Mange e-posttjenere krever at minst domenenavnet må være gyldig.',
	'config-upload-settings' => 'Bilde- og filopplastinger',
	'config-upload-enable' => 'Aktiver filopplastinger',
	'config-upload-help' => 'Filopplastinger kan potensielt utsette tjeneren din for sikkerhetsrisikoer.
For mer informasjon, les [http://www.mediawiki.org/wiki/Manual:Security sikkerhetsseksjonen] i manualen.

For å aktivere filopplastinger, endre modusen i <code>images</code>-undermappen i MediaWikis rotmappe slik at nettjeneren kan skrive til den.
Aktiver så dette alternativet.',
	'config-upload-deleted' => 'Mappe for slettede filer:',
	'config-upload-deleted-help' => 'Velg en mappe for å arkivere slettede filer.
Ideelt burde ikke denne være tilgjengelig for nettet.',
	'config-logo' => 'Logo-URL:',
	'config-logo-help' => 'MediaWikis standarddrakt inkluderer plass til en 135x160 pikslers logo i øvre venstre hjørne.
Last opp et bilde i passende størrelse og skriv inn nettadressen her.

Hvis du ikke ønsker en logo, la denne boksen være tom.',
	'config-instantcommons' => 'Aktiver Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] er en funksjon som gjør det mulig for wikier å bruke bilder, lyder og andre media funnet på nettstedet [http://commons.wikimedia.org/ Wikimedia Commons].
For å gjøre dette krever MediaWiki tilgang til internett. 

For mer informasjon om denne funksjonen, inklusive instruksjoner om hvordan man setter opp dette for andre wikier enn Wikimedia Commons, konsulter [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos manualen].',
	'config-cc-again' => 'Velg igjen...',
	'config-cc-not-chosen' => 'Velg hvilken Creative Commons-lisens du ønsker og klikk «fortsett».',
	'config-advanced-settings' => 'Avansert konfigurasjon',
	'config-extensions' => 'Utvidelser',
	'config-install-step-done' => 'ferdig',
	'config-install-step-failed' => 'mislyktes',
	'config-install-extensions' => 'Inkludert utvidelser',
	'config-install-database' => 'Setter opp database',
	'config-install-user' => 'Oppretter databasebruker',
	'config-install-tables' => 'Oppretter tabeller',
);

/** Polish (Polski)
 * @author Holek
 * @author Sp5uhe
 */
$messages['pl'] = array(
	'config-desc' => 'Instalator MediaWiki',
	'config-title' => 'Instalacja MediaWiki $1',
	'config-information' => 'Informacja',
	'config-localsettings-upgrade' => 'Plik <code>LocalSettings.php</code> istnieje.
Aby oprogramowanie zostało zaktualizowane musisz wstawić wartość <code>$wgUpgradeKey</code> w poniższe pole.
Odnajdziesz ją w LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Wykryto obecność pliku LocalSettings.php.
Do wykonania aktualizacji instalacji należy dodać opcję --upgrade=yes.',
	'config-localsettings-key' => 'Klucz aktualizacji',
	'config-localsettings-badkey' => 'Podany klucz jest nieprawidłowy',
	'config-upgrade-key-missing' => 'Wykryto zainstalowane wcześniej MediaWiki.
Jeśli chcesz je zaktualizować dodaj na koniec pliku LocalSettings.php poniższą linię tekstu.

$1',
	'config-localsettings-incomplete' => 'Istniejący plik LocalSettings.php wygląda na niekompletny.
Brak wartości zmiennej $1.
Zmień plik LocalSettings.php, tak by zawierał deklarację wartości tej zmiennej, a następnie kliknij „Dalej”.',
	'config-localsettings-connection-error' => 'Wystąpił błąd podczas łączenia z bazą danych z wykorzystaniem danych z LocalSettings.php lub AdminSettings.php.
Popraw ustawienia i spróbuj ponownie.

$1',
	'config-session-error' => 'Błąd uruchomienia sesji – $1',
	'config-session-expired' => 'Wygląda na to, że Twoja sesja wygasła.
Czas życia sesji został skonfigurowany na $1.
Możesz go wydłużyć zmieniając <code>session.gc_maxlifetime</code> w pliku php.ini.
Uruchom ponownie proces instalacji.',
	'config-no-session' => 'Dane sesji zostały utracone.
Sprawdź plik php.ini i upewnij się, że <code>session.save_path</code> wskazuje na odpowiedni katalog.',
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
	'config-page-existingwiki' => 'Istniejąca wiki',
	'config-help-restart' => 'Czy chcesz usunąć wszystkie zapisane dane, które podałeś i uruchomić ponownie proces instalacji?',
	'config-restart' => 'Tak, zacznij od nowa',
	'config-welcome' => '=== Sprawdzenie środowiska instalacji ===
Wykonywane są podstawowe testy sprawdzające czy to środowisko jest odpowiednie dla instalacji MediaWiki. 
Jeśli potrzebujesz pomocy podczas instalacji załącz wyniki tych testów.',
	'config-copyright' => "=== Prawa autorskie i warunki użytkowania ===

$1

To oprogramowanie jest wolne; możesz je rozprowadzać dalej i modyfikować zgodnie z warunkami licencji GNU General Public License opublikowanej przez Free Software Foundation w wersji 2 tej licencji lub (według Twojego wyboru) którejś z późniejszych jej wersji. 

Niniejsze oprogramowanie jest rozpowszechniane w nadziei, że będzie użyteczne, ale '''bez żadnej gwarancji'''; nawet bez domniemanej gwarancji '''handlowej''' lub '''przydatności do określonego celu'''.
Zobacz treść licencji GNU General Public License, aby uzyskać więcej szczegółów. 

Razem z oprogramowaniem powinieneś otrzymać <doclink href=Copying>kopię licencji GNU General Public License</doclink>. Jeśli jej nie otrzymałeś, napisz do Free Software Foundation, Inc, 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA. lub [http://www.gnu.org/copyleft/gpl.html przeczytaj ją online].",
	'config-sidebar' => '* [http://www.mediawiki.org Strona domowa MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Podręcznik użytkownika]
* [http://www.mediawiki.org/wiki/Manual:Contents Podręcznik administratora]
* [http://www.mediawiki.org/wiki/Manual:FAQ Odpowiedzi na często zadawane pytania]
----
* <doclink href=Readme>Przeczytaj to</doclink>
* <doclink href=ReleaseNotes>Informacje o tej wersji</doclink>
* <doclink href=Copying>Kopiowanie</doclink>
* <doclink href=UpgradeDoc>Aktualizacja</doclink>',
	'config-env-good' => 'Środowisko oprogramowania zostało sprawdzone.
Możesz teraz zainstalować MediaWiki.',
	'config-env-bad' => 'Środowisko oprogramowania zostało sprawdzone.
Nie możesz zainstalować MediaWiki.',
	'config-env-php' => 'Zainstalowane jest PHP w wersji $1.',
	'config-unicode-using-utf8' => 'Korzystanie z normalizacji Unicode utf8_normalize.so napisanej przez Brion Vibbera.',
	'config-unicode-using-intl' => 'Korzystanie z [http://pecl.php.net/intl rozszerzenia intl PECL] do normalizacji Unicode.',
	'config-unicode-pure-php-warning' => "'''Uwaga!''' [http://pecl.php.net/intl Rozszerzenie intl PECL] do obsługi normalizacji Unicode nie jest dostępne. Użyta zostanie mało wydajna zwykła implementacja w PHP.
Jeśli prowadzisz stronę o dużym natężeniu ruchu, powinieneś zapoznać się z informacjami o [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalizacji Unicode].",
	'config-unicode-update-warning' => "'''Uwaga''' – zainstalowana wersja normalizacji Unicode korzysta z nieaktualnej biblioteki [http://site.icu-project.org/ projektu ICU].
Powinieneś [http://www.mediawiki.org/wiki/Unicode_normalization_considerations zrobić aktualizację] jeśli chcesz korzystać w pełni z Unicode.",
	'config-no-db' => 'Nie można odnaleźć właściwego sterownika bazy danych!',
	'config-no-db-help' => 'Należy zainstalować sterownik bazy danych dla PHP.
Obsługiwane są następujące typy baz danych: $1. 

Jeżeli korzystasz ze współdzielonego hostingu, zwróć się do administratora o zainstalowanie odpowiedniego sterownika bazy danych. 
Jeśli skompilowałeś PHP samodzielnie, skonfiguruj je ponownie z włączonym klientem bazy danych, na przykład za pomocą polecenia
<code>./configure --with-mysql</code>. 
Jeśli zainstalowałeś PHP jako pakiet Debiana lub Ubuntu, musisz również zainstalować moduł php5-mysql.',
	'config-no-fts3' => "'''Uwaga''' – SQLite został skompilowany bez [http://sqlite.org/fts3.html modułu FTS3] – funkcje wyszukiwania nie będą dostępne.",
	'config-register-globals' => "'''Uwaga –  w konfiguracji PHP włączona jest opcja <code>[http://php.net/register_globals register_globals]</code>.'''
'''Jeśli możesz, wyłącz ją.'''
MediaWiki będzie działać, ale Twój serwer może być narażony potencjalnymi lukami w zabezpieczeniach.",
	'config-magic-quotes-runtime' => "'''Błąd krytyczny – włączono [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]!'''
Ta opcja powoduje nieprzewidywalne uszkodzenia wprowadzanych danych.
Zainstalować lub korzystać z MediaWiki można pod warunkiem, że ta opcja jest wyłączona.",
	'config-magic-quotes-sybase' => "'''Błąd krytyczny – włączono [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase]!'''
Ta opcja powoduje nieprzewidywalne uszkodzenia wprowadzanych danych.
Zainstalować lub korzystać z MediaWiki można pod warunkiem, że ta opcja jest wyłączona.",
	'config-mbstring' => "'''Błąd krytyczny – włączono [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]!'''
Ta opcja powoduje błędy i może wywołać nieprzewidywalne uszkodzenia wprowadzanych danych.
Zainstalować lub korzystać z MediaWiki można pod warunkiem, że ta opcja jest wyłączona.",
	'config-ze1' => "'''Błąd krytyczny – włączono [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]!'''
Ta opcja powoduje okropne błędy podczas korzystania z MediaWiki.
Zainstalować lub korzystać z MediaWiki można wyłącznie wtedy, gdy ta opcja jest wyłączona.",
	'config-safe-mode' => "'''Ostrzeżenie''' – uaktywniono [http://www.php.net/features.safe-mode tryb awaryjny] PHP.
Opcja ta może powodować problemy, szczególnie w przypadku korzystania z przesyłania plików i używania znacznika <code>math</code>.",
	'config-xml-bad' => 'Brak modułu XML dla PHP.
MediaWiki wymaga funkcji z tego modułu i nie może działać w tej konfiguracji.
Jeśli korzystasz z Mandrake, zainstaluj pakiet php-xml.',
	'config-pcre' => 'Wygląda na to, że brak modułu PCRE.
MediaWiki do pracy wymaga funkcji obsługi wyrażeń regularnych kompatybilnej z Perlem.',
	'config-pcre-no-utf8' => "'''Błąd krytyczny''' – wydaje się, że moduł PCRE w PHP został skompilowany bez wsparcia dla UTF‐8.
MediaWiki wymaga wsparcia dla UTF‐8 do prawidłowego działania.",
	'config-memory-raised' => 'PHP <code>memory_limit</code> było ustawione na $1, zostanie zwiększone do $2.',
	'config-memory-bad' => "'''Uwaga:''' PHP <code>memory_limit</code> jest ustawione na $1. 
To jest prawdopodobnie zbyt mało.
Instalacja może się nie udać!",
	'config-xcache' => '[Http://trac.lighttpd.net/xcache/ XCache] jest zainstalowany',
	'config-apc' => '[Http://www.php.net/apc APC] jest zainstalowany',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] jest zainstalowany',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] jest zainstalowany',
	'config-no-cache' => "'''Uwaga:''' Nie można odnaleźć [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] lub [http://www.iis.net/download/WinCacheForPhp WinCache].
Buforowanie obiektów nie będzie możliwe.",
	'config-diff3-bad' => 'Nie znaleziono GNU diff3.',
	'config-imagemagick' => 'Odnaleziono ImageMagick <code>$1</code>.
Miniatury grafik będą generowane jeśli włączysz przesyłanie plików.',
	'config-gd' => 'Odnaleziono wbudowaną bibliotekę graficzną GD.
Miniatury grafik będą generowane jeśli włączysz przesyłanie plików.',
	'config-no-scaling' => 'Nie można odnaleźć biblioteki GD lub ImageMagick.
Tworzenie miniatur grafik będzie wyłączone.',
	'config-no-uri' => "'''Błąd.''' Nie można określić aktualnego URI.
Instalacja została przerwana.",
	'config-uploads-not-safe' => "'''Uwaga''' – domyślny katalog do którego zapisywane są przesyłane pliki <code>$1</code> jest podatny na wykonanie dowolnego skryptu.
Chociaż MediaWiki sprawdza wszystkie przesłane pliki pod kątem bezpieczeństwa, zaleca się jednak, aby [http://www.mediawiki.org/wiki/Manual:Security#Upload_security zamknąć tę lukę w zabezpieczeniach] przed włączeniem przesyłania plików.",
	'config-brokenlibxml' => 'Twój system jest kombinacją wersji PHP i libxml2, które zawierają błędy mogące powodować ukryte uszkodzenia danych w MediaWiki i innych aplikacjach sieci web.
Wykonaj aktualizację PHP do wersji 5.2.9 lub późniejszej oraz libxml2 do wersji 2.7.3 lub późniejszej ([http://bugs.php.net/bug.php?id=45996 błąd w PHP]).
Instalacja została przerwana.',
	'config-using531' => 'PHP $1 nie współpracuje poprawnie z MediaWiki z powodu błędu dotyczącego referencyjnych argumentów funkcji <code>__call()</code>.
Uaktualnij do PHP 5.3.2 lub nowszego. Możesz również cofnąć wersję do PHP 5.3.0 aby naprawić ten błąd ([http://bugs.php.net/bug.php?id=50394 błąd w PHP]).
Instalacja została przerwana.',
	'config-db-type' => 'Typ bazy danych',
	'config-db-host' => 'Adres serwera bazy danych',
	'config-db-host-help' => 'Jeśli serwer bazy danych jest na innej maszynie, wprowadź jej nazwę domenową lub adres IP.

Jeśli korzystasz ze współdzielonego hostingu, operator serwera powinien podać Ci prawidłową nazwę serwera w swojej dokumentacji.

Jeśli instalujesz oprogramowanie na serwerze Windowsowym i korzystasz z MySQL, użycie „localhost” może nie zadziałać jako nazwa hosta. Jeśli wystąpi ten problem użyj „127.0.0.1” jako lokalnego adresu IP.',
	'config-db-host-oracle' => 'TNS bazy danych',
	'config-db-host-oracle-help' => 'Wprowadź prawidłową [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm nazwę połączenia lokalnego]. Plik „tnsnames.ora” musi być widoczny dla instalatora.<br />Jeśli używasz biblioteki klienckiej 10g lub nowszej możesz również skorzystać z metody nazw [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm łatwego łączenia].',
	'config-db-wiki-settings' => 'Zidentyfikuj tę wiki',
	'config-db-name' => 'Nazwa bazy danych',
	'config-db-name-help' => 'Wybierz nazwę, która zidentyfikuje Twoją wiki. 
Nie może ona zawierać spacji.

Jeśli korzystasz ze współdzielonego hostingu, dostawca usługi hostingowej może wymagać użycia konkretnej nazwy bazy danych lub pozwalać na tworzenie baz danych za pośrednictwem panelu użytkownika.',
	'config-db-name-oracle' => 'Schemat bazy danych',
	'config-db-install-account' => 'Konto użytkownika dla instalatora',
	'config-db-username' => 'Nazwa użytkownika bazy danych',
	'config-db-password' => 'Hasło bazy danych',
	'config-db-install-username' => 'Wprowadź nazwę użytkownika, który będzie używany do łączenia się z bazą danych podczas procesu instalacji.
Nie jest to nazwa konta MediaWiki, a użytkownika bazy danych.',
	'config-db-install-password' => 'Wprowadź hasło, które będzie wykorzystywane do łączenia się z bazą danych w procesie instalacji.
To nie jest hasło konta MediaWiki, lecz hasło do bazy danych.',
	'config-db-install-help' => 'Podaj nazwę użytkownika i jego hasło, które zostaną użyte do połączenia z bazą danych w czasie procesu instalacji.',
	'config-db-account-lock' => 'Użyj tej samej nazwy użytkownika i hasła w czasie normalnej pracy.',
	'config-db-wiki-account' => 'Konto użytkownika do normalnej pracy',
	'config-db-prefix' => 'Przedrostek tabel bazy danych',
	'config-db-charset' => 'Zestaw znaków bazy danych',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binarny',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 kompatybilny wstecz UTF-8',
	'config-mysql-old' => 'Wymagany jest MySQL $1 lub nowszy; korzystasz z $2.',
	'config-db-port' => 'Port bazy danych',
	'config-db-schema' => 'Schemat dla MediaWiki',
	'config-db-schema-help' => 'Ten schemat jest zazwyczaj właściwy.
Zmień go wyłącznie jeśli jesteś pewien, że powinieneś.',
	'config-sqlite-dir' => 'Katalog danych SQLite',
	'config-oracle-def-ts' => 'Domyślna przestrzeń tabel',
	'config-oracle-temp-ts' => 'Przestrzeń tabel tymczasowych',
	'config-support-info' => 'MediaWiki może współpracować z następującymi systemami baz danych:

$1

Jeśli system baz danych, z którego chcesz skorzystać nie jest wymieniony, postępuj zgodnie z instrukcjami aby móc z niego skorzystać.',
	'config-support-mysql' => '* $1 jest domyślną bazą danych dla MediaWiki i jest najlepiej wspierane ([http://www.php.net/manual/en/mysql.installation.php jak skompilować PHP ze wsparciem dla MySQL])',
	'config-support-postgres' => '* $1 jest popularnym systemem baz danych z otwartym kodem; jest alternatywą dla MySQL ([http://www.php.net/manual/en/pgsql.installation.php jak skompilować PHP ze wsparciem dla PostgreSQL])',
	'config-support-sqlite' => '* $1 jest lekkim systemem bazy danych, który jest bardzo dobrze wspierany. ([http://www.php.net/manual/en/pdo.installation.php Jak skompilować PHP ze wsparciem dla SQLite], korzystając z PDO)',
	'config-support-oracle' => '* $1 jest komercyjną profesjonalną bazą danych. ([http://www.php.net/manual/en/oci8.installation.php Jak skompilować PHP ze wsparciem dla OCI8])',
	'config-header-mysql' => 'Ustawienia MySQL',
	'config-header-postgres' => 'Ustawienia PostgreSQL',
	'config-header-sqlite' => 'Ustawienia SQLite',
	'config-header-oracle' => 'Ustawienia Oracle',
	'config-invalid-db-type' => 'Nieprawidłowy typ bazy danych',
	'config-missing-db-name' => 'Należy wpisać wartość w polu „Nazwa bazy danych”',
	'config-missing-db-host' => 'Musisz wpisać wartość w polu „Serwer bazy danych”',
	'config-missing-db-server-oracle' => 'Należy wpisać wartość w polu „Baza danych TNS”',
	'config-invalid-db-server-oracle' => 'Nieprawidłowa baza danych TNS „$1”.
Używaj wyłącznie liter ASCII (a-z, A-Z), cyfr (0-9), podkreślenia (_) i kropek (.).',
	'config-invalid-db-name' => 'Nieprawidłowa nazwa bazy danych „$1”.
Używaj wyłącznie liter ASCII (a-z, A-Z), cyfr (0-9), podkreślenia (_) lub znaku odejmowania (-).',
	'config-invalid-db-prefix' => 'Nieprawidłowy prefiks bazy danych „$1”.
Używaj wyłącznie liter ASCII (a-z, A-Z), cyfr (0-9), podkreślenia (_) lub znaku odejmowania (-).',
	'config-connection-error' => '$1. 

Sprawdź adres serwera, nazwę użytkownika i hasło, a następnie spróbuj ponownie.',
	'config-invalid-schema' => 'Nieprawidłowy schemat dla MediaWiki „$1”.
Używaj wyłącznie liter ASCII (a-z, A-Z), cyfr (0-9) i podkreślenia (_).',
	'config-postgres-old' => 'Wymagany jest PostgreSQL $1 lub nowszy; korzystasz z $2.',
	'config-sqlite-name-help' => 'Wybierz nazwę, która będzie identyfikować Twoją wiki.
Nie wolno używać spacji ani myślników.
Zostanie ona użyta jako nazwa pliku danych SQLite.',
	'config-sqlite-mkdir-error' => 'Błąd podczas tworzenia katalogu dla danych „$1”. 
Sprawdź lokalizację i spróbuj ponownie.',
	'config-sqlite-dir-unwritable' => 'Nie można zapisać do katalogu „$1”.
Zmień uprawnienia dostępu do katalogu tak, aby serwer WWW mógł pisać do niego, a następnie spróbuj ponownie.',
	'config-sqlite-connection-error' => '$1. 

Sprawdź katalog danych oraz nazwę bazy danych, a następnie spróbuj ponownie.',
	'config-sqlite-readonly' => 'Plik <code>$1</code> nie jest zapisywalny.',
	'config-sqlite-cant-create-db' => 'Nie można utworzyć pliku bazy danych <code>$1</code>.',
	'config-sqlite-fts3-downgrade' => 'Brak wsparcia FTS3 dla PHP. Tabele zostały cofnięte',
	'config-can-upgrade' => "W bazie danych są już tabele MediaWiki. 
Aby uaktualnić je do MediaWiki $1, kliknij '''Dalej'''.",
	'config-upgrade-done-no-regenerate' => 'Aktualizacja zakończona. 

Możesz wreszcie [$1 zacząć korzystać ze swojej wiki].',
	'config-regenerate' => 'Ponowne generowanie LocalSettings.php →',
	'config-show-table-status' => 'Zapytanie „SHOW TABLE STATUS” nie powiodło się!',
	'config-unknown-collation' => "'''Uwaga''' – bazy danych używa nierozpoznanej metody porównywania.",
	'config-db-web-account' => 'Konto bazy danych dla dostępu przez WWW',
	'config-db-web-help' => 'Wybierz nazwę użytkownika i hasło, z których korzystać będzie serwer WWW do łączenia się z serwerem baz danych, podczas zwykłej pracy z wiki.',
	'config-db-web-account-same' => 'Użyj tego samego konta, co dla instalacji',
	'config-db-web-create' => 'Utwórz konto, jeśli jeszcze nie istnieje',
	'config-db-web-no-create-privs' => 'Konto podane do wykonania instalacji nie ma wystarczających uprawnień, aby utworzyć nowe konto. 
Konto, które wskazałeś tutaj musi już istnieć.',
	'config-mysql-engine' => 'Silnik przechowywania',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Zestaw znaków bazy danych',
	'config-mysql-binary' => 'binarny',
	'config-mysql-utf8' => 'UTF‐8',
	'config-site-name' => 'Nazwa wiki',
	'config-site-name-help' => 'Ten napis pojawi się w pasku tytułowym przeglądarki oraz w różnych innych miejscach.',
	'config-site-name-blank' => 'Wprowadź nazwę witryny.',
	'config-project-namespace' => 'Przestrzeń nazw projektu',
	'config-ns-generic' => 'Projekt',
	'config-ns-site-name' => 'Taka sama jak nazwa wiki $1',
	'config-ns-other' => 'Inna (należy określić)',
	'config-ns-other-default' => 'MojaWiki',
	'config-ns-invalid' => 'Podana przestrzeń nazw „<nowiki>$1</nowiki>” jest nieprawidłowa.
Podaj inną przestrzeń nazw projektu.',
	'config-admin-box' => 'Konto administratora',
	'config-admin-name' => 'Administrator',
	'config-admin-password' => 'Hasło',
	'config-admin-password-confirm' => 'Hasło powtórnie',
	'config-admin-help' => 'Wprowadź preferowaną nazwę użytkownika, na przykład „Jan Kowalski”. 
Tej nazwy będziesz używać do logowania się do wiki.',
	'config-admin-name-blank' => 'Wpisz nazwę użytkownika, który będzie administratorem.',
	'config-admin-name-invalid' => 'Podana nazwa użytkownika „<nowiki>$1</nowiki>” jest nieprawidłowa.
Podaj inną nazwę.',
	'config-admin-password-blank' => 'Wprowadź hasło dla konta administratora.',
	'config-admin-password-same' => 'Hasło nie może być takie samo jak nazwa użytkownika.',
	'config-admin-password-mismatch' => 'Wprowadzone dwa hasła różnią się między sobą.',
	'config-admin-email' => 'Adres e‐mail',
	'config-admin-email-help' => 'Wpisz adres e‐mail, aby mieć możliwość odbierania e‐maili od innych użytkowników na wiki, zresetowania hasła oraz otrzymywania powiadomień o zmianach na stronach z listy obserwowanych.',
	'config-admin-error-user' => 'Błąd wewnętrzny podczas tworzenia konta administratora o nazwie „<nowiki>$1</nowiki>”.',
	'config-admin-error-password' => 'Wewnętrzny błąd podczas ustawiania hasła dla administratora „<nowiki>$1</nowiki>”: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Wpisałeś nieprawidłowy adres e‐mail',
	'config-subscribe' => 'Zapisz się na [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce listę pocztową z ogłaszaniami o nowych wersjach].',
	'config-subscribe-help' => 'Jest to lista o małej liczbie wiadomości, wykorzystywana do przesyłania informacji o udostępnieniu nowej wersji oraz istotnych sprawach dotyczących bezpieczeństwa.
Powinieneś zapisać się na tę listę i aktualizować zainstalowane oprogramowanie MediaWiki gdy pojawia się nowa wersja.',
	'config-almost-done' => 'To już prawie koniec!
Możesz pominąć pozostałe czynności konfiguracyjne i zainstalować wiki.',
	'config-optional-continue' => 'Zadaj mi więcej pytań.',
	'config-optional-skip' => 'Jestem już znudzony, po prostu zainstaluj wiki.',
	'config-profile' => 'Profil uprawnień użytkowników',
	'config-profile-wiki' => 'Tradycyjne wiki',
	'config-profile-no-anon' => 'Wymagane utworzenie konta',
	'config-profile-fishbowl' => 'Wyłącznie zatwierdzeni edytorzy',
	'config-profile-private' => 'Prywatna wiki',
	'config-license' => 'Prawa autorskie i licencja',
	'config-license-none' => 'Brak stopki z licencją',
	'config-license-cc-by-sa' => 'Creative Commons – za uznaniem autora, na tych samych zasadach',
	'config-license-cc-by-nc-sa' => 'Creative Commons – za uznaniem autora, bez użycia komercyjnego, na tych samych zasadach',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 lub późniejsza',
	'config-license-pd' => 'Domena publiczna',
	'config-license-cc-choose' => 'Wybierz własną licencję Creative Commons',
	'config-email-settings' => 'Ustawienia e-maili',
	'config-enable-email' => 'Włącz wychodzące wiadomości e–mail',
	'config-email-user' => 'Włącz możliwość przesyłania e‐maili pomiędzy użytkownikami',
	'config-email-user-help' => 'Zezwalaj użytkownikom na wysyłanie wzajemnie e‐maili, jeśli będą mieć włączoną tę funkcję w swoich preferencjach.',
	'config-email-usertalk' => 'Włącz powiadamianie o zmianach na stronie dyskusji użytkownika',
	'config-email-usertalk-help' => 'Pozwól użytkownikom otrzymywać powiadomienia o zmianach na stronie dyskusji użytkownika, jeśli będą mieć włączoną tę funkcję w swoich preferencjach.',
	'config-email-watchlist' => 'Włącz powiadomienie o zmianach stron obserwowanych',
	'config-email-watchlist-help' => 'Pozwól użytkownikom otrzymywać powiadomienia o zmianach na stronach obserwowanych, jeśli będą mieć włączoną tę funkcję w swoich preferencjach.',
	'config-email-auth' => 'Włącz uwierzytelnianie e‐mailem',
	'config-email-sender' => 'Zwrotny adres e‐mail',
	'config-upload-settings' => 'Przesyłanie obrazków i plików',
	'config-upload-enable' => 'Włącz przesyłanie plików na serwer',
	'config-upload-deleted' => 'Katalog dla usuniętych plików',
	'config-upload-deleted-help' => 'Wybierz katalog, w którym będzie archiwum usuniętych plików.
Najlepiej, aby nie był on dostępny z internetu.',
	'config-logo' => 'Adres URL logo',
	'config-instantcommons' => 'Włącz Instant Commons',
	'config-cc-error' => 'Wybieranie licencji Creative Commons nie dało wyniku.
Wpisz nazwę licencji ręcznie.',
	'config-cc-again' => 'Wybierz jeszcze raz...',
	'config-cc-not-chosen' => 'Wybierz którą chcesz licencję Creative Commons i kliknij „Dalej”.',
	'config-advanced-settings' => 'Konfiguracja zaawansowana',
	'config-cache-options' => 'Ustawienia buforowania obiektów',
	'config-cache-none' => 'Brak buforowania (wszystkie funkcje będą działać, ale mogą wystąpić kłopoty z wydajnością na dużych witrynach wiki)',
	'config-cache-accel' => 'Buforowania obiektów PHP (APC, eAccelerator, XCache lub WinCache)',
	'config-cache-memcached' => 'Użyj Memcached (wymaga dodatkowej instalacji i konfiguracji)',
	'config-memcached-servers' => 'Serwery Memcached:',
	'config-memcached-help' => 'Lista adresów IP do wykorzystania przez Memcached. 
Adresy powinny być umieszczane po jednym w linii i określać również wykorzystywany port. Na przykład:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-extensions' => 'Rozszerzenia',
	'config-install-alreadydone' => "'''Uwaga''' – wydaje się, że MediaWiki jest już zainstalowane, a obecnie próbujesz zainstalować je ponownie.
Przejdź do następnej strony.",
	'config-install-step-done' => 'gotowe',
	'config-install-step-failed' => 'nieudane',
	'config-install-extensions' => 'Włącznie z rozszerzeniami',
	'config-install-database' => 'Konfigurowanie bazy danych',
	'config-install-pg-schema-failed' => 'Utworzenie tabel nie powiodło się.
Upewnij się, że użytkownik „$1” może zapisywać do schematu „$2”.',
	'config-install-pg-commit' => 'Zatwierdzanie zmian',
	'config-pg-no-plpgsql' => 'Musisz zainstalować język PL/pgSQL w bazie danych $1',
	'config-install-user' => 'Tworzenie użytkownika bazy danych',
	'config-install-user-grant-failed' => 'Przyznanie uprawnień użytkownikowi „$1” nie powiodło się – $2',
	'config-install-tables' => 'Tworzenie tabel',
	'config-install-tables-exist' => "'''Uwaga''' – wygląda na to, że tabele MediaWiki już istnieją. 
Pomijam tworzenie tabel.",
	'config-install-tables-failed' => "'''Błąd''' – tworzenie tabeli nie powiodło się z powodu błędu – $1",
	'config-install-interwiki' => 'Wypełnianie tabeli domyślnymi interwiki',
	'config-install-interwiki-list' => 'Nie można odnaleźć pliku <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Uwaga''' – wygląda na to, że tabela interwiki ma już jakieś wpisy. 
Tworzenie domyślnej listy pominięto.",
	'config-install-keys' => 'Generowanie tajnego klucza',
	'config-install-sysop' => 'Tworzenie konta administratora',
	'config-install-subscribe-fail' => 'Nie można zapisać na listę „mediawiki-announce“',
	'config-install-mainpage' => 'Tworzenie strony głównej z domyślną zawartością',
	'config-install-mainpage-failed' => 'Nie udało się wstawić strony głównej – $1',
	'config-download-localsettings' => 'Pobierz LocalSettings.php',
	'config-help' => 'pomoc',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 * @author Krinkle
 */
$messages['pms'] = array(
	'config-desc' => "L'instalador për mediaWiki",
	'config-title' => 'Anstalassion ëd MediaWiki $1',
	'config-information' => 'Anformassion',
	'config-localsettings-upgrade' => "A l'é stàit trovà n'archivi <code>LocalSettings.php</code>.
Për agiorné cost'anstalassion, ch'a anserissa ël valor ëd <code>\$wgUpgradeKey</code> ant la casela sì-sota.
A la trovrà an LocalSetting.php.",
	'config-localsettings-key' => "Ciav d'agiornament:",
	'config-session-error' => 'Eror an fasend parte la session: $1',
	'config-session-expired' => "Ij sò dat ëd session a smijo scadù.
Le session a son configurà për na durà ëd $1.
A peul aumenté sòn an ampostand <code>session.gc_maxlifetime</code> an php.ini.
Ch'a anandia torna ël process d'instalassion.",
	'config-no-session' => "Ij sò dat ëd session a son përdù!
Ch'a contròla sò php.ini e ch'as sigura che <code>session.save_path</code> a sia ampostà ant ël dossié giust.",
	'config-your-language' => 'Toa lenga:',
	'config-your-language-help' => "Selessioné na lenga da dovré durant ël process d'instalassion.",
	'config-wiki-language' => 'Lenga dla Wiki:',
	'config-wiki-language-help' => 'Selession-a la lenga dont la wiki a sarà prevalentement scrivùa.',
	'config-back' => '← André',
	'config-continue' => 'Continua →',
	'config-page-language' => 'Lenga',
	'config-page-welcome' => 'Bin  ëvnù a MediaWiki!',
	'config-page-dbconnect' => 'Coleghesse a la base ëd dàit',
	'config-page-upgrade' => "Agiorné l'instalassion esistenta",
	'config-page-dbsettings' => 'Ampostassion dla base ëd dàit',
	'config-page-name' => 'Nòm',
	'config-page-options' => 'Opsion',
	'config-page-install' => 'Instala',
	'config-page-complete' => 'Completa!',
	'config-page-restart' => "Fé torna parte l'instalassion",
	'config-page-readme' => 'Lesme',
	'config-page-releasenotes' => 'Nòte ëd publicassion',
	'config-page-copying' => 'Copié',
	'config-page-upgradedoc' => 'Agiorné',
	'config-help-restart' => "Veul-lo scancelé tùit ij dat salvà ch'a l'ha anserì e anandié torna ël process d'instalassion?",
	'config-restart' => 'É!, felo torna parte',
	'config-welcome' => "=== Contròj d'ambient ===
Dle verìfiche ëd base a son fàite për vëdde se st'ambient a va bin për l'instalassion ëd MediaWiki.
S'a l'ha da manca d'agiut durant l'anstalassion, a dovrìa fornì j'arzultà dë sti contròj.",
	'config-copyright' => "=== Drit d'Autor e Condission ===

$1

Cost-sì a l'é un programa lìber e a gràtis: a peul ridistribuilo e/o modifichelo sota le condission dla licensa pùblica general GNU com publicà da la Free Software Foundation; la version 2 dla Licensa, o (a toa sèrnìa) qualsëssìa version pi recenta.

Cost programa a l'é distribuì ant la speransa ch'a sia ùtil, ma '''sensa gnun-e garansìe'''; sensa gnanca la garansia implìssita ëd '''comersiabilità''' o '''d'esse adat a un but particolar'''.

A dovrìa avèj arseivù <doclink href=Copying>na còpia ëd la licensa pùblica general GNU</doclink> ansema a sto programa; dësnò, ch'a scriva a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA opura [http://www.gnu.org/copyleft/gpl.html ch'a la lesa an linia].",
	'config-sidebar' => "* [http://www.mediawiki.org Intrada MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Guida dl'Utent]
* [http://www.mediawiki.org/wiki/Manual:Contents Guida dl'Aministrator]
* [http://www.mediawiki.org/wiki/Manual:FAQ Soens an ciamo]",
	'config-env-good' => "L'ambient a l'é stàit controlà.
It peule instalé MediaWiki.",
	'config-env-bad' => "L'ambient a l'é stàit controlà.
It peule pa instalé MediaWiki.",
	'config-env-php' => "PHP $1 a l'é instalà.",
	'config-unicode-using-utf8' => 'As deuvra utf8_normalize.so ëd Brion Vibber për la normalisassion Unicode.',
	'config-unicode-using-intl' => "As deuvra l'[http://pecl.php.net/intl estension intl PECL] për la normalisassion Unicode.",
	'config-unicode-pure-php-warning' => "'''Avis:''' L'[http://pecl.php.net/intl estension intl PECL] a l'é pa disponìbil për gestì la normalisassion Unicode, da già che l'implementassion an PHP pur a faliss për lentëssa.
S'a gestiss un sit a àut tràfich, a dovrìa lese cheicòs an sla [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalisassion Unicode].",
	'config-unicode-update-warning' => "'''Avis:''' La version instalà dlë spassiador ëd normalisassion Unicode a deuvra na version veja ëd la librarìa dël [http://site.icu-project.org/ proget ICU].
A dovrìa fé n'[http://www.mediawiki.org/wiki/Unicode_normalization_considerations agiornament] s'a l'é anteressà a dovré Unicode.",
	'config-no-db' => 'Impossìbil tové un pilòta ëd base ëd dàit bon!',
	'config-no-db-help' => "A dev instalé un pilòta ëd base ëd dàit për PHP.
A son mantnùe le sòrt ëd base ëd dàit sì-dapress: $1.

S'a l'é ospità ëd fasson partagià, ch'a ciama al fornidor d'ospitalità d'instalé un pilòta ëd base ëd dàit adat.
S'a l'ha compilà chiel-midem PHP, ch'a lo configura torna con un client ëd base ëd dàit abilità, për esempi an dovrand <code>./configure --with-mysql</code>.
S'a l'ha instalà PHP da un pachet Debian o Ubuntu, antlora a dev ëdcò instalé ël mòdul php5-mysql.",
	'config-no-fts3' => "'''Avis''': SQLite a l'é compilà sensa ël mòdul [http://sqlite.org/fts3.html FTS3], le funsion d'arserca a saran pa disponìbij su cost motor.",
	'config-register-globals' => "'''Avis: L'opsion <code>[http://php.net/register_globals register_globals]</code> ëd PHP a l'é abilità.'''
'''Ch'a la disabìlita s'a peul.'''
MediaWiki a marcërà, ma sò servent a l'é espòst a 'd possìbij vunerabilità ëd sicurëssa.",
	'config-magic-quotes-runtime' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime] a l'é ativ!'''
Costa opsion a danegia ij dat d'intrada an manera pa prevedìbil.
A peul pa instalé o dovré MediaWiki se st'opsion a l'é pa disabilità.",
	'config-magic-quotes-sybase' => "'''Fatal: [http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-sybase magic_quotes_sybase] a l'é ativ!'''
Costa opsion a danegia ij dat d'intrada an manera pa prevedìbil.
A peul pa instalé o dovré MediaWiki se st'opsion a l'é pa disabilità.",
	'config-mbstring' => "'''Fatal: [http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload] a l'é ativ!'''
Costa opsion a càusa d'eror e a peul danegié ij dat d'intrada an manera pa prevedìbil.
A peul pa instalé o dovré MediaWiki se st'opsion a l'é pa disabilità.",
	'config-ze1' => "'''Fatal: [http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode] a l'é ativ!'''
Costa opsion a càusa dij bigat afros con MediaWiki.
A peul pa instalé o dovré MediaWiki se st'opsion a l'é pa disabilità.",
	'config-safe-mode' => "'''Avis:''' [http://www.php.net/features.safe-mode Safe mode] ëd PHP a l'é ativ.
A peul causé ëd problema, dzortut s'as deuvro ël cariament d'archivi e ël manteniment ëd <code>math</code>.",
	'config-xml-bad' => "Mòdul XML ed PHP mancant.
MediaWiki a l'ha da manca dle funsion an sto mòdul e a travajërà pa an costa configurassion.
S'a fa giré mandrake, ch'a instala ël pachet php-xml.",
	'config-pcre' => "A smija che ël mòdul d'apògg PCRE a sia mancant.
MediaWiki a l'ha da manca dle funsion d'espression regolar Perl-compatìbij për marcé.",
	'config-memory-raised' => "<code>memory_limit</code> ëd PHP a l'é $1, aussà a $2.",
	'config-memory-bad' => "'''Avis:''' <code>memory_limit</code> ëd PHP a l'é $1.
Sossì a l'é probabilment tròp bass.
L'instalassion a peul falì!",
	'config-xcache' => "[http://trac.lighttpd.net/xcache/ XCache] a l'é instalà",
	'config-apc' => "[http://www.php.net/apc APC] a l'é instalà",
	'config-eaccel' => "[http://eaccelerator.sourceforge.net/ eAccelerator] a l'é instalà",
	'config-wincache' => "[http://www.iis.net/download/WinCacheForPhp WinCache]  a l'é instalà",
	'config-no-cache' => "'''Avis:''' As treuva pa [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] o [http://www.iis.net/download/WinCacheForPhp WinCache]. Ël buté d'oget an memòria local a l'é pa abilità.",
	'config-diff3-bad' => 'GNU diff3 pa trovà.',
	'config-imagemagick' => "Trovà ImageMagick: <code>$1</code>.
La miniaturisassion ëd figure a sarà abilità s'it abìlite le carie.",
	'config-gd' => "Trovà la librarìa gràfica antëgrà GD.
La miniaturisassion ëd figure a sarà abilità s'a abìlita ij cariament.",
	'config-no-scaling' => 'As treuva pa la librarìa GD o ImageMagick.
La miniaturisassion ëd figure a sarà disabilità.',
	'config-no-uri' => "'''Eror:''' As peul pa determiné l'URI corenta.
Instalassion abortìa.",
	'config-uploads-not-safe' => "'''Avis:''' Sò dossié stàndard për carié <code>$1</code> a l'é vulneràbil a l'esecussion ëd qualsëssìa senari.
Bele che MediaWiki a contròla j'aspet ëd sicurëssa ëd tùit j'archivi carià, a l'é motobin arcomandà ëd [http://www.mediawiki.org/wiki/Manual:Security#Upload_security saré ës përtus ëd sicurëssa] prima d'abilité ij cariament.",
	'config-db-type' => 'Sòrt ëd base ëd dàit:',
	'config-db-host' => 'Ospitant ëd la base ëd dàit:',
	'config-db-host-help' => "Se sò servent ëd base ëd dàit a l'é su un servent diferent, ch'a anseriss ambelessì ël nòm dl'ospitant o l'adrëssa IP.

S'a deuvra n'ospitalità partagià, sò fornidor d'ospitalità a dovrìa deje ël nòm dl'ospitant giust ant soa documentassion.

Se a anstala su un servent Windows e a deuvra MySQL, dovré \"localhost\" a podrìa funsioné nen com nòm dël servent. S'a marcia nen, ch'a preuva \"127.0.0.1\" com adrëssa IP local.",
	'config-db-host-oracle' => 'TNS dla base ëd dàit:',
	'config-db-host-oracle-help' => "Anserì un [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm nòm ëd conession local] bon; n'archivi tnsnames.ora a dev esse visìbil da costa anstalassion..<br />S'a deuvra le librarìe cliente 10g o pi neuve a peul ëdcò dovré ël métod ëd nominassion [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].",
	'config-db-wiki-settings' => 'Identìfica sta wiki',
	'config-db-name' => 'Nòm dla base ëd dàit:',
	'config-db-name-help' => "Ch'a serna un nòm ch'a identìfica soa wiki.
A dovrìa conten-e gnun ëspassi o tratin.

S'a deuvra n'ospitalità partagià, sò fornidor ëd l'ospitalità a-j darà un nòm ëd base ëd dàit specìfich da dovré, o a lassrà ch'a lo crea via un panel ëd contròl.",
	'config-db-name-oracle' => 'Schema dla base ëd dàit:',
	'config-db-install-account' => "Cont d'utent për l'instalassion.",
	'config-db-username' => "Nòm d'utent dla base ëd dàit:",
	'config-db-password' => 'Ciav dla base ëd dàit:',
	'config-db-install-help' => "Ch'a anserissa lë stranòm d'utent e la ciav che a saran dovrà për coleghesse a la base ëd dàit durant ël process d'instalassion.",
	'config-db-account-lock' => "Dovré ij midem stranòm d'utent e ciav durant j'operassion normaj",
	'config-db-wiki-account' => "Cont d'utent për j'operassion normaj",
	'config-db-wiki-help' => "Ch'a anseriss lë stranòm d'utent e la ciav che a saran dovrà për coleghesse a la base ëd dàit durant j'operassion normaj dla wiki.
S'ël cont a esist pa, e ël cont d'instalassion a l'ha ij privilegi ch'a-i van, sto cont utent a sarà creà con ij privilegi mìnin për fé marcé la wiki.",
	'config-db-prefix' => 'Prefiss dle tàule dla base ëd dàit:',
	'config-db-prefix-help' => "S'a l'ha dabzògn ëd partagé na base ëd dàit an tra vàire wiki, o tra MediaWiki e n'àutra aplicassion dl'aragnà, a peul serne ëd gionté un prefiss a tùit ij nòm ëd le tàule për evité ëd conflit.
Ch'a deuvra ni dë spassi ni ëd tratin.

Cost camp a l'é lassà normalment veuid.",
	'config-db-charset' => 'Ansema dij caràter dla base ëd dàit',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binari',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => "MySQL 4.0 compatìbil a l'andaré con UTF-8",
	'config-charset-help' => "'''Avis:''' S'a deuvra '''UTF-8 compatìbil a l'andaré''' su MySQL 4.1+, e peui a fa na còpia con <code>mysqldump</code>, a podrìa scancelé tùit ij caràter nen-ASCII, dësbland sensa speranse soe còpie!

An '''manera binaria''', MediaWiki a memorisa ël test UTF-8 an dij camp binari ant la base ëd dàit.
Sossì a l'é pi eficient che la manera UTF-8 ëd MySQL, e a përmët ëd dovré tut l'ansema ëd caràter Unicode.
An '''manera UTF-8''', MySQL a arconòss an che ansema ëd caràter a son ij sò dat, e a peul presenteje e convertije apropriatament, ma a-j lassrà pa memorisé ij caràter dzora al [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes pian multilenghe ëd base].",
	'config-mysql-old' => "A-i é da manca ëd MySQL $1 o pi recent, chiel a l'ha $2.",
	'config-db-port' => 'Porta dla base ëd dàit:',
	'config-db-schema' => 'Schema për MediaWiki',
	'config-db-schema-help' => "Jë schema sì-dzora a son normalment giust.
Ch'a-j cangia mach s'a sa ch'a n'ha da manca.",
	'config-sqlite-dir' => 'Dossié dij dat SQLite:',
	'config-sqlite-dir-help' => "SQLite a memorisa tùit ij dat ant n'archivi ùnich.

Ël dossié che chiel a forniss a dev esse scrivìbil dal servent durant l'instalassion.

A dovrìa '''pa''' esse acessìbil da l'aragnà, sossì a l'é për sòn ch'i l'oma pa butalo andova a-i son ij sò file PHP.

L'instalador a scriverà n'archivi <code>.htaccess</code> ansema con chiel, ma se lòn a faliss quaidun a peul intré an soa base ëd dàit originaria.
Lòn a comprend ij dat brut ëd l'utent (adrëssa ëd pòsta eletrònica, ciav tërbola) e ëdcò le revision scancelà e d'àutri dat segret ëd la wiki.

Ch'a consìdera ëd buté la base ëd dàit tuta antrega da n'àutra part, për esempi an <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Spassi dla tàula dë stàndard:',
	'config-oracle-temp-ts' => 'Spassi dla tàula temporani:',
	'config-support-info' => "MediaWiki a manten ij sistema ëd base ëd dàit sì-dapress:

$1

S'a vëd pa listà sì-sota ël sistema ëd base ëd dàit ch'a preuva a dovré, antlora va andaré a j'istrussion dl'anliura sì-dzora për abilité ël manteniment.",
	'config-support-mysql' => "* $1 e l'é l'obietiv primari për MediaWiki e a l'é mej mantnù ([http://www.php.net/manual/en/mysql.installation.php com compilé PHP con ël manteniment MySQL])",
	'config-support-postgres' => "* $1 e l'é un sistema ëd base ëd dàit popolar a sorgiss duverta com alternativa a MySQL ([http://www.php.net/manual/en/pgsql.installation.php com compilé PHP con ël manteniment ëd PostgreSQL])",
	'config-support-sqlite' => "* $1 e l'é un sistema ëd base ëd dàit leger che a l'é motobin bin mantnù ([http://www.php.net/manual/en/pdo.installation.php com compilé PHP con ël manteniment ëd SQLite], a deuvra PDO)",
	'config-support-oracle' => "* $1 a l'é na base ëd dàit comersial për j'amprèise. ([http://www.php.net/manual/en/oci8.installation.php Com compilé PHP con ël manteniment OCI8])",
	'config-header-mysql' => 'Ampostassion MySQL',
	'config-header-postgres' => 'Ampostassion PostgreSQL',
	'config-header-sqlite' => 'Ampostassion SQLite',
	'config-header-oracle' => 'Ampostassion Oracle',
	'config-invalid-db-type' => 'Sòrt ëd ëd base ëd dàit pa bon-a',
	'config-missing-db-name' => 'A dev buteje un valor për "Nòm ëd la base ëd dàit"',
	'config-missing-db-server-oracle' => 'A dev buteje un valor për "TNS ëd la base ëd dat"',
	'config-invalid-db-server-oracle' => 'TNS ëd la base ëd dat pa bon "$1".
Dovré mach dle litre ASCII (a-z, A-Z), nùmer (0-9), sotlignadure (_) e pontin (.).',
	'config-invalid-db-name' => 'Nòm ëd la base ëd dàit pa bon "$1".
Dovré mach litre ASCII (a-z, A-Z), nùmer (0-9) e sotlignadure (_).',
	'config-invalid-db-prefix' => 'Prefiss dla base ëd dàit pa bon "$1".
Dovré mach litre ASCII (a-z, A-Z), nùmer (0-9) e sotlignadure (_).',
	'config-connection-error' => "$1.

Controla l'ospitant, lë stranòm d'utent e la ciav sì-sota e prové torna.",
	'config-invalid-schema' => 'Schema pa bon për MediaWiki "$1".
Dovré mach litre ASCII (a-z, A-Z), nùmer (0-9) e sotlignadure (_).',
	'config-postgres-old' => "A-i é da manca ëd PostgreSQL $1 o pi recent, chiel a l'ha $2.",
	'config-sqlite-name-help' => "Serne un nòm ch'a identìfica soa wiki.
Dovré nì dë spassi nì ëd tratin.
Sòn a sarà dovrà për ël nòm ëd l'archivi ëd dat SQLite.",
	'config-sqlite-parent-unwritable-group' => "As peul pa creesse ël dossié ëd dat <code><nowiki>$1</nowiki></code>, përchè ël dossié a mont <code><nowiki>$2</nowiki></code> a l'é pa scrivìbil dal servent.

L'instalador a l'ha determinà sota che utent a gira sò servent.
Fé an manera che ël dossié <code><nowiki>$3</nowiki></code> a sia scrivìbil da chiel për continué.
Su un sistema Unix/Linux buté:
<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>",
	'config-sqlite-parent-unwritable-nogroup' => "As peul pa creesse ël dossié ëd dat <code><nowiki>$1</nowiki></code>, përchè ël dossié a mont <code><nowiki>$2</nowiki></code> a l'é pa scrivìbil dal servent.

L'instalador a peul pa determiné l'utent sota ël qual a gira sò servent.
Fé an manera che ël dossié <code><nowiki>$3</nowiki></code> a sia scrivìbil globalment da chiel (e da d'àutri) për continué.
Su un sistema Unix/Linux buté:
<pre>cd $2
mkdir $3
chmod a+w $3</pre>",
	'config-sqlite-mkdir-error' => 'Eror an creand ël dossié ëd dat "$1".
Ch\'a contròla la locassion e ch\'a preuva torna.',
	'config-sqlite-dir-unwritable' => 'As peul pa scrivse an sël dossié "$1".
Modifiché ij sò përmess an manera che ël servent a peula scrivje ansima, e prové torna.',
	'config-sqlite-connection-error' => '$1.

Controlé ël dossié ëd dat e ël nòm ëd la base ëd dàit ambelessì-sota e prové torna.',
	'config-sqlite-readonly' => "L'archivi <code>$1</code> a l'é nen scrivìbil.",
	'config-sqlite-cant-create-db' => "As peul pa cresse l'archivi ëd base ëd dàit <code>$1</code>.",
	'config-sqlite-fts3-downgrade' => "PHP a l'ha pa ël supòrt ëd FTS3, le tàule a son degradà",
	'config-can-upgrade' => "A-i é dle tàule MediaWiki an costa base ëd dàit.
Për agiorneje a MediaWiki $1, ch'a sgnaca su '''Continué'''.",
	'config-upgrade-done' => "Agiornament completà.

Adess a peule [$1 ancaminé a dovré soa wiki].

S'a veul generé torna sò archivi <code>LocalSettings.php</code>, ch'a sgnaca ël boton sì-sota.
Sòn a l'è '''pa arcomandà''' gavà ch'a rancontra dij problema con soa wiki.",
	'config-regenerate' => 'Generé torna LocalSettings.php →',
	'config-show-table-status' => 'Arcesta SHOW TABLE STATUS falìa!',
	'config-unknown-collation' => "'''Avis:''' La base ëd dàit a deuvra na classificassion pa arconossùa.",
	'config-db-web-account' => "Cont dla base ëd dàit për l'acess a l'aragnà",
	'config-db-web-help' => "Ch'a selession-a lë stranòm d'utent e la ciav che ël servent ëd l'aragnà a dovrërà për coleghesse al servent dle base ëd dàit, durant j'operassion ordinarie dla wiki.",
	'config-db-web-account-same' => "Ch'a deuvra ël midem cont com për l'istalassion",
	'config-db-web-create' => "Crea ël cont se a esist pa anco'",
	'config-db-web-no-create-privs' => "Ël cont ch'a l'ha specificà për l'instalassion a l'ha pa basta 'd privilegi për creé un cont.
Ël cont ch'a spessìfica ambelessì a dev già esiste.",
	'config-mysql-engine' => 'Motor ëd memorisassion:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB''' a l'é scasi sempe la mej opsion, da già ch'a l'ha un bon manteniment dla concorensa.

'''MyISAM''' a peul esse pi lest an instalassion për n'utent sol o mach an letura.
La base ëd dàit MyISAM a tira a corompse pi 'd soens che la base ëd dàit InnoDB.",
	'config-mysql-charset' => 'Ansem ëd caràter dla base ëd dàit:',
	'config-mysql-binary' => 'Binari',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "An '''manera binaria''', MediaWiki a memorisa ël test UTF-8 ant la base ëd dàit an camp binari.
Sòn a l'é pi eficient che la manera UTF-8 ëd MySQL, e a-j përmët ëd dovré l'ansema antregh ëd caràter Unicode.

An '''manera UTF-8''', MySQL a conossrà an che ansem ëd caràter a son ij sò dat, e a peul presenteje e convertije apropriatament, ma a-j lassa pa memorisé ij caràter ëdzora al [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes pian multilenghìstich ëd base].",
	'config-site-name' => 'Nòm ëd la wiki:',
	'config-site-name-help' => "Sòn a comparirà ant la bara dël tìtol dël navigador e an vàire d'àutri pòst.",
	'config-site-name-blank' => "Ch'a buta un nòm ëd sit.",
	'config-project-namespace' => 'Spassi nominal dël proget:',
	'config-ns-generic' => 'Proget',
	'config-ns-site-name' => 'Midem com ël nom dla wiki: $1',
	'config-ns-other' => 'Àutr (specìfica)',
	'config-ns-other-default' => 'MyWiki',
	'config-project-namespace-help' => "Andasend daré a l'esempi ëd Wikipedia, vàire wiki a manten-o soe pàgine ëd regolament separà da soe pàgine ëd contnù, ant në \"'''spassi nominal ëd proget'''\".
Tùit ij tìtoj ëd pàgina ant cost ëspassi nominal a parto con un sert prefiss, che a peul specifiché ambelessì.
Tradissionalment, sto prefiss a l'é derivà dal nòm ëd la wiki, ma a peul pa conten-e caràter ëd pontegiatura coma \"#\" o \":\".",
	'config-ns-invalid' => 'Lë spassi nominal specificà "<nowiki>$1</nowiki>" a l\'é pa bon.
Specìfica në spassi nominal ëd proget diferent.',
	'config-admin-box' => "Cont ëd l'Aministrator",
	'config-admin-name' => 'Tò nòm:',
	'config-admin-password' => 'Ciav:',
	'config-admin-password-confirm' => 'Buté torna la ciav:',
	'config-admin-help' => "Ch'a butà ambelessì tò stranòm d'utent preferì, për esempi \"Gioann Scriv\".
Cost-sì a l'é lë stranòm ch'a dovrërà për intré ant la wiki.",
	'config-admin-name-blank' => "Ch'a anserissa në stranòm d'aministrator.",
	'config-admin-name-invalid' => 'Ël nòm utent specificà "<nowiki>$1</nowiki>" a l\'é pa bon.
Specìfica un nòm utent diferent.',
	'config-admin-password-blank' => "Ch'a anserissa na ciav për ël cont d'aministrator.",
	'config-admin-password-same' => "La ciav a dev nen esse l'istessa ëd lë stranòm d'utent.",
	'config-admin-password-mismatch' => "Le doe ciav che a l'ha scrivù a son diferente antra 'd lor.",
	'config-admin-email' => 'Adrëssa ëd pòsta eletrònica:',
	'config-admin-email-help' => "Ch'a anserissa ambelessì n'adrëssa ëd pòsta eletrònica për përmëtt-je d'arsèive ëd mëssagi da d'àutri utent an sla wiki, riamposté soa ciav, e esse anformà ëd camgiament a le pàgine ch'a ten sot-euj.",
	'config-admin-error-user' => 'Eror antern an creand n\'aministrator con lë stranòm "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Eror antern an ampostand na ciav për l\'admin "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => "Ch'a sot-scriva la [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce lista ëd discussion ëd j'anonsi ëd publicassion].",
	'config-subscribe-help' => "Costa a l'é na lista ëd discussion a bass tràfich dovrà për j'anonsi ëd publicassion, comprèis d'amportant anonsi ëd sicurëssa.
A dovrìa sot-ëscrivla e agiorné soa instalassion mediaWiki quand che ëd version neuve a rivo.",
	'config-almost-done' => "A l'ha bele che fàit!
A peul adess sauté la configurassion rimanenta e instalé dlongh la wiki.",
	'config-optional-continue' => "Ciameme d'àutre chestion.",
	'config-optional-skip' => 'I son già anojà, instala mach la wiki.',
	'config-profile' => "Profil dij drit d'utent:",
	'config-profile-wiki' => 'Wiki tradissional',
	'config-profile-no-anon' => 'A venta creé un cont',
	'config-profile-fishbowl' => 'Mach editor autorisà',
	'config-profile-private' => 'Wiki privà',
	'config-profile-help' => "Le wiki a marcio mej quand ch'a lassa che pì përsone possìbij a-j modìfico.
An MediaWiki, a l'é bel fé revisioné ij cambi recent, e buté andré minca dann che a sia fàit da utent noviss o malissios.

An tùit ij cas, an tanti a l'han trovà che MediaWiki a sia ùtil ant na gran varietà ëd manere, e dle vire a l'é pa bel fé convince cheidun dij vantagi dla wiki.
Parèj a l'ha doe possibilità.

Un '''{{int:config-profile-wiki}}''' a përmët a chicassìa ëd modifiché, bele sensa intré ant ël sistema.
Na wiki con  '''{{int:config-profile-no-anon}}''' a dà pì 'd contròl, ma a peul slontané dij contribudor casuaj.

Ël senari '''{{int:config-profile-fishbowl}}''' a përmët a j'utent aprovà ëd modifiché, ma ël pùblich a peul vëdde le pàgine, comprèisa la stòria.
Un '''{{int:config-profile-private}}''' a përmët mach a j'utent aprovà ëd vëdde le pàgine, con la midema partìa ch'a peul modifiché.

Configurassion ëd drit d'utent pi complicà a son disponìbij apress l'instalassion, vëdde la [http://www.mediawiki.org/wiki/Manual:User_rights pàgina a pòsta dël manual].",
	'config-license' => "Drit d'autor e licensa",
	'config-license-none' => 'Gnun-a licensa an nòta an bass',
	'config-license-cc-by-sa' => 'Creative Commons atribussion an part uguaj',
	'config-license-cc-by-nc-sa' => 'Creative Commons atribussion nen comersial an part uguaj',
	'config-license-gfdl-old' => 'Licensa ëd documentassion lìbera GNU 1.2',
	'config-license-gfdl-current' => 'Licensa ëd documentassion lìbera GNU 1.3 o pi recenta',
	'config-license-pd' => 'Domini Pùblich',
	'config-license-cc-choose' => 'Selessioné na licensa Creative Commons përsonalisà',
	'config-license-help' => "Vàire wiki pùbliche a buto tute le contribussion sota na [http://freedomdefined.org/Definition licensa lìbera]. Sòn a giuta a creé un sens d'apartenensa a la comunità e a ancoragia ëd contribussion ëd longa durà.
A l'é generalment nen necessari për na wiki privà o d'asienda.

S'a veul podèj dovré dij test da Wikipedia, e a veul che Wikipedia a aceta dij test copià da soa wiki, a dovrìa serne '''Creative Commons Attribution Share Alike'''.

La GNU Free Documentation License a l'era la veja licensa dont sota a-i era Wikipedia.
A l'é anco' na licensa bon-a, an tùit ij cas, sta licensa a l'ha chèich funsion ch'a rendo difìcij l'utilisassion e l'antërpretassion.",
	'config-email-settings' => 'Ampostassion ëd pòsta eletrònica',
	'config-enable-email' => 'Abilité ij mëssagi ëd pòsta eletrònica an surtìa',
	'config-enable-email-help' => "S'a veul che la pòsta eletrònica a marcia, j'[http://www.php.net/manual/en/mail.configuration.php ampostassion ëd pòsta eletrònica PHP] a devo esse configurà për da bin.
S'a veul pa 'd funsion ëd pòsta eletrònica, a dev disabiliteje ambelessì.",
	'config-email-user' => 'Abilité ij mëssagi ëd pòsta eletrònica da utent a utent',
	'config-email-user-help' => "A përmët a tùit j'utent ëd mandesse ëd mëssagi ëd pòsta eletrònica se lor a l'han abilità sòn an soe preferense.",
	'config-email-usertalk' => "Abilité notìfica dle pàgine ëd discussion dj'utent",
	'config-email-usertalk-help' => "A përmët a j'utent d'arsèive na notìfica dle modìfiche dle pàgine ëd discussion d'utent, s'a l'han abilitalo ant soe preferense.",
	'config-email-watchlist' => "Abilité la notìfica ëd lòn ch'as ten sot euj",
	'config-email-watchlist-help' => "A përmët a j'utent d'arsèive dle notificassion a propòsit dle pàgine ch'a ten-o sot euj s'a l'han abilitalo ant soe preferense.",
	'config-email-auth' => "Abilité l'autenticassion për pòsta eletrònica",
	'config-email-auth-help' => "Se st'opsion a l'é abilità, j'utent a devo confirmé soe adrësse ëd pòsta eletrònica an dovrand un colegament mandà a lor quand ch'a l'han ampostala o cambiala.
Mach j'adrësse ëd pòsta eletrònica autenticà a peulo arsèive ëd mëssagi da j'àutri utent o cangé adrëssa ëd notìfica.
Amposté st'opsion a l'é '''arcomandà''' për le wiki pùbliche a càusa ëd possìbij abus ëd le funsion ëd pòsta eletrònica.",
	'config-email-sender' => 'Adrëssa ëd pòsta eletrònica ëd ritorn:',
	'config-email-sender-help' => "Ch'a anserissa l'adrëssa ëd pòsta eletrònica da dovré com adrëssa d'artorn dij mëssagi an surtìa.
Ambelessì a l'é andova j'arspòste a saran mandà.
Motobin ëd servent ëd pòsta a ciamo che almanch la part dël nòm ëd domini a sia bon-a.",
	'config-upload-settings' => 'Cariament ëd figure e archivi',
	'config-upload-enable' => "Abilité ël cariament d'archivi",
	'config-upload-help' => "Carié d'archivi potensialment a espon sò servent a d'arzigh ëd sicurëssa.
Per pi d'anformassion, ch'a lesa la [http://www.mediawiki.org/wiki/Manual:Security session ëd sicurëssa] d'ës manual.

Për abilité ël cariament d'archivi, ch'a modìfica la manera dël sot-dossié dle <code>figure</code> sota al dossié rèis ëd MediaWiki an manera che ël servent dl'aragnà a peussa scrivlo.
Peui ch'a abìlita costa opsion.",
	'config-upload-deleted' => "Dossié për j'archivi scancelà:",
	'config-upload-deleted-help' => "ch'a serna un dossié andova goerné j'archivi scancelà.
Idealment, sòn a dovrìa pa esse acessìbil an sl'aragnà.",
	'config-logo' => 'Anliura dla marca:',
	'config-logo-help' => "La pel dë stàndard ëd MediaWiki a comprend lë spassi për na marca ëd 135x160 pontin ant ël canton an àut a snista.
Ch'a dëscaria na figura ëd la dimension aproprià, e ch'a anserissa l'anliura ambelessì.

S'a veul gnun-e marche, ch'a lassa ës camp bianch.",
	'config-instantcommons' => 'Abìlita Instant Commons',
	'config-instantcommons-help' => "[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] a l'é na funsion ch'a përmët a le wiki ëd dovré dle figure, dij son e d'àutri mojen trovà an sël sit [http://commons.wikimedia.org/ Wikimedia Commons].
Për dovré sossì, MediaWiki a l'ha da manca dl'acess a la ragnà. 

Për pi d'anformassion su sta funsion, comprèise j'istrussion ëd com ampostela për wiki diferente da Wikimedia Commons, ch'a consulta [http://mediawiki.org/wiki/Manual:\$wgForeignFileRepos ël manual].",
	'config-cc-error' => "La selession ëd la licensa Creative Commons a l'ha dàit gnun arzultà.
Ch'a anserissa ël nòm dla licensa a man.",
	'config-cc-again' => 'Torna cheuje...',
	'config-cc-not-chosen' => 'Sern che licensa Creative Commons it veule e sgnaca "anans".',
	'config-advanced-settings' => 'Configurassion avansà',
	'config-cache-options' => "Ampostassion për la memorisassion local d'oget:",
	'config-cache-help' => "La memorisassion loca d'oget a l'é dovrà për amelioré l'andi ëd MediaWiki an butant an local dij dat dovrà 'd soens.
Ij sit da mesan a gròss a son motobin ancoragià a abilité sòn, e ij sit cit a l'avran ëdcò dij benefissi.",
	'config-cache-none' => "Gnun-a memorisassion local (gnun-a funsionalità gavà, ma l'andi a peul esse anfluensà an sij sit ëd wiki gròsse)",
	'config-cache-accel' => "Memorisassion local d'oget PHP (APC, eAccelerator, XCache o WinCache)",
	'config-cache-memcached' => "Dovré Memcached (a ciama n'ampostassion e na configurassion adissionaj)",
	'config-memcached-servers' => 'Servent Memcached:',
	'config-memcached-help' => "Lista d'adrësse IP da dovré për Memcached.
A dovrìa esse separà con dle vìrgole e specifiché la pòrta da dovré (për esempi: 127.0.0.1:11211, 192.168.1.25:11211).",
	'config-extensions' => 'Estension',
	'config-extensions-help' => "J'estension listà dì-sota a son ëstàite trovà ant sò dossié <code>./extensions</code>.

A peulo avèj da manca ëd configurassion adissionaj, ma a peul abiliteje adess",
	'config-install-alreadydone' => "'''Avis''' A smija ch'a l'abie già instalà MediaWiki e ch'a preuva a instalelo torna.
Për piasì, ch'a vada a la pàgina ch'a-i ven.",
	'config-install-step-done' => 'fàit',
	'config-install-step-failed' => 'falì',
	'config-install-extensions' => "Comprende j'estension",
	'config-install-database' => 'Creassion ëd la base ëd dàit',
	'config-install-pg-schema-failed' => 'Creassion dle tàule falìa.
Sigurte che l\'utent "$1" a peussa scrive lë schema "$2".',
	'config-install-user' => "Creassion ëd n'utent ëd la base ëd dàit",
	'config-install-user-grant-failed' => 'Falì a dé ij përmess a l\'utent "$1": $2',
	'config-install-tables' => 'Creassion dle tàule',
	'config-install-tables-exist' => "'''Avis''': A smija che le tàule ëd mediaWiki a esisto già.
Sauté la creassion.",
	'config-install-tables-failed' => "'''Eror''': Creassion ëd le tàule falìa con l'eror sì-dapress: $1",
	'config-install-interwiki' => "Ampiniment dë stàndard ëd le tàule dj'anliure interwiki",
	'config-install-interwiki-list' => "As peul pa trovesse l'archivi <code>interwiki.list</code>.",
	'config-install-interwiki-exists' => "'''Avis''': La tàula interwiki a smija ch'a l'abia già dj'element.
Për stàndard, la lista a sarà sautà.",
	'config-install-keys' => 'Generassion ëd la ciav segreta',
	'config-install-sysop' => "Creassion dël cont ëd l'utent aministrator",
	'config-install-done' => "'''Congratulassion!'''
A l'ha instalà për da bin mediaWiki.

L'instalador a l'ha generà n'archivi <code>LocalSettings.php</code>.
A conten tuta soa configurassion.

A dovrà [$1 dëscarielo] e butelo ant la bas ëd l'instalassion ëd soa wiki (ël midem dossié d'index.php).
'''Nòta''': S'a lo fa nen adess, cost archivi ëd configurassion generà a sarà pa disponìbil për chiel pi tard s'a chita l'instalassion sensa dëscarielo.

Quand che a l'é stàit fàit, a peul '''[$2 intré an soa wiki]'''.",
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
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
 * @author Platonides
 * @author SandroHc
 * @author Waldir
 */
$messages['pt'] = array(
	'config-desc' => 'O instalador do MediaWiki',
	'config-title' => 'Instalação MediaWiki $1',
	'config-information' => 'Informação',
	'config-localsettings-upgrade' => 'Foi detectado um ficheiro <code>LocalSettings.php</code>.
Para actualizar esta instalação, por favor introduza o valor de <code>$wgUpgradeKey</code> na caixa abaixo.
Encontra este valor no LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Foi detectada a existência de um ficheiro LocalSettings.php.
Para actualizar esta instalação execute o update.php, por favor.',
	'config-localsettings-key' => 'Chave de actualização:',
	'config-localsettings-badkey' => 'A chave que forneceu está incorreta.',
	'config-upgrade-key-missing' => 'Foi detectada uma instalação existente do MediaWiki.
Para actualizar esta instalação, por favor coloque a seguinte linha no final do seu LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'O ficheiro LocalSettings.php existente parece estar incompleto.
A variável $1 não está definida.
Por favor defina esta variável no LocalSettings.php e clique "Continuar".',
	'config-localsettings-connection-error' => 'Ocorreu um erro ao ligar à base de dados usando as configurações especificadas no LocalSettings.php ou AdminSettings.php. Por favor corrija essas configurações e tente novamente. 

$1',
	'config-session-error' => 'Erro ao iniciar a sessão: $1',
	'config-session-expired' => 'Os seus dados de sessão parecem ter expirado.
As sessões estão configuradas para uma duração de $1.
Pode aumentar esta duração configurando <code>session.gc_maxlifetime</code> no php.ini.
Reinicie o processo de instalação.',
	'config-no-session' => 'Os seus dados de sessão foram perdidos!
Verifique o seu php.ini e certifique-se de que em <code>session.save_path</code> está definido um directório apropriado.',
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
	'config-page-existingwiki' => 'Wiki existente',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]
----
* <doclink href=Readme>Leia-me</doclink>
* <doclink href=ReleaseNotes>Notas de lançamento</doclink>
* <doclink href=Copying>Cópia</doclink>
* <doclink href=UpgradeDoc>Atualização</doclink>',
	'config-env-good' => 'O ambiente foi verificado.
Pode instalar o MediaWiki.',
	'config-env-bad' => 'O ambiente foi verificado.
Não pode instalar o MediaWiki.',
	'config-env-php' => 'O PHP $1 está instalado.',
	'config-env-php-toolow' => 'O PHP $1 está instalado. 
No entanto, o MediaWiki requer o PHP $2 ou superior.',
	'config-unicode-using-utf8' => 'A usar o utf8_normalize.so, por Brian Viper, para a normalização Unicode.',
	'config-unicode-using-intl' => 'A usar a [http://pecl.php.net/intl extensão intl PECL] para a normalização Unicode.',
	'config-unicode-pure-php-warning' => "'''Aviso''': A [http://pecl.php.net/intl extensão intl PECL] não está disponível para efectuar a normalização Unicode. Irá recorrer-se à implementação em PHP puro, que é mais lenta.
Se o seu site tem alto volume de tráfego, devia informar-se um pouco sobre a [http://www.mediawiki.org/wiki/Unicode_normalization_considerations/pt normalização Unicode].",
	'config-unicode-update-warning' => "'''Aviso''': A versão instalada do wrapper de normalização Unicode usa uma versão mais antiga da biblioteca do [http://site.icu-project.org/ projecto ICU].
Devia [http://www.mediawiki.org/wiki/Unicode_normalization_considerations actualizá-la] se tem quaisquer preocupações sobre o uso do Unicode.",
	'config-no-db' => "Não foi possível encontrar um controlador ''(driver)'' apropriado para a base de dados!",
	'config-no-db-help' => "Precisa de instalar um controlador ''(driver)'' de base de dados para o PHP.
São suportadas as seguintes bases de dados: $1.

Se o seu site está alojado num servidor partilhado, peça ao fornecedor do alojamento para instalar um controlador de base de dados apropriado.
Se fez a compilação do PHP você mesmo, reconfigure-o com um cliente de base de dados activado, usando, por exemplo, <code>./configure --with-mysql</code>.
Se instalou o PHP a partir de um pacote Debian ou Ubuntu, então precisa de instalar também o módulo php5-mysql.",
	'config-no-fts3' => "'''Aviso''': O SQLite foi compilado sem o módulo [http://sqlite.org/fts3.html FTS3]; as funcionalidades de pesquisa não estarão disponíveis nesta instalação.",
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
	'config-xml-bad' => 'Falta o módulo XML do PHP.
O MediaWiki necessita de funções deste módulo e não funcionará com esta configuração.
Se está a executar o Mandrake, instale o pacote php-xml.',
	'config-pcre' => 'Parece faltar o módulo de suporte PCRE.
Para funcionar, o MediaWiki necessita das funções de expressões regulares compatíveis com Perl.',
	'config-pcre-no-utf8' => "'''Fatal''': O módulo PCRE do PHP parece ter sido compilado sem suporte PCRE_UTF8.
O MediaWiki necessita do suporte UTF-8 para funcionar correctamente.",
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
	'config-diff3-bad' => 'O GNU diff3 não foi encontrado.',
	'config-imagemagick' => 'Foi encontrado o ImageMagick: <code>$1</code>.
Se possibilitar uploads, a miniaturização de imagens será activada.',
	'config-gd' => 'Foi encontrada a biblioteca gráfica GD.
Se possibilitar uploads, a miniaturização de imagens será activada.',
	'config-no-scaling' => 'Não foi encontrada a biblioteca gráfica GD nem o ImageMagick.
A miniaturização de imagens será desactivada.',
	'config-no-uri' => "'''Erro:''' Não foi possível determinar a URI actual.
A instalação foi abortada.",
	'config-uploads-not-safe' => "'''Aviso:''' O directório por omissão para uploads <code>$1</code>, está vulnerável à execução arbitrária de scripts.
Embora o MediaWiki verifique a existência de ameaças de segurança em todos os ficheiros enviados, é altamente recomendado que [http://www.mediawiki.org/wiki/Manual:Security#Upload_security vede esta vulnerabilidade de segurança] antes de possibilitar uploads.",
	'config-brokenlibxml' => 'O seu sistema tem uma combinação de versões de PHP e libxml2 conhecida por ser problemática, podendo causar corrupção de dados no MediaWiki e outras aplicações da internet.
Actualize para o PHP versão 5.2.9 ou posterior e libxml2 versão 2.7.3 ou posterior ([http://bugs.php.net/bug.php?id=45996 incidência reportada no PHP]).
Instalação interrompida.',
	'config-using531' => 'O MediaWiki não pode ser usado com o PHP $1 devido a um problema que envolve parâmetros de referência para <code>__call()</code>.
Para resolver este problema, actualize o PHP para a versão 5.3.2 ou posterior, ou reverta-o para a 5.3.0.
Instalação interrompida.',
	'config-db-type' => 'Tipo da base de dados:',
	'config-db-host' => 'Servidor da base de dados:',
	'config-db-host-help' => 'Se a base de dados estiver num servidor separado, introduza aqui o nome ou o endereço IP desse servidor.

Se estiver a usar um servidor partilhado, o fornecedor do alojamento deve ter-lhe fornecido o nome do servidor na documentação.

Se está a fazer a instalação num servidor Windows com MySQL, usar como nome do servidor "localhost" poderá não funcionar. Se não funcionar, tente usar "127.0.0.1" como endereço IP local.',
	'config-db-host-oracle' => 'TNS (Transparent Network Substrate) da base de dados:',
	'config-db-host-oracle-help' => 'Introduza um [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Nome Local de Ligação] válido; tem de estar visível para esta instalação um ficheiro tnsnames.ora.<br />Se está a usar bibliotecas cliente versão 10g ou posterior, também pode usar o método [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Ligação Fácil] de atribuição do nome.',
	'config-db-wiki-settings' => 'Identifique esta wiki',
	'config-db-name' => 'Nome da base de dados:',
	'config-db-name-help' => 'Escolha um nome para identificar a sua wiki.
O nome não deve conter espaços.

Se estiver a usar um servidor partilhado, o fornecedor do alojamento deve poder fornecer-lhe o nome de uma base de dados que possa usar, ou permite-lhe criar bases de dados através de um painel de controle.',
	'config-db-name-oracle' => "Esquema ''(schema)'' da base de dados:",
	'config-db-account-oracle-warn' => "Há três cenários suportados na instalação do servidor de base de dados Oracle: 

Se pretende criar a conta de acesso pela internet na base de dados durante o processo de instalação, forneça como conta para a instalação uma conta com o papel de SYSDBA na base de dados e especifique as credenciais desejadas para a conta de acesso pela internet. Se não pretende criar a conta de acesso pela internet durante a instalação, pode criá-la manualmente e fornecer só essa conta para a instalação (se ela tiver as permissões necessárias para criar os objectos do esquema ''(schema)''). A terceira alternativa é fornecer duas contas diferentes; uma com privilégios de criação e outra com privilégios limitados para o acesso pela internet.

Existe um script para criação de uma conta com os privilégios necessários no directório \"maintenance/oracle/\" desta instalação. Mantenha em mente que usar uma conta com privilégios limitados impossibilita todas as operações de manutenção com a conta padrão.",
	'config-db-install-account' => 'Conta do utilizador para a instalação',
	'config-db-username' => 'Nome do utilizador da base de dados:',
	'config-db-password' => 'Palavra-chave do utilizador da base de dados:',
	'config-db-password-empty' => 'Introduza a palavra-chave do novo utilizador da base de dados: $1.
Embora seja possível criar utilizadores sem palavra-chave, fazê-lo não é seguro.',
	'config-db-install-username' => 'Introduza o nome de utilizador que será usado para aceder à base de dados durante o processo de instalação. Este utilizador não é o do MediaWiki; é o utilizador da base de dados.',
	'config-db-install-password' => 'Introduza a palavra-chave do utilizador que será usado para aceder à base de dados durante o processo de instalação. Esta palavra-chave não é a do utilizador do MediaWiki; é a palavra-chave do utilizador da base de dados.',
	'config-db-install-help' => 'Introduza o nome de utilizador e a palavra-chave que serão usados para aceder à base de dados durante o processo de instalação.',
	'config-db-account-lock' => 'Usar o mesmo nome de utilizador e palavra-chave durante a operação normal',
	'config-db-wiki-account' => 'Conta de utilizador para a operação normal',
	'config-db-wiki-help' => 'Introduza o nome de utilizador e a palavra-chave que serão usados para aceder à base de dados durante a operação normal da wiki.
Se o utilizador não existir na base de dados, mas a conta de instalação tiver privilégios suficientes, o utilizador que introduzir será criado na base de dados com os privilégios mínimos necessários para a operação normal da wiki.',
	'config-db-prefix' => 'Prefixo para as tabelas da base de dados:',
	'config-db-prefix-help' => 'Se necessitar de partilhar uma só base de dados entre várias wikis, ou entre o MediaWiki e outra aplicação, pode escolher adicionar um prefixo ao nome de todas as tabelas desta instalação, para evitar conflitos.
Não use espaços.

Normalmente, este campo deve ficar vazio.',
	'config-db-charset' => 'Conjunto de caracteres da base de dados',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binary',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 backwards-compatible UTF-8',
	'config-charset-help' => "'''Aviso:''' Se usar '''backwards-compatible UTF-8''' (\"UTF-8 compatível com versões anteriores\") no MySQL 4.1+, e depois fizer cópias de segurança da base de dados usando <code>mysqldump</code>, poderá destruir todos os caracteres que não fazem parte do conjunto ASCII, corrompendo assim, de forma irreversível, as suas cópias de segurança!

No modo '''binary''' (\"binário\"), o MediaWiki armazena o texto UTF-8 na base de dados em campos binários.
Isto é mais eficiente do que o modo UTF-8 do MySQL e permite que sejam usados todos os caracteres Unicode.
No modo '''UTF-8''', o MySQL saberá em que conjunto de caracteres os seus dados estão e pode apresentá-los e convertê-los da forma mais adequada,
mas não lhe permitirá armazenar caracteres acima do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilingue Básico].",
	'config-mysql-old' => 'É necessário o MySQL $1 ou posterior; tem a versão $2.',
	'config-db-port' => 'Porta da base de dados:',
	'config-db-schema' => "Esquema ''(schema)'' do MediaWiki",
	'config-db-schema-help' => 'Normalmente, este esquema ("schema") estará correcto.
Altere-o só se souber que precisa de o fazer.',
	'config-sqlite-dir' => 'Directório de dados do SQLite:',
	'config-sqlite-dir-help' => "O SQLite armazena todos os dados num único ficheiro.

Durante a instalação, o servidor de internet precisa de ter permissão de escrita no directório que especificar.

Este directório '''não''' deve poder ser acedido directamente da internet, por isso está a ser colocado onde estão os seus ficheiros PHP.

Juntamente com o directório, o instalador irá criar um ficheiro <code>.htaccess</code>, mas se esta operação falhar é possível que alguém venha a ter acesso directo à base de dados.
Isto inclui acesso aos dados dos utilizadores (endereços de correio electrónico, palavras-chave encriptadas), às revisões eliminadas e a outros dados de acesso restrito na wiki.

Considere colocar a base de dados num local completamente diferente, como, por exemplo, em <code>/var/lib/mediawiki/asuawiki</code>.",
	'config-oracle-def-ts' => 'Tablespace padrão:',
	'config-oracle-temp-ts' => 'Tablespace temporário:',
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
	'config-support-oracle' => '* $1 é uma base de dados de uma empresa comercial. ([http://www.php.net/manual/en/oci8.installation.php How to compile PHP with OCI8 support])',
	'config-header-mysql' => 'Definições MySQL',
	'config-header-postgres' => 'Definições PostgreSQL',
	'config-header-sqlite' => 'Definições SQLite',
	'config-header-oracle' => 'Definições Oracle',
	'config-invalid-db-type' => 'O tipo de base de dados é inválido',
	'config-missing-db-name' => 'Tem de introduzir um valor para "Nome da base de dados"',
	'config-missing-db-host' => 'Tem de introduzir um valor para "Servidor da base de dados"',
	'config-missing-db-server-oracle' => 'Tem de introduzir um valor para "TNS da base de dados"',
	'config-invalid-db-server-oracle' => 'O TNS da base de dados, "$1", é inválido.
Use só letras (a-z, A-Z), algarismos (0-9), sublinhados (_) e pontos (.) dos caracteres ASCII.',
	'config-invalid-db-name' => 'O nome da base de dados, "$1",  é inválido.
Use só letras (a-z, A-Z), algarismos (0-9), sublinhados (_) e hífens (-) dos caracteres ASCII.',
	'config-invalid-db-prefix' => 'O prefixo da base de dados, "$1",  é inválido.
Use só letras (a-z, A-Z), algarismos (0-9), sublinhados (_) e hífens (-) dos caracteres ASCII.',
	'config-connection-error' => '$1.

Verifique o servidor, o nome do utilizador e a palavra-chave abaixo e tente novamente.',
	'config-invalid-schema' => "O esquema ''(schema)'' do MediaWiki, \"\$1\", é inválido.
Use só letras (a-z, A-Z), algarismos (0-9) e sublinhados (_) dos caracteres ASCII.",
	'config-db-sys-create-oracle' => 'O instalador só permite criar uma conta nova usando uma conta SYSDBA.',
	'config-db-sys-user-exists-oracle' => 'A conta "$1" já existe. A conta SYSDBA só pode criar uma conta nova!',
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
	'config-can-upgrade' => "Esta base de dados contém tabelas do MediaWiki.
Para actualizá-las para o MediaWiki $1, clique '''Continuar'''.",
	'config-upgrade-done' => "Actualização terminada.

Agora pode [$1 começar a usar a sua wiki].

Se quiser regenerar o seu ficheiro <code>LocalSettings.php</code>, clique o botão abaixo.
Esta operação '''não é recomendada''' a menos que esteja a ter problemas com a sua wiki.",
	'config-upgrade-done-no-regenerate' => 'Actualização terminada.

Agora pode [$1 começar a usar a sua wiki].',
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
	'config-mysql-charset' => 'Conjunto de caracteres da base de dados:',
	'config-mysql-binary' => 'Binary',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "No modo '''binary''' (\"binário\"), o MediaWiki armazena o texto UTF-8 na base de dados em campos binários.
Isto é mais eficiente do que o modo UTF-8 do MySQL e permite que sejam usados todos os caracteres Unicode.

No modo '''UTF-8''', o MySQL saberá em que conjunto de caracteres os seus dados estão e pode apresentá-los e convertê-los da forma mais adequada,
mas não lhe permitirá armazenar caracteres acima do [http://en.wikipedia.org/wiki/Mapping_of_Unicode_character_planes Plano Multilingue Básico].",
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
	'config-ns-conflict' => 'O espaço nominal que especificou, "<nowiki>$1</nowiki>", cria um conflito com um dos espaços nominais padrão do MediaWiki.
Especifique um espaço nominal do projecto diferente.',
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
	'config-admin-email-help' => 'Introduza aqui um correio electrónico que lhe permita receber mensagens de outros utilizadores da wiki, reiniciar a sua palavra-chave e receber notificações de alterações às suas páginas vigiadas. Pode deixar o campo vazio.',
	'config-admin-error-user' => 'Ocorreu um erro interno ao criar um administrador com o nome "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Ocorreu um erro interno ao definir uma palavra-chave para o administrador "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-admin-error-bademail' => 'Introduziu um correio electrónico inválido',
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
	'config-license-cc-by-sa' => 'Atribuição - Partilha nos Mesmos Termos, da Creative Commons',
	'config-license-cc-by-nc-sa' => 'Atribuição - Uso Não-Comercial - Partilha nos Mesmos Termos, da Creative Commons',
	'config-license-cc-0' => 'Creative Commons Zero',
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
Para poder usá-los, o MediaWiki necessita de acesso à internet. 

Para mais informações sobre esta funcionalidade, incluindo instruções sobre como configurá-la para usar outras wikis em vez da Wikimedia Commons, consulte o [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos Manual Técnico].',
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
	'config-memcached-help' => 'Lista de endereços IP que serão usados para o Memcached.
Deve-se colocar um por linha e indicar a porta a utilizar. Por exemplo:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Seleccionou o Memcached como tipo de chache, mas não especificou nenhum servidor.',
	'config-memcache-badip' => 'Introduziu um endereço IP inválido para o Memcached: $1.',
	'config-memcache-noport' => 'Não especificou a porta a usar para o servidor Memcached: $1.
Se não sabe qual é a porta, a predefinida é a 11211.',
	'config-memcache-badport' => 'Os números das portas do Memcached devem estar entre $1 e $2.',
	'config-extensions' => 'Extensões',
	'config-extensions-help' => 'Foi detectada a existência das extensões listadas acima, no seu directório <code>./extensions</code>.

Estas talvez necessitem de configurações adicionais, mas pode activá-las agora',
	'config-install-alreadydone' => "'''Aviso:''' Parece que já instalou o MediaWiki e está a tentar instalá-lo novamente.
Passe para a próxima página, por favor.",
	'config-install-begin' => 'Ao clicar "{{int:config-continue}}", vai iniciar a instalação do MediaWiki. 
Se quiser fazer mais alterações, clique Voltar.',
	'config-install-step-done' => 'terminado',
	'config-install-step-failed' => 'falhou',
	'config-install-extensions' => 'A incluir as extensões',
	'config-install-database' => 'A preparar a base de dados',
	'config-install-pg-schema-not-exist' => "O esquema ''(schema)'' PostgreSQL não existe",
	'config-install-pg-schema-failed' => 'A criação das tabelas falhou.
Certifique-se de que o utilizador "$1" pode escrever no esquema \'\'(schema)\'\' "$2".',
	'config-install-pg-commit' => 'A gravar as alterações',
	'config-install-pg-plpgsql' => 'A verificar a presença da linguagem PL/pgSQL',
	'config-pg-no-plpgsql' => 'É preciso instalar a linguagem PL/pgSQL na base de dados $1',
	'config-pg-no-create-privs' => 'A conta que especificou para a instalação não tem privilégios suficientes para criar uma conta.',
	'config-install-user' => 'A criar o utilizador da base de dados',
	'config-install-user-alreadyexists' => 'O utilizador "$1" já existe',
	'config-install-user-create-failed' => 'A criação do utilizador "$1" falhou: $2',
	'config-install-user-grant-failed' => 'A atribuição das permissões ao utilizador "$1" falhou: $2',
	'config-install-tables' => 'A criar as tabelas',
	'config-install-tables-exist' => "'''Aviso''': As tabelas do MediaWiki parecem já existir.
A criação das tabelas será saltada.",
	'config-install-tables-failed' => "'''Erro''': A criação das tabelas falhou com o seguinte erro: $1",
	'config-install-interwiki' => 'A preencher a tabela padrão de interwikis',
	'config-install-interwiki-list' => 'Não foi possível encontrar o ficheiro <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Aviso''': A tabela de interwikis parece já conter entradas.
O preenchimento padrão desta tabela será saltado.",
	'config-install-stats' => 'A inicializar as estatísticas',
	'config-install-keys' => 'A gerar a chave secreta',
	'config-install-sysop' => 'A criar a conta de administrador',
	'config-install-subscribe-fail' => 'Não foi possível subscrever a lista mediawiki-announce',
	'config-install-mainpage' => 'A criar a página principal com o conteúdo padrão.',
	'config-install-extension-tables' => 'A criar as tabelas das extensões activadas',
	'config-install-mainpage-failed' => 'Não foi possível inserir a página principal: $1',
	'config-install-done' => "'''Parabéns!'''
Terminou a instalação do MediaWiki.

O instalador gerou um ficheiro <code>LocalSettings.php</code>.
Este ficheiro contém todas as configurações.

Precisa de fazer o download do ficheiro e colocá-lo no directório de raiz da sua instalação (o mesmo directório onde está o ficheiro index.php). Este download deverá ter sido iniciado automaticamente.

Se o download não foi iniciado, ou se o cancelou, pode recomeçá-lo clicando o link abaixo:

$3

'''Nota''': Se não fizer isto agora, o ficheiro que foi gerado deixará de estar disponível quando sair do processo de instalação.

Depois de terminar o passo anterior, pode '''[$2 entrar na wiki]'''.",
	'config-download-localsettings' => 'Download do LocalSettings.php',
	'config-help' => 'ajuda',
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Giro720
 * @author Gustavo
 * @author Marcionunes
 */
$messages['pt-br'] = array(
	'config-desc' => 'O instalador do MediaWiki',
	'config-title' => 'Instalação MediaWiki $1',
	'config-information' => 'Informações',
	'config-localsettings-upgrade' => "'''Aviso''': Foi detetada a existência de um arquivo <code>LocalSettings.php</code>.
É possível atualizar o seu software.
Mova o <code>LocalSettings.php</code> para um lugar seguro e execute o instalador novamente, por favor.",
	'config-localsettings-cli-upgrade' => 'Foi detectado um arquivo LocalSettings.php.
Para atualizar esta instalação, por favor, use: --upgrade=yes.',
	'config-localsettings-key' => 'Chave de atualização:',
	'config-localsettings-badkey' => 'A senha inserida está incorreta.',
	'config-upgrade-key-missing' => 'Foi detectada uma instalação existente do MediaWiki.
Para atualizar esta instalação, por favor, coloque a seguinte linha na parte inferior do seu LocalSettings.php:

$ 1',
	'config-session-error' => 'Erro ao iniciar a sessão: $1',
	'config-session-expired' => 'Os seus dados de sessão parecem ter expirado.
As sessões estão configuradas para uma duração de $1.
Você pode aumentar esta duração configurando <code>session.gc_maxlifetime</code> no php.ini.
Reinicie o processo de instalação.',
	'config-no-session' => 'Os seus dados de sessão foram perdidos!
Verifique o seu php.ini e certifique-se de que em <code>session.save_path</code> está definido um diretório apropriado.',
	'config-your-language' => 'A sua língua:',
	'config-your-language-help' => 'Selecione a língua que será usada durante o processo de instalação.',
	'config-wiki-language' => 'Língua da wiki:',
	'config-wiki-language-help' => 'Selecione a língua que será predominante na wiki.',
	'config-back' => '← Voltar',
	'config-continue' => 'Continuar →',
	'config-page-language' => 'Língua',
	'config-page-welcome' => 'Bem-vindo(a) ao MediaWiki!',
	'config-page-dbconnect' => 'Ligar à base de dados',
	'config-page-upgrade' => 'Atualizar a instalação existente',
	'config-page-dbsettings' => 'Configurações da base de dados',
	'config-page-name' => 'Nome',
	'config-page-options' => 'Opções',
	'config-page-install' => 'Instalar',
	'config-page-complete' => 'Terminado!',
	'config-page-restart' => 'Reiniciar a instalação',
	'config-page-readme' => 'Leia-me',
	'config-page-releasenotes' => 'Notas de lançamento',
	'config-page-copying' => 'Copiando',
	'config-page-upgradedoc' => 'Atualizando',
	'config-help-restart' => 'Deseja limpar todos os dados salvos que você introduziu e reiniciar o processo de instalação?',
	'config-restart' => 'Sim, reiniciar',
	'config-welcome' => '=== Verificações do ambiente ===
São realizadas verificações básicas para determinar se este ambiente é apropriado para instalação do MediaWiki.
Você deverá fornecer os resultados destas verificações se você precisar de ajuda durante a instalação.',
	'config-copyright' => "=== Direitos autorais e Termos de uso ===

$1

Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo nos termos da licença GNU General Public License, tal como publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (por opção sua) qualquer versão posterior.

Este programa é distribuído na esperança de que seja útil, mas '''sem qualquer garantia'''; inclusive, sem a garantia implícita da '''possibilidade de ser comercializado''' ou de '''adequação para qualquer finalidade específica'''.
Consulte a licença GNU General Public License para mais detalhes.

Em conjunto com este programa você deve ter recebido <doclink href=Copying>uma cópia da licença GNU General Public License</doclink>; se não a recebeu, peça-a por escrito para Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ou [http://www.gnu.org/copyleft/gpl.html leia-a na internet].",
	'config-sidebar' => '* [http://www.mediawiki.org/wiki/MediaWiki/pt Página principal do MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents/pt Ajuda]
* [http://www.mediawiki.org/wiki/Manual:Contents/pt Manual técnico]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ]',
	'config-env-good' => 'O ambiente foi verificado.
Você pode instalar o MediaWiki.',
	'config-env-bad' => 'O ambiente foi verificado.
Você não pode instalar o MediaWiki.',
	'config-env-php' => 'O PHP $1 está instalado.',
	'config-unicode-using-utf8' => 'A usar o utf8_normalize.so, de Brian Viper, para a normalização Unicode.',
	'config-unicode-using-intl' => 'Usando a [http://pecl.php.net/intl extensão intl PECL] para a normalização Unicode.',
	'config-unicode-pure-php-warning' => "'''Aviso''': A [http://pecl.php.net/intl extensão intl PECL] não está disponível para efetuar a normalização Unicode.
Se o seu site tem um alto volume de tráfego, devia informar-se um pouco sobre a [http://www.mediawiki.org/wiki/Unicode_normalization_considerations normalização Unicode].",
	'config-no-db' => 'Não foi possível encontrar um driver de banco de dados adequado!',
	'config-no-db-help' => 'Você precisa instalar um driver de banco de dados para PHP.
Os seguintes tipos de banco de dados são suportados: $1.

Se você estiver em hospedagem compartilhada, pergunte ao seu provedor de hospedagem para instalar um driver de banco de dados apropriado.
Se você compilou o PHP você mesmo, reconfigurá-lo com um cliente de banco de dados habilitado, por exemplo, usando <code>./configure --with-mysql</code>.
Se você instalou o PHP de um Debian ou Ubuntu package, então você também precisa instalar o módulo php5-mysql.',
	'config-no-fts3' => "' ' 'Aviso' ' ': O SQLite foi compilado sem o módulo [http://sqlite.org/fts3.html FTS3]; as funcionalidades de pesquisa não estarão disponíveis nesta instalação.",
	'config-register-globals' => "' ' 'Aviso: A opção <code>[http://php.net/register_globals register_globals]</code> do PHP está ativada.'''
' ' 'Desative-a, se puder.'''
O MediaWiki funcionará mesmo assim, mas o seu servidor ficará exposto a potenciais vulnerabilidades de segurança.",
	'config-logo-help' => 'O tema padrão do MediaWiki inclui espaço para um logotipo de 135x160 pixels no canto superior esquerdo.
Faça o upload de uma imagem com estas dimensões e introduza aqui a URL dessa imagem.

Se você não pretende usar um logotipo, deixe este campo em branco.',
);

/** Romanian (Română)
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'config-session-error' => 'Eroare la pornirea sesiunii: $1',
	'config-your-language' => 'Limba ta:',
	'config-your-language-help' => 'Alege o limbă pentru a o utiliza în timpul procesului de instalare.',
	'config-wiki-language' => 'Limbă wiki:',
	'config-wiki-language-help' => 'Alege limba în care wiki-ul va fi scris predominant.',
	'config-back' => '← Înapoi',
	'config-continue' => 'Continuă →',
	'config-page-language' => 'Limbă',
	'config-page-welcome' => 'Bun venit la MediaWiki!',
	'config-page-dbconnect' => 'Conectează la baza de date',
	'config-page-upgrade' => 'Extinde instalarea existentă',
	'config-page-dbsettings' => 'Setări ale bazei de date',
	'config-page-name' => 'Nume',
	'config-page-options' => 'Opţiuni',
	'config-page-install' => 'Instalare',
	'config-page-restart' => 'Reporneşte instalarea',
	'config-page-readme' => 'Citeşte-mă',
	'config-page-releasenotes' => 'Note de lansare',
	'config-db-type' => 'Tipul bazei de date:',
	'config-db-host' => 'Gazdă bază de date:',
	'config-header-mysql' => 'Setările MySQL',
	'config-header-sqlite' => 'Setări SQLite',
	'config-header-oracle' => 'Setări Oracle',
	'config-missing-db-name' => 'Trebuie să introduci o valoare pentru „Numele bazei de date”',
	'config-ns-generic' => 'Proiect',
	'config-admin-password' => 'Parolă:',
);

/** Russian (Русский)
 * @author DCamer
 * @author Eleferen
 * @author Krinkle
 * @author MaxSem
 * @author Yuriy Apostol
 * @author Александр Сигачёв
 * @author Сrower
 */
$messages['ru'] = array(
	'config-desc' => 'Инсталлятор MediaWiki',
	'config-title' => 'Установка MediaWiki $1',
	'config-information' => 'Информация',
	'config-localsettings-upgrade' => 'Обнаружен файл <code>LocalSettings.php</code>. 
Для обновления этой установки, пожалуйста, введите значение <code>$wgUpgradeKey</code>.
Его можно найти в файле LocalSettings.php.',
	'config-localsettings-cli-upgrade' => 'Обнаружен файл LocalSettings.php.
Для обновления этой установки, пожалуйста, запустите update.php',
	'config-localsettings-key' => 'Ключ обновления:',
	'config-localsettings-badkey' => 'Вы указали неправильный ключ',
	'config-upgrade-key-missing' => 'Обнаружена существующая установленная копия MediaWiki.
Чтобы обновить обнаруженную установку, пожалуйста, добавьте следующую строку в конец вашего файла LocalSettings.php:

$1',
	'config-localsettings-incomplete' => 'Похоже, что существующий файл LocalSettings.php не является полными.
Не установлена переменная $1.
Пожалуйста, измените LocalSettings.php так, чтобы значение этой переменной было задано, затем нажмите «Продолжить».',
	'config-localsettings-connection-error' => 'Произошла ошибка при подключении к базе данных с помощью настроек, указанных в LocalSettings.php или AdminSettings.php. Пожалуйста, исправьте эти настройки и повторите попытку.

$1',
	'config-session-error' => 'Ошибка при запуске сессии: $1',
	'config-session-expired' => 'Ваша сессия истекла. 
Сессии настроены на длительность $1. 
Вы её можете увеличить, изменив <code>session.gc_maxlifetime</code> в php.ini. 
Перезапустите процесс установки.',
	'config-no-session' => 'Данные сессии потеряны! 
Проверьте ваш php.ini и убедитесь, что <code>session.save_path</code> установлен в соответствующий каталог.',
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
	'config-page-copying' => 'Лицензия',
	'config-page-upgradedoc' => 'Обновление',
	'config-page-existingwiki' => 'Существующая вики',
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
* [http://www.mediawiki.org/wiki/Manual:FAQ/ru FAQ]
----
* <doclink href=Readme>Readme-файл</doclink>
* <doclink href=ReleaseNotes>Информация о выпуске</doclink>
* <doclink href=Copying>Лицензия</doclink>
* <doclink href=UpgradeDoc>Обновление</doclink>',
	'config-env-good' => 'Проверка внешней среды была успешно проведена.
Вы можете установить MediaWiki.',
	'config-env-bad' => 'Была проведена проверка внешней среды. 
Вы не можете установить MediaWiki.',
	'config-env-php' => 'Установленная версия PHP: $1.',
	'config-env-php-toolow' => 'Найден PHP $1, тогда как MediaWiki требуется PHP версии $2 или выше.',
	'config-unicode-using-utf8' => 'Использовать Brion Vibber utf8_normalize.so для нормализации Юникода.',
	'config-unicode-using-intl' => 'Будет использовано [http://pecl.php.net/intl расширение «intl» для PECL] для нормализации Юникода.',
	'config-unicode-pure-php-warning' => "'''Внимание!''': [http://pecl.php.net/intl международное расширение PECL] недоступно для нормализации Юникода, будет использоваться медленная реализация на чистом PHP.
Если ваш сайт работает под высокой нагрузкой, вам следует больше узнать о [http://www.mediawiki.org/wiki/Unicode_normalization_considerations нормализации Юникода].",
	'config-unicode-update-warning' => "'''Предупреждение''': установленная версия обёртки нормализации Юникода использует старую версию библиотеки [http://site.icu-project.org/ проекта ICU].
Вы должны [http://www.mediawiki.org/wiki/Unicode_normalization_considerations обновить версию], если хотите полноценно использовать Юникод.",
	'config-no-db' => 'Не найдено поддержки баз данных!',
	'config-no-db-help' => 'Вам необходимо установить драйвера базы данных для PHP. 
Поддерживаются следующие типы баз данных: $1. 

Если вы используете виртуальный хостинг, обратитесь к своему хостинг-провайдеру с просьбой установить подходящий драйвер базы данных. 
Если вы скомпилировали PHP сами, сконфигурируйте его снова с включенным клиентом базы данных, например, с помощью <code>./configure --with-mysql</code>. 
Если вы установили PHP из пакетов Debian или Ubuntu, то вам также необходимо установить модуль php5-mysql.',
	'config-no-fts3' => "'''Внимание''': SQLite собран без модуля [http://sqlite.org/fts3.html FTS3] — поиск не будет работать для этой базы данных.",
	'config-register-globals' => "'''Внимание: PHP-опция <code>[http://php.net/register_globals register_globals]</code> включена.'''
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
	'config-xml-bad' => 'XML-модуль РНР отсутствует.
MediaWiki не будет работать в этой конфигурации, так как требуется функционал этого модуля.
Если вы работаете в Mandrake, установите PHP XML-пакет.',
	'config-pcre' => 'Модуль поддержки PCRE не найден. 
Для работы MediaWiki требуется поддержка Perl-совместимых регулярных выражений.',
	'config-pcre-no-utf8' => "'''Фатальная ошибка'''. Модуль PCRE для PHP, похоже, собран без поддержки PCRE_UTF8. 
MediaWiki требует поддержки UTF-8 для корректной работы.",
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
	'config-diff3-bad' => 'GNU diff3 не найден.',
	'config-imagemagick' => 'Обнаружен ImageMagick: <code>$1</code>.
Возможно отображение миниатюр изображений, если вы разрешите закачки файлов.',
	'config-gd' => 'Найдена встроенная графическая библиотека GD. 
Возможность использования миниатюр изображений будет включена, если вы включите их загрузку.',
	'config-no-scaling' => 'Не удалось найти встроенную библиотеку GD или ImageMagick.
Возможность использования миниатюр изображений будет отключена.',
	'config-no-uri' => "'''Ошибка:''' Не могу определить текущий URI. 
Установка прервана.",
	'config-uploads-not-safe' => "'''Внимание:''' директория, используемая по умолчанию для загрузок (<code>$1</code>) уязвима к выполнению произвольных скриптов. 
Хотя MediaWiki проверяет все загружаемые файлы на наличие угроз, настоятельно рекомендуется [http://www.mediawiki.org/wiki/Manual:Security#Upload_security закрыть данную уязвимость] перед включением загрузки файлов.",
	'config-brokenlibxml' => 'В вашей системе имеется сочетание версий PHP и libxml2, могущее привести к скрытым повреждениям данных в MediaWiki и других веб-приложениях. 
Обновите PHP до версии 5.2.9 или старше и libxml2 до 2.7.3 или старше ([http://bugs.php.net/bug.php?id=45996 сведения об ошибке]). 
Установка прервана.',
	'config-using531' => 'PHP $1 не совместим с MediaWiki из-за ошибки с параметрами-ссылками при вызовах <code>__call()</code>.
Обновитесь до PHP 5.3.2 и выше, или откатитесь до PHP 5.3.0, чтобы избежать этой проблемы.
Установка прервана.',
	'config-db-type' => 'Тип базы данных:',
	'config-db-host' => 'Хост базы данных:',
	'config-db-host-help' => 'Если сервер базы данных находится на другом сервере, введите здесь его имя хоста или IP-адрес. 

Если вы используете виртуальный хостинг, ваш провайдер должен указать правильное имя хоста в своей документации.

Если вы устанавливаете систему на сервере под Windows и используете MySQL, имя сервера «localhost» может не работать. В этом случае попробуйте указать «127.0.0.1».',
	'config-db-host-oracle' => 'TNS базы данных:',
	'config-db-host-oracle-help' => 'Введите действительный [http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm Local Connect Name]; файл tnsnames.ora должен быть видимым для этой инсталляции. <br />При использовании клиентских библиотек версии 10g и старше также возможно использовать метод именования [http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm Easy Connect].',
	'config-db-wiki-settings' => 'Идентификация этой вики',
	'config-db-name' => 'Имя базы данных:',
	'config-db-name-help' => 'Выберите название-идентификатор для вашей вики. 
Оно не должно содержать пробелов. 

Если вы используете виртуальный хостинг, провайдер или выдаст вам конкретное имя базы данных, или позволит создавать базы данных с помощью панели управления.',
	'config-db-name-oracle' => 'Схема базы данных:',
	'config-db-account-oracle-warn' => 'Поддерживаются три сценария установки Oracle в качестве базы данных:

Если вы хотите создать учётную запись базы данных в процессе установки, пожалуйста, укажите учётную запись роли SYSDBA для установки и укажите желаемые полномочия учётной записи с веб-доступом. вы также можете учётную запись с веб-доступом вручную и указать только её (если у неё есть необходимые разрешения на создание объектов схемы) или указать две учётные записи, одну с правами создания объектов, а другую с ограничениями для веб-доступа.

Сценарий для создания учётной записи с необходимыми привилегиями можно найти в папке «maintenance/oracle/» этой программы установки. Имейте в виду, что использование ограниченной учётной записи приведёт к отключению всех возможностей обслуживания с учётной записи по умолчанию.',
	'config-db-install-account' => 'Учётная запись для установки',
	'config-db-username' => 'Имя пользователя базы данных:',
	'config-db-password' => 'Пароль базы данных:',
	'config-db-password-empty' => 'Пожалуйста, введите пароль для нового пользователя базы данных «$1». 
Хотя и возможно создание пользователей без паролей, это небезопасно.',
	'config-db-install-username' => 'Введите имя пользователя, которое будет использоваться для подключения к базе данных в процессе установки. 
Это не имя пользователя MediaWiki, это имя пользователя для базы данных.',
	'config-db-install-password' => 'Введите пароль, который будет использоваться для подключения к базе данных в процессе установки. 
Это не пароль пользователя MediaWiki, это пароль для базы данных.',
	'config-db-install-help' => 'Введите имя пользователя и пароль, которые будут использоваться для подключения к базе данных во время процесса установки.',
	'config-db-account-lock' => 'Использовать то же имя пользователя и пароль для обычной работы',
	'config-db-wiki-account' => 'Учётная запись для обычной работы',
	'config-db-wiki-help' => 'Введите имя пользователя и пароль, которые будут использоваться для подключения к базе данных во время обычной работы вики. 
Если такой учётной записи не существует, а установочная учётная запись имеет достаточно привилегий, то обычная учётная запись будет создана с минимально необходимыми для работы вики привилегиями.',
	'config-db-prefix' => 'Префикс таблиц базы данных:',
	'config-db-prefix-help' => 'Если вам нужно делить одну базу данных между несколькими вики, или между MediaWiki и другими веб-приложениями, вы можете добавить префикс для всех имён таблиц. 
Не используйте пробелы. 

Это поле обычно остаётся пустым.',
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
	'config-db-schema-help' => 'Эта схема обычно работают хорошо.
Изменяйте её только если знаете, что вам это нужно.',
	'config-sqlite-dir' => 'Директория данных SQLite:',
	'config-sqlite-dir-help' => "SQLite хранит все данные в одном файле. 

Директория, которую вы должны указать, должна быть доступна для записи веб-сервером во время установки. 

Она '''не должна''' быть доступна через Интернет, поэтому не должна совпадать с той, где хранятся PHP файлы.

Установщик запишет в эту директорию файл <code>.htaccess</code>, но если это не сработает, кто-нибудь может получить доступ ко всей базе данных.
В этой базе находится в том числе и информация о пользователях (адреса электронной почты, хэши паролей), а также удалённые страницы и другие секретные данные о вики. 

По возможности, расположите базу данных где-нибудь в стороне, например, в <code>/var/lib/mediawiki/yourwiki</code>.",
	'config-oracle-def-ts' => 'Пространство таблиц по умолчанию:',
	'config-oracle-temp-ts' => 'Временное пространство таблиц:',
	'config-type-mysql' => 'MySQL',
	'config-type-postgres' => 'PostgreSQL',
	'config-type-sqlite' => 'SQLite',
	'config-type-oracle' => 'Oracle',
	'config-support-info' => 'MediaWiki поддерживает следующие СУБД: 

$1 

Если вы не видите своей системы хранения данных в этом списке, следуйте инструкциям, на которые есть ссылка выше, чтобы получить поддержку.',
	'config-support-mysql' => '* $1 — основная база данных для MediaWiki, и лучше поддерживается ([http://www.php.net/manual/en/mysql.installation.php инструкция, как собрать PHP с поддержкой MySQL])',
	'config-support-postgres' => '* $1 — популярная открытая СУБД, альтернатива MySQL ([http://www.php.net/manual/en/pgsql.installation.php инструкция, как собрать PHP с поддержкой PostgreSQL]). Могут встречаться небольшие неисправленные ошибки, не рекомендуется для использования в рабочей системе.',
	'config-support-sqlite' => '* $1 — это легковесная система баз данных, имеющая очень хорошую поддержку. ([http://www.php.net/manual/en/pdo.installation.php инструкция, как собрать PHP с поддержкой SQLite], работающей посредством PDO)',
	'config-support-oracle' => '* $1 — это коммерческая база данных масштаба предприятия. ([http://www.php.net/manual/en/oci8.installation.php Как собрать PHP с поддержкой OCI8])',
	'config-header-mysql' => 'Настройки MySQL',
	'config-header-postgres' => 'Настройки PostgreSQL',
	'config-header-sqlite' => 'Настройки SQLite',
	'config-header-oracle' => 'Настройки Oracle',
	'config-invalid-db-type' => 'Неверный тип базы данных',
	'config-missing-db-name' => 'Вы должны ввести значение параметра «Имя базы данных»',
	'config-missing-db-host' => 'Необходимо ввести значение параметра «Сервер базы данных»',
	'config-missing-db-server-oracle' => 'Вы должны заполнить поле «TNS базы данных»',
	'config-invalid-db-server-oracle' => 'Неверное имя TNS базы данных «$1».
Используйте только символы ASCII (a-z, A-Z), цифры (0-9), знаки подчёркивания (_) и точки (.).',
	'config-invalid-db-name' => 'Неверное имя базы данных «$1».
Используйте только ASCII-символы (a-z, A-Z), цифры (0-9), знак подчёркивания (_) и дефис(-).',
	'config-invalid-db-prefix' => 'Неверный префикс базы данных «$1».
Используйте только буквы ASCII (a-z, A-Z), цифры (0-9), знак подчёркивания (_) и дефис (-).',
	'config-connection-error' => '$1.

Проверьте хост, имя пользователя и пароль и попробуйте ещё раз.',
	'config-invalid-schema' => 'Неправильная схема для MediaWiki «$1». 
Используйте только ASCII символы (a-z, A-Z), цифры(0-9) и знаки подчёркивания(_).',
	'config-db-sys-create-oracle' => 'Программа установки поддерживает только использование SYSDBA для создания новой учётной записи.',
	'config-db-sys-user-exists-oracle' => 'Учётная запись «$1». SYSDBA может использоваться только для создания новой учётной записи!',
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
	'config-sqlite-mkdir-error' => 'Ошибка при создании директории для данных «$1».
Проверьте расположение и повторите попытку.',
	'config-sqlite-dir-unwritable' => 'Невозможно произвести запись в каталог «$1».
Измените настройки доступа так, чтобы веб-сервер мог записывать в этот каталог, и попробуйте ещё раз.',
	'config-sqlite-connection-error' => '$1. 

Проверьте название базы данных и директорию с данными и попробуйте ещё раз.',
	'config-sqlite-readonly' => 'Файл <code>$1</code> недоступен для записи.',
	'config-sqlite-cant-create-db' => 'Не удаётся создать файл базы данных <code>$1</code> .',
	'config-sqlite-fts3-downgrade' => 'У PHP отсутствует поддержка FTS3 — сбрасываем таблицы',
	'config-can-upgrade' => "В базе данных найдены таблицы MediaWiki. 
Чтобы обновить их до MediaWiki $1, нажмите на кнопку '''«Продолжить»'''.",
	'config-upgrade-done' => "Обновление завершено. 

Теперь вы можете [$1 начать использовать вики]. 

Если вы хотите повторно создать файл <code>LocalSettings.php</code>, нажмите на кнопку ниже. 
Это действие '''не рекомендуется''', если у вас не возникло проблем при установке.",
	'config-upgrade-done-no-regenerate' => 'Обновление завершено.

Теперь вы можете [$1 начать работу с вики].',
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
	'config-ns-other' => 'Другое (укажите)',
	'config-ns-other-default' => 'MyWiki',
	'config-project-namespace-help' => "Следуя примеру Википедии, многие вики хранят свои страницы правил отдельно от страниц основного содержания, в так называемом '''«пространстве имён проекта»'''. 
Все названия страниц в этом пространстве имён начинается с определённого префикса, который вы можете задать здесь. 
Обычно, этот префикс происходит от имени вики, но он не может содержать знаки препинания, символы «#» или «:».",
	'config-ns-invalid' => 'Указанное пространство имён <nowiki>$1</nowiki> недопустимо. 
Укажите другое пространство имён проекта.',
	'config-ns-conflict' => 'Указанное пространство имён «<nowiki>$1</nowiki>» конфликтует со стандартным пространством имён MediaWiki.
Укажите другое пространство имён проекта.',
	'config-admin-box' => 'Учётная запись администратора',
	'config-admin-name' => 'Имя:',
	'config-admin-password' => 'Пароль:',
	'config-admin-password-confirm' => 'Пароль ещё раз:',
	'config-admin-help' => 'Введите ваше имя пользователя здесь, например, «Иван Иванов».
Это имя будет использоваться для входа в вики.',
	'config-admin-name-blank' => 'Введите имя пользователя администратора.',
	'config-admin-name-invalid' => 'Указанное имя пользователя «<nowiki>$1</nowiki>» недопустимо. 
Укажите другое имя пользователя.',
	'config-admin-password-blank' => 'Введите пароль для учётной записи администратора.',
	'config-admin-password-same' => 'Пароль не должен быть таким же, как имя пользователя.',
	'config-admin-password-mismatch' => 'Введённые вами пароли не совпадают.',
	'config-admin-email' => 'Адрес электронной почты:',
	'config-admin-email-help' => 'Введите адрес электронной почты, чтобы получать сообщения от других пользователей вики, иметь возможность восстановить пароль, а также получать уведомления об изменениях страниц из списка наблюдения. Вы можете оставить это поле пустым.',
	'config-admin-error-user' => 'Внутренняя ошибка при создании учётной записи администратора с именем «<nowiki>$1</nowiki>».',
	'config-admin-error-password' => 'Внутренняя ошибка при установке пароля для учётной записи администратора «<nowiki>$1</nowiki>»: <pre>$2</pre>',
	'config-admin-error-bademail' => 'Вы ввели неправильный адрес электронной почты',
	'config-subscribe' => 'Подписаться на [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce рассылку новостей о появлении новых версий MediaWiki].',
	'config-subscribe-help' => 'Это список рассылки с малым числом сообщений, используется для анонса новых выпусков и сообщений о проблемах с безопасностью.
Вам следует подписаться на него и обновлять движок MediaWiki, по мере выхода новых версий.',
	'config-almost-done' => 'Вы почти у цели! 
Остальные настройки можно пропустить и приступить к установке вики.',
	'config-optional-continue' => 'Произвести тонкую настройку',
	'config-optional-skip' => 'Хватит, установить вики',
	'config-profile' => 'Профиль прав прользователей:',
	'config-profile-wiki' => 'Традиционная вики',
	'config-profile-no-anon' => 'Требуется создание учётной записи',
	'config-profile-fishbowl' => 'Только для авторизованных редакторов',
	'config-profile-private' => 'Закрытая вики',
	'config-profile-help' => "Вики-технология лучше всего работает, когда вы позволяете редактировать сайт максимально широкому кругу лиц.
В MediaWiki легко просмотреть последних изменений и, при необходимости, откатить любой ущерб сделанный злоумышленниками или наивными пользователями.

Однако, движок MediaWiki можно использовать и иными способами, и не далеко не всех удаётся убедить в преимуществах открытой вики-работы.
Так что в вас есть выбор.

Конфигурация '''«{{int:config-profile-wiki}}»''' позволяет всем править страницы даже не регистрируясь на сайте. Конфигурация '''{{int:config-profile-no-anon}}''' обеспечивает дополнительный учёт, но может отсечь случайных участников. 

Сценарий '''«{{int:config-profile-fishbowl}}»''' разрешает редактирование только определённым участникам, но общедоступным остаётся просмотр страниц, в том числе просмотр истории изменения. В режиме '''«{{int:config-profile-private}}»''' просмотр страниц разрешён только определённым пользователям, какая-то их часть может иметь также права на редактирование.

Более сложные схемы разграничения прав можно настроить после установки, см. [http://www.mediawiki.org/wiki/Manual:User_rights соответствующее руководство].",
	'config-license' => 'Авторские права и лицензии:',
	'config-license-none' => 'Не указывать лицензию в колонтитуле внизу страницы',
	'config-license-cc-by-sa' => 'Creative Commons атрибуция — с сохранением условий',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-cc-0' => 'Creative Commons Zero',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-license-gfdl-current' => 'GNU Free Documentation License 1.3 или более поздней версии',
	'config-license-pd' => 'Общественное достояние',
	'config-license-cc-choose' => 'Выберите одну из лицензий Creative Commons',
	'config-license-help' => "Многие общедоступные вики разрешают использовать свои материалы на условиях [http://freedomdefined.org/Definition/Ru свободных лицензий].
Это помогает созданию чувства общности, стимулирует долгосрочное участие.
Но в этом нет необходимости для частных или корпоративных вики. 

Если вы хотите использовать тексты из Википедии или хотите, что в Википедию можно было копировать тексты из вашей вики, вам следует выбрать '''Creative Commons Attribution Share Alike'''. 

GNU Free Documentation License раньше была основной лицензией Википедии.
Она все ещё используется, однако, она имеет некоторые особенности, осложняющие повторное использование и интерпретацию её материалов.",
	'config-email-settings' => 'Настройки электронной почты',
	'config-enable-email' => 'Включить исходящие e-mail',
	'config-enable-email-help' => 'Если вы хотите, чтобы электронная почта работала, необходимо выполнить [http://www.php.net/manual/en/mail.configuration.php соответствующие настройки PHP].
Если вы не хотите использовать возможности электронной почты в вики, вы можете её отключить.',
	'config-email-user' => 'Включить электронную почту от участника к участнику',
	'config-email-user-help' => 'Разрешить всем пользователям отправлять друг другу электронные письма, если выставлена соответствующая настройка в профиле.',
	'config-email-usertalk' => 'Включить уведомления пользователей о сообщениях на их странице обсуждения',
	'config-email-usertalk-help' => 'Разрешить пользователям получать уведомления об изменениях своих страниц обсуждения, если они разрешат это в своих настройках.',
	'config-email-watchlist' => 'Включить уведомление на электронную почту об изменении списка наблюдения',
	'config-email-watchlist-help' => 'Разрешить пользователям получать уведомления об отслеживаемых ими страницах, если они разрешили это в своих настройках.',
	'config-email-auth' => 'Включить аутентификацию через электронную почту',
	'config-email-auth-help' => "Если эта опция включена, пользователи должны подтвердить свой адрес электронной почты перейдя по ссылке, которая отправляется на e-mail. Подтверждение требуется каждый раз при смене электронного ящика в настройках пользователя.
Только прошедшие проверку подлинности адреса электронной почты, могут получать электронные письма от других пользователей или изменять уведомления, отправляемые по электронной почте.
Включение этой опции '''рекомендуется'''  для открытых вики в целях пресечения потенциальных злоупотреблений возможностями электронной почты.",
	'config-email-sender' => 'Обратный адрес электронной почты:',
	'config-email-sender-help' => 'Введите адрес электронной почты для использования в качестве обратного адреса исходящей электронной почты. 
На него будут отправляться отказы.
Многие почтовые серверы требуют, чтобы по крайней мере доменное имя в нём было правильным.',
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
	'config-logo-help' => 'Тема по умолчанию для MediaWiki включает пространство для логотипа размером 135x160 в левом верхнем углу. 
Загрузите изображение соответствующего размера, и введите его URL здесь. 

Если вам не нужен логотип, оставьте это поле пустым.',
	'config-instantcommons' => 'Включить Instant Commons',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons Instant Commons] — это функция, позволяющая использовать изображения, звуки и другие медиафайлы с Викисклада ([http://commons.wikimedia.org/ Wikimedia Commons]). 
Для работы этой функции MediaWiki необходим доступ к Интернету.  

Дополнительную информацию об Instant Commons, в том числе указания о том, как её настроить для других вики, отличных от Викисклада, можно найти в [http://mediawiki.org/wiki/Manual:$wgForeignFileRepos руководстве].',
	'config-cc-error' => 'Механизм выбора лицензии Creative Commons не вернул результата.
Введите название лицензии вручную.',
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
Перечислите по одному адресу на строку с указанием портов. Например:
 127.0.0.1:11211
 192.168.1.25:1234',
	'config-memcache-needservers' => 'Вы выбрали тип кэширования Memcached, но не задали адреса серверов.',
	'config-memcache-badip' => 'Вы ввели неверный IP-адрес для Memcached: $1.',
	'config-memcache-noport' => 'Не указан порт для сервера Memcached: $1.
Если вы не знаете порт, по умолчанию используется 11211.',
	'config-memcache-badport' => 'Номера портов Memcached должны лежать в пределах от $1 до $2.',
	'config-extensions' => 'Расширения',
	'config-extensions-help' => 'Расширения MediaWiki, перечисленные выше, были найдены в каталоге <code>./extensions</code>. 

Они могут потребовать дополнительные настройки, но их можно включить прямо сейчас',
	'config-install-alreadydone' => "'''Предупреждение:''' Вы, кажется, уже устанавливали MediaWiki и пытаетесь произвести повторную установку.
Пожалуйста, перейдите на следующую страницу.",
	'config-install-begin' => 'Нажав «{{int:config-continue}}», вы начнёте установку MediaWiki.
Если вы хотите внести изменения, нажмите «Назад».',
	'config-install-step-done' => 'выполнено',
	'config-install-step-failed' => 'не удалось',
	'config-install-extensions' => 'В том числе расширения',
	'config-install-database' => 'Настройка базы данных',
	'config-install-pg-schema-not-exist' => 'Схемы PostgreSQL не существует',
	'config-install-pg-schema-failed' => 'Не удалось создать таблицы.
Убедитесь в том, что пользователь «$1» может писать в схему «$2».',
	'config-install-pg-commit' => 'Внесение изменений',
	'config-install-pg-plpgsql' => 'Проверка языка PL/pgSQL',
	'config-pg-no-plpgsql' => 'Вам необходимо установить поддержку языка PL/pgSQL для базы данных $1',
	'config-pg-no-create-privs' => 'Учётная запись, указанная для установки, не обладает достаточными привилегиями для создания учётной записи.',
	'config-install-user' => 'Создание базы данных пользователей',
	'config-install-user-alreadyexists' => 'Участник «$1» уже существует',
	'config-install-user-create-failed' => 'Не получилось создать участника «$1»: $2',
	'config-install-user-grant-failed' => 'Ошибка предоставления прав пользователю «$1»: $2',
	'config-install-tables' => 'Создание таблиц',
	'config-install-tables-exist' => "'''Предупреждение''': таблицы MediaWiki, возможно, уже существуют.
Пропуск повторного создания.",
	'config-install-tables-failed' => "'''Ошибка''': Таблица не может быть создана из-за ошибки: $1",
	'config-install-interwiki' => 'Заполнение таблицы интервики значениями по умолчанию',
	'config-install-interwiki-list' => 'Не удалось найти файл <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Предупреждение''': в интервики-таблице, кажется, уже есть записи.
Создание стандартного списка, пропущено.",
	'config-install-stats' => 'Статистика инициализации',
	'config-install-keys' => 'Создание секретного ключа',
	'config-insecure-keys' => "'''Предупреждение.''' {{PLURAL:$2|Ключ безопасности $1, созданный во время установки, недостаточно надёжен|Ключи безопасности $1, созданные во время установки, недостаточно надёжны}}. Рассмотрите возможность {{PLURAL:$2|его|их}} изменения вручную.",
	'config-install-sysop' => 'Создание учётной записи администратора',
	'config-install-subscribe-fail' => 'Не удаётся подписаться на mediawiki-announce',
	'config-install-mainpage' => 'Создание главной страницы с содержимым по умолчанию',
	'config-install-extension-tables' => 'Создание таблиц для включённых расширений',
	'config-install-mainpage-failed' => 'Не удаётся вставить главную страницу: $1',
	'config-install-done' => "'''Поздравляем!'''
Вы успешно установили MediaWiki.

Во время установки был создан файл <code>LocalSettings.php</code>.
Он содержит всю конфигурации вики.

Вам необходимо скачать его и положить в корневую директорию вашей вики (ту же директорию, где находится файл index.php). Его загрузка должна начаться автоматически.

Если автоматическая загрузка не началась или вы её отменили, вы можете скачать по ссылке ниже:

$3

'''Примечание''': Если вы не сделаете этого сейчас, то сгенерированный файл конфигурации не будет доступен вам в дальнейшем, если вы выйдете из установки, не скачивая его.

По окончании действий, описанных выше, вы сможете '''[$2 войти в вашу вики]'''.",
	'config-download-localsettings' => 'Загрузить LocalSettings.php',
	'config-help' => 'справка',
);

/** Slovenian (Slovenščina)
 * @author Dbc334
 */
$messages['sl'] = array(
	'config-desc' => 'Namestitveni program za MediaWiki',
	'config-title' => 'Namestitev MediaWiki $1',
	'config-information' => 'Informacije',
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

/** Serbian Cyrillic ekavian (‪Српски (ћирилица)‬) */
$messages['sr-ec'] = array(
	'config-continue' => 'Настави →',
	'config-page-language' => 'Језик',
);

/** Swedish (Svenska)
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'config-desc' => 'Installationsprogram för MediaWiki',
	'config-title' => 'Installation av MediaWiki $1',
	'config-information' => 'Information',
	'config-localsettings-key' => 'Uppgraderingsnyckel:',
	'config-localsettings-badkey' => 'Nyckeln du angav är inkorrekt.',
	'config-session-error' => 'Fel vid uppstart av session: $1',
	'config-your-language' => 'Ditt språk:',
	'config-your-language-help' => 'Välj ett språk som ska användas under installationen.',
	'config-wiki-language' => 'Wikispråk:',
	'config-wiki-language-help' => 'Välj det språk som wikin främst kommer att skrivas i.',
	'config-back' => '← Tillbaka',
	'config-continue' => 'Fortsätt →',
	'config-page-language' => 'Språk',
	'config-page-welcome' => 'Välkommen till MediaWiki!',
	'config-page-dbconnect' => 'Anslut till databas',
	'config-page-upgrade' => 'Uppgradera existerande installation',
	'config-page-dbsettings' => 'Databasinställningar',
	'config-page-name' => 'Namn',
	'config-page-options' => 'Alternativ',
	'config-page-install' => 'Installera',
	'config-page-complete' => 'Slutfört!',
	'config-page-restart' => 'Starta om installationen',
	'config-page-readme' => 'Läs mig',
	'config-page-releasenotes' => 'Utgivningsanteckningar',
	'config-page-copying' => 'Kopiering',
	'config-page-upgradedoc' => 'Uppgradering',
	'config-help-restart' => 'Vill du rensa all sparad data som du har skrivit in och starta om installationen?',
	'config-restart' => 'Ja, starta om',
	'config-sidebar' => '* [http://www.mediawiki.org MediaWikis hemsida]
* [http://www.mediawiki.org/wiki/Help:Contents Användarguide]
* [http://www.mediawiki.org/wiki/Manual:Contents Administratörguide]
* [http://www.mediawiki.org/wiki/Manual:FAQ Frågor och svar]
----
* <doclink href=Readme>Läs mig</doclink>
* <doclink href=ReleaseNotes>Utgivningsanteckningar</doclink>
* <doclink href=Copying>Kopiering</doclink>
* <doclink href=UpgradeDoc>Uppgradering</doclink>',
	'config-env-good' => 'Miljön har kontrollerats.
Du kan installera MediaWiki.',
	'config-env-bad' => 'Miljön har kontrollerats.
Du kan inte installera MediaWiki.',
	'config-env-php' => 'PHP $1 är installerad.',
	'config-env-php-toolow' => 'PHP $1 är installerad.
MediaWiki kräver PHP $2 eller högre.',
	'config-header-mysql' => 'MySQL-inställningar',
	'config-header-postgres' => 'PostgreSQL-inställningar',
	'config-header-sqlite' => 'SQLite-inställningar',
	'config-header-oracle' => 'Oracle-inställningar',
	'config-invalid-db-type' => 'Ogiltig databastyp',
	'config-missing-db-name' => 'Du måste ange ett värde för "Databasnamn"',
	'config-missing-db-host' => 'Du måste ange ett värde för "Databasvärd"',
	'config-invalid-db-name' => '"$1" är ett ogiltigt databasnamn.
Använd bara ASCII-bokstäver (a-z, A-Z), siffror (0-9), understreck (_) och bindestreck (-).',
	'config-invalid-db-prefix' => '"$1" är ett ogiltigt databasprefix.
Använd bara ASCII-bokstäver (a-z, A-Z), siffror (0-9), understreck (_) och bindestreck (-).',
	'config-connection-error' => '$1.

Kontrollera värden, användarnamnet och lösenordet nedan och försök igen',
	'config-invalid-schema' => '"$1" är ett ogiltigt schema för MediaWiki.
Använd bara ASCII-bokstäver (a-z, A-Z), siffror (0-9), understreck (_) och bindestreck (-).',
);

/** Tamil (தமிழ்)
 * @author TRYPPN
 */
$messages['ta'] = array(
	'config-information' => 'தகவல்',
	'config-your-language' => 'தங்களது மொழி:',
	'config-back' => '← முந்தைய',
	'config-continue' => 'தொடரவும் →',
	'config-page-language' => 'மொழி',
	'config-page-name' => 'பெயர்',
	'config-page-options' => 'விருப்பத்தேர்வுகள்',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'config-desc' => 'మీడియావికీ కొరకై స్థాపకి',
	'config-title' => 'మీడియావికీ $1స్థాపన',
	'config-information' => 'సమాచారం',
	'config-your-language' => 'మీ భాష:',
	'config-wiki-language' => 'వికీ భాష:',
	'config-back' => '← వెనక్కి',
	'config-continue' => 'కొనసాగించు →',
	'config-page-language' => 'భాష',
	'config-page-welcome' => 'మీడియావికీకి స్వాగతం!',
	'config-page-dbsettings' => 'డాటాబేసు అమరికలు',
	'config-page-name' => 'పేరు',
	'config-page-options' => 'ఎంపికలు',
	'config-page-install' => 'స్థాపించు',
	'config-page-complete' => 'పూర్తయ్యింది!',
	'config-page-readme' => 'నన్ను చదవండి',
	'config-page-releasenotes' => 'విడుదల విశేషాలు',
	'config-db-type' => 'డాటాబేసు రకం:',
	'config-db-name' => 'డాటాబేసు పేరు:',
	'config-db-install-account' => 'స్థాపనకి వాడుకరి ఖాతా',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-header-mysql' => 'MySQL అమరికలు',
	'config-header-postgres' => 'PostgreSQL అమరికలు',
	'config-header-sqlite' => 'SQLite అమరికలు',
	'config-header-oracle' => 'Oracle అమరికలు',
	'config-invalid-db-type' => 'తప్పుడు డాటాబేసు రకం',
	'config-connection-error' => '$1.

క్రింది హోస్టు, వాడుకరిపేరు మరియు సంకేతపదాలను ఒకసారి సరిచూసుకుని అప్పుడు ప్రయత్నించండి.',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'వికీ యొక్క పేరు:',
	'config-ns-other' => 'ఇతర (ఇవ్వండి)',
	'config-admin-name' => 'మీ పేరు:',
	'config-admin-password' => 'సంకేతపదం:',
	'config-admin-password-confirm' => 'సంకేతపదం మళ్ళీ:',
	'config-admin-email' => 'ఈ-మెయిలు చిరునామా:',
	'config-profile-wiki' => 'సంప్రదాయ వికీ',
	'config-profile-no-anon' => 'ఖాతా సృష్టింపు తప్పనిసరి',
	'config-profile-private' => 'అంతరంగిక వికీ',
	'config-license' => 'కాపీహక్కులు మరియు లైసెన్సు:',
	'config-license-pd' => 'సార్వజనీనం',
	'config-email-settings' => 'ఈ-మెయిల్ అమరికలు',
	'config-upload-deleted' => 'తొలగించిన దస్త్రాల కొరకు సంచయం:',
	'config-install-step-done' => 'పూర్తయింది',
	'config-install-step-failed' => 'విఫలమైంది',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 * @author Sky Harbor
 */
$messages['tl'] = array(
	'config-desc' => 'Ang instalador para sa MediaWiki',
	'config-title' => 'Instalasyong $1 ng MediaWiki',
	'config-information' => 'Kabatiran',
	'config-localsettings-key' => 'Susi ng pagsasapanahon:',
	'config-localsettings-badkey' => 'Hindi tama ang susing ibinigay mo.',
	'config-session-error' => 'Kamalian sa pagsisimula ng sesyon: $1',
	'config-no-session' => 'Nawala ang iyong datos ng sesyon!
Suriin ang iyong php.ini at tiyakin na ang <code>session.save_path</code> ay nakatakda sa angkop na direktoryo.',
	'config-your-language' => 'Ang wika mo:',
	'config-your-language-help' => 'Pumili ng isang wikang gagamitin habang isinasagawa ang pagtatalaga.',
	'config-wiki-language' => 'Wika ng Wiki:',
	'config-wiki-language-help' => 'Piliin ang wika kung saan mangingibabaw na isusulat ang wiki.',
	'config-back' => '← Bumalik',
	'config-continue' => 'Magpatuloy →',
	'config-page-language' => 'Wika',
	'config-page-welcome' => 'Maligayang pagdating sa MediaWiki!',
	'config-page-dbconnect' => 'Umugnay sa kalipunan ng datos',
	'config-page-upgrade' => 'Itaas ng uri ang umiiral na pagkakatalaga',
	'config-page-dbsettings' => 'Mga katakdaan ng kalipunan ng datos',
	'config-page-name' => 'Pangalan',
	'config-page-options' => 'Mga mapipili',
	'config-page-install' => 'Italaga',
	'config-page-complete' => 'Buo na!',
	'config-page-restart' => 'Simulan muli ang pag-iinstala',
	'config-page-readme' => 'Basahin ako',
	'config-page-releasenotes' => 'Pakawalan ang mga tala',
	'config-page-copying' => 'Kinokopya',
	'config-page-upgradedoc' => 'Itinataas ang uri',
	'config-page-existingwiki' => 'Umiiral na wiki',
	'config-help-restart' => 'Nais mo bang hawiin ang lahat ng nasagip na datong ipinasok mo at muling simulan ang proseso ng pagluluklok?',
	'config-restart' => 'Oo, muling simulan ito',
	'config-welcome' => '=== Pagsusuring pangkapaligiran ===
Isinasagawa ang payak na mga pagsusuri upang makita kung ang kapaligirang ito ay angkop para sa pagluluklok ng MediaWiki.
Dapat mong ibigay ang mga kinalabasan ng mga pagsusuring ito kung kailangan mo ng tulong habang nagluluklok.',
	'config-copyright' => "=== Karapatang-ari at Tadhana ===

$1

Ang programang ito ay malayang software; maaari mo itong ipamahagi at/o baguhin sa ilalim ng mga tadhana ng Pangkalahatang Pampublikong Lisensiyang GNU ayon sa pagkakalathala ng Free Software Foundation; na maaaring bersyong 2 ng Lisensiya, o (kung nais mo) anumang susunod na bersyon.

Ipinamamahagi ang programang ito na umaasang magiging gamitin, subaliut '''walang anumang katiyakan'''; na walang pahiwatig ng '''pagiging mabenta''' o '''kaangkupan para sa isang tiyak na layunin'''.
Tingnan ang Pangkalahatang Pampublikong Lisensiyang GNU para sa mas maraming detalye.

Dapat nakatanggap ka ng <doclink href=Copying>isang sipi ng Pangkalahatang Pampublikong Lisensiyang GNU</doclink> kasama ng programang ito; kung hindi, sumulat sa Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, o [http://www.gnu.org/licenses//gpl.html basahin ito sa Internet].",
	'config-sidebar' => '* [http://www.mediawiki.org Tahanan ng MediaWiki]
* [http://www.mediawiki.org/wiki/Help:Contents Gabay ng Tagagamit]
* [http://www.mediawiki.org/wiki/Manual:Contents Gabay ng Tagapangasiwa]
* [http://www.mediawiki.org/wiki/Manual:FAQ Mga Malimit Itanong]
----
* <doclink href=Readme>Basahin ako</doclink>
* <doclink href=ReleaseNotes>Mga tala ng paglalabas</doclink>
* <doclink href=Copying>Pagkopya</doclink>
* <doclink href=UpgradeDoc>Pagsasapanahon</doclink>',
	'config-env-good' => 'Nasuri na ang kapaligiran.
Mailuluklok mo ang MediaWiki.',
	'config-env-bad' => 'Nasuri na ang kapaligiran.
Hindi mo mailuklok ang MediaWiki.',
	'config-env-php' => 'Naitalaga ang PHP na $1.',
	'config-env-php-toolow' => 'Naitalaga ang PHP $1.
Subalit, nangangailangan ang MediaWiki ng PHP $2 o mas mataas pa.',
	'config-unicode-using-utf8' => 'Ginagamit ang utf8_normalize.so ni Brion Vibber para sa pagpapanormal ng Unikodigo.',
	'config-unicode-using-intl' => 'Ginagamit ang [http://pecl.php.net/intl intl dugtong na PECL] para sa pagsasanormal ng Unikodigo.',
	'config-no-db' => 'Hindi matagpuan ang isang angkop na drayber ng kalipunan ng datos!',
	'config-memory-raised' => 'Ang <code>hangganan_ng_alaala</code> ng PHP ay $1, itinaas sa $2.',
	'config-memory-bad' => "'''Babala:''' Ang <code>hangganan_ng_alaala</code> ng PHP ay $1.
Ito ay maaaring napakababa.
Maaaring mabigo ang pagluluklok!",
	'config-xcache' => 'Ininstala na ang [http://trac.lighttpd.net/xcache/ XCache]',
	'config-apc' => 'Ininstala na ang [http://www.php.net/apc APC]',
	'config-eaccel' => 'Ininstala na ang [http://eaccelerator.sourceforge.net/ eAccelerator]',
	'config-wincache' => 'Ininstala na ang [http://www.iis.net/download/WinCacheForPhp WinCache]',
	'config-no-cache' => "'''Babala:''' Hindi mahanap ang [http://eaccelerator.sourceforge.net eAccelerator], [http://www.php.net/apc APC], [http://trac.lighttpd.net/xcache/ XCache] o [http://www.iis.net/download/WinCacheForPhp WinCache].
Hindi pinapagana ang pagbabaon ng mga bagay.",
	'config-diff3-bad' => 'Hindi natagpuan ang GNU diff3.',
	'config-imagemagick' => 'Natagpuan ang ImageMagick: <code>$1</code>.
Papaganahin ang pagkakagyat ng larawan kapag pinagana mo ang mga pagkakargang paitaas.',
	'config-no-scaling' => 'Hindi matagpuan ang aklatang GD o ImageMagick.
Hindi papaganahin ang pagkakagyat ng larawan.',
	'config-no-uri' => "'''Kamalian:''' Hindi matukoy ang kasalukuyang URI.
Pinigilan ang pag-iinstala.",
	'config-db-type' => 'Uri ng kalipunan ng datos:',
	'config-db-host' => 'Tagapagpasinaya ng kalipunan ng datos:',
	'config-db-host-oracle' => 'TNS ng kalipunan ng dato:',
	'config-db-wiki-settings' => 'Kilalanin ang wiking ito',
	'config-db-name' => 'Pangalan ng kalipunan ng dato:',
	'config-db-install-account' => 'Akawnt ng tagagamit para sa pagluluklok',
	'config-db-username' => 'Pangalang pangtagagamit ng kalipunan ng dato:',
	'config-db-password' => 'Hudyat sa kalipunan ng dato:',
	'config-db-install-help' => 'Ipasok ang pangalan ng tagagamit at hudyat na gagamitin upang umugnay sa kalipunan ng dato habang isinasagawa ang pagluluklok.',
	'config-db-account-lock' => 'Gamitin ang gayun ding pangalan ng tagagamit at hudyat habang nasa normal na operasyon',
	'config-db-wiki-account' => 'Akawnt ng tagagamit para sa pangkaraniwang pagpapaandar',
	'config-db-prefix' => 'Unlapi ng talahanayan ng kalipunan ng dato:',
	'config-db-charset' => 'Pangkat ng panitik ng kalipunan ng dato',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 binaryo',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 paurong-kabagay UTF-8',
	'config-mysql-old' => 'Hindi kailangan ang MySQL na $1 o mas bago, mayroon kang $2.',
	'config-db-port' => 'Daungan ng kalipunan ng dato:',
	'config-db-schema' => 'Panukala para sa MediaWiki',
	'config-db-schema-help' => 'Ang nasa itaas na panukala ay pangkaraniwang magiging maayos.
Baguhin lamang ito kung alam mong kinakailangan.',
	'config-sqlite-dir' => 'Direktoryo ng dato ng SQLite:',
	'config-oracle-def-ts' => 'Likas na nakatakdang puwang ng talahanayan:',
	'config-oracle-temp-ts' => 'Pansamantalang puwang ng talahanayan:',
	'config-header-mysql' => 'Mga katakdaan ng MySQL',
	'config-header-postgres' => 'Mga katakdaan ng PostgreSQL',
	'config-header-sqlite' => 'Mga katakdaan ng SQLite',
	'config-header-oracle' => 'Mga katakdaan ng Oracle',
	'config-invalid-db-type' => 'Hindi tanggap na uri ng kalipunan ng dato',
	'config-missing-db-name' => 'Dapat kang magpasok ng isang halaga para sa "pangalan ng Kalipunan ng Dao"',
	'config-invalid-db-name' => 'Hindi tanggap na pangalan ng kalipunan ng dato na "$1".
Gumamit lamang ng mga titik ng ASCII (a-z, A-Z), mga bilang (0-9), mga salangguhit (_) at mga gitling (-).',
	'config-invalid-db-prefix' => 'Hindi tanggap na unlapi ng kalipunan ng dato na "$1". 
Gamitin lamang ang mga titik na ASCII (a-z, A-Z), mga bilang (0-9), mga salangguhit (_) at mga gitling (-).',
	'config-postgres-old' => 'Kailangan ang PostgreSQL $1 o mas bago, mayroon kang $2.',
	'config-sqlite-readonly' => 'Ang talaksang <code>$1</code> ay hindi maisusulat.',
	'config-sqlite-cant-create-db' => 'Hindi malikha ang talaksang <code>$1</code> ng kalipunan ng dato.',
	'config-sqlite-fts3-downgrade' => 'Nawawala ang suportang FTS3 ng PHP, ibinababa ang uri ng mga talahanayan',
	'config-regenerate' => 'Muling likhain ang LocalSettings.php →',
	'config-show-table-status' => 'Nabigo ang pagtatanong na IPAKITA ANG KALAGAYAN NG TALAHANAYAN!',
	'config-db-web-account' => 'Akawnt ng kalipunan ng dato para sa pagpunta sa web',
	'config-db-web-help' => 'Piliin ang pangalan ng tagagamit at hudyat na gagamitin ng tagapaghain ng web upang umugnay sa tagapaghain ng kalipunan ng dato, habang nasa pangkaraniwang pagtakbo ng wiki.',
	'config-db-web-account-same' => 'Gamitin ang gayun din akawnt katulad ng sa pagluluklok',
	'config-db-web-create' => 'Likhain ang akawnt kung hindi pa ito umiiral',
	'config-mysql-engine' => 'Makinang imbakan:',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-charset' => 'Pangkat ng panitik ng kalipunan ng dato:',
	'config-mysql-binary' => 'Binaryo',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name' => 'Pangalan ng wiki:',
	'config-site-name-help' => "Lilitaw ito sa bareta ng pamagat ng pantingin-tingin at sa samu't saring ibang mga lugar.",
	'config-site-name-blank' => 'Magpasok ng isang pangalan ng sityo.',
	'config-project-namespace' => 'Puwang na pampangalan ng proyekto:',
	'config-ns-generic' => 'Proyekto',
	'config-ns-site-name' => 'Katulad ng sa pangalan ng wiki: $1',
	'config-ns-other' => 'Iba pa (tukuyin)',
	'config-ns-other-default' => 'Wiki Ko',
	'config-admin-box' => 'Akawnt ng tagapangasiwa',
	'config-admin-name' => 'Pangalan mo:',
	'config-admin-password' => 'Hudyat:',
	'config-admin-password-confirm' => 'Hudyat uli:',
	'config-admin-name-blank' => 'Magpasok ng isang pangalan ng tagagamit na tagapangasiwa.',
	'config-admin-name-invalid' => 'Ang tinukoy na pangalan ng tagagamit na "<nowiki>$1</nowiki>" ay hindi tanggap.
Tumukoy ng ibang pangalan ng tagagamit.',
	'config-admin-password-blank' => 'Magpasok ng isang hudyat para sa akawnt ng tagapangasiwa.',
	'config-admin-password-same' => 'Ang hudyat ay hindi dapat na katulad ng pangalan ng tagagamit.',
	'config-admin-password-mismatch' => 'Hindi magkatugma ang ipinasok mong dalawang mga hudyat.',
	'config-admin-email' => 'Tirahan ng e-liham:',
	'config-admin-error-user' => 'Panloob na kamalian kapag nililikha ang isang tagapangasiwa na may pangalang "<nowiki>$1</nowiki>".',
	'config-admin-error-password' => 'Panloob na kamalian kapag nagtatakda ng isang hudyat na para sa tagapangasiwang "<nowiki>$1</nowiki>": <pre>$2</pre>',
	'config-subscribe' => 'Tumanggap mula sa [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce talaan ng mga pinadadalhan ng mga nilalabas na mga pabatid].',
	'config-almost-done' => 'Halos tapos ka na!
Maaari mo ngayong laktawan ang natitira pang pag-aayos at iluklok na ang wiki ngayon.',
	'config-optional-continue' => 'Magtanong sa akin ng marami pang mga tanong.',
	'config-optional-skip' => 'Naiinip na ako, basta iluklok na lang ang wiki.',
	'config-profile' => 'Balangkas ng mga karapatan ng tagagamit:',
	'config-profile-wiki' => 'Tradisyonal na wiki',
	'config-profile-no-anon' => 'Kailangan ang paglikha ng akawnt',
	'config-profile-fishbowl' => 'Pinahintulutang mga patnugot lamang',
	'config-profile-private' => 'Pribadong wiki',
	'config-license' => 'Karapatang-ari at lisensiya:',
	'config-license-none' => 'Walang talababa ng lisensiya',
	'config-license-cc-by-sa' => 'Malikhaing Pangkaraniwang Pagtukoy Pamamahaging Magkatulad',
	'config-license-cc-by-nc-sa' => 'Malikhaing Pangkaraniwang Pagtukoy Hindi-Pangkalakal Pamamahaging Magkatulad',
	'config-license-gfdl-old' => 'Lisensiya ng Malayang Dokumenstasyon 1.2 ng GNU',
	'config-license-gfdl-current' => 'Lisensiya ng Malayang Dokumenstasyon 1.3 ng GNU o mas bago',
	'config-license-pd' => 'Nasasakupan ng Madla',
	'config-license-cc-choose' => 'Pumili ng isang pasadyang Lisensiya ng Malikhaing mga Pangkaraniwan',
	'config-email-settings' => 'Mga katakdaan ng e-liham',
	'config-enable-email' => 'Paganahin ang palabas na e-liham',
	'config-email-user' => 'Paganahin ang tagagamit-sa-tagagamit na e-liham',
	'config-email-user-help' => 'Payagan ang lahat ng mga tagagamit na magpadala ng e-liham sa bawat isa kapag pinagana nila ito sa kanilang mga nais.',
	'config-email-usertalk' => 'Paganahin ang pabatid na pampahina ng usapan ng tagagamit',
	'config-email-usertalk-help' => 'Payagan ang mga tagagamit na tumanggap ng mga pabatid sa mga pagbabago ng pahina ng usapan ng tagagamit, kapag pinagana nila ito sa kanilang mga nais.',
	'config-email-watchlist' => 'Paganahin ang pabatid ng talaan ng bantayan',
	'config-email-watchlist-help' => 'Payagan ang mga tagagamit na tumanggap ng mga pabatid tungkol sa kanilang binabantayang mga pahina kapag pinagana nila ito sa kanilang mga nais.',
	'config-email-auth' => 'Paganahin ang pagpapatunay ng e-liham',
	'config-email-sender' => 'Pabalik na tirahan ng e-liham:',
	'config-upload-settings' => 'Mga pagkakarga ng mga larawan at talaksan',
	'config-upload-enable' => 'Paganahin ang pagkakarga ng talaksan',
	'config-upload-deleted' => 'Direktoryo para sa binurang mga talaksan:',
	'config-upload-deleted-help' => 'Pumili ng isang direktoryong pagsusupnayan ng naburang mga talaksan.
Ideyal na dapat itong  hindi mapupuntahan mula sa web.',
	'config-logo' => 'URL ng logo:',
	'config-instantcommons' => 'Paganahin ang Mga Pangkaraniwang Biglaan',
	'config-cc-error' => 'Hindi nagbigay ng resulta ang pampili ng lisensiya ng Malikhaing Pangkaraniwan.
Ipasok na kinakamay ang pangalan ng lisensiya.',
	'config-cc-again' => 'Pumili uli...',
	'config-cc-not-chosen' => 'Piliin kung anong lisensiya ng Malikhaing mga Pangkaraniwan ang nais mo at pindutin ang "magpatuloy".',
	'config-advanced-settings' => 'Mas masulong na pagkakaayos',
	'config-cache-options' => 'Mga katakdaan para sa pagtatago ng bagay:',
	'config-memcached-servers' => 'Mga tagapaghaing itinago sa alaala:',
	'config-memcache-needservers' => 'Pinili mo ang Memcached bilang uri mo ng taguan ngunit hindi tumukoy ng anumang mga tagapaghain.',
	'config-memcache-badip' => 'Nagpasok ka ng isang hindi tanggap na tirahan ng IP para sa Memcached: $1.',
	'config-memcache-noport' => 'Hindi ka tumukoy ng isang daungan na gagamitin para sa tagapaghain ng Memcached: $1.
Kung hindi mo alam ang daungan, ang likas na nakatakda ay 11211.',
	'config-memcache-badport' => 'Ang bilang ng daungan ng Memcached ay dapat na nasa pagitan ng $1 at $2.',
	'config-extensions' => 'Mga dugtong',
	'config-install-step-done' => 'nagawa na',
	'config-install-step-failed' => 'nabigo',
	'config-install-extensions' => 'Isinasama ang mga karugtong',
	'config-install-database' => 'Inihahanda ang kalipunan ng dato',
	'config-install-pg-schema-failed' => 'Nabigo ang paglikha ng mga talahanayan.
Tiyakin na ang tagagamit na "$1" ay maaaring makasulat sa balangkas na "$2".',
	'config-install-pg-commit' => 'Isinasagawa ang mga pagbabago',
	'config-install-pg-plpgsql' => 'Sumusuri ng wikang PL/pgSQL',
	'config-pg-no-plpgsql' => 'Kailangan mong magtalaga ng wikang PL/pgSQL sa loob ng kalipunan ng datong $1',
	'config-pg-no-create-privs' => 'Ang tinukoy mong akawnt para sa pagtatalaga ay walang sapat na mga pribilehiyo upang makalikha ng isang akawnt.',
	'config-install-user' => 'Nililikha ang tagagamit ng kalipunan ng dato',
	'config-install-user-alreadyexists' => 'Umiiral na ang tagagamit na "$1"',
	'config-install-user-create-failed' => 'Nabigo ang paglikha ng tagagamit na "$1": $2',
	'config-install-user-grant-failed' => 'Nabigo ang pagbibigay ng pahintulot sa tagagamit na "$1": $2',
	'config-install-tables' => 'Nililikha ang mga talahanayan',
	'config-install-tables-exist' => "'''Babala''': Tila umiiral na ang mga talahanayan ng MediaWiki.
Nilalaktawan ang paglikha.",
	'config-install-tables-failed' => "'''Kamalian''': Nabigo ang paglikha ng talahanayan na may sumusunod na kamalian: $1",
	'config-install-interwiki' => 'Nilalagyan ng laman ang likas na nakatakdang talahanayan ng interwiki',
	'config-install-interwiki-list' => 'Hindi matagpuan ang talaksang <code>interwiki.list</code>.',
	'config-install-interwiki-exists' => "'''Babala''': Tila may mga laman na ang talahanayan ng interwiki.
Nilalaktawan ang likas na nakatakdang talaan.",
	'config-install-stats' => 'Sinisimulan ang estadistika',
	'config-install-keys' => 'Ginagawa ang lihim na susi',
	'config-install-sysop' => 'Nililikha ang akawnt ng tagagamit na tagapangasiwa',
	'config-install-subscribe-fail' => 'Hindi nagawang sumipi mula sa mediawiki-announce',
	'config-install-mainpage' => 'Nililikha ang pangunahing pahina na may likas na nakatakdang nilalaman',
	'config-install-extension-tables' => 'Nililikha ang mga talahanayan para sa pinagaganang mga dugtong',
	'config-install-mainpage-failed' => 'Hindi maisingit ang pangunahing pahina: $1',
	'config-install-done' => "'''Maligayang bati!'''
Matagumpay mong nailuklok ang MediaWiki.

Ang tagapagluklok ay nakagawa ng isang talaksan ng <code>LocalSettings.php</code>.
Naglalaman ito ng lahat ng iyong mga pagsasaayos.

Kailangan mo itong ikargang paibaba at ilagay ito sa lipon ng iyong pagluluklok ng wiki (katulad ng direktoryo ng index.php).  Ang pagkakargang paibaba ay dapat na kusang magsimula.

Kung ang pagkakargang paibaba ay hindi inialok, o kung hindi mo ito itinuloy, maaari mong muling simulan ang pagkakargang paibaba sa pamamagitan ng pagpindot sa kawing na nasa ibaba:

$3

'''Paunawa''': Kapag hindi mo ito ginawa ngayon, ang nagawang talaksang ito ng pagkakaayos ay hindi mo na makukuha mamaya kapag lumabas ka mula sa pagluluklok na hindi ikinakarga itong paibaba.

Kapag nagawa na iyan, maaari ka nang '''[$2 pumasok sa wiki mo]'''.",
	'config-download-localsettings' => 'Ikargang paibaba ang LocalSettings.php',
	'config-help' => 'saklolo',
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
	'config-env-good' => 'Перевірку середовища успішно завершено.
Ви можете встановити MediaWiki.',
	'config-env-bad' => 'Було проведено перевірку середовища. Ви не можете встановити MediaWiki.',
	'config-env-php' => 'Встановлено версію PHP: $1.',
	'config-unicode-using-utf8' => 'Використовувати utf8_normalize.so Брайона Віббера для нормалізації Юнікоду.',
	'config-unicode-using-intl' => 'Використовувати [http://pecl.php.net/intl міжнародне розширення PECL] для нормалізації Юнікоду.',
	'config-unicode-pure-php-warning' => "'''Увага''': [http://pecl.php.net/intl міжнародне розширення PECL] не може провести нормалізацію Юнікоду. 
Якщо ваш сайт має високий трафік, вам варто почитати про [http://www.mediawiki.org/wiki/Unicode_normalization_considerations нормалізацію Юнікоду].",
	'config-no-db' => 'Не вдалося знайти відповідний драйвер бази даних!',
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache] встановлено',
	'config-apc' => '[http://www.php.net/apc APC] встановлено',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator] встановлено',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache] встановлено',
	'config-db-type' => 'Тип бази даних:',
	'config-db-host' => 'Хост бази даних:',
	'config-db-name' => 'Назва бази даних:',
	'config-db-password' => 'Пароль бази даних:',
	'config-db-charset' => 'Кодування бази даних',
	'config-db-port' => 'Порт бази даних:',
	'config-invalid-db-type' => 'Невірний тип бази даних',
	'config-invalid-db-name' => 'Неприпустима назва бази даних "$1".
Використовуйте тільки ASCII букви (a-z, A-Z), цифри (0-9), знаки підкреслення (_) і дефіси (-).',
	'config-invalid-db-prefix' => 'Неприпустимий префікс бази даних "$1". 
Використовуйте тільки ASCII букви (a-z, A-Z), цифри (0-9), знаки підкреслення (_) і дефіси (-).',
	'config-sqlite-cant-create-db' => 'Не вдалося створити файл бази даних <code>$1</code>.',
	'config-db-web-create' => 'Створити обліковий запис, якщо його ще не існує',
	'config-mysql-charset' => 'Кодування бази даних:',
	'config-mysql-binary' => 'Двійкове',
	'config-site-name' => 'Назва вікі:',
	'config-site-name-blank' => 'Введіть назву сайту.',
	'config-project-namespace' => 'Простір назв проекту:',
	'config-ns-generic' => 'Проект',
	'config-admin-name' => "Ваше ім'я:",
	'config-admin-password' => 'Пароль:',
	'config-admin-password-confirm' => 'Пароль ще раз:',
	'config-admin-password-mismatch' => 'Два введені вами паролі не збігаються.',
	'config-admin-email' => 'Адреса електронної пошти:',
	'config-license' => 'Авторські права і ліцензія:',
	'config-license-cc-by-nc-sa' => 'Creative Commons Attribution Non-Commercial Share Alike',
	'config-license-gfdl-old' => 'GNU Free Documentation License 1.2',
	'config-email-settings' => 'Налаштування електронної пошти',
	'config-upload-enable' => 'Дозволити завантаження файлів',
	'config-upload-deleted' => 'Каталог для вилучених файлів:',
	'config-cc-again' => 'Виберіть знову ...',
	'config-extensions' => 'Розширення',
	'config-install-step-done' => 'виконано',
	'config-install-step-failed' => 'не вдалося',
	'config-install-interwiki-list' => 'Не вдалося знайти файл <code>interwiki.list</code>.',
);

/** Yiddish (ייִדיש)
 * @author פוילישער
 */
$messages['yi'] = array(
	'config-admin-name' => 'אײַער נאָמען:',
);

/** Simplified Chinese (‪中文(简体)‬)
 * @author Hydra
 * @author PhiLiP
 * @author 阿pp
 */
$messages['zh-hans'] = array(
	'config-desc' => 'MediaWiki安装程序',
	'config-title' => 'MediaWiki $1配置',
	'config-information' => '信息',
	'config-localsettings-upgrade' => '已检测到<code>LocalSettings.php</code>文件。要升级该配置，请在下面的框中输入<code>$wgUpgradeKey</code>的值。您可以在LocalSettings.php中找到它。',
	'config-localsettings-cli-upgrade' => '已检测到LocalSettings.php文件。要升级该配置，请使用--upgrade=yes选项。',
	'config-localsettings-key' => '升级密钥：',
	'config-localsettings-badkey' => '您提供的密钥不正确。',
	'config-upgrade-key-missing' => '检测到MediaWiki的配置已经存在。若要升级该配置，请将下面一行文本添加到LocalSettings.php的底部：

$1',
	'config-localsettings-incomplete' => '当前的LocalSettings.php可能并不完整，因为变量$1没有设置。请在LocalSettings.php设置该变量，并单击“继续”。',
	'config-localsettings-connection-error' => '在使用LocalSettings.php或AdminSettings.php中指定的设置连接数据库时发生错误。请修复相应设置并重试。

$1',
	'config-session-error' => '启动会话出错：$1',
	'config-session-expired' => '您的会话数据可能已经过期，当前会话的使用期限被设定为$1。您可以在php.ini中设置<code>session.gc_maxlifetime</code>来延长此期限，并重新启动本配置程序。',
	'config-no-session' => '您的会话数据丢失了！请检查php.ini并确保<code>session.save_path</code>被设置为适当的目录。',
	'config-your-language' => '您使用的语言：',
	'config-your-language-help' => '选择在安装过程中使用的语言。',
	'config-wiki-language' => 'Wiki使用的语言：',
	'config-wiki-language-help' => '选择将要安装的wiki在多数情况下使用的语言。',
	'config-back' => '← 后退',
	'config-continue' => '继续 →',
	'config-page-language' => '语言',
	'config-page-welcome' => '欢迎使用MediaWiki！',
	'config-page-dbconnect' => '连接到数据库',
	'config-page-upgrade' => '升级当前配置',
	'config-page-dbsettings' => '数据库设置',
	'config-page-name' => '名称',
	'config-page-options' => '选项',
	'config-page-install' => '安装',
	'config-page-complete' => '完成！',
	'config-page-restart' => '重新开始安装',
	'config-page-readme' => '自述',
	'config-page-releasenotes' => '发布说明',
	'config-page-copying' => '复制',
	'config-page-upgradedoc' => '更新',
	'config-page-existingwiki' => '已有wiki',
	'config-help-restart' => '是否要清除所有已输入且保存的数据，并重新启动安装过程吗？',
	'config-restart' => '是的，重启吧',
	'config-welcome' => '=== 环境检查 ===
对当前环境是否适合安装MediaWiki作基本的检查。如果您在安装过程中需要帮助，请提供这些检查的结果。',
	'config-copyright' => "=== 版权和条款 ===

\$1

本程序为自由软件；您可依据自由软件基金会所发表的GNU通用公共授权条款规定，就本程序再为发布与／或修改；无论您依据的是本授权的第二版或（您自行选择的）任一日后发行的版本。

本程序是基于使用目的而加以发布，然而'''不负任何担保责任'''；亦无对'''适售性'''或'''特定目的适用性'''所为的默示性担保。详情请参照GNU通用公共授权。

您应已收到附随于本程序的<doclink href=\"Copying\">GNU通用公共授权的副本</doclink>；如果没有，请写信至自由软件基金会：59 Temple Place - Suite 330, Boston, Ma 02111-1307, USA，或[http://www.gnu.org/copyleft/gpl.html 在线阅读]。",
	'config-sidebar' => '* [http://www.mediawiki.org/wiki/MediaWiki/zh-hans MediaWiki首页]
* [http://www.mediawiki.org/wiki/Help:Contents/zh-hans 用户帮助]
* [http://www.mediawiki.org/wiki/Manual:Contents 管理员帮助]
* [http://www.mediawiki.org/wiki/Manual:FAQ/zh-hans 常见问题解答]',
	'config-env-good' => '环境检查已经完成。您可以安装MediaWiki。',
	'config-env-bad' => '环境检查已经完成。您不能安装MediaWiki。',
	'config-env-php' => 'PHP $1已安装。',
	'config-unicode-using-utf8' => '使用Brion Vibber的utf8_normalize.so实现Unicode正常化。',
	'config-unicode-using-intl' => '使用[http://pecl.php.net/intl intl PECL扩展]实现Unicode正常化。',
	'config-unicode-pure-php-warning' => "'''警告'''：[http://pecl.php.net/intl intl PECL扩展]无法处理Unicode正常化，故只能退而采用运行较慢的纯PHP实现的方法。如果您运行着一个高流量的站点，请参阅[http://www.mediawiki.org/wiki/Unicode_normalization_considerations Unicode正常化]一文。",
	'config-unicode-update-warning' => "'''警告'''：Unicode正常化封装器的已安装版本使用了旧版本的[http://site.icu-project.org/ ICU项目]库。如果您需要使用Unicode，请将其[http://www.mediawiki.org/wiki/Unicode_normalization_considerations 升级]。",
	'config-no-db' => '找不到合适的数据库驱动！',
	'config-no-db-help' => '您需要为PHP安装数据库驱动。MediaWiki支持下列数据库类型：$1

如果您正在使用共享主机，请让您的主机提供商为您安装适当的数据库驱动。
如果PHP由您自行编译，请将其重新配置以启用数据库客户端，例如使用<code>./configure --with-mysql</code>。
如果PHP是您通过Debian或Ubuntu包安装的，那么您还需要安装php5-mysql模块。',
	'config-no-fts3' => "'''警告'''：已编译的SQLite不包含[http://sqlite.org/fts3.html FTS3模块]，后台搜索功能将不可用。",
	'config-register-globals' => "'''警告：PHP的<code>[http://php.net/register_globals register_globals]</code>选项被启用。请尽量禁用该功能，'''虽然不会影响MediaWiki的运行，但您的服务器会被暴露给潜在的安全漏洞。",
	'config-magic-quotes-runtime' => "'''致命错误：[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_runtime]被启用！'''
此选项会无法预测地破坏输入的数据，请将其禁用，否则您将不能安装或使用MediaWiki。",
	'config-magic-quotes-sybase' => "'''致命错误：[http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime magic_quotes_sybase]被启用！'''
此选项会无法预测地破坏输入的数据，请将其禁用，否则您将不能安装或使用MediaWiki。",
	'config-mbstring' => "'''致命错误：[http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload mbstring.func_overload]被启用！'''
此选项会导致错误并不可预测地破坏数据，请将其禁用，否则您将不能安装或使用MediaWiki。",
	'config-ze1' => "'''致命错误：[http://www.php.net/manual/en/ini.core.php zend.ze1_compatibility_mode]被启用！'''
此选项将导致MediaWiki出现极其严重的故障，请将其禁用，否则您将不能安装或使用MediaWiki。",
	'config-safe-mode' => "'''警告：'''PHP的[http://www.php.net/features.safe-mode 安全模式]已启用。它可能会导致一些问题，尤其在对文件上传和数学公式<code>math</code>的支持方面。",
	'config-xml-bad' => '缺少PHP的XML模块。MediaWiki需要使用该模块提供的函数，在当前配置下将无法工作。如果您正在使用Mandrake Linux，请安装php-xml包。',
	'config-pcre' => '可能缺少PCRE的支持模块。MediaWiki的运行需要兼容于Perl的正则表达式函数。',
	'config-pcre-no-utf8' => "'''致命错误'''：PHP的PCRE模块在编译时可能没有包含PCRE_UTF8支持。MediaWiki需要UTF-8支持才能正常工作。",
	'config-memory-raised' => 'PHP的内存使用上限<code>memory_limit</code>为$1，自动提升到$2。',
	'config-memory-bad' => "'''警告：'''PHP的内存使用上限<code>memory_limit</code>为$1。该设定可能过低，并导致安装失败！",
	'config-xcache' => '[http://trac.lighttpd.net/xcache/ XCache]已安装',
	'config-apc' => '[http://www.php.net/apc APC]已安装',
	'config-eaccel' => '[http://eaccelerator.sourceforge.net/ eAccelerator]已安装',
	'config-wincache' => '[http://www.iis.net/download/WinCacheForPhp WinCache]已安装',
	'config-no-cache' => "'''警告：'''找不到[http://eaccelerator.sourceforge.net eAccelerator]、[http://www.php.net/apc APC]、[http://trac.lighttpd.net/xcache/ XCache]或[http://www.iis.net/download/WinCacheForPhp WinCache]，无法启用对象缓存。
Object caching is not enabled.",
	'config-diff3-bad' => '找不到GNU diff3。',
	'config-imagemagick' => '已找到ImageMagick：<code>$1</code>。如果你启用了上传功能，缩略图功能也将被启用。',
	'config-gd' => '已找到内建的GD图形库。如果你启用了上传功能，缩略图功能也将被启用。',
	'config-no-scaling' => '找不到GD库或ImageMagick。缩略图功能将不可用。',
	'config-no-uri' => "'''错误：'''无法确定当前的URI。安装已中断。",
	'config-uploads-not-safe' => "'''警告：'''您的默认上传目录<code>$1</code>存在允许执行任意脚本的漏洞。尽管MediaWiki会对所有已上传的文件进行安全检查，但我们仍然强烈建议您在启用上传功能前[http://www.mediawiki.org/wiki/Manual:Security#Upload_security 关闭该安全漏洞]。",
	'config-brokenlibxml' => '您的系统安装的PHP和libxml2版本组合存在故障，并可能在MediaWiki和其他web应用程序中造成隐藏的数据损坏。请将PHP升级到5.2.9或以上，libxml2升级到2.7.3或以上（[http://bugs.php.net/bug.php?id=45996 PHP的故障报告]）。安装已中断。',
	'config-using531' => '由于函数<code>__call()</code>的引用参数存在故障，PHP $1和MediaWiki无法兼容。请升级到PHP 5.3.2或以上版本，或降级到PHP 5.3.0以修复该问题（[http://bugs.php.net/bug.php?id=50394 PHP的故障报告]）。安装已中断。',
	'config-db-type' => '数据库类型：',
	'config-db-host' => '数据库主机：',
	'config-db-host-help' => '如果您的数据库位于另一台服务器上，在此输入主机名或IP地址。

如果您使用的是共享web主机，您的主机提供商应会在他们的文档中给出正确的主机名称。

如果您使用了Windows服务器和MySQL数据库，使用“localhost”可能无法识别到本地服务器。如果是这样的话，请尝试指定本地服务器的IP地址为“127.0.0.1”。',
	'config-db-host-oracle' => '数据库透明网络底层（TNS）：',
	'config-db-host-oracle-help' => '请输入合法的[http://download.oracle.com/docs/cd/B28359_01/network.111/b28317/tnsnames.htm 本地连接名]，并确保tnsnames.ora文件对本安装程序可见。<br />如果您使用的客户端库为10g或更新的版本，您还可以使用[http://download.oracle.com/docs/cd/E11882_01/network.112/e10836/naming.htm 简单连接名方法]（easy connect naming method）。',
	'config-db-wiki-settings' => '标识本wiki',
	'config-db-name' => '数据库名称：',
	'config-db-name-help' => '请输入一个可以标识您的wiki的名称。请勿使用空格。

如果您正在使用共享web主机，您的主机提供商或会给您指定一个数据库名称，或会让您通过控制面板创建数据库。',
	'config-db-name-oracle' => '数据库模式：',
	'config-db-install-account' => '用于安装的用户帐号',
	'config-db-username' => '数据库用户名：',
	'config-db-password' => '数据库密码：',
	'config-db-install-username' => '请输入在安装过程中用于连接数据库的用户名。请勿输入MediaWiki帐号的用户名，请输入您数据库的用户名。',
	'config-db-install-password' => '请输入在安装过程中用于连接数据库的密码。请勿输入MediaWiki帐号的密码，请输入您数据库的密码。',
	'config-db-install-help' => '请输入在安装过程中用于连接数据库的用户名和密码。',
	'config-db-account-lock' => '在普通操作中使用相同的用户名和密码',
	'config-db-wiki-account' => '用于普通操作的用户帐号',
	'config-db-wiki-help' => '输入在普通的wiki操作中（安装完成后）将用于连接数据库的用户名和密码。如果该帐号并不存在，而安装帐号具有足够的权限，该用户帐号会被自动创建，并被赋予足以运行此wiki的最低权限。',
	'config-db-prefix' => '数据库表前缀：',
	'config-db-prefix-help' => '如果您需要在多个wiki之间（或在MediaWiki与其他web应用程序之间）共享一个数据库，您可以通过添加前缀的方式来避免出现表名称的冲突。请勿使用空格。

此字段通常可留空。',
	'config-db-charset' => '数据库字符集',
	'config-charset-mysql5-binary' => 'MySQL 4.1/5.0 二进制',
	'config-charset-mysql5' => 'MySQL 4.1/5.0 UTF-8',
	'config-charset-mysql4' => 'MySQL 4.0 UTF-8（向后兼容）',
	'config-charset-help' => "'''警告：'''如果您在MySQL 4.1+中使用'''向后兼容的UTF-8'''字符集，并在之后使用<code>mysqldump</code>备份了数据库，则可能损坏所有的非ASCII字符，从而不可逆地破坏您的备份！

在'''二进制模式'''下，MediaWiki会将UTF-8编码的文本存于数据库的二进制字段中。相对于MySQL的UTF-8模式，这种方法效率更高，并允许您使用全范围的Unicode字符。

在'''UTF-8模式'''下，MySQL将知道您数据使用的字符集，并能适当地提供和转换内容。但这样做您将无法在数据库中存储[http://zh.wikipedia.org/wiki/基本多文种平面 基本多文种平面]以外的字符。",
	'config-mysql-old' => '需要MySQL $1或更新的版本，您的版本为$2。',
	'config-db-port' => '数据库端口：',
	'config-db-schema' => 'MediaWiki的数据库模式',
	'config-db-schema-help' => '上述数据库模式的设置通常是正确的。请在有此需求时才更改它们。',
	'config-sqlite-dir' => 'SQLite数据目录：',
	'config-sqlite-dir-help' => "SQLite会将所有的数据存储于单一文件中。

您所提供的目录必须在安装过程中对网页服务器可写。

该目录'''不应'''允许通过web访问，因此我们不会将数据文件和PHP文件放在一起。

安装程序在创建数据文件时，亦会在相同目录下创建<code>.htaccess</code>以控制权限。假若此等控制失效，则可能会将您的数据文件暴露于公共空间，让他人可以获取用户数据（电子邮件地址、杂凑后的密码）、被删除的版本以及其他在wiki上被限制访问的数据。

请考虑将数据库统一放置在某处，如<code>/var/lib/mediawiki/yourwiki</code>下。",
	'config-oracle-def-ts' => '默认表空间：',
	'config-oracle-temp-ts' => '临时表空间：',
	'config-support-info' => 'MediaWiki支持以下数据库系统：

$1

如果您在下面列出的数据库系统中没有找到您希望使用的系统，请根据上方链向的指引启用支持。',
	'config-support-mysql' => '* $1是MediaWiki的首选数据库，对它的支持最为完备（[http://www.php.net/manual/en/mysql.installation.php 如何将对MySQL的支持编译进PHP中]）',
	'config-support-postgres' => '* $1是一种流行的开源数据库系统，可作为MySQL的替代（[http://www.php.net/manual/en/pgsql.installation.php 如何将对PostgreSQL的支持编译进PHP中]）',
	'config-support-sqlite' => '* $1是一种轻量级的数据库系统，能被良好地支持。（[http://www.php.net/manual/en/pdo.installation.php 如何将对SQLite的支持编译进PHP中]，须使用PDO）',
	'config-support-oracle' => '* $1是一种商用企业级的数据库。（[http://www.php.net/manual/en/oci8.installation.php 如何将对OCI8的支持编译进PHP中]）',
	'config-header-mysql' => 'MySQL设置',
	'config-header-postgres' => 'PostgreSQL设置',
	'config-header-sqlite' => 'SQLite设置',
	'config-header-oracle' => 'Oracle设置',
	'config-invalid-db-type' => '无效的数据库类型',
	'config-missing-db-name' => '您必须为“数据库名称”输入内容',
	'config-missing-db-host' => '您必须为“数据库主机”输入内容',
	'config-missing-db-server-oracle' => '您必须为“数据库透明网络底层（TNS）”输入内容',
	'config-invalid-db-server-oracle' => '无效的数据库TNS“$1”。请只使用ASCII字母（a-z、A-Z）、数字（0-9）、下划线（_）和点号（.）。',
	'config-invalid-db-name' => '无效的数据库名称“$1”。请只使用ASCII字母（a-z、A-Z）、数字（0-9）、下划线（_）和连字号（-）。',
	'config-invalid-db-prefix' => '无效的数据库前缀“$1”。请只使用ASCII字母（a-z、A-Z）、数字（0-9）、下划线（_）和连字号（-）。',
	'config-connection-error' => '$1。

请检查下列的主机、用户名和密码设置后重试。',
	'config-invalid-schema' => '无效的MediaWiki数据库模式“$1”。请只使用ASCII字母（a-z、A-Z）、数字（0-9）和下划线（_）。',
	'config-postgres-old' => '需要PostgreSQL $1或更新的版本，您的版本为$2。',
	'config-sqlite-name-help' => '请为您的wiki指定一个用于标识的名称。请勿使用空格或连字号，该名称将被用作SQLite的数据文件名。',
	'config-sqlite-parent-unwritable-group' => '由于父目录<code><nowiki>$2</nowiki></code>对网页服务器不可写，无法创建数据目录<code><nowiki>$1</nowiki></code>。

安装程序已确定您网页服务器所使用的用户。请将<code><nowiki>$3</nowiki></code>目录设为对该用户可写以继续安装过程。在Unix/Linux系统中，您可以逐行输入下列命令：

<pre>cd $2
mkdir $3
chgrp $4 $3
chmod g+w $3</pre>',
	'config-sqlite-parent-unwritable-nogroup' => '由于父目录<code><nowiki>$2</nowiki></code>对网页服务器不可写，无法创建数据目录<code><nowiki>$1</nowiki></code>。

安装程序无法确定您网页服务器所使用的用户。请将<code><nowiki>$3</nowiki></code>目录设为全局可写（对所有用户）以继续安装过程。在Unix/Linux系统中，您可以逐行输入下列命令：

<pre>cd $2
mkdir $3
chmod a+w $3</pre>',
	'config-sqlite-mkdir-error' => '创建数据目录“$1”时发生错误。请检查路径后重试。',
	'config-sqlite-dir-unwritable' => '无法写入目录“$1”。请修改该目录的权限，使其对网页服务器可写后重试。',
	'config-sqlite-connection-error' => '$1。

请检查下列的数据目录和数据库名称后重试。',
	'config-sqlite-readonly' => '文件<code>$1</code>不可写。',
	'config-sqlite-cant-create-db' => '无法创建数据文件<code>$1</code>。',
	'config-sqlite-fts3-downgrade' => 'PHP缺少FTS3支持，正在降级数据表',
	'config-can-upgrade' => "在数据库中发现了MediaWiki的数据表。要将它们升级至MediaWiki $1，请点击'''继续'''。",
	'config-upgrade-done' => "升级完成。

现在您可以[$1 开始使用您的wiki]了。

如果您需要重新生成<code>LocalSettings.php</code>文件，请点击下面的按钮。除非您的wiki出现了问题，我们'''不推荐'''您执行此操作。",
	'config-upgrade-done-no-regenerate' => '升级完成。

现在您可以[$1 开始使用您的wiki]了。',
	'config-regenerate' => '重新生成LocalSettings.php →',
	'config-show-table-status' => '查询SHOW TABLE STATUS失败！',
	'config-unknown-collation' => "'''警告：'''数据库使用了无法识别的整理。",
	'config-db-web-account' => '供网页访问使用的数据库帐号',
	'config-db-web-help' => '请指定在wiki执行普通操作时，网页服务器用于连接数据库服务器的用户名和密码。',
	'config-db-web-account-same' => '使用和安装程序相同的帐号',
	'config-db-web-create' => '如果帐号不存在，则自动创建',
	'config-db-web-no-create-privs' => '您指定给安装程序的帐号缺少创建帐号的权限，因此您指定的帐号必须已经存在。',
	'config-mysql-engine' => '存储引擎：',
	'config-mysql-innodb' => 'InnoDB',
	'config-mysql-myisam' => 'MyISAM',
	'config-mysql-engine-help' => "'''InnoDB'''通常是最佳选项，因为它对并发操作有着良好的支持。

'''MyISAM'''在单用户或只读环境下可能会有更快的性能表现。但MyISAM数据库出错的概率一般要大于InnoDB数据库。",
	'config-mysql-charset' => '数据库字符集：',
	'config-mysql-binary' => '二进制',
	'config-mysql-utf8' => 'UTF-8',
	'config-mysql-charset-help' => "在'''二进制模式'''下，MediaWiki会将UTF-8编码的文本存于数据库的二进制字段中。相对于MySQL的UTF-8模式，这种方法效率更高，并允许您使用全范围的Unicode字符。

在'''UTF-8模式'''下，MySQL将知道您数据使用的字符集，并能适当地提供和转换内容。但这样做您将无法在数据库中存储[http://zh.wikipedia.org/wiki/基本多文种平面 基本多文种平面]以外的字符。",
	'config-site-name' => 'Wiki的名称：',
	'config-site-name-help' => '填入的内容会出现在浏览器的标题栏以及其他多处位置中。',
	'config-site-name-blank' => '输入网站的名称。',
	'config-project-namespace' => '项目名字空间：',
	'config-ns-generic' => '项目',
	'config-ns-site-name' => '与wiki名称相同：$1',
	'config-ns-other' => '其他（自定义）',
	'config-ns-other-default' => '我的Wiki',
	'config-project-namespace-help' => "依循维基百科形成的惯例，许多wiki将他们的方针页面存放在与内容页面不同的“'''项目名字空间'''”中。所有位于该名字空间下的页面标题都会被冠以固定的前缀，您可以在此处指定这一前缀。传统上，这一前缀应与wiki的命名保持一致，但请勿在其中使用标点符号，如“#”或“:”。",
	'config-ns-invalid' => '指定的名字空间“<nowiki>$1</nowiki>”无效，请为项目名字空间指定其他名称。',
	'config-admin-box' => '管理员帐号',
	'config-admin-name' => '您的名字：',
	'config-admin-password' => '密码：',
	'config-admin-password-confirm' => '确认密码：',
	'config-admin-help' => '在此输入您想使用的用户名，例如“乔帮主”。您将使用该名称登录本wiki。',
	'config-admin-name-blank' => '输入管理员的用户名。',
	'config-admin-name-invalid' => '指定的用户名“<nowiki>$1</nowiki>”无效，请指定其他用户名。',
	'config-admin-password-blank' => '输入管理员帐号的密码。',
	'config-admin-password-same' => '密码不能和用户名相同。',
	'config-admin-password-mismatch' => '两次输入的密码并不相同。',
	'config-admin-email' => '电子邮件地址：',
	'config-admin-email-help' => '在此输入电子邮件地址，这样您将可以收到本wiki上的其他用户发来的电子邮件，可以重置您的密码，并能在监视列表中的页面被更改时收到邮件通知。',
	'config-admin-error-user' => '在创建用户名为“<nowiki>$1</nowiki>”的管理员帐号时发生内部错误。',
	'config-admin-error-password' => '在为管理员“<nowiki>$1</nowiki>”设置密码时发生内部错误：<pre>$2</pre>',
	'config-admin-error-bademail' => '您输入了无效的电子邮件地址。',
	'config-subscribe' => '订阅[https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce 发行公告邮件列表]。',
	'config-subscribe-help' => '此低流量的邮件列表仅用于发行公告，其中包括重要安全公告。请订阅该列表以便在新的版本推出时升级您的MediaWiki。',
	'config-almost-done' => '您几乎已经完成了！现在您可以跳过剩下的配置流程并立即安装wiki。',
	'config-optional-continue' => '多问我一些问题吧。',
	'config-optional-skip' => '我已经不耐烦了，赶紧安装我的wiki。',
	'config-profile' => '用户权限配置：',
	'config-profile-wiki' => '传统wiki',
	'config-profile-no-anon' => '需要注册帐号',
	'config-profile-fishbowl' => '编辑受限',
	'config-profile-private' => '非公开wiki',
	'config-profile-help' => "如果您允许尽量多的人编写wiki，网站上的内容会更加丰富。在MediaWiki中，您可以轻松地审查最近更改，并轻易回退掉新手或破坏者造成的损害。

然而，许多人觉得让MediaWiki存在多种角色将更加好用；同时，要说服所有人都愿以wiki的方式作贡献并非一件易事。因此，您可以有以下选择：

'''{{int:config-profile-wiki}}'''允许包括未登录用户在内的所有人编辑。'''{{int:config-profile-no-anon}}'''的wiki需要额外的注册流程，这有可能会阻碍随意贡献者。

'''{{int:config-profile-fishbowl}}'''模式只允许获批准的用户编辑，但对公众开放页面浏览（包括历史记录）。'''{{int:config-profile-private}}'''则只允许获批准的用户浏览、编辑页面。

安装完成后，您还可以对用户权限进行更多、更复杂的配置，参见[http://www.mediawiki.org/wiki/Manual:User_rights 相关的使用手册]。",
	'config-license' => '版权和许可证：',
	'config-license-none' => '页脚无许可证',
	'config-license-cc-by-sa' => '知识共享署名-相同方式分享',
	'config-license-cc-by-nc-sa' => '知识共享署名-非商业性使用-相同方式共享',
	'config-license-gfdl-old' => 'GNU自由文档许可证 1.2',
	'config-license-gfdl-current' => 'GNU自由文档许可证 1.3或更高版本',
	'config-license-pd' => '公有领域',
	'config-license-cc-choose' => '选择自定义的知识共享许可证',
	'config-license-help' => "许多公共wiki会以[http://freedomdefined.org/Definition 自由许可证]的方式释放出编者的所有贡献。这有助于构建社区的主人翁意识，并能鼓励长期贡献。对于非公共wiki或公司wiki，这并非必要条件。

如果您希望使用来自维基百科的内容，并希望维基百科能接受复制自您的wiki的内容，请选择'''知识共享署名-相同方式共享'''。

GNU自由文档许可证是维基百科曾经使用过的许可证，并迄今有效。然而，该许可证的一些特性会增加重用或演绎内容的难度。",
	'config-email-settings' => '电子邮件设置',
	'config-enable-email' => '启用出站电子邮件',
	'config-enable-email-help' => '如果您希望使用电子邮件功能，请正确配置[http://www.php.net/manual/en/mail.configuration.php PHP的邮件设定]。如果您不需要任何电子邮件功能，请在此处禁用它。',
	'config-email-user' => '启用用户到用户的电子邮件',
	'config-email-user-help' => '允许所有用户互发邮件，假若他们启用了该功能。',
	'config-email-usertalk' => '启用用户讨论页通知',
	'config-email-usertalk-help' => '允许用户收到用户讨论页被修改的通知，假若他们启用了该功能。',
	'config-email-watchlist' => '启用监视列表通知',
	'config-email-watchlist-help' => '允许用户收到与其监视列表有关的通知，假若他们启用了该功能。',
	'config-email-auth' => '启用电子邮件身份验证',
	'config-email-auth-help' => "如果启用此选项，在用户设置或修改电子邮件地址时，就会收到一封邮件，内含确认电子地址的链接。只有经过身份验证的电子邮件地址，才能收到来自其他用户的电子邮件，或任何修改通知的邮件。'''建议'''公开wiki启用本选项，以防对电子邮件功能的滥用。",
	'config-email-sender' => '回复电子邮件地址：',
	'config-email-sender-help' => '输入要用来发送出站电子邮件的地址，该地址将会收到被拒收的邮件。许多邮件服务器要求域名部分必须有效。',
	'config-upload-settings' => '图像和文件上传',
	'config-upload-enable' => '启用文件上传',
	'config-upload-help' => '文件上传可能会将您的服务器暴露在安全风险下。有关更多的信息，请参阅手册的[http://www.mediawiki.org/wiki/Manual:Security 安全部分]。

要启用文件上传，请先将MediaWiki根目录下的<code>images</code>子目录更改为对web服务器可写，然后再启用此选项。',
	'config-upload-deleted' => '已删除文件的目录：',
	'config-upload-deleted-help' => '指定用于存放被删除文件的目录。理想情况下，该目录不应能通过web访问。',
	'config-logo' => '标志URL：',
	'config-logo-help' => '在MediaWiki的默认外观中，左上角部位有一块135x160像素的区域可用于展示站点的标志。请上传一幅相应大小的图像，并在此输入URL。

如果您不希望使用标志，请将本处留空。',
	'config-instantcommons' => '启用即时共享资源',
	'config-instantcommons-help' => '[http://www.mediawiki.org/wiki/InstantCommons 即时共享资源]可以让wiki使用来自[http://commons.wikimedia.org/ 维基共享资源]网站的图像、音频和其他媒体文件。要启用该功能，MediaWiki必须能够访问互联网。

有关此功能的详细信息，包括如何将其他wiki网站设为具有类似共享功能的方法，请参考[http://mediawiki.org/wiki/Manual:$wgForeignFileRepos 手册]。',
	'config-cc-error' => '知识共享许可证挑选器无法找到结果，请手动输入许可证的名称。',
	'config-cc-again' => '重新挑选……',
	'config-cc-not-chosen' => '选择您希望使用的知识共享许可证，并点击“继续”。',
	'config-advanced-settings' => '高级设置',
	'config-cache-options' => '对象缓存设置：',
	'config-cache-help' => '对象缓存可通过缓存频繁使用的数据来提高MediaWiki的速度。高度推荐中到大型的网站启用该功能，小型网站亦能从其中受益。',
	'config-cache-none' => '无缓存（不影响功能，但对较大型的wiki网站会有速度影响）',
	'config-cache-accel' => 'PHP对象缓存（APC、eAccelerator、XCache或WinCache）',
	'config-cache-memcached' => '使用Memcached（需要另外安装并配置）',
	'config-memcached-servers' => 'Memcached服务器：',
	'config-memcached-help' => '用于Memcached的IP地址列表。请以半角逗号分割，并指定要使用的端口（例如：127.0.0.1:11211, 192.168.1.25:11211）。',
	'config-extensions' => '扩展',
	'config-extensions-help' => '已在您的<code>./extensions</code>目录中发现下列扩展。

您可能要对它们进行额外的配置，但您现在可以启用它们。',
	'config-install-alreadydone' => "'''警告：'''您似乎已经安装了MediaWiki，并试图重新安装它。请前往下一个页面。",
	'config-install-begin' => '点击继续后，您将开始安装MediaWiki。如果您还想对配置作一些修改，请点击后退。',
	'config-install-step-done' => '完成',
	'config-install-step-failed' => '失败',
	'config-install-extensions' => '正在启用扩展',
	'config-install-database' => '正在配置数据库',
	'config-install-pg-schema-not-exist' => 'PostgreSQL 架构不存在',
	'config-install-pg-schema-failed' => '创建数据表失败。请确保用户“$1”拥有写入模式“$2”的权限。',
	'config-install-pg-commit' => '正在提交更改',
	'config-install-pg-plpgsql' => '正在检查PL/pgSQL语言',
	'config-pg-no-plpgsql' => '您需要为数据库$1安装PL/pgSQL语言',
	'config-pg-no-create-privs' => '为安装程序指定的帐号缺少创建帐号的权限。',
	'config-install-user' => '正在创建数据库用户',
	'config-install-user-grant-failed' => '授予用户“$1”权限失败：$2',
	'config-install-tables' => '正在创建数据表',
	'config-install-tables-exist' => "'''警告'''：MediaWiki的数据表似乎已经存在，跳过创建。",
	'config-install-tables-failed' => "'''错误'''：创建数据表出错，下为错误信息：$1",
	'config-install-interwiki' => '正在填充默认的跨wiki数据表',
	'config-install-interwiki-list' => '找不到文件<code>interwiki.list</code>。',
	'config-install-interwiki-exists' => "'''警告'''：跨wiki数据表似乎已有内容，跳过默认列表。",
	'config-install-stats' => '初始化统计',
	'config-install-keys' => '正在生成密钥',
	'config-install-sysop' => '正在创建管理员用户帐号',
	'config-install-subscribe-fail' => '无法订阅mediawiki-announce',
	'config-install-mainpage' => '正在创建显示默认内容的首页',
	'config-install-extension-tables' => '正在为已启用扩展创建数据表',
	'config-install-mainpage-failed' => '无法插入首页:$1',
	'config-install-done' => "'''恭喜！'''
您已经成功地安装了MediaWiki。

安装程序已经生成了<code>LocalSettings.php</code>文件，其中包含了您所有的配置。

您需要下载该文件，并将其放在您wiki的根目录（index.php的同级目录）中。稍后下载将自动开始。

如果浏览器没有提示您下载，或者您取消了下载，您可以点击下面的链接重新开始下载：

$3

'''注意'''：如果您现在不完成本步骤，而是没有下载便退出了安装过程，此后您将无法获得自动生成的配置文件。

当本步骤完成后，您可以 '''[$2 进入您的wiki]'''。",
	'config-download-localsettings' => '下载LocalSettings.php',
	'config-help' => '帮助',
);

/** Traditional Chinese (‪中文(繁體)‬)
 * @author Mark85296341
 */
$messages['zh-hant'] = array(
	'config-information' => '資訊',
	'config-your-language' => '您的語言：',
	'config-your-language-help' => '選擇一個要使用的語言在安裝過程中。',
	'config-wiki-language' => 'Wiki 語言：',
	'config-back' => '←返回',
	'config-continue' => '繼續→',
	'config-page-language' => '語言',
	'config-page-welcome' => '歡迎您來到 MediaWiki！',
	'config-page-dbconnect' => '連接到資料庫',
	'config-page-upgrade' => '升級現有的安裝',
	'config-page-dbsettings' => '資料庫設定',
	'config-page-name' => '名稱',
	'config-page-options' => '選項',
	'config-page-install' => '安裝',
	'config-page-complete' => '完成！',
	'config-page-restart' => '重新安裝',
	'config-page-readme' => '讀我',
	'config-page-copying' => '複製',
	'config-page-upgradedoc' => '升級',
	'config-restart' => '是的，重新啟動',
	'config-db-type' => '資料庫類型：',
	'config-db-host' => '資料庫主機：',
	'config-db-host-oracle' => '資料庫的 TNS：',
	'config-db-wiki-settings' => '識別這個 Wiki',
	'config-db-name' => '資料庫名稱：',
	'config-db-name-oracle' => '資料庫架構：',
	'config-db-username' => '資料庫使用者名稱：',
	'config-db-password' => '資料庫密碼：',
	'config-sqlite-dir' => 'SQLite 的資料目錄：',
	'config-header-mysql' => 'MySQL 的設定',
	'config-header-sqlite' => 'SQLite 的設定',
	'config-header-oracle' => '甲骨文設定',
	'config-invalid-db-type' => '無效的資料庫類型',
	'config-db-web-create' => '建立帳號，如果它不存在',
	'config-mysql-charset' => '資料庫字符集：',
	'config-mysql-utf8' => 'UTF-8',
	'config-site-name-blank' => '輸入站點名稱。',
	'config-ns-other' => '其他（請註明）',
	'config-admin-password' => '密碼：',
	'config-admin-password-confirm' => '再次輸入密碼：',
	'config-admin-name-blank' => '輸入管理員的使用者名稱。',
	'config-admin-password-blank' => '輸入管理員帳號密碼。',
	'config-admin-password-same' => '密碼不能與使用者名稱相同。',
	'config-admin-email' => 'E-mail 地址：',
	'config-admin-error-bademail' => '你輸入了一個無效的電子郵件地址。',
	'config-license' => '版權和許可證：',
	'config-license-pd' => '公共領域',
	'config-email-settings' => 'E-mail 設定',
	'config-email-auth' => '啟用電子郵件認證',
	'config-email-sender' => '返回電子郵件地址：',
	'config-upload-settings' => '圖片和檔案上傳',
	'config-upload-enable' => '啟用檔案上傳',
	'config-cc-again' => '重新選取......',
	'config-advanced-settings' => '進階配置',
	'config-extensions' => '擴充套件',
	'config-install-step-done' => '完成',
	'config-install-step-failed' => '失敗',
	'config-install-pg-commit' => '提交更改',
	'config-help' => '說明',
);

