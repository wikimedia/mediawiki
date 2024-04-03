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

	private string $sql;
	private int $flags;
	private string $queryVerb;
	private ?string $writeTable;
	private string $cleanedSql;

	/**
	 * @param string $sql SQL statement text
	 * @param int $flags Bit field of ISQLPlatform::QUERY_CHANGE_* constants
	 * @param string $queryVerb The first words of the SQL statement that convey what kind of
	 *  database/table/column/index command was specified. Except for the following cases, this
	 *  will be the first word of the SQL statement:
	 *   - "RELEASE SAVEPOINT"
	 *   - "ROLLBACK TO SAVEPOINT"
	 *   - "CREATE TEMPORARY"
	 *   - "CREATE INDEX"
	 *   - "DROP INDEX"
	 *   - "CREATE DATABASE"
	 *   - "ALTER DATABASE"
	 *   - "DROP DATABASE"
	 * @param string|null $writeTable The table targeted for writes, if any. Can be omitted if
	 *   it would be hard to identify the table (e.g. when parsing an arbitrary SQL string).
	 * @param string $cleanedSql Sanitized/simplified SQL statement text for logging.
	 *   Typically, this means replacing variables / parameter values with placeholders.
	 *   Can be omitted, in which case the code using the Query is responsible for sanitizing.
	 */
	public function __construct(
		string $sql,
		$flags,
		$queryVerb,
		string $writeTable = null,
		$cleanedSql = ''
	) {
		$this->sql = $sql;
		$this->flags = $flags;
		$this->queryVerb = $queryVerb;
		$this->writeTable = $writeTable;
		$this->cleanedSql = substr( $cleanedSql, 0, 255 );
	}

	public function isWriteQuery(): bool {
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

		throw new DBLanguageError( __METHOD__ . ' called with incorrect flags parameter' );
	}

	public function getVerb(): string {
		return $this->queryVerb;
	}

	private function fieldHasBit( int $flags, int $bit ): bool {
		return ( ( $flags & $bit ) === $bit );
	}

	public function getSQL(): string {
		return $this->sql;
	}

	public function getFlags(): int {
		// The whole concept of flags is terrible. This should be deprecated.
		return $this->flags;
	}

	/**
	 * Get the table which is being written to, or null for a read query or if
	 * the destination is unknown.
	 */
	public function getWriteTable(): ?string {
		return $this->writeTable;
	}

	/**
	 * Get the cleaned/sanitized SQL statement text for logging.
	 * Might return an empty string, which means sanitization is the caller's responsibility.
	 */
	public function getCleanedSql(): string {
		return $this->cleanedSql;
	}
}
