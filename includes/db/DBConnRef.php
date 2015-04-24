<?php
/**
 * Helper class to handle automatically marking connections as reusable (via RAII pattern)
 * as well handling deferring the actual network connection until the handle is used
 *
 * @ingroup Database
 * @since 1.22
 */
class DBConnRef implements IDatabase {
	/** @var LoadBalancer */
	private $lb;

	/** @var DatabaseBase|null */
	private $conn;

	/** @var array|null */
	private $params;

	/**
	 * @param LoadBalancer $lb
	 * @param DatabaseBase|array $conn Connection or (server index, group, wiki ID) array
	 */
	public function __construct( LoadBalancer $lb, $conn ) {
		$this->lb = $lb;
		if ( $conn instanceof DatabaseBase ) {
			$this->conn = $conn;
		} else {
			$this->params = $conn;
		}
	}

	public function __call( $name, $arguments ) {
		if ( $this->conn === null ) {
			list( $db, $groups, $wiki ) = $this->params;
			$this->conn = $this->lb->getConnection( $db, $groups, $wiki );
		}

		return call_user_func_array( array( $this->conn, $name ), $arguments );
	}

	public function __destruct() {
		if ( $this->conn !== null ) {
			$this->lb->reuseConnection( $this->conn );
		}
	}
}
