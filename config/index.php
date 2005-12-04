<?php
# MediaWiki web-based config/installation
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

error_reporting( E_ALL );
header( "Content-type: text/html; charset=utf-8" );
@ini_set( "display_errors", true );

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,nofollow">
	<title>MediaWiki installation</title>
	<style type="text/css">
	#credit {
		float: right;
		width: 200px;
		font-size: 0.7em;
		background-color: #eee;
		color: black;
		border: solid 1px #444;
		padding: 8px;
		margin-left: 8px;
	}

	dl.setup dd {
		margin-left: 0;
	}
	dl.setup dd label.column {
		clear: left;
		font-weight: bold;
		width: 12em;
		float: left;
		text-align: right;
		padding-right: 1em;
	}
	dl.setup dt {
		clear: left;
		font-size: 0.8em;
		margin-left: 10em;
		/* margin-right: 200px; */
		margin-bottom: 2em;
	}
	.error {
		color: red;
	}
	ul.plain {
		list-style: none;
		clear: both;
		margin-left: 12em;
	}
	</style>
</head>

<body>

<div id="credit">
 <center>
  <a href="http://www.mediawiki.org/">
   <img src="../skins/common/images/mediawiki.png" width="135" height="135" alt="" border="0" />
  </a>
 </center>

 <p><strong><a href="http://www.mediawiki.org/">MediaWiki</a></strong> is
 Copyright (C) 2001-2005 by Magnus Manske, Brion Vibber, Lee Daniel Crocker,
 Tim Starling, Erik M&ouml;ller, Gabriel Wicke and others.</p>

 <ul>
  <li><a href="../README">Readme</a></li>
  <li><a href="../RELEASE-NOTES">Release notes</a></li>
  <li><a href="../docs/">doc/</a></li>
  <li><a href="http://meta.wikipedia.org/wiki/MediaWiki_User's_Guide">User's Guide</a></li>
 </ul>

 <p>This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.</p>

 <p>This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.</p>

 <p>You should have received <a href="../COPYING">a copy of the GNU General Public License</a>
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 or <a href="http://www.gnu.org/copyleft/gpl.html">read it online</a></p>
</div>

<?php

# Relative includes seem to break if a parent directory is not readable;
# this is common for public_html subdirs under user home directories.
#
# As a dirty hack, we'll try to set up the include path first.
#
$IP = dirname( dirname( __FILE__ ) );
$sep = (DIRECTORY_SEPARATOR == "\\") ? ";" : ":";
ini_set( "include_path", ".$sep$IP$sep$IP/includes$sep$IP/languages" );

define( "MEDIAWIKI", true );
define( "MEDIAWIKI_INSTALL", true );
require_once( "includes/Defines.php" );
require_once( "includes/DefaultSettings.php" );
require_once( "includes/MagicWord.php" );
require_once( "includes/Namespace.php" );
?>

<h1>MediaWiki <?php print $wgVersion ?> installation</h1>


<?php

/* Check for existing configurations and bug out! */

