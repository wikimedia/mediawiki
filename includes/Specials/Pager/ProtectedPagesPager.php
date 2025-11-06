<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use UnexpectedValueException;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;

class ProtectedPagesPager extends TablePager {

	private string $type;
	private ?string $level;
	private ?int $namespace;
	private ?string $sizetype;
	private int $size;
	private bool $indefonly;
	private bool $cascadeonly;
	private bool $noredirect;

	private CommentStore $commentStore;
	private LinkBatchFactory $linkBatchFactory;
	private RowCommentFormatter $rowCommentFormatter;

	/** @var string[] */
	private array $formattedComments = [];

	public function __construct(
		IContextSource $context,
		CommentStore $commentStore,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		RowCommentFormatter $rowCommentFormatter,
		?string $type,
		?string $level,
		?int $namespace,
		?string $sizetype,
		?int $size,
		bool $indefonly,
		bool $cascadeonly,
		bool $noredirect
	) {
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();
		parent::__construct( $context, $linkRenderer );
		$this->commentStore = $commentStore;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->type = $type ?? 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
		$this->sizetype = $sizetype;
		$this->size = $size ?? 0;
		$this->indefonly = $indefonly;
		$this->cascadeonly = $cascadeonly;
		$this->noredirect = $noredirect;
	}

	/** @inheritDoc */
	public function preprocessResults( $result ) {
		# Do a link batch query
		$lb = $this->linkBatchFactory->newLinkBatch();
		$rowsWithComments = [];

		foreach ( $result as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
			// for old protection rows, user and comment are missing
			if ( $row->actor_name !== null ) {
				$lb->addUser( new UserIdentityValue( $row->actor_user, $row->actor_name ) );
			}
			if ( $row->log_timestamp !== null ) {
				$rowsWithComments[] = $row;
			}
		}

		$lb->execute();

		// Format the comments
		$this->formattedComments = $this->rowCommentFormatter->formatRows(
			new FakeResultWrapper( $rowsWithComments ),
			'log_comment',
			null,
			null,
			'pr_id'
		);
	}

	/** @inheritDoc */
	protected function getFieldNames() {
		static $headers = null;

		if ( $headers === null ) {
			$headers = [
				'log_timestamp' => 'protectedpages-timestamp',
				'pr_page' => 'protectedpages-page',
				'pr_expiry' => 'protectedpages-expiry',
				'actor_user' => 'protectedpages-performer',
				'pr_params' => 'protectedpages-params',
				'log_comment' => 'protectedpages-reason',
			];
			foreach ( $headers as $key => $val ) {
				$headers[$key] = $this->msg( $val )->text();
			}
		}

		return $headers;
	}

	/**
	 * @param string $field
	 * @param string|null $value
	 * @return string HTML
	 */
	public function formatValue( $field, $value ) {
		/** @var stdClass $row */
		$row = $this->mCurrentRow;
		$linkRenderer = $this->getLinkRenderer();

		switch ( $field ) {
			case 'log_timestamp':
				// when timestamp is null, this is a old protection row
				if ( $value === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-timestamp' )->escaped()
					);
				} else {
					$formatted = htmlspecialchars( $this->getLanguage()->userTimeAndDate(
						$value, $this->getUser() ) );
				}
				break;

			case 'pr_page':
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( !$title ) {
					$formatted = Html::element(
						'span',
						[ 'class' => 'mw-invalidtitle' ],
						Linker::getInvalidTitleDescription(
							$this->getContext(),
							$row->page_namespace,
							$row->page_title
						)
					);
				} else {
					$formatted = $linkRenderer->makeLink( $title );
				}
				$formatted = Html::rawElement( 'bdi', [
					'dir' => $this->getLanguage()->getDir()
				], $formatted );
				if ( $row->page_len !== null ) {
					$formatted .= ' ' . Html::rawElement(
							'span',
							[ 'class' => 'mw-protectedpages-length' ],
							Linker::formatRevisionSize( $row->page_len )
						);
				}
				break;

			case 'pr_expiry':
				$formatted = htmlspecialchars( $this->getLanguage()->formatExpiry(
					$value, /* User preference timezone */true, 'infinity', $this->getUser() ) );
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( $title && $this->getAuthority()->isAllowed( 'protect' ) ) {
					$changeProtection = $linkRenderer->makeKnownLink(
						$title,
						$this->msg( 'protect_change' )->text(),
						[],
						[ 'action' => 'unprotect' ]
					);
					$formatted .= ' ' . Html::rawElement(
							'span',
							[ 'class' => 'mw-protectedpages-actions' ],
							$this->msg( 'parentheses' )->rawParams( $changeProtection )->escaped()
						);
				}
				break;

			case 'actor_user':
				// when timestamp is null, this is a old protection row
				if ( $row->log_timestamp === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-performer' )->escaped()
					);
				} else {
					$username = $row->actor_name;
					if ( LogEventsList::userCanBitfield(
						$row->log_deleted,
						LogPage::DELETED_USER,
						$this->getAuthority()
					) ) {
						$formatted = Linker::userLink( (int)$value, $username )
							. Linker::userToolLinks( (int)$value, $username );
					} else {
						$formatted = $this->msg( 'rev-deleted-user' )->escaped();
					}
					if ( LogEventsList::isDeleted( $row, LogPage::DELETED_USER ) ) {
						$formatted = '<span class="history-deleted">' . $formatted . '</span>';
					}
				}
				break;

