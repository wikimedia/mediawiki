<?php

# MediaWiki web-based config/installation
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>, 2006 Rob Church <robchur@gmail.com>
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
# 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

error_reporting( E_ALL );
header( "Content-type: text/html; charset=utf-8" );
@ini_set( "display_errors", true );

# In case of errors, let output be clean.
$wgRequestTime = microtime( true );

# Attempt to set up the include path, to fix problems with relative includes
$IP = dirname( dirname( __FILE__ ) );
define( 'MW_INSTALL_PATH', $IP );
$sep = PATH_SEPARATOR;
if( !ini_set( "include_path", ".$sep$IP$sep$IP/includes$sep$IP/languages" ) ) {
	set_include_path( ".$sep$IP$sep$IP/includes$sep$IP/languages" );
}

# Define an entry point and include some files
define( "MEDIAWIKI", true );
define( "MEDIAWIKI_INSTALL", true );

// Run version checks before including other files
// so people don't see a scary parse error.
require_once( "install-utils.inc" );
install_version_checks();

require_once( "includes/Defines.php" );
require_once( "includes/DefaultSettings.php" );
require_once( "includes/MagicWord.php" );
require_once( "includes/Namespace.php" );
require_once( "includes/ProfilerStub.php" );

## Databases we support:

$ourdb = array();
$ourdb['mysql']['fullname']      = 'MySQL';
$ourdb['mysql']['havedriver']    = 0;
$ourdb['mysql']['compile']       = 'mysql';
$ourdb['mysql']['bgcolor']       = '#ffe5a7';
$ourdb['mysql']['rootuser']      = 'root';

$ourdb['postgres']['fullname']   = 'PostgreSQL';
$ourdb['postgres']['havedriver'] = 0;
$ourdb['postgres']['compile']    = 'pgsql';
$ourdb['postgres']['bgcolor']    = '#aaccff';
$ourdb['postgres']['rootuser']   = 'postgres';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>MediaWiki <?php echo( $wgVersion ); ?> Installation</title>
	<style type="text/css">

		@import "../skins/monobook/main.css";

		.env-check {
			font-size: 90%;
			margin: 1em 0 1em 2.5em;
		}

		.config-section {
			margin-top: 2em;
		}

		.config-section label.column {
			clear: left;
			font-weight: bold;
			width: 13em;
			float: left;
			text-align: right;
			padding-right: 1em;
			padding-top: .2em;
		}

		.config-input {
			clear: left;
			zoom: 100%; /* IE hack */
		}

		.config-section .config-desc {
			clear: left;
			margin: 0 0 2em 18em;
			padding-top: 1em;
			font-size: 85%;
		}

		.iput-text, .iput-password {
			width: 14em;
			margin-right: 1em;
		}

		.error {
			color: red;
			background-color: #fff;
			font-weight: bold;
			left: 1em;
			font-size: 100%;
		}

		.error-top {
			color: red;
			background-color: #FFF0F0;
			border: 2px solid red;
			font-size: 130%;
			font-weight: bold;
			padding: 1em 1.5em;
			margin: 2em 0 1em;
		}

		ul.plain {
			list-style-type: none;
			list-style-image: none;
			float: left;
			margin: 0;
			padding: 0;
		}

		.btn-install {
			font-weight: bold;
			font-size: 110%;
			padding: .2em .3em;
		}

		.license {
			font-size: 85%;
			padding-top: 3em;
		}

	</style>
	<script type="text/javascript">
	<!--
	function hideall() {
		<?php foreach (array_keys($ourdb) as $db) {
		echo "\n		document.getElementById('$db').style.display='none';";
		}
		?>

	}
	function toggleDBarea(id,defaultroot) {
		hideall();
		var dbarea = document.getElementById(id).style;
		dbarea.display = (dbarea.display == 'none') ? 'block' : 'none';
		var db = document.getElementById('RootUser');
		if (defaultroot) {
<?php foreach (array_keys($ourdb) as $db) {
			echo "			if (id == '$db') { db.value = '".$ourdb[$db]['rootuser']."';}\n";
}?>
		}
	}
	// -->
	</script>
</head>

<body>
<div id="globalWrapper">
<div id="column-content">
<div id="content">
<div id="bodyContent">

<h1>MediaWiki <?php print $wgVersion ?> Installation</h1>

<?php

/* Check for existing configurations and bug out! */

