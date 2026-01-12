<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ExternalStore;

use LogicException;
use MediaWiki\MediaWikiServices;

/**
 * External storage using HTTP requests.
 *
 * Example class for HTTP accessible external objects.
 * Only supports reading, not storing.
 *
 * @see ExternalStoreAccess
 * @ingroup ExternalStorage
 */
class ExternalStoreHttp extends ExternalStoreMedium {
	/** @inheritDoc */
	public function fetchFromURL( $url ) {
		return MediaWikiServices::getInstance()->getHttpRequestFactory()->
			get( $url, [], __METHOD__ );
	}

	/** @inheritDoc */
	public function store( $location, $data ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new LogicException( "ExternalStoreHttp is read-only and does not support store()." );
	}

	/** @inheritDoc */
	public function isReadOnly( $location ) {
		return true;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( ExternalStoreHttp::class, 'ExternalStoreHttp' );
