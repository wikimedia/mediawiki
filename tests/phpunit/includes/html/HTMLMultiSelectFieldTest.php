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

	function providerForUsecheckboxes() {
		// Constants to make test cases easier to read
		$CHECKS    = true;
		$NO_CHECKS = false;

		return array(
			# Format:
			#  - number of options
			#  - usecheckboxes' option
			#  - expected: whether checkboxes should be used
			#  - [message]: optional message

			# 'usecheckboxes' set to well known values
			array( 5, true,  $CHECKS    ),
			array( 5, null,  $CHECKS    ),
			array( 5, false, $NO_CHECKS ),

			# 'usecheckboxes' set to a numeric value
			array( 5, 5,     $CHECKS    ),
			array( 5, 4,     $NO_CHECKS ),
			array( 5, 3,     $NO_CHECKS ),
			array( 9, 0,     $NO_CHECKS ),

			array( 1, 3,     $CHECKS    ),
		);
	}

	/**
	 * Tests whether the 'usecheckboxes' option is evaluated in the right way
	 * @dataProvider providerForUsecheckboxes
	 */
	public function testUsecheckboxesOption( $nopts, $pval, $expected, $message = '' ) {
		$options = array();
		for($i=0;$i<$nopts;$i++) {
			$options[] = chr( 65 + $i);
		}

		$params = array(
			'label-message' => 'test',
			'options' => $options,
			'prefix' => 'test',
			'fieldname' => 'test',
		);
		$params['usecheckboxes'] = $pval;
		$field = new HTMLMultiSelectField( $params );
		$actual = $field->isUsingCheckboxes();
		$this->assertEquals( $expected, $actual, $message );
	}

	function testCreatesABasicMultiSelectField() {
		$s = new HTMLMultiSelectField( array(
			'fieldname' => 'somefield',
			'options' => array(
				1 => 'one',
				2 => 'two',
			),
		) );
		$this->assertEquals(
' <div class="mw-htmlform-flatlist-item"><input name="wpsomefield[]" type="checkbox" value="one" id="mw-input-wpsomefield-one" />&#160;<label for="mw-input-wpsomefield-one">1</label></div> <div class="mw-htmlform-flatlist-item"><input name="wpsomefield[]" type="checkbox" value="two" id="mw-input-wpsomefield-two" />&#160;<label for="mw-input-wpsomefield-two">2</label></div>'
			, $s->getInputHTML( array() )
		);
	}

	function testCreatesMultiSelectFieldWithNoCheckboxes() {
		$s = new HTMLMultiSelectField( array(
			'fieldname' => 'somefield',
			'usecheckboxes' => false,
			'options' => array(
				1 => 'one',
				2 => 'two',
			),
		) );
		$this->assertEquals(
'<select multiple="" size="2" name="wpsomefield[]"><option id="mw-input-wpsomefield-one" value="one" class="mw-htmlform-flatlist-item">1</option><option id="mw-input-wpsomefield-two" value="two" class="mw-htmlform-flatlist-item">2</option></select>'
			, $s->getInputHTML( array() )
		);
	}


}
