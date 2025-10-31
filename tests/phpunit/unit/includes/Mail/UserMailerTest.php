<?php

namespace MediaWiki\Tests\Unit\Mail;

use MediaWiki\Mail\UserMailer;
use MediaWikiUnitTestCase;

/**
 * @group Mail
 * @covers \MediaWiki\Mail\UserMailer
 */
class UserMailerTest extends MediaWikiUnitTestCase {

	public function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			UserMailer::quotedPrintable( "\xc4\x88u legebla?", "UTF-8" )
		);

		$this->assertEquals(
			"=?UTF-8?Q?F=C3=B6o=2EBar?=",
			UserMailer::quotedPrintable( "Föo.Bar", "UTF-8" )
		);

		$this->assertEquals(
			"Foo.Bar",
			UserMailer::quotedPrintable( "Foo.Bar", "UTF-8" )
		);
	}
}
