# Known-array count benchmark

Each iteration updates one of 100 array keys and adds `count($items)` to a
checksum. Mutation prevents measuring only a loop-invariant count. This is
an isolated array-update/count workload, not an application-wide benchmark.

From this directory with PHP 8.5 and a matching PHPX/embed installation:

```sh
php run.php 30000000
php ../../bin/tpc.php project.yml --no-progress -o array_count_benchmark
./array_count_benchmark 30000000
```

Expected checksum: `2999995050`. Compile baseline and candidate into separate
binary paths and build directories, keeping PHPX and compiler flags fixed.
Alternate execution order, discard a warm-up pair, and compare medians.

## Sample result

Linux ARM64 in Docker, PHP 8.5.10 ZTS, PHPX `6a68f38`, GCC `-O2`, no LTO;
TypePHP baseline `692841a6` versus direct known-array count lowering.
30 million iterations, nine measured pairs after one discarded warm-up:

| Build | Median | Range |
| --- | ---: | ---: |
| Baseline | 319.63 ms | 301.22–345.40 ms |
| Direct count | 192.73 ms | 185.74–196.94 ms |

Elapsed time decreased 39.70%; all nine pairs were faster and checksums
matched. Raw samples in seconds are in `results-arm64.json`. Measurements
include process startup and exclude compilation, on a shared development
machine. They do not imply the same application-wide improvement or a
speedup against native PHP.
