<?php

namespace MediaWiki\User\TempUser;

use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A serial provider which allocates IDs from the local database, or from a
 * shared database if $wgSharedDB is used. It is "local" in the sense that it
 * uses the same DB connection as the local wiki.
 *
 * @since 1.39
 */
class LocalSerialProvider extends DBSerialProvider {
	private ILoadBalancer $lb;

	/**
	 * @param array $config
	 *   - numShards (int, default 1): A small integer. This can be set to a
	 *     value greater than 1 to avoid acquiring a global lock when
	 *     allocating IDs, at the expense of making the IDs be non-monotonic.
	 * @param ILoadBalancer $lb
	 */
	public function __construct( $config, ILoadBalancer $lb ) {
		parent::__construct( $config );
		$this->lb = $lb;
	}

	protected function getDB() {
		return $this->lb->getConnection(
			DB_PRIMARY,
			[],
			false,
			// So that startAtomic() will start a commit, reducing lock time.
			// Without this flag, the transaction will be open until the start
			// of request shutdown. This could be omitted to reduce the
			// connection overhead, with numShards tuned upwards to compensate.
			ILoadBalancer::CONN_TRX_AUTOCOMMIT
		);
	}

	protected function getTableName() {
		return 'user_autocreate_serial';
	}
}
