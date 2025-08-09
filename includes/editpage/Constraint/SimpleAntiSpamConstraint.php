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
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use StatusValue;

/**
 * Verify simple anti spam measure of an extra hidden text field
 *
 * @since 1.36
 * @internal
 */
class SimpleAntiSpamConstraint implements IEditConstraint {

	/**
	 * @param LoggerInterface $logger for logging hits
	 * @param string $input
	 * @param UserIdentity $user for logging hits
	 * @param Title $title for logging hits
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
		private readonly string $input,
		private readonly UserIdentity $user,
		private readonly Title $title,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->input === '' ) {
			return self::CONSTRAINT_PASSED;
		}
		$this->logger->debug(
			'{name} editing "{title}" submitted bogus field "{input}"',
			[
				'name' => $this->user->getName(),
				'title' => $this->title->getPrefixedText(),
				'input' => $this->input
			]
		);
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->input !== '' ) {
			$statusValue->fatal( 'spamprotectionmatch', '' );
			$statusValue->value = self::AS_SPAM_ERROR;
		}
		return $statusValue;
	}

}
