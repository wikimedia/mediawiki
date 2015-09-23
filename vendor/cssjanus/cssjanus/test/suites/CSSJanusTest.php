<?php

class CSSJanusTest extends PHPUnit_Framework_TestCase {

	public static function provideData() {
		$data = self::getSpec();
		$cases = array();
		$defaultSettings = array(
			'swapLtrRtlInUrl' => false,
			'swapLeftRightInUrl' => false,
		);
		foreach ($data as $name => $test) {
			$settings = isset($test['settings']) ? $test['settings'] : array();
			$settings += $defaultSettings;
			foreach ($test['cases'] as $i => $case) {
				$input = $case[0];
				$noop = !isset($case[1]);
				$output = $noop ? $input : $case[1];

				$cases[] = array(
					$input,
					$settings,
					$output,
					$name,
				);

				if (!$noop) {
					// Round trip
					$cases[] = array(
						$output,
						$settings,
						$input,
						$name,
					);
				}
			}
		}
		return $cases;
	}

	/**
	 * @dataProvider provideData
	 */
	public function testTransform($input, $settings, $output, $name) {
		$this->assertEquals(
			$output,
			CSSJanus::transform($input, $settings['swapLtrRtlInUrl'], $settings['swapLeftRightInUrl']),
			$name
		);
	}

	protected static function getSpec() {
		static $json;
		if ($json == null) {
			$version = '1.1.1';
			$dir = dirname(__DIR__);
			$file = "$dir/data-v$version.json";
			if (!is_readable($file)) {
				array_map('unlink', glob("$dir/data-v*.json"));
				$json = file_get_contents("https://github.com/cssjanus/cssjanus/raw/v$version/test/data.json");
				if ($json === false) {
					throw new Exception('Failed to fetch data');
				}
				file_put_contents($file, $json);
			} else {
				$json = file_get_contents($file);
			}
		}
		return json_decode($json, /* $assoc = */ true);
	}
}
