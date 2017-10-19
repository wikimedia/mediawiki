<?php
/**
 * Base code for update jobs that do something with some secondary
 * data extracted from article.
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

/**
 * Abstract base class for update jobs that do something with some secondary
 * data extracted from article.
 */
abstract class DataUpdate implements DeferrableUpdate {
	/** @var mixed Result from LBFactory::getEmptyTransactionTicket() */
	protected $ticket;
	/** @var string Short update cause action description */
	protected $causeAction = 'unknown';
	/** @var string Short update cause user description */
	protected $causeAgent = 'unknown';

	public function __construct() {
		// noop
	}

	/**
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @since 1.28
	 */
	public function setTransactionTicket( $ticket ) {
		$this->ticket = $ticket;
	}

	/**
	 * @param string $action Action type
	 * @param string $user User name
	 */
	public function setCause( $action, $user ) {
		$this->causeAction = $action;
		$this->causeAgent = $user;
	}

	/**
	 * @return string
	 */
	public function getCauseAction() {
		return $this->causeAction;
	}

	/**
	 * @return string
	 */
	public function getCauseAgent() {
		return $this->causeAgent;
	}

	/**
	 * Convenience method, calls doUpdate() on every DataUpdate in the array.
	 *
	 * @param DataUpdate[] $updates A list of DataUpdate instances
	 * @throws Exception
	 * @deprecated Since 1.28 Use DeferredUpdates::execute()
	 */
	public static function runUpdates( array $updates ) {
		foreach ( $updates as $update ) {
			$update->doUpdate();
		}
	}
}
