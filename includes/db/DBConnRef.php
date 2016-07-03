<?php
/**
 * Helper class to handle automatically marking connections as reusable (via RAII pattern)
 * as well handling deferring the actual network connection until the handle is used
 *
 * @note: proxy methods are defined explicity to avoid interface errors
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

	function __call( $name, array $arguments ) {
		if ( $this->conn === null ) {
			list( $db, $groups, $wiki ) = $this->params;
			$this->conn = $this->lb->getConnection( $db, $groups, $wiki );
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

	public function pendingWriteQueryDuration() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function pendingWriteCallers() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function isOpen() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function setFlag( $flag ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function clearFlag( $flag ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getFlag( $flag ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getProperty( $name ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getWikiID() {
		return $this->__call( __FUNCTION__, func_get_args() );
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

	public function fieldInfo( $table, $field ) {
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

	public function reportConnectionError( $error = 'Unknown error' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function query( $sql, $fname = __METHOD__, $tempIgnore = false ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function freeResult( $res ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectField(
		$table, $var, $cond = '', $fname = __METHOD__, $options = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function selectFieldValues(
		$table, $var, $cond = '', $fname = __METHOD__, $options = []
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
		$table, $vars = '*', $conds = '', $fname = __METHOD__, $options = []
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

	public function indexUnique( $table, $index ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function insert( $table, $a, $fname = __METHOD__, $options = [] ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function makeList( $a, $mode = LIST_COMMA ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function makeWhereFrom2d( $data, $baseKey, $subKey ) {
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
		$fname = __METHOD__, $insertOptions = [], $selectOptions = []
	) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unionSupportsOrderAndLimit() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function unionQueries( $sqls, $all ) {
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

	public function wasErrorReissuable() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function wasReadOnlyError() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getSlavePos() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getMasterPos() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function onTransactionIdle( $callback ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function onTransactionPreCommitOrIdle( $callback ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function startAtomic( $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function endAtomic( $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function doAtomicSection( $fname, $callback ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function begin( $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function commit( $fname = __METHOD__, $flush = '' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function rollback( $fname = __METHOD__, $flush = '' ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function timestamp( $ts = 0 ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function timestampOrNull( $ts = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function ping() {
		return $this->__call( __FUNCTION__, func_get_args() );
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

	/**
	 * Clean up the connection when out of scope
	 */
	function __destruct() {
		if ( $this->conn !== null ) {
			$this->lb->reuseConnection( $this->conn );
		}
	}
}
