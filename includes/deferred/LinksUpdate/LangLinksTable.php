<?php

namespace MediaWiki\Deferred\LinksUpdate;

use ParserOutput;

/**
 * langlinks
 *
 * Link ID format: string[]
 *   - 0: Language code
 *   - 1: Foreign title
 *
 * @since 1.38
 */
class LangLinksTable extends LinksTable {
	private $newLinks = [];
	private $existingLinks;

	public function setParserOutput( ParserOutput $parserOutput ) {
		// Convert the format of the interlanguage links
		// I didn't want to change it in the ParserOutput, because that array is passed all
		// the way back to the skin, so either a skin API break would be required, or an
		// inefficient back-conversion.
		$ill = $parserOutput->getLanguageLinks();
		$this->newLinks = [];
		foreach ( $ill as $link ) {
			[ $key, $title ] = explode( ':', $link, 2 );
			$this->newLinks[$key] = $title;
		}
	}

	protected function getTableName() {
		return 'langlinks';
	}

	protected function getFromField() {
		return 'll_from';
	}

	protected function getExistingFields() {
		return [ 'll_lang', 'll_title' ];
	}

	protected function getNewLinkIDs() {
		foreach ( $this->newLinks as $key => $title ) {
			yield [ (string)$key, $title ];
		}
	}

	/**
	 * Get the existing links as an array where the key is the language code
	 * and the value is the title of the target in that language.
	 *
	 * @return array
	 */
	private function getExistingLinks() {
		if ( $this->existingLinks === null ) {
			$this->existingLinks = [];
			foreach ( $this->fetchExistingRows() as $row ) {
				$this->existingLinks[$row->ll_lang] = $row->ll_title;
			}
		}
		return $this->existingLinks;
	}

	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $lang => $title ) {
			yield [ (string)$lang, $title ];
		}
	}

	protected function isExisting( $linkId ) {
		$links = $this->getExistingLinks();
		[ $lang, $title ] = $linkId;
		return \array_key_exists( $lang, $links )
			&& $links[$lang] === $title;
	}

	protected function isInNewSet( $linkId ) {
		[ $lang, $title ] = $linkId;
		return \array_key_exists( $lang, $this->newLinks )
			&& $this->newLinks[$lang] === $title;
	}

	protected function insertLink( $linkId ) {
		[ $lang, $title ] = $linkId;
		$this->insertRow( [
			'll_lang' => $lang,
			'll_title' => $title
		] );
	}

	protected function deleteLink( $linkId ) {
		$this->deleteRow( [
			'll_lang' => $linkId[0]
		] );
	}
}
