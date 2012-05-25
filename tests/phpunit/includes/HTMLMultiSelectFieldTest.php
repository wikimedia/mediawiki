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
	function setUp() {
		parent::setUp();
		$this->params = array(
			'label-message' => 'test',
			'options' => array( 'a', 'b', 'c', 'd' ),
			'prefix' => 'test',
			'fieldname' => 'test',
		);
	}

	function tearDown() {
		unset( $this->params );
		parent::tearDown();
	}

	protected function assertParamValueResultsIn( $pval, $expected ) {
		$this->params['usecheckboxes'] = $pval;
		$field = new HTMLMultiSelectField( $this->params );
		$actual = $field->isUsingCheckboxes();
		$this->assertEquals( $expected, $actual );
		unset( $this->params['usecheckboxes'] );
	}

	/**
	 * Tests whether the 'usecheckboxes' option is evaluated in the right way
	 */
	public function testUseCheckboxes() {
		$this->assertParamValueResultsIn( true, true );
		$this->assertParamValueResultsIn( false, false );
		$this->assertParamValueResultsIn( 5, true );
		$this->assertParamValueResultsIn( 4, true );
		$this->assertParamValueResultsIn( 3, false );
	}
}
