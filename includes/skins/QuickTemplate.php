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

	/**
	 * @var array
	 */
	public $data;

	/** @var Config $config */
	protected $config;

	/**
	 * @param Config|null $config
	 */
	function __construct( Config $config = null ) {
		$this->data = [];
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
			$this->data[$name] .= $value;
		} else {
			$this->data[$name] = $value;
		}
	}

	/**
	 * Gets the template data requested
	 * @since 1.22
	 * @param string $name Key for the data
	 * @param mixed|null $default Optional default (or null)
	 * @return mixed The value of the data requested or the deafult
	 * @return-taint onlysafefor_htmlnoent
	 */
	public function get( $name, $default = null ) {
		return $this->data[$name] ?? $default;
	}

	/**
	 * @deprecated since 1.31 This function is a now-redundant optimisation intended
	 *  for very old versions of PHP. The use of references here makes the code
	 *  more fragile and is incompatible with plans like T140664. Use set() instead.
	 * @param string $name
	 * @param mixed &$value
	 */
	public function setRef( $name, &$value ) {
		wfDeprecated( __METHOD__, '1.31' );
		$this->data[$name] =& $value;
	}

	/**
	 * Main function, used by classes that subclass QuickTemplate
	 * to show the actual HTML output
	 */
	abstract public function execute();

	/**
	 * @private
	 * @param string $str
	 * @suppress SecurityCheck-DoubleEscaped $this->data can be either
	 */
	function text( $str ) {
		echo htmlspecialchars( $this->data[$str] );
	}

	/**
	 * @private
	 * @param string $str
	 * @suppress SecurityCheck-XSS phan-taint-check cannot tell if $str is pre-escaped
	 */
	function html( $str ) {
		echo $this->data[$str];
	}

	/**
	 * @private
	 * @param string $msgKey
	 */
	function msg( $msgKey ) {
		echo htmlspecialchars( wfMessage( $msgKey )->text() );
	}

	/**
	 * @private
	 * @param string $msgKey
	 * @warning You should never use this method. I18n messages should be escaped
	 * @deprecated 1.32 Use ->msg() instead.
	 * @suppress SecurityCheck-XSS
	 * @return-taint exec_html
	 */
	function msgHtml( $msgKey ) {
		wfDeprecated( __METHOD__, '1.32' );
		echo wfMessage( $msgKey )->text();
	}

	/**
	 * An ugly, ugly hack.
	 * @deprecated since 1.33 Use ->msg() instead.
	 * @param string $msgKey
	 */
	function msgWiki( $msgKey ) {
		// TODO: Add wfDeprecated( __METHOD__, '1.33' ) after 1.33 got released
		global $wgOut;

		$text = wfMessage( $msgKey )->plain();
		echo $wgOut->parseAsInterface( $text );
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
	 * @param string $msgKey
	 * @return bool
	 */
	function haveMsg( $msgKey ) {
		$msg = wfMessage( $msgKey );
		return $msg->exists() && !$msg->isDisabled();
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
