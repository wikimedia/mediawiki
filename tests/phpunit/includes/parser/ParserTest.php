<?php

/**
 * @covers Parser::__construct
 */
class ParserTest extends MediaWikiIntegrationTestCase {
	public function provideConstructorArguments() {
		// Create a mock Config object that will satisfy ServiceOptions::__construct
		$mockConfig = $this->createMock( 'Config' );
		$mockConfig->method( 'has' )->willReturn( true );
		$mockConfig->method( 'get' )->willReturn( 'I like otters.' );

		// Stub out a MagicWordFactory so the Parser can initialize its
		// function hooks when it is created.
		$mwFactory = $this->getMockBuilder( MagicWordFactory::class )
			->disableOriginalConstructor()
			->setMethods( [ 'get', 'getVariableIDs' ] )
			->getMock();
		$mwFactory
			->method( 'get' )->will( $this->returnCallback( function ( $arg ) {
				$mw = $this->getMockBuilder( MagicWord::class )
					->disableOriginalConstructor()
					->setMethods( [ 'getSynonyms' ] )
					->getMock();
				$mw->method( 'getSynonyms' )->willReturn( [] );
				return $mw;
			} ) );
		$mwFactory
			->method( 'getVariableIDs' )->willReturn( [] );

		$newArgs = [
			$this->createMock( 'MediaWiki\Config\ServiceOptions' ),
			$mwFactory,
			$this->createMock( 'Language' ),
			$this->createMock( 'ParserFactory' ),
			'a snail can sleep for three years',
			$this->createMock( 'MediaWiki\Special\SpecialPageFactory' ),
			$this->createMock( 'MediaWiki\Linker\LinkRendererFactory' ),
			$this->createMock( 'NamespaceInfo' )
		];

		$oldArgs = [
			[],
			$mwFactory,
			$this->createMock( 'Language' ),
			$this->createMock( 'ParserFactory' ),
			'a snail can sleep for three years',
			$this->createMock( 'MediaWiki\Special\SpecialPageFactory' )
		];

		yield 'current_args_without_namespace_info' => [
			$newArgs,
		];

		yield 'backward_compatible_args_minimal' => [
			array_merge( $oldArgs ),
		];

		yield 'backward_compatible_args_with_config' => [
			array_merge( $oldArgs, [ $mockConfig ] ),
		];

		yield 'backward_compatible_args_with_link_renderer' => [
			array_merge( $oldArgs, [
				$mockConfig,
				$this->createMock( 'MediaWiki\Linker\LinkRendererFactory' )
			] ),
		];

		yield 'backward_compatible_args_with_ns_info' => [
			array_merge( $oldArgs, [
				$mockConfig,
				$this->createMock( 'MediaWiki\Linker\LinkRendererFactory' ),
				$this->createMock( 'NamespaceInfo' )
			] ),
		];
	}

	/**
	 * @dataProvider provideConstructorArguments
	 * @covers Parser::__construct
	 */
	public function testBackwardsCompatibleConstructorArguments( $args ) {
		$this->hideDeprecated( 'Parser::__construct' );
		$this->hideDeprecated( 'old calling convention for Parser::__construct' );
		$parser = new Parser( ...$args );

		$refObject = new ReflectionObject( $parser );

		// If testing backwards compatibility, test service options separately
		if ( is_array( $args[0] ) ) {
			$svcOptionsProp = $refObject->getProperty( 'svcOptions' );
			$svcOptionsProp->setAccessible( true );
			$this->assertInstanceOf(
				MediaWiki\Config\ServiceOptions::class,
				$svcOptionsProp->getValue( $parser )
			);
			unset( $args[0] );

			// If a Config is passed, the fact that we were able to create a ServiceOptions
			// instance without error from it proves that this argument works.
			if ( isset( $args[6] ) ) {
				unset( $args[6] );
			}
		}

		foreach ( $refObject->getProperties() as $prop ) {
			$prop->setAccessible( true );
			foreach ( $args as $idx => $mockTest ) {
				if ( $prop->getValue( $parser ) === $mockTest ) {
					unset( $args[$idx] );
				}
			}
		}

		$this->assertSame( [], $args, 'Not all arguments to the Parser constructor were ' .
			'found on the Parser object' );
	}
}
