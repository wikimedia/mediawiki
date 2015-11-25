<?php
/**
 * MediaWiki Widgets – SearchInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Search input widget.
 */
class SearchInputWidget extends TitleInputWidget {

	protected $pushPending = false;
	protected $validateTitle = false;
	protected $highlightFirst = false;

	/**
	 * @param array $config Configuration options
	 * @param int|null $config['pushPending'] Whether the input should be visually marked as
	 *  "pending", while requesting suggestions (default: true)
	 */
	public function __construct( array $config = array() ) {
		// Parent constructor
		parent::__construct(
			array_merge( array( 'infusable' => true, 'maxLength' => 255 ), $config )
		);

		// Properties, which are ignored in PHP and just shipped back to JS
		if ( isset( $config['pushPending'] ) ) {
			$this->pushPending = $config['pushPending'];
		}

		// Initialization
		$this->addClasses( array( 'mw-widget-searchInputWidget' ) );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.SearchInputWidget';
	}

	public function getConfig( &$config ) {
		if ( $this->pushPending !== null ) {
			$config['pushPending'] = $this->pushPending;
		}
		return parent::getConfig( $config );
	}
}
