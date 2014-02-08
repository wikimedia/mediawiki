<?php
if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install this extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/TrackingCategories/TrackingCategories.php" );
EOT;
	exit( 1 );
}

$wgExtensionCredits[ 'specialpage' ][] = array(
	'path' => __FILE__,
	'name' => 'Tracking Categories',
	'author' => 'Kunal Grover',
	'descriptionmsg' => 'trackingcategories-desc',
	'version' => '1.0.0',
);

$wgSpecialPageGroups[ 'TrackingCategories' ] = 'pages';
$wgAutoloadClasses[ 'SpecialTrackingCategories' ] = __DIR__ . '/SpecialTrackingCategories.php';
$wgExtensionMessagesFiles[ 'TrackingCategories' ] = __DIR__ . '/TrackingCategories.i18n.php';
$wgExtensionMessagesFiles[ 'TrackingCategoriesAlias' ] = __DIR__ . '/TrackingCategories.alias.php';
$wgSpecialPages[ 'TrackingCategories' ] = 'SpecialTrackingCategories';
