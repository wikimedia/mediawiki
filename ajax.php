<?php

$wgRequestTime = microtime();

unset( $IP );
@ini_set( 'allow_url_fopen', 0 ); # For security...

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially defines
# a valid entry point. If you put it in includes/Defines.php, then any script that includes
# it becomes an entry point, thereby defeating its purpose.
define( 'MEDIAWIKI', true );
require_once( './includes/Defines.php' );
require_once( './LocalSettings.php' );
require_once( 'includes/Setup.php' );
require_once( 'includes/AjaxFunctions.php' );

if ( ! $wgUseAjax ) {
	die ( -1 );
}

wfProfileIn( 'main-misc-setup' );

header( 'Content-Type: text/html; charset=utf-8', true );

// List of exported PHP functions
$sajax_export_list = array( 'wfSajaxSearch' );

$mode = "";

$wgAjaxCachePolicy = new AjaxCachePolicy();

if (! empty($_GET["rs"])) {
	$mode = "get";
}

if (!empty($_POST["rs"])) {
	$mode = "post";
}

if (empty($mode)) {
	return;
}

if ($mode == "get") {
	$func_name = $_GET["rs"];
	if (! empty($_GET["rsargs"])) {
		$args = $_GET["rsargs"];
	} else {
		$args = array();
	}
} else {
	$func_name = $_POST["rs"];
	if (! empty($_POST["rsargs"])) {
		$args = $_POST["rsargs"];
	} else {
		$args = array();
	}
}

if (! in_array($func_name, $sajax_export_list)) {
	echo "-:$func_name not callable";
} else {
	echo "+:";
	$result = call_user_func_array($func_name, $args);
	$wgAjaxCachePolicy->writeHeader();
	echo $result;
}
exit;

?>
