<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Category\TrackingCategories;
use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * A special page that displays list of tracking categories.
 *
 * Tracking categories allow pages with certain characteristics to be tracked.
 * It works by adding any such page to a category automatically.
 * Category is specified by the tracking category's system message.
 *
 * @ingroup SpecialPage
 * @since 1.23
 */
class SpecialTrackingCategories extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private TrackingCategories $trackingCategories;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		TrackingCategories $trackingCategories
	) {
		parent::__construct( 'TrackingCategories' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->trackingCategories = $trackingCategories;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Tracking categories' );
		$this->getOutput()->getMetadata()->setPreventClickjacking( false );
		$this->getOutput()->addModuleStyles( [
			'jquery.tablesorter.styles',
			'mediawiki.pager.styles'
		] );
		$this->getOutput()->addModules( 'jquery.tablesorter' );
		$this->getOutput()->addHTML(
			Html::openElement( 'table', [ 'class' => [ 'mw-datatable', 'sortable' ],
				'id' => 'mw-trackingcategories-table' ] ) . "\n" .
			'<thead><tr>' .
			Html::element( 'th', [], $this->msg( 'trackingcategories-msg' )->text() ) .
			Html::element( 'th', [], $this->msg( 'trackingcategories-name' )->text() ) .
			Html::element( 'th', [], $this->msg( 'trackingcategories-desc' )->text() ) .
			'</tr></thead>'
		);

		$categoryList = $this->trackingCategories->getTrackingCategories();

		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $categoryList as $data ) {
			$batch->addObj( $data['msg'] );
			foreach ( $data['cats'] as $catTitle ) {
				$batch->addObj( $catTitle );
			}
		}
		$batch->execute();

		$this->getHookRunner()->onSpecialTrackingCategories__preprocess( $this, $categoryList );

		$linkRenderer = $this->getLinkRenderer();

		foreach ( $categoryList as $catMsg => $data ) {
			$allMsgs = [];
			$catDesc = $catMsg . '-desc';

			$catMsgTitleText = $linkRenderer->makeLink(
				$data['msg'],
				$catMsg
			);

			foreach ( $data['cats'] as $catTitle ) {
				$html = Html::rawElement( 'bdi', [ 'dir' => $this->getContentLanguage()->getDir() ],
					$linkRenderer->makeLink(
						$catTitle,
						$catTitle->getText()
					) );

				$this->getHookRunner()->onSpecialTrackingCategories__generateCatLink(
					$this, $catTitle, $html );

				$allMsgs[] = $html;
			}

			# Extra message, when no category was found
			if ( $allMsgs === [] ) {
				$allMsgs[] = $this->msg( 'trackingcategories-disabled' )->parse();
			}

			/*
			 * Show category description if it exists as a system message
			 * as category-name-desc
			 */
			$descMsg = $this->msg( $catDesc );
			if ( $descMsg->isBlank() ) {
				$descMsg = $this->msg( 'trackingcategories-nodesc' );
			}

			$this->getOutput()->addHTML(
				Html::openElement( 'tr' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-name' ] ) .
					$this->getLanguage()->commaList( array_unique( $allMsgs ) ) .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-msg' ] ) .
					$catMsgTitleText .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-desc' ] ) .
					$descMsg->parse() .
				Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' )
			);
		}
		$this->getOutput()->addHTML( Html::closeElement( 'table' ) );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pages';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialTrackingCategories::class, 'SpecialTrackingCategories' );
