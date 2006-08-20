<?php

/**
 * Generate an OpenSearch description file
 */

require_once( dirname(__FILE__) . '/includes/WebStart.php' );
$shortName = htmlspecialchars( mb_substr( $wgSitename, 0, 16 ) );
$siteName = htmlspecialchars( $wgSitename );

if ( !preg_match( '/^https?:/', $wgFavicon ) ) {
	$favicon = htmlspecialchars( $wgServer . $wgFavicon );
} else {
	$favicon = htmlspecialchars( $wgFavicon );
}

$title = Title::makeTitle( NS_SPECIAL, 'Search' );
$template = $title->getFullURL( 'search={searchTerms}' );

$response = $wgRequest->response();
$response->header( 'Content-type: application/opensearchdescription+xml' );

# Set an Expires header so that squid can cache it for a short time
# Short enough so that the sysadmin barely notices when $wgSitename is changed
$expiryTime = 300; # 5 minutes
$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expiryTime ) . ' GMT' );

echo <<<EOT
<?xml version="1.0"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName>$shortName</ShortName>
<Description>$siteName</Description>
<Image height="16" width="16" type="image/x-icon">$favicon</Image>
<Url type="text/html" method="get" template="$template"/>
</OpenSearchDescription>
EOT;

?>
