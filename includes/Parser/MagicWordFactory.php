<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Parser;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;

/**
 * Store information about magic words, and create/cache MagicWord objects.
 *
 * See docs/magicword.md.
 *
 * Possible future improvements:
 *   * Simultaneous searching for a number of magic words
 *   * $mObjects in shared memory
 *
 * @since 1.32
 * @ingroup Parser
 */
class MagicWordFactory {

	private bool $mVariableIDsInitialised = false;

	/** @var string[] */
	private array $mVariableIDs = [
		'!',
		'=',
		'articlepath',
		'basepagename',
		'basepagenamee',
		'bcp47',
		'cascadingsources',
		'contentlanguage',
		'contentmodel',
		'currentday',
		'currentday2',
		'currentdayname',
		'currentdow',
		'currenthour',
		'currentmonth',
		'currentmonth1',
		'currentmonthname',
		'currentmonthnamegen',
		'currentmonthabbrev',
		'currenttime',
		'currenttimestamp',
		'currentversion',
		'currentweek',
		'currentyear',
		'dir',
		'directionmark',
		'fullpagename',
		'fullpagenamee',
		'isbn',
		'language',
		'localday',
		'localday2',
		'localdayname',
		'localdow',
		'localhour',
		'localmonth',
		'localmonth1',
		'localmonthabbrev',
		'localmonthname',
		'localmonthnamegen',
		'localtime',
		'localtimestamp',
		'localweek',
		'localyear',
		'namespace',
		'namespacee',
		'namespacenumber',
		'numberofactiveusers',
		'numberofadmins',
		'numberofarticles',
		'numberofedits',
		'numberoffiles',
		'numberofpages',
		'numberofusers',
		'pageid',
		'pagelanguage',
		'pagename',
		'pagenamee',
		'revisionday',
		'revisionday2',
		'revisionid',
		'revisionmonth',
		'revisionmonth1',
		'revisionsize',
		'revisiontimestamp',
		'revisionyear',
		'revisionuser',
		'rootpagename',
		'rootpagenamee',
		'scriptpath',
		'server',
		'servername',
		'sitename',
		'stylepath',
		'subjectpagename',
		'subjectpagenamee',
		'subjectspace',
		'subjectspacee',
		'subpagename',
		'subpagenamee',
		'talkspace',
		'talkspacee',
		'talkpagename',
		'talkpagenamee',
		'userlanguage',
	];

	/** @var string[] */
	private array $mDoubleUnderscoreIDs = [
		'expectshortpage',
		'expectunusedcategory',
		'expectunusedtemplate',
		'forcetoc',
		'hiddencat',
		'index',
		'newsectionlink',
		'nocontentconvert',
		'noeditsection',
		'nogallery',
		'noindex',
		'nonewsectionlink',
		'notitleconvert',
		'notoc',
		'staticredirect',
		'toc',
	];

	/** @var array<string,MagicWord> */
	private array $mObjects = [];
	private ?MagicWordArray $mDoubleUnderscoreArray = null;

	private readonly HookRunner $hookRunner;

	/**
	 * @internal For ServiceWiring only
	 */
	public function __construct(
		private readonly Language $contLang,
		HookContainer $hookContainer,
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	public function getContentLanguage(): Language {
		return $this->contLang;
	}

	/**
	 * Get a MagicWord object for a given internal ID
	 *
	 * @param string $id The internal name of the magic word
	 * @return MagicWord
	 */
	public function get( $id ): MagicWord {
		if ( !isset( $this->mObjects[$id] ) ) {
			$mw = new MagicWord( null, [], false, $this->contLang );
			$mw->load( $id );
			$this->mObjects[$id] = $mw;
		}
		return $this->mObjects[$id];
	}

	/**
	 * Get an array of parser variable IDs
	 *
	 * @return string[]
	 */
	public function getVariableIDs(): array {
		if ( !$this->mVariableIDsInitialised ) {
			# Get variable IDs
			$this->hookRunner->onGetMagicVariableIDs( $this->mVariableIDs );
			$this->mVariableIDsInitialised = true;
		}
		return $this->mVariableIDs;
	}

	/**
	 * Get an array of parser substitution modifier IDs
	 *
	 * @return string[]
	 * @deprecated since 1.42, use {@see getSubstArray} instead
	 */
	public function getSubstIDs(): array {
		wfDeprecated( __METHOD__, '1.42' );
		return [ 'subst', 'safesubst' ];
	}

	/**
	 * @internal for use in {@see Parser::braceSubstitution} only
	 */
	public function getSubstArray(): MagicWordArray {
		return $this->newArray( [ 'subst', 'safesubst' ] );
	}

	/**
	 * Allow external reads of TTL array
	 *
	 * @param string $id
	 * @return int
	 * @deprecated Since 1.40
	 */
	public function getCacheTTL( $id ) {
		return -1;
	}

	/**
	 * Get a MagicWordArray of double-underscore entities
	 */
	public function getDoubleUnderscoreArray(): MagicWordArray {
		if ( $this->mDoubleUnderscoreArray === null ) {
			$this->hookRunner->onGetDoubleUnderscoreIDs( $this->mDoubleUnderscoreIDs );
			$this->mDoubleUnderscoreArray = $this->newArray( $this->mDoubleUnderscoreIDs );
		}
		return $this->mDoubleUnderscoreArray;
	}

	/**
	 * Get a new MagicWordArray with provided $names
	 *
	 * @param string[] $names
	 * @return MagicWordArray
	 */
	public function newArray( array $names = [] ): MagicWordArray {
		return new MagicWordArray( $names, $this );
	}
}
