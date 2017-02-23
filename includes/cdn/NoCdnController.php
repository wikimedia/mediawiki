<?php

namespace MediaWiki\Cdn;

use DeferredUpdates;
use HttpResponse;
use OutputPage;
use Title;
use WebRequest;
use Wikimedia\Assert\Assert;

/**
 * A CDN controller that does not support any active purging. Use this when no reverse proxies or
 * content delivery network are available.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class NoCdnUpdateController implements CdnUpdateController, CdnHeaderController {

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
	 * @param bool|int $deferred
	 */
	public function purgeDependentResources( Title $title, $deferred = DeferredUpdates::PRESEND ) {
		// noop
	}

	/**
	 * @see CdnHeaderController::applyCacheControl
	 *
	 * @param WebRequest $request
	 * @param OutputPage $output
	 * @param array $dependsOn
	 */
	public function applyCacheControl( WebRequest $request, OutputPage $output, array $dependsOn = [] ) {
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
