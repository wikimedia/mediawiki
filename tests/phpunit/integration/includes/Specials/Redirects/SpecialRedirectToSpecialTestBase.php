<?php

namespace MediaWiki\Tests\Integration\Specials\Redirects;

use MediaWiki\Context\RequestContext;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * A base class for tests of special pages that extend {@link SpecialRedirectWithAction}. Used to
 * test the common functons of the special page
 */
abstract class SpecialRedirectToSpecialTestBase extends SpecialPageTestBase {

	/**
	 * Returns the name of the special page that is the expected redirect target
	 */
	abstract protected function getRedirectName(): string;

	/**
	 * @return string|null Returns the string that should be used as the subpage. If the special page
	 *   redirect does not use the subpage then specify null (where the subpage from the source
	 *   title is used).
	 */
	abstract protected function getRedirectSubpage(): string|null;

	/** @dataProvider provideRedirectsToSpecialPage */
	public function testRedirectsToSpecialPage( string $sourceSubpage ): void {
		$context = RequestContext::getMain();
		[ $html ] = $this->executeSpecialPage( $sourceSubpage, null, null, null, false, $context );

		$expectedSpecialPageTarget = SpecialPage::getTitleFor(
			$this->getRedirectName(),
			$this->getRedirectSubpage() ?? $sourceSubpage
		);
		$this->assertSame(
			$expectedSpecialPageTarget->getFullUrlForRedirect(),
			$context->getOutput()->getRedirect(),
			'Did not redirect to the expected action'
		);
		$this->assertSame( '', $html );
	}

	public static function provideRedirectsToSpecialPage(): array {
		return [
			'Source special page has no subpage' => [
				'sourceSubpage' => ''
			],
			'Source special page has subpage' => [
				'sourceSubpage' => 'Test'
			],
		];
	}
}
