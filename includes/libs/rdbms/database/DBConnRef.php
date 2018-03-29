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
class DBConnRef implements IDatabase {
	/** @var ILoadBalancer */
	private $lb;
	/** @var Database|null Live connection handle */
	private $conn;
	/** @var array|null N-tuple of (server index, group, DatabaseDomain|string) */
	private $params;

	const FLD_INDEX = 0;
	const FLD_GROUP = 1;
	const FLD_DOMAIN = 2;
	const FLD_FLAGS = 3;

	/**
	 * @param ILoadBalancer $lb Connection manager for $conn
	 * @param Database|array $conn Database handle or (server index, query groups, domain, flags)
	 */
	public function __construct( ILoadBalancer $lb, $conn ) {
		$this->lb = $lb;
		if ( $conn instanceof Database ) {
			$this->conn = $conn; // live handle
		} elseif ( count( $conn ) >= 4 && $conn[self::FLD_DOMAIN] !== false ) {
			$this->params = $conn;
		} else {
			throw new InvalidArgumentException( "Missing lazy connection arguments." );
		}
	}

	function __call( $name, array $arguments ) {
		if ( $this->conn === null ) {
			list( $db, $groups, $wiki, $flags ) = $this->params;
			$this->conn = $this->lb->getConnection( $db, $groups, $wiki, $flags );
		}

		return call_user_func_array( [ $this->conn, $name ], $arguments );
	}

	public function getServerInfo() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function bufferResults( $buffer = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function trxLevel() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function trxTimestamp() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function explicitTrxActive() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function tablePrefix( $prefix = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function dbSchema( $schema = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getLBInfo( $name = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setLBInfo( $name, $value = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setLazyMasterHandle( IDatabase $conn ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function implicitGroupby() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function implicitOrderby() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lastQuery() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function doneWrites() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lastDoneWrites() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function writesPending() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function writesOrCallbacksPending() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function pendingWriteQueryDuration( $type = self::ESTIMATE_TOTAL ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function pendingWriteCallers() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function pendingWriteRowsAffected() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function isOpen() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function clearFlag( $flag, $remember = self::REMEMBER_NOTHING ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function restoreFlags( $state = self::RESTORE_PRIOR ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getFlag( $flag ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getProperty( $name ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getDomainID() {
		if ( $this->conn === null ) {
			$domain = $this->params[self::FLD_DOMAIN];
			// Avoid triggering a database connection
			return $domain instanceof DatabaseDomain ? $domain->getId() : $domain;
		}

		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getWikiID() {
		return $this->getDomainID();
	}

	public function getType() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function open( $server, $user, $password, $dbName ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function fetchObject( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function fetchRow( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function numRows( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function numFields( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function fieldName( $res, $n ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function insertId() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function dataSeek( $res, $row ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lastErrno() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lastError() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function affectedRows() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getSoftwareLink() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getServerVersion() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function close() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function freeResult( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function select(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectRow(
		$table, $vars, $conds, $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function estimateRowCount(
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectRowCount(
		$tables, $vars = '*', $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function indexExists( $table, $index, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function insert( $table, $a, $fname = __METHOD__, $options = [] ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function makeList( $a, $mode = self::LIST_COMMA ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function bitNot( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function bitAnd( $fieldLeft, $fieldRight ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function bitOr( $fieldLeft, $fieldRight ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildConcat( $stringList ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildSubstring( $input, $startPosition, $length = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildStringCast( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildIntegerCast( $field ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildSelectSubquery(
		$table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function databasesAreIndependent() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectDB( $db ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getDBname() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getServer() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function addQuotes( $s ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function buildLike() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function anyChar() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function anyString() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function nextSequenceValue( $seqName ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function upsert(
		$table, array $rows, array $uniqueIndexes, array $set, $fname = __METHOD__
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function deleteJoin(
		$delTable, $joinTable, $delVar, $joinVar, $conds, $fname = __METHOD__
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function insertSelect(
		$destTable, $srcTable, $varMap, $conds,
		$fname = __METHOD__, $insertOptions = [], $selectOptions = [], $selectJoinConds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unionSupportsOrderAndLimit() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unionQueries( $sqls, $all ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unionConditionPermutations(
		$table, $vars, array $permute_conds, $extra_conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function conditional( $cond, $trueVal, $falseVal ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function strreplace( $orig, $old, $new ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getServerUptime() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasDeadlock() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasLockTimeout() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasConnectionLoss() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasReadOnlyError() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasErrorReissuable() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getReplicaPos() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getMasterPos() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function serverIsReadOnly() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function onTransactionResolution( callable $callback, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function onTransactionIdle( callable $callback, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function onTransactionPreCommitOrIdle( callable $callback, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setTransactionListener( $name, callable $callback = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function startAtomic(
		$fname = __METHOD__, $cancelable = IDatabase::ATOMIC_NOT_CANCELABLE
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function endAtomic( $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function cancelAtomic( $fname = __METHOD__, AtomicSectionIdentifier $sectionId = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function doAtomicSection(
		$fname, callable $callback, $cancelable = self::ATOMIC_NOT_CANCELABLE
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function begin( $fname = __METHOD__, $mode = IDatabase::TRANSACTION_EXPLICIT ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function commit( $fname = __METHOD__, $flush = '' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function rollback( $fname = __METHOD__, $flush = '' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function flushSnapshot( $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function timestamp( $ts = 0 ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function timestampOrNull( $ts = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function ping( &$rtt = null ) {
		return func_num_args()
			? $this->__call( __FUNCTION__, [ &$rtt ] )
			: $this->__call( __FUNCTION__, [] ); // method cares about null vs missing
	}

	public function getLag() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getSessionLagStatus() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function maxListLen() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function encodeBlob( $b ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function decodeBlob( $b ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setSessionOptions( array $options ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setSchemaVars( $vars ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lockIsFree( $lockName, $method ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function lock( $lockName, $method, $timeout = 5 ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unlock( $lockName, $method ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getScopedLockAndFlush( $lockKey, $fname, $timeout ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function namedLocksEnqueue() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getInfinity() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function encodeExpiry( $expiry ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function decodeExpiry( $expiry, $format = TS_MW ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setBigSelects( $value = true ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function isReadOnly() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setTableAliases( array $aliases ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setIndexAliases( array $aliases ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	/**
	 * Clean up the connection when out of scope
	 */
	function __destruct() {
		if ( $this->conn ) {
			$this->lb->reuseConnection( $this->conn );
		}
	}
}

class_alias( DBConnRef::class, 'DBConnRef' );
