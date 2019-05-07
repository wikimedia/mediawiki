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
 * @ingroup Profiler
 */

namespace Wikimedia\Rdbms;

/**
 * Lazy-loaded wrapper for simplification and scrubbing of SQL queries for profiling
 *
 * @since 1.34
 */
class GeneralizedSql {
	/** @var string */
	private $rawSql;
	/** @var string */
	private $trxId;
	/** @var string */
	private $prefix;

	/** @var string|null */
	private $genericSql;

	/**
	 * @param string $rawSql
	 * @param string $trxId
	 * @param string $prefix
	 */
	public function __construct( $rawSql, $trxId, $prefix ) {
		$this->rawSql = $rawSql;
		$this->trxId = $trxId;
		$this->prefix = $prefix;
	}

	/**
	 * Removes most variables from an SQL query and replaces them with X or N for numbers.
	 * It's only slightly flawed. Don't use for anything important.
	 *
	 * @param string $sql A SQL Query
	 *
	 * @return string
	 */
	private static function generalizeSQL( $sql ) {
		# This does the same as the regexp below would do, but in such a way
		# as to avoid crashing php on some large strings.
		# $sql = preg_replace( "/'([^\\\\']|\\\\.)*'|\"([^\\\\\"]|\\\\.)*\"/", "'X'", $sql );

		$sql = str_replace( "\\\\", '', $sql );
		$sql = str_replace( "\\'", '', $sql );
		$sql = str_replace( "\\\"", '', $sql );
		$sql = preg_replace( "/'.*'/s", "'X'", $sql );
		$sql = preg_replace( '/".*"/s', "'X'", $sql );

		# All newlines, tabs, etc replaced by single space
		$sql = preg_replace( '/\s+/', ' ', $sql );

		# All numbers => N,
		# except the ones surrounded by characters, e.g. l10n
		$sql = preg_replace( '/-?\d+(,-?\d+)+/s', 'N,...,N', $sql );
		$sql = preg_replace( '/(?<![a-zA-Z])-?\d+(?![a-zA-Z])/s', 'N', $sql );

		return $sql;
	}

	/**
	 * @return string
	 */
	public function stringify() {
		if ( $this->genericSql !== null ) {
			return $this->genericSql;
		}

		$this->genericSql = $this->prefix .
			substr( self::generalizeSQL( $this->rawSql ), 0, 255 ) .
			( $this->trxId ? " [TRX#{$this->trxId}]" : "" );

		return $this->genericSql;
	}
}
