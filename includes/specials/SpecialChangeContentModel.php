<?php

class SpecialChangeContentModel extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );
	}

	public function doesWrites() {
		return true;
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

	protected function postText() {
		$text = '';
		if ( $this->title ) {
			$contentModelLogPage = new LogPage( 'contentmodel' );
			$text = Xml::element( 'h2', null, $contentModelLogPage->getName()->text() );
			$out = '';
			LogEventsList::showLogExtract( $out, 'contentmodel', $this->title );
			$text .= $out;
		}
		return $text;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	protected function alterForm( HTMLForm $form ) {
		if ( !$this->title ) {
			$form->setMethod( 'GET' );
		}

		$this->addHelpLink( 'Help:ChangeContentModel' );

		// T120576
		$form->setSubmitTextMsg( 'changecontentmodel-submit' );
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
		$fields = [
			'pagetitle' => [
				'type' => 'title',
				'creatable' => true,
				'name' => 'pagetitle',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label',
				'validation-callback' => [ $this, 'validateTitle' ],
			],
		];
		if ( $this->title ) {
			$options = $this->getOptionsForTitle( $this->title );
			if ( empty( $options ) ) {
				throw new ErrorPageError(
					'changecontentmodel-emptymodels-title',
					'changecontentmodel-emptymodels-text',
					$this->title->getPrefixedText()
				);
			}
			$fields['pagetitle']['readonly'] = true;
			$fields += [
				'model' => [
					'type' => 'select',
					'name' => 'model',
					'options' => $options,
					'label-message' => 'changecontentmodel-model-label'
				],
				'reason' => [
					'type' => 'text',
					'name' => 'reason',
					'validation-callback' => function ( $reason ) {
						$match = EditPage::matchSummarySpamRegex( $reason );
						if ( $match ) {
							return $this->msg( 'spamprotectionmatch', $match )->parse();
						}

						return true;
					},
					'label-message' => 'changecontentmodel-reason-label',
				],
			];
		}

		return $fields;
	}

	private function getOptionsForTitle( Title $title = null ) {
		$models = ContentHandler::getContentModels();
		$options = [];
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
		if ( $data['pagetitle'] === '' ) {
			// Initial form view of special page, pass
			return false;
		}

		// At this point, it has to be a POST request. This is enforced by HTMLForm,
		// but lets be safe verify that.
		if ( !$this->getRequest()->wasPosted() ) {
			throw new RuntimeException( "Form submission was not POSTed" );
		}

		$this->title = Title::newFromText( $data['pagetitle'] );
		$titleWithNewContentModel = clone $this->title;
		$titleWithNewContentModel->setContentModel( $data['model'] );
		$user = $this->getUser();
		// Check permissions and make sure the user has permission to:
		$errors = wfMergeErrorArrays(
			// edit the contentmodel of the page
			$this->title->getUserPermissionsErrors( 'editcontentmodel', $user ),
			// edit the page under the old content model
			$this->title->getUserPermissionsErrors( 'edit', $user ),
			// edit the contentmodel under the new content model
			$titleWithNewContentModel->getUserPermissionsErrors( 'editcontentmodel', $user ),
			// edit the page under the new content model
			$titleWithNewContentModel->getUserPermissionsErrors( 'edit', $user )
		);
		if ( $errors ) {
			$out = $this->getOutput();
			$wikitext = $out->formatPermissionsErrorMessage( $errors );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', [ $wikitext ] ) );
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
					$oldContent->serialize(), $this->title, $data['model']
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

		// All other checks have passed, let's check rate limits
		if ( $user->pingLimiter( 'editcontentmodel' ) ) {
			throw new ThrottledError();
		}

		$flags = $this->oldRevision ? EDIT_UPDATE : EDIT_NEW;
		$flags |= EDIT_INTERNAL;
		if ( $user->isAllowed( 'bot' ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		$log = new ManualLogEntry( 'contentmodel', $this->oldRevision ? 'change' : 'new' );
		$log->setPerformer( $user );
		$log->setTarget( $this->title );
		$log->setComment( $data['reason'] );
		$log->setParameters( [
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $data['model']
		] );

		$formatter = LogFormatter::newFromEntry( $log );
		$formatter->setContext( RequestContext::newExtraneousContext( $this->title ) );
		$reason = $formatter->getPlainActionText();
		if ( $data['reason'] !== '' ) {
			$reason .= $this->msg( 'colon-separator' )->inContentLanguage()->text() . $data['reason'];
		}

		// Run edit filters
		$derivativeContext = new DerivativeContext( $this->getContext() );
		$derivativeContext->setTitle( $this->title );
		$derivativeContext->setWikiPage( $page );
		$status = new Status();
		if ( !Hooks::run( 'EditFilterMergedContent',
				[ $derivativeContext, $newContent, $status, $reason,
				$user, false ] )
		) {
			if ( $status->isGood() ) {
				// TODO: extensions should really specify an error message
				$status->fatal( 'hookaborted' );
			}
			return $status;
		}

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

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
