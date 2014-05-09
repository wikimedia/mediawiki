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
 * @license GPL 2+
 */

/**
 * MalformedTitleException is thrown when a TitleParser is unable to parse a title string.
 *
 * @license GPL 2+
 */
class MalformedTitleException extends Exception {
	private $titleText;

	public function __construct( $text ) {
		$this->titleText = $text;
	}

	public function getTitleText() {
		return $this->titleText;
	}

	/**
	 * Return l10n messages to be used by BadTitleError for this exception.
	 * @return array( page title message, error text message, error text parameters )
	 */
	public function getErrorPageParams() {
		return array(
			'badtitle',
			'badtitletext',
			array()
		);
	}
}

class MalformedTitleInterwikiPresentException extends MalformedTitleException {
}

class MalformedTitleEmptyException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-empty',
			'title-invalid-empty-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleBadUtf8Exception extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-utf8',
			'title-invalid-utf8-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleInvalidTalkException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-talk-namespace',
			'title-invalid-talk-namespace-text',
			array()
		);
	}
}

class MalformedTitleIllegalCharactersException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-characters',
			'title-invalid-characters-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleRelativeException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-relative',
			'title-invalid-relative-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleTildesException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-magic-tilde',
			'title-invalid-magic-tilde-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleLengthExceededException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-too-long',
			'title-invalid-too-long-text',
			array( $this->getTitleText() )
		);
	}
}

class MalformedTitleLeadingColonException extends MalformedTitleException {
	public function getErrorPageParams() {
		return array(
			'title-invalid-leading-colon',
			'title-invalid-leading-colon-text',
			array( $this->getTitleText() )
		);
	}
}
