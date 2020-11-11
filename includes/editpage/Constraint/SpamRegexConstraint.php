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
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\SpamChecker;
use Psr\Log\LoggerInterface;
use StatusValue;
use Title;

/**
 * Verify summary and text do not match spam regexes
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class SpamRegexConstraint implements IEditConstraint {

	/** @var LoggerInterface */
	private $logger;

	/** @var SpamChecker */
	private $spamChecker;

	/** @var string */
	private $summary;

	/** @var string */
	private $sectionHeading;

	/** @var string */
	private $text;

	/** @var string */
	private $reqIP;

	/** @var Title */
	private $title;

	/** @var string */
	private $match = '';

	/**
	 * @param LoggerInterface $logger for logging hits
	 * @param SpamChecker $spamChecker
	 * @param string $summary
	 * @param string $section
	 * @param string $sectionHeading
	 * @param string $text
	 * @param string $reqIP for logging hits
	 * @param Title $title for logging hits
	 */
	public function __construct(
		LoggerInterface $logger,
		SpamChecker $spamChecker,
		string $summary,
		string $section,
		string $sectionHeading,
		string $text,
		string $reqIP,
		Title $title
	) {
		if ( $section == 'new' ) {
			// $wgSpamRegex is enforced on this new heading/summary because, unlike
			// regular summaries, it is added to the actual wikitext.
			// sectiontitle is only set if the API is used with `sectiontitle`, otherwise
			// the summary is used which comes from the API `summary` parameter or the
			// "Add Topic" user interface
			$sectionHeadingToCheck = ( $sectionHeading !== '' ? $sectionHeading : $summary );
		} else {
			// No section heading to check
			$sectionHeadingToCheck = '';
		}

		$this->logger = $logger;
		$this->spamChecker = $spamChecker;
		$this->summary = $summary;
		$this->sectionHeading = $sectionHeadingToCheck;
		$this->text = $text;
		$this->reqIP = $reqIP;
		$this->title = $title;
	}

	public function checkConstraint() : string {
		$match = $this->spamChecker->checkSummary( $this->summary );
		if ( $match === false ) {
			// $wgSpamRegex is enforced on this new heading/summary because, unlike
			// regular summaries, it is added to the actual wikitext.
			// EditPage has already determined, based on if this is the API with `sectiontitle`,
			// or action=edit, or the API with `summary`, what will be the section title.
			// If the section isn't new, the $this->sectionHeading is an empty string
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

	public function getLegacyStatus() : StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->match !== '' ) {
			$match = str_replace( "\n", '', $this->match );
			$statusValue->fatal( 'spamprotectionmatch', $match );
			$statusValue->value = self::AS_SPAM_ERROR;
		}
		return $statusValue;
	}

	/**
	 * @return string
	 */
	public function getMatch() : string {
		return $this->match;
	}

}
