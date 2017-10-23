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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.25
 */

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
			$params[4] = Message::rawParam(
				"<span class=\"blockExpiry\" title=\"$durationTooltip\">" .
				$this->context->getLanguage()->translateBlockExpiry(
					$params[4],
					$this->context->getUser(),
					wfTimestamp( TS_UNIX, $this->entry->getTimestamp() )
				) .
				'</span>'
			);
			$params[5] = isset( $params[5] ) ?
				self::formatBlockFlags( $params[5], $this->context->getLanguage() ) : '';
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
		// Preload user page for non-autoblocks
		if ( substr( $title->getText(), 0, 1 ) !== '#' ) {
			return [ $title->getTalkPage() ];
		}
		return [];
	}

	public function getActionLinks() {
		$subtype = $this->entry->getSubtype();
		$linkRenderer = $this->getLinkRenderer();
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| !( $subtype === 'block' || $subtype === 'reblock' )
			|| !$this->context->getUser()->isAllowed( 'block' )
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
	public static function formatBlockFlags( $flags, $lang ) {
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
	public static function formatBlockFlag( $flag, $lang ) {
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

		$subtype = $entry->getSubtype();
		if ( $subtype === 'block' || $subtype === 'reblock' ) {
			// Defaults for old log entries missing some fields
			$params += [
				'5::duration' => 'infinite',
				'6:array:flags' => [],
			];

			if ( !is_array( $params['6:array:flags'] ) ) {
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

	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['flags'] ) ) {
			ApiResult::setIndexedTagName( $ret['flags'], 'f' );
		}
		return $ret;
	}

}
