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
}

class MalformedTitleInterwikiPresentException extends MalformedTitleException {
}

class MalformedTitleEmptyException extends MalformedTitleException {
}

class MalformedTitleBadUtf8Exception extends MalformedTitleException {
}

class MalformedTitleInvalidTalkException extends MalformedTitleException {
}

class MalformedTitleIllegalCharactersException extends MalformedTitleException {
}

class MalformedTitleRelativeException extends MalformedTitleException {
}

class MalformedTitleTildesException extends MalformedTitleException {
}

class MalformedTitleLengthExceededException extends MalformedTitleException {
}

class MalformedTitleLeadingColonException extends MalformedTitleException {
}
