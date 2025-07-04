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

use Wikimedia\Rdbms\Query;

/**
 * @since 1.38
 * @see ISQLPlatform
 */
class SqlitePlatform extends SQLPlatform {
	/** @inheritDoc */
	public function buildGreatest( $fields, $values ) {
		return $this->buildSuperlative( 'MAX', $fields, $values );
	}

	/** @inheritDoc */
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
	 * @param array $options Query options, will be ignored in Sqlite
	 * @return string
	 */
	public function unionQueries( $sqls, $all, $options = [] ) {
		$glue = $all ? ' UNION ALL ' : ' UNION ';

		return implode( $glue, $sqls );
	}

	/**
	 * @return bool
	 */
	public function unionSupportsOrderAndLimit() {
		return false;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function tableName( string $name, $format = 'quoted' ) {
		if ( preg_match( '/^sqlite_[a-z_]+$/', $name ) ) {
			// Such names are reserved for internal SQLite tables
			return $name;
		}

		return parent::tableName( $name, $format );
	}

	/** @inheritDoc */
	protected function makeSelectOptions( array $options ) {
		// Remove problematic options that the base implementation converts to SQL
		foreach ( $options as $k => $v ) {
			if ( is_numeric( $k ) && ( $v === 'FOR UPDATE' || $v === 'LOCK IN SHARE MODE' ) ) {
				$options[$k] = '';
			}
		}

		return parent::makeSelectOptions( $options );
	}

	/** @inheritDoc */
	public function buildGroupConcatField(
		$delim, $tables, $field, $conds = '', $join_conds = []
	) {
		$fld = "group_concat($field," . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $tables, $fld, $conds, static::CALLER_SUBQUERY, [], $join_conds ) . ')';
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function dropTableSqlText( $table ) {
		// No CASCADE support; https://www.sqlite.org/lang_droptable.html
		return "DROP TABLE " . $this->tableName( $table );
	}

	/** @inheritDoc */
	public function isTransactableQuery( Query $sql ) {
		return parent::isTransactableQuery( $sql ) && !in_array(
				$sql->getVerb(),
				[ 'ATTACH', 'PRAGMA' ],
				true
			);
	}
}
