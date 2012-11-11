<?php
/**
 * @author Srikanth L
 * @copyright Copyright © 2012, Srikanth L
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageTa.php */
class LanguageTaTest extends LanguageClassesTestCase {


	/** @dataProvider providerGrammar */
	function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	// The comments in the beginning of the line help avoid RTL problems
	// with text editors.
	function providerGrammar() {
	return array (
		array(
			/* result */ 'விக்கிப்பீடியாவை', //wikipediavai
			/* word   */ 'விக்கிப்பீடியா',
			/* case   */ 'ISuffix',
		),
		array(
			/* result */ 'தாமரையை', //thamaraiyai
			/* word   */ 'தாமரை',
			/* case   */ 'ISuffix',
		),
		array(
			/* result */ 'காற்றை',
			/* word   */ 'காற்று',
			/* case   */ 'ISuffix',
		)
		);
	}
}