			case 'pr_params':
				$params = [];
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$params[] = $this->msg( 'restriction-level-' . $row->pr_level )->escaped();
				if ( $row->pr_cascade ) {
					$params[] = $this->msg( 'protect-summary-cascade' )->escaped();
				}
				$formatted = $this->getLanguage()->commaList( $params );
				break;

			case 'log_comment':
				// when timestamp is null, this is an old protection row
				if ( $row->log_timestamp === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-reason' )->escaped()
					);
				} else {
					if ( LogEventsList::userCanBitfield(
						$row->log_deleted,
						LogPage::DELETED_COMMENT,
						$this->getAuthority()
					) ) {
						$formatted = $this->formattedComments[$row->pr_id];
					} else {
						$formatted = $this->msg( 'rev-deleted-comment' )->escaped();
					}
					if ( LogEventsList::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
						$formatted = '<span class="history-deleted">' . $formatted . '</span>';
					}
				}
				break;

			default:
				throw new UnexpectedValueException( "Unknown field '$field'" );
		}

		return $formatted;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$conds = [
			$dbr->expr( 'pr_expiry', '>', $dbr->timestamp() )
				->or( 'pr_expiry', '=', null ),
			'page_id=pr_page',
			$dbr->expr( 'pr_type', '=', $this->type ),
		];

		if ( $this->sizetype == 'min' ) {
			$conds[] = 'page_len>=' . $this->size;
		} elseif ( $this->sizetype == 'max' ) {
			$conds[] = 'page_len<=' . $this->size;
		}

		if ( $this->indefonly ) {
			$conds['pr_expiry'] = [ $dbr->getInfinity(), null ];
		}
		if ( $this->cascadeonly ) {
			$conds['pr_cascade'] = 1;
		}
		if ( $this->noredirect ) {
			$conds['page_is_redirect'] = 0;
		}

		if ( $this->level ) {
			$conds[] = $dbr->expr( 'pr_level', '=', $this->level );
		}
		if ( $this->namespace !== null ) {
			$conds[] = $dbr->expr( 'page_namespace', '=', $this->namespace );
		}

		$commentQuery = $this->commentStore->getJoin( 'log_comment' );

		return [
			'tables' => [
				'page', 'page_restrictions', 'log_search',
				'logparen' => [ 'logging', 'actor' ] + $commentQuery['tables'],
			],
			'fields' => [
				'pr_id',
				'page_namespace',
				'page_title',
				'page_len',
				'pr_type',
				'pr_level',
				'pr_expiry',
				'pr_cascade',
				'log_timestamp',
				'log_deleted',
				'actor_name',
				'actor_user'
			] + $commentQuery['fields'],
			'conds' => $conds,
			'join_conds' => [
				'log_search' => [
					'LEFT JOIN', [
						'ls_field' => 'pr_id', 'ls_value = ' . $dbr->buildStringCast( 'pr_id' )
					]
				],
				'logparen' => [
					'LEFT JOIN', [
						'ls_log_id = log_id'
					]
				],
				'actor' => [
					'JOIN', [
						'actor_id=log_actor'
					]
				]
			] + $commentQuery['joins']
		];
	}

	/** @inheritDoc */
	protected function getTableClass() {
		return parent::getTableClass() . ' mw-protectedpages';
	}

	/** @inheritDoc */
	public function getIndexField() {
		return 'pr_id';
	}

	/** @inheritDoc */
	public function getDefaultSort() {
		return 'pr_id';
	}

	/** @inheritDoc */
	protected function isFieldSortable( $field ) {
		// no index for sorting exists
		return false;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( ProtectedPagesPager::class, 'ProtectedPagesPager' );
