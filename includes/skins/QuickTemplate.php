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
 */
use MediaWiki\MediaWikiServices;

/**
 * Generic wrapper for template functions, with interface
 * compatible with what we use of PHPTAL 0.7.
 * @ingroup Skins
 */
abstract class QuickTemplate {

	/** @var Config $config */
	protected $config;

	/**
	 * @param Config $config
	 */
	function __construct( Config $config = null ) {
		$this->data = [];
		$this->translator = new MediaWikiI18N();
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' was called with no Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		$this->config = $config;
	}

	/**
	 * Sets the value $value to $name
	 * @param string $name
	 * @param mixed $value
	 */
	public function set( $name, $value ) {
		$this->data[$name] = $value;
	}

	/**
	* extends the value of data with name $name with the value $value
	* @since 1.25
	* @param string $name
	* @param mixed $value
	*/
	public function extend( $name, $value ) {
		if ( $this->haveData( $name ) ) {
			$this->data[$name] = $this->data[$name] . $value;
		} else {
			$this->data[$name] = $value;
		}
	}

	/**
	 * Gets the template data requested
	 * @since 1.22
	 * @param string $name Key for the data
	 * @param mixed $default Optional default (or null)
	 * @return mixed The value of the data requested or the deafult
	 */
	public function get( $name, $default = null ) {
		if ( isset( $this->data[$name] ) ) {
			return $this->data[$name];
		} else {
			return $default;
		}
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setRef( $name, &$value ) {
		$this->data[$name] =& $value;
	}

	/**
	 * @param MediaWikiI18N $t
	 */
	public function setTranslator( &$t ) {
		$this->translator = &$t;
	}

	/**
	 * Main function, used by classes that subclass QuickTemplate
	 * to show the actual HTML output
	 */
	abstract public function execute();

	/**
	 * @private
	 * @param string $str
	 */
	function text( $str ) {
		echo htmlspecialchars( $this->data[$str] );
	}

	/**
	 * @private
	 * @param string $str
	 */
	function html( $str ) {
		echo $this->data[$str];
	}

	/**
	 * @private
	 * @param string $str
	 */
	function msg( $str ) {
		echo htmlspecialchars( $this->translator->translate( $str ) );
	}

	/**
	 * @private
	 * @param string $str
	 */
	function msgHtml( $str ) {
		echo $this->translator->translate( $str );
	}

	/**
	 * An ugly, ugly hack.
	 * @private
	 * @param string $str
	 */
	function msgWiki( $str ) {
		global $wgOut;

		$text = $this->translator->translate( $str );
		echo $wgOut->parse( $text );
	}

	/**
	 * @private
	 * @param string $str
	 * @return bool
	 */
	function haveData( $str ) {
		return isset( $this->data[$str] );
	}

	/**
	 * @private
	 *
	 * @param string $str
	 * @return bool
	 */
	function haveMsg( $str ) {
		$msg = $this->translator->translate( $str );
		return ( $msg != '-' ) && ( $msg != '' ); # ????
	}

	/**
	 * Get the Skin object related to this object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		return $this->data['skin'];
	}

	/**
	 * Fetch the output of a QuickTemplate and return it
	 *
	 * @since 1.23
	 * @return string
	 */
	public function getHTML() {
		ob_start();
		$this->execute();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
