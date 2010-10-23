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
 * consistuent messages or the resource itself is changed.
 */
class MessageBlobStore {

	/**
	 * Get the message blobs for a set of modules
	 *
	 * @param $resourceLoader ResourceLoader object
	 * @param $modules array Array of module objects keyed by module name
	 * @param $lang string Language code
	 * @return array An array mapping module names to message blobs
	 */
	public static function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		wfProfileIn( __METHOD__ );
		if ( !count( $modules ) ) {
			wfProfileOut( __METHOD__ );
			return array();
		}
		// Try getting from the DB first
		$blobs = self::getFromDB( $resourceLoader, array_keys( $modules ), $lang );

		// Generate blobs for any missing modules and store them in the DB
		$missing = array_diff( array_keys( $modules ), array_keys( $blobs ) );
		foreach ( $missing as $name ) {
			$blob = self::insertMessageBlob( $name, $modules[$name], $lang );
			if ( $blob ) {
				$blobs[$name] = $blob;
			}
		}

		wfProfileOut( __METHOD__ );
		return $blobs;
	}

	/**
	 * Generate and insert a new message blob. If the blob was already
	 * present, it is not regenerated; instead, the preexisting blob
	 * is fetched and returned.
	 *
	 * @param $name String: module name
	 * @param $module ResourceLoaderModule object
	 * @param $lang String: language code
	 * @return mixed Message blob or false if the module has no messages
	 */
	public static function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		$blob = self::generateMessageBlob( $module, $lang );

		if ( !$blob ) {
			return false;
		}

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

		return $blob;
	}

	/**
	 * Update all message blobs for a given module.
	 *
	 * @param $name String: module name
	 * @param $module ResourceLoaderModule object
	 * @param $lang String: language code (optional)
	 * @return Mixed: if $lang is set, the new message blob for that language is 
	 *    returned if present. Otherwise, null is returned.
	 */
	public static function updateModule( $name, ResourceLoaderModule $module, $lang = null ) {
		$retval = null;

		// Find all existing blobs for this module
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select( 'msg_resource',
			array( 'mr_lang', 'mr_blob' ),
			array( 'mr_resource' => $name ),
			__METHOD__
		);

		// Build the new msg_resource rows
		$newRows = array();
		$now = $dbw->timestamp();
		// Save the last-processed old and new blobs for later
		$oldBlob = $newBlob = null;

		foreach ( $res as $row ) {
			$oldBlob = $row->mr_blob;
			$newBlob = self::generateMessageBlob( $module, $row->mr_lang );

			if ( $row->mr_lang === $lang ) {
				$retval = $newBlob;
			}
			$newRows[] = array(
				'mr_resource' => $name,
				'mr_lang' => $row->mr_lang,
				'mr_blob' => $newBlob,
				'mr_timestamp' => $now
			);
		}

		$dbw->replace( 'msg_resource',
			array( array( 'mr_resource', 'mr_lang' ) ),
			$newRows, __METHOD__
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

		return $retval;
	}

	/**
	 * Update a single message in all message blobs it occurs in.
	 *
	 * @param $key String: message key
	 */
	public static function updateMessage( $key ) {
		$dbw = wfGetDB( DB_MASTER );

		// Keep running until the updates queue is empty.
		// Due to update conflicts, the queue might not be emptied
		// in one iteration.
		$updates = null;
		do {
			$updates = self::getUpdatesForMessage( $key, $updates );

			foreach ( $updates as $key => $update ) {
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
					unset( $updates[$key] );
				}
			}
		} while ( count( $updates ) );

		// No need to update msg_resource_links because we didn't add
		// or remove any messages, we just changed their contents.
	}

	public static function clear() {
		// TODO: Give this some more thought
		// TODO: Is TRUNCATE better?
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'msg_resource', '*', __METHOD__ );
		$dbw->delete( 'msg_resource_links', '*', __METHOD__ );
	}

	/**
	 * Create an update queue for updateMessage()
	 *
	 * @param $key String: message key
	 * @param $prevUpdates Array: updates queue to refresh or null to build a fresh update queue
	 * @return Array: updates queue
	 */
	private static function getUpdatesForMessage( $key, $prevUpdates = null ) {
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
				'newBlob' => self::reencodeBlob( $row->mr_blob, $key, $row->mr_lang )
			);
		}

		return $updates;
	}

	/**
	 * Reencode a message blob with the updated value for a message
	 *
	 * @param $blob String: message blob (JSON object)
	 * @param $key String: message key
	 * @param $lang String: language code
	 * @return Message blob with $key replaced with its new value
	 */
	private static function reencodeBlob( $blob, $key, $lang ) {
		$decoded = FormatJson::decode( $blob, true );
		$decoded[$key] = wfMsgExt( $key, array( 'language' => $lang ) );

		return FormatJson::encode( $decoded );
	}

	/**
	 * Get the message blobs for a set of modules from the database.
	 * Modules whose blobs are not in the database are silently dropped.
	 *
	 * @param $resourceLoader ResourceLoader object
	 * @param $modules Array of module names
	 * @param $lang String: language code
	 * @return array Array mapping module names to blobs
	 */
	private static function getFromDB( ResourceLoader $resourceLoader, $modules, $lang ) {
		global $wgCacheEpoch;
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
			// older than $wgCacheEpoch
			if ( array_keys( FormatJson::decode( $row->mr_blob, true ) ) !== $module->getMessages() ||
					wfTimestamp( TS_MW, $row->mr_timestamp ) <= $wgCacheEpoch ) {
				$retval[$row->mr_resource] = self::updateModule( $row->mr_resource, $module, $lang );
			} else {
				$retval[$row->mr_resource] = $row->mr_blob;
			}
		}

		return $retval;
	}

	/**
	 * Generate the message blob for a given module in a given language.
	 *
	 * @param $module ResourceLoaderModule object
	 * @param $lang String: language code
	 * @return String: JSON object
	 */
	private static function generateMessageBlob( ResourceLoaderModule $module, $lang ) {
		$messages = array();

		foreach ( $module->getMessages() as $key ) {
			$messages[$key] = wfMsgExt( $key, array( 'language' => $lang ) );
		}

		return FormatJson::encode( $messages );
	}
}
