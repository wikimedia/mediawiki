<?php
/**
 * Formatter for merge log entries.
 *
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
 * @license GPL-2.0-or-later
 * @since 1.25
 */

use MediaWiki\MediaWikiServices;

/**
 * This class formats merge log entries.
 *
 * @since 1.25
 */
class MergeLogFormatter extends LogFormatter {
	public function getPreloadTitles() {
		$params = $this->extractParameters();

		return [ Title::newFromText( $params[3] ) ];
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$oldname = $this->makePageLink( $this->entry->getTarget(), [ 'redirect' => 'no' ] );
		$newname = $this->makePageLink( Title::newFromText( $params[3] ) );
		$params[2] = Message::rawParam( $oldname );
		$params[3] = Message::rawParam( $newname );
		$params[4] = $this->context->getLanguage()
			->userTimeAndDate( $params[4], $this->context->getUser() );
		return $params;
	}

	public function getActionLinks() {
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| !MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $this->context->getUser(), 'mergehistory' )
		) {
			return '';
		}

		// Show unmerge link
		$params = $this->extractParameters();
		$revert = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'MergeHistory' ),
			$this->msg( 'revertmerge' )->text(),
			[],
			[
				'target' => $params[3],
				'dest' => $this->entry->getTarget()->getPrefixedDBkey(),
				'mergepoint' => $params[4],
				'submitted' => 1 // show the revisions immediately
			]
		);

		return $this->msg( 'parentheses' )->rawParams( $revert )->escaped();
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'4:title:dest',
			'5:timestamp:mergepoint',
			'4::dest' => '4:title:dest',
			'5::mergepoint' => '5:timestamp:mergepoint',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}
}
