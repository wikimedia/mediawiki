<?php
/**
 * Implements Special:Categories
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
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class SpecialCategories extends SpecialPage {

	/**
	 * @var PageLinkRenderer
	 */
	protected $linkRenderer = null;

	public function __construct() {
		parent::__construct( 'Categories' );

		// Since we don't control the constructor parameters, we can't inject services that way.
		// Instead, we initialize services in the execute() method, and allow them to be overridden
		// using the initServices() method.
	}

	/**
	 * Initialize or override the PageLinkRenderer SpecialCategories collaborates with.
	 * Useful mainly for testing.
	 *
	 * @todo the pager should also be injected, and de-coupled from the rendering logic.
	 *
	 * @param PageLinkRenderer $linkRenderer
	 */
	public function setPageLinkRenderer(
		PageLinkRenderer $linkRenderer
	) {
		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * Initialize any services we'll need (unless it has already been provided via a setter).
	 * This allows for dependency injection even though we don't control object creation.
	 */
	private function initServices() {
		if ( !$this->linkRenderer ) {
			$lang = $this->getContext()->getLanguage();
			$titleFormatter = new MediaWikiTitleCodec( $lang, GenderCache::singleton() );
			$this->linkRenderer = new MediaWikiPageLinkRenderer( $titleFormatter );
		}
	}

	public function execute( $par ) {
		$this->initServices();

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();

		$from = $this->getRequest()->getText( 'from', $par );

		$cap = new CategoryPager( $this->getContext(), $from, $this->linkRenderer );
		$cap->doQuery();

		$this->getOutput()->addHTML(
			Html::openElement( 'div', array( 'class' => 'mw-spcontent' ) ) .
				$this->msg( 'categoriespagetext', $cap->getNumRows() )->parseAsBlock() .
				$cap->getStartForm( $from ) .
				$cap->getNavigationBar() .
				'<ul>' . $cap->getBody() . '</ul>' .
				$cap->getNavigationBar() .
				Html::closeElement( 'div' )
		);
	}

	protected function getGroupName() {
		return 'pages';
	}
}

/**
 * TODO: Allow sorting by count.  We need to have a unique index to do this
 * properly.
 *
 * @ingroup SpecialPage Pager
 */
class CategoryPager extends AlphabeticPager {

	/**
	 * @var PageLinkRenderer
	 */
	protected $linkRenderer;

	/**
	 * @param IContextSource $context
	 * @param string $from
	 * @param PageLinkRenderer $linkRenderer
	 */
	public function __construct( IContextSource $context, $from, PageLinkRenderer $linkRenderer
	) {
		parent::__construct( $context );
		$from = str_replace( ' ', '_', $from );
		if ( $from !== '' ) {
			$from = Title::capitalize( $from, NS_CATEGORY );
			$this->setOffset( $from );
			$this->setIncludeOffset( true );
		}

		$this->linkRenderer = $linkRenderer;
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'category' ),
			'fields' => array( 'cat_title', 'cat_pages' ),
			'conds' => array( 'cat_pages > 0' ),
			'options' => array( 'USE INDEX' => 'cat_title' ),
		);
	}

	function getIndexField() {
#		return array( 'abc' => 'cat_title', 'count' => 'cat_pages' );
		return 'cat_title';
	}

	function getDefaultQuery() {
		parent::getDefaultQuery();
		unset( $this->mDefaultQuery['from'] );

		return $this->mDefaultQuery;
	}

#	protected function getOrderTypeMessages() {
#		return array( 'abc' => 'special-categories-sort-abc',
#			'count' => 'special-categories-sort-count' );
#	}

	protected function getDefaultDirections() {
#		return array( 'abc' => false, 'count' => true );
		return false;
	}

	/* Override getBody to apply LinksBatch on resultset before actually outputting anything. */
	public function getBody() {
		$batch = new LinkBatch;

		$this->mResult->rewind();

		foreach ( $this->mResult as $row ) {
			$batch->addObj( Title::makeTitleSafe( NS_CATEGORY, $row->cat_title ) );
		}
		$batch->execute();
		$this->mResult->rewind();

		return parent::getBody();
	}

	function formatRow( $result ) {
		$title = new TitleValue( NS_CATEGORY, $result->cat_title );
		$text = $title->getText();
		$link = $this->linkRenderer->renderHtmlLink( $title, $text );

		$count = $this->msg( 'nmembers' )->numParams( $result->cat_pages )->escaped();
		return Html::rawElement( 'li', null, $this->getLanguage()->specialList( $link, $count ) ) . "\n";
	}

	public function getStartForm( $from ) {
		return Xml::tags(
			'form',
			array( 'method' => 'get', 'action' => wfScript() ),
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
				Xml::fieldset(
					$this->msg( 'categories' )->text(),
					Xml::inputLabel(
						$this->msg( 'categoriesfrom' )->text(),
						'from', 'from', 20, $from ) .
						' ' .
						Xml::submitButton( $this->msg( 'allpagessubmit' )->text()
						)
				)
		);
	}
}
