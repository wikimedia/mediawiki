<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Context\RequestContext;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlHelper;
use MediaWiki\Linker\Linker;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use ParserOptions;
use Psr\Log\LoggerInterface;
use Skin;

/**
 * Add anchors and other heading formatting, and replace the section link placeholders.
 * @internal
 */
class HandleSectionLinks extends ContentTextTransformStage {
	private const EDITSECTION_REGEX = '#<mw:editsection page="(.*?)" section="(.*?)">(.*?)</mw:editsection>#s';
	private const HEADING_REGEX =
		'/<H(?P<level>[1-6])(?P<attrib>(?:[^\'">]*|"([^"]*)"|\'([^\']*)\')*>)(?P<header>[\s\S]*?)<\/H[1-6] *>/i';

	private LoggerInterface $logger;
	private TitleFactory $titleFactory;

	public function __construct( LoggerInterface $logger, TitleFactory $titleFactory ) {
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		$isParsoid = $options['isParsoidContent'] ?? false;
		return !$isParsoid;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$text = $this->replaceHeadings( $text );

		if (
			( $options['enableSectionEditLinks'] ?? true ) &&
			!$po->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS )
		) {
			return $this->addSectionLinks( $text, $po, $options );
		} else {
			return preg_replace( self::EDITSECTION_REGEX, '', $text );
		}
	}

	private function replaceHeadings( string $text ): string {
		return preg_replace_callback( self::HEADING_REGEX, function ( $m ) {
			// Parse attributes out of the <h#> tag. Do not actually use HtmlHelper's output,
			// because EDITSECTION_REGEX is sensitive to quotes in HTML serialization.
			$attrs = [];
			HtmlHelper::modifyElements(
				$m[0],
				static fn ( $node ) => in_array( $node->name, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ] ),
				static function ( $node ) use ( &$attrs ) {
					$attrs = $node->attrs->getValues();
					return $node;
				}
			);

			if ( !isset( $attrs['data-mw-anchor'] ) ) {
				return $m[0];
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
			}, $m['header'] );

			return Linker::makeHeadline(
				(int)$m['level'],
				Html::expandAttributes( $attrs ) . '>',
				$anchor,
				$contents,
				$editlink,
				$fallbackAnchor
			);
		}, $text );
	}

	private function addSectionLinks( string $text, ParserOutput $po, array $options ): string {
		$skin = $this->resolveSkin( $options );
		$titleText = $po->getTitleText();
		return preg_replace_callback( self::EDITSECTION_REGEX, function ( $m ) use ( $skin, $titleText ) {
			$editsectionPage = $this->titleFactory->newFromText( htmlspecialchars_decode( $m[1] ) );

			if ( !$editsectionPage instanceof Title ) {
				$this->logger
					->error( 'AddSectionLinks::transform: bad title in editsection placeholder', [
						'placeholder' => $m[0],
						'editsectionPage' => $m[1],
						'titletext' => $titleText,
						'phab' => 'T261347',
					] );

				return '';
			}
			$editsectionSection = htmlspecialchars_decode( $m[2] );
			$editsectionContent = Sanitizer::decodeCharReferences( $m[3] );
			return $skin->doEditSectionLink( $editsectionPage, $editsectionSection, $editsectionContent,
				$skin->getLanguage() );
		}, $text );
	}

	/**
	 * Extracts the skin from the $options array, with a fallback on request context skin
	 * @param array $options
	 * @return Skin
	 */
	private function resolveSkin( array $options ): Skin {
		$skin = $options[ 'skin' ] ?? null;
		if ( !$skin ) {
			// T348853 passing $skin will be mandatory in the future
			$skin = RequestContext::getMain()->getSkin();
		}
		return $skin;
	}
}
