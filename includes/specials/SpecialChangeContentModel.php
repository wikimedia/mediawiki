<?php

class SpecialChangeContentModel extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );
	}

	/**
	 * @var Title|null
	 */
	private $title;

	private $oldRevision;

	protected function setParameter( $par ) {
		$title = Title::newFromText( $par );
		if ( $title ) {
			$this->title = $title;
			$this->par = $title->getPrefixedText();
		} else {
			$this->par = '';
		}
	}

	public function validateTitle( $title ) {
		if ( !$title ) {
		}
		try {
			$titleObj = Title::newFromTextThrow( $title );
		} catch ( MalformedTitleException $e ) {
			$msg = $this->msg( $e->getErrorMessage() );
			$params = $e->getErrorMessageParameters();
			if ( $params ) {
				$msg->params( $params );
			}
			return $msg->parse();
		}

		$this->oldRevision = Revision::newFromTitle( $titleObj );
		$oldContent = $this->oldRevision->getContent();
		if ( !$oldContent instanceof TextContent ) {
			return $this->msg( 'changecontentmodel-nontext' )->escaped();
		}

		return true;
	}

	protected function getFormFields() {
		$that = $this;
		return array(
			'title' => array(
				'type' => 'text',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label',
				'validation-callback' => function ( $title ) use ( $that ) {
					try {
						Title::newFromTextThrow( $title );
						return true;
					} catch ( MalformedTitleException $e ) {
						$msg = $that->msg( $e->getErrorMessage() );
						$params = $e->getErrorMessageParameters();
						if ( $params ) {
							call_user_func_array( array( $msg, 'params' ), $params );
						}
						return $msg->parse();
					}
				}
			),
			'model' => array(
				'type' => 'select',
				'options' => $this->getOptionsForTitle( $this->title ),
				'label-message' => 'changecontentmodel-model-label'
			),
			'reason' => array(
				'type' => 'text',
				'validation-callback' => function( $reason ) use ( $that ) {
					$match = EditPage::matchSummarySpamRegex( $reason );
					if ( $match ) {
						return $that->msg( 'spamprotectionmatch', $match )->parse();
					}

					return true;
				},
				'label-message' => 'changecontentmodel-reason-label',
			),
		);
	}

	private function getOptionsForTitle( Title $title = null ) {
		$models = ContentHandler::getContentModels();
		$options = array();
		foreach ( $models as $model ) {
			$handler = ContentHandler::getForModelID( $model );
			if ( !$handler instanceof TextContentHandler ) {
				continue;
			}
			if ( $title ) {
				if ( $title->getContentModel() === $model ) {
					continue;
				}
				if ( !$handler->canBeUsedOn( $title ) ) {
					continue;
				}
			}
			$options[ContentHandler::getLocalizedName( $model )] = $model;
		}

		return $options;
	}

	public function onSubmit( array $data ) {
		$title = Title::newFromText( $data['title' ] );
		$page = WikiPage::factory( $title );
		if ( !$this->oldRevision ) {
			$this->oldRevision = $page->getRevision();
		}
		$oldContent = $this->oldRevision->getContent();
		$newContent = ContentHandler::makeContent( $oldContent->getNativeData(), $title, $data['model'] );
		$user = $this->getUser();
		$flags = EDIT_UPDATE;
		if ( $user->isAllowed( 'bot' ) ) {
			$flags |= EDIT_FORCE_BOT;
		}
		return $page->doEditContent(
			$newContent,
			$data['reason'],
			$flags,
			$this->oldRevision->getId(),
			$user
		);
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'changecontentmodel-success-title' ) );
		$out->addWikiMsg( 'changecontentmodel-success-text', $this->title );
	}
}
