<?php
/**
 * Copyright © 2011 Sam Reed
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
 */

use MediaWiki\Api\ApiHookRunner;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup API
 */
class ApiFeedContributions extends ApiBase {

	/** @var RevisionStore */
	private $revisionStore;

	/** @var TitleParser */
	private $titleParser;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var HookContainer */
	private $hookContainer;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var UserFactory */
	private $userFactory;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var ApiHookRunner */
	private $hookRunner;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param RevisionStore $revisionStore
	 * @param TitleParser $titleParser
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $loadBalancer
	 * @param NamespaceInfo $namespaceInfo
	 * @param ActorMigration $actorMigration
	 * @param UserFactory $userFactory
	 * @param CommentFormatter $commentFormatter
	 */
	public function __construct(
		ApiMain $main,
		$action,
		RevisionStore $revisionStore,
		TitleParser $titleParser,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		HookContainer $hookContainer,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $namespaceInfo,
		ActorMigration $actorMigration,
		UserFactory $userFactory,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $main, $action );
		$this->revisionStore = $revisionStore;
		$this->titleParser = $titleParser;
		$this->linkRenderer = $linkRenderer;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->hookContainer = $hookContainer;
		$this->loadBalancer = $loadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->actorMigration = $actorMigration;
		$this->userFactory = $userFactory;
		$this->commentFormatter = $commentFormatter;

