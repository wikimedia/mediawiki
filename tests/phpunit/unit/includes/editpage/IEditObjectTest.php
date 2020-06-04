<?php

use MediaWiki\EditPage\IEditObject;

/**
 * @covers EditPage
 *
 * MediaWikiCoversValidator fails when trying to say that this covers an interface,
 * but this covers \MediaWiki\EditPage\IEditObject primarily
 *
 * @author DannyS712
 */
class IEditObjectTest extends MediaWikiUnitTestCase {

	public function testConstants() {
		// Ensure that each of the constants used as a status is unique
		$reflection = new ReflectionClass( IEditObject::class );
		$constants = $reflection->getConstants();

		// Keys (constant names) are required to be unique by php, only need to
		// test the values
		$values = array_values( $constants );
		$uniqueValues = array_unique( $values );
		$this->assertArrayEquals(
			$values,
			$uniqueValues,
			'All status constants have unique values'
		);

		// Make sure that any old reference to EditPage::AS_* still works
		foreach ( $constants as $key => $value ) {
			$this->assertSame(
				constant( EditPage::class . '::' . $key ),
				$value,
				"EditPage::$key still works properly"
			);
		}
	}
}
