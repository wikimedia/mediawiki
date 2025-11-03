<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class ProtectedTitlesPager extends AlphabeticPager {

	private ?string $level;
	private ?int $namespace;

	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		?string $level,
		?int $namespace
	) {
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();
		$this->level = $level;
		$this->namespace = $namespace;
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
	}

	/** @inheritDoc */
	protected function doBatchLookups() {
		$this->mResult->seek( 0 );

		$lb = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $this->mResult as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
		}
		$lb->execute();
	}

	/** @inheritDoc */
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
		$conds = [
			$dbr->expr( 'pt_expiry', '>', $this->mDb->timestamp() )
				->or( 'pt_expiry', '=', null ),
		];
		if ( $this->level ) {
			$conds['pt_create_perm'] = $this->level;
		}

		if ( $this->namespace !== null ) {
			$conds[] = $dbr->expr( 'pt_namespace', '=', $this->namespace );
		}

		return [
			'tables' => 'protected_titles',
			'fields' => [ 'pt_namespace', 'pt_title', 'pt_create_perm',
				'pt_expiry', 'pt_timestamp' ],
			'conds' => $conds
		];
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'pt_timestamp', 'pt_namespace', 'pt_title' ] ];
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( ProtectedTitlesPager::class, 'ProtectedTitlesPager' );
