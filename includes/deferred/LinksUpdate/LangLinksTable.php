<?php

namespace MediaWiki\Deferred\LinksUpdate;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;

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
	/** @var string[] */
	private $newLinks = [];
	/** @var string[]|null */
	private $existingLinks;

	public function setParserOutput( ParserOutput $parserOutput ) {
		// Convert the format of the interlanguage links
		$this->newLinks = [];
		foreach (
			$parserOutput->getLinkList( ParserOutputLinkTypes::LANGUAGE )
			as [ 'link' => $link ]
		) {
			$key = $link->getInterwiki();
			$title = $link->getText();
			if ( $link->hasFragment() ) {
				$title .= '#' . $link->getFragment();
			}
			// Ensure that the "first" link has precedence (T26502) although
			// ParserOutput::addLanguageLink() should ensure we don't see dups
			$this->newLinks[$key] ??= $title;
		}
	}

	/** @inheritDoc */
	protected function getTableName() {
		return 'langlinks';
	}

	/** @inheritDoc */
	protected function getFromField() {
		return 'll_from';
	}

	/** @inheritDoc */
	protected function getExistingFields() {
		return [ 'll_lang', 'll_title' ];
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function getExistingLinkIDs() {
		foreach ( $this->getExistingLinks() as $lang => $title ) {
			yield [ (string)$lang, $title ];
		}
	}

	/** @inheritDoc */
	protected function isExisting( $linkId ) {
		$links = $this->getExistingLinks();
		[ $lang, $title ] = $linkId;
		return \array_key_exists( $lang, $links )
			&& $links[$lang] === $title;
	}

	/** @inheritDoc */
	protected function isInNewSet( $linkId ) {
		[ $lang, $title ] = $linkId;
		return \array_key_exists( $lang, $this->newLinks )
			&& $this->newLinks[$lang] === $title;
	}

	/** @inheritDoc */
	protected function insertLink( $linkId ) {
		[ $lang, $title ] = $linkId;
		$this->insertRow( [
			'll_lang' => $lang,
			'll_title' => $title
		] );
	}

	/** @inheritDoc */
	protected function deleteLink( $linkId ) {
		$this->deleteRow( [
			'll_lang' => $linkId[0]
		] );
	}
}
