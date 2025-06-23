<?php

namespace MediaWiki\Settings\Source\Format;

use InvalidArgumentException;
use LogicException;
use Stringable;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;

class YamlFormat implements Stringable, SettingsFormat {

	public const PARSER_PHP_YAML = 'php-yaml';

	public const PARSER_SYMFONY = 'symfony';

	/** @var string[] */
	private $useParsers;

	/**
	 * @param string[] $useParsers which parsers to try in order.
	 */
	public function __construct( array $useParsers = [ self::PARSER_PHP_YAML, self::PARSER_SYMFONY ] ) {
		$this->useParsers = $useParsers;
	}

	public function decode( string $data ): array {
		foreach ( $this->useParsers as $parser ) {
			if ( self::isParserAvailable( $parser ) ) {
				return $this->parseWith( $parser, $data );
			}
		}
		throw new LogicException( 'No parser available' );
	}

	/**
	 * Check whether a specific YAML parser is available.
	 *
	 * @param string $parser one of the PARSER_* constants.
	 * @return bool
	 */
	public static function isParserAvailable( string $parser ): bool {
		switch ( $parser ) {
			case self::PARSER_PHP_YAML:
				return function_exists( 'yaml_parse' );
			case self::PARSER_SYMFONY:
				return true;
			default:
				throw new InvalidArgumentException( 'Unknown parser: ' . $parser );
		}
	}

	/**
	 * @param string $parser
	 * @param string $data
	 * @return array
	 */
	private function parseWith( string $parser, string $data ): array {
		switch ( $parser ) {
			case self::PARSER_PHP_YAML:
				return $this->parseWithPhp( $data );
			case self::PARSER_SYMFONY:
				return $this->parseWithSymfony( $data );
			default:
				throw new InvalidArgumentException( 'Unknown parser: ' . $parser );
		}
	}

	private function parseWithPhp( string $data ): array {
		$previousValue = ini_set( 'yaml.decode_php', false );
		try {
			$ndocs = 0;
			$result = AtEase::quietCall(
				'yaml_parse',
				$data,
				0,
				$ndocs,
				[
					/**
					 * Crash if provided YAML has PHP constants in it.
					 * We do not want to support that.
					 *
					 * @return never
					 */
					'!php/const' => static function (): never {
						throw new UnexpectedValueException(
							'PHP constants are not supported'
						);
					},
				]
			);
			if ( $result === false ) {
				throw new UnexpectedValueException( 'Failed to parse YAML' );
			}
			return $result;
		} finally {
			ini_set( 'yaml.decode_php', $previousValue );
		}
	}

	private function parseWithSymfony( string $data ): array {
		try {
			return Yaml::parse( $data, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE );
		} catch ( ParseException $e ) {
			throw new UnexpectedValueException(
				'Failed to parse YAML ' . $e->getMessage()
			);
		}
	}

	public static function supportsFileExtension( string $ext ): bool {
		$ext = strtolower( $ext );
		return $ext === 'yml' || $ext === 'yaml';
	}

	public function __toString() {
		return 'YAML';
	}
}
