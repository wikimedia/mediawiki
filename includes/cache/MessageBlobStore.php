<?php
/**
 * Resource message blobs storage used by the resource loader.
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
 * This class provides access to the resource message blobs storage used by
 * the ResourceLoader.
 *
 * A message blob is a JSON object containing the interface messages for a
 * certain resource in a certain language. These message blobs are cached
 * in the msg_resource table and automatically invalidated when one of their
 * constituent messages or the resource itself is changed.
 */
class MessageBlobStore {
	/**
	 * In-process cache for message blobs.
	 *
	 * Keyed by language code, then module name.
	 *
	 * @var array
	 */
	protected $blobCache = array();

	/**
	 * Get the singleton instance
	 *
	 * @since 1.24
	 * @deprecated since 1.25
	 * @return MessageBlobStore
	 */
	public static function getInstance() {
		wfDeprecated( __METHOD__, '1.25' );
		return new self;
	}

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @param ResourceLoader $resourceLoader
	 * @param array $modules Array of module objects keyed by module name
	 * @param string $lang Language code
	 * @return array An array mapping module names to message blobs
	 */
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		if ( !count( $modules ) ) {
			return array();
		}

		$blobs = array();

		// Try in-process cache
		$missingFromCache = array();
		foreach ( $modules as $name => $module ) {
			if ( isset( $this->blobCache[$lang][$name] ) ) {
				$blobs[$name] = $this->blobCache[$lang][$name];
			} else {
				$missingFromCache[] = $name;
			}
		}

		// Try DB cache
		if ( $missingFromCache ) {
			$blobs += $this->getFromDB( $resourceLoader, $missingFromCache, $lang );
		}

		// Generate new blobs for any remaining modules and store in DB
		$missingFromDb = array_diff( array_keys( $modules ), array_keys( $blobs ) );
		foreach ( $missingFromDb as $name ) {
			$blob = $this->insertMessageBlob( $name, $modules[$name], $lang );
			if ( $blob ) {
				$blobs[$name] = $blob;
			}
		}

		// Update in-process cache
		if ( isset( $this->blobCache[$lang] ) ) {
			$this->blobCache[$lang] += $blobs;
		} else {
			$this->blobCache[$lang] = $blobs;
		}

