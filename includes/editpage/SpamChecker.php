<?php

namespace MediaWiki\EditPage;

/**
 * Service to check if text (either content or a summary) qualifies as spam
 *
 * Text qualifies as spam if it matches the global $wgSpamRegex
 * Summaries qualify as spam if they match the global $wgSummarySpamRegex
 *
 * @author DannyS712
 * @since 1.35
 */
class SpamChecker {

	/** @var string[] */
	private $spamRegex;

	/** @var string[] */
	private $summaryRegex;

	/**
	 * @param string[] $spamRegex
	 * @param string[] $summaryRegex
	 */
	public function __construct( $spamRegex, $summaryRegex ) {
		$this->spamRegex = $spamRegex;
		$this->summaryRegex = $summaryRegex;
	}

	/**
	 * Check whether content text is considered spam
	 *
	 * @param string $text
	 * @return bool|string Matching string or false
	 */
	public function checkContent( string $text ) {
		return self::checkInternal( $text, $this->spamRegex );
	}

	/**
	 * Check whether summary text is considered spam
	 *
	 * @param string $summary
	 * @return bool|string Matching string or false
	 */
	public function checkSummary( string $summary ) {
		return self::checkInternal( $summary, $this->summaryRegex );
	}

	/**
	 * @param string $text
	 * @param array $regexes
	 * @return bool|string
	 */
	private static function checkInternal( string $text, array $regexes ) {
		foreach ( $regexes as $regex ) {
			$matches = [];
			if ( preg_match( $regex, $text, $matches ) ) {
				return $matches[0];
			}
		}
		return false;
	}
}
