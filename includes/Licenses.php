<?php
/**
 * A License class for use on Special:Upload
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

class Licenses {
	/**#@+
	 * @access private
	 */
	/**
	 * @var string
	 */
	var $msg;
	
	/**
	 * @var array
	 */
	var $licenses = array();

	/**
	 * @var string
	 */
	var $html;

	/**
	 * Constrictor
	 *
	 * @param string $str The string to build the licenses member from, will use
	 *                    wfMsgForContent( 'licenses' ) if null (default: null)
	 */
	function Licenses( $str = null ) {
		// PHP sucks, this should be possible in the constructor
		$this->msg = is_null( $str ) ? wfMsgForContent( 'licenses' ) : $str;
		$this->html = '';

		$this->makeLicenses();
		$tmp = $this->getLicenses();
		$this->makeHtml( $tmp );
	}
	
	/**#@+
	 * @access private
	 */
	function makeLicenses() {
		$levels = array();
		$lines = explode( "\n", $this->msg );
		
		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 )
				continue;
			else {
				list( $level, $line ) = $this->trimStars( $line );
				
				if ( strpos( $line, '|' ) !== false ) {
					$obj = new License( $line );
					// TODO: Do this without using eval()
					eval( '$this->licenses' . $this->makeIndexes( $levels ) . '[] = $obj;' );
				} else {
					if ( $level < count( $levels ) )
						$levels = array_slice( $levels, count( $levels ) - $level );
					if ( $level == count( $levels ) )
						$levels[$level - 1] = $line;
					else if ( $level > count( $levels ) )
						$levels[] = $line;
	
				}
			}
		}
	}
	
	function trimStars( $str ) {
		$i = $count = 0;
		
		while ($str[$i++] == '*')
			++$count;
	
		return array( $count, ltrim( $str, '* ' ) );
	}
	
	function makeIndexes( $arr ) {
		$str = '';
	
		foreach ( $arr as $item )
			$str .= '["' . addslashes( $item ) . '"]';
		
		return $str;
	}

	function makeHtml( &$tagset, $depth = 0 ) {
		foreach ( $tagset as $key => $val )
			if ( is_array( $val ) ) {
				
				$this->html .= $this->outputOption(
					$this->msg( $key ),
					array(
						'value' => ''
					),
					$depth
				);
				$this->makeHtml( $val, $depth + 1 );
			} else {
				$this->html .= $this->outputOption(
					$this->msg( $val->text ),
					array(
						'value' => $val->template
					),
					$depth
				);
			}
	}

	function outputOption( $val, $attribs = null, $depth ) {
		$val = str_repeat( /* &nbsp */ "\xc2\xa0", $depth ) . $val;
		return str_repeat( "\t", $depth ) . wfElement( 'option', $attribs, $val ) . "\n";
	}
	
	function msg( $str ) {
		$out = wfMsg( $str );
		return wfNoMsg( $str, $out ) ? $str : $out;
	}
	
	/**#@-*/
	
	/**
	 *  Accessor for $this->licenses
	 *
	 * @return array
	 */
	function getLicenses() { return $this->licenses; }

	/**
	 * Accessor for $this->html
	 *
	 * @return string
	 */
	function getHtml() { return $this->html; }
}

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
	 * @param string $str
	 */
	function License( $str ) {
		list( $template, $text ) = explode( '|', $str, 2 );
		
		$this->template = $template;
		$this->text = $text;
	}
}
?>
