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

$errorParanoia = ( (error_reporting() & E_NOTICE) != 0 );

$IP = ".."; # Just to suppress notices, not for anything useful
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


include( "../install-utils.inc" );
include( "../maintenance/updaters.inc" );
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

<p><i>Please include all of the lines below when reporting installation problems.</i></p>

<h2>Checking environment...</h2>
<ul>
<?php
$endl = "
";
$conf = new ConfigData;

install_version_checks();
print "<li>PHP " . phpversion() . " ok</li>\n";

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

if( $errorParanoia ) {
	print "<li>Error reporting includes E_NOTICE level; this will be ignored.</li>\n";
}

$conf->zlib = function_exists( "gzencode" );
if( $conf->zlib ) {
	print "<li>Have zlib support; enabling output compression.</li>\n";
} else {
	print "<li>No zlib support.</li>\n";
}

$conf->ImageMagick = false;

$conf->HaveGD = function_exists( "imagejpeg" );
if( $conf->HaveGD ) {
	print "<li>Found GD graphics library built-in, image thumbnailing will be enabled if you enable uploads.</li>\n";
} else {
	$imcheck = array( "/usr/bin", "/usr/local/bin", "/sw/bin" );
	foreach( $imcheck as $dir ) {
		$im = "$dir/convert";
		if( file_exists( $im ) ) {
			print "<li>Found ImageMagick: <tt>$im</tt>; image thumbnailing will be enabled if you enable uploads.</li>\n";
			$conf->ImageMagick = $im;
			break;
		}
	}
	if( !$conf->ImageMagick ) {
		print "<li>Couldn't find GD library or ImageMagick; image thumbnailing disabled.</li>\n";
	}
}

