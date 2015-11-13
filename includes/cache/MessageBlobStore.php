<?php
/**
 * Resource message blobs storage used by ResourceLoader.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Roan Kattouw
 * @author Trevor Parscal
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * This class generates message blobs for use by ResourceLoader modules.
 *
 * A message blob is a JSON object containing the interface messages for a certain module in
 * a certain language. These blobs used to be cached in the database, but this class now
 * uses MessageCache (via wfMessage) directly – see T113092.
 */
class MessageBlobStore implements LoggerAwareInterface {

	/* @var ResourceLoader|null */
	protected $resourceloader;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var WANObjectCache
	 */
	private $wanCache;

	/**
	 * @param ResourceLoader $rl
	 * @param LoggerInterface $logger
	 */
	public function __construct( ResourceLoader $rl = null, LoggerInterface $logger = null ) {
		$this->resourceloader = $rl;
		$this->logger = $logger ?: new NullLogger();
		$this->wanCache = ObjectCache::getMainWANInstance();
	}

	/**
	 * @since 1.27
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Get the message blob for a module
	 *
	 * @since 1.27
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string JSON
	 */
	public function getBlob( ResourceLoaderModule $module, $lang ) {
		$blobs = $this->getBlobs( array( $module->getName() => $module ), $lang );
		return $blobs[$module->getName()];
	}

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @since 1.27
	 * @param ResourceLoaderModule[] $modules Array of module objects keyed by name
	 * @param string $lang Language code
	 * @return array An array mapping module names to message blobs
	 */
	public function getBlobs( array $modules, $lang ) {
		// Each cache key for a message blob by module name and language code also has a generic
		// check key without language code. This is used to invalidate any and all language subkeys
		// that exist for a module from the updateMessage() method.
		$cache = $this->wanCache;
		$checkKeys = array();
		$cacheKeys = array();
		foreach ( $modules as $name => $module ) {
			$cacheKey = $cache->makeKey( __CLASS__, $name, $lang );
			$cacheKeys[$name] = $cacheKey;
			$checkKeys[$cacheKey] = $cache->makeKey( __CLASS__, $name );
		}
		$curTTLs = array();
		$result = $cache->getMulti( array_values( $cacheKeys ), $curTTLs, $checkKeys );

		$blobs = array();
		foreach ( $modules as $name => $module ) {
			$key = $cacheKeys[$name];
			if ( isset( $result[$key] ) && $curTTLs[$key] !== null && $curTTLs[$key] >= 0 ) {
				// Use unexpired cache
				$blobs[$name] = $result[$key];
			} else {
				$this->logger->debug( 'MessageBlobStore miss for {cacheKey}', array( 'cacheKey' => $key ) );
				// Regenerate and cache for a week
				$blobs[$name] = $this->generateMessageBlob( $module, $lang );
				// Add part of a day to TTL to avoid all modules expiring at once
				$cache->set( $key, $blobs[$name],
					$cache::TTL_WEEK + mt_rand( 0, $cache::TTL_DAY )
				);
			}
		}
		return $blobs;
	}

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @deprecated since 1.27 Use getBlobs() instead
	 * @return array
	 */
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		return $this->getBlobs( $modules, $lang );
	}

	/**
	 * Previously used to populate a cache table in the database.
	 *
	 * @deprecated since 1.27 Obsolete
	 * @return bool
	 */
	public function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		return false;
	}

	/**
	 * Invalidate cache keys for modules using this message key.
	 *
	 * Called by MessageCache when a message has changed.
	 *
	 * @param string $key Message key
	 */
	public function updateMessage( $key ) {
		$rl = $this->getResourceLoader();
		$moduleNames = $rl->getModulesByMessage( $key );
		foreach ( $moduleNames as $name ) {
			$cache->touchCheckKey( $cache->makeKey( __CLASS__, $name ) );
		}
	}

	/**
	 * @deprecated since 1.27 Obsolete
	 */
	public function clear() {
		try {
			// Not using TRUNCATE, because that needs extra permissions,
			// which maybe not granted to the database user.
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete( 'msg_resource', '*', __METHOD__ );
		} catch ( Exception $e ) {
			wfDebug( __METHOD__ . " failed to update DB: $e\n" );
		}
	}

	/**
	 * @since 1.27
	 * @return ResourceLoader
	 */
	protected function getResourceLoader() {
		// Back-compat: This class supports instantiation without a ResourceLoader object.
		// Lazy-initialise this property because most callers don't need it.
		if ( $this->resourceloader === null ) {
			$this->logger->info( __CLASS__ . ' created without a ResourceLoader instance' );
			$this->resourceloader = new ResourceLoader();
		}
		return $this->resourceloader;
	}

	/**
	 * @since 1.27
	 * @param string $key Message key
	 * @param string $lang Language code
	 * @return string
	 */
	private function fetchMessage( $key, $lang ) {
		$message = wfMessage( $key )->inLanguage( $lang );
		$value = $message->plain();
		if ( !$message->exists() ) {
			$this->logger->warning( __METHOD__ . " failed to find {message} ({lang})", array(
				'message' => $key,
				'lang' => $lang,
			) );
		}
		return $value;
	}

	/**
	 * Generate the message blob for a given module in a given language.
	 *
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string JSON blob
	 */
	private function generateMessageBlob( ResourceLoaderModule $module, $lang ) {
		$messages = array();
		foreach ( $module->getMessages() as $key ) {
			$messages[$key] = $this->fetchMessage( $key, $lang );
		}
		$json = FormatJson::encode( (object)$messages );

		if ( $json === false ) {
			$this->logger->warning( 'Failed to encode message blob for {module} ({lang})', array(
				'module' => $module->getName(),
				'lang' => $lang,
			) );
			$json = '{}';
		}
		return $json;
	}
}
