<?php

namespace MediaWiki\Cdn;

use HttpResponse;
use OutputPage;
use Title;
use WebRequest;
use Wikimedia\Assert\Assert;

/**
 * ActiveCdnController
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class NoCdnController implements CdnController {

	/**
	 * @param Title $title
	 *
	 * @return string[]
	 */
	public function getDependentResources( Title $title ) {
		// noop
		return [];
	}

	/**
	 * @param Title $title Title of the page for which to purge dependent resources.
	 *
	 * @throws \Exception if supportsBuckets() returns false.
	 * @return void
	 */
	public function purgeDependentResources( Title $title ) {
		// noop
	}

	/**
	 * @param Title $requestTitle
	 * @param WebRequest $request
	 * @param OutputPage $output
	 */
	public function applyCacheControl( Title $requestTitle, WebRequest $request, OutputPage $output ) {
		wfDebug( __METHOD__ . ": no caching **", 'private' );

		$response = $request->response();
		$lastModified = $output->getLastModified();

		if ( $output->isCacheable() ) {
			# We do want clients to cache if they can, but they *must* check for updates
			# on revisiting the page.
			wfDebug( __METHOD__ . ": private caching; {$lastModified} **", 'private' );
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( "Cache-Control: private, must-revalidate, max-age=0" );
		} else {
			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );
		}
	}

}
