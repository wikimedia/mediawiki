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

use MediaWiki\Content\Content;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\Title;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Verify the page does not redirect to an unknown page unless
 *  - the user is okay with a broken redirect, or
 *  - the page already redirected to an unknown page before the edit
 *
 * @since 1.44
 * @internal
 */
class BrokenRedirectConstraint implements IEditConstraint {

	private ?Title $allowedBrokenTarget;
	private Content $newContent;
	private Content $originalContent;
	private LinkTarget $title;
	private string $submitButtonLabel;
	private string $result;
	public ?Title $brokenRedirectTarget;

	/**
	 * @param ?Title $allowedBrokenTarget
	 * @param Content $newContent
	 * @param Content $originalContent
	 * @param LinkTarget $title
	 * @param string $submitButtonLabel
	 */
	public function __construct(
		?Title $allowedBrokenTarget,
		Content $newContent,
		Content $originalContent,
		LinkTarget $title,
		string $submitButtonLabel
	) {
		$this->allowedBrokenTarget = $allowedBrokenTarget;
		$this->newContent = $newContent;
		$this->originalContent = $originalContent;
		$this->title = $title;
		$this->submitButtonLabel = $submitButtonLabel;
	}

	public function checkConstraint(): string {
		$newRedirectTarget = $this->newContent->getRedirectTarget();

		// the constraint should only be checked if there is a redirect in the new content, and either
		// - $allowedBrokenTarget is null (the last save attempt didn't contain a broken redirect)
		// - the last save attempt contained a broken redirect, and the non-existent target is not the same as
		//   the one in this save attempt (T395767)
		if ( $newRedirectTarget !== null && !$this->allowedBrokenTarget?->equals( $newRedirectTarget ) ) {
			if ( !$newRedirectTarget->isKnown()
				&& !$newRedirectTarget->equals( $this->title )
			) {
				$currentTarget = $this->originalContent->getRedirectTarget();

				// fail if there was no previous content or the previous content contained
				// a redirect to a different page
				if ( !$currentTarget?->equals( $newRedirectTarget ) ) {
					$this->result = self::CONSTRAINT_FAILED;
					$this->brokenRedirectTarget = $newRedirectTarget;

					return self::CONSTRAINT_FAILED;
				}
			}

		}
		$this->result = self::CONSTRAINT_PASSED;

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->fatal( MessageValue::new( 'edit-constraint-brokenredirect',
				[ MessageValue::new( $this->submitButtonLabel ) ] ) );
			$statusValue->value = self::AS_BROKEN_REDIRECT;
		}

		return $statusValue;
	}

}
