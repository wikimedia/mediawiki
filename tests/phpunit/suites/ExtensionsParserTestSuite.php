<?php

use PHPUnit\Framework\TestSuite;

class ExtensionsParserTestSuite extends TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::NO_CORE );
	}

}
