<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use Stringable;

/**
 * Helper class used for automatically re-using IDatabase connections and lazily
 * establishing the actual network connection to a database host.
 *
 * It does this by deferring to ILoadBalancer::getConnectionInternal, which in
 * turn ensures we share and re-use a single connection for a given database
 * wherever possible.
 *
 * This class previously used an RAII-style pattern where connections would be
 * claimed from a pool, and then added back to the pool for re-use only after
 * the calling code's variable for this object went out of scope (a __destruct
 * got called when the calling function returns or throws). This is no longer
 * needed today as LoadBalancer now permits re-use internally even for
 * overlapping callers, where two pieces of code may both obtain their own
 * DBConnRef object and where both are used alternatingly, and yet still share
 * the same connection.
 *
 * @par Example:
 * @code
 *     function getRowData() {
 *         $conn = $this->lb->getConnection( DB_REPLICA );
 *         $row = $conn->select( ... );
 *         return $row ? (array)$row : false;
 *     }
 * @endcode
 *
 * @ingroup Database
 * @since 1.22
 */
class DBConnRef implements Stringable, IMaintainableDatabase, IDatabaseForOwner {
	/** @var ILoadBalancer */
	private $lb;
	/** @var Database|null Live connection handle */
	private $conn;
	/**
	 * @var array Map of (DBConnRef::FLD_* constant => connection parameter)
	 * @phan-var array{0:int,1:array|string|false,2:DatabaseDomain,3:int}
	 */
	private $params;
	/** @var int One of DB_PRIMARY/DB_REPLICA */
	private $role;

	/**
	 * @var int Reference to the $modcount passed to the constructor.
	 *      $conn is valid if $modCountRef and $modCountFix are the same.
	 */
	private $modCountRef;

	/**
	 * @var int Last known good value of $modCountRef
	 *      $conn is valid if $modCountRef and $modCountFix are the same.
	 */
	private $modCountFix;

	private const FLD_INDEX = 0;
	private const FLD_GROUPS = 1;
	private const FLD_DOMAIN = 2;
	private const FLD_FLAGS = 3;

	/**
	 * @internal May not be used outside Rdbms LoadBalancer
	 * @param ILoadBalancer $lb Connection manager for $conn
	 * @param array $params [server index, query groups, domain, flags]
	 * @param int $role The type of connection asked for; one of DB_PRIMARY/DB_REPLICA
	 * @param null|int &$modcount Reference to a modification counter. This is for
	 *  LoadBalancer::reconfigure to indicate that a new connection should be acquired.
	 */
	public function __construct( ILoadBalancer $lb, $params, $role, &$modcount = 0 ) {
		if ( !is_array( $params ) || count( $params ) < 4 || $params[self::FLD_DOMAIN] === false ) {
			throw new InvalidArgumentException( "Missing lazy connection arguments." );
		}

		$params[self::FLD_DOMAIN] = DatabaseDomain::newFromId( $params[self::FLD_DOMAIN] );

		$this->lb = $lb;
		$this->params = $params;
		$this->role = $role;

		// $this->conn is valid as long as $this->modCountRef and $this->modCountFix are the same.
		$this->modCountRef = &$modcount; // remember reference
		$this->modCountFix = $modcount;  // remember current value
	}

	/**
	 * Connect to the database if we are not already connected.
	 */
	public function ensureConnection() {
		if ( $this->modCountFix !== $this->modCountRef ) {
			// Discard existing connection, unless we are in an ongoing transaction.
			// This is triggered by LoadBalancer::reconfigure(), to allow changed settings
			// to take effect. The primary use case are replica servers being taken out of
			// rotation, or the primary database changing.
			if ( $this->conn && !$this->conn->trxLevel() ) {
				$this->conn->close();
				$this->conn = null;
			}
		}

		if ( $this->conn === null ) {
			$this->conn = $this->lb->getConnectionInternal(
				$this->params[self::FLD_INDEX],
				$this->params[self::FLD_GROUPS],
				$this->params[self::FLD_DOMAIN]->getId(),
				$this->params[self::FLD_FLAGS]
			);
			$this->modCountFix = $this->modCountRef;
		}

		if ( !$this->params[self::FLD_DOMAIN]->equals( $this->conn->getDomainID() ) ) {
			// The underlying connection handle is likely being shared by other DBConnRef
			// instances in a load balancer. Make sure that each one routes queries by their
			// owner function to the domain that the owner expects.
			$this->conn->selectDomain( $this->params[self::FLD_DOMAIN] );
		}
	}

