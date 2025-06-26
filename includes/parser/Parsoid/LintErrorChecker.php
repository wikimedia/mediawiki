<?php
declare( strict_types = 1 );
// SPDX-License-Identifier: GPL-2.0-or-later

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Config\Config;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Parsoid\Parsoid;

/**
 * Check arbitrary wikitext for lint errors
 *
 * @since 1.43
 */
class LintErrorChecker {
	private Parsoid $parsoid;
	private PageConfigFactory $pageConfigFactory;
	private TitleFactory $titleFactory;
	private ExtensionRegistry $extensionRegistry;
	private Config $mainConfig;

	public function __construct(
		Parsoid $parsoid,
		PageConfigFactory $pageConfigFactory,
		TitleFactory $titleFactory,
		ExtensionRegistry $extensionRegistry,
		Config $mainConfig

	) {
		$this->parsoid = $parsoid;
		$this->pageConfigFactory = $pageConfigFactory;
		$this->titleFactory = $titleFactory;
		$this->extensionRegistry = $extensionRegistry;
		$this->mainConfig = $mainConfig;
	}

	private function linterOptions( array $disabled ): array {
		// FIXME: We shouldn't be this interwined with an extension (T360809)
		if ( $this->extensionRegistry->isLoaded( 'Linter' ) ) {
			foreach ( $this->mainConfig->get( 'LinterCategories' ) as $name => $cat ) {
				if ( $cat['priority'] === 'none' ) {
					$disabled[] = $name;
				}
			}
		}
		$disabled = array_unique( $disabled );
		return [ 'linterOverrides' => [ 'disabled' => $disabled ] ];
	}

	/**
	 * Check the given wikitext for lint errors
	 *
	 * While not strictly required, you'll get better results if the wikitext has already gone through PST
	 *
	 * @param string $wikitext Wikitext after PST
	 * @return array Array of error objects returned by Parsoid's lint API (empty array for no errors)
	 */
	public function check( string $wikitext ): array {
		return $this->checkSome( $wikitext, [] );
	}

	/**
	 * Check the given wikitext for lint errors against a subset of lint categories
	 *
	 * While not strictly required, you'll get better results if the wikitext has already gone through PST
	 *
	 * @param string $wikitext Wikitext after PST
	 * @param string[] $disabled Array of lint categories to disable
	 * @return array Array of error objects returned by Parsoid's lint API (empty array for no errors)
	 */
	public function checkSome( string $wikitext, array $disabled ): array {
		$title = $this->titleFactory->newMainPage();
		$fakeRevision = new MutableRevisionRecord( $title );
		$fakeRevision->setSlot(
			SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				new WikitextContent( $wikitext )
			)
		);

		return $this->parsoid->wikitext2lint(
			$this->pageConfigFactory->createFromParserOptions(
				ParserOptions::newFromAnon(),
				$title,
				$fakeRevision
			),
			$this->linterOptions( $disabled ),
			new ParserOutput()
		);
	}
}
