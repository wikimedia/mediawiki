<?php
/*
 * How to use:
 *
 * php importBSDevData.php --source demo-de --branch REL1_27 --installationpath /var/www/bluespice --username beep --password beep
 *
 * --source = the source package.
 * --branch = the branch of the source package.
 * --installationpath = your local installation where you whish to import.
 * --username = Basic Auth data for the download url.
 * --password = Basic Auth data for the download url.
 *
 * Source and branch depends on the offered package. You have to know the correct data.
 */

if ( !extension_loaded( 'zip' ) ) {
	die( "PHP extensions \"ZIP\" is not available.\n" );
}

$cfgBaseURL = "https://[username]:[password]@buildservice.bluespice.com/development/import";

$cmdArg = getopt( '', [
	'source:',
	'installationpath:',
	'branch:',
	'username:',
	'password:',
] );

if ( !isset( $cmdArg['source'] ) ) {
	die( "You must define the source with --source.\n" );
}
elseif ( !isset( $cmdArg['installationpath'] ) ) {
	die( "You must define the installationpath with --installationpath.\n" );
}
elseif ( !isset( $cmdArg['branch'] ) ) {
	die( "You must define the branch with --branch.\n" );
}
elseif ( !isset( $cmdArg['username'] ) ) {
	die( "You must define your username with --username.\n" );
}
elseif ( !isset( $cmdArg['password'] ) ) {
	die( "You must define your password with --password.\n" );
}

if ( !file_exists( $cmdArg['installationpath'] . '/LocalSettings.php' ) ) {
	die( $cmdArg['installationpath'] . "/LocalSettings.php does not exist.\n" );
}

$downloadURL  = str_replace( '[username]', $cmdArg['username'], $cfgBaseURL );
$downloadURL  = str_replace( '[password]', $cmdArg['password'], $downloadURL );
$downloadURL .= '/' . str_replace( '-', '/', $cmdArg['source'] ) . '/' . $cmdArg['branch'] . '.zip';

$downloadURLheaders = get_headers( $downloadURL );
if ( !$downloadURLheaders || $downloadURLheaders[0] == 'HTTP/1.1 404 Not Found' ) {
    die( "Source URL not found.\n" );
}

$tmpDirName = "/tmp/{$cmdArg['branch']}_" . time();
$tmpFileName = $tmpDirName . "/{$cmdArg['branch']}.zip";

if ( !mkdir( $tmpDirName ) ) {
	die( "Creation of temporary directory {$tmpDirName} failed.\n" );
}

$fopen = fopen( $tmpFileName , 'w+' );

$curlHandler = curl_init( $downloadURL );
curl_setopt( $curlHandler, CURLOPT_TIMEOUT, 120 );
curl_setopt( $curlHandler, CURLOPT_FILE, $fopen );
curl_setopt( $curlHandler, CURLOPT_FOLLOWLOCATION, true );
curl_exec( $curlHandler );
curl_close( $curlHandler );

fclose( $fopen );

$unzip = new ZipArchive;
$unzip->open( $tmpFileName );
$unzip->extractTo( $tmpDirName );
$unzip->close();

if ( !file_exists( $tmpDirName . "/database.sql" ) ) {
	die( "The file {$tmpDirName}/database.sql does not exist." );
}

$fileArray = [ 'config', 'data', 'images' ];

foreach( $fileArray as $file ) {
	$srcFile = $tmpDirName . '/' . $file;

	if ( $file == 'config' ) {
		$destFile = $cmdArg['installationpath'] . '/extensions/BlueSpiceFoundation/config';
	}
	elseif ( $file == 'data' ) {
		$destFile = $cmdArg['installationpath'] . '/extensions/BlueSpiceFoundation/data';
	}
	elseif ( $file == 'images' ) {
		$destFile = $cmdArg['installationpath'] . '/images';
	}

	if ( file_exists( $destFile ) ) {
		if ( PHP_OS === 'Windows' ) {
			$cmd = "rd /s /q {$destFile}";
		}
		else {
			$cmd = "rm -rf {$destFile}";
		}
		exec( $cmd );
	}

	if ( file_exists( $destFile ) ) {
		die( "Destination directory {$destFile} not writable.\n" );
	}

	if ( PHP_OS === 'Windows' ) {
		$cmd = "xcopy {$srcFile} {$destFile} /s /e /h";
	}
	else {
		$cmd = "cp -a {$srcFile} {$destFile}";
	}
	exec( $cmd );

}

$getLocalSettings = file_get_contents( $cmdArg['installationpath'] . "/LocalSettings.php" );

preg_match( "/wgDBserver\ \=\ \"(.*)\"/mU", $getLocalSettings, $getDBserver );
$wgDB['server'] = $getDBserver[1];

preg_match( "/wgDBname\ \=\ \"(.*)\"/mU", $getLocalSettings, $getDBname );
$wgDB['name'] = $getDBname[1];

preg_match( "/wgDBuser\ \=\ \"(.*)\"/mU", $getLocalSettings, $getDBuser );
$wgDB['user'] = $getDBuser[1];

preg_match( "/wgDBpassword\ \=\ \"(.*)\"/mU", $getLocalSettings, $getDBpassword );
$wgDB['password'] = $getDBpassword[1];

$cmd = "mysql -h{$wgDB['server']} -u{$wgDB['user']} -p{$wgDB['password']} {$wgDB['name']} < {$tmpDirName}/database.sql";
exec( $cmd );

$cmd = "php {$cmdArg['installationpath']}/maintenance/update.php --quick";
exec( $cmd );
exec( $cmd );
exec( $cmd );

$cmd = "php {$cmdArg['installationpath']}/maintenance/rebuildall.php";
exec( $cmd );

#$cmd = "php {$cmdArg['installationpath']}/extensions/BlueSpiceExtensions/ExtendedSearch/maintenance/searchUpdate.php";
#exec( $cmd );

if ( PHP_OS === 'Windows' ) {
	$cmd = "rmdir {$tmpDirName} /s /q";
}
else {
	$cmd = "rm -rf {$tmpDirName}";
}
exec( $cmd );