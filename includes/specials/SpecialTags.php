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
	 * @var array List of active tags
	 */
	public $activeTags;

	function __construct() {
		parent::__construct( 'Tags' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		switch ( $par ) {
			case 'delete':
				$this->showDeleteTagForm( $request->getVal( 'tag' ) );
				break;
			case 'activate':
				$this->showActivateDeactivateForm( $request->getVal( 'tag' ), true );
				break;
			case 'deactivate':
				$this->showActivateDeactivateForm( $request->getVal( 'tag' ), false );
				break;
			case 'create':
				// fall through, thanks to HTMLForm's logic
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

		// Show form to create a tag
		if ( $user->isAllowed( 'managechangetags' ) ) {
			$fields = array(
				'Tag' => array(
					'type' => 'text',
					'label' => $this->msg( 'tags-create-tag-name' )->plain(),
					'required' => true,
				),
				'Reason' => array(
					'type' => 'text',
					'label' => $this->msg( 'tags-create-reason' )->plain(),
					'size' => 50,
				),
				'IgnoreWarnings' => array(
					'type' => 'hidden',
				),
			);

			$form = new HTMLForm( $fields, $this->getContext() );
			$form->setAction( $this->getPageTitle( 'create' )->getLocalURL() );
			$form->setWrapperLegendMsg( 'tags-create-heading' );
			$form->setHeaderText( $this->msg( 'tags-create-explanation' )->plain() );
			$form->setSubmitCallback( array( $this, 'processCreateTagForm' ) );
			$form->setSubmitTextMsg( 'tags-create-submit' );
			$form->show();

			// If processCreateTagForm generated a redirect, there's no point
			// continuing with this, as the user is just going to end up getting sent
			// somewhere else. Additionally, if we keep going here, we end up
			// populating the memcache of tag data (see ChangeTags::listDefinedTags)
			// with out-of-date data from the slave, because the slave hasn't caught
			// up to the fact that a new tag has been created as part of an implicit,
			// as yet uncommitted transaction on master.
			if ( $out->getRedirect() !== '' ) {
				return;
			}
		}

		// Whether to show the "Actions" column in the tag list
		// If any actions added in the future require other user rights, add those
		// rights here
		$showActions = $user->isAllowed( 'managechangetags' );

		// Write the headers
		$tagUsageStatistics = ChangeTags::tagUsageStatistics();

		// Show header only if there exists atleast one tag
		if ( !$tagUsageStatistics ) {
			return;
		}
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, $this->msg( 'tags-tag' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-display-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-description-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-source-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-active-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-hitcount-header' )->parse() ) .
			( $showActions ?
				Xml::tags( 'th', array( 'class' => 'unsortable' ),
					$this->msg( 'tags-actions-header' )->parse() ) :
				'' )
		);

		// Used in #doTagRow()
		$this->explicitlyDefinedTags = array_fill_keys(
			ChangeTags::listExplicitlyDefinedTags(), true );
		$this->extensionDefinedTags = array_fill_keys(
			ChangeTags::listExtensionDefinedTags(), true );
		$this->extensionActivatedTags = array_fill_keys(
			ChangeTags::listExtensionActivatedTags(), true );

		foreach ( $tagUsageStatistics as $tag => $hitcount ) {
			$html .= $this->doTagRow( $tag, $hitcount, $showActions );
		}

		$out->addHTML( Xml::tags(
			'table',
			array( 'class' => 'mw-datatable sortable mw-tags-table' ),
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

		$sourceMsgs = array();
		$isExtension = isset( $this->extensionDefinedTags[$tag] );
		$isExplicit = isset( $this->explicitlyDefinedTags[$tag] );
		if ( $isExtension ) {
			$sourceMsgs[] = $this->msg( 'tags-source-extension' )->escaped();
		}
		if ( $isExplicit ) {
			$sourceMsgs[] = $this->msg( 'tags-source-manual' )->escaped();
		}
		if ( !$sourceMsgs ) {
			$sourceMsgs[] = $this->msg( 'tags-source-none' )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, implode( Xml::element( 'br' ), $sourceMsgs ) );

		$isActive = $isExplicit || isset( $this->extensionActivatedTags[$tag] );
		$activeMsg = ( $isActive ? 'tags-active-yes' : 'tags-active-no' );
		$newRow .= Xml::tags( 'td', null, $this->msg( $activeMsg )->escaped() );

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
			// delete
			if ( ChangeTags::canDeleteTag( $tag, $user )->isOK() ) {
				$actionLinks[] = Linker::linkKnown( $this->getPageTitle( 'delete' ),
					$this->msg( 'tags-delete' )->escaped(),
					array(),
					array( 'tag' => $tag ) );
			}

			// activate
			if ( ChangeTags::canActivateTag( $tag, $user )->isOK() ) {
				$actionLinks[] = Linker::linkKnown( $this->getPageTitle( 'activate' ),
					$this->msg( 'tags-activate' )->escaped(),
					array(),
					array( 'tag' => $tag ) );
			}

			// deactivate
			if ( ChangeTags::canDeactivateTag( $tag, $user )->isOK() ) {
				$actionLinks[] = Linker::linkKnown( $this->getPageTitle( 'deactivate' ),
					$this->msg( 'tags-deactivate' )->escaped(),
					array(),
					array( 'tag' => $tag ) );
			}

			$newRow .= Xml::tags( 'td', null, $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}

	public function processCreateTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = trim( strval( $data['Tag'] ) );
		$ignoreWarnings = isset( $data['IgnoreWarnings'] ) && $data['IgnoreWarnings'] === '1';
		$status = ChangeTags::createTagWithChecks( $tag, $data['Reason'],
			$context->getUser(), $ignoreWarnings );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} elseif ( $status->isOK() ) {
			// we have some warnings, so we show a confirmation form
			$fields = array(
				'Tag' => array(
					'type' => 'hidden',
					'default' => $data['Tag'],
				),
				'Reason' => array(
					'type' => 'hidden',
					'default' => $data['Reason'],
				),
				'IgnoreWarnings' => array(
					'type' => 'hidden',
					'default' => '1',
				),
			);

			// fool HTMLForm into thinking the form hasn't been submitted yet. Otherwise
			// we get into an infinite loop!
			$context->getRequest()->unsetVal( 'wpEditToken' );

			$headerText = $this->msg( 'tags-create-warnings-above', $tag,
				count( $status->getWarningsArray() ) )->parseAsBlock() .
				$out->parse( $status->getWikitext() ) .
				$this->msg( 'tags-create-warnings-below' )->parseAsBlock();

			$subform = new HTMLForm( $fields, $this->getContext() );
			$subform->setAction( $this->getPageTitle( 'create' )->getLocalURL() );
			$subform->setWrapperLegendMsg( 'tags-create-heading' );
			$subform->setHeaderText( $headerText );
			$subform->setSubmitCallback( array( $this, 'processCreateTagForm' ) );
			$subform->setSubmitTextMsg( 'htmlform-yes' );
			$subform->show();

			$out->addBacklinkSubtitle( $this->getPageTitle() );
			return true;
		} else {
			$out->addWikiText( "<div class=\"error\">\n" . $status->getWikitext() .
				"\n</div>" );
			return false;
		}
	}

	protected function showDeleteTagForm( $tag ) {
		$user = $this->getUser();
		if ( !$user->isAllowed( 'managechangetags' ) ) {
			throw new PermissionsError( 'managechangetags' );
		}

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'tags-delete-title' ) );
		$out->addBacklinkSubtitle( $this->getPageTitle() );

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
		$this->extensionActivatedTags = array_fill_keys(
			ChangeTags::listExtensionActivatedTags(), true );
		if ( isset( $this->extensionActivatedTags[$tag] ) ) {
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
		$form->tagAction = 'delete'; // custom property on HTMLForm object
		$form->setSubmitCallback( array( $this, 'processTagForm' ) );
		$form->setSubmitTextMsg( 'tags-delete-submit' );
		$form->setSubmitDestructive(); // nasty!
		$form->addPreText( $preText );
		$form->show();
	}

	protected function showActivateDeactivateForm( $tag, $activate ) {
		$actionStr = $activate ? 'activate' : 'deactivate';

		$user = $this->getUser();
		if ( !$user->isAllowed( 'managechangetags' ) ) {
			throw new PermissionsError( 'managechangetags' );
		}

		$out = $this->getOutput();
		// tags-activate-title, tags-deactivate-title
		$out->setPageTitle( $this->msg( "tags-$actionStr-title" ) );
		$out->addBacklinkSubtitle( $this->getPageTitle() );

		// is it possible to do this?
		$func = $activate ? 'canActivateTag' : 'canDeactivateTag';
		$result = ChangeTags::$func( $tag, $user );
		if ( !$result->isGood() ) {
			$out->wrapWikiMsg( "<div class=\"error\">\n$1" . $result->getWikiText() .
				"\n</div>" );
			if ( !$result->isOK() ) {
				return;
			}
		}

		// tags-activate-question, tags-deactivate-question
		$preText = $this->msg( "tags-$actionStr-question", $tag )->parseAsBlock();

		$fields = array();
		// tags-activate-reason, tags-deactivate-reason
		$fields['Reason'] = array(
			'type' => 'text',
			'label' => $this->msg( "tags-$actionStr-reason" )->plain(),
			'size' => 50,
		);
		$fields['HiddenTag'] = array(
			'type' => 'hidden',
			'name' => 'tag',
			'default' => $tag,
			'required' => true,
		);

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setAction( $this->getPageTitle( $actionStr )->getLocalURL() );
		$form->tagAction = $actionStr;
		$form->setSubmitCallback( array( $this, 'processTagForm' ) );
		// tags-activate-submit, tags-deactivate-submit
		$form->setSubmitTextMsg( "tags-$actionStr-submit" );
		$form->addPreText( $preText );
		$form->show();
	}

	public function processTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = $data['HiddenTag'];
		$status = call_user_func( array( 'ChangeTags', "{$form->tagAction}TagWithChecks" ),
			$tag, $data['Reason'], $context->getUser(), true );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} elseif ( $status->isOK() && $form->tagAction === 'delete' ) {
			// deletion succeeded, but hooks raised a warning
			$out->addWikiText( $this->msg( 'tags-delete-warnings-after-delete', $tag,
				count( $status->getWarningsArray() ) )->text() . "\n" .
				$status->getWikitext() );
			$out->addReturnTo( $this->getPageTitle() );
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
