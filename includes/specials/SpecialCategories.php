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

	function __construct() {
		parent::__construct( 'Categories' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();

		$offset = $this->getRequest()->getText( 'offset' );
		if ( ! $offset ) {
			// 'from' param is deprecated as of 1.21 but still recognized
			// for backwards compatibility, if the offset param is missing
			$offset = $this->getRequest()->getText( 'from', $par );
		}

		$cap = new CategoryPager( $this->getContext(), $offset );
		$cap->doQuery();

		$this->getOutput()->addHTML(
			Html::openElement( 'div', array( 'class' => 'mw-spcontent' ) ) .
			$this->msg( 'categoriespagetext', $cap->getNumRows() )->parseAsBlock() .
			$cap->getStartForm( $offset, $this->getTitle() ) .
			$cap->getNavigationBar() .
			'<ul>' . $cap->getBody() . '</ul>' .
			$cap->getNavigationBar() .
			Html::closeElement( 'div' )
		);
	}
}

/**
 * TODO: Allow sorting by count.  We need to have a unique index to do this
 * properly.
 *
 * @ingroup SpecialPage Pager
 */
class CategoryPager extends AlphabeticPager {
	function __construct( IContextSource $context, $offset ) {
		parent::__construct( $context );
		$offset = str_replace( ' ', '_', $offset );
		if( $offset !== '' ) {
			$offset = Title::capitalize( $offset, NS_CATEGORY );
			$this->setOffset( $offset );
			$this->setIncludeOffset( true );
		}
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'category' ),
			'fields' => array( 'cat_title','cat_pages' ),
			'conds' => array( 'cat_pages > 0' ),
			'options' => array( 'USE INDEX' => 'cat_title' ),
		);
	}

	function getIndexField() {
#		return array( 'abc' => 'cat_title', 'count' => 'cat_pages' );
		return 'cat_title';
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

	function formatRow($result) {
		$title = Title::makeTitle( NS_CATEGORY, $result->cat_title );
		$titleText = Linker::link( $title, htmlspecialchars( $title->getText() ) );
		$count = $this->msg( 'nmembers' )->numParams( $result->cat_pages )->escaped();
		return Xml::tags( 'li', null, $this->getLanguage()->specialList( $titleText, $count ) ) . "\n";
	}

	public function getStartForm( $offset, $title ) {
		global $wgScript;

		return
			Xml::tags( 'form', array( 'method' => 'get', 'action' => $wgScript ),
				Html::hidden( 'title', $title->getPrefixedText() ) .
				Xml::fieldset( $this->msg( 'categories' )->text(),
					Xml::inputLabel( $this->msg( 'categoriesfrom' )->text(),
						'offset', 'offset', 20, $from ) .
					' ' .
					Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) ) );
	}
}
