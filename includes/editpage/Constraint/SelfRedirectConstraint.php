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

namespace MediaWiki\EditPage\Constraint;

use Content;
use MediaWiki\Linker\LinkTarget;
use StatusValue;

/**
 * Verify the page does not redirect to itself unless
 *   - the user is okay with a self redirect, or
 *   - the page already redirected to itself before the edit
 *
 * @since 1.36
 * @internal
 */
class SelfRedirectConstraint implements IEditConstraint {

	private bool $allowSelfRedirect;
	private Content $newContent;
	private Content $originalContent;
	private LinkTarget $title;
	private string $result;

	/**
	 * @param bool $allowSelfRedirect
	 * @param Content $newContent
	 * @param Content $originalContent
	 * @param LinkTarget $title
	 */
	public function __construct(
		bool $allowSelfRedirect,
		Content $newContent,
		Content $originalContent,
		LinkTarget $title
	) {
		$this->allowSelfRedirect = $allowSelfRedirect;
		$this->newContent = $newContent;
		$this->originalContent = $originalContent;
		$this->title = $title;
	}

	public function checkConstraint(): string {
		if ( !$this->allowSelfRedirect
			&& $this->newContent->isRedirect()
			&& $this->newContent->getRedirectTarget()->equals( $this->title )
		) {
			// T29683 If the page already redirects to itself, don't warn.
			$currentTarget = $this->originalContent->getRedirectTarget();
			if ( !$currentTarget || !$currentTarget->equals( $this->title ) ) {
				$this->result = self::CONSTRAINT_FAILED;
				return self::CONSTRAINT_FAILED;
			}
		}
		$this->result = self::CONSTRAINT_PASSED;
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->fatal( 'selfredirect' );
			$statusValue->value = self::AS_SELF_REDIRECT;
		}
		return $statusValue;
	}

}