if( file_exists( "../LocalSettings.php" ) ) {
	dieout( "<h2>Wiki is configured.</h2>

	<p>Already configured... <a href='../index.php'>return to the wiki</a>.</p>

	<p>(You should probably remove this directory for added security.)</p>" );
}

if( file_exists( "./LocalSettings.php" ) ) {
	writeSuccessMessage();

	dieout( '' );
}

if( !is_writable( "." ) ) {
	dieout( "<h2>Can't write config file, aborting</h2>

	<p>In order to configure the wiki you have to make the <tt>config</tt> subdirectory
	writable by the web server. Once configuration is done you'll move the created
	<tt>LocalSettings.php</tt> to the parent directory, and for added safety you can
	then remove the <tt>config</tt> subdirectory entirely.</p>

	<p>To make the directory writable on a Unix/Linux system:</p>

	<pre>
	cd <i>/path/to/wiki</i>
	chmod a+w config
	</pre>" );
}


require_once( "install-utils.inc" );
require_once( "maintenance/updaters.inc" );

class ConfigData {
	function getEncoded( $data ) {
		# removing latin1 support, no need...
		return $data;
	}
	function getSitename() { return $this->getEncoded( $this->Sitename ); }
	function getSysopName() { return $this->getEncoded( $this->SysopName ); }
	function getSysopPass() { return $this->getEncoded( $this->SysopPass ); }
}

?>

<p><em>Please include all of the lines below when reporting installation problems.</em></p>

<h2>Checking environment...</h2>
<ul>
<?php
$endl = "
";
$wgNoOutputBuffer = true;
$conf = new ConfigData;

install_version_checks();

print "<li>PHP " . phpversion() . ": ok</li>\n";

if( ini_get( "register_globals" ) ) {
	?>
	<li><b class='error'>Warning:</b> <strong>PHP's
	<tt><a href="http://php.net/register_globals">register_globals</a></tt>
	option is enabled.</strong> MediaWiki will work correctly, but this setting
	increases your exposure to potential security vulnerabilities in PHP-based
	software running on your server. <strong>You should disable it if you are able.</strong></li>
	<?php
}

$fatal = false;

if( ini_get( "magic_quotes_runtime" ) ) {
	$fatal = true;
	?><li class='error'><strong>Fatal: <a href='http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-runtime'>magic_quotes_runtime</a> is active!</strong>
	This option corrupts data input unpredictably; you cannot install or use
	MediaWiki unless this option is disabled.
	<?php
}

if( ini_get( "magic_quotes_sybase" ) ) {
	$fatal = true;
	?><li class='error'><strong>Fatal: <a href='http://www.php.net/manual/en/ref.sybase.php#ini.magic-quotes-sybase'>magic_quotes_sybase</a> is active!</strong>
	This option corrupts data input unpredictably; you cannot install or use
	MediaWiki unless this option is disabled.
	<?php
}

if( ini_get( "mbstring.func_overload" ) ) {
	$fatal = true;
	?><li class='error'><strong>Fatal: <a href='http://www.php.net/manual/en/ref.mbstring.php#mbstring.overload'>mbstring.func_overload</a> is active!</strong>
	This option causes errors and may corrupt data unpredictably;
	you cannot install or use MediaWiki unless this option is disabled.
	<?php
}

if( $fatal ) {
	dieout( "</ul><p>Cannot install wiki.</p>" );
}

if( ini_get( "safe_mode" ) ) {
	$conf->safeMode = true;
	?>
	<li><b class='error'>Warning:</b> <strong>PHP's
	<a href='http://www.php.net/features.safe-mode'>safe mode</a> is active.</strong>
	You may have problems caused by this, particularly if using image uploads.
	</li>
	<?php
} else {
	$conf->safeMode = false;
}


$sapi = php_sapi_name();
$conf->prettyURLs = true;
print "<li>PHP server API is $sapi; ";
switch( $sapi ) {
case "apache":
case "apache2handler":
	print "ok, using pretty URLs (<tt>index.php/Page_Title</tt>)";
	break;
case "cgi":
case "cgi-fcgi":
case "apache2filter":
	print "using ugly URLs (<tt>index.php?title=Page_Title</tt>)";
	$conf->prettyURLs = false;
	break;
default:
	print "unknown; using pretty URLs (<tt>index.php/Page_Title</tt>), if you have trouble change this in <tt>LocalSettings.php</tt>";
}
print "</li>\n";

$conf->xml = function_exists( "utf8_encode" );
if( $conf->xml ) {
	print "<li>Have XML / Latin1-UTF-8 conversion support.</li>\n";
} else {
	dieout( "PHP's XML module is missing; the wiki requires functions in
		this module and won't work in this configuration.
		If you're running Mandrake, install the php-xml package." );
}

$memlimit = ini_get( "memory_limit" );
$conf->raiseMemory = false;
if( empty( $memlimit ) || $memlimit == -1 ) {
	print "<li>PHP is configured with no <tt>memory_limit</tt>.</li>\n";
} else {
	print "<li>PHP's <tt>memory_limit</tt> is " . htmlspecialchars( $memlimit ) . ". <strong>If this is too low, installation may fail!</strong> ";
	$n = IntVal( $memlimit );
	if( preg_match( '/^([0-9]+)[Mm]$/', trim( $memlimit ), $m ) ) {
		$n = IntVal( $m[1] * (1024*1024) );
	}
	if( $n < 20*1024*1024 ) {
		print "Attempting to raise limit to 20M... ";
		if( false === ini_set( "memory_limit", "20M" ) ) {
			print "failed.";
		} else {
			$conf->raiseMemory = true;
			print "ok.";
		}
	}
	print "</li>\n";
}

$conf->zlib = function_exists( "gzencode" );
if( $conf->zlib ) {
	print "<li>Have zlib support; enabling output compression.</li>\n";
} else {
	print "<li>No zlib support.</li>\n";
}

$conf->turck = function_exists( 'mmcache_get' );
if ( $conf->turck ) {
	print "<li><a href=\"http://turck-mmcache.sourceforge.net/\">Turck MMCache</a> installed</li>\n";
}
$conf->eaccel = function_exists( 'eaccelerator_get' );
if ( $conf->eaccel ) {
    $conf->turck = 'eaccelerator';
    print "<li><a href=\"http://eaccelerator.sourceforge.net/\">eAccelerator</a> installed</li>\n";
}
if (!$conf->turck && !$conf->eaccel) {
	print "<li>Neither <a href=\"http://turck-mmcache.sourceforge.net/\">Turck MMCache</a> nor <a href=\"http://eaccelerator.sourceforge.net/\">eAccelerator</a> are installed, " .
	  "can't use object caching functions</li>\n";
}

$conf->diff3 = false;
$diff3locations = array("/usr/bin", "/opt/csw/bin", "/usr/gnu/bin", "/usr/sfw/bin") + explode(":", getenv("PATH"));
$diff3names = array("gdiff3", "diff3");

$diff3versioninfo = array('$1 --version 2>&1', 'diff3 (GNU diffutils)');
foreach ($diff3locations as $loc) {
	$exe = locate_executable($loc, $diff3names, $diff3versioninfo);
	if ($exe !== false) {
		$conf->diff3 = $exe;
		break;
	}
}

if ($conf->diff3)
	print "<li>Found GNU diff3: <tt>$conf->diff3</tt>.</li>";
else
	print "<li>GNU diff3 not found.</li>";

$conf->ImageMagick = false;
$imcheck = array( "/usr/bin", "/usr/local/bin", "/sw/bin", "/opt/local/bin" );
foreach( $imcheck as $dir ) {
	$im = "$dir/convert";
	if( file_exists( $im ) ) {
		print "<li>Found ImageMagick: <tt>$im</tt>; image thumbnailing will be enabled if you enable uploads.</li>\n";
		$conf->ImageMagick = $im;
		break;
	}
}

$conf->HaveGD = function_exists( "imagejpeg" );
if( $conf->HaveGD ) {
	print "<li>Found GD graphics library built-in";
	if( !$conf->ImageMagick ) {
		print ", image thumbnailing will be enabled if you enable uploads";
	}
	print ".</li>\n";
} else {
	if( !$conf->ImageMagick ) {
		print "<li>Couldn't find GD library or ImageMagick; image thumbnailing disabled.</li>\n";
	}
}

$conf->UseImageResize = $conf->HaveGD || $conf->ImageMagick;

# $conf->IP = "/Users/brion/Sites/inplace";
$conf->IP = dirname( dirname( __FILE__ ) );
print "<li>Installation directory: <tt>" . htmlspecialchars( $conf->IP ) . "</tt></li>\n";

# $conf->ScriptPath = "/~brion/inplace";
$conf->ScriptPath = preg_replace( '{^(.*)/config.*$}', '$1', $_SERVER["PHP_SELF"] ); # was SCRIPT_NAME
print "<li>Script URI path: <tt>" . htmlspecialchars( $conf->ScriptPath ) . "</tt></li>\n";

	$conf->posted = ($_SERVER["REQUEST_METHOD"] == "POST");

	$conf->Sitename = ucfirst( importPost( "Sitename", "" ) );
	$defaultEmail = empty( $_SERVER["SERVER_ADMIN"] )
		? 'root@localhost'
		: $_SERVER["SERVER_ADMIN"];
	$conf->EmergencyContact = importPost( "EmergencyContact", $defaultEmail );
	$conf->DBserver = importPost( "DBserver", "localhost" );
	$conf->DBname = importPost( "DBname", "wikidb" );
	$conf->DBuser = importPost( "DBuser", "wikiuser" );
	$conf->DBpassword = importPost( "DBpassword" );
	$conf->DBpassword2 = importPost( "DBpassword2" );
	$conf->DBprefix = importPost( "DBprefix" );
	$conf->DBmysql5 = (importPost( "DBmysql5" ) == "true") ? "true" : "false";
	$conf->RootPW = importPost( "RootPW" );
	$conf->LanguageCode = importPost( "LanguageCode", "en" );
	$conf->SysopName = importPost( "SysopName", "WikiSysop" );
	$conf->SysopPass = importPost( "SysopPass" );
	$conf->SysopPass2 = importPost( "SysopPass2" );

/* Check for validity */
$errs = array();

if( $conf->Sitename == "" || $conf->Sitename == "MediaWiki" || $conf->Sitename == "Mediawiki" ) {
	$errs["Sitename"] = "Must not be blank or \"MediaWiki\".";
}
if( $conf->DBuser == "" ) {
	$errs["DBuser"] = "Must not be blank";
}
if( $conf->DBpassword == "" ) {
	$errs["DBpassword"] = "Must not be blank";
}
if( $conf->DBpassword != $conf->DBpassword2 ) {
	$errs["DBpassword2"] = "Passwords don't match!";
}
if( !preg_match( '/^[A-Za-z_0-9]*$/', $conf->DBprefix ) ) {
	$errs["DBprefix"] = "Invalid table prefix";
}

if( $conf->SysopPass == "" ) {
	$errs["SysopPass"] = "Must not be blank";
}
if( $conf->SysopPass != $conf->SysopPass2 ) {
	$errs["SysopPass2"] = "Passwords don't match!";
}

$conf->License = importRequest( "License", "none" );
if( $conf->License == "gfdl" ) {
	$conf->RightsUrl = "http://www.gnu.org/copyleft/fdl.html";
	$conf->RightsText = "GNU Free Documentation License 1.2";
	$conf->RightsCode = "gfdl";
	$conf->RightsIcon = '${wgStylePath}/common/images/gnu-fdl.png';
} elseif( $conf->License == "none" ) {
	$conf->RightsUrl = $conf->RightsText = $conf->RightsCode = $conf->RightsIcon = "";
} else {
	$conf->RightsUrl = importRequest( "RightsUrl", "" );
	$conf->RightsText = importRequest( "RightsText", "" );
	$conf->RightsCode = importRequest( "RightsCode", "" );
	$conf->RightsIcon = importRequest( "RightsIcon", "" );
}

$conf->Shm = importRequest( "Shm", "none" );
$conf->MCServers = importRequest( "MCServers" );

/* Test memcached servers */

if ( $conf->Shm == 'memcached' && $conf->MCServers ) {
	$conf->MCServerArray = array_map( 'trim', explode( ',', $conf->MCServers ) );
	foreach ( $conf->MCServerArray as $server ) {
		$error = testMemcachedServer( $server );
		if ( $error ) {
			$errs["MCServers"] = $error;
			break;
		}
	}
} else if ( $conf->Shm == 'memcached' ) {
	$errs["MCServers"] = "Please specify at least one server if you wish to use memcached";
}

/* default values for installation */
$conf->Email	=importRequest("Email", "email_enabled");
$conf->Emailuser=importRequest("Emailuser", "emailuser_enabled");
$conf->Enotif	=importRequest("Enotif", "enotif_allpages");
$conf->Eauthent	=importRequest("Eauthent", "eauthent_enabled");

if( $conf->posted && ( 0 == count( $errs ) ) ) {
	do { /* So we can 'continue' to end prematurely */
		$conf->Root = ($conf->RootPW != "");

		/* Load up the settings and get installin' */
		$local = writeLocalSettings( $conf );
		$wgCommandLineMode = false;
		chdir( ".." );
		eval($local);
		$wgDBadminuser = "root";
		$wgDBadminpassword = $conf->RootPW;
		$wgDBprefix = $conf->DBprefix;
		$wgCommandLineMode = true;
		$wgUseDatabaseMessages = false;	/* FIXME: For database failure */
		require_once( "includes/Setup.php" );
		chdir( "config" );

		require_once( "maintenance/InitialiseMessages.inc" );

		$wgTitle = Title::newFromText( "Installation script" );
		$mysqlOldClient = version_compare( mysql_get_client_info(), "4.1.0", "lt" );
		if( $mysqlOldClient ) {
			print "<li><b>PHP is linked with old MySQL client libraries. If you are
				using a MySQL 4.1 server and have problems connecting to the database,
				see <a href='http://dev.mysql.com/doc/mysql/en/old-client.html'
			 	>http://dev.mysql.com/doc/mysql/en/old-client.html</a> for help.</b></li>\n";
		}
		print "<li>Trying to connect to MySQL on $wgDBserver as root...\n";
		$wgDatabase = Database::newFromParams( $wgDBserver, "root", $conf->RootPW, "", 1 );

		if( $wgDatabase->isOpen() ) {
			$myver = mysql_get_server_info( $wgDatabase->mConn );
			$wgDatabase->ignoreErrors(true);
			$conf->Root = true;
			print "<ul><li>Connected as root (automatic)</li></ul></li>\n";
		} else {
			print "<ul><li>MySQL error " . ($err = mysql_errno() ) .
				": " . htmlspecialchars( mysql_error() ) . "</li></ul></li>";
			$ok = false;
			switch( $err ) {
			case 1045:
			case 2000:
				if( $conf->Root ) {
					$errs["RootPW"] = "Check password";
				} else {
					print "<li>Trying regular user...\n";
					/* Try the regular user... */
					$wgDBadminuser = $wgDBuser;
					$wgDBadminpassword = $wgDBpassword;
					/* Wait one second for connection rate limiting, present on some systems */
					sleep(1);
					$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, "", 1 );
					if( !$wgDatabase->isOpen() ) {
						print "<ul><li>MySQL error " . ($err = mysql_errno() ) .
							": " . htmlspecialchars( mysql_error() ) . "</li></ul></li>";
						$errs["DBuser"] = "Check name/pass";
						$errs["DBpassword"] = "or enter root";
						$errs["DBpassword2"] = "password below";
						$errs["RootPW"] = "Got root?";
					} else {
						$myver = mysql_get_server_info( $wgDatabase->mConn );
						$wgDatabase->ignoreErrors(true);
						$conf->Root = false;
						$conf->RootPW = "";
						print " ok.</li>\n";
						# And keep going...
						$ok = true;
					}
					break;
				}
			case 2002:
			case 2003:
				$errs["DBserver"] = "Connection failed";
				break;
			default:
				$errs["DBserver"] = "Couldn't connect to database ($err)";
				break;
			}
			if( !$ok ) continue;
		}

		if ( !$wgDatabase->isOpen() ) {
			$errs["DBserver"] = "Couldn't connect to database";
			continue;
		}

		print "<li>Connected to $myver";
		if( version_compare( $myver, "4.0.0" ) >= 0 ) {
			print "; enabling MySQL 4 enhancements";
			$conf->DBmysql4 = true;
			$local = writeLocalSettings( $conf );
		}
		$mysqlNewAuth   = version_compare( $myver, "4.1.0", "ge" );
		if( $mysqlNewAuth && $mysqlOldClient ) {
			print "; <b class='error'>You are using MySQL 4.1 server, but PHP is linked
			 	to old client libraries; if you have trouble with authentication, see
			 	<a href='http://dev.mysql.com/doc/mysql/en/old-client.html'
			 	>http://dev.mysql.com/doc/mysql/en/old-client.html</a> for help.</b>";
		}
		if( $wgDBmysql5 ) {
			if( $mysqlNewAuth ) {
				print "; enabling MySQL 4.1/5.0 charset mode";
			} else {
				print "; <b class='error'>MySQL 4.1/5.0 charset mode enabled,
					but older version detected; will likely fail.</b>";
			}
		}
		print "</li>\n";

		@$sel = mysql_select_db( $wgDBname, $wgDatabase->mConn );
		if( $sel ) {
			print "<li>Database <tt>" . htmlspecialchars( $wgDBname ) . "</tt> exists</li>\n";
		} else {
			$err = mysql_errno();
			if ( $err != 1049 ) {
				print "<ul><li>Error selecting database $wgDBname: $err " . htmlspecialchars( mysql_error() ) .
					"</li></ul>";
				continue;
			}
			$res = $wgDatabase->query( "CREATE DATABASE `$wgDBname`" );
			if( !$res ) {
				print "<li>Couldn't create database <tt>" .
					htmlspecialchars( $wgDBname ) .
					"</tt>; try with root access or check your username/pass.</li>\n";
				$errs["RootPW"] = "&lt;- Enter";
				continue;
			}
			print "<li>Created database <tt>" . htmlspecialchars( $wgDBname ) . "</tt></li>\n";
		}

		$wgDatabase->selectDB( $wgDBname );

		if( $wgDatabase->tableExists( "cur" ) || $wgDatabase->tableExists( "revision" ) ) {
			print "<li>There are already MediaWiki tables in this database. Checking if updates are needed...</li>\n";

			# Create user if required
			if ( $conf->Root ) {
				$conn = Database::newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 1 );
				if ( $conn->isOpen() ) {
					print "<li>DB user account ok</li>\n";
					$conn->close();
				} else {
					print "<li>Granting user permissions...";
					if( $mysqlOldClient && $mysqlNewAuth ) {
						print " <b class='error'>If the next step fails, see <a href='http://dev.mysql.com/doc/mysql/en/old-client.html'>http://dev.mysql.com/doc/mysql/en/old-client.html</a> for help.</b>";
					}
					print "</li>\n";
					dbsource( "../maintenance/users.sql", $wgDatabase );
				}
			}
			print "<pre>\n";
			chdir( ".." );
			flush();
			do_all_updates();
			chdir( "config" );

			print "</pre>\n";
			print "<li>Finished update checks.</li>\n";
		} else {
			# FIXME: Check for errors
			print "<li>Creating tables...";
			if( $wgDBmysql5 ) {
				print " using MySQL 5 table defs...";
				dbsource( "../maintenance/mysql5/tables.sql", $wgDatabase );
			} else {
				print " using MySQL 3/4 table defs...";
				dbsource( "../maintenance/tables.sql", $wgDatabase );
			}
			dbsource( "../maintenance/interwiki.sql", $wgDatabase );
			print " done.</li>\n";

			print "<li>Initializing data...";
			$wgDatabase->insert( 'site_stats',
				array( 'ss_row_id'        => 1,
				       'ss_total_views'   => 0,
				       'ss_total_edits'   => 0,
				       'ss_good_articles' => 0 ) );
			# setting up the db user
			if( $conf->Root ) {
				print "<li>Granting user permissions...</li>\n";
				dbsource( "../maintenance/users.sql", $wgDatabase );
			}

			if( $conf->SysopName ) {
				$u = User::newFromName( $conf->getSysopName() );
				if ( 0 == $u->idForName() ) {
					$u->addToDatabase();
					$u->setPassword( $conf->getSysopPass() );
					$u->saveSettings();

					$u->addGroup( "sysop" );
					$u->addGroup( "bureaucrat" );

					print "<li>Created sysop account <tt>" .
						htmlspecialchars( $conf->SysopName ) . "</tt>.</li>\n";
				} else {
					print "<li>Could not create user - already exists!</li>\n";
				}
			} else {
				print "<li>Skipped sysop account creation, no name given.</li>\n";
			}

			$titleobj = Title::newFromText( wfMsgNoDB( "mainpage" ) );
			$article = new Article( $titleobj );
			$newid = $article->insertOn( $wgDatabase );
			$revision = new Revision( array(
				'page'      => $newid,
				'text'      => wfMsg( 'mainpagetext' ) . "\n\n" . wfMsg( 'mainpagedocfooter' ),
				'comment'   => '',
				'user'      => 0,
				'user_text' => 'MediaWiki default',
				) );
			$revid = $revision->insertOn( $wgDatabase );
			$article->updateRevisionOn( $wgDatabase, $revision );

			print "<li><pre>";
			initialiseMessages();
			print "</pre></li>\n";
		}

		/* Write out the config file now that all is well */
		print "<p>Creating LocalSettings.php...</p>\n\n";
		$localSettings =  "<" . "?php$endl$local$endl?" . ">";
		// Fix up a common line-ending problem (due to CVS on Windows)
		$localSettings = str_replace( "\r\n", "\n", $localSettings );

		if( version_compare( phpversion(), "4.3.2" ) >= 0 ) {
			$xt = "xt"; # Refuse to overwrite an existing file
		} else {
			$xt = "wt"; # 'x' is not available prior to PHP 4.3.2. We did check above, but race conditions blah blah
		}
		$f = fopen( "LocalSettings.php", $xt );

		if( $f == false ) {
			dieout( "<p>Couldn't write out LocalSettings.php. Check that the directory permissions are correct and that there isn't already a file of that name here...</p>\n" .
			"<p>Here's the file that would have been written, try to paste it into place manually:</p>\n" .
			"<pre>\n" . htmlspecialchars( $localSettings ) . "</pre>\n" );
		}
		if(fwrite( $f, $localSettings ) ) {
			fclose( $f );
			writeSuccessMessage();
		} else {
			fclose( $f );
			die("<p class='error'>An error occured while writing the config/LocalSettings.php file. Check user rights and disk space then try again.</p>\n");

		}

	} while( false );
}
?>
</ul>


<?php

if( count( $errs ) ) {
	/* Display options form */

	if( $conf->posted ) {
		echo "<p class='error'>Something's not quite right yet; make sure everything below is filled out correctly.</p>\n";
	}
?>

<form action="index.php" name="config" method="post">


<h2>Site config</h2>

<dl class="setup">
	<dd>
		<?php
		aField( $conf, "Sitename", "Site name:" );
		?>
	</dd>
	<dt>
		Your site name should be a relatively short word. It'll appear as the namespace
		name for 'meta' pages as well as throughout the user interface. Good site names
		are things like "<a href="http://www.wikipedia.org/">Wikipedia</a>" and
		"<a href="http://openfacts.berlios.de/">OpenFacts</a>"; avoid punctuation,
		which may cause problems.
	</dt>

	<dd>
		<?php
		aField( $conf, "EmergencyContact", "Contact e-mail" );
		?>
	</dd>
	<dt>
		This will be used as the return address for password reminders and
		may be displayed in some error conditions so visitors can get in
		touch with you. It is also be used as the default sender address of e-mail
		notifications (enotifs).
	</dt>

	<dd>
		<label class='column' for="LanguageCode">Language</label>
		<select id="LanguageCode" name="LanguageCode">

		<?php
			$list = getLanguageList();
			foreach( $list as $code => $name ) {
				$sel = ($code == $conf->LanguageCode) ? 'selected="selected"' : '';
				echo "\t\t<option value=\"$code\" $sel>$name</option>\n";
			}
		?>
		</select>
	</dd>
	<dt>
		You may select the language for the user interface of the wiki...
		Some localizations are less complete than others. Unicode (UTF-8 encoding)
		is used for all localizations.
	</dt>

	<dd>
		<label class='column'>Copyright/license metadata</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "License", "no license metadata", "radio", "none" ); ?></li>
		<li><?php aField( $conf, "License", "GNU Free Documentation License 1.2 (Wikipedia-compatible)", "radio", "gfdl" ); ?></li>
		<li><?php
			aField( $conf, "License", "a Creative Commons license...", "radio", "cc" );
			$partner = "MediaWiki";
			$exit = urlencode( "$wgServer{$conf->ScriptPath}/config/index.php?License=cc&RightsUrl=[license_url]&RightsText=[license_name]&RightsCode=[license_code]&RightsIcon=[license_button]" );
			$icon = urlencode( "$wgServer$wgUploadPath/wiki.png" );
			$ccApp = htmlspecialchars( "http://creativecommons.org/license/?partner=$partner&exit_url=$exit&partner_icon_url=$icon" );
			print "<a href=\"$ccApp\">choose</a>";
			?> (link will wipe out any other data in this form!)
		<?php if( $conf->License == "cc" ) { ?>
			<ul>
				<li><?php aField( $conf, "RightsIcon", "<img src=\"" . htmlspecialchars( $conf->RightsIcon ) . "\" alt='icon' />", "hidden" ); ?></li>
				<li><?php aField( $conf, "RightsText", htmlspecialchars( $conf->RightsText ), "hidden" ); ?></li>
				<li><?php aField( $conf, "RightsCode", "code: " . htmlspecialchars( $conf->RightsCode ), "hidden" ); ?></li>
				<li><?php aField( $conf, "RightsUrl", "<a href=\"" . htmlspecialchars( $conf->RightsUrl ) . "\">" . htmlspecialchars( $conf->RightsUrl ) . "</a>", "hidden" ); ?></li>
			</ul>
		<?php } ?>
			</li>
		</ul>
	</dd>
	<dt>
		MediaWiki can include a basic license notice, icon, and machine-readable
		copyright metadata if your wiki's content is to be licensed under
		the GNU FDL or a Creative Commons license. If you're not sure, leave
		it at "none".
	</dt>


	<dd>
		<?php aField( $conf, "SysopName", "Sysop account name:", "" ) ?>
	</dd>
	<dd>
		<?php aField( $conf, "SysopPass", "password:", "password" ) ?>
	</dd>
	<dd>
		<?php aField( $conf, "SysopPass2", "again:", "password" ) ?>
	</dd>
	<dt>
		A sysop user account can lock or delete pages, block problematic IP
		addresses from editing, and other maintenance tasks. If creating a new
		wiki database, a sysop account will be created with the given name
		and password.
	</dt>

	<dd>
		<label class='column'>Shared memory caching</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "Shm", "no caching", "radio", "none" ); ?></li>
		<?php
			if ( $conf->turck ) {
				echo "<li>";
				aField( $conf, "Shm", "Turck MMCache", "radio", "turck" );
				echo "</li>";
			}
		?>
		<?php
			if ( $conf->eaccel ) {
				echo "<li>";
				aField( $conf, "Shm", "eAccelerator", "radio", "eaccel" );
				echo "</li>";
			}
		?>
		<li><?php aField( $conf, "Shm", "Memcached", "radio", "memcached" ); ?></li>
		<li><?php aField( $conf, "MCServers", "Memcached servers", "" ) ?></li>
		</ul>
	</dd>
	<dt>
		Using a shared memory system such as Turck MMCache, eAccelerator, or Memcached will speed
		up MediaWiki significantly. Memcached is the best solution but needs to be
		installed. Specify the server addresses and ports in a comma-separted list. Only
		use Turck shared memory if the wiki will be running on a single Apache server.
	</dl>

