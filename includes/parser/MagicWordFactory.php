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

namespace MediaWiki\Parser;

use Language;
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
		'pagelanguage',
		'numberofadmins',
		'cascadingsources',
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
	 * @deprecated Since 1.40
	 */
	public function getCacheTTL( $id ) {
		return -1;
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
	public function newArray( array $names = [] ): MagicWordArray {
		return new MagicWordArray( $names, $this );
	}
}

/**
 * @deprecated since 1.40
 */
class_alias( MagicWordFactory::class, 'MagicWordFactory' );
