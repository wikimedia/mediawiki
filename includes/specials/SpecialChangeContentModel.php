<?php

class SpecialChangeContentModel extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );
	}

	/**
	 * @var Title|null
	 */
	private $title;

	protected function setParameter( $par ) {
		$title = Title::newFromText( $par );
		if ( $title ) {
			$this->title = $title;
			$this->par = $title->getPrefixedText();
		} else {
			$this->par = '';
		}
	}
	protected function getFormFields() {
		return array(
			'title' => array(
				'type' => 'text',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label'
			),
			'model' => array(
				'type' => 'select',
				'options' => $this->getOptionsForTitle( $this->title ),
			)
		);
	}

	private function getOptionsForTitle( Title $title = null ) {
		$models = ContentHandler::getContentModels();
		$options = array();
		foreach ( $models as $model ) {
			if ( $title ) {
				if ( $title->getContentModel() === $model ) {
					continue;
				}
				if ( !ContentHandler::getForModelID( $model )->canBeUsedOn( $title ) ) {
					continue;
				}
			}
			$options[ContentHandler::getLocalizedName( $model )] = $model;
		}

		return $options;
	}

	public function onSubmit( array $data ) {
		return Status::newGood();
	}
}
