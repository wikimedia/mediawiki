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
 */

namespace MediaWiki\Specials;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * A special page that lists tags for edits
 *
 * @ingroup SpecialPage
 */
class SpecialTags extends SpecialPage {

	/**
	 * @var array List of explicitly defined tags
	 */
	protected $explicitlyDefinedTags;

	/**
	 * @var array List of software defined tags
	 */
	protected $softwareDefinedTags;

	/**
	 * @var array List of software activated tags
	 */
	protected $softwareActivatedTags;
	private ChangeTagsStore $changeTagsStore;

	public function __construct( ChangeTagsStore $changeTagsStore ) {
		parent::__construct( 'Tags' );
		$this->changeTagsStore = $changeTagsStore;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Manual:Tags' );
		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );

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

	private function showTagList() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'tags-title' ) );
		$out->wrapWikiMsg( "<div class='mw-tags-intro'>\n$1\n</div>", 'tags-intro' );

		$authority = $this->getAuthority();
		$userCanManage = $authority->isAllowed( 'managechangetags' );
		$userCanDelete = $authority->isAllowed( 'deletechangetags' );
		$userCanEditInterface = $authority->isAllowed( 'editinterface' );

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
					'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
					'label' => $this->msg( 'tags-create-reason' )->plain(),
					'size' => 50,
				],
				'IgnoreWarnings' => [
					'type' => 'hidden',
				],
			];

			HTMLForm::factory( 'ooui', $fields, $this->getContext() )
				->setAction( $this->getPageTitle( 'create' )->getLocalURL() )
				->setWrapperLegendMsg( 'tags-create-heading' )
				->setHeaderHtml( $this->msg( 'tags-create-explanation' )->parseAsBlock() )
				->setSubmitCallback( $this->processCreateTagForm( ... ) )
				->setSubmitTextMsg( 'tags-create-submit' )
				->show();

			// If processCreateTagForm generated a redirect, there's no point
			// continuing with this, as the user is just going to end up getting sent
			// somewhere else. Additionally, if we keep going here, we end up
			// populating the memcache of tag data (see ChangeTagsStore->listDefinedTags)
			// with out-of-date data from the replica DB, because the replica DB hasn't caught
			// up to the fact that a new tag has been created as part of an implicit,
			// as yet uncommitted transaction on primary DB.
			if ( $out->getRedirect() !== '' ) {
				return;
			}
		}

		// Used to get hitcounts for #doTagRow()
		$tagStats = $this->changeTagsStore->tagUsageStatistics();

		// Used in #doTagRow()
		$this->explicitlyDefinedTags = array_fill_keys(
			$this->changeTagsStore->listExplicitlyDefinedTags(), true );
		$this->softwareDefinedTags = array_fill_keys(
			$this->changeTagsStore->listSoftwareDefinedTags(), true );

		// List all defined tags, even if they were never applied
		$definedTags = array_keys( $this->explicitlyDefinedTags + $this->softwareDefinedTags );

		// Show header only if there exists at least one tag
		if ( !$tagStats && !$definedTags ) {
			return;
		}

		// Write the headers
		$thead = Html::rawElement( 'tr', [], Html::rawElement( 'th', [], $this->msg( 'tags-tag' )->parse() ) .
			Html::rawElement( 'th', [], $this->msg( 'tags-display-header' )->parse() ) .
			Html::rawElement( 'th', [], $this->msg( 'tags-description-header' )->parse() ) .
			Html::rawElement( 'th', [], $this->msg( 'tags-source-header' )->parse() ) .
			Html::rawElement( 'th', [], $this->msg( 'tags-active-header' )->parse() ) .
			Html::rawElement( 'th', [], $this->msg( 'tags-hitcount-header' )->parse() ) .
			( ( $userCanManage || $userCanDelete ) ?
				Html::rawElement( 'th', [ 'class' => 'unsortable' ],
					$this->msg( 'tags-actions-header' )->parse() ) :
				'' )
		);

		$tbody = '';
		// Used in #doTagRow()
		$this->softwareActivatedTags = array_fill_keys(
			$this->changeTagsStore->listSoftwareActivatedTags(), true );

		// Insert tags that have been applied at least once
		foreach ( $tagStats as $tag => $hitcount ) {
			$tbody .= $this->doTagRow( $tag, $hitcount, $userCanManage,
				$userCanDelete, $userCanEditInterface );
		}
		// Insert tags defined somewhere but never applied
		foreach ( $definedTags as $tag ) {
			if ( !isset( $tagStats[$tag] ) ) {
				$tbody .= $this->doTagRow( $tag, 0, $userCanManage, $userCanDelete, $userCanEditInterface );
			}
		}

		$out->addModuleStyles( [
			'jquery.tablesorter.styles',
			'mediawiki.pager.styles'
		] );
		$out->addModules( 'jquery.tablesorter' );
		$out->addHTML( Html::rawElement(
			'table',
			[ 'class' => 'mw-datatable sortable mw-tags-table' ],
			Html::rawElement( 'thead', [], $thead ) .
				Html::rawElement( 'tbody', [], $tbody )
		) );
	}

	private function doTagRow(
		string $tag, int $hitcount, bool $showManageActions, bool $showDeleteActions, bool $showEditLinks
	): string {
		$newRow = '';
		$newRow .= Html::rawElement( 'td', [], Html::element( 'code', [], $tag ) );

		$linkRenderer = $this->getLinkRenderer();
		$disp = ChangeTags::tagDescription( $tag, $this->getContext() );
		if ( $disp === false ) {
			$disp = Html::element( 'em', [], $this->msg( 'tags-hidden' )->text() );
		}
		if ( $showEditLinks ) {
			$disp .= ' ';
			$editLink = $linkRenderer->makeLink(
				$this->msg( "tag-$tag" )->getTitle(),
				$this->msg( 'tags-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$helpEditLink = $linkRenderer->makeLink(
				$this->msg( "tag-$tag-helppage" )->inContentLanguage()->getTitle(),
				$this->msg( 'tags-helppage-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$disp .= $this->msg( 'parentheses' )->rawParams(
				$this->getLanguage()->pipeList( [ $editLink, $helpEditLink ] )
			)->escaped();
		}
		$newRow .= Html::rawElement( 'td', [], $disp );

		$msg = $this->msg( "tag-$tag-description" );
		$desc = !$msg->exists() ? '' : $msg->parse();
		if ( $showEditLinks ) {
			$desc .= ' ';
			$editDescLink = $linkRenderer->makeLink(
				$this->msg( "tag-$tag-description" )->inContentLanguage()->getTitle(),
				$this->msg( 'tags-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$desc .= $this->msg( 'parentheses' )->rawParams( $editDescLink )->escaped();
		}
		$newRow .= Html::rawElement( 'td', [], $desc );

		$sourceMsgs = [];
		$isSoftware = isset( $this->softwareDefinedTags[$tag] );
		$isExplicit = isset( $this->explicitlyDefinedTags[$tag] );
		if ( $isSoftware ) {
			// TODO: Rename this message
			$sourceMsgs[] = $this->msg( 'tags-source-extension' )->escaped();
		}
		if ( $isExplicit ) {
			$sourceMsgs[] = $this->msg( 'tags-source-manual' )->escaped();
		}
		if ( !$sourceMsgs ) {
			$sourceMsgs[] = $this->msg( 'tags-source-none' )->escaped();
		}
		$newRow .= Html::rawElement( 'td', [], implode( Html::element( 'br' ), $sourceMsgs ) );

		$isActive = $isExplicit || isset( $this->softwareActivatedTags[$tag] );
		$activeMsg = ( $isActive ? 'tags-active-yes' : 'tags-active-no' );
		$newRow .= Html::rawElement( 'td', [], $this->msg( $activeMsg )->escaped() );

		$hitcountLabelMsg = $this->msg( 'tags-hitcount' )->numParams( $hitcount );
		if ( $this->getConfig()->get( MainConfigNames::UseTagFilter ) ) {
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
		$newRow .= Html::rawElement( 'td', [ 'data-sort-value' => $hitcount ], $hitcountLabel );

		$actionLinks = [];

		if ( $showDeleteActions && ChangeTags::canDeleteTag( $tag )->isOK() ) {
			$actionLinks[] = $linkRenderer->makeKnownLink(
				$this->getPageTitle( 'delete' ),
				$this->msg( 'tags-delete' )->text(),
				[],
				[ 'tag' => $tag ] );
		}

		if ( $showManageActions ) { // we've already checked that the user had the requisite userright
			if ( ChangeTags::canActivateTag( $tag )->isOK() ) {
				$actionLinks[] = $linkRenderer->makeKnownLink(
					$this->getPageTitle( 'activate' ),
					$this->msg( 'tags-activate' )->text(),
					[],
					[ 'tag' => $tag ] );
			}

			if ( ChangeTags::canDeactivateTag( $tag )->isOK() ) {
				$actionLinks[] = $linkRenderer->makeKnownLink(
					$this->getPageTitle( 'deactivate' ),
					$this->msg( 'tags-deactivate' )->text(),
					[],
					[ 'tag' => $tag ] );
			}
		}

		if ( $showDeleteActions || $showManageActions ) {
			$newRow .= Html::rawElement( 'td', [], $this->getLanguage()->pipeList( $actionLinks ) );
		}

		return Html::rawElement( 'tr', [], $newRow ) . "\n";
	}

	private function processCreateTagForm( array $data, HTMLForm $form ): bool {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = trim( strval( $data['Tag'] ) );
		$ignoreWarnings = isset( $data['IgnoreWarnings'] ) && $data['IgnoreWarnings'] === '1';
		$status = ChangeTags::createTagWithChecks( $tag, $data['Reason'],
			$context->getAuthority(), $ignoreWarnings );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} elseif ( $status->isOK() ) {
			// We have some warnings, so we adjust the form for confirmation.
			// This would override the existing field and its default value.
			$form->addFields( [
				'IgnoreWarnings' => [
					'type' => 'hidden',
					'default' => '1',
				],
			] );

			$headerText = $this->msg( 'tags-create-warnings-above', $tag,
				count( $status->getMessages( 'warning' ) ) )->parseAsBlock() .
				$out->parseAsInterface( $status->getWikiText() ) .
				$this->msg( 'tags-create-warnings-below' )->parseAsBlock();

			$form->setHeaderHtml( $headerText )
				->setSubmitTextMsg( 'htmlform-yes' );

			$out->addBacklinkSubtitle( $this->getPageTitle() );
			return false;
		} else {
			foreach ( $status->getMessages() as $msg ) {
				$out->addHTML( Html::errorBox(
					$this->msg( $msg )->parse()
				) );
			}
			return false;
		}
	}

	/**
	 * @param string $tag
	 */
	protected function showDeleteTagForm( $tag ) {
		$authority = $this->getAuthority();
		if ( !$authority->isAllowed( 'deletechangetags' ) ) {
			throw new PermissionsError( 'deletechangetags' );
		}

		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'tags-delete-title' ) );
		$out->addBacklinkSubtitle( $this->getPageTitle() );

		// is the tag actually able to be deleted?
		$canDeleteResult = ChangeTags::canDeleteTag( $tag, $authority );
		if ( !$canDeleteResult->isGood() ) {
			foreach ( $canDeleteResult->getMessages() as $msg ) {
				$out->addHTML( Html::errorBox(
					$this->msg( $msg )->parse()
				) );
			}
			if ( !$canDeleteResult->isOK() ) {
				return;
			}
		}

		$preText = $this->msg( 'tags-delete-explanation-initial', $tag )->parseAsBlock();
		$tagUsage = $this->changeTagsStore->tagUsageStatistics();
		if ( isset( $tagUsage[$tag] ) && $tagUsage[$tag] > 0 ) {
			$preText .= $this->msg( 'tags-delete-explanation-in-use', $tag,
				$tagUsage[$tag] )->parseAsBlock();
		}
		$preText .= $this->msg( 'tags-delete-explanation-warning', $tag )->parseAsBlock();

		// see if the tag is in use
		$this->softwareActivatedTags = array_fill_keys(
			$this->changeTagsStore->listSoftwareActivatedTags(), true );
		if ( isset( $this->softwareActivatedTags[$tag] ) ) {
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

		HTMLForm::factory( 'ooui', $fields, $this->getContext() )
			->setAction( $this->getPageTitle( 'delete' )->getLocalURL() )
			->setSubmitCallback( function ( $data, $form ) {
				return $this->processTagForm( $data, $form, 'delete' );
			} )
			->setSubmitTextMsg( 'tags-delete-submit' )
			->setSubmitDestructive()
			->addPreHtml( $preText )
			->show();
	}

	/**
	 * @param string $tag
	 * @param bool $activate
	 */
	protected function showActivateDeactivateForm( $tag, $activate ) {
		$actionStr = $activate ? 'activate' : 'deactivate';

		$authority = $this->getAuthority();
		if ( !$authority->isAllowed( 'managechangetags' ) ) {
			throw new PermissionsError( 'managechangetags' );
		}

		$out = $this->getOutput();
		// tags-activate-title, tags-deactivate-title
		$out->setPageTitleMsg( $this->msg( "tags-$actionStr-title" ) );
		$out->addBacklinkSubtitle( $this->getPageTitle() );

		// is it possible to do this?
		if ( $activate ) {
			$result = ChangeTags::canActivateTag( $tag, $authority );
		} else {
			$result = ChangeTags::canDeactivateTag( $tag, $authority );
		}
		if ( !$result->isGood() ) {
			foreach ( $result->getMessages() as $msg ) {
				$out->addHTML( Html::errorBox(
					$this->msg( $msg )->parse()
				) );
			}
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

		HTMLForm::factory( 'ooui', $fields, $this->getContext() )
			->setAction( $this->getPageTitle( $actionStr )->getLocalURL() )
			->setSubmitCallback( function ( $data, $form ) use ( $actionStr ) {
				return $this->processTagForm( $data, $form, $actionStr );
			} )
			// tags-activate-submit, tags-deactivate-submit
			->setSubmitTextMsg( "tags-$actionStr-submit" )
			->addPreHtml( $preText )
			->show();
	}

	/**
	 * @param array $data
	 * @param HTMLForm $form
	 * @param string $action
	 * @return bool
	 */
	public function processTagForm( array $data, HTMLForm $form, string $action ) {
		$context = $form->getContext();
		$out = $context->getOutput();

		$tag = $data['HiddenTag'];
		// activateTagWithChecks, deactivateTagWithChecks, deleteTagWithChecks
		$method = "{$action}TagWithChecks";
		$status = ChangeTags::$method(
			$tag, $data['Reason'], $context->getUser(), true );

		if ( $status->isGood() ) {
			$out->redirect( $this->getPageTitle()->getLocalURL() );
			return true;
		} elseif ( $status->isOK() && $action === 'delete' ) {
			// deletion succeeded, but hooks raised a warning
			$out->addWikiTextAsInterface( $this->msg( 'tags-delete-warnings-after-delete', $tag,
				count( $status->getMessages( 'warning' ) ) )->text() . "\n" .
				$status->getWikitext() );
			$out->addReturnTo( $this->getPageTitle() );
			return true;
		} else {
			foreach ( $status->getMessages() as $msg ) {
				$out->addHTML( Html::errorBox(
					$this->msg( $msg )->parse()
				) );
			}
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

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialTags::class, 'SpecialTags' );
