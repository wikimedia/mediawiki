<?php

class CoreParserTestSuite extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		return ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::CORE_ONLY );
	}

}

