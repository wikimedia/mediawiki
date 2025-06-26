<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Psr\Log\LoggerInterface;

/**
 * Adds debug info to the output
 * @internal
 */
class RenderDebugInfo extends ContentTextTransformStage {

	private HookRunner $hookRunner;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, HookContainer $hookContainer
	) {
		parent::__construct( $options, $logger );
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $options['includeDebugInfo'] ?? false;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$debugInfo = $this->debugInfo( $po );
		return $text . $debugInfo;
	}

	private function debugInfo( ParserOutput $po ): string {
		$text = '';

		$limitReportData = $po->getLimitReportData();
		// If nothing set it, we can't get it.
		if ( $limitReportData ) {
			$limitReport = "NewPP limit report\n";

			if ( array_key_exists( 'cachereport-origin', $limitReportData ) ) {
				$limitReport .= "Parsed by {$limitReportData['cachereport-origin']}\n";
			}

			if ( array_key_exists( 'cachereport-timestamp', $limitReportData ) ) {
				$limitReport .= "Cached time: {$limitReportData['cachereport-timestamp']}\n";
			}

			if ( array_key_exists( 'cachereport-ttl', $limitReportData ) ) {
				$limitReport .= "Cache expiry: {$limitReportData['cachereport-ttl']}\n";
			}

			if ( array_key_exists( 'cachereport-transientcontent', $limitReportData ) ) {
				$transient = $limitReportData['cachereport-transientcontent'] ? 'true' : 'false';
				$limitReport .= "Reduced expiry: $transient\n";
			}

			// TODO: flags should go into limit report too.
			$limitReport .= 'Complications: [' . implode( ', ', $po->getAllFlags() ) . "]\n";

			foreach ( $limitReportData as $key => $value ) {
				if ( in_array( $key, [
					'cachereport-origin',
					'cachereport-timestamp',
					'cachereport-ttl',
					'cachereport-transientcontent',
					'limitreport-timingprofile',
				] ) ) {
					// These entries have non-numeric parameters, and therefore are processed separately.
					continue;
				}

				// TODO inject MessageFormatter instead of Message::newFromSpecifier
				if ( $this->hookRunner->onParserLimitReportFormat(
					$key, $value, $limitReport, false, false )
				) {
					$keyMsg = Message::newFromSpecifier( $key )->inLanguage( 'en' )->useDatabase( false );
					$valueMsg = Message::newFromSpecifier( "$key-value" )
						->inLanguage( 'en' )->useDatabase( false );
					if ( !$valueMsg->exists() ) {
						$valueMsg = new RawMessage( '$1' );
					}
					if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
						$valueMsg->params( $value );
						$limitReport .= "{$keyMsg->text()}: {$valueMsg->text()}\n";
					}
				}
			}
			// Since we're not really outputting HTML, decode the entities and
			// then re-encode the things that need hiding inside HTML comments.
			$limitReport = htmlspecialchars_decode( $limitReport );

			// Sanitize for comment. Note '‐' in the replacement is U+2010,
			// which looks much like the problematic '-'.
			$limitReport = str_replace( [ '-', '&' ], [ '‐', '&amp;' ], $limitReport );
			$text = "\n<!-- \n$limitReport-->\n";

			$profileReport = $limitReportData['limitreport-timingprofile'] ?? null;
			if ( $profileReport ) {
				$text .= "<!--\nTransclusion expansion time report (%,ms,calls,template)\n";
				$text .= implode( "\n", $profileReport ) . "\n-->\n";
			}
		}

		if ( $po->getCacheMessage() ) {
			$text .= "\n<!-- " . $po->getCacheMessage() . "\n -->\n";
		}

		$parsoidVersion = $po->getExtensionData( 'core:parsoid-version' );
		if ( $parsoidVersion ) {
			$text .= "\n<!--Parsoid $parsoidVersion-->\n";
		}

		return $text;
	}
}
