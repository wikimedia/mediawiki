<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use MediaWiki\Category\CategoryViewer;
use MediaWiki\Title\Title;

/**
 * Special handling for category description pages.
 *
 * This displays category members: subcategories, pages, and files categorised here.
 *
 * @method WikiCategoryPage getPage() Set by overwritten newPage() in this class
 */
class CategoryPage extends Article {
	/** @var class-string<CategoryViewer> Subclasses can change this to override the viewer class. */
	protected $mCategoryViewerClass = CategoryViewer::class;

	/**
	 * @param Title $title
	 * @return WikiCategoryPage
	 */
	protected function newPage( Title $title ) {
		// Overload mPage with a category-specific page
		return new WikiCategoryPage( $title );
	}

	public function view() {
		$request = $this->getContext()->getRequest();
		$diff = $request->getVal( 'diff' );

		if ( $diff !== null && $this->isDiffOnlyView() ) {
			parent::view();
			return;
		}

		if ( !$this->getHookRunner()->onCategoryPageView( $this ) ) {
			return;
		}

		$title = $this->getTitle();
		if ( $title->inNamespace( NS_CATEGORY ) ) {
			$this->openShowCategory();
		}

		parent::view();

		if ( $title->inNamespace( NS_CATEGORY ) ) {
			$this->closeShowCategory();
		}

		# Use adaptive TTLs for CDN so delayed/failed purges are noticed less often
		$outputPage = $this->getContext()->getOutput();
		$outputPage->adaptCdnTTL(
			$this->getPage()->getTouched(),
			60
		);
	}

	public function openShowCategory() {
		# For overloading
	}

	public function closeShowCategory() {
		// Use these as defaults for back compat --catrope
		$request = $this->getContext()->getRequest();
		$oldFrom = $request->getVal( 'from' );
		$oldUntil = $request->getVal( 'until' );

		$reqArray = $request->getQueryValues();

		$from = $until = [];
		foreach ( [ 'page', 'subcat', 'file' ] as $type ) {
			$from[$type] = $request->getVal( "{$type}from", $oldFrom );
			$until[$type] = $request->getVal( "{$type}until", $oldUntil );

			// Do not want old-style from/until propagating in nav links.
			if ( !isset( $reqArray["{$type}from"] ) && isset( $reqArray["from"] ) ) {
				$reqArray["{$type}from"] = $reqArray["from"];
			}
			if ( !isset( $reqArray["{$type}to"] ) && isset( $reqArray["to"] ) ) {
				$reqArray["{$type}to"] = $reqArray["to"];
			}
		}

		unset( $reqArray["from"] );
		unset( $reqArray["to"] );

		$viewer = new $this->mCategoryViewerClass(
			$this->getPage(),
			$this->getContext(),
			$from,
			$until,
			$reqArray
		);
		$out = $this->getContext()->getOutput();
		$out->addHTML( $viewer->getHTML() );
		$this->addHelpLink( 'Help:Categories' );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CategoryPage::class, 'CategoryPage' );
