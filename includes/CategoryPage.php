<?php
/**
 * Class for viewing MediaWiki category description pages.
 * Modelled after ImagePage.php.
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Special handling for category description pages, showing pages,
 * subcategories and file that belong to the category
 */
class CategoryPage extends Article {
	# Subclasses can change this to override the viewer class.
	protected $mCategoryViewerClass = 'CategoryViewer';

	/**
	 * @param $title Title
	 * @return WikiCategoryPage
	 */
	protected function newPage( Title $title ) {
		// Overload mPage with a category-specific page
		return new WikiCategoryPage( $title );
	}

	/**
	 * Constructor from a page id
	 * @param $id Int article ID to load
	 */
	public static function newFromID( $id ) {
		$t = Title::newFromID( $id );
		# @todo FIXME: Doesn't inherit right
		return $t == null ? null : new self( $t );
		# return $t == null ? null : new static( $t ); // PHP 5.3
	}

	function view() {
		$request = $this->getContext()->getRequest();
		$diff = $request->getVal( 'diff' );
		$diffOnly = $request->getBool( 'diffonly',
			$this->getContext()->getUser()->getOption( 'diffonly' ) );

		if ( isset( $diff ) && $diffOnly ) {
			parent::view();
			return;
		}

		if ( !wfRunHooks( 'CategoryPageView', array( &$this ) ) ) {
			return;
		}

		$title = $this->getTitle();
		if ( NS_CATEGORY == $title->getNamespace() ) {
			$this->openShowCategory();
		}

		parent::view();

		if ( NS_CATEGORY == $title->getNamespace() ) {
			$this->closeShowCategory();
		}
	}

	function openShowCategory() {
		# For overloading
	}

	function closeShowCategory() {
		// Use these as defaults for back compat --catrope
		$request = $this->getContext()->getRequest();
		$oldFrom = $request->getVal( 'from' );
		$oldUntil = $request->getVal( 'until' );

		$reqArray = $request->getValues();

		$from = $until = array();
		foreach ( array( 'page', 'subcat', 'file' ) as $type ) {
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

		$viewer = new $this->mCategoryViewerClass( $this->getContext()->getTitle(), $this->getContext(), $from, $until, $reqArray );
		$this->getContext()->getOutput()->addHTML( $viewer->getHTML() );
	}
}
