<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageNl.php */
class LanguageNlTest extends LanguageClassesTestCase {

	/**
	 * @covers Language::formatNum
	 * @todo split into a test and a dataprovider
	 */
	public function testFormatNum() {
		$this->assertEquals( '1.234.567', $this->getLang()->formatNum( '1234567' ) );
		$this->assertEquals( '12.345', $this->getLang()->formatNum( '12345' ) );
		$this->assertEquals( '1', $this->getLang()->formatNum( '1' ) );
		$this->assertEquals( '123', $this->getLang()->formatNum( '123' ) );
		$this->assertEquals( '1.234', $this->getLang()->formatNum( '1234' ) );
		$this->assertEquals( '12.345,56', $this->getLang()->formatNum( '12345.56' ) );
		$this->assertEquals( ',1234556', $this->getLang()->formatNum( '.1234556' ) );
	}
}
