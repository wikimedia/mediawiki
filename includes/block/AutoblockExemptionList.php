<?php

namespace MediaWiki\Block;

use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * Provides access to the wiki's autoblock exemption list.
 * @since 1.42
 */
class AutoblockExemptionList {
	private LoggerInterface $logger;
	/** Should be for the wiki's content language */
	private ITextFormatter $textFormatter;

	public function __construct(
		LoggerInterface $logger,
		ITextFormatter $textFormatter
	) {
		$this->logger = $logger;
		$this->textFormatter = $textFormatter;
	}

	/**
	 * Checks whether a given IP is on the autoblock exemption list.
	 *
	 * @param string $ip The IP to check
	 * @return bool
	 */
	public function isExempt( $ip ) {
		$list = $this->textFormatter->format(
			MessageValue::new( 'block-autoblock-exemptionlist' )
		);
		$lines = explode( "\n", $list );
		$this->logger->debug( "Checking the autoblock exemption list.." );
		foreach ( $lines as $line ) {
			// List items only
			if ( !str_starts_with( $line, '*' ) ) {
				continue;
			}

			$wlEntry = substr( $line, 1 );
			$wlEntry = trim( $wlEntry );

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
