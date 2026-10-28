<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\DefaultTextConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWiki\ShadowPage\ShadowPage;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;

/**
 * Tests the DefaultTextConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\DefaultTextConstraint
 */
class DefaultTextConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getTitle() {
		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_MEDIAWIKI );
		return $title;
	}

	private function getShadowPageLoader( $defaultText ) {
		if ( $defaultText === false ) {
			$page = null;
		} else {
			$page = $this->createMock( ShadowPage::class );
			$page->method( 'getPreloadContent' )->willReturn(
				new DummyContentForTesting( $defaultText )
			);
		}
		$loader = $this->createMock( ShadowPageLoader::class );
		$loader->method( 'get' )->willReturn( $page );
		return $loader;
	}

	public function testPass() {
		$constraint = new DefaultTextConstraint(
			$this->getShadowPageLoader( 'DefaultMessageTextGoesHere' ),
			$this->getTitle(),
			'User provided text goes here',
			new PageEditAttemptState(),
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 */
	public function testFailure( string|bool $defaultText, string $userInput, string $expectedMessage ) {
		$editAttemptState = new PageEditAttemptState();
		$constraint = new DefaultTextConstraint(
			$this->getShadowPageLoader( $defaultText ),
			$this->getTitle(),
			$userInput,
			$editAttemptState,
		);
		$status = $this->assertConstraintFailed( $constraint, EditConstraint::AS_BLANK_ARTICLE );
		$this->assertTrue( $editAttemptState->shouldAllowBlankArticle() );
		$this->assertTrue( $status->canResubmit() );
		$this->assertStatusMessage( $expectedMessage, $status );
	}

	public static function provideTestFailure() {
		yield 'Matching message text' => [
			'MessageText',
			'MessageText',
			'edit-constraint-defaulttext-default-warning'
		];
		yield 'Blank page and no default' => [
			false,
			'',
			'edit-constraint-defaulttext-blank-warning'
		];
	}

}
