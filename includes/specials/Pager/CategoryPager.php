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
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class CategoryPager extends AlphabeticPager {

	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		IContextSource $context,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		string $from
	) {
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
		$from = str_replace( ' ', '_', $from );
		if ( $from !== '' ) {
			$from = Title::capitalize( $from, NS_CATEGORY );
			$this->setOffset( $from );
			$this->setIncludeOffset( true );
		}
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'category' ],
			'fields' => [ 'cat_title', 'cat_pages' ],
			'options' => [ 'USE INDEX' => 'cat_title' ],
		];
	}

	/** @inheritDoc */
	public function getIndexField() {
		return 'cat_title';
	}

	/** @inheritDoc */
	public function getDefaultQuery() {
		parent::getDefaultQuery();
		unset( $this->mDefaultQuery['from'] );

		return $this->mDefaultQuery;
	}

	/**
	 * Override getBody to apply LinksBatch on resultset before actually outputting anything.
	 * @inheritDoc
	 */
	public function getBody() {
		$batch = $this->linkBatchFactory->newLinkBatch();

		$this->mResult->rewind();

		foreach ( $this->mResult as $row ) {
			$batch->add( NS_CATEGORY, $row->cat_title );
		}
		$batch->execute();
		$this->mResult->rewind();

		return parent::getBody();
	}

	/** @inheritDoc */
	public function formatRow( $result ) {
		$title = new TitleValue( NS_CATEGORY, $result->cat_title );
		$text = $title->getText();
		$link = $this->getLinkRenderer()->makeLink( $title, $text );

		$count = $this->msg( 'nmembers' )->numParams( $result->cat_pages )->escaped();
		return Html::rawElement( 'li', [], $this->getLanguage()->specialList( $link, $count ) ) . "\n";
	}

	/** @inheritDoc */
	public function getStartForm( $from ) {
		$formDescriptor = [
			'from' => [
				'type' => 'title',
				'namespace' => NS_CATEGORY,
				'relative' => true,
				'label-message' => 'categoriesfrom',
				'name' => 'from',
				'id' => 'from',
				'size' => 20,
				'default' => $from,
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setSubmitTextMsg( 'categories-submit' )
			->setWrapperLegendMsg( 'categories' )
			->setMethod( 'get' );
		return $htmlForm->prepareForm()->getHTML( false );
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( CategoryPager::class, 'CategoryPager' );
