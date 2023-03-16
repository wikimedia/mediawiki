<?php
/**
 * Checks that all Codex tokens have defaults defined in mediawiki.skin.defaults.less
 *
 * @coversNothing
 */
class CodexTokenDefaultsTest extends MediaWikiIntegrationTestCase {

	public function testTokenDefaults() {
		$resourcesPath = __DIR__ . '/../../../resources';
		$codexTokensLess = "$resourcesPath/lib/codex-design-tokens/theme-wikimedia-ui.less";
		$defaultsLess = "$resourcesPath/src/mediawiki.less/mediawiki.skin.defaults.less";

		$codexVars = static::getVariableNames( $codexTokensLess );
		$defaultVars = static::getVariableNames( $defaultsLess );
		$missingVars = array_values( array_diff( $codexVars, $defaultVars ) );
		$this->assertEquals( [], $missingVars );
	}

	protected static function getVariableNames( $lessFilePath ) {
		// We'd like to do something like:
		//     $parser = new Less_Parser;
		//     $parser->parseFile( $lessFilePath, '' );
		//     $parser->getCss(); // Have to call this for ->getVariables() to work; discard the result
		//     return array_keys( $parser->getVariables() );
		// But that doesn't work, the Less compiler appears to crash because of the calc() and rgba()
		// calls in the variable values. Instead, use regexes :(

		$fileContents = file_get_contents( $lessFilePath );
		$matches = null;
		preg_match_all( '/^@([^:]+):/m', $fileContents, $matches, PREG_PATTERN_ORDER );
		return $matches[1];
	}
}
