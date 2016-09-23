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
	 * @var changeTagsContext object providing a unique context for
	 * all individual ChangeTag instances, i.e. :
	 * tag usage statistics, array of stored tags,
	 * array of registered tags with their params.
	 */
	private $changeTagsContext = null;

	function __construct() {
		parent::__construct( 'Tags' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// Make ChangeTagsContext object to provide unified
		// information for all ChangeTag instances
		$this->changeTagsContext = new ChangeTagsContext( $this->getConfig() );

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
		$userCanManage = $user->isAllowed( 'managechangetags' );
		$userCanDelete = $user->isAllowed( 'deletechangetags' );
		$userCanEditInterface = $user->isAllowed( 'editinterface' );

		// Show form to create a tag
		if ( $userCanManage ) {
			$fields = [
				'Tag' => [
					'type' => 'text',
					'label' => $this->msg( 'tags-create-tag-name' )->plain(),
					'required' => true,
				],
				'Reason' => [
					'type' => 'text',
					'label' => $this->msg( 'tags-create-reason' )->plain(),
					'size' => 50,
				],
				'IgnoreWarnings' => [
					'type' => 'hidden',
				],
			];

			$form = new HTMLForm( $fields, $this->getContext() );
			$form->setAction( $this->getPageTitle( 'create' )->getLocalURL() );
			$form->setWrapperLegendMsg( 'tags-create-heading' );
			$form->setHeaderText( $this->msg( 'tags-create-explanation' )->parseAsBlock() );
			$form->setSubmitCallback( [ $this, 'processCreateTagForm' ] );
			$form->setSubmitTextMsg( 'tags-create-submit' );
			$form->show();

			// If processCreateTagForm generated a redirect, there's no point
			// continuing with this, as the user is just going to end up getting sent
			// somewhere else. Additionally, if we keep going here, we end up
			// populating the memcache of tag data (see ChangeTags::listDefinedTags)
			// with out-of-date data from the replica DB, because the replica DB hasn't caught
			// up to the fact that a new tag has been created as part of an implicit,
			// as yet uncommitted transaction on master.
			if ( $out->getRedirect() !== '' ) {
				return;
			}
		}
		$out->wrapWikiMsg( "<div class='mw-tags-settings'>\n$1\n</div>", 'tags-settings' );

		// Retrieve stats and definitions
		$tagStats = $this->changeTagsContext->getTagStats();
		$definedTags = $this->changeTagsContext->getDefinedTags();

		// Show header only if there exists at least one tag
		if ( !$tagStats && !$definedTags ) {
			return;
		}

		// Write the headers
		$html = Xml::tags( 'tr', null, Xml::tags( 'th', null, $this->msg( 'tags-tag' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-appearance-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-display-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-description-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-source-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-active-header' )->parse() ) .
			Xml::tags( 'th', null, $this->msg( 'tags-hitcount-header' )->parse() ) .
			( ( $userCanManage || $userCanDelete ) ?
				Xml::tags( 'th', [ 'class' => 'unsortable' ],
					$this->msg( 'tags-actions-header' )->parse() ) :
				'' )
		);

		// Append tag rows for tags applied at least once (based on change_tag table)
		foreach ( $tagStats as $tag => $hitcount ) {
			$html .= $this->doTagRow( $tag, $hitcount, $userCanManage,
				$userCanDelete, $userCanEditInterface );
		}

		// Append tag rows for tags that are not (currently) applied but are defined somewhere
		foreach ( $definedTags as $tag => &$val ) {
			if ( !isset( $tagStats[$tag] ) ) {
				$html .= $this->doTagRow( $tag, 0, $userCanManage, $userCanDelete,
					$userCanEditInterface );
			}
		}

		$out->addHTML( Xml::tags(
			'table',
			[ 'class' => 'mw-datatable sortable mw-tags-table' ],
			$html
		) );
	}

	function doTagRow( $tag, $hitcount, $showManageActions, $showDeleteActions,
		$showEditLinks
	) {

		// Build change tag object
		$changeTag = new ChangeTag( $tag, $this->changeTagsContext );

		// Try to retrieve extension name (when relevant)
		$extName = $changeTag->getExtensionName();

		$newRow = '';
		$newRow .= Xml::tags( 'td', null, Xml::element( 'code', null, $tag ) );

		$linkRenderer = $this->getLinkRenderer();

		$app = ChangeTags::tagAppearance( $tag );
		if ( $showEditLinks ) {
			$app .= ' ';
			$editLink = $linkRenderer->makeLink(
				Title::makeTitle( NS_MEDIAWIKI, "Tag-$tag-appearance" ),
				$this->msg( 'tags-edit' )->escaped()
			);
			$app .= $this->msg( 'parentheses' )->rawParams( $editLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $app );

		$disp = ChangeTags::tagDescription( $tag );
		if ( $showEditLinks ) {
			$disp .= ' ';
			$editLink = $linkRenderer->makeLink(
				$this->msg( "tag-$tag" )->inContentLanguage()->getTitle(),
				$this->msg( 'tags-edit' )->text()
			);
			$disp .= $this->msg( 'parentheses' )->rawParams( $editLink )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, $disp );

		$msg = $this->msg( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		if ( $showEditLinks ) {
			$desc .= ' ';
			$editDescLink = $linkRenderer->makeLink(
				$this->msg( "tag-$tag-description" )->inContentLanguage()->getTitle(),
				$this->msg( 'tags-edit' )->text()
			);
			$desc .= $this->msg( 'parentheses' )->rawParams( $editDescLink )->escaped();
		}
		if ( $extName ) {
			// Add a description specific to the extension source with params from hook
			$msgKey = 'tags-description-extension-' . $extName;
			$extMsg = $this->msg( $msgKey );
			if ( $extMsg->exists() ) {
				$desc .= $desc ? Xml::element( 'br' ) : '';
				$extParams = $changeTag->getExtensionDescriptionMessageParams();
				if ( $extParams ) {
					$desc .= $extMsg->params( $extParams )->parse();
				} else {
					$desc .= $extMsg->parse();
				}
			}
		}
		$newRow .= Xml::tags( 'td', null, $desc );

		$sourceMsgs = [];
		if ( $changeTag->isSoftwareDefined() ) {
			// default message key
			// TODO: Rename this message
			$msgKey = 'tags-source-extension';
			// if specific source msg exists, overwrite default
			if ( $extName ) {
				$extMsgKey = 'tags-source-extension-' . $extName;
				$extMsg = $this->msg( $extMsgKey );
				if ( $extMsg->exists() ) {
					$msgKey = $extMsgKey;
				}
			}
			$sourceMsgs[] = $this->msg( $msgKey )->escaped();
		}
		if ( $changeTag->isUserDefined() ) {
			$sourceMsgs[] = $this->msg( 'tags-source-manual' )->escaped();
		}
		if ( !$sourceMsgs ) {
			$sourceMsgs[] = $this->msg( 'tags-source-none' )->escaped();
		}
		$newRow .= Xml::tags( 'td', null, implode( Xml::element( 'br' ), $sourceMsgs ) );

		$activeMsg = $changeTag->isActive() ? 'tags-active-yes' : 'tags-active-no';
		$newRow .= Xml::tags( 'td', null, $this->msg( $activeMsg )->escaped() );

		$hitcountLabelMsg = $this->msg( 'tags-hitcount' )->numParams( $hitcount );
		if ( $this->getConfig()->get( 'UseTagFilter' ) ) {
			$hitcountLabel = $linkRenderer->makeLink(
				SpecialPage::getTitleFor( 'Recentchanges' ),
				$hitcountLabelMsg->text(),
				[],
				[ 'tagfilter' => $tag ]
			);
		} else {
			$hitcountLabel = $hitcountLabelMsg->escaped();
		}

		// add raw $hitcount for sorting, because tags-hitcount contains numbers and letters
		$newRow .= Xml::tags( 'td', [ 'data-sort-value' => $hitcount ], $hitcountLabel );

		// actions
		$actionLinks = [];

		// delete (we already know the user is allowed and the tag exists)
		if ( $showDeleteActions && $changeTag->canDelete( null, false )->isOK() ) {
			$actionLinks[] = $linkRenderer->makeKnownLink(
				$this->getPageTitle( 'delete' ),
				$this->msg( 'tags-delete' )->text(),
				[],
				[ 'tag' => $tag ] );
		}

		if ( $showManageActions ) { // we've already checked that the user had the requisite userright

			// activate (we already know the user is allowed and the tag exists)
			if ( $changeTag->canActivate( null, false )->isOK() ) {
				$actionLinks[] = $linkRenderer->makeKnownLink(
					$this->getPageTitle( 'activate' ),
					$this->msg( 'tags-activate' )->text(),
					[],
					[ 'tag' => $tag ] );
			}

			// deactivate (we already know the user is allowed and the tag exists)
			if ( $changeTag->canDeactivate( null, false )->isOK() ) {
				$actionLinks[] = $linkRenderer->makeKnownLink(
					$this->getPageTitle( 'deactivate' ),
					$this->msg( 'tags-deactivate' )->text(),
					[],
					[ 'tag' => $tag ] );
			}

		}

		if ( $showDeleteActions || $showManageActions ) {
			$newRow .= Xml::tags( 'td', null, $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Xml::tags( 'tr', null, $newRow ) . "\n";
	}

	public function processCreateTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = trim( strval( $data['Tag'] ) );
		$ignoreWarnings = isset( $data['IgnoreWarnings'] ) && $data['IgnoreWarnings'] === '1';
		$changeTagsManager = new ChangeTagsManager(
			$this->changeTagsContext,
			$context->getUser(),
			$ignoreWarnings
		);
		$status = $this->changeTagsManager->createTagWithChecks( $tag, $data['Reason'] );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} elseif ( $status->isOK() ) {
			// we have some warnings, so we show a confirmation form
			$fields = [
				'Tag' => [
					'type' => 'hidden',
					'default' => $data['Tag'],
				],
				'Reason' => [
					'type' => 'hidden',
					'default' => $data['Reason'],
				],
				'IgnoreWarnings' => [
					'type' => 'hidden',
					'default' => '1',
				],
			];

			// fool HTMLForm into thinking the form hasn't been submitted yet. Otherwise
			// we get into an infinite loop!
			$context->getRequest()->unsetVal( 'wpEditToken' );

			$headerText = $this->msg( 'tags-create-warnings-above', $tag,
				count( $status->getWarningsArray() ) )->parseAsBlock() .
				$out->parse( $status->getWikiText() ) .
				$this->msg( 'tags-create-warnings-below' )->parseAsBlock();

			$subform = new HTMLForm( $fields, $this->getContext() );
			$subform->setAction( $this->getPageTitle( 'create' )->getLocalURL() );
			$subform->setWrapperLegendMsg( 'tags-create-heading' );
			$subform->setHeaderText( $headerText );
			$subform->setSubmitCallback( [ $this, 'processCreateTagForm' ] );
			$subform->setSubmitTextMsg( 'htmlform-yes' );
			$subform->show();

			$out->addBacklinkSubtitle( $this->getPageTitle() );
			return true;
		} else {
			$out->addWikiText( "<div class=\"error\">\n" . $status->getWikiText() .
				"\n</div>" );
			return false;
		}
	}

	protected function showDeleteTagForm( $tag ) {
		$user = $this->getUser();
		if ( !$user->isAllowed( 'deletechangetags' ) ) {
			throw new PermissionsError( 'deletechangetags' );
		}

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'tags-delete-title' ) );
		$out->addBacklinkSubtitle( $this->getPageTitle() );

		$changeTag = new ChangeTag( $tag, $this->changeTagsContext );

		// is the tag actually able to be deleted?
		$canDeleteResult = $changeTag->canDelete( $user );
		if ( !$canDeleteResult->isGood() ) {
			$out->addWikiText( "<div class=\"error\">\n" . $canDeleteResult->getWikiText() .
				"\n</div>" );
			if ( !$canDeleteResult->isOK() ) {
				return;
			}
		}

		$preText = $this->msg( 'tags-delete-explanation-initial', $tag )->parseAsBlock();

		// see if the tag has been previously applied
		$hitcount = $changeTag->getHitcount();
		if ( $hitcount > 0 ) {
			$preText .= $this->msg( 'tags-delete-explanation-in-use', $tag,
				$hitcount )->parseAsBlock();
		}
		$preText .= $this->msg( 'tags-delete-explanation-warning', $tag )->parseAsBlock();

		// see if the tag is in use
		if ( $changeTag->isSoftwareDefined() && $changeTag->isActive() ) {
			$preText .= $this->msg( 'tags-delete-explanation-active', $tag )->parseAsBlock();
		}

		$fields = [];
		$fields['Reason'] = [
			'type' => 'text',
			'label' => $this->msg( 'tags-delete-reason' )->plain(),
			'size' => 50,
		];
		$fields['HiddenTag'] = [
			'type' => 'hidden',
			'name' => 'tag',
			'default' => $tag,
			'required' => true,
		];

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setAction( $this->getPageTitle( 'delete' )->getLocalURL() );
		$form->tagAction = 'delete'; // custom property on HTMLForm object
		$form->setSubmitCallback( [ $this, 'processTagForm' ] );
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
		$changeTag = new ChangeTag( $tag, $this->changeTagsContext );
		$func = $activate ? 'canActivate' : 'canDeactivate';
		$result = $changeTag->$func( $user );
		if ( !$result->isGood() ) {
			$out->addWikiText( "<div class=\"error\">\n" . $result->getWikiText() .
				"\n</div>" );
			if ( !$result->isOK() ) {
				return;
			}
		}

		// tags-activate-question, tags-deactivate-question
		$preText = $this->msg( "tags-$actionStr-question", $tag )->parseAsBlock();

		$fields = [];
		// tags-activate-reason, tags-deactivate-reason
		$fields['Reason'] = [
			'type' => 'text',
			'label' => $this->msg( "tags-$actionStr-reason" )->plain(),
			'size' => 50,
		];
		$fields['HiddenTag'] = [
			'type' => 'hidden',
			'name' => 'tag',
			'default' => $tag,
			'required' => true,
		];

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setAction( $this->getPageTitle( $actionStr )->getLocalURL() );
		$form->tagAction = $actionStr;
		$form->setSubmitCallback( [ $this, 'processTagForm' ] );
		// tags-activate-submit, tags-deactivate-submit
		$form->setSubmitTextMsg( "tags-$actionStr-submit" );
		$form->addPreText( $preText );
		$form->show();
	}

	public function processTagForm( array $data, HTMLForm $form ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = $data['HiddenTag'];
		$changeTagsManager = new ChangeTagsManager(
			$this->changeTagsContext,
			$context->getUser(),
			true
		);
		$status = call_user_func( [ $changeTagsManager, "{$form->tagAction}TagWithChecks" ],
			$tag, $data['Reason'] );

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

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		// The subpages does not have an own form, so not listing it at the moment
		return [
			// 'delete',
			// 'activate',
			// 'deactivate',
			// 'create',
		];
	}

	protected function getGroupName() {
		return 'changes';
	}
}
