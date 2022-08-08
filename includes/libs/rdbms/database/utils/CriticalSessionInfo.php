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
namespace Wikimedia\Rdbms;

/**
 * @ingroup Database
 * @internal This class should not be used outside of Database
 */
class CriticalSessionInfo {
	/** @var TransactionIdentifier|null */
	public $trxId;
	/** @var bool */
	public $trxExplicit;
	/** @var string[] */
	public $trxWriteCallers;
	/** @var string[] */
	public $trxPreCommitCbCallers;
	/** @var array<string,array> */
	public $namedLocks;
	/** @var array<string,array> */
	public $tempTables;

	/**
	 * @param TransactionIdentifier|null $trxId
	 * @param bool $trxExplicit
	 * @param array $trxWriteCallers
	 * @param array $trxPreCommitCbCallers
	 * @param array $namedLocks
	 * @param array $tempTables
	 */
	public function __construct(
		?TransactionIdentifier $trxId,
		bool $trxExplicit,
		array $trxWriteCallers,
		array $trxPreCommitCbCallers,
		array $namedLocks,
		array $tempTables
	) {
		$this->trxId = $trxId;
		$this->trxExplicit = $trxExplicit;
		$this->trxWriteCallers = $trxWriteCallers;
		$this->trxPreCommitCbCallers = $trxPreCommitCbCallers;
		$this->namedLocks = $namedLocks;
		$this->tempTables = $tempTables;
	}
}
