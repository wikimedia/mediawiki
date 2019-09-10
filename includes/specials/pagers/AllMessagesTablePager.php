<?php
/**
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
 * @ingroup Pager
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Linker\LinkRenderer;
use Wikimedia\Rdbms\FakeResultWrapper;

/**
 * Use TablePager for prettified output. We have to pretend that we're
 * getting data from a table when in fact not all of it comes from the database.
 *
 * @ingroup Pager
 */
class AllMessagesTablePager extends TablePager {

	/**
	 * @var string
	 */
	protected $langcode;

	/**
	 * @var bool
	 */
	protected $foreign;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $suffix;

	/**
	 * @var Language
	 */
	public $lang;

	/**
	 * @var null|bool
	 */
	public $custom;

	/**
	 * @param IContextSource|null $context
	 * @param FormOptions $opts
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( IContextSource $context = null, FormOptions $opts,
		LinkRenderer $linkRenderer
	) {
		parent::__construct( $context, $linkRenderer );

		$this->mIndexField = 'am_title';
		// FIXME: Why does this need to be set to DIR_DESCENDING to produce ascending ordering?
		$this->mDefaultDirection = IndexPager::DIR_DESCENDING;

		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$this->lang = wfGetLangObj( $opts->getValue( 'lang' ) );

		$this->langcode = $this->lang->getCode();
		$this->foreign = !$this->lang->equals( $contLang );

		$filter = $opts->getValue( 'filter' );
		if ( $filter === 'all' ) {
			$this->custom = null; // So won't match in either case
		} else {
			$this->custom = ( $filter === 'unmodified' );
		}

		$prefix = $this->getLanguage()->ucfirst( $opts->getValue( 'prefix' ) );
		$prefix = $prefix !== '' ?
			Title::makeTitleSafe( NS_MEDIAWIKI, $opts->getValue( 'prefix' ) ) :
			null;

		if ( $prefix !== null ) {
			$displayPrefix = $prefix->getDBkey();
			$this->prefix = '/^' . preg_quote( $displayPrefix, '/' ) . '/i';
		} else {
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

	function getAllMessages( $descending ) {
		$messageNames = Language::getLocalisationCache()->getSubitemList( 'en', 'messages' );

		// Normalise message names so they look like page titles and sort correctly - T86139
		$messageNames = array_map( [ $this->lang, 'ucfirst' ], $messageNames );

		if ( $descending ) {
			rsort( $messageNames );
		} else {
			asort( $messageNames );
		}

		return $messageNames;
	}

	/**
	 * Determine which of the MediaWiki and MediaWiki_talk namespace pages exist.
	 * Returns [ 'pages' => ..., 'talks' => ... ], where the subarrays have
	 * an entry for each existing page, with the key being the message name and
	 * value arbitrary.
	 *
	 * @param array $messageNames
	 * @param string $langcode What language code
	 * @param bool $foreign Whether the $langcode is not the content language
	 * @return array A 'pages' and 'talks' array with the keys of existing pages
	 */
	public static function getCustomisedStatuses( $messageNames, $langcode = 'en', $foreign = false ) {
		// FIXME: This function should be moved to Language:: or something.

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'page',
			[ 'page_namespace', 'page_title' ],
			[ 'page_namespace' => [ NS_MEDIAWIKI, NS_MEDIAWIKI_TALK ] ],
			__METHOD__,
			[ 'USE INDEX' => 'name_title' ]
		);
		$xNames = array_flip( $messageNames );

		$pageFlags = $talkFlags = [];

		foreach ( $res as $s ) {
			$exists = false;

			if ( $foreign ) {
				$titleParts = explode( '/', $s->page_title );
				if ( count( $titleParts ) === 2 &&
					$langcode === $titleParts[1] &&
					isset( $xNames[$titleParts[0]] )
				) {
					$exists = $titleParts[0];
				}
			} elseif ( isset( $xNames[$s->page_title] ) ) {
				$exists = $s->page_title;
			}

			$title = Title::newFromRow( $s );
			if ( $exists && $title->inNamespace( NS_MEDIAWIKI ) ) {
				$pageFlags[$exists] = true;
			} elseif ( $exists && $title->inNamespace( NS_MEDIAWIKI_TALK ) ) {
				$talkFlags[$exists] = true;
			}
		}

