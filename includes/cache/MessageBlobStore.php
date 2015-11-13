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

/**
 * This class provides access to the message blobs used by ResourceLoader modules.
 *
 * A message blob is a JSON object containing the interface messages for a
 * certain module in a certain language. These message blobs are cached
 * in the automatically invalidated when one of their constituent messages,
 * or the module definition, is changed.
 */
class MessageBlobStore {

	public function __construct( ResourceLoader $unused = null ) {
	}

	/**
	 * Get the message blob for a module
	 *
	 * @since 1.27
	 * @param ResourceLoaderModule|array $modules Array of module objects keyed by module name
	 * @param string $lang Language code
	 * @return string|null JSON blob or null of module has no messages
	 */
	public function getBlob( ResourceLoaderModule $module, $lang ) {
		return $this->generateMessageBlob( $module, $lang );
	}

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @param ResourceLoader $resourceLoader
	 * @param ResourceLoaderModule|array $modules Array of module objects keyed by module name
	 * @param string $lang Language code
	 * @return array An array mapping module names to message blobs
	 */
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		$modules = is_array( $modules ) ? $modules : array( $modules );

		if ( !count( $modules ) ) {
			return array();
		}

		$blobs = array();

		// Try to generate blobs for each module
		foreach ( $module as $name => $module ) {
			$blob = $this->generateMessageBlob( $module, $lang );
			if ( $blob ) {
				$blobs[$name] = $blob;
			}
		}

		return $blobs;
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
			wfDebugLog( 'resourceloader', __METHOD__ . " failed to find: '$key' ($lang)" );
		}
		return $message->plain();
	}

	/**
	 * Generate the message blob for a given module in a given language.
	 *
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string|null JSON blob or null if the module has no messages
	 */
	private function generateMessageBlob( ResourceLoaderModule $module, $lang ) {
		if ( !$module->getMessages() ) {
			return null;
		}
		$messages = array();
		foreach ( $module->getMessages() as $key ) {
			$messages[$key] = $this->fetchMessage( $key, $lang );
		}

		return FormatJson::encode( (object)$messages );
	}

	/**
	 * Update a single message in all message blobs it occurs in.
	 *
	 * Called from MessageCache when a message value is edited.
	 *
	 * @param string $key Message key
	 */
	public function updateMessage( $key ) {
		// TODO:
		// - Find which modules use the message
		// - Purge those keys
	}


	/**
	 * Generate the message blob for a module/language pair.
	 *
	 * Previously used to populate a cache table in the database.
	 *
	 * @deprecated since 1.27 Obsolete
	 * @param string $name Module name
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string|bool Message blob or false if the module has no messages
	 */
	public function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		return $this->generateMessageBlob( $module, $lang ) ?: false;
	}

	/**
	 * Update the message blob in the database for a module/language pair.
	 *
	 * @deprecated since 1.27 Obsolete
	 * @param string $name Module name
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return null
	 */
	public function updateModule( $name, ResourceLoaderModule $module, $lang ) {
		return null;
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
}
