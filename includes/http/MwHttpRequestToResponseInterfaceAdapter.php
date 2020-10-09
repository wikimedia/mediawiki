<?php

declare( strict_types = 1 );
namespace MediaWiki\Http;

use LogicException;
use MWHttpRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;

/**
 * @since 1.36
 * @unstable
 *
 * @license GPL-2.0-or-later
 */
class MwHttpRequestToResponseInterfaceAdapter implements ResponseInterface {

	/**
	 * @var MWHttpRequest
	 */
	private $mwHttpRequest;

	/**
	 * @param MWHttpRequest $mwHttpRequest the MWHttpRequest must contain response information, i.e. must have been
	 *                                     `execute`d
	 */
	public function __construct( MWHttpRequest $mwHttpRequest ) {
		$this->validateHasResponse( $mwHttpRequest );
		$this->mwHttpRequest = $mwHttpRequest;
	}

	public function getProtocolVersion(): void {
		// This is not accessible via MWHttpRequest, but it is set in its protected `respVersion` property.
		// If this is ever needed, it can get exposed in MWHttpRequest.
		throw new LogicException( __METHOD__ . ' is not implemented' );
	}

	public function withProtocolVersion( $version ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function getHeaders(): array {
		return $this->mwHttpRequest->getResponseHeaders();
	}

	public function hasHeader( $name ): bool {
		return isset( $this->mwHttpRequest->getResponseHeaders()[$name] );
	}

	public function getHeader( $name ): array {
		return $this->hasHeader( $name ) ? $this->mwHttpRequest->getResponseHeaders()[$name] : [];
	}

	public function getHeaderLine( $name ): string {
		return $this->hasHeader( $name )
			? implode( ',', $this->mwHttpRequest->getResponseHeaders()[$name] )
			: '';
	}

	public function withHeader( $name, $value ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function withAddedHeader( $name, $value ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function withoutHeader( $name ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function getBody(): StreamInterface {
		return stream_for( $this->mwHttpRequest->getContent() );
	}

	public function withBody( StreamInterface $body ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function getStatusCode(): int {
		return $this->mwHttpRequest->getStatus();
	}

	public function withStatus( $code, $reasonPhrase = '' ): void {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function getReasonPhrase(): string {
		return ''; // not exposed through MWHttpRequest, unlikely to ever be useful
	}

	private function throwExceptionForBuilderMethod( string $method ): void {
		throw new LogicException( "Builder method $method is not supported." );
	}

	private function validateHasResponse( MWHttpRequest $mwHttpRequest ): void {
		/**
		 * MWHttpRequest objects contain request information, but also contain response information after calling
		 * `execute`. The best way of determining whether a MWHttpRequest contains response information is to check
		 * whether its headers list is empty.
		 */
		if ( empty( $mwHttpRequest->getResponseHeaders() ) ) {
			throw new LogicException( 'Trying to get response information from a request that was not yet executed' );
		}
	}
}
