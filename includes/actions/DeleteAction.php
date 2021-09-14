<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * Handle page deletion
 *
 * @ingroup Actions
 */
class DeleteAction extends FormlessAction {

	/** @var WatchlistManager */
	private $watchlistManager;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var BacklinkCacheFactory */
	private $backlinkCacheFactory;

	/**
	 * @inheritDoc
	 */
	public function __construct( Page $page, IContextSource $context = null ) {
		parent::__construct( $page, $context );
		$services = MediaWikiServices::getInstance();
		$this->watchlistManager = $services->getWatchlistManager();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->backlinkCacheFactory = $services->getBacklinkCacheFactory();
	}

	public function getName() {
		return 'delete';
	}

	public function onView() {
		return null;
	}

	public function show() {
		$this->useTransactionalTimeLimit();
		$this->addHelpLink( 'Help:Sysop deleting and undeleting' );
		if ( $this->getArticle() instanceof ImagePage ) {
			$this->tempDeleteFile();
		} else {
			$this->tempDeleteArticle();
		}
	}

	private function tempDeleteArticle() {
		$article = $this->getArticle();
		$title = $this->getTitle();
		$context = $this->getContext();
		$user = $context->getUser();
		$request = $context->getRequest();

		# Check permissions
		$permissionStatus = PermissionStatus::newEmpty();
		if ( !$context->getAuthority()->authorizeWrite( 'delete', $title, $permissionStatus ) ) {
			throw new PermissionsError( 'delete', $permissionStatus );
		}

		# Read-only check...
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		# Better double-check that it hasn't been deleted yet!
		$article->getPage()->loadPageData(
			$request->wasPosted() ? WikiPage::READ_LATEST : WikiPage::READ_NORMAL
		);
		if ( !$article->getPage()->exists() ) {
			$deleteLogPage = new LogPage( 'delete' );
			$outputPage = $context->getOutput();
			$outputPage->setPageTitle( $context->msg( 'cannotdelete-title', $title->getPrefixedText() ) );
			$outputPage->wrapWikiMsg( "<div class=\"error mw-error-cannotdelete\">\n$1\n</div>",
				[ 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) ]
			);
			$outputPage->addHTML(
				Xml::element( 'h2', null, $deleteLogPage->getName()->text() )
			);
			LogEventsList::showLogExtract(
				$outputPage,
				'delete',
				$title
			);

			return;
		}

		$deleteReasonList = $request->getText( 'wpDeleteReasonList', 'other' );
		$deleteReason = $request->getText( 'wpReason' );

		if ( $deleteReasonList == 'other' ) {
			$reason = $deleteReason;
		} elseif ( $deleteReason != '' ) {
			// Entry from drop down menu + additional comment
			$colonseparator = wfMessage( 'colon-separator' )->inContentLanguage()->text();
			$reason = $deleteReasonList . $colonseparator . $deleteReason;
		} else {
			$reason = $deleteReasonList;
		}

		if ( $request->wasPosted() && $user->matchEditToken( $request->getVal( 'wpEditToken' ),
				[ 'delete', $this->getTitle()->getPrefixedText() ] )
		) {
			# Flag to hide all contents of the archived revisions

			$suppress = $request->getCheck( 'wpSuppress' ) &&
				$context->getAuthority()->isAllowed( 'suppressrevision' );

			$article->doDelete( $reason, $suppress );

			$this->watchlistManager->setWatch( $request->getCheck( 'wpWatch' ), $context->getAuthority(), $title );

			return;
		}

		// Generate deletion reason
		$hasHistory = false;
		if ( !$reason ) {
			try {
				$reason = $article->getPage()
					->getAutoDeleteReason( $hasHistory );
			} catch ( Exception $e ) {
				# if a page is horribly broken, we still want to be able to
				# delete it. So be lenient about errors here.
				wfDebug( "Error while building auto delete summary: $e" );
				$reason = '';
			}
		}

