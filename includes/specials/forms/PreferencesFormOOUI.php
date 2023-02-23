<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Form to edit user preferences.
 *
 * @since 1.32
 */
class PreferencesFormOOUI extends OOUIHTMLForm {
	/** @var bool Override default value from HTMLForm */
	protected $mSubSectionBeforeFields = false;

	/** @var User|null */
	private $modifiedUser;

	/** @var bool */
	private $privateInfoEditable = true;

	/** @var bool */
	private $optionsEditable = true;

	/**
	 * @param User $user
	 */
	public function setModifiedUser( $user ) {
		$this->modifiedUser = $user;
	}

	/**
	 * @return User
	 */
	public function getModifiedUser() {
		if ( $this->modifiedUser === null ) {
			return $this->getUser();
		} else {
			return $this->modifiedUser;
		}
	}

	/**
	 * @return bool
	 */
	public function isPrivateInfoEditable() {
		return $this->privateInfoEditable;
	}

	/**
	 * Whether the
	 * @param bool $editable
	 */
	public function setPrivateInfoEditable( $editable ) {
		$this->privateInfoEditable = $editable;
		$this->suppressDefaultSubmit( !$this->privateInfoEditable && !$this->optionsEditable );
	}

	/**
	 * @return bool
	 */
	public function areOptionsEditable() {
		return $this->optionsEditable;
	}

	/**
	 * @param bool $optionsEditable
	 */
	public function setOptionsEditable( $optionsEditable ) {
		$this->optionsEditable = $optionsEditable;
		$this->suppressDefaultSubmit( !$this->privateInfoEditable && !$this->optionsEditable );
	}

	/**
	 * Get extra parameters for the query string when redirecting after
	 * successful save.
	 *
	 * @return array
	 */
	public function getExtraSuccessRedirectParameters() {
		return [];
	}

	/**
	 * @param string $html
	 * @return string
	 */
	public function wrapForm( $html ) {
		$html = Xml::tags( 'div', [ 'id' => 'preferences' ], $html );

		return parent::wrapForm( $html );
	}

	/**
	 * Separate multi-option preferences into multiple preferences, since we
	 * have to store them separately
	 * @param array $data
	 * @return array
	 */
	public function filterDataForSubmit( $data ) {
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( $field instanceof HTMLNestedFilterable ) {
				$info = $field->mParams;
				$prefix = $info['prefix'] ?? $fieldname;
				foreach ( $field->filterDataForSubmit( $data[$fieldname] ) as $key => $value ) {
					$data["$prefix$key"] = $value;
				}
				unset( $data[$fieldname] );
			}
		}

