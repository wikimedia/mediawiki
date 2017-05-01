<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * Special page for adding and removing change tags to individual revisions.
 * A lot of this is copied out of SpecialRevisiondelete.
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

	/** @var bool Whether user is allowed to perform the action */
	private $isAllowed;

	/** @var string */
	private $reason;

	public function __construct() {
		parent::__construct( 'EditTags', 'changetags' );
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$this->checkPermissions();
		$this->checkReadOnly();

		$output = $this->getOutput();
		$user = $this->getUser();
		$request = $this->getRequest();

		// Check blocks
		if ( $user->isBlocked() ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		$this->setHeaders();
		$this->outputHeader();

		$this->getOutput()->addModules( [ 'mediawiki.special.edittags',
			'mediawiki.special.edittags.styles' ] );

		$this->submitClicked = $request->wasPosted() && $request->getBool( 'wpSubmit' );

		// Handle our many different possible input types
		$ids = $request->getVal( 'ids' );
		if ( !is_null( $ids ) ) {
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

		// sanity check of parameter
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

		$this->isAllowed = $user->isAllowed( 'changetags' );

		$this->reason = $request->getVal( 'wpReason' );
		// We need a target page!
		if ( is_null( $this->targetObj ) ) {
			$output->addWikiMsg( 'undelete-header' );
			return;
		}
		// Give a link to the logs/hist for this page
		$this->showConvenienceLinks();

		// Either submit or create our form
		if ( $this->isAllowed && $this->submitClicked ) {
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

			$links = [];
			$links[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				$this->msg( 'viewpagelogs' )->escaped(),
				[],
				[
					'page' => $this->targetObj->getPrefixedText(),
					'hide_tag_log' => '0',
				]
			);
			if ( !$this->targetObj->isSpecialPage() ) {
				// Give a link to the page history
				$links[] = Linker::linkKnown(
					$this->targetObj,
					$this->msg( 'pagehist' )->escaped(),
					[],
					[ 'action' => 'history' ]
				);
			}
			// Link to Special:Tags
			$links[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Tags' ),
				$this->msg( 'tags-edit-manage-link' )->escaped()
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
		if ( is_null( $this->revList ) ) {
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
		$userAllowed = true;

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
		// @codingStandardsIgnoreStart Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
		for ( $list->reset(); $list->current(); $list->next() ) {
			// @codingStandardsIgnoreEnd
			$item = $list->current();
			$numRevisions++;
			$out->addHTML( $item->getHTML() );
		}

		if ( !$numRevisions ) {
			throw new ErrorPageError( 'tags-edit-nooldid-title', 'tags-edit-nooldid-text' );
		}

		$out->addHTML( "</ul>" );
		// Explanation text
		$out->wrapWikiMsg( '<p>$1</p>', "tags-edit-{$this->typeName}-explanation" );

		// Show form if the user can submit
		if ( $this->isAllowed ) {
			$form = Xml::openElement( 'form', [ 'method' => 'post',
					'action' => $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ),
					'id' => 'mw-revdel-form-revisions' ] ) .
				Xml::fieldset( $this->msg( "tags-edit-{$this->typeName}-legend",
					count( $this->ids ) )->text() ) .
				$this->buildCheckBoxes() .
				Xml::openElement( 'table' ) .
				"<tr>\n" .
					'<td class="mw-label">' .
						Xml::label( $this->msg( 'tags-edit-reason' )->text(), 'wpReason' ) .
					'</td>' .
					'<td class="mw-input">' .
						Xml::input(
							'wpReason',
							60,
							$this->reason,
							[ 'id' => 'wpReason', 'maxlength' => 100 ]
						) .
					'</td>' .
				"</tr><tr>\n" .
					'<td></td>' .
					'<td class="mw-submit">' .
						Xml::submitButton( $this->msg( "tags-edit-{$this->typeName}-submit",
							$numRevisions )->text(), [ 'name' => 'wpSubmit' ] ) .
					'</td>' .
				"</tr>\n" .
				Xml::closeElement( 'table' ) .
				Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() ) .
				Html::hidden( 'target', $this->targetObj->getPrefixedText() ) .
				Html::hidden( 'type', $this->typeName ) .
				Html::hidden( 'ids', implode( ',', $this->ids ) ) .
				Xml::closeElement( 'fieldset' ) . "\n" .
				Xml::closeElement( 'form' ) . "\n";
		} else {
			$form = '';
		}
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

			// @codingStandardsIgnoreStart Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
			for ( $list->reset(); $list->current(); $list->next() ) {
				// @codingStandardsIgnoreEnd
				$currentTags = $list->current()->getTags();
				if ( $currentTags ) {
					$tags = array_merge( $tags, explode( ',', $currentTags ) );
				}
			}
			$tags = array_unique( $tags );

			$html = '<table id="mw-edittags-tags-selector-multi"><tr><td>';
			$tagSelect = $this->getTagSelect( [], $this->msg( 'tags-edit-add' )->plain() );
			$html .= '<p>' . $tagSelect[0] . '</p>' . $tagSelect[1] . '</td><td>';
			$html .= Xml::element( 'p', null, $this->msg( 'tags-edit-remove' )->plain() );
			$html .= Xml::checkLabel( $this->msg( 'tags-edit-remove-all-tags' )->plain(),
				'wpRemoveAllTags', 'mw-edittags-remove-all' );
			$i = 0; // used for generating checkbox IDs only
			foreach ( $tags as $tag ) {
				$html .= Xml::element( 'br' ) . "\n" . Xml::checkLabel( $tag,
					'wpTagsToRemove[]', 'mw-edittags-remove-' . $i++, false, [
						'value' => $tag,
						'class' => 'mw-edittags-remove-checkbox',
					] );
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
	 * ChangeTags::listExplicitlyDefinedTags, will be appended to the <select>
	 * element.
	 * @param string $label The text of a <label> to precede the <select>
	 * @return array HTML <label> element at index 0, HTML <select> element at
	 * index 1
	 */
	protected function getTagSelect( $selectedTags, $label ) {
		$result = [];
		$result[0] = Xml::label( $label, 'mw-edittags-tag-list' );

		$select = new XmlSelect( 'wpTagList[]', 'mw-edittags-tag-list', $selectedTags );
		$select->setAttribute( 'multiple', 'multiple' );
		$select->setAttribute( 'size', '8' );

		$tags = ChangeTags::listExplicitlyDefinedTags();
		$tags = array_unique( array_merge( $tags, $selectedTags ) );

		// Values of $tags are also used as <option> labels
		$select->addOptions( array_combine( $tags, $tags ) );

		$result[1] = $select->getHTML();
		return $result;
	}

	/**
	 * UI entry point for form submission.
	 * @throws PermissionsError
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
		$tagList = $request->getArray( 'wpTagList' );
		if ( is_null( $tagList ) ) {
			$tagList = [];
		}
		$existingTags = $request->getVal( 'wpExistingTags' );
		if ( is_null( $existingTags ) || $existingTags === '' ) {
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
				$tagsToRemove = $request->getArray( 'wpTagsToRemove' );
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
				$tagsToRemove, null, $this->reason, $this->getUser() );
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
		$this->getOutput()->setPageTitle( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->wrapWikiMsg( "<div class=\"successbox\">\n$1\n</div>",
			'tags-edit-success' );
		$this->wasSaved = true;
		$this->revList->reloadFromMaster();
		$this->reason = ''; // no need to spew the reason back at the user
		$this->showForm();
	}

	/**
	 * Report that the submit operation failed
	 * @param Status $status
	 */
	protected function failure( $status ) {
		$this->getOutput()->setPageTitle( $this->msg( 'actionfailed' ) );
		$this->getOutput()->addWikiText( '<div class="errorbox">' .
			$status->getWikiText( 'tags-edit-failure' ) .
			'</div>'
		);
		$this->showForm();
	}

	public function getDescription() {
		return $this->msg( 'tags-edit-title' )->text();
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
