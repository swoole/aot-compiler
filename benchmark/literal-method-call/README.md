# Literal method expression benchmark

Repeatedly calls `$target->{'run'}($value)` through an `object` parameter.
The method name is a string literal, while the receiver is resolved at runtime.
This isolates repeated resolution of a fixed method name.

From this directory with PHP 8.5 and a matching PHPX/embed installation:

```sh
php run.php 10000000
php ../../bin/tpc.php project.yml --no-progress -o literal_method_benchmark
./literal_method_benchmark 10000000
```

Expected checksum: `505000000`. Build baseline and candidate into separate
build directories and binary paths with the same PHPX library and flags.
Alternate execution order, discard a warm-up pair, then compare medians.

## Sample result

Linux ARM64 in Docker, PHP 8.5.10 ZTS, PHPX `db0aabd`, GCC `-O2`, no LTO;
baseline TypePHP `91f133f1` versus literal-name call caching.
10 million calls, nine measured pairs after one discarded warm-up:

| Build | Median |
| --- | ---: |
| Baseline | 357.32 ms |
| Literal-name cache | 235.60 ms |

Elapsed time decreased 34.06%, with all nine pairs faster and matching
checksums. Raw samples in seconds are in `results-arm64.json`. Measurements
include process startup on a shared development machine; this is not an
application-wide speedup or a claim about native PHP execution speed.
