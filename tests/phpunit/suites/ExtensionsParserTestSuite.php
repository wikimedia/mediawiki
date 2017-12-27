<?php
class ExtensionsParserTestSuite extends PHPUnit\Framework\TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::NO_CORE );
	}

}
