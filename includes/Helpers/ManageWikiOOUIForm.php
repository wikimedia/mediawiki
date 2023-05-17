<?php

namespace Miraheze\ManageWiki\Helpers;

use OOUIHTMLForm;
use Xml;

class ManageWikiOOUIForm extends OOUIHTMLForm {
	/** @var bool */
	protected $mSubSectionBeforeFields = false;

	public function wrapForm( $html ) {
		$html = Xml::tags( 'div', [ 'id' => 'managewiki' ], $html );

		return parent::wrapForm( $html );
	}

	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		$layout = parent::wrapFieldSetSection( $legend, $section, $attributes, $isRoot );

		$layout->addClasses( [ 'managewiki-fieldset-wrapper' ] );
		$layout->removeClasses( [ 'oo-ui-panelLayout-framed' ] );

		return $layout;
	}

	public function getBody() {
		$tabPanels = [];
		foreach ( $this->mFieldTree as $key => $val ) {
			if ( !is_array( $val ) ) {
				wfDebug( __METHOD__ . " encountered a field not attached to a section: '{$key}'" );

				continue;
			}

			$label = $this->getLegend( $key );

			$content =
				$this->getHeaderText( $key ) .
				$this->displaySection(
					$val,
					'',
					"mw-section-{$key}-"
				) .
				$this->getFooterText( $key );

			$tabPanels[] = new \OOUI\TabPanelLayout( 'mw-section-' . $key, [
				'classes' => [ 'mw-htmlform-autoinfuse-lazy' ],
				'label' => $label,
				'content' => new \OOUI\FieldsetLayout( [
					'classes' => [ 'managewiki-section-fieldset' ],
					'id' => "mw-section-{$key}",
					'label' => $label,
					'items' => [
						new \OOUI\Widget( [
							'content' => new \OOUI\HtmlSnippet( $content )
						] ),
					],
				] ),
				'expanded' => false,
				'framed' => true,
			] );
		}

		$indexLayout = new \OOUI\IndexLayout( [
			'infusable' => true,
			'expanded' => false,
			'autoFocus' => false,
			'classes' => [ 'managewiki-tabs' ],
		] );

		$indexLayout->addTabPanels( $tabPanels );

		$header = $this->formatFormHeader();

		$form = new \OOUI\PanelLayout( [
			'framed' => true,
			'expanded' => false,
			'classes' => [ 'managewiki-tabs-wrapper' ],
			'content' => $indexLayout
		] );

		return $header . $form;
	}

	public function getButtons() {
		if ( !$this->mShowSubmit ) {
			return;
		}

		$descriptor = [];

		$descriptor['reason'] = [
			'type' => 'text',
			'placeholder-message' => 'managewiki-placeholder-reason',
			'id' => 'managewiki-submit-reason',
			'required' => true
		];

		$field = $this->addFields( $descriptor )->getField( 'reason' );

		$html = $field->getInputOOUI( '' );

		$html .= parent::getButtons();

		$html .= new \OOUI\ButtonInputWidget( [
			'label' => $this->msg( 'managewiki-review' )->text(),
			'id' => 'managewiki-review'
		] );

		$html = Xml::tags( 'div', [ 'class' => 'managewiki-submit-formfields' ], $html );

		return $html;
	}
}
