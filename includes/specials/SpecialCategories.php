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
	 * @var callable
	 */
	protected $pagerBuilder = null;

	public function __construct() {
		parent::__construct( 'Categories' );

		// Since we don't control the constructor parameters, we can't inject services that way.
		// Instead, we initialize services in the execute() method, and allow them to be overridden
		// using the initServices() method.
	}

	/**
	 * Initialize or override the pagerBuilder SpecialCategories::newCategoryPager() will
	 * use to create a pager that will execute the database query and generate the HTML output.
	 *
	 * @param callable $pagerBuilder A callable that accepts one parameter,
	 * the "from" page title as a string, and returns an IndexPager for listing
	 * results.
	 *
	 * @throws InvalidArgumentException
	 */
	public function setPagerBuilder( $pagerBuilder ) {
		if ( !is_callable( $pagerBuilder ) ) {
			throw new InvalidArgumentException( '$pagerBuilder must be callable' );
		}

		$this->pagerBuilder = $pagerBuilder;
	}

	/**
	 * Default builder for the IndexPager to be used by this special page.
	 * Will be called from newCategoryPager() unless overridden by setPagerBuilder().
	 *
	 * @param string $from The title the pager should start at.
	 *
	 * @return CategoryPager
	 */
	protected function buildCategoryPager( $from ) {
		global $wgArticlePath, $wgLocalInterwiki;

		$titleFormatter = new MediaWikiTitleCodec( $this->getLanguage(), GenderCache::singleton(), $wgLocalInterwiki );
		$linkRenderer = new MediaWikiPageLinkRenderer( $titleFormatter, $wgArticlePath );
		$resultRowFormatter = new CategoryPagerRowFormatter( $this->getContext(), $linkRenderer );

		return new CategoryPager( $this->getContext(), $from, $resultRowFormatter );
	}

	/**
	 * Returns a new IndexPager suitable for listing categories starting from
	 * the title given by $from.
	 *
	 * The IndexPager is created using the builder set via setPagerBuilder(),
	 * defaulting to buildCategoryPager() if setPagerBuilder() wasn't used.
	 *
	 * @param string $from The title the pager should start at.
	 *
	 * @return CategoryPager
	 */
	protected function newCategoryPager( $from ) {
		if ( !$this->pagerBuilder ) {
			$this->pagerBuilder = array( $this, 'buildCategoryPager' );
		}

		return call_user_func( $this->pagerBuilder, $from );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();

		$from = $this->getRequest()->getText( 'from', $par );

		$cap = $this->newCategoryPager( $from );
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
	 * @var ResultRowFormatter
	 */
	protected $rowFormatter;

	/**
	 * @param IContextSource $context
	 * @param string $from
	 * @param ResultRowFormatter $rowFormatter
	 */
	public function __construct( IContextSource $context, $from, ResultRowFormatter $rowFormatter ) {
		parent::__construct( $context );
		$from = str_replace( ' ', '_', $from );
		if ( $from !== '' ) {
			$from = Title::capitalize( $from, NS_CATEGORY );
			$this->setOffset( $from );
			$this->setIncludeOffset( true );
		}

		$this->rowFormatter = $rowFormatter;
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
			$batch->add( NS_CATEGORY, $row->cat_title );
		}
		$batch->execute();
		$this->mResult->rewind();

		return parent::getBody();
	}

	function formatRow( $result ) {
		return $this->rowFormatter->formatRow( $result );
	}

	public function getStartForm( $from ) {
		global $wgScript;

		return Xml::tags(
			'form',
			array( 'method' => 'get', 'action' => $wgScript ),
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

/**
 * Formatter for result rows on SpecialCategories
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 *
 * @ingroup SpecialPage Formatter
 */
class CategoryPagerRowFormatter extends ContextSource implements ResultRowFormatter {

	/**
	 * @var TitleFormatter
	 */
	protected $titleFormatter;

	/**
	 * @var PageLinkRenderer
	 */
	protected $linkRenderer;

	/**
	 * @param IContextSource $context
	 * @param PageLinkRenderer $linkRenderer
	 */
	function __construct(
		IContextSource $context,
		PageLinkRenderer $linkRenderer
	) {
		parent::setContext( $context );

		$this->linkRenderer = $linkRenderer;
	}

	/**
	 * @param object $row the result row to format, as an stdclass object
	 *
	 * @return string HTML
	 */
	function formatRow( $row ) {
		$title = new TitleValue( NS_CATEGORY, $row->cat_title );
		$text = $title->getText();
		$link = $this->linkRenderer->renderHtmlLink( $title, htmlspecialchars( $text ) );

		$count = $this->msg( 'nmembers' )->numParams( $row->cat_pages )->escaped();
		return Html::rawElement( 'li', null, $this->getLanguage()->specialList( $link, $count ) ) . "\n";
	}
}
