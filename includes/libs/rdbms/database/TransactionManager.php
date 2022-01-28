<?php
/**
 * This file deals with database interface functions
 * and query specifics/optimisations.
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
namespace Wikimedia\Rdbms;

/**
 * @ingroup Database
 * @internal
 */
class TransactionManager {
	/** @var string Application-side ID of the active transaction or an empty string otherwise */
	private $trxId = '';
	/** @var float|null UNIX timestamp at the time of BEGIN for the last transaction */
	private $trxTimestamp = null;

	public function trxLevel() {
		return ( $this->trxId != '' ) ? 1 : 0;
	}

	/**
	 * TODO: This should be removed once all usages have been migrated here
	 * @return string
	 */
	public function getTrxId(): string {
		return $this->trxId;
	}

	/**
	 * TODO: This should be removed once all usages have been migrated here
	 */
	public function newTrxId() {
		static $nextTrxId;
		$nextTrxId = ( $nextTrxId !== null ? $nextTrxId++ : mt_rand() ) % 0xffff;
		$this->trxId = sprintf( '%06x', mt_rand( 0, 0xffffff ) ) . sprintf( '%04x', $nextTrxId );
	}

	/**
	 * Reset the application-side transaction ID and return the old one
	 * This will become private soon.
	 * @return string The old transaction ID or an empty string if there wasn't one
	 */
	public function consumeTrxId() {
		$old = $this->trxId;
		$this->trxId = '';

		return $old;
	}

	public function trxTimestamp(): ?float {
		return $this->trxLevel() ? $this->trxTimestamp : null;
	}

	/**
	 * @param float|null $trxTimestamp
	 * @unstable This will be removed once usages are migrated here
	 */
	public function setTrxTimestamp( ?float $trxTimestamp ) {
		$this->trxTimestamp = $trxTimestamp;
	}

}
