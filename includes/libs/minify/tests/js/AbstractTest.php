<?php

use MatthiasMullie\Minify;
use MatthiasMullie\Scrapbook\Adapters\MemoryStore;
use MatthiasMullie\Scrapbook\Psr6\Pool;

/**
 * Tests common functions of abstract Minify class by using JS implementation.
 */
class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $path1 = __DIR__.'/sample/source/script1.js';
        $path2 = __DIR__.'/sample/source/script2.js';
        $content1 = file_get_contents($path1);
        $content2 = file_get_contents($path2);

        // 1 source in constructor
        $minifier = new Minify\JS($content1);
        $result = $minifier->minify();

        $this->assertEquals($content1, $result);

        // multiple sources in constructor
        $minifier = new Minify\JS($content1, $content2);
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2, $result);

        // file in constructor
        $minifier = new Minify\JS($path1);
        $result = $minifier->minify();

        $this->assertEquals($content1, $result);

        // multiple files in constructor
        $minifier = new Minify\JS($path1, $path2);
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2, $result);
    }

    /**
     * @test
     */
    public function add()
    {
        $path1 = __DIR__.'/sample/source/script1.js';
        $path2 = __DIR__.'/sample/source/script2.js';
        $content1 = file_get_contents($path1);
        $content2 = file_get_contents($path2);
        $content3 = 'var test=3';

        // 1 source in add
        $minifier = new Minify\JS();
        $minifier->add($content1);
        $result = $minifier->minify();

        $this->assertEquals($content1, $result);

        // multiple sources in add
        $minifier = new Minify\JS();
        $minifier->add($content1);
        $minifier->add($content2);
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2, $result);

        // file in add
        $minifier = new Minify\JS();
        $minifier->add($path1);
        $result = $minifier->minify();

        $this->assertEquals($content1, $result);

        // multiple files in add
        $minifier = new Minify\JS();
        $minifier->add($path1);
        $minifier->add($path2);
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2, $result);

        // array of files in add
        $minifier = new Minify\JS();
        $minifier->add(array($path1, $path2));
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2, $result);

        // array of files + overload in add
        $minifier = new Minify\JS();
        $minifier->add(array($path1, $path2), $content3);
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2.';'.$content3, $result);

        $minifier = new Minify\JS();
        $minifier->add($path1, array($path2, $content3));
        $result = $minifier->minify();

        $this->assertEquals($content1.';'.$content2.';'.$content3, $result);
    }

    /**
     * @test
     */
    public function loadBigString()
    {
        // content greater than PHP_MAXPATHLEN
        // https://github.com/matthiasmullie/minify/issues/90
        $content = rtrim(str_repeat('var a="b";', 500), ';');

        $minifier = new Minify\JS($content);

        $this->assertEquals($minifier->minify(), $content);
    }

    /**
     * @test
     */
    public function loadOpenBaseDirRestricted()
    {
        if (!function_exists('pcntl_fork') || defined('HHVM_VERSION')) {
            $this->markTestSkipped("Can't fork, skip open_basedir test");
        }

        /*
         * Testing open_basedir restrictions is rather annoying, since they can
         * not be relaxed at runtime (if we tighten open_basedir for 1 test,
         * it'll also apply for all the others)
         * I'll run the open_basedir restriction in a separate thread instead.
         */

        $pid = pcntl_fork();
        if ($pid === -1) {
            // can't fork, ignore this test...
        } elseif ($pid === 0) {
            // https://github.com/matthiasmullie/minify/issues/111
            ini_set('open_basedir', __DIR__.'/../..');

            // instead of displaying warnings & moving to the next test, just
            // quit with the error code; the other thread will pick it up
            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                // only exit if it is an error that is being reported (we don't
                // want suppressed errors to register as failures, that happens
                // on purpose!)
                if (error_reporting() & $errno) {
                    exit($errno);
                }
            });

            $minifier = new Minify\JS('/tmp/bogus/path');
            $minifier->minify();

            // don't keep executing the rest of the tests in this thread!
            exit;
        } else {
            pcntl_wait($status);

            $this->assertEquals($status, 0, 'open_basedir restriction caused minifier to fail');
        }
    }

    /**
     * @test
     */
    public function save()
    {
        $path = __DIR__.'/sample/source/script1.js';
        $content = file_get_contents($path);
        $savePath = __DIR__.'/sample/target/script1.js';

        $minifier = new Minify\JS($path);
        $minifier->minify($savePath);

        $this->assertEquals(file_get_contents($savePath), $content);
    }

    /**
     * @test
     *
     * @expectedException MatthiasMullie\Minify\Exceptions\IOException
     */
    public function checkFileOpenFail()
    {
        $minifier = new Minify\JS();
        $wrongPath = '';

        $object = new ReflectionObject($minifier);
        $method = $object->getMethod('openFileForWriting');
        $method->setAccessible(true);

        $method->invokeArgs($minifier, array($wrongPath));
    }

    /**
     * @test
     *
     * @expectedException MatthiasMullie\Minify\Exceptions\IOException
     */
    public function checkFileWriteFail()
    {
        $minifier = new Minify\JS();
        $wrongPath = '';

        $object = new ReflectionObject($minifier);
        $method = $object->getMethod('writeToFile');
        $method->setAccessible(true);

        $method->invokeArgs($minifier, array($wrongPath, ''));
    }

    /**
     * @test
     */
    public function gzip()
    {
        $path = __DIR__.'/sample/source/script1.js';
        $content = file_get_contents($path);
        $savePath = __DIR__.'/sample/target/script1.js.gz';

        $minifier = new Minify\JS($path);
        $minifier->gzip($savePath, 9);

        $this->assertEquals(file_get_contents($savePath), gzencode($content, 9, FORCE_GZIP));
    }

    /**
     * @test
     */
    public function cache()
    {
        $path = __DIR__.'/sample/source/script1.js';
        $content = file_get_contents($path);

        $cache = new MemoryStore();
        $pool = new Pool($cache);
        $item = $pool->getItem('cache-script1');

        $minifier = new Minify\JS($path);
        $item = $minifier->cache($item);

        $this->assertEquals($item->get(), $content);
    }
}
