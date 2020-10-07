<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Assert\Assert;

/**
 * @ingroup ExternalStorage
 */
class ExternalStoreFactory implements LoggerAwareInterface {
	/** @var string[] List of storage access protocols */
	private $protocols;
	/** @var string[] List of base storage URLs that define locations for writes */
	private $writeBaseUrls;
	/** @var string Default database domain to store content under */
	private $localDomainId;
	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param string[] $externalStores See $wgExternalStores
	 * @param string[] $defaultStores See $wgDefaultExternalStore
	 * @param string $localDomainId Local database/wiki ID
	 * @param LoggerInterface|null $logger
	 */
	public function __construct(
		array $externalStores,
		array $defaultStores,
		$localDomainId,
		LoggerInterface $logger = null
	) {
		Assert::parameterType( 'string', $localDomainId, '$localDomainId' );

		$this->protocols = array_map( 'strtolower', $externalStores );
		$this->writeBaseUrls = $defaultStores;
		$this->localDomainId = $localDomainId;
		$this->logger = $logger ?: new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return string[] List of active store types/protocols (lowercased), e.g. [ "db" ]
	 * @since 1.34
	 */
	public function getProtocols() {
		return $this->protocols;
	}

	/**
	 * @return string[] List of default base URLs for writes, e.g. [ "DB://cluster1" ]
	 * @since 1.34
	 */
	public function getWriteBaseUrls() {
		return $this->writeBaseUrls;
	}

	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * The 'domain' field in $params will be set to the local DB domain if it is unset
	 * or false. A special 'isDomainImplicit' flag is set when this happens, which should
	 * only be used to handle legacy DB domain configuration concerns (e.g. T200471).
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters.
	 * @return ExternalStoreMedium The store class or false on error
	 * @throws ExternalStoreException When $proto is not recognized
	 */
	public function getStore( $proto, array $params = [] ) {
		$protoLowercase = strtolower( $proto ); // normalize
		if ( !$this->protocols || !in_array( $protoLowercase, $this->protocols ) ) {
			throw new ExternalStoreException( "Protocol '$proto' is not enabled." );
		}

		$class = 'ExternalStore' . ucfirst( $proto );
		if ( isset( $params['wiki'] ) ) {
			$params += [ 'domain' => $params['wiki'] ]; // b/c
		}
		if ( !isset( $params['domain'] ) || $params['domain'] === false ) {
			$params['domain'] = $this->localDomainId; // default
			$params['isDomainImplicit'] = true; // b/c for ExternalStoreDB
		}
		// @TODO: ideally, this class should not hardcode what classes need what backend factory
		// objects. For now, inject the factory instances into __construct() for those that do.
		if ( $protoLowercase === 'db' ) {
			$params['lbFactory'] = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		} elseif ( $protoLowercase === 'mwstore' ) {
			$params['fbGroup'] = MediaWikiServices::getInstance()->getFileBackendGroup();
		}
		$params['logger'] = $this->logger;

		if ( !class_exists( $class ) ) {
			throw new ExternalStoreException( "Class '$class' is not defined." );
		}

		// Any custom modules should be added to $wgAutoLoadClasses for on-demand loading
		return new $class( $params );
	}

	/**
	 * Get the ExternalStoreMedium for a given URL
	 *
	 * $url is either of the form:
	 *   - a) "<proto>://<location>/<path>", for retrieval, or
	 *   - b) "<proto>://<location>", for storage
	 *
	 * @param string $url
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return ExternalStoreMedium
	 * @throws ExternalStoreException When the protocol is missing or not recognized
	 * @since 1.34
	 */
	public function getStoreForUrl( $url, array $params = [] ) {
		list( $proto, $path ) = self::splitStorageUrl( $url );
		if ( $path == '' ) { // bad URL
			throw new ExternalStoreException( "Invalid URL '$url'" );
		}

		return $this->getStore( $proto, $params );
	}

	/**
	 * Get the location within the appropriate store for a given a URL
	 *
	 * @param string $url
	 * @return string
	 * @throws ExternalStoreException
	 * @since 1.34
	 */
	public function getStoreLocationFromUrl( $url ) {
		list( , $location ) = self::splitStorageUrl( $url );
		if ( $location == '' ) { // bad URL
			throw new ExternalStoreException( "Invalid URL '$url'" );
		}

		return $location;
	}

	/**
	 * @param string[] $urls
	 * @return array[] Map of (protocol => list of URLs)
	 * @throws ExternalStoreException
	 * @since 1.34
	 */
	public function getUrlsByProtocol( array $urls ) {
		$urlsByProtocol = [];
		foreach ( $urls as $url ) {
			list( $proto, ) = self::splitStorageUrl( $url );
			$urlsByProtocol[$proto][] = $url;
		}

		return $urlsByProtocol;
	}

	/**
	 * @param string $storeUrl
	 * @return string[] (protocol, store location or location-qualified path)
	 * @throws ExternalStoreException
	 */
	private static function splitStorageUrl( $storeUrl ) {
		$parts = explode( '://', $storeUrl );
		if ( count( $parts ) != 2 || $parts[0] === '' || $parts[1] === '' ) {
			throw new ExternalStoreException( "Invalid storage URL '$storeUrl'" );
		}

		return $parts;
	}
}
