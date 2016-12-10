<?php

namespace MediaWiki\Cdn;

use HttpResponse;
use OutputPage;
use Title;
use WebRequest;

/**
 * ActiveCdnController
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class NullCdnController implements CdnController {

	/**
	 * @param string[] $resourceUris
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purge( array $resourceUris ) {
		// noop
	}

	/**
	 * @param Title $requestTitle
	 * @param WebRequest $request
	 * @param OutputPage $output
	 */
	public function applyCacheControl( Title $requestTitle, WebRequest $request, OutputPage $output ) {
		// noop
	}

	/**
	 * @param array $resourceUris
	 */
	public function applyBucketControl( array $resourceUris, HttpResponse $response ) {
		// noop
	}

	/**
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title ) {
		// noop
		return [];
	}
}
