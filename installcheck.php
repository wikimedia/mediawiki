<?php
/**
 * Hallo Welt! MediaWiki Distribution
 * @package BlueSpice
 * @copyright Copyright (C) 2016 Hallo Welt! GmbH
 * @author Marc Reymann
 * @version $Id: $
 *
 * Install check for BlueSpice.
 * This file checks several values and settings on a
 * LAMP/WAMP machine which are needed to run BlueSpice.
 */

//TODO: Check if BlueSpiceExtensions.php.template was renamed
//TODO: Check if Windows users use 127.0.0.1 instead of localhost
//TODO: UTF-8 check via post to PHP_SELF
//DONE: Make this work on IIS without modifications
//TODO: Restrict access to localhost by default?
//DONE: Check error_logging configuration
//DONE: is_writable() sometimes doesn't work on Windows, try to actually create and delete a file
//DONE: Check for writable session_save_path
//TODO: Check for disabled functions (e. g. ini_set())
//TODO: Check for safe mode, open_basedir (Elgg's data dir), magic_quotes_gpc, etc.
//TODO: Check if folders with .htaccess can be accessed (Apache via .htaccess, AllowOverride Limit; IIS via GUI).
//TODO: Check if impersonation is active on Windows
//DONE: Add server sanity checks (APC, wincache, register_globals)
//      cf. http://www.mediawiki.org/wiki/PHP_configuration
//      Interesting stuff: http://www.php.net/manual/de/ref.info.php

