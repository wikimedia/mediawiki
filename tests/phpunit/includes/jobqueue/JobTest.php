<?php

/**
 * @author Addshore
 */
class JobTest extends MediaWikiTestCase {

	/**
	 * @covers Job::toString
	 */
	public function testToString() {
	}

	/**
	 * @dataProvider provideTestJobFactory
	 *
	 * @param mixed $handler
	 *
	 * @covers Job::factory
	 */
	public function testJobFactory( $handler ) {
		$this->mergeMWGlobalArrayValue( 'wgJobClasses', [ 'testdummy' => $handler ] );

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
