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

/**
 * Lazy-loaded wrapper for simplification and scrubbing of SQL queries for profiling
 *
 * @since 1.34
 * @ingroup Profiler
 * @ingroup Database
 */
class GeneralizedSql {
	/** @var string */
	private $rawSql;
	/** @var string */
	private $prefix;

	/** @var string|null */
	private $genericSql;

	/**
	 * @param string $rawSql
	 * @param string $prefix
	 */
	public function __construct( $rawSql, $prefix ) {
		$this->rawSql = $rawSql;
		$this->prefix = $prefix;
	}

	/**
	 * @return string
	 */
	public function stringify() {
		if ( $this->genericSql !== null ) {
			return $this->genericSql;
		}

		$this->genericSql = $this->prefix .
			substr( QueryBuilderFromRawSql::generalizeSQL( $this->rawSql ), 0, 255 );

		return $this->genericSql;
	}

	/**
	 * @return string
	 */
	public function getRawSql() {
		return $this->rawSql;
	}

	/**
	 * @param Query $query
	 * @param string $prefix
	 * @return self
	 */
	public static function newFromQuery( Query $query, $prefix ) {
		$generalizedSql = new self( $query->getSQL(), $prefix );

		$cleanedSql = $query->getCleanedSql();
		if ( $cleanedSql != '' ) {
			// Generalized SQL already provided; no need to use regexes
			$generalizedSql->genericSql = $prefix . $cleanedSql;
		}

		return $generalizedSql;
	}
}
