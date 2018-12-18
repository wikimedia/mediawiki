<?php

class ProfilerExcimer extends Profiler {
	private $cpuProf;
	private $realProf;
	private $period;

	/**
	 * @param array $params Associative array of parameters:
	 *    - period: The sampling period
	 *    - maxDepth: The maximum stack depth collected
	 *    - cpuProfiler: A pre-started ExcimerProfiler instance for CPU
	 *      profiling of the entire request including configuration.
	 *    - realProfiler: A pre-started ExcimerProfiler instance for wall
	 *      clock profiling of the entire request.
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		$this->period = $params['period'] ?? 0.01;
		$maxDepth = $params['maxDepth'] ?? 100;

		if ( isset( $params['cpuProfiler'] ) ) {
			$this->cpuProf = $params['cpuProfiler'];
		} else {
			$this->cpuProf = new ExcimerProfiler;
			$this->cpuProf->setEventType( EXCIMER_CPU );
			$this->cpuProf->setPeriod( $this->period );
			$this->cpuProf->setMaxDepth( $maxDepth );
			$this->cpuProf->start();
		}

		if ( isset( $params['realProfiler'] ) ) {
			$this->realProf = $params['realProfiler'];
		} else {
			$this->realProf = new ExcimerProfiler;
			$this->realProf->setEventType( EXCIMER_REAL );
			$this->realProf->setPeriod( $this->period );
			$this->realProf->setMaxDepth( $maxDepth );
			$this->realProf->start();
		}
	}

	public function scopedProfileIn( $section ) {
	}

	public function close() {
		$this->cpuProf->stop();
		$this->realProf->stop();
	}

	public function getFunctionStats() {
		$this->close();
		$cpuStats = $this->cpuProf->getLog()->aggregateByFunction();
		$realStats = $this->realProf->getLog()->aggregateByFunction();
		$allNames = array_keys( $realStats + $cpuStats );
		$cpuSamples = $this->cpuProf->getLog()->getEventCount();
		$realSamples = $this->realProf->getLog()->getEventCount();

		$resultStats = [ [
			'name' => '-total',
			'calls' => 1,
			'memory' => 0,
			'%memory' => 0,
			'min_real' => 0,
			'max_real' => 0,
			'cpu' => $cpuSamples * $this->period * 1000,
			'%cpu' => 100,
			'real' => $realSamples * $this->period * 1000,
			'%real' => 100,
		] ];

		foreach ( $allNames as $funcName ) {
			$cpuEntry = $cpuStats[$funcName] ?? false;
			$realEntry = $realStats[$funcName] ?? false;
			$resultEntry = [
				'name' => $funcName,
				'calls' => 0,
				'memory' => 0,
				'%memory' => 0,
				'min_real' => 0,
				'max_real' => 0,
			];

			if ( $cpuEntry ) {
				$resultEntry['cpu'] = $cpuEntry['inclusive'] * $this->period * 1000;
				$resultEntry['%cpu'] = $cpuEntry['inclusive'] / $cpuSamples * 100;
			} else {
				$resultEntry['cpu'] = 0;
				$resultEntry['%cpu'] = 0;
			}
			if ( $realEntry ) {
				$resultEntry['real'] = $realEntry['inclusive'] * $this->period * 1000;
				$resultEntry['%real'] = $realEntry['inclusive'] / $realSamples * 100;
			} else {
				$resultEntry['real'] = 0;
				$resultEntry['%real'] = 0;
			}

			$resultStats[] = $resultEntry;
		}
		return $resultStats;
	}

	public function getOutput() {
		$this->close();
		$cpuLog = $this->cpuProf->getLog();
		$realLog = $this->realProf->getLog();
		$cpuStats = $cpuLog->aggregateByFunction();
		$realStats = $realLog->aggregateByFunction();
		$allNames = array_keys( $cpuStats + $realStats );
		$cpuSamples = $cpuLog->getEventCount();
		$realSamples = $realLog->getEventCount();

		$result = '';

		$titleFormat = "%-70s %10s %11s %10s %11s %10s %11s %10s %11s\n";
		$statsFormat = "%-70s %10d %10.1f%% %10d %10.1f%% %10d %10.1f%% %10d %10.1f%%\n";
		$result .= sprintf( $titleFormat,
			'Name',
			'CPU incl', 'CPU incl%', 'CPU self', 'CPU self%',
			'Real incl', 'Real incl%', 'Real self', 'Real self%'
		);

		foreach ( $allNames as $funcName ) {
			$realEntry = $realStats[$funcName] ?? false;
			$cpuEntry = $cpuStats[$funcName] ?? false;
			$realIncl = $realEntry ? $realEntry['inclusive'] : 0;
			$realSelf = $realEntry ? $realEntry['self'] : 0;
			$cpuIncl = $cpuEntry ? $cpuEntry['inclusive'] : 0;
			$cpuSelf = $cpuEntry ? $cpuEntry['self'] : 0;
			$result .= sprintf( $statsFormat,
				$funcName,
				$cpuIncl * $this->period * 1000,
				$cpuIncl == 0 ? 0 : $cpuIncl / $cpuSamples * 100,
				$cpuSelf * $this->period * 1000,
				$cpuSelf == 0 ? 0 : $cpuSelf / $cpuSamples * 100,
				$realIncl * $this->period * 1000,
				$realIncl == 0 ? 0 : $realIncl / $realSamples * 100,
				$realSelf * $this->period * 1000,
				$realSelf == 0 ? 0 : $realSelf / $realSamples * 100
			);
		}

		return $result;
	}
}
