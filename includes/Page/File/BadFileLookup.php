<?php

namespace MediaWiki\Page\File;

use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\TitleParser;
use Wikimedia\ObjectCache\BagOStuff;

class BadFileLookup {
	/** @var callable Returns contents of bad file list (see comment for isBadFile()) */
	private $listCallback;

	private BagOStuff $cache;
	private RepoGroup $repoGroup;
	private TitleParser $titleParser;
	private HookRunner $hookRunner;

	/** @var array<string,array<int,array<string,true>>>|null Parsed bad file list */
	private $badFiles;

	/**
	 * Do not call directly. Use MediaWikiServices.
	 *
	 * @param callable $listCallback Callback that returns wikitext of a bad file list
	 * @param BagOStuff $cache For caching parsed versions of the bad file list
	 * @param RepoGroup $repoGroup
	 * @param TitleParser $titleParser
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		callable $listCallback,
		BagOStuff $cache,
		RepoGroup $repoGroup,
		TitleParser $titleParser,
		HookContainer $hookContainer
	) {
		$this->listCallback = $listCallback;
		$this->cache = $cache;
		$this->repoGroup = $repoGroup;
		$this->titleParser = $titleParser;
		$this->hookRunner = new HookRunner( $hookContainer );
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
	public function isBadFile( $name, ?LinkTarget $contextTitle = null ) {
		// Handle redirects; callers almost always hit RepoGroup::findFile() anyway,
		// so just use that method because it has a fast process cache.
		$file = $this->repoGroup->findFile( $name );
		// XXX If we don't find the file we also don't replace spaces by underscores or otherwise
		// validate or normalize the title, is this right?
		if ( $file ) {
			$name = $file->getTitle()->getDBkey();
		}

		// Run the extension hook
		$bad = false;
		if ( !$this->hookRunner->onBadImage( $name, $bad ) ) {
			return (bool)$bad;
		}

		if ( $this->badFiles === null ) {
			$list = ( $this->listCallback )();
			$key = $this->cache->makeKey( 'bad-image-list', sha1( $list ) );
			$this->badFiles = $this->cache->getWithSetCallback(
				$key,
				BagOStuff::TTL_DAY,
				function () use ( $list ) {
					return $this->buildBadFilesList( $list );
				}
			);
		}

		return isset( $this->badFiles[$name] ) && ( !$contextTitle ||
			!isset( $this->badFiles[$name][$contextTitle->getNamespace()][$contextTitle->getDBkey()] ) );
	}

	/**
	 * @param string $list
	 * @return array<string,array<int,array<string,true>>>
	 */
	private function buildBadFilesList( string $list ): array {
		$ret = [];
		$lines = explode( "\n", $list );
		foreach ( $lines as $line ) {
			// List items only
			if ( !str_starts_with( $line, '*' ) ) {
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
				} catch ( MalformedTitleException ) {
					continue;
				}
				if ( $i == 0 ) {
					$fileDBkey = $title->getDBkey();
				} else {
					$exceptions[$title->getNamespace()][$title->getDBkey()] = true;
				}
			}

			if ( $fileDBkey !== null ) {
				$ret[$fileDBkey] = $exceptions;
			}
		}
		return $ret;
	}
}
