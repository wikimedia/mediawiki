<?php
/**
 * Implements Special:Allmessages
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
 * @ingroup SpecialPage
 */

/**
 * Use this special page to get a list of the MediaWiki system messages.
 *
 * @file
 * @ingroup SpecialPage
 */
class SpecialAllmessages extends SpecialPage {
	/**
	 * @var AllmessagesTablePager
	 */
	protected $table;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Allmessages' );
	}

	/**
	 * Show the special page
	 *
	 * @param $par Mixed: parameter passed to the page or null
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$this->setHeaders();

		global $wgUseDatabaseMessages;
		if ( !$wgUseDatabaseMessages ) {
			$out->addWikiMsg( 'allmessagesnotsupportedDB' );

			return;
		} else {
			$this->outputHeader( 'allmessagestext' );
		}

		$out->addModuleStyles( 'mediawiki.special' );

		$this->table = new AllmessagesTablePager(
			$this,
			array(),
			wfGetLangObj( $request->getVal( 'lang', $par ) )
		);

		$this->langcode = $this->table->lang->getCode();

		$out->addHTML( $this->table->buildForm() .
			$this->table->getNavigationBar() .
			$this->table->getBody() .
			$this->table->getNavigationBar() );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}

/**
 * Use TablePager for prettified output. We have to pretend that we're
 * getting data from a table when in fact not all of it comes from the database.
 */
class AllmessagesTablePager extends TablePager {
	protected $filter, $prefix, $langcode, $displayPrefix;

	public $mLimitsShown;

	/**
	 * @var Language
	 */
	public $lang;

	/**
	 * @var null|bool
	 */
	public $custom;

	function __construct( $page, $conds, $langObj = null ) {
		parent::__construct( $page->getContext() );
		$this->mIndexField = 'am_title';
		$this->mPage = $page;
		$this->mConds = $conds;
		$this->mDefaultDirection = true; // always sort ascending
		$this->mLimitsShown = array( 20, 50, 100, 250, 500, 5000 );

		global $wgContLang;

		$this->talk = $this->msg( 'talkpagelinktext' )->escaped();

		$this->lang = ( $langObj ? $langObj : $wgContLang );
		$this->langcode = $this->lang->getCode();
		$this->foreign = $this->langcode != $wgContLang->getCode();

		$request = $this->getRequest();

		$this->filter = $request->getVal( 'filter', 'all' );
		if ( $this->filter === 'all' ) {
			$this->custom = null; // So won't match in either case
		} else {
			$this->custom = ( $this->filter == 'unmodified' );
		}

		$prefix = $this->getLanguage()->ucfirst( $request->getVal( 'prefix', '' ) );
		$prefix = $prefix != '' ? Title::makeTitleSafe( NS_MEDIAWIKI, $request->getVal( 'prefix', null ) ) : null;
		if ( $prefix !== null ) {
			$this->displayPrefix = $prefix->getDBkey();
			$this->prefix = '/^' . preg_quote( $this->displayPrefix ) . '/i';
		} else {
			$this->displayPrefix = false;
			$this->prefix = false;
		}

		// The suffix that may be needed for message names if we're in a
		// different language (eg [[MediaWiki:Foo/fr]]: $suffix = '/fr'
		if ( $this->foreign ) {
			$this->suffix = '/' . $this->langcode;
		} else {
			$this->suffix = '';
		}
	}

