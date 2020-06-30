<?php

/**
 * @group Database
 *
 * @covers ReadOnlyMode
 * @covers ConfiguredReadOnlyMode
 */
class ReadOnlyModeTest extends MediaWikiIntegrationTestCase {
	public function provider() {
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
			$lb = $this->createLB( $params );
			$rom = new ReadOnlyMode( $rom, $lb );
		}

		return $rom;
	}

	private function createLB( $params ) {
		$lb = $this->getMockBuilder( \Wikimedia\Rdbms\LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$lb->expects( $this->any() )->method( 'getReadOnlyReason' )
			->willReturn( $params['lbMessage'] );
		return $lb;
	}

	private function createFile( $params ) {
		if ( $params['hasFileName'] ) {
			$fileName = $this->getNewTempFile();

			if ( $params['fileContents'] === false ) {
				unlink( $fileName );
			} else {
				file_put_contents( $fileName, $params['fileContents'] );
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
