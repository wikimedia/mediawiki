<?php
/**
 * Displays information about a page.
 *
 * Copyright © 2011 Alexandre Emsenhuber
 *
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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Displays information about a page.
 *
 * @ingroup Actions
 */
class InfoAction extends FormlessAction {
	private const VERSION = 1;

	/** @var Language */
	private $contentLanguage;

	/** @var HookRunner */
	private $hookRunner;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var MagicWordFactory */
	private $magicWordFactory;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var PageProps */
	private $pageProps;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var WANObjectCache */
	private $wanObjectCache;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var RedirectLookup */
	private $redirectLookup;

	/** @var RestrictionStore */
	private $restrictionStore;

	/** @var LinksMigration */
	private $linksMigration;

	/**
	 * @param Page $page
	 * @param IContextSource $context
	 * @param Language $contentLanguage
	 * @param HookContainer $hookContainer
	 * @param LanguageNameUtils $languageNameUtils
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkRenderer $linkRenderer
	 * @param ILoadBalancer $loadBalancer
	 * @param MagicWordFactory $magicWordFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param PageProps $pageProps
	 * @param RepoGroup $repoGroup
	 * @param RevisionLookup $revisionLookup
	 * @param WANObjectCache $wanObjectCache
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param RedirectLookup $redirectLookup
	 * @param RestrictionStore $restrictionStore
	 * @param LinksMigration $linksMigration
	 */
	public function __construct(
		Page $page,
		IContextSource $context,
		Language $contentLanguage,
		HookContainer $hookContainer,
		LanguageNameUtils $languageNameUtils,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		ILoadBalancer $loadBalancer,
		MagicWordFactory $magicWordFactory,
		NamespaceInfo $namespaceInfo,
		PageProps $pageProps,
		RepoGroup $repoGroup,
		RevisionLookup $revisionLookup,
		WANObjectCache $wanObjectCache,
		WatchedItemStoreInterface $watchedItemStore,
		RedirectLookup $redirectLookup,
		RestrictionStore $restrictionStore,
		LinksMigration $linksMigration
	) {
		parent::__construct( $page, $context );
		$this->contentLanguage = $contentLanguage;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->languageNameUtils = $languageNameUtils;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linkRenderer = $linkRenderer;
		$this->loadBalancer = $loadBalancer;
		$this->magicWordFactory = $magicWordFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->pageProps = $pageProps;
		$this->repoGroup = $repoGroup;
		$this->revisionLookup = $revisionLookup;
		$this->wanObjectCache = $wanObjectCache;
		$this->watchedItemStore = $watchedItemStore;
		$this->redirectLookup = $redirectLookup;
		$this->restrictionStore = $restrictionStore;
		$this->linksMigration = $linksMigration;
	}

	/**
	 * Returns the name of the action this object responds to.
	 *
	 * @return string Lowercase name
	 */
	public function getName() {
		return 'info';
	}

	/**
	 * Whether this action can still be executed by a blocked user.
	 *
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * Whether this action requires the wiki not to be locked.
	 *
	 * @return bool
	 */
	public function requiresWrite() {
		return false;
	}

	/**
	 * Clear the info cache for a given Title.
	 *
	 * @since 1.22
	 * @param PageIdentity $page Title to clear cache for
	 * @param int|null $revid Revision id to clear
	 */
	public static function invalidateCache( PageIdentity $page, $revid = null ) {
		$services = MediaWikiServices::getInstance();
		if ( !$revid ) {
			$revision = $services->getRevisionLookup()
				->getRevisionByTitle( $page, 0, IDBAccessObject::READ_LATEST );
			$revid = $revision ? $revision->getId() : null;
		}
		if ( $revid !== null ) {
			$cache = $services->getMainWANObjectCache();
			$key = self::getCacheKey( $cache, $page, $revid );
			$cache->delete( $key );
		}
	}

