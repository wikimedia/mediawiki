<?php

namespace MediaWiki\Tests\Unit\EditPage;

use MediaWiki\EditPage\EditStatusFormatter;
use MediaWiki\Html\Html;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Message\Message;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use MediaWikiUnitTestCase;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\EditPage\EditStatusFormatter
 */
class EditStatusFormatterTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideTestFormatStatus
	 */
	public function testFormatStatus(
		string $expectedHtml,
		PageEditStatus $status,
		?MessageSpecifier $submitButtonLabel = null,
	) {
		$mockLocalizer = $this->createMock( MessageLocalizer::class );
		$test = $this;
		$mockLocalizer->method( 'msg' )->willReturnCallback( static function ( $msg ) use ( $test ) {
			$message = $test->createMock( Message::class );
			$message->method( 'parse' )
				// Use ->text() as ->parse() uses the service container
				->willReturnCallback( static fn () => ( new FakeQqxMessageLocalizer() )->msg( $msg )->text() );
			return $message;
		} );
		$formatter = new EditStatusFormatter( $mockLocalizer );
		$this->assertSame( $expectedHtml, $formatter->formatStatus( $status, $submitButtonLabel ) );
	}

	public static function provideTestFormatStatus(): array {
		return [
			[
				'',
				PageEditStatus::newGood(),
			],
			[
				Html::warningBox( '(test-warning)' ),
				PageEditStatus::newGood()->warning( 'test-warning' ),
			],
			[
				Html::errorBox( '(test-error)' ),
				PageEditStatus::newFatal( 'test-error' ),
			],
			[
				Html::errorBox( '(test-error)' ) . Html::warningBox( '(test-warning)' ),
				PageEditStatus::newFatal( 'test-error' )->warning( 'test-warning' ),
			],
			[
				Html::warningBox( '(warning1)<br>(warning2)' ),
				PageEditStatus::newGood()->warning( 'warning1' )->warning( 'warning2' ),
			],
			[
				Html::warningBox( '(edit-constraint-resubmittable-warning: ' .
					'(edit-constraint-newsectionmissingsubject-warning), (submit-button-label))' ),
				PageEditStatus::newGood()
					->warning( 'edit-constraint-newsectionmissingsubject-warning' )
					->setCanResubmit( true ),
				MessageValue::new( 'submit-button-label' ),
			],
			[
				Html::warningBox( '(edit-constraint-newsectionmissingsubject-warning)' ),
				PageEditStatus::newGood()
					->warning( 'edit-constraint-newsectionmissingsubject-warning' ),
				MessageValue::new( 'submit-button-label' ),
			],
		];
	}

}
