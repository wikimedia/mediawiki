<?php

# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/ContributionReporting/ContributionReporting.php" );
EOT;
        exit( 1 );
}

$wgLandingPageBase = 'http://wikimediafoundation.org/wiki/Support_Wikipedia';

$wgKnownLandingPages = array( 'US' => 'en',
			      'DE' => '',
			      'PL' => ''
		       ); # Which Chapters actually have landing pages

$wgExtensionCredits['specialpage'][] = array(
        'path' => __FILE__,
        'name' => 'GeoLite',
        'url' => 'http://www.mediawiki.org/wiki/Extension:GeoLite',
        'author' => array( 'Tomasz Finc' ),
        'descriptionmsg' => 'geolite-desc',
);

$dir = dirname( __FILE__ ) . '/';

$wgAutoloadClasses['SpecialGeoLite'] = $dir . 'GeoLite_body.php';
$wgExtensionMessagesFiles['GeoLite'] = $dir . 'GeoLite.i18n.php';
$wgSpecialPages['GeoLite'] = 'SpecialGeoLite';
$wgSpecialPageGroups['GeoLite'] = 'contribution';

?>
