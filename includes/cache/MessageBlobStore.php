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

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @param ResourceLoader $rl
	 * @param LoggerInterface $logger
	 */
	public function __construct( ResourceLoader $unused = null, LoggerInterface $logger = null ) {
		$this->logger = $logger ?: new NullLogger();
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
		return $this->generateMessageBlob( $module, $lang );
	}

	/**
	 * Generate message blobs for a set of modules
	 *
	 * @param array $modules Array of module objects keyed by name
	 * @param string $lang Language code
	 * @return array An array mapping module names to message blobs
	 */
	public function getBlobs( $modules, $lang ) {
		$blobs = array();
		foreach ( $modules as $name => $module ) {
			$blobs[$name] = $this->generateMessageBlob( $module, $lang );
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
	 * Previously used to update a cache table in the database.
	 *
	 * @deprecated since 1.27 Obsolete
	 * @return null
	 */
	public function updateModule( $name, ResourceLoaderModule $module, $lang ) {
		return null;
	}

	/**
	 * Previously used to purge rows from a cache table in the database.
	 *
	 * @deprecated since 1.27 Obsolete
	 */
	public function updateMessage( $key ) {
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
	 * @param string $key Message key
	 * @param string $lang Language code
	 * @return string
	 */
	private function fetchMessage( $key, $lang ) {
		$message = wfMessage( $key )->inLanguage( $lang );
		if ( !$message->exists() ) {
			$this->logger->warning( __METHOD__ . " failed to find {message} ({lang})", array(
				'message' => $key,
				'lang' => $lang,
			) );
		}
		return $message->plain();
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