// Bail out if not in $IP
if (!file_exists("LocalSettings.php")) exit("This file must be placed where your LocalSettings.php is.");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BlueSpice-Install-Check</title>
<link rel="icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGsSURBVDjLjZNLSwJRFICtFv2AgggS2vQLDFvVpn0Pi4iItm1KItvWJqW1pYsRemyyNILARbZpm0WtrJ0kbmbUlHmr4+t0z60Z7oSSAx935txzvrlPBwA4EPKMEVwE9z+ME/qtOkbgqtVqUqPRaDWbTegE6YdQKBRkJazAjcWapoGu6xayLIMoilAoFKhEEAQIh8OWxCzuQwEmVKtVMAyDtoiqqiBJEhSLRSqoVCqAP+E47keCAvfU5sDQ8MRs/OYNtr1x2PXdwuJShLLljcFlNAW5HA9khLYp0TUhSYMLHm7PLEDS7zyw3ybRqyfg+TyBtwl2sDP1nKWFiUSazFex3tk45sXjL1Aul20CGTs+syVY37igBbwg03eMsfH9gwSsrZ+Doig2QZsdNiZmMkVrKmwc18azHKELyQrOMEHTDJp8HXu1hostG8dY8PiRngdWMEq467ZwbDxwlIR8XrQLcBvn5k9Gpmd8fn/gHlZWT20C/D4k8eTDB3yVFKjX6xSbgD1If8G970Q3QbvbPehAyxL8SibJEdaxo5dikqvS28sInCjp4Tqb4NV3fgPirZ4pD4KS4wAAAABJRU5ErkJggg==" type="image/png" />
<style type="text/css">
  body
  {
	color:black;
	background-color:silver;
	font-family:Verdana,sans-serif;
	font-size:12px;
  }
  #wrapper { width:940px; margin:18px auto 18px auto; padding:0 12px 18px 12px; background-color:#ffffff; border:1px solid #999999; }
  #infobox { position:fixed; z-index:3; bottom:30px; right:50px; width:auto; height:auto; text-align:center; color:#666666; text-shadow:white 1px 1px 1px; }
  #waitbox { position:fixed; z-index:4; bottom:50%; right:50%; width:auto; height:auto; text-align:center; color:#000000; font-weight:bold;
             background-color:#ffffff; border:1px solid #999999; padding:10px; font-size:20px; -moz-border-radius:10px; }
  h2 { color:blue; font-size:14px; }
  tt { background-color:yellow;  }
  pre { font-family:Consolas,monospace; font-size:14px; margin:6px; }
  .ok { color:green; font-weight:bold; }
  .warn { color:#ffa500; font-weight:bold; }
  .fail { color:red; font-weight:bold; }
  .command{ overflow:auto; width:auto; padding:6px; border-width:2px; border-color:silver; border-style:solid; -moz-border-radius:10px; }

</style>
</head>
<body onload="document.getElementById('waitbox').style.display='none';">
<div id="waitbox">Now checking your system...<br/>This may take a few seconds.</div>
<?php flush(); ?>
<div id="infobox">BlueSpice Installation Checker<br/>Hallo Welt! GmbH</div>
<div id="wrapper">
<?php
$d = DIRECTORY_SEPARATOR;
$p = PATH_SEPARATOR;

// General MediaWiki requirements check. Cf. http://www.mediawiki.org/wiki/Installation

if (version_compare(PHP_VERSION, '5.3.2', '<')) {
    echo 'BlueSpice works with PHP 5.3.2 or higher. Your version: ' . PHP_VERSION . "\n";
    exit();
}

// -------------- Tools -----------------

echo( "\n\n<h2 id=\"diag\">Diagnostics:</h2>" );

echo( "<ul>" );
//echo( '<li><a href="' . $_SERVER['PHP_SELF'] . '?action=diag#diag" onclick="window.location.reload(true);">Run BlueSpice diagnostics</a></li>' );
echo( '<li><a href="' . $_SERVER['PHP_SELF'] . '?action=diag">Run BlueSpice diagnostics</a></li>' );
echo( "</ul>" );
echo( "\n<br /><hr /><br />\n" );


function action_diag() {
// #########################  diagnostics  ##############################

if( !file_exists ("extensions/BlueSpiceFoundation/config/nm-settings.php") )
{
	echo( "<span class=\"warn\">WARNING!</span> BlueSpice seems not to be installed yet. Skipping BlueSpice diags." );
}
else
{
	// Mail server
	$smtp_host = ini_get( "SMTP" );
	$smtp_port = ini_get( "smtp_port" );
	echo( "<h2>Checking mail server:</h2>" );
	echo( "Trying $smtp_host at port $smtp_port.<br/>" );
	$smtp = @fsockopen( "tcp://$smtp_host", $smtp_port, $errno, $errstr, 3 ); // last value is timeout in sec.
	if( !$smtp )
	{
		echo( "<span class=\"warn\">WARNING!</span> Mail will not work. Error message: <i>" . $errno . ": " . $errstr . "</i><br/>" );
	}
else
{
	echo( "<span class=\"ok\">OK</span> - " . htmlspecialchars(fread( $smtp, 256 )) . "<br/>" ); 
	fclose( $smtp );
}
}
} // action_diag end


// Commands to execute?

if (isset($_GET['action'])) {
	echo( "<h2>Command output:</h2>" );
	echo( "<div class=\"command\"><pre>" );
	switch ($_GET['action']) {
		case "diag":
			action_diag();
			break;
		case "foo":
			echo( "This is some dummy text. 0123456789.\nThis is some dummy text. 0123456789." );
			break;
		default:
			echo "unknown command - nothing done";
	}
	echo( "<pre></div><br />" );
	echo( '<a href="' . $_SERVER['PHP_SELF'] . '">&lt;&lt;&lt; BACK</a> --- ' );
	echo( '<a href="' . $_SERVER['PHP_SELF'] . '?action=diag' . '">RETRY</a><hr />' );
}


// PHP extensions
echo( "<h2>Checking PHP extensions:</h2>" );
$requiredExt = array();
$requiredExt[] = array( "curl",     "<span class=\"warn\">WARNING!</span> This extension is needed if you want to use curl in the extended search." );
$requiredExt[] = array( "dom",      "<span class=\"warn\">WARNING!</span> This extension is needed if you want to use ImageMaps." );
$requiredExt[] = array( "gd",       "<span class=\"fail\">FAILED!</span>" );
$requiredExt[] = array( "json",     "<span class=\"fail\">FAILED!</span>" );
$requiredExt[] = array( "mbstring", "<span class=\"fail\">FAILED!</span>" );
$requiredExt[] = array( "mysqli",    "<span class=\"fail\">FAILED!</span>" );
$requiredExt[] = array( "pcre",     "<span class=\"fail\">FAILED!</span>" );
$requiredExt[] = array( "tidy",     "<span class=\"fail\">FAILED!</span>" );
#$requiredExt[] = array( "openssl",  "<span class=\"warn\">WARNING!</span> This extension is needed if you want to connect to SSL/TLS secured services." );
#$requiredExt[] = array( "ldap",     "<span class=\"warn\">WARNING!</span> This extension is needed if you want to connect to an LDAP/AD server." );

checkExt( $requiredExt );


// FileInfo Magic Database
if( extension_loaded( "fileinfo" ) ) {
	echo( "<br/>\nChecking fileinfo usability: " );
	$finfo = @finfo_open(FILEINFO_MIME); // try to open magic file at (obscure!) default locations
	if (!$finfo) {
    		echo( "<span class=\"fail\">FAILED!</span> Opening fileinfo database failed.<br/>\n" );
	}
	else {
		echo( "<span class=\"ok\">OK</span>\n" );
		echo( " (Example output for " . __FILE__ . ": <tt>" . finfo_file($finfo, __FILE__) . "</tt>)<br/>\n");
	}
}


// PHP file uploads
echo( "<h2>Checking PHP file uploads:</h2>" );
if( ini_get('file_uploads') ) {
	$uploaddir = ini_get('upload_tmp_dir');
	if( $uploaddir ) {
		checkWrite( array($uploaddir.$d) );
	}
	else {
		echo( "<span class=\"warn\">WARNING!</span> File upload is enabled but upload_tmp_dir is not set. Make sure your system's temp dir is writable by the web server." );
	}	
}
else {
	echo( "<span class=\"fail\">FAILED!</span> File uploads disabled! Set <tt>file_uploads = On</tt> in your php.ini." );
}


// PHP session save path
echo( "<h2>Checking PHP session save path:</h2>" );
if( ini_get('session.save_path') ) {
	$sessdir = ini_get('session.save_path');
	if( $sessdir ) {
		checkWrite( array($sessdir.$d) );
	}
	else {
		echo( "<span class=\"fail\">FAIL!</span>session.save_path is set ($sessdir) but is not writable by the web server." );
	}	
}
else {
	echo( "<span class=\"fail\">FAILED!</span> session.save_path is not set. Set <tt>session.save_path</tt> to point to a path writable by the web server in your php.ini." );
}

/*
// PHP error logging
echo( "<h2>Checking PHP error log:</h2>" );
$errlog = ini_get('error_log');
if( $errlog ) {
	if ( strtolower($errlog) == "syslog" ) {
		echo( "Logging to syslog / event log." );
	}
	else {
		checkWrite( array($errlog) );
		if (is_readable($errlog)) echo ( "Filesize: " . format_bytes(filesize($errlog)) );
	}
}
else {
	echo( "<span class=\"warn\">WARNING!</span> error_log is not set. Set <tt>error_log</tt> to point to a path writable by the web server in your php.ini." );
}
 */


// PHP check php.ini's numeric, bool values (TODO: string)
echo( "<h2>Checking php.ini values:</h2>" );
$requiredVal = array();
$requiredVal[] = array( "memory_limit",           "64M"    , "intmin");
$requiredVal[] = array( "max_execution_time",     "120"    , "intmin");
$requiredVal[] = array( "post_max_size",          "32M"    , "intmin");
$requiredVal[] = array( "upload_max_filesize",    "32M"    , "intmin");
$requiredVal[] = array( "register_globals",       "0"      , "bool");
#$requiredVal[] = array( "error_reporting",        "6135"   , "intmax");
#$requiredVal[] = array( "session.gc_maxlifetime", "28800"  , "intmin");
#$requiredVal[] = array( "pcre.backtrack_limit",   "500000" , "intmin");

checkVal( $requiredVal );


// Files and directories writable by web server
echo( "<h2>Checking write access:</h2>" );
$reqWrite = array(	"cache/" ,"images/",
			"extensions/BlueSpiceFoundation/config/",
			"extensions/BlueSpiceFoundation/data/" );
checkWrite( $reqWrite );


// Check template renaming
echo( "<h2>Checking for necessary files</h2>" );
$reqMove = array(	"extensions/BlueSpiceExtensions/BlueSpiceExtensions.php",
					"LocalSettings.BlueSpice.php" );
checkMove( $reqMove );


// Check if display_errors is on and displays warnings (which breaks some stuff)
echo( "<h2>Checking error handling:</h2>" );
if ( false != ini_get( "display_errors" ) && ini_get( "error_reporting" ) > 6135 ) {
	echo( "<span class=\"fail\">FAILED!</span> Your system is configured to display warning messages. Either set <tt>display_errors</tt> to false or lower the value for <tt>error_reporting</tt> in your php.ini.\n" );
}
else {
	echo ( "<span class=\"ok\">OK</span>\n" );
}


// There is still a dependency on url_fopen in ExtendedSearch, so check for it:
echo( "<h2>Checking allow_url_fopen:</h2>" );
#@ini_set( 'allow_url_fopen', 1 ); # this is not consistently done in our code, so it must be set in php.ini!
if (ini_get( 'allow_url_fopen' ))
{
	echo( "<span class=\"ok\">OK</span>\n" );

}
else
{
	echo( "<span class=\"fail\">FAILED!</span>allow_url_fopen is set to false. Extended Search will not work. Set <tt>allow_url_fopen = on</tt> in your php.ini." );

}


// -------------- Informational stuff -----------------

echo( "\n<br /><br /><hr /><br />\n" );
echo( "\n\n<h2>Informational:</h2>" );

ob_start();
phpinfo( INFO_GENERAL );
$info_raw = ob_get_contents();
ob_end_clean();
echo( "<h3>PHP Configuration File:</h3>" );
if (function_exists('php_ini_loaded_file')) {
	$ini_path = php_ini_loaded_file(); $method = 'internal PHP function';
}
else {
	preg_match( '#Loaded Configuration File </td><td class="v">(.*?) </td>#', $info_raw, $matches );
	if( $matches[1] ) { $ini_path = $matches[1]; $method = "method 1"; }
	else
	{
		preg_match( '#Configuration File \(php\.ini\) Path </td><td class="v">(.*?) </td>#', $info_raw, $matches );
		if( $matches[1] ) { $ini_path = $matches[1]; $method = "method 2"; }
	}
}
if( $ini_path )
{
	echo( "Configuration file in use: <tt>" . $ini_path . "</tt> (determined by $method)" );
}
else
{
	echo( "<span class=\"warn\">WARNING!</span> The location of your php.ini could not be determined automatically" );
}

// PHP error logging
echo( "<h3>PHP Error Log:</h3>" );
$errlog = ini_get('error_log');
if( $errlog ) {
	echo "Logging to ";
	if ( strtolower($errlog) == "syslog" ) {
		echo( "syslog / event log." );
	}
	else {
		echo "<tt>$errlog</tt>. Current size: ";
		if (is_readable($errlog)) echo ( format_bytes(filesize($errlog)) );
	}
}
else {
	echo( "Setting \"error_log\" is not set. Are you logging to Apache's log?" );
}

echo( "<h3>Single-Sign-On:</h3>" );
if (!empty($_SERVER['AUTH_TYPE'])) echo( "\$_SERVER['AUTH_TYPE']): ".$_SERVER['AUTH_TYPE']."<br />" );
if (!empty($_SERVER['REMOTE_USER'])) {
	echo( "\$_SERVER['REMOTE_USER']: <tt>".$_SERVER['REMOTE_USER']."</tt> - Looks good!" );	
} 
else {
	echo( "\$_SERVER['REMOTE_USER'] is not set. SSO is not configured correctly and WILL NOT WORK!" );
}


// Time zone
echo( "<h3>Checking time zone:</h3>" );
if ( function_exists( 'date_default_timezone_get' ) ) {
	$reqtz = "Europe/Berlin";
	$curtz = date_default_timezone_get();
	$curtz_short = date('T');
	echo( "Your current time zone is $curtz ($curtz_short)" );
}
else {
	echo( "Your PHP version is too old. Cannot reliably determine time zone.<br/>" );
}

echo( "<h3>Server time:</h3>" );
echo( "<pre>" );
echo date( 'r' ) . "\n";
echo( "</pre>" );

echo( "<h3>PHP version:</h3>" );
echo phpversion() . " (" . php_sapi_name() . ")";

// works for PHP 5 >= 5.2.1
if (version_compare(PHP_VERSION, '5.2.1', '>=')) {
	echo( "<h3>PHP Temp Directory:</h3>" );
	echo sys_get_temp_dir();
}

echo( "<h3>PHP_Uname:</h3>" );
echo( "<pre>" );
echo php_uname();
echo( "</pre>" );

echo( "<h3>PHP Bytecode Caches:</h3>" );
$apc = function_exists('apc_fetch');
$xcache = function_exists( 'xcache_get' );
$eaccel = function_exists( 'eaccelerator_get' );
$opcache = false;
if ( function_exists( 'opcache_get_configuration' ) ) {
	$opcache = opcache_get_configuration();
	$opcache = $opcache["directives"]["opcache.enable"];
}
$wincache = function_exists( 'wincache_ucache_get' );
if ( !( $eaccel || $apc || $xcache || $opcache || $wincache ) ) {
	echo('No bytecode cache detected. Consider using <a href="http://eaccelerator.sourceforge.net">eAccelerator</a>, <a href="http://www.php.net/apc">APC</a>, <a href="http://trac.lighttpd.net/xcache/">XCache</a>, <a href="http://php.net/manual/en/book.opcache.php">OPCache</a> or <a href="http://www.iis.net/download/wincacheforphp">Windows Cache Extension</a> if you\'re on IIS.');
}
else {
	echo('Nice, found a bytecode cache! (detected: ');
	$foundcaches = array();
	foreach ( array ( "apc", "xcache", "eaccel", "opcache", "wincache" ) as $cachetype ) {
		if($$cachetype) $foundcaches[] = $cachetype; 
	}
	echo(implode(",", $foundcaches).')');
	echo("<br />\n");
	echo('For optimal performance make sure you set <tt>$wgMainCacheType = CACHE_ACCEL;</tt> <a href="http://www.mediawiki.org/wiki/Manual:$wgMainCacheType">(more info)</a>'); // TODO: CACHE_ACCEL & WinCache are BROKEN with BS! :-(
}

echo( "<h3>PHP Thread Safety:</h3>" );
preg_match( '#Thread Safety </td><td class="v">(.*?)</td>#', $info_raw, $matches );
if ($matches[1]) {
	echo "Thread Safety: ".$matches[1];
	$threadsafe = !(strpos($matches[1], 'enabled') === false);
}
else {
	echo "Thread Safety information could not be determined automatically.";
}

echo( "<h3>PHP Server API:</h3>" );
preg_match( '#Server API </td><td class="v">(.*?)</td>#', $info_raw, $matches );
if ($matches[1]) {
	echo "Server API: ".$matches[1];
	$fastcgi = !(strpos(strtolower($matches[1]), 'fastcgi') === false);
}
else {
	echo "Server API information could not be determined automatically.";
}

//if (strpos($_SERVER["SERVER_SOFTWARE"], 'IIS') === true) {
//	echo( "IIS" );
//}

echo( "<h3>\$_SERVER['DOCUMENT_ROOT']:</h3>" );
echo '$_SERVER[\'DOCUMENT_ROOT\'] = ' . $_SERVER['DOCUMENT_ROOT'];

echo( "<h3>dirname(__FILE__):</h3>" );
echo 'dirname(__FILE__) = ' . dirname(__FILE__);

echo( "<h3>PHP modules:</h3>" );
echo( "<pre>" );
$phpmod = get_loaded_extensions();
natcasesort($phpmod);
//echo implode(",",array_values($phpmod));
print_r(array_values($phpmod));
echo( "</pre>" );

if (strtolower(substr($_SERVER["SERVER_SOFTWARE"],0,6)) == "apache") {
	print_r(apache_getenv('HTTPD_ROOT'));
	print_r(apache_getenv('SERVER_CONFIG_FILE'));

	echo( "<h3>Apache version:</h3>" );
	echo apache_get_version();

	echo( "<h3>Apache modules:</h3>" );
	echo( "<pre>" );
	$apmod = apache_get_modules();
	natcasesort($apmod);
	print_r(array_values($apmod));
	echo( "</pre>" );
}





// only functions past this point --------------------------------------------------------------------------------

/**
 * Check php.ini values
 * @param array $requiredVal
 * @return void
 */
function checkVal( $requiredVal )
{
	foreach( $requiredVal as $values )
	{
		list( $valname, $reqval, $type ) = $values;
		echo( "Checking: $valname - " );

		switch ($type) {

			case "intmin":
				$curval = ini_get( $valname );
				if( return_bytes( $curval ) < return_bytes( $reqval ))
				{
					echo( "<span class=\"warn\">WARNING!</span> $valname should be at least $reqval (current value: $curval). " );
					echo( "Set <tt>$valname = $reqval</tt> in your php.ini.<br/>\n" );
				}
				else
				{
					echo( "<span class=\"ok\">OK</span> ($curval)<br/>\n" );
				}
				break;

			case "intmax":
				$curval = ini_get( $valname );
				if( return_bytes( $curval ) > return_bytes( $reqval ))
				{
					echo( "<span class=\"warn\">WARNING!</span> $valname should be at most $reqval (current value: $curval). " );
					echo( "Set <tt>$valname = $reqval</tt> in your php.ini.<br/>\n" );
				}
				else
				{
					echo( "<span class=\"ok\">OK</span> ($curval)<br/>\n" );
				}
				break;

			case "bool":
				$reqvaltxt = $reqval ? "On" : "Off";
				$curval = (bool)ini_get( $valname );
				if( $curval != $reqval )
				{
					echo( "<span class=\"warn\">WARNING!</span> $valname should be $reqvaltxt. " );
					echo( "Set <tt>$valname = $reqvaltxt</tt> in your php.ini.<br/>\n" );
				}
				else
				{
					echo( "<span class=\"ok\">OK</span> ($reqvaltxt)<br/>\n" );
				}
				break;

			default:
				echo( "<span class=\"warn\">WARNING!</span> Syntax error: No type given for $valname" );

		}
	}
}

/**
 * Check PHP extensions
 * @param array $requiredExt
 * @return void
 */
function checkExt( $requiredExt )
{
	foreach( $requiredExt as $values )
	{
		list( $extname, $helptext ) = $values;
		echo( "Checking: $extname - " );
		if( extension_loaded( $extname ) )
		{
			echo( "<span class=\"ok\">OK</span><br/>\n" );
		}
		else
		{
			echo( "$helptext<br/>\n" );
		}
	}
}

/**
 * Convert symbolic values to integer
 * @param string $val
 * @return int
 */
function return_bytes( $val )
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

/**
 * Convert integer to symbolic values
 * @param int $size
 * @return string
 */
function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

/**
 * Check if path is writable by the web server
 * @param string $path
 * @return bool
 */
function is__writable($path) {
//should work in spite of Windows ACLs bug
//NOTE: use a trailing slash for folders!!!
//see http://bugs.php.net/bug.php?id=27609
//see http://bugs.php.net/bug.php?id=30931

    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}

/**
 * Check if paths are writable by the web server
 * @param array $reqWrite
 * @return void
 */
function checkWrite( $reqWrite )
{
	foreach( $reqWrite as $path)
	{
		echo( "Checking $path - " );
                if (file_exists( $path ) )
                {
                    if( is__writable( $path ) )
                    {
                            echo( "<span class=\"ok\">OK</span><br/>" );
                    }
                    else
                    {
                            echo( "<span class=\"fail\">FAILED!</span><br/>\n" );
                            echo( "Change permissions of path <tt>$path</tt> to allow write access to the web server.<br/>\n" );
                    }
                }
                else
                {
                    echo( "<span class=\"fail\">FAILED!</span> " );
                    echo( "File or directory not found." );
		    if ( file_exists( rtrim( $path, '/') . ".template" ) ) echo( " Remember to rename the template files!" );
		    echo( "<br />\n" );
                }

	}
}

/**
 * Check if template files were renamed correctly
 * @param array $reqMove
 * @return void
 */
function checkMove( $reqMove )
{
	foreach( $reqMove as $path)
	{
		echo( "Checking $path - " );
                if (file_exists( $path ) ) {
                       echo( "<span class=\"ok\">OK</span><br/>" );
                }
                else {
                    echo( "<span class=\"fail\">FAILED!</span> " );
                    echo( "File or directory not found." );
		    if ( file_exists( rtrim( $path, '/') . ".template" ) ) echo( " Remember to rename the template files!" );
		    echo( "<br />\n" );
                }

	}
}

/**
 * Check if a URL exists
 * Make a lot of fuss to make timeout configurable
 * Then, if a socket could be successfully opened, a possible 404 should return immediately
 * @param string $url URL
 * @param int $timeout Timeout for socket connection attempt
 * @return bool
 */
function urlExists($url, $timeout=10) {
        $url_info=parse_url($url);
        if (isset($url_info['scheme']) && $url_info['scheme'] == 'https') {
                $port = isset($url_info['port']) ? $url_info['port'] : 443;
                @$fp=fsockopen('ssl://'.$url_info['host'], $port, $errno, $errstr, $timeout);
        } else {
                $port = isset($url_info['port']) ? $url_info['port'] : 80;
                @$fp=fsockopen($url_info['host'], $port, $errno, $errstr, $timeout);
        }
        if (!$fp) return false;
	return (bool) @file_get_contents($url);
}
?>
</div>
</body>
</html>
