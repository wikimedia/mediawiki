<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DatabaseMySQL;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * @covers \Wikimedia\Rdbms\DatabaseFactory
 */
class DatabaseFactoryTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testFactory() {
		$factory = new DatabaseFactory();
		$m = Database::NEW_UNCONNECTED; // no-connect mode
		$p = [
			'host' => 'localhost',
			'serverName' => 'localdb',
			'user' => 'me',
			'password' => 'myself',
			'dbname' => 'i'
		];

		$this->assertInstanceOf( DatabaseMySQL::class, $factory->create( 'mysql', $p, $m ) );
		$this->assertInstanceOf( DatabaseMySQL::class, $factory->create( 'MySql', $p, $m ) );
		$this->assertInstanceOf( DatabaseMySQL::class, $factory->create( 'MySQL', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, $factory->create( 'postgres', $p, $m ) );
		$this->assertInstanceOf( DatabasePostgres::class, $factory->create( 'Postgres', $p, $m ) );

		$x = $p + [ 'dbFilePath' => 'some/file.sqlite' ];
		$this->assertInstanceOf( DatabaseSqlite::class, $factory->create( 'sqlite', $x, $m ) );
		$x = $p + [ 'dbDirectory' => 'some/file' ];
		$this->assertInstanceOf( DatabaseSqlite::class, $factory->create( 'sqlite', $x, $m ) );

		$conn = $factory->create( 'sqlite', $p, $m );
		$this->assertEquals( 'localhost', $conn->getServer() );
		$this->assertEquals( 'localdb', $conn->getServerName() );
	}
}
