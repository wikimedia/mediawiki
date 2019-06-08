<?php
/**
 * License selector for use on Special:Upload.
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
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 */

use MediaWiki\MediaWikiServices;

/**
 * A License class for use on Special:Upload
 */
class Licenses extends HTMLFormField {
	/** @var string */
	protected $msg;

	/** @var array */
	protected $lines = [];

	/** @var string */
	protected $html;

	/** @var string|null */
	protected $selected;
	/**#@-*/

	/**
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->msg = static::getMessageFromParams( $params );
		$this->selected = null;

		$this->makeLines();
	}

	/**
	 * @param array $params
	 * @return string
	 */
	protected static function getMessageFromParams( $params ) {
		if ( !empty( $params['licenses'] ) ) {
			return $params['licenses'];
		}

		// If the licenses page is in $wgForceUIMsgAsContentMsg (which is the case
		// on Commons), translations will be in the database, in subpages of this
		// message (e.g. MediaWiki:Licenses/<lang>)
		// If there is no such translation, the result will be '-' (the empty default
		// in the i18n files), so we'll need to force it to look up the actual licenses
		// in the default site language (= get the translation from MediaWiki:Licenses)
		// Also see https://phabricator.wikimedia.org/T3495
		$defaultMsg = wfMessage( 'licenses' )->inContentLanguage();
		if ( !$defaultMsg->exists() || $defaultMsg->plain() === '-' ) {
			$defaultMsg = wfMessage( 'licenses' )->inLanguage(
				MediaWikiServices::getInstance()->getContentLanguage() );
		}

		return $defaultMsg->plain();
	}

	/**
	 * @param string $line
	 * @return License
	 */
	protected function buildLine( $line ) {
		return new License( $line );
	}

	/**
	 * @private
	 */
	protected function makeLines() {
		$levels = [];
		$lines = explode( "\n", $this->msg );

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			} else {
				list( $level, $line ) = $this->trimStars( $line );

				if ( strpos( $line, '|' ) !== false ) {
					$obj = $this->buildLine( $line );
					$this->stackItem( $this->lines, $levels, $obj );
				} else {
					if ( $level < count( $levels ) ) {
						$levels = array_slice( $levels, 0, $level );
					}
					if ( $level == count( $levels ) ) {
						$levels[$level - 1] = $line;
					} elseif ( $level > count( $levels ) ) {
						$levels[] = $line;
					}
				}
			}
		}
	}

	/**
	 * @param string $str
	 * @return array
	 */
	protected function trimStars( $str ) {
		$numStars = strspn( $str, '*' );
		return [ $numStars, ltrim( substr( $str, $numStars ), ' ' ) ];
	}

	/**
	 * @param array &$list
	 * @param array $path
	 * @param mixed $item
	 */
	protected function stackItem( &$list, $path, $item ) {
		$position =& $list;
		if ( $path ) {
			foreach ( $path as $key ) {
				$position =& $position[$key];
			}
		}
		$position[] = $item;
	}

	/**
	 * @param array $tagset
	 * @param int $depth
	 * @return string
	 */
	protected function makeHtml( $tagset, $depth = 0 ) {
		$html = '';

		foreach ( $tagset as $key => $val ) {
			if ( is_array( $val ) ) {
				$html .= $this->outputOption(
					$key, '',
					[
						'disabled' => 'disabled',
						'style' => 'color: GrayText', // for MSIE
					],
					$depth
				);
				$html .= $this->makeHtml( $val, $depth + 1 );
			} else {
				$html .= $this->outputOption(
					$val->text, $val->template,
					[ 'title' => '{{' . $val->template . '}}' ],
					$depth
				);
			}
		}

		return $html;
	}

	/**
	 * @param string $message
	 * @param string $value
	 * @param null|array $attribs
	 * @param int $depth
	 * @return string
	 */
	protected function outputOption( $message, $value, $attribs = null, $depth = 0 ) {
		$msgObj = $this->msg( $message );
		$text = $msgObj->exists() ? $msgObj->text() : $message;
		$attribs['value'] = $value;
		if ( $value === $this->selected ) {
			$attribs['selected'] = 'selected';
		}

		$val = str_repeat( /* &nbsp */ "\u{00A0}", $depth * 2 ) . $text;
		return str_repeat( "\t", $depth ) . Xml::element( 'option', $attribs, $val ) . "\n";
	}

	/**#@-*/

	/**
	 * Accessor for $this->lines
	 *
	 * @return array
	 */
	public function getLines() {
		return $this->lines;
	}

	/**
	 * Accessor for $this->lines
	 *
	 * @return array
	 *
	 * @deprecated since 1.31 Use getLines() instead
	 */
	public function getLicenses() {
		return $this->getLines();
	}

	/**
	 * @inheritDoc
	 */
	public function getInputHTML( $value ) {
		$this->selected = $value;

		// add a default "no license selected" option
		$default = $this->buildLine( '|nolicense' );
		array_unshift( $this->lines, $default );

		$html = $this->makeHtml( $this->getLines() );

		$attribs = [
			'name' => $this->mName,
			'id' => $this->mID
		];
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attribs['disabled'] = 'disabled';
		}

		$html = Html::rawElement( 'select', $attribs, $html );

		// remove default "no license selected" from lines again
		array_shift( $this->lines );

		return $html;
	}
}
