<?php
/**
 * @license GPL-2.0-or-later
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
