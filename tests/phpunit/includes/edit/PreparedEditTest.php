<?php

namespace MediaWiki\Tests\EditPage;

use JsonContent;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Storage\MutableRevisionSlots;
use ParserOptions;
use ParserOutput;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Title;
use TitleValue;
use User;
use WikitextContent;

/**
 * @covers \MediaWiki\Edit\PreparedEdit
 * @group Database
 */
class PreparedEditTest extends PHPUnit_Framework_TestCase {

	public function testConstruction() {
		$title = Title::makeTitle( NS_MAIN, __METHOD__ );

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
		$output = new ParserOutput( 'Hello World' );

		$edit = new PreparedEdit(
			$title,
			$newContentSlots,
			$transformedContentSlots,
			$user,
			$popts,
			$output,
			17
		);

		$this->assertSame(
			$title,
			$edit->getTitle(),
			'getTitle()'
		);

		$this->assertSame(
			$transformedContentSlots,
			$edit->getSlots(),
			'getSlots()'
		);

		$this->assertSame(
			$pstContent,
			$edit->getContent( 'main' ),
			'getContent()'
		);

		$this->assertSame(
			$popts,
			$edit->getParserOptions(),
			'getParserOptions()'
		);

		$this->assertSame(
			[ 'main' ],
			$edit->getSlotRoles(),
			'getSlotRoles()'
		);

		$this->assertSame(
			$output,
			$edit->getParserOutput(),
			'getParserOutput()'
		);

		$this->assertSame(
			17,
			$edit->getRevisionId(),
			'getRevisionId()'
		);

		// Deprecated fields for compat:
		// NOTE: the format, timestamp, and oldContent fields are unused and always null.
		$this->assertTrue( $newContent->equals( $edit->newContent ), "newContent field" );
		$this->assertTrue( $pstContent->equals( $edit->pstContent ), "pstContent field" );
		$this->assertTrue( $pstContent->equals( $edit->pstContent ), "pstContent field" );
		$this->assertSame( $output, $edit->output, "output field" );
		$this->assertSame( $popts, $edit->popts, "popts field" );
		$this->assertSame( 17, $edit->revid, "revid field" );
	}

	public function testMakeSignature() {
		$revId = 17;
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

		$sig = PreparedEdit::makeSignature( $title, $slots, $user, $revId );

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title2, $slots, $user, $revId ),
			'$title'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots2, $user, $revId ),
			'$newContentSlots'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots, $user2, $revId ),
			'$user'
		);

		$this->assertNotEquals(
			$sig,
			PreparedEdit::makeSignature( $title, $slots, $user, 7 ),
			'$revId'
		);
	}

	public function testGetSlotParserOutput() {
		$title = Title::makeTitle( NS_MAIN, __METHOD__ );

		$newContent = new JsonContent( '[ "Content" ]' );
		$pstContent = new JsonContent( '[ "Transformed Content" ]' );

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
		$output = new ParserOutput( 'Hello World' );

		$edit = new PreparedEdit(
			$title,
			$newContentSlots,
			$transformedContentSlots,
			$user,
			$popts,
			$output,
			17
		);

		$output = $edit->getSlotParserOutput( 'main', false );
		$this->assertInstanceOf(
			ParserOutput::class,
			$output
		);

		$this->assertSame(
			$output,
			$edit->getSlotParserOutput( 'main', false ),
			're-use output'
		);

		$this->assertSame(
			'',
			$output->getText(),
			'no html'
		);

		$outputWithHtml = $edit->getSlotParserOutput( 'main', true );
		$this->assertNotSame(
			$output,
			$outputWithHtml,
			'don\'t use output withpout HTML when output with HTML is requested'
		);

		$this->assertContains(
			'Transformed Content',
			$outputWithHtml->getText(),
			'with html'
		);

		$this->assertSame(
			$outputWithHtml,
			$edit->getSlotParserOutput( 'main', false ),
			'keep output with HTML when requested without HTML'
		);
	}

}
