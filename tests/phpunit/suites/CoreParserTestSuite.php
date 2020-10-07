<?php

use PHPUnit\Framework\TestSuite;

class CoreParserTestSuite extends TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::CORE_ONLY );
	}

}
