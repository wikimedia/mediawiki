<?php

namespace MediaWiki\Widget;

/**
 * Complex title input widget.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class ComplexTitleInputWidget extends \OOUI\Widget {

	protected $namespace = null;
	protected $title = null;

	/**
	 * Like TitleInputWidget, but the namespace has to be input through a separate dropdown field.
	 *
	 * @param array $config Configuration options
	 *   - array $config['namespace'] Configuration for the NamespaceInputWidget dropdown
	 *     with list of namespaces
	 *   - array $config['title'] Configuration for the TitleInputWidget text field
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge(
			[
				'namespace' => [],
				'title' => [],
			],
			$config
		);

		parent::__construct( $config );

		// Properties
		$this->config = $config;
		$this->namespace = new NamespaceInputWidget( $config['namespace'] );
		$this->title = new TitleInputWidget( array_merge(
			$config['title'],
			[
				'relative' => true,
				'namespace' => isset( $config['namespace']['value'] ) ?
					$config['namespace']['value'] :
					null,
			]
		) );

		// Initialization
		$this
			->addClasses( [ 'mw-widget-complexTitleInputWidget' ] )
			->appendContent( $this->namespace, $this->title );
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.ComplexTitleInputWidget';
	}

	public function getConfig( &$config ) {
		$config['namespace'] = $this->config['namespace'];
		$config['namespace']['dropdown']['$overlay'] = true;
		$config['title'] = $this->config['title'];
		$config['title']['$overlay'] = true;
		return parent::getConfig( $config );
	}
}
