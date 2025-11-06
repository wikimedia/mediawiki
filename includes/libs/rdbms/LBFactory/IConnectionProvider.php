<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

/**
 * Provide primary and replica IDatabase connections.
 *
 * This is a narrow interface intended as the main entrypoint to the Rdbms library.
 * No methods should be added unless absolutely needed.
 *
 * The main implementation is \Wikimedia\Rdbms\LBFactory.
 * To obtain an instance, use \MediaWiki\MediaWikiServices::getDBLoadBalancerFactory().
 *
 * @see ILBFactory
 * @since 1.40
 * @ingroup Database
 */
interface IConnectionProvider {
	/**
	 * Get connection to the primary database.
	 *
	 * This should be used when there the code needs to write to the database.
	 *
	 * This method accepts virtual domains
	 * ({@see \MediaWiki\MainConfigSchema::VirtualDomainsMapping}).
	 *
	 * @since 1.40
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @return IDatabase
	 */
	public function getPrimaryDatabase( $domain = false ): IDatabase;

	/**
	 * Get connection to a replica database.
	 *
	 * Note that a read can have replication lag.
	 *
	 * This method accepts virtual domains
	 * ({@see \MediaWiki\MainConfigSchema::VirtualDomainsMapping}).
	 *
	 * @since 1.40
	 * @param string|false $domain Domain ID, or false for the current domain
	 * @param string|null $group Query group; null for the default group
	 * @return IReadableDatabase
	 */
	public function getReplicaDatabase( string|false $domain = false, $group = null ): IReadableDatabase;

	/**
	 * Commit primary DB transactions and wait for replication (if $ticket indicates it is safe).
	 *
	 * This is mostly used in jobs or deferred updates dealing with batching.
	 *
	 * The ticket is used to check that the caller owns the transaction round or can act on
	 * behalf of the caller that owns the transaction round.
	 *
	 * @see ILBFactory::commitPrimaryChanges()
	 * @see ILBFactory::waitForReplication()
	 * @since 1.28
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @param array $opts Options to waitForReplication()
	 * @return bool True if the wait was successful, false on timeout
	 */
	public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] );

	/**
	 * Get a token asserting that no write transactions are active on tracked connections.
	 *
	 * This is mostly used in jobs or deferred updates dealing with batching.
	 *
	 * @since 1.28
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @return mixed A value to pass to commitAndWaitForReplication()
	 */
	public function getEmptyTransactionTicket( $fname );
}
