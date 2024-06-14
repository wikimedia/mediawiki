<?php

use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;

/**
 * @group ContentHandler
 * @covers \MessageContent
 */
class MessageContentTest extends MediaWikiLangTestCase {

	public function testGetWikitext() {
		$msg = new Message( 'about' );
		$this->hideDeprecated( 'MessageContent' );
		$cnt = new MessageContent( $msg );

		$this->assertSame( $msg->text(), $cnt->getWikitext() );
	}

	public function testGetMessage() {
		$msg = new Message( 'about' );
		$this->hideDeprecated( 'MessageContent' );
		$cnt = new MessageContent( $msg );

		$this->assertEquals( $msg, $cnt->getMessage() );
	}

	public function testGetTextForSummary() {
		$msg = new RawMessage( 'ää' );
		$this->hideDeprecated( 'MessageContent' );
		$cnt = new MessageContent( $msg );

		$this->assertSame( 'ä', $cnt->getTextForSummary( 3 ) );
	}

	public function testSerialize() {
		$msg = new Message( 'about' );
		$this->hideDeprecated( 'MessageContent' );
		$cnt = new MessageContent( $msg );

		$this->assertSame( $msg->plain(), $cnt->serialize() );
	}

	public function testEquals() {
		$this->hideDeprecated( 'MessageContent' );
		$msg1 = new Message( 'about' );
		$cnt1 = new MessageContent( $msg1 );

		$msg2 = new Message( 'about' );
		$cnt2 = new MessageContent( $msg2 );

		$msg3 = new Message( 'faq' );
		$cnt3 = new MessageContent( $msg3 );
		$cnt4 = new WikitextContent( $msg3->plain() );

		$this->assertTrue( $cnt1->equals( $cnt2 ) );
		$this->assertFalse( $cnt1->equals( $cnt3 ) );
		$this->assertFalse( $cnt1->equals( $cnt4 ) );
	}
}
