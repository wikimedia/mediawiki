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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Feed\ChannelFeed;
use MediaWiki\Feed\FeedItem;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\NewPagesPager;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * List of newly created pages
 *
 * @see SpecialRecentChanges
 * @see SpecialNewFiles
 * @ingroup SpecialPage
 */
class SpecialNewPages extends IncludableSpecialPage {
	/**
	 * @var FormOptions
	 */
	protected $opts;
	/** @var array[] */
	protected $customFilters;

	/** @var bool */
	protected $showNavigation = false;

	private LinkBatchFactory $linkBatchFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private GroupPermissionsLookup $groupPermissionsLookup;
	private RevisionLookup $revisionLookup;
	private NamespaceInfo $namespaceInfo;
	private UserOptionsLookup $userOptionsLookup;
	private RowCommentFormatter $rowCommentFormatter;
	private ChangeTagsStore $changeTagsStore;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IContentHandlerFactory $contentHandlerFactory,
		GroupPermissionsLookup $groupPermissionsLookup,
		RevisionLookup $revisionLookup,
		NamespaceInfo $namespaceInfo,
		UserOptionsLookup $userOptionsLookup,
		RowCommentFormatter $rowCommentFormatter,
		ChangeTagsStore $changeTagsStore,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct( 'Newpages' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->revisionLookup = $revisionLookup;
		$this->namespaceInfo = $namespaceInfo;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->changeTagsStore = $changeTagsStore;
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * @param string|null $par
	 */
	protected function setup( $par ) {
		$opts = new FormOptions();
		$this->opts = $opts; // bind
		$opts->add( 'hideliu', false );
		$opts->add(
			'hidepatrolled',
			$this->userOptionsLookup->getBoolOption( $this->getUser(), 'newpageshidepatrolled' )
		);
		$opts->add( 'hidebots', false );
		$opts->add( 'hideredirs', true );
		$opts->add(
			'limit',
			$this->userOptionsLookup->getIntOption( $this->getUser(), 'rclimit' )
		);
		$opts->add( 'offset', '' );
		$opts->add( 'namespace', '0' );
		$opts->add( 'username', '' );
		$opts->add( 'feed', '' );
		$opts->add( 'tagfilter', '' );
		$opts->add( 'tagInvert', false );
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );
		$opts->add( 'size-mode', 'max' );
		$opts->add( 'size', 0 );

		$this->customFilters = [];
		$this->getHookRunner()->onSpecialNewPagesFilters( $this, $this->customFilters );
		// @phan-suppress-next-line PhanEmptyForeach False positive
		foreach ( $this->customFilters as $key => $params ) {
			$opts->add( $key, $params['default'] );
		}

		$opts->fetchValuesFromRequest( $this->getRequest() );
		if ( $par ) {
			$this->parseParams( $par );
		}

		// The hideliu option is only available when anonymous users can create pages, as if specified when they
		// cannot create pages it always would produce no results. Therefore, if anon users cannot create pages
		// then set hideliu as false overriding the value provided by the user.
		if ( !$this->canAnonymousUsersCreatePages() ) {
			$opts->setValue( 'hideliu', false, true );
		}

		$opts->validateIntBounds( 'limit', 0, 5000 );
	}

