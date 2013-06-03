<?php
/**
 * Utilitie for stuff related to RDFa handling.
 *
 * @author Daniel Friesen
 * @file
 */

namespace MediaWiki\RDFa;
use \InvalidArgumentException;
use \Sanitizer;

/**
 * @author Daniel Friesen
 * @class
 */
class Utils {

	protected static $rdfaAttrRules = array(
		'SafeCURIEorCURIEorIRI',
		'TERMorCURIEorAbsIRI',
		'TERMorCURIEorAbsIRIs',
	);

	protected static $rdfaAttrRuleMap = array(
		# A single safe CURIE, CURIE, absolute IRI, or relative IRI
		'about'    => 'SafeCURIEorCURIEorIRI',
		'resource' => 'SafeCURIEorCURIEorIRI',
		# A single TERM, CURIE, or absolute IRI
		'datatype' => 'TERMorCURIEorAbsIRI',
		# Multiple space separated TERMs, CURIEs, or absolute IRIs
		'property' => 'TERMorCURIEorAbsIRIs',
		'rel'      => 'TERMorCURIEorAbsIRIs',
		'rev'      => 'TERMorCURIEorAbsIRIs',
		'typeof'   => 'TERMorCURIEorAbsIRIs',
	);


	private static function xmlNameChars() {
		static $xmlNameStartChar = '_a-zA-Z\x{C0}-\x{D6}\x{D8}-\x{F6}\x{F8}-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}'; # : is removed
		static $xmlNameChar = null;
		if ( is_null( $xmlNameChar ) ) {
			$xmlNameChar = '-.0-9\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}' . $xmlNameStartChar;
		}
		return array( $xmlNameStartChar, $xmlNameChar );
	}

	/**
	 * Return a [] character bracket expression that can be use in a pcre
	 * pattern with a u modifier to match a valid RDFa prefix.
	 *
	 * @return string The pattern to include in the regular expression
	 */
	public static function prefixCharsPattern() {
		static $prefixChars = null;
		if ( is_null( $prefixChars ) ) {
			# RDFa prefix; RDFa defines this based on xml's NameStartChar and NameChar
			list( $xmlNameStartChar, $xmlNameChar ) = self::xmlNameChars();
			$prefixChars = "[{$xmlNameStartChar}][{$xmlNameChar}]*";
		}
		return $prefixChars;
	}

	/**
	 * Return a [] character bracket expression that can be use in a pcre
	 * pattern with a u modifier to match a valid RDFa term.
	 *
	 * @return string The pattern to include in the regular expression
	 */
	public static function termCharsPattern() {
		static $termChars = null;
		if ( is_null( $termChars ) ) {
			list( $xmlNameStartChar, $xmlNameChar ) = self::xmlNameChars();
			$termChars = "[{$xmlNameStartChar}][{$xmlNameChar}\\/]*";
		}
		return $termChars;
	}

	/**
	 * Helper function to build an %RDFa 1.1 prefix="" attribute.
	 * Accepts an array with %RDFa prefixes to include in the attribute.
	 *
	 * Prefixes as the array keys and IRIs as the array values.
	 * Then builds the value of the attribute. Returning null if no
	 * prexies are defines.
	 *
	 * @param array $prefixMap The prefix mappings to add
	 * @return string|null The value for the prefix="" attr
	 */
	public static function buildPrefixAttribute( $prefixMap ) {
		$prefixes = array();
		foreach ( $prefixMap as $prefix => $uri ) {
			$prefixes[] = "{$prefix}: {$uri}";
		}
		if ( count( $prefixes ) > 0 ) {
			return implode( ' ', $prefixes );
		}
		return null;
	}

