<?php
/**
 * MediaWiki Widgets – TitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Title input widget.
 */
class TitleInputWidget extends \OOUI\TextInputWidget {

	protected $namespace = null;
	protected $relative = null;
	protected $suggestions = null;

	/**
	 * @param array $config Configuration options
	 * @param int|null $config['namespace'] Namespace to prepend to queries
	 * @param bool|null $config['relative'] If a namespace is set, return a title relative to it (default: true)
	 * @param bool|null $config['suggestions'] Display search suggestions (default: true)
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct( array_merge( array( 'infusable' => true, 'maxLength' => 255 ), $config ) );

		// Properties, which are ignored in PHP and just shipped back to JS
		if ( isset( $config['namespace'] ) ) {
			$this->namespace = $config['namespace'];
		}
		if ( isset( $config['relative'] ) ) {
			$this->relative = $config['relative'];
		}
		if ( isset( $config['suggestions'] ) ) {
			$this->suggestions = $config['suggestions'];
		}

		// Initialization
		$this->addClasses( array( 'mw-widget-titleInputWidget' ) );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.TitleInputWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->namespace !== null ) {
			$config['namespace'] = $this->namespace;
		}
		if ( $this->relative !== null ) {
			$config['relative'] = $this->relative;
		}
		if ( $this->suggestions !== null ) {
			$config['suggestions'] = $this->suggestions;
		}
		return parent::getConfig( $config );
	}
}
