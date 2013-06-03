<?php
/**
 * Classes implementing methods of working with d%RDFa prefixes.
 *
 * @author Daniel Friesen
 * @file
 */

namespace MediaWiki\RDFa;
use \ArrayUtils;

/**
 * A class that implements an %RDFa prefix context.
 * Used for dynamically registering prefixes for a
 * shared prefix="".
 *
 * @author Daniel Friesen
 * @class
 */
class PrefixContext {
	

	/**
	 * The parent PrefixContext if there is one.
	 * @var PrefixContext
	 */
	protected $parent;

	/**
	 * The immediate context's mapping of prefix
	 * strings to Prefix instances.
	 * @var array
	 */
	protected $prefixMap;

	/**
	 * The immediate context's mapping of IRIs
	 * to a list of prefix strings registered
	 * for that IRI in the immediate context.
	 * @var array
	 */
	protected $iriMap;

	/**
	 * A list of prefix strings that have been returned by this instance's {@link PrefixContext::prefix}.
	 * Whether they are immediately registered or not.
	 *
	 * This is used to prevent {@link PrefixContext::prefix} from overriding a prefix defined on a
	 * parent context when someone has already used it from this context.
	 * @var array
	 */
	protected $usedPrefixes;

	/**
	 * Create a prefix context.
	 *
	 * @param PrefixContext $parent An optional parent context to inherit from.
	 */
	public function __construct( PrefixContext $parent = null ) {
		$this->parent = $parent;
		$this->prefixMap = array();
		$this->iriMap = array();
		$this->usedPrefixes = array();
	}

	/**
	 * Force a prefix to be set on a context.
	 * This is for internal use only, use {@link PrefixContext::prefix}
	 * instead of this in actual code.
	 *
	 * @param string $prefix The prefix text
	 * @param string $iri The IRI string
	 * @return Prefix The prefix instance that was created
	 * @see {@link PrefixContext::prefix}
	 */
	protected function set( $prefix, $iri ) {
		# Remove old prexies from the IRI map
		if ( array_key_exists( $prefix, $this->prefixMap ) ) {
			$oldP = $this->prefixMap[$prefix];
			ArrayUtils::setRemove( $this->iriMap[$oldP->iri()], $oldP->prefix() );
			if ( count( $this->iriMap[$oldP->iri()] ) === 0 ) {
				unset( $this->iriMap[$oldP->iri()] );
			}
		}

		# Set prefix mapping
		$p = new Prefix( $prefix, $iri );
		$this->prefixMap[$prefix] = $p;

		# Add an iri to prefix mapping
		if ( !array_key_exists( $iri, $this->iriMap ) ) {
			$this->iriMap[$iri] = array();
		}
		ArrayUtils::setAdd( $this->iriMap[$iri], $prefix );

		return $p;
	}

	/**
	 * Return a prefix object for a prefix string which may be set on this context
	 * or one of it's parent contexts if defined. This is used if you want to try
	 * expanding a CURIE you happen to have on hand.
     *
     * If you wish to instead create a CURIE yourself you should use {@link PrefixContext::prefix}
	 * instead of assuming a prefix was defined somewhere.
	 *
	 * @param string $prefix The prefix text
	 * @return Prefix|null The prefix instance if set, otherwise null
	 */
	public function get( $prefix ) {
		if ( array_key_exists( $prefix, $this->prefixMap ) ) {
			return $this->prefixMap[$prefix];
		}
		if ( $this->parent ) {
			return $this->parent->get( $prefix );
		}
		return null;
	}

	/**
	 * Return an associative array mapping prefixes
	 * to IRIs in the immediate context.
	 *
	 * @return array The mapping array
	 */
	public function prefixMap() {
		$prefixes = array();
		foreach ( $this->prefixMap as $p ) {
			$prefixes[$p->prefix()] = $p->iri();
		}
		return $prefixes;
	}

	/**
	 * Return the value of an %RDFa 1.1 prefix="" attribute
	 * for the immediate context.
	 *
	 * @return string|null The value for the prefix="" attr
	 */
	public function prefixAttribute() {
		return Utils::buildPrefixAttribute( $this-> prefixMap() );
	}

