<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\ChangeTags\ChangeTagsList;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RevisionDelete\RevisionDeleter;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;

/**
 * Add or remove change tags to individual revisions.
 *
 * A lot of this was copied out of SpecialRevisiondelete.
 *
 * @ingroup SpecialPage
 * @since 1.25
 */
class SpecialEditTags extends UnlistedSpecialPage {
	/** @var Status|null Result of the last submit attempt */
	protected $status;

	/** @var array Target ID list */
	private $ids;

	/** @var Title Title object for target parameter */
	private $targetObj;

	/** @var string Deletion type, may be revision or logentry */
	private $typeName;

	/** @var ChangeTagsList Storing the list of items to be tagged */
	private $revList;

	public function __construct(
		private readonly PermissionManager $permissionManager,
		private readonly ChangeTagsStore $changeTagsStore,
	) {
		parent::__construct( 'EditTags' );
	}

	/** @inheritDoc */
	public function getRestriction(): string {
		return 'changetags';
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->checkPermissions();
		$this->checkReadOnly();

		$output = $this->getOutput();
		$user = $this->getUser();
		$request = $this->getRequest();

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Tags' );

		$output->addModules( [ 'mediawiki.misc-authed-ooui' ] );
		$output->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special',
		] );

		$submitClicked = $request->getVal( 'action' ) === 'submit' && $request->wasPosted();

		// Handle our many different possible input types
		$ids = $request->getVal( 'ids' );
		if ( $ids !== null ) {
			// Allow CSV from the form hidden field, or a single ID for show/hide links
			$this->ids = explode( ',', $ids );
		} else {
			// Array input
			$this->ids = array_keys( $request->getArray( 'ids', [] ) );
		}
		$this->ids = array_unique( array_filter( $this->ids ) );

		// No targets?
		if ( count( $this->ids ) == 0 ) {
			throw new ErrorPageError( 'tags-edit-nooldid-title', 'tags-edit-nooldid-text' );
		}

		$this->typeName = $request->getVal( 'type' );
		$this->targetObj = Title::newFromText( $request->getText( 'target' ) );

		switch ( $this->typeName ) {
			case 'logentry':
			case 'logging':
				$this->typeName = 'logentry';
				break;
			default:
				$this->typeName = 'revision';
				break;
		}

		// Allow the list type to adjust the passed target
		// Yuck! Copied straight out of SpecialRevisiondelete, but it does exactly
		// what we want
		$this->targetObj = RevisionDeleter::suggestTarget(
			$this->typeName === 'revision' ? 'revision' : 'logging',
			$this->targetObj,
			$this->ids
		);

		// We need a target page!
		if ( $this->targetObj === null ) {
			$output->addWikiMsg( 'undelete-header' );
			return;
		}

		// Check blocks
		$checkReplica = !$submitClicked;
		if (
			$this->permissionManager->isBlockedFrom(
				$user,
				$this->targetObj,
				$checkReplica
			)
		) {
			throw new UserBlockedError(
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
				$user->getBlock(),
				$user,
				$this->getLanguage(),
				$request->getIP()
			);
		}

		// Give a link to the logs/hist for this page
		$this->showConvenienceLinks();

		$form = $this->getForm();
		$result = $form->tryAuthorizedSubmit();
		$wasSaved = $result === true;
		if ( $wasSaved ) {
			$this->success();
			$this->showTargetList();
			$this->getForm( true )->displayForm( false );
		} else {
			if ( $this->status instanceof Status ) {
				$this->failure();
			}
			$this->showTargetList();
			$form->displayForm( $result );
		}

		// Show relevant lines from the tag log
		$tagLogPage = new LogPage( 'tag' );
		$output->addHTML( "<h2>" . $tagLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract(
			$output,
			'tag',
			$this->targetObj,
			'', /* user */
			[ 'lim' => 25, 'conds' => [], 'useMaster' => $wasSaved ]
		);
	}

	/**
	 * Show some useful links in the subtitle
	 */
	protected function showConvenienceLinks() {
		// Give a link to the logs/hist for this page
		if ( $this->targetObj ) {
			// Also set header tabs to be for the target.
			$this->getSkin()->setRelevantTitle( $this->targetObj );

			$linkRenderer = $this->getLinkRenderer();
			$links = [];
			$links[] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log' ),
				$this->msg( 'viewpagelogs' )->text(),
				[],
				[
					'page' => $this->targetObj->getPrefixedText(),
					'wpfilters' => [ 'tag' ],
				]
			);
			if ( !$this->targetObj->isSpecialPage() ) {
				// Give a link to the page history
				$links[] = $linkRenderer->makeKnownLink(
					$this->targetObj,
					$this->msg( 'pagehist' )->text(),
					[],
					[ 'action' => 'history' ]
				);
			}
			// Link to Special:Tags
			$links[] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Tags' ),
				$this->msg( 'tags-edit-manage-link' )->text()
			);
			// Logs themselves don't have histories or archived revisions
			$this->getOutput()->addSubtitle( $this->getLanguage()->pipeList( $links ) );
		}
	}

	/**
	 * Get the list object for this request
	 * @return ChangeTagsList
	 */
	protected function getList() {
		if ( $this->revList === null ) {
			$this->revList = ChangeTagsList::factory(
				$this->typeName, $this->getContext(), $this->targetObj, $this->ids
			);
		}
		$this->revList->reset();

		return $this->revList;
	}

	/**
	 * Show a list of items that we will operate on, above the form
	 */
	protected function showTargetList() {
		$out = $this->getOutput();
		// Messages: tags-edit-revision-selected, tags-edit-logentry-selected
		$out->wrapWikiMsg( "<strong>$1</strong>", [
			"tags-edit-{$this->typeName}-selected",
			$this->getLanguage()->formatNum( count( $this->ids ) ),
			$this->targetObj->getPrefixedText()
		] );

		$this->addHelpLink( 'Help:Tags' );
		$out->addHTML( "<ul>" );

		$numRevisions = 0;
		$list = $this->getList();
		for ( ; $list->current(); $list->next() ) {
			$item = $list->current();
			if ( !$item->canView() ) {
				throw new ErrorPageError( 'permissionserrors', 'tags-update-no-permission' );
			}
			$numRevisions++;
			$out->addHTML( $item->getHTML() );
		}

		if ( !$numRevisions ) {
			throw new ErrorPageError( 'tags-edit-nooldid-title', 'tags-edit-nooldid-text' );
		}

		$out->addHTML( "</ul>" );
		// Explanation text
		$out->wrapWikiMsg( '<p>$1</p>', "tags-edit-{$this->typeName}-explanation" );
	}

	/**
	 * Build and return the OOUI HTMLForm for editing tags.
	 *
	 * @param bool $reset If true, reset all fields to their defaults
	 * @return HTMLForm
	 */
	protected function getForm( bool $reset = false ): HTMLForm {
		$formDescriptor = $this->buildFormFields( $reset );
		$formDescriptor['Reason'] = [
			'type' => 'text',
			'name' => 'wpReason',
			'id' => 'wpReason',
			'label-message' => 'tags-edit-reason',
			// HTML maxlength uses "UTF-16 code units"; the JS overrides this to count
			// Unicode codepoints instead.
			'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'maxlength-unit' => 'codepoints',
			'infusable' => true,
			'default' => '',
			'nodata' => $reset,
		];

		return HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setId( 'mw-edit-tags-form' )
			->setAction( $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ) )
			->addHiddenFields( [
				'target' => $this->targetObj->getPrefixedText(),
				'type' => $this->typeName,
				'ids' => implode( ',', $this->ids ),
			] )
			->setSubmitText(
				$this->msg( "tags-edit-{$this->typeName}-submit", count( $this->ids ) )->text()
			)
			->setWrapperLegend(
				$this->msg( "tags-edit-{$this->typeName}-legend", count( $this->ids ) )->text()
			)
			->setSubmitCallback( [ $this, 'onSubmit' ] )
			->prepareForm();
	}

	/**
	 * Build the HTMLForm field descriptors for the tag editing form.
	 *
	 * @param bool $reset If true, reset all fields to their defaults
	 * @return array[] HTMLForm field descriptor array
	 */
	protected function buildFormFields( bool $reset = false ): array {
		$formDescriptor = [];
		$list = $this->getList();
		$singleRev = $list->length() === 1;

		$existingTags = [];
		for ( ; $list->current(); $list->next() ) {
			$currentTags = $list->current()->getTags();
			if ( $currentTags ) {
				$existingTags = array_merge( $existingTags, explode( ',', $currentTags ) );
			}
		}
		$existingTags = array_unique( $existingTags );

		$explicitlyDefined = $this->changeTagsStore->listExplicitlyDefinedTags();
		// tags applied by software are not in listExplicitlyDefinedTags() and cannot
		// be added or removed thriugh this form. so we exclude them from all interactive fields.
		$softwareTags = array_values( array_diff( $existingTags, $explicitlyDefined ) );
		$manageableTags = array_values( array_diff( $existingTags, $softwareTags ) );

		$selectableTags = array_unique( array_merge( $explicitlyDefined, $manageableTags ) );
		$tagOptions = array_combine( $selectableTags, $selectableTags );

		if ( $singleRev ) {
			if ( $existingTags ) {
				$existingDisplay = $this->getLanguage()->commaList(
					array_map( 'htmlspecialchars', $existingTags )
				);
			} else {
				$existingDisplay = $this->msg( 'tags-edit-existing-tags-none' )->parse();
			}
			$formDescriptor['existing'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $existingDisplay,
				'label-message' => 'tags-edit-existing-tags',
			];

			$formDescriptor['TagList'] = [
				'type' => 'multiselect',
				'dropdown' => true,
				'placeholder-message' => 'tags-edit-chosen-placeholder',
				'options' => $tagOptions,
				'default' => $manageableTags,
				'nodata' => $reset,
				'label-message' => 'tags-edit-new-tags',
			];
		} else {
			if ( $softwareTags ) {
				$formDescriptor['softwareTagsInfo'] = [
					'type' => 'info',
					'raw' => true,
					'default' => $this->getLanguage()->commaList(
						array_map( 'htmlspecialchars', $softwareTags )
					),
					'label-message' => 'tags-edit-existing-tags',
				];
			}

			$formDescriptor['TagList'] = [
				'type' => 'multiselect',
				'dropdown' => true,
				'placeholder-message' => 'tags-edit-chosen-placeholder',
				'options' => $tagOptions,
				'default' => [],
				'nodata' => $reset,
				'label-message' => 'tags-edit-add',
			];

			if ( $manageableTags ) {
				$removeOptions = array_combine( $manageableTags, $manageableTags );
				$formDescriptor += [
					'RemoveLabel' => [
						'type' => 'info',
						'default' => $this->msg( 'tags-edit-remove' )->text(),
					],
					'RemoveAllTags' => [
						'type' => 'check',
						'label-message' => 'tags-edit-remove-all-tags',
						'nodata' => $reset,
					],
					'TagsToRemove' => [
						'type' => 'multiselect',
						'options' => $removeOptions,
						'cssclass' => 'mw-edittags-remove-tags',
						'nodata' => $reset,
					],
				];
			}
		}

		$formDescriptor['ExistingTags'] = [
			'type' => 'hidden',
			'default' => implode( ',', $manageableTags ),
		];

		return $formDescriptor;
	}

	/**
	 * HTMLForm submit callback.
	 *
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return bool True on success, false to redisplay the form
	 */
	public function onSubmit( array $alldata, HTMLForm $form ) {
		$tagList = $alldata['TagList'] ?? [];

		$existingTags = $alldata['ExistingTags'];
		if ( $existingTags === '' ) {
			$existingTags = [];
		} else {
			$existingTags = explode( ',', $existingTags );
		}

		if ( count( $this->ids ) > 1 ) {
			// multiple revisions selected
			$tagsToAdd = $tagList;
			if ( !empty( $alldata['RemoveAllTags'] ) ) {
				$tagsToRemove = $existingTags;
			} else {
				$tagsToRemove = $alldata['TagsToRemove'] ?? [];
			}
		} else {
			$tagsToAdd = array_diff( $tagList, $existingTags );
			$tagsToRemove = array_diff( $existingTags, $tagList );
		}

		if ( !$tagsToAdd && !$tagsToRemove ) {
			$this->status = Status::newFatal( 'tags-edit-none-selected' );
		} else {
			$this->status = $this->getList()->updateChangeTagsOnAll(
				$tagsToAdd,
				$tagsToRemove,
				null,
				$alldata['Reason'],
				$this->getAuthority()
			);
		}

		if ( $this->status->isGood() ) {
			return true;
		}

		return false;
	}

	/**
	 * Report that the submit operation succeeded.
	 */
	protected function success() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'actioncomplete' ) );
		$out->addHTML(
			Html::successBox( $out->msg( 'tags-edit-success' )->parse() )
		);
		$this->revList->reloadFromPrimary();
	}

	/**
	 * Report that the submit operation failed.
	 */
	protected function failure() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'actionfailed' ) );
		$out->addHTML(
			Html::errorBox(
				$out->parseAsContent(
					$this->status->getWikiText( 'tags-edit-failure', false, $this->getLanguage() )
				)
			)
		);
	}

	/** @inheritDoc */
	public function getDescription() {
		return $this->msg( 'tags-edit-title' );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

// @codeCoverageIgnoreStart
/** @deprecated class alias since 1.41 */
class_alias( SpecialEditTags::class, 'SpecialEditTags' );
// @codeCoverageIgnoreEnd
