<?php

/**
 * @covers Parser::__construct
 */
class ParserTest extends MediaWikiTestCase {
	public function provideConstructorArguments() {
		// Create a mock Config object that will satisfy ServiceOptions::__construct
		$mockConfig = $this->createMock( 'Config' );
		$mockConfig->method( 'has' )->willReturn( true );
		$mockConfig->method( 'get' )->willReturn( 'I like otters.' );

		$newArgs = [
			$this->createMock( 'MediaWiki\Config\ServiceOptions' ),
			$this->createMock( 'MagicWordFactory' ),
			$this->createMock( 'Language' ),
			$this->createMock( 'ParserFactory' ),
			'a snail can sleep for three years',
			$this->createMock( 'MediaWiki\Special\SpecialPageFactory' ),
			$this->createMock( 'MediaWiki\Linker\LinkRendererFactory' ),
			$this->createMock( 'NamespaceInfo' )
		];

		$oldArgs = [
			[],
			$this->createMock( 'MagicWordFactory' ),
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
		$parser = new Parser( ...$args );

		$refObject = new ReflectionObject( $parser );

		// If testing backwards compatibility, test service options separately
		if ( is_array( $args[0] ) ) {
			$svcOptionsProp = $refObject->getProperty( 'svcOptions' );
			$svcOptionsProp->setAccessible( true );
			$this->assertType( 'MediaWiki\Config\ServiceOptions',
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

		$this->assertCount( 0, $args, 'Not all arguments to the Parser constructor were ' .
			'found on the Parser object' );
	}
}
