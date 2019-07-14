<?php
/**
 * Block restriction interface.
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
 */

namespace MediaWiki\Block;

use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MWException;
use stdClass;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

class BlockRestrictionStore {

	/**
	 * Map of all of the restriction types.
	 */
	private $types = [
		PageRestriction::TYPE_ID => PageRestriction::class,
		NamespaceRestriction::TYPE_ID => NamespaceRestriction::class,
	];

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @param ILoadBalancer $loadBalancer load balancer for acquiring database connections
	 */
	public function __construct( ILoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Retrieves the restrictions from the database by block id.
	 *
	 * @since 1.33
	 * @param int|array $blockId
	 * @param IDatabase|null $db
	 * @return Restriction[]
	 */
	public function loadByBlockId( $blockId, IDatabase $db = null ) {
		if ( $blockId === null || $blockId === [] ) {
			return [];
		}

		$db = $db ?: $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$result = $db->select(
			[ 'ipblocks_restrictions', 'page' ],
			[ 'ir_ipb_id', 'ir_type', 'ir_value', 'page_namespace', 'page_title' ],
			[ 'ir_ipb_id' => $blockId ],
			__METHOD__,
			[],
			[ 'page' => [ 'LEFT JOIN', [ 'ir_type' => PageRestriction::TYPE_ID, 'ir_value=page_id' ] ] ]
		);

		return $this->resultToRestrictions( $result );
	}

	/**
	 * Inserts the restrictions into the database.
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return bool
	 */
	public function insert( array $restrictions ) {
		if ( !$restrictions ) {
			return false;
		}

		$rows = [];
		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}
			$rows[] = $restriction->toRow();
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$dbw->insert(
			'ipblocks_restrictions',
			$rows,
			__METHOD__,
			[ 'IGNORE' ]
		);

		return true;
	}

	/**
	 * Updates the list of restrictions. This method does not allow removing all
	 * of the restrictions. To do that, use ::deleteByBlockId().
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @return bool
	 */
	public function update( array $restrictions ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );

		// Organize the restrictions by blockid.
		$restrictionList = $this->restrictionsByBlockId( $restrictions );

		// Load the existing restrictions and organize by block id. Any block ids
		// that were passed into this function will be used to load all of the
		// existing restrictions. This list might be the same, or may be completely
		// different.
		$existingList = [];
		$blockIds = array_keys( $restrictionList );
		if ( !empty( $blockIds ) ) {
			$result = $dbw->select(
				[ 'ipblocks_restrictions' ],
				[ 'ir_ipb_id', 'ir_type', 'ir_value' ],
				[ 'ir_ipb_id' => $blockIds ],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);

			$existingList = $this->restrictionsByBlockId(
				$this->resultToRestrictions( $result )
			);
		}

		$result = true;
		// Perform the actions on a per block-id basis.
		foreach ( $restrictionList as $blockId => $blockRestrictions ) {
			// Insert all of the restrictions first, ignoring ones that already exist.
			$success = $this->insert( $blockRestrictions );

			// Update the result. The first false is the result, otherwise, true.
			$result = $success && $result;

			$restrictionsToRemove = $this->restrictionsToRemove(
				$existingList[$blockId] ?? [],
				$restrictions
			);

			if ( empty( $restrictionsToRemove ) ) {
				continue;
			}

			$success = $this->delete( $restrictionsToRemove );

			// Update the result. The first false is the result, otherwise, true.
			$result = $success && $result;
		}

		$dbw->endAtomic( __METHOD__ );