<h2>E-mail, e-mail notification and authentication setup</h2>

<dl class="setup">
	<dd>
		<label class='column'>E-mail (general)</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "Email", "enabled", "radio", "email_enabled" ); ?></li>
		<li><?php aField( $conf, "Email", "disabled", "radio", "email_disabled" ); ?></li>
		</ul>
	</dd>
	<dt>
		Use this to disable all e-mail functions (send a password reminder, user-to-user e-mail and e-mail notification),
		if sending e-mails on your server doesn't work.
	</dt>
	<dd>
		<label class='column'>User-to-user e-mail</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "Emailuser", "enabled", "radio", "emailuser_enabled" ); ?></li>
		<li><?php aField( $conf, "Emailuser", "disabled", "radio", "emailuser_disabled" ); ?></li>
		</ul>
	</dd>
	<dt>
		Use this to disable only the user-to-user e-mail function (EmailUser).
	</dt>
	<dd>
		<label class='column'>E-mail notification</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "Enotif", "disabled", "radio", "enotif_disabled" ); ?></li>
		<li><?php aField( $conf, "Enotif", "enabled for changes of watch-listed and user_talk pages (recommended for small wikis; perhaps not suited for large wikis)", "radio", "enotif_allpages" ); ?></li>
		<li><?php aField( $conf, "Enotif", "enabled for changes of user_talk pages only (suited for small and large wikis)", "radio", "enotif_usertalk" ); ?></li>
		</ul>
	</dd>
	<dt>
		<p>
		E-mail notification sends a notification e-mail to a user, when the user_talk page is changed
                and/or when watch-listed pages are changed, depending on the above settings.
		When testing this feature, be reminded, that obviously an e-mail address must be present in your preferences
		and that your own changes never trigger notifications to be sent to yourself.</p>

		<p>Users get corresponding options to select or deselect in their users' preferences.
		The user options are not shown on the preference page, if e-mail notification is disabled.</p>

		<p>There are additional options for fine tuning in /includes/DefaultSettings.php .</p>
	</dt>

	<dd>
		<label class='column'>E-mail address authentication</label>
		<div>Select one:</div>

		<ul class="plain">
		<li><?php aField( $conf, "Eauthent", "disabled", "radio", "eauthent_disabled" ); ?></li>
		<li><?php aField( $conf, "Eauthent", "enabled", "radio", "eauthent_enabled" ); ?></li>
		</ul>
	</dd>
	<dt>
		<p>E-mail address authentication uses a scheme to authenticate e-mail addresses of the users. The user who initially enters or changes his/her stored e-mail address
		gets a link with a token mailed to that address. The stored e-mail address is authenticated at the moment the user comes back to the wiki via the link.</p>

		<p>The e-mail address stays authenticated as long as the user does not change it; the time of authentication is indicated
		on the user preference page.</p>

		<p>If the option is enabled, only authenticated e-mail addresses can receive EmailUser mails and/or
		e-mail notification mails.</p>
	</dt>

	</dl>

