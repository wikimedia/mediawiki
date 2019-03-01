<?php

/**
 * @author Addshore
 */
class JobTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideTestToString
	 *
	 * @param Job $job
	 * @param string $expected
	 *
	 * @covers Job::toString
	 */
	public function testToString( $job, $expected ) {
		$this->assertEquals( $expected, $job->toString() );
	}

	public function provideTestToString() {
		$mockToStringObj = $this->getMockBuilder( stdClass::class )
			->setMethods( [ '__toString' ] )->getMock();
		$mockToStringObj->expects( $this->any() )
			->method( '__toString' )
			->will( $this->returnValue( '{STRING_OBJ_VAL}' ) );

		$requestId = 'requestId=' . WebRequest::getRequestId();

		return [
			[
				$this->getMockJob( false ),
				'someCommand  ' . $requestId
			],
			[
				$this->getMockJob( [ 'key' => 'val' ] ),
				'someCommand  key=val ' . $requestId
			],
			[
				$this->getMockJob( [ 'key' => [ 'inkey' => 'inval' ] ] ),
				'someCommand  key={"inkey":"inval"} ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1' ] ),
				'someCommand  0=val1 ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1', 'val2' ] ),
				'someCommand  0=val1 1=val2 ' . $requestId
			],
			[
				$this->getMockJob( [ new stdClass() ] ),
				'someCommand  0=object(stdClass) ' . $requestId
			],
			[
				$this->getMockJob( [ $mockToStringObj ] ),
				'someCommand  0={STRING_OBJ_VAL} ' . $requestId
			],
			[
				$this->getMockJob( [
					"pages" => [
						"932737" => [
							0,
							"Robert_James_Waller"
						]
					],
					"rootJobSignature" => "45868e99bba89064e4483743ebb9b682ef95c1a7",
					"rootJobTimestamp" => "20160309110158",
					"masterPos" => [
						"file" => "db1023-bin.001288",
						"pos" => "308257743",
						"asOfTime" => 1457521464.3814
					],
					"triggeredRecursive" => true
				] ),
				'someCommand  pages={"932737":[0,"Robert_James_Waller"]} ' .
				'rootJobSignature=45868e99bba89064e4483743ebb9b682ef95c1a7 ' .
				'rootJobTimestamp=20160309110158 masterPos=' .
				'{"file":"db1023-bin.001288","pos":"308257743","asOfTime":' .
				// Embed dynamically because TestSetup sets serialize_precision=17
				// which, in PHP 7.1 and 7.2, produces 1457521464.3814001 instead
				json_encode( 1457521464.3814 ) . '} triggeredRecursive=1 ' .
				$requestId
			],
		];
	}

	public function getMockJob( $params ) {
		$mock = $this->getMockForAbstractClass(
			Job::class,
			[ 'someCommand', new Title(), $params ],
			'SomeJob'
		);
		return $mock;
	}

	/**
	 * @dataProvider provideTestJobFactory
	 *
	 * @param mixed $handler
	 *
	 * @covers Job::factory
	 */
	public function testJobFactory( $handler ) {
		$this->mergeMwGlobalArrayValue( 'wgJobClasses', [ 'testdummy' => $handler ] );

		$job = Job::factory( 'testdummy', Title::newMainPage(), [] );
		$this->assertInstanceOf( NullJob::class, $job );

		$job2 = Job::factory( 'testdummy', Title::newMainPage(), [] );
		$this->assertInstanceOf( NullJob::class, $job2 );
		$this->assertNotSame( $job, $job2, 'should not reuse instance' );
	}

	public function provideTestJobFactory() {
		return [
			'class name' => [ 'NullJob' ],
			'closure' => [ function ( Title $title, array $params ) {
				return new NullJob( $title, $params );
			} ],
			'function' => [ [ $this, 'newNullJob' ] ],
			'static function' => [ self::class . '::staticNullJob' ]
		];
	}

	public function newNullJob( Title $title, array $params ) {
		return new NullJob( $title, $params );
	}

	public static function staticNullJob( Title $title, array $params ) {
		return new NullJob( $title, $params );
	}

}
