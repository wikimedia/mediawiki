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

use MediaWiki\Title\Title;
use StatusValue;

/**
 * Don't save a new page if it's blank or if it's a MediaWiki:
 * message with content equivalent to default (allow empty pages
 * in this case to disable messages, see T52124)
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class DefaultTextConstraint implements IEditConstraint {

	private string $result;

	/**
	 * @param Title $title
	 * @param bool $allowBlank
	 * @param string $userProvidedText
	 */
	public function __construct(
		private readonly Title $title,
		private readonly bool $allowBlank,
		private readonly string $userProvidedText,
	) {
	}

	public function checkConstraint(): string {
		$defaultMessageText = $this->title->getDefaultMessageText();
		if ( $this->title->getNamespace() === NS_MEDIAWIKI && $defaultMessageText !== false ) {
			$defaultText = $defaultMessageText;
		} else {
			$defaultText = '';
		}

		if ( !$this->allowBlank && $this->userProvidedText === $defaultText ) {
			$this->result = self::CONSTRAINT_FAILED;
		} else {
			$this->result = self::CONSTRAINT_PASSED;
		}
		return $this->result;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->fatal( 'blankarticle' );
			$statusValue->setResult( false, self::AS_BLANK_ARTICLE );
		}
		return $statusValue;
	}

}
