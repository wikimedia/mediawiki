<?php
/**
 * MediaWiki Widgets � UserListInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
namespace MediaWiki\Widget;

/**
 * User input widget.
 */
class UserListInputWidget extends \OOUI\Widget {

	protected $config = [];
	protected $userinput = null;
	protected $userinputLabel = null;
	protected $groupinput = null;
	protected $groupinputLabel = null;
	protected $editsonlyCheck = null;
	protected $editsonlyCheckLabel = null;
	protected $creationsortCheck = null;
	protected $creationsortCheckLabel = null;
	protected $descsortCheck = null;
	protected $descsortCheckLabel = null;
	protected $sortWidget = null;

	/**
	 * @param array $config Configuration options
	 */
	public function __construct( array $config = [] ) {
		// Configuration initialization
		$config = array_merge(
			[
				// Config options for nested widgets
				'userinput' => [],
				'userinputLabel' => [],
				'groupinput' => [],
				'groupinputLabel' => [],
				'editsonlyCheck' => [],
				'editsonlyCheckLabel' => [],
				'creationsortCheck' => [],
				'creationsortCheckLabel' => [],
				'descsortCheck' => [],
				'descsortCheckLabel' => [],
			],
			$config
		);

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->config = $config;

		$userinputWidgetItems = [];

		$this->userinputLabel = $userinputWidgetItems[] = new \OOUI\LabelWidget( [
			'input' => $this->userinput,
		] + $config['userinputLabel']);

		$this->userinput = $userinputWidgetItems[] = new UserInputWidget( $config['userinput'] );

		// add a group dropdown field, if needed
		if ( $config['groupinput'] !== null ) {
			$this->groupinputLabel = $userinputWidgetItems[] = new \OOUI\LabelWidget( [
				'input' => $this->groupinput,
			] + $config['groupinputLabel'] );

			$this->groupinput = $userinputWidgetItems[] =
				new \OOUI\DropdownInputWidget( $config['groupinput'] );
		}

		// $checkboxItems hold all checkbox items that should be added under the input fields
		$checkboxItems = [];
		if ( $config['editsonlyCheck'] !== null ) {
			$this->editsonlyCheck = new \OOUI\CheckboxInputWidget( array_merge(
				$config['editsonlyCheck'],
				[ 'value' => 1 ]
			) );

			$this->editsonlyCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->editsonlyCheck,
				array_merge(
					[ 'align' => 'inline' ],
					$config['editsonlyCheckLabel']
				)
			);
		}
		if ( $config['creationsortCheck'] !== null ) {
			$this->creationsortCheck = new \OOUI\CheckboxInputWidget( array_merge(
				$config['creationsortCheck'],
				[ 'value' => 1 ]
			) );

			$this->creationsortCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->creationsortCheck,
				array_merge(
					[ 'align' => 'inline' ],
					$config['creationsortCheckLabel']
				)
			);
		}
		if ( $config['descsortCheck'] !== null ) {
			$this->descsortCheck = new \OOUI\CheckboxInputWidget( array_merge(
				$config['descsortCheck'],
				[ 'value' => 1 ]
			) );

			$this->creationsortCheckLabel = $checkboxItems[] = new \OOUI\FieldLayout(
				$this->descsortCheck,
				array_merge(
					[ 'align' => 'inline' ],
					$config['descsortCheckLabel']
				)
			);
		}

		if ( $checkboxItems ) {
			// wrap the elements in $checkboxItems into it's own horizontal layout
			$this->sortWidget = new \OOUI\HorizontalLayout( [
				'items' => $checkboxItems
			] );
		}

		if ( $userinputWidgetItems ) {
			$this->userinputWidget = new \OOUI\HorizontalLayout( [
				'items' => $userinputWidgetItems,
			] );
		}

		// add the content's to the widget
		$this->appendContent(
			$this->userinputWidget,
			$this->sortWidget
		);
	}

	protected function getJavaScriptClassName() {
		return 'mw.widgets.UserListInputWidget';
	}

	public function getConfig( &$config ) {
		$config = array_merge(
			$config,
			array_intersect_key(
				$this->config,
				array_fill_keys( [
					'userinput',
					'userinputLabel',
					'groupinput',
					'groupinputLabel',
					'editsonlyCheck',
					'editsonlyCheckLabel',
					'creationsortCheck',
					'creationsortCheckLabel',
					'descsortCheck',
					'descsortCheckLabel'
				], true )
			)
		);
		return parent::getConfig( $config );
	}
}
