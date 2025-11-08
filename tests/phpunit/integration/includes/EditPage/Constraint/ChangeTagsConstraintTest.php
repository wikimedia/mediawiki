<?php
/**
 * @license GPL-2.0-or-later
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
