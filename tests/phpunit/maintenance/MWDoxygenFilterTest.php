<?php

/**
 * @covers MWDoxygenFilter
 */
class MWDoxygenFilterTest extends \PHPUnit\Framework\TestCase {

	public static function provideFilter() {
		yield 'No @var' => [
			<<<'CODE'
<?php class MyClass {
	/** Some Words here */
	protected $name;
}
CODE
		];

		yield 'One-line var with type' => [
			<<<'CODE'
<?php class MyClass {
	/** @var SomeType */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**  */
	protected SomeType $name;
}
CODE
		];

		yield 'One-line var with type and description' => [
			<<<'CODE'
<?php class MyClass {
	/** @var SomeType Some description */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**  Some description */
	protected SomeType $name;
}
CODE
		];

		yield 'One-line var with type and description that starts like a variable name' => [
			<<<'CODE'
<?php class MyClass {
	/** @var array $_GET data from some thing */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**  $_GET data from some thing */
	protected array $name;
}
CODE
		];

		yield 'One-line var with type, name, and description' => [
			// In this full form, Doxygen understands it just fine.
			// No changes made.
			<<<'CODE'
<?php class MyClass {
	/** @var SomeType $name Some description */
	protected $name;
}
CODE
		];

		yield 'Multi-line var with type' => [
			<<<'CODE'
<?php class MyClass {
	/**
	 * @var SomeType
	 */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**
	 * 
	 */
	protected SomeType $name;
}
CODE
		];

		yield 'Multi-line var with type and description' => [
			<<<'CODE'
<?php class MyClass {
	/**
	 * Some description
	 * @var SomeType
	 */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**
	 * Some description
	 * 
	 */
	protected SomeType $name;
}
CODE
		];

		yield 'Multi-line var with type, name, and description' => [
			<<<'CODE'
<?php class MyClass {
	/**
	 * Some description
	 * @var SomeType $name
	 */
	protected $name;
}
CODE
			, <<<'CODE'
<?php class MyClass {
	/**
	 * Some description
	 * @var SomeType $name
	 */
	protected $name;
}
CODE
		];
	}

	/**
	 * @dataProvider provideFilter
	 */
	public function testFilter( $source, $expected = null ) {
		if ( $expected === null ) {
			$expected = $source;
		}
		$this->assertSame( $expected, MWDoxygenFilter::filter( $source ), 'Source code' );
	}
}
