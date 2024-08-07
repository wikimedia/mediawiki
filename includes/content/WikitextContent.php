<?php
/**
 * Content object for wiki text pages.
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
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

namespace MediaWiki\Content;

use Content;
use InvalidArgumentException;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Title\Title;

/**
 * Content object for wiki text pages.
 *
 * @newable
 * @ingroup Content
 */
class WikitextContent extends TextContent {

	/**
	 * @var string[] flags set by PST
	 */
	private $preSaveTransformFlags = [];

	/**
	 * @stable to call
	 *
	 * @param string $text
	 */
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @param string|int $sectionId
	 *
	 * @return Content|false|null
	 *
	 * @see Content::getSection()
	 */
	public function getSection( $sectionId ) {
		$text = $this->getText();
		$sect = MediaWikiServices::getInstance()->getParserFactory()->getInstance()
			->getSection( $text, $sectionId, false );

		if ( $sect === false ) {
			return false;
		} else {
			return new static( $sect );
		}
	}

	/**
	 * @param string|int|null|false $sectionId
	 * @param Content $with New section content, must have the same content model as $this.
	 * @param string $sectionTitle
	 *
	 * @return Content
	 *
	 * @see Content::replaceSection()
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		// @phan-suppress-previous-line PhanParamSignatureMismatch False positive
		$myModelId = $this->getModel();
		$sectionModelId = $with->getModel();

		if ( $sectionModelId != $myModelId ) {
			throw new InvalidArgumentException( "Incompatible content model for section: " .
				"document uses $myModelId but " .
				"section uses $sectionModelId." );
		}
		/** @var self $with $oldtext */
		'@phan-var self $with';

		$oldtext = $this->getText();
		$text = $with->getText();

		if ( strval( $sectionId ) === '' ) {
			return $with; # XXX: copy first?
		}

		if ( $sectionId === 'new' ) {
			# Inserting a new section
			$subject = strval( $sectionTitle ) !== '' ? wfMessage( 'newsectionheaderdefaultlevel' )
					->plaintextParams( $sectionTitle )->inContentLanguage()->text() . "\n\n" : '';
			$hookRunner = ( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) );
			if ( $hookRunner->onPlaceNewSection( $this, $oldtext, $subject, $text ) ) {
				$text = strlen( trim( $oldtext ) ) > 0
					? "{$oldtext}\n\n{$subject}{$text}"
					: "{$subject}{$text}";
			}
		} else {
			# Replacing an existing section; roll out the big guns
			$text = MediaWikiServices::getInstance()->getParserFactory()->getInstance()
				->replaceSection( $oldtext, $sectionId, $text );
		}

		$newContent = new static( $text );

		return $newContent;
	}

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended.
	 *
	 * @param string $header
	 *
	 * @return Content
	 */
	public function addSectionHeader( $header ) {
		$text = strval( $header ) !== '' ? wfMessage( 'newsectionheaderdefaultlevel' )
			->plaintextParams( $header )->inContentLanguage()->text() . "\n\n" : '';
		$text .= $this->getText();

		return new static( $text );
	}

	/**
	 * Extract the redirect target and the remaining text on the page.
	 *
	 * @since 1.23
	 * @deprecated since 1.41, use WikitextContentHandler::getRedirectTargetAndText
	 *
	 * @return array List of two elements: Title|null and string.
	 */
	public function getRedirectTargetAndText() {
		wfDeprecated( __METHOD__, '1.41' );

		$handler = $this->getContentHandler();
		[ $target, $content ] = $handler->extractRedirectTargetAndText( $this );

		return [ Title::castFromLinkTarget( $target ), $content->getText() ];
	}

	/**
	 * Implement redirect extraction for wikitext.
	 *
	 * @return Title|null
	 *
	 * @see Content::getRedirectTarget
	 */
	public function getRedirectTarget() {
		// TODO: The redirect target should be injected on construction.
		//       But that only works if the object is created by WikitextContentHandler.

		$handler = $this->getContentHandler();
		[ $target, ] = $handler->extractRedirectTargetAndText( $this );

		return Title::castFromLinkTarget( $target );
	}

	/**
	 * This implementation replaces the first link on the page with the given new target
	 * if this Content object is a redirect. Otherwise, this method returns $this.
	 *
	 * @since 1.21
	 *
	 * @param Title $target
	 *
	 * @return Content
	 *
	 * @see Content::updateRedirect()
	 */
	public function updateRedirect( Title $target ) {
		if ( !$this->isRedirect() ) {
			return $this;
		}

		# Fix the text
		# Remember that redirect pages can have categories, templates, etc.,
		# so the regex has to be fairly general
		$newText = preg_replace( '/ \[ \[  [^\]]*  \] \] /x',
			'[[' . $target->getFullText() . ']]',
			$this->getText(), 1 );

		return new static( $newText );
	}

	/**
	 * Returns true if this content is not a redirect, and this content's text
	 * is countable according to the criteria defined by $wgArticleCountMethod.
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out (default: null).
	 * @param Title|null $title Optional title, defaults to the title from the current main request.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		$articleCountMethod = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ArticleCountMethod );

		if ( $this->isRedirect() ) {
			return false;
		}

		if ( $articleCountMethod === 'link' ) {
			if ( $hasLinks === null ) { # not known, find out
				// @TODO: require an injected title
				if ( !$title ) {
					$context = RequestContext::getMain();
					$title = $context->getTitle();
				}
				$contentRenderer = MediaWikiServices::getInstance()->getContentRenderer();
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable getTitle does not return null here
				$po = $contentRenderer->getParserOutput( $this, $title, null, null, false );
				$links = $po->getLinks();
				$hasLinks = $links !== [];
			}

			return $hasLinks;
		}

		return true;
	}

	/**
	 * @param int $maxlength
	 *
	 * @return string
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		$truncatedtext = parent::getTextForSummary( $maxlength );

		# clean up unfinished links
		# XXX: make this optional? wasn't there in autosummary, but required for
		# deletion summary.
		$truncatedtext = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $truncatedtext );

		return $truncatedtext;
	}

	/**
	 * This implementation calls $word->match() on the this TextContent object's text.
	 *
	 * @param MagicWord $word
	 *
	 * @return bool
	 *
	 * @see Content::matchMagicWord()
	 */
	public function matchMagicWord( MagicWord $word ) {
		return $word->match( $this->getText() );
	}

	/**
	 * Records flags set by preSaveTransform
	 *
	 * @internal for use by WikitextContentHandler
	 *
	 * @param string[] $flags
	 */
	public function setPreSaveTransformFlags( array $flags ) {
		$this->preSaveTransformFlags = $flags;
	}

	/**
	 * Records flags set by preSaveTransform
	 *
	 * @internal for use by WikitextContentHandler
	 * @return string[]
	 */
	public function getPreSaveTransformFlags() {
		return $this->preSaveTransformFlags;
	}

	public function getContentHandler(): WikitextContentHandler {
		$handler = parent::getContentHandler();
		'@phan-var WikitextContentHandler $handler';

		return $handler;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( WikitextContent::class, 'WikitextContent' );