		$this->hookRunner = new ApiHookRunner( $hookContainer );
	}

	/**
	 * This module uses a custom feed wrapper printer.
	 *
	 * @return ApiFormatFeedWrapper
	 */
	public function getCustomPrinter() {
		return new ApiFormatFeedWrapper( $this->getMain() );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$config = $this->getConfig();
		if ( !$config->get( MainConfigNames::Feed ) ) {
			$this->dieWithError( 'feed-unavailable' );
		}

		$feedClasses = $config->get( MainConfigNames::FeedClasses );
		if ( !isset( $feedClasses[$params['feedformat']] ) ) {
			$this->dieWithError( 'feed-invalid' );
		}

		if ( $params['showsizediff'] && $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$this->dieWithError( 'apierror-sizediffdisabled' );
		}

		$msg = $this->msg( 'Contributions' )->inContentLanguage()->text();
		$feedTitle = $config->get( MainConfigNames::Sitename ) . ' - ' . $msg .
			' [' . $config->get( MainConfigNames::LanguageCode ) . ']';

		$target = $params['user'];
		if ( ExternalUserNames::isExternal( $target ) ) {
			// Interwiki names make invalid titles, so put the target in the query instead.
			$feedUrl = SpecialPage::getTitleFor( 'Contributions' )->getFullURL( [ 'target' => $target ] );
		} else {
			$feedUrl = SpecialPage::getTitleFor( 'Contributions', $target )->getFullURL();
		}

		$feed = new $feedClasses[$params['feedformat']] (
			$feedTitle,
			htmlspecialchars( $msg ),
			$feedUrl
		);

		// Convert year/month parameters to end parameter
		$params['start'] = '';
		$params['end'] = '';
		$params = ContribsPager::processDateFilter( $params );

		$targetUser = $this->userFactory->newFromName( $target, UserRigorOptions::RIGOR_NONE );

		$pager = new ContribsPager(
			$this->getContext(), [
				'target' => $target,
				'namespace' => $params['namespace'],
				'start' => $params['start'],
				'end' => $params['end'],
				'tagFilter' => $params['tagfilter'],
				'deletedOnly' => $params['deletedonly'],
				'topOnly' => $params['toponly'],
				'newOnly' => $params['newonly'],
				'hideMinor' => $params['hideminor'],
				'showSizeDiff' => $params['showsizediff'],
			],
			$this->linkRenderer,
			$this->linkBatchFactory,
			$this->hookContainer,
			$this->loadBalancer,
			$this->actorMigration,
			$this->revisionStore,
			$this->namespaceInfo,
			$targetUser,
			$this->commentFormatter
		);

		$feedLimit = $this->getConfig()->get( MainConfigNames::FeedLimit );
		if ( $pager->getLimit() > $feedLimit ) {
			$pager->setLimit( $feedLimit );
		}

		$feedItems = [];
		if ( $pager->getNumRows() > 0 ) {
			$count = 0;
			$limit = $pager->getLimit();
			foreach ( $pager->mResult as $row ) {
				// ContribsPager selects one more row for navigation, skip that row
				if ( ++$count > $limit ) {
					break;
				}
				$item = $this->feedItem( $row );
				if ( $item !== null ) {
					$feedItems[] = $item;
				}
			}
		}

		ApiFormatFeedWrapper::setResult( $this->getResult(), $feed, $feedItems );
	}

	protected function feedItem( $row ) {
		// This hook is the api contributions equivalent to the
		// ContributionsLineEnding hook. Hook implementers may cancel
		// the hook to signal the user is not allowed to read this item.
		$feedItem = null;
		$hookResult = $this->hookRunner->onApiFeedContributions__feedItem(
			$row, $this->getContext(), $feedItem );
		// Hook returned a valid feed item
		if ( $feedItem instanceof FeedItem ) {
			return $feedItem;
		// Hook was canceled and did not return a valid feed item
		} elseif ( !$hookResult ) {
			return null;
		}

		// Hook completed and did not return a valid feed item
		$title = Title::makeTitle( (int)$row->page_namespace, $row->page_title );

		if ( $title && $this->getAuthority()->authorizeRead( 'read', $title ) ) {
			$date = $row->rev_timestamp;
			$comments = $title->getTalkPage()->getFullURL();
			$revision = $this->revisionStore->newRevisionFromRow( $row, 0, $title );

			return new FeedItem(
				$title->getPrefixedText(),
				$this->feedItemDesc( $revision ),
				$title->getFullURL( [ 'diff' => $revision->getId() ] ),
				$date,
				$this->feedItemAuthor( $revision ),
				$comments
			);
		}

		return null;
	}

	/**
	 * @since 1.32, takes a RevisionRecord instead of a Revision
	 * @param RevisionRecord $revision
	 * @return string
	 */
	protected function feedItemAuthor( RevisionRecord $revision ) {
		$user = $revision->getUser();
		return $user ? $user->getName() : '';
	}

	/**
	 * @since 1.32, takes a RevisionRecord instead of a Revision
	 * @param RevisionRecord $revision
	 * @return string
	 */
	protected function feedItemDesc( RevisionRecord $revision ) {
		$msg = $this->msg( 'colon-separator' )->inContentLanguage()->text();
		try {
			$content = $revision->getContent( SlotRecord::MAIN );
		} catch ( RevisionAccessException $e ) {
			$content = null;
		}

		if ( $content instanceof TextContent ) {
			// only textual content has a "source view".
			$html = nl2br( htmlspecialchars( $content->getText(), ENT_COMPAT ) );
		} else {
			// XXX: we could get an HTML representation of the content via getParserOutput, but that may
			//     contain JS magic and generally may not be suitable for inclusion in a feed.
			//     Perhaps Content should have a getDescriptiveHtml method and/or a getSourceText method.
			// Compare also FeedUtils::formatDiffRow.
			$html = '';
		}

		$comment = $revision->getComment();

		return '<p>' . htmlspecialchars( $this->feedItemAuthor( $revision ) ) . $msg .
			htmlspecialchars( FeedItem::stripComment( $comment->text ?? '' ) ) .
			"</p>\n<hr />\n<div>" . $html . '</div>';
	}

	public function getAllowedParams() {
		$feedFormatNames = array_keys( $this->getConfig()->get( MainConfigNames::FeedClasses ) );

		$ret = [
			'feedformat' => [
				ParamValidator::PARAM_DEFAULT => 'rss',
				ParamValidator::PARAM_TYPE => $feedFormatNames
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'cidr', 'id', 'interwiki' ],
				ParamValidator::PARAM_REQUIRED => true,
			],
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'year' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'month' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'tagfilter' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => array_values( ChangeTags::listDefinedTags() ),
				ParamValidator::PARAM_DEFAULT => '',
			],
			'deletedonly' => false,
			'toponly' => false,
			'newonly' => false,
			'hideminor' => false,
			'showsizediff' => [
				ParamValidator::PARAM_DEFAULT => false,
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['showsizediff'][ApiBase::PARAM_HELP_MSG] = 'api-help-param-disabled-in-miser-mode';
		}

		return $ret;
	}

	protected function getExamplesMessages() {
		return [
			'action=feedcontributions&user=Example'
				=> 'apihelp-feedcontributions-example-simple',
		];
	}
}
