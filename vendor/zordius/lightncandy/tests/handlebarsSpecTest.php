<?php

require_once('src/lightncandy.php');

$tmpdir = sys_get_temp_dir();

class HandlebarsSpecTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider jsonSpecProvider
     */
    public function testSpecs($spec)
    {
        global $tmpdir;

        //// Skip bad specs
        // No expect in spec
        if (!isset($spec['expected'])) {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , no expected result in spec, skip.");
        }
        // This spec is bad , lightncandy result as '} hello }' and same with mustache.js
        if ($spec['template'] === '{{{{raw}}}} {{test}} {{{{/raw}}}}') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , bad spec, skip.");
        }
        // missing partial in this spec
        if ($spec['it'] === 'rendering function partial in vm mode') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , bad spec, skip.");
        }
        // Helper depend on an external class, skip it now.
        if ($spec['it'] === 'simple literals work') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , external class not found, skip.");
        }
        // partial not found: global_test
        if ($spec['message'] === 'Partials can use globals or passed') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , partial not found, skip.");
        }
        // lambda not found in spec
        if ($spec['it'] === "bug reported by @fat where lambdas weren't being properly resolved") {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lambda not found, skip.");
        }

        //// Skip unsupported features
        // can not get any hint of 'function' from handlebars-spec , maybe it is a conversion error.
        if (($spec['description'] === 'basic context') && preg_match('/functions/', $spec['it'])) {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , undefined function in spec json, skip.");
        }
        if (preg_match('/(.+) with function argument/', $spec['it'])) {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , undefined function in spec json, skip.");
        }
        if ($spec['it'] === 'Functions are bound to the context in knownHelpers only mode') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , undefined function in spec json, skip.");
        }
        if ($spec['it'] === 'lambdas are resolved by blockHelperMissing, not handlebars proper') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , undefined function in spec json, skip.");
        }
        if ($spec['description'] === '#SafeString') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , undefined function in spec json, skip.");
        }

        // Do not support includeZero now
        if (($spec['description'] === '#if') && preg_match('/includeZero=true/', $spec['template'])) {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lightncandy do not support this now.");
        }

        // Do not support setting options.data now
        if ($spec['it'] === 'data passed to helpers') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lightncandy do not support this now.");
        }

        // Do not support buildin helper : lookup now
        if ($spec['description'] == '#lookup') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lightncandy do not support this now.");
        }

        // Lightncandy will not support old path style as foo/bar , now only support foo.bar .
        if ($spec['it'] === 'literal paths') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lightncandy do not support this now.");
        }

        // Do not support {{~{foo}~}} , use {{{~foo~}}}
        if ($spec['template'] === ' {{~{foo}~}} ') {
            $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , lightncandy do not support this now.");
        }

        // setup helpers
        $helpers = Array();
        if (isset($spec['helpers'])) {
            foreach ($spec['helpers'] as $name => $func) {
                if (!isset($func['php'])) {
                    $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , no PHP helper code provided for this case.");
                }

                // Wrong PHP helper interface in spec, skip.
                preg_match('/function\s*\(.+?\)/', isset($func['javascript']) ? $func['javascript'] : '', $js_args);
                preg_match('/function\s*\(.+?\)/', $func['php'], $php_args);
                $jsn = isset($js_args[0]) ? substr_count($js_args[0], ',') : 0;
                $phpn = isset($php_args[0]) ? substr_count($php_args[0], ',') : 0;
                if ($jsn !== $phpn) {
                    $this->markTestIncomplete("Skip [{$spec['file']}#{$spec['description']}]#{$spec['no']} , PHP helper interface is wrong.");
                }

                $hname = "custom_helper_{$spec['no']}_$name";
                $helpers[$name] = $hname;
                eval(preg_replace('/function/', "function $hname", $func['php'], 1));
            }

        }

        if (($spec['it'] === 'tokenizes hash arguments') || ($spec['it'] === 'tokenizes special @ identifiers')) {
            $helpers['foo'] = function () {return 'ABC';};
        }

        $flag = LightnCandy::FLAG_HANDLEBARSJS | LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_RUNTIMEPARTIAL | LightnCandy::FLAG_EXTHELPER | LightnCandy::FLAG_ERROR_SKIPPARTIAL | LightnCandy::FLAG_EXTHELPER;

        foreach (Array($flag, $flag | LightnCandy::FLAG_STANDALONE) as $f) {
            try {
                $php = LightnCandy::compile($spec['template'], Array(
                    'flags' => $f,
                    'hbhelpers' => $helpers,
                    'basedir' => $tmpdir,
                    'partials' => isset($spec['partials']) ? $spec['partials'] : null,
                ));
            } catch (Exception $e) {
                if (($spec['description'] === 'Tokenizer') && preg_match('/tokenizes inverse .+ as "OPEN_INVERSE.+CLOSE"/', $spec['it'])) {
                    continue;
                }
                print_r(LightnCandy::getContext());
                $this->fail('Exception:' . $e->getMessage());
            }
            $renderer = LightnCandy::prepare($php);
            if ($spec['description'] === 'Tokenizer') {
                // no compile error means passed
                continue;
            }
            $this->assertEquals($spec['expected'], $renderer($spec['data']), "[{$spec['file']}#{$spec['description']}]#{$spec['no']}:{$spec['it']} PHP CODE: $php");
        }
    }

    public function jsonSpecProvider()
    {
        $ret = Array();

        foreach (glob('specs/handlebars/spec/*.json') as $file) {
           $i=0;
           $json = json_decode(file_get_contents($file), true);
           $ret = array_merge($ret, array_map(function ($d) use ($file, &$i) {
               $d['file'] = $file;
               $d['no'] = ++$i;
               if (!isset($d['message'])) {
                   $d['message'] = null;
               }
               if (!isset($d['data'])) {
                   $d['data'] = null;
               }
               return Array($d);
           }, $json));
        }

        return $ret;
    }
}
?>
