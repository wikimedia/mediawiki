<?php

/**
 * Tests for the HTMLMultiSelectFieldTest class.
 *
 * @file
 * @since 1.20
 *
 * @ingroup Test
 *
 * @author Daniel Werner
 */
class HTMLMultiSelectFieldTest extends MediaWikiTestCase {
	/**
	 * Tests whether the 'usecheckboxes' option is evaluated in the right way
	 */
	public function testUseCheckboxes() {
		$params = array(
			'usecheckboxes' => true,
			'label-message' => 'test',
			'options' => array( 'a', 'b', 'c', 'd' ),
			'prefix' => 'test',
			'fieldname' => 'test',
		);

		$params['usecheckboxes'] = true;
		$field = new HTMLMultiSelectField( $params );
		$this->assertTrue( $field->isUsingCheckboxes() );

		$params['usecheckboxes'] = false;
		$field = new HTMLMultiSelectField( $params );
		$this->assertFalse( $field->isUsingCheckboxes() );

		$params['usecheckboxes'] = 5;
		$field = new HTMLMultiSelectField( $params );
		$this->assertTrue( $field->isUsingCheckboxes() );

		$params['usecheckboxes'] = 4;
		$field = new HTMLMultiSelectField( $params );
		$this->assertTrue( $field->isUsingCheckboxes() );

		$params['usecheckboxes'] = 3;
		$field = new HTMLMultiSelectField( $params );
		$this->assertFalse( $field->isUsingCheckboxes() );
	}
}
