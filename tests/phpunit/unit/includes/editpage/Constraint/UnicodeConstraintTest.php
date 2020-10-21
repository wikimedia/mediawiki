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
use MediaWiki\EditPage\Constraint\UnicodeConstraint;

/**
 * Tests the UnicodeConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\UnicodeConstraint
 */
class UnicodeConstraintTest extends MediaWikiUnitTestCase {

	public function testPass() {
		$constraint = new UnicodeConstraint( UnicodeConstraint::VALID_UNICODE );
		$this->assertSame(
			IEditConstraint::CONSTRAINT_PASSED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertTrue( $status->isGood() );
	}

	public function testFailure() {
		$constraint = new UnicodeConstraint( 'NotTheCorrectUnicode' );
		$this->assertSame(
			IEditConstraint::CONSTRAINT_FAILED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertFalse( $status->isGood() );
		$this->assertSame(
			IEditConstraint::AS_UNICODE_NOT_SUPPORTED,
			$status->getValue()
		);
	}

}
