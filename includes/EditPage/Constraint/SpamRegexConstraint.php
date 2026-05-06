<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Page\PageReference;
use MediaWiki\PageEdit\PageEditStatus;
use Psr\Log\LoggerInterface;

/**
 * Verify summary and text do not match spam regexes
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class SpamRegexConstraint extends EditConstraint {

	private string $match = '';

	/**
	 * @param LoggerInterface $logger for logging hits
	 * @param SpamChecker $spamChecker
	 * @param string $summary
	 * @param ?string $sectionHeading
	 * @param string $text
	 * @param string $reqIP for logging hits
	 * @param PageReference $page for logging hits
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
		private readonly SpamChecker $spamChecker,
		private readonly string $summary,
		private readonly ?string $sectionHeading,
		private readonly string $text,
		private readonly string $reqIP,
		private readonly PageReference $page,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		$match = $this->spamChecker->checkSummary( $this->summary );
		if ( $match === false && $this->sectionHeading !== null ) {
			// If the section isn't new, the $this->sectionHeading is null
			$match = $this->spamChecker->checkContent( $this->sectionHeading );
		}
		if ( $match === false ) {
			$match = $this->spamChecker->checkContent( $this->text );
		}

		if ( $match !== false ) {
			$this->match = $match;
			$match = str_replace( "\n", '', $match );
			$this->logger->debug(
				'{ip} spam regex hit [[{title}]]: "{match}"',
				[
					'ip' => $this->reqIP,
					'title' => $this->page,
					'match' => $match
				]
			);
			return PageEditStatus::newFatal( 'spamprotectionmatch', $match )
				->setValue( self::AS_SPAM_ERROR );
		}

		return PageEditStatus::newGood();
	}

	public function getMatch(): string {
		return $this->match;
	}

}
