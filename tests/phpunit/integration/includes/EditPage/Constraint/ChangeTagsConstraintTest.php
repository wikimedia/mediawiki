<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\ChangeTagsConstraint;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\Tests\Unit\EditPage\Constraint\EditConstraintTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;

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
			EditConstraint::AS_CHANGE_TAG_ERROR
		);
	}

}
