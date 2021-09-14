This directory hold several benchmarking scripts used track performances of
MediaWiki and/or PHP.

## Consistency

To gain greater precision than the time elapsed as reported by the benchmark
itself, one can use the `perf_events` tool on Linux to count the number of
CPU instructions, in addition to measuring how long it took to execute them.

This should accurately tell you how much machine code is executed. Note that
this does not correctly model the cost of each instruction (especially
memory access).

For example:

```
$ perf stat -e instructions php -d opcache.enable_cli=1 \
  maintenance/benchmarks/benchmarkEval.php --code="Html::openElement( 'a', [ 'class' => 'foo' ] )" \
  --inner=1000 --count=10000

eval:
   count: 10000
    rate:    590.1/s
   total: 16946.61ms
    mean:     1.69ms
      ...

Performance counter stats for 'php maintenance/benchmarks/..':
    83651088078  instructions
   17.225255198  seconds time elapsed
            ...
```

## Fixtures

* data/tidy/australia-untidy.html.gz: Representative input text for benchmarkTidy.php.
  It needs to be decompressed before use.
* data/CommentFormatter/rc100-2021-07-29.json: Input for Linker::formatComment() from
https://en.wikipedia.org/w/api.php?action=query&format=json&list=recentchanges&rcprop=title%7Ccomment&rclimit=100
