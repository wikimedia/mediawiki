<?php

/**
 * Tests for the TestORMRow class.
 * TestORMRow is a dummy class to be able to test the abstract ORMRow class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.20
 *
 * @ingroup Test
 *
 * @group ORM
 *
 * The database group has as a side effect that temporal database tables are created. This makes
 * it possible to test without poisoning a production database.
 * @group Database
 *
 * Some of the tests takes more time, and needs therefor longer time before they can be aborted
 * as non-functional. The reason why tests are aborted is assumed to be set up of temporal databases
 * that hold the first tests in a pending state awaiting access to the database.
 * @group medium
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
require_once __DIR__ . "/ORMRowTest.php";

/**
 * @covers TestORMRow
 */
class TestORMRowTest extends ORMRowTest {

	/**
	 * @since 1.20
	 * @return string
	 */
	protected function getRowClass() {
		return 'TestORMRow';
	}

	/**
	 * @since 1.20
	 * @return IORMTable
	 */
	protected function getTableInstance() {
		return TestORMTable::singleton();
	}

	protected function setUp() {
		parent::setUp();

		$dbw = wfGetDB( DB_MASTER );

		$isSqlite = $GLOBALS['wgDBtype'] === 'sqlite';
		$isPostgres = $GLOBALS['wgDBtype'] === 'postgres';

		$idField = $isSqlite ? 'INTEGER' : 'INT unsigned';
		$primaryKey = $isSqlite ? 'PRIMARY KEY AUTOINCREMENT' : 'auto_increment PRIMARY KEY';

		if ( $isPostgres ) {
			$dbw->query(
				'CREATE TABLE IF NOT EXISTS ' . $dbw->tableName( 'orm_test' ) . "(
					test_id serial PRIMARY KEY,
					test_name TEXT NOT NULL DEFAULT '',
					test_age INTEGER NOT NULL DEFAULT 0,
					test_height REAL NOT NULL DEFAULT 0,
					test_awesome INTEGER NOT NULL DEFAULT 0,
					test_stuff BYTEA,
					test_moarstuff BYTEA,
					test_time TIMESTAMPTZ
					);",
					__METHOD__
				);
		} else {
			$dbw->query(
				'CREATE TABLE IF NOT EXISTS ' . $dbw->tableName( 'orm_test' ) . '(
					test_id                    ' . $idField . '        NOT NULL ' . $primaryKey . ',
					test_name                  VARCHAR(255)        NOT NULL,
					test_age                   TINYINT unsigned    NOT NULL,
					test_height                FLOAT               NOT NULL,
					test_awesome               TINYINT unsigned    NOT NULL,
					test_stuff                 BLOB                NOT NULL,
					test_moarstuff             BLOB                NOT NULL,
					test_time                  varbinary(14)       NOT NULL
				);',
				__METHOD__
			);
		}
	}

	protected function tearDown() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->dropTable( 'orm_test', __METHOD__ );

		parent::tearDown();
	}

	public function constructorTestProvider() {
		$dbw = wfGetDB( DB_MASTER );
		return array(
			array(
				array(
					'name' => 'Foobar',
					'time' => $dbw->timestamp( '20120101020202' ),
					'age' => 42,
					'height' => 9000.1,
					'awesome' => true,
					'stuff' => array( 13, 11, 7, 5, 3, 2 ),
					'moarstuff' => (object)array( 'foo' => 'bar', 'bar' => array( 4, 2 ), 'baz' => true )
				),
				true
			),
		);
	}

	/**
	 * @since 1.21
	 * @return array
	 */
	protected function getMockValues() {
		return array(
			'id' => 1,
			'str' => 'foobar4645645',
			'int' => 42,
			'float' => 4.2,
			'bool' => '',
			'array' => array( 42, 'foobar' ),
			'blob' => new stdClass()
		);
	}
}

class TestORMRow extends ORMRow {
}

class TestORMTable extends ORMTable {

	/**
	 * Returns the name of the database table objects of this type are stored in.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getName() {
		return 'orm_test';
	}

	/**
	 * Returns the name of a IORMRow implementing class that
	 * represents single rows in this table.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRowClass() {
		return 'TestORMRow';
	}

	/**
	 * Returns an array with the fields and their types this object contains.
	 * This corresponds directly to the fields in the database, without prefix.
	 *
	 * field name => type
	 *
	 * Allowed types:
	 * * id
	 * * str
	 * * int
	 * * float
	 * * bool
	 * * array
	 * * blob
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getFields() {
		return array(
			'id' => 'id',
			'name' => 'str',
			'age' => 'int',
			'height' => 'float',
			'awesome' => 'bool',
			'stuff' => 'array',
			'moarstuff' => 'blob',
			'time' => 'str', // TS_MW
		);
	}

	/**
	 * Gets the db field prefix.
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	protected function getFieldPrefix() {
		return 'test_';
	}
}
