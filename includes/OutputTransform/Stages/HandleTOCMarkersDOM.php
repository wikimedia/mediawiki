<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\OutputTransform\ContentDOMTransformStage;
use ParserOptions;
use ParserOutput;
use Wikimedia\Assert\Assert;
use Wikimedia\Parsoid\Core\TOCData;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

class HandleTOCMarkersDOM extends ContentDOMTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return !( $options['allowTOC'] ?? true ) || ( $options['injectTOC'] ?? true );
	}

	public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ?ParserOptions $popts, array &$options
	): DocumentFragment {
		$markers = DOMCompat::querySelectorAll( $df, "meta[property='mw:PageProp/toc']" );
		$replaced = false;
		foreach ( $markers as $marker ) {
			// Replace the first marker with the TOC, and in case multiple markers are present, ensure that the
			// others are removed.
			if ( $replaced || !( $options['allowTOC'] ?? true ) ) {
				$marker->remove();
			} elseif ( ( $options['allowTOC'] ?? true ) && ( $options['injectTOC'] ?? true ) ) {
				$this->injectToc( $po, $options, $marker );
				$replaced = true;
			}
		}
		return $df;
	}

	private function injectToc( ParserOutput $po, array $options, Element $marker ) {
		$numSections = count( $po->getSections() );
		if ( $numSections === 0 ) {
			$marker->remove();
			return;
		}
		$tocData = $po->getTOCData();
		$lang = $this->resolveUserLanguage( $options );
		$maxTocLevel = $options['maxtoclevel'] ?? null;
		if ( $maxTocLevel === null ) {
			// Use wiki-configured default
			$services = MediaWikiServices::getInstance();
			$config = $services->getMainConfig();
			$maxTocLevel = $config->get( MainConfigNames::MaxTocLevel );
		}
		$doc = $marker->ownerDocument;
		Assert::invariant( $doc !== null, 'marker without document owner' );
		$toc = $this->generateToc( $tocData, $lang, $doc, $maxTocLevel );
		if ( $toc ) {
			$marker->replaceWith( $toc );
		} else {
			$marker->remove();
		}
	}

	private function generateToc( ?TocData $tocData, Language $lang, Document $doc, int $maxTocLevel ): ?Element {
		if ( !$tocData ) {
			return null;
		}
		$title = wfMessage( 'toc' )->inLanguage( $lang )->text();

		$toggle = $this->createElement( $doc, 'input', [
			'type' => 'checkbox',
			'role' => 'button',
			'id' => 'toctogglecheckbox',
			'class' => 'toctogglecheckbox',
			'style' => 'display:none'
		] );

		$tocTitle = $this->createElement( $doc,
			'div', [
				'class' => 'toctitle',
				'lang' => $lang->getHtmlCode(),
				'dir' => $lang->getDir()
			],
			$this->createElement(
				$doc,
				'h2',
				[ 'id' => 'mw-toc-heading' ],
				$title
			),
			$this->createElement(
				$doc,
				'span',
				[ 'class' => 'toctogglespan' ],
				$this->createElement(
					$doc,
					'label',
					[
						'class' => 'toctogglelabel',
						'for' => 'toctogglecheckbox'
					]
				)
			)
		);

		$tocContent = $this->generateTocContent( $tocData, $maxTocLevel, $doc );

		$toc = $this->createElement( $doc, 'div', [
			'id' => 'toc',
			'class' => 'toc',
			'role' => 'navigation',
			'aria-labelledby' => 'mw-toc-heading'
		], $toggle, $tocTitle, $tocContent );

		return $toc;
	}

	private function generateTocContent( TOCData $tocData, int $maxTocLevel, Document $doc ): Element {
		$top = null;
		$curr = null;
		$lastLevel = 0;
		foreach ( ( $tocData->getSections() ) as $section ) {
			$tocLevel = $section->tocLevel;
			if ( $tocLevel < $maxTocLevel ) {
				if ( $tocLevel > $lastLevel ) {
					$ul = $doc->createElement( 'ul' );
					if ( $curr ) {
						$curr->appendChild( $ul );
					}
					$curr = $ul;
					if ( $top === null ) {
						$top = $curr;
					}
				} elseif ( $tocLevel <= $lastLevel ) {
					if ( $curr && DOMUtils::nodeName( $curr ) === 'li' ) {
						$curr = DOMCompat::getParentElement( $curr );
					}
					if ( $lastLevel < $maxTocLevel ) {
						for ( $i = 0; $i < $lastLevel - $tocLevel; $i++ ) {
							// we're closing both a <ul> and a <li> here
							$curr = DOMCompat::getParentElement( $curr );
							// @phan-suppress-next-line PhanPluginDuplicateAdjacentStatement
							$curr = DOMCompat::getParentElement( $curr );
						}
					}
				}
				$classes = "toclevel-$tocLevel";

				// Parser.php used to suppress tocLine by setting $sectionindex to false.
				// In those circumstances, we can now encounter '' or a "T-" prefixed index
				// for when the section comes from templates.
				$sectionIndex = $section->index;
				$linkAnchor = $section->linkAnchor;
				$tocNumber = $section->number;
				$tocLine = $section->line;
				$fragTocLine = DOMUtils::parseHTMLToFragment( $doc, $tocLine );
				if ( $sectionIndex !== false && $sectionIndex !== '' && !str_starts_with( $sectionIndex, "T-" ) ) {
					$classes .= " tocsection-$sectionIndex";
				}
				$li = $this->createElement(
					$doc,
					'li',
					[ 'class' => $classes ],
					$this->createElement(
						$doc,
						'a',
						[ 'href' => "#$linkAnchor" ],
						$this->createElement(
							$doc,
							'span',
							[ 'class' => 'tocnumber' ],
							$tocNumber
						),
						' ',
						$this->createElement(
							$doc,
							'span',
							[ 'class' => 'toctext' ],
							$fragTocLine->hasChildNodes() ? $fragTocLine : ''
						)
					)
				);

				$curr->appendChild( $li );
				$curr = $li;
				$lastLevel = $tocLevel;
			}
		}
		return $top;
	}

	/**
	 * Extracts the userLanguage from the $options array, with a fallback on skin language and request
	 * context language
	 * @param array $options
	 * @return Language
	 */
	private function resolveUserLanguage( array $options ): Language {
		$userLang = $options['userLang'] ?? null;
		$skin = $options['skin'] ?? null;
		if ( ( !$userLang ) && $skin ) {
			// TODO: We probably don't need a full Skin option here
			$userLang = $skin->getLanguage();
		}
		if ( !$userLang ) {
			// T348853 passing either userLang or skin will be mandatory in the future
			$userLang = RequestContext::getMain()->getLanguage();
		}
		return $userLang;
	}
}
