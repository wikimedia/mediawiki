<?php
/**
 * Base code for update jobs that put some secondary data extracted
 * from article content into the database.
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
use \MediaWiki\MediaWikiServices;

/**
 * Abstract base class for update jobs that put some secondary data extracted
 * from article content into the database.
 *
 * @note subclasses should NOT start or commit transactions in their doUpdate() method,
 *       a transaction will automatically be wrapped around the update. Starting another
 *       one would break the outer transaction bracket. If need be, subclasses can override
 *       the beginTransaction() and commitTransaction() methods.
 */
abstract class SqlDataUpdate extends DataUpdate {
	/** @var IDatabase Database connection reference */
	protected $mDb;
	/** @var array SELECT options to be used (array) */
	protected $mOptions = [];
	/** @var bool Whether this update should be wrapped in a transaction */
	protected $mUseTransaction;

	/**
	 * Constructor
	 *
	 * @param bool $withTransaction Whether this update should be wrapped in a
	 *   transaction (default: true). A transaction is only started if no
	 *   transaction is already in progress, see beginTransaction() for details.
	 */
	public function __construct( $withTransaction = true ) {
		parent::__construct();

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$this->mDb = $lb->getLazyConnectionRef( DB_MASTER );
		$this->mUseTransaction = $withTransaction;
	}

	public function useTransaction() {
		return $this->mUseTransaction;
	}
}
