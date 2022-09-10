<?php

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;

/**
 * A simple {@link MultiHttpClient} implementation that can be used to prevent
 * HTTP requests in tests. All attempts to execute a request will fail.
 *
 * Use MockHttpTrait for controlling responses to mock HTTP requests.
 *
 * @author Daniel Kinzler
 * @license GPL-2.0-or-later
 */
class NullMultiHttpClient extends MultiHttpClient {

	/**
	 * Always fails.
	 *
	 * @param array $reqs
	 * @param array $opts
	 *
	 * @throws AssertionFailedError always
	 */
	public function runMulti( array $reqs, array $opts = [] ) {
		$urls = implode( ', ', array_column( $reqs, 'url' ) );
		Assert::fail( "HTTP requests to {$urls} blocked. Use MockHttpTrait." );
	}

}
