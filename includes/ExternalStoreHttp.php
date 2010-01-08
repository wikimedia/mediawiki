<?php

/**
 * Example class for HTTP accessable external objects.
 * Only supports reading, not storing.
 *
 * @ingroup ExternalStorage
 */
class ExternalStoreHttp {

	/**
	 * Fetch data from given URL
	 *
	 * @param $url String: the URL
	 * @return String: the content at $url
	 */
	function fetchFromURL( $url ) {
		$ret = Http::get( $url );
		return $ret;
	}

	/* XXX: may require other methods, for store, delete,
	 * whatever, for initial ext storage
	 */
}