<h2>Database config</h2>

<dl class="setup">
	<dd><?php
		aField( $conf, "DBserver", "MySQL server" );
	?></dd>
	<dt>
		If your database server isn't on your web server, enter the name
		or IP address here.
	</dt>

	<dd><?php
		aField( $conf, "DBname", "Database name" );
	?></dd>
	<dd><?php
		aField( $conf, "DBuser", "DB username" );
	?></dd>
	<dd><?php
		aField( $conf, "DBpassword", "DB password", "password" );
	?></dd>
	<dd><?php
		aField( $conf, "DBpassword2", "again", "password" );
	?></dd>
	<dt>
		If you only have a single user account and database available,
		enter those here. If you have database root access (see below)
		you can specify new accounts/databases to be created.
	</dt>

	<dd><?php
		aField( $conf, "DBprefix", "Database table prefix" );
	?></dd>
	<dt>
		<p>If you need to share one database between multiple wikis, or
		MediaWiki and another web application, you may choose to
		add a prefix to all the table names to avoid conflicts.</p>

		<p>Avoid exotic characters; something like <tt>mw_</tt> is good.</p>
	</dt>
	
	<dd><label class="column">Database charset</label>
		<div>Select one:</div>
		<ul class="plain">
		<li><?php aField( $conf, "DBmysql5", "Backwards-compatible UTF-8", "radio", "false" ); ?></li>
		<li><?php aField( $conf, "DBmysql5", "Experimental MySQL 4.1/5.0 UTF-8", "radio", "true" ); ?></li>
		</ul>
	</dd>
	<dt>
		<b>EXPERIMENTAL:</b> You can enable explicit Unicode charset support
		for MySQL 4.1 and 5.0 servers. This is not well tested and may
		cause things to break. <b>If upgrading an older installation, leave
		in backwards-compatible mode.</b>
	</dt>

	<dd>
		<?php
		aField( $conf, "RootPW", "DB root password", "password" );
		?>
	</dd>
	<dt>
		You will only need this if the database and/or user account
		above don't already exist.
		Do <em>not</em> type in your machine's root password! MySQL
		has its own "root" user with a separate password. (It might
		even be blank, depending on your configuration.)
	</dt>

	<dd>
		<label class='column'>&nbsp;</label>
		<input type="submit" value="Install!" />
	</dd>
