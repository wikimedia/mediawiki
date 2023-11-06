<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Context\RequestContext;
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
 * Replace the section link placeholders by their proper value
 * @internal
 */
class HandleSectionLinks extends ContentTextTransformStage {
	private const EDITSECTION_REGEX = '#<mw:editsection page="(.*?)" section="(.*?)">(.*?)</mw:editsection>#s';

	private LoggerInterface $logger;
	private TitleFactory $titleFactory;

	public function __construct( LoggerInterface $logger, TitleFactory $titleFactory ) {
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return true;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		if (
			( $options['enableSectionEditLinks'] ?? true ) &&
			!$po->getOutputFlag( ParserOutputFlags::NO_SECTION_EDIT_LINKS )
		) {
			return $this->addSectionLinks( $text, $po, $options );
		} else {
			return preg_replace( self::EDITSECTION_REGEX, '', $text );
		}
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
