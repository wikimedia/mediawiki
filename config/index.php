<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
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
	}
	dl.setup dt {
		font-size: 0.8em;
		margin-left: 10em;
		margin-right: 200px;
		margin-bottom: 2em;
	}
	dl.setup dd label {
		font-weight: bold;
		width: 10em;
		float: left;
		text-align: right;
		padding-right: 1em;
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
class ConfigData {}

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

?>
</ul>


<?php
	$conf->Sitename = ucfirst( importPost( "Sitename", "" ) );
	$conf->EmergencyContact = importPost( "EmergencyContact", $_SERVER["SERVER_ADMIN"] );
	$conf->DBserver = importPost( "DBserver", "localhost" );
	$conf->DBname = importPost( "DBname", "wikidb" );
	$conf->DBuser = importPost( "DBuser", "wikiuser" );
	$conf->DBpassword = importPost( "DBpassword" );
	$conf->DBpassword2 = importPost( "DBpassword2" );
	$conf->RootPW = importPost( "RootPW" );
	$conf->LanguageCode = importPost( "LanguageCode", "en-utf8" );

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

if( count( $errs ) ) {
	/* Display options form */
?>
<h2>Database config</h2>

<form name="config" method="post">

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
</dl>

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
		If the wiki breaks terribly, it may display this contact address.
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
		more compatible with older browsers for some languages. The default
		for most languages is Unicode.
	</dt>
	
	<dd>
		<label>&nbsp;</label>
		<input type="submit" value="Install!" />
	</dd>
</dl>

</form>

<?php
} else {
	/* Go for it! */
	print "<p>Creating LocalSettings.php...</p>\n\n";
	
	$local = writeLocalSettings( $conf );
	$f = fopen( "LocalSettings.php", "xt" );
	if( $f == false ) {
		dieout( "Couldn't write out LocalSettings.php. Check that the directory permissions are correct and that there isn't already a fiel of that name here." );
	}
	fwrite( $f, $local );
	fclose( $f );
	
	print "<p>Success! Move the LocalSettings.php file into the parent directory, then follow
	<a href='{$conf->ScriptPath}/index.php'>this link</a> to your wiki.</p>\n";
}

/* -------------------------------------------------------------------------------------- */

function writeAdminSettings( $conf ) {
	return "<" . "?php
\$wgDBadminuser      = \"{$conf->DBadminuser}\";
\$wgDBadminpassword  = \"{$conf->DBadminpassword}\";
?" . ">";
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
	return "<" . "?php
# This file was automatically generated. Don't touch unless you
# know what you're doing; see LocalSettings.sample for an edit-
# friendly file.

\$IP = \"{$conf->IP}\";
ini_set( \"include_path\", ini_get(\"include_path\") . \":\$IP/includes:\$IP/languages\" );
include_once( \"includes/DefaultSettings.php\" );

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

?" . ">";
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
	$wgLanguageCode = "xxx";
	function wfLocalUrl( $x ) { return $x; }
	function wfLocalUrlE( $x ) { return $x; }
	include( "../languages/Language.php" );
	
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
</html>