<?php
/**
 * Message blobs storage used by ResourceLoader.
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
 * @author Timo Tijhof
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Database;

/**
 * This class generates message blobs for use by ResourceLoader modules.
 *
 * A message blob is a JSON object containing the interface messages for a certain module in
 * a certain language.
 */
class MessageBlobStore implements LoggerAwareInterface {

	/* @var ResourceLoader|null */
	private $resourceloader;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var WANObjectCache
	 */
	protected $wanCache;

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
		$blobs = $this->getBlobs( [ $module->getName() => $module ], $lang );
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
		$checkKeys = [
			// Global check key, see clear()
			$cache->makeKey( __CLASS__ )
		];
		$cacheKeys = [];
		foreach ( $modules as $name => $module ) {
			$cacheKey = $this->makeCacheKey( $module, $lang );
			$cacheKeys[$name] = $cacheKey;
			// Per-module check key, see updateMessage()
			$checkKeys[$cacheKey][] = $cache->makeKey( __CLASS__, $name );
		}
		$curTTLs = [];
		$result = $cache->getMulti( array_values( $cacheKeys ), $curTTLs, $checkKeys );

		$blobs = [];
		foreach ( $modules as $name => $module ) {
			$key = $cacheKeys[$name];
			if ( !isset( $result[$key] ) || $curTTLs[$key] === null || $curTTLs[$key] < 0 ) {
				$blobs[$name] = $this->recacheMessageBlob( $key, $module, $lang );
			} else {
				// Use unexpired cache
				$blobs[$name] = $result[$key];
			}
		}
		return $blobs;
	}

	/**
	 * @deprecated since 1.27 Use getBlobs() instead
	 * @return array
	 */
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		return $this->getBlobs( $modules, $lang );
	}

	/**
	 * @deprecated since 1.27 Obsolete. Used to populate a cache table in the database.
	 * @return bool
	 */
	public function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		return false;
	}

	/**
	 * @since 1.27
	 * @param ResourceLoaderModule $module
	 * @param string $lang
	 * @return string Cache key
	 */
	private function makeCacheKey( ResourceLoaderModule $module, $lang ) {
		$messages = array_values( array_unique( $module->getMessages() ) );
		sort( $messages );
		return $this->wanCache->makeKey( __CLASS__, $module->getName(), $lang,
			md5( json_encode( $messages ) )
		);
	}

	/**
	 * @since 1.27
	 * @param string $cacheKey
	 * @param ResourceLoaderModule $module
	 * @param string $lang
	 * @return string JSON blob
	 */
	protected function recacheMessageBlob( $cacheKey, ResourceLoaderModule $module, $lang ) {
		$blob = $this->generateMessageBlob( $module, $lang );
		$cache = $this->wanCache;
		$cache->set( $cacheKey, $blob,
			// Add part of a day to TTL to avoid all modules expiring at once
			$cache::TTL_WEEK + mt_rand( 0, $cache::TTL_DAY ),
			Database::getCacheSetOptions( wfGetDB( DB_REPLICA ) )
		);
		return $blob;
	}

	/**
	 * Invalidate cache keys for modules using this message key.
	 * Called by MessageCache when a message has changed.
	 *
	 * @param string $key Message key
	 */
	public function updateMessage( $key ) {
		$moduleNames = $this->getResourceLoader()->getModulesByMessage( $key );
		foreach ( $moduleNames as $moduleName ) {
			// Uses a holdoff to account for database replica DB lag (for MessageCache)
			$this->wanCache->touchCheckKey( $this->wanCache->makeKey( __CLASS__, $moduleName ) );
		}
	}

	/**
	 * Invalidate cache keys for all known modules.
	 * Called by LocalisationCache after cache is regenerated.
	 */
	public function clear() {
		$cache = $this->wanCache;
		// Disable holdoff because this invalidates all modules and also not needed since
		// LocalisationCache is stored outside the database and doesn't have lag.
		$cache->touchCheckKey( $cache->makeKey( __CLASS__ ), $cache::HOLDOFF_NONE );
	}

	/**
	 * @since 1.27
	 * @return ResourceLoader
	 */
	protected function getResourceLoader() {
		// Back-compat: This class supports instantiation without a ResourceLoader object.
		// Lazy-initialise this property because most callers don't need it.
		if ( $this->resourceloader === null ) {
			$this->logger->warning( __CLASS__ . ' created without a ResourceLoader instance' );
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
	protected function fetchMessage( $key, $lang ) {
		$message = wfMessage( $key )->inLanguage( $lang );
		$value = $message->plain();
		if ( !$message->exists() ) {
			$this->logger->warning( 'Failed to find {messageKey} ({lang})', [
				'messageKey' => $key,
				'lang' => $lang,
			] );
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
		$messages = [];
		foreach ( $module->getMessages() as $key ) {
			$messages[$key] = $this->fetchMessage( $key, $lang );
		}

		$json = FormatJson::encode( (object)$messages );
		// @codeCoverageIgnoreStart
		if ( $json === false ) {
			$this->logger->warning( 'Failed to encode message blob for {module} ({lang})', [
				'module' => $module->getName(),
				'lang' => $lang,
			] );
			$json = '{}';
		}
		// codeCoverageIgnoreEnd
		return $json;
	}
}
