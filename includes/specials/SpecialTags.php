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

	function __construct() {
		parent::__construct( 'Tags' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// Are we being asked to delete a tag?
		$request = $this->getRequest();
		switch ( $request->getVal( 'action' ) ) {
			case 'delete':
				$this->deleteTag( $request->getVal( 'tag' ) );
				break;
			default:
				$this->doTagList();
				break;
		}
	}

	function doTagList() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'tags-title' ) );
		$out->wrapWikiMsg( "<div class='mw-tags-intro'>\n$1\n</div>", 'tags-intro' );

		$user = $this->getUser();
		// Whether to show the "Actions" column
		// If any actions added in the future require other user rights, add those
		// rights here
		$showActions = $user->isAllowedAny( 'managechangetags' );

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

		foreach ( ChangeTags::tagUsageStatistics() as $tag => $hitcount ) {
			$html .= $this->doTagRow( $tag, $hitcount );
		}

		$out->addHTML( Xml::tags(
			'table',
			array( 'class' => 'wikitable sortable mw-tags-table' ),
			$html
		) );
	}

	function doTagRow( $tag, $hitcount ) {
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
		if ( $user->isAllowed( 'managechangetags' ) ) {
			$actionLinks[] = Linker::linkKnown( $this->getPageTitle(),
				$this->msg( 'tags-delete' )->escaped(),
				array(),
				array( 'action' => 'delete', 'tag' => $tag ) );
		}
		if ( count( $actionLinks ) ) {
			$newRow .= Xml::tags( 'td', null, $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}

	protected function deleteTag( $tag ) {
		$user = $this->getUser();
		if ( !$user->isAllowed( 'managechangetags' ) ) {
			throw new PermissionsError( 'managechangetags' );
		}

		$out = $this->getOutput();
		$out->preventClickjacking();
		$out->setPageTitle( $this->msg( 'tags-delete-title' ) );

		// is the tag actually able to be deleted?
		$tagUsage = ChangeTags::tagUsageStatistics();
		if ( !isset( $tagUsage[$tag] ) ) {
			$out->wrapWikiMsg( "<div class=\"error\">\n$1\n</div>",
				array( 'tags-delete-not-found', $tag ) );
			return;
		} else {
			$preText = $this->msg( 'tags-delete-explanation-initial', $tag )->parseAsBlock();
			if ( $tagUsage[$tag] > 0 ) {
				$preText .= $this->msg( 'tags-delete-explanation-in-use', $tag,
					$tagUsage[$tag] )->parseAsBlock();
			}
			$preText .= $this->msg( 'tags-delete-explanation-warning', $tag )->parseAsBlock();
		}
		// see if the tag is in use
		$definedTags = ChangeTags::listDefinedTags();
		if ( in_array( $tag, $definedTags ) ) {
			$preText .= $this->msg( 'tags-delete-explanation-active', $tag )->parseAsBlock();
		}

		$fields = array();
		if ( $user->isAllowedAll( 'editinterface', 'delete' ) ) {
			$fields['DeleteMessages'] = array(
				'type' => 'check',
				'label-message' => 'tags-delete-messages',
				'default' => false,
			);
		}
		$fields['HiddenAction'] = array(
			'type' => 'hidden',
			'name' => 'action',
			'default' => 'delete',
		);
		$fields['HiddenTag'] = array(
			'type' => 'hidden',
			'name' => 'tag',
			'default' => $tag,
		);

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setSubmitCallback( array( __CLASS__, 'processDeleteTag' ) );
		$form->setSubmitTextMsg( 'tags-delete-submit' );
		$form->addPreText( $preText );
		$form->show();

		// if $form->show() didn't send us off somewhere else, let's set our
		// breadcrumb link
		$out->addBacklinkSubtitle( $this->getPageTitle() );
	}

	public static function processDeleteTag( array $data, HTMLForm $form ) {
		$tag = $data['HiddenTag'];
		if ( !$tag ) {
			// nothing to do...
			$sp = new SpecialTags;
			$sp->doTagList();
			return true;
		}

		$context = $form->getContext();
		$out = $context->getOutput();

		// does the tag exist?
		$tagUsage = ChangeTags::tagUsageStatistics();
		if ( !isset( $tagUsage[$tag] ) ) {
			$out->wrapWikiMsg( "<div class=\"error\">\n$1\n</div>",
				array( 'tags-delete-not-found', $tag ) );
			return false;
		}

		// do it!
		if ( ChangeTags::deleteTagEverywhere( $tag ) ) {
			$out->wrapWikiMsg( "<div class=\"success\">\n$1\n</div>",
				'tags-delete-success', $data['HiddenTag'] );
			$sp = new SpecialTags;
			$sp->doTagList();
			return true;
		} else {
			// some kind of error
			$out->wrapWikiMsg( "<div class=\"error\">\n$1\n</div>",
				'tags-delete-error', $data['HiddenTag'] );
			// redisplay the form, in case the error was transient
			return false;
		}
	}

	protected function getGroupName() {
		return 'changes';
	}
}
