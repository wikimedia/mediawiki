<?php
/**
 * Benchmark for using a regexp vs. mb_check_encoding to check for UTF-8 encoding.
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
 * @ingroup Benchmark
 */

require_once __DIR__ . '/../includes/Benchmarker.php';

/**
 * This little benchmark executes the regexp formerly used in Language->checkTitleEncoding()
 * and compares its execution time against that of mb_check_encoding.
 *
 * @ingroup Benchmark
 */
class BenchUtf8TitleCheck extends Benchmarker {
	private $data;

	private $isutf8;

	public function __construct() {
		parent::__construct();

		// phpcs:disable Generic.Files.LineLength
		$this->data = [
			"",
			// 7bit ASCII
			"United States of America",
			"S%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e",
			"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn",
			// This comes from T38839
			"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn%7C"
			. "Catherine%20Willows%7CDavid%20Hodges%7CDavid%20Phillips%7CGil%20Grissom%7CGreg%20Sanders%7CHodges%7C"
			. "Internet%20Movie%20Database%7CJim%20Brass%7CLady%20Heather%7C"
			. "Les%20Experts%20(s%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e)%7CLes%20Experts%20:%20Manhattan%7C"
			. "Les%20Experts%20:%20Miami%7CListe%20des%20personnages%20des%20Experts%7C"
			. "Liste%20des%20%C3%A9pisodes%20des%20Experts%7CMod%C3%A8le%20discussion:Palette%20Les%20Experts%7C"
			. "Nick%20Stokes%7CPersonnage%20de%20fiction%7CPersonnage%20fictif%7CPersonnage%20de%20fiction%7C"
			. "Personnages%20r%C3%A9currents%20dans%20Les%20Experts%7CRaymond%20Langston%7CRiley%20Adams%7C"
			. "Saison%201%20des%20Experts%7CSaison%2010%20des%20Experts%7CSaison%2011%20des%20Experts%7C"
			. "Saison%2012%20des%20Experts%7CSaison%202%20des%20Experts%7CSaison%203%20des%20Experts%7C"
			. "Saison%204%20des%20Experts%7CSaison%205%20des%20Experts%7CSaison%206%20des%20Experts%7C"
			. "Saison%207%20des%20Experts%7CSaison%208%20des%20Experts%7CSaison%209%20des%20Experts%7C"
			. "Sara%20Sidle%7CSofia%20Curtis%7CS%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e%7CWallace%20Langham%7C"
			. "Warrick%20Brown%7CWendy%20Simms%7C%C3%89tats-Unis"
		];
		// phpcs:enable

		$this->addDescription( "Benchmark for using a regexp vs. mb_check_encoding " .
			"to check for UTF-8 encoding." );
	}

	public function execute() {
		$benchmarks = [];
		foreach ( $this->data as $val ) {
			$benchmarks[] = [
				'function' => [ $this, 'use_regexp' ],
				'args' => [ rawurldecode( $val ) ]
			];
			$benchmarks[] = [
				'function' => [ $this, 'use_regexp_non_capturing' ],
				'args' => [ rawurldecode( $val ) ]
			];
			$benchmarks[] = [
				'function' => [ $this, 'use_regexp_once_only' ],
				'args' => [ rawurldecode( $val ) ]
			];
			$benchmarks[] = [
				'function' => [ $this, 'use_mb_check_encoding' ],
				'args' => [ rawurldecode( $val ) ]
			];
		}
		$this->bench( $benchmarks );
	}

	protected function use_regexp( $s ) {
		$this->isutf8 = preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
	}

	protected function use_regexp_non_capturing( $s ) {
		// Same as above with a non-capturing subgroup.
		$this->isutf8 = preg_match( '/^(?:[\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
	}

	protected function use_regexp_once_only( $s ) {
		// Same as above with a once-only subgroup.
		$this->isutf8 = preg_match( '/^(?>[\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
			'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s );
	}

	protected function use_mb_check_encoding( $s ) {
		$this->isutf8 = mb_check_encoding( $s, 'UTF-8' );
	}
}

$maintClass = BenchUtf8TitleCheck::class;
require_once RUN_MAINTENANCE_IF_MAIN;
