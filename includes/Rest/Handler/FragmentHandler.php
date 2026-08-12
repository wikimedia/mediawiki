<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseHeaders;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;

/**
 * Base class for handlers that serves HTML fragments
 * given an Edge Side Includes (ESI) tag.
 * @unstable Experimental, part of the fragments/v0-internal module.
 */
abstract class FragmentHandler extends SimpleHandler {

	/**
	 * @return string The HTML fragment to serve.
	 */
	abstract protected function getFragmentHtml(): string;

	public function run(): Response {
		$response = $this->getResponseFactory()->create();
		$response->setHeader( ResponseHeaders::CONTENT_TYPE, 'text/html; charset=utf-8' );
		$response->setBody( new StringStream( $this->getFragmentHtml() ) );
		return $response;
	}
}