	/**
	 * Function for registering and returning prefixes on a context.
	 * Whenever you want to use a %RDFa prefix in some code you should setup a context or grab the
	 * most relevant one and use this method to get a Prefix instance.
	 *
	 * Do \b NOT rely on the prefix you pass to this method being the same as the actual prefix
	 * that gets registered. Create all of your CURIEs, etc... using methods on the returned Prefix.
	 *
	 * Behavior in various circumstances:
	 * - A prefix for $iri already exists:
	 *   - Return that prefix if keep is false.
	 *   - Try to set the prefix anyways if keep is true.
	 * - The prefix is already in use on the same context:
	 *   - Use a {$prefix}{#} pattern p1, p2, etc...
	 *   - If a prefix for $iri already exists (ie: we're here cause keep is true) use that prefix.
	 * - The prefix is already in use on a parent context for a different IRI:
	 *   - Set a prefix on this context that'll override that prefix in this context.
	 *
	 * Special rules:
	 *   - If a previous call to prefix returned something for some prefix string future calls to
	 *     prefix should never set a different prefix on it. Even if it was a parent prefix that
	 *     was returned and the rules would usually have an overriding prefix set.
	 *
	 * @param string $prefix The preferred prefix text if the IRI isn't already registered with one
	 * @param string $iri The IRI to register/return a prefix for
	 * @param boolean $keepPrefixIfPossible Try to keep the same prefix text as $prefix
	 *                                      even if it means registering a duplicate prefix.
	 * @return Prefix The registered/returned prefix.
	 */
	public function prefix( $prefix, $iri, $keepPrefixIfPossible = false ) {
		# Helper code to register the use of a prefix string while we return the prefix.
		$_usedPrefixes = &$this->usedPrefixes;
		$_used = function( $p ) use( &$_usedPrefixes ) {
			ArrayUtils::setAdd( $_usedPrefixes, $p->prefix() );
			return $p;
		};

		# If this or any parent contexts use the right IRI for this prefix, return it
		$p = $this->get( $prefix );
		if ( $p && $p->iri() === $iri ) {
			return $_used( $p );
		}

		if ( !$keepPrefixIfPossible || array_key_exists( $prefix, $this->prefixMap ) ) {
			# We don't need to keep the same prefix

			# Look for uses of this IRI in the context chain
			$inuse = array(); # Prefixes that are already used for different IRIs
			$c = $this;
			while ( $c ) {
				if ( array_key_exists( $iri, $c->iriMap ) ) {
					$prefixes = array_diff( $c->iriMap[$iri], $inuse );
					if ( count( $prefixes ) > 0 ) {
						return $_used( $c->prefixMap[$prefixes[0]] );
					}
				}

				$inuse = array_merge( $inuse, array_keys( $c->prefixMap ) );
				$c = $c->parent;
			}
		}

		# This prefix is already in use and we don't have a different one registered for this IRI.
		# In addition to registered prefixes consider prefixes we've previously returned as used.
		if ( array_key_exists( $prefix, $this->prefixMap ) || in_array( $prefix, $this->usedPrefixes ) ) {
			for ( $n = 1; ; ++$n ) {
				$newPrefix = "{$prefix}{$n}";
				if ( !array_key_exists( $newPrefix, $this->prefixMap ) && !in_array( $newPrefix, $this->usedPrefixes ) ) {
					# Break from the loop when we've found the first unused prefix
					break;
				}
			}

			return $_used( $this->set( $newPrefix, $iri ) );
		}

		# 
		return $_used( $this->set( $prefix, $iri ) );
	}

}

/**
 * A class that implements an %RDFa prefix.
 *
 * @author Daniel Friesen
 * @see PrefixContext
 * @class
 */
class Prefix {

	protected $prefix, $iri;

	/**
	 * Create a prefix instance
	 *
	 * @param string $prefix The prefix string
	 * @param string $iri The IRI text
	 */
	public function __construct( $prefix, $iri ) {
		$this->prefix = $prefix;
		$this->iri = $iri;
	}

	/**
	 * Return the prefix string
	 *
	 * @return string The prefix
	 */
	public function prefix() {
		return $this->prefix;
	}

	/**
	 * Return the IRI text
	 *
	 * @return string The IRI
	 */
	public function iri() {
		return $this->iri;
	}

	/**
	 * Return CURIE text for a name
	 *
	 * @param string $name The name string
	 * @return string The CURIE
	 */
	public function curie( $name ) {
		return "{$this->prefix}:{$name}";
	}

	/**
	 * Return Safe CURIE text for a name
	 *
	 * @param string $name The name string
	 * @return string The Safe CURIE
	 */
	public function safeCurie( $name ) {
		return "[{$this->prefix}:{$name}]";
	}

	/**
	 * Expand name text into a full IRI
	 *
	 * @param string $name The name string
	 * @return string The expanded IRI
	 */
	public function expand( $name ) {
		return $this->iri . $name;
	}

}