		return [ 'pages' => $pageFlags, 'talks' => $talkFlags ];
	}

	/**
	 * This function normally does a database query to get the results; we need
	 * to make a pretend result using a FakeResultWrapper.
	 * @param string $offset
	 * @param int $limit
	 * @param bool $order
	 * @return FakeResultWrapper
	 */
	function reallyDoQuery( $offset, $limit, $order ) {
		$asc = ( $order === self::QUERY_ASCENDING );

		$messageNames = $this->getAllMessages( $order );
		$statuses = self::getCustomisedStatuses( $messageNames, $this->langcode, $this->foreign );

		$rows = [];
		$count = 0;
		foreach ( $messageNames as $key ) {
			$customised = isset( $statuses['pages'][$key] );
			if ( $customised !== $this->custom &&
				( $asc && ( $key < $offset || !$offset ) || !$asc && $key > $offset ) &&
				( ( $this->prefix && preg_match( $this->prefix, $key ) ) || $this->prefix === false )
			) {
				$actual = $this->msg( $key )->inLanguage( $this->lang )->plain();
				$default = $this->msg( $key )->inLanguage( $this->lang )->useDatabase( false )->plain();
				$rows[] = [
					'am_title' => $key,
					'am_actual' => $actual,
					'am_default' => $default,
					'am_customised' => $customised,
					'am_talk_exists' => isset( $statuses['talks'][$key] )
				];
				$count++;
			}

			if ( $count === $limit ) {
				break;
			}
		}

		return new FakeResultWrapper( $rows );
	}

	protected function getStartBody() {
		$tableClass = $this->getTableClass();
		return Xml::openElement( 'table', [
			'class' => "mw-datatable $tableClass",
			'id' => 'mw-allmessagestable'
		] ) .
		"\n" .
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
			</tr></thead>\n";
	}

	function getEndBody() {
		return Html::closeElement( 'table' );
	}

	function formatValue( $field, $value ) {
		$linkRenderer = $this->getLinkRenderer();
		switch ( $field ) {
			case 'am_title' :
				$title = Title::makeTitle( NS_MEDIAWIKI, $value . $this->suffix );
				$talk = Title::makeTitle( NS_MEDIAWIKI_TALK, $value . $this->suffix );
				$translation = Linker::makeExternalLink(
					'https://translatewiki.net/w/i.php?' . wfArrayToCgi( [
						'title' => 'Special:SearchTranslations',
						'group' => 'mediawiki',
						'grouppath' => 'mediawiki',
						'language' => $this->getLanguage()->getCode(),
						'query' => $value . ' ' . $this->msg( $value )->plain()
					] ),
					$this->msg( 'allmessages-filter-translate' )->text()
				);
				$talkLink = $this->msg( 'talkpagelinktext' )->escaped();

				if ( $this->mCurrentRow->am_customised ) {
					$title = $linkRenderer->makeKnownLink( $title, $this->getLanguage()->lcfirst( $value ) );
				} else {
					$title = $linkRenderer->makeBrokenLink(
						$title, $this->getLanguage()->lcfirst( $value )
					);
				}
				if ( $this->mCurrentRow->am_talk_exists ) {
					$talk = $linkRenderer->makeKnownLink( $talk, $talkLink );
				} else {
					$talk = $linkRenderer->makeBrokenLink(
						$talk,
						$talkLink
					);
				}

				return $title . ' ' .
				$this->msg( 'parentheses' )->rawParams( $talk )->escaped() .
				' ' .
				$this->msg( 'parentheses' )->rawParams( $translation )->escaped();

			case 'am_default' :
			case 'am_actual' :
				return Sanitizer::escapeHtmlAllowEntities( $value );
		}

		return '';
	}

	/**
	 * @param stdClass $row
	 * @return string HTML
	 */
	function formatRow( $row ) {
		// Do all the normal stuff
		$s = parent::formatRow( $row );

		// But if there's a customised message, add that too.
		if ( $row->am_customised ) {
			$s .= Html::openElement( 'tr', $this->getRowAttrs( $row, true ) );
			$formatted = strval( $this->formatValue( 'am_actual', $row->am_actual ) );

			if ( $formatted === '' ) {
				$formatted = "\u{00A0}";
			}

			$s .= Html::element( 'td', $this->getCellAttrs( 'am_actual', $row->am_actual ), $formatted )
				. Html::closeElement( 'tr' );
		}

		return Html::rawElement( 'tbody', [], $s );
	}

	function getRowAttrs( $row ) {
		return [];
	}

	/**
	 * @param string $field
	 * @param string $value
	 * @return array HTML attributes
	 */
	function getCellAttrs( $field, $value ) {
		$attr = [];
		if ( $field === 'am_title' ) {
			if ( $this->mCurrentRow->am_customised ) {
				$attr += [ 'rowspan' => '2' ];
			}
		} else {
			$attr += [
				'lang' => $this->lang->getHtmlCode(),
				'dir' => $this->lang->getDir(),
			];
			if ( $this->mCurrentRow->am_customised ) {
				// CSS class: am_default, am_actual
				$attr += [ 'class' => $field ];
			}
		}
		return $attr;
	}

	// This is not actually used, as getStartBody is overridden above
	function getFieldNames() {
		return [
			'am_title' => $this->msg( 'allmessagesname' )->text(),
			'am_default' => $this->msg( 'allmessagesdefault' )->text()
		];
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
		return [];
	}

}