		// If the page has a history, insert a warning
		if ( $hasHistory ) {
			$title = $this->getTitle();

			// The following can use the real revision count as this is only being shown for users
			// that can delete this page.
			// This, as a side-effect, also makes sure that the following query isn't being run for
			// pages with a larger history, unless the user has the 'bigdelete' right
			// (and is about to delete this page).
			$dbr = wfGetDB( DB_REPLICA );
			$revisions = $edits = (int)$dbr->selectField(
				'revision',
				'COUNT(rev_page)',
				[ 'rev_page' => $title->getArticleID() ],
				__METHOD__
			);

			// @todo i18n issue/patchwork message
			$context->getOutput()->addHTML(
				'<strong class="mw-delete-warning-revisions">' .
				$context->msg( 'historywarning' )->numParams( $revisions )->parse() .
				$context->msg( 'word-separator' )->escaped() . $this->linkRenderer->makeKnownLink(
					$title,
					$context->msg( 'history' )->text(),
					[],
					[ 'action' => 'history' ] ) .
				'</strong>'
			);

			if ( $title->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$context->getOutput()->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					[
						'delete-warning-toobig',
						$context->getLanguage()->formatNum( $wgDeleteRevisionsLimit )
					]
				);
			}
		}

		$this->tempConfirmDelete( $reason );
	}

	private function tempDeleteFile() {
		$file = $this->getArticle()->getFile();
		if ( !$file->exists() || !$file->isLocal() || $file->getRedirected() ) {
			// Standard article deletion
			$this->tempDeleteArticle();
			return;
		}
		'@phan-var LocalFile $file';

		$context = $this->getContext();
		$services = MediaWikiServices::getInstance();
		$deleter = new FileDeleteForm(
			$file,
			$context,
			$services->getReadOnlyMode(),
			$services->getRepoGroup(),
			$services->getWatchlistManager(),
			$this->linkRenderer,
			$services->getUserOptionsLookup()
		);
		$deleter->execute();
	}

	/**
	 * @param string $reason
	 */
	private function tempConfirmDelete( string $reason ): void {
		wfDebug( "Article::confirmDelete" );

		$title = $this->getTitle();
		$ctx = $this->getContext();
		$outputPage = $ctx->getOutput();
		$outputPage->setPageTitle( wfMessage( 'delete-confirm', $title->getPrefixedText() ) );
		$outputPage->addBacklinkSubtitle( $title );
		$outputPage->setRobotPolicy( 'noindex,nofollow' );
		$outputPage->addModules( 'mediawiki.action.delete' );
		$outputPage->addModuleStyles( 'mediawiki.action.styles' );

		$backlinkCache = $this->backlinkCacheFactory->getBacklinkCache( $title );
		if ( $backlinkCache->hasLinks( 'pagelinks' ) || $backlinkCache->hasLinks( 'templatelinks' ) ) {
			$outputPage->addHtml(
				Html::warningBox(
					$outputPage->msg( 'deleting-backlinks-warning' )->parse(),
					'plainlinks'
				)
			);
		}

		$subpageQueryLimit = 51;
		$subpages = $title->getSubpages( $subpageQueryLimit );
		$subpageCount = count( $subpages );
		if ( $subpageCount > 0 ) {
			$outputPage->addHtml(
				Html::warningBox(
					$outputPage->msg( 'deleting-subpages-warning', Message::numParam( $subpageCount ) )->parse(),
					'plainlinks'
				)
			);
		}
		$outputPage->addWikiMsg( 'confirmdeletetext' );

		$this->getHookRunner()->onArticleConfirmDelete( $this->getArticle(), $outputPage, $reason );

		$user = $this->getContext()->getUser();
		$services = MediaWikiServices::getInstance();
		$checkWatch = $services->getUserOptionsLookup()->getBoolOption( $user, 'watchdeletion' ) ||
			$this->watchlistManager->isWatched( $user, $title );

		$outputPage->enableOOUI();

		$fields = [];

		$suppressAllowed = $this->getContext()->getAuthority()->isAllowed( 'suppressrevision' );
		$dropDownReason = $ctx->msg( 'deletereason-dropdown' )->inContentLanguage()->text();
		// Add additional specific reasons for suppress
		if ( $suppressAllowed ) {
			$dropDownReason .= "\n" . $ctx->msg( 'deletereason-dropdown-suppress' )
					->inContentLanguage()->text();
		}

		$options = Xml::listDropDownOptions(
			$dropDownReason,
			[ 'other' => $ctx->msg( 'deletereasonotherlist' )->inContentLanguage()->text() ]
		);
		$options = Xml::listDropDownOptionsOoui( $options );

		$fields[] = new OOUI\FieldLayout(
			new OOUI\DropdownInputWidget( [
				'name' => 'wpDeleteReasonList',
				'inputId' => 'wpDeleteReasonList',
				'tabIndex' => 1,
				'infusable' => true,
				'value' => '',
				'options' => $options
			] ),
			[
				'label' => $ctx->msg( 'deletecomment' )->text(),
				'align' => 'top',
			]
		);

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpReason',
				'inputId' => 'wpReason',
				'tabIndex' => 2,
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $reason,
				'autofocus' => true,
			] ),
			[
				'label' => $ctx->msg( 'deleteotherreason' )->text(),
				'align' => 'top',
			]
		);

		if ( $user->isRegistered() ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'inputId' => 'wpWatch',
					'tabIndex' => 3,
					'selected' => $checkWatch,
				] ),
				[
					'label' => $ctx->msg( 'watchthis' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}
		if ( $suppressAllowed ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpSuppress',
					'inputId' => 'wpSuppress',
					'tabIndex' => 4,
				] ),
				[
					'label' => $ctx->msg( 'revdelete-suppress' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		$fields[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( [
				'name' => 'wpConfirmB',
				'inputId' => 'wpConfirmB',
				'tabIndex' => 5,
				'value' => $ctx->msg( 'deletepage' )->text(),
				'label' => $ctx->msg( 'deletepage' )->text(),
				'flags' => [ 'primary', 'destructive' ],
				'type' => 'submit',
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new OOUI\FieldsetLayout( [
			'label' => $ctx->msg( 'delete-legend' )->text(),
			'id' => 'mw-delete-table',
			'items' => $fields,
		] );

		$form = new OOUI\FormLayout( [
			'method' => 'post',
			'action' => $title->getLocalURL( 'action=delete' ),
			'id' => 'deleteconfirm',
		] );
		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				Html::hidden( 'wpEditToken', $user->getEditToken( [ 'delete', $title->getPrefixedText() ] ) )
			)
		);

		$outputPage->addHTML(
			new OOUI\PanelLayout( [
				'classes' => [ 'deletepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		if ( $this->getContext()->getAuthority()->isAllowed( 'editinterface' ) ) {
			$link = '';
			if ( $suppressAllowed ) {
				$link .= $this->linkRenderer->makeKnownLink(
					$ctx->msg( 'deletereason-dropdown-suppress' )->inContentLanguage()->getTitle(),
					$ctx->msg( 'delete-edit-reasonlist-suppress' )->text(),
					[],
					[ 'action' => 'edit' ]
				);
				$link .= $ctx->msg( 'pipe-separator' )->escaped();
			}
			$link .= $this->linkRenderer->makeKnownLink(
				$ctx->msg( 'deletereason-dropdown' )->inContentLanguage()->getTitle(),
				$ctx->msg( 'delete-edit-reasonlist' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$outputPage->addHTML( '<p class="mw-delete-editreasons">' . $link . '</p>' );
		}

		$deleteLogPage = new LogPage( 'delete' );
		$outputPage->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $outputPage, 'delete', $title );
	}

	public function doesWrites() {
		return true;
	}
}
