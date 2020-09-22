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
 *
 * @author Ostrzyciel
 */

namespace MediaWiki\Storage;

use BagOStuff;
use FormatJson;
use MediaWiki\Config\ServiceOptions;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Class allowing easy storage and retrieval of EditResults associated with revisions.
 *
 * EditResults are stored in the main object stash and (depending on wiki's configuration)
 * in revert change tags. This class stores the relevant data in the main stash. When
 * asked to retrieve an EditResult for an edit and the requested key is not present in the
 * main stash, the class will attempt to retrieve the EditResult from revert tags.
 *
 * @since 1.36
 *
 * @internal Used by RevertedTagUpdateManager
 */
class EditResultCache {

	public const CONSTRUCTOR_OPTIONS = [
		'RCMaxAge'
	];

	private const CACHE_KEY_PREFIX = 'EditResult';

	/** @var BagOStuff */
	private $mainObjectStash;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ServiceOptions */
	private $options;

	/**
	 * @param BagOStuff $mainObjectStash Main object stash, see
	 *  MediaWikiServices::getMainObjectStash()
	 * @param ILoadBalancer $loadBalancer
	 * @param ServiceOptions $options
	 */
	public function __construct(
		BagOStuff $mainObjectStash,
		ILoadBalancer $loadBalancer,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->mainObjectStash = $mainObjectStash;
		$this->loadBalancer = $loadBalancer;
		$this->options = $options;
	}

	/**
	 * Store the EditResult in the main object stash.
	 *
	 * @param int $revisionId
	 * @param EditResult $editResult
	 *
	 * @return bool Success
	 */
	public function set( int $revisionId, EditResult $editResult ) : bool {
		return $this->mainObjectStash->set(
			$this->makeKey( $revisionId ),
			FormatJson::encode( $editResult ),
			// Patrol flags are not stored for longer than $wgRCMaxAge
			$this->options->get( 'RCMaxAge' )
		);
	}

	/**
	 * Get an EditResult for the given revision ID.
	 *
	 * Will first attempt to get the EditResult from the main stash. If this fails, it
	 * will try to retrieve the EditResult from revert change tags of this revision.
	 *
	 * @param int $revisionId
	 *
	 * @return EditResult|null Returns null on failure
	 */
	public function get( int $revisionId ) : ?EditResult {
		$result = $this->mainObjectStash->get( $this->makeKey( $revisionId ) );

		// not found in stash, try change tags
		if ( !$result ) {
			$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
			$result = $dbr->selectField(
				[ 'change_tag', 'change_tag_def' ],
				'ct_params',
				[
					'ct_rev_id' => $revisionId,
					'ctd_id = ct_tag_id',
					'ctd_name' => [
						'mw-rollback',
						'mw-undo',
						'mw-manual-revert'
					]
				],
				__METHOD__
			);
		}

		if ( !$result ) {
			return null;
		}

		$decoded = FormatJson::decode( $result, true );
		return $decoded ? EditResult::newFromArray( $decoded ) : null;
	}

	/**
	 * Generates a cache key for the given revision ID.
	 *
	 * @param int $revisionId
	 *
	 * @return string
	 */
	private function makeKey( int $revisionId ) : string {
		return $this->mainObjectStash->makeKey( self::CACHE_KEY_PREFIX, $revisionId );
	}
}
