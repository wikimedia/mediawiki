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
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Xml\XmlSelect;
use RevisionDeleter;

/**
 * Add or remove change tags to individual revisions.
 *
 * A lot of this was copied out of SpecialRevisiondelete.
 *
 * @ingroup SpecialPage
 * @since 1.25
 */
class SpecialEditTags extends UnlistedSpecialPage {
	/** @var bool Was the DB modified in this request */
	protected $wasSaved = false;

	/** @var bool True if the submit button was clicked, and the form was posted */
	private $submitClicked;

	/** @var array Target ID list */
	private $ids;

	/** @var Title Title object for target parameter */
	private $targetObj;

	/** @var string Deletion type, may be revision or logentry */
	private $typeName;

	/** @var ChangeTagsList Storing the list of items to be tagged */
	private $revList;

	/** @var string */
	private $reason;

	private PermissionManager $permissionManager;
	private ChangeTagsStore $changeTagsStore;

	public function __construct( PermissionManager $permissionManager, ChangeTagsStore $changeTagsStore ) {
		parent::__construct( 'EditTags', 'changetags' );

		$this->permissionManager = $permissionManager;
		$this->changeTagsStore = $changeTagsStore;
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

		$output->addModules( [ 'mediawiki.misc-authed-curate' ] );
		$output->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special'
		] );

		$this->submitClicked = $request->wasPosted() && $request->getBool( 'wpSubmit' );

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

		$this->reason = $request->getVal( 'wpReason', '' );
		// We need a target page!
		if ( $this->targetObj === null ) {
			$output->addWikiMsg( 'undelete-header' );
			return;
		}

		// Check blocks
		$checkReplica = !$this->submitClicked;
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

		// Either submit or create our form
		if ( $this->submitClicked ) {
			$this->submit();
		} else {
			$this->showForm();
		}

		// Show relevant lines from the tag log
		$tagLogPage = new LogPage( 'tag' );
		$output->addHTML( "<h2>" . $tagLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract(
			$output,
			'tag',
			$this->targetObj,
			'', /* user */
			[ 'lim' => 25, 'conds' => [], 'useMaster' => $this->wasSaved ]
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
			$this->revList = ChangeTagsList::factory( $this->typeName, $this->getContext(),
				$this->targetObj, $this->ids );
		}

		return $this->revList;
	}

	/**
	 * Show a list of items that we will operate on, and show a form which allows
	 * the user to modify the tags applied to those items.
	 */
	protected function showForm() {
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
		// Live revisions...
		$list = $this->getList();
		for ( $list->reset(); $list->current(); $list->next() ) {
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

		// Show form
		$form = Html::openElement( 'form', [ 'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ),
				'id' => 'mw-revdel-form-revisions' ] ) .
			Html::openElement( 'fieldset' ) .
			Html::element(
				'legend', [],
				$this->msg( "tags-edit-{$this->typeName}-legend", count( $this->ids ) )->text()
			) .
			$this->buildCheckBoxes() .
			Html::openElement( 'table' ) .
			"<tr>\n" .
				'<td class="mw-label">' .
					Html::label( $this->msg( 'tags-edit-reason' )->text(), 'wpReason' ) .
				'</td>' .
				'<td class="mw-input">' .
					Html::element( 'input', [ 'name' => 'wpReason', 'size' => 60, 'value' => $this->reason,
						'id' => 'wpReason',
						// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
						// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
						// Unicode codepoints.
						'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
					] ) .
				'</td>' .
			"</tr><tr>\n" .
				'<td></td>' .
				'<td class="mw-submit">' .
					Html::submitButton( $this->msg( "tags-edit-{$this->typeName}-submit",
						$numRevisions )->text(), [ 'name' => 'wpSubmit' ] ) .
				'</td>' .
			"</tr>\n" .
			Html::closeElement( 'table' ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() ) .
			Html::hidden( 'target', $this->targetObj->getPrefixedText() ) .
			Html::hidden( 'type', $this->typeName ) .
			Html::hidden( 'ids', implode( ',', $this->ids ) ) .
			Html::closeElement( 'fieldset' ) . "\n" .
			Html::closeElement( 'form' ) . "\n";

		$out->addHTML( $form );
	}

	/**
	 * @return string HTML
	 */
	protected function buildCheckBoxes() {
		// If there is just one item, provide the user with a multi-select field
		$list = $this->getList();
		$tags = [];
		if ( $list->length() == 1 ) {
			$list->reset();
			$tags = $list->current()->getTags();
			if ( $tags ) {
				$tags = explode( ',', $tags );
			} else {
				$tags = [];
			}

			$html = '<table id="mw-edittags-tags-selector">';
			$html .= '<tr><td>' . $this->msg( 'tags-edit-existing-tags' )->escaped() .
				'</td><td>';
			if ( $tags ) {
				$html .= $this->getLanguage()->commaList( array_map( 'htmlspecialchars', $tags ) );
			} else {
				$html .= $this->msg( 'tags-edit-existing-tags-none' )->parse();
			}
			$html .= '</td></tr>';
			$tagSelect = $this->getTagSelect( $tags, $this->msg( 'tags-edit-new-tags' )->plain() );
			$html .= '<tr><td>' . $tagSelect[0] . '</td><td>' . $tagSelect[1];
		} else {
			// Otherwise, use a multi-select field for adding tags, and a list of
			// checkboxes for removing them

			for ( $list->reset(); $list->current(); $list->next() ) {
				$currentTags = $list->current()->getTags();
				if ( $currentTags ) {
					$tags = array_merge( $tags, explode( ',', $currentTags ) );
				}
			}
			$tags = array_unique( $tags );

			$html = '<table id="mw-edittags-tags-selector-multi"><tr><td>';
			$tagSelect = $this->getTagSelect( [], $this->msg( 'tags-edit-add' )->plain() );
			$html .= '<p>' . $tagSelect[0] . '</p>' . $tagSelect[1] . '</td><td>';
			$html .= Html::element( 'p', [], $this->msg( 'tags-edit-remove' )->plain() );
			$html .= Html::element( 'input', [
				'type' => 'checkbox', 'name' => 'wpRemoveAllTags', 'value' => '1',
				'id' => 'mw-edittags-remove-all'
			] ) . '&nbsp;'
				. Html::label( $this->msg( 'tags-edit-remove-all-tags' )->plain(), 'mw-edittags-remove-all' );
			$i = 0; // used for generating checkbox IDs only
			foreach ( $tags as $tag ) {
				$id = 'mw-edittags-remove-' . $i++;
				$html .= Html::element( 'br' ) . "\n" . Html::element( 'input', [
					'type' => 'checkbox', 'name' => 'wpTagsToRemove[]', 'value' => $tag,
					'class' => 'mw-edittags-remove-checkbox', 'id' => $id,
				] ) . '&nbsp;' . Html::label( $tag, $id );
			}
		}

		// also output the tags currently applied as a hidden form field, so we
		// know what to remove from the revision/log entry when the form is submitted
		$html .= Html::hidden( 'wpExistingTags', implode( ',', $tags ) );
		$html .= '</td></tr></table>';

		return $html;
	}

	/**
	 * Returns a <select multiple> element with a list of change tags that can be
	 * applied by users.
	 *
	 * @param array $selectedTags The tags that should be preselected in the
	 * list. Any tags in this list, but not in the list returned by
	 * ChangeTagsStore::listExplicitlyDefinedTags, will be appended to the <select>
	 * element.
	 * @param string $label The text of a <label> to precede the <select>
	 * @return array HTML <label> element at index 0, HTML <select> element at
	 * index 1
	 */
	protected function getTagSelect( $selectedTags, $label ) {
		$result = [];
		$result[0] = Html::label( $label, 'mw-edittags-tag-list' );

		$select = new XmlSelect( 'wpTagList[]', 'mw-edittags-tag-list', $selectedTags );
		$select->setAttribute( 'multiple', 'multiple' );
		$select->setAttribute( 'size', '8' );

		$tags = $this->changeTagsStore->listExplicitlyDefinedTags();
		$tags = array_unique( array_merge( $tags, $selectedTags ) );

		// Values of $tags are also used as <option> labels
		$select->addOptions( array_combine( $tags, $tags ) );

		$result[1] = $select->getHTML();
		return $result;
	}

	/**
	 * UI entry point for form submission.
	 * @return bool
	 */
	protected function submit() {
		// Check edit token on submission
		$request = $this->getRequest();
		$token = $request->getVal( 'wpEditToken' );
		if ( $this->submitClicked && !$this->getUser()->matchEditToken( $token ) ) {
			$this->getOutput()->addWikiMsg( 'sessionfailure' );
			return false;
		}

		// Evaluate incoming request data
		$tagList = $request->getArray( 'wpTagList' ) ?? [];
		$existingTags = $request->getVal( 'wpExistingTags' );
		if ( $existingTags === null || $existingTags === '' ) {
			$existingTags = [];
		} else {
			$existingTags = explode( ',', $existingTags );
		}

		if ( count( $this->ids ) > 1 ) {
			// multiple revisions selected
			$tagsToAdd = $tagList;
			if ( $request->getBool( 'wpRemoveAllTags' ) ) {
				$tagsToRemove = $existingTags;
			} else {
				$tagsToRemove = $request->getArray( 'wpTagsToRemove', [] );
			}
		} else {
			// single revision selected
			// The user tells us which tags they want associated to the revision.
			// We have to figure out which ones to add, and which to remove.
			$tagsToAdd = array_diff( $tagList, $existingTags );
			$tagsToRemove = array_diff( $existingTags, $tagList );
		}

		if ( !$tagsToAdd && !$tagsToRemove ) {
			$status = Status::newFatal( 'tags-edit-none-selected' );
		} else {
			$status = $this->getList()->updateChangeTagsOnAll( $tagsToAdd,
				$tagsToRemove, null, $this->reason, $this->getAuthority() );
		}

		if ( $status->isGood() ) {
			$this->success();
			return true;
		} else {
			$this->failure( $status );
			return false;
		}
	}

	/**
	 * Report that the submit operation succeeded
	 */
	protected function success() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'actioncomplete' ) );
		$out->addHTML(
			Html::successBox( $out->msg( 'tags-edit-success' )->parse() )
		);
		$this->wasSaved = true;
		$this->revList->reloadFromPrimary();
		$this->reason = ''; // no need to spew the reason back at the user
		$this->showForm();
	}

	/**
	 * Report that the submit operation failed
	 * @param Status $status
	 */
	protected function failure( $status ) {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'actionfailed' ) );
		$out->addHTML(
			Html::errorBox(
				$out->parseAsContent(
					$status->getWikiText( 'tags-edit-failure', false, $this->getLanguage() )
				)
			)
		);
		$this->showForm();
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

/** @deprecated class alias since 1.41 */
class_alias( SpecialEditTags::class, 'SpecialEditTags' );
