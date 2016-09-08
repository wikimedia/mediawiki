<?php
class ExtensionsParserTestSuite extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::NO_CORE );
	}

}
