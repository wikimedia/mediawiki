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
	function provider() {
		return array(
			array( true, true ),
			array( false, false ),
			array( 5, true ),
			array( 4, true ),
			array( 3, false )
		);
	}

	/**
	 * Tests whether the 'usecheckboxes' option is evaluated in the right way
	 * @dataProvider provider
	 */
	public function testUseCheckboxes( $pval, $expected ) {
		$params = array(
			'label-message' => 'test',
			'options' => array( 'a', 'b', 'c', 'd' ),
			'prefix' => 'test',
			'fieldname' => 'test',
		);
		$params['usecheckboxes'] = $pval;
		$field = new HTMLMultiSelectField( $params );
		$actual = $field->isUsingCheckboxes();
		$this->assertEquals( $expected, $actual );
	}
}
