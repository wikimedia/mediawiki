<?php
/**
 * Formatter for user rights log entries.
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
 * @author Alexandre Emsenhuber
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.22
 */

/**
 * This class formats rights log entries.
 *
 * @since 1.21
 */
class RightsLogFormatter extends LogFormatter {
	protected function makePageLink( Title $title = null, $parameters = array() ) {
		global $wgContLang, $wgUserrightsInterwikiDelimiter;

		if ( !$this->plaintext ) {
			$text = $wgContLang->ucfirst( $title->getText() );
			$parts = explode( $wgUserrightsInterwikiDelimiter, $text, 2 );

			if ( count( $parts ) === 2 ) {
				$titleLink = WikiMap::foreignUserLink( $parts[1], $parts[0],
					htmlspecialchars( $title->getPrefixedText() ) );

				if ( $titleLink !== false ) {
					return $titleLink;
				}
			}
		}

		return parent::makePageLink( $title, $parameters );
	}

	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->getMessageParameters();
		if ( !isset( $params[3] ) && !isset( $params[4] ) ) {
			$key .= '-legacy';
		}

		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		// Really old entries
		if ( !isset( $params[3] ) && !isset( $params[4] ) ) {
			return $params;
		}

		$oldGroups = $params[3];
		$newGroups = $params[4];

		// Less old entries
		if ( $oldGroups === '' ) {
			$oldGroups = array();
		} elseif ( is_string( $oldGroups ) ) {
			$oldGroups = array_map( 'trim', explode( ',', $oldGroups ) );
		}
		if ( $newGroups === '' ) {
			$newGroups = array();
		} elseif ( is_string( $newGroups ) ) {
			$newGroups = array_map( 'trim', explode( ',', $newGroups ) );
		}

		$userName = $this->entry->getTarget()->getText();
		if ( !$this->plaintext && count( $oldGroups ) ) {
			foreach ( $oldGroups as &$group ) {
				$group = User::getGroupMember( $group, $userName );
			}
		}
		if ( !$this->plaintext && count( $newGroups ) ) {
			foreach ( $newGroups as &$group ) {
				$group = User::getGroupMember( $group, $userName );
			}
		}

		$lang = $this->context->getLanguage();
		if ( count( $oldGroups ) ) {
			$params[3] = $lang->listToText( $oldGroups );
		} else {
			$params[3] = $this->msg( 'rightsnone' )->text();
		}
		if ( count( $newGroups ) ) {
			// Array_values is used here because of bug 42211
			// see use of array_unique in UserrightsPage::doSaveUserGroups on $newGroups.
			$params[4] = $lang->listToText( array_values( $newGroups ) );
		} else {
			$params[4] = $this->msg( 'rightsnone' )->text();
		}

		return $params;
	}
}
