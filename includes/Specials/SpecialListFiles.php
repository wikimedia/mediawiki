<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Pager\ImageListPager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Listfiles
 *
 * @ingroup SpecialPage
 */
class SpecialListFiles extends IncludableSpecialPage {

	private RepoGroup $repoGroup;
	private IConnectionProvider $dbProvider;
	private CommentStore $commentStore;
	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private RowCommentFormatter $rowCommentFormatter;
	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		RepoGroup $repoGroup,
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		RowCommentFormatter $rowCommentFormatter,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Listfiles' );
		$this->repoGroup = $repoGroup;
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->linkBatchFactory = $linkBatchFactory;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Managing_files' );

		if ( $this->including() ) {
			$userName = (string)$par;
			$search = '';
			$showAll = false;
		} else {
			$userName = $this->getRequest()->getText( 'user', $par ?? '' );
			$search = $this->getRequest()->getText( 'ilsearch', '' );
			$showAll = $this->getRequest()->getBool( 'ilshowall', false );
		}
		// Sanitize usernames to avoid symbols in the title of page.
		$sanitizedUserName = $this->userNameUtils->getCanonical( $userName, UserRigorOptions::RIGOR_NONE );
		if ( $sanitizedUserName !== false ) {
			$userName = $sanitizedUserName;
		}

		if ( $userName !== '' ) {
			$pageTitle = $this->msg( 'listfiles_subpage' )->plaintextParams( $userName );
		} else {
			$pageTitle = $this->msg( 'listfiles' );
		}

		$pager = new ImageListPager(
			$this->getContext(),
			$this->commentStore,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$this->repoGroup,
			$this->userNameUtils,
			$this->rowCommentFormatter,
			$this->linkBatchFactory,
			$userName,
			$search,
			$this->including(),
			$showAll
		);

		$out = $this->getOutput();
		$out->setPageTitleMsg( $pageTitle );
		$out->addModuleStyles( 'mediawiki.special' );
		$parserOptions = ParserOptions::newFromContext( $this->getContext() );
		if ( $this->including() ) {
			$out->addParserOutputContent( $pager->getBodyOutput(), $parserOptions );
		} else {
			$user = $pager->getRelevantUser();
			if ( $user ) {
				$this->getSkin()->setRelevantUser( $user );
			}
			$pager->getForm();
			$out->addParserOutputContent( $pager->getFullOutput(), $parserOptions );
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'media';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialListFiles::class, 'SpecialListFiles' );
