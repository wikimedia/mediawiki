<?php

namespace MediaWiki\Specials;

use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use SearchEngineFactory;

/**
 * @ingroup SpecialPage
 */
class SpecialChangeContentModel extends FormSpecialPage {

	private IContentHandlerFactory $contentHandlerFactory;
	private ContentModelChangeFactory $contentModelChangeFactory;
	private SpamChecker $spamChecker;
	private RevisionLookup $revisionLookup;
	private WikiPageFactory $wikiPageFactory;
	private SearchEngineFactory $searchEngineFactory;
	private CollationFactory $collationFactory;

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		ContentModelChangeFactory $contentModelChangeFactory,
		SpamChecker $spamChecker,
		RevisionLookup $revisionLookup,
		WikiPageFactory $wikiPageFactory,
		SearchEngineFactory $searchEngineFactory,
		CollationFactory $collationFactory
	) {
		parent::__construct( 'ChangeContentModel', 'editcontentmodel' );

		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->contentModelChangeFactory = $contentModelChangeFactory;
		$this->spamChecker = $spamChecker;
		$this->revisionLookup = $revisionLookup;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->collationFactory = $collationFactory;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function postHtml() {
		$text = '';
		if ( $this->title ) {
			$contentModelLogPage = new LogPage( 'contentmodel' );
			$text = Html::element( 'h2', [], $contentModelLogPage->getName()->text() );
			$out = '';
			LogEventsList::showLogExtract( $out, 'contentmodel', $this->title );
			$text .= $out;
		}
		return $text;
	}

	/** @inheritDoc */
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

	/**
	 * @param string $title
	 * @return string|bool
	 */
	private function validateTitle( $title ) {
		// Already validated by HTMLForm, but if not, throw
		// an exception instead of a fatal
		$titleObj = Title::newFromTextThrow( $title );

		$this->oldRevision = $this->revisionLookup->getRevisionByTitle( $titleObj ) ?: false;

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

	/** @inheritDoc */
	protected function getFormFields() {
		$fields = [
			'pagetitle' => [
				'type' => 'title',
				'creatable' => true,
				'name' => 'pagetitle',
				'default' => $this->par,
				'label-message' => 'changecontentmodel-title-label',
				'validation-callback' => $this->validateTitle( ... ),
			],
		];
		if ( $this->title ) {
			$options = $this->getOptionsForTitle( $this->title );
			if ( !$options ) {
				throw new ErrorPageError(
					'changecontentmodel-emptymodels-title',
					'changecontentmodel-emptymodels-text',
					[ $this->title->getPrefixedText() ]
				);
			}
			$fields['pagetitle']['readonly'] = true;
			$fields += [
				'model' => [
					'type' => 'select',
					'name' => 'model',
					'default' => $this->title->getContentModel(),
					'options' => $options,
					'label-message' => 'changecontentmodel-model-label'
				],
				'reason' => [
					'type' => 'text',
					'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
					'name' => 'reason',
					'validation-callback' => function ( $reason ) {
						if ( $reason === null || $reason === '' ) {
							// Null on form display, or no reason given
							return true;
						}

						$match = $this->spamChecker->checkSummary( $reason );

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

	/**
	 * @return array $options An array of data for an OOUI drop-down list. The array keys
	 * correspond to the human readable text in the drop-down list. The array values
	 * correspond to the <option value="">.
	 */
	private function getOptionsForTitle( ?Title $title = null ) {
		$models = $this->contentHandlerFactory->getContentModels();
		$options = [];
		foreach ( $models as $model ) {
			$handler = $this->contentHandlerFactory->getContentHandler( $model );
			if ( !$handler->supportsDirectEditing() ) {
				continue;
			}
			if ( $title ) {
				if ( !$handler->canBeUsedOn( $title ) ) {
					continue;
				}
			}
			$options[ContentHandler::getLocalizedName( $model )] = $model;
		}

		// Put the options in the drop-down list in alphabetical order.
		// Sort by array key, case insensitive.
		$collation = $this->collationFactory->getCategoryCollation();
		uksort( $options, static function ( $a, $b ) use ( $collation ) {
			$a = $collation->getSortKey( $a );
			$b = $collation->getSortKey( $b );
			return strcmp( $a, $b );
		} );

		return $options;
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		$this->title = Title::newFromText( $data['pagetitle'] );
		$page = $this->wikiPageFactory->newFromTitle( $this->title );

		$changer = $this->contentModelChangeFactory->newContentModelChange(
				$this->getContext()->getAuthority(),
				$page,
				$data['model']
			);

		$permissionStatus = $changer->authorizeChange();
		if ( !$permissionStatus->isGood() ) {
			$out = $this->getOutput();
			$wikitext = $out->formatPermissionStatus( $permissionStatus );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', [ $wikitext ] ) );
		}

		$status = $changer->doContentModelChange(
			$this->getContext(),
			$data['reason'],
			true
		);

		return $status;
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'changecontentmodel-success-title' ) );
		$out->addWikiMsg( 'changecontentmodel-success-text', $this->title->getPrefixedText() );
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
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialChangeContentModel::class, 'SpecialChangeContentModel' );
