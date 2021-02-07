<?php

namespace MediaWiki\Shell;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Http\HttpRequestFactory;
use Shellbox\Client;

/**
 * This is a service which provides a configured client to access a remote
 * Shellbox installation.
 *
 * @since 1.36
 */
class ShellboxClientFactory {
	/** @var HttpRequestFactory */
	private $requestFactory;
	/** @var string|null */
	private $url;
	/** @var string|null */
	private $key;

	/** The default request timeout, in seconds */
	public const DEFAULT_TIMEOUT = 10;

	/**
	 * @internal Use MediaWikiServices::getShellboxClientFactory()
	 * @param HttpRequestFactory $requestFactory The factory which will be used
	 *   to make HTTP clients.
	 * @param string|null $url The Shellbox base URL
	 * @param string|null $key The shared secret key used for HMAC authentication
	 */
	public function __construct( HttpRequestFactory $requestFactory, $url, $key ) {
		$this->requestFactory = $requestFactory;
		$this->url = $url;
		$this->key = $key;
	}

	/**
	 * Test whether remote Shellbox is enabled by configuration.
	 *
	 * @return bool
	 */
	public function isEnabled() {
		return $this->url !== null && strlen( $this->key );
	}

	/**
	 * Get a Shellbox client with the specified options. If remote Shellbox is
	 * not configured (isEnabled() returns false), an exception will be thrown.
	 *
	 * @param array $options Associative array of options:
	 *   - timeout: The request timeout in seconds
	 * @return Client
	 * @throws \RuntimeException
	 */
	public function getClient( array $options = [] ) {
		if ( !$this->isEnabled() ) {
			throw new \RuntimeException( 'To use a remote shellbox to run shell commands, ' .
				'$wgShellboxUrl and $wgShellboxSecretKey must be configured.' );
		}

		return new Client(
			new ShellboxHttpClient( $this->requestFactory,
				$options['timeout'] ?? self::DEFAULT_TIMEOUT ),
			new Uri( $this->url ),
			$this->key
		);
	}
}
