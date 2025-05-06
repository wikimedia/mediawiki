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

use MediaWiki\EditPage\Constraint\ChangeTagsConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * Tests the ChangeTagsConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ChangeTagsConstraint
 * @group Database
 */
class ChangeTagsConstraintTest extends MediaWikiIntegrationTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;

	public function testPass() {
		$tagName = 'tag-for-constraint-test-pass';
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( $tagName );

		$constraint = new ChangeTagsConstraint(
			$this->mockRegisteredUltimateAuthority(),
			[ $tagName ]
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testNoTags() {
		// Early return for no tags being added
		$constraint = new ChangeTagsConstraint(
			$this->mockRegisteredUltimateAuthority(),
			[]
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$tagName = 'tag-for-constraint-test-fail';
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( $tagName );

		$constraint = new ChangeTagsConstraint(
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'applychangetags' ] ),
			[ $tagName ]
		);
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_CHANGE_TAG_ERROR
		);
	}

}
