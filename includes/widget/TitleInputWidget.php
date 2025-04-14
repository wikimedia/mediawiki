<?php

namespace MediaWiki\Widget;

use OOUI\TextInputWidget;

/**
 * Title input widget.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class TitleInputWidget extends TextInputWidget {

	/** @var int|null */
	protected $namespace = null;
	/** @var bool|null */
	protected $relative = null;
	/** @var bool|null */
	protected $suggestions = null;
	/** @var bool|null */
	protected $highlightFirst = null;
	/** @var bool|null */
	protected $validateTitle = null;

	/**
	 * @param array $config Configuration options
	 *   - int|null $config['namespace'] Namespace to prepend to queries
	 *   - bool|null $config['relative'] If a namespace is set,
	 *     return a title relative to it (default: true)
	 *   - bool|null $config['suggestions'] Display search suggestions (default: true)
	 *   - bool|null $config['highlightFirst'] Automatically highlight
	 *     the first result (default: true)
	 *   - bool|null $config['validateTitle'] Whether the input must
	 *     be a valid title (default: true)
	 */
	public function __construct( array $config = [] ) {
		parent::__construct(
			array_merge( [ 'maxLength' => 255 ], $config )
		);

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
		if ( isset( $config['highlightFirst'] ) ) {
			$this->highlightFirst = $config['highlightFirst'];
		}
		if ( isset( $config['validateTitle'] ) ) {
			$this->validateTitle = $config['validateTitle'];
		}

		// Initialization
		$this->addClasses( [ 'mw-widget-titleInputWidget' ] );
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.TitleInputWidget';
	}

	/** @inheritDoc */
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
		if ( $this->highlightFirst !== null ) {
			$config['highlightFirst'] = $this->highlightFirst;
		}
		if ( $this->validateTitle !== null ) {
			$config['validateTitle'] = $this->validateTitle;
		}
		$config['$overlay'] = true;
		return parent::getConfig( $config );
	}
}