	public function __call( $name, array $arguments ) {
		$this->ensureConnection();

		return $this->conn->$name( ...$arguments );
	}

	/**
	 * @return int DB_PRIMARY when this *requires* the primary DB, otherwise DB_REPLICA
	 * @since 1.33
	 */
	public function getReferenceRole() {
		return $this->role;
	}

	/** @inheritDoc */
	public function getServerInfo() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function trxLevel() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function trxTimestamp() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function explicitTrxActive() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function tablePrefix( $prefix = null ) {
		if ( $prefix !== null ) {
			// Disallow things that might confuse the LoadBalancer tracking
			throw $this->getDomainChangeException();
		}

		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			$prefix = $this->params[self::FLD_DOMAIN]->getTablePrefix();
		} else {
			// This will just return the prefix
			$prefix = $this->__call( __FUNCTION__, func_get_args() );
		}

		return $prefix;
	}

	/** @inheritDoc */
	public function dbSchema( $schema = null ) {
		if ( $schema !== null ) {
			// Disallow things that might confuse the LoadBalancer tracking
			throw $this->getDomainChangeException();
		}

		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			$schema = (string)( $this->params[self::FLD_DOMAIN]->getSchema() );
		} else {
			// This will just return the schema
			$schema = $this->__call( __FUNCTION__, func_get_args() );
		}

		return $schema;
	}

	/** @inheritDoc */
	public function getLBInfo( $name = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setLBInfo( $nameOrArray, $value = null ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		// Disallow things that might confuse the LoadBalancer tracking
		throw $this->getDomainChangeException();
	}

	/** @inheritDoc */
	public function implicitOrderby() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lastDoneWrites() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function writesPending() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function writesOrCallbacksPending() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function pendingWriteCallers() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function isOpen() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getFlag( $flag ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getProperty( $name ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getDomainID() {
		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			return $this->params[self::FLD_DOMAIN]->getId();
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getType() {
		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			$index = $this->normalizeServerIndex( $this->params[self::FLD_INDEX] );
			if ( $index >= 0 ) {
				// In theory, if $index is DB_REPLICA, the type could vary
				return $this->lb->getServerType( $index );
			}
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function insertId() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lastErrno() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lastError() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function affectedRows() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getSoftwareLink() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getServerVersion() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function close( $fname = __METHOD__ ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new DBUnexpectedError( $this->conn, 'Cannot close shared connection.' );
	}

	/** @inheritDoc */
	public function query( $sql, $fname = __METHOD__, $flags = 0 ) {
		if ( $this->role !== ILoadBalancer::DB_PRIMARY ) {
			$flags |= IDatabase::QUERY_REPLICA_ROLE;
		}

		return $this->__call( __FUNCTION__, [ $sql, $fname, $flags ] );
	}

	public function newSelectQueryBuilder(): SelectQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new SelectQueryBuilder( $this );
	}

	public function newUnionQueryBuilder(): UnionQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new UnionQueryBuilder( $this );
	}

	public function newUpdateQueryBuilder(): UpdateQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new UpdateQueryBuilder( $this );
	}

	public function newDeleteQueryBuilder(): DeleteQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new DeleteQueryBuilder( $this );
	}

	public function newInsertQueryBuilder(): InsertQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new InsertQueryBuilder( $this );
	}

	public function newReplaceQueryBuilder(): ReplaceQueryBuilder {
		// Use $this not $this->conn so that the domain is preserved (T326377)
		return new ReplaceQueryBuilder( $this );
	}

	/** @inheritDoc */
	public function selectField(
		$tables, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function selectFieldValues(
		$tables, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	): array {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function select(
		$tables, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function selectSQLText(
		$tables, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function limitResult( $sql, $limit, $offset = false ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function selectRow(
		$tables, $vars, $conds, $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function estimateRowCount(
		$tables, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	): int {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function selectRowCount(
		$tables, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	): int {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lockForUpdate(
		$table, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function indexExists( $table, $index, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getPrimaryKeyColumns( $table, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function tableExists( $table, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function insert( $table, $rows, $fname = __METHOD__, $options = [] ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function update( $table, $set, $conds, $fname = __METHOD__, $options = [] ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildComparison( string $op, array $conds ): string {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function makeList( array $a, $mode = self::LIST_COMMA ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function factorConds( $condsArray ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function bitNot( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function bitAnd( $fieldLeft, $fieldRight ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function bitOr( $fieldLeft, $fieldRight ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildConcat( $stringList ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildGroupConcat( $field, $delim ): string {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildGroupConcatField(
		$delim, $tables, $field, $conds = '', $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildGreatest( $fields, $values ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildLeast( $fields, $values ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildSubstring( $input, $startPosition, $length = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildStringCast( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildIntegerCast( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildExcludedValue( $column ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildSelectSubquery(
		$tables, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function databasesAreIndependent() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function selectDomain( $domain ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		// Disallow things that might confuse the LoadBalancer tracking
		throw $this->getDomainChangeException();
	}

	/** @inheritDoc */
	public function getDBname() {
		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			return $this->params[self::FLD_DOMAIN]->getDatabase();
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getServer() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getServerName() {
		if ( $this->conn === null ) {
			// Avoid triggering a database connection
			$index = $this->normalizeServerIndex( $this->params[self::FLD_INDEX] );
			if ( $index >= 0 ) {
				// If $index is DB_REPLICA, the server name could vary
				return $this->lb->getServerName( $index );
			}
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function addQuotes( $s ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function expr( string $field, string $op, $value ): Expression {
		// Does not use __call here to delay creating the db connection
		return new Expression( $field, $op, $value );
	}

	/** @inheritDoc */
	public function andExpr( array $conds ): AndExpressionGroup {
		// Does not use __call here to delay creating the db connection
		return AndExpressionGroup::newFromArray( $conds );
	}

	/** @inheritDoc */
	public function orExpr( array $conds ): OrExpressionGroup {
		// Does not use __call here to delay creating the db connection
		return OrExpressionGroup::newFromArray( $conds );
	}

	/** @inheritDoc */
	public function addIdentifierQuotes( $s ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function buildLike( $param, ...$params ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function anyChar() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function anyString() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function upsert(
		$table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function deleteJoin(
		$delTable, $joinTable, $delVar, $joinVar, $conds, $fname = __METHOD__
	) {
		$this->assertRoleAllowsWrites();

		$this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function insertSelect(
		$destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__, $insertOptions = [], $selectOptions = [], $selectJoinConds = []
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function unionSupportsOrderAndLimit() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function unionQueries( $sqls, $all, $options = [] ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function conditional( $cond, $caseTrueExpression, $caseFalseExpression ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function strreplace( $orig, $old, $new ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function primaryPosWait( DBPrimaryPos $pos, $timeout ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getPrimaryPos() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function serverIsReadOnly() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function onTransactionResolution( callable $callback, $fname = __METHOD__ ) {
		// DB_REPLICA role: caller might want to refresh cache after a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function onTransactionCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		// DB_REPLICA role: caller might want to refresh cache after a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		// DB_REPLICA role: caller might want to refresh cache after a cache mutex is released
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setTransactionListener( $name, ?callable $callback = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function startAtomic(
		$fname = __METHOD__, $cancelable = IDatabase::ATOMIC_NOT_CANCELABLE
	) {
		// Don't call assertRoleAllowsWrites(); caller might want a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function endAtomic( $fname = __METHOD__ ) {
		// Don't call assertRoleAllowsWrites(); caller might want a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function cancelAtomic( $fname = __METHOD__, ?AtomicSectionIdentifier $sectionId = null ) {
		// Don't call assertRoleAllowsWrites(); caller might want a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function doAtomicSection(
		$fname, callable $callback, $cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		// Don't call assertRoleAllowsWrites(); caller might want a REPEATABLE-READ snapshot
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function begin( $fname = __METHOD__, $mode = IDatabase::TRANSACTION_EXPLICIT ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function commit( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function rollback( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function flushSession( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function flushSnapshot( $fname = __METHOD__, $flush = self::FLUSHING_ONE ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function timestamp( $ts = 0 ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function timestampOrNull( $ts = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function ping( &$rtt = null ) {
		return func_num_args()
			? $this->__call( __FUNCTION__, [ &$rtt ] )
			: $this->__call( __FUNCTION__, [] ); // method cares about null vs missing
	}

	/** @inheritDoc */
	public function getLag() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getSessionLagStatus() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function encodeBlob( $b ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function decodeBlob( $b ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setSessionOptions( array $options ) {
		$this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setSchemaVars( $vars ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lockIsFree( $lockName, $method ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function lock( $lockName, $method, $timeout = 5, $flags = 0 ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function unlock( $lockName, $method ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getScopedLockAndFlush( $lockKey, $fname, $timeout ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getInfinity() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function encodeExpiry( $expiry ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function decodeExpiry( $expiry, $format = TS_MW ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function isReadOnly() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function setTableAliases( array $aliases ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function getTableAliases() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function tableName( $name, $format = 'quoted' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function tableNames( ...$tables ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function tableNamesN( ...$tables ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function sourceFile(
		$filename,
		?callable $lineCallback = null,
		?callable $resultCallback = null,
		$fname = false,
		?callable $inputCallback = null
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function sourceStream(
		$fp,
		?callable $lineCallback = null,
		?callable $resultCallback = null,
		$fname = __METHOD__,
		?callable $inputCallback = null
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function dropTable( $table, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function truncateTable( $table, $fname = __METHOD__ ) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function streamStatementEnd( &$sql, &$newLine ) {
		return $this->__call( __FUNCTION__, [ &$sql, &$newLine ] );
	}

	/** @inheritDoc */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$this->assertRoleAllowsWrites();

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function indexUnique( $table, $index, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/** @inheritDoc */
	public function fieldInfo( $table, $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function __toString() {
		if ( $this->conn === null ) {
			return $this->getType() . ' object #' . spl_object_id( $this );
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/**
	 * Error out if the role is not DB_PRIMARY
	 *
	 * Note that the underlying connection may or may not itself be read-only.
	 * It could even be to a writable primary (both server-side and to the application).
	 * This error is meant for the case when a DB_REPLICA handle was requested but a
	 * a write was attempted on that handle regardless.
	 *
	 * In configurations where the primary DB has some generic read load or is the only server,
	 * DB_PRIMARY/DB_REPLICA will sometimes (or always) use the same connection to the primary DB.
	 * This does not effect the role of DBConnRef instances.
	 * @throws DBReadOnlyRoleError
	 */
	protected function assertRoleAllowsWrites() {
		// DB_PRIMARY is "prima facie" writable
		if ( $this->role !== ILoadBalancer::DB_PRIMARY ) {
			throw new DBReadOnlyRoleError( $this->conn, "Cannot write with role DB_REPLICA" );
		}
	}

	/**
	 * @return DBUnexpectedError
	 */
	protected function getDomainChangeException() {
		return new DBUnexpectedError(
			$this,
			"Cannot directly change the selected DB domain; any underlying connection handle " .
			"is owned by a LoadBalancer instance and possibly shared with other callers. " .
			"LoadBalancer automatically manages DB domain re-selection of unused handles."
		);
	}

	/**
	 * @param int $i Specific or virtual (DB_PRIMARY/DB_REPLICA) server index
	 * @return int|mixed
	 */
	protected function normalizeServerIndex( $i ) {
		return ( $i === ILoadBalancer::DB_PRIMARY ) ? ServerInfo::WRITER_INDEX : $i;
	}
}
