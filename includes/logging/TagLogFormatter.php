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
 */

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * This class formats tag log entries.
 *
 * Parameters (one-based indexes):
 * 4::revid
 * 5::logid
 * 6:list:tagsAdded
 * 7:number:tagsAddedCount
 * 8:list:tagsRemoved
 * 9:number:tagsRemovedCount
 *
 * @since 1.25
 */
class TagLogFormatter extends LogFormatter {

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$isRevLink = !empty( $params[3] );
		if ( $isRevLink ) {
			$id = $params[3];
			$target = $this->entry->getTarget();
			$query = [
				'oldid' => $id,
				'diff' => 'prev'
			];
		} else {
			$id = $params[4];
			$target = SpecialPage::getTitleValueFor( 'Log' );
			$query = [
				'logid' => $id,
			];
		}

		$formattedNumber = $this->context->getLanguage()->formatNumNoSeparators( $id );
		if ( $this->plaintext ) {
			$link = $formattedNumber;
		} elseif ( !$isRevLink || $target->exists() ) {
			$link = $this->getLinkRenderer()->makeKnownLink(
				$target, $formattedNumber, [], $query );
		} else {
			$link = htmlspecialchars( $formattedNumber );
		}

		if ( $isRevLink ) {
			// @phan-suppress-next-line SecurityCheck-XSS Unlikely positive, only if language format is bad
			$params[3] = Message::rawParam( $link );
		} else {
			// @phan-suppress-next-line SecurityCheck-XSS Unlikely positive, only if language format is bad
			$params[4] = Message::rawParam( $link );
		}

		return $params;
	}

	/** @inheritDoc */
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$rawParams = $this->entry->getParameters();

		$add = ( $rawParams['7:number:tagsAddedCount'] > 0 );
		$remove = ( $rawParams['9:number:tagsRemovedCount'] > 0 );
		$key .= ( $remove ? ( $add ? '' : '-remove' ) : '-add' );

		if ( $rawParams['4::revid'] ) {
			// Messages: logentry-tag-update-add-revision, logentry-tag-update-remove-revision,
			// logentry-tag-update-revision
			$key .= '-revision';
		} else {
			// Messages: logentry-tag-update-add-logentry, logentry-tag-update-remove-logentry,
			// logentry-tag-update-logentry
			$key .= '-logentry';
		}

		return $key;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( TagLogFormatter::class, 'TagLogFormatter' );
