<?php
/**
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

namespace MediaWiki\ResourceLoader;

use MediaWiki\Json\FormatJson;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;

/**
 * This class generates message blobs for use by ResourceLoader.
 *
 * A message blob is a JSON object containing the interface messages for a
 * certain module in a certain language.
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class MessageBlobStore implements LoggerAwareInterface {
	/** @var ResourceLoader */
	private $resourceloader;

	/** @var LoggerInterface */
	protected $logger;

	/** @var WANObjectCache */
	protected $wanCache;

	/**
	 * @param ResourceLoader $rl
	 * @param LoggerInterface|null $logger
	 * @param WANObjectCache|null $wanObjectCache
	 */
	public function __construct(
		ResourceLoader $rl,
		?LoggerInterface $logger,
		?WANObjectCache $wanObjectCache
	) {
		$this->resourceloader = $rl;
		$this->logger = $logger ?: new NullLogger();

		// NOTE: when changing this assignment, make sure the code in the instantiator for
		// LocalisationCache which calls MessageBlobStore::clearGlobalCacheEntry() uses the
		// same cache object.
		$this->wanCache = $wanObjectCache ?: MediaWikiServices::getInstance()
			->getMainWANObjectCache();
	}

	/**
	 * @since 1.27
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Get the message blob for a module
	 *
	 * @since 1.27
	 * @param Module $module
	 * @param string $lang Language code
	 * @return string JSON
	 */
	public function getBlob( Module $module, $lang ) {
		$blobs = $this->getBlobs( [ $module->getName() => $module ], $lang );
		return $blobs[$module->getName()];
	}

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @since 1.27
	 * @param Module[] $modules Array of module objects keyed by name
	 * @param string $lang Language code
	 * @return string[] An array mapping module names to message blobs
	 */
	public function getBlobs( array $modules, $lang ) {
		// Each cache key for a message blob by module name and language code also has a generic
		// check key without language code. This is used to invalidate any and all language subkeys
		// that exist for a module from the updateMessage() method.
		$checkKeys = [
			self::makeGlobalPurgeKey( $this->wanCache )
		];
		$cacheKeys = [];
		foreach ( $modules as $name => $module ) {
			$cacheKey = $this->makeBlobCacheKey( $name, $lang, $module );
			$cacheKeys[$name] = $cacheKey;
			$checkKeys[$cacheKey][] = $this->makeModulePurgeKey( $name );
		}
		$curTTLs = [];
		$result = $this->wanCache->getMulti( array_values( $cacheKeys ), $curTTLs, $checkKeys );

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
	 * @param WANObjectCache $cache
	 * @return string Cache key
	 */
	private static function makeGlobalPurgeKey( WANObjectCache $cache ) {
		return $cache->makeGlobalKey( 'resourceloader-messageblob' );
	}

	/**
	 * Per-module check key, for ::updateMessage()
	 *
	 * @param string $name
	 * @return string Cache key
	 */
	private function makeModulePurgeKey( $name ) {
		return $this->wanCache->makeKey( 'resourceloader-messageblob', $name );
	}

	/**
	 * @param string $name
	 * @param string $lang
	 * @param Module $module
	 * @return string Cache key
	 */
	private function makeBlobCacheKey( $name, $lang, Module $module ) {
		$messages = array_values( array_unique( $module->getMessages() ) );
		sort( $messages );
		return $this->wanCache->makeKey( 'resourceloader-messageblob',
			$name,
			$lang,
			md5( json_encode( $messages ) )
		);
	}

	/**
	 * @since 1.27
	 * @param string $cacheKey
	 * @param Module $module
	 * @param string $lang
	 * @return string JSON blob
	 */
	protected function recacheMessageBlob( $cacheKey, Module $module, $lang ) {
		$blob = $this->generateMessageBlob( $module, $lang );
		$cache = $this->wanCache;
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$cache->set( $cacheKey, $blob,
			// Add part of a day to TTL to avoid all modules expiring at once
			$cache::TTL_WEEK + mt_rand( 0, $cache::TTL_DAY ),
			Database::getCacheSetOptions( $dbr )
		);
		return $blob;
	}

	/**
	 * Invalidate cache keys for modules using this message key.
	 * Called by MessageCache when a message has changed.
	 *
	 * @param string $key Message key
	 */
	public function updateMessage( $key ): void {
		$moduleNames = $this->resourceloader->getModulesByMessage( $key );
		foreach ( $moduleNames as $moduleName ) {
			// Use the default holdoff TTL to account for database replica DB lag
			// which can affect MessageCache.
			$this->wanCache->touchCheckKey( $this->makeModulePurgeKey( $moduleName ) );
		}
	}

	/**
	 * Invalidate cache keys for all known modules.
	 *
	 * Used by LocalisationCache, DatabaseUpdater and purgeMessageBlobStore.php script
	 * after regenerating l10n cache.
	 */
	public static function clearGlobalCacheEntry( WANObjectCache $cache ) {
		// Disable holdoff TTL because:
		// - LocalisationCache is populated by messages on-disk and don't have DB lag,
		//   thus there is no need for hold off. We only clear it after new localisation
		//   updates are known to be deployed to all servers.
		// - This global check key invalidates message blobs for all modules for all wikis
		//   in cache contexts (e.g. languages, skins). Setting a hold-off on this key could
		//   cause a cache stampede since no values would be stored for several seconds.
		$cache->touchCheckKey( self::makeGlobalPurgeKey( $cache ), $cache::HOLDOFF_TTL_NONE );
	}

	/**
	 * @since 1.27
	 * @param string $key Message key
	 * @param string $lang Language code
	 * @return string|null
	 */
	protected function fetchMessage( $key, $lang ) {
		$message = wfMessage( $key )->inLanguage( $lang );
		if ( !$message->exists() ) {
			$this->logger->warning( 'Failed to find {messageKey} ({lang})', [
				'messageKey' => $key,
				'lang' => $lang,
			] );
			$value = null;
		} else {
			$value = $message->plain();
		}
		return $value;
	}

	/**
	 * Generate the message blob for a given module in a given language.
	 *
	 * @param Module $module
	 * @param string $lang Language code
	 * @return string JSON blob
	 */
	private function generateMessageBlob( Module $module, $lang ) {
		$messages = [];
		foreach ( $module->getMessages() as $key ) {
			$value = $this->fetchMessage( $key, $lang );
			// If the message does not exist, omit it from the blob so that
			// client-side mw.message may do its own existence handling.
			if ( $value !== null ) {
				$messages[$key] = $value;
			}
		}

		$json = FormatJson::encode( (object)$messages, false, FormatJson::UTF8_OK );
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
