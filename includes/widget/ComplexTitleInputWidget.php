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
	 * @param array $config Configuration options
	 * @param string $config['nameNamespace'] HTML input name for the namespace dropdown box (default:
	 *     'namespace')
	 * @param string $config['nameTitle'] HTML input name for the title text box. (default: 'title')
	 * @param int $config['valueNamespace'] Input value of the namespace dropdown box.
	 * @param int $config['valueTitle'] Input value of the title text box.
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization
		$config = array_merge(
			array(
				'nameNamespace' => 'namespace',
				'nameTitle' => 'title',
				'valueNamespace' => NS_MAIN,
				'valueTitle' => '',
			),
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->namespace = new NamespaceInputWidget( array(
			'nameNamespace' => $config['nameNamespace'],
			'valueNamespace' => $config['valueNamespace'],
			// Disable additional checkboxes
			'nameInvert' => null,
			'nameAssociated' => null,
		) );
		$this->title = new TitleInputWidget( array(
			'name' => $config['nameTitle'],
			'value' => $config['valueTitle'],
			'namespace' => $config['valueNamespace'],
			'relative' => true,
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
		$config['namespace'] = $this->namespace;
		$config['title'] = $this->title;
		return parent::getConfig( $config );
	}
}
