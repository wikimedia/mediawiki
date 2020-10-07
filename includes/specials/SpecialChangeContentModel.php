<?php

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

class SpecialChangeContentModel extends FormSpecialPage {

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param IContentHandlerFactory|null $contentHandlerFactory
	 * @internal use @see SpecialPageFactory::getPage
	 */
	public function __construct( ?IContentHandlerFactory $contentHandlerFactory = null ) {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );

		if ( !$contentHandlerFactory ) {
			wfDeprecated( __METHOD__ . ' without $contentHandlerFactory parameter', '1.35' );
			$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		}
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * @var Title|null
	 */
	private $title;

	/**
	 * @var RevisionRecord|bool|null
	 *
	 * A RevisionRecord object, false if no revision exists, null if not loaded yet
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
		$this->addHelpLink( 'Help:ChangeContentModel' );

		if ( $this->title ) {
			$form->setFormIdentifier( 'modelform' );
		} else {
			$form->setFormIdentifier( 'titleform' );
		}

		// T120576
		$form->setSubmitTextMsg( 'changecontentmodel-submit' );

		if ( $this->title ) {
			$this->getOutput()->addBacklinkSubtitle( $this->title );
		}
	}

	public function validateTitle( $title ) {
		// Already validated by HTMLForm, but if not, throw
		// an exception instead of a fatal
		$titleObj = Title::newFromTextThrow( $title );

		$this->oldRevision = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getRevisionByTitle( $titleObj ) ?: false;

		if ( $this->oldRevision ) {
			$oldContent = $this->oldRevision->getContent( SlotRecord::MAIN );
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
			$spamChecker = MediaWikiServices::getInstance()->getSpamChecker();

			$options = $this->getOptionsForTitle( $this->title );
			if ( empty( $options ) ) {
				throw new ErrorPageError(
					'changecontentmodel-emptymodels-title',
					'changecontentmodel-emptymodels-text',
					[ $this->title->getPrefixedText() ]
				);
			}
			$fields['pagetitle']['readonly'] = true;
			$fields += [
				'currentmodel' => [
					'type' => 'text',
					'name' => 'currentcontentmodel',
					'default' => $this->title->getContentModel(),
					'label-message' => 'changecontentmodel-current-label',
					'readonly' => true
				],
				'model' => [
					'type' => 'select',
					'name' => 'model',
					'options' => $options,
					'label-message' => 'changecontentmodel-model-label'
				],
				'reason' => [
					'type' => 'text',
					'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
					'name' => 'reason',
					'validation-callback' => function ( $reason ) use ( $spamChecker ) {
						if ( $reason === null || $reason === '' ) {
							// Null on form display, or no reason given
							return true;
						}

						$match = $spamChecker->checkSummary( $reason );

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
		$models = $this->contentHandlerFactory->getContentModels();
		$options = [];
		foreach ( $models as $model ) {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
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
		$user = $this->getUser();
		$this->title = Title::newFromText( $data['pagetitle'] );
		$page = WikiPage::factory( $this->title );

		$changer = MediaWikiServices::getInstance()
			->getContentModelChangeFactory()
			->newContentModelChange(
				$user,
				$page,
				$data['model']
			);

		$errors = $changer->checkPermissions();
		if ( $errors ) {
			$out = $this->getOutput();
			$wikitext = $out->formatPermissionsErrorMessage( $errors );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', [ $wikitext ] ) );
		}

		// Can also throw a ThrottledError, don't catch it
		$status = $changer->doContentModelChange(
			$this->getContext(),
			$data['reason'],
			true
		);

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
