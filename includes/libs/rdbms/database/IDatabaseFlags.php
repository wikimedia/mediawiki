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
namespace Wikimedia\Rdbms\Database;

/**
 * @ingroup Database
 */
interface IDatabaseFlags {

	/** @var string Do not remember the prior flags */
	public const REMEMBER_NOTHING = '';
	/** @var string Remember the prior flags */
	public const REMEMBER_PRIOR = 'remember';
	/** @var string Restore to the prior flag state */
	public const RESTORE_PRIOR = 'prior';
	/** @var string Restore to the initial flag state */
	public const RESTORE_INITIAL = 'initial';

	/** @var int Enable debug logging of all SQL queries */
	public const DBO_DEBUG = 1;
	/** @var int Unused since 1.34 */
	public const DBO_NOBUFFER = 2;
	/** @var int Unused since 1.31 */
	public const DBO_IGNORE = 4;
	/** @var int Automatically start a transaction before running a query if none is active */
	public const DBO_TRX = 8;
	/** @var int Join load balancer transaction rounds (which control DBO_TRX) in non-CLI mode */
	public const DBO_DEFAULT = 16;
	/** @var int Use DB persistent connections if possible */
	public const DBO_PERSISTENT = 32;
	/** @var int DBA session mode; was used by Oracle */
	public const DBO_SYSDBA = 64;
	/** @var int Schema file mode; was used by Oracle */
	public const DBO_DDLMODE = 128;

	/**
	 * Set a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Clear a flag for this connection
	 *
	 * @param int $flag One of (IDatabase::DBO_DEBUG, IDatabase::DBO_TRX)
	 * @param string $remember IDatabase::REMEMBER_* constant [default: REMEMBER_NOTHING]
	 */
	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING );

	/**
	 * Restore the flags to their prior state before the last setFlag/clearFlag call
	 *
	 * @param string $state IDatabase::RESTORE_* constant. [default: RESTORE_PRIOR]
	 * @since 1.28
	 */
	public function restoreFlags( $state = self::RESTORE_PRIOR );

	/**
	 * Returns a boolean whether the flag $flag is set for this connection
	 *
	 * @param int $flag One of the class IDatabase::DBO_* constants
	 * @return bool
	 */
	public function getFlag( $flag );
}
