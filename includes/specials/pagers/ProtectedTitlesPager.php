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

namespace MediaWiki\Pager;

use IContextSource;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class ProtectedTitlesPager extends AlphabeticPager {

	/**
	 * @var array
	 */
	public $mConds;

	/** @var string|null */
	private $level;

	/** @var int|null */
	private $namespace;

	private LinkBatchFactory $linkBatchFactory;

	/**
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param IConnectionProvider $dbProvider
	 * @param array $conds
	 * @param string|null $type
	 * @param string|null $level
	 * @param int|null $namespace
	 * @param string|null $sizetype
	 * @param int|null $size
	 */
	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		$conds,
		$type,
		$level,
		$namespace,
		$sizetype,
		$size
	) {
		// Set database before parent constructor to avoid setting it there with wfGetDB
		$this->mDb = $dbProvider->getReplicaDatabase();
		$this->mConds = $conds;
		$this->level = $level;
		$this->namespace = $namespace;
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
	}

	protected function doBatchLookups() {
		$this->mResult->seek( 0 );
		$lb = $this->linkBatchFactory->newLinkBatch();

		foreach ( $this->mResult as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
		}

		$lb->execute();
	}

	public function formatRow( $row ) {
		$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
		if ( !$title ) {
			return Html::rawElement(
				'li',
				[],
				Html::element(
					'span',
					[ 'class' => 'mw-invalidtitle' ],
					Linker::getInvalidTitleDescription(
						$this->getContext(),
						$row->pt_namespace,
						$row->pt_title
					)
				)
			) . "\n";
		}

		$link = $this->getLinkRenderer()->makeLink( $title );
		// Messages: restriction-level-sysop, restriction-level-autoconfirmed
		$description = $this->msg( 'restriction-level-' . $row->pt_create_perm )->escaped();
		$lang = $this->getLanguage();
		$expiry = strlen( $row->pt_expiry ) ?
			$lang->formatExpiry( $row->pt_expiry, TS_MW ) :
			'infinity';

		if ( $expiry !== 'infinity' ) {
			$user = $this->getUser();
			$description .= $this->msg( 'comma-separator' )->escaped() . $this->msg(
				'protect-expiring-local',
				$lang->userTimeAndDate( $expiry, $user ),
				$lang->userDate( $expiry, $user ),
				$lang->userTime( $expiry, $user )
			)->escaped();
		}

		return '<li>' . $lang->specialList( $link, $description ) . "</li>\n";
	}

	/**
	 * @return array
	 */
	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$conds = $this->mConds;
		$conds[] = 'pt_expiry > ' . $dbr->addQuotes( $this->mDb->timestamp() ) .
			' OR pt_expiry IS NULL';
		if ( $this->level ) {
			$conds['pt_create_perm'] = $this->level;
		}

		if ( $this->namespace !== null ) {
			$conds[] = 'pt_namespace=' . $dbr->addQuotes( $this->namespace );
		}

		return [
			'tables' => 'protected_titles',
			'fields' => [ 'pt_namespace', 'pt_title', 'pt_create_perm',
				'pt_expiry', 'pt_timestamp' ],
			'conds' => $conds
		];
	}

	public function getIndexField() {
		return [ [ 'pt_timestamp', 'pt_namespace', 'pt_title' ] ];
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( ProtectedTitlesPager::class, 'ProtectedTitlesPager' );
