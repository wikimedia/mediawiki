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

header( "Content-type: text/html; charset=utf-8" );

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
	dl.setup dd label {
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
	</style>
</head>

<body>

<div id="credit">
 <center>
  <a href="http://www.mediawiki.org/"><img
    src="../images/wiki.png" width="135" height="135" alt="" border="0" /></a>
 </center>
 
 <b><a href="http://www.mediawiki.org/">MediaWiki</a></b> is
 Copyright (C) 2001-2004 by Magnus Manske, Brion Vibber, Lee Daniel Crocker,
 Tim Starling, Erik M&ouml;ller, and others.</p>
 
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
 
 <p>This progarm is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.</p>
 
 <p>You should have received <a href="../COPYING">a copy of the GNU General Public License</a>
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 or <a href="http://www.gnu.org/copyleft/gpl.html">read it online</a></p>
</div>

<?php
include( "../includes/DefaultSettings.php" );
?>

<h1>MediaWiki <?php print $wgVersion ?> installation</h1>


<?php

/* Check for existing configurations and bug out! */

if( file_exists( "../LocalSettings.php" ) || file_exists( "../AdminSettings.php" ) ) {
	dieout( "<h2>Wiki is configured.</h2>
	
	<p>Already configured... <a href='../index.php'>return to the wiki</a>.</p>
	
	<p>(You should probably remove this directory for added security.)</p>" );
}

if( file_exists( "./LocalSettings.php" ) || file_exists( "./AdminSettings.php" ) ) {
	dieout( "<h2>You're configured!</h2>
	
	<p>Please move <tt>LocalSettings.php</tt> to the parent directory, then
	<a href='../index.php'>try out your wiki</a>.
	(You should remove this config directory for added security once you're done.)</p>" );
}


include( "../install-utils.inc" );
class ConfigData {
	function getEncoded( $data ) {
		# Hackish
		global $wgInputEncoding;
		if( strcasecmp( $wgInputEncoding, "utf-8" ) == 0 ) {
			return $data;
		} else {
			return utf8_decode( $data ); /* to latin1 wikis */
		}
	}
	function getSitename() { return $this->getEncoded( $this->Sitename ); }
	function getSysopName() { return $this->getEncoded( $this->SysopName ); }
	function getSysopPass() { return $this->getEncoded( $this->SysopPass ); }
}

?>


<h2>Checking environment...</h2>
<ul>
<?php

$conf = new ConfigData;

install_version_checks();
print "<li>PHP " . phpversion() . " ok</li>\n";

/*
$conf->zlib = function_exists( "gzencode" );
$z = $conf->zlib ? "Have" : "No";
print "<li>$z zlib support</li>\n";

$conf->gd = function_exists( "imagejpeg" );
if( $conf->gd ) {
	print "<li>Found GD graphics library built-in</li>\n";
} else {
	print "<li>No built-in GD library</li>\n";
}

if( file_exists( "/usr/bin/convert" ) ) {
	$conf->ImageMagick = "/usr/bin/convert";
	print "<li>Found ImageMagick: /usr/bin/convert</li>\n";
} elseif( file_exists( "/usr/local/bin/convert" ) ) {
	$conf->ImageMagick = "/usr/local/bin/convert";
	print "<li>Found ImageMagick: /usr/local/bin/convert</li>\n";
} else {
	$conf->ImageMagick = false;
	print "<li>No ImageMagick.</li>\n";
}
*/

# $conf->IP = "/Users/brion/Sites/inplace";
chdir( ".." );
$conf->IP = getcwd();
chdir( "config" );
print "<li>Installation directory: <tt>" . htmlspecialchars( $conf->IP ) . "</tt></li>\n";

# $conf->ScriptPath = "/~brion/inplace";
$conf->ScriptPath = preg_replace( '{^(.*)/config.*$}', '$1', $_SERVER["REQUEST_URI"] );
print "<li>Script URI path: <tt>" . htmlspecialchars( $conf->ScriptPath ) . "</tt></li>\n";

	$conf->posted = ($_SERVER["REQUEST_METHOD"] == "POST");
	
	$conf->Sitename = ucfirst( importPost( "Sitename", "" ) );
	$conf->EmergencyContact = importPost( "EmergencyContact", $_SERVER["SERVER_ADMIN"] );
	$conf->DBserver = importPost( "DBserver", "localhost" );
	$conf->DBname = importPost( "DBname", "wikidb" );
	$conf->DBuser = importPost( "DBuser", "wikiuser" );
	$conf->DBpassword = importPost( "DBpassword" );
	$conf->DBpassword2 = importPost( "DBpassword2" );
	$conf->RootPW = importPost( "RootPW" );
	$conf->LanguageCode = importPost( "LanguageCode", "en-utf8" );
	$conf->SysopName = importPost( "SysopName", "WikiSysop" );
	$conf->SysopPass = importPost( "SysopPass" );
	$conf->SysopPass2 = importPost( "SysopPass2" );

/* Check for validity */
$errs = array();

if( $conf->Sitename == "" || $conf->Sitename == "MediaWiki" || $conf->Sitename == "Mediawiki" ) {
	$errs["Sitename"] = "Must not be blank or \"MediaWiki\".";
}
if( $conf->DBpassword == "" ) {
	$errs["DBpassword"] = "Must not be blank";
}
if( $conf->DBpassword != $conf->DBpassword2 ) {
	$errs["DBpassword2"] = "Passwords don't match!";
}

if( $conf->SysopPass == "" ) {
	$errs["SysopPass"] = "Must not be blank";
}
if( $conf->SysopPass != $conf->SysopPass2 ) {
	$errs["SysopPass2"] = "Passwords don't match!";
}

if( $conf->posted && ( 0 == count( $errs ) ) ) {
	do { /* So we can 'continue' to end prematurely */
		$conf->Root = ($conf->RootPW != "");
		
		/* Load up the settings and get installin' */
		$local = writeLocalSettings( $conf );
		$wgCommandLineMode = false;
		eval($local);

		$wgDBadminuser = $wgDBuser;
		$wgDBadminpassword = $wgDBpassword;
		$wgCommandLineMode = true;
		$wgUseDatabaseMessages = false;	/* FIXME: For database failure */
		include_once( "Setup.php" );
		include_once( "../maintenance/InitialiseMessages.inc" );

		$wgTitle = Title::newFromText( "Installation script" );
		
		if( $conf->Root ) {
			$wgDatabase = Database::newFromParams( $wgDBserver, "root", $conf->RootPW, "", 1 );
		} else {
			$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, "", 1 );
		}
		$wgDatabase->mIgnoreErrors = true;
		
		if ( !$wgDatabase->isOpen() ) {
			$errs["DBserver"] = "Couldn't connect to database";
			continue;
		}

		@$myver = mysql_get_server_info( $wgDatabase->mConn );
		if( !$myver ) {
			print "<li>MySQL error " . ($err = mysql_errno() ) .
				": " . htmlspecialchars( mysql_error() );
			switch( $err ) {
			case 1045:
				if( $conf->Root ) {
					$errs["RootPW"] = "Check password";
				} else {
					$errs["DBuser"] = "Check name/pass";
					$errs["DBpassword"] = "or enter root";
					$errs["DBpassword2"] = "password below";
					$errs["RootPW"] = "Got root?";
				}
				break;
			case 2002:
			case 2003:
				$errs["DBserver"] = "Connection failed";
				break;
			default:
				$errs["DBserver"] = "Couldn't connect to database";
			}
			continue;
		}
		print "<li>Connected to database... $myver";
		if( version_compare( $myver, "4.0.0" ) >= 0 ) {
			print "; enabling MySQL 4 enhancements";
			$conf->DBmysql4 = true;
			$local = writeLocalSettings( $conf );
		}
		print "</li>\n";
		
		@$sel = mysql_select_db( $wgDBname, $wgDatabase->mConn );
		if( $sel ) {
			print "<li>Database <tt>" . htmlspecialchars( $wgDBname ) . "</tt> exists</li>\n";
		} else {
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
		
		if( $wgDatabase->tableExists( "cur" ) ) {
			print "<li>There are already MediaWiki tables in this database. Skipping rest of database setup...</li>\n";
		} else {
			# FIXME: Check for errors
			print "<li>Creating tables...";
			dbsource( "../maintenance/tables.sql", $wgDatabase );
			dbsource( "../maintenance/interwiki.sql", $wgDatabase );
			dbsource( "../maintenance/indexes.sql", $wgDatabase );
			print " done.</li>\n";
			
			print "<li>Initializing data...";
			$wgDatabase->query( "INSERT INTO site_stats (ss_row_id,ss_total_views," .
				"ss_total_edits,ss_good_articles) VALUES (1,0,0,0)" );
			
			if( $conf->SysopName ) {
				$u = User::newFromName( $conf->getSysopName() );
				if ( 0 == $u->idForName() ) {
					$u->addToDatabase();
					$u->setPassword( $conf->getSysopPass() );
					$u->addRight( "sysop" );
					$u->addRight( "developer" ); /* ?? */
					$u->saveSettings();
					print "<li>Created sysop account <tt>" .
						htmlspecialchars( $conf->SysopName ) . "</tt>.</li>\n";
				} else {
					print "<li>Could not create user - already exists!</li>\n";
				}
			} else {
				print "<li>Skipped sysop account creation, no name given.</li>\n";
			}
			
			# FIXME: Main page, logs
			# FIXME: Initialize messages
			print "<li>(NYI: pages)</li>\n";
			
			print "<li>";
			initialiseMessages();
			print "</li>\n";
			
			if( $conf->Root ) {
				# Grant user permissions
				dbsource( "../maintenance/users.sql", $wgDatabase );
			}
		}

		/* Write out the config file now that all is well */
		print "<p>Creating LocalSettings.php...</p>\n\n";
		$f = fopen( "LocalSettings.php", "xt" );
		if( $f == false ) {
			dieout( "Couldn't write out LocalSettings.php. Check that the directory permissions are correct and that there isn't already a fiel of that name here." );
		}
		fwrite( $f, "<" . "?php\n$local\n?" . ">" );
		fclose( $f );
		
		print "<p>Success! Move the LocalSettings.php file into the parent directory, then follow
		<a href='{$conf->ScriptPath}/index.php'>this link</a> to your wiki.</p>\n";
		
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

<form name="config" method="post">


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
		touch with you.
	</dt>

	<dd>
		<label for="LanguageCode">Language</label>
		<select id="LanguageCode" name="LanguageCode">
		<?php
			$list = getLanguageList();
			foreach( $list as $code => $name ) {
				$sel = ($code == $conf->LanguageCode) ? "selected" : "";
				echo "\t\t<option value=\"$code\" $sel>$name</option>\n";
			}
		?>
		</select>
	</dd>
	<dt>
		You may select the language for the user interface of the wiki...
		Some localizations are less complete than others. This also controls
		the character encoding; Unicode is more flexible, but Latin-1 may be
		more compatible with older browsers for some languages. Unicode will
		be used where not specified otherwise.
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
		<label>&nbsp;</label>
		<input type="submit" value="Install!" />
	</dd>
</dl>


</form>

<?php
}

/* -------------------------------------------------------------------------------------- */

function writeAdminSettings( $conf ) {
	return "
\$wgDBadminuser      = \"{$conf->DBadminuser}\";
\$wgDBadminpassword  = \"{$conf->DBadminpassword}\";
";
}

function writeLocalSettings( $conf ) {
	$conf->DBmysql4 = $conf->DBmysql4 ? 'true' : 'false';
	$conf->UseImageResize = $conf->UseImageResize ? 'true' : 'false';
	$conf->DBsqluser = $conf->DBuser;
	$conf->DBsqlpassword = $conf->DBpassword;
	$conf->PasswordSender = $conf->EmergencyContact;
	if( $conf->LanguageCode == "en-utf8" ) {
		$conf->LanguageCode = "en";
		$conf->Encoding = "UTF-8";
	}
	return "
# This file was automatically generated. Don't touch unless you
# know what you're doing; see LocalSettings.sample for an edit-
# friendly file.

\$IP = \"{$conf->IP}\";
ini_set( \"include_path\", \"\$IP/includes:\$IP/languages:\" . ini_get(\"include_path\") );
include_once( \"DefaultSettings.php\" );

if( \$wgCommandLineMode ) {
	die( \"Can't use command-line utils with in-place install yet, sorry.\" );
}

\$wgSitename         = \"{$conf->Sitename}\";

\$wgScriptPath	    = \"{$conf->ScriptPath}\";
\$wgScript           = \"\$wgScriptPath/index.php\";
\$wgRedirectScript   = \"\$wgScriptPath/redirect.php\";

\$wgArticlePath      = \"\$wgScript?title=\$1\";
# \$wgArticlePath     = \"\$wgScript/\$1\"; # Prettier if you're setup for it

\$wgStyleSheetPath   = \"\$wgScriptPath/stylesheets\";
\$wgStyleSheetDirectory = \"\$IP/stylesheets\";

\$wgUploadPath       = \"\$wgScriptPath/images\";
\$wgUploadDirectory	= \"\$IP/images\";
\$wgLogo				= \"\$wgUploadPath/wiki.png\";

\$wgEmergencyContact = \"{$conf->EmergencyContact}\";
\$wgPasswordSender	= \"{$conf->PasswordSender}\";

\$wgDBserver         = \"{$conf->DBserver}\";
\$wgDBname           = \"{$conf->DBname}\";
\$wgDBuser           = \"{$conf->DBuser}\";
\$wgDBpassword       = \"{$conf->DBpassword}\";
\$wgDBsqluser        = \"{$conf->DBsqluser}\";
\$wgDBsqlpassword	= \"{$conf->DBsqlpassword}\";

\$wgDBmysql4 = \$wgEnablePersistentLC = {$conf->DBmysql4};

\$wgUseImageResize		= {$conf->UseImageResize};

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
# \$wgUseTeX			= true;
# \$wgMathPath         = \"{$wgUploadPath}/math\";
# \$wgMathDirectory    = \"{$wgUploadDirectory}/math\";
# \$wgTmpDirectory     = \"{$wgUploadDirectory}/tmp\";

\$wgLocalInterwiki   = \$wgSitename;

\$wgLanguageCode = \"{$conf->LanguageCode}\";
" . ($conf->Encoding ? "\$wgInputEncoding = \$wgOutputEncoding = \"{$conf->Encoding}\";" : "" ) . "

";
}

function dieout( $text ) {
	die( $text . "\n\n</body>\n</html>" );
}

function importPost( $name, $default = "" ) {
	if( isset( $_POST[$name] ) ) {
		return $_POST[$name];
	} else {
		return $default;
	}
}

function aField( &$conf, $field, $text, $type = "" ) {
	if( $type != "" ) $type = "type=\"$type\"";
	echo "\t\t<label for=\"$field\">$text</label>\n";
	echo "\t\t<input $type name=\"$field\" id=\"$field\" value=\"";
	echo htmlspecialchars( $conf->$field );
	echo "\" />\n";
	
	global $errs;
	if(isset($errs[$field])) echo "<span class='error'>" . $errs[$field] . "</span>\n";
}

function getLanguageList() {
	global $wgLanguageNames;
	if( !isset( $wgLanguageNames ) ) {
		$wgLanguageCode = "xxx";
		function wfLocalUrl( $x ) { return $x; }
		function wfLocalUrlE( $x ) { return $x; }
		include( "../languages/Language.php" );
	}
	
	$codes = array();
	$latin1 = array( "da", "de", "en", "es", "fr", "nl", "sv" );
	
	$d = opendir( "../languages" );
	while( false !== ($f = readdir( $d ) ) ) {
		if( preg_match( '/Language([A-Z][a-z]+)\.php$/', $f, $m ) ) {
			$code = strtolower( $m[1] );
			$codes[$code] = "$code - " . $wgLanguageNames[$code];
			if( in_array( $code, $latin1 ) ) {
				$codes[$code] .= " - Latin-1";
			}
		}
	}
	$codes["en-utf8"] = "en - English - Unicode";
	closedir( $d );
	ksort( $codes );
	return $codes;
}

?>

</body>
</html>