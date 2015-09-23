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
		$par = $this->getRequest()->getVal( 'pagetitle', $par );
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

	protected function alterForm( HTMLForm $form ) {
		if ( !$this->title ) {
			$form->setMethod( 'GET' );
		}
	}

	public function validateTitle( $title ) {
		if ( !$title ) {
			// No form input yet
			return true;
		}

		// Already validated by HTMLForm, but if not, throw
		// and exception instead of a fatal
		$titleObj = Title::newFromTextThrow( $title );

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
		$fields = array(
			'pagetitle' => array(
				'type' => 'title',
				'creatable' => true,
				'name' => 'pagetitle',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label',
				'validation-callback' => array( $this, 'validateTitle' ),
			),
		);
		if ( $this->title ) {
			$fields['pagetitle']['readonly'] = true;
			$fields += array(
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

		return $fields;
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
		global $wgContLang;

		if ( $data['pagetitle'] === '' ) {
			// Initial form view of special page, pass
			return false;
		}

		// At this point, it has to be a POST request. This is enforced by HTMLForm,
		// but lets be safe verify that.
		if ( !$this->getRequest()->wasPosted() ) {
			throw new RuntimeException( "Form submission was not POSTed" );
		}

		$this->title = Title::newFromText( $data['pagetitle' ] );
		$user = $this->getUser();
		// Check permissions and make sure the user has permission to edit the specific page
		$errors = $this->title->getUserPermissionsErrors( 'editcontentmodel', $user );
		$errors = wfMergeErrorArrays( $errors, $this->title->getUserPermissionsErrors( 'edit', $user ) );
		if ( $errors ) {
			$out = $this->getOutput();
			$wikitext = $out->formatPermissionsErrorMessage( $errors );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', array( $wikitext ) ) );
		}

		$page = WikiPage::factory( $this->title );
		if ( $this->oldRevision === null ) {
			$this->oldRevision = $page->getRevision() ?: false;
		}
		$oldModel = $this->title->getContentModel();
		if ( $this->oldRevision ) {
			$oldContent = $this->oldRevision->getContent();
			try {
				$newContent = ContentHandler::makeContent(
					$oldContent->getNativeData(), $this->title, $data['model']
				);
			} catch ( MWException $e ) {
				return Status::newFatal(
					$this->msg( 'changecontentmodel-cannot-convert' )
						->params(
							$this->title->getPrefixedText(),
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

		$log = new ManualLogEntry( 'contentmodel', 'change' );
		$log->setPerformer( $user );
		$log->setTarget( $this->title );
		$log->setComment( $data['reason'] );
		$log->setParameters( array(
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $data['model']
		) );

		$formatter = LogFormatter::newFromEntry( $log );
		$formatter->setContext( RequestContext::newExtraneousContext( $this->title ) );
		$reason = $formatter->getPlainActionText();
		if ( $data['reason'] !== '' ) {
			$reason .= $this->msg( 'colon-separator' )->inContentLanguage()->text() . $data['reason'];
		}
		# Truncate for whole multibyte characters.
		$reason = $wgContLang->truncate( $reason, 255 );

		$status = $page->doEditContent(
			$newContent,
			$reason,
			$flags,
			$this->oldRevision ? $this->oldRevision->getId() : false,
			$user
		);
		if ( !$status->isOK() ) {
			return $status;
		}

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
