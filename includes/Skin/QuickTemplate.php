<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Skin;

use MediaWiki\Config\Config;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\MediaWikiServices;

/**
 * PHP-based skin template that holds data.
 *
 * Modern usage with returned output:
 *
 *     class MyTemplate extends QuickTemplate {
 *         public function execute() {
 *             $html = 'Hello, ' . Html::element( 'strong', [], $this->get( 'name' ) );
 *             echo $html;
 *         }
 *     }
 *     $tpl = new MyTemplate();
 *     $tpl->set( 'name', 'World' );
 *     $output = $tpl->getHTML();
 *
 * Classic usage with native HTML echo:
 *
 *     class MyTemplate extends QuickTemplate {
 *         public function execute() { ?>
 *
 *             Hello, <strong><?php $this->text( 'name' ); ?></strong>
 *
 *         <?php
 *         }
 *     }
 *     $tpl = new MyTemplate();
 *     $tpl->set( 'name', 'World' );
 *
 *     $tpl->execute(); // echo output
 *
 *
 * QuickTemplate was originally developed as drop-in replacement for PHPTAL 0.7 (<http://phptal.org/>).
 *
 * @stable to extend
 * @ingroup Skins
 */
abstract class QuickTemplate {
	use ProtectedHookAccessorTrait;

	/**
	 * @var array
	 */
	public $data;

	/** @var Config */
	protected $config;

	/** @var array */
	private $deprecated = [];

	/**
	 * @param Config|null $config
	 */
	public function __construct( ?Config $config = null ) {
		$this->data = [];
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' was called with no Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		$this->config = $config;
	}

	/**
	 * Sets a template key as deprecated.
	 *
	 * @internal only for usage inside Skin and SkinTemplate class.
	 * @param string $name
	 * @param string $version When it was deprecated e.g. 1.38
	 */
	public function deprecate( string $name, string $version ) {
		$this->deprecated[$name] = $version;
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
	 * Checks if the template key is deprecated
	 */
	private function checkDeprecationStatus( string $name ) {
		$deprecated = $this->deprecated[ $name ] ?? false;
		if ( $deprecated ) {
			wfDeprecated(
				'QuickTemplate::(get/html/text/haveData) with parameter `' . $name . '`',
				$deprecated
			);
		}
	}

	/**
	 * Gets the template data requested
	 * @since 1.22
	 * @param string $name Key for the data
	 * @param mixed|null $default Optional default (or null)
	 * @return mixed The value of the data requested or the default
	 * @return-taint onlysafefor_htmlnoent
	 */
	public function get( $name, $default = null ) {
		$this->checkDeprecationStatus( $name );
		return $this->data[$name] ?? $default;
	}

	/**
	 * Main function, used by classes that subclass QuickTemplate
	 * to show the actual HTML output
	 */
	abstract public function execute();

	/**
	 * @param string $str
	 * @suppress SecurityCheck-DoubleEscaped $this->data can be either
	 */
	protected function text( $str ) {
		$this->checkDeprecationStatus( $str );
		echo htmlspecialchars( $this->data[$str] );
	}

	/**
	 * @param string $str
	 * @suppress SecurityCheck-XSS phan-taint-check cannot tell if $str is pre-escaped
	 */
	public function html( $str ) {
		$this->checkDeprecationStatus( $str );
		echo $this->data[$str];
	}

	/**
	 * @param string $msgKey
	 */
	public function msg( $msgKey ) {
		echo htmlspecialchars( wfMessage( $msgKey )->text() );
	}

	/**
	 * @param string $str
	 * @return bool
	 */
	private function haveData( $str ) {
		$this->checkDeprecationStatus( $str );
		return isset( $this->data[$str] );
	}

	/**
	 * @param string $msgKey
	 * @return bool
	 */
	protected function haveMsg( $msgKey ) {
		return !wfMessage( $msgKey )->isDisabled();
	}

	/**
	 * Get the Skin object related to this object
	 *
	 * @return SkinTemplate
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

/** @deprecated class alias since 1.44 */
class_alias( QuickTemplate::class, 'QuickTemplate' );
