<?php

namespace MediaWiki\Tests;

use Exception;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use PHPUnit\Framework\Assert;

/**
 * @internal For testing MediaWikiEntryPoint subclasses.
 *           Should be revised before wider use.
 */
class MockEnvironment extends EntryPointEnvironment {

	public const MOCK_REQUEST_URL = '/just/a/test';

	private ?FauxRequest $request = null;

	private array $serverInfo = [];

	public function __construct( ?FauxRequest $request = null ) {
		if ( $request ) {
			if ( !$request->hasRequestURL() ) {
				$request->setRequestURL( self::MOCK_REQUEST_URL );
			}

			// Note that setRequestInfo() will reset $this->request to null
			$this->setRequestInfo(
				$request->getRequestURL(),
				$request->getQueryValuesOnly(),
				$request->getMethod()
			);
		}

		$this->request = $request;
	}

	public function setRequestInfo( string $requestUrl, string|array $params = '', string $method = 'GET' ) {
		$this->request = null;

		$this->setServerInfo(
			'REQUEST_URI',
			$requestUrl
		);
		$this->setServerInfo(
			'REQUEST_METHOD',
			$method
		);
		$this->setServerInfo(
			'QUERY_STRING',
			is_string( $params ) ? $params : wfArrayToCgi( $params )
		);
	}

	public function getFauxRequest(): FauxRequest {
		if ( !$this->request ) {
			$data = wfCgiToArray( $this->getServerInfo( 'QUERY_STRING', '' ) );
			$wasPosted = $this->getServerInfo( 'REQUEST_METHOD', 'GET' ) === 'POST';
			$requestUrl = $this->getServerInfo( 'REQUEST_URI' ) ?? self::MOCK_REQUEST_URL;

			$request = new FauxRequest( $data, $wasPosted );
			$request->setServerInfo( $this->serverInfo );
			$request->setRequestURL( $requestUrl );

			// This adds a virtual 'title' query parameter. Normally called from Setup.php
			$request->interpolateTitle();
			$this->request = $request;
		}

		return $this->request;
	}

	public function getFauxResponse(): FauxResponse {
		return $this->getFauxRequest()->response();
	}

	public function makeFauxContext( array $config = [] ): RequestContext {
		$context = new RequestContext();
		$context->setRequest( $this->getFauxRequest() );
		$context->setLanguage( 'qqx' );
		$context->setConfig( new MultiConfig( [
			new HashConfig( $config ),
			$context->getConfig()
		] ) );

		return $context;
	}

	public function isCli(): bool {
		return false;
	}

	public function hasFastCgi(): bool {
		return true;
	}

	public function fastCgiFinishRequest(): bool {
		return true;
	}

	public function setServerInfo( string $key, mixed $value ) {
		$this->serverInfo[$key] = $value;
	}

	public function getServerInfo( string $key, mixed $default = null ): mixed {
		return $this->serverInfo[$key] ?? $default;
	}

	public function exit( int $code = 0 ) {
		throw new Exception( $code );
	}

	public function disableModDeflate(): void {
		// no-op
	}

	/** @inheritDoc */
	public function getEnv( string $name ) {
		// Implement when needed.
		return false;
	}

	/** @inheritDoc */
	public function getIni( string $name ) {
		// Implement when needed.
		return false;
	}

	/** @inheritDoc */
	public function setIniOption( string $name, $value ) {
		// Implement when needed.
		return false;
	}

	public function assertStatusCode( int $expected, ?string $message = null ) {
		$message ??= "HTTP status";
		$code = $this->getFauxResponse()->getStatusCode() ?? 200;
		Assert::assertSame( $expected, $code, $message );
	}

	public function assertHeaderValue( ?string $expected, string $name, ?string $message = null ) {
		$message ??= "$name header";
		Assert::assertSame( $expected, $this->getFauxResponse()->getHeader( $name ), $message );
	}

	public function assertCookieValue( ?string $expected, string $name, ?string $message = null ) {
		$message ??= "$name header";
		Assert::assertSame( $expected, $this->getFauxResponse()->getCookie( $name ), $message );
	}

}
