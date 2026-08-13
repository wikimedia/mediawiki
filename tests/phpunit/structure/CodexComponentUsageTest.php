<?php

namespace MediaWiki\Tests\Structure;

use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\CodexModule;
use MediaWikiIntegrationTestCase;

/**
 * Guard against a CodexModule declaring Codex components in its "codexComponents" array that
 * nothing uses. Every declared component is bundled into the module's JS and CSS payload and
 * shipped to everyone the module is served to, whether or not anything imports it.
 *
 * CodexModule already reports the opposite mistake: the Proxy in its synthetic codex.js throws
 * when a component that was never declared is read. Over-declaring produces no such signal, and
 * cannot: nothing observes the absence of a require. Refer to T433648.
 *
 * This covers every registered CodexModule, so with extensions and skins installed it guards the
 * whole ecosystem rather than one repository at a time.
 *
 * @coversNothing
 * @group ResourceLoader
 * @group Database
 */
class CodexComponentUsageTest extends MediaWikiIntegrationTestCase {

	/**
	 * Match a Codex component reference (e.g. "CdxButton"). Requires an uppercase letter after the
	 * "Cdx" prefix and a word boundary before it, so that neither a lowercase-suffixed identifier
	 * ("Cdxfoo") nor a longer name that merely contains one ("MyCdxButton") is matched.
	 *
	 * A match in a comment or string would also count, so the check under-reports rather than
	 * failing a correct module.
	 */
	private const COMPONENT_REGEX = '/\bCdx[A-Z]\w*/';

	/** @var array<string,string[]> Components referenced by a module's own files, by module name */
	private array $scanCache = [];

	public function testNoUnusedCodexComponents() {
		$resourceLoader = $this->getServiceContainer()->getResourceLoader();
		$dependents = $this->buildDependentsMap( $resourceLoader );

		$checked = 0;
		$failures = [];
		foreach ( $resourceLoader->getModuleNames() as $moduleName ) {
			$module = $resourceLoader->getModule( $moduleName );
			if ( !$module instanceof CodexModule || $module->shouldSkipStructureTest() ) {
				continue;
			}

			// A style-only module (codexStyleOnly) pulls in a component's CSS without ever naming
			// it in JS, so every one of its declarations would look unused.
			if ( $module->getType() === RL\Module::LOAD_STYLES ) {
				continue;
			}

			// Non-component exports such as composables ("useModelWrapper") can legitimately
			// appear in "codexComponents"; a source scan can't attribute their usage, so they
			// don't participate.
			$declared = array_filter(
				$module->getCodexComponents(),
				static fn ( $component ) => str_starts_with( $component, 'Cdx' )
			);
			if ( !$declared ) {
				// Not a tree-shaking module, or it declares only non-components.
				continue;
			}

			// A module's synthetic codex.js is reachable by anything that depends on it, so a
			// module may legitimately declare components purely for its dependents to require.
			// mediawiki.codex.typeaheadSearch is one such shared bundle: it has no packageFiles at
			// all, and every component it declares is used by dependents.
			$used = [];
			foreach ( [ $moduleName, ...$dependents[$moduleName] ] as $consumer ) {
				$used = array_merge( $used, $this->scanModule( $resourceLoader, $consumer ) );
			}

			$checked++;
			$unused = array_diff( $declared, $used );
			if ( $unused ) {
				$failures[] = "$moduleName: " . implode( ', ', $unused );
			}
		}

		$this->assertSame(
			[],
			$failures,
			'The following modules declare Codex component(s) in their "codexComponents" array ' .
			'that neither they nor any module depending on them use. Remove them to avoid ' .
			'shipping unused code.'
		);
		$this->assertGreaterThan(
			0,
			$checked,
			'Expected to find at least one tree-shaking CodexModule to check'
		);
	}

	/**
	 * Map each module name to every module that depends on it, directly or transitively.
	 *
	 * Transitive dependents matter because a module can require the exports of anything in its
	 * dependency tree, not just what it lists itself.
	 *
	 * @return array<string,string[]>
	 */
	private function buildDependentsMap( RL\ResourceLoader $resourceLoader ): array {
		$dependencies = [];
		foreach ( $resourceLoader->getModuleNames() as $moduleName ) {
			$dependencies[$moduleName] = $resourceLoader->getModule( $moduleName )->getDependencies();
		}

		$dependents = array_fill_keys( array_keys( $dependencies ), [] );
		foreach ( $dependencies as $moduleName => $_ ) {
			// Walk this module's transitive dependencies and record it as a dependent of each.
			$seen = [];
			$queue = $dependencies[$moduleName];
			while ( $queue ) {
				$dependency = array_pop( $queue );
				if ( isset( $seen[$dependency] ) || !isset( $dependencies[$dependency] ) ) {
					continue;
				}
				$seen[$dependency] = true;
				$dependents[$dependency][] = $moduleName;
				$queue = array_merge( $queue, $dependencies[$dependency] );
			}
		}
		return $dependents;
	}

	/**
	 * Collect the Codex components referenced by one module's own package files.
	 *
	 * The synthetic files CodexModule injects are skipped: "codex.js" names every declared
	 * component by construction and would make the check vacuous, and the bundled component
	 * sources under "_codex/" reference each other's dependencies.
	 *
	 * @return string[]
	 */
	private function scanModule( RL\ResourceLoader $resourceLoader, string $moduleName ): array {
		if ( isset( $this->scanCache[$moduleName] ) ) {
			return $this->scanCache[$moduleName];
		}

		$module = $resourceLoader->getModule( $moduleName );
		$context = new RL\Context( $resourceLoader, new FauxRequest( [ 'modules' => $moduleName ] ) );
		$packageFiles = $module->getPackageFiles( $context );

		$used = [];
		foreach ( $packageFiles['files'] ?? [] as $name => $file ) {
			if ( $name === 'codex.js' || str_starts_with( (string)$name, '_codex/' ) ) {
				continue;
			}
			$content = $file['content'] ?? null;
			if ( is_array( $content ) ) {
				// A Vue single-file component, whose content is split into 'script' and 'style'.
				// A 'data' file holds decoded JSON instead and has neither, so it drops out here.
				$content = $content['script'] ?? null;
			}
			if ( !is_string( $content ) ) {
				continue;
			}
			if ( preg_match_all( self::COMPONENT_REGEX, $content, $matches ) ) {
				$used = array_merge( $used, $matches[0] );
			}
		}

		$this->scanCache[$moduleName] = array_values( array_unique( $used ) );
		return $this->scanCache[$moduleName];
	}
}