		return $blobs;
	}

	/**
	 * Generate and insert a new message blob. If the blob was already
	 * present, it is not regenerated; instead, the preexisting blob
	 * is fetched and returned.
	 *
	 * @param string $name Module name
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return mixed Message blob or false if the module has no messages
	 */
	public function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		$blob = $this->generateMessageBlob( $module, $lang );

		if ( !$blob ) {
			return false;
		}

		try {
			$dbw = wfGetDB( DB_MASTER );
			$success = $dbw->insert( 'msg_resource', array(
					'mr_lang' => $lang,
					'mr_resource' => $name,
					'mr_blob' => $blob,
					'mr_timestamp' => $dbw->timestamp()
				),
				__METHOD__,
				array( 'IGNORE' )
			);

			if ( $success ) {
				if ( $dbw->affectedRows() == 0 ) {
					// Blob was already present, fetch it
					$blob = $dbw->selectField( 'msg_resource', 'mr_blob', array(
							'mr_resource' => $name,
							'mr_lang' => $lang,
						),
						__METHOD__
					);
				} else {
					// Update msg_resource_links
					$rows = array();

					foreach ( $module->getMessages() as $key ) {
						$rows[] = array(
							'mrl_resource' => $name,
							'mrl_message' => $key
						);
					}
					$dbw->insert( 'msg_resource_links', $rows,
						__METHOD__, array( 'IGNORE' )
					);
				}
			}
		} catch ( DBError $e ) {
			wfDebug( __METHOD__ . " failed to update DB: $e\n" );
		}
		return $blob;
	}

	/**
	 * Update the message blob for a given module in a given language
	 *
	 * @param string $name Module name
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string Regenerated message blob, or null if there was no blob for
	 *   the given module/language pair.
	 */
	public function updateModule( $name, ResourceLoaderModule $module, $lang ) {
		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow( 'msg_resource', 'mr_blob',
			array( 'mr_resource' => $name, 'mr_lang' => $lang ),
			__METHOD__
		);
		if ( !$row ) {
			return null;
		}

		// Save the old and new blobs for later
		$oldBlob = $row->mr_blob;
		$newBlob = $this->generateMessageBlob( $module, $lang );

		try {
			$newRow = array(
				'mr_resource' => $name,
				'mr_lang' => $lang,
				'mr_blob' => $newBlob,
				'mr_timestamp' => $dbw->timestamp()
			);

			$dbw->replace( 'msg_resource',
				array( array( 'mr_resource', 'mr_lang' ) ),
				$newRow, __METHOD__
			);

			// Figure out which messages were added and removed
			$oldMessages = array_keys( FormatJson::decode( $oldBlob, true ) );
			$newMessages = array_keys( FormatJson::decode( $newBlob, true ) );
			$added = array_diff( $newMessages, $oldMessages );
			$removed = array_diff( $oldMessages, $newMessages );

			// Delete removed messages, insert added ones
			if ( $removed ) {
				$dbw->delete( 'msg_resource_links', array(
						'mrl_resource' => $name,
						'mrl_message' => $removed
					), __METHOD__
				);
			}

			$newLinksRows = array();

			foreach ( $added as $message ) {
				$newLinksRows[] = array(
					'mrl_resource' => $name,
					'mrl_message' => $message
				);
			}

			if ( $newLinksRows ) {
				$dbw->insert( 'msg_resource_links', $newLinksRows, __METHOD__,
					array( 'IGNORE' ) // just in case
				);
			}
		} catch ( Exception $e ) {
			wfDebug( __METHOD__ . " failed to update DB: $e\n" );
		}
		return $newBlob;
	}

	/**
	 * Update a single message in all message blobs it occurs in.
	 *
	 * @param string $key Message key
	 */
	public function updateMessage( $key ) {
		try {
			$dbw = wfGetDB( DB_MASTER );

			// Keep running until the updates queue is empty.
			// Due to update conflicts, the queue might not be emptied
			// in one iteration.
			$updates = null;
			do {
				$updates = $this->getUpdatesForMessage( $key, $updates );

				foreach ( $updates as $k => $update ) {
					// Update the row on the condition that it
					// didn't change since we fetched it by putting
					// the timestamp in the WHERE clause.
					$success = $dbw->update( 'msg_resource',
						array(
							'mr_blob' => $update['newBlob'],
							'mr_timestamp' => $dbw->timestamp() ),
						array(
							'mr_resource' => $update['resource'],
							'mr_lang' => $update['lang'],
							'mr_timestamp' => $update['timestamp'] ),
						__METHOD__
					);

					// Only requeue conflicted updates.
					// If update() returned false, don't retry, for
					// fear of getting into an infinite loop
					if ( !( $success && $dbw->affectedRows() == 0 ) ) {
						// Not conflicted
						unset( $updates[$k] );
					}
				}
			} while ( count( $updates ) );

			// No need to update msg_resource_links because we didn't add
			// or remove any messages, we just changed their contents.
		} catch ( Exception $e ) {
			wfDebug( __METHOD__ . " failed to update DB: $e\n" );
		}
	}

	public function clear() {
		// TODO: Give this some more thought
		try {
			// Not using TRUNCATE, because that needs extra permissions,
			// which maybe not granted to the database user.
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete( 'msg_resource', '*', __METHOD__ );
			$dbw->delete( 'msg_resource_links', '*', __METHOD__ );
		} catch ( Exception $e ) {
			wfDebug( __METHOD__ . " failed to update DB: $e\n" );
		}
	}

	/**
	 * Create an update queue for updateMessage()
	 *
	 * @param string $key Message key
	 * @param array $prevUpdates Updates queue to refresh or null to build a fresh update queue
	 * @return array Updates queue
	 */
	private function getUpdatesForMessage( $key, $prevUpdates = null ) {
		$dbw = wfGetDB( DB_MASTER );

		if ( is_null( $prevUpdates ) ) {
			// Fetch all blobs referencing $key
			$res = $dbw->select(
				array( 'msg_resource', 'msg_resource_links' ),
				array( 'mr_resource', 'mr_lang', 'mr_blob', 'mr_timestamp' ),
				array( 'mrl_message' => $key, 'mr_resource=mrl_resource' ),
				__METHOD__
			);
		} else {
			// Refetch the blobs referenced by $prevUpdates

			// Reorganize the (resource, lang) pairs in the format
			// expected by makeWhereFrom2d()
			$twoD = array();

			foreach ( $prevUpdates as $update ) {
				$twoD[$update['resource']][$update['lang']] = true;
			}

			$res = $dbw->select( 'msg_resource',
				array( 'mr_resource', 'mr_lang', 'mr_blob', 'mr_timestamp' ),
				$dbw->makeWhereFrom2d( $twoD, 'mr_resource', 'mr_lang' ),
				__METHOD__
			);
		}

		// Build the new updates queue
		$updates = array();

		foreach ( $res as $row ) {
			$updates[] = array(
				'resource' => $row->mr_resource,
				'lang' => $row->mr_lang,
				'timestamp' => $row->mr_timestamp,
				'newBlob' => $this->reencodeBlob( $row->mr_blob, $key, $row->mr_lang )
			);
		}

		return $updates;
	}

	/**
	 * Reencode a message blob with the updated value for a message
	 *
	 * @param string $blob Message blob (JSON object)
	 * @param string $key Message key
	 * @param string $lang Language code
	 * @return string Message blob with $key replaced with its new value
	 */
	private function reencodeBlob( $blob, $key, $lang ) {
		$decoded = FormatJson::decode( $blob, true );
		$decoded[$key] = wfMessage( $key )->inLanguage( $lang )->plain();

		return FormatJson::encode( (object)$decoded );
	}

	/**
	 * Get the message blobs for a set of modules from the database.
	 * Modules whose blobs are not in the database are silently dropped.
	 *
	 * @param ResourceLoader $resourceLoader
	 * @param array $modules Array of module names
	 * @param string $lang Language code
	 * @throws MWException
	 * @return array Array mapping module names to blobs
	 */
	private function getFromDB( ResourceLoader $resourceLoader, $modules, $lang ) {
		if ( !count( $modules ) ) {
			return array();
		}

		$config = $resourceLoader->getConfig();
		$retval = array();
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'msg_resource',
			array( 'mr_blob', 'mr_resource', 'mr_timestamp' ),
			array( 'mr_resource' => $modules, 'mr_lang' => $lang ),
			__METHOD__
		);

		foreach ( $res as $row ) {
			$module = $resourceLoader->getModule( $row->mr_resource );
			if ( !$module ) {
				// This shouldn't be possible
				throw new MWException( __METHOD__ . ' passed an invalid module name' );
			}

			// Update the module's blobs if the set of messages changed or if the blob is
			// older than the CacheEpoch setting
			$keys = array_keys( FormatJson::decode( $row->mr_blob, true ) );
			$values = array_values( array_unique( $module->getMessages() ) );
			if ( $keys !== $values
				|| wfTimestamp( TS_MW, $row->mr_timestamp ) <= $config->get( 'CacheEpoch' )
			) {
				$retval[$row->mr_resource] = $this->updateModule( $row->mr_resource, $module, $lang );
			} else {
				$retval[$row->mr_resource] = $row->mr_blob;
			}
		}

		return $retval;
	}

	/**
	 * Generate the message blob for a given module in a given language.
	 *
	 * @param ResourceLoaderModule $module
	 * @param string $lang Language code
	 * @return string JSON object
	 */
	private function generateMessageBlob( ResourceLoaderModule $module, $lang ) {
		$messages = array();

		foreach ( $module->getMessages() as $key ) {
			$messages[$key] = wfMessage( $key )->inLanguage( $lang )->plain();
		}

		return FormatJson::encode( (object)$messages );
	}
}
