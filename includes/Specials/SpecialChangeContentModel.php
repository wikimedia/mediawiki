<?php

namespace MediaWiki\Specials;

use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\DataStashTrait;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Search\SearchEngineFactory;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use StatusValue;

/**
 * @ingroup SpecialPage
 */
class SpecialChangeContentModel extends FormSpecialPage {
	use DataStashTrait;

	public function __construct(
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly ContentModelChangeFactory $contentModelChangeFactory,
		private readonly SpamChecker $spamChecker,
		private readonly RevisionLookup $revisionLookup,
		private readonly WikiPageFactory $wikiPageFactory,
		private readonly SearchEngineFactory $searchEngineFactory,
		private readonly CollationFactory $collationFactory,
		private readonly PermissionManager $permissionManager,
	) {
		parent::__construct( 'ChangeContentModel' );
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

	private string $oldContentModel;

	private bool $titleExisted;

	private string $newContentModel;

	private ?array $stashedData = null;

	private bool $reauthInProgress = false;

	protected function getTitle(): Title {
		return $this->title
			? $this->getPageTitle( $this->title->getPrefixedText() )
			: $this->getPageTitle();
	}

	private function getStashKeyForTitle( Title $title ): string {
		return $this->getName() . ':' . $title->getPrefixedDBkey();
	}

	protected function handleRetrievedData( array $data ): void {
		$this->stashedData = $data;
	}

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
	public function execute( $par ) {
		$this->setParameter( $par );
		if ( $this->title ) {
			$this->setStashKey( $this->getStashKeyForTitle( $this->title ) );
			if ( $this->retrieveStashedData() ) {
				$this->setHeaders();
				$this->outputHeader();
				$this->checkExecutePermissions( $this->getUser() );

				$result = $this->onSubmit( $this->stashedData );
				if ( $this->reauthInProgress ) {
					return;
				}
				$this->destroyStashedData();
				if ( $result === true || ( $result instanceof StatusValue && $result->isGood() ) ) {
					$this->onSuccess();
				} else {
					$this->getForm()->prepareForm()->displayForm( $result );
				}
				return;
			}
		}

		parent::execute( $par );
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
			if ( $this->title->exists() ) {
				// T120576
				$form->setSubmitTextMsg( 'changecontentmodel-submit' );
			} else {
				$form->setSubmitTextMsg( 'changecontentmodel-create-submit' );
			}
			$this->getOutput()->addBacklinkSubtitle( $this->title );
		} else {
			$form->setFormIdentifier( 'titleform' );
			// T120576
			$form->setSubmitTextMsg( 'changecontentmodel-submit' );
		}
	}

	/** @inheritDoc */
	public function checkPermissions() {
		$user = $this->getUser();
		if ( $this->title ) {
			$perm = $this->title->exists() ? 'editcontentmodel' : 'createwithcontentmodel';
			$this->permissionManager->throwPermissionErrors(
				$perm,
				$user,
				$this->title,
				PermissionManager::RIGOR_FULL
			);
		} elseif ( !$this->permissionManager->userHasAnyRight( $user, 'editcontentmodel', 'createwithcontentmodel' ) ) {
			// The intended use case of this special page is to change the content model of an existing page
			// nothing stops you from creating a new page with it but that's a hack so display the permission error
			// for editing an existing page's content model if you can't do either
			throw new PermissionsError( 'editcontentmodel' );
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
	protected function preHtml() {
		if ( $this->title ) {
			// Checking permissions is handled by checkPermissions above
			if ( $this->title->exists() ) {
				$msg = $this->msg( 'changecontentmodel-editing', $this->title->getPrefixedText() );
			} else {
				$msg = $this->msg( 'changecontentmodel-create', $this->title->getPrefixedText() );
			}
		} elseif ( !$this->permissionManager->userHasRight( $this->getUser(), 'editcontentmodel' ) ) {
			$msg = $this->msg( 'changecontentmodel-create-only' );
		} else {
			$msg = $this->msg( 'changecontentmodel-edit' );
		}
		return $msg->parseAsBlock();
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
				// If you need to enter a non-existing page then don't show autocomplete for existing ones ...
				'suggestions' => $this->permissionManager->userHasRight( $this->getUser(), 'editcontentmodel' ),
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
		$this->reauthInProgress = false;
		$this->title = Title::newFromText( $data['pagetitle'] );
		$this->titleExisted = $this->title->exists();
		$this->oldContentModel = $this->title->getContentModel();
		$this->newContentModel = $data['model'];
		$page = $this->wikiPageFactory->newFromTitle( $this->title );

		$changer = $this->contentModelChangeFactory->newContentModelChange(
				$this->getContext()->getAuthority(),
				$page,
				$data['model']
			);

		$permissionStatus = $changer->authorizeChange();
		if ( !$permissionStatus->isGood() ) {
			if ( $permissionStatus->getReauthOperation() !== null ) {
				$this->setStashKey( $this->getStashKeyForTitle( $this->title ) );
				$queryParams = $this->stashDataOnPost();
				$this->doReauthRedirect( $permissionStatus, $queryParams );
				$this->reauthInProgress = true;
				return false;
			}
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
		if ( $this->titleExisted ) {
			$out->setPageTitleMsg( $this->msg( 'changecontentmodel-success-title' ) );
			$out->addWikiMsg( 'changecontentmodel-success-text',
				$this->title->getPrefixedText(),
				ContentHandler::getLocalizedName( $this->oldContentModel, $this->getLanguage() ),
				ContentHandler::getLocalizedName( $this->newContentModel, $this->getLanguage() )
			);
		} else {
			$out->setPageTitleMsg( $this->msg( 'changecontentmodel-create-success-title' ) );
			$out->addWikiMsg( 'changecontentmodel-create-success-text',
				$this->title->getPrefixedText(),
				ContentHandler::getLocalizedName( $this->newContentModel, $this->getLanguage() )
			);
		}
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

// @codeCoverageIgnoreStart
/** @deprecated class alias since 1.41 */
class_alias( SpecialChangeContentModel::class, 'SpecialChangeContentModel' );
// @codeCoverageIgnoreEnd
