<?php

class SpecialChangeContentModel extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );
	}

	/**
	 * @var Title|null
	 */
	private $title;

	/**
	 * @var Revision|bool|null
	 *
	 * A Revision object, false if no revision exists, null if not loaded yet
	 */
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

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function validateTitle( $title ) {
		if ( !$title ) {
			// No form input yet
			return true;
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
		if ( !$titleObj->canExist() ) {
			return $this->msg(
				'changecontentmodel-title-cantexist',
				$titleObj->getPrefixedText()
			)->escaped();
		}

		$this->oldRevision = Revision::newFromTitle( $titleObj ) ?: false;

		if ( $this->oldRevision ) {
			$oldContent = $this->oldRevision->getContent();
			if ( !$oldContent->getContentHandler()->supportsDirectEditing() ) {
				return $this->msg( 'changecontentmodel-nodirectediting' )
					->params( ContentHandler::getLocalizedName( $oldContent->getModel() ) )
					->escaped();
			}
		}

		return true;
	}

	protected function getFormFields() {
		$that = $this;
		return array(
			'pagetitle' => array(
				'type' => 'text',
				'name' => 'pagetitle',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label',
				'validation-callback' => array( $this, 'validateTitle' ),
			),
			'model' => array(
				'type' => 'select',
				'name' => 'model',
				'options' => $this->getOptionsForTitle( $this->title ),
				'label-message' => 'changecontentmodel-model-label'
			),
			'reason' => array(
				'type' => 'text',
				'name' => 'reason',
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
			if ( !$handler->supportsDirectEditing() ) {
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
		$title = Title::newFromText( $data['pagetitle' ] );
		$user = $this->getUser();

		// Check permissions and make sure the user has permission to edit the specific page
		$errors = $title->getUserPermissionsErrors( 'editcontentmodel', $user );
		$errors = wfMergeErrorArrays( $errors, $title->getUserPermissionsErrors( 'edit', $user ) );
		if ( $errors ) {
			$out = $this->getOutput();
			$wikitext = $out->formatPermissionsErrorMessage( $errors );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', array( $wikitext ) ) );
		}

		$page = WikiPage::factory( $title );
		if ( $this->oldRevision === null ) {
			$this->oldRevision = $page->getRevision() ?: false;
		}
		$oldModel = $title->getContentModel();
		if ( $this->oldRevision ) {
			$oldContent = $this->oldRevision->getContent();
			try {
				$newContent = ContentHandler::makeContent(
					$oldContent->getNativeData(), $title, $data['model']
				);
			} catch ( MWException $e ) {
				return Status::newFatal(
					$this->msg( 'changecontentmodel-cannot-convert' )
						->params(
							$title->getPrefixedText(),
							ContentHandler::getLocalizedName( $data['model'] )
						)
				);
			}
		} else {
			// Page doesn't exist, create an empty content object
			$newContent = ContentHandler::getForModelID( $data['model'] )->makeEmptyContent();
		}
		$flags = $this->oldRevision ? EDIT_UPDATE : EDIT_NEW;
		if ( $user->isAllowed( 'bot' ) ) {
			$flags |= EDIT_FORCE_BOT;
		}
		$status = $page->doEditContent(
			$newContent,
			$data['reason'],
			$flags,
			$this->oldRevision ? $this->oldRevision->getId() : false,
			$user
		);
		if ( !$status->isOK() ) {
			return $status;
		}

		$log = new ManualLogEntry( 'contentmodel', 'change' );
		$log->setPerformer( $user );
		$log->setTarget( $title );
		$log->setComment( $data['reason'] );
		$log->setParameters( array(
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $data['model']
		) );
		$logid = $log->insert();
		$log->publish( $logid );

		return $status;
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'changecontentmodel-success-title' ) );
		$out->addWikiMsg( 'changecontentmodel-success-text', $this->title );
	}
}
