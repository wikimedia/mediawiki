<?php
class ExtensionsParserTestSuite extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		return MediaWikiParserTest::suite( MediaWikiParserTest::NO_CORE );
	}

}
