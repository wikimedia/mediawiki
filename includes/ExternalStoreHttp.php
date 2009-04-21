<?php
/**
 * Example class for HTTP accessable external objects
 *
 * @ingroup ExternalStorage
 */
class ExternalStoreHttp {
	/* Fetch data from given URL */
	function fetchFromURL($url) {
		$ret = file_get_contents( $url );
		return $ret;
	}

	/* XXX: may require other methods, for store, delete,
	 * whatever, for initial ext storage
	 */
}
