<?php

namespace MediaWiki\OutputTransform\Stages;

use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlHelper;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\TitleFactory;
use Psr\Log\LoggerInterface;

/**
 * Add anchors and other heading formatting, and replace the section link placeholders.
 * @internal
 */
class HandleSectionLinks extends ContentTextTransformStage {
	private const EDITSECTION_REGEX = '#<mw:editsection page="(.*?)" section="(.*?)">(.*?)</mw:editsection>#s';
	private const HEADING_REGEX =
		'/<H(?P<level>[1-6])(?P<attrib>(?:[^\'">]*|"([^"]*)"|\'([^\']*)\')*>)(?P<header>[\s\S]*?)<\/H[1-6] *>/i';

	private TitleFactory $titleFactory;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, TitleFactory $titleFactory
	) {
		parent::__construct( $options, $logger );
		$this->titleFactory = $titleFactory;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		$isParsoid = $options['isParsoidContent'] ?? false;
		return !$isParsoid;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$text = $this->replaceHeadings( $text, $options );

		if (
			( $options['enableSectionEditLinks'] ?? true ) &&
			!$po->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS )
		) {
			return $this->addSectionLinks( $text, $po, $options );
		} else {
			return preg_replace( self::EDITSECTION_REGEX, '', $text );
		}
	}

	/**
	 * Check if the heading has attributes that can only be added using HTML syntax.
	 */
	private function isHtmlHeading( array $attrs ): bool {
		foreach ( $attrs as $name => $value ) {
			if ( !Sanitizer::isReservedDataAttribute( $name ) ) {
				return true;
			}
		}
		return false;
	}

	private function replaceHeadings( string $text, array $options ): string {
		$needToCheckExistingWrappers = preg_match( '/class="[^"]*\bmw-heading\b[^"]*"/', $text );

		return preg_replace_callback( self::HEADING_REGEX, function ( $m ) use (
			$needToCheckExistingWrappers, $text
		) {
			// Parse attributes out of the <h#> tag. Do not actually use HtmlHelper's output,
			// because EDITSECTION_REGEX is sensitive to quotes in HTML serialization.
			$attrs = [];
			HtmlHelper::modifyElements(
				$m[0][0],
				static fn ( $node ) => in_array( $node->name, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ] ),
				static function ( $node ) use ( &$attrs ) {
					$attrs = $node->attrs->getValues();
					return $node;
				}
			);

			if ( !isset( $attrs['data-mw-anchor'] ) ) {
				return $m[0][0];
			}

			$anchor = $attrs['data-mw-anchor'];
			$fallbackAnchor = $attrs['data-mw-fallback-anchor'] ?? false;
			unset( $attrs['data-mw-anchor'] );
			unset( $attrs['data-mw-fallback-anchor'] );

			// Split the heading content from the section edit link placeholder
			$editlink = '';
			$contents = preg_replace_callback( self::EDITSECTION_REGEX, static function ( $mm ) use ( &$editlink ) {
				$editlink = $mm[0];
				return '';
			}, $m['header'][0] );

			$wrapperType = 'mwheading';

			// Do not add another wrapper if an existing wrapper if present.
			// This is to support DiscussionTools adding wrappers itself.
			// TODO: This is obviously bad and unreliable. One day, this code will use DOM transforms,
			// and can just check ->parentNode like in HandleParsoidSectionLinks.
			if ( $needToCheckExistingWrappers ) {
				$textBeforeMatch = substr( $text, 0, $m[0][1] );
				$openOffset = strrpos( $textBeforeMatch, '<div class="mw-heading' );
				if ( $openOffset !== false ) {
					$closeOffset = strpos( $textBeforeMatch, '</div>', $openOffset );
					if ( $closeOffset === false ) {
						// Looks like we're already inside a wrapper
						$wrapperType = 'none';
					}
				}
			}

			if ( $this->isHtmlHeading( $attrs ) ) {
				// This is a <h#> tag with attributes added using HTML syntax.
				// Mark it with a class to make them easier to distinguish (T68637).
				Html::addClass( $attrs['class'], 'mw-html-heading' );

				// Do not add the wrapper if the heading has attributes added using HTML syntax (T353489).
				// In this case it's also guaranteed that there's no edit link, so we don't need wrappers.
				$wrapperType = 'none';
			}

			return $this->makeHeading(
				(int)$m['level'][0],
				$attrs,
				$anchor,
				$contents,
				$editlink,
				$fallbackAnchor,
				$wrapperType
			);
		}, $text, -1, $count, PREG_OFFSET_CAPTURE );
	}

	/**
	 * @param int $level The level of the headline (1-6)
	 * @param array $attrs HTML attributes
	 * @param string $anchor The anchor to give the headline (the bit after the #)
	 * @param string $html HTML for the text of the header
	 * @param string $link HTML to add for the section edit link
	 * @param string|false $fallbackAnchor A second, optional anchor to give for
	 *   backward compatibility (false to omit)
	 * @param string $wrapperType 'mwheading' or 'none'
	 * @return string HTML headline
	 */
	private function makeHeading( $level, $attrs, $anchor, $html,
		$link, $fallbackAnchor, string $wrapperType
	) {
		$anchorEscaped = htmlspecialchars( $anchor, ENT_COMPAT );
		$idAttr = " id=\"$anchorEscaped\"";
		if ( isset( $attrs['id'] ) ) {
			$idAttr = '';
		}
		$fallback = '';
		if ( $fallbackAnchor !== false && $fallbackAnchor !== $anchor ) {
			$fallbackAnchor = htmlspecialchars( $fallbackAnchor, ENT_COMPAT );
			$fallback = "<span id=\"$fallbackAnchor\"></span>";
		}

		switch ( $wrapperType ) {
			case 'mwheading':
				return "<div class=\"mw-heading mw-heading$level\">"
					. "<h$level$idAttr" . Html::expandAttributes( $attrs ) . ">$fallback$html</h$level>"
					. $link
					. "</div>";
			case 'none':
				return "<h$level$idAttr" . Html::expandAttributes( $attrs ) . ">$fallback$html</h$level>"
					. $link;
			default:
				throw new LogicException( "Bad wrapper type: $wrapperType" );
		}
	}

	private function addSectionLinks( string $text, ParserOutput $po, array $options ): string {
		$skin = $this->resolveSkin( $options );
		if ( !$skin ) {
			// Should be unreachable
			return $text;
		}
		$titleText = $po->getTitleText();
		return preg_replace_callback( self::EDITSECTION_REGEX, function ( $m ) use ( $skin, $titleText ) {
			$editsectionPage = $this->titleFactory->newFromTextThrow( htmlspecialchars_decode( $m[1] ) );
			$editsectionSection = htmlspecialchars_decode( $m[2] );
			$editsectionContent = Sanitizer::decodeCharReferences( $m[3] );
			return $skin->doEditSectionLink( $editsectionPage, $editsectionSection, $editsectionContent,
				$skin->getLanguage() );
		}, $text );
	}

	/**
	 * Extracts the skin from the $options array, with a fallback on request context skin
	 * @param array $options
	 * @return ?Skin
	 */
	private function resolveSkin( array $options ): ?Skin {
		$skin = $options[ 'skin' ] ?? null;
		if ( !$skin ) {
			if ( defined( 'MW_NO_SESSION' ) ) {
				return null;
			}
			// T348853 passing $skin will be mandatory in the future
			$skin = RequestContext::getMain()->getSkin();
		}
		return $skin;
	}
}
