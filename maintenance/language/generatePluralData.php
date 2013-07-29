<?php
/**
 * Generates pluralizations data files
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
 * @ingroup MaintenanceLanguage
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Generates normalizer data files for Arabic and Malayalam.
 * For NFC see includes/normal.
 *
 * @ingroup MaintenanceLanguage
 */
class GeneratePluralData extends Maintenance {
	public $pluralRules = array();
	public $pluralRuleTypes = array();

	public function __construct() {
		parent::__construct();
	}

	public function execute() {
		global $IP;

		$this->loadPluralFiles();
		$ret = file_put_contents( "$IP/serialized/plurals.ser",
			serialize( array( $this->pluralRules, $this->pluralRuleTypes  ) ) );
		if( $ret === false ) {
			$err = error_get_last();
			throw new MWException( "Problem writing plurals.ser: " . $err['message'] );
		}
	}

	/**
	 * Mostly copy-pasta from LocalisationCache
	 */
	protected function loadPluralFiles() {
		global $IP;
		$cldrPlural = "$IP/languages/data/plurals.xml";
		$mwPlural = "$IP/languages/data/plurals-mediawiki.xml";
		// Load CLDR plural rules
		$this->loadPluralFile( $cldrPlural );
		if ( file_exists( $mwPlural ) ) {
			// Override or extend
			$this->loadPluralFile( $mwPlural );
		}
	}

	/**
	 * Load a plural XML file with the given filename, compile the relevant
	 * rules, and save the compiled rules in a process-local cache.
	 */
	protected function loadPluralFile( $fileName ) {
		$doc = new DOMDocument;
		$doc->load( $fileName );
		$rulesets = $doc->getElementsByTagName( "pluralRules" );
		foreach ( $rulesets as $ruleset ) {
			$codes = $ruleset->getAttribute( 'locales' );
			$rules = array();
			$ruleTypes = array();
			$ruleElements = $ruleset->getElementsByTagName( "pluralRule" );
			foreach ( $ruleElements as $elt ) {
				$ruleType = $elt->getAttribute( 'count' );
				$rules[] = $elt->nodeValue;
				$ruleTypes[] = $ruleType;
			}
			foreach ( explode( ' ', $codes ) as $code ) {
				$this->pluralRules[$code] = $rules;
				$this->pluralRuleTypes[$code] = $ruleTypes;
			}
		}
	}

}

$maintClass = 'GeneratePluralData';
require_once RUN_MAINTENANCE_IF_MAIN;
