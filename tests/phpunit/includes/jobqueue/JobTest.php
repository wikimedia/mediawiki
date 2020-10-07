<?php

/**
 * @author Addshore
 */
class JobTest extends MediaWikiIntegrationTestCase {

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
				$this->getMockJob( [ 'key' => 'val' ] ),
				'someCommand Special: key=val ' . $requestId
			],
			[
				$this->getMockJob( [ 'key' => [ 'inkey' => 'inval' ] ] ),
				'someCommand Special: key={"inkey":"inval"} ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1' ] ),
				'someCommand Special: 0=val1 ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1', 'val2' ] ),
				'someCommand Special: 0=val1 1=val2 ' . $requestId
			],
			[
				$this->getMockJob( [ (object)[] ] ),
				'someCommand Special: 0=object(stdClass) ' . $requestId
			],
			[
				$this->getMockJob( [ $mockToStringObj ] ),
				'someCommand Special: 0={STRING_OBJ_VAL} ' . $requestId
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
				'someCommand Special: pages={"932737":[0,"Robert_James_Waller"]} ' .
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
			[ 'someCommand', $params ],
			'SomeJob'
		);

		return $mock;
	}

	/**
	 * @covers Job::__construct()
	 */
	public function testInvalidParamsArgument() {
		$params = false;
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( '$params must be an array' );
		$job = $this->getMockJob( $params );
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
				return Job::factory( 'null', $title, $params );
			} ],
			'function' => [ [ $this, 'newNullJob' ] ],
			'static function' => [ self::class . '::staticNullJob' ]
		];
	}

	public function newNullJob( Title $title, array $params ) {
		return Job::factory( 'null', $title, $params );
	}

	public static function staticNullJob( Title $title, array $params ) {
		return Job::factory( 'null', $title, $params );
	}

	/**
	 * @covers Job::factory
	 * @covers Job::__construct()
	 */
	public function testJobSignatureGeneric() {
		$testPage = Title::makeTitle( NS_PROJECT, 'x' );
		$blankTitle = Title::makeTitle( NS_SPECIAL, '' );
		$params = [ 'z' => 1, 'lives' => 1, 'usleep' => 0 ];
		$paramsWithTitle = $params + [ 'namespace' => NS_PROJECT, 'title' => 'x' ];

		$job = new NullJob( [ 'namespace' => NS_PROJECT, 'title' => 'x' ] + $params );
		$this->assertEquals( $testPage->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $paramsWithTitle );

		$job = Job::factory( 'null', $testPage, $params );
		$this->assertEquals( $blankTitle->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $params );

		$job = Job::factory( 'null', $paramsWithTitle );
		$this->assertEquals( $testPage->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $paramsWithTitle );

		$job = Job::factory( 'null', $params );
		$this->assertTrue( $blankTitle->equals( $job->getTitle() ) );
		$this->assertJobParamsMatch( $job, $params );
	}

	/**
	 * @covers Job::factory
	 * @covers Job::__construct()
	 */
	public function testJobSignatureTitleBased() {
		$testPage = Title::makeTitle( NS_PROJECT, 'x' );
		$blankPage = Title::makeTitle( NS_SPECIAL, 'Blankpage' );
		$params = [ 'z' => 1, 'causeAction' => 'unknown', 'causeAgent' => 'unknown' ];
		$paramsWithTitle = $params + [ 'namespace' => NS_PROJECT, 'title' => 'x' ];
		$paramsWithBlankpage = $params + [ 'namespace' => NS_SPECIAL, 'title' => 'Blankpage' ];

		$job = new RefreshLinksJob( $testPage, $params );
		$this->assertEquals( $testPage->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertTrue( $testPage->equals( $job->getTitle() ) );
		$this->assertJobParamsMatch( $job, $paramsWithTitle );

		$job = Job::factory( 'refreshLinks', $testPage, $params );
		$this->assertEquals( $testPage->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $paramsWithTitle );

		$job = Job::factory( 'refreshLinks', $paramsWithTitle );
		$this->assertEquals( $testPage->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $paramsWithTitle );

		$job = Job::factory( 'refreshLinks', $params );
		$this->assertTrue( $blankPage->equals( $job->getTitle() ) );
		$this->assertJobParamsMatch( $job, $paramsWithBlankpage );
	}

	/**
	 * @covers Job::factory
	 * @covers Job::__construct()
	 */
	public function testJobSignatureTitleBasedIncomplete() {
		$testPage = Title::makeTitle( NS_PROJECT, 'x' );
		$blankTitle = Title::makeTitle( NS_SPECIAL, '' );
		$params = [ 'z' => 1, 'causeAction' => 'unknown', 'causeAgent' => 'unknown' ];

		$job = new RefreshLinksJob( $testPage, $params + [ 'namespace' => 0 ] );
		$this->assertEquals( $blankTitle->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $params + [ 'namespace' => 0 ] );

		$job = new RefreshLinksJob( $testPage, $params + [ 'title' => 'x' ] );
		$this->assertEquals( $blankTitle->getPrefixedText(), $job->getTitle()->getPrefixedText() );
		$this->assertJobParamsMatch( $job, $params + [ 'title' => 'x' ] );
	}

	private function assertJobParamsMatch( IJobSpecification $job, array $params ) {
		$actual = $job->getParams();
		unset( $actual['requestId'] );

		$this->assertEquals( $actual, $params );
	}
}
