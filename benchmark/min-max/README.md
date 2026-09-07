# Integer min/max benchmark

This benchmark repeatedly clamps integer damage and accumulates a checksum. It
isolates two-argument integer `min()` / `max()` calls; it is not representative
of every PHP workload. It emits one line after the loop, so terminal output is
not part of the hot path.

From this directory, with PHP 8.5 and a matching PHPX/embed installation:

```sh
php run.php 10000000
php ../../bin/tpc.php project.yml --no-progress -o min_max_benchmark
./min_max_benchmark 10000000
```

The PHP and AOT checksums must match. Compile baseline and candidate revisions
into different build directories and binary paths, then alternate their
execution order. Exclude compilation time, discard a warm-up pair, and compare
medians over multiple runs. Keep PHPX, PHP, compiler flags, and machine fixed.

## Sample result

Linux ARM64 in Docker, PHP 8.5.10 ZTS, PHPX `6a68f38`, GCC `-O2`;
baseline TypePHP `72b7ce9b` versus this integer min/max lowering change.
Nine measured runs per binary after one discarded pair, 10,000,000 iterations:

| Build | Median | Range |
| --- | ---: | ---: |
| Baseline | 482.56 ms | 456.41–497.78 ms |
| Integer lowering | 48.01 ms | 44.10–57.38 ms |

This is a 10.05x speedup for the isolated workload. A separate local turn-based
game test (100,000 games, nine samples) had medians of 1648.95 ms and
1610.13 ms, a 2.35% reduction. The game samples overlap substantially;
that small difference is not established as a reliable whole-game speedup.

The game includes many object, array, and string operations. This change does
not imply that AOT is universally faster than PHP or JIT. Timings come from a
shared development machine, not a dedicated benchmark host. Raw microbenchmark
samples are in `results-arm64.json`; times include process startup and exclude
compilation.
