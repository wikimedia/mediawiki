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

/**
 * This class formats redirect creation log entries.
 * @since 1.29
 */
class NewRedirectLogFormatter extends LogFormatter {
	public function getPreloadTitles() {
		$params = $this->extractParameters();
		return [ Title::newFromText( $params[3] ) ];
	}

	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		$key .= '-new_redir';
		return $key;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$redirect = $this->makePageLink( $this->entry->getTarget(), [ 'redirect' => 'no' ] );
		$params[3] = Message::rawParam( $redirect );
		return $params;
	}

}
