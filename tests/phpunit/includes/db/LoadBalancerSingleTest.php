<?php

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancerSingle;

/**
 * Holds tests for LoadBalancer MediaWiki class.
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
 * @covers Wikimedia\Rdbms\LoadBalancerSingle
 */
class LoadBalancerSingleTest extends MediaWikiTestCase {

	/**
	 * @return IDatabase
	 */
	private function getDatabaseMock() {
		$db = $this->getMock( IDatabase::class );

		$db->method( 'delete' )->willReturn( new FakeResultWrapper( [] ) );
		$db->method( 'isOpen' )->willReturn( true );

		$db->method( 'getLBInfo' )->willReturnCallback(
			function ( $name = null ) {
				if ( $name === 'master' ) {
					return true;
				} elseif ( $name === null ) {
					return [ 'master' => true ];
				} else {
					return null;
				}
			}
		);

		return $db;
	}

	public function testLoadBalancerSingle() {
		$db = $this->getDatabaseMock();
		$lb = new LoadBalancerSingle( [
			'connection' => $db,
		] );

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );
		$this->assertWriteAllowed( $dbw );

		$dbr = $lb->getConnection( DB_REPLICA );
		$this->assertNotSame( $dbw, $dbr, 'Replica connection is not master connection' );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_REPLICA connects to master' );
		$this->assertTrue( $dbr->getLBInfo( 'noWrite' ), 'Replica connection has noWrite set' );
		$this->assertWriteForbidden( $dbr );

		$lb->closeAll();
	}

	private function assertWriteForbidden( IDatabase $db ) {
		try {
			$db->delete( 'user', [ 'user_id' => 57634126 ], 'TEST' );
			$this->fail( 'Write operation should have failed!' );
		} catch ( DBError $ex ) {
			$this->assertContains( 'Write operation', $ex->getMessage() );
		}
	}

	private function assertWriteAllowed( IDatabase $db ) {
		$this->assertNotSame( false, $db->delete( 'user', [ 'user_id' => 57634126 ] ) );
	}

}
