<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Http;

use GuzzleHttp\Client;
use GuzzleHttpRequest;
use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use MWHttpRequest;
use Profiler;
use Psr\Log\LoggerInterface;
use Wikimedia\Http\MultiHttpClient;
use Wikimedia\Http\TelemetryHeadersInterface;

/**
 * Factory creating MWHttpRequest objects.
 */
class HttpRequestFactory {
	/** @var ServiceOptions */
	private $options;
	/** @var LoggerInterface */
	private $logger;
	/** @var TelemetryHeadersInterface|null */
	private $telemetry;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::HTTPTimeout,
		MainConfigNames::HTTPConnectTimeout,
		MainConfigNames::HTTPMaxTimeout,
		MainConfigNames::HTTPMaxConnectTimeout,
		MainConfigNames::LocalVirtualHosts,
		MainConfigNames::LocalHTTPProxy,
	];

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		?TelemetryHeadersInterface $telemetry = null
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->logger = $logger;
		$this->telemetry = $telemetry;
	}

	/**
	 * Generate a new MWHttpRequest object
	 * @param string $url Url to use
	 * @param array $options Possible keys for the array:
	 *    - timeout             Timeout length in seconds or 'default'
	 *    - connectTimeout      Timeout for connection, in seconds (curl only) or 'default'
	 *    - maxTimeout          Override for the configured maximum timeout. This should not be
	 *                          used in production code.
	 *    - maxConnectTimeout   Override for the configured maximum connect timeout. This should
	 *                          not be used in production code.
	 *    - postData            An array of key-value pairs or a url-encoded form data
	 *    - proxy               The proxy to use.
	 *                          Otherwise it will use $wgHTTPProxy or $wgLocalHTTPProxy (if set)
	 *                          Otherwise it will use the environment variable "http_proxy" (if set)
	 *    - noProxy             Don't use any proxy at all. Takes precedence over proxy value(s).
	 *    - sslVerifyHost       Verify hostname against certificate
	 *    - sslVerifyCert       Verify SSL certificate
	 *    - caInfo              Provide CA information
	 *    - maxRedirects        Maximum number of redirects to follow (defaults to 5)
	 *    - followRedirects     Whether to follow redirects (defaults to false).
	 *                          Note: this should only be used when the target URL is trusted,
	 *                          to avoid attacks on intranet services accessible by HTTP.
	 *    - userAgent           A user agent, if you want to override the default
	 *                          "MediaWiki/{MW_VERSION}".
	 *    - logger              A \Psr\Logger\LoggerInterface instance for debug logging
	 *    - username            Username for HTTP Basic Authentication
	 *    - password            Password for HTTP Basic Authentication
	 *    - originalRequest     Information about the original request (as a WebRequest object or
	 *                          an associative array with 'ip' and 'userAgent').
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{timeout?:int|string,connectTimeout?:int|string,postData?:string|array,proxy?:?string,noProxy?:bool,sslVerifyHost?:bool,sslVerifyCert?:bool,caInfo?:?string,maxRedirects?:int,followRedirects?:bool,userAgent?:string,method?:string,logger?:\Psr\Log\LoggerInterface,username?:string,password?:string,originalRequest?:\MediaWiki\Request\WebRequest|array{ip:string,userAgent:string}} $options
	 * @param string $caller The method making this request, for profiling @phan-mandatory-param
	 * @return MWHttpRequest
	 * @see MWHttpRequest::__construct
	 */
	public function create( $url, array $options = [], $caller = __METHOD__ ) {
		if ( !isset( $options['logger'] ) ) {
			$options['logger'] = $this->logger;
		}
		$options['timeout'] = $this->normalizeTimeout(
			$options['timeout'] ?? null,
			$options['maxTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxTimeout ) ?: INF
		);
		$options['connectTimeout'] = $this->normalizeTimeout(
			$options['connectTimeout'] ?? null,
			$options['maxConnectTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPConnectTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxConnectTimeout ) ?: INF
		);
		$client = new GuzzleHttpRequest( $url, $options, $caller, Profiler::instance() );
		if ( $this->telemetry ) {
			$client->addTelemetry( $this->telemetry );
		}
		return $client;
	}

	/**
	 * Given a passed parameter value, a default and a maximum, figure out the
	 * correct timeout to pass to the backend.
	 *
	 * @param int|float|string|null $parameter The timeout in seconds, or "default" or null
	 * @param int|float|null $maxParameter The maximum timeout specified by the caller
	 * @param int|float $default The configured default timeout
	 * @param int|float $maxConfigured The configured maximum timeout
	 * @return int|float
	 */
	private function normalizeTimeout( $parameter, $maxParameter, $default, $maxConfigured ) {
		if ( $parameter === 'default' || $parameter === null ) {
			if ( !is_numeric( $default ) ) {
				throw new InvalidArgumentException(
					'$wgHTTPTimeout and $wgHTTPConnectTimeout must be set to a number' );
			}
			$value = $default;
		} else {
			$value = $parameter;
		}
		$max = $maxParameter ?? $maxConfigured;
		if ( $max && $value > $max ) {
			return $max;
		}

		return $value;
	}

	/**
	 * Simple function to test if we can make any sort of requests at all, using
	 * cURL or fopen()
	 * @return bool
	 */
	public function canMakeRequests() {
		return function_exists( 'curl_init' ) || wfIniGetBool( 'allow_url_fopen' );
	}

	/**
	 * Perform an HTTP request
	 *
	 * @since 1.34
	 * @param string $method HTTP method. Usually GET/POST
	 * @param string $url Full URL to act on. If protocol-relative, will be expanded to an http://
	 *  URL
	 * @param array $options See HttpRequestFactory::create
	 * @param string $caller The method making this request, for profiling @phan-mandatory-param
	 * @return string|null null on failure or a string on success
	 */
	public function request( $method, $url, array $options = [], $caller = __METHOD__ ) {
		$logger = LoggerFactory::getInstance( 'http' );
		$logger->debug( "$method: $url" );

		$options['method'] = strtoupper( $method );

		$req = $this->create( $url, $options, $caller );
		$status = $req->execute();

		if ( $status->isOK() ) {
			return $req->getContent();
		} else {
			$errors = array_map( static fn ( $msg ) => $msg->getKey(), $status->getMessages( 'error' ) );
			$logger->warning( Status::wrap( $status )->getWikiText( false, false, 'en' ),
				[ 'error' => $errors, 'caller' => $caller, 'content' => $req->getContent() ] );
			return null;
		}
	}

	/**
	 * Simple wrapper for `request( 'GET' )`, parameters have the same meaning as for `request()`
	 *
	 * @since 1.34
	 * @param string $url
	 * @param array $options
	 * @param string $caller @phan-mandatory-param
	 * @return string|null
	 */
	public function get( $url, array $options = [], $caller = __METHOD__ ) {
		return $this->request( 'GET', $url, $options, $caller );
	}

	/**
	 * Simple wrapper for `request( 'POST' )`, parameters have the same meaning as for `request()`
	 *
	 * @since 1.34
	 * @param string $url
	 * @param array $options
	 * @param string $caller @phan-mandatory-param
	 * @return string|null
	 */
	public function post( $url, array $options = [], $caller = __METHOD__ ) {
		return $this->request( 'POST', $url, $options, $caller );
	}

	/**
	 * @return string
	 */
	public function getUserAgent() {
		return 'MediaWiki/' . MW_VERSION;
	}

	/**
	 * Get a MultiHttpClient with MediaWiki configured defaults applied.
	 *
	 * Unlike create(), by default, no proxy will be used. To use a proxy,
	 * specify the 'proxy' option.
	 *
	 * @param array $options Options as documented in MultiHttpClient::__construct(),
	 *   except that for consistency with create(), 'timeout' is accepted as an
	 *   alias for 'reqTimeout', and 'connectTimeout' is accepted as an alias for
	 *  'connTimeout'.
	 * @return MultiHttpClient
	 */
	public function createMultiClient( $options = [] ) {
		$options['reqTimeout'] = $this->normalizeTimeout(
			$options['reqTimeout'] ?? $options['timeout'] ?? null,
			$options['maxReqTimeout'] ?? $options['maxTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxTimeout ) ?: INF
		);
		$options['connTimeout'] = $this->normalizeTimeout(
			$options['connTimeout'] ?? $options['connectTimeout'] ?? null,
			$options['maxConnTimeout'] ?? $options['maxConnectTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPConnectTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxConnectTimeout ) ?: INF
		);
		$options += [
			'maxReqTimeout' => $this->options->get( MainConfigNames::HTTPMaxTimeout ) ?: INF,
			'maxConnTimeout' =>
				$this->options->get( MainConfigNames::HTTPMaxConnectTimeout ) ?: INF,
			'userAgent' => $this->getUserAgent(),
			'logger' => $this->logger,
			'localProxy' => $this->options->get( MainConfigNames::LocalHTTPProxy ),
			'localVirtualHosts' => $this->options->get( MainConfigNames::LocalVirtualHosts ),
			'telemetry' => Telemetry::getInstance(),
		];
		return new MultiHttpClient( $options );
	}

	/**
	 * Get a GuzzleHttp\Client instance.
	 *
	 * @since 1.36
	 * @param array $config Client configuration settings.
	 * @return Client
	 *
	 * @see \GuzzleHttp\RequestOptions for a list of available request options.
	 * @see Client::__construct() for additional options.
	 * Additional options that should not be used in production code:
	 *	- maxTimeout          Override for the configured maximum timeout.
	 *	- maxConnectTimeout   Override for the configured maximum connect timeout.
	 */
	public function createGuzzleClient( array $config = [] ): Client {
		$config['timeout'] = $this->normalizeTimeout(
			$config['timeout'] ?? null,
			$config['maxTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxTimeout ) ?: INF
		);

		$config['connect_timeout'] = $this->normalizeTimeout(
			$config['connect_timeout'] ?? null,
			$config['maxConnectTimeout'] ?? null,
			$this->options->get( MainConfigNames::HTTPConnectTimeout ),
			$this->options->get( MainConfigNames::HTTPMaxConnectTimeout ) ?: INF
		);

		if ( !isset( $config['headers']['User-Agent'] ) ) {
			$config['headers']['User-Agent'] = $this->getUserAgent();
		}
		if ( $this->telemetry ) {
			$config['headers'] = array_merge(
				$this->telemetry->getRequestHeaders(), $config['headers']
			);
		}

		return new Client( $config );
	}
}
