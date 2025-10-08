<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Tidy\TidyDriverBase;
use Psr\Log\LoggerInterface;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * Inject table of contents (or empty string if there's no sections)
 * @internal
 */
class HandleTOCMarkers extends ContentTextTransformStage {

	private TidyDriverBase $tidy;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, TidyDriverBase $tidy
	) {
		parent::__construct( $options, $logger );
		$this->tidy = $tidy;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return !( $options['allowTOC'] ?? true ) || ( $options['injectTOC'] ?? true );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		if ( ( $options['allowTOC'] ?? true ) && ( $options['injectTOC'] ?? true ) ) {
			return $this->injectTOC( $text, $po, $options );
		}
		if ( !( $options['allowTOC'] ?? true ) ) {
			return Parser::replaceTableOfContentsMarker( $text, '' );
		}
		return $text;
	}

	private function injectTOC( string $text, ParserOutput $po, array $options ): string {
		$lang = $this->resolveUserLanguage( $options );
		$numSections = count( $po->getSections() );
		$tocData = $po->getTOCData();
		if ( $numSections === 0 ) {
			$toc = '';
		} else {
			$toc = self::generateTOC( $tocData, $lang );
			// TODO: This may no longer be needed since Ic0a805f29c928d0c2edf266ea045b0d29bb45a28
			$toc = $this->tidy->tidy( $toc, [ Sanitizer::class, 'armorFrenchSpaces' ] );
		}

		return Parser::replaceTableOfContentsMarker( $text, $toc );
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

	/**
	 * Add another level to the Table of Contents
	 *
	 * @return string
	 */
	private static function tocIndent() {
		return "\n<ul>\n";
	}

	/**
	 * Finish one or more sublevels on the Table of Contents
	 *
	 * @param int $level
	 * @return string
	 */
	private static function tocUnindent( $level ) {
		return "</li>\n" . str_repeat( "</ul>\n</li>\n", $level > 0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 *
	 * @param string $linkAnchor Identifier
	 * @param string $tocline Properly escaped HTML
	 * @param string $tocnumber Unescaped text
	 * @param int $level
	 * @param string|false $sectionIndex
	 * @return string
	 */
	private static function tocLine( $linkAnchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
		$classes = "toclevel-$level";

		// Parser.php used to suppress tocLine by setting $sectionindex to false.
		// In those circumstances, we can now encounter '' or a "T-" prefixed index
		// for when the section comes from templates.
		if ( $sectionIndex !== false && $sectionIndex !== '' && !str_starts_with( $sectionIndex, "T-" ) ) {
			$classes .= " tocsection-$sectionIndex";
		}

		// <li class="$classes"><a href="#$linkAnchor"><span class="tocnumber">
		// $tocnumber</span> <span class="toctext">$tocline</span></a>
		return Html::openElement( 'li', [ 'class' => $classes ] )
			. Html::rawElement( 'a',
				[ 'href' => "#$linkAnchor" ],
				Html::element( 'span', [ 'class' => 'tocnumber' ], $tocnumber )
					. ' '
					. Html::rawElement( 'span', [ 'class' => 'toctext' ], $tocline )
			);
	}

	/**
	 * End a Table Of Contents line.
	 * tocUnindent() will be used instead if we're ending a line below
	 * the new level.
	 * @return string
	 */
	private static function tocLineEnd() {
		return "</li>\n";
	}

	/**
	 * Wraps the TOC in a div with ARIA navigation role and provides the hide/collapse JavaScript.
	 *
	 * @param string $toc Html of the Table Of Contents
	 * @param Language|null $lang Language for the toc title, defaults to user language
	 * @return string Full html of the TOC
	 */
	private static function tocList( $toc, ?Language $lang = null ) {
		$lang ??= RequestContext::getMain()->getLanguage();

		$title = wfMessage( 'toc' )->inLanguage( $lang )->escaped();

		return '<div id="toc" class="toc" role="navigation" aria-labelledby="mw-toc-heading">'
			. Html::element( 'input', [
				'type' => 'checkbox',
				'role' => 'button',
				'id' => 'toctogglecheckbox',
				'class' => 'toctogglecheckbox',
				'style' => 'display:none',
			] )
			. Html::openElement( 'div', [
				'class' => 'toctitle',
				'lang' => $lang->getHtmlCode(),
				'dir' => $lang->getDir(),
			] )
			. '<h2 id="mw-toc-heading">' . $title . '</h2>'
			. '<span class="toctogglespan">'
			. Html::label( '', 'toctogglecheckbox', [
				'class' => 'toctogglelabel',
			] )
			. '</span>'
			. '</div>'
			. $toc
			. "</ul>\n</div>\n";
	}

	/**
	 * Generate a table of contents from a section tree.
	 *
	 * @param ?TOCData $tocData Return value of ParserOutput::getSections()
	 * @param Language|null $lang Language for the toc title, defaults to user language
	 * @param array $options
	 *   - 'maxtoclevel' Max TOC level to generate
	 * @return string HTML fragment
	 */
	private static function generateTOC( ?TOCData $tocData, ?Language $lang = null, array $options = [] ): string {
		$toc = '';
		$lastLevel = 0;
		$maxTocLevel = $options['maxtoclevel'] ?? null;
		if ( $maxTocLevel === null ) {
			// Use wiki-configured default
			$services = MediaWikiServices::getInstance();
			$config = $services->getMainConfig();
			$maxTocLevel = $config->get( MainConfigNames::MaxTocLevel );
		}
		foreach ( ( $tocData ? $tocData->getSections() : [] ) as $section ) {
			$tocLevel = $section->tocLevel;
			if ( $tocLevel < $maxTocLevel ) {
				if ( $tocLevel > $lastLevel ) {
					$toc .= self::tocIndent();
				} elseif ( $tocLevel < $lastLevel ) {
					if ( $lastLevel < $maxTocLevel ) {
						$toc .= self::tocUnindent(
							$lastLevel - $tocLevel );
					} else {
						$toc .= self::tocLineEnd();
					}
				} else {
					$toc .= self::tocLineEnd();
				}

				$toc .= self::tocLine( $section->linkAnchor,
					$section->line, $section->number,
					$tocLevel, $section->index );
				$lastLevel = $tocLevel;
			}
		}
		if ( $lastLevel < $maxTocLevel && $lastLevel > 0 ) {
			$toc .= self::tocUnindent( $lastLevel - 1 );
		}
		return self::tocList( $toc, $lang );
	}
}
