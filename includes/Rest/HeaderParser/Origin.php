<?php

namespace MediaWiki\Rest\HeaderParser;

use Wikimedia\Assert\Assert;

/**
 * A class to assist with the parsing of Origin header according to the RFC 6454
 * @link https://tools.ietf.org/html/rfc6454#section-7
 * @since 1.36
 */
class Origin extends HeaderParserBase {

	public const HEADER_NAME = 'Origin';

	/** @var bool whether the origin was set to null */
	private $isNullOrigin;

	/** @var array List of specified origins */
	private $origins = [];

	/**
	 * Parse an Origin header list as returned by RequestInterface::getHeader().
	 *
	 * @param string[] $headerList
	 * @return self
	 */
	public static function parseHeaderList( array $headerList ) : self {
		$parser = new self( $headerList );
		$parser->execute();
		return $parser;
	}

	/**
	 * Whether the Origin header was explicitly set to `null`.
	 *
	 * @return bool
	 */
	public function isNullOrigin(): bool {
		return $this->isNullOrigin;
	}

	/**
	 * Whether the Origin header contains multiple origins.
	 *
	 * @return bool
	 */
	public function isMultiOrigin(): bool {
		return count( $this->getOriginList() ) > 1;
	}

	/**
	 * Get the list of origins.
	 *
	 * @return string[]
	 */
	public function getOriginList(): array {
		return $this->origins;
	}

	/**
	 * @return string
	 */
	public function getSingleOrigin(): string {
		Assert::precondition( !$this->isMultiOrigin(),
			'Cannot get single origin, header specifies multiple' );
		return $this->getOriginList()[0];
	}

	/**
	 * Check whether all the origins match at least one of the rules in $allowList.
	 *
	 * @param string[] $allowList
	 * @param string[] $excludeList
	 * @return bool
	 */
	public function match( array $allowList, array $excludeList ): bool {
		if ( $this->isNullOrigin() ) {
			return false;
		}

		foreach ( $this->getOriginList() as $origin ) {
			if ( !self::matchSingleOrigin( $origin, $allowList, $excludeList ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Checks whether the origin matches at list one of the provided rules in $allowList.
	 *
	 * @param string $origin
	 * @param array $allowList
	 * @param array $excludeList
	 * @return bool
	 */
	private static function matchSingleOrigin( string $origin, array $allowList, array $excludeList ): bool {
		foreach ( $allowList as $rule ) {
			if ( preg_match( self::wildcardToRegex( $rule ), $origin ) ) {
				// Rule matches, check exceptions
				foreach ( $excludeList as $exc ) {
					if ( preg_match( self::wildcardToRegex( $exc ), $origin ) ) {
						return false;
					}
				}

				return true;
			}
		}

		return false;
	}

	/**
	 * Private constructor. Use the public static functions for public access.
	 *
	 * @param string[] $input
	 */
	private function __construct( array $input ) {
		if ( count( $input ) !== 1 ) {
			$this->error( 'Only a single Origin header field allowed in HTTP request' );
		}
		$this->setInput( trim( $input[0] ) );
	}

	private function execute() {
		if ( $this->input === 'null' ) {
			$this->isNullOrigin = true;
		} else {
			$this->isNullOrigin = false;
			$this->origins = preg_split( '/\s+/', $this->input );
			if ( count( $this->origins ) === 0 ) {
				$this->error( 'Origin header must contain at least one origin' );
			}
		}
	}

	/**
	 * Helper function to convert wildcard string into a regex
	 * '*' => '.*?'
	 * '?' => '.'
	 *
	 * @param string $wildcard String with wildcards
	 * @return string Regular expression
	 */
	private static function wildcardToRegex( $wildcard ) {
		$wildcard = preg_quote( $wildcard, '/' );
		$wildcard = str_replace(
			[ '\*', '\?' ],
			[ '.*?', '.' ],
			$wildcard
		);

		return "/^https?:\/\/$wildcard$/";
	}
}