</dl>


</form>

<?php
}

/* -------------------------------------------------------------------------------------- */
function writeSuccessMessage() {
	global $conf;
	if ( ini_get( 'safe_mode' ) && !ini_get( 'open_basedir' ) ) {
		echo <<<EOT
<p>Installation successful!</p>
<p>To complete the installation, please do the following:
<ol>
	<li>Download config/LocalSettings.php with your FTP client or file manager</li>
	<li>Upload it to the parent directory</li>
	<li>Delete config/LocalSettings.php</li>
	<li>Start using <a href='../index.php'>your wiki</a>!
</ol>
<p>If you are in a shared hosting environment, do <strong>not</strong> just move LocalSettings.php
remotely. LocalSettings.php is currently owned by the user your webserver is running under,
which means that anyone on the same server can read your database password! Downloading
it and uploading it again will hopefully change the ownership to a user ID specific to you.</p>
EOT;
	} else {
		echo "<p>Installation successful! Move the config/LocalSettings.php file into the parent directory, then follow
			<a href='../index.php'>this link</a> to your wiki.</p>\n";
	}
}


function escapePhpString( $string ) {
	return strtr( $string,
		array(
			"\n" => "\\n",
			"\r" => "\\r",
			"\t" => "\\t",
			"\\" => "\\\\",
			"\$" => "\\\$",
			"\"" => "\\\""
		));
}

