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

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\SelfRedirectConstraint;

/**
 * Tests the SelfRedirectConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\SelfRedirectConstraint
 */
class SelfRedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getContent( $title, $isSelfRedirect ) {
		$content = $this->createMock( Content::class );
		$contentRedirectTarget = $this->createMock( Title::class );
		// No $this->once() since only called for the new content
		$content->method( 'isRedirect' )
			->willReturn( true );
		$content->expects( $this->once() )
			->method( 'getRedirectTarget' )
			->willReturn( $contentRedirectTarget );
		$contentRedirectTarget->expects( $this->once() )
			->method( 'equals' )
			->with( $this->equalTo( $title ) )
			->willReturn( $isSelfRedirect );
		return $content;
	}

	public function testPass() {
		// New content is a self redirect, but so is existing content, so no warning
		$title = $this->createMock( Title::class );
		$constraint = new SelfRedirectConstraint(
			false, // $allowSelfRedirect
			$this->getContent( $title, true ),
			$this->getContent( $title, true ),
			$title
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		// New content is a self redirect, but existing content is not
		$title = $this->createMock( Title::class );
		$constraint = new SelfRedirectConstraint(
			false, // $allowSelfRedirect
			$this->getContent( $title, true ),
			$this->getContent( $title, false ),
			$title
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_SELF_REDIRECT
		);
	}

}
