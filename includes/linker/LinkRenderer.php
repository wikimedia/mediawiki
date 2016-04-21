<?php
/**
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
 * @file
 * @license GPL-2.0+
 * @author Kunal Mehta <legoktm@member.fsf.org>
 */
namespace MediaWiki\Linker;

use DummyLinker;
use Hooks;
use Html;
use HtmlArmor;
use Linker;
use MediaWiki\MediaWikiServices;
use Sanitizer;
use Title;
use TitleFormatter;

/**
 * Class that generates HTML <a> links for pages.
 *
 * @since 1.28
 */
class LinkRenderer {

	/**
	 * Whether to force the pretty article path
	 *
	 * @var bool
	 */
	private $forceArticlePath = false;

	/**
	 * A PROTO_* constant or false
	 *
	 * @var string|bool|int
	 */
	private $expandUrls = false;

	/**
	 * Whether extra classes should be added
	 *
	 * @var bool
	 */
	private $noClasses = false;

	/**
	 * @var int
	 */
	private $stubThreshold = 0;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * Whether to run the legacy Linker hooks
	 *
	 * @var bool
	 */
	private $runLegacyBeginHook = true;

	/**
	 * @param TitleFormatter $titleFormatter
	 */
	public function __construct( TitleFormatter $titleFormatter ) {
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * @param bool $force
	 */
	public function setForceArticlePath( $force ) {
		$this->forceArticlePath = $force;
	}

	/**
	 * @return bool
	 */
	public function getForceArticlePath() {
		return $this->forceArticlePath;
	}

	/**
	 * @param string|bool|int $expand A PROTO_* constant or false
	 */
	public function setExpandURLs( $expand ) {
		$this->expandUrls = $expand;
	}

	/**
	 * @return string|bool|int a PROTO_* constant or false
	 */
	public function getExpandURLs() {
		return $this->expandUrls;
	}

	/**
	 * @param bool $no
	 */
	public function setNoClasses( $no ) {
		$this->noClasses = $no;
	}

	/**
	 * @return bool
	 */
	public function getNoClasses() {
		return $this->noClasses;
	}

	/**
	 * @param int $threshold
	 */
	public function setStubThreshold( $threshold ) {
		$this->stubThreshold = $threshold;
	}

	/**
	 * @return int
	 */
	public function getStubThreshold() {
		return $this->stubThreshold;
	}

	/**
	 * @param bool $run
	 */
	public function setRunLegacyBeginHook( $run ) {
		$this->runLegacyBeginHook = $run;
	}

	/**
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string
	 */
	public function makeLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		$title = Title::newFromLinkTarget( $target );
		if ( $title->isKnown() ) {
			return $this->makeKnownLink( $target, $text, $extraAttribs, $query );
		} else {
			return $this->makeBrokenLink( $target, $text, $extraAttribs, $query );
		}
	}

	/**
	 * Get the options in the legacy format
	 *
	 * @param bool $isKnown Whether the link is known or broken
	 * @return array
	 */
	private function getLegacyOptions( $isKnown ) {
		$options = [ 'stubThreshold' => $this->stubThreshold ];
		if ( $this->noClasses ) {
			$options[] = 'noclasses';
		}
		if ( $this->forceArticlePath ) {
			$options[] = 'forcearticlepath';
		}
		if ( $this->expandUrls === PROTO_HTTP ) {
			$options[] = 'http';
		} elseif ( $this->expandUrls === PROTO_HTTPS ) {
			$options[] = 'https';
		}

		$options[] = $isKnown ? 'known' : 'broken';

		return $options;
	}

	private function runBeginHook( LinkTarget $target, &$text, &$extraAttribs, &$query, $isKnown ) {
		$ret = null;
		if ( !Hooks::run( 'HtmlPageLinkRendererBegin',
			[ $this, $target, &$text, &$extraAttribs, &$query, &$ret ] )
		) {
			return $ret;
		}

		// Now run the legacy hook
		return $this->runLegacyBeginHook( $target, $text, $extraAttribs, $query, $isKnown );
	}

	private function runLegacyBeginHook( LinkTarget $target, &$text, &$extraAttribs, &$query,
		$isKnown
	) {
		if ( !$this->runLegacyBeginHook || !Hooks::isRegistered( 'LinkBegin' ) ) {
			// Disabled, or nothing registered
			return null;
		}

		$realOptions = $options = $this->getLegacyOptions( $isKnown );
		$ret = null;
		$dummy = new DummyLinker();
		$title = Title::newFromLinkTarget( $target );
		$realHtml = $html = HtmlArmor::getHtml( $text );
		if ( !Hooks::run( 'LinkBegin',
			[ $dummy, $title, &$html, &$extraAttribs, &$query, &$options, &$ret ] )
		) {
			return $ret;
		}

		if ( $html !== null && $html !== $realHtml ) {
			// &$html was modified, so re-armor it as $text
			$text = new HtmlArmor( $html );
		}

		// Check if they changed any of the options, hopefully not!
		if ( $options !== $realOptions ) {
			$factory = MediaWikiServices::getInstance()->getLinkRendererFactory();
			// They did, so create a separate instance and have that take over the rest
			$newRenderer = $factory->createFromLegacyOptions( $options );
			// Don't recurse the hook...
			$newRenderer->setRunLegacyBeginHook( false );
			if ( in_array( 'known', $options, true ) ) {
				return $newRenderer->makeKnownLink( $title, $text, $extraAttribs, $query );
			} elseif ( in_array( 'broken', $options, true ) ) {
				return $newRenderer->makeBrokenLink( $title, $text, $extraAttribs, $query );
			} else {
				return $newRenderer->makeLink( $title, $text, $extraAttribs, $query );
			}
		}

		return null;
	}

