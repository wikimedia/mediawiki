<?php

require_once('src/lightncandy.php');

$tmpdir = sys_get_temp_dir();

class MustacheSpecTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider jsonSpecProvider
     */
    public function testSpecs($spec)
    {
        global $tmpdir;

        $flag = LightnCandy::FLAG_MUSTACHE | LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_RUNTIMEPARTIAL;

        foreach (Array($flag, $flag | LightnCandy::FLAG_STANDALONE) as $f) {
            $php = LightnCandy::compile($spec['template'], Array(
                'flags' => $f,
                'partials' => isset($spec['partials']) ? $spec['partials'] : null,
                'basedir' => $tmpdir,
            ));
            $renderer = LightnCandy::prepare($php);
            $this->assertEquals($spec['expected'], $renderer($spec['data']), "[{$spec['file']}.{$spec['name']}]#{$spec['no']}:{$spec['desc']} PHP CODE: $php");
        }
    }

    public function jsonSpecProvider()
    {
        $ret = Array();

        foreach (glob('specs/mustache/specs/*.json') as $file) {
            // Skip lambda extension
            if (preg_match('/lambdas\\.json$/', $file)) {
                continue;
            }

            $i=0;
            $json = json_decode(file_get_contents($file), true);
            $ret = array_merge($ret, array_map(function ($d) use ($file, &$i) {
                $d['file'] = $file;
                $d['no'] = ++$i;
                return Array($d);
            }, $json['tests']));
        }

        return $ret;
    }
}


?>
