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
namespace Wikimedia\Rdbms\Platform;

/**
 * @since 1.38
 * @see ISQLPlatform
 */
class SqlitePlatform extends SQLPlatform {
	public function buildGreatest( $fields, $values ) {
		return $this->buildSuperlative( 'MAX', $fields, $values );
	}

	public function buildLeast( $fields, $values ) {
		return $this->buildSuperlative( 'MIN', $fields, $values );
	}

	/**
	 * Build a concatenation list to feed into a SQL query
	 *
	 * @param string[] $stringList
	 * @return string
	 */
	public function buildConcat( $stringList ) {
		return '(' . implode( ') || (', $stringList ) . ')';
	}

	/**
	 * @param string[] $sqls
	 * @param bool $all Whether to "UNION ALL" or not
	 * @return string
	 */
	public function unionQueries( $sqls, $all ) {
		$glue = $all ? ' UNION ALL ' : ' UNION ';

		return implode( $glue, $sqls );
	}

	/**
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit() {
		return false;
	}

	public function buildSubstring( $input, $startPosition, $length = null ) {
		$this->assertBuildSubstringParams( $startPosition, $length );
		$params = [ $input, $startPosition ];
		if ( $length !== null ) {
			$params[] = $length;
		}
		return 'SUBSTR(' . implode( ',', $params ) . ')';
	}

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28
	 */
	public function buildStringCast( $field ) {
		return 'CAST ( ' . $field . ' AS TEXT )';
	}

	/**
	 * Use MySQL's naming (accounts for prefix etc) but remove surrounding backticks
	 *
	 * @param string $name
	 * @param string $format
	 * @return string
	 */
	public function tableName( $name, $format = 'quoted' ) {
		// table names starting with sqlite_ are reserved
		if ( strpos( $name, 'sqlite_' ) === 0 ) {
			return $name;
		}

		return str_replace( '"', '', parent::tableName( $name, $format ) );
	}

	protected function makeSelectOptions( array $options ) {
		// Remove problematic options that the base implementation converts to SQL
		foreach ( $options as $k => $v ) {
			if ( is_numeric( $k ) && ( $v === 'FOR UPDATE' || $v === 'LOCK IN SHARE MODE' ) ) {
				$options[$k] = '';
			}
		}

		return parent::makeSelectOptions( $options );
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "group_concat($field," . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT OR IGNORE INTO', '' ];
	}

	/**
	 * @param array $options
	 * @return array
	 */
	protected function makeUpdateOptionsArray( $options ) {
		$options = parent::makeUpdateOptionsArray( $options );
		$options = $this->rewriteIgnoreKeyword( $options );

		return $options;
	}

	/**
	 * @param array $options
	 * @return array
	 */
	private function rewriteIgnoreKeyword( $options ) {
		# SQLite uses OR IGNORE not just IGNORE
		foreach ( $options as $k => $v ) {
			if ( $v == 'IGNORE' ) {
				$options[$k] = 'OR IGNORE';
			}
		}

		return $options;
	}

	public function dropTableSqlText( $table ) {
		// No CASCADE support; https://www.sqlite.org/lang_droptable.html
		return "DROP TABLE " . $this->tableName( $table );
	}

	public function isTransactableQuery( $sql ) {
		return parent::isTransactableQuery( $sql ) && !in_array(
				$this->getQueryVerb( $sql ),
				[ 'ATTACH', 'PRAGMA' ],
				true
			);
	}

	public function isWriteQuery( $sql, $flags ) {
		return parent::isWriteQuery( $sql, $flags ) && !preg_match( '/^(ATTACH|PRAGMA)\b/i', $sql );
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function replaceVars( $s ) {
		$s = parent::replaceVars( $s );
		if ( preg_match( '/^\s*(CREATE|ALTER) TABLE/i', $s ) ) {
			// CREATE TABLE hacks to allow schema file sharing with MySQL

			// binary/varbinary column type -> blob
			$s = preg_replace( '/\b(var)?binary(\(\d+\))/i', 'BLOB', $s );
			// no such thing as unsigned
			$s = preg_replace( '/\b(un)?signed\b/i', '', $s );
			// INT -> INTEGER
			$s = preg_replace( '/\b(tiny|small|medium|big|)int(\s*\(\s*\d+\s*\)|\b)/i', 'INTEGER', $s );
			// floating point types -> REAL
			$s = preg_replace(
				'/\b(float|double(\s+precision)?)(\s*\(\s*\d+\s*(,\s*\d+\s*)?\)|\b)/i',
				'REAL',
				$s
			);
			// varchar -> TEXT
			$s = preg_replace( '/\b(var)?char\s*\(.*?\)/i', 'TEXT', $s );
			// TEXT normalization
			$s = preg_replace( '/\b(tiny|medium|long)text\b/i', 'TEXT', $s );
			// BLOB normalization
			$s = preg_replace( '/\b(tiny|small|medium|long|)blob\b/i', 'BLOB', $s );
			// BOOL -> INTEGER
			$s = preg_replace( '/\bbool(ean)?\b/i', 'INTEGER', $s );
			// DATETIME -> TEXT
			$s = preg_replace( '/\b(datetime|timestamp)\b/i', 'TEXT', $s );
			// No ENUM type
			$s = preg_replace( '/\benum\s*\([^)]*\)/i', 'TEXT', $s );
			// binary collation type -> nothing
			$s = preg_replace( '/\bbinary\b/i', '', $s );
			// auto_increment -> autoincrement
			$s = preg_replace( '/\bauto_increment\b/i', 'AUTOINCREMENT', $s );
			// No explicit options
			$s = preg_replace( '/\)[^);]*(;?)\s*$/', ')\1', $s );
			// AUTOINCREMENT should immediately follow PRIMARY KEY
			$s = preg_replace( '/primary key (.*?) autoincrement/i', 'PRIMARY KEY AUTOINCREMENT $1', $s );
		} elseif ( preg_match( '/^\s*CREATE (\s*(?:UNIQUE|FULLTEXT)\s+)?INDEX/i', $s ) ) {
			// No truncated indexes
			$s = preg_replace( '/\(\d+\)/', '', $s );
			// No FULLTEXT
			$s = preg_replace( '/\bfulltext\b/i', '', $s );
		} elseif ( preg_match( '/^\s*DROP INDEX/i', $s ) ) {
			// DROP INDEX is database-wide, not table-specific, so no ON <table> clause.
			$s = preg_replace( '/\sON\s+[^\s]*/i', '', $s );
		} elseif ( preg_match( '/^\s*INSERT IGNORE\b/i', $s ) ) {
			// INSERT IGNORE --> INSERT OR IGNORE
			$s = preg_replace( '/^\s*INSERT IGNORE\b/i', 'INSERT OR IGNORE', $s );
		}

		return $s;
	}
}
