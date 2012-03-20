<?php
/**
 * Abstract class for type hinting (accepts WikiPage, Article, ImagePage, CategoryPage)
 */
abstract class Page {}

/**
 * Class representing a MediaWiki article and history.
 *
 * Some fields are public only for backwards-compatibility. Use accessors.
 * In the past, this class was part of Article.php and everything was public.
 *
 * @internal documentation reviewed 15 Mar 2010
 */
class WikiPage extends Page {
	/**
	 * @var Title
	 */
	public $mTitle = null;

	/**@{{
	 * @protected
	 */
	public $mDataLoaded = false;         // !< Boolean
	public $mIsRedirect = false;         // !< Boolean
	public $mLatest = false;             // !< Integer (false means "not loaded")
	public $mPreparedEdit = false;       // !< Array
	/**@}}*/

	/**
	 * @var Title
	 */
	protected $mRedirectTarget = null;

	/**
	 * @var Revision
	 */
	protected $mLastRevision = null;

	/**
	 * @var string; timestamp of the current revision or empty string if not loaded
	 */
	protected $mTimestamp = '';

	/**
	 * @var string
	 */
	protected $mTouched = '19700101000000';

	/**
	 * @var int|null
	 */
	protected $mCounter = null;

	/**
	 * Constructor and clear the article
	 * @param $title Title Reference to a Title object.
	 */
	public function __construct( Title $title ) {
		$this->mTitle = $title;
	}

	/**
	 * Create a WikiPage object of the appropriate class for the given title.
	 *
	 * @param $title Title
	 * @return WikiPage object of the appropriate type
	 */
	public static function factory( Title $title ) {
		$ns = $title->getNamespace();

		if ( $ns == NS_MEDIA ) {
			throw new MWException( "NS_MEDIA is a virtual namespace; use NS_FILE." );
		} elseif ( $ns < 0 ) {
			throw new MWException( "Invalid or virtual namespace $ns given." );
		}

		switch ( $ns ) {
			case NS_FILE:
				$page = new WikiFilePage( $title );
				break;
			case NS_CATEGORY:
				$page = new WikiCategoryPage( $title );
				break;
			default:
				$page = new WikiPage( $title );
		}

		return $page;
	}

	/**
	 * Constructor from a page id
	 *
	 * @param $id Int article ID to load
	 *
	 * @return WikiPage
	 */
	public static function newFromID( $id ) {
		$t = Title::newFromID( $id );
		if ( $t ) {
			return self::factory( $t );
		}
		return null;
	}

	/**
	 * Returns overrides for action handlers.
	 * Classes listed here will be used instead of the default one when
	 * (and only when) $wgActions[$action] === true. This allows subclasses
	 * to override the default behavior.
	 *
	 * @todo: move this UI stuff somewhere else
	 *
	 * @return Array
	 */
	public function getActionOverrides() {
		return array();
	}

	/**
	 * Get the title object of the article
	 * @return Title object of this page
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Clear the object
	 */
	public function clear() {
		$this->mDataLoaded = false;

		$this->mCounter = null;
		$this->mRedirectTarget = null; # Title object if set
		$this->mLastRevision = null; # Latest revision
		$this->mTouched = '19700101000000';
		$this->mTimestamp = '';
		$this->mIsRedirect = false;
		$this->mLatest = false;
		$this->mPreparedEdit = false;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new page.
	 *
	 * @return array
	 */
	public static function selectFields() {
		return array(
			'page_id',
			'page_namespace',
			'page_title',
			'page_restrictions',
			'page_counter',
			'page_is_redirect',
			'page_is_new',
			'page_random',
			'page_touched',
			'page_latest',
			'page_len',
            'page_content_model',
		);
	}

	/**
	 * Fetch a page record with the given conditions
	 * @param $dbr DatabaseBase object
	 * @param $conditions Array
	 * @return mixed Database result resource, or false on failure
	 */
	protected function pageData( $dbr, $conditions ) {
		$fields = self::selectFields();

		wfRunHooks( 'ArticlePageDataBefore', array( &$this, &$fields ) );

		$row = $dbr->selectRow( 'page', $fields, $conditions, __METHOD__ );

		wfRunHooks( 'ArticlePageDataAfter', array( &$this, &$row ) );

		return $row;
	}

	/**
	 * Fetch a page record matching the Title object's namespace and title
	 * using a sanitized title string
	 *
	 * @param $dbr DatabaseBase object
	 * @param $title Title object
	 * @return mixed Database result resource, or false on failure
	 */
	public function pageDataFromTitle( $dbr, $title ) {
		return $this->pageData( $dbr, array(
			'page_namespace' => $title->getNamespace(),
			'page_title'     => $title->getDBkey() ) );
	}

	/**
	 * Fetch a page record matching the requested ID
	 *
	 * @param $dbr DatabaseBase
	 * @param $id Integer
	 * @return mixed Database result resource, or false on failure
	 */
	public function pageDataFromId( $dbr, $id ) {
		return $this->pageData( $dbr, array( 'page_id' => $id ) );
	}

	/**
	 * Set the general counter, title etc data loaded from
	 * some source.
	 *
	 * @param $data Object|String One of the following:
	 *		A DB query result object or...
	 *		"fromdb" to get from a slave DB or...
	 *		"fromdbmaster" to get from the master DB
	 * @return void
	 */
	public function loadPageData( $data = 'fromdb' ) {
		if ( $data === 'fromdbmaster' ) {
			$data = $this->pageDataFromTitle( wfGetDB( DB_MASTER ), $this->mTitle );
		} elseif ( $data === 'fromdb' ) { // slave
			$data = $this->pageDataFromTitle( wfGetDB( DB_SLAVE ), $this->mTitle );
			# Use a "last rev inserted" timestamp key to dimish the issue of slave lag.
			# Note that DB also stores the master position in the session and checks it.
			$touched = $this->getCachedLastEditTime();
			if ( $touched ) { // key set
				if ( !$data || $touched > wfTimestamp( TS_MW, $data->page_touched ) ) {
					$data = $this->pageDataFromTitle( wfGetDB( DB_MASTER ), $this->mTitle );
				}
			}
		}

		$lc = LinkCache::singleton();

		if ( $data ) {
			$lc->addGoodLinkObjFromRow( $this->mTitle, $data );

			$this->mTitle->loadFromRow( $data );

			# Old-fashioned restrictions
			$this->mTitle->loadRestrictions( $data->page_restrictions );

			$this->mCounter     = intval( $data->page_counter );
			$this->mTouched     = wfTimestamp( TS_MW, $data->page_touched );
			$this->mIsRedirect  = intval( $data->page_is_redirect );
			$this->mLatest      = intval( $data->page_latest );
		} else {
			$lc->addBadLinkObj( $this->mTitle );

			$this->mTitle->loadFromRow( false );
		}

		$this->mDataLoaded = true;
	}

	/**
	 * @return int Page ID
	 */
	public function getId() {
		return $this->mTitle->getArticleID();
	}

	/**
	 * @return bool Whether or not the page exists in the database
	 */
	public function exists() {
		return $this->mTitle->exists();
	}

	/**
	 * Check if this page is something we're going to be showing
	 * some sort of sensible content for. If we return false, page
	 * views (plain action=view) will return an HTTP 404 response,
	 * so spiders and robots can know they're following a bad link.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		return $this->mTitle->exists() || $this->mTitle->isAlwaysKnown();
	}

	/**
	 * @return int The view count for the page
	 */
	public function getCount() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return $this->mCounter;
	}

	/**
	 * Tests if the article text represents a redirect
	 *
	 * @param $text mixed string containing article contents, or boolean
	 * @return bool
	 */
	public function isRedirect( $text = false ) {
        if ( $text === false ) $content = $this->getContent();
        else $content = ContentHandler::makeContent( $text, $this->mTitle ); # TODO: allow model and format to be provided; or better, expect a Content object

        return $content->isRedirect();
	}

	/**
	 * Loads page_touched and returns a value indicating if it should be used
	 * @return boolean true if not a redirect
	 */
	public function checkTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return !$this->mIsRedirect;
	}

	/**
	 * Get the page_touched field
	 * @return string containing GMT timestamp
	 */
	public function getTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mTouched;
	}

	/**
	 * Get the page_latest field
	 * @return integer rev_id of current revision
	 */
	public function getLatest() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return (int)$this->mLatest;
	}

	/**
	 * Loads everything except the text
	 * This isn't necessary for all uses, so it's only done if needed.
	 */
	protected function loadLastEdit() {
		if ( $this->mLastRevision !== null ) {
			return; // already loaded
		}

		$latest = $this->getLatest();
		if ( !$latest ) {
			return; // page doesn't exist or is missing page_latest info
		}

		$revision = Revision::newFromPageId( $this->getId(), $latest );
		if ( $revision ) { // sanity
			$this->setLastEdit( $revision );
		}
	}

	/**
	 * Set the latest revision
	 */
	protected function setLastEdit( Revision $revision ) {
		$this->mLastRevision = $revision;
		$this->mTimestamp = $revision->getTimestamp();
	}

	/**
	 * Get the latest revision
	 * @return Revision|null
	 */
	public function getRevision() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision;
		}
		return null;
	}

    /**
     * Get the content of the current revision. No side-effects...
     *
     * @param $audience Integer: one of:
     *      Revision::FOR_PUBLIC       to be displayed to all users
     *      Revision::FOR_THIS_USER    to be displayed to $wgUser
     *      Revision::RAW              get the text regardless of permissions
     * @return Content|null The content of the current revision
     */
    public function getContent( $audience = Revision::FOR_PUBLIC ) {
        $this->loadLastEdit();
        if ( $this->mLastRevision ) {
            return $this->mLastRevision->getContent( $audience );
        }
        return false;
    }

	/**
	 * Get the text of the current revision. No side-effects...
	 *
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return String|false The text of the current revision
     * @deprecated as of 1.20, getContent() should be used instead.
	 */
	public function getText( $audience = Revision::FOR_PUBLIC ) { #FIXME: deprecated, replace usage!
        wfDeprecated( __METHOD__, '1.20' );
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getText( $audience );
		}
		return false;
	}

	/**
	 * Get the text of the current revision. No side-effects...
	 *
	 * @return String|false The text of the current revision
	 */
	public function getRawText() { #FIXME: deprecated, replace usage!
		return $this->getText( Revision::RAW );
	}

    /**
     * Get the content of the current revision. No side-effects...
     *
     * @return Contet|false The text of the current revision
     */
    protected function getNativeData() {
        $content = $this->getContent( Revision::RAW );
        if ( !$content ) return null;

        return $content->getNativeData();
    }

	/**
	 * @return string MW timestamp of last article revision
	 */
	public function getTimestamp() {
		// Check if the field has been filled by WikiPage::setTimestamp()
		if ( !$this->mTimestamp ) {
			$this->loadLastEdit();
		}
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * Set the page timestamp (use only to avoid DB queries)
	 * @param $ts string MW timestamp of last article revision
	 * @return void
	 */
	public function setTimestamp( $ts ) {
		$this->mTimestamp = wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return int user ID for the user that made the last article revision
	 */
	public function getUser( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUser( $audience );
		} else {
			return -1;
		}
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return string username of the user that made the last article revision
	 */
	public function getUserText( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUserText( $audience );
		} else {
			return '';
		}
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return string Comment stored for the last article revision
	 */
	public function getComment( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getComment( $audience );
		} else {
			return '';
		}
	}

	/**
	 * Returns true if last revision was marked as "minor edit"
	 *
	 * @return boolean Minor edit indicator for the last article revision.
	 */
	public function getMinorEdit() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->isMinor();
		} else {
			return false;
		}
	}

	/**
	 * Get the cached timestamp for the last time the page changed.
	 * This is only used to help handle slave lag by comparing to page_touched.
	 * @return string MW timestamp
	 */
	protected function getCachedLastEditTime() {
		global $wgMemc;
		$key = wfMemcKey( 'page-lastedit', md5( $this->mTitle->getPrefixedDBkey() ) );
		return $wgMemc->get( $key );
	}

	/**
	 * Set the cached timestamp for the last time the page changed.
	 * This is only used to help handle slave lag by comparing to page_touched.
	 * @param $timestamp string
	 * @return void
	 */
	public function setCachedLastEditTime( $timestamp ) {
		global $wgMemc;
		$key = wfMemcKey( 'page-lastedit', md5( $this->mTitle->getPrefixedDBkey() ) );
		$wgMemc->set( $key, wfTimestamp( TS_MW, $timestamp ), 60*15 );
	}

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param $editInfo Object or false: object returned by prepareTextForEdit(),
	 *        if false, the current database state will be used
	 * @return Boolean
	 */
	public function isCountable( $editInfo = false ) {
		global $wgArticleCountMethod;

		if ( !$this->mTitle->isContentPage() ) {
			return false;
		}

        if ( $editInfo ) {
            $content = ContentHandler::makeContent( $editInfo->pst, $this->mTitle );
            # TODO: take model and format from edit info!
        } else {
            $content = $this->getContent();
        }

		if ( $content->isRedirect( ) ) {
			return false;
		}

        $hasLinks = null;

        if ( $wgArticleCountMethod === 'link' ) {
            # nasty special case to avoid re-parsing to detect links

            if ( $editInfo ) {
                // ParserOutput::getLinks() is a 2D array of page links, so
                // to be really correct we would need to recurse in the array
                // but the main array should only have items in it if there are
                // links.
                $hasLinks = (bool)count( $editInfo->output->getLinks() );
            } else {
                $hasLinks = (bool)wfGetDB( DB_SLAVE )->selectField( 'pagelinks', 1,
                    array( 'pl_from' => $this->getId() ), __METHOD__ );
            }
        }

		return $content->isCountable( $hasLinks );
	}

	/**
	 * If this page is a redirect, get its target
	 *
	 * The target will be fetched from the redirect table if possible.
	 * If this page doesn't have an entry there, call insertRedirect()
	 * @return Title|mixed object, or null if this page is not a redirect
	 */
	public function getRedirectTarget() {
		if ( !$this->mTitle->isRedirect() ) {
			return null;
		}

		if ( $this->mRedirectTarget !== null ) {
			return $this->mRedirectTarget;
		}

		# Query the redirect table
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'redirect',
			array( 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ),
			array( 'rd_from' => $this->getId() ),
			__METHOD__
		);

		// rd_fragment and rd_interwiki were added later, populate them if empty
		if ( $row && !is_null( $row->rd_fragment ) && !is_null( $row->rd_interwiki ) ) {
			return $this->mRedirectTarget = Title::makeTitle(
				$row->rd_namespace, $row->rd_title,
				$row->rd_fragment, $row->rd_interwiki );
		}

		# This page doesn't have an entry in the redirect table
		return $this->mRedirectTarget = $this->insertRedirect();
	}

	/**
	 * Insert an entry for this page into the redirect table.
	 *
	 * Don't call this function directly unless you know what you're doing.
	 * @return Title object or null if not a redirect
	 */
	public function insertRedirect() {
		// recurse through to only get the final target
		$retval = Title::newFromRedirectRecurse( $this->getRawText() ); #FIXME: move this to Content object
		if ( !$retval ) {
			return null;
		}
		$this->insertRedirectEntry( $retval );
		return $retval;
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate
	 * it redirects to $rt .
	 * @param $rt Title redirect target
	 */
	public function insertRedirectEntry( $rt ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'redirect', array( 'rd_from' ),
			array(
				'rd_from'      => $this->getId(),
				'rd_namespace' => $rt->getNamespace(),
				'rd_title'     => $rt->getDBkey(),
				'rd_fragment'  => $rt->getFragment(),
				'rd_interwiki' => $rt->getInterwiki(),
			),
			__METHOD__
		);
	}

	/**
	 * Get the Title object or URL this page redirects to
	 *
	 * @return mixed false, Title of in-wiki target, or string with URL
	 */
	public function followRedirect() {
		return $this->getRedirectURL( $this->getRedirectTarget() );
	}

	/**
	 * Get the Title object or URL to use for a redirect. We use Title
	 * objects for same-wiki, non-special redirects and URLs for everything
	 * else.
	 * @param $rt Title Redirect target
	 * @return mixed false, Title object of local target, or string with URL
	 */
	public function getRedirectURL( $rt ) {
		if ( !$rt ) {
			return false;
		}

		if ( $rt->isExternal() ) {
			if ( $rt->isLocal() ) {
				// Offsite wikis need an HTTP redirect.
				//
				// This can be hard to reverse and may produce loops,
				// so they may be disabled in the site configuration.
				$source = $this->mTitle->getFullURL( 'redirect=no' );
				return $rt->getFullURL( 'rdfrom=' . urlencode( $source ) );
			} else {
				// External pages pages without "local" bit set are not valid
				// redirect targets
				return false;
			}
		}

		if ( $rt->isSpecialPage() ) {
			// Gotta handle redirects to special pages differently:
			// Fill the HTTP response "Location" header and ignore
			// the rest of the page we're on.
			//
			// Some pages are not valid targets
			if ( $rt->isValidRedirectTarget() ) {
				return $rt->getFullURL();
			} else {
				return false;
			}
		}

		return $rt;
	}

	/**
	 * Get a list of users who have edited this article, not including the user who made
	 * the most recent revision, which you can get from $article->getUser() if you want it
	 * @return UserArrayFromResult
	 */
	public function getContributors() {
		# @todo FIXME: This is expensive; cache this info somewhere.

		$dbr = wfGetDB( DB_SLAVE );

		if ( $dbr->implicitGroupby() ) {
			$realNameField = 'user_real_name';
		} else {
			$realNameField = 'MIN(user_real_name) AS user_real_name';
		}

		$tables = array( 'revision', 'user' );

		$fields = array(
			'rev_user as user_id',
			'rev_user_text AS user_name',
			$realNameField,
			'MAX(rev_timestamp) AS timestamp',
		);

		$conds = array( 'rev_page' => $this->getId() );

		// The user who made the top revision gets credited as "this page was last edited by
		// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
		$user = $this->getUser();
		if ( $user ) {
			$conds[] = "rev_user != $user";
		} else {
			$conds[] = "rev_user_text != {$dbr->addQuotes( $this->getUserText() )}";
		}

		$conds[] = "{$dbr->bitAnd( 'rev_deleted', Revision::DELETED_USER )} = 0"; // username hidden?

		$jconds = array(
			'user' => array( 'LEFT JOIN', 'rev_user = user_id' ),
		);

		$options = array(
			'GROUP BY' => array( 'rev_user', 'rev_user_text' ),
			'ORDER BY' => 'timestamp DESC',
		);

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $jconds );
		return new UserArrayFromResult( $res );
	}

	/**
	 * Get the last N authors
	 * @param $num Integer: number of revisions to get
	 * @param $revLatest String: the latest rev_id, selected from the master (optional)
	 * @return array Array of authors, duplicates not removed
	 */
	public function getLastNAuthors( $num, $revLatest = 0 ) {
		wfProfileIn( __METHOD__ );
		// First try the slave
		// If that doesn't have the latest revision, try the master
		$continue = 2;
		$db = wfGetDB( DB_SLAVE );

		do {
			$res = $db->select( array( 'page', 'revision' ),
				array( 'rev_id', 'rev_user_text' ),
				array(
					'page_namespace' => $this->mTitle->getNamespace(),
					'page_title' => $this->mTitle->getDBkey(),
					'rev_page = page_id'
				), __METHOD__,
				array(
					'ORDER BY' => 'rev_timestamp DESC',
					'LIMIT' => $num
				)
			);

			if ( !$res ) {
				wfProfileOut( __METHOD__ );
				return array();
			}

			$row = $db->fetchObject( $res );

			if ( $continue == 2 && $revLatest && $row->rev_id != $revLatest ) {
				$db = wfGetDB( DB_MASTER );
				$continue--;
			} else {
				$continue = 0;
			}
		} while ( $continue );

		$authors = array( $row->rev_user_text );

		foreach ( $res as $row ) {
			$authors[] = $row->rev_user_text;
		}

		wfProfileOut( __METHOD__ );
		return $authors;
	}

	/**
	 * Should the parser cache be used?
	 *
	 * @param $parserOptions ParserOptions to check
	 * @param $oldid int
	 * @return boolean
	 */
	public function isParserCacheUsed( ParserOptions $parserOptions, $oldid ) {
		global $wgEnableParserCache;

		return $wgEnableParserCache
			&& $parserOptions->getStubThreshold() == 0
			&& $this->mTitle->exists()
			&& ( $oldid === null || $oldid === 0 || $oldid === $this->getLatest() )
			&& $this->mTitle->isWikitextPage(); #FIXME: ask ContentHandler if cachable!
	}

	/**
	 * Get a ParserOutput for the given ParserOptions and revision ID.
	 * The parser cache will be used if possible.
	 *
	 * @since 1.19
	 * @param $parserOptions ParserOptions to use for the parse operation
	 * @param $oldid Revision ID to get the text from, passing null or 0 will
	 *               get the current revision (default value)
	 * @return ParserOutput or false if the revision was not found
	 */
	public function getParserOutput( ParserOptions $parserOptions, $oldid = null ) {
		wfProfileIn( __METHOD__ );

		$useParserCache = $this->isParserCacheUsed( $parserOptions, $oldid );
		wfDebug( __METHOD__ . ': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $parserOptions->getStubThreshold() ) {
			wfIncrStats( 'pcache_miss_stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = ParserCache::singleton()->get( $this, $parserOptions );
			if ( $parserOutput !== false ) {
				wfProfileOut( __METHOD__ );
				return $parserOutput;
			}
		}

		if ( $oldid === null || $oldid === 0 ) {
			$oldid = $this->getLatest();
		}

		$pool = new PoolWorkArticleView( $this, $parserOptions, $oldid, $useParserCache );
		$pool->execute();

		wfProfileOut( __METHOD__ );

		return $pool->getParserOutput();
	}

	/**
	 * Do standard deferred updates after page view
	 * @param $user User The relevant user
	 */
	public function doViewUpdates( User $user ) {
		global $wgDisableCounters;
		if ( wfReadOnly() ) {
			return;
		}

		# Don't update page view counters on views from bot users (bug 14044)
		if ( !$wgDisableCounters && !$user->isAllowed( 'bot' ) && $this->mTitle->exists() ) {
			DeferredUpdates::addUpdate( new ViewCountUpdate( $this->getId() ) );
			DeferredUpdates::addUpdate( new SiteStatsUpdate( 1, 0, 0 ) );
		}

		# Update newtalk / watchlist notification status
		$user->clearNotification( $this->mTitle );
	}

	/**
	 * Perform the actions of a page purging
	 */
	public function doPurge() {
		global $wgUseSquid;

		if( !wfRunHooks( 'ArticlePurge', array( &$this ) ) ){
			return false;
		}

		// Invalidate the cache
		$this->mTitle->invalidateCache();
		$this->clear();

		if ( $wgUseSquid ) {
			// Commit the transaction before the purge is sent
			$dbw = wfGetDB( DB_MASTER );
			$dbw->commit();

			// Send purge
			$update = SquidUpdate::newSimplePurge( $this->mTitle );
			$update->doUpdate();
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			if ( $this->mTitle->exists() ) {
				$text = $this->getNativeData(); #FIXME: may not be a string. check Content model!
			} else {
				$text = false;
			}

			MessageCache::singleton()->replace( $this->mTitle->getDBkey(), $text );
		}
		return true;
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @param $dbw DatabaseBase
	 * @return int The newly created page_id key, or false if the title already existed
	 */
	public function insertOn( $dbw ) {
		wfProfileIn( __METHOD__ );

		$page_id = $dbw->nextSequenceValue( 'page_page_id_seq' );
		$dbw->insert( 'page', array(
			'page_id'           => $page_id,
			'page_namespace'    => $this->mTitle->getNamespace(),
			'page_title'        => $this->mTitle->getDBkey(),
			'page_counter'      => 0,
			'page_restrictions' => '',
			'page_is_redirect'  => 0, # Will set this shortly...
			'page_is_new'       => 1,
			'page_random'       => wfRandom(),
			'page_touched'      => $dbw->timestamp(),
			'page_latest'       => 0, # Fill this in shortly...
			'page_len'          => 0, # Fill this in shortly...
		), __METHOD__, 'IGNORE' );

		$affected = $dbw->affectedRows();

		if ( $affected ) {
			$newid = $dbw->insertId();
			$this->mTitle->resetArticleID( $newid );
		}
		wfProfileOut( __METHOD__ );

		return $affected ? $newid : false;
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * @param $dbw DatabaseBase: object
	 * @param $revision Revision: For ID number, and text used to set
	 *                  length and redirect status fields
	 * @param $lastRevision Integer: if given, will not overwrite the page field
	 *                      when different from the currently set value.
	 *                      Giving 0 indicates the new page flag should be set
	 *                      on.
	 * @param $lastRevIsRedirect Boolean: if given, will optimize adding and
	 *                           removing rows in redirect table.
	 * @return bool true on success, false on failure
	 * @private
	 */
	public function updateRevisionOn( $dbw, $revision, $lastRevision = null, $lastRevIsRedirect = null ) {
		wfProfileIn( __METHOD__ );

		$text = $revision->getText();
		$len = strlen( $text );
		$rt = Title::newFromRedirectRecurse( $text );

		$conditions = array( 'page_id' => $this->getId() );

		if ( !is_null( $lastRevision ) ) {
			# An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$now = wfTimestampNow();
		$dbw->update( 'page',
			array( /* SET */
				'page_latest'      => $revision->getId(),
				'page_touched'     => $dbw->timestamp( $now ),
				'page_is_new'      => ( $lastRevision === 0 ) ? 1 : 0,
				'page_is_redirect' => $rt !== null ? 1 : 0,
				'page_len'         => $len,
			),
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() != 0;
		if ( $result ) {
			$this->updateRedirectOn( $dbw, $rt, $lastRevIsRedirect );
			$this->setLastEdit( $revision );
			$this->setCachedLastEditTime( $now );
			$this->mLatest = $revision->getId();
			$this->mIsRedirect = (bool)$rt;
			# Update the LinkCache.
			LinkCache::singleton()->addGoodLinkObj( $this->getId(), $this->mTitle, $len, $this->mIsRedirect, $this->mLatest );
		}

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param $dbw DatabaseBase
	 * @param $redirectTitle Title object pointing to the redirect target,
	 *                       or NULL if this is not a redirect
	 * @param $lastRevIsRedirect If given, will optimize adding and
	 *                           removing rows in redirect table.
	 * @return bool true on success, false on failure
	 * @private
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = !is_null( $redirectTitle );

		if ( !$isRedirect && $lastRevIsRedirect === false ) {
			return true;
		}

		wfProfileIn( __METHOD__ );
		if ( $isRedirect ) {
			$this->insertRedirectEntry( $redirectTitle );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = array( 'rd_from' => $this->getId() );
			$dbw->delete( 'redirect', $where, __METHOD__ );
		}

		if ( $this->getTitle()->getNamespace() == NS_FILE ) {
			RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $this->getTitle() );
		}
		wfProfileOut( __METHOD__ );

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @param $dbw Database object
	 * @param $revision Revision object
	 * @return mixed
	 */
	public function updateIfNewerOn( $dbw, $revision ) {
		wfProfileIn( __METHOD__ );

		$row = $dbw->selectRow(
			array( 'revision', 'page' ),
			array( 'rev_id', 'rev_timestamp', 'page_is_redirect' ),
			array(
				'page_id' => $this->getId(),
				'page_latest=rev_id' ),
			__METHOD__ );

		if ( $row ) {
			if ( wfTimestamp( TS_MW, $row->rev_timestamp ) >= $revision->getTimestamp() ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$prev = $row->rev_id;
			$lastRevIsRedirect = (bool)$row->page_is_redirect;
		} else {
			# No or missing previous revision; mark the page as new
			$prev = 0;
			$lastRevIsRedirect = null;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev, $lastRevIsRedirect );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Get the text that needs to be saved in order to undo all revisions
	 * between $undo and $undoafter. Revisions must belong to the same page,
	 * must exist and must not be deleted
	 * @param $undo Revision
	 * @param $undoafter Revision Must be an earlier revision than $undo
	 * @return mixed string on success, false on failure
     * @deprecated since 1.20: use ContentHandler::getUndoContent() instead.
	 */
	public function getUndoText( Revision $undo, Revision $undoafter = null ) { #FIXME: replace usages.
        $this->loadLastEdit();

        if ( $this->mLastRevision ) {
            $handler = ContentHandler::getForTitle( $this->getTitle() );
            $undone = $handler->getUndoContent( $this->mLastRevision, $undo, $undoafter );

            return ContentHandler::getContentText( $undone );
        }

        return false;
	}

	/**
	 * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...)
	 * @param $text String: new text of the section
	 * @param $sectionTitle String: new section's subject, only if $section is 'new'
	 * @param $edittime String: revision timestamp or null to use the current revision
	 * @return Content new complete article content, or null if error
	 */
	public function replaceSection( $section, $text, $sectionTitle = '', $edittime = null ) {
		wfProfileIn( __METHOD__ );

        $sectionContent = ContentHandler::makeContent( $text, $this->getTitle() ); #XXX: could make section title, but that's not required.

		if ( strval( $section ) == '' ) {
			// Whole-page edit; let the whole text through
            $newContent = $sectionContent;
		} else {
			// Bug 30711: always use current version when adding a new section
			if ( is_null( $edittime ) || $section == 'new' ) {
				$oldContent = $this->getContent();
				if ( ! $oldContent ) {
					wfDebug( __METHOD__ . ": no page text\n" );
					wfProfileOut( __METHOD__ );
					return null;
				}
			} else {
				$dbw = wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );

				if ( !$rev ) {
					wfDebug( "WikiPage::replaceSection asked for bogus section (page: " .
						$this->getId() . "; section: $section; edittime: $edittime)\n" );
					wfProfileOut( __METHOD__ );
					return null;
				}

                $oldContent = $rev->getContent();
			}

            $newContent = $oldContent->replaceSection( $section, $sectionContent, $sectionTitle );
		}

		wfProfileOut( __METHOD__ );
		return $newContent;
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * @param $flags Int
	 * @return Int updated $flags
	 */
	function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->mTitle->getArticleID() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}

	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @param $text String: new text
	 * @param $summary String: edit summary
	 * @param $flags Integer bitfield:
	 *      EDIT_NEW
	 *          Article is known or assumed to be non-existent, create a new one
	 *      EDIT_UPDATE
	 *          Article is known or assumed to be pre-existing, update it
	 *      EDIT_MINOR
	 *          Mark this edit minor, if the user is allowed to do so
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the edit a "bot" edit regardless of user rights
	 *      EDIT_DEFER_UPDATES
	 *          Defer some of the updates until the end of index.php
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the article will be detected.
	 * If EDIT_UPDATE is specified and the article doesn't exist, the function will return an
	 * edit-gone-missing error. If EDIT_NEW is specified and the article does exist, an
	 * edit-already-exists error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param $baseRevId the revision ID this edit was based off, if any
	 * @param $user User the user doing the edit
	 *
	 * @return Status object. Possible errors:
	 *     edit-hook-aborted:       The ArticleSave hook aborted the edit but didn't set the fatal flag of $status
	 *     edit-gone-missing:       In update mode, but the article didn't exist
	 *     edit-conflict:           In update mode, the article changed unexpectedly
	 *     edit-no-change:          Warning that the text was the same as before
	 *     edit-already-exists:     In creation mode, but the article already exists
	 *
	 *  Extensions may define additional errors.
	 *
	 *  $return->value will contain an associative array with members as follows:
	 *     new:                     Boolean indicating if the function attempted to create a new article
	 *     revision:                The revision object for the inserted revision, or null
	 *
	 *  Compatibility note: this function previously returned a boolean value indicating success/failure
	 */
	public function doEdit( $text, $summary, $flags = 0, $baseRevId = false, $user = null ) { #FIXME: change $text to $content
		global $wgUser, $wgDBtransactions, $wgUseAutomaticEditSummaries;

		# Low-level sanity check
		if ( $this->mTitle->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}

		wfProfileIn( __METHOD__ );

		$user = is_null( $user ) ? $wgUser : $user;
		$status = Status::newGood( array() );

		# Load $this->mTitle->getArticleID() and $this->mLatest if it's not already
		$this->loadPageData( 'fromdbmaster' );

		$flags = $this->checkFlags( $flags );

		if ( !wfRunHooks( 'ArticleSave', array( &$this, &$user, &$text, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$status ) ) )
		{
			wfDebug( __METHOD__ . ": ArticleSave hook aborted save!\n" );

			if ( $status->isOK() ) {
				$status->fatal( 'edit-hook-aborted' );
			}

			wfProfileOut( __METHOD__ );
			return $status;
		}

		# Silently ignore EDIT_MINOR if not allowed
		$isminor = ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' );
		$bot = $flags & EDIT_FORCE_BOT;

		$oldtext = $this->getNativeData(); // current revision #FIXME: may not be a string. check Content model!
		$oldsize = strlen( $oldtext );
		$oldid = $this->getLatest();
		$oldIsRedirect = $this->isRedirect();
		$oldcountable = $this->isCountable();

		# Provide autosummaries if one is not provided and autosummaries are enabled.
		if ( $wgUseAutomaticEditSummaries && $flags & EDIT_AUTOSUMMARY && $summary == '' ) {
			$summary = self::getAutosummary( $oldtext, $text, $flags ); #FIXME: ContentHandler::getAutosummary()
		}

		$editInfo = $this->prepareTextForEdit( $text, null, $user );
		$text = $editInfo->pst;
		$newsize = strlen( $text );

		$dbw = wfGetDB( DB_MASTER );
		$now = wfTimestampNow();
		$this->mTimestamp = $now;

		if ( $flags & EDIT_UPDATE ) {
			# Update article, but only if changed.
			$status->value['new'] = false;

			if ( !$oldid ) {
				# Article gone missing
				wfDebug( __METHOD__ . ": EDIT_UPDATE specified but article doesn't exist\n" );
				$status->fatal( 'edit-gone-missing' );

				wfProfileOut( __METHOD__ );
				return $status;
			}

			# Make sure the revision is either completely inserted or not inserted at all
			if ( !$wgDBtransactions ) {
				$userAbort = ignore_user_abort( true );
			}

			$revision = new Revision( array(
				'page'       => $this->getId(),
				'comment'    => $summary,
				'minor_edit' => $isminor,
				'text'       => $text, #FIXME: set content instead, leave serialization to revision?!
				'parent_id'  => $oldid,
				'user'       => $user->getId(),
				'user_text'  => $user->getName(),
				'timestamp'  => $now
			) );

			$changed = ( strcmp( $text, $oldtext ) != 0 );

			if ( $changed ) {
				$dbw->begin();
				$revisionId = $revision->insertOn( $dbw );

				# Update page
				#
				# Note that we use $this->mLatest instead of fetching a value from the master DB
				# during the course of this function. This makes sure that EditPage can detect
				# edit conflicts reliably, either by $ok here, or by $article->getTimestamp()
				# before this function is called. A previous function used a separate query, this
				# creates a window where concurrent edits can cause an ignored edit conflict.
				$ok = $this->updateRevisionOn( $dbw, $revision, $oldid, $oldIsRedirect );

				if ( !$ok ) {
					/* Belated edit conflict! Run away!! */
					$status->fatal( 'edit-conflict' );

					# Delete the invalid revision if the DB is not transactional
					if ( !$wgDBtransactions ) {
						$dbw->delete( 'revision', array( 'rev_id' => $revisionId ), __METHOD__ );
					}

					$revisionId = 0;
					$dbw->rollback();
				} else {
					global $wgUseRCPatrol;
					wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, $baseRevId, $user ) );
					# Update recentchanges
					if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
						# Mark as patrolled if the user can do so
						$patrolled = $wgUseRCPatrol && !count(
							$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
						# Add RC row to the DB
						$rc = RecentChange::notifyEdit( $now, $this->mTitle, $isminor, $user, $summary,
							$oldid, $this->getTimestamp(), $bot, '', $oldsize, $newsize,
							$revisionId, $patrolled
						);

						# Log auto-patrolled edits
						if ( $patrolled ) {
							PatrolLog::record( $rc, true );
						}
					}
					$user->incEditCount();
					$dbw->commit();
				}
			} else {
				// Bug 32948: revision ID must be set to page {{REVISIONID}} and
				// related variables correctly
				$revision->setId( $this->getLatest() );
			}

			if ( !$wgDBtransactions ) {
				ignore_user_abort( $userAbort );
			}

			// Now that ignore_user_abort is restored, we can respond to fatal errors
			if ( !$status->isOK() ) {
				wfProfileOut( __METHOD__ );
				return $status;
			}

			# Update links tables, site stats, etc.
			$this->doEditUpdates( $revision, $user, array( 'changed' => $changed,
				'oldcountable' => $oldcountable ) );

			if ( !$changed ) {
				$status->warning( 'edit-no-change' );
				$revision = null;
				// Update page_touched, this is usually implicit in the page update
				// Other cache updates are done in onArticleEdit()
				$this->mTitle->invalidateCache();
			}
		} else {
			# Create new article
			$status->value['new'] = true;

			$dbw->begin();

			# Add the page record; stake our claim on this title!
			# This will return false if the article already exists
			$newid = $this->insertOn( $dbw );

			if ( $newid === false ) {
				$dbw->rollback();
				$status->fatal( 'edit-already-exists' );

				wfProfileOut( __METHOD__ );
				return $status;
			}

			# Save the revision text...
			$revision = new Revision( array(
				'page'       => $newid,
				'comment'    => $summary,
				'minor_edit' => $isminor,
				'text'       => $text,
				'user'       => $user->getId(),
				'user_text'  => $user->getName(),
				'timestamp'  => $now
			) );
			$revisionId = $revision->insertOn( $dbw );

			# Update the page record with revision data
			$this->updateRevisionOn( $dbw, $revision, 0 );

			wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, false, $user ) );

			# Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				global $wgUseRCPatrol, $wgUseNPPatrol;

				# Mark as patrolled if the user can do so
				$patrolled = ( $wgUseRCPatrol || $wgUseNPPatrol ) && !count(
					$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
				# Add RC row to the DB
				$rc = RecentChange::notifyNew( $now, $this->mTitle, $isminor, $user, $summary, $bot,
					'', strlen( $text ), $revisionId, $patrolled );

				# Log auto-patrolled edits
				if ( $patrolled ) {
					PatrolLog::record( $rc, true );
				}
			}
			$user->incEditCount();
			$dbw->commit();

			# Update links, etc.
			$this->doEditUpdates( $revision, $user, array( 'created' => true ) );

			wfRunHooks( 'ArticleInsertComplete', array( &$this, &$user, $text, $summary,
				$flags & EDIT_MINOR, null, null, &$flags, $revision ) );
		}

		# Do updates right now unless deferral was requested
		if ( !( $flags & EDIT_DEFER_UPDATES ) ) {
			DeferredUpdates::doUpdates();
		}

		// Return the new revision (or null) to the caller
		$status->value['revision'] = $revision;

		wfRunHooks( 'ArticleSaveComplete', array( &$this, &$user, $text, $summary,
			$flags & EDIT_MINOR, null, null, &$flags, $revision, &$status, $baseRevId ) );

		# Promote user to any groups they meet the criteria for
		$user->addAutopromoteOnceGroups( 'onEdit' );

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 * @param User|string $user User object or 'canonical'
	 * @return ParserOptions
	 */
	public function makeParserOptions( $user ) {
		global $wgContLang;
		if ( $user instanceof User ) { // settings per user (even anons)
			$options = ParserOptions::newFromUser( $user );
		} else { // canonical settings
			$options = ParserOptions::newFromUserAndLang( new User, $wgContLang );
		}
		$options->enableLimitReport(); // show inclusion/loop reports
		$options->setTidy( true ); // fix bad HTML
		return $options;
	}

	/**
	 * Prepare text which is about to be saved.
	 * Returns a stdclass with source, pst and output members
	 */
	public function prepareTextForEdit( $text, $revid = null, User $user = null ) {
		global $wgParser, $wgContLang, $wgUser;
		$user = is_null( $user ) ? $wgUser : $user;
		// @TODO fixme: check $user->getId() here???
		if ( $this->mPreparedEdit
			&& $this->mPreparedEdit->newText == $text
			&& $this->mPreparedEdit->revid == $revid
		) {
			// Already prepared
			return $this->mPreparedEdit;
		}

		$popts = ParserOptions::newFromUserAndLang( $user, $wgContLang );
		wfRunHooks( 'ArticlePrepareTextForEdit', array( $this, $popts ) );

		$edit = (object)array();
		$edit->revid = $revid;
		$edit->newText = $text;
		$edit->pst = $wgParser->preSaveTransform( $text, $this->mTitle, $user, $popts );
		$edit->popts = $this->makeParserOptions( 'canonical' );
		$edit->output = $wgParser->parse( $edit->pst, $this->mTitle, $edit->popts, true, true, $revid );
		$edit->oldText = $this->getRawText(); #FIXME: $oldcontent instead?!

		$this->mPreparedEdit = $edit;

		return $edit;
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @private
	 * @param $revision Revision object
	 * @param $user User object that did the revision
	 * @param $options Array of options, following indexes are used:
	 * - changed: boolean, whether the revision changed the content (default true)
	 * - created: boolean, whether the revision created the page (default false)
	 * - oldcountable: boolean or null (default null):
	 *   - boolean: whether the page was counted as an article before that
	 *     revision, only used in changed is true and created is false
	 *   - null: don't change the article count
	 */
	public function doEditUpdates( Revision $revision, User $user, array $options = array() ) {
		global $wgEnableParserCache;

		wfProfileIn( __METHOD__ );

		$options += array( 'changed' => true, 'created' => false, 'oldcountable' => null );
		$text = $revision->getText();

		# Parse the text
		# Be careful not to double-PST: $text is usually already PST-ed once
		if ( !$this->mPreparedEdit || $this->mPreparedEdit->output->getFlag( 'vary-revision' ) ) {
			wfDebug( __METHOD__ . ": No prepared edit or vary-revision is set...\n" );
			$editInfo = $this->prepareTextForEdit( $text, $revision->getId(), $user );
		} else {
			wfDebug( __METHOD__ . ": No vary-revision, using prepared edit...\n" );
			$editInfo = $this->mPreparedEdit;
		}

		# Save it to the parser cache
		if ( $wgEnableParserCache ) {
			$parserCache = ParserCache::singleton();
			$parserCache->save( $editInfo->output, $this, $editInfo->popts );
		}

		# Update the links tables
		$u = new LinksUpdate( $this->mTitle, $editInfo->output );
		$u->doUpdate();

		wfRunHooks( 'ArticleEditUpdates', array( &$this, &$editInfo, $options['changed'] ) );

		if ( wfRunHooks( 'ArticleEditUpdatesDeleteFromRecentchanges', array( &$this ) ) ) {
			if ( 0 == mt_rand( 0, 99 ) ) {
				// Flush old entries from the `recentchanges` table; we do this on
				// random requests so as to avoid an increase in writes for no good reason
				global $wgRCMaxAge;

				$dbw = wfGetDB( DB_MASTER );
				$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
				$dbw->delete(
					'recentchanges',
					array( "rc_timestamp < '$cutoff'" ),
					__METHOD__
				);
			}
		}

		if ( !$this->mTitle->exists() ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		$id = $this->getId();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( !$options['changed'] ) {
			$good = 0;
			$total = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$this->isCountable( $editInfo );
			$total = 1;
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable( $editInfo ) - (int)$options['oldcountable'];
			$total = 0;
		} else {
			$good = 0;
			$total = 0;
		}

		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 1, $good, $total ) );
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $title, $text ) );

		# If this is another user's talk page, update newtalk.
		# Don't do this if $options['changed'] = false (null-edits) nor if
		# it's a minor edit and the user doesn't want notifications for those.
		if ( $options['changed']
			&& $this->mTitle->getNamespace() == NS_USER_TALK
			&& $shortTitle != $user->getTitleKey()
			&& !( $revision->isMinor() && $user->isAllowed( 'nominornewtalk' ) )
		) {
			if ( wfRunHooks( 'ArticleEditUpdateNewTalk', array( &$this ) ) ) {
				$other = User::newFromName( $shortTitle, false );
				if ( !$other ) {
					wfDebug( __METHOD__ . ": invalid username\n" );
				} elseif ( User::isIP( $shortTitle ) ) {
					// An anonymous user
					$other->setNewtalk( true );
				} elseif ( $other->isLoggedIn() ) {
					$other->setNewtalk( true );
				} else {
					wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
				}
			}
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->replace( $shortTitle, $text );
		}

		if( $options['created'] ) {
			self::onArticleCreate( $this->mTitle );
		} else {
			self::onArticleEdit( $this->mTitle );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Edit an article without doing all that other stuff
	 * The article must already exist; link tables etc
	 * are not updated, caches are not flushed.
	 *
	 * @param $text String: text submitted
	 * @param $user User The relevant user
	 * @param $comment String: comment submitted
	 * @param $minor Boolean: whereas it's a minor modification
	 */
	public function doQuickEdit( $text, User $user, $comment = '', $minor = 0 ) {
		wfProfileIn( __METHOD__ );

		$dbw = wfGetDB( DB_MASTER );
		$revision = new Revision( array(
			'page'       => $this->getId(),
			'text'       => $text,
			'comment'    => $comment,
			'minor_edit' => $minor ? 1 : 0,
		) );
		$revision->insertOn( $dbw );
		$this->updateRevisionOn( $dbw, $revision );

		wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, false, $user ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param $limit Array: set of restriction keys
	 * @param $reason String
	 * @param &$cascade Integer. Set to false if cascading protection isn't allowed.
	 * @param $expiry Array: per restriction type expiration
	 * @param $user User The user updating the restrictions
	 * @return bool true on success
	 */
	public function doUpdateRestrictions( array $limit, array $expiry, &$cascade, $reason, User $user ) {
		global $wgContLang;

		if ( wfReadOnly() ) {
			return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		}

		$restrictionTypes = $this->mTitle->getRestrictionTypes();

		$id = $this->mTitle->getArticleID();

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		# @todo FIXME: Same limitations as described in ProtectionForm.php (line 37);
		# we expect a single selection, but the schema allows otherwise.
		$isProtected = false;
		$protect = false;
		$changed = false;

		$dbw = wfGetDB( DB_MASTER );

		foreach ( $restrictionTypes as $action ) {
			if ( !isset( $expiry[$action] ) ) {
				$expiry[$action] = $dbw->getInfinity();
			}
			if ( !isset( $limit[$action] ) ) {
				$limit[$action] = '';
			} elseif ( $limit[$action] != '' ) {
				$protect = true;
			}

			# Get current restrictions on $action
			$current = implode( '', $this->mTitle->getRestrictions( $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				# Only check expiry change if the action is actually being
				# protected, since expiry does nothing on an not-protected
				# action.
				if ( $this->mTitle->getRestrictionExpiry( $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $this->mTitle->areRestrictionsCascading() != $cascade ) {
			$changed = true;
		}

		# If nothing's changed, do nothing
		if ( !$changed ) {
			return Status::newGood();
		}

		if ( !$protect ) { # No protection at all means unprotection
			$revCommentMsg = 'unprotectedarticle';
			$logAction = 'unprotect';
		} elseif ( $isProtected ) {
			$revCommentMsg = 'modifiedarticleprotection';
			$logAction = 'modify';
		} else {
			$revCommentMsg = 'protectedarticle';
			$logAction = 'protect';
		}

		$encodedExpiry = array();
		$protectDescription = '';
		foreach ( $limit as $action => $restrictions ) {
			$encodedExpiry[$action] = $dbw->encodeExpiry( $expiry[$action] );
			if ( $restrictions != '' ) {
				$protectDescription .= $wgContLang->getDirMark() . "[$action=$restrictions] (";
				if ( $encodedExpiry[$action] != 'infinity' ) {
					$protectDescription .= wfMsgForContent( 'protect-expiring',
						$wgContLang->timeanddate( $expiry[$action], false, false ) ,
						$wgContLang->date( $expiry[$action], false, false ) ,
						$wgContLang->time( $expiry[$action], false, false ) );
				} else {
					$protectDescription .= wfMsgForContent( 'protect-expiry-indefinite' );
				}

				$protectDescription .= ') ';
			}
		}
		$protectDescription = trim( $protectDescription );

		if ( $id ) { # Protection of existing page
			if ( !wfRunHooks( 'ArticleProtect', array( &$this, &$user, $limit, $reason ) ) ) {
				return Status::newGood();
			}

			# Only restrictions with the 'protect' right can cascade...
			# Otherwise, people who cannot normally protect can "protect" pages via transclusion
			$editrestriction = isset( $limit['edit'] ) ? array( $limit['edit'] ) : $this->mTitle->getRestrictions( 'edit' );

			# The schema allows multiple restrictions
			if ( !in_array( 'protect', $editrestriction ) && !in_array( 'sysop', $editrestriction ) ) {
				$cascade = false;
			}

			# Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
				if ( $restrictions != '' ) {
					$dbw->replace( 'page_restrictions', array( array( 'pr_page', 'pr_type' ) ),
						array( 'pr_page' => $id,
							'pr_type' => $action,
							'pr_level' => $restrictions,
							'pr_cascade' => ( $cascade && $action == 'edit' ) ? 1 : 0,
							'pr_expiry' => $encodedExpiry[$action]
						),
						__METHOD__
					);
				} else {
					$dbw->delete( 'page_restrictions', array( 'pr_page' => $id,
						'pr_type' => $action ), __METHOD__ );
				}
			}

			# Prepare a null revision to be added to the history
			$editComment = $wgContLang->ucfirst( wfMsgForContent( $revCommentMsg, $this->mTitle->getPrefixedText() ) );
			if ( $reason ) {
				$editComment .= ": $reason";
			}
			if ( $protectDescription ) {
				$editComment .= " ($protectDescription)";
			}
			if ( $cascade ) {
				$editComment .= ' [' . wfMsgForContent( 'protect-summary-cascade' ) . ']';
			}

			# Insert a null revision
			$nullRevision = Revision::newNullRevision( $dbw, $id, $editComment, true );
			$nullRevId = $nullRevision->insertOn( $dbw );

			$latest = $this->getLatest();
			# Update page record
			$dbw->update( 'page',
				array( /* SET */
					'page_touched' => $dbw->timestamp(),
					'page_restrictions' => '',
					'page_latest' => $nullRevId
				), array( /* WHERE */
					'page_id' => $id
				), __METHOD__
			);

			wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $nullRevision, $latest, $user ) );
			wfRunHooks( 'ArticleProtectComplete', array( &$this, &$user, $limit, $reason ) );
		} else { # Protection of non-existing page (also known as "title protection")
			# Cascade protection is meaningless in this case
			$cascade = false;

			if ( $limit['create'] != '' ) {
				$dbw->replace( 'protected_titles',
					array( array( 'pt_namespace', 'pt_title' ) ),
					array(
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey(),
						'pt_create_perm' => $limit['create'],
						'pt_timestamp' => $dbw->encodeExpiry( wfTimestampNow() ),
						'pt_expiry' => $encodedExpiry['create'],
						'pt_user' => $user->getId(),
						'pt_reason' => $reason,
					), __METHOD__
				);
			} else {
				$dbw->delete( 'protected_titles',
					array(
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey()
					), __METHOD__
				);
			}
		}

		$this->mTitle->flushRestrictions();

		if ( $logAction == 'unprotect' ) {
			$logParams = array();
		} else {
			$logParams = array( $protectDescription, $cascade ? 'cascade' : '' );
		}

		# Update the protection log
		$log = new LogPage( 'protect' );
		$log->addEntry( $logAction, $this->mTitle, trim( $reason ), $logParams, $user );

		return Status::newGood();
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 * @param $limit Array
	 * @return String
	 */
	protected static function flattenRestrictions( $limit ) {
		if ( !is_array( $limit ) ) {
			throw new MWException( 'WikiPage::flattenRestrictions given non-array restriction set' );
		}

		$bits = array();
		ksort( $limit );

		foreach ( $limit as $action => $restrictions ) {
			if ( $restrictions != '' ) {
				$bits[] = "$action=$restrictions";
			}
		}

		return implode( ':', $bits );
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param $reason string delete reason for deletion log
	 * @param $suppress bitfield
	 * 	Revision::DELETED_TEXT
	 * 	Revision::DELETED_COMMENT
	 * 	Revision::DELETED_USER
	 * 	Revision::DELETED_RESTRICTED
	 * @param $id int article ID
	 * @param $commit boolean defaults to true, triggers transaction end
	 * @param &$errors Array of errors to append to
	 * @param $user User The relevant user
	 * @return boolean true if successful
	 */
	public function doDeleteArticle(
		$reason, $suppress = false, $id = 0, $commit = true, &$error = '', User $user = null
	) {
		global $wgUser;
		$user = is_null( $user ) ? $wgUser : $user;

		wfDebug( __METHOD__ . "\n" );

		if ( ! wfRunHooks( 'ArticleDelete', array( &$this, &$user, &$reason, &$error ) ) ) {
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$t = $this->mTitle->getDBkey();
		$id = $id ? $id : $this->mTitle->getArticleID( Title::GAID_FOR_UPDATE );

		if ( $t === '' || $id == 0 ) {
			return false;
		}

		// Bitfields to further suppress the content
		if ( $suppress ) {
			$bitfield = 0;
			// This should be 15...
			$bitfield |= Revision::DELETED_TEXT;
			$bitfield |= Revision::DELETED_COMMENT;
			$bitfield |= Revision::DELETED_USER;
			$bitfield |= Revision::DELETED_RESTRICTED;
		} else {
			$bitfield = 'rev_deleted';
		}

		$dbw->begin();
		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		//
		// For backwards compatibility, note that some older archive
		// table entries will have ar_text and ar_flags fields still.
		//
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.
		$dbw->insertSelect( 'archive', array( 'page', 'revision' ),
			array(
				'ar_namespace'  => 'page_namespace',
				'ar_title'      => 'page_title',
				'ar_comment'    => 'rev_comment',
				'ar_user'       => 'rev_user',
				'ar_user_text'  => 'rev_user_text',
				'ar_timestamp'  => 'rev_timestamp',
				'ar_minor_edit' => 'rev_minor_edit',
				'ar_rev_id'     => 'rev_id',
				'ar_parent_id'  => 'rev_parent_id',
				'ar_text_id'    => 'rev_text_id',
				'ar_text'       => '\'\'', // Be explicit to appease
				'ar_flags'      => '\'\'', // MySQL's "strict mode"...
				'ar_len'        => 'rev_len',
				'ar_page_id'    => 'page_id',
				'ar_deleted'    => $bitfield,
				'ar_sha1'       => 'rev_content_model',
				'ar_content_format'       => 'rev_content_format',
				'ar_content_format'       => 'rev_sha1'
			), array(
				'page_id' => $id,
				'page_id = rev_page'
			), __METHOD__
		);

		# Now that it's safely backed up, delete it
		$dbw->delete( 'page', array( 'page_id' => $id ), __METHOD__ );
		$ok = ( $dbw->affectedRows() > 0 ); // getArticleId() uses slave, could be laggy

		if ( !$ok ) {
			$dbw->rollback();
			return false;
		}

		$this->doDeleteUpdates( $id );

		# Log the deletion, if the page was suppressed, log it at Oversight instead
		$logtype = $suppress ? 'suppress' : 'delete';

		$logEntry = new ManualLogEntry( $logtype, 'delete' );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $this->mTitle );
		$logEntry->setComment( $reason );
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );

		if ( $commit ) {
			$dbw->commit();
		}

		wfRunHooks( 'ArticleDeleteComplete', array( &$this, &$user, $reason, $id ) );
		return true;
	}

	/**
	 * Do some database updates after deletion
	 *
	 * @param $id Int: page_id value of the page being deleted
	 */
	public function doDeleteUpdates( $id ) {
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 1, - (int)$this->isCountable(), -1 ) );

		$dbw = wfGetDB( DB_MASTER );

		# Delete restrictions for it
		$dbw->delete( 'page_restrictions', array ( 'pr_page' => $id ), __METHOD__ );

		# Fix category table counts
		$cats = array();
		$res = $dbw->select( 'categorylinks', 'cl_to', array( 'cl_from' => $id ), __METHOD__ );

		foreach ( $res as $row ) {
			$cats [] = $row->cl_to;
		}

		$this->updateCategoryCounts( array(), $cats );

		# If using cascading deletes, we can skip some explicit deletes
		if ( !$dbw->cascadingDeletes() ) {
			$dbw->delete( 'revision', array( 'rev_page' => $id ), __METHOD__ );

			# Delete outgoing links
			$dbw->delete( 'pagelinks', array( 'pl_from' => $id ), __METHOD__ );
			$dbw->delete( 'imagelinks', array( 'il_from' => $id ), __METHOD__ );
			$dbw->delete( 'categorylinks', array( 'cl_from' => $id ), __METHOD__ );
			$dbw->delete( 'templatelinks', array( 'tl_from' => $id ), __METHOD__ );
			$dbw->delete( 'externallinks', array( 'el_from' => $id ), __METHOD__ );
			$dbw->delete( 'langlinks', array( 'll_from' => $id ), __METHOD__ );
			$dbw->delete( 'iwlinks', array( 'iwl_from' => $id ), __METHOD__ );
			$dbw->delete( 'redirect', array( 'rd_from' => $id ), __METHOD__ );
			$dbw->delete( 'page_props', array( 'pp_page' => $id ), __METHOD__ );
		}

		# If using cleanup triggers, we can skip some manual deletes
		if ( !$dbw->cleanupTriggers() ) {
			# Clean up recentchanges entries...
			$dbw->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG,
					'rc_namespace' => $this->mTitle->getNamespace(),
					'rc_title' => $this->mTitle->getDBkey() ),
				__METHOD__ );
			$dbw->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ),
				__METHOD__ );
		}

		# Clear caches
		self::onArticleDelete( $this->mTitle );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );
	}

	/**
	 * Roll back the most recent consecutive set of edits to a page
	 * from the same user; fails if there are no eligible edits to
	 * roll back to, e.g. user is the sole contributor. This function
	 * performs permissions checks on $user, then calls commitRollback()
	 * to do the dirty work
	 * 
	 * @todo: seperate the business/permission stuff out from backend code
	 *
	 * @param $fromP String: Name of the user whose edits to rollback.
	 * @param $summary String: Custom summary. Set to default summary if empty.
	 * @param $token String: Rollback token.
	 * @param $bot Boolean: If true, mark all reverted edits as bot.
	 *
	 * @param $resultDetails Array: contains result-specific array of additional values
	 *    'alreadyrolled' : 'current' (rev)
	 *    success        : 'summary' (str), 'current' (rev), 'target' (rev)
	 *
	 * @param $user User The user performing the rollback
	 * @return array of errors, each error formatted as
	 *   array(messagekey, param1, param2, ...).
	 * On success, the array is empty.  This array can also be passed to
	 * OutputPage::showPermissionsErrorPage().
	 */
	public function doRollback(
		$fromP, $summary, $token, $bot, &$resultDetails, User $user
	) {
		$resultDetails = null;

		# Check permissions
		$editErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $user );
		$rollbackErrors = $this->mTitle->getUserPermissionsErrors( 'rollback', $user );
		$errors = array_merge( $editErrors, wfArrayDiff2( $rollbackErrors, $editErrors ) );

		if ( !$user->matchEditToken( $token, array( $this->mTitle->getPrefixedText(), $fromP ) ) ) {
			$errors[] = array( 'sessionfailure' );
		}

		if ( $user->pingLimiter( 'rollback' ) || $user->pingLimiter() ) {
			$errors[] = array( 'actionthrottledtext' );
		}

		# If there were errors, bail out now
		if ( !empty( $errors ) ) {
			return $errors;
		}

		return $this->commitRollback( $fromP, $summary, $bot, $resultDetails, $user );
	}

	/**
	 * Backend implementation of doRollback(), please refer there for parameter
	 * and return value documentation
	 *
	 * NOTE: This function does NOT check ANY permissions, it just commits the
	 * rollback to the DB. Therefore, you should only call this function direct-
	 * ly if you want to use custom permissions checks. If you don't, use
	 * doRollback() instead.
	 * @param $fromP String: Name of the user whose edits to rollback.
	 * @param $summary String: Custom summary. Set to default summary if empty.
	 * @param $bot Boolean: If true, mark all reverted edits as bot.
	 *
	 * @param $resultDetails Array: contains result-specific array of additional values
	 * @param $guser User The user performing the rollback
	 */
	public function commitRollback( $fromP, $summary, $bot, &$resultDetails, User $guser ) {
		global $wgUseRCPatrol, $wgContLang;

		$dbw = wfGetDB( DB_MASTER );

		if ( wfReadOnly() ) {
			return array( array( 'readonlytext' ) );
		}

		# Get the last editor
		$current = Revision::newFromTitle( $this->mTitle );
		if ( is_null( $current ) ) {
			# Something wrong... no page?
			return array( array( 'notanarticle' ) );
		}

		$from = str_replace( '_', ' ', $fromP );
		# User name given should match up with the top revision.
		# If the user was deleted then $from should be empty.
		if ( $from != $current->getUserText() ) {
			$resultDetails = array( 'current' => $current );
			return array( array( 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
			) );
		}

		# Get the last edit not by this guy...
		# Note: these may not be public values
		$user = intval( $current->getRawUser() );
		$user_text = $dbw->addQuotes( $current->getRawUserText() );
		$s = $dbw->selectRow( 'revision',
			array( 'rev_id', 'rev_timestamp', 'rev_deleted' ),
			array( 'rev_page' => $current->getPage(),
				"rev_user != {$user} OR rev_user_text != {$user_text}"
			), __METHOD__,
			array( 'USE INDEX' => 'page_timestamp',
				'ORDER BY' => 'rev_timestamp DESC' )
			);
		if ( $s === false ) {
			# No one else ever edited this page
			return array( array( 'cantrollback' ) );
		} elseif ( $s->rev_deleted & Revision::DELETED_TEXT || $s->rev_deleted & Revision::DELETED_USER ) {
			# Only admins can see this text
			return array( array( 'notvisiblerev' ) );
		}

		$set = array();
		if ( $bot && $guser->isAllowed( 'markbotedits' ) ) {
			# Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}

		if ( $wgUseRCPatrol ) {
			# Mark all reverted edits as patrolled
			$set['rc_patrolled'] = 1;
		}

		if ( count( $set ) ) {
			$dbw->update( 'recentchanges', $set,
				array( /* WHERE */
					'rc_cur_id' => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
					"rc_timestamp > '{$s->rev_timestamp}'",
				), __METHOD__
			);
		}

		# Generate the edit summary if necessary
		$target = Revision::newFromId( $s->rev_id );
		if ( empty( $summary ) ) {
			if ( $from == '' ) { // no public user name
				$summary = wfMsgForContent( 'revertpage-nouser' );
			} else {
				$summary = wfMsgForContent( 'revertpage' );
			}
		}

		# Allow the custom summary to use the same args as the default message
		$args = array(
			$target->getUserText(), $from, $s->rev_id,
			$wgContLang->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ) ),
			$current->getId(), $wgContLang->timeanddate( $current->getTimestamp() )
		);
		$summary = wfMsgReplaceArgs( $summary, $args );

		# Save
		$flags = EDIT_UPDATE;

		if ( $guser->isAllowed( 'minoredit' ) ) {
			$flags |= EDIT_MINOR;
		}

		if ( $bot && ( $guser->isAllowedAny( 'markbotedits', 'bot' ) ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		# Actually store the edit
		$status = $this->doEdit( $target->getText(), $summary, $flags, $target->getId(), $guser );
		if ( !empty( $status->value['revision'] ) ) {
			$revId = $status->value['revision']->getId();
		} else {
			$revId = false;
		}

		wfRunHooks( 'ArticleRollbackComplete', array( $this, $guser, $target, $current ) );

		$resultDetails = array(
			'summary' => $summary,
			'current' => $current,
			'target'  => $target,
			'newid'   => $revId
		);

		return array();
	}

	/**
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 *
	 * This is a good place to put code to clear caches, for instance.
	 *
	 * This is called on page move and undelete, as well as edit
	 *
	 * @param $title Title object
	 */
	public static function onArticleCreate( $title ) {
		# Update existence markers on article/talk tabs...
		if ( $title->isTalkPage() ) {
			$other = $title->getSubjectPage();
		} else {
			$other = $title->getTalkPage();
		}

		$other->invalidateCache();
		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();
		$title->deleteTitleProtection();
	}

	/**
	 * Clears caches when article is deleted
	 *
	 * @param $title Title
	 */
	public static function onArticleDelete( $title ) {
		# Update existence markers on article/talk tabs...
		if ( $title->isTalkPage() ) {
			$other = $title->getSubjectPage();
		} else {
			$other = $title->getTalkPage();
		}

		$other->invalidateCache();
		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();

		# File cache
		HTMLFileCache::clearFileCache( $title );

		# Messages
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->replace( $title->getDBkey(), false );
		}

		# Images
		if ( $title->getNamespace() == NS_FILE ) {
			$update = new HTMLCacheUpdate( $title, 'imagelinks' );
			$update->doUpdate();
		}

		# User talk pages
		if ( $title->getNamespace() == NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			if ( $user ) {
				$user->setNewtalk( false );
			}
		}

		# Image redirects
		RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param $title Title object
	 * @todo:  verify that $title is always a Title object (and never false or null), add Title hint to parameter $title
	 */
	public static function onArticleEdit( $title ) {
		// Invalidate caches of articles which include this page
		DeferredUpdates::addHTMLCacheUpdate( $title, 'templatelinks' );


		// Invalidate the caches of all pages which redirect here
		DeferredUpdates::addHTMLCacheUpdate( $title, 'redirect' );

		# Purge squid for this page only
		$title->purgeSquid();

		# Clear file cache for this page only
		HTMLFileCache::clearFileCache( $title );
	}

	/**#@-*/

	/**
	 * Returns a list of hidden categories this page is a member of.
	 * Uses the page_props and categorylinks tables.
	 *
	 * @return Array of Title objects
	 */
	public function getHiddenCategories() {
		$result = array();
		$id = $this->mTitle->getArticleID();

		if ( $id == 0 ) {
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'categorylinks', 'page_props', 'page' ),
			array( 'cl_to' ),
			array( 'cl_from' => $id, 'pp_page=page_id', 'pp_propname' => 'hiddencat',
				'page_namespace' => NS_CATEGORY, 'page_title=cl_to' ),
			__METHOD__ );

		if ( $res !== false ) {
			foreach ( $res as $row ) {
				$result[] = Title::makeTitle( NS_CATEGORY, $row->cl_to );
			}
		}

		return $result;
	}

	/**
	* Return an applicable autosummary if one exists for the given edit.
	* @param $oldtext String: the previous text of the page.
	* @param $newtext String: The submitted text of the page.
	* @param $flags Int bitmask: a bitmask of flags submitted for the edit.
	* @return string An appropriate autosummary, or an empty string.
    * @deprecated since 1.20, use ContentHandler::getAutosummary() instead
	*/
	public static function getAutosummary( $oldtext, $newtext, $flags ) {
		# NOTE: stub for backwards-compatibility. assumes the given text is wikitext. will break horribly if it isn't.

        $handler = ContentHandler::getForModelName( CONTENT_MODEL_WIKITEXT );
        $oldContent = $handler->unserialize( $oldtext );
        $newContent = $handler->unserialize( $newtext );

        return $handler->getAutosummary( $oldContent, $newContent, $flags );
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @param &$hasHistory Boolean: whether the page has a history
	 * @return mixed String containing deletion reason or empty string, or boolean false
	 *    if no revision occurred
     * @deprecated since 1.20, use ContentHandler::getAutoDeleteReason() instead
	 */
	public function getAutoDeleteReason( &$hasHistory ) {
        #NOTE: stub for backwards-compatibility.

		$handler = ContentHandler::getForTitle( $this->getTitle() );
        $handler->getAutoDeleteReason( $this->getTitle(), $hasHistory );
	}

	/**
	 * Update all the appropriate counts in the category table, given that
	 * we've added the categories $added and deleted the categories $deleted.
	 *
	 * @param $added array   The names of categories that were added
	 * @param $deleted array The names of categories that were deleted
	 */
	public function updateCategoryCounts( $added, $deleted ) {
		$ns = $this->mTitle->getNamespace();
		$dbw = wfGetDB( DB_MASTER );

		# First make sure the rows exist.  If one of the "deleted" ones didn't
		# exist, we might legitimately not create it, but it's simpler to just
		# create it and then give it a negative value, since the value is bogus
		# anyway.
		#
		# Sometimes I wish we had INSERT ... ON DUPLICATE KEY UPDATE.
		$insertCats = array_merge( $added, $deleted );
		if ( !$insertCats ) {
			# Okay, nothing to do
			return;
		}

		$insertRows = array();

		foreach ( $insertCats as $cat ) {
			$insertRows[] = array(
				'cat_id' => $dbw->nextSequenceValue( 'category_cat_id_seq' ),
				'cat_title' => $cat
			);
		}
		$dbw->insert( 'category', $insertRows, __METHOD__, 'IGNORE' );

		$addFields    = array( 'cat_pages = cat_pages + 1' );
		$removeFields = array( 'cat_pages = cat_pages - 1' );

		if ( $ns == NS_CATEGORY ) {
			$addFields[]    = 'cat_subcats = cat_subcats + 1';
			$removeFields[] = 'cat_subcats = cat_subcats - 1';
		} elseif ( $ns == NS_FILE ) {
			$addFields[]    = 'cat_files = cat_files + 1';
			$removeFields[] = 'cat_files = cat_files - 1';
		}

		if ( $added ) {
			$dbw->update(
				'category',
				$addFields,
				array( 'cat_title' => $added ),
				__METHOD__
			);
		}

		if ( $deleted ) {
			$dbw->update(
				'category',
				$removeFields,
				array( 'cat_title' => $deleted ),
				__METHOD__
			);
		}
	}

	/**
	 * Updates cascading protections
	 *
	 * @param $parserOutput ParserOutput object for the current version
	 */
	public function doCascadeProtectionUpdates( ParserOutput $parserOutput ) {
		if ( wfReadOnly() || !$this->mTitle->areRestrictionsCascading() ) {
			return;
		}

		// templatelinks table may have become out of sync,
		// especially if using variable-based transclusions.
		// For paranoia, check if things have changed and if
		// so apply updates to the database. This will ensure
		// that cascaded protections apply as soon as the changes
		// are visible.

		# Get templates from templatelinks
		$id = $this->mTitle->getArticleID();

		$tlTemplates = array();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'templatelinks' ),
			array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $id ),
			__METHOD__
		);

		foreach ( $res as $row ) {
			$tlTemplates["{$row->tl_namespace}:{$row->tl_title}"] = true;
		}

		# Get templates from parser output.
		$poTemplates = array();
		foreach ( $parserOutput->getTemplates() as $ns => $templates ) {
			foreach ( $templates as $dbk => $id ) {
				$poTemplates["$ns:$dbk"] = true;
			}
		}

		# Get the diff
		$templates_diff = array_diff_key( $poTemplates, $tlTemplates );

		if ( count( $templates_diff ) > 0 ) {
			# Whee, link updates time.
			$u = new LinksUpdate( $this->mTitle, $parserOutput, false );
			$u->doUpdate();
		}
	}

	/**
	 * Return a list of templates used by this article.
	 * Uses the templatelinks table
	 *
	 * @deprecated in 1.19; use Title::getTemplateLinksFrom()
	 * @return Array of Title objects
	 */
	public function getUsedTemplates() {
		return $this->mTitle->getTemplateLinksFrom();
	}

	/**
	 * Perform article updates on a special page creation.
	 *
	 * @param $rev Revision object
	 *
	 * @todo This is a shitty interface function. Kill it and replace the
	 * other shitty functions like doEditUpdates and such so it's not needed
	 * anymore.
	 * @deprecated since 1.18, use doEditUpdates()
	 */
	public function createUpdates( $rev ) {
		wfDeprecated( __METHOD__, '1.18' );
		global $wgUser;
		$this->doEditUpdates( $rev, $wgUser, array( 'created' => true ) );
	}

	/**
	 * This function is called right before saving the wikitext,
	 * so we can do things like signatures and links-in-context.
	 *
	 * @deprecated in 1.19; use Parser::preSaveTransform() instead
	 * @param $text String article contents
	 * @param $user User object: user doing the edit
	 * @param $popts ParserOptions object: parser options, default options for
	 *               the user loaded if null given
	 * @return string article contents with altered wikitext markup (signatures
	 * 	converted, {{subst:}}, templates, etc.)
	 */
	public function preSaveTransform( $text, User $user = null, ParserOptions $popts = null ) {
		global $wgParser, $wgUser;

		wfDeprecated( __METHOD__, '1.19' );

		$user = is_null( $user ) ? $wgUser : $user;

		if ( $popts === null ) {
			$popts = ParserOptions::newFromUser( $user );
		}

		return $wgParser->preSaveTransform( $text, $this->mTitle, $user, $popts );
	}

	/**
	 * Check whether the number of revisions of this page surpasses $wgDeleteRevisionsLimit
	 *
	 * @deprecated in 1.19; use Title::isBigDeletion() instead.
	 * @return bool
	 */
	public function isBigDeletion() {
		wfDeprecated( __METHOD__, '1.19' );
		return $this->mTitle->isBigDeletion();
	}

	/**
	 * Get the  approximate revision count of this page.
	 *
	 * @deprecated in 1.19; use Title::estimateRevisionCount() instead.
	 * @return int
	 */
	public function estimateRevisionCount() {
		wfDeprecated( __METHOD__, '1.19' );
		return $this->mTitle->estimateRevisionCount();
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 *
	 * @deprecated since 1.19
	 * @param $limit Array: set of restriction keys
	 * @param $reason String
	 * @param &$cascade Integer. Set to false if cascading protection isn't allowed.
	 * @param $expiry Array: per restriction type expiration
	 * @param $user User The user updating the restrictions
	 * @return bool true on success
	 */
	public function updateRestrictions(
		$limit = array(), $reason = '', &$cascade = 0, $expiry = array(), User $user = null
	) {
		global $wgUser;

		$user = is_null( $user ) ? $wgUser : $user;

		return $this->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $user )->isOK();
	}

	/**
	 * @deprecated since 1.18
	 */
	public function quickEdit( $text, $comment = '', $minor = 0 ) {
		wfDeprecated( __METHOD__, '1.18' );
		global $wgUser;
		return $this->doQuickEdit( $text, $wgUser, $comment, $minor );
	}

	/**
	 * @deprecated since 1.18
	 */
	public function viewUpdates() {
		wfDeprecated( __METHOD__, '1.18' );
		global $wgUser;
		return $this->doViewUpdates( $wgUser );
	}

	/**
	 * @deprecated since 1.18
	 */
	public function useParserCache( $oldid ) {
		wfDeprecated( __METHOD__, '1.18' );
		global $wgUser;
		return $this->isParserCacheUsed( ParserOptions::newFromUser( $wgUser ), $oldid );
	}
}

