<?php

namespace MediaWiki\Rest;

use HttpStatus;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface {
	/** @var int */
	private $statusCode = 200;

	/** @var string */
	private $reasonPhrase = 'OK';

	/** @var string */
	private $protocolVersion = '1.1';

	/** @var StreamInterface */
	private $body;

	/** @var HeaderContainer */
	private $headerContainer;

	/** @var array */
	private $cookies = [];

	/**
	 * @internal Use ResponseFactory
	 * @param string $bodyContents
	 */
	public function __construct( $bodyContents = '' ) {
		$this->body = new StringStream( $bodyContents );
		$this->headerContainer = new HeaderContainer;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function getReasonPhrase() {
		return $this->reasonPhrase;
	}

	public function setStatus( $code, $reasonPhrase = '' ) {
		$this->statusCode = $code;
		if ( $reasonPhrase === '' ) {
			$reasonPhrase = HttpStatus::getMessage( $code ) ?? '';
		}
		$this->reasonPhrase = $reasonPhrase;
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	public function getHeaders() {
		return $this->headerContainer->getHeaders();
	}

	public function hasHeader( $name ) {
		return $this->headerContainer->hasHeader( $name );
	}

	public function getHeader( $name ) {
		return $this->headerContainer->getHeader( $name );
	}

	public function getHeaderLine( $name ) {
		return $this->headerContainer->getHeaderLine( $name );
	}

	public function getBody() {
		return $this->body;
	}

	public function setProtocolVersion( $version ) {
		$this->protocolVersion = $version;
	}

	public function setHeader( $name, $value ) {
		$this->headerContainer->setHeader( $name, $value );
	}

	public function addHeader( $name, $value ) {
		$this->headerContainer->addHeader( $name, $value );
	}

	public function removeHeader( $name ) {
		$this->headerContainer->removeHeader( $name );
	}

	public function setBody( StreamInterface $body ) {
		$this->body = $body;
	}

	public function getRawHeaderLines() {
		return $this->headerContainer->getRawHeaderLines();
	}

	public function setCookie( $name, $value, $expire = 0, $options = [] ) {
		$this->cookies[] = [
			'name' => $name,
			'value' => $value,
			'expire' => $expire,
			'options' => $options
		];
	}

	public function getCookies() {
		return $this->cookies;
	}
}
