<?php
/**
 * Formatter for move log entries.
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
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.22
 */

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;

/**
 * This class formats move log entries.
 *
 * @since 1.19
 */
class MoveLogFormatter extends LogFormatter {
	private TitleParser $titleParser;

	public function __construct(
		LogEntry $entry,
		TitleParser $titleParser
	) {
		parent::__construct( $entry );
		$this->titleParser = $titleParser;
	}

	/** @inheritDoc */
	public function getPreloadTitles() {
		$params = $this->extractParameters();

		try {
			return [ $this->titleParser->parseTitle( $params[3] ) ];
		} catch ( MalformedTitleException ) {
			// namespace configuration may have changed to make $params[3] invalid (T370396);
			// nothing to preload in this case
			return [];
		}
	}

	/** @inheritDoc */
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		if ( isset( $params[4] ) && $params[4] === '1' ) {
			// Messages: logentry-move-move-noredirect, logentry-move-move_redir-noredirect
			$key .= '-noredirect';
		}

		return $key;
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$oldname = $this->makePageLink( $this->entry->getTarget(), [ 'redirect' => 'no' ] );
		$newname = $this->makePageLink( Title::newFromText( $params[3] ) );
		$params[2] = Message::rawParam( $oldname );
		$params[3] = Message::rawParam( $newname );
		unset( $params[4] ); // handled in getMessageKey

		return $params;
	}

	/** @inheritDoc */
	public function getActionLinks() {
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| $this->entry->getSubtype() !== 'move'
			|| !$this->context->getAuthority()->isAllowed( 'move' )
		) {
			return '';
		}

		$params = $this->extractParameters();
		$destTitle = Title::newFromText( $params[3] );
		if ( !$destTitle || !$destTitle->exists() ) {
			return '';
		}

		$revert = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'Movepage' ),
			$this->msg( 'revertmove' )->text(),
			[],
			[
				'wpOldTitle' => $destTitle->getPrefixedDBkey(),
				'wpNewTitle' => $this->entry->getTarget()->getPrefixedDBkey(),
				'wpReason' => $this->msg( 'revertmove-summary' )->inContentLanguage()->text(),
				'wpMovetalk' => 0
			]
		);

		return $this->msg( 'parentheses' )->rawParams( $revert )->escaped();
	}

	/** @inheritDoc */
	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'4:title:target',
			'5:bool:suppressredirect',
			'4::target' => '4:title:target',
			'5::noredir' => '5:bool:suppressredirect',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		if ( !isset( $params['5:bool:suppressredirect'] ) ) {
			$params['5:bool:suppressredirect'] = false;
		}

		return $params;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( MoveLogFormatter::class, 'MoveLogFormatter' );
