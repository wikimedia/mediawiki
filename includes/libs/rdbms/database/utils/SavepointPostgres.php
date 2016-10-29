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
 * @ingroup Database
 */
use Psr\Log\LoggerInterface;

/**
 * Manage savepoints within a transaction
 * @ingroup Database
 * @since 1.19
 */
class SavepointPostgres {
	/** @var DatabasePostgres Establish a savepoint within a transaction */
	protected $dbw;
	/** @var LoggerInterface */
	protected $logger;
	/** @var int */
	protected $id;
	/** @var bool */
	protected $didbegin;

	/**
	 * @param DatabasePostgres $dbw
	 * @param int $id
	 * @param LoggerInterface $logger
	 */
	public function __construct( DatabasePostgres $dbw, $id, LoggerInterface $logger ) {
		$this->dbw = $dbw;
		$this->logger = $logger;
		$this->id = $id;
		$this->didbegin = false;
		/* If we are not in a transaction, we need to be for savepoint trickery */
		if ( !$dbw->trxLevel() ) {
			$dbw->begin( __CLASS__, DatabasePostgres::TRANSACTION_INTERNAL );
			$this->didbegin = true;
		}
	}

	public function __destruct() {
		if ( $this->didbegin ) {
			$this->dbw->rollback();
			$this->didbegin = false;
		}
	}

	public function commit() {
		if ( $this->didbegin ) {
			$this->dbw->commit( __CLASS__, DatabasePostgres::FLUSHING_INTERNAL );
			$this->didbegin = false;
		}
	}

	protected function query( $keyword, $msg_ok, $msg_failed ) {
		if ( $this->dbw->doQuery( $keyword . " " . $this->id ) !== false ) {
			$this->logger->debug( sprintf( $msg_ok, $this->id ) );
		} else {
			$this->logger->debug( sprintf( $msg_failed, $this->id ) );
		}
	}

	public function savepoint() {
		$this->query( "SAVEPOINT",
			"Transaction state: savepoint \"%s\" established.\n",
			"Transaction state: establishment of savepoint \"%s\" FAILED.\n"
		);
	}

	public function release() {
		$this->query( "RELEASE",
			"Transaction state: savepoint \"%s\" released.\n",
			"Transaction state: release of savepoint \"%s\" FAILED.\n"
		);
	}

	public function rollback() {
		$this->query( "ROLLBACK TO",
			"Transaction state: savepoint \"%s\" rolled back.\n",
			"Transaction state: rollback of savepoint \"%s\" FAILED.\n"
		);
	}

	public function __toString() {
		return (string)$this->id;
	}
}