$conf->UseImageResize = $conf->HaveGD || $conf->ImageMagick;

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
		$wgDatabase = Database::newFromParams( $wgDBserver, "root", $conf->RootPW, "", 1 );
		$wgDatabase->mIgnoreErrors = true;
		
		@$myver = mysql_get_server_info( $wgDatabase->mConn );
		if( $myver ) {
			$conf->Root = true;
			print "<li>Connected as root (automatic)</li>\n";
		} else {
			print "<li>MySQL error " . ($err = mysql_errno() ) .
				": " . htmlspecialchars( mysql_error() );
			$ok = false;
			switch( $err ) {
			case 1045:
				if( $conf->Root ) {
					$errs["RootPW"] = "Check password";
				} else {
					print "<li>Trying regular user...\n";
					/* Try the regular user... */
					$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, "", 1 );
					$wgDatabase->isOpen();
					$wgDatabase->mIgnoreErrors = true;
					@$myver = mysql_get_server_info( $wgDatabase->mConn );
					if( !$myver ) {
						$errs["DBuser"] = "Check name/pass";
						$errs["DBpassword"] = "or enter root";
						$errs["DBpassword2"] = "password below";
						$errs["RootPW"] = "Got root?";
						print " need password.</li>\n";
					} else {
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
				$errs["DBserver"] = "Couldn't connect to database";
				break;
			}
			if( !$ok ) continue;
		}
		
		if ( !$wgDatabase->isOpen() ) {
			$errs["DBserver"] = "Couldn't connect to database";
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
			print "<li>There are already MediaWiki tables in this database. Checking if updates are needed...</li>\n<pre>";
			
			chdir( ".." );
			flush();
			do_ipblocks_update(); flush();
			do_interwiki_update(); flush();
			do_index_update(); flush();
			do_linkscc_update(); flush();
			do_hitcounter_update(); flush();
			do_recentchanges_update(); flush();
			initialiseMessages(); flush();
			chdir( "config" );
			
			print "</pre>\n";
			print "<li>Finished update checks.</li>\n";
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
					$u->addRight( "bureaucrat" );
					$u->saveSettings();
					print "<li>Created sysop account <tt>" .
						htmlspecialchars( $conf->SysopName ) . "</tt>.</li>\n";
				} else {
					print "<li>Could not create user - already exists!</li>\n";
				}
			} else {
				print "<li>Skipped sysop account creation, no name given.</li>\n";
			}
			
			print "<li>Initialising log pages...";
			$logs = array(
				"uploadlogpage" => "uploadlogpagetext",
				"dellogpage" => "dellogpagetext",
				"protectlogpage" => "protectlogtext",
				"blocklogpage" => "blocklogtext"
			);
			$metaNamespace = Namespace::getWikipedia();
			$now = wfTimestampNow();
			$won = wfInvertTimestamp( $now );
			foreach( $logs as $page => $text ) {
				$logTitle = wfStrencode( $wgLang->ucfirst( str_replace( " ", "_", wfMsgNoDB( $page ) ) ) );
				$logText = wfStrencode( wfMsgNoDB( $text ) );
				$wgDatabase->query( "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
				  "cur_restrictions,cur_timestamp,inverse_timestamp,cur_touched) " .
				  "VALUES ($metaNamespace,'$logTitle','$logText','sysop','$now','$won','$now')" );
			}
			print "</li>\n";
			
			$titleobj = Title::newFromText( wfMsgNoDB( "mainpage" ) );
			$title = $titleobj->getDBkey();
			$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text,cur_timestamp,inverse_timestamp,cur_touched) " .
			  "VALUES (0,'$title','" .
			  wfStrencode( wfMsg( "mainpagetext" ) . "\n\n" . wfMsg( "mainpagedocfooter" ) ) . "','$now','$won','$now')";
			$wgDatabase->query( $sql, $fname );
			
			print "<li><pre>";
			initialiseMessages();
			print "</pre></li>\n";
			
			if( $conf->Root ) {
				# Grant user permissions
				print "<li>Granting user permissions...</li>\n";
				dbsource( "../maintenance/users.sql", $wgDatabase );
			}
		}

		/* Write out the config file now that all is well */
		print "<p>Creating LocalSettings.php...</p>\n\n";
		$localSettings =  "<" . "?php$endl$local$endl?" . ">";
		
		if( version_compare( phpversion(), "4.3.2" ) >= 0 ) {
			$xt = "xt"; # Refuse to overwrite an existing file
		} else {
			$xt = "wt"; # 'x' is not available prior to PHP 4.3.2. We did check above, but race conditions blah blah
		}
		$f = fopen( "LocalSettings.php", $xt );
		
		if( $f == false ) {
			dieout( "Couldn't write out LocalSettings.php. Check that the directory permissions are correct and that there isn't already a file of that name here...</p>\n" .
			"<p>Here's the file that would have been written, try to paste it into place manually:</p>\n" .
			"<pre>\n" . htmlspecialchars( $localSettings ) . "</pre>\n" );
		}
		fwrite( $f, $localSettings );
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
	$conf->DBmysql4 = @$conf->DBmysql4 ? 'true' : 'false';
	$conf->UseImageResize = $conf->UseImageResize ? 'true' : 'false';
	$conf->PasswordSender = $conf->EmergencyContact;
	if( $conf->LanguageCode == "en-utf8" ) {
		$conf->LanguageCode = "en";
		$conf->Encoding = "UTF-8";
	}
	$zlib = ($conf->zlib ? "" : "# ");
	$magic = ($conf->ImageMagick ? "" : "# ");
	$convert = ($conf->ImageMagick ? $conf->ImageMagick : "/usr/bin/convert" );
	$pretty = ($conf->prettyURLs ? "" : "# ");
	$ugly = ($conf->prettyURLs ? "# " : "");

	# Add slashes to strings for double quoting
	$slconf = array_map( "addslashes", get_object_vars( $conf ) );
	
	$sep = (DIRECTORY_SEPARATOR == "\\") ? ";" : ":";
	return "
