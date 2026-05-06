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
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\TitleFactory;
use Wikimedia\Parsoid\Parsoid;

/**
 * Check arbitrary wikitext for lint errors
 *
 * @since 1.43
 */
class LintErrorChecker {
	public function __construct(
		private readonly Parsoid $parsoid,
		private readonly PageConfigFactory $pageConfigFactory,
		private readonly TitleFactory $titleFactory,
		private readonly ExtensionRegistry $extensionRegistry,
		private readonly Config $mainConfig,
	) {
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
	 * @param RevisionRecord|string $revision Revision containing wikitext after PST
	 * @return array Array of error objects returned by Parsoid's lint API (empty array for no errors)
	 * @note Passing a string as $revision is deprecated since 1.47 and will
	 *   emit warnings.  Use MutableRevisionRecord::newFromContent() to pass
	 *   a WikitextContent constructed with your string.
	 */
	public function check( RevisionRecord|string $revision ): array {
		return $this->checkSome( $revision, [] );
	}

	/**
	 * Check the given wikitext for lint errors against a subset of lint categories
	 *
	 * While not strictly required, you'll get better results if the wikitext has already gone through PST
	 *
	 * @param RevisionRecord|string $revision Revision containing wikitext after PST
	 * @param string[] $disabled Array of lint categories to disable
	 * @return array Array of error objects returned by Parsoid's lint API (empty array for no errors)
	 * @note Passing a string as $revision is deprecated since 1.47 and will
	 *   emit warnings.  Use MutableRevisionRecord::newFromContent() to pass
	 *   a WikitextContent constructed with your string.
	 */
	public function checkSome( RevisionRecord|string $revision, array $disabled ): array {
		if ( is_string( $revision ) ) {
			wfDeprecatedMsg(
				'Passing $revision as string to ' . __METHOD__ . ' is deprecated since 1.47.'
			);
			$revision = MutableRevisionRecord::newFromContent(
				$this->titleFactory->newMainPage(),
				new WikitextContent( $revision )
			);
		}
		return $this->parsoid->wikitext2lint(
			$this->pageConfigFactory->createFromParserOptions(
				ParserOptions::newFromAnon(),
				$revision->getPage(),
				$revision
			),
			$this->linterOptions( $disabled ),
			new ParserOutput()
		);
	}
}
