<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageNl.php */
class LanguageNlTest extends LanguageClassesTestCase {

	function testFormatNum() {
		$this->assertEquals( '1.234.567', self::getLang()->formatNum( '1234567' ) );
		$this->assertEquals( '12.345', self::getLang()->formatNum( '12345' ) );
		$this->assertEquals( '1', self::getLang()->formatNum( '1' ) );
		$this->assertEquals( '123', self::getLang()->formatNum( '123' ) );
		$this->assertEquals( '1.234', self::getLang()->formatNum( '1234' ) );
		$this->assertEquals( '12.345,56', self::getLang()->formatNum( '12345.56' ) );
		$this->assertEquals( ',1234556', self::getLang()->formatNum( '.1234556' ) );
	}
}
