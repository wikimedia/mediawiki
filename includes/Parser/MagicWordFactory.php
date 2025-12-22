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
		'currentmonth',
		'currentmonth1',
		'currentmonthname',
		'currentmonthnamegen',
		'currentmonthabbrev',
		'currentday',
		'currentday2',
		'currentdayname',
		'currentyear',
		'currenttime',
		'currenthour',
		'localmonth',
		'localmonth1',
		'localmonthname',
		'localmonthnamegen',
		'localmonthabbrev',
		'localday',
		'localday2',
		'localdayname',
		'localyear',
		'localtime',
		'localhour',
		'numberofarticles',
		'numberoffiles',
		'numberofedits',
		'articlepath',
		'pageid',
		'sitename',
		'server',
		'servername',
		'scriptpath',
		'stylepath',
		'pagename',
		'pagenamee',
		'fullpagename',
		'fullpagenamee',
		'namespace',
		'namespacee',
		'namespacenumber',
		'currentweek',
		'currentdow',
		'localweek',
		'localdow',
		'revisionid',
		'revisionday',
		'revisionday2',
		'revisionmonth',
		'revisionmonth1',
		'revisionyear',
		'revisiontimestamp',
		'revisionuser',
		'revisionsize',
		'subpagename',
		'subpagenamee',
		'talkspace',
		'talkspacee',
		'subjectspace',
		'subjectspacee',
		'talkpagename',
		'talkpagenamee',
		'subjectpagename',
		'subjectpagenamee',
		'numberofusers',
		'numberofactiveusers',
		'numberofpages',
		'currentversion',
		'rootpagename',
		'rootpagenamee',
		'basepagename',
		'basepagenamee',
		'currenttimestamp',
		'localtimestamp',
		'directionmark',
		'contentlanguage',
		'userlanguage',
		'pagelanguage',
		'numberofadmins',
		'cascadingsources',
		'bcp47',
		'contentmodel',
		'dir',
		'language',
		'isbn',
	];

	/** @var string[] */
	private array $mDoubleUnderscoreIDs = [
		'notoc',
		'nogallery',
		'forcetoc',
		'toc',
		'noeditsection',
		'newsectionlink',
		'nonewsectionlink',
		'hiddencat',
		'expectunusedcategory',
		'expectunusedtemplate',
		'index',
		'noindex',
		'staticredirect',
		'notitleconvert',
		'nocontentconvert',
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
			$this->hookRunner->onMagicWordwgVariableIDs( $this->mVariableIDs );
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
