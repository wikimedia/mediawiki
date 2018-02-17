<?php

/**
 * @covers ClassCollector
 */
class ClassCollectorTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideCases() {
		return [
			[
				"class Foo {}",
				[ 'Foo' ],
			],
			[
				"namespace Example;\nclass Foo {}\nclass Bar {}",
				[ 'Example\Foo', 'Example\Bar' ],
			],
			[
				"class_alias( 'Foo', 'Bar' );",
				[ 'Bar' ],
			],
			[
				"namespace Example;\nclass Foo {}\nclass_alias( 'Example\Foo', 'Foo' );",
				[ 'Example\Foo', 'Foo' ],
			],
			[
				"namespace Example;\nclass Foo {}\nclass_alias( 'Example\Foo', 'Bar' );",
				[ 'Example\Foo', 'Bar' ],
			],
			[
				"class_alias( Foo::class, 'Bar' );",
				[ 'Bar' ],
			],
			[
				// Namespaced class is not currently supported. Must use namespace declaration
				// earlier in the file.
				"class_alias( Example\Foo::class, 'Bar' );",
				[],
			],
			[
				"namespace Example;\nclass Foo {}\nclass_alias( Foo::class, 'Bar' );",
				[ 'Example\Foo', 'Bar' ],
			],
		];
	}

	/**
	 * @dataProvider provideCases
	 */
	public function testGetClasses( $code, array $classes, $message = null ) {
		$cc = new ClassCollector();
		$this->assertEquals( $classes, $cc->getClasses( "<?php\n$code" ), $message );
	}
}
