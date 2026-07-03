<?php

namespace webhubworks\ohdear\health\exceptions;

use Throwable;

/**
 * Marks exceptions caused by conditions that are expected to resolve on their
 * own, e.g. a Packagist timeout during `composer audit`. Cron-cached checks
 * keep serving their last good result when compute fails with one of these,
 * instead of overwriting it with a failure result.
 */
interface IndicatesTransientFailure extends Throwable
{
}
