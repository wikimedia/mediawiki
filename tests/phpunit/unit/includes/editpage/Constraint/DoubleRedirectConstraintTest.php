<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\Content\Content;
use MediaWiki\EditPage\Constraint\DoubleRedirectConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Title\Title;

/**
 * Tests the DoubleRedirectConstraint
 *
 * @author SomeRandomDeveloper
 *
 * @covers \MediaWiki\EditPage\Constraint\DoubleRedirectConstraint
 */
class DoubleRedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getContent( $isDoubleRedirect ) {
		$content = $this->createMock( Content::class );
		$contentRedirectTarget = $this->createMock( Title::class );
		// No $this->once() since only called for the new content
		$content->method( 'isRedirect' )
			->willReturn( true );
		$content->expects( $this->atLeastOnce() )
			->method( 'getRedirectTarget' )
			->willReturn( $contentRedirectTarget );
		$contentRedirectTarget->expects( $this->once() )
			->method( 'isRedirect' )
			->willReturn( $isDoubleRedirect );

		return $content;
	}

	public function testPass() {
		// both the old and the new content have a double redirect, so no warning
		$title = $this->createMock( Title::class );
		$redirectLookup = $this->createMock( RedirectLookup::class );
		$constraint = new DoubleRedirectConstraint(
			false, // $allowDoubleRedirects
			$this->getContent( true ),
			$this->getContent( true ),
			$title,
			$redirectLookup
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		// New content is a double redirect, but existing content is not
		$title = $this->createMock( Title::class );
		$redirectLookup = $this->createMock( RedirectLookup::class );
		$realTarget = $this->createMock( Title::class );
		$realTarget->method( 'getText' )
			->willReturn( 'RealTarget' );
		$redirectLookup->method( 'getRedirectTarget' )
			->willReturn( $realTarget );
		$constraint = new DoubleRedirectConstraint(
			false, // $allowDoubleRedirects
			$this->getContent( true ),
			$this->getContent( false ),
			$title,
			$redirectLookup
		);

		// required by wfEscapeWikiText
		global $wgEnableMagicLinks;
		$old = $wgEnableMagicLinks;
		$wgEnableMagicLinks = [];

		try {
			$this->assertConstraintFailed(
				$constraint,
				IEditConstraint::AS_DOUBLE_REDIRECT
			);
		} finally {
			$wgEnableMagicLinks = $old;
		}
	}

}
