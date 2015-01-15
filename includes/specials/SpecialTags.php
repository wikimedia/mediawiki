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

		foreach ( ChangeTags::tagUsageStatistics() as $tag => $hitcount ) {
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
			if ( ChangeTags::canDeleteTag( $tag, $user )->isOK() ) {
				$actionLinks[] = Linker::linkKnown( $this->getPageTitle( 'delete' ),
					$this->msg( 'tags-delete' )->escaped(),
					array(),
					array( 'tag' => $tag ) );
			}

			$newRow .= Xml::tags( 'td', null, $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Xml::tags( 'tr', null, $newRow ) . "\n";
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
		$canDeleteResult = ChangeTags::canDeleteTag( $tag, $user );
		if ( !$canDeleteResult->isGood() ) {
			$out->addWikiText( "<div class=\"error\">\n" . $canDeleteResult->getWikiText() .
				"\n</div>" );
			if ( !$canDeleteResult->isOK() ) {
				return;
			}
		}

		$preText = $this->msg( 'tags-delete-explanation-initial', $tag )->parseAsBlock();
		$tagUsage = ChangeTags::tagUsageStatistics();
		if ( $tagUsage[$tag] > 0 ) {
			$preText .= $this->msg( 'tags-delete-explanation-in-use', $tag,
				$tagUsage[$tag] )->parseAsBlock();
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
		$form->setSubmitDestructive(); // nasty!
		$form->addPreText( $preText );
		$form->show();

		// if $form->show() didn't send us off somewhere else, let's set our
		// breadcrumb link
		$out->addBacklinkSubtitle( $this->getPageTitle() );
	}

	public function processDeleteTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$status = ChangeTags::deleteTagWithChecks( $data['HiddenTag'], $data['Reason'],
			$context->getUser(), true );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} else {
			$out->addWikiText( "<div class=\"error\">\n" . $status->getWikitext() .
				"\n</div>" );
			return false;
		}
	}

	protected function getGroupName() {
		return 'changes';
	}
}
