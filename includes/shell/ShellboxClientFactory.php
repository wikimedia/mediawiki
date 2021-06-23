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
	/** @var (string|false|null)[]|null */
	private $urls;
	/** @var string|null */
	private $key;

	/** The default request timeout, in seconds */
	public const DEFAULT_TIMEOUT = 10;

	/**
	 * @internal Use MediaWikiServices::getShellboxClientFactory()
	 * @param HttpRequestFactory $requestFactory The factory which will be used
	 *   to make HTTP clients.
	 * @param (string|false|null)[]|null $urls The Shellbox base URL mapping
	 * @param string|null $key The shared secret key used for HMAC authentication
	 */
	public function __construct( HttpRequestFactory $requestFactory, $urls, $key ) {
		$this->requestFactory = $requestFactory;
		$this->urls = $urls;
		$this->key = $key;
	}

	/**
	 * Test whether remote Shellbox is enabled by configuration.
	 *
	 * @param string|null $service Same as the service option for getClient.
	 * @return bool
	 */
	public function isEnabled( ?string $service = null ): bool {
		return $this->getUrl( $service ) !== null;
	}

	/**
	 * Get a Shellbox client with the specified options. If remote Shellbox is
	 * not configured (isEnabled() returns false), an exception will be thrown.
	 *
	 * @param array $options Associative array of options:
	 *   - timeout: The request timeout in seconds
	 *   - service: the shellbox backend name to get the URL from the mapping
	 * @return Client
	 * @throws \RuntimeException
	 */
	public function getClient( array $options = [] ) {
		$url = $this->getUrl( $options['service'] ?? null );
		if ( $url === null ) {
			throw new \RuntimeException( 'To use a remote shellbox to run shell commands, ' .
				'$wgShellboxUrls and $wgShellboxSecretKey must be configured.' );
		}

		return new Client(
			new ShellboxHttpClient( $this->requestFactory,
				$options['timeout'] ?? self::DEFAULT_TIMEOUT ),
			new Uri( $url ),
			$this->key
		);
	}

	private function getUrl( ?string $service ): ?string {
		if ( $this->urls === null || !strlen( $this->key ) ) {
			return null;
		}
		$url = $this->urls[$service] ?? $this->urls['default'] ?? null;
		if ( !is_string( $url ) ) {
			return null;
		}
		return $url;
	}

}
