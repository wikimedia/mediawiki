<?php

use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @covers \Wikimedia\Rdbms\ReadOnlyMode
 * @covers \Wikimedia\Rdbms\ConfiguredReadOnlyMode
 */
class ReadOnlyModeTest extends MediaWikiUnitTestCase {

	/** @var string */
	private $fileName;

	protected function setUp(): void {
		parent::setUp();

		// Do not use wfTmpDir() as that depends on globals.
		// Based on MediaWikiIntegrationTestCase::getNewTempFile()
		$this->fileName = tempnam(
			TempFSFile::getUsableTempDirectory(),
			'MW_PHPUnit_ReadOnlyModeTest'
		);
	}

	protected function tearDown(): void {
		@unlink( $this->fileName );
		parent::tearDown();
	}

	public static function provider() {
		$rawTests = [
			'None of anything' => [
				'confMessage' => null,
				'hasFileName' => false,
				'fileContents' => false,
				'lbMessage' => false,
				'expectedState' => false,
				'expectedMessage' => false,
				'expectedConfState' => false,
				'expectedConfMessage' => false
			],
			'File missing' => [
				'confMessage' => null,
				'hasFileName' => true,
				'fileContents' => false,
				'lbMessage' => false,
				'expectedState' => false,
				'expectedMessage' => false,
				'expectedConfState' => false,
				'expectedConfMessage' => false
			],
			'File empty' => [
				'confMessage' => null,
				'hasFileName' => true,
				'fileContents' => '',
				'lbMessage' => false,
				'expectedState' => false,
				'expectedMessage' => false,
				'expectedConfState' => false,
				'expectedConfMessage' => false
			],
			'File has message' => [
				'confMessage' => null,
				'hasFileName' => true,
				'fileContents' => 'Message',
				'lbMessage' => false,
				'expectedState' => true,
				'expectedMessage' => 'Message',
				'expectedConfState' => true,
				'expectedConfMessage' => 'Message',
			],
			'Conf has message' => [
				'confMessage' => 'Message',
				'hasFileName' => false,
				'fileContents' => false,
				'lbMessage' => false,
				'expectedState' => true,
				'expectedMessage' => 'Message',
				'expectedConfState' => true,
				'expectedConfMessage' => 'Message'
			],
			"Conf=false means don't check the file" => [
				'confMessage' => false,
				'hasFileName' => true,
				'fileContents' => 'Message',
				'lbMessage' => false,
				'expectedState' => false,
				'expectedMessage' => false,
				'expectedConfState' => false,
				'expectedConfMessage' => false,
			],
			'LB has message' => [
				'confMessage' => null,
				'hasFileName' => false,
				'fileContents' => false,
				'lbMessage' => 'Message',
				'expectedState' => true,
				'expectedMessage' => 'Message',
				'expectedConfState' => false,
				'expectedConfMessage' => false
			],
			'All three have a message: conf wins' => [
				'confMessage' => 'conf',
				'hasFileName' => true,
				'fileContents' => 'file',
				'lbMessage' => 'lb',
				'expectedState' => true,
				'expectedMessage' => 'conf',
				'expectedConfState' => true,
				'expectedConfMessage' => 'conf'
			]
		];
		$cookedTests = [];
		foreach ( $rawTests as $desc => $test ) {
			$cookedTests[$desc] = [ $test ];
		}
		return $cookedTests;
	}

	private function createMode( $params, $makeLB ) {
		$rom = new ConfiguredReadOnlyMode( $params['confMessage'], $this->createFile( $params ) );

		if ( $makeLB ) {
			$lb = $this->createLBF( $params );
			$rom = new ReadOnlyMode( $rom, $lb );
		}

		return $rom;
	}

	private function createLBF( $params ) {
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getReadOnlyReason' )
			->willReturn( $params['lbMessage'] );
		$lbf = $this->createMock( ILBFactory::class );
		$lbf->method( 'getLoadBalancer' )
			->willReturn( $lb );
		return $lbf;
	}

	private function createFile( $params ) {
		if ( $params['hasFileName'] ) {
			$fileName = $this->fileName;
			if ( $params['fileContents'] === false ) {
				unlink( $this->fileName );
			} else {
				file_put_contents( $this->fileName, $params['fileContents'] );
			}
		} else {
			$fileName = null;
		}
		return $fileName;
	}

	/**
	 * @dataProvider provider
	 */
	public function testWithLB( $params ) {
		$rom = $this->createMode( $params, true );
		$this->assertSame( $params['expectedMessage'], $rom->getReason() );
		$this->assertSame( $params['expectedState'], $rom->isReadOnly() );
	}

	/**
	 * @dataProvider provider
	 */
	public function testWithoutLB( $params ) {
		$cro = $this->createMode( $params, false );
		$this->assertSame( $params['expectedConfMessage'], $cro->getReason() );
		$this->assertSame( $params['expectedConfState'], $cro->isReadOnly() );
	}

	public function testSetReadOnlyReason() {
		$rom = $this->createMode(
			[
				'confMessage' => 'conf',
				'hasFileName' => false,
				'fileContents' => false,
				'lbMessage' => 'lb'
			],
			true );
		$rom->setReason( 'override' );
		$this->assertSame( 'override', $rom->getReason() );
	}
}
