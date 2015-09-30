<?php
/**
 * Holds tests for LBFactory abstract MediaWiki class.
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
 * @group Database
 * @file
 * @author Antoine Musso
 * @copyright © 2013 Antoine Musso
 * @copyright © 2013 Wikimedia Foundation Inc.
 */
class LBFactoryTest extends MediaWikiTestCase {

	/**
	 * @dataProvider getLBFactoryClassProvider
	 */
	public function testGetLBFactoryClass( $expected, $deprecated ) {
		$mockDB = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->getMock();

		$config = array(
			'class'          => $deprecated,
			'connection'     => $mockDB,
			# Various other parameters required:
			'sectionsByDB'   => array(),
			'sectionLoads'   => array(),
			'serverTemplate' => array(),
		);

		$this->hideDeprecated( '$wgLBFactoryConf must be updated. See RELEASE-NOTES for details' );
		$result = LBFactory::getLBFactoryClass( $config );

		$this->assertEquals( $expected, $result );
	}

	public function getLBFactoryClassProvider() {
		return array(
			# Format: new class, old class
			array( 'LBFactorySimple', 'LBFactory_Simple' ),
			array( 'LBFactorySingle', 'LBFactory_Single' ),
			array( 'LBFactoryMulti', 'LBFactory_Multi' ),
			array( 'LBFactoryFake', 'LBFactory_Fake' ),
		);
	}

	public function testLBFactorySimpleServer() {
		$this->setMwGlobals( 'wgDBservers', false );

		$factory = new LBFactorySimple( array() );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'master' ), 'DB_SLAVE also gets the master' );

		$factory->shutdown();
		$lb->closeAll();
	}

	public function testLBFactorySimpleServers() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;

		$this->setMwGlobals( 'wgDBservers', array(
			array( // master
				'host'		=> $wgDBserver,
				'dbname'    => $wgDBname,
				'user'		=> $wgDBuser,
				'password'	=> $wgDBpassword,
				'type'		=> $wgDBtype,
				'load'      => 0,
				'flags'     => DBO_TRX // REPEATABLE-READ for consistency
			),
			array( // emulated slave
				'host'		=> $wgDBserver,
				'dbname'    => $wgDBname,
				'user'		=> $wgDBuser,
				'password'	=> $wgDBpassword,
				'type'		=> $wgDBtype,
				'load'      => 100,
				'flags'     => DBO_TRX // REPEATABLE-READ for consistency
			)
		) );

		$factory = new LBFactorySimple( array() );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'slave' ), 'slave shows as slave' );

		$factory->shutdown();
		$lb->closeAll();
	}

	public function testLBFactoryMulti() {
		global $wgDBserver, $wgDBname, $wgDBuser, $wgDBpassword, $wgDBtype;

		$factory = new LBFactoryMulti( array(
			'sectionsByDB' => array(),
			'sectionLoads' => array(
				'DEFAULT' => array(
					'test-db1' => 0,
					'test-db2' => 100,
				),
			),
			'serverTemplate' => array(
				'dbname'	  => $wgDBname,
				'user'		  => $wgDBuser,
				'password'	  => $wgDBpassword,
				'type'		  => $wgDBtype,
				'flags'		  => DBO_DEFAULT
			),
			'hostsByName' => array(
				'test-db1'  => $wgDBserver,
				'test-db2'  => $wgDBserver
			),
		) );
		$lb = $factory->getMainLB();

		$dbw = $lb->getConnection( DB_MASTER );
		$this->assertTrue( $dbw->getLBInfo( 'master' ), 'master shows as master' );

		$dbr = $lb->getConnection( DB_SLAVE );
		$this->assertTrue( $dbr->getLBInfo( 'slave' ), 'slave shows as slave' );

		$factory->shutdown();
		$lb->closeAll();
	}
}
