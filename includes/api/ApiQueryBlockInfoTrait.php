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

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Permissions\PermissionManager;

/**
 * @ingroup API
 */
trait ApiQueryBlockInfoTrait {
	use ApiBlockInfoTrait;

	/**
	 * Filters hidden users (where the user doesn't have the right to view them)
	 * Also adds relevant block information
	 *
	 * @param bool $showBlockInfo
	 * @return void
	 */
	private function addBlockInfoToQuery( $showBlockInfo ) {
		$db = $this->getDB();

		if ( $showBlockInfo ) {
			$queryInfo = DatabaseBlock::getQueryInfo();
		} else {
			$queryInfo = [
				'tables' => [ 'ipblocks' ],
				'fields' => [ 'ipb_deleted' ],
				'joins' => [],
			];
		}

		$this->addTables( [ 'blk' => $queryInfo['tables'] ] );
		$this->addFields( $queryInfo['fields'] );
		$this->addJoinConds( $queryInfo['joins'] );
		$this->addJoinConds( [
			'blk' => [ 'LEFT JOIN', [
				'ipb_user=user_id',
				'ipb_expiry > ' . $db->addQuotes( $db->timestamp() ),
			] ],
		] );

		// Don't show hidden names
		if ( !$this->getPermissionManager()->userHasRight( $this->getUser(), 'hideuser' ) ) {
			$this->addWhere( 'ipb_deleted = 0 OR ipb_deleted IS NULL' );
		}
	}

	/**
	 * @name Methods required from ApiQueryBase
	 * @{
	 */

	/** @see ApiBase::getDB */
	abstract protected function getDB();

	/** @see ApiBase::getPermissionManager */
	abstract protected function getPermissionManager(): PermissionManager;

	/** @see IContextSource::getUser */
	abstract public function getUser();

	/** @see ApiQueryBase::addTables */
	abstract protected function addTables( $tables, $alias = null );

	/** @see ApiQueryBase::addFields */
	abstract protected function addFields( $fields );

	/** @see ApiQueryBase::addWhere */
	abstract protected function addWhere( $conds );

	/** @see ApiQueryBase::addJoinConds */
	abstract protected function addJoinConds( $conds );

	/**@}*/

}
