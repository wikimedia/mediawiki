<?php

declare( strict_types = 1 );
namespace MediaWiki\Http;

use GuzzleHttp\Psr7\Utils;
use LogicException;
use MWHttpRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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

	/** @inheritDoc */
	public function getProtocolVersion(): string {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		// This is not accessible via MWHttpRequest, but it is set in its protected `respVersion` property.
		// If this is ever needed, it can get exposed in MWHttpRequest.
		throw new LogicException( __METHOD__ . ' is not implemented' );
	}

	/** @inheritDoc */
	public function withProtocolVersion( $version ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	/** @inheritDoc */
	public function getHeaders(): array {
		return $this->mwHttpRequest->getResponseHeaders();
	}

	/** @inheritDoc */
	public function hasHeader( $name ): bool {
		return isset( $this->mwHttpRequest->getResponseHeaders()[$name] );
	}

	/** @inheritDoc */
	public function getHeader( $name ): array {
		return $this->hasHeader( $name ) ? $this->mwHttpRequest->getResponseHeaders()[$name] : [];
	}

	/** @inheritDoc */
	public function getHeaderLine( $name ): string {
		return $this->hasHeader( $name )
			? implode( ',', $this->mwHttpRequest->getResponseHeaders()[$name] )
			: '';
	}

	/** @inheritDoc */
	public function withHeader( $name, $value ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	/** @inheritDoc */
	public function withAddedHeader( $name, $value ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	/** @inheritDoc */
	public function withoutHeader( $name ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	/** @inheritDoc */
	public function getBody(): StreamInterface {
		return Utils::streamFor( $this->mwHttpRequest->getContent() );
	}

	public function withBody( StreamInterface $body ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	/** @inheritDoc */
	public function getStatusCode(): int {
		return $this->mwHttpRequest->getStatus();
	}

	/** @inheritDoc */
	public function withStatus( $code, $reasonPhrase = '' ): self {
		$this->throwExceptionForBuilderMethod( __METHOD__ );
	}

	public function getReasonPhrase(): string {
		return ''; // not exposed through MWHttpRequest, unlikely to ever be useful
	}

	/**
	 * @param string $method
	 * @return never
	 */
	private function throwExceptionForBuilderMethod( string $method ): never {
		throw new LogicException( "Builder method $method is not supported." );
	}

	private function validateHasResponse( MWHttpRequest $mwHttpRequest ): void {
		/*
		 * MWHttpRequest objects contain request information, but also contain response information after calling
		 * `execute`. The best way of determining whether a MWHttpRequest contains response information is to check
		 * whether its headers list is empty.
		 */
		if ( !$mwHttpRequest->getResponseHeaders() ) {
			throw new LogicException( 'Trying to get response information from a request that was not yet executed' );
		}
	}
}
