<?php

/**
 * @covers ParserFactory
 */
class ParserFactoryIntegrationTest extends MediaWikiIntegrationTestCase {
	public function provideConstructorArguments() {
		// Create a mock Config object that will satisfy ServiceOptions::__construct
		$mockConfig = $this->createMock( 'Config' );
		$mockConfig->method( 'has' )->willReturn( true );
		$mockConfig->method( 'get' )->willReturn( 'I like otters.' );

		$mocks = [
			[ 'the plural of platypus...' ],
			$this->createMock( 'MagicWordFactory' ),
			$this->createMock( 'Language' ),
			'...is platypodes',
			$this->createMock( 'MediaWiki\Special\SpecialPageFactory' ),
			$mockConfig,
			$this->createMock( 'MediaWiki\Linker\LinkRendererFactory' ),
		];

		yield 'args_without_namespace_info' => [
			$mocks,
		];
		yield 'args_with_namespace_info' => [
			array_merge( $mocks, [ $this->createMock( 'NamespaceInfo' ) ] ),
		];
	}

	/**
	 * @dataProvider provideConstructorArguments
	 * @covers ParserFactory::__construct
	 */
	public function testBackwardsCompatibleConstructorArguments( $args ) {
		$this->hideDeprecated( 'ParserFactory::__construct with Config parameter' );
		$factory = new ParserFactory( ...$args );
		$parser = $factory->create();

		// It is expected that these are not present on the parser.
		unset( $args[5] );
		unset( $args[0] );

		foreach ( ( new ReflectionObject( $parser ) )->getProperties() as $prop ) {
			$prop->setAccessible( true );
			foreach ( $args as $idx => $mockTest ) {
				if ( $prop->getValue( $parser ) === $mockTest ) {
					unset( $args[$idx] );
				}
			}
		}

		$this->assertCount( 0, $args, 'Not all arguments to the ParserFactory constructor were ' .
			'found in Parser member variables' );
	}
}