	/**
	 * Shows page information on GET request.
	 *
	 * @return string Page information that will be added to the output
	 */
	public function onView() {
		$this->getOutput()->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.action.styles',
		] );

		// "Help" button
		$this->addHelpLink( 'Page information' );

		// Validate revision
		$oldid = $this->getArticle()->getOldID();
		if ( $oldid ) {
			$revRecord = $this->getArticle()->fetchRevisionRecord();

			// Revision is missing
			if ( $revRecord === null ) {
				return $this->msg( 'missing-revision', $oldid )->parse();
			}

			// Revision is not current
			if ( !$revRecord->isCurrent() ) {
				return $this->msg( 'pageinfo-not-current' )->plain();
			}
		}

		$content = '';

		// Page header
		if ( !$this->msg( 'pageinfo-header' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-header' )->parse();
		}

		// Get page information
		$pageInfo = $this->pageInfo();

		// Allow extensions to add additional information
		$this->hookRunner->onInfoAction( $this->getContext(), $pageInfo );

		// Render page information
		foreach ( $pageInfo as $header => $infoTable ) {
			// Messages:
			// pageinfo-header-basic, pageinfo-header-edits, pageinfo-header-restrictions,
			// pageinfo-header-properties, pageinfo-category-info
			$content .= $this->makeHeader(
				$this->msg( "pageinfo-$header" )->text(),
				"mw-pageinfo-$header"
			) . "\n";
			$table = "\n";
			$below = "";
			foreach ( $infoTable as $infoRow ) {
				if ( $infoRow[0] == "below" ) {
					$below = $infoRow[1] . "\n";
					continue;
				}
				$name = ( $infoRow[0] instanceof Message ) ? $infoRow[0]->escaped() : $infoRow[0];
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$value = ( $infoRow[1] instanceof Message ) ? $infoRow[1]->escaped() : $infoRow[1];
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$id = ( $infoRow[0] instanceof Message ) ? $infoRow[0]->getKey() : null;
				$table = $this->addRow( $table, $name, $value, $id ) . "\n";
			}
			if ( $table === "\n" ) {
				// Don't add tables with no rows
				$content .= "\n" . $below;
			} else {
				$content = $this->addTable( $content, $table ) . "\n" . $below;
			}
		}

		// Page footer
		if ( !$this->msg( 'pageinfo-footer' )->isDisabled() ) {
			$content .= $this->msg( 'pageinfo-footer' )->parse();
		}

		return $content;
	}

	/**
	 * Creates a header that can be added to the output.
	 *
	 * @param string $header The header text.
	 * @param string $canonicalId
	 * @return string The HTML.
	 */
	protected function makeHeader( $header, $canonicalId ) {
		return Html::rawElement(
			'h2',
			[ 'id' => Sanitizer::escapeIdForAttribute( $canonicalId ) ],
			Html::element(
				'span',
				[
					'class' => 'mw-headline',
					'id' => Sanitizer::escapeIdForAttribute( $header ),
				],
				$header
			)
		);
	}

	/**
	 * Adds a row to a table that will be added to the content.
	 *
	 * @param string $table The table that will be added to the content
	 * @param string $name The name of the row
	 * @param string $value The value of the row
	 * @param string|null $id The ID to use for the 'tr' element
	 * @return string The table with the row added
	 */
	protected function addRow( $table, $name, $value, $id ) {
		return $table .
			Html::rawElement(
				'tr',
				$id === null ? [] : [ 'id' => 'mw-' . $id ],
				Html::rawElement( 'td', [ 'style' => 'vertical-align: top;' ], $name ) .
					Html::rawElement( 'td', [], $value )
			);
	}

	/**
	 * Adds a table to the content that will be added to the output.
	 *
	 * @param string $content The content that will be added to the output
	 * @param string $table
	 * @return string The content with the table added
	 */
	protected function addTable( $content, $table ) {
		return $content .
			Html::rawElement(
				'table',
				[ 'class' => 'wikitable mw-page-info' ],
				$table
			);
	}

	/**
	 * Returns an array of info groups (will be rendered as tables), keyed by group ID.
	 * Group IDs are arbitrary and used so that extensions may add additional information in
	 * arbitrary positions (and as message keys for section headers for the tables, prefixed
	 * with 'pageinfo-').
	 * Each info group is a non-associative array of info items (rendered as table rows).
	 * Each info item is an array with two elements: the first describes the type of
	 * information, the second the value for the current page. Both can be strings (will be
	 * interpreted as raw HTML) or messages (will be interpreted as plain text and escaped).
	 *
	 * @return array
	 */
	protected function pageInfo() {
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$title = $this->getTitle();
		$id = $title->getArticleID();
		$config = $this->context->getConfig();
		$linkRenderer = $this->linkRenderer;

		$pageCounts = $this->pageCounts();

		$props = $this->pageProps->getAllProperties( $title );
		$pageProperties = $props[$id] ?? [];

		// Basic information
		$pageInfo = [];
		$pageInfo['header-basic'] = [];

		// Display title
		$displayTitle = $pageProperties['displaytitle'] ??
			htmlspecialchars( $title->getPrefixedText(), ENT_NOQUOTES );

		$pageInfo['header-basic'][] = [
			$this->msg( 'pageinfo-display-title' ),
			$displayTitle
		];

		// Is it a redirect? If so, where to?
		$redirectTarget = $this->redirectLookup->getRedirectTarget( $this->getWikiPage() );
		if ( $redirectTarget !== null ) {
			$pageInfo['header-basic'][] = [
				$this->msg( 'pageinfo-redirectsto' ),
				$linkRenderer->makeLink( $redirectTarget ) .
				$this->msg( 'word-separator' )->escaped() .
				$this->msg( 'parentheses' )->rawParams( $linkRenderer->makeLink(
					$redirectTarget,
					$this->msg( 'pageinfo-redirectsto-info' )->text(),
					[],
					[ 'action' => 'info' ]
				) )->escaped()
			];
		}

		// Default sort key
		$sortKey = $pageProperties['defaultsort'] ?? $title->getCategorySortkey();

		$sortKey = htmlspecialchars( $sortKey );
		$pageInfo['header-basic'][] = [ $this->msg( 'pageinfo-default-sort' ), $sortKey ];

		// Page length (in bytes)
		$pageInfo['header-basic'][] = [
			$this->msg( 'pageinfo-length' ),
			$lang->formatNum( $title->getLength() )
		];

		// Page namespace
		$pageNamespace = $title->getNsText();
		if ( $pageNamespace ) {
			$pageInfo['header-basic'][] = [ $this->msg( 'pageinfo-namespace' ), $pageNamespace ];
		}

		// Page ID (number not localised, as it's a database ID)
		$pageInfo['header-basic'][] = [ $this->msg( 'pageinfo-article-id' ), $id ];

		// Language in which the page content is (supposed to be) written
		$pageLang = $title->getPageLanguage()->getCode();

		$pageLangHtml = $pageLang . ' - ' .
			$this->languageNameUtils->getLanguageName( $pageLang, $lang->getCode() );
		// Link to Special:PageLanguage with pre-filled page title if user has permissions
		if ( $config->get( MainConfigNames::PageLanguageUseDB )
			&& $this->getAuthority()->probablyCan( 'pagelang', $title )
		) {
			$pageLangHtml .= ' ' . $this->msg( 'parentheses' )->rawParams( $linkRenderer->makeLink(
				SpecialPage::getTitleValueFor( 'PageLanguage', $title->getPrefixedText() ),
				$this->msg( 'pageinfo-language-change' )->text()
			) )->escaped();
		}

		$pageInfo['header-basic'][] = [
			$this->msg( 'pageinfo-language' )->escaped(),
			$pageLangHtml
		];

		// Content model of the page
		$modelHtml = htmlspecialchars( ContentHandler::getLocalizedName( $title->getContentModel() ) );
		// If the user can change it, add a link to Special:ChangeContentModel
		if ( $this->getAuthority()->probablyCan( 'editcontentmodel', $title ) ) {
			$modelHtml .= ' ' . $this->msg( 'parentheses' )->rawParams( $linkRenderer->makeLink(
				SpecialPage::getTitleValueFor( 'ChangeContentModel', $title->getPrefixedText() ),
				$this->msg( 'pageinfo-content-model-change' )->text()
			) )->escaped();
		}

		$pageInfo['header-basic'][] = [
			$this->msg( 'pageinfo-content-model' ),
			$modelHtml
		];

		if ( $title->inNamespace( NS_USER ) ) {
			$pageUser = User::newFromName( $title->getRootText() );
			if ( $pageUser && $pageUser->getId() && !$pageUser->isHidden() ) {
				$pageInfo['header-basic'][] = [
					$this->msg( 'pageinfo-user-id' ),
					$pageUser->getId()
				];
			}
		}

		// Search engine status
		$parserOutput = new ParserOutput();
		if ( isset( $pageProperties['noindex'] ) ) {
			$parserOutput->setIndexPolicy( 'noindex' );
		}
		if ( isset( $pageProperties['index'] ) ) {
			$parserOutput->setIndexPolicy( 'index' );
		}

		// Use robot policy logic
		$policy = $this->getArticle()->getRobotPolicy( 'view', $parserOutput );
		$pageInfo['header-basic'][] = [
			// Messages: pageinfo-robot-index, pageinfo-robot-noindex
			$this->msg( 'pageinfo-robot-policy' ),
			$this->msg( "pageinfo-robot-{$policy['index']}" )
		];

		$unwatchedPageThreshold = $config->get( MainConfigNames::UnwatchedPageThreshold );
		if ( $this->getAuthority()->isAllowed( 'unwatchedpages' ) ||
			( $unwatchedPageThreshold !== false &&
				$pageCounts['watchers'] >= $unwatchedPageThreshold )
		) {
			// Number of page watchers
			$pageInfo['header-basic'][] = [
				$this->msg( 'pageinfo-watchers' ),
				$lang->formatNum( $pageCounts['watchers'] )
			];
			if (
				$config->get( MainConfigNames::ShowUpdatedMarker ) &&
				isset( $pageCounts['visitingWatchers'] )
			) {
				$minToDisclose = $config->get( MainConfigNames::UnwatchedPageSecret );
				if ( $pageCounts['visitingWatchers'] > $minToDisclose ||
					$this->getAuthority()->isAllowed( 'unwatchedpages' ) ) {
					$pageInfo['header-basic'][] = [
						$this->msg( 'pageinfo-visiting-watchers' ),
						$lang->formatNum( $pageCounts['visitingWatchers'] )
					];
				} else {
					$pageInfo['header-basic'][] = [
						$this->msg( 'pageinfo-visiting-watchers' ),
						$this->msg( 'pageinfo-few-visiting-watchers' )
					];
				}
			}
		} elseif ( $unwatchedPageThreshold !== false ) {
			$pageInfo['header-basic'][] = [
				$this->msg( 'pageinfo-watchers' ),
				$this->msg( 'pageinfo-few-watchers' )->numParams( $unwatchedPageThreshold )
			];
		}

		// Redirects to this page
		$whatLinksHere = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$pageInfo['header-basic'][] = [
			$linkRenderer->makeLink(
				$whatLinksHere,
				$this->msg( 'pageinfo-redirects-name' )->text(),
				[],
				[
					'hidelinks' => 1,
					'hidetrans' => 1,
					'hideimages' => $title->getNamespace() === NS_FILE
				]
			),
			$this->msg( 'pageinfo-redirects-value' )
				->numParams( count( $title->getRedirectsHere() ) )
		];

		// Is it counted as a content page?
		if ( $this->getWikiPage()->isCountable() ) {
			$pageInfo['header-basic'][] = [
				$this->msg( 'pageinfo-contentpage' ),
				$this->msg( 'pageinfo-contentpage-yes' )
			];
		}

		// Subpages of this page, if subpages are enabled for the current NS
		if ( $this->namespaceInfo->hasSubpages( $title->getNamespace() ) ) {
			$prefixIndex = SpecialPage::getTitleFor(
				'Prefixindex',
				$title->getPrefixedText() . '/'
			);
			$pageInfo['header-basic'][] = [
				$linkRenderer->makeLink(
					$prefixIndex,
					$this->msg( 'pageinfo-subpages-name' )->text()
				),
				$this->msg( 'pageinfo-subpages-value' )
					->numParams(
						$pageCounts['subpages']['total'],
						$pageCounts['subpages']['redirects'],
						$pageCounts['subpages']['nonredirects']
					)
			];
		}

		if ( $title->inNamespace( NS_CATEGORY ) ) {
			$category = Category::newFromTitle( $title );

			$allCount = $category->getMemberCount();
			$subcatCount = $category->getSubcatCount();
			$fileCount = $category->getFileCount();
			$pageCount = $category->getPageCount( Category::COUNT_CONTENT_PAGES );

			$pageInfo['category-info'] = [
				[
					$this->msg( 'pageinfo-category-total' ),
					$lang->formatNum( $allCount )
				],
				[
					$this->msg( 'pageinfo-category-pages' ),
					$lang->formatNum( $pageCount )
				],
				[
					$this->msg( 'pageinfo-category-subcats' ),
					$lang->formatNum( $subcatCount )
				],
				[
					$this->msg( 'pageinfo-category-files' ),
					$lang->formatNum( $fileCount )
				]
			];
		}

		// Display image SHA-1 value
		if ( $title->inNamespace( NS_FILE ) ) {
			$fileObj = $this->repoGroup->findFile( $title );
			if ( $fileObj !== false ) {
				// Convert the base-36 sha1 value obtained from database to base-16
				$output = Wikimedia\base_convert( $fileObj->getSha1(), 36, 16, 40 );
				$pageInfo['header-basic'][] = [
					$this->msg( 'pageinfo-file-hash' ),
					$output
				];
			}
		}

		// Page protection
		$pageInfo['header-restrictions'] = [];

		// Is this page affected by the cascading protection of something which includes it?
		if ( $this->restrictionStore->isCascadeProtected( $title ) ) {
			$cascadingFrom = '';
			$sources = $this->restrictionStore->getCascadeProtectionSources( $title )[0];

			foreach ( $sources as $sourcePageIdentity ) {
				$cascadingFrom .= Html::rawElement(
					'li',
					[],
					$linkRenderer->makeKnownLink( $sourcePageIdentity )
				);
			}

			$cascadingFrom = Html::rawElement( 'ul', [], $cascadingFrom );
			$pageInfo['header-restrictions'][] = [
				$this->msg( 'pageinfo-protect-cascading-from' ),
				$cascadingFrom
			];
		}

		// Is out protection set to cascade to other pages?
		if ( $this->restrictionStore->areRestrictionsCascading( $title ) ) {
			$pageInfo['header-restrictions'][] = [
				$this->msg( 'pageinfo-protect-cascading' ),
				$this->msg( 'pageinfo-protect-cascading-yes' )
			];
		}

		// Page protection
		foreach ( $this->restrictionStore->listApplicableRestrictionTypes( $title ) as $restrictionType ) {
			$protections = $this->restrictionStore->getRestrictions( $title, $restrictionType );

			switch ( count( $protections ) ) {
				case 0:
					$message = $this->getNamespaceProtectionMessage( $title );
					if ( $message === null ) {
						// Allow all users
						$message = $this->msg( 'protect-default' )->escaped();
					}
					break;

				case 1:
					// Messages: protect-level-autoconfirmed, protect-level-sysop
					$message = $this->msg( 'protect-level-' . $protections[0] );
					if ( !$message->isDisabled() ) {
						$message = $message->escaped();
						break;
					}
					// Intentional fall-through if message is disabled (or non-existent)

				default:
					// Require "$1" permission
					$message = $this->msg( "protect-fallback", $lang->commaList( $protections ) )->parse();
					break;
			}
			$expiry = $this->restrictionStore->getRestrictionExpiry( $title, $restrictionType );
			$formattedexpiry = $expiry === null ? '' : $this->msg(
				'parentheses',
				$lang->formatExpiry( $expiry, true, 'infinity', $user )
			)->escaped();
			$message .= $this->msg( 'word-separator' )->escaped() . $formattedexpiry;

			// Messages: restriction-edit, restriction-move, restriction-create,
			// restriction-upload
			$pageInfo['header-restrictions'][] = [
				$this->msg( "restriction-$restrictionType" ), $message
			];
		}
		$protectLog = SpecialPage::getTitleFor( 'Log' );
		$pageInfo['header-restrictions'][] = [
			'below',
			$linkRenderer->makeKnownLink(
				$protectLog,
				$this->msg( 'pageinfo-view-protect-log' )->text(),
				[],
				[ 'type' => 'protect', 'page' => $title->getPrefixedText() ]
			),
		];

		if ( !$this->getWikiPage()->exists() ) {
			return $pageInfo;
		}

		// Edit history
		$pageInfo['header-edits'] = [];

		$firstRev = $this->revisionLookup->getFirstRevision( $this->getTitle() );
		$lastRev = $this->getWikiPage()->getRevisionRecord();
		$batch = $this->linkBatchFactory->newLinkBatch();
		if ( $firstRev ) {
			$firstRevUser = $firstRev->getUser( RevisionRecord::FOR_THIS_USER, $user );
			if ( $firstRevUser ) {
				$batch->add( NS_USER, $firstRevUser->getName() );
				$batch->add( NS_USER_TALK, $firstRevUser->getName() );
			}
		}

		if ( $lastRev ) {
			$lastRevUser = $lastRev->getUser( RevisionRecord::FOR_THIS_USER, $user );
			if ( $lastRevUser ) {
				$batch->add( NS_USER, $lastRevUser->getName() );
				$batch->add( NS_USER_TALK, $lastRevUser->getName() );
			}
		}

		$batch->execute();

		if ( $firstRev ) {
			// Page creator
			$pageInfo['header-edits'][] = [
				$this->msg( 'pageinfo-firstuser' ),
				Linker::revUserTools( $firstRev )
			];

			// Date of page creation
			$pageInfo['header-edits'][] = [
				$this->msg( 'pageinfo-firsttime' ),
				$linkRenderer->makeKnownLink(
					$title,
					$lang->userTimeAndDate( $firstRev->getTimestamp(), $user ),
					[],
					[ 'oldid' => $firstRev->getId() ]
				)
			];
		}

		if ( $lastRev ) {
			// Latest editor
			$pageInfo['header-edits'][] = [
				$this->msg( 'pageinfo-lastuser' ),
				Linker::revUserTools( $lastRev )
			];

			// Date of latest edit
			$pageInfo['header-edits'][] = [
				$this->msg( 'pageinfo-lasttime' ),
				$linkRenderer->makeKnownLink(
					$title,
					$lang->userTimeAndDate( $this->getWikiPage()->getTimestamp(), $user ),
					[],
					[ 'oldid' => $this->getWikiPage()->getLatest() ]
				)
			];
		}

		// Total number of edits
		$pageInfo['header-edits'][] = [
			$this->msg( 'pageinfo-edits' ),
			$lang->formatNum( $pageCounts['edits'] )
		];

		// Total number of distinct authors
		if ( $pageCounts['authors'] > 0 ) {
			$pageInfo['header-edits'][] = [
				$this->msg( 'pageinfo-authors' ),
				$lang->formatNum( $pageCounts['authors'] )
			];
		}

		// Recent number of edits (within past 30 days)
		$pageInfo['header-edits'][] = [
			$this->msg(
				'pageinfo-recent-edits',
				$lang->formatDuration( $config->get( MainConfigNames::RCMaxAge ) )
			),
			$lang->formatNum( $pageCounts['recent_edits'] )
		];

		// Recent number of distinct authors
		$pageInfo['header-edits'][] = [
			$this->msg( 'pageinfo-recent-authors' ),
			$lang->formatNum( $pageCounts['recent_authors'] )
		];

		// Array of MagicWord objects
		$magicWords = $this->magicWordFactory->getDoubleUnderscoreArray();

		// Array of magic word IDs
		$wordIDs = $magicWords->names;

		// Array of IDs => localized magic words
		$localizedWords = $this->contentLanguage->getMagicWords();

		$listItems = [];
		foreach ( $pageProperties as $property => $value ) {
			if ( in_array( $property, $wordIDs ) ) {
				$listItems[] = Html::element( 'li', [], $localizedWords[$property][1] );
			}
		}

		$localizedList = Html::rawElement( 'ul', [], implode( '', $listItems ) );
		$hiddenCategories = $this->getWikiPage()->getHiddenCategories();

		if (
			count( $listItems ) > 0 ||
			count( $hiddenCategories ) > 0 ||
			$pageCounts['transclusion']['from'] > 0 ||
			$pageCounts['transclusion']['to'] > 0
		) {
			$options = [ 'LIMIT' => $config->get( MainConfigNames::PageInfoTransclusionLimit ) ];
			$transcludedTemplates = $title->getTemplateLinksFrom( $options );
			if ( $config->get( MainConfigNames::MiserMode ) ) {
				$transcludedTargets = [];
			} else {
				$transcludedTargets = $title->getTemplateLinksTo( $options );
			}

			// Page properties
			$pageInfo['header-properties'] = [];

			// Magic words
			if ( count( $listItems ) > 0 ) {
				$pageInfo['header-properties'][] = [
					$this->msg( 'pageinfo-magic-words' )->numParams( count( $listItems ) ),
					$localizedList
				];
			}

			// Hidden categories
			if ( count( $hiddenCategories ) > 0 ) {
				$pageInfo['header-properties'][] = [
					$this->msg( 'pageinfo-hidden-categories' )
						->numParams( count( $hiddenCategories ) ),
					Linker::formatHiddenCategories( $hiddenCategories )
				];
			}

			// Transcluded templates
			if ( $pageCounts['transclusion']['from'] > 0 ) {
				if ( $pageCounts['transclusion']['from'] > count( $transcludedTemplates ) ) {
					$more = $this->msg( 'morenotlisted' )->escaped();
				} else {
					$more = null;
				}

				$templateListFormatter = new TemplatesOnThisPageFormatter(
					$this->getContext(),
					$linkRenderer,
					$this->linkBatchFactory,
					$this->restrictionStore
				);

				$pageInfo['header-properties'][] = [
					$this->msg( 'pageinfo-templates' )
						->numParams( $pageCounts['transclusion']['from'] ),
					$templateListFormatter->format( $transcludedTemplates, false, $more )
				];
			}

			if ( !$config->get( MainConfigNames::MiserMode ) && $pageCounts['transclusion']['to'] > 0 ) {
				if ( $pageCounts['transclusion']['to'] > count( $transcludedTargets ) ) {
					$more = $linkRenderer->makeLink(
						$whatLinksHere,
						$this->msg( 'moredotdotdot' )->text(),
						[],
						[ 'hidelinks' => 1, 'hideredirs' => 1 ]
					);
				} else {
					$more = null;
				}

				$templateListFormatter = new TemplatesOnThisPageFormatter(
					$this->getContext(),
					$linkRenderer,
					$this->linkBatchFactory,
					$this->restrictionStore
				);

				$pageInfo['header-properties'][] = [
					$this->msg( 'pageinfo-transclusions' )
						->numParams( $pageCounts['transclusion']['to'] ),
					$templateListFormatter->format( $transcludedTargets, false, $more )
				];
			}
		}

		return $pageInfo;
	}

	/**
	 * Get namespace protection message for title or null if no namespace protection
	 * has been applied
	 *
	 * @param Title $title
	 * @return ?string HTML
	 */
	protected function getNamespaceProtectionMessage( Title $title ): ?string {
		$rights = [];
		if ( $title->isRawHtmlMessage() ) {
			$rights[] = 'editsitecss';
			$rights[] = 'editsitejs';
		} elseif ( $title->isSiteCssConfigPage() ) {
			$rights[] = 'editsitecss';
		} elseif ( $title->isSiteJsConfigPage() ) {
			$rights[] = 'editsitejs';
		} elseif ( $title->isSiteJsonConfigPage() ) {
			$rights[] = 'editsitejson';
		} elseif ( $title->isUserCssConfigPage() ) {
			$rights[] = 'editusercss';
		} elseif ( $title->isUserJsConfigPage() ) {
			$rights[] = 'edituserjs';
		} elseif ( $title->isUserJsonConfigPage() ) {
			$rights[] = 'edituserjson';
		} else {
			$namespaceProtection = $this->context->getConfig()->get( MainConfigNames::NamespaceProtection );
			$right = $namespaceProtection[$title->getNamespace()] ?? null;
			if ( $right ) {
				// a single string as the value is allowed as well as an array
				$rights = (array)$right;
			}
		}
		if ( $rights ) {
			return $this->msg( 'protect-fallback', $this->getLanguage()->commaList( $rights ) )->parse();
		} else {
			return null;
		}
	}

	/**
	 * Returns page counts that would be too "expensive" to retrieve by normal means.
	 *
	 * @return array
	 */
	private function pageCounts() {
		$page = $this->getWikiPage();
		$fname = __METHOD__;
		$config = $this->context->getConfig();
		$cache = $this->wanObjectCache;

		return $cache->getWithSetCallback(
			self::getCacheKey( $cache, $page->getTitle(), $page->getLatest() ),
			WANObjectCache::TTL_WEEK,
			function ( $oldValue, &$ttl, &$setOpts ) use ( $page, $config, $fname ) {
				$title = $page->getTitle();
				$id = $title->getArticleID();

				$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );

				$field = 'rev_actor';
				$pageField = 'rev_page';

				$watchedItemStore = $this->watchedItemStore;

				$result = [];
				$result['watchers'] = $watchedItemStore->countWatchers( $title );

				if ( $config->get( MainConfigNames::ShowUpdatedMarker ) ) {
					$updated = (int)wfTimestamp( TS_UNIX, $page->getTimestamp() );
					$result['visitingWatchers'] = $watchedItemStore->countVisitingWatchers(
						$title,
						$updated - $config->get( MainConfigNames::WatchersMaxAge )
					);
				}

				// Total number of edits
				$edits = (int)$dbr->newSelectQueryBuilder()
					->select( 'COUNT(*)' )
					->from( 'revision' )
					->where( [ 'rev_page' => $id ] )
					->caller( $fname )
					->fetchField();
				$result['edits'] = $edits;

				// Total number of distinct authors
				if ( $config->get( MainConfigNames::MiserMode ) ) {
					$result['authors'] = 0;
				} else {
					$result['authors'] = (int)$dbr->newSelectQueryBuilder()
						->select( "COUNT(DISTINCT $field)" )
						->from( 'revision' )
						->where( [ $pageField => $id ] )
						->caller( $fname )
						->fetchField();
				}

				// "Recent" threshold defined by RCMaxAge setting
				$threshold = $dbr->timestamp( time() - $config->get( MainConfigNames::RCMaxAge ) );

				// Recent number of edits
				$edits = (int)$dbr->newSelectQueryBuilder()
					->select( 'COUNT(rev_page)' )
					->from( 'revision' )
					->where( [ 'rev_page' => $id ] )
					->andWhere( [ "rev_timestamp >= " . $dbr->addQuotes( $threshold ) ] )
					->caller( $fname )
					->fetchField();
				$result['recent_edits'] = $edits;

				// Recent number of distinct authors
				$result['recent_authors'] = (int)$dbr->newSelectQueryBuilder()
					->select( "COUNT(DISTINCT $field)" )
					->from( 'revision' )
					->where( [ $pageField => $id ] )
					->andWhere( [ 'rev_timestamp >= ' . $dbr->addQuotes( $threshold ) ] )
					->caller( $fname )
					->fetchField();

				// Subpages (if enabled)
				if ( $this->namespaceInfo->hasSubpages( $title->getNamespace() ) ) {
					$conds = [ 'page_namespace' => $title->getNamespace() ];
					$conds[] = 'page_title ' .
						$dbr->buildLike( $title->getDBkey() . '/', $dbr->anyString() );

					// Subpages of this page (redirects)
					$conds['page_is_redirect'] = 1;
					$result['subpages']['redirects'] = (int)$dbr->newSelectQueryBuilder()
						->select( 'COUNT(page_id)' )
						->from( 'page' )
						->where( $conds )
						->caller( $fname )
						->fetchField();
					// Subpages of this page (non-redirects)
					$conds['page_is_redirect'] = 0;
					$result['subpages']['nonredirects'] = (int)$dbr->newSelectQueryBuilder()
						->select( 'COUNT(page_id)' )
						->from( 'page' )
						->where( $conds )
						->caller( $fname )
						->fetchField();

					// Subpages of this page (total)
					$result['subpages']['total'] = $result['subpages']['redirects']
						+ $result['subpages']['nonredirects'];
				}

				// Counts for the number of transclusion links (to/from)
				if ( $config->get( MainConfigNames::MiserMode ) ) {
					$result['transclusion']['to'] = 0;
				} else {
					$result['transclusion']['to'] = (int)$dbr->newSelectQueryBuilder()
						->select( 'COUNT(tl_from)' )
						->from( 'templatelinks' )
						->where( $this->linksMigration->getLinksConditions( 'templatelinks', $title ) )
						->caller( $fname )
						->fetchField();
				}

				$result['transclusion']['from'] = (int)$dbr->newSelectQueryBuilder()
					->select( 'COUNT(*)' )
					->from( 'templatelinks' )
					->where( [ 'tl_from' => $title->getArticleID() ] )
					->caller( $fname )
					->fetchField();

				return $result;
			}
		);
	}

	/**
	 * Returns the name that goes in the "<h1>" page title.
	 *
	 * @return string
	 */
	protected function getPageTitle() {
		return $this->msg( 'pageinfo-title', $this->getTitle()->getPrefixedText() )->text();
	}

	/**
	 * Returns the description that goes below the "<h1>" tag.
	 *
	 * @return string
	 */
	protected function getDescription() {
		return '';
	}

	/**
	 * @param WANObjectCache $cache
	 * @param PageIdentity $page
	 * @param int $revId
	 * @return string
	 */
	protected static function getCacheKey( WANObjectCache $cache, PageIdentity $page, $revId ) {
		return $cache->makeKey( 'infoaction', md5( (string)$page ), $revId, self::VERSION );
	}
}