		return $result;
	}

	/**
	 * Updates the list of restrictions by parent id.
	 *
	 * @since 1.33
	 * @param int $parentBlockId
	 * @param Restriction[] $restrictions
	 * @return bool
	 */
	public function updateByParentBlockId( $parentBlockId, array $restrictions ) {
		// If removing all of the restrictions, then just delete them all.
		if ( empty( $restrictions ) ) {
			return $this->deleteByParentBlockId( $parentBlockId );
		}

		$parentBlockId = (int)$parentBlockId;

		$db = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$db->startAtomic( __METHOD__ );

		$blockIds = $db->selectFieldValues(
			'ipblocks',
			'ipb_id',
			[ 'ipb_parent_block_id' => $parentBlockId ],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);

		$result = true;
		foreach ( $blockIds as $id ) {
			$success = $this->update( $this->setBlockId( $id, $restrictions ) );
			// Update the result. The first false is the result, otherwise, true.
			$result = $success && $result;
		}

		$db->endAtomic( __METHOD__ );

		return $result;
	}

	/**
	 * Delete the restrictions.
	 *
	 * @since 1.33
	 * @param Restriction[] $restrictions
	 * @throws MWException
	 * @return bool
	 */
	public function delete( array $restrictions ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$result = true;
		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			$success = $dbw->delete(
				'ipblocks_restrictions',
				// The restriction row is made up of a compound primary key. Therefore,
				// the row and the delete conditions are the same.
				$restriction->toRow(),
				__METHOD__
			);
			// Update the result. The first false is the result, otherwise, true.
			$result = $success && $result;
		}

		return $result;
	}

	/**
	 * Delete the restrictions by block ID.
	 *
	 * @since 1.33
	 * @param int|array $blockId
	 * @throws MWException
	 * @return bool
	 */
	public function deleteByBlockId( $blockId ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		return $dbw->delete(
			'ipblocks_restrictions',
			[ 'ir_ipb_id' => $blockId ],
			__METHOD__
		);
	}

	/**
	 * Delete the restrictions by parent block ID.
	 *
	 * @since 1.33
	 * @param int|array $parentBlockId
	 * @throws MWException
	 * @return bool
	 */
	public function deleteByParentBlockId( $parentBlockId ) {
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		return $dbw->deleteJoin(
			'ipblocks_restrictions',
			'ipblocks',
			'ir_ipb_id',
			'ipb_id',
			[ 'ipb_parent_block_id' => $parentBlockId ],
			__METHOD__
		);
	}

	/**
	 * Checks if two arrays of Restrictions are effectively equal. This is a loose
	 * equality check as the restrictions do not have to contain the same block
	 * ids.
	 *
	 * @since 1.33
	 * @param Restriction[] $a
	 * @param Restriction[] $b
	 * @return bool
	 */
	public function equals( array $a, array $b ) {
		$filter = function ( $restriction ) {
			return $restriction instanceof Restriction;
		};

		// Ensure that every item in the array is a Restriction. This prevents a
		// fatal error from calling Restriction::getHash if something in the array
		// is not a restriction.
		$a = array_filter( $a, $filter );
		$b = array_filter( $b, $filter );

		$aCount = count( $a );
		$bCount = count( $b );

		// If the count is different, then they are obviously a different set.
		if ( $aCount !== $bCount ) {
			return false;
		}

		// If both sets contain no items, then they are the same set.
		if ( $aCount === 0 && $bCount === 0 ) {
			return true;
		}

		$hasher = function ( $r ) {
			return $r->getHash();
		};

		$aHashes = array_map( $hasher, $a );
		$bHashes = array_map( $hasher, $b );

		sort( $aHashes );
		sort( $bHashes );

		return $aHashes === $bHashes;
	}

	/**
	 * Set the blockId on a set of restrictions and return a new set.
	 *
	 * @since 1.33
	 * @param int $blockId
	 * @param Restriction[] $restrictions
	 * @return Restriction[]
	 */
	public function setBlockId( $blockId, array $restrictions ) {
		$blockRestrictions = [];

		foreach ( $restrictions as $restriction ) {
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			// Clone the restriction so any references to the current restriction are
			// not suddenly changed to a different blockId.
			$restriction = clone $restriction;
			$restriction->setBlockId( $blockId );

			$blockRestrictions[] = $restriction;
		}

		return $blockRestrictions;
	}

	/**
	 * Get the restrictions that should be removed, which are existing
	 * restrictions that are not in the new list of restrictions.
	 *
	 * @param Restriction[] $existing
	 * @param Restriction[] $new
	 * @return array
	 */
	private function restrictionsToRemove( array $existing, array $new ) {
		return array_filter( $existing, function ( $e ) use ( $new ) {
			foreach ( $new as $restriction ) {
				if ( !$restriction instanceof Restriction ) {
					continue;
				}

				if ( $restriction->equals( $e ) ) {
					return false;
				}
			}

			return true;
		} );
	}

	/**
	 * Converts an array of restrictions to an associative array of restrictions
	 * where the keys are the block ids.
	 *
	 * @param Restriction[] $restrictions
	 * @return array
	 */
	private function restrictionsByBlockId( array $restrictions ) {
		$blockRestrictions = [];

		foreach ( $restrictions as $restriction ) {
			// Ensure that all of the items in the array are restrictions.
			if ( !$restriction instanceof Restriction ) {
				continue;
			}

			if ( !isset( $blockRestrictions[$restriction->getBlockId()] ) ) {
				$blockRestrictions[$restriction->getBlockId()] = [];
			}

			$blockRestrictions[$restriction->getBlockId()][] = $restriction;
		}

		return $blockRestrictions;
	}

	/**
	 * Convert an Result Wrapper to an array of restrictions.
	 *
	 * @param IResultWrapper $result
	 * @return Restriction[]
	 */
	private function resultToRestrictions( IResultWrapper $result ) {
		$restrictions = [];
		foreach ( $result as $row ) {
			$restriction = $this->rowToRestriction( $row );

			if ( !$restriction ) {
				continue;
			}

			$restrictions[] = $restriction;
		}

		return $restrictions;
	}

	/**
	 * Convert a result row from the database into a restriction object.
	 *
	 * @param stdClass $row
	 * @return Restriction|null
	 */
	private function rowToRestriction( stdClass $row ) {
		if ( array_key_exists( (int)$row->ir_type, $this->types ) ) {
			$class = $this->types[ (int)$row->ir_type ];
			return call_user_func( [ $class, 'newFromRow' ], $row );
		}

		return null;
	}
}