		return $data;
	}

	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		$layout = parent::wrapFieldSetSection( $legend, $section, $attributes, $isRoot );

		$layout->addClasses( [ 'mw-prefs-fieldset-wrapper' ] );
		$layout->removeClasses( [ 'oo-ui-panelLayout-framed' ] );

		return $layout;
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	public function getBody() {
		$out = $this->getOutput();
		$skin = $out->getSkin();
		$this->getHookRunner()->onPreferencesGetLayout( $useMobileLayout,
			$skin->getSkinName(), [ 'isResponsive' => $skin->isResponsive() ] );

		if ( $useMobileLayout ) {
			// Import the icons used in the mobile view
			$out->addModuleStyles(
				[
					'oojs-ui.styles.icons-user',
					'oojs-ui.styles.icons-editing-core',
					'oojs-ui.styles.icons-editing-advanced',
					'oojs-ui.styles.icons-wikimediaui',
					'oojs-ui.styles.icons-content',
					'oojs-ui.styles.icons-moderation',
					'oojs-ui.styles.icons-interactions',
					'oojs-ui.styles.icons-movement',
					'oojs-ui.styles.icons-wikimedia',
					'oojs-ui.styles.icons-media',
					'oojs-ui.styles.icons-accessibility',
					'oojs-ui.styles.icons-layout',
				]
			);
			$form = $this->createMobilePreferencesForm();
		} else {
			$form = $this->createDesktopPreferencesForm();
		}

		$header = $this->formatFormHeader();

		return $header . $form;
	}

	/**
	 * Get the "<legend>" for a given section key. Normally this is the
	 * prefs-$key message but we'll allow extensions to override it.
	 * @param string $key
	 * @return string
	 */
	public function getLegend( $key ) {
		$legend = parent::getLegend( $key );
		$this->getHookRunner()->onPreferencesGetLegend( $this, $key, $legend );
		return $legend;
	}

	/**
	 * Get the keys of each top level preference section.
	 * @return string[] List of section keys
	 */
	public function getPreferenceSections() {
		return array_keys( array_filter( $this->mFieldTree, 'is_array' ) );
	}

	/**
	 * Create the preferences form for a mobile layout.
	 * @return string
	 */
	private function createMobilePreferencesForm() {
		$prefPanels = [];
		$iconNames = [
			'personal' => 'userAvatar',
			'rendering' => 'palette',
			'editing' => 'edit',
			'rc' => 'recentChanges',
			'watchlist' => 'watchlist',
			'searchoptions' => 'search',
			'misc' => '',
		];
		$hookIcons = [];
		// Get icons from extensions that have their own sections
		$this->getHookRunner()->onPreferencesGetIcon( $hookIcons );
		$iconNames += $hookIcons;

		foreach ( $this->mFieldTree as $key => $val ) {
			if ( !is_array( $val ) ) {
				wfDebug( __METHOD__ . " encountered a field not attached to a section: '$key'" );
				continue;
			}
			$label = $this->getLegend( $key );
			$content =
				$this->getHeaderHtml( $key ) .
				$this->displaySection(
					$val,
					"",
					"mw-prefsection-$key-"
				) .
				$this->getFooterHtml( $key );

			$prefPanel = new OOUI\PanelLayout( [
				'expanded' => false,
				'content' => [],
				'framed' => false,
				'classes' => [
					'mw-mobile-prefsection',
					'mw-prefs-section-fieldset',
				],
				'tagName' => 'fieldset',
			] );

			$iconHeaderDiv = ( new OOUI\Tag( 'div' ) )
				->addClasses( [ 'mw-prefs-header-container' ] );
			$iconExists = array_key_exists( $key, $iconNames );
			if ( $iconExists ) {
				$iconName = $iconNames[ $key ];
			} else {
				$iconName = "settings";
			}
			$spanIcon = new OOUI\IconWidget( [
				'icon' => $iconName,
				'label' => $label,
				'title' => $label,
				'classes' => [ 'mw-prefs-icon' ],
			] );
			$prefTitle = ( new OOUI\Tag( 'h5' ) )->appendContent( $label )->addClasses( [ 'prefs-title' ] );
			$iconHeaderDiv->appendContent( $spanIcon );
			$iconHeaderDiv->appendContent( $prefTitle );
			$prefPanel->appendContent( $iconHeaderDiv );
			$prefDescriptionMsg = $this->msg( "prefs-description-" . $key );
			$prefDescription = $prefDescriptionMsg->exists() ? $prefDescriptionMsg->text() : "";
			$prefPanel->appendContent( ( new OOUI\Tag( 'p' ) )
				->appendContent( $prefDescription )
				->addClasses( [ 'mw-prefs-description' ] )
			);
			$contentDiv = ( new OOUI\Tag( 'div' ) );
			$contentDiv->addClasses( [ 'mw-prefs-content-page' ] );
			$contentDiv->setAttributes( [
				'id' => 'mw-mobile-prefs-' . $key . '-content'
			] );
			$contentHeader = ( new OOUI\Tag( 'div' ) )->setAttributes( [
				'id' => 'mw-mobile-prefs-' . $key . '-head'
			] );
			$contentHeader->addClasses( [ 'mw-prefs-content-head' ] );
			$contentHeaderTitle = ( new OOUI\Tag( 'h5' ) )->setAttributes( [
				'id' => 'mw-mobile-prefs-' . $key . '-title',
			] );
			$contentHeaderTitle->appendContent( $label )->addClasses( [ 'mw-prefs-header-title' ] );
			$formContent = new OOUI\Widget( [
				'content' => new OOUI\HtmlSnippet( $content )
			] );
			$hiddenForm = ( new OOUI\Tag( 'div' ) )->appendContent( $formContent );
			$contentHeader->appendContent( $contentHeaderTitle );
			$contentDiv->appendContent( $contentHeader );
			$contentDiv->appendContent( $hiddenForm );
			$prefPanel->appendContent( $contentDiv );
			$prefPanel->setAttributes( [
				'id' => 'mw-mobile-prefs-' . $key,
			] );
			$prefPanel->setInfusable( true );
			$prefPanels[] = $prefPanel;
		}

		$form = new OOUI\StackLayout( [
			'items' => $prefPanels,
			'continuous' => true,
			'expanded' => true,
			'classes' => [ 'mw-mobile-preferences-container' ]
		] );
		$form->setAttributes( [
			'id' => 'mw-prefs-container',
		] );
		$form->setInfusable( true );

		return $form;
	}

	/**
	 * Create the preferences form for a desktop layout.
	 * @return string
	 */
	private function createDesktopPreferencesForm() {
		$tabPanels = [];
		foreach ( $this->mFieldTree as $key => $val ) {
			if ( !is_array( $val ) ) {
				wfDebug( __METHOD__ . " encountered a field not attached to a section: '$key'" );
				continue;
			}
			$label = $this->getLegend( $key );
			$content =
				$this->getHeaderHtml( $key ) .
				$this->displaySection(
					$val,
					"",
					"mw-prefsection-$key-"
				) .
				$this->getFooterHtml( $key );

			$tabPanels[] = new OOUI\TabPanelLayout( 'mw-prefsection-' . $key, [
				'classes' => [ 'mw-htmlform-autoinfuse-lazy' ],
				'label' => $label,
				'content' => new OOUI\FieldsetLayout( [
					'classes' => [ 'mw-prefs-section-fieldset' ],
					'id' => "mw-prefsection-$key",
					'label' => $label,
					'items' => [
						new OOUI\Widget( [
							'content' => new OOUI\HtmlSnippet( $content )
						] ),
					],
				] ),
				'expanded' => false,
				'framed' => true,
			] );
		}

		$indexLayout = new OOUI\IndexLayout( [
			'infusable' => true,
			'expanded' => false,
			'autoFocus' => false,
			'classes' => [ 'mw-prefs-tabs' ],
		] );
		$indexLayout->addTabPanels( $tabPanels );

		$form = new OOUI\PanelLayout( [
			'framed' => true,
			'expanded' => false,
			'classes' => [ 'mw-prefs-tabs-wrapper' ],
			'content' => $indexLayout
		] );

		return $form;
	}
}
