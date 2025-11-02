<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Title\Title;
use Psr\Log\LoggerInterface;
use StatusValue;

/**
 * Verify summary and text do not match spam regexes
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class SpamRegexConstraint implements IEditConstraint {

	private string $match = '';

	/**
	 * @param LoggerInterface $logger for logging hits
	 * @param SpamChecker $spamChecker
	 * @param string $summary
	 * @param ?string $sectionHeading
	 * @param string $text
	 * @param string $reqIP for logging hits
	 * @param Title $title for logging hits
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
		private readonly SpamChecker $spamChecker,
		private readonly string $summary,
		private readonly ?string $sectionHeading,
		private readonly string $text,
		private readonly string $reqIP,
		private readonly Title $title,
	) {
	}

	public function checkConstraint(): string {
		$match = $this->spamChecker->checkSummary( $this->summary );
		if ( $match === false && $this->sectionHeading !== null ) {
			// If the section isn't new, the $this->sectionHeading is null
			$match = $this->spamChecker->checkContent( $this->sectionHeading );
		}
		if ( $match === false ) {
			$match = $this->spamChecker->checkContent( $this->text );
		}

		if ( $match === false ) {
			return self::CONSTRAINT_PASSED;
		}

		$this->match = $match;
		$this->logger->debug(
			'{ip} spam regex hit [[{title}]]: "{match}"',
			[
				'ip' => $this->reqIP,
				'title' => $this->title->getPrefixedDBkey(),
				'match' => str_replace( "\n", '', $match )
			]
		);
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->match !== '' ) {
			$match = str_replace( "\n", '', $this->match );
			$statusValue->fatal( 'spamprotectionmatch', $match );
			$statusValue->value = self::AS_SPAM_ERROR;
		}
		return $statusValue;
	}

	public function getMatch(): string {
		return $this->match;
	}

}
