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

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @since 1.39
 * @see ISQLPlatform
 */
class PostgresPlatform extends SQLPlatform {
	/** @var string */
	private $coreSchema;

	public function limitResult( $sql, $limit, $offset = false ) {
		return "$sql LIMIT $limit " . ( is_numeric( $offset ) ? " OFFSET {$offset} " : '' );
	}

	public function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function timestamp( $ts = 0 ) {
		$ct = new ConvertibleTimestamp( $ts );

		return $ct->getTimestamp( TS_POSTGRES );
	}

	public function buildStringCast( $field ) {
		return $field . '::text';
	}

	public function implicitOrderby() {
		return false;
	}

	public function getCoreSchema(): string {
		return $this->coreSchema;
	}

	public function setCoreSchema( string $coreSchema ): void {
		$this->coreSchema = $coreSchema;
	}

	protected function makeSelectOptions( array $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = $useIndex = $ignoreIndex = '';

		$noKeyOptions = [];
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		if ( isset( $options['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE OF ' .
				implode( ', ', array_map( [ $this, 'tableName' ], $options['FOR UPDATE'] ) );
		} elseif ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
	}

	protected function relationSchemaQualifier() {
		if ( $this->coreSchema === $this->schema ) {
			// The schema to be used is now in the search path; no need for explicit qualification
			return '';
		}

		return parent::relationSchemaQualifier();
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "array_to_string(array_agg($field)," . $this->quoter->addQuotes( $delim ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT INTO', 'ON CONFLICT DO NOTHING' ];
	}
}
