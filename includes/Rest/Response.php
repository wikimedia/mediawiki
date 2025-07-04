<?php

namespace MediaWiki\Rest;

use Psr\Http\Message\StreamInterface;
use Wikimedia\Http\HttpStatus;

class Response implements ResponseInterface {

	private int $statusCode = 200;
	private string $reasonPhrase = 'OK';
	private string $protocolVersion = '1.1';
	private StreamInterface $body;
	private HeaderContainer $headerContainer;
	private array $cookies = [];

	/**
	 * @param string|StreamInterface $body
	 *
	 * @internal Use ResponseFactory
	 */
	public function __construct( $body = '' ) {
		if ( is_string( $body ) ) {
			$body = new StringStream( $body );
		}

		$this->body = $body;
		$this->headerContainer = new HeaderContainer;
	}

	/**
	 * @internal for backwards compatibility code
	 */
	public static function cast( ResponseInterface $iResponse ): Response {
		if ( $iResponse instanceof Response ) {
			return $iResponse;
		}

		$resp = new Response(
			$iResponse->getBody()
		);

		foreach ( $iResponse->getHeaders() as $name => $values ) {
			$resp->setHeader( $name, $values );
		}

		return $resp;
	}

	/** @inheritDoc */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/** @inheritDoc */
	public function getReasonPhrase() {
		return $this->reasonPhrase;
	}

	/** @inheritDoc */
	public function setStatus( $code, $reasonPhrase = '' ) {
		$this->statusCode = $code;
		if ( $reasonPhrase === '' ) {
			$reasonPhrase = HttpStatus::getMessage( $code ) ?? '';
		}
		$this->reasonPhrase = $reasonPhrase;
	}

	/** @inheritDoc */
	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	/** @inheritDoc */
	public function getHeaders() {
		return $this->headerContainer->getHeaders();
	}

	/** @inheritDoc */
	public function hasHeader( $name ) {
		return $this->headerContainer->hasHeader( $name );
	}

	/** @inheritDoc */
	public function getHeader( $name ) {
		return $this->headerContainer->getHeader( $name );
	}

	/** @inheritDoc */
	public function getHeaderLine( $name ) {
		return $this->headerContainer->getHeaderLine( $name );
	}

	/** @inheritDoc */
	public function getBody() {
		return $this->body;
	}

	/** @inheritDoc */
	public function setProtocolVersion( $version ) {
		$this->protocolVersion = $version;
	}

	/** @inheritDoc */
	public function setHeader( $name, $value ) {
		$this->headerContainer->setHeader( $name, $value );
	}

	/** @inheritDoc */
	public function addHeader( $name, $value ) {
		$this->headerContainer->addHeader( $name, $value );
	}

	/** @inheritDoc */
	public function removeHeader( $name ) {
		$this->headerContainer->removeHeader( $name );
	}

	/** @inheritDoc */
	public function setBody( StreamInterface $body ) {
		$this->body = $body;
	}

	/** @inheritDoc */
	public function getRawHeaderLines() {
		return $this->headerContainer->getRawHeaderLines();
	}

	/** @inheritDoc */
	public function setCookie( $name, $value, $expire = 0, $options = [] ) {
		$this->cookies[] = [
			'name' => $name,
			'value' => $value,
			'expire' => $expire,
			'options' => $options
		];
	}

	/** @inheritDoc */
	public function getCookies() {
		return $this->cookies;
	}
}
