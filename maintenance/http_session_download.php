<?php 
/*
 * simple entry point to initiate a background download
 * 
 * arguments: 
 * 
 * -sid {$session_id} -usk {$upload_session_key}
 */

global $optionsWithArgs;
$optionsWithArgs = Array('sid', 'usk');

//act like a "normal user"
$wgUseNormalUser = true;

require_once( 'commandLine.inc' );

if(!isset($options['sid']) || !isset($options['usk'])){
	print<<<EOT
	simple entry point to initiate a background download
	
	Usage: http_session_download.php [options]
	Options:
		--sid the session id (required)
		--usk the upload session key (also required)  
EOT;

	exit();
}
wfProfileIn('http_session_download.php');

//run the download: 
Http::doSessionIdDownload( $options['sid'], $options['usk'] );

//close up shop:
// Execute any deferred updates
wfDoUpdates();
			
// Log what the user did, for book-keeping purposes.	
wfLogProfilingData();
			
// Shut down the database before exit
wfGetLBFactory()->shutdown();

wfProfileOut('http_session_download.php');
?>