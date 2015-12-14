<?php
/**
 * @defgroup Benchmark Benchmark
 * @ingroup  Maintenance
 */

/**
 * Base code for benchmark scripts.
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
 *
 * @file
 * @ingroup Benchmark
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Base class for benchmark scripts.
 *
 * @ingroup Benchmark
 */
 $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
 function getOS() { 

    global $user_agent;

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser() {

    global $user_agent;

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}


$user_os        =   getOS();
$user_browser   =   getBrowser();

$device_details =   "<strong>Browser: </strong>".$user_browser."<br /><strong>Operating System: </strong>".$user_os."";

$php_version = phpversion();
print $php_version;

print_r($device_details);

echo("<br /><br /><br />".$_SERVER['HTTP_USER_AGENT']."");
 
 
 
 
abstract class Benchmarker extends Maintenance {
	private $results;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'count', "How many time to run a benchmark", false, true );
	}

	public function bench( array $benchs ) {
		$bench_number = 0;
		$count = $this->getOption( 'count', 100 );

		foreach ( $benchs as $bench ) {
			// handle empty args
			if ( !array_key_exists( 'args', $bench ) ) {
				$bench['args'] = array();
			}

			$bench_number++;
			$start = microtime( true );
			for ( $i = 0; $i < $count; $i++ ) {
				call_user_func_array( $bench['function'], $bench['args'] );
			}
			$delta = microtime( true ) - $start;

			// function passed as a callback
			if ( is_array( $bench['function'] ) ) {
				$ret = get_class( $bench['function'][0] ) . '->' . $bench['function'][1];
				$bench['function'] = $ret;
			}

			$this->results[$bench_number] = array(
				'function' => $bench['function'],
				'arguments' => $bench['args'],
				'count' => $count,
				'delta' => $delta,
				'average' => $delta / $count,
			);
		}
	}

	public function getFormattedResults() {
		$ret = '';
		foreach ( $this->results as $res ) {
			// show function with args
			$ret .= sprintf( "%s times: function %s(%s) :\n",
				$res['count'],
				$res['function'],
				join( ', ', $res['arguments'] )
			);
			$ret .= sprintf( "   %6.2fms (%6.2fms each)\n",
				$res['delta'] * 1000,
				$res['average'] * 1000
			);
		}

		return $ret;
	}
}
