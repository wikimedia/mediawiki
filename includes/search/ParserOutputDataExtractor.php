<?php

namespace MediaWiki\Search;

use Category;
use ParserOutput;
use Title;

/**
 * Extracts data from ParserOutput for indexing in the search engine.
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
 * @since 1.28
 */
class ParserOutputSearchDataExtractor {

	/**
	 * @return array
	 */
	public function getCategories( ParserOutput $parserOutput ) {
		$categories = [];

		foreach ( array_keys( $parserOutput->getCategories() ) as $key ) {
			$categories[] = Category::newFromName( $key )->getTitle()->getText();
		}

		return $categories;
	}

	/**
	 * @return array
	 */
	public function getExternalLinks( ParserOutput $parserOutput ) {
		return array_keys( $parserOutput->getExternalLinks() );
	}

	/**
	 * @return array
	 */
	public function getOutgoingLinks( ParserOutput $parserOutput ) {
		$outgoingLinks = [];

		foreach ( $parserOutput->getLinks() as $linkedNamespace => $namespaceLinks ) {
			foreach ( array_keys( $namespaceLinks ) as $linkedDbKey ) {
				$outgoingLinks[] =
					Title::makeTitle( $linkedNamespace, $linkedDbKey )->getPrefixedDBkey();
			}
		}

		return $outgoingLinks;
	}

	/**
	 * @return array
	 */
	public function getTemplates( ParserOutput $parserOutput ) {
		$templates = [];

		foreach ( $parserOutput->getTemplates() as $tNS => $templatesInNS ) {
			foreach ( array_keys( $templatesInNS ) as $tDbKey ) {
				$templateTitle = Title::makeTitleSafe( $tNS, $tDbKey );
				if ( $templateTitle && $templateTitle->exists() ) {
					$templates[] = $templateTitle->getPrefixedText();
				}
			}
		}

		return $templates;
	}

}