	/**
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string
	 */
	public function makeKnownLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		// Run begin hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query, true );
		if ( $ret !== null ) {
			return $ret;
		}
		$target = $this->normalizeTarget( $target );
		$url = $this->getLinkURL( $target, $query );
		$attribs = [];
		if ( !$this->noClasses ) {
			$classes = [];
			if ( $target->isExternal() ) {
				$classes[] = 'extiw';
			}
			$title = Title::newFromLinkTarget( $target );
			$colour = Linker::getLinkColour( $title, $this->stubThreshold );
			if ( $colour !== '' ) {
				$classes[] = $colour;
			}
			if ( $classes ) {
				$attribs['class'] = implode( ' ', $classes );
			}
		}

		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			$attribs['title'] = $prefixedText;
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $extraAttribs );

		if ( $text === null ) {
			$text = $this->getLinkText( $target );
		}

		return $this->buildAElement( $target, $text, $attribs, true );
	}

	/**
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string
	 */
	public function makeBrokenLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		// Run legacy hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query, false );
		if ( $ret !== null ) {
			return $ret;
		}

		# We don't want to include fragments for broken links, because they
		# generally make no sense.
		if ( $target->hasFragment() ) {
			$target = $target->createFragmentTarget( '' );
		}
		$target = $this->normalizeTarget( $target );

		if ( !isset( $query['action'] ) && $target->getNamespace() !== NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}

		$url = $this->getLinkURL( $target, $query );
		$attribs = $this->noClasses ? [] : [ 'class' => 'new' ];
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			// This ends up in parser cache!
			$attribs['title'] = wfMessage( 'red-link-title', $prefixedText )
				->inContentLanguage()
				->text();
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $extraAttribs );

		if ( $text === null ) {
			$text = $this->getLinkText( $target );
		}

		return $this->buildAElement( $target, $text, $attribs, false );
	}

	/**
	 * Builds the final <a> element
	 *
	 * @param LinkTarget $target
	 * @param string|HtmlArmor $text
	 * @param array $attribs
	 * @param bool $isKnown
	 * @return null|string
	 */
	private function buildAElement( LinkTarget $target, $text, array $attribs, $isKnown ) {
		$ret = null;
		if ( !Hooks::run( 'HtmlPageLinkRendererEnd',
			[ $this, $target, $isKnown, &$text, &$attribs, &$ret ] )
		) {
			return $ret;
		}

		$html = HtmlArmor::getHtml( $text );

		// Run legacy hook
		if ( Hooks::isRegistered( 'LinkEnd' ) ) {
			$dummy = new DummyLinker();
			$title = Title::newFromLinkTarget( $target );
			$options = $this->getLegacyOptions( $isKnown );
			if ( !Hooks::run( 'LinkEnd',
				[ $dummy, $title, $options, &$html, &$attribs, &$ret ] )
			) {
				return $ret;
			}
		}

		return Html::rawElement( 'a', $attribs, $html );
	}

	/**
	 * @param LinkTarget $target
	 * @return string non-escaped text
	 */
	private function getLinkText( LinkTarget $target ) {
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		// If the target is just a fragment, with no title, we return the fragment
		// text.  Otherwise, we return the title text itself.
		if ( $prefixedText === '' && $target->hasFragment() ) {
			return $target->getFragment();
		}

		return $prefixedText;
	}

	private function getLinkURL( LinkTarget $target, array $query = [] ) {
		// TODO: Use a LinkTargetResolver service instead of Title
		$title = Title::newFromLinkTarget( $target );
		$proto = $this->expandUrls !== false
			? $this->expandUrls
			: PROTO_RELATIVE;
		if ( $this->forceArticlePath ) {
			$realQuery = $query;
			$query = [];
		} else {
			$realQuery = [];
		}
		$url = $title->getLinkURL( $query, false, $proto );

		if ( $this->forceArticlePath && $realQuery ) {
			$url = wfAppendQuery( $url, $realQuery );
		}

		return $url;
	}

	/**
	 * Normalizes the provided target
	 *
	 * @todo move the code from Linker actually here
	 * @param LinkTarget $target
	 * @return LinkTarget
	 */
	private function normalizeTarget( LinkTarget $target ) {
		return Linker::normaliseSpecialPage( $target );
	}

	/**
	 * Merges two sets of attributes
	 *
	 * @param array $defaults
	 * @param array $attribs
	 *
	 * @return array
	 */
	private function mergeAttribs( $defaults, $attribs ) {
		if ( !$attribs ) {
			return $defaults;
		}
		# Merge the custom attribs with the default ones, and iterate
		# over that, deleting all "false" attributes.
		$ret = [];
		$merged = Sanitizer::mergeAttributes( $defaults, $attribs );
		foreach ( $merged as $key => $val ) {
			# A false value suppresses the attribute
			if ( $val !== false ) {
				$ret[$key] = $val;
			}
		}
		return $ret;
	}

}