if( file_exists( "../LocalSettings.php" ) ) {
	dieout( "<p><strong>Setup has completed, <a href='../index.php'>your wiki</a> is configured.</strong></p>

	<p>Please delete the /config directory for extra security.</p></div></div></div></div>" );
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
	</pre>
	
	<p>Afterwards retry to start the <a href=\"\">setup</a>.</p>" );
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

<ul>
	<li>
		<b>Don't forget security updates!</b> Keep an eye on the
		<a href="http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce">low-traffic
		release announcements mailing list</a>.
	</li>
</ul>


<h2>Checking environment...</h2>
<p><em>Please include all of the lines below when reporting installation problems.</em></p>
<ul class="env-check">
<?php
$endl = "
";
$wgNoOutputBuffer = true;
$conf = new ConfigData;

install_version_checks();

print "<li>PHP " . phpversion() . " installed</li>\n";

## Temporarily turn off all errors as we try to discover installed databases
$olderrnum = error_reporting(0);

$phpdatabases = array();
foreach (array_keys($ourdb) as $db) {
	$compname = $ourdb[$db]['compile'];
	if (extension_loaded($compname) or dl($compname . '.' . PHP_SHLIB_SUFFIX)) {
		array_push($phpdatabases, $db);
		$ourdb[$db]['havedriver'] = 1;
	}
}

error_reporting($olderrornum);

if (!$phpdatabases) {
	print "Could not find a suitable database driver!<ul>";
	foreach (array_keys($ourdb) AS $db) {
		$comp = $ourdb[$db]['compile'];
		$full = $ourdb[$db]['fullname'];
		print "<li>For <b>$full</b>, compile PHP using <b>--with-$comp</b>, "
			."or install the $comp.so module</li>\n";
	}
	dieout( "</ul></ul>" );
}

print "<li>Found database drivers for:";
foreach (array_keys($ourdb) AS $db) {
	if ($ourdb[$db]['havedriver']) {
		$DefaultDBtype = $db;
		print "  ".$ourdb[$db]['fullname'];
	}
}
print "</li>\n";
if (count($phpdatabases) != 1)
	$DefaultDBtype = '';

if( ini_get( "register_globals" ) ) {
	?>
	<li>
		<div style="font-size:110%">
		<strong class="error">Warning:</strong>
		<strong>PHP's <tt><a href="http://php.net/register_globals">register_globals</a></tt> option is enabled. Disable it if you can.</strong>
		</div>
		MediaWiki will work, but your server is more exposed to PHP-based security vulnerabilities.
	</li>
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
	dieout( "</ul><p>Cannot install MediaWiki.</p>" );
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
	// For some reason cgi.fix_pathinfo isn't retrievable via ini_get()
	if( isset( $_SERVER['ORIG_PATH_INFO'] ) ) {
		echo "cgi.fix_pathinfo is set, good; ";
	} else {
		echo "cgi.fix_pathinfo is not set, assuming PATH_INFO broken; ";
		$conf->prettyURLs = false;
	}
	break;
case "apache2filter":
case "isapi":
	// Pretty sure these two break from past tests
	$conf->prettyURLs = false;
	break;
default:
	print "unknown, assuming PATH_INFO broken for safety; ";
	$conf->prettyURLs = false;
}
if( $conf->prettyURLs ) {
	print "ok, using pretty URLs (<tt>index.php/Page_Title</tt>)";
} else {
	print "using ugly URLs (<tt>index.php?title=Page_Title</tt>)";
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

# Crude check for session support
if( !function_exists( 'session_name' ) )
	dieout( "PHP's session module is missing. MediaWiki requires session support in order to function." );

# Likewise for PCRE
if( !function_exists( 'preg_match' ) )
	dieout( "The PCRE regular expression functions are missing. MediaWiki requires these in order to function." );

$memlimit = ini_get( "memory_limit" );
$conf->raiseMemory = false;
if( empty( $memlimit ) || $memlimit == -1 ) {
	print "<li>PHP is configured with no <tt>memory_limit</tt>.</li>\n";
} else {
	print "<li>PHP's <tt>memory_limit</tt> is " . htmlspecialchars( $memlimit ) . ". <strong>If this is too low, installation may fail!</strong> ";
	$n = intval( $memlimit );
	if( preg_match( '/^([0-9]+)[Mm]$/', trim( $memlimit ), $m ) ) {
		$n = intval( $m[1] * (1024*1024) );
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

$conf->apc = function_exists('apc_fetch');
if ($conf->apc ) {
	print "<li><a href=\"http://www.php.net/apc\">APC</a> installed</li>";
}

$conf->eaccel = function_exists( 'eaccelerator_get' );
if ( $conf->eaccel ) {
	$conf->turck = 'eaccelerator';
	print "<li><a href=\"http://eaccelerator.sourceforge.net/\">eAccelerator</a> installed</li>\n";
}

if( !$conf->turck && !$conf->eaccel && !$conf->apc ) {
	echo( '<li>Couldn\'t find <a href="http://turck-mmcache.sourceforge.net">Turck MMCache</a>,
		<a href="http://eaccelerator.sourceforge.net">eAccelerator</a> or
		<a href="http://www.php.net/apc">APC</a>. Object caching functions cannot be used.</li>' );
}

$conf->diff3 = false;
$diff3locations = array_merge(
	array(
		"/usr/bin",
		"/usr/local/bin",
		"/opt/csw/bin",
		"/usr/gnu/bin",
		"/usr/sfw/bin" ),
	explode( $sep, getenv( "PATH" ) ) );
$diff3names = array( "gdiff3", "diff3", "diff3.exe" );

$diff3versioninfo = array( '$1 --version 2>&1', 'diff3 (GNU diffutils)' );
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
$imcheck = array( "/usr/bin", "/opt/csw/bin", "/usr/local/bin", "/sw/bin", "/opt/local/bin" );
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

$conf->IP = dirname( dirname( __FILE__ ) );
print "<li>Installation directory: <tt>" . htmlspecialchars( $conf->IP ) . "</tt></li>\n";


// PHP_SELF isn't available sometimes, such as when PHP is CGI but
// cgi.fix_pathinfo is disabled. In that case, fall back to SCRIPT_NAME
// to get the path to the current script... hopefully it's reliable. SIGH
$path = ($_SERVER["PHP_SELF"] === '')
	? $_SERVER["SCRIPT_NAME"]
	: $_SERVER["PHP_SELF"];
$conf->ScriptPath = preg_replace( '{^(.*)/config.*$}', '$1', $path );
print "<li>Script URI path: <tt>" . htmlspecialchars( $conf->ScriptPath ) . "</tt></li>\n";

print "<li style='font-weight:bold;color:green;font-size:110%'>Environment checked. You can install MediaWiki.</li>\n";
	$conf->posted = ($_SERVER["REQUEST_METHOD"] == "POST");

	$conf->Sitename = ucfirst( importPost( "Sitename", "" ) );
	$defaultEmail = empty( $_SERVER["SERVER_ADMIN"] )
		? 'root@localhost'
		: $_SERVER["SERVER_ADMIN"];
	$conf->EmergencyContact = importPost( "EmergencyContact", $defaultEmail );
	$conf->DBtype = importPost( "DBtype", $DefaultDBtype );
?>

<?php
	$conf->DBserver = importPost( "DBserver", "localhost" );
	$conf->DBname = importPost( "DBname", "wikidb" );
	$conf->DBuser = importPost( "DBuser", "wikiuser" );
	$conf->DBpassword = importPost( "DBpassword" );
	$conf->DBpassword2 = importPost( "DBpassword2" );
	$conf->SysopName = importPost( "SysopName", "WikiSysop" );
	$conf->SysopPass = importPost( "SysopPass" );
	$conf->SysopPass2 = importPost( "SysopPass2" );
	$conf->RootUser = importPost( "RootUser", "root" );
	$conf->RootPW = importPost( "RootPW", "-" );

	## MySQL specific:
	$conf->DBprefix     =  importPost( "DBprefix" );
	$conf->DBmysql5     = (importPost( "DBmysql5" ) == "true") ? "true" : "false";
	$conf->LanguageCode =  importPost( "LanguageCode", "en" );

	## Postgres specific:
	$conf->DBport      = importPost( "DBport",      "5432" );
	$conf->DBmwschema  = importPost( "DBmwschema",  "mediawiki" );
	$conf->DBts2schema = importPost( "DBts2schema", "public" );

/* Check for validity */
$errs = array();

if( $conf->Sitename == "" || $conf->Sitename == "MediaWiki" || $conf->Sitename == "Mediawiki" ) {
	$errs["Sitename"] = "Must not be blank or \"MediaWiki\"";
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
$conf->Email     = importRequest("Email", "email_enabled");
$conf->Emailuser = importRequest("Emailuser", "emailuser_enabled");
$conf->Enotif    = importRequest("Enotif", "enotif_allpages");
$conf->Eauthent  = importRequest("Eauthent", "eauthent_enabled");

if( $conf->posted && ( 0 == count( $errs ) ) ) {
	do { /* So we can 'continue' to end prematurely */
		$conf->Root = ($conf->RootPW != "");

		/* Load up the settings and get installin' */
		$local = writeLocalSettings( $conf );
		echo "<li style=\"list-style: none\">\n";
		echo "<p><b>Generating configuration file...</b></p>\n";
		// for debugging: // echo "<pre>" . htmlspecialchars( $local ) . "</pre>\n";
		echo "</li>\n";		

		$wgCommandLineMode = false;
		chdir( ".." );
		eval($local);
		$conf->DBtypename = '';
		foreach (array_keys($ourdb) as $db) {
			if ($conf->DBtype === $db)
				$conf->DBtypename = $ourdb[$db]['fullname'];
		}
		if ( ! strlen($conf->DBtype)) {
			$errs["DBpicktype"] = "Please choose a database type";
			continue;
		}

		if (! $conf->DBtypename) {
			$errs["DBtype"] = "Unknown database type '$conf->DBtype'";
			continue;
		}
		print "<li>Database type: {$conf->DBtypename}</li>\n";
		$dbclass = 'Database'.ucfirst($conf->DBtype);
		$wgDBtype = $conf->DBtype;
		$wgDBadminuser = "root";
		$wgDBadminpassword = $conf->RootPW;

		## Mysql specific:
		$wgDBprefix = $conf->DBprefix;

		## Postgres specific:
		$wgDBport      = $conf->DBport;
		$wgDBmwschema  = $conf->DBmwschema;
		$wgDBts2schema = $conf->DBts2schema;

		$wgCommandLineMode = true;
		$wgUseDatabaseMessages = false; /* FIXME: For database failure */
		require_once( "includes/Setup.php" );
		chdir( "config" );

		require_once( "maintenance/InitialiseMessages.inc" );

		$wgTitle = Title::newFromText( "Installation script" );
		error_reporting( E_ALL );
		print "<li>Loading class: $dbclass";
		$dbc = new $dbclass;

		if( $conf->DBtype == 'mysql' ) {
			$mysqlOldClient = version_compare( mysql_get_client_info(), "4.1.0", "lt" );
			if( $mysqlOldClient ) {
				print "<li><b>PHP is linked with old MySQL client libraries. If you are
					using a MySQL 4.1 server and have problems connecting to the database,
					see <a href='http://dev.mysql.com/doc/mysql/en/old-client.html'
			 		>http://dev.mysql.com/doc/mysql/en/old-client.html</a> for help.</b></li>\n";
			}
			$ok = true; # Let's be optimistic
			
			# Decide if we're going to use the superuser or the regular database user
			if( $conf->RootPW == '-' ) {
				# Regular user
				$conf->Root = false;
				$db_user = $wgDBuser;
				$db_pass = $wgDBpassword;
			} else {
				# Superuser
				$conf->Root = true;
				$db_user = $conf->RootUser;
				$db_pass = $conf->RootPW;
			}
			
			# Attempt to connect
			echo( "<li>Attempting to connect to database server as $db_user..." );
			$wgDatabase = Database::newFromParams( $wgDBserver, $db_user, $db_pass, '', 1 );

			# Check the connection and respond to errors
			if( $wgDatabase->isOpen() ) {
				# Seems OK
				$ok = true;
				$wgDBadminuser = $db_user;
				$wgDBadminpassword = $db_pass;
				echo( "success.</li>\n" );
				$wgDatabase->ignoreErrors( true );
				$myver = $wgDatabase->getServerVersion();
			} else {
				# There were errors, report them and back out
				$ok = false;
				$errno = mysql_errno();
				$errtx = htmlspecialchars( mysql_error() );
				switch( $errno ) {
					case 1045:
					case 2000:
						echo( "failed due to authentication errors. Check passwords.</li>" );
						if( $conf->Root ) {
							# The superuser details are wrong
							$errs["RootUser"] = "Check username";
							$errs["RootPW"] = "and password";
						} else {
							# The regular user details are wrong
							$errs["DBuser"] = "Check username";
							$errs["DBpassword"] = "and password";
						}
						break;
					case 2002:
					case 2003:
					default:
						# General connection problem
						echo( "failed with error [$errno] $errtx.</li>\n" );
						$errs["DBserver"] = "Connection failed";
						break;
				} # switch
			} #conn. att.
		
			if( !$ok ) { continue; }

		} else /* not mysql */ {
			error_reporting( E_ALL );
			$wgSuperUser = '';
			## Possible connect as a superuser
			if( $conf->RootPW != '-' and strlen($conf->RootPW)) {
				$wgDBsuperuser = $conf->RootUser;
				echo( "<li>Attempting to connect to database \"postgres\" as superuser \"$wgDBsuperuser\"..." );
				$wgDatabase = $dbc->newFromParams($wgDBserver, $wgDBsuperuser, $conf->RootPW, "postgres", 1);
				if (!$wgDatabase->isOpen()) {
					print " error: " . $wgDatabase->lastError() . "</li>\n";
					$errs["DBserver"] = "Could not connect to database as superuser";
					$errs["RootUser"] = "Check username";
					$errs["RootPW"] = "and password";
					continue;
				}
			}
			echo( "<li>Attempting to connect to database \"$wgDBname\" as \"$wgDBuser\"..." );
			$wgDatabase = $dbc->newFromParams($wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 1);
			if (!$wgDatabase->isOpen()) {
				print " error: " . $wgDatabase->lastError() . "</li>\n";
			} else {
				$myver = $wgDatabase->getServerVersion();
			}
		}

		if ( !$wgDatabase->isOpen() ) {
			$errs["DBserver"] = "Couldn't connect to database";
			continue;
		}

		print "<li>Connected to $myver";
		if ($conf->DBtype == 'mysql') {
			if( version_compare( $myver, "4.0.14" ) < 0 ) {
				dieout( " -- mysql 4.0.14 or later required. Aborting." );
			}
			$mysqlNewAuth = version_compare( $myver, "4.1.0", "ge" );
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

			@$sel = $wgDatabase->selectDB( $wgDBname );
			if( $sel ) {
				print "<li>Database <tt>" . htmlspecialchars( $wgDBname ) . "</tt> exists</li>\n";
			} else {
				$err = mysql_errno();
				if ( $err != 1049 ) {
					print "<ul><li>Error selecting database $wgDBname: $err " .
						htmlspecialchars( mysql_error() ) . "</li></ul>";
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
		}
		else if ($conf->DBtype == 'postgres') {
			if( version_compare( $myver, "PostgreSQL 8.0" ) < 0 ) {
				dieout( " <b>Postgres 8.0 or later is required</b>. Aborting.</li></ul>" );
			}
		}

		if( $wgDatabase->tableExists( "cur" ) || $wgDatabase->tableExists( "revision" ) ) {
			print "<li>There are already MediaWiki tables in this database. Checking if updates are needed...</li>\n";

			# Create user if required (todo: other databases)
			if ( $conf->Root && $conf->DBtype == 'mysql') {
				$conn = $dbc->newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 1 );
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
			if ($conf->DBtype == 'mysql') {
				if( $wgDBmysql5 ) {
					print " using MySQL 5 table defs...";
					dbsource( "../maintenance/mysql5/tables.sql", $wgDatabase );
				} else {
					print " using MySQL 4 table defs...";
					dbsource( "../maintenance/tables.sql", $wgDatabase );
				}
				dbsource( "../maintenance/interwiki.sql", $wgDatabase );
			} else if ($conf->DBtype == 'postgres') {
				$wgDatabase->setup_database();
			}
			else {
				$errs["DBtype"] = "Do not know how to handle database type '$conf->DBtype'";
				continue;
			}

			print " done.</li>\n";

			print "<li>Initializing data...</li>\n";
			$wgDatabase->insert( 'site_stats',
				array ( 'ss_row_id'        => 1,
						'ss_total_views'   => 0,
						'ss_total_edits'   => 0,
						'ss_good_articles' => 0 ) );

			# Set up the "regular user" account *if we can, and if we need to*
			if( $conf->Root and $conf->DBtype == 'mysql') {
				# See if we need to
				$wgDatabase2 = $dbc->newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 1 );
				if( $wgDatabase2->isOpen() ) {
					# Nope, just close the test connection and continue
					$wgDatabase2->close();
					echo( "<li>User $wgDBuser exists. Skipping grants.</li>\n" );
				} else {
					# Yes, so run the grants
					echo( "<li>Granting user permissions to $wgDBuser on $wgDBname..." );
					dbsource( "../maintenance/users.sql", $wgDatabase );
					echo( "success.</li>\n" );
				}
			}

			if( $conf->SysopName ) {
				$u = User::newFromName( $conf->getSysopName() );
				if ( !$u ) {
					print "<li><strong class=\"error\">Warning:</strong> Skipped sysop account creation - invalid username!</li>\n";
				}
				else if ( 0 == $u->idForName() ) {
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

			initialiseMessages( false, false, 'printListItem' );
		}

		/* Write out the config file now that all is well */
		print "<li style=\"list-style: none\">\n";
		print "<p>Creating LocalSettings.php...</p>\n\n";
		$localSettings = "<" . "?php$endl$local$endl?" . ">\r\n";
		// Fix up a common line-ending problem (due to CVS on Windows)
		$localSettings = str_replace( "\r\n", "\n", $localSettings );
		$f = fopen( "LocalSettings.php", 'xt' );

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
		print "</li>\n";

	} while( false );
}
?>
</ul>


<?php

if( count( $errs ) ) {
	/* Display options form */

	if( $conf->posted ) {
		echo "<p class='error-top'>Something's not quite right yet; make sure everything below is filled out correctly.</p>\n";
	}
?>

<form action="index.php" name="config" method="post">


<h2>Site config</h2>

<div class="config-section">
	<div class="config-input">
		<?php
		aField( $conf, "Sitename", "Wiki name:" );
		?>
	</div>
	<p class="config-desc">
		Preferably a short word without punctuation, i.e. "Wikipedia".<br />
		Will appear as the namespace name for "meta" pages, and throughout the interface.
	</p>

	<div class="config-input">
		<?php
		aField( $conf, "EmergencyContact", "Contact e-mail:" );
		?>
	</div>
	<p class="config-desc">
		Displayed to users in some error messages, used as the return address for password reminders, and used as the default sender address of e-mail notifications.
	</p>

	<div class="config-input">
		<label class='column' for="LanguageCode">Language:</label>
		<select id="LanguageCode" name="LanguageCode">

		<?php
			$list = getLanguageList();
			foreach( $list as $code => $name ) {
				$sel = ($code == $conf->LanguageCode) ? 'selected="selected"' : '';
				echo "\t\t<option value=\"$code\" $sel>$name</option>\n";
			}
		?>
		</select>
	</div>
	<p class="config-desc">
		Select the language for your wiki's interface. Some localizations aren't fully complete. Unicode (UTF-8) used for all localizations.
	</p>

	<div class="config-input">
		<label class='column'>Copyright/license:</label>

		<ul class="plain">
		<li><?php aField( $conf, "License", "No license metadata", "radio", "none" ); ?></li>
		<li><?php aField( $conf, "License", "GNU Free Documentation License 1.2 (Wikipedia-compatible)", "radio", "gfdl" ); ?></li>
		<li><?php
			aField( $conf, "License", "A Creative Commons license - ", "radio", "cc" );
			$partner = "MediaWiki";
			$exit = urlencode( "$wgServer{$conf->ScriptPath}/config/index.php?License=cc&RightsUrl=[license_url]&RightsText=[license_name]&RightsCode=[license_code]&RightsIcon=[license_button]" );
			$icon = urlencode( "$wgServer$wgUploadPath/wiki.png" );
			$ccApp = htmlspecialchars( "http://creativecommons.org/license/?partner=$partner&exit_url=$exit&partner_icon_url=$icon" );
			print "<a href=\"$ccApp\" target='_blank'>choose</a>";
			?>
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
	</div>
	<p class="config-desc">
		A notice, icon, and machine-readable copyright metadata will be displayed for the license you pick.
	</p>


	<div class="config-input">
		<?php aField( $conf, "SysopName", "Admin username:" ) ?>
	</div>
	<div class="config-input">
		<?php aField( $conf, "SysopPass", "Password:", "password" ) ?>
	</div>
	<div class="config-input">
		<?php aField( $conf, "SysopPass2", "Password confirm:", "password" ) ?>
	</div>
	<p class="config-desc">
		An admin can lock/delete pages, block users from editing, and do other maintenance tasks.<br />
		A new account will be added only when creating a new wiki database.
	</p>

	<div class="config-input">
		<label class='column'>Shared memory caching:</label>

		<ul class="plain">
		<li><?php aField( $conf, "Shm", "No caching", "radio", "none" ); ?></li>
		<?php
			if ( $conf->turck ) {
				echo "<li>";
				aField( $conf, "Shm", "Turck MMCache", "radio", "turck" );
				echo "</li>";
			}
			if ( $conf->apc ) {
				echo "<li>";
				aField( $conf, "Shm", "APC", "radio", "apc" );
				echo "</li>";
			}
			if ( $conf->eaccel ) {
				echo "<li>";
				aField( $conf, "Shm", "eAccelerator", "radio", "eaccel" );
				echo "</li>";
			}
		?>
		<li><?php aField( $conf, "Shm", "Memcached", "radio", "memcached" ); ?></li>
		</ul>
		<div style="clear:left"><?php aField( $conf, "MCServers", "Memcached servers:", "text" ) ?></div>
	</div>
	<p class="config-desc">
		Using a shared memory system such as Turck MMCache, APC, eAccelerator, or Memcached 
		will speed up MediaWiki significantly. Memcached is the best solution but needs to be
		installed. Specify the server addresses and ports in a comma-separted list. Only
		use Turck shared memory if the wiki will be running on a single Apache server.
	</p>
</div>

<h2>E-mail, e-mail notification and authentication setup</h2>

<div class="config-section">
	<div class="config-input">
		<label class='column'>E-mail features (global):</label>
		<ul class="plain">
		<li><?php aField( $conf, "Email", "Enabled", "radio", "email_enabled" ); ?></li>
		<li><?php aField( $conf, "Email", "Disabled", "radio", "email_disabled" ); ?></li>
		</ul>
	</div>
	<p class="config-desc">
		Use this to disable all e-mail functions (password reminders, user-to-user e-mail and e-mail notifications)
		if sending mail doesn't work on your server.
	</p>

	<div class="config-input">
		<label class='column'>User-to-user e-mail:</label>
		<ul class="plain">
		<li><?php aField( $conf, "Emailuser", "Enabled", "radio", "emailuser_enabled" ); ?></li>
		<li><?php aField( $conf, "Emailuser", "Disabled", "radio", "emailuser_disabled" ); ?></li>
		</ul>
	</div>
	<p class="config-desc">
		The user-to-user e-mail feature (Special:Emailuser) lets the wiki act as a relay to allow users to exchange e-mail without publicly advertising their e-mail address.
	</p>
	<div class="config-input">
		<label class='column'>E-mail notification about changes:</label>
		<ul class="plain">
		<li><?php aField( $conf, "Enotif", "Disabled", "radio", "enotif_disabled" ); ?></li>
		<li><?php aField( $conf, "Enotif", "Enabled for changes to user discussion pages only", "radio", "enotif_usertalk" ); ?></li>
		<li><?php aField( $conf, "Enotif", "Enabled for changes to user discussion pages, and to pages on watchlists (not recommended for large wikis)", "radio", "enotif_allpages" ); ?></li>
		</ul>
	</div>
	<div class="config-desc">
		<p>
		For this feature to work, an e-mail address must be present for the user account, and the notification
		options in the user's preferences must be enabled. Also note the 
		authentication option below. When testing the feature, keep in mind that your own changes will never trigger notifications to be sent to yourself.</p>

		<p>There are additional options for fine tuning in /includes/DefaultSettings.php; copy these to your LocalSettings.php and edit them there to change them.</p>
	</div>

	<div class="config-input">
		<label class='column'>E-mail address authentication:</label>
		<ul class="plain">
		<li><?php aField( $conf, "Eauthent", "Disabled", "radio", "eauthent_disabled" ); ?></li>
		<li><?php aField( $conf, "Eauthent", "Enabled", "radio", "eauthent_enabled" ); ?></li>
		</ul>
	</div>
	<div class="config-desc">
		<p>If this option is enabled, users have to confirm their e-mail address using a magic link sent to them whenever they set or change it, and only authenticated e-mail addresses can receive mails from other users and/or
		change notification mails. Setting this option is <B>recommended</B> for public wikis because of potential abuse of the e-mail features above.</p>
	</div>

</div>

<h2>Database config</h2>

<div class="config-section">
<div class="config-input">
		<label class='column'>Database type:</label>
<?php if (isset($errs['DBpicktype'])) print "<span class='error'>$errs[DBpicktype]</span>\n"; ?>
		<ul class='plain'><?php database_picker($conf) ?></ul>
	</div>

	<div class="config-input" style="clear:left"><?php
		aField( $conf, "DBserver", "Database host:" );
	?></div>
	<p class="config-desc">
		If your database server isn't on your web server, enter the name or IP address here.
	</p>

	<div class="config-input"><?php
		aField( $conf, "DBname", "Database name:" );
	?></div>
	<div class="config-input"><?php
		aField( $conf, "DBuser", "DB username:" );
	?></div>
	<div class="config-input"><?php
		aField( $conf, "DBpassword", "DB password:", "password" );
	?></div>
	<div class="config-input"><?php
		aField( $conf, "DBpassword2", "DB password confirm:", "password" );
	?></div>
	<p class="config-desc">
		If you only have a single user account and database available,
		enter those here. If you have database root access (see below)
		you can specify new accounts/databases to be created. This account 
		will not be created if it pre-exists. If this is the case, ensure that it
		has SELECT, INSERT, UPDATE and DELETE permissions on the MediaWiki database.
	</p>

	<div class="config-input">
		<?php
		aField( $conf, "RootUser", "Superuser account:", "superuser" );
		?>
	</div>
	<div class="config-input">
		<?php
		aField( $conf, "RootPW", "Superuser password:", "password" );
		?>
	</div>
	
	<p class="config-desc">
		If the database user specified above does not exist, or does not have access to create
		the database (if needed) or tables within it, please provide details of a superuser account,
		such as <strong>root</strong>, which does. Leave the password set to <strong>-</strong> if this is not needed.
	</p>

	<?php database_switcher('mysql'); ?>
	<div class="config-input"><?php
		aField( $conf, "DBprefix", "Database table prefix:" );
	?></div>
	<div class="config-desc">
		<p>If you need to share one database between multiple wikis, or
		MediaWiki and another web application, you may choose to
		add a prefix to all the table names to avoid conflicts.</p>

		<p>Avoid exotic characters; something like <tt>mw_</tt> is good.</p>
	</div>

	<div class="config-input"><label class="column">Database charset</label>
		<div>Select one:</div>
		<ul class="plain">
		<li><?php aField( $conf, "DBmysql5", "Backwards-compatible UTF-8", "radio", "false" ); ?></li>
		<li><?php aField( $conf, "DBmysql5", "Experimental MySQL 4.1/5.0 UTF-8", "radio", "true" ); ?></li>
		</ul>
	</div>
	<p class="config-desc">
		<b>EXPERIMENTAL:</b> You can enable explicit Unicode charset support
		for MySQL 4.1 and 5.0 servers. This is not well tested and may
		cause things to break. <b>If upgrading an older installation, leave
		in backwards-compatible mode.</b>
	</p>
	</div>

	<?php database_switcher('postgres'); ?>
	<div class="config-input"><?php
		aField( $conf, "DBport", "Database port:" );
	?></div>
	<div class="config-input"><?php
		aField( $conf, "DBmwschema", "Schema for mediawiki:" );
	?></div>
	<div class="config-input"><?php
		aField( $conf, "DBts2schema", "Schema for tsearch2:" );
	?></div>
	<div class="config-desc">
		<p>The username specified above will have it's search path set to the above schemas, 
		so it is recommended that you create a new user.</p>
	</div>
	</div>

	<div class="config-input" style="padding:2em 0 3em">
		<label class='column'>&nbsp;</label>
		<input type="submit" value="Install MediaWiki!" class="btn-install" />
	</div>

</div>

<script type="text/javascript">
window.onload = toggleDBarea('<?php echo $conf->DBtype; ?>',
<?php
	## If they passed in a root user name, don't populate it on page load
	echo strlen(importPost('RootUser', '')) ? 0 : 1;
?>);
</script>

</form>

<?php
}

/* -------------------------------------------------------------------------------------- */
function writeSuccessMessage() {
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
		echo "<p><span style='font-weight:bold;color:green;font-size:110%'>Installation successful!</span> Move the <tt>config/LocalSettings.php</tt> file into the parent directory, then follow
			<strong><a href='../index.php'>this link</a></strong> to your wiki.</p>\n";
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
		case 'apc':
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

	$localsettings = "
# This file was automatically generated by the MediaWiki installer.
# If you make manual changes, please keep track in case you need to
# recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.

# If you customize your file layout, set \$IP to the directory that contains
# the other MediaWiki files. It will be used as a base to locate files.
if( defined( 'MW_INSTALL_PATH' ) ) {
	\$IP = MW_INSTALL_PATH;
} else {
	\$IP = dirname( __FILE__ );
}

\$path = array( \$IP, \"\$IP/includes\", \"\$IP/languages\" );
set_include_path( implode( PATH_SEPARATOR, \$path ) . PATH_SEPARATOR . get_include_path() );

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

\$wgScriptPath       = \"{$slconf['ScriptPath']}\";
\$wgScript           = \"\$wgScriptPath/index.php\";
\$wgRedirectScript   = \"\$wgScriptPath/redirect.php\";

## For more information on customizing the URLs please see:
## http://meta.wikimedia.org/wiki/Eliminating_index.php_from_the_url

## 'Pretty' URLs using PATH_INFO work on most configurations with
## PHP configured as an Apache module.
{$pretty}\$wgArticlePath      = \"\$wgScript/\$1\";

## If using PHP as a CGI module, the ?title= style might have to be used
## depending on the configuration. If it fails, try enabling the option
## cgi.fix_pathinfo in php.ini, then switch to pretty URLs.
{$ugly}\$wgArticlePath      = \"\$wgScript?title=\$1\";

\$wgStylePath        = \"\$wgScriptPath/skins\";
\$wgStyleDirectory   = \"\$IP/skins\";
\$wgLogo             = \"\$wgStylePath/common/images/wiki.png\";

\$wgUploadPath       = \"\$wgScriptPath/images\";
\$wgUploadDirectory  = \"\$IP/images\";

\$wgEnableEmail      = $enableemail;
\$wgEnableUserEmail  = $enableuseremail;

\$wgEmergencyContact = \"{$slconf['EmergencyContact']}\";
\$wgPasswordSender = \"{$slconf['PasswordSender']}\";

## For a detailed description of the following switches see
## http://meta.wikimedia.org/Enotif and http://meta.wikimedia.org/Eauthent
## There are many more options for fine tuning available see
## /includes/DefaultSettings.php
## UPO means: this is also a user preference option
\$wgEnotifUserTalk = $enotifusertalk; # UPO
\$wgEnotifWatchlist = $enotifwatchlist; # UPO
\$wgEmailAuthentication = $eauthent;

\$wgDBtype           = \"{$slconf['DBtype']}\";
\$wgDBserver         = \"{$slconf['DBserver']}\";
\$wgDBname           = \"{$slconf['DBname']}\";
\$wgDBuser           = \"{$slconf['DBuser']}\";
\$wgDBpassword       = \"{$slconf['DBpassword']}\";
\$wgDBport           = \"{$slconf['DBport']}\";
\$wgDBprefix         = \"{$slconf['DBprefix']}\";

# Schemas for Postgres
\$wgDBmwschema       = \"{$slconf['DBmwschema']}\";
\$wgDBts2schema      = \"{$slconf['DBts2schema']}\";

# Experimental charset support for MySQL 4.1/5.0.
\$wgDBmysql5 = {$conf->DBmysql5};

## Shared memory settings
\$wgMainCacheType = $cacheType;
\$wgMemCachedServers = $mcservers;

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
\$wgEnableUploads       = false;
\$wgUseImageResize      = {$conf->UseImageResize};
{$magic}\$wgUseImageMagick = true;
{$magic}\$wgImageMagickConvertCommand = \"{$convert}\";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
{$hashedUploads}\$wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
\$wgUseTeX           = false;
\$wgMathPath         = \"{\$wgUploadPath}/math\";
\$wgMathDirectory    = \"{\$wgUploadDirectory}/math\";
\$wgTmpDirectory     = \"{\$wgUploadDirectory}/tmp\";

\$wgLocalInterwiki   = \$wgSitename;

\$wgLanguageCode = \"{$slconf['LanguageCode']}\";

\$wgProxyKey = \"$secretKey\";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook':
\$wgDefaultSkin = 'monobook';

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

# When you make changes to this configuration file, this will make
# sure that cached pages are cleared.
\$configdate = gmdate( 'YmdHis', @filemtime( __FILE__ ) );
\$wgCacheEpoch = max( \$wgCacheEpoch, \$configdate );
	"; ## End of setting the $localsettings string

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

$radioCount = 0;

function aField( &$conf, $field, $text, $type = "text", $value = "", $onclick = '' ) {
	global $radioCount;
	if( $type != "" ) {
		$xtype = "type=\"$type\"";
	} else {
		$xtype = "";
	}

	$id = $field;
	$nolabel = ($type == "radio") || ($type == "hidden");

	if ($type == 'radio')
		$id .= $radioCount++;

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
	echo "\t\t<input $xtype name=\"$field\" id=\"$id\" class=\"iput-$type\" $checked ";
	if ($onclick) {
		echo " onclick='toggleDBarea(\"$value\",1)' " ;
	}
	echo "value=\"";
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
		require_once( "languages/Names.php" );
	}

	$codes = array();

	$d = opendir( "../languages/messages" );
	/* In case we are called from the root directory */
	if (!$d)
		$d = opendir( "languages/messages");
	while( false !== ($f = readdir( $d ) ) ) {
		$m = array();
		if( preg_match( '/Messages([A-Z][a-z_]+)\.php$/', $f, $m ) ) {
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
		$command = "$loc".DIRECTORY_SEPARATOR."$name";
		if (file_exists($command)) {
			if (!$versioninfo)
				return $command;

			$file = str_replace('$1', $command, $versioninfo[0]);
			if (strstr(`$file`, $versioninfo[1]) !== false)
				return $command;
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
	if ( !$errstr && count( $hostport ) != 2 ) {
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

function database_picker($conf) {
	global $ourdb;
	print "\n";
	foreach(array_keys($ourdb) as $db) {
		if ($ourdb[$db]['havedriver']) {
			print "<li>";
			aField( $conf, "DBtype", $ourdb[$db]['fullname'], 'radio', $db, 'onclick');
			print "</li>\n";
		}
	}
	print "\n";
}

function database_switcher($db) {
	global $ourdb;
	$color = $ourdb[$db]['bgcolor'];
	$full = $ourdb[$db]['fullname'];
	print "<div id='$db' style='display:none; background: $color'>\n";
	print "<h3>$full specific options:</h3>\n";
}

function printListItem( $item ) {
	print "<li>$item</li>";
}

?>

	<div class="license">
	<hr>
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
	 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
	 or <a href="http://www.gnu.org/copyleft/gpl.html">read it online</a></p>
	</div>

</div></div></div>


<div id="column-one">
	<div class="portlet" id="p-logo">
	  <a style="background-image: url(../skins/common/images/mediawiki.png);"
	    href="http://www.mediawiki.org/"
	    title="Main Page"></a>
	</div>
	<script type="text/javascript"> if (window.isMSIE55) fixalpha(); </script>
	<div class='portlet'><div class='pBody'>
		<ul>
			<li><strong><a href="http://www.mediawiki.org/">MediaWiki home</a></strong></li>
			<li><a href="../README">Readme</a></li>
			<li><a href="../RELEASE-NOTES">Release notes</a></li>
			<li><a href="../docs/">Documentation</a></li>
			<li><a href="http://meta.wikipedia.org/wiki/MediaWiki_User's_Guide">User's Guide</a></li>
			<li><a href="http://meta.wikimedia.org/wiki/MediaWiki_FAQ">FAQ</a></li>
		</ul>
		<p style="font-size:90%;margin-top:1em">MediaWiki is Copyright &copy; 2001-2006 by Magnus Manske, Brion Vibber, Lee Daniel Crocker, Tim Starling, Erik M&ouml;ller, Gabriel Wicke and others.</p>
	</div></div>
</div>

</div>

</body>
</html>
