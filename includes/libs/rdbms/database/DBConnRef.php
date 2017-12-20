<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Helper class to handle automatically marking connections as reusable (via RAII pattern)
 * as well handling deferring the actual network connection until the handle is used
 *
 * @note: proxy methods are defined explicitly to avoid interface errors
 * @ingroup Database
 * @since 1.22
 */
class DBConnRef extends DBConnectionProxy {
	/** @var ILoadBalancer */
	private $lb;

	/** @var array|null N-tuple of (server index, group, DatabaseDomain|string) */
	private $params = null;

	const FLD_INDEX = 0;
	const FLD_GROUP = 1;
	const FLD_DOMAIN = 2;
	const FLD_FLAGS = 3;

	/**
	 * @param ILoadBalancer $lb Connection manager for $conn
	 * @param IDatabase|array $conn Database handle or (server index, query groups, domain, flags)
	 */
	public function __construct( ILoadBalancer $lb, $conn ) {
		$this->lb = $lb;

		if ( $conn instanceof IDatabase ) {
			parent::__construct( $conn );
		} elseif ( count( $conn ) >= 4 && $conn[self::FLD_DOMAIN] !== false ) {
			$this->params = $conn;

			$instantiator = function () {
				list( $db, $groups, $wiki, $flags ) = $this->params;
				return $this->lb->getConnection( $db, $groups, $wiki, $flags );
			};

			parent::__construct( $instantiator );
		} else {
			throw new InvalidArgumentException( "Missing lazy connection arguments." );
		}
	}

	public function getDomainID() {
		// Avoid triggering a database connection
		if ( $this->params ) {
			$domain = $this->params[self::FLD_DOMAIN];
			return $domain instanceof DatabaseDomain ? $domain->getId() : $domain;
		}

		return parent::getDomainId();
	}

	public function getWikiID() {
		return $this->getDomainID();
	}

	/**
	 * Clean up the connection when out of scope
	 */
	function __destruct() {
		if ( $this->connection ) {
			$this->lb->reuseConnection( $this->connection );
		}
	}

	public function __toString() {
		if ( $this->connection ) {
			return get_class( $this ) . '(' . $this->connection . ')';
		} else {
			return get_class( $this ) . '(' . $this->getDomainID() . ')';
		}
	}

}

class_alias( DBConnRef::class, 'DBConnRef' );
