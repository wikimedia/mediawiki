<?php

namespace MediaWiki\Widget;

use MediaWiki\Html\Html;
use OOUI\DropdownInputWidget;
use OOUI\InputWidget;

/**
 * Namespace input widget. Displays a dropdown box with the choice of available namespaces.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class NamespaceInputWidget extends DropdownInputWidget {
	/** @var string */
	protected $includeAllValue;
	/** @var bool */
	protected $userLang;
	/** @var int[] */
	protected $exclude;
	/** @var int[]|null */
	protected $include;

	/**
	 * @param array $config Configuration options
	 *   - string $config['includeAllValue'] If specified, add a "all namespaces" option to the
	 *     namespace dropdown, and use this as the input value for it
	 *   - bool $config['userLang'] Display namespaces in user language
	 *   - int[] $config['exclude'] List of namespace numbers to exclude from the selector
	 *   - int[]|null $config['include'] List of namespace numbers to only include in the selector, or null
	 *     to not apply this filter.
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config['options'] = $this->getNamespaceDropdownOptions( $config );

		parent::__construct( $config );

		// Properties
		$this->includeAllValue = $config['includeAllValue'] ?? null;
		$this->userLang = $config['userLang'] ?? false;
		$this->exclude = $config['exclude'] ?? [];
		$this->include = $config['include'] ?? null;

		// Initialization
		$this->addClasses( [ 'mw-widget-namespaceInputWidget' ] );
	}

	protected function getNamespaceDropdownOptions( array $config ): array {
		$namespaceOptionsParams = [
			'all' => $config['includeAllValue'] ?? null,
			'in-user-lang' => $config['userLang'] ?? false,
			'exclude' => $config['exclude'] ?? null,
			'include' => $config['include'] ?? null,
		];
		$namespaceOptions = Html::namespaceSelectorOptions( $namespaceOptionsParams );

		$options = [];
		foreach ( $namespaceOptions as $id => $name ) {
			$options[] = [
				'data' => (string)$id,
				'label' => $name,
			];
		}

		return $options;
	}

	/** @inheritDoc */
	protected function getJavaScriptClassName() {
		return 'mw.widgets.NamespaceInputWidget';
	}

	/** @inheritDoc */
	public function getConfig( &$config ) {
		$config['includeAllValue'] = $this->includeAllValue;
		$config['userLang'] = $this->userLang;
		$config['exclude'] = $this->exclude;
		$config['include'] = $this->include;
		$config['dropdown']['$overlay'] = true;
		// Skip DropdownInputWidget's getConfig(), we don't need 'options' config
		return InputWidget::getConfig( $config );
	}
}
