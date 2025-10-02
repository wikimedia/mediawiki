<?php
/**
 * The web entry point for generating an OpenSearch description document.
 *
 * See <http://www.opensearch.org/> for the specification of the OpenSearch
 * "description" document. In a nut shell, this tells browsers how and where
 * to submit submit search queries to get a search results page back,
 * as well as how to get typeahead suggestions (see ApiOpenSearch).
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup entrypoint
 */

// This endpoint is supposed to be independent of request cookies and other
// details of the session. Enforce this constraint with respect to session use.
define( 'MW_NO_SESSION', 1 );

define( 'MW_ENTRY_POINT', 'opensearch_desc' );

require_once __DIR__ . '/includes/WebStart.php';

$url = wfScript( 'rest' ) . '/v1/search';
$ctype = $wgRequest->getRawVal( 'ctype' );

if ( $ctype !== null ) {
	$url = wfAppendQuery( $url, [ 'ctype' => $ctype ] );
}

$wgRequest->response()->header( 'Location: ' . $url, true, 308 );
$wgRequest->response()->header( 'Cache-control: max-age=600' );
