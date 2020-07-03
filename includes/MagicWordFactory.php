<?php
/**
 * See docs/magicword.md.
 *
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
 * @ingroup Parser
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;

/**
 * A factory that stores information about MagicWords, and creates them on demand with caching.
 *
 * Possible future improvements:
 *   * Simultaneous searching for a number of magic words
 *   * $mObjects in shared memory
 *
 * @since 1.32
 * @ingroup Parser
 */
class MagicWordFactory {
	/** #@- */

	/** @var bool */
	private $mVariableIDsInitialised = false;

	/** @var string[] */
	private $mVariableIDs = [
		'!',
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
		'pagelanguage',
		'numberofadmins',
		'cascadingsources',
	];

	/** Array of caching hints for ParserCache
	 * @var array [ string => int ]
	 */
	private $mCacheTTLs = [
		'currentmonth' => 86400,
		'currentmonth1' => 86400,
		'currentmonthname' => 86400,
		'currentmonthnamegen' => 86400,
		'currentmonthabbrev' => 86400,
		'currentday' => 3600,
		'currentday2' => 3600,
		'currentdayname' => 3600,
		'currentyear' => 86400,
		'currenttime' => 3600,
		'currenthour' => 3600,
		'localmonth' => 86400,
		'localmonth1' => 86400,
		'localmonthname' => 86400,
		'localmonthnamegen' => 86400,
		'localmonthabbrev' => 86400,
		'localday' => 3600,
		'localday2' => 3600,
		'localdayname' => 3600,
		'localyear' => 86400,
		'localtime' => 3600,
		'localhour' => 3600,
		'numberofarticles' => 3600,
		'numberoffiles' => 3600,
		'numberofedits' => 3600,
		'currentweek' => 3600,
		'currentdow' => 3600,
		'localweek' => 3600,
		'localdow' => 3600,
		'numberofusers' => 3600,
		'numberofactiveusers' => 3600,
		'numberofpages' => 3600,
		'currentversion' => 86400,
		'currenttimestamp' => 3600,
		'localtimestamp' => 3600,
		'pagesinnamespace' => 3600,
		'numberofadmins' => 3600,
		'numberingroup' => 3600,
	];

	/** @var string[] */
	private $mDoubleUnderscoreIDs = [
		'notoc',
		'nogallery',
		'forcetoc',
		'toc',
		'noeditsection',
		'newsectionlink',
		'nonewsectionlink',
		'hiddencat',
		'expectunusedcategory',
		'index',
		'noindex',
		'staticredirect',
		'notitleconvert',
		'nocontentconvert',
	];

	/** @var string[] */
	private $mSubstIDs = [
		'subst',
		'safesubst',
	];

	/** @var array [ string => MagicWord ] */
	private $mObjects = [];

	/** @var MagicWordArray */
	private $mDoubleUnderscoreArray = null;

	/** @var Language */
	private $contLang;

	/** @var HookRunner */
	private $hookRunner;

	/** #@- */

	/**
	 * @param Language $contLang Content language
	 * @param HookContainer $hookContainer
	 */
	public function __construct( Language $contLang, HookContainer $hookContainer ) {
		$this->contLang = $contLang;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	public function getContentLanguage() {
		return $this->contLang;
	}

	/**
	 * Factory: creates an object representing an ID
	 *
	 * @param string $id The internal name of the magic word
	 *
	 * @return MagicWord
	 */
	public function get( $id ) {
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
	public function getVariableIDs() {
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
	 * @return string[]
	 */
	public function getSubstIDs() {
		return $this->mSubstIDs;
	}

	/**
	 * Allow external reads of TTL array
	 *
	 * @param string $id
	 * @return int
	 */
	public function getCacheTTL( $id ) {
		if ( array_key_exists( $id, $this->mCacheTTLs ) ) {
			return $this->mCacheTTLs[$id];
		} else {
			return -1;
		}
	}

	/**
	 * Get a MagicWordArray of double-underscore entities
	 *
	 * @return MagicWordArray
	 */
	public function getDoubleUnderscoreArray() {
		if ( $this->mDoubleUnderscoreArray === null ) {
			$this->hookRunner->onGetDoubleUnderscoreIDs( $this->mDoubleUnderscoreIDs );
			$this->mDoubleUnderscoreArray = $this->newArray( $this->mDoubleUnderscoreIDs );
		}
		return $this->mDoubleUnderscoreArray;
	}

	/**
	 * Get a new MagicWordArray with provided $names
	 *
	 * @param array $names
	 * @return MagicWordArray
	 */
	public function newArray( array $names = [] ) : MagicWordArray {
		return new MagicWordArray( $names, $this );
	}
}
