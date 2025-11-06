<?php

namespace MediaWiki\Block;

use Generator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * Provides access to the wiki's autoblock exemption list.
 * @since 1.42
 */
class AutoblockExemptionList {
	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::AutoblockExemptions,
	];

	private ServiceOptions $options;
	private LoggerInterface $logger;
	/** Should be for the wiki's content language */
	private ITextFormatter $textFormatter;

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ITextFormatter $textFormatter
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->logger = $logger;
		$this->textFormatter = $textFormatter;
	}

	/** @return Generator<string> */
	private function getOnWikiExemptionList() {
		$list = $this->textFormatter->format(
			MessageValue::new( 'block-autoblock-exemptionlist' )
		);
		$lines = explode( "\n", $list );

		foreach ( $lines as $line ) {
			// List items only
			if ( !str_starts_with( $line, '*' ) ) {
				continue;
			}

			$wlEntry = substr( $line, 1 );
			$wlEntry = trim( $wlEntry );
			yield $wlEntry;
		}
	}

	/** @return Generator<string> */
	private function getExemptionList() {
		// @phan-suppress-next-line PhanTypeInvalidYieldFrom
		yield from $this->options->get( MainConfigNames::AutoblockExemptions );
		yield from $this->getOnWikiExemptionList();
	}

	/**
	 * Checks whether a given IP is on the autoblock exemption list.
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public function isExempt( $ip ) {
		$this->logger->debug( "Checking the autoblock exemption list.." );
		foreach ( $this->getExemptionList() as $wlEntry ) {
			$this->logger->debug( "Checking $ip against $wlEntry..." );

			// Is the IP in this range?
			if ( IPUtils::isInRange( $ip, $wlEntry ) ) {
				$this->logger->debug( " IP $ip matches $wlEntry, not autoblocking" );
				return true;
			} else {
				$this->logger->debug( " No match" );
			}
		}

		return false;
	}
}
