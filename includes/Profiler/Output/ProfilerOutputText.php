<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Adds profiler output to the HTTP response.
 *
 * The least sophisticated profiler output class possible, view source! :)
 *
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputText extends ProfilerOutput {
	/** @var float Min real time display threshold */
	private $thresholdMs;

	/** @var bool Whether to use visible text or a comment (for HTML responses) */
	private $visible;

	public function __construct( Profiler $collector, array $params ) {
		parent::__construct( $collector, $params );
		$this->thresholdMs = $params['thresholdMs'] ?? 1.0;
		$this->visible = $params['visible'] ?? false;
	}

	/** @inheritDoc */
	public function logsToOutput() {
		return true;
	}

	public function log( array $stats ) {
		$out = '';

		// Filter out really tiny entries
		$min = $this->thresholdMs;
		$stats = array_filter( $stats, static function ( $a ) use ( $min ) {
			return $a['real'] > $min;
		} );
		// Sort descending by time elapsed
		usort( $stats, static function ( $a, $b ) {
			return $b['real'] <=> $a['real'];
		} );

		array_walk( $stats,
			static function ( $item ) use ( &$out ) {
				$out .= sprintf( "%6.2f%% %3.3f %6d - %s\n",
					$item['%real'], $item['real'], $item['calls'], $item['name'] );
			}
		);

		$contentType = $this->collector->getContentType();
		if ( wfIsCLI() ) {
			print $this->formatHtmlComment( $out ) . "\n";
		} elseif ( $contentType === 'text/html' ) {
			if ( $this->visible ) {
				print '<pre>' . htmlspecialchars( $out ) . '</pre>';
			} else {
				print $this->formatHtmlComment( $out ) . "\n";
			}
		} elseif ( $contentType === 'text/javascript' || $contentType === 'text/css' ) {
			print "\n" . $this->formatBlockComment( $out ) . "\n";
		}
	}

	private function formatHtmlComment( string $text ): string {
		// Escape any closing "-->"
		return "<!--\n" . htmlspecialchars( $text ) . "\n-->";
	}

	private function formatBlockComment( string $text ): string {
		// Escape any closing "*/"
		$encText = str_replace( '*/', '* /', $text );
		return "/*\n$encText\n*/";
	}
}
