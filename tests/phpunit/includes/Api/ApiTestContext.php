<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Permissions\Authority;
use MediaWiki\Request\WebRequest;

class ApiTestContext extends RequestContext {

	/**
	 * Returns a DerivativeContext with the request variables in place
	 *
	 * @param WebRequest $request WebRequest request object including parameters and session
	 * @param Authority|null $performer
	 * @return DerivativeContext
	 */
	public function newTestContext( WebRequest $request, ?Authority $performer = null ) {
		$context = new DerivativeContext( $this );
		$context->setRequest( $request );
		if ( $performer !== null ) {
			$context->setAuthority( $performer );
		}

		return $context;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( ApiTestContext::class, 'ApiTestContext' );
