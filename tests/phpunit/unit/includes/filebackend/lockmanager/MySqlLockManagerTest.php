<?php

use Wikimedia\TestingAccessWrapper;

class MySqlLockManagerTest extends MediaWikiUnitTestCase {
	// 31 characters long
	private const FAKE_SESSION = '0123456789abcdef0123456789abcde';

	/**
	 * @covers MysqlLockManager::__construct
	 * @covers MySqlLockManager::doGetLocksOnServer
	 * @covers MySqlLockManager::initConnection
	 *
	 * @dataProvider provideGetLocksOnServer
	 * @param array $params Keys:
	 *   - lockArgs: Array of arguments to pass to lock()
	 *   - sharedInsertKeys: Array of keys that are expected to be inserted in filelocks_shared
	 *     table (the session is assumed to be self::FAKE_SESSION)
	 *   - exclusiveInsertKeys: Same but for filelocks_exclusive
	 *   - exclusiveSelectFieldKeys: Array of keys expected to be checked for in filelocks_exclusive
	 *   - exclusiveSelectFieldResult: '1' if the keys will be found there, false if not
	 *   - sharedSelectFieldKeys: Array of keys expected to be checked for in filelocks_shared
	 *   - sharedSelectFieldResult1: Result expected the first time, before the exclusive lock
	 *     insertion
	 *   - sharedSelectFieldResult2: Result expected after an exclusive lock is obtained
	 *   - expectedOK: Boolean, true for success and false for failure
	 *   - lockedPaths: Array of paths that the LockManager should be told it holds exclusive locks
	 *     on already (locksHeld member)
	 */
	public function testGetLocksOnServer( array $params ) {
		$mockDb = $this->createMock( IDatabase::class );

		$mockDb->expects( $this->never() )->method( $this->anythingBut( 'query', 'startAtomic',
			'selectField', 'insert', 'setSessionOptions', 'trxLevel', 'rollback', 'close',
			'addQuotes' ) );

		$isolationSet = false;
		$trxStarted = false;

		$mockDb->expects( $this->once() )->method( 'query' )
			->with( 'SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;' )
			->will( $this->returnCallback( function () use ( &$isolationSet ) {
				$isolationSet = true;
			} ) );

		$mockDb->expects( $this->once() )->method( 'startAtomic' )
			->will( $this->returnCallback( function () use ( &$trxStarted ) {
				$trxStarted = true;
			} ) );

		// Because of the way PHPUnit works, we don't test the order of inserts relative to
		// selectField. It shouldn't matter if the correct results are obtained in all cases.
		$expectedInserts = [];
		if ( isset( $params['sharedInsertKeys'] ) ) {
			$rows = [];
			foreach ( $params['sharedInsertKeys'] as $key ) {
				$rows[] = [ 'fls_key' => $key, 'fls_session' => self::FAKE_SESSION ];
			}
			$expectedInserts[] = [
				'filelocks_shared',
				$rows,
				self::isType( 'string' ),
				[ 'IGNORE' ],
			];
		}
		if ( isset( $params['exclusiveInsertKeys'] ) ) {
			$rows = [];
			foreach ( $params['exclusiveInsertKeys'] as $key ) {
				$rows[] = [ 'fle_key' => $key ];
			}
			$expectedInserts[] = [
				'filelocks_exclusive',
				$rows,
				self::isType( 'string' ),
				[],
			];
		}

		$mockDb->expects( $this->exactly( count( $expectedInserts ) ) )->method( 'insert' )
			->withConsecutive( ...$expectedInserts )
			->will( $this->returnCallback( function () use ( &$isolationSet, &$trxStarted ) {
				$this->assertTrue( $isolationSet, 'Read uncommitted must be set before queries' );
				$this->assertTrue( $trxStarted, 'Transaction must be started before writes' );
			} ) );

		$expectedSelectFieldArgs = [];
		$selectFieldReturns = [];
		if ( isset( $params['exclusiveSelectFieldKeys'] ) ) {
			$expectedSelectFieldArgs[] = [
				'filelocks_exclusive', '1', [ 'fle_key' => $params['exclusiveSelectFieldKeys'] ]
			];
			$selectFieldReturns[] = $params['exclusiveSelectFieldResult'];
		}
		if ( isset( $params['sharedSelectFieldKeys'] ) ) {
			$row = [
				'filelocks_shared',
				'1',
				[
					'fls_key' => $params['sharedSelectFieldKeys'],
					"fls_session != '" . self::FAKE_SESSION . "'"
				]
			];
			$expectedSelectFieldArgs[] = $row;
			$selectFieldReturns[] = $params['sharedSelectFieldResult1'];
			if ( isset( $params['sharedSelectFieldResult2'] ) ) {
				// Query will be the same, result may be different
				$expectedSelectFieldArgs[] = $row;
				$selectFieldReturns[] = $params['sharedSelectFieldResult2'];
			}
		}

		$mockDb->expects( $this->exactly( count( $expectedSelectFieldArgs ) ) )
			->method( 'selectField' )
			->withConsecutive( ...$expectedSelectFieldArgs )
			->willReturnOnConsecutiveCalls( ...$selectFieldReturns );

		$mockDb->method( 'addQuotes' )->will( $this->returnCallback( function ( $s ) {
			return "'$s'";
		} ) );

		$lm = new MySqlLockManager( [
			'dbServers' => [ 'main' => $mockDb ],
			'dbsByBucket' => [ [ 'main' ] ],
		] );

		// We need a predictable session here
		$wrapper = TestingAccessWrapper::newFromObject( $lm );
		// This tests the constructor's truncation of the session to 31 chars from 32
		$this->assertRegExp( '/^[0-9a-z]{31}$/', $wrapper->session );
		$wrapper->session = self::FAKE_SESSION;

		$locksHeld = [];
		foreach ( $params['lockedPaths'] ?? [] as $path ) {
			$locksHeld[$path][LockManager::LOCK_EX] = true;
		}
		$wrapper->locksHeld = $locksHeld;

		$status = $lm->lock( ...$params['lockArgs'] );

		$expectedErrors = [];
		if ( !( $params['expectedOK'] ?? true ) ) {
			foreach ( $params['lockArgs'][0] as $path ) {
				$expectedErrors[] = [
					'type' => 'error',
					'message' => 'lockmanager-fail-acquirelock',
					'params' => [ $path ],
				];
			}
		}
		$this->assertSame( $expectedErrors, $status->getErrors() );
		$this->assertSame( !$expectedErrors, $status->isOK() );
	}

