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

use Wikimedia\Rdbms\Platform\SQLPlatform;

/**
 * Holds information on Query to be executed
 *
 * @ingroup Database
 * @internal
 * @since 1.41
 */
class Query {
	private $sql;
	private $flags;
	private $queryVerb;
	private $queryTables;
	private string $cleanedSql;

	/**
	 * @param string $sql
	 * @param int $flags
	 * @param string $queryVerb
	 * @param string|string[]|null|false $queryTables
	 * @param string $cleanedSql
	 */
	public function __construct(
		string $sql,
		$flags,
		$queryVerb,
		$queryTables = [],
		$cleanedSql = ''
	) {
		$this->sql = $sql;
		$this->flags = $flags;
		$this->queryVerb = $queryVerb;
		if ( is_array( $queryTables ) ) {
			$this->queryTables = array_values( $queryTables );
		} elseif ( $queryTables === '' || $queryTables === null || $queryTables === false ) {
			$this->queryTables = [];
		} elseif ( is_string( $queryTables ) ) {
			$this->queryTables = [ $queryTables ];
		} else {
			throw new DBLanguageError( __METHOD__ . ' called with incorrect table parameter' );
		}
		$this->cleanedSql = substr( $cleanedSql, 0, 255 );
	}

	public function isWriteQuery() {
		// Check if a SQL wrapper method already flagged the query as a non-write
		if (
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_CHANGE_NONE ) ||
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_CHANGE_TRX ) ||
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_CHANGE_LOCKS )
		) {
			return false;
		}
		// Check if a SQL wrapper method already flagged the query as a write
		if (
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_CHANGE_ROWS ) ||
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_CHANGE_SCHEMA ) ||
			$this->fieldHasBit( $this->flags, SQLPlatform::QUERY_PSEUDO_PERMANENT )
		) {
			return true;
		}
		// Infer it from SQL itself.
		// TODO: Get rid of all other flags
		return QueryBuilderFromRawSql::buildQuery( $this->sql, 0 )->isWriteQuery();
	}

	public function getVerb() {
		return $this->queryVerb;
	}

	private function fieldHasBit( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
	}

	public function getSQL() {
		return $this->sql;
	}

	/** @return int */
	public function getFlags() {
		// The whole concept of flags is terrible. This should be deprecated.
		return $this->flags;
	}

	public function getTables(): array {
		return $this->queryTables;
	}

	public function getCleanedSql(): string {
		return $this->cleanedSql;
	}
}
