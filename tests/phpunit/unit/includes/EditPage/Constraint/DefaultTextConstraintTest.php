<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\DefaultTextConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\Language\RawMessage;
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
			false, // Allow blank
			'User provided text goes here',
			new RawMessage( '' ),
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param string|bool $defaultText
	 * @param string $userInput
	 */
	public function testFailure( $defaultText, $userInput ) {
		$constraint = new DefaultTextConstraint(
			$this->getShadowPageLoader( $defaultText ),
			$this->getTitle(),
			false, // Allow blank
			$userInput,
			new RawMessage( '' ),
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_BLANK_ARTICLE );
	}

	public static function provideTestFailure() {
		yield 'Matching message text' => [ 'MessageText', 'MessageText' ];
		yield 'Blank page and no default' => [ false, '' ];
	}

}
