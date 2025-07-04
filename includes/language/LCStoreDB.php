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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;

/**
 * LCStore implementation which uses the standard DB functions to store data.
 *
 * @ingroup Language
 */
class LCStoreDB implements LCStore {
	/** @var string|null Language code */
	private $code;
	/** @var array Server configuration map */
	private $server;

	/** @var array Rows buffered for insertion */
	private $batch = [];

	/** @var IDatabase|null */
	private $dbw;
	/** @var bool Whether write batch was recently written */
	private $writesDone = false;
	/** @var bool Whether the DB is read-only or otherwise unavailable for writing */
	private $readOnly = false;

	public function __construct( array $params ) {
		$this->server = $params['server'] ?? [];
	}

	/** @inheritDoc */
	public function get( $code, $key ) {
		if ( $this->server || $this->writesDone ) {
			// If a server configuration map is specified, always used that connection
			// for reads and writes. Otherwise, if writes occurred in finishWrite(), make
			// sure those changes are always visible.
			$db = $this->getWriteConnection();
		} else {
			$db = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		}

		$value = $db->newSelectQueryBuilder()
			->select( 'lc_value' )
			->from( 'l10n_cache' )
			->where( [ 'lc_lang' => $code, 'lc_key' => $key ] )
			->caller( __METHOD__ )->fetchField();

		return ( $value !== false ) ? unserialize( $db->decodeBlob( $value ) ) : null;
	}

	/** @inheritDoc */
	public function startWrite( $code ) {
		if ( $this->readOnly ) {
			return;
		} elseif ( !$code ) {
			throw new InvalidArgumentException( __METHOD__ . ": Invalid language \"$code\"" );
		}

		$dbw = $this->getWriteConnection();
		$this->readOnly = $dbw->isReadOnly();

		$this->code = $code;
		$this->batch = [];
	}

	public function finishWrite() {
		if ( $this->readOnly ) {
			return;
		} elseif ( $this->code === null ) {
			throw new LogicException( __CLASS__ . ': must call startWrite() before finishWrite()' );
		}

		$scope = Profiler::instance()->getTransactionProfiler()->silenceForScope();
		$dbw = $this->getWriteConnection();
		$dbw->startAtomic( __METHOD__ );
		try {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'l10n_cache' )
				->where( [ 'lc_lang' => $this->code ] )
				->caller( __METHOD__ )->execute();
			foreach ( array_chunk( $this->batch, 500 ) as $rows ) {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'l10n_cache' )
					->rows( $rows )
					->caller( __METHOD__ )->execute();
			}
			$this->writesDone = true;
		} catch ( DBQueryError $e ) {
			if ( $dbw->isReadOnly() ) {
				$this->readOnly = true; // just avoid site downtime
			} else {
				throw $e;
			}
		}
		$dbw->endAtomic( __METHOD__ );
		ScopedCallback::consume( $scope );

		$this->code = null;
		$this->batch = [];
	}

	/** @inheritDoc */
	public function set( $key, $value ) {
		if ( $this->readOnly ) {
			return;
		} elseif ( $this->code === null ) {
			throw new LogicException( __CLASS__ . ': must call startWrite() before set()' );
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
				$dbFactory = MediaWikiServices::getInstance()->getDatabaseFactory();
				$this->dbw = $dbFactory->create( $this->server['type'], $this->server );
				if ( !$this->dbw ) {
					throw new RuntimeException( __CLASS__ . ': failed to obtain a DB connection' );
				}
			} else {
				$this->dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
			}
		}

		return $this->dbw;
	}
}
