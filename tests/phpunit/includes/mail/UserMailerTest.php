<?php

class UserMailerTest extends MediaWikiLangTestCase {

	/**
	 * @covers UserMailer::quotedPrintable
	 */
	public function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			UserMailer::quotedPrintable( "\xc4\x88u legebla?", "UTF-8" ) );
	}

}
