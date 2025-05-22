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
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Title\Title;
use StatusValue;

/**
 * Verify the page does not redirect to another redirect unless
 *  - the user is okay with a double redirect, or
 *  - the page already redirected to another redirect before the edit
 *
 * @since 1.44
 * @internal
 */
class DoubleRedirectConstraint implements IEditConstraint {

	private bool $allowDoubleRedirects;
	private Content $newContent;
	private Content $originalContent;
	private LinkTarget $title;
	private string $result;
	public bool $willCreateSelfRedirect;
	private ?LinkTarget $doubleRedirectTarget;
	private RedirectLookup $redirectLookup;

	/**
	 * @param bool $allowDoubleRedirects
	 * @param Content $newContent
	 * @param Content $originalContent
	 * @param LinkTarget $title
	 * @param RedirectLookup $redirectLookup
	 */
	public function __construct(
		bool $allowDoubleRedirects,
		Content $newContent,
		Content $originalContent,
		LinkTarget $title,
		RedirectLookup $redirectLookup
	) {
		$this->allowDoubleRedirects = $allowDoubleRedirects;
		$this->newContent = $newContent;
		$this->originalContent = $originalContent;
		$this->title = $title;
		$this->redirectLookup = $redirectLookup;
	}

	public function checkConstraint(): string {
		if ( !$this->allowDoubleRedirects ) {
			$newRedirectTarget = $this->newContent->getRedirectTarget();

			if ( $newRedirectTarget !== null && $newRedirectTarget->isRedirect() &&
				!$newRedirectTarget->equals( $this->title ) ) {

				$currentTarget = $this->originalContent->getRedirectTarget();

				// fail if there was no previous content or the previous content already contained a double redirect
				if ( !$currentTarget || !$currentTarget->isRedirect() ) {
					$this->result = self::CONSTRAINT_FAILED;
					$this->doubleRedirectTarget =
						$this->redirectLookup->getRedirectTarget( $this->newContent->getRedirectTarget() );
					$this->willCreateSelfRedirect =
						$this->doubleRedirectTarget != null &&
						$this->doubleRedirectTarget->isSameLinkAs( $this->title );

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
			$realRedirectTarget = $this->redirectLookup->getRedirectTarget( $this->newContent->getRedirectTarget() );
			$realTargetTitle = Title::castFromLinkTarget( $realRedirectTarget );

			$statusValue->fatal(
				'edit-constraint-doubleredirect',
				wfEscapeWikiText( $realTargetTitle->getPrefixedText() )
			);
			$statusValue->value = self::AS_DOUBLE_REDIRECT;
		}

		return $statusValue;
	}

}
