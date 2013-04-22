<?php
 /**
 *
 * Copyright © 22.04.13 by the authors listed below.
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
 * @license GPL 2+
 * @file
 *
 * @author Daniel Kinzler
 */


/**
 * TestPageSet is a utility class for test cases that need a list of wiki pages to work on.
 * To save time, we should only set up such a list once, but we can not do this in the
 * data provider evaluation phase of php unit, because at that time, the test database clone
 * is not yet present (we would write pages into the real database).
 *
 * So we can only create the pages once the test actually runs, but we want to be able to
 * name specific pages in the output of data providers, so the provider can generate a data
 * set for e.g. "append to page X" without yet knowing that pages ID or even the final title
 * (which may depend on the namespace setup).
 *
 * TestPageSet provides a solution by allowing test pages to be referred to by a symbolic name
 * (a handle). Once the pages have been created, that symbolic name can then be resolved into
 * a Title object to get the actual page name and ID.
 */
class TestPageSet {

	/**
	 * @var Title[]
	 */
	protected $titles = array();

	/**
	 * @var int
	 */
	protected $namespace;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @param int $namespace The namespace to place test pages in. Typically,
	 *        MediaWikiTestCase::getDefaultWikitextNS() is used to determine the namespace.
	 * @param string $prefix A prefix to use for creating titles from handles.
	 *        Typically the name of the test class.
	 *
	 * @example new TestPageSet( $this->getDefaultWikitextNS(), basename( __CLASS__ ) )
	 */
	public function __construct( $namespace, $prefix ) {
		$this->namespace = $namespace;
		$this->prefix = $prefix;
	}

	/**
	 * Creates a page for testing. The title is derived from the $handle parameter.
	 * To get the actual title and ID of the page, use getTitle( $handle ).
	 *
	 * @note Do not call this during the data provider evaluation phase of phpunit! The
	 * test database has not been set up at that point! Use symbolic names in test case
	 * data sets, and create the required pages in the actual test case, using a
	 * TestPageSet object.
	 *
	 * @param string $handle
	 * @param Content $content
	 * @param string $summary
	 * @param User|null $user
	 *
	 * @return Title
	 * @throws MWException if page creation failed
	 */
	public function createTestPage( $handle, Content $content, $summary = "Testing", $user = null ) {
		$title = $this->getTitle( $handle );
		$page = WikiPage::factory( $title );

		$status = $page->doEditContent( $content, $summary, EDIT_NEW, false, $user );

		if ( !$status->isOK() ) {
			throw new MWException( "Creation of test page $handle failed!\n" . $status->getWikiText() );
		}

		$title = $page->getTitle();
		$this->titles[$handle] = $title;
		return $title;
	}

	/**
	 * Returns the Title for the given test page handle.
	 *
	 * @param string $handle
	 *
	 * @return Title
	 * @throws MWException if the page is not known
	 */
	public function getTitle( $handle ) {
		if ( isset( $this->titles[$handle] ) ) {
			return $this->titles[$handle];
		} else {
			return $title = Title::newFromText( $this->prefix . $handle, $this->namespace );
		}
	}

	/**
	 * Converts a list of handles to a list of article IDs.
	 * May be used by test case implementation when a list of pages
	 * is given by the data provider as a list of handles.
	 *
	 * @param array $handles
	 *
	 * @return array page ids
	 */
	public function handlesToIds( array $handles ) {
		$this_ = $this;
		$ids = array_map(
			function ( $handle ) use ( $this_ ) {
				return $this_->getTitle( $handle )->getArticleID();
			},
			$handles
		);

		return $ids;
	}

	/**
	 * Converts a list of handles to a list of article title strings.
	 * May be used by test case implementation when a list of pages
	 * is given by the data provider as a list of handles.
	 *
	 * @param array $handles
	 *
	 * @return array page titles (as strings)
	 */
	public function handlesToTitles( array $handles ) {
		$this_ = $this;
		$titles = array_map(
			function ( $handle ) use ( $this_ ) {
				return $this_->getTitle( $handle )->getFullText();
			},
			$handles
		);

		return $titles;
	}

}