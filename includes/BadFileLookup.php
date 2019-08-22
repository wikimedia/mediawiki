<?php

namespace MediaWiki;

use BagOStuff;
use Hooks;
use MalformedTitleException;
use MediaWiki\Linker\LinkTarget;
use RepoGroup;
use TitleParser;

class BadFileLookup {
	/** @var callable Returns contents of blacklist (see comment for isBadFile()) */
	private $blacklistCallback;

	/** @var BagOStuff Cache of parsed bad image list */
	private $cache;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var TitleParser */
	private $titleParser;

	/** @var array|null Parsed blacklist */
	private $badFiles;

	/**
	 * Do not call directly. Use MediaWikiServices.
	 *
	 * @param callable $blacklistCallback Callback that returns wikitext of a file blacklist
	 * @param BagOStuff $cache For caching parsed versions of the blacklist
	 * @param RepoGroup $repoGroup
	 * @param TitleParser $titleParser
	 */
	public function __construct(
		callable $blacklistCallback,
		BagOStuff $cache,
		RepoGroup $repoGroup,
		TitleParser $titleParser
	) {
		$this->blacklistCallback = $blacklistCallback;
		$this->cache = $cache;
		$this->repoGroup = $repoGroup;
		$this->titleParser = $titleParser;
	}

	/**
	 * Determine if a file exists on the 'bad image list'.
	 *
	 * The format of MediaWiki:Bad_image_list is as follows:
	 *    * Only list items (lines starting with "*") are considered
	 *    * The first link on a line must be a link to a bad file
	 *    * Any subsequent links on the same line are considered to be exceptions,
	 *      i.e. articles where the file may occur inline.
	 *
	 * @param string $name The file name to check
	 * @param LinkTarget|null $contextTitle The page on which the file occurs, if known
	 * @return bool
	 */
	public function isBadFile( $name, LinkTarget $contextTitle = null ) {
		// Handle redirects; callers almost always hit wfFindFile() anyway, so just use that method
		// because it has a fast process cache.
		$file = $this->repoGroup->findFile( $name );
		// XXX If we don't find the file we also don't replace spaces by underscores or otherwise
		// validate or normalize the title, is this right?
		if ( $file ) {
			$name = $file->getTitle()->getDBkey();
		}

		// Run the extension hook
		$bad = false;
		if ( !Hooks::run( 'BadImage', [ $name, &$bad ] ) ) {
			return (bool)$bad;
		}

		if ( $this->badFiles === null ) {
			// Not used before in this request, try the cache
			$blacklist = ( $this->blacklistCallback )();
			$key = $this->cache->makeKey( 'bad-image-list', sha1( $blacklist ) );
			$this->badFiles = $this->cache->get( $key ) ?: null;
		}

		if ( $this->badFiles === null ) {
			// Cache miss, build the list now
			$this->badFiles = [];
			$lines = explode( "\n", $blacklist );
			foreach ( $lines as $line ) {
				// List items only
				if ( substr( $line, 0, 1 ) !== '*' ) {
					continue;
				}

				// Find all links
				$m = [];
				// XXX What is the ':?' doing in the regex? Why not let the TitleParser strip it?
				if ( !preg_match_all( '/\[\[:?(.*?)\]\]/', $line, $m ) ) {
					continue;
				}

				$fileDBkey = null;
				$exceptions = [];
				foreach ( $m[1] as $i => $titleText ) {
					try {
						$title = $this->titleParser->parseTitle( $titleText );
					} catch ( MalformedTitleException $e ) {
						continue;
					}
					if ( $i == 0 ) {
						$fileDBkey = $title->getDBkey();
					} else {
						$exceptions[$title->getNamespace()][$title->getDBkey()] = true;
					}
				}

				if ( $fileDBkey !== null ) {
					$this->badFiles[$fileDBkey] = $exceptions;
				}
			}
			$this->cache->set( $key, $this->badFiles, 24 * 60 * 60 );
		}

		return isset( $this->badFiles[$name] ) && ( !$contextTitle ||
			!isset( $this->badFiles[$name][$contextTitle->getNamespace()]
				[$contextTitle->getDBkey()] ) );
	}
}
