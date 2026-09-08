# Known-array append benchmark

This benchmark appends a function result to a known array, 1,000 times per
round, and accumulates a checksum. It isolates the append fallback that must
materialize its RHS before writing. It does not represent every PHP workload.

With PHP 8.5 and a matching PHPX/embed installation, from this directory:

```sh
php run.php 100000
php ../../bin/tpc.php project.yml --no-progress -o array_append_benchmark
./array_append_benchmark 100000
```

The checksum must be `200000000`. Build baseline and candidate revisions into
separate build directories and binary paths. Keep PHPX, PHP, compiler flags,
and machine fixed. Alternate binary execution order, discard a warm-up pair,
and compare medians across multiple runs; exclude compilation time.

## Sample result

Linux ARM64 in Docker, PHP 8.5.10 ZTS, PHPX `6a68f38`, GCC `-O2`, no LTO;
baseline TypePHP `692841a6` versus direct known-array append lowering.
Nine measured runs per binary after one discarded pair, 100,000 rounds:

| Build | Median | Range |
| --- | ---: | ---: |
| Baseline | 980.88 ms | 970.47–1018.14 ms |
| Direct append | 563.60 ms | 558.99–582.68 ms |

Elapsed time decreased by 42.54% for this append-heavy microbenchmark; all
nine paired runs were faster. This is not an application-wide speedup or a
comparison against native PHP. Measurements include process startup and were
collected on a shared development machine. Raw times in seconds are in
`results-arm64.json`; its `count` field is the number of rounds.
