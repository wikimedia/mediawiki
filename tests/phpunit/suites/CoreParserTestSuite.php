<?php

class CoreParserTestSuite extends PHPUnit\Framework\TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::CORE_ONLY );
	}

}
