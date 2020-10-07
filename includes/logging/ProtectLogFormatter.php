<?php
/**
 * Formatter for protect log entries.
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
 * @since 1.26
 */

use MediaWiki\MediaWikiServices;

/**
 * This class formats protect log entries.
 *
 * @since 1.26
 */
class ProtectLogFormatter extends LogFormatter {
	public function getPreloadTitles() {
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'move_prot' ) {
			$params = $this->extractParameters();
			return [ Title::newFromText( $params[3] ) ];
		}
		return [];
	}

	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		if ( isset( $params[4] ) && $params[4] ) {
			// Messages: logentry-protect-protect-cascade, logentry-protect-modify-cascade
			$key .= '-cascade';
		}

		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'protect' || $subtype === 'modify' ) {
			$rawParams = $this->entry->getParameters();
			if ( isset( $rawParams['details'] ) ) {
				$params[3] = $this->createProtectDescription( $rawParams['details'] );
			} elseif ( isset( $params[3] ) ) {
				// Old way of Restrictions and expiries
				$params[3] = $this->context->getLanguage()->getDirMark() . $params[3];
			} else {
				// Very old way (nothing set)
				$params[3] = '';
			}
			// Cascading flag
			if ( isset( $params[4] ) ) {
				// handled in getMessageKey
				unset( $params[4] );
			}
		} elseif ( $subtype === 'move_prot' ) {
			$oldname = $this->makePageLink( Title::newFromText( $params[3] ), [ 'redirect' => 'no' ] );
			$params[3] = Message::rawParam( $oldname );
		}

		return $params;
	}

	public function getActionLinks() {
		$linkRenderer = $this->getLinkRenderer();
		$subtype = $this->entry->getSubtype();
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| $subtype === 'move_prot' // the move log entry has the right action link
		) {
			return '';
		}

		// Show history link for pages that exist otherwise show nothing
		$title = $this->entry->getTarget();
		$links = [];
		if ( $title->exists() ) {
			$links [] = $linkRenderer->makeLink( $title,
				$this->msg( 'hist' )->text(),
				[],
				[
					'action' => 'history',
					'offset' => $this->entry->getTimestamp(),
				]
			);
		}

		// Show change protection link
		if ( MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $this->context->getUser(), 'protect' )
		) {
			$links[] = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'protect_change' )->text(),
				[],
				[ 'action' => 'protect' ]
			);
		}

		if ( empty( $links ) ) {
			return '';
		} else {
			return $this->msg( 'parentheses' )->rawParams(
				$this->context->getLanguage()->pipeList( $links )
			)->escaped();
		}
	}

	protected function getParametersForApi() {
		$entry = $this->entry;
		$subtype = $this->entry->getSubtype();
		$params = $entry->getParameters();

		$map = [];
		if ( $subtype === 'protect' || $subtype === 'modify' ) {
			$map = [
				'4::description',
				'5:bool:cascade',
				'details' => ':array:details',
			];
		} elseif ( $subtype === 'move_prot' ) {
			$map = [
				'4:title:oldtitle',
				'4::oldtitle' => '4:title:oldtitle',
			];
		}
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		// Change string to explicit boolean
		if ( isset( $params['5:bool:cascade'] ) && is_string( $params['5:bool:cascade'] ) ) {
			$params['5:bool:cascade'] = $params['5:bool:cascade'] === 'cascade';
		}

		return $params;
	}

	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['details'] ) && is_array( $ret['details'] ) ) {
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			foreach ( $ret['details'] as &$detail ) {
				if ( isset( $detail['expiry'] ) ) {
					$detail['expiry'] = $contLang->
						formatExpiry( $detail['expiry'], TS_ISO_8601, 'infinite' );
				}
			}
		}

		return $ret;
	}

	/**
	 * Create the protect description to show in the log formatter
	 *
	 * @param array[] $details
	 * @return string
	 */
	public function createProtectDescription( array $details ) {
		$protectDescription = '';

		foreach ( $details as $param ) {
			$expiryText = $this->formatExpiry( $param['expiry'] );

			// Messages: restriction-edit, restriction-move, restriction-create,
			// restriction-upload
			$action = $this->context->msg( 'restriction-' . $param['type'] )->escaped();

			$protectionLevel = $param['level'];
			// Messages: protect-level-autoconfirmed, protect-level-sysop
			$message = $this->context->msg( 'protect-level-' . $protectionLevel );
			if ( $message->isDisabled() ) {
				// Require "$1" permission
				$restrictions = $this->context->msg( "protect-fallback", $protectionLevel )->parse();
			} else {
				$restrictions = $message->escaped();
			}

			if ( $protectDescription !== '' ) {
				$protectDescription .= $this->context->msg( 'word-separator' )->escaped();
			}

			$protectDescription .= $this->context->msg( 'protect-summary-desc' )
				->params( $action, $restrictions, $expiryText )->escaped();
		}

		return $protectDescription;
	}

	private function formatExpiry( $expiry ) {
		if ( wfIsInfinity( $expiry ) ) {
			return $this->context->msg( 'protect-expiry-indefinite' )->text();
		}
		$lang = $this->context->getLanguage();
		$user = $this->context->getUser();
		return $this->context->msg(
			'protect-expiring-local',
			$lang->userTimeAndDate( $expiry, $user ),
			$lang->userDate( $expiry, $user ),
			$lang->userTime( $expiry, $user )
		)->text();
	}

}
