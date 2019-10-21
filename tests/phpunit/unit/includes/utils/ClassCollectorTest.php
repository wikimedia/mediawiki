<?php

/**
 * @covers ClassCollector
 */
class ClassCollectorTest extends MediaWikiUnitTestCase {

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
				// Support a multiline 'class' statement
				"namespace Example;\nclass Foo extends\n\tFooBase {\n\t"
						. "public function x() {}\n}\nclass_alias( 'Example\Foo', 'Bar' );",
				[ 'Example\Foo', 'Bar' ],
			],
			[
				"class_alias( Foo::class, 'Bar' );",
				[ 'Bar' ],
			],
			[
				// Support nested class_alias() calls
					"if ( false ) {\n\tclass_alias( Foo::class, 'Bar' );\n}",
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
			[
				"new class() extends Foo {}",
				[]
			]
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
