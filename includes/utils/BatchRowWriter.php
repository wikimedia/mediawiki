<?php
/**
 * Updates database rows by primary key in batches.
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
 * @ingroup Maintenance
 */
use Wikimedia\Rdbms\IDatabase;
use \MediaWiki\MediaWikiServices;

class BatchRowWriter {
	/**
	 * @var IDatabase $db The database to write to
	 */
	protected $db;

	/**
	 * @var string $table The name of the table to update
	 */
	protected $table;

	/**
	 * @var string $clusterName A cluster name valid for use with LBFactory
	 */
	protected $clusterName;

	/**
	 * @param IDatabase $db The database to write to
	 * @param string       $table       The name of the table to update
	 * @param string|bool  $clusterName A cluster name valid for use with LBFactory
	 */
	public function __construct( IDatabase $db, $table, $clusterName = false ) {
		$this->db = $db;
		$this->table = $table;
		$this->clusterName = $clusterName;
	}

	/**
	 * @param array $updates Array of arrays each containing two keys, 'primaryKey'
	 *  and 'changes'. primaryKey must contain a map of column names to values
	 *  sufficient to uniquely identify the row changes must contain a map of column
	 *  names to update values to apply to the row.
	 */
	public function write( array $updates ) {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );

		foreach ( $updates as $update ) {
			$this->db->update(
				$this->table,
				$update['changes'],
				$update['primaryKey'],
				__METHOD__
			);
		}

		$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
	}
}