	/**
	 * @param string $par
	 */
	protected function parseParams( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			$m = [];
			if ( $bit === 'shownav' ) {
				$this->showNavigation = true;
			} elseif ( $bit === 'hideliu' ) {
				$this->opts->setValue( 'hideliu', true );
			} elseif ( $bit === 'hidepatrolled' ) {
				$this->opts->setValue( 'hidepatrolled', true );
			} elseif ( $bit === 'hidebots' ) {
				$this->opts->setValue( 'hidebots', true );
			} elseif ( $bit === 'showredirs' ) {
				$this->opts->setValue( 'hideredirs', false );
			} elseif ( is_numeric( $bit ) ) {
				$this->opts->setValue( 'limit', intval( $bit ) );
			} elseif ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$this->opts->setValue( 'limit', intval( $m[1] ) );
			} elseif ( preg_match( '/^offset=([^=]+)$/', $bit, $m ) ) {
				// PG offsets not just digits!
				$this->opts->setValue( 'offset', intval( $m[1] ) );
			} elseif ( preg_match( '/^username=(.*)$/', $bit, $m ) ) {
				$this->opts->setValue( 'username', $m[1] );
			} elseif ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $this->getLanguage()->getNsIndex( $m[1] );
				if ( $ns !== false ) {
					$this->opts->setValue( 'namespace', $ns );
				}
			} else {
				// T62424 try to interpret unrecognized parameters as a namespace
				$ns = $this->getLanguage()->getNsIndex( $bit );
				if ( $ns !== false ) {
					$this->opts->setValue( 'namespace', $ns );
				}
			}
		}
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		$this->showNavigation = !$this->including(); // Maybe changed in setup
		$this->setup( $par );

		$this->addHelpLink( 'Help:New pages' );

		if ( !$this->including() ) {
			// Settings
			$this->form();

			$feedType = $this->opts->getValue( 'feed' );
			if ( $feedType ) {
				$this->feed( $feedType );

				return;
			}

			$allValues = $this->opts->getAllValues();
			unset( $allValues['feed'] );
			$out->setFeedAppendQuery( wfArrayToCgi( $allValues ) );
		}

		$pager = $this->getNewPagesPager();
		$pager->mLimit = $this->opts->getValue( 'limit' );
		$pager->mOffset = $this->opts->getValue( 'offset' );

		if ( $pager->getNumRows() ) {
			$navigation = '';
			if ( $this->showNavigation ) {
				$navigation = $pager->getNavigationBar();
			}
			$out->addHTML( $navigation . $pager->getBody() . $navigation );
			// Add styles for change tags
			$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		} else {
			$out->addWikiMsg( 'specialpage-empty' );
		}
	}

	protected function filterLinks(): string {
		// show/hide links
		$showhide = [ $this->msg( 'show' )->escaped(), $this->msg( 'hide' )->escaped() ];

		// Option value -> message mapping
		$filters = [
			'hideliu' => 'newpages-showhide-registered',
			'hidepatrolled' => 'newpages-showhide-patrolled',
			'hidebots' => 'newpages-showhide-bots',
			'hideredirs' => 'newpages-showhide-redirect'
		];
		foreach ( $this->customFilters as $key => $params ) {
			$filters[$key] = $params['msg'];
		}

		// Disable some if needed
		if ( !$this->canAnonymousUsersCreatePages() ) {
			unset( $filters['hideliu'] );
		}
		if ( !$this->getUser()->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = [];
		$changed = $this->opts->getChangedValues();
		unset( $changed['offset'] ); // Reset offset if query type changes

		// wfArrayToCgi(), called from LinkRenderer/Title, will not output null and false values
		// to the URL, which would omit some options (T158504). Fix it by explicitly setting them
		// to 0 or 1.
		// Also do this only for boolean options, not eg. namespace or tagfilter
		foreach ( $changed as $key => $value ) {
			if ( array_key_exists( $key, $filters ) ) {
				$changed[$key] = $changed[$key] ? '1' : '0';
			}
		}

		$self = $this->getPageTitle();
		$linkRenderer = $this->getLinkRenderer();
		foreach ( $filters as $key => $msg ) {
			$onoff = 1 - $this->opts->getValue( $key );
			$link = $linkRenderer->makeLink(
				$self,
				new HtmlArmor( $showhide[$onoff] ),
				[],
				[ $key => $onoff ] + $changed
			);
			$links[$key] = $this->msg( $msg )->rawParams( $link )->escaped();
		}

		return $this->getLanguage()->pipeList( $links );
	}

	protected function form() {
		$out = $this->getOutput();

		// Consume values
		$this->opts->consumeValue( 'offset' ); // don't carry offset, DWIW
		$namespace = $this->opts->consumeValue( 'namespace' );
		$username = $this->opts->consumeValue( 'username' );
		$tagFilterVal = $this->opts->consumeValue( 'tagfilter' );
		$tagInvertVal = $this->opts->consumeValue( 'tagInvert' );
		$nsinvert = $this->opts->consumeValue( 'invert' );
		$nsassociated = $this->opts->consumeValue( 'associated' );

		$size = $this->opts->consumeValue( 'size' );
		$max = $this->opts->consumeValue( 'size-mode' ) === 'max';

		// Check username input validity
		$ut = Title::makeTitleSafe( NS_USER, $username );
		$userText = $ut ? $ut->getText() : '';

		$formDescriptor = [
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'label-message' => 'namespace',
				'default' => $namespace,
			],
			'nsinvert' => [
				'type' => 'check',
				'name' => 'invert',
				'label-message' => 'invert',
				'default' => $nsinvert,
				'tooltip' => 'invert',
			],
			'nsassociated' => [
				'type' => 'check',
				'name' => 'associated',
				'label-message' => 'namespace_association',
				'default' => $nsassociated,
				'tooltip' => 'namespace_association',
			],
			'tagFilter' => [
				'type' => 'tagfilter',
				'name' => 'tagfilter',
				'label-message' => 'tag-filter',
				'default' => $tagFilterVal,
			],
			'tagInvert' => [
				'type' => 'check',
				'name' => 'tagInvert',
				'label-message' => 'invert',
				'hide-if' => [ '===', 'tagFilter', '' ],
				'default' => $tagInvertVal,
			],
			'username' => [
				'type' => 'user',
				'name' => 'username',
				'label-message' => 'newpages-username',
				'default' => $userText,
				'id' => 'mw-np-username',
				'size' => 30,
			],
			'size' => [
				'type' => 'sizefilter',
				'name' => 'size',
				'default' => ( $max ? -1 : 1 ) * $size,
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );

		// Store query values in hidden fields so that form submission doesn't lose them
		foreach ( $this->opts->getUnconsumedValues() as $key => $value ) {
			$htmlForm->addHiddenField( $key, $value );
		}

		$htmlForm
			->setMethod( 'get' )
			->setFormIdentifier( 'newpagesform' )
			// The form should be visible on each request (inclusive requests with submitted forms), so
			// return always false here.
			->setSubmitCallback(
				static function () {
					return false;
				}
			)
			->setSubmitTextMsg( 'newpages-submit' )
			->setWrapperLegendMsg( 'newpages' )
			->addFooterHtml( Html::rawElement(
				'div',
				[],
				$this->filterLinks()
			) )
			->show();
		$out->addModuleStyles( 'mediawiki.special' );
	}

	private function getNewPagesPager(): NewPagesPager {
		return new NewPagesPager(
			$this->getContext(),
			$this->getLinkRenderer(),
			$this->groupPermissionsLookup,
			$this->getHookContainer(),
			$this->linkBatchFactory,
			$this->namespaceInfo,
			$this->changeTagsStore,
			$this->rowCommentFormatter,
			$this->contentHandlerFactory,
			$this->tempUserConfig,
			$this->opts,
		);
	}

	/**
	 * Output a subscription feed listing recent edits to this page.
	 *
	 * @param string $type
	 */
	protected function feed( $type ) {
		if ( !$this->getConfig()->get( MainConfigNames::Feed ) ) {
			$this->getOutput()->addWikiMsg( 'feed-unavailable' );

			return;
		}

		$feedClasses = $this->getConfig()->get( MainConfigNames::FeedClasses );
		'@phan-var array<string,class-string<ChannelFeed>> $feedClasses';
		if ( !isset( $feedClasses[$type] ) ) {
			$this->getOutput()->addWikiMsg( 'feed-invalid' );

			return;
		}

		$feed = new $feedClasses[$type](
			$this->feedTitle(),
			$this->msg( 'tagline' )->text(),
			$this->getPageTitle()->getFullURL()
		);

		$pager = $this->getNewPagesPager();
		$limit = $this->opts->getValue( 'limit' );
		$pager->mLimit = min( $limit, $this->getConfig()->get( MainConfigNames::FeedLimit ) );

		$feed->outHeader();
		if ( $pager->getNumRows() > 0 ) {
			foreach ( $pager->mResult as $row ) {
				$feed->outItem( $this->feedItem( $row ) );
			}
		}
		$feed->outFooter();
	}

	protected function feedTitle(): string {
		$desc = $this->getDescription()->text();
		$code = $this->getConfig()->get( MainConfigNames::LanguageCode );
		$sitename = $this->getConfig()->get( MainConfigNames::Sitename );

		return "$sitename - $desc [$code]";
	}

	protected function feedItem( $row ) {
		$title = Title::makeTitle( intval( $row->rc_namespace ), $row->rc_title );
		if ( $title ) {
			$date = $row->rc_timestamp;
			$comments = $title->getTalkPage()->getFullURL();

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $row ),
				$title->getFullURL(),
				$date,
				$this->feedItemAuthor( $row ),
				$comments
			);
		} else {
			return null;
		}
	}

	protected function feedItemAuthor( $row ): string {
		return $row->rc_user_text ?? '';
	}

	protected function feedItemDesc( $row ): string {
		$revisionRecord = $this->revisionLookup->getRevisionById( $row->rev_id );
		if ( !$revisionRecord ) {
			return '';
		}

		$content = $revisionRecord->getContent( SlotRecord::MAIN );
		if ( $content === null ) {
			return '';
		}

		// XXX: include content model/type in feed item?
		$revUser = $revisionRecord->getUser();
		$revUserText = $revUser ? $revUser->getName() : '';
		$revComment = $revisionRecord->getComment();
		$revCommentText = $revComment ? $revComment->text : '';
		return '<p>' . htmlspecialchars( $revUserText ) .
			$this->msg( 'colon-separator' )->inContentLanguage()->escaped() .
			htmlspecialchars( FeedItem::stripComment( $revCommentText ) ) .
			"</p>\n<hr />\n<div>" .
			nl2br( htmlspecialchars( $content->serialize() ) ) . "</div>";
	}

	/**
	 * @return bool Whether any users classed anonymous can create pages (when temporary accounts are enabled, then
	 *   this definition includes temporary accounts).
	 */
	private function canAnonymousUsersCreatePages(): bool {
		// Get all the groups which anon users can be in.
		$anonGroups = [ '*' ];
		if ( $this->tempUserConfig->isKnown() ) {
			$anonGroups[] = 'temp';
		}
		// Check if any of the groups have the createpage or createtalk right.
		foreach ( $anonGroups as $group ) {
			$anonUsersCanCreatePages = $this->groupPermissionsLookup->groupHasPermission( $group, 'createpage' ) ||
				$this->groupPermissionsLookup->groupHasPermission( $group, 'createtalk' );
			if ( $anonUsersCanCreatePages ) {
				return true;
			}
		}
		return false;
	}

	protected function getGroupName() {
		return 'changes';
	}

	protected function getCacheTTL() {
		return 60 * 5;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialNewPages::class, 'SpecialNewpages' );
