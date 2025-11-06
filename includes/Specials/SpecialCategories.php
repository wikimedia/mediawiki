<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Pager\CategoryPager;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Categories
 *
 * @ingroup SpecialPage
 */
class SpecialCategories extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Categories' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Categories' );
		$this->getOutput()->getMetadata()->setPreventClickjacking( false );

		$from = $this->getRequest()->getText( 'from', $par ?? '' );

		$cap = new CategoryPager(
			$this->getContext(),
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$from
		);
		$cap->doQuery();

		$this->getOutput()->addHTML(
			Html::openElement( 'div', [ 'class' => 'mw-spcontent' ] ) .
				$this->msg( 'categoriespagetext', $cap->getNumRows() )->parseAsBlock() .
				$cap->getStartForm( $from ) .
				$cap->getNavigationBar() .
				'<ul>' . $cap->getBody() . '</ul>' .
				$cap->getNavigationBar() .
				Html::closeElement( 'div' )
		);
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pages';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialCategories::class, 'SpecialCategories' );
