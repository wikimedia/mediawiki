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
 * @ingroup Pager
 */

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

class ProtectedPagesPager extends TablePager {

	public $mConds;
	private $type, $level, $namespace, $sizetype, $size, $indefonly, $cascadeonly, $noredirect;

	/**
	 * @param SpecialPage $form
	 * @param array $conds
	 * @param string $type
	 * @param string $level
	 * @param int $namespace
	 * @param string $sizetype
	 * @param int $size
	 * @param bool $indefonly
	 * @param bool $cascadeonly
	 * @param bool $noredirect
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( $form, $conds, $type, $level, $namespace,
		$sizetype, $size, $indefonly, $cascadeonly, $noredirect,
		LinkRenderer $linkRenderer
	) {
		parent::__construct( $form->getContext(), $linkRenderer );
		$this->mConds = $conds;
		$this->type = $type ?: 'edit';
		$this->level = $level;
		$this->namespace = $namespace;
		$this->sizetype = $sizetype;
		$this->size = intval( $size );
		$this->indefonly = (bool)$indefonly;
		$this->cascadeonly = (bool)$cascadeonly;
		$this->noredirect = (bool)$noredirect;
	}

	public function preprocessResults( $result ) {
		# Do a link batch query
		$lb = new LinkBatch;
		$userids = [];

		foreach ( $result as $row ) {
			$lb->add( $row->page_namespace, $row->page_title );
			// field is nullable, maybe null on old protections
			if ( $row->log_user !== null ) {
				$userids[] = $row->log_user;
			}
		}

		// fill LinkBatch with user page and user talk
		if ( count( $userids ) ) {
			$userCache = UserCache::singleton();
			$userCache->doQuery( $userids, [], __METHOD__ );
			foreach ( $userids as $userid ) {
				$name = $userCache->getProp( $userid, 'name' );
				if ( $name !== false ) {
					$lb->add( NS_USER, $name );
					$lb->add( NS_USER_TALK, $name );
				}
			}
		}

		$lb->execute();
	}

	protected function getFieldNames() {
		static $headers = null;

		if ( $headers == [] ) {
			$headers = [
				'log_timestamp' => 'protectedpages-timestamp',
				'pr_page' => 'protectedpages-page',
				'pr_expiry' => 'protectedpages-expiry',
				'log_user' => 'protectedpages-performer',
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
	 * @param string $value
	 * @return string HTML
	 * @throws MWException
	 */
	public function formatValue( $field, $value ) {
		/** @var object $row */
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
				if ( $row->page_len !== null ) {
					$formatted .= $this->getLanguage()->getDirMark() .
						' ' . Html::rawElement(
							'span',
							[ 'class' => 'mw-protectedpages-length' ],
							Linker::formatRevisionSize( $row->page_len )
						);
				}
				break;

			case 'pr_expiry':
				$formatted = htmlspecialchars( $this->getLanguage()->formatExpiry(
					$value, /* User preference timezone */true ) );
				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( $title && MediaWikiServices::getInstance()
						 ->getPermissionManager()
						 ->userHasRight( $this->getUser(), 'protect' )
				) {
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

			case 'log_user':
				// when timestamp is null, this is a old protection row
				if ( $row->log_timestamp === null ) {
					$formatted = Html::rawElement(
						'span',
						[ 'class' => 'mw-protectedpages-unknown' ],
						$this->msg( 'protectedpages-unknown-performer' )->escaped()
					);
				} else {
					$username = UserCache::singleton()->getProp( $value, 'name' );
					if ( LogEventsList::userCanBitfield(
						$row->log_deleted,
						LogPage::DELETED_USER,
						$this->getUser()
					) ) {
						if ( $username === false ) {
							$formatted = htmlspecialchars( $value );
						} else {
							$formatted = Linker::userLink( $value, $username )
								. Linker::userToolLinks( $value, $username );
						}
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
						$this->getUser()
					) ) {
						$value = CommentStore::getStore()->getComment( 'log_comment', $row )->text;
						$formatted = Linker::formatComment( $value ?? '' );
					} else {
						$formatted = $this->msg( 'rev-deleted-comment' )->escaped();
					}
					if ( LogEventsList::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
						$formatted = '<span class="history-deleted">' . $formatted . '</span>';
					}
				}
				break;

			default:
				throw new MWException( "Unknown field '$field'" );
		}

		return $formatted;
	}

	public function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pr_expiry > ' . $this->mDb->addQuotes( $this->mDb->timestamp() ) .
			' OR pr_expiry IS NULL';
		$conds[] = 'page_id=pr_page';
		$conds[] = 'pr_type=' . $this->mDb->addQuotes( $this->type );

		if ( $this->sizetype == 'min' ) {
			$conds[] = 'page_len>=' . $this->size;
		} elseif ( $this->sizetype == 'max' ) {
			$conds[] = 'page_len<=' . $this->size;
		}

		if ( $this->indefonly ) {
			$infinity = $this->mDb->addQuotes( $this->mDb->getInfinity() );
			$conds[] = "pr_expiry = $infinity OR pr_expiry IS NULL";
		}
		if ( $this->cascadeonly ) {
			$conds[] = 'pr_cascade = 1';
		}
		if ( $this->noredirect ) {
			$conds[] = 'page_is_redirect = 0';
		}

		if ( $this->level ) {
			$conds[] = 'pr_level=' . $this->mDb->addQuotes( $this->level );
		}
		if ( $this->namespace !== null ) {
			$conds[] = 'page_namespace=' . $this->mDb->addQuotes( $this->namespace );
		}

		$commentQuery = CommentStore::getStore()->getJoin( 'log_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );

		return [
			'tables' => [
				'page', 'page_restrictions', 'log_search',
				'logparen' => [ 'logging' ] + $commentQuery['tables'] + $actorQuery['tables'],
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
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'conds' => $conds,
			'join_conds' => [
				'log_search' => [
					'LEFT JOIN', [
						'ls_field' => 'pr_id', 'ls_value = ' . $this->mDb->buildStringCast( 'pr_id' )
					]
				],
				'logparen' => [
					'LEFT JOIN', [
						'ls_log_id = log_id'
					]
				]
			] + $commentQuery['joins'] + $actorQuery['joins']
		];
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' mw-protectedpages';
	}

	public function getIndexField() {
		return 'pr_id';
	}

	public function getDefaultSort() {
		return 'pr_id';
	}

	protected function isFieldSortable( $field ) {
		// no index for sorting exists
		return false;
	}
}