	/**
	 * Sanitize attributes accepting CURIEs like about and rel (not
	 * href, src, content, or inlist).
	 *
	 * @param string $attrOrRule Either a RDFa rule like
	 *   TERMorCURIEorAbsIRIs or an attribute name to map to one of those.
	 * @param string $value The value of the attribute to sanitize.
	 */
	public static function sanitizeAttr( $attrOrRule, $value ) {
		$rule = array_key_exists( $attrOrRule, self::$rdfaAttrRuleMap )
			# Map attribute to rule
			? self::$rdfaAttrRuleMap[$attrOrRule]
			# Argument was a rule
			: $attrOrRule;
		# Make sure a valid rule was used
		if ( !in_array( $rule, self::$rdfaAttrRules ) ) {
			throw new InvalidArgumentException( "$rule is not a valid rule for an RDFa attribute." );
		}

		# Shortcut empty attributes
		if ( is_null( $value ) || $value === false || ( is_string( $value ) && trim( $value ) === "" ) ) {
			return $value;
		}

		$originalNames = preg_split( '/\s+/', trim( $value ) );

		# TERMorCURIEorAbsIRIs is the only whitespace separated list type the others are single items
		# For single items, if multiple are found consider the value invalid and return false
		if ( $rule !== 'TERMorCURIEorAbsIRIs' && count( $originalNames ) > 1 ) {
			return null;
		}

		// Patterns for use in tests
		$rdfaPrefixChars = self::prefixCharsPattern();
		$rdfaTermChars = self::termCharsPattern();
		$hrefExp = '/^(' . wfUrlProtocols() . ')[^\s]+$/';

		// Quick lookups for whether the rule allows something
		$safeCurieAllowed = ( $rule === 'SafeCURIEorCURIEorIRI' );
		$relativesAllowed = ( $rule === 'SafeCURIEorCURIEorIRI' );
		$termAllowed = ( $rule === 'TERMorCURIEorAbsIRI' || $rule === 'TERMorCURIEorAbsIRIs' );

		$resultNames = array();
		foreach ( $originalNames as $name ) {
			# Match an absolute IRI using our whitelist, we don't support relative IRIs
			if ( preg_match( $hrefExp, $name ) ) {
				$resultNames[] = $name;
				continue;
			}

			# Match a fragment if relative IRIs are supported.
			# While normal relative and /path urls are not properly stable in MW fragments are.
			# So we don't support relative IRIs but we'll let fragments pass through.
			# Provided it's passed through our escapeId like id="" is.
			if ( $relativesAllowed && preg_match( "/#(.*)/uA", $name, $m ) ) {
				$resultNames[] = '#' . Sanitizer::escapeId( $m[1], 'noninitial' );
				continue;
			}

			# Match a CURIE
			if ( preg_match( "/({$rdfaPrefixChars})?:.*/uA", $name ) ) {
				# Excessive paranoia, anywhere we're allowed to use a safe CURIE convert
				# the CURIEs we get into safe CURIEs.
				if ( $safeCurieAllowed ) {
					$name = '[' . $name . ']';
				}
				$resultNames[] = $name;
				continue;
			}

			# Match a safe CURIE if allowed in this attr
			if ( $safeCurieAllowed && preg_match( "/\[({$rdfaPrefixChars})?:[^\]]*\]/uA", $name ) ) {
				$resultNames[] = $name;
				continue;
			}

			# Match a TERM if allowed in this attr
			if ( $termAllowed && preg_match( "/{$rdfaTermChars}/uA", $name ) ) {
				# Convert terms into CURIEs with an empty (default) prefix. These work the same as terms
				# as long as a "local default vocabulary" is defined (by vocab="" or the host language) or
				# there are no "local term mappings" defined by the host language (HTML+RDFa 1.1 doesn't define any).
				# http://www.w3.org/TR/rdfa-core/#P_term
				$name = ':' . $name;
				$resultNames[] = $name;
				continue;
			}

			# Relative IRIs and invalid items are discarded
		}

		# If the list is empty after sanitizing it then drop the attribute
		# But not if the attribute allows relative IRIs. In which case ""
		# implies "This current IRI". Which is needed for some graphs to work.
		if ( !$relativesAllowed && count( $resultNames ) === 0 ) {
			return null;
		}

		return implode( ' ', $resultNames );
	}

}