function writeLocalSettings( $conf ) {
	$conf->DBmysql4 = @$conf->DBmysql4 ? 'true' : 'false';
	$conf->UseImageResize = $conf->UseImageResize ? 'true' : 'false';
	$conf->PasswordSender = $conf->EmergencyContact;
	$zlib = ($conf->zlib ? "" : "# ");
	$magic = ($conf->ImageMagick ? "" : "# ");
	$convert = ($conf->ImageMagick ? $conf->ImageMagick : "/usr/bin/convert" );
	$pretty = ($conf->prettyURLs ? "" : "# ");
	$ugly = ($conf->prettyURLs ? "# " : "");
	$rights = ($conf->RightsUrl) ? "" : "# ";
	$hashedUploads = $conf->safeMode ? '' : '# ';

	switch ( $conf->Shm ) {
		case 'memcached':
			$cacheType = 'CACHE_MEMCACHED';
			$mcservers = var_export( $conf->MCServerArray, true );
			break;
		case 'turck':
		case 'eaccel':
			$cacheType = 'CACHE_ACCEL';
			$mcservers = 'array()';
			break;
		default:
			$cacheType = 'CACHE_NONE';
			$mcservers = 'array()';
	}

	if ( $conf->Email == 'email_enabled' ) {
		$enableemail = 'true';
		$enableuseremail = ( $conf->Emailuser == 'emailuser_enabled' ) ? 'true' : 'false' ;
		$eauthent = ( $conf->Eauthent == 'eauthent_enabled' ) ? 'true' : 'false' ;
		switch ( $conf->Enotif ) {
			case 'enotif_usertalk':
				$enotifusertalk = 'true';
				$enotifwatchlist = 'false';
				break;
			case 'enotif_allpages':
				$enotifusertalk = 'true';
				$enotifwatchlist = 'true';
				break;
			default:
				$enotifusertalk = 'false';
				$enotifwatchlist = 'false';
		}
	} else {
		$enableuseremail = 'false';
		$enableemail = 'false';
		$eauthent = 'false';
		$enotifusertalk = 'false';
		$enotifwatchlist = 'false';
	}

	$file = @fopen( "/dev/urandom", "r" );
	if ( $file ) {
		$secretKey = bin2hex( fread( $file, 32 ) );
		fclose( $file );
	} else {
		$secretKey = "";
		for ( $i=0; $i<8; $i++ ) {
			$secretKey .= dechex(mt_rand(0, 0x7fffffff));
		}
		print "<li>Warning: \$wgSecretKey key is insecure, generated with mt_rand(). Consider changing it manually.</li>\n";
	}

	# Add slashes to strings for double quoting
	$slconf = array_map( "escapePhpString", get_object_vars( $conf ) );
	if( $conf->License == 'gfdl' ) {
		# Needs literal string interpolation for the current style path
		$slconf['RightsIcon'] = $conf->RightsIcon;
	}

	$sep = (DIRECTORY_SEPARATOR == "\\") ? ";" : ":";
	$localsettings = "
# This file was automatically generated by the MediaWiki installer.
# If you make manual changes, please keep track in case you need to
# recreate them later.

\$IP = \"{$slconf['IP']}\";
ini_set( \"include_path\", \".$sep\$IP$sep\$IP/includes$sep\$IP/languages\" );
require_once( \"includes/DefaultSettings.php\" );

# If PHP's memory limit is very low, some operations may fail.
" . ($conf->raiseMemory ? '' : '# ' ) . "ini_set( 'memory_limit', '20M' );" . "

if ( \$wgCommandLineMode ) {
	if ( isset( \$_SERVER ) && array_key_exists( 'REQUEST_METHOD', \$_SERVER ) ) {
		die( \"This script must be run from the command line\\n\" );
	}
} elseif ( empty( \$wgNoOutputBuffer ) ) {
	## Compress output if the browser supports it
	{$zlib}if( !ini_get( 'zlib.output_compression' ) ) @ob_start( 'ob_gzhandler' );
}

\$wgSitename         = \"{$slconf['Sitename']}\";

\$wgScriptPath	    = \"{$slconf['ScriptPath']}\";
\$wgScript           = \"\$wgScriptPath/index.php\";
\$wgRedirectScript   = \"\$wgScriptPath/redirect.php\";

## If using PHP as a CGI module, use the ugly URLs
{$pretty}\$wgArticlePath      = \"\$wgScript/\$1\";
{$ugly}\$wgArticlePath      = \"\$wgScript?title=\$1\";

\$wgStylePath        = \"\$wgScriptPath/skins\";
\$wgStyleDirectory   = \"\$IP/skins\";
\$wgLogo             = \"\$wgStylePath/common/images/wiki.png\";

\$wgUploadPath       = \"\$wgScriptPath/images\";
\$wgUploadDirectory  = \"\$IP/images\";

\$wgEnableEmail = $enableemail;
\$wgEnableUserEmail = $enableuseremail;

\$wgEmergencyContact = \"{$slconf['EmergencyContact']}\";
\$wgPasswordSender	= \"{$slconf['PasswordSender']}\";

## For a detailed description of the following switches see
## http://meta.wikimedia.org/Enotif and http://meta.wikimedia.org/Eauthent
## There are many more options for fine tuning available see
## /includes/DefaultSettings.php
## UPO means: this is also a user preference option
\$wgEnotifUserTalk = $enotifusertalk; # UPO
\$wgEnotifWatchlist = $enotifwatchlist; # UPO
\$wgEmailAuthentication = $eauthent;

\$wgDBserver         = \"{$slconf['DBserver']}\";
\$wgDBname           = \"{$slconf['DBname']}\";
\$wgDBuser           = \"{$slconf['DBuser']}\";
\$wgDBpassword       = \"{$slconf['DBpassword']}\";
\$wgDBprefix         = \"{$slconf['DBprefix']}\";

# If you're on MySQL 3.x, this next line must be FALSE:
\$wgDBmysql4 = {$conf->DBmysql4};

# Experimental charset support for MySQL 4.1/5.0.
\$wgDBmysql5 = {$conf->DBmysql5};

## Shared memory settings
\$wgMainCacheType = $cacheType;
\$wgMemCachedServers = $mcservers;

## To enable image uploads, make sure the 'images' directory
## is writable, then uncomment this:
# \$wgEnableUploads		= true;
\$wgUseImageResize		= {$conf->UseImageResize};
{$magic}\$wgUseImageMagick = true;
{$magic}\$wgImageMagickConvertCommand = \"{$convert}\";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
{$hashedUploads}\$wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
# \$wgUseTeX			= true;
\$wgMathPath         = \"{\$wgUploadPath}/math\";
\$wgMathDirectory    = \"{\$wgUploadDirectory}/math\";
\$wgTmpDirectory     = \"{\$wgUploadDirectory}/tmp\";

\$wgLocalInterwiki   = \$wgSitename;

\$wgLanguageCode = \"{$slconf['LanguageCode']}\";

\$wgProxyKey = \"$secretKey\";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook':
# \$wgDefaultSkin = 'monobook';

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
{$rights}\$wgEnableCreativeCommonsRdf = true;
\$wgRightsPage = \"\"; # Set to the title of a wiki page that describes your license/copyright
\$wgRightsUrl = \"{$slconf['RightsUrl']}\";
\$wgRightsText = \"{$slconf['RightsText']}\";
\$wgRightsIcon = \"{$slconf['RightsIcon']}\";
# \$wgRightsCode = \"{$slconf['RightsCode']}\"; # Not yet used

\$wgDiff3 = \"{$slconf['diff3']}\";
";
	// Keep things in Unix line endings internally;
	// the system will write out as local text type.
	return str_replace( "\r\n", "\n", $localsettings );
}

function dieout( $text ) {
	die( $text . "\n\n</body>\n</html>" );
}

function importVar( &$var, $name, $default = "" ) {
	if( isset( $var[$name] ) ) {
		$retval = $var[$name];
		if ( get_magic_quotes_gpc() ) {
			$retval = stripslashes( $retval );
		}
	} else {
		$retval = $default;
	}
	return $retval;
}

function importPost( $name, $default = "" ) {
	return importVar( $_POST, $name, $default );
}

function importRequest( $name, $default = "" ) {
	return importVar( $_REQUEST, $name, $default );
}

function aField( &$conf, $field, $text, $type = "", $value = "" ) {
	if( $type != "" ) {
		$xtype = "type=\"$type\"";
	} else {
		$xtype = "";
	}

	if(!(isset($id)) or ($id == "") ) $id = $field;
	$nolabel = ($type == "radio") || ($type == "hidden");
	if( $nolabel ) {
		echo "\t\t<label>";
	} else {
		echo "\t\t<label class='column' for=\"$id\">$text</label>\n";
	}

	if( $type == "radio" && $value == $conf->$field ) {
		$checked = "checked='checked'";
	} else {
		$checked = "";
	}
	echo "\t\t<input $xtype name=\"$field\" id=\"$id\" $checked value=\"";
	if( $type == "radio" ) {
		echo htmlspecialchars( $value );
	} else {
		echo htmlspecialchars( $conf->$field );
	}
	echo "\" />\n";
	if( $nolabel ) {
		echo " $text</label>\n";
	}

	global $errs;
	if(isset($errs[$field])) echo "<span class='error'>" . $errs[$field] . "</span>\n";
}

function getLanguageList() {
	global $wgLanguageNames;
	if( !isset( $wgLanguageNames ) ) {
		$wgContLanguageCode = "xxx";
		function wfLocalUrl( $x ) { return $x; }
		function wfLocalUrlE( $x ) { return $x; }
		require_once( "languages/Names.php" );
	}

	$codes = array();

	$d = opendir( "../languages" );
	while( false !== ($f = readdir( $d ) ) ) {
		if( preg_match( '/Language([A-Z][a-z_]+)\.php$/', $f, $m ) ) {
			$code = str_replace( '_', '-', strtolower( $m[1] ) );
			if( isset( $wgLanguageNames[$code] ) ) {
				$name = $code . ' - ' . $wgLanguageNames[$code];
			} else {
				$name = $code;
			}
			$codes[$code] = $name;
		}
	}
	closedir( $d );
	ksort( $codes );
	return $codes;
}

#Check for location of an executable
# @param string $loc single location to check
# @param array $names filenames to check for.
# @param mixed $versioninfo array of details to use when checking version, use false for no version checking
function locate_executable($loc, $names, $versioninfo = false) {
	if (!is_array($names))
		$names = array($names);

	foreach ($names as $name) {
		if (file_exists("$loc/$name")) {
			if (!$versioninfo)
				return "$loc/$name";

			$file = str_replace('$1', "$loc/$name", $versioninfo[0]);
			if (strstr(`$file`, $versioninfo[1]) !== false)
				return "$loc/$name";
		}
	}
	return false;
}

# Test a memcached server
function testMemcachedServer( $server ) {
	$hostport = explode(":", $server);
	$errstr = false;
	$fp = false;
	if ( !function_exists( 'fsockopen' ) ) {
		$errstr = "Can't connect to memcached, fsockopen() not present";
	}
	if ( !$errstr &&  count( $hostport ) != 2 ) {
		$errstr = 'Please specify host and port';
		var_dump( $hostport );
	}
	if ( !$errstr ) {
		list( $host, $port ) = $hostport;
		$errno = 0;
		$fsockerr = '';

		$fp = @fsockopen( $host, $port, $errno, $fsockerr, 1.0 );
		if ( $fp === false ) {
			$errstr = "Cannot connect to memcached on $host:$port : $fsockerr";
		}
	}
	if ( !$errstr ) {
		$command = "version\r\n";
		$bytes = fwrite( $fp, $command );
		if ( $bytes != strlen( $command ) ) {
			$errstr = "Cannot write to memcached socket on $host:$port";
		}
	}
	if ( !$errstr ) {
		$expected = "VERSION ";
		$response = fread( $fp, strlen( $expected ) );
		if ( $response != $expected ) {
			$errstr = "Didn't get correct memcached response from $host:$port";
		}
	}
	if ( $fp ) {
		fclose( $fp );
	}
	if ( !$errstr ) {
		echo "<li>Connected to memcached on $host:$port successfully";
	}
	return $errstr;
}
?>

</body>
</html>
