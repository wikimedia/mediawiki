This directory hold several benchmarking scripts used track performances of
MediaWiki and/or PHP.

## Consistency

On Linux, use of `taskset` and `nice` can help get more consistent results.

For example:

 $ taskset 1 nice -n-10 php bench_wfIsWindows.php

## Fixtures

* data/tidy/australia-untidy.html.gz: Representative input text for benchmarkTidy.php.
  It needs to be decompressed before use.
* data/CommentFormatter/rc100-2021-07-29.json: Input for Linker::formatComment() from
https://en.wikipedia.org/w/api.php?action=query&format=json&list=recentchanges&rcprop=title%7Ccomment&rclimit=100
