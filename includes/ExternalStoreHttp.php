<?php
/**
 *
 * @package MediaWiki
 *
 * Example class for HTTP accessable external objects
 *
 */

class ExternalStoreHttp {
	/* Fetch data from given URL */
	function fetchFromURL($url) {
	ini_set( "allow_url_fopen", true );
	$ret = file_get_contents( $url );
	ini_set( "allow_url_fopen", false );
		return $ret;
	}

	/* XXX: may require other methods, for store, delete,
	 * whatever, for initial ext storage
	 */
}
?>