	function buildForm() {
		global $wgScript;

		$attrs = array( 'id' => 'mw-allmessages-form-lang', 'name' => 'lang' );
		$msg = wfMessage( 'allmessages-language' );
		$langSelect = Xml::languageSelector( $this->langcode, false, null, $attrs, $msg );

		$out = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-allmessages-form' ) ) .
			Xml::fieldset( $this->msg( 'allmessages-filter-legend' )->text() ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::openElement( 'table', array( 'class' => 'mw-allmessages-table' ) ) . "\n" .
			'<tr>
				<td class="mw-label">' .
			Xml::label( $this->msg( 'allmessages-prefix' )->text(), 'mw-allmessages-form-prefix' ) .
			"</td>\n
			<td class=\"mw-input\">" .
			Xml::input( 'prefix', 20, str_replace( '_', ' ', $this->displayPrefix ), array( 'id' => 'mw-allmessages-form-prefix' ) ) .
			"</td>\n
			</tr>
			<tr>\n
			<td class='mw-label'>" .
			$this->msg( 'allmessages-filter' )->escaped() .
			"</td>\n
				<td class='mw-input'>" .
			Xml::radioLabel( $this->msg( 'allmessages-filter-unmodified' )->text(),
				'filter',
				'unmodified',
				'mw-allmessages-form-filter-unmodified',
				( $this->filter == 'unmodified' )
			) .
			Xml::radioLabel( $this->msg( 'allmessages-filter-all' )->text(),
				'filter',
				'all',
				'mw-allmessages-form-filter-all',
				( $this->filter == 'all' )
			) .
			Xml::radioLabel( $this->msg( 'allmessages-filter-modified' )->text(),
				'filter',
				'modified',
				'mw-allmessages-form-filter-modified',
				( $this->filter == 'modified' )
			) .
			"</td>\n
			</tr>
			<tr>\n
				<td class=\"mw-label\">" . $langSelect[0] . "</td>\n
				<td class=\"mw-input\">" . $langSelect[1] . "</td>\n
			</tr>" .

			'<tr>
				<td class="mw-label">' .
			Xml::label( $this->msg( 'table_pager_limit_label' )->text(), 'mw-table_pager_limit_label' ) .
			'</td>
			<td class="mw-input">' .
			$this->getLimitSelect() .
			'</td>
			<tr>
				<td></td>
				<td>' .
			Xml::submitButton( $this->msg( 'allmessages-filter-submit' )->text() ) .
			"</td>\n
			</tr>" .

			Xml::closeElement( 'table' ) .
			$this->getHiddenFields( array( 'title', 'prefix', 'filter', 'lang', 'limit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

		return $out;
	}

	function getAllMessages( $descending ) {
		wfProfileIn( __METHOD__ );
		$messageNames = Language::getLocalisationCache()->getSubitemList( 'en', 'messages' );
		if ( $descending ) {
			rsort( $messageNames );
		} else {
			asort( $messageNames );
		}

		// Normalise message names so they look like page titles
		$messageNames = array_map( array( $this->lang, 'ucfirst' ), $messageNames );

		wfProfileOut( __METHOD__ );

		return $messageNames;
	}

	/**
	 * Determine which of the MediaWiki and MediaWiki_talk namespace pages exist.
	 * Returns array( 'pages' => ..., 'talks' => ... ), where the subarrays have
	 * an entry for each existing page, with the key being the message name and
	 * value arbitrary.
	 *
	 * @param array $messageNames
	 * @param string $langcode What language code
	 * @param bool $foreign Whether the $langcode is not the content language
	 * @return array: a 'pages' and 'talks' array with the keys of existing pages
	 */
	public static function getCustomisedStatuses( $messageNames, $langcode = 'en', $foreign = false ) {
		// FIXME: This function should be moved to Language:: or something.
		wfProfileIn( __METHOD__ . '-db' );

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page',
			array( 'page_namespace', 'page_title' ),
			array( 'page_namespace' => array( NS_MEDIAWIKI, NS_MEDIAWIKI_TALK ) ),
			__METHOD__,
			array( 'USE INDEX' => 'name_title' )
		);
		$xNames = array_flip( $messageNames );

		$pageFlags = $talkFlags = array();

		foreach ( $res as $s ) {
			$exists = false;
			if ( $foreign ) {
				$title = explode( '/', $s->page_title );
				if ( count( $title ) === 2 && $langcode == $title[1]
					&& isset( $xNames[$title[0]] )
				) {
					$exists = $title[0];
				}
			} elseif ( isset( $xNames[$s->page_title] ) ) {
				$exists = $s->page_title;
			}
			if ( $exists && $s->page_namespace == NS_MEDIAWIKI ) {
				$pageFlags[$exists] = true;
			} elseif ( $exists && $s->page_namespace == NS_MEDIAWIKI_TALK ) {
				$talkFlags[$exists] = true;
			}
		}

		wfProfileOut( __METHOD__ . '-db' );

		return array( 'pages' => $pageFlags, 'talks' => $talkFlags );
	}

	/**
	 *  This function normally does a database query to get the results; we need
	 * to make a pretend result using a FakeResultWrapper.
	 * @param string $offset
	 * @param int $limit
	 * @param bool $descending
	 * @return FakeResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $descending ) {
		$result = new FakeResultWrapper( array() );

		$messageNames = $this->getAllMessages( $descending );
		$statuses = self::getCustomisedStatuses( $messageNames, $this->langcode, $this->foreign );

		$count = 0;
		foreach ( $messageNames as $key ) {
			$customised = isset( $statuses['pages'][$key] );
			if ( $customised !== $this->custom &&
				( $descending && ( $key < $offset || !$offset ) || !$descending && $key > $offset ) &&
				( ( $this->prefix && preg_match( $this->prefix, $key ) ) || $this->prefix === false )
			) {
				$actual = wfMessage( $key )->inLanguage( $this->langcode )->plain();
				$default = wfMessage( $key )->inLanguage( $this->langcode )->useDatabase( false )->plain();
				$result->result[] = array(
					'am_title' => $key,
					'am_actual' => $actual,
					'am_default' => $default,
					'am_customised' => $customised,
					'am_talk_exists' => isset( $statuses['talks'][$key] )
				);
				$count++;
			}

			if ( $count == $limit ) {
				break;
			}
		}

		return $result;
	}

	function getStartBody() {
		return Xml::openElement( 'table', array( 'class' => 'mw-datatable TablePager', 'id' => 'mw-allmessagestable' ) ) . "\n" .
			"<thead><tr>
				<th rowspan=\"2\">" .
			$this->msg( 'allmessagesname' )->escaped() . "
				</th>
				<th>" .
			$this->msg( 'allmessagesdefault' )->escaped() .
			"</th>
			</tr>\n
			<tr>
				<th>" .
			$this->msg( 'allmessagescurrent' )->escaped() .
			"</th>
			</tr></thead><tbody>\n";
	}

	function formatValue( $field, $value ) {
		switch ( $field ) {
			case 'am_title' :
				$title = Title::makeTitle( NS_MEDIAWIKI, $value . $this->suffix );
				$talk = Title::makeTitle( NS_MEDIAWIKI_TALK, $value . $this->suffix );

				if ( $this->mCurrentRow->am_customised ) {
					$title = Linker::linkKnown( $title, $this->getLanguage()->lcfirst( $value ) );
				} else {
					$title = Linker::link(
						$title,
						$this->getLanguage()->lcfirst( $value ),
						array(),
						array(),
						array( 'broken' )
					);
				}
				if ( $this->mCurrentRow->am_talk_exists ) {
					$talk = Linker::linkKnown( $talk, $this->talk );
				} else {
					$talk = Linker::link(
						$talk,
						$this->talk,
						array(),
						array(),
						array( 'broken' )
					);
				}

				return $title . ' ' . $this->msg( 'parentheses' )->rawParams( $talk )->escaped();

			case 'am_default' :
			case 'am_actual' :
				return Sanitizer::escapeHtmlAllowEntities( $value, ENT_QUOTES );
		}
		return '';
	}

	function formatRow( $row ) {
		// Do all the normal stuff
		$s = parent::formatRow( $row );

		// But if there's a customised message, add that too.
		if ( $row->am_customised ) {
			$s .= Xml::openElement( 'tr', $this->getRowAttrs( $row, true ) );
			$formatted = strval( $this->formatValue( 'am_actual', $row->am_actual ) );
			if ( $formatted == '' ) {
				$formatted = '&#160;';
			}
			$s .= Xml::tags( 'td', $this->getCellAttrs( 'am_actual', $row->am_actual ), $formatted )
				. "</tr>\n";
		}

		return $s;
	}

	function getRowAttrs( $row, $isSecond = false ) {
		$arr = array();
		if ( $row->am_customised ) {
			$arr['class'] = 'allmessages-customised';
		}
		if ( !$isSecond ) {
			$arr['id'] = Sanitizer::escapeId( 'msg_' . $this->getLanguage()->lcfirst( $row->am_title ) );
		}

		return $arr;
	}

	function getCellAttrs( $field, $value ) {
		if ( $this->mCurrentRow->am_customised && $field == 'am_title' ) {
			return array( 'rowspan' => '2', 'class' => $field );
		} elseif ( $field == 'am_title' ) {
			return array( 'class' => $field );
		} else {
			return array( 'lang' => $this->langcode, 'dir' => $this->lang->getDir(), 'class' => $field );
		}
	}

	// This is not actually used, as getStartBody is overridden above
	function getFieldNames() {
		return array(
			'am_title' => $this->msg( 'allmessagesname' )->text(),
			'am_default' => $this->msg( 'allmessagesdefault' )->text()
		);
	}

	function getTitle() {
		return SpecialPage::getTitleFor( 'Allmessages', false );
	}

	function isFieldSortable( $x ) {
		return false;
	}

	function getDefaultSort() {
		return '';
	}

	function getQueryInfo() {
		return '';
	}
}
