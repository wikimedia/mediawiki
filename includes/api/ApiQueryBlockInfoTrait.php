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
 */

namespace MediaWiki\Api;

use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @ingroup API
 */
trait ApiQueryBlockInfoTrait {
	use ApiBlockInfoTrait;

	/**
	 * Filter hidden users if the current user does not have the ability to
	 * view them. Also add a field hu_deleted which will be true if the user
	 * is hidden.
	 *
	 * @since 1.42
	 */
	private function addDeletedUserFilter() {
		// TODO: inject dependencies the way ApiWatchlistTrait does
		$utils = MediaWikiServices::getInstance()->getHideUserUtils();
		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$this->addWhere( $utils->getExpression( $this->getDB() ) );
			// The field is always false since we are filtering out rows where it is true
			$this->addFields( [ 'hu_deleted' => '1=0' ] );
		} else {
			$this->addFields( [
				'hu_deleted' => $utils->getExpression(
					$this->getDB(),
					'user_id',
					HideUserUtils::HIDDEN_USERS
				)
			] );
		}
	}

	/**
	 * For a set of rows with a user_id field, get the block details for all
	 * users, and return them in array, formatted using
	 * ApiBlockInfoTrait::getBlockDetails().
	 *
	 * @since 1.42
	 * @param iterable<stdClass>|IResultWrapper $rows Rows with a user_id field
	 * @return array The block details indexed by user_id. If a user is not blocked,
	 *   the key will be absent.
	 */
	private function getBlockDetailsForRows( $rows ) {
		$ids = [];
		foreach ( $rows as $row ) {
			$ids[] = (int)$row->user_id;
		}
		if ( !$ids ) {
			return [];
		}
		$blocks = MediaWikiServices::getInstance()->getDatabaseBlockStore()
			->newListFromConds( [ 'bt_user' => $ids ] );
		$blocksByUser = [];
		foreach ( $blocks as $block ) {
			$blocksByUser[$block->getTargetUserIdentity()->getId()][] = $block;
		}
		$infoByUser = [];
		foreach ( $blocksByUser as $id => $userBlocks ) {
			if ( count( $userBlocks ) > 1 ) {
				$maybeCompositeBlock = CompositeBlock::createFromBlocks( ...$userBlocks );
			} else {
				$maybeCompositeBlock = $userBlocks[0];
			}
			$infoByUser[$id] = $this->getBlockDetails( $maybeCompositeBlock );
		}
		return $infoByUser;
	}

	/***************************************************************************/
	// region   Methods required from ApiQueryBase
	/** @name   Methods required from ApiQueryBase */

	/**
	 * @see ApiBase::getDB
	 * @return IReadableDatabase
	 */
	abstract protected function getDB();

	/**
	 * @see IContextSource::getAuthority
	 * @return Authority
	 */
	abstract public function getAuthority();

	/**
	 * @see ApiQueryBase::addTables
	 * @param string|array $tables
	 * @param string|null $alias
	 */
	abstract protected function addTables( $tables, $alias = null );

	/**
	 * @see ApiQueryBase::addFields
	 * @param array|string $fields
	 */
	abstract protected function addFields( $fields );

	/**
	 * @see ApiQueryBase::addWhere
	 * @param string|array|IExpression $conds
	 */
	abstract protected function addWhere( $conds );

	/**
	 * @see ApiQueryBase::addJoinConds
	 * @param array $conds
	 */
	abstract protected function addJoinConds( $conds );

	/**
	 * @return SelectQueryBuilder
	 */
	abstract protected function getQueryBuilder();

	// endregion -- end of methods required from ApiQueryBase

}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryBlockInfoTrait::class, 'ApiQueryBlockInfoTrait' );
