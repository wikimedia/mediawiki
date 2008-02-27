<?php

/**
 * Generate an OpenSearch description file
 */

require_once( dirname(__FILE__) . '/includes/WebStart.php' );
require_once( dirname(__FILE__) . '/languages/Names.php' );
$fullName = wfMsgForContent( 'opensearch-desc' );
$shortName = htmlspecialchars( mb_substr( $fullName, 0, 24 ) );
$siteName = htmlspecialchars( $fullName );


$favicon = htmlspecialchars( wfExpandUrl( $wgFavicon ) );

$title = SpecialPage::getTitleFor( 'Search' );
$template = $title->escapeFullURL( 'search={searchTerms}' );

$suggest = htmlspecialchars($wgServer . $wgScriptPath . '/api.php?action=opensearch&search={searchTerms}');


$response = $wgRequest->response();
if( $wgRequest->getVal( 'ctype' ) == 'application/xml' ) {
	// Makes testing tweaks about a billion times easier
	$ctype = 'application/xml';
} else {
	$ctype = 'application/opensearchdescription+xml';
}
$response->header( "Content-type: $ctype" );

# Set an Expires header so that squid can cache it for a short time
# Short enough so that the sysadmin barely notices when $wgSitename is changed
$expiryTime = 600; # 10 minutes
$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expiryTime ) . ' GMT' );
$response->header( 'Cache-control: max-age=600' );

echo <<<EOT
<?xml version="1.0"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName>$shortName</ShortName>
<Description>$siteName</Description>
<Image height="16" width="16" type="image/x-icon">$favicon</Image>
<Url type="text/html" method="get" template="$template"/>
<Url type="application/x-suggestions+json" method="GET" template="$suggest"/>
</OpenSearchDescription>
EOT;


?>
