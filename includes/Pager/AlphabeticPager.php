<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Pager;

/**
 * IndexPager with an alphabetic list and a formatted navigation bar
 *
 * @stable to extend
 * @ingroup Pager
 */
abstract class AlphabeticPager extends IndexPager {

	/**
	 * @stable to override
	 * @return string HTML
	 */
	public function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( $this->mNavigationBar !== null ) {
			return $this->mNavigationBar;
		}

		$navBuilder = $this->getNavigationBuilder()
			->setPrevMsg( 'prevn' )
			->setNextMsg( 'nextn' )
			->setFirstMsg( 'page_first' )
			->setLastMsg( 'page_last' );

		$this->mNavigationBar = $navBuilder->getHtml();

		return $this->mNavigationBar;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( AlphabeticPager::class, 'AlphabeticPager' );