# This file was automatically generated by the MediaWiki installer.
# If you make manual changes, please keep track in case you need to
# recreate them later.

\$IP = \"{$slconf[IP]}\";
ini_set( \"include_path\", \"\$IP/includes$sep\$IP/languages$sep\" . ini_get(\"include_path\") );
include_once( \"DefaultSettings.php\" );

if( \$wgCommandLineMode ) {
	die( \"Can't use command-line utils with in-place install yet, sorry.\" );
} else {
	## Compress output if the browser supports it
	{$zlib}if( !ini_get( 'zlib.output_compression' ) ) ob_start( 'ob_gzhandler' );
}

\$wgSitename         = \"{$slconf[Sitename]}\";

\$wgScriptPath	    = \"{$slconf[ScriptPath]}\";
\$wgScript           = \"\$wgScriptPath/index.php\";
\$wgRedirectScript   = \"\$wgScriptPath/redirect.php\";

## If using PHP as a CGI module, use the ugly URLs
{$pretty}\$wgArticlePath      = \"\$wgScript/\$1\";
{$ugly}\$wgArticlePath      = \"\$wgScript?title=\$1\";

\$wgStyleSheetPath   = \"\$wgScriptPath/stylesheets\";
\$wgStyleSheetDirectory = \"\$IP/stylesheets\";

\$wgUploadPath       = \"\$wgScriptPath/images\";
\$wgUploadDirectory	= \"\$IP/images\";
\$wgLogo				= \"\$wgUploadPath/wiki.png\";
\$wgStockPath        = \$wgUploadPath;

\$wgEmergencyContact = \"{$slconf[EmergencyContact]}\";
\$wgPasswordSender	= \"{$slconf[PasswordSender]}\";

\$wgDBserver         = \"{$slconf[DBserver]}\";
\$wgDBname           = \"{$slconf[DBname]}\";
\$wgDBuser           = \"{$slconf[DBuser]}\";
\$wgDBpassword       = \"{$slconf[DBpassword]}\";

## To allow SQL queries through the wiki's Special:Askaql page,
## uncomment the next lines. THIS IS VERY INSECURE. If you want
## to allow semipublic read-only SQL access for your sysops,
## you should define a MySQL user with limited privileges.
## See MySQL docs: http://www.mysql.com/doc/en/GRANT.html
#
# \$wgAllowSysopQueries = true;
# \$wgDBsqluser        = \"sqluser\";
# \$wgDBsqlpassword    = \"sqlpass\";

\$wgDBmysql4 = \$wgEnablePersistentLC = {$conf->DBmysql4};

## To enable image uploads, make sure the 'images' directory
## is writable, then uncomment this:
# \$wgDisableUploads		= false;
\$wgUseImageResize		= {$conf->UseImageResize};
{$magic}\$wgUseImageMagick = true;
{$magic}\$wgImageMagickConvertCommand = \"{$convert}\";

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
# \$wgUseTeX			= true;
\$wgMathPath         = \"{\$wgUploadPath}/math\";
\$wgMathDirectory    = \"{\$wgUploadDirectory}/math\";
\$wgTmpDirectory     = \"{\$wgUploadDirectory}/tmp\";

\$wgLocalInterwiki   = \$wgSitename;

\$wgLanguageCode = \"{$slconf[LanguageCode]}\";
" . ($conf->Encoding ? "\$wgInputEncoding = \$wgOutputEncoding = \"{$slconf[Encoding]}\";" : "" ) . "

";
}

function dieout( $text ) {
	die( $text . "\n\n</body>\n</html>" );
}

function importPost( $name, $default = "" ) {
	if( isset( $_POST[$name] ) ) {
		$retval = $_POST[$name];
		if ( get_magic_quotes_gpc() ) {
			$retval = stripslashes( $retval );
		}
	} else {
		$retval = $default;
	}
	return $retval;
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
	$latin1 = array( "da", "de", "en", "es", "nl", "sv" );
	
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
