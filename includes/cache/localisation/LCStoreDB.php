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

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBQueryError;

/**
 * LCStore implementation which uses the standard DB functions to store data.
 * This will work on any MediaWiki installation.
 */
class LCStoreDB implements LCStore {
	/** @var string Language code */
	private $code;
	/** @var array Server configuration map */
	private $server;

	/** @var array Rows buffered for insertion */
	private $batch = [];

	/** @var IDatabase|null */
	private $dbw;
	/** @var bool Whether a batch of writes were recently written */
	private $writesDone = false;
	/** @var bool Whether the DB is read-only or otherwise unavailable for writes */
	private $readOnly = false;

	public function __construct( $params ) {
		$this->server = $params['server'] ?? [];
	}

	public function get( $code, $key ) {
		if ( $this->server || $this->writesDone ) {
			// If a server configuration map is specified, always used that connection
			// for reads and writes. Otherwise, if writes occurred in finishWrite(), make
			// sure those changes are always visible.
			$db = $this->getWriteConnection();
		} else {
			$db = wfGetDB( DB_REPLICA );
		}

		$value = $db->selectField(
			'l10n_cache',
			'lc_value',
			[ 'lc_lang' => $code, 'lc_key' => $key ],
			__METHOD__
		);

		return ( $value !== false ) ? unserialize( $db->decodeBlob( $value ) ) : null;
	}

	public function startWrite( $code ) {
		if ( $this->readOnly ) {
			return;
		} elseif ( !$code ) {
			throw new MWException( __METHOD__ . ": Invalid language \"$code\"" );
		}

		$dbw = $this->getWriteConnection();
		$this->readOnly = $dbw->isReadOnly();

		$this->code = $code;
		$this->batch = [];
	}

	public function finishWrite() {
		if ( $this->readOnly ) {
			return;
		} elseif ( is_null( $this->code ) ) {
			throw new MWException( __CLASS__ . ': must call startWrite() before finishWrite()' );
		}

		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$oldSilenced = $trxProfiler->setSilenced( true );
		try {
			$dbw = $this->getWriteConnection();
			$dbw->startAtomic( __METHOD__ );
			try {
				$dbw->delete( 'l10n_cache', [ 'lc_lang' => $this->code ], __METHOD__ );
				foreach ( array_chunk( $this->batch, 500 ) as $rows ) {
					$dbw->insert( 'l10n_cache', $rows, __METHOD__ );
				}
				$this->writesDone = true;
			} catch ( DBQueryError $e ) {
				if ( $dbw->wasReadOnlyError() ) {
					$this->readOnly = true; // just avoid site down time
				} else {
					throw $e;
				}
			}
			$dbw->endAtomic( __METHOD__ );
		} finally {
			$trxProfiler->setSilenced( $oldSilenced );
		}

		$this->code = null;
		$this->batch = [];
	}

	public function set( $key, $value ) {
		if ( $this->readOnly ) {
			return;
		} elseif ( is_null( $this->code ) ) {
			throw new MWException( __CLASS__ . ': must call startWrite() before set()' );
		}

		$dbw = $this->getWriteConnection();

		$this->batch[] = [
			'lc_lang' => $this->code,
			'lc_key' => $key,
			'lc_value' => $dbw->encodeBlob( serialize( $value ) )
		];
	}

	/**
	 * @return IDatabase
	 */
	private function getWriteConnection() {
		if ( !$this->dbw ) {
			if ( $this->server ) {
				$this->dbw = Database::factory( $this->server['type'], $this->server );
				if ( !$this->dbw ) {
					throw new MWException( __CLASS__ . ': failed to obtain a DB connection' );
				}
			} else {
				$this->dbw = wfGetDB( DB_MASTER );
			}
		}

		return $this->dbw;
	}
}
