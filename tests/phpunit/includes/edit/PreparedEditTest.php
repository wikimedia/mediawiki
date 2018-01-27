<?php

namespace MediaWiki\Tests\EditPage;

use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Storage\MutableRevisionSlots;
use ParserOptions;
use ParserOutput;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use TitleValue;
use User;
use WikitextContent;

/**
 * @covers \MediaWiki\Edit\PreparedEdit
 */
class PreparedEditTest extends PHPUnit_Framework_TestCase {

	public function testConstruction() {
		$title = new TitleValue( NS_MAIN, __METHOD__ );

		$newContent = new WikitextContent( 'Content' );
		$pstContent = new WikitextContent( 'Transformed Content' );

		$newContentSlots = new MutableRevisionSlots();
		$newContentSlots->setContent( 'main', $newContent );

		$transformedContentSlots = new MutableRevisionSlots();
		$transformedContentSlots->setContent( 'main', $pstContent );

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();

		$user->method( 'getName' )
			->willReturn( 'Frank' );

		$popts = new ParserOptions();
		$outputs = [ 'main' => new ParserOutput( 'Hello World' ) ];
		$combinedOutput = new ParserOutput( 'Hello Combined World' );

		$edit = new PreparedEdit(
			$title,
			$newContentSlots,
			$transformedContentSlots,
			$user,
			$popts,
			$outputs,
			$combinedOutput
		);

		$this->assertSame(
			$transformedContentSlots,
			$edit->getTransformedContentSlots(),
			'getTransformedContentSlots()'
		);

		$this->assertSame(
			$popts,
			$edit->getParserOptions(),
			'getParserOptions()'
		);

		$this->assertSame(
			$outputs['main'],
			$edit->getParserOutput( 'main' ),
			'getParserOutput()'
		);

		$this->assertSame(
			[ 'main' ],
			$edit->getSlotRoles(),
			'getSlotRoles()'
		);

		$this->assertSame(
			$combinedOutput,
			$edit->getCombinedParserOutput(),
			'getParserOutput()'
		);

		$this->assertSame(
			0,
			$edit->getRevisionId(),
			'getRevisionId()'
		);

		// Deprecated fields for compat:
		// NOTE: the format, timestamp, and oldContent fields are unused and always null.
		$this->assertTrue( $newContent->equals( $edit->newContent ), "newContent field" );
		$this->assertTrue( $pstContent->equals( $edit->pstContent ), "pstContent field" );
		$this->assertTrue( $pstContent->equals( $edit->pstContent ), "pstContent field" );
		$this->assertSame( $combinedOutput, $edit->output, "output field" );
		$this->assertSame( $popts, $edit->popts, "popts field" );
		$this->assertSame( 0, $edit->revid, "revid field" );

		// $variations impacts signature
		$edit2 = new PreparedEdit(
			$title,
			$newContentSlots,
			$transformedContentSlots,
			$user,
			$popts,
			$outputs,
			$combinedOutput,
			23
		);

		$this->assertNotEquals( $edit->getSignature(), $edit2->getSignature(), 'getSignature()' );

		$this->assertSame( 23, $edit2->getRevisionId(), 'getRevisionId()' );
		$this->assertSame( 23, $edit2->revid, "revid field" );
	}

	public function testMakeSignature() {
		$title = new TitleValue( NS_MAIN, __METHOD__ );
		$title2 = new TitleValue( NS_MAIN, __METHOD__ . '_other' );

		$newContent = new WikitextContent( 'Content One' );
		$newContent2 = new WikitextContent( 'Content Two' );

		$slots = new MutableRevisionSlots();
		$slots->setContent( 'main', $newContent );

		$slots2 = new MutableRevisionSlots();
		$slots2->setContent( 'main', $newContent2 );

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();

		$user->method( 'getName' )
			->willReturn( 'Jenny' );

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user2 */
		$user2 = $this->getMockBuilder( User::class )
			->disableOriginalConstructor()
			->getMock();

		$user2->method( 'getName' )
			->willReturn( 'Frank' );

		$sig = PreparedEdit::makeSignature( $title, $slots, $user, 0 );

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title2, $slots, $user, 0 ),
			'$title'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots2, $user, 0 ),
			'$newContentSlots'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots, $user2, 0 ),
			'$user'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots, $user, 7 ),
			'$revid'
		);
	}

}
