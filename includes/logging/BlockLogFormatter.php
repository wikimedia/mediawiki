<?php
/**
 * Formatter for block log entries.
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
 * This class formats block log entries.
 *
 * @since 1.25
 */
class BlockLogFormatter extends LogFormatter {
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$title = $this->entry->getTarget();
		if ( substr( $title->getText(), 0, 1 ) === '#' ) {
			// autoblock - no user link possible
			$params[2] = $title->getText();
			$params[3] = ''; // no user name for gender use
		} else {
			// Create a user link for the blocked
			$username = $title->getText();
			// @todo Store the user identifier in the parameters
			// to make this faster for future log entries
			$targetUser = User::newFromName( $username, false );
			$params[2] = Message::rawParam( $this->makeUserLink( $targetUser, Linker::TOOL_LINKS_NOBLOCK ) );
			$params[3] = $username; // plain user name for gender use
		}

		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'block' || $subtype === 'reblock' ) {
			if ( !isset( $params[4] ) ) {
				// Very old log entry without duration: means infinite
				$params[4] = 'infinite';
			}
			// Localize the duration, and add a tooltip
			// in English to help visitors from other wikis.
			// The lrm is needed to make sure that the number
			// is shown on the correct side of the tooltip text.
			$durationTooltip = '&lrm;' . htmlspecialchars( $params[4] );
			$blockExpiry = $this->context->getLanguage()->translateBlockExpiry(
				$params[4],
				$this->context->getUser(),
				wfTimestamp( TS_UNIX, $this->entry->getTimestamp() )
			);
			if ( $this->plaintext ) {
				$params[4] = Message::rawParam( $blockExpiry );
			} else {
				$params[4] = Message::rawParam(
					"<span class=\"blockExpiry\" title=\"$durationTooltip\">" .
					htmlspecialchars( $blockExpiry ) .
					'</span>'
				);
			}
			$params[5] = isset( $params[5] ) ?
				self::formatBlockFlags( $params[5], $this->context->getLanguage() ) : '';

			// block restrictions
			if ( isset( $params[6] ) ) {
				$pages = $params[6]['pages'] ?? [];
				$pages = array_map( function ( $page ) {
					return $this->makePageLink( Title::newFromText( $page ) );
				}, $pages );

				$namespaces = $params[6]['namespaces'] ?? [];
				$namespaces = array_map( function ( $ns ) {
					$text = (int)$ns === NS_MAIN
						? $this->msg( 'blanknamespace' )->escaped()
						: htmlspecialchars( $this->context->getLanguage()->getFormattedNsText( $ns ) );
					$params = [ 'namespace' => $ns ];

					return $this->makePageLink( SpecialPage::getTitleFor( 'Allpages' ), $params, $text );
				}, $namespaces );

				$restrictions = [];
				if ( $pages ) {
					$restrictions[] = $this->msg( 'logentry-partialblock-block-page' )
						->numParams( count( $pages ) )
						->rawParams( $this->context->getLanguage()->listToText( $pages ) )->text();
				}

				if ( $namespaces ) {
					$restrictions[] = $this->msg( 'logentry-partialblock-block-ns' )
						->numParams( count( $namespaces ) )
						->rawParams( $this->context->getLanguage()->listToText( $namespaces ) )->text();
				}

				$params[6] = Message::rawParam( $this->context->getLanguage()->listToText( $restrictions ) );
			}
		}

		return $params;
	}

	protected function extractParameters() {
		$params = parent::extractParameters();
		// Legacy log params returning the params in index 3 and 4, moved to 4 and 5
		if ( $this->entry->isLegacy() && isset( $params[3] ) ) {
			if ( isset( $params[4] ) ) {
				$params[5] = $params[4];
			}
			$params[4] = $params[3];
			$params[3] = '';
		}
		return $params;
	}

	public function getPreloadTitles() {
		$title = $this->entry->getTarget();
		$preload = [];
		// Preload user page for non-autoblocks
		if ( substr( $title->getText(), 0, 1 ) !== '#' && $title->canExist() ) {
			$preload[] = $title->getTalkPage();
		}
		// Preload page restriction
		$params = $this->extractParameters();
		if ( isset( $params[6]['pages'] ) ) {
			foreach ( $params[6]['pages'] as $page ) {
				$preload[] = Title::newFromText( $page );
			}
		}
		return $preload;
	}

	public function getActionLinks() {
		$subtype = $this->entry->getSubtype();
		$linkRenderer = $this->getLinkRenderer();
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| !( $subtype === 'block' || $subtype === 'reblock' )
			|| !MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $this->context->getUser(), 'block' )
		) {
			return '';
		}

		// Show unblock/change block link
		$title = $this->entry->getTarget();
		$links = [
			$linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Unblock', $title->getDBkey() ),
				$this->msg( 'unblocklink' )->text()
			),
			$linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Block', $title->getDBkey() ),
				$this->msg( 'change-blocklink' )->text()
			)
		];

		return $this->msg( 'parentheses' )->rawParams(
			$this->context->getLanguage()->pipeList( $links ) )->escaped();
	}

	/**
	 * Convert a comma-delimited list of block log flags
	 * into a more readable (and translated) form
	 *
	 * @param string $flags Flags to format
	 * @param Language $lang
	 * @return string
	 */
	public static function formatBlockFlags( $flags, Language $lang ) {
		$flags = trim( $flags );
		if ( $flags === '' ) {
			return ''; // nothing to do
		}
		$flags = explode( ',', $flags );
		$flagsCount = count( $flags );

		for ( $i = 0; $i < $flagsCount; $i++ ) {
			$flags[$i] = self::formatBlockFlag( $flags[$i], $lang );
		}

		return wfMessage( 'parentheses' )->inLanguage( $lang )
			->rawParams( $lang->commaList( $flags ) )->escaped();
	}

	/**
	 * Translate a block log flag if possible
	 *
	 * @param int $flag Flag to translate
	 * @param Language $lang Language object to use
	 * @return string
	 */
	public static function formatBlockFlag( $flag, Language $lang ) {
		static $messages = [];

		if ( !isset( $messages[$flag] ) ) {
			$messages[$flag] = htmlspecialchars( $flag ); // Fallback

			// For grepping. The following core messages can be used here:
			// * block-log-flags-angry-autoblock
			// * block-log-flags-anononly
			// * block-log-flags-hiddenname
			// * block-log-flags-noautoblock
			// * block-log-flags-nocreate
			// * block-log-flags-noemail
			// * block-log-flags-nousertalk
			$msg = wfMessage( 'block-log-flags-' . $flag )->inLanguage( $lang );

			if ( $msg->exists() ) {
				$messages[$flag] = $msg->escaped();
			}
		}

		return $messages[$flag];
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			// While this looks wrong to be starting at 5 rather than 4, it's
			// because getMessageParameters uses $4 for its own purposes.
			'5::duration',
			'6:array:flags',
			'6::flags' => '6:array:flags',
		];

		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		ksort( $params );

		$subtype = $entry->getSubtype();
		if ( $subtype === 'block' || $subtype === 'reblock' ) {
			// Defaults for old log entries missing some fields
			$params += [
				'5::duration' => 'infinite',
				'6:array:flags' => [],
			];

			if ( !is_array( $params['6:array:flags'] ) ) {
				// @phan-suppress-next-line PhanSuspiciousValueComparison
				$params['6:array:flags'] = $params['6:array:flags'] === ''
					? []
					: explode( ',', $params['6:array:flags'] );
			}

			if ( !wfIsInfinity( $params['5::duration'] ) ) {
				$ts = wfTimestamp( TS_UNIX, $entry->getTimestamp() );
				$expiry = strtotime( $params['5::duration'], $ts );
				if ( $expiry !== false && $expiry > 0 ) {
					$params[':timestamp:expiry'] = $expiry;
				}
			}
		}

		return $params;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeInvalidDimOffset
	 */
	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['flags'] ) ) {
			ApiResult::setIndexedTagName( $ret['flags'], 'f' );
		}

		if ( isset( $ret['restrictions']['pages'] ) ) {
			$ret['restrictions']['pages'] = array_map( function ( $title ) {
				return $this->formatParameterValueForApi( 'page', 'title-link', $title );
			}, $ret['restrictions']['pages'] );
			ApiResult::setIndexedTagName( $ret['restrictions']['pages'], 'p' );
		}

		if ( isset( $ret['restrictions']['namespaces'] ) ) {
			ApiResult::setIndexedTagName( $ret['restrictions']['namespaces'], 'ns' );
		}

		return $ret;
	}

	protected function getMessageKey() {
		$type = $this->entry->getType();
		$subtype = $this->entry->getSubtype();
		$sitewide = $this->entry->getParameters()['sitewide'] ?? true;

		$key = "logentry-$type-$subtype";
		if ( ( $subtype === 'block' || $subtype === 'reblock' ) && !$sitewide ) {
			// $this->getMessageParameters is doing too much. We just need
			// to check the presence of restrictions ($param[6]) and calling
			// on parent gives us that
			$params = parent::getMessageParameters();

			// message changes depending on whether there are editing restrictions or not
			if ( isset( $params[6] ) ) {
				$key = "logentry-partial$type-$subtype";
			} else {
				$key = "logentry-non-editing-$type-$subtype";
			}
		}

		return $key;
	}
}
