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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * A License class for use on Special:Upload
 */
class Licenses extends HTMLFormField {
	/** @var string */
	protected $msg;

	/** @var array */
	protected $licenses = [];

	/** @var string */
	protected $html;
	/**#@-*/

	/**
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->msg = empty( $params['licenses'] )
			? wfMessage( 'licenses' )->inContentLanguage()->plain()
			: $params['licenses'];
		$this->selected = null;

		$this->makeLicenses();
	}

	/**
	 * @private
	 */
	protected function makeLicenses() {
		$levels = [];
		$lines = explode( "\n", $this->msg );

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			} else {
				list( $level, $line ) = $this->trimStars( $line );

				if ( strpos( $line, '|' ) !== false ) {
					$obj = new License( $line );
					$this->stackItem( $this->licenses, $levels, $obj );
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
	 * @param array $list
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
	 */
	protected function makeHtml( $tagset, $depth = 0 ) {
		foreach ( $tagset as $key => $val ) {
			if ( is_array( $val ) ) {
				$this->html .= $this->outputOption(
					$key, '',
					[
						'disabled' => 'disabled',
						'style' => 'color: GrayText', // for MSIE
					],
					$depth
				);
				$this->makeHtml( $val, $depth + 1 );
			} else {
				$this->html .= $this->outputOption(
					$val->text, $val->template,
					[ 'title' => '{{' . $val->template . '}}' ],
					$depth
				);
			}
		}
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

		$val = str_repeat( /* &nbsp */ "\xc2\xa0", $depth * 2 ) . $text;
		return str_repeat( "\t", $depth ) . Xml::element( 'option', $attribs, $val ) . "\n";
	}

	/**#@-*/

	/**
	 *  Accessor for $this->licenses
	 *
	 * @return array
	 */
	public function getLicenses() {
		return $this->licenses;
	}

	/**
	 * Accessor for $this->html
	 *
	 * @param bool $value
	 *
	 * @return string
	 */
	public function getInputHTML( $value ) {
		$this->selected = $value;

		$this->html = $this->outputOption( wfMessage( 'nolicense' )->text(), '',
			(bool)$this->selected ? null : [ 'selected' => 'selected' ] );
		$this->makeHtml( $this->getLicenses() );

		$attribs = [
			'name' => $this->mName,
			'id' => $this->mID
		];
		if ( !empty( $this->mParams['disabled'] ) ) {
			$attibs['disabled'] = 'disabled';
		}

		return Html::rawElement( 'select', $attribs, $this->html );
	}
}

/**
 * A License class for use on Special:Upload (represents a single type of license).
 */
class License {
	/** @var string */
	public $template;

	/** @var string */
	public $text;

	/**
	 * @param string $str License name??
	 */
	function __construct( $str ) {
		list( $text, $template ) = explode( '|', strrev( $str ), 2 );

		$this->template = strrev( $template );
		$this->text = strrev( $text );
	}
}
