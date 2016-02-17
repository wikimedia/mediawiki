<?php
/**
 * MediaWiki Widgets – NamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * Namespace input widget. Displays a dropdown box with the choice of available namespaces.
 */
class NamespaceInputWidget extends \OOUI\DropdownInputWidget {

	protected $includeAllValue = null;

	/**
	 * @param array $config Configuration options
	 * @param string $config['includeAllValue'] If specified, add a "all namespaces" option to the
	 *     namespace dropdown, and use this as the input value for it
	 * @param number[] $config['exclude'] List of namespace numbers to exclude from the selector
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config['options'] = $this->getNamespaceDropdownOptions( $config );

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->includeAllValue = isset( $config['includeAllValue'] ) ? $config['includeAllValue'] : null;
		$this->exclude = isset( $config['exclude'] ) ? $config['exclude'] : [];

		// Initialization
		$this->addClasses( [ 'mw-widget-namespaceInputWidget' ] );
	}

	protected function getNamespaceDropdownOptions( array $config ) {
		$namespaceOptionsParams = [
			'all' => isset( $config['includeAllValue'] ) ? $config['includeAllValue'] : null,
			'exclude' => isset( $config['exclude'] ) ? $config['exclude'] : null
		];
		$namespaceOptions = \Html::namespaceSelectorOptions( $namespaceOptionsParams );

		$options = [];
		foreach ( $namespaceOptions as $id => $name ) {
			$options[] = [
				'data' => (string)$id,
				'label' => $name,
			];
		}

		return $options;
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.NamespaceInputWidget';
	}

	public function getConfig( &$config ) {
		$config['includeAllValue'] = $this->includeAllValue;
		$config['exclude'] = $this->exclude;
		// Skip DropdownInputWidget's getConfig(), we don't need 'options' config
		return \OOUI\InputWidget::getConfig( $config );
	}
}