class PoolWorkArticleView extends PoolCounterWork {

	/**
	 * @var Page
	 */
	private $page;

	/**
	 * @var string
	 */
	private $cacheKey;

	/**
	 * @var integer
	 */
	private $revid;

	/**
	 * @var ParserOptions
	 */
	private $parserOptions;

	/**
	 * @var string|null
	 */
	private $text;

	/**
	 * @var ParserOutput|false
	 */
	private $parserOutput = false;

	/**
	 * @var bool
	 */
	private $isDirty = false;

	/**
	 * @var Status|false
	 */
	private $error = false;

	/**
	 * Constructor
	 *
	 * @param $page Page
	 * @param $revid Integer: ID of the revision being parsed
	 * @param $useParserCache Boolean: whether to use the parser cache
	 * @param $parserOptions parserOptions to use for the parse operation
	 * @param $content Content|String: content to parse or null to load it; may also be given as a wikitext string, for BC
	 */
	function __construct( Page $page, ParserOptions $parserOptions, $revid, $useParserCache, $content = null ) {
        if ( is_string($content) ) { #BC: old style call
            $modelName = $page->getRevision()->getContentModelName();
            $format = $page->getRevision()->getContentFormat();
            $content = ContentHandler::makeContent( $content, $page->getTitle(), $modelName, $format );
        }

		$this->page = $page;
		$this->revid = $revid;
		$this->cacheable = $useParserCache;
		$this->parserOptions = $parserOptions;
		$this->content = $content;
		$this->cacheKey = ParserCache::singleton()->getKey( $page, $parserOptions );
		parent::__construct( 'ArticleView', $this->cacheKey . ':revid:' . $revid );
	}

