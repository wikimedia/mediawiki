<?php

namespace JsonSchema\Tests\Drafts;

use JsonSchema\Tests\Constraints\BaseTestCase;

abstract class BaseDraftTestCase extends BaseTestCase
{
    protected $relativeTestsRoot = '/../../../../vendor/json-schema/JSON-Schema-Test-Suite/tests';

    private function setUpTests($isValid)
    {
        $filePaths = $this->getFilePaths();
        $skippedTests = $this->getSkippedTests();
        $tests = array();

        foreach ($filePaths as $path) {
            foreach (glob($path . '/*.json') as $file) {
                if (!in_array(basename($file), $skippedTests)) {
                    $suites = json_decode(file_get_contents($file));
                    foreach ($suites as $suite) {
                        foreach ($suite->tests as $test) {
                            if ($isValid === $test->valid) {
                                $tests[] = array(json_encode($test->data), json_encode($suite->schema));
                           }
                        }
                    }
                }
            }
        }

        return $tests;
    }

    public function getInvalidTests()
    {
        return $this->setUpTests(false);
    }

    public function getValidTests()
    {
        return $this->setUpTests(true);
    }

    protected abstract function getFilePaths();

    protected abstract function getSkippedTests();
}