	/**
	 * Copied from LockManager::sha1Base36Absolute
	 *
	 * @param string $path
	 * @param string|null $domain
	 * @return string
	 */
	private static function sha1Base36Absolute( $path, $domain = null ) {
		$domain = $domain ?? 'global';
		return Wikimedia\base_convert( sha1( "$domain:$path" ), 16, 36, 31 );
	}

	public static function provideGetLocksOnServer() {
		$key = self::sha1Base36Absolute( 'MyFile.png' );
		$key2 = self::sha1Base36Absolute( 'MyFile.jpg' );

		return [
			'Simple exclusive lock' => [ [
				'lockArgs' => [ [ 'MyFile.png' ] ],
				'sharedInsertKeys' => [ $key ],
				'sharedSelectFieldKeys' => [ $key ],
				'sharedSelectFieldResult1' => false,
				'exclusiveInsertKeys' => [ $key ],
				'sharedSelectFieldResult2' => false,
			] ],
			'Simple shared lock' => [ [
				'lockArgs' => [ [ 'MyFile.png' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key ],
				'exclusiveSelectFieldKeys' => [ $key ],
				'exclusiveSelectFieldResult' => false,
			] ],
			'Exclusive lock blocked by shared lock' => [ [
				'lockArgs' => [ [ 'MyFile.png' ] ],
				'sharedInsertKeys' => [ $key ],
				'sharedSelectFieldKeys' => [ $key ],
				'sharedSelectFieldResult1' => '1',
				'expectedOK' => false,
			] ],
			'Exclusive lock blocked by shared lock on second try' => [ [
				'lockArgs' => [ [ 'MyFile.png' ] ],
				'sharedInsertKeys' => [ $key ],
				'sharedSelectFieldKeys' => [ $key ],
				'sharedSelectFieldResult1' => false,
				'exclusiveInsertKeys' => [ $key ],
				'sharedSelectFieldResult2' => '1',
				'expectedOK' => false,
			] ],
			'Shared lock blocked by exclusive lock' => [ [
				'lockArgs' => [ [ 'MyFile.png' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key ],
				'exclusiveSelectFieldKeys' => [ $key ],
				'exclusiveSelectFieldResult' => '1',
				'expectedOK' => false,
			] ],
			'Shared lock where we already hold an exclusive lock on the path' => [ [
				'lockArgs' => [ [ 'MyFile.png' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key ],
				'lockedPaths' => [ 'MyFile.png' ],
			] ],

			// Two keys at once
			'Exclusive lock on two keys' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ] ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'sharedSelectFieldKeys' => [ $key, $key2 ],
				'sharedSelectFieldResult1' => false,
				'exclusiveInsertKeys' => [ $key, $key2 ],
				'sharedSelectFieldResult2' => false,
			] ],
			'Shared lock on two keys' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldResult' => false,
			] ],
			'Exclusive lock on two keys blocked by shared lock' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ] ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'sharedSelectFieldKeys' => [ $key, $key2 ],
				'sharedSelectFieldResult1' => '1',
				'expectedOK' => false,
			] ],
			'Exclusive lock on two keys blocked by shared lock on second try' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ] ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'sharedSelectFieldKeys' => [ $key, $key2 ],
				'sharedSelectFieldResult1' => false,
				'exclusiveInsertKeys' => [ $key, $key2 ],
				'sharedSelectFieldResult2' => '1',
				'expectedOK' => false,
			] ],
			'Shared lock on two keys blocked by exclusive lock' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldResult' => '1',
				'expectedOK' => false,
			] ],
			'Shared lock on two keys where we already hold an exclusive lock on one' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldKeys' => [ $key2 ],
				'exclusiveSelectFieldResult' => false,
				'lockedPaths' => [ 'MyFile.png' ],
			] ],
			'Shared lock on two keys, one already held and one blocked' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'exclusiveSelectFieldKeys' => [ $key2 ],
				'exclusiveSelectFieldResult' => '1',
				'expectedOK' => false,
				'lockedPaths' => [ 'MyFile.png' ],
			] ],
			'Shared lock on two keys, both already held' => [ [
				'lockArgs' => [ [ 'MyFile.png', 'MyFile.jpg' ], LockManager::LOCK_SH ],
				'sharedInsertKeys' => [ $key, $key2 ],
				'lockedPaths' => [ 'MyFile.png', 'MyFile.jpg' ],
			] ],
		];
	}

	/**
	 * @covers MySqlLockManager::releaseAllLocks
	 * @dataProvider provideReleaseAllLocks
	 * @param int $trxLevel What the mock DB should return from trxLevel()
	 * @param int $expectedRollbacks How many times we expect rollback() to be called
	 */
	public function testReleaseAllLocks( $trxLevel, $expectedRollbacks ) {
		$mockDb = $this->createMock( IDatabase::class );
		$mockDb->expects( $this->once() )->method( 'trxLevel' )->willReturn( $trxLevel );
		$mockDb->expects( $this->exactly( $expectedRollbacks ) )->method( 'rollback' );
		$mockDb->expects( $this->never() )->method( $this->anythingBut( 'query', 'startAtomic',
			'selectField', 'insert', 'setSessionOptions', 'trxLevel', 'rollback', 'close',
			'addQuotes' ) );

		$lm = new MySqlLockManager( [
			'dbServers' => [ 'main' => $mockDb ],
			'dbsByBucket' => [ [ 'main' ] ],
		] );

		// Initialize the connection
		$lm->lock( [ 'a' ] );

		// __destruct should call releaseAllLocks
		unset( $lm );
	}

	public static function provideReleaseAllLocks() {
		return [
			[ 0, 0 ],
			[ 1, 1 ],
			[ 734, 1 ],
		];
	}

	/**
	 * @covers MySqlLockManager::releaseAllLocks
	 */
	public function testReleaseAllLocks_exception() {
		$mockDb = $this->createMock( IDatabase::class );
		// These methods will be called once by unlock() and again by destructor
		$mockDb->expects( $this->exactly( 2 ) )->method( 'trxLevel' )->willReturn( 1 );
		$mockDb->expects( $this->exactly( 2 ) )->method( 'rollback' )
			->will( $this->throwException( new DBError( $mockDb, '' ) ) );
		$mockDb->expects( $this->never() )->method( $this->anythingBut( 'query', 'startAtomic',
			'selectField', 'insert', 'setSessionOptions', 'trxLevel', 'rollback', 'close',
			'addQuotes' ) );

		$lm = new MySqlLockManager( [
			'dbServers' => [ 'main' => $mockDb ],
			'dbsByBucket' => [ [ 'main' ] ],
		] );

		// Initialize the connection
		$lm->lock( [ 'a' ] );

		// Unlocking everything will call releaseAllLocks
		$status = $lm->unlock( [ 'a' ] );
		$this->assertSame( [ [
			'type' => 'error',
			'message' => 'lockmanager-fail-db-release',
			'params' => [ 'main' ],
		] ], $status->getErrors() );
		$this->assertFalse( $status->isOK() );
	}
}
