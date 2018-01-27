This directory hold several benchmarking scripts used track performances of
MediaWiki and/or PHP.

## Consistency

On Linux, use of `taskset` and `nice` can help get more consistent results.

For example:

 $ taskset 1 nice -n-10 php bench_wfIsWindows.php

## Fixtures

* australia-untidy.html.gz: Representative input text for benchmarkTidy.php.
  It needs to be decompressed before use.
