<?php
namespace JakubOnderka\PhpParallelLint\Process;

class SkipLintProcess extends PhpProcess
{
    /** @var array */
    private $skipped = array();

    /** @var bool */
    private $done = false;

    /** @var string */
    private $endLastChunk = '';

    /**
     * @param PhpExecutable $phpExecutable
     * @param array $filesToCheck
     */
    public function __construct(PhpExecutable $phpExecutable, array $filesToCheck)
    {
        $parameters = array('-n ' . escapeshellarg(__DIR__ . '/../../bin/skip-linting.php'));

        parent::__construct($phpExecutable, $parameters, implode("\n", $filesToCheck));
    }

    public function getChunk()
    {
        if (!$this->isFinished()) {
            $this->processLines(fread($this->stdout, 8192));
        }
    }

    /**
     * @return bool
     * @throws \JakubOnderka\PhpParallelLint\RunTimeException
     */
    public function isFinished()
    {
        $isFinished = parent::isFinished();
        if ($isFinished && !$this->done) {
            $this->done = true;
            $output = $this->getOutput();
            $this->processLines($output);
        }

        return $isFinished;
    }

    /**
     * @param string $file
     * @return bool|null
     */
    public function isSkipped($file)
    {
        if (isset($this->skipped[$file])) {
            return $this->skipped[$file];
        }

        return null;
    }

    /**
     * @param string $content
     */
    private function processLines($content)
    {
        if (!empty($content)) {
            $lines = explode("\n", $this->endLastChunk . $content);
            $this->endLastChunk = array_pop($lines);
            foreach ($lines as $line) {
                $parts = explode(';', $line);
                list($file, $status) = $parts;
                $this->skipped[$file] = $status === '1' ? true : false;
            }
        }
    }
}