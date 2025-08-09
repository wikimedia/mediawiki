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
 */

namespace Wikimedia\Tests\Leximorph\Handler;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Leximorph\Handler\Plural;
use Wikimedia\Leximorph\Provider;

/**
 * PluralTest
 *
 * This test class verifies the functionality of the {@see Plural} handler.
 * It tests that the class correctly processes pluralization rules based on
 * various input values and expected pluralization outcomes.
 *
 * Covered tests include:
 *   - Proper selection of plural forms for various languages.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Handler\Plural
 */
class PluralTest extends TestCase {

	/**
	 * Data provider for testProcess.
	 *
	 * Each test case provides:
	 *  - A language code.
	 *  - A count value.
	 *  - An array of plural forms.
	 *  - The expected output.
	 *
	 * @return Generator<array{string, float, array<string>, string}>
	 */
	public static function providePluralCases(): Generator {
		yield 'Amharic Zero' => [ 'am', 0, [ 'one', 'other' ], 'one' ];
		yield 'Amharic One' => [ 'am', 1, [ 'one', 'other' ], 'one' ];
		yield 'Amharic Two' => [ 'am', 2, [ 'one', 'other' ], 'other' ];
		yield 'Amharic Many' => [ 'am', 200, [ 'one', 'other' ], 'other' ];
		yield 'Lower SorbianFew 0' => [ 'apc', 0, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianOne' => [ 'apc', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Lower SorbianTwo' => [ 'apc', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Lower SorbianFew 3' => [ 'apc', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianFew 9' => [ 'apc', 9, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianFew 10' => [ 'apc', 10, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianOther 11' => [ 'apc', 11, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 12' => [ 'apc', 12, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 99' => [ 'apc', 99, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 100' => [ 'apc', 100, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 101' => [ 'apc', 101, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 102' => [ 'apc', 102, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianFew 103' => [ 'apc', 103, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianFew 104' => [ 'apc', 104, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianFew 109' => [ 'apc', 109, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianFew 110' => [ 'apc', 110, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianOther 111' => [ 'apc', 111, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 112' => [ 'apc', 112, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 9999' => [ 'apc', 9999, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianOther 1000' => [ 'apc', 1000, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower SorbianFew 1003' => [ 'apc', 1003, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower SorbianOther 1.7' => [ 'apc', 1.7, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Arabic Zero' => [ 'ar', 0, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'zero' ];
		yield 'Arabic One' => [ 'ar', 1, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'one' ];
		yield 'Arabic Two' => [ 'ar', 2, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'two' ];
		yield 'Arabic Few 3' => [ 'ar', 3, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few' ];
		yield 'Arabic Few 9' => [ 'ar', 9, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few' ];
		yield 'Arabic Few 110' => [ 'ar', 110, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few' ];
		yield 'Arabic Many 11' => [ 'ar', 11, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many' ];
		yield 'Arabic Many 15' => [ 'ar', 15, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many' ];
		yield 'Arabic Many 99' => [ 'ar', 99, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many' ];
		yield 'Arabic Many 9999' => [ 'ar', 9999, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many' ];
		yield 'Arabic Other 100' => [ 'ar', 100, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Arabic Other 102' => [ 'ar', 102, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Arabic Other 1000' => [ 'ar', 1000, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Arabic Other 1.7' => [ 'ar', 1.7, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Be-tarask One' => [ 'be-tarask', 1, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Be-tarask Many 11' => [ 'be-tarask', 11, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Be-tarask One 91' => [ 'be-tarask', 91, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Be-tarask One 121' => [ 'be-tarask', 121, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Be-tarask Few 2' => [ 'be-tarask', 2, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Be-tarask Few 3' => [ 'be-tarask', 3, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Be-tarask Few 4' => [ 'be-tarask', 4, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Be-tarask Few 334' => [ 'be-tarask', 334, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Be-tarask Many 5' => [ 'be-tarask', 5, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Be-tarask Many 15' => [ 'be-tarask', 15, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Be-tarask Many 120' => [ 'be-tarask', 120, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Be-tarask Two Forms One' => [ 'be-tarask', 1, [ '1=one', 'other' ], 'one' ];
		yield 'Belarusian One' => [ 'be', 1, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Belarusian Many 11' => [ 'be', 11, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Belarusian One 91' => [ 'be', 91, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Belarusian One 121' => [ 'be', 121, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Belarusian Few 2' => [ 'be', 2, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Belarusian Few 3' => [ 'be', 3, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Belarusian Few 4' => [ 'be', 4, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Belarusian Few 334' => [ 'be', 334, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Belarusian Many 5' => [ 'be', 5, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Belarusian Many 15' => [ 'be', 15, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Belarusian Many 120' => [ 'be', 120, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Bhojpuri Zero' => [ 'bho', 0, [ 'one', 'other' ], 'one' ];
		yield 'Bhojpuri One' => [ 'bho', 1, [ 'one', 'other' ], 'one' ];
		yield 'Bhojpuri Other 2' => [ 'bho', 2, [ 'one', 'other' ], 'other' ];
		yield 'Bhojpuri Other 200' => [ 'bho', 200, [ 'one', 'other' ], 'other' ];
		yield 'Bosnian Other 0' => [ 'bs', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Bosnian One' => [ 'bs', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Bosnian Few 2' => [ 'bs', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Bosnian Few 4' => [ 'bs', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Bosnian Other 5' => [ 'bs', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Bosnian Other 11' => [ 'bs', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Bosnian Other 20' => [ 'bs', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Bosnian One 21' => [ 'bs', 21, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Bosnian Few 24' => [ 'bs', 24, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Bosnian Other 25' => [ 'bs', 25, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Bosnian Other 200' => [ 'bs', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech Other 0' => [ 'cs', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech One' => [ 'cs', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Czech Few 2' => [ 'cs', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Czech Few 3' => [ 'cs', 3, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Czech Few 4' => [ 'cs', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Czech Other 5' => [ 'cs', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech Other 11' => [ 'cs', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech Other 20' => [ 'cs', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech Other 25' => [ 'cs', 25, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Czech Other 200' => [ 'cs', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Old Church Slavonic Other 0' => [ 'cu', 0, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Old Church Slavonic One' => [ 'cu', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Old Church Slavonic Two' => [ 'cu', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Old Church Slavonic Few 3' => [ 'cu', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Old Church Slavonic Few 4' => [ 'cu', 4, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Old Church Slavonic Other 5' => [ 'cu', 5, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Old Church Slavonic One 11' => [ 'cu', 11, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Old Church Slavonic Other 20' => [ 'cu', 20, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Old Church Slavonic Two 22' => [ 'cu', 22, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Old Church Slavonic Few 223' => [ 'cu', 223, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Old Church Slavonic Other 200' => [ 'cu', 200, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Welsh Zero' => [ 'cy', 0, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'zero' ];
		yield 'Welsh One' => [ 'cy', 1, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'one' ];
		yield 'Welsh Two' => [ 'cy', 2, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'two' ];
		yield 'Welsh Few 3' => [ 'cy', 3, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'few' ];
		yield 'Welsh Many 6' => [ 'cy', 6, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'many' ];
		yield 'Welsh Other 4' => [ 'cy', 4, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 5' => [ 'cy', 5, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 11' => [ 'cy', 11, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 20' => [ 'cy', 20, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 22' => [ 'cy', 22, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 223' => [ 'cy', 223, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Welsh Other 200.00' => [ 'cy', 200.00, [ 'zero', 'one', 'two', 'few', 'many', 'other' ], 'other' ];
		yield 'Lower Sorbian Other 0' => [ 'dsb', 0, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower Sorbian One' => [ 'dsb', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Lower Sorbian One 101' => [ 'dsb', 101, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Lower Sorbian One 90001' => [ 'dsb', 90001, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Lower Sorbian Two' => [ 'dsb', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Lower Sorbian Few 3' => [ 'dsb', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower Sorbian Few 203' => [ 'dsb', 203, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower Sorbian Few 4' => [ 'dsb', 4, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Lower Sorbian Other 99' => [ 'dsb', 99, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Lower Sorbian Other 555' => [ 'dsb', 555, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'French One 0' => [ 'fr', 0, [ 'one', 'other' ], 'one' ];
		yield 'French One 1' => [ 'fr', 1, [ 'one', 'other' ], 'one' ];
		yield 'French Other 2' => [ 'fr', 2, [ 'one', 'other' ], 'other' ];
		yield 'French Other 200' => [ 'fr', 200, [ 'one', 'other' ], 'other' ];
		yield 'Irish Other 0' => [ 'ga', 0, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Irish One' => [ 'ga', 1, [ 'one', 'two', 'other' ], 'one' ];
		yield 'Irish Two' => [ 'ga', 2, [ 'one', 'two', 'other' ], 'two' ];
		yield 'Irish Other 200' => [ 'ga', 200, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Scottish Gaelic Other 0' => [ 'gd', 0, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Scottish Gaelic One' => [ 'gd', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Scottish Gaelic Two' => [ 'gd', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Scottish Gaelic One 11' => [ 'gd', 11, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Scottish Gaelic Two 12' => [ 'gd', 12, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Scottish Gaelic Few 3' => [ 'gd', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Scottish Gaelic Few 19' => [ 'gd', 19, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Scottish Gaelic Other 200' => [ 'gd', 200, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Scottish Gaelic Explicit Other 0' => [ 'gd', 0,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'other' ];
		yield 'Scottish Gaelic Explicit One' => [ 'gd', 1,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'one' ];
		yield 'Scottish Gaelic Explicit Two' => [ 'gd', 2,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'two' ];
		yield 'Scottish Gaelic Explicit Few 3' => [ 'gd', 3,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'few' ];
		yield 'Scottish Gaelic Explicit Few 19' => [ 'gd', 19,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'few' ];
		yield 'Scottish Gaelic Explicit Other 200' => [ 'gd', 200,
			[ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ], 'other' ];
		yield 'Manx Few 0' => [ 'gv', 0, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Manx One' => [ 'gv', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Manx Two' => [ 'gv', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Manx Other 3' => [ 'gv', 3, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Manx Few 20' => [ 'gv', 20, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Manx One 21' => [ 'gv', 21, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Manx Two 22' => [ 'gv', 22, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Manx Other 23' => [ 'gv', 23, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Manx Other 50' => [ 'gv', 50, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Manx Few 60' => [ 'gv', 60, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Manx Few 80' => [ 'gv', 80, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Manx Few 100' => [ 'gv', 100, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Hebrew Two Forms Other 0' => [ 'he', 0, [ 'one', 'other' ], 'other' ];
		yield 'Hebrew Two Forms One' => [ 'he', 1, [ 'one', 'other' ], 'one' ];
		yield 'Hebrew Two Forms Other 3' => [ 'he', 3, [ 'one', 'other' ], 'other' ];
		yield 'Hebrew Two Forms Other 10' => [ 'he', 10, [ 'one', 'other' ], 'other' ];
		yield 'Hebrew Three Forms Other 0' => [ 'he', 0, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Hebrew Three Forms One' => [ 'he', 1, [ 'one', 'two', 'other' ], 'one' ];
		yield 'Hebrew Three Forms Two' => [ 'he', 2, [ 'one', 'two', 'other' ], 'two' ];
		yield 'Hebrew Three Forms Other 3' => [ 'he', 3, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Hebrew Three Forms Other 10' => [ 'he', 10, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Hebrew Four Forms Other 0' => [ 'he', 0, [ 'one', 'two', 'many', 'other' ], 'other' ];
		yield 'Hebrew Four Forms One' => [ 'he', 1, [ 'one', 'two', 'many', 'other' ], 'one' ];
		yield 'Hebrew Four Forms Two' => [ 'he', 2, [ 'one', 'two', 'many', 'other' ], 'two' ];
		yield 'Hebrew Four Forms Other 3' => [ 'he', 3, [ 'one', 'two', 'many', 'other' ], 'other' ];
		yield 'Hebrew Four Forms Other 10' => [ 'he', 10, [ 'one', 'two', 'many', 'other' ], 'other' ];
		yield 'Hebrew Four Forms Many 20' => [ 'he', 20, [ 'one', 'two', 'many', 'other' ], 'many' ];
		yield 'Hindi One 0' => [ 'hi', 0, [ 'one', 'other' ], 'one' ];
		yield 'Hindi One 1' => [ 'hi', 1, [ 'one', 'other' ], 'one' ];
		yield 'Hindi Other 2' => [ 'hi', 2, [ 'one', 'other' ], 'other' ];
		yield 'Hindi Other 200' => [ 'hi', 200, [ 'one', 'other' ], 'other' ];
		yield 'Croatian Other 0' => [ 'hr', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Croatian One' => [ 'hr', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Croatian Few 2' => [ 'hr', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Croatian Few 4' => [ 'hr', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Croatian Other 5' => [ 'hr', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Croatian Other 11' => [ 'hr', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Croatian Other 20' => [ 'hr', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Croatian One 21' => [ 'hr', 21, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Croatian Few 24' => [ 'hr', 24, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Croatian Other 25' => [ 'hr', 25, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Croatian Other 200' => [ 'hr', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Upper Sorbian Other 0' => [ 'hsb', 0, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Upper Sorbian One' => [ 'hsb', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Upper Sorbian One 101' => [ 'hsb', 101, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Upper Sorbian One 90001' => [ 'hsb', 90001, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Upper Sorbian Two' => [ 'hsb', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Upper Sorbian Few 3' => [ 'hsb', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Upper Sorbian Few 203' => [ 'hsb', 203, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Upper Sorbian Few 4' => [ 'hsb', 4, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Upper Sorbian Other 99' => [ 'hsb', 99, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Upper Sorbian Other 555' => [ 'hsb', 555, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Hungarian Other 0' => [ 'hu', 0, [ 'one', 'other' ], 'other' ];
		yield 'Hungarian One' => [ 'hu', 1, [ 'one', 'other' ], 'one' ];
		yield 'Hungarian Other 2' => [ 'hu', 2, [ 'one', 'other' ], 'other' ];
		yield 'Hungarian Other 200' => [ 'hu', 200, [ 'one', 'other' ], 'other' ];
		yield 'Armenian One 0' => [ 'hy', 0, [ 'one', 'other' ], 'one' ];
		yield 'Armenian One 1' => [ 'hy', 1, [ 'one', 'other' ], 'one' ];
		yield 'Armenian Other 2' => [ 'hy', 2, [ 'one', 'other' ], 'other' ];
		yield 'Armenian Other 200' => [ 'hy', 200, [ 'one', 'other' ], 'other' ];
		yield 'Interslavic Latin One' => [ 'isv-latn', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Interslavic Latin Other 11' => [ 'isv-latn', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Interslavic Latin One 91' => [ 'isv-latn', 91, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Interslavic Latin One 121' => [ 'isv-latn', 121, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Interslavic Latin Few 2' => [ 'isv-latn', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Interslavic Latin Few 3' => [ 'isv-latn', 3, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Interslavic Latin Few 4' => [ 'isv-latn', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Interslavic Latin Few 334' => [ 'isv-latn', 334, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Interslavic Latin Other 5' => [ 'isv-latn', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Interslavic Latin Other 15' => [ 'isv-latn', 15, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Interslavic Latin Other 120' => [ 'isv-latn', 120, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Interslavic Latin Two Forms One' => [ 'isv-latn', 1, [ 'one', 'other' ], 'one' ];
		yield 'Interslavic Latin Two Forms Other 11' => [ 'isv-latn', 11, [ 'one', 'other' ], 'other' ];
		yield 'Interslavic Latin Two Forms One 91' => [ 'isv-latn', 91, [ 'one', 'other' ], 'one' ];
		yield 'Interslavic Latin Two Forms One 121' => [ 'isv-latn', 121, [ 'one', 'other' ], 'one' ];
		yield 'Kölsch One' => [ 'ksh', 1, [ 'zero', 'one', 'other' ], 'one' ];
		yield 'Kölsch Zero' => [ 'ksh', 0, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Kölsch Other 2' => [ 'ksh', 2, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Kölsch Other 200' => [ 'ksh', 200, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Lingala One 0' => [ 'ln', 0, [ 'one', 'other' ], 'one' ];
		yield 'Lingala One 1' => [ 'ln', 1, [ 'one', 'other' ], 'one' ];
		yield 'Lingala Other 2' => [ 'ln', 2, [ 'one', 'other' ], 'other' ];
		yield 'Lingala Other 200' => [ 'ln', 200, [ 'one', 'other' ], 'other' ];
		yield 'Lithuanian Other 0' => [ 'lt', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Lithuanian One 1' => [ 'lt', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Lithuanian Few 2' => [ 'lt', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Lithuanian Few 9' => [ 'lt', 9, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Lithuanian Other 10' => [ 'lt', 10, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Lithuanian Other 11' => [ 'lt', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Lithuanian Other 20' => [ 'lt', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Lithuanian One 21' => [ 'lt', 21, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Lithuanian Few 32' => [ 'lt', 32, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Lithuanian One 41' => [ 'lt', 41, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Lithuanian One 40001' => [ 'lt', 40001, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Latvian Zero 0' => [ 'lv', 0, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Latvian One 1' => [ 'lv', 1, [ 'zero', 'one', 'other' ], 'one' ];
		yield 'Latvian Zero 11' => [ 'lv', 11, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Latvian One 21' => [ 'lv', 21, [ 'zero', 'one', 'other' ], 'one' ];
		yield 'Latvian Zero 411' => [ 'lv', 411, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Latvian Other 2' => [ 'lv', 2, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Latvian Other 9' => [ 'lv', 9, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Latvian Zero 12' => [ 'lv', 12, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Latvian Other 12.345' => [ 'lv', 12.345, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Latvian Zero 20' => [ 'lv', 20, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Latvian Other 22' => [ 'lv', 22, [ 'zero', 'one', 'other' ], 'other' ];
		yield 'Latvian One 31' => [ 'lv', 31, [ 'zero', 'one', 'other' ], 'one' ];
		yield 'Latvian Zero 200' => [ 'lv', 200, [ 'zero', 'one', 'other' ], 'zero' ];
		yield 'Malagasy One 0' => [ 'mg', 0, [ 'one', 'other' ], 'one' ];
		yield 'Malagasy One 1' => [ 'mg', 1, [ 'one', 'other' ], 'one' ];
		yield 'Malagasy Other 2' => [ 'mg', 2, [ 'one', 'other' ], 'other' ];
		yield 'Malagasy Other 200' => [ 'mg', 200, [ 'one', 'other' ], 'other' ];
		yield 'Malagasy Other 123.3434' => [ 'mg', 123.3434, [ 'one', 'other' ], 'other' ];
		yield 'Macedonian Other 0' => [ 'mk', 0, [ 'one', 'other' ], 'other' ];
		yield 'Macedonian One 1' => [ 'mk', 1, [ 'one', 'other' ], 'one' ];
		yield 'Macedonian One 11' => [ 'mk', 11, [ 'one', 'other' ], 'one' ];
		yield 'Macedonian One 21' => [ 'mk', 21, [ 'one', 'other' ], 'one' ];
		yield 'Macedonian One 411' => [ 'mk', 411, [ 'one', 'other' ], 'one' ];
		yield 'Macedonian Other 12.345' => [ 'mk', 12.345, [ 'one', 'other' ], 'other' ];
		yield 'Macedonian Other 20' => [ 'mk', 20, [ 'one', 'other' ], 'other' ];
		yield 'Macedonian One 31' => [ 'mk', 31, [ 'one', 'other' ], 'one' ];
		yield 'Macedonian Other 200' => [ 'mk', 200, [ 'one', 'other' ], 'other' ];
		yield 'Moldovan Few 0' => [ 'mo', 0, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan One 1' => [ 'mo', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Moldovan Few 2' => [ 'mo', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Few 19' => [ 'mo', 19, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Other 20' => [ 'mo', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Moldovan Other 99' => [ 'mo', 99, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Moldovan Other 100' => [ 'mo', 100, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Moldovan Few 101' => [ 'mo', 101, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Few 119' => [ 'mo', 119, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Other 120' => [ 'mo', 120, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Moldovan Other 200' => [ 'mo', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Moldovan Few 201' => [ 'mo', 201, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Few 219' => [ 'mo', 219, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Moldovan Other 220' => [ 'mo', 220, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Maltese Few 0' => [ 'mt', 0, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Maltese One 1' => [ 'mt', 1, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Maltese Few 2' => [ 'mt', 2, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Maltese Few 10' => [ 'mt', 10, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Maltese Many 11' => [ 'mt', 11, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Maltese Many 19' => [ 'mt', 19, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Maltese Other 20' => [ 'mt', 20, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Maltese Other 99' => [ 'mt', 99, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Maltese Other 100' => [ 'mt', 100, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Maltese Other 101' => [ 'mt', 101, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Maltese Few 102' => [ 'mt', 102, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Maltese Few 110' => [ 'mt', 110, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Maltese Many 111' => [ 'mt', 111, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Maltese Many 119' => [ 'mt', 119, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Maltese Other 120' => [ 'mt', 120, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Maltese Other 201' => [ 'mt', 201, [ 'one', 'few', 'many', 'other' ], 'other' ];
		yield 'Northern Sotho One 0' => [ 'nso', 0, [ 'one', 'other' ], 'one' ];
		yield 'Northern Sotho One 1' => [ 'nso', 1, [ 'one', 'other' ], 'one' ];
		yield 'Northern Sotho Other 2' => [ 'nso', 2, [ 'one', 'other' ], 'other' ];
		yield 'Polish Many 0' => [ 'pl', 0, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish One' => [ 'pl', 1, [ 'one', 'few', 'many' ], 'one' ];
		yield 'Polish Few 2' => [ 'pl', 2, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Few 3' => [ 'pl', 3, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Few 4' => [ 'pl', 4, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Many 5' => [ 'pl', 5, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 9' => [ 'pl', 9, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 10' => [ 'pl', 10, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 11' => [ 'pl', 11, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 21' => [ 'pl', 21, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Few 22' => [ 'pl', 22, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Few 23' => [ 'pl', 23, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Few 24' => [ 'pl', 24, [ 'one', 'few', 'many' ], 'few' ];
		yield 'Polish Many 25' => [ 'pl', 25, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 200' => [ 'pl', 200, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Polish Many 201' => [ 'pl', 201, [ 'one', 'few', 'many' ], 'many' ];
		yield 'Romanian Few 0' => [ 'ro', 0, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian One' => [ 'ro', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Romanian Few 2' => [ 'ro', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Few 19' => [ 'ro', 19, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Other 20' => [ 'ro', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Romanian Other 99' => [ 'ro', 99, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Romanian Other 100' => [ 'ro', 100, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Romanian Few 101' => [ 'ro', 101, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Few 119' => [ 'ro', 119, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Other 120' => [ 'ro', 120, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Romanian Other 200' => [ 'ro', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Romanian Few 201' => [ 'ro', 201, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Few 219' => [ 'ro', 219, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Romanian Other 220' => [ 'ro', 220, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Russian One' => [ 'ru', 1, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Russian Many 11' => [ 'ru', 11, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Russian One 91' => [ 'ru', 91, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Russian One 121' => [ 'ru', 121, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Russian Few 2' => [ 'ru', 2, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Russian Few 3' => [ 'ru', 3, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Russian Few 4' => [ 'ru', 4, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Russian Few 334' => [ 'ru', 334, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Russian Many 5' => [ 'ru', 5, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Russian Many 15' => [ 'ru', 15, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Russian Many 120' => [ 'ru', 120, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Russian Explicit 12' => [ 'ru', 12, [ 'one', 'few', 'many', 'other', '12=dozen' ], 'dozen' ];
		yield 'Russian Explicit 100' =>
		[ 'ru', 100, [ 'one', 'few', 'many', '100=hundred', 'other', '12=dozen' ], 'hundred' ];
		yield 'Northern Sami Other 0' => [ 'se', 0, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Northern Sami One' => [ 'se', 1, [ 'one', 'two', 'other' ], 'one' ];
		yield 'Northern Sami Two' => [ 'se', 2, [ 'one', 'two', 'other' ], 'two' ];
		yield 'Northern Sami Other 3' => [ 'se', 3, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Northern Sami TwoForms Other 0' => [ 'se', 0, [ 'one', 'other' ], 'other' ];
		yield 'Northern Sami TwoForms One' => [ 'se', 1, [ 'one', 'other' ], 'one' ];
		yield 'Northern Sami TwoForms Other 2' => [ 'se', 2, [ 'one', 'other' ], 'other' ];
		yield 'Northern Sami TwoForms Other 3' => [ 'se', 3, [ 'one', 'other' ], 'other' ];
		yield 'Samogitian Few 0' => [ 'sgs', 0, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian One' => [ 'sgs', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Samogitian Two' => [ 'sgs', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Samogitian Other 3' => [ 'sgs', 3, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Samogitian Few 10' => [ 'sgs', 10, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian Few 11' => [ 'sgs', 11, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian Few 12' => [ 'sgs', 12, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian Few 19' => [ 'sgs', 19, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian Other 20' => [ 'sgs', 20, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Samogitian Few 100' => [ 'sgs', 100, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian One 101' => [ 'sgs', 101, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Samogitian Few 111' => [ 'sgs', 111, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Samogitian Few 112' => [ 'sgs', 112, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Serbocroatian Other 0' => [ 'sh', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbocroatian One' => [ 'sh', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Serbocroatian Few 2' => [ 'sh', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbocroatian Few 4' => [ 'sh', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbocroatian Other 5' => [ 'sh', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbocroatian Other 10' => [ 'sh', 10, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbocroatian Other 11' => [ 'sh', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbocroatian Other 12' => [ 'sh', 12, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbocroatian One 101' => [ 'sh', 101, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Serbocroatian Few 102' => [ 'sh', 102, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbocroatian Other 111' => [ 'sh', 111, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak Other 0' => [ 'sk', 0, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak One' => [ 'sk', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Slovak Few 2' => [ 'sk', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Slovak Few 3' => [ 'sk', 3, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Slovak Few 4' => [ 'sk', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Slovak Other 5' => [ 'sk', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak Other 11' => [ 'sk', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak Other 20' => [ 'sk', 20, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak Other 25' => [ 'sk', 25, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovak Other 200' => [ 'sk', 200, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Slovenian Other 0' => [ 'sl', 0, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Slovenian One' => [ 'sl', 1, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Slovenian Two' => [ 'sl', 2, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Slovenian Few 3' => [ 'sl', 3, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Slovenian Few 4' => [ 'sl', 4, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Slovenian Other 5' => [ 'sl', 5, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Slovenian Other 99' => [ 'sl', 99, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Slovenian Other 100' => [ 'sl', 100, [ 'one', 'two', 'few', 'other' ], 'other' ];
		yield 'Slovenian One 101' => [ 'sl', 101, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Slovenian Two 102' => [ 'sl', 102, [ 'one', 'two', 'few', 'other' ], 'two' ];
		yield 'Slovenian Few 103' => [ 'sl', 103, [ 'one', 'two', 'few', 'other' ], 'few' ];
		yield 'Slovenian One 201' => [ 'sl', 201, [ 'one', 'two', 'few', 'other' ], 'one' ];
		yield 'Southern Sami Other 0' => [ 'sma', 0, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Southern Sami One' => [ 'sma', 1, [ 'one', 'two', 'other' ], 'one' ];
		yield 'Southern Sami Two' => [ 'sma', 2, [ 'one', 'two', 'other' ], 'two' ];
		yield 'Southern Sami Other 3' => [ 'sma', 3, [ 'one', 'two', 'other' ], 'other' ];
		yield 'Serbian One' => [ 'sr', 1, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Serbian Other 11' => [ 'sr', 11, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbian One 91' => [ 'sr', 91, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Serbian One 121' => [ 'sr', 121, [ 'one', 'few', 'other' ], 'one' ];
		yield 'Serbian Few 2' => [ 'sr', 2, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbian Few 3' => [ 'sr', 3, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbian Few 4' => [ 'sr', 4, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbian Few 334' => [ 'sr', 334, [ 'one', 'few', 'other' ], 'few' ];
		yield 'Serbian Other 5' => [ 'sr', 5, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbian Other 15' => [ 'sr', 15, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Serbian Other 120' => [ 'sr', 120, [ 'one', 'few', 'other' ], 'other' ];
		yield 'Tigrinya One 0' => [ 'ti', 0, [ 'one', 'other' ], 'one' ];
		yield 'Tigrinya One 1' => [ 'ti', 1, [ 'one', 'other' ], 'one' ];
		yield 'Tigrinya Other 2' => [ 'ti', 2, [ 'one', 'other' ], 'other' ];
		yield 'Tagalog One 0' => [ 'tl', 0, [ 'one', 'other' ], 'one' ];
		yield 'Tagalog One 1' => [ 'tl', 1, [ 'one', 'other' ], 'one' ];
		yield 'Tagalog One 2' => [ 'tl', 2, [ 'one', 'other' ], 'one' ];
		yield 'Tagalog Other 4' => [ 'tl', 4, [ 'one', 'other' ], 'other' ];
		yield 'Tagalog Other 6' => [ 'tl', 6, [ 'one', 'other' ], 'other' ];
		yield 'Ukrainian One 1' => [ 'uk', 1, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Ukrainian Many 11' => [ 'uk', 11, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Ukrainian One 91' => [ 'uk', 91, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Ukrainian One 121' => [ 'uk', 121, [ 'one', 'few', 'many', 'other' ], 'one' ];
		yield 'Ukrainian Few 2' => [ 'uk', 2, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Ukrainian Few 3' => [ 'uk', 3, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Ukrainian Few 4' => [ 'uk', 4, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Ukrainian Few 334' => [ 'uk', 334, [ 'one', 'few', 'many', 'other' ], 'few' ];
		yield 'Ukrainian Many 5' => [ 'uk', 5, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Ukrainian Many 15' => [ 'uk', 15, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Ukrainian Many 120' => [ 'uk', 120, [ 'one', 'few', 'many', 'other' ], 'many' ];
		yield 'Walloon One 0' => [ 'wa', 0, [ 'one', 'other' ], 'one' ];
		yield 'Walloon One 1' => [ 'wa', 1, [ 'one', 'other' ], 'one' ];
		yield 'Walloon Other 2' => [ 'wa', 2, [ 'one', 'other' ], 'other' ];
	}

	/**
	 * @dataProvider providePluralCases
	 *
	 * Tests that pluralization rules are correctly applied.
	 *
	 * @param string $lang Language code (e.g., 'en', 'ru').
	 * @param float $count Numeric value used for plural selection.
	 * @param array<string> $forms List of plural forms (e.g., ['one', 'few', 'other']).
	 * @param string $expected Expected output based on pluralization rules.
	 *
	 * @since 1.45
	 */
	public function testProcess( string $lang, float $count, array $forms, string $expected ): void {
		$provider = new Provider( $lang, new NullLogger() );
		$pluralHandler = new Plural( $provider );
		$result = $pluralHandler->process( $count, $forms );
		$this->assertSame( $expected, $result );
	}

	/**
	 * Additional test cases for English-like pluralization rules.
	 *
	 * @return Generator<array{string, float, array<string>, string}>
	 */
	public static function provideEnglishPluralCases(): Generator {
		$cases = [
			[ 'plural', 0, [ 'singular', 'plural' ] ],
			[ 'explicit zero', 0, [ '0=explicit zero', 'singular', 'plural' ] ],
			[ 'explicit one', 1, [ 'singular', 'plural', '1=explicit one' ] ],
			[ 'singular', 1, [ 'singular', 'plural', '0=explicit zero' ] ],
			[ 'plural', 3, [ '0=explicit zero', '1=explicit one', 'singular', 'plural' ] ],
			[ 'explicit eleven', 11, [ 'singular', 'plural', '11=explicit eleven' ] ],
			[ 'plural', 12, [ 'singular', 'plural', '11=explicit twelve' ] ],
			[ 'plural', 12, [ 'singular', 'plural', '=explicit form' ] ],
			[ 'other', 2, [ 'kissa=kala', '1=2=3', 'other' ] ],
			[ '', 2, [ '0=explicit zero', '1=explicit one' ] ],
		];

		foreach ( $cases as $idx => [ $expected, $count, $forms ] ) {
			yield "English Case $idx" => [ 'en', $count, $forms, $expected ];
		}
	}

	/**
	 * @dataProvider provideEnglishPluralCases
	 *
	 * Tests that explicit plural forms (e.g., '0=zero', '1=one') are
	 * correctly matched against count values.
	 *
	 * @param string $lang Language code (e.g., 'en').
	 * @param float $count Numeric value used for plural selection.
	 * @param array<string> $forms List of plural forms with explicit mappings.
	 * @param string $expected Expected output string based on rules.
	 */
	public function testProcessWithExplicitForms(
		string $lang,
		float $count,
		array $forms,
		string $expected
	): void {
		$provider = new Provider( $lang, new NullLogger() );
		$pluralHandler = new Plural( $provider );
		$result = $pluralHandler->process( $count, $forms );
		$this->assertSame( $expected, $result );
	}
}
