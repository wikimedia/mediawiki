<?php
/**
 * MediaWiki Widgets – ComplexTitleInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Complex title input widget.
 */
class ComplexTitleInputWidget extends \OOUI\Widget {

	protected $namespace = null;
	protected $title = null;

	/**
	 * Like TitleInputWidget, but the namespace has to be input through a separate dropdown field.
	 *
	 * @param array $config Configuration options
	 * @param array $config['namespace'] Configuration for the NamespaceInputWidget dropdown with list
	 *     of namespaces
	 * @param array $config['title'] Configuration for the TitleInputWidget text field
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization
		$config = array_merge(
			array(
				'namespace' => array(),
				'title' => array(),
			),
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;
		$this->namespace = new NamespaceInputWidget( $config['namespace'] );
		$this->title = new TitleInputWidget( array_merge(
			$config['title'],
			array(
				// The inner TitleInputWidget shouldn't be infusable, only the ComplexTitleInputWidget itself can be.
				'infusable' => false,
				'relative' => true,
				'namespace' => isset( $config['namespace']['value'] ) ? $config['namespace']['value'] : null,
			)
		) );

		// Initialization
		$this
			->addClasses( array( 'mw-widget-complexTitleInputWidget' ) )
			->appendContent( $this->namespace, $this->title );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.ComplexTitleInputWidget';
	}

	public function getConfig( &$config ) {
		$config['namespace'] = $this->config['namespace'];
		$config['title'] = $this->config['title'];
		return parent::getConfig( $config );
	}
}