	/**
	 * Get the ParserOutput from this object, or false in case of failure
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput() {
		return $this->parserOutput;
	}

	/**
	 * Get whether the ParserOutput is a dirty one (i.e. expired)
	 *
	 * @return bool
	 */
	public function getIsDirty() {
		return $this->isDirty;
	}

	/**
	 * Get a Status object in case of error or false otherwise
	 *
	 * @return Status|false
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @return bool
	 */
	function doWork() {
		global $wgParser, $wgUseFileCache;

		$isCurrent = $this->revid === $this->page->getLatest();

		if ( $this->content !== null ) {
			$content = $this->content;
		} elseif ( $isCurrent ) {
            $content = $this->page->getContent( Revision::RAW ); #XXX: why use RAW audience here, and PUBLIC (default) below?
		} else {
			$rev = Revision::newFromTitle( $this->page->getTitle(), $this->revid );
			if ( $rev === null ) {
				return false;
			}
            $content = $rev->getContent(); #XXX: why use PUBLIC audience here (default), and RAW above?
		}

		$time = - wfTime();
		$this->parserOutput = $content->getParserOutput( $this->parserOptions );
		$time += wfTime();

		# Timing hack
		if ( $time > 3 ) {
			wfDebugLog( 'slow-parse', sprintf( "%-5.2f %s", $time,
				$this->page->getTitle()->getPrefixedDBkey() ) );
		}

		if ( $this->cacheable && $this->parserOutput->isCacheable() ) {
			ParserCache::singleton()->save( $this->parserOutput, $this->page, $this->parserOptions );
		}

		// Make sure file cache is not used on uncacheable content.
		// Output that has magic words in it can still use the parser cache
		// (if enabled), though it will generally expire sooner.
		if ( !$this->parserOutput->isCacheable() || $this->parserOutput->containsOldMagic() ) {
			$wgUseFileCache = false;
		}

		if ( $isCurrent ) {
			$this->page->doCascadeProtectionUpdates( $this->parserOutput );
		}

		return true;
	}

	/**
	 * @return bool
	 */
	function getCachedWork() {
		$this->parserOutput = ParserCache::singleton()->get( $this->page, $this->parserOptions );

		if ( $this->parserOutput === false ) {
			wfDebug( __METHOD__ . ": parser cache miss\n" );
			return false;
		} else {
			wfDebug( __METHOD__ . ": parser cache hit\n" );
			return true;
		}
	}

	/**
	 * @return bool
	 */
	function fallback() {
		$this->parserOutput = ParserCache::singleton()->getDirty( $this->page, $this->parserOptions );

		if ( $this->parserOutput === false ) {
			wfDebugLog( 'dirty', "dirty missing\n" );
			wfDebug( __METHOD__ . ": no dirty cache\n" );
			return false;
		} else {
			wfDebug( __METHOD__ . ": sending dirty output\n" );
			wfDebugLog( 'dirty', "dirty output {$this->cacheKey}\n" );
			$this->isDirty = true;
			return true;
		}
	}

	/**
	 * @param $status Status
	 */
	function error( $status ) {
		$this->error = $status;
		return false;
	}
}
