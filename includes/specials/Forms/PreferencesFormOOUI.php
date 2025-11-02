<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLCheckField;
use MediaWiki\HTMLForm\Field\HTMLToggleSwitchField;
use MediaWiki\HTMLForm\HTMLNestedFilterable;
use MediaWiki\HTMLForm\OOUIHTMLForm;
use MediaWiki\User\User;

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

	/** @var bool */
	private $useMobileLayout;

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

	/** @inheritDoc */
	public function wrapForm( $html ) {
		$html = Html::rawElement( 'div', [ 'id' => 'preferences' ], $html );

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

	/** @inheritDoc */
	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		$layout = parent::wrapFieldSetSection( $legend, $section, $attributes, $isRoot );

		$layout->addClasses( [ 'mw-prefs-fieldset-wrapper' ] );
		$layout->removeClasses( [ 'oo-ui-panelLayout-framed' ] );

		return $layout;
	}

	private function isMobileLayout(): bool {
		if ( $this->useMobileLayout === null ) {
			$skin = $this->getSkin();
			$this->useMobileLayout = false;
			$this->getHookRunner()->onPreferencesGetLayout( $this->useMobileLayout,
				$skin->getSkinName(), [ 'isResponsive' => $skin->isResponsive() ] );
		}
		return $this->useMobileLayout;
	}

	/**
	 * @inheritDoc
	 */
	public function addFields( $descriptor ) {
		// Replace checkbox fields with toggle switchs on Special:Preferences
		if ( $this->isMobileLayout() && $this->getTitle()->isSpecial( 'Preferences' ) ) {
			foreach ( $descriptor as $_ => &$info ) {
				if ( isset( $info['type'] ) && in_array( $info['type'], [ 'check', 'toggle' ] ) ) {
					unset( $info['type'] );
					$info['class'] = HTMLToggleSwitchField::class;
				} elseif ( isset( $info['class'] ) && $info['class'] === HTMLCheckField::class ) {
					$info['class'] = HTMLToggleSwitchField::class;
				}
			}
		}
		return parent::addFields( $descriptor );
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	public function getBody() {
		if ( $this->isMobileLayout() ) {
			// Import the icons used in the mobile view
			$this->getOutput()->addModuleStyles(
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
	 * @return OOUI\Tag
	 */
	private function createMobilePreferencesForm() {
		$sectionButtons = [];
		$sectionContents = [];
		$iconNames = $this->getIconNames();

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

			// Creating the header section
			$label = ( new OOUI\Tag( 'div' ) )->appendContent(
				( new OOUI\Tag( 'h5' ) )->appendContent( $label )->addClasses( [ 'mw-prefs-title' ] ),
				$this->createMobileDescription( $key )
			);
			$contentDiv = $this->createContentMobile( $key, $label, $content );

			$sectionButton = new OOUI\ButtonWidget( [
				'id' => 'mw-mobile-prefs-' . $key,
				'icon' => $iconNames[ $key ] ?? 'settings',
				'label' => new OOUI\HtmlSnippet( $label->toString() ),
				'data' => $key,
				'classes' => [ 'mw-mobile-prefsection' ],
				'framed' => false,
			] );
			$sectionButtons[] = $sectionButton;
			$sectionContents[] = $contentDiv;
		}

		$buttonGroup = new OOUI\ButtonGroupWidget( [
			'classes' => [ 'mw-mobile-prefs-sections' ],
			'infusable' => true,
		] );
		$buttonGroup->addItems( $sectionButtons );
		$form = ( new OOUI\Tag( 'div' ) )
			->setAttributes( [ 'id' => 'mw-prefs-container' ] )
			->addClasses( [ 'mw-mobile-prefs-container' ] )
			->appendContent( $buttonGroup )
			->appendContent( $sectionContents );

		return $form;
	}

	/**
	 * Get the icon names for each mobile preference section.
	 * @return array
	 */
	private function getIconNames() {
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

		return $iconNames;
	}

	/**
	 * Creates a description tag for each section of the mobile layout.
	 * @param string $key
	 * @return OOUI\Tag
	 */
	private function createMobileDescription( $key ) {
		$prefDescriptionMsg = $this->msg( "prefs-description-" . $key );
		$prefDescription = $prefDescriptionMsg->exists() ? $prefDescriptionMsg->text() : "";
		$prefDescriptionElement = ( new OOUI\Tag( 'p' ) )
			->appendContent( $prefDescription )
			->addClasses( [ 'mw-prefs-description' ] );

		return $prefDescriptionElement;
	}

	/**
	 * Creates the contents for each section of the mobile layout.
	 * @param string $key
	 * @param string|OOUI\Tag $label
	 * @param string $content
	 * @return OOUI\Tag
	 */
	private function createContentMobile( $key, $label, $content ) {
		$contentDiv = ( new OOUI\Tag( 'div' ) );
		$contentDiv->addClasses( [
			'mw-prefs-content-page',
			'mw-prefs-section-fieldset',
		] );
		$contentDiv->setAttributes( [
			'id' => 'mw-mobile-prefs-' . $key
		] );
		$contentBody = ( new OOUI\Tag( 'div' ) )
			->addClasses( [ 'mw-htmlform-autoinfuse-lazy' ] )
			->setAttributes( [
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
		$contentBody->appendContent( $contentHeader );
		$contentBody->appendContent( $hiddenForm );
		$contentDiv->appendContent( $contentBody );

		return $contentDiv;
	}

	/**
	 * Create the preferences form for a desktop layout.
	 * @return OOUI\PanelLayout
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
