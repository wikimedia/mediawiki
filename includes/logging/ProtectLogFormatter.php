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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.25
 */

/**
 * This class formats protect log entries.
 *
 * @since 1.25
 */
class ProtectLogFormatter extends LogFormatter {
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
			// Restrictions and expiries
			if ( isset( $params[3] ) ) {
				$params[3] = $this->context->getLanguage()->getDirMark() . htmlspecialchars( $params[3] );
			}
			// Cascading flag
			if ( isset( $params[4] ) ) {
				// handled in getMessageKey
				unset( $params[4] );
			}
		} elseif ( $subtype === 'move_prot' ) {
			$oldname = $this->makePageLink( Title::newFromText( $params[3] ), array( 'redirect' => 'no' ) );
			$params[3] = Message::rawParam( $oldname );
		}

		return $params;
	}

	public function getActionLinks() {
		$subtype = $this->entry->getSubtype();
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| !( $subtype === 'protect' || $subtype === 'modify' || $subtype === 'unprotect' )
		) {
			return '';
		}

		// Show change protection link
		$title = $this->entry->getTarget();
		$links = array(
			Linker::link( $title,
				$this->msg( 'hist' )->escaped(),
				array(),
				array(
					'action' => 'history',
					'offset' => $this->entry->getTimestamp()
				)
			)
		);
		if ( $this->context->getUser()->isAllowed( 'protect' ) ) {
			$links[] = Linker::linkKnown(
				$title,
				$this->msg( 'protect_change' )->escaped(),
				array(),
				array( 'action' => 'protect' )
			);
		}

		return $this->msg( 'parentheses' )->rawParams(
			$this->context->getLanguage()->pipeList( $links ) )->escaped();
	}
}
