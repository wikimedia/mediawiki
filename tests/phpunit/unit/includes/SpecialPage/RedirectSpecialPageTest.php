<?php

namespace MediaWiki\Tests\Unit\SpecialPage;

use LogicException;
use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\SpecialPage\RedirectSpecialPage
 */
class RedirectSpecialPageTest extends MediaWikiUnitTestCase {
	public function testShowNoRedirectPageThrowsWhenNotOverridden(): void {
		$mock = new class ( 'Test' ) extends RedirectSpecialPage {
			public function getRedirect( $subpage ): bool {
				return false;
			}
		};

		$this->expectException( LogicException::class );
		$mock->execute( null );
	}
}
