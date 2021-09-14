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

use MediaWiki\EditPage\Constraint\AutoSummaryMissingSummaryConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;

/**
 * Tests the AutoSummaryMissingSummaryConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\AutoSummaryMissingSummaryConstraint
 */
class AutoSummaryMissingSummaryConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$originalContent = $this->createMock( Content::class );
		$newContent = $this->createMock( Content::class );
		$newContent->expects( $this->once() )
			->method( 'equals' )
			->with( $originalContent )
			->willReturn( false );
		$newContent->expects( $this->once() )
			->method( 'isRedirect' )
			->willReturn( false );
		$constraint = new AutoSummaryMissingSummaryConstraint(
			'UserSummary',
			'AutoSummary',
			false,
			$newContent,
			$originalContent
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$originalContent = $this->createMock( Content::class );
		$newContent = $this->createMock( Content::class );
		$newContent->expects( $this->once() )
			->method( 'equals' )
			->with( $originalContent )
			->willReturn( false );
		$newContent->expects( $this->once() )
			->method( 'isRedirect' )
			->willReturn( false );
		$constraint = new AutoSummaryMissingSummaryConstraint(
			'UserSummary',
			md5( 'UserSummary' ),
			false,
			$newContent,
			$originalContent
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_SUMMARY_NEEDED );
	}

}
