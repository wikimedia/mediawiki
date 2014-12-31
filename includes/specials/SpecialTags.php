<?php
/**
 * Implements Special:Tags
 *
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
 * A special page that lists tags for edits
 *
 * @ingroup SpecialPage
 */
class SpecialTags extends SpecialPage {
	/**
	 * @var array List of defined tags
	 */
	public $definedTags;

	/**
	 * @var array Tag usage data
	 */
	protected $tagUsage;

	/**
	 * Can't delete tags with more than this many uses. Similar in intent to
	 * the bigdelete user right
	 */
	const MAX_DELETE_USES = 5000;

	function __construct() {
		parent::__construct( 'Tags' );

		// We're going to need this eventually
		$this->tagUsage = ChangeTags::tagUsageStatistics();
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// Are we being asked to delete a tag?
		$request = $this->getRequest();
		switch ( $par ) {
			case 'delete':
				$this->showDeleteTagForm( $request->getVal( 'tag' ) );
				break;
			default:
				$this->showTagList();
				break;
		}
	}

	function showTagList() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'tags-title' ) );
		$out->wrapWikiMsg( "<div class='mw-tags-intro'>\n$1\n</div>", 'tags-intro' );

		$user = $this->getUser();
		// Whether to show the "Actions" column
		// If any actions added in the future require other user rights, add those
		// rights here
		$showActions = $user->isAllowed( 'managechangetags' );

		// Write the headers
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, $this->msg( 'tags-tag' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-display-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-description-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-active-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-hitcount-header' )->parse() ) .
			( $showActions ?
				Xml::tags( 'th', array( 'class' => 'unsortable' ),
					$this->msg( 'tags-actions-header' )->parse() ) :
				'' )
		);

		// Used in #doTagRow()
		$this->definedTags = array_fill_keys( ChangeTags::listDefinedTags(), true );

		foreach ( $this->tagUsage as $tag => $hitcount ) {
			$html .= $this->doTagRow( $tag, $hitcount, $showActions );
		}

		$out->addHTML( Xml::tags(
			'table',
			array( 'class' => 'wikitable sortable mw-tags-table' ),
			$html
		) );
	}

	function doTagRow( $tag, $hitcount, $showActions ) {
		$user = $this->getUser();
		$newRow = '';
		$newRow .= Xml::tags( 'td', null, Xml::element( 'code', null, $tag ) );

		$disp = ChangeTags::tagDescription( $tag );
		if ( $user->isAllowed( 'editinterface' ) ) {
			$disp .= ' ';
			$editLink = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag" ),
				$this->msg( 'tags-edit' )->escaped()
			);
			$disp .= $this->msg( 'parentheses' )->rawParams( $editLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $disp );

		$msg = $this->msg( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		if ( $user->isAllowed( 'editinterface' ) ) {
			$desc .= ' ';
			$editDescLink = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-description" ),
				$this->msg( 'tags-edit' )->escaped()
			);
			$desc .= $this->msg( 'parentheses' )->rawParams( $editDescLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $desc );

		$active = isset( $this->definedTags[$tag] ) ? 'tags-active-yes' : 'tags-active-no';
		$active = $this->msg( $active )->escaped();
		$newRow .= Xml::tags( 'td', null, $active );

		$hitcountLabel = $this->msg( 'tags-hitcount' )->numParams( $hitcount )->escaped();
		$hitcountLink = Linker::link(
			SpecialPage::getTitleFor( 'Recentchanges' ),
			$hitcountLabel,
			array(),
			array( 'tagfilter' => $tag )
		);

		// add raw $hitcount for sorting, because tags-hitcount contains numbers and letters
		$newRow .= Xml::tags( 'td', array( 'data-sort-value' => $hitcount ), $hitcountLink );

		// actions
		$actionLinks = array();
		if ( $showActions ) {
			if ( $this->canDeleteTag( $tag )->isGood() ) {
				$actionLinks[] = Linker::linkKnown( $this->getPageTitle( 'delete' ),
					$this->msg( 'tags-delete' )->escaped(),
					array(),
					array( 'tag' => $tag ) );
			}

			$newRow .= Xml::tags( 'td', null, $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}

	/**
	 * @param string $tag Tag that you are interested in deleting
	 * @return Status
	 */
	public function canDeleteTag( $tag ) {
		if ( !isset( $this->tagUsage[$tag] ) ) {
			return Status::newFatal( 'tags-delete-not-found', $tag );
		}
		if ( $this->tagUsage[$tag] > self::MAX_DELETE_USES ) {
			return Status::newFatal( 'tags-delete-too-many-uses', $tag, self::MAX_DELETE_USES );
		}

		$status = Status::newGood();
		Hooks::run( 'SpecialTagsCanDeleteTag', array( $tag, $status ) );
		return $status;
	}

	protected function showDeleteTagForm( $tag ) {
		$user = $this->getUser();
		if ( !$user->isAllowed( 'managechangetags' ) ) {
			throw new PermissionsError( 'managechangetags' );
		}

		$out = $this->getOutput();
		$out->preventClickjacking();
		$out->setPageTitle( $this->msg( 'tags-delete-title' ) );

		// is the tag actually able to be deleted?
		$canDeleteResult = $this->canDeleteTag( $tag );
		if ( !$canDeleteResult->isGood() ) {
			$out->addWikiText( "<div class=\"error\">\n" . $canDeleteResult->getWikiText() .
				"\n</div>" );
			if ( !$canDeleteResult->isOK() ) {
				return;
			}
		}

		$preText = $this->msg( 'tags-delete-explanation-initial', $tag )->parseAsBlock();
		if ( $this->tagUsage[$tag] > 0 ) {
			$preText .= $this->msg( 'tags-delete-explanation-in-use', $tag,
				$this->tagUsage[$tag] )->parseAsBlock();
		}
		$preText .= $this->msg( 'tags-delete-explanation-warning', $tag )->parseAsBlock();

		// see if the tag is in use
		$definedTags = ChangeTags::listDefinedTags();
		if ( in_array( $tag, $definedTags ) ) {
			$preText .= $this->msg( 'tags-delete-explanation-active', $tag )->parseAsBlock();
		}

		$fields = array();
		$fields['Reason'] = array(
			'type' => 'text',
			'label' => $this->msg( 'tags-delete-reason' )->plain(),
			'size' => 50,
		);
		$fields['HiddenTag'] = array(
			'type' => 'hidden',
			'name' => 'tag',
			'default' => $tag,
			'required' => true,
		);

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setAction( $this->getPageTitle( 'delete' )->getLocalURL() );
		$form->setSubmitCallback( array( $this, 'processDeleteTagForm' ) );
		$form->setSubmitTextMsg( 'tags-delete-submit' );
		$form->addPreText( $preText );
		$form->show();

		// if $form->show() didn't send us off somewhere else, let's set our
		// breadcrumb link
		$out->addBacklinkSubtitle( $this->getPageTitle() );
	}

	public function processDeleteTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$status = $this->processDeleteTag( $data['HiddenTag'], $data['Reason'],
			$context );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} else {
			$out->addWikiText( "<div class=\"error\">\n" . $status->getWikitext() .
				"\n</div>" );
			return false;
		}
	}

	/**
	 * @param string $tag
	 * @param string $reason
	 * @param IContextSource $context
	 * @param bool $ignoreWarnings Can be used for API interaction, default false
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 */
	public function processDeleteTag( $tag, $reason, IContextSource $context,
		$ignoreWarnings = false ) {

		// does the tag exist?
		$canDeleteResult = $this->canDeleteTag( $tag );
		if ( $ignoreWarnings ? !$canDeleteResult->isOK() : !$canDeleteResult->isGood() ) {
			return $canDeleteResult;
		}

		// do it!
		$deleteResult = ChangeTags::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isGood() ) {
			return $deleteResult;
		}

		// log it
		$dbw = wfGetDB( DB_MASTER );
		$logEntry = new ManualLogEntry( 'tagmanagement', 'delete' );
		$logEntry->setPerformer( $context->getUser() );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( $this->getPageTitle() );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( array(
			'4:tag' => $tag,
			'5:count' => $this->tagUsage[$tag],
		) );
		$logEntry->setRelations( array( 'Tag' => $tag ) );
		$logId = $logEntry->insert( $dbw );

		// refresh the tag statistics
		$this->tagUsage = ChangeTags::tagUsageStatistics();

		return Status::newGood( $logId );
	}

	protected function getGroupName() {
		return 'changes';
	}
}
