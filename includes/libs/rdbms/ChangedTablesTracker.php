<?php

namespace Wikimedia\Rdbms;

use RuntimeException;

/**
 * Utility class that keeps a list of DB tables that were (presumably) changed by write queries. This should only be
 * used in PHPUnit tests.
 * This class should remain a static util class, because it must never be replaced by a mock in tests.
 *
 * @ingroup Database
 * @internal
 */
class ChangedTablesTracker {
	private const TRACKED_VERBS = [ 'INSERT', 'UPDATE', 'REPLACE' ];
	private static bool $trackingEnabled = false;
	/** @var array<string,array<string,true>> Map of [ domain => [ table name => true ] ] */
	private static array $tableMap = [];

	/**
	 * Enables query tracking and resets the list of changed tables.
	 */
	public static function startTracking(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new RuntimeException( "This class is internal and should only be used in tests." );
		}
		if ( self::$trackingEnabled ) {
			throw new RuntimeException( "Tracking is already enabled" );
		}
		self::$trackingEnabled = true;
		self::$tableMap = [];
	}

	/**
	 * Get the tables for a given domain ID
	 *
	 * @param string $domainId
	 * @return string[]
	 */
	public static function getTables( string $domainId ): array {
		$tableMap = self::$tableMap[$domainId] ?? [];
		return array_keys( $tableMap );
	}

	/**
	 * Reset the internal list and disable tracking.
	 */
	public static function stopTracking(): void {
		if ( !self::$trackingEnabled ) {
			throw new RuntimeException( "Tracking is not enabled" );
		}
		self::$tableMap = [];
		self::$trackingEnabled = false;
	}

	/**
	 * When tracking is enabled and a query alters tables, record the list of tables that are altered.
	 * Any table that gets dropped gets removed from the list of altered tables.
	 */
	public static function recordQuery( DatabaseDomain $domain, Query $query ): void {
		if ( !self::$trackingEnabled ) {
			return;
		}
		if ( !$query->isWriteQuery() ) {
			return;
		}
		$domainId = $domain->getId();
		$queryVerb = $query->getVerb();
		$tableName = $query->getWriteTable();
		if ( $tableName === null ) {
			return;
		}
		if ( $queryVerb === 'DROP' ) {
			// Special case: if a table is being dropped, forget about it.
			unset( self::$tableMap[$domainId][$tableName] );
			return;
		}
		if ( !in_array( $queryVerb, self::TRACKED_VERBS, true ) ) {
			return;
		}

		self::$tableMap[$domainId][$tableName] = true;
	}
}
