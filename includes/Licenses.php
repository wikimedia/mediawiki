<?php
/**
 * A License class for use on Special:Upload
 *
 * @ingroup SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class Licenses extends HTMLFormField {
	/**
	 * @var string
	 */
	protected $msg;

	/**
	 * @var array
	 */
	protected $licenses = array();

	/**
	 * @var string
	 */
	protected $html;
	/**#@-*/

	/**
	 * Constructor
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->msg = empty( $params['licenses'] ) ? wfMsgForContent( 'licenses' ) : $params['licenses'];
		$this->selected = null;

		$this->makeLicenses();
	}

	/**#@+
	 * @private
	 */
	protected function makeLicenses() {
		$levels = array();
		$lines = explode( "\n", $this->msg );

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 )
				continue;
			else {
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
					} else if ( $level > count( $levels ) ) {
						$levels[] = $line;
					}
				}
			}
		}
	}

	protected function trimStars( $str ) {
		$numStars = strspn( $str, '*' );
		return array( $numStars, ltrim( substr( $str, $numStars ), ' ' ) );
	}

	protected function stackItem( &$list, $path, $item ) {
		$position =& $list;
		if ( $path )
			foreach( $path as $key )
				$position =& $position[$key];
		$position[] = $item;
	}

	protected function makeHtml( $tagset, $depth = 0 ) {
		foreach ( $tagset as $key => $val )
			if ( is_array( $val ) ) {
				$this->html .= $this->outputOption(
					$this->msg( $key ), '',
					array(
						'disabled' => 'disabled',
						'style' => 'color: GrayText', // for MSIE
					),
					$depth
				);
				$this->makeHtml( $val, $depth + 1 );
			} else {
				$this->html .= $this->outputOption(
					$this->msg( $val->text ), $val->template,
					array( 'title' => '{{' . $val->template . '}}' ),
					$depth
				);
			}
	}

	protected function outputOption( $text, $value, $attribs = null, $depth = 0 ) {
		$attribs['value'] = $value;
		if ( $value === $this->selected )
			$attribs['selected'] = 'selected';
		$val = str_repeat( /* &nbsp */ "\xc2\xa0", $depth * 2 ) . $text;
		return str_repeat( "\t", $depth ) . Xml::element( 'option', $attribs, $val ) . "\n";
	}

	protected function msg( $str ) {
		$msg = wfMessage( $str );
		return $msg->exists() ? $msg->text() : $str;
	}

	/**#@-*/

	/**
	 *  Accessor for $this->licenses
	 *
	 * @return array
	 */
	public function getLicenses() { return $this->licenses; }

	/**
	 * Accessor for $this->html
	 *
	 * @return string
	 */
	public function getInputHTML( $value ) {
		$this->selected = $value;

		$this->html = $this->outputOption( wfMsg( 'nolicense' ), '',
			(bool)$this->selected ? null : array( 'selected' => 'selected' ) );
		$this->makeHtml( $this->getLicenses() );

		$attribs = array(
			'name' => $this->mName,
			'id' => $this->mID
		);
		if ( !empty( $this->mParams['disabled'] ) )
			$attibs['disabled'] = 'disabled';

		return Html::rawElement( 'select', $attribs, $this->html );
	}
}

/**
 * A License class for use on Special:Upload (represents a single type of license).
 */
class License {
	/**
	 * @var string
	 */
	var $template;

	/**
	 * @var string
	 */
	var $text;

	/**
	 * Constructor
	 *
	 * @param $str String: license name??
	 */
	function __construct( $str ) {
		list( $text, $template ) = explode( '|', strrev( $str ), 2 );

		$this->template = strrev( $template );
		$this->text = strrev( $text );
	}
}
