<?php
/**
 * Provide things related to namespaces.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;

/**
 * This is a utility class for dealing with namespaces that encodes all the "magic" behaviors of
 * them based on index.  The textual names of the namespaces are handled by Language.php.
 *
 * @since 1.34
 */
class NamespaceInfo {

	/**
	 * These namespaces should always be first-letter capitalized, now and
	 * forevermore. Historically, they could've probably been lowercased too,
	 * but some things are just too ingrained now. :)
	 */
	private const ALWAYS_CAPITALIZED_NAMESPACES = [ NS_SPECIAL, NS_USER, NS_MEDIAWIKI ];

	/** @var array<int,string>|null Canonical namespaces cache */
	private $canonicalNamespaces = null;

	/** @var array<string,int>|false Canonical namespaces index cache */
	private $namespaceIndexes = false;

	/** @var int[]|null Valid namespaces cache */
	private $validNamespaces = null;

	private readonly HookRunner $hookRunner;

	/**
	 * Definitions of the NS_ constants are in Defines.php
	 *
	 * @internal
	 */
	public const CANONICAL_NAMES = [
		NS_MEDIA            => 'Media',
		NS_SPECIAL          => 'Special',
		NS_MAIN             => '',
		NS_TALK             => 'Talk',
		NS_USER             => 'User',
		NS_USER_TALK        => 'User_talk',
		NS_PROJECT          => 'Project',
		NS_PROJECT_TALK     => 'Project_talk',
		NS_FILE             => 'File',
		NS_FILE_TALK        => 'File_talk',
		NS_MEDIAWIKI        => 'MediaWiki',
		NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
		NS_TEMPLATE         => 'Template',
		NS_TEMPLATE_TALK    => 'Template_talk',
		NS_HELP             => 'Help',
		NS_HELP_TALK        => 'Help_talk',
		NS_CATEGORY         => 'Category',
		NS_CATEGORY_TALK    => 'Category_talk',
	];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CanonicalNamespaceNames,
		MainConfigNames::CapitalLinkOverrides,
		MainConfigNames::CapitalLinks,
		MainConfigNames::ContentNamespaces,
		MainConfigNames::ExtraNamespaces,
		MainConfigNames::ExtraSignatureNamespaces,
		MainConfigNames::NamespaceContentModels,
		MainConfigNames::NamespacesWithSubpages,
		MainConfigNames::NonincludableNamespaces,
	];

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param array<int,string> $extensionNamespaces From other extension's "ExtensionNamespaces"
	 *  attributes
	 * @param int[] $immovableNamespaces From other extension's "ImmovableNamespaces" attributes
	 */
	public function __construct(
		private readonly ServiceOptions $options,
		HookContainer $hookContainer,
		private readonly array $extensionNamespaces,
		private readonly array $immovableNamespaces
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Throw an exception when trying to get the subject or talk page
	 * for a given namespace where it does not make sense.
	 * Special namespaces are defined in includes/Defines.php and have
	 * a value below 0 (ex: NS_SPECIAL = -1 , NS_MEDIA = -2)
	 *
	 * @param int $index
	 * @param string $method
	 */
	private function isMethodValidFor( $index, string $method ): void {
		if ( $index < NS_MAIN ) {
			throw new MWException( "$method does not make any sense for given namespace $index" );
		}
	}

	/**
	 * Throw if given index isn't an integer or integer-like string and so can't be a valid namespace.
	 *
	 * @param int|string $index
	 * @param string $method
	 *
	 * @throws InvalidArgumentException
	 * @return int Cleaned up namespace index
	 */
	private function makeValidNamespace( $index, $method ) {
		if ( !(
			is_int( $index )
			// Namespace index numbers as strings
			|| ctype_digit( $index )
			// Negative numbers as strings
			|| ( $index[0] === '-' && ctype_digit( substr( $index, 1 ) ) )
		) ) {
			throw new InvalidArgumentException(
				"$method called with non-integer (" . get_debug_type( $index ) . ") namespace '$index'"
			);
		}

		return intval( $index );
	}

	/**
	 * Can pages in the given namespace be moved?
	 *
	 * @param int $index Namespace index
	 */
	public function isMovable( $index ): bool {
		// Special and virtual namespaces can never be moved
		if ( $index < NS_MAIN ) {
			return false;
		}

		$result = !in_array( $index, $this->immovableNamespaces );

		/**
		 * @since 1.20
		 */
		$this->hookRunner->onNamespaceIsMovable( $index, $result );

		return $result;
	}

	/**
	 * Is the given namespace is a subject (non-talk) namespace?
	 *
	 * @param int $index Namespace index
	 */
	public function isSubject( $index ): bool {
		return !$this->isTalk( $index );
	}

	/**
	 * Is the given namespace a talk namespace?
	 *
	 * @param int $index Namespace index
	 */
	public function isTalk( $index ): bool {
		$index = $this->makeValidNamespace( $index, __METHOD__ );

		return $index > NS_MAIN
			&& $index % 2 === 1;
	}

	/**
	 * Get the talk namespace index for a given namespace
	 *
	 * @param int $index Namespace index
	 * @throws MWException if the given namespace doesn't have an associated talk namespace
	 *         (e.g. NS_SPECIAL).
	 */
	public function getTalk( $index ): int {
		$index = $this->makeValidNamespace( $index, __METHOD__ );

		$this->isMethodValidFor( $index, __METHOD__ );
		return $this->isTalk( $index )
			? $index
			: $index + 1;
	}

	/**
	 * Get a LinkTarget referring to the talk page of $target.
	 *
	 * @see canHaveTalkPage
	 * @param LinkTarget $target
	 * @return LinkTarget Talk page for $target
	 * @throws MWException if $target doesn't have talk pages, e.g. because it's in NS_SPECIAL,
	 *         because it's a relative section-only link, or it's an interwiki link.
	 */
	public function getTalkPage( LinkTarget $target ): LinkTarget {
		if ( $target->getText() === '' ) {
			throw new MWException( 'Can\'t determine talk page associated with relative section link' );
		}

		if ( $target->getInterwiki() !== '' ) {
			throw new MWException( 'Can\'t determine talk page associated with interwiki link' );
		}

		if ( $this->isTalk( $target->getNamespace() ) ) {
			return $target;
		}

		// NOTE: getTalk throws on bad namespaces!
		return new TitleValue( $this->getTalk( $target->getNamespace() ), $target->getDBkey() );
	}

	/**
	 * Can the title have a corresponding talk page?
	 *
	 * False for relative section-only links (with getText() === ''),
	 * interwiki links (with getInterwiki() !== ''), and pages in NS_SPECIAL.
	 *
	 * @see getTalkPage
	 *
	 * @return bool True if this title either is a talk page or can have a talk page associated.
	 */
	public function canHaveTalkPage( LinkTarget $target ): bool {
		return $target->getNamespace() >= NS_MAIN &&
			!$target->isExternal() &&
			$target->getText() !== '';
	}

	/**
	 * Get the subject namespace index for a given namespace
	 * Special namespaces (NS_MEDIA, NS_SPECIAL) are always the subject.
	 *
	 * @param int $index Namespace index
	 */
	public function getSubject( $index ): int {
		$index = $this->makeValidNamespace( $index, __METHOD__ );

		# Handle special namespaces
		if ( $index < NS_MAIN ) {
			return $index;
		}

		return $this->isTalk( $index )
			? $index - 1
			: $index;
	}

	/**
	 * @param LinkTarget $target
	 * @return LinkTarget Subject page for $target
	 */
	public function getSubjectPage( LinkTarget $target ): LinkTarget {
		if ( $this->isSubject( $target->getNamespace() ) ) {
			return $target;
		}
		return new TitleValue( $this->getSubject( $target->getNamespace() ), $target->getDBkey() );
	}

	/**
	 * Get the associated namespace.
	 * For talk namespaces, returns the subject (non-talk) namespace
	 * For subject (non-talk) namespaces, returns the talk namespace
	 *
	 * @param int $index Namespace index
	 * @throws MWException if called on a namespace that has no talk pages (e.g., NS_SPECIAL)
	 */
	public function getAssociated( $index ): int {
		$this->isMethodValidFor( $index, __METHOD__ );

		if ( $this->isSubject( $index ) ) {
			return $this->getTalk( $index );
		}
		return $this->getSubject( $index );
	}

	/**
	 * @param LinkTarget $target
	 * @return LinkTarget Talk page for $target if it's a subject page, subject page if it's a talk
	 *   page
	 * @throws MWException if $target's namespace doesn't have talk pages (e.g., NS_SPECIAL)
	 */
	public function getAssociatedPage( LinkTarget $target ): LinkTarget {
		if ( $target->getText() === '' ) {
			throw new MWException( 'Can\'t determine talk page associated with relative section link' );
		}

		if ( $target->getInterwiki() !== '' ) {
			throw new MWException( 'Can\'t determine talk page associated with interwiki link' );
		}

		return new TitleValue(
			$this->getAssociated( $target->getNamespace() ), $target->getDBkey() );
	}

	/**
	 * Returns whether the specified namespace exists
	 *
	 * @param int $index
	 */
	public function exists( $index ): bool {
		$nslist = $this->getCanonicalNamespaces();
		return isset( $nslist[$index] );
	}

	/**
	 * Returns whether the specified namespaces are the same namespace
	 *
	 * @note It's possible that in the future we may start using something
	 * other than just namespace indexes. Under that circumstance making use
	 * of this function rather than directly doing comparison will make
	 * sure that code will not potentially break.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 */
	public function equals( $ns1, $ns2 ): bool {
		return $ns1 == $ns2;
	}

	/**
	 * Returns whether the specified namespaces share the same subject.
	 * eg: NS_USER and NS_USER wil return true, as well
	 *     NS_USER and NS_USER_TALK will return true.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 */
	public function subjectEquals( $ns1, $ns2 ): bool {
		return $this->getSubject( $ns1 ) == $this->getSubject( $ns2 );
	}

	/**
	 * Returns array of all defined namespaces with their canonical
	 * (English) names.
	 *
	 * @return array<int,string>
	 */
	public function getCanonicalNamespaces(): array {
		if ( $this->canonicalNamespaces === null ) {
			$this->canonicalNamespaces =
				[ NS_MAIN => '' ] + $this->options->get( MainConfigNames::CanonicalNamespaceNames );
			$this->canonicalNamespaces += $this->extensionNamespaces;
			if ( is_array( $this->options->get( MainConfigNames::ExtraNamespaces ) ) ) {
				$this->canonicalNamespaces += $this->options->get( MainConfigNames::ExtraNamespaces );
			}
			$this->hookRunner->onCanonicalNamespaces( $this->canonicalNamespaces );
		}
		return $this->canonicalNamespaces;
	}

	/**
	 * Returns the canonical (English) name for a given index
	 *
	 * @param int $index Namespace index
	 * @return string|false If no canonical definition.
	 */
	public function getCanonicalName( $index ): string|false {
		$nslist = $this->getCanonicalNamespaces();
		return $nslist[$index] ?? false;
	}

	/**
	 * Returns the index for a given canonical name, or NULL
	 * The input *must* be converted to lower case first
	 *
	 * @param string $name Namespace name
	 */
	public function getCanonicalIndex( string $name ): ?int {
		if ( $this->namespaceIndexes === false ) {
			$this->namespaceIndexes = [];
			foreach ( $this->getCanonicalNamespaces() as $i => $text ) {
				$this->namespaceIndexes[strtolower( $text )] = $i;
			}
		}
		return $this->namespaceIndexes[$name] ?? null;
	}

	/**
	 * Returns an array of the namespaces (by integer id) that exist on the wiki. Used primarily by
	 * the API in help documentation. The array is sorted numerically and omits negative namespaces.
	 * @return int[]
	 */
	public function getValidNamespaces(): array {
		if ( $this->validNamespaces === null ) {
			$this->validNamespaces = [];
			foreach ( $this->getCanonicalNamespaces() as $ns => $_ ) {
				if ( $ns >= 0 ) {
					$this->validNamespaces[] = $ns;
				}
			}
			// T109137: sort numerically
			sort( $this->validNamespaces, SORT_NUMERIC );
		}

		return $this->validNamespaces;
	}

	/**
	 * Does this namespace ever have a talk namespace?
	 *
	 * @param int $index Namespace ID
	 * @return bool True if this namespace either is or has a corresponding talk namespace.
	 */
	public function hasTalkNamespace( $index ): bool {
		return $index >= NS_MAIN;
	}

	/**
	 * Does this namespace contain content, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @param int $index Index to check
	 */
	public function isContent( $index ): bool {
		return $index == NS_MAIN ||
			in_array( $index, $this->options->get( MainConfigNames::ContentNamespaces ) );
	}

	/**
	 * Might pages in this namespace require the use of the Signature button on
	 * the edit toolbar?
	 *
	 * @param int $index Index to check
	 */
	public function wantSignatures( $index ): bool {
		return $this->isTalk( $index ) ||
			in_array( $index, $this->options->get( MainConfigNames::ExtraSignatureNamespaces ) );
	}

	/**
	 * Can pages in a namespace be watched?
	 *
	 * @param int $index
	 */
	public function isWatchable( $index ): bool {
		return $index >= NS_MAIN;
	}

	/**
	 * Does the namespace allow subpages? Note that this refers to structured
	 * handling of subpages, and does not include SpecialPage subpage parameters.
	 *
	 * @param int $index Index to check
	 */
	public function hasSubpages( $index ): bool {
		return !empty( $this->options->get( MainConfigNames::NamespacesWithSubpages )[$index] );
	}

	/**
	 * Get a list of all namespace indices which are considered to contain content
	 * @return int[] Array of namespace indices
	 */
	public function getContentNamespaces(): array {
		$contentNamespaces = $this->options->get( MainConfigNames::ContentNamespaces );
		if ( !is_array( $contentNamespaces ) || $contentNamespaces === [] ) {
			return [ NS_MAIN ];
		} elseif ( !in_array( NS_MAIN, $contentNamespaces ) ) {
			// always force NS_MAIN to be part of array (to match the algorithm used by isContent)
			array_unshift( $contentNamespaces, NS_MAIN );
		}
		return $contentNamespaces;
	}

	/**
	 * List all namespace indices which are considered subject, aka not a talk
	 * or special namespace. See also NamespaceInfo::isSubject
	 *
	 * @return int[] Array of namespace indices
	 */
	public function getSubjectNamespaces(): array {
		return array_filter(
			$this->getValidNamespaces(),
			$this->isSubject( ... )
		);
	}

	/**
	 * List all namespace indices which are considered talks, aka not a subject
	 * or special namespace. See also NamespaceInfo::isTalk
	 *
	 * @return int[] Array of namespace indices
	 */
	public function getTalkNamespaces(): array {
		return array_filter(
			$this->getValidNamespaces(),
			$this->isTalk( ... )
		);
	}

	/**
	 * Is the namespace first-letter capitalized?
	 *
	 * @param int $index Index to check
	 */
	public function isCapitalized( $index ): bool {
		// Turn NS_MEDIA into NS_FILE
		$index = $index === NS_MEDIA ? NS_FILE : $index;

		// Make sure to get the subject of our namespace
		$index = $this->getSubject( $index );

		// Some namespaces are special and should always be upper case
		if ( in_array( $index, self::ALWAYS_CAPITALIZED_NAMESPACES ) ) {
			return true;
		}
		$overrides = $this->options->get( MainConfigNames::CapitalLinkOverrides );
		if ( isset( $overrides[$index] ) ) {
			// CapitalLinkOverrides is explicitly set
			return $overrides[$index];
		}
		// Default to the global setting
		return $this->options->get( MainConfigNames::CapitalLinks );
	}

	/**
	 * Does the namespace (potentially) have different aliases for different
	 * genders. Not all languages make a distinction here.
	 *
	 * @param int $index Index to check
	 */
	public function hasGenderDistinction( $index ): bool {
		return $index == NS_USER || $index == NS_USER_TALK;
	}

	/**
	 * It is not possible to use pages from this namespace as template?
	 *
	 * @param int $index Index to check
	 */
	public function isNonincludable( $index ): bool {
		$namespaces = $this->options->get( MainConfigNames::NonincludableNamespaces );
		return $namespaces && in_array( $index, $namespaces );
	}

	/**
	 * Get the default content model for a namespace
	 * This does not mean that all pages in that namespace have the model
	 *
	 * @note To determine the default model for a new page's main slot, or any slot in general,
	 * use SlotRoleHandler::getDefaultModel() together with SlotRoleRegistry::getRoleHandler().
	 *
	 * @param int $index Index to check
	 * @return null|string Default model name for the given namespace, if set
	 */
	public function getNamespaceContentModel( $index ): ?string {
		return $this->options->get( MainConfigNames::NamespaceContentModels )[$index] ?? null;
	}

	/**
	 * Returns the link type to be used for categories.
	 *
	 * This determines which section of a category page titles
	 * in the namespace will appear within.
	 *
	 * @param int $index Namespace index
	 * @return string One of 'subcat', 'file', 'page'
	 */
	public function getCategoryLinkType( $index ): string {
		$this->isMethodValidFor( $index, __METHOD__ );

		return match ( $index ) {
			NS_CATEGORY => 'subcat',
			NS_FILE => 'file',
			default => 'page',
		};
	}

	/**
	 * Retrieve the indexes for the namespaces defined by core.
	 *
	 * @since 1.34
	 *
	 * @return int[]
	 */
	public static function getCommonNamespaces(): array {
		return array_keys( self::CANONICAL_NAMES );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( NamespaceInfo::class, 'NamespaceInfo' );
