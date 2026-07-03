<?php

namespace webhubworks\ohdear\health\checks;

use Craft;
use DateTimeImmutable;
use DateTimeZone;
use OhDear\HealthCheckResults\CheckResult;
use Throwable;
use webhubworks\ohdear\health\exceptions\IndicatesTransientFailure;

/**
 * Read-from-cache behavior for checks whose results are refreshed
 * out-of-band via the `ohdear/health-check/refresh` console command.
 *
 * Opt in per check via ->cachedViaCron($staleAfterSeconds). Until enabled,
 * compute() runs inline on every endpoint hit (legacy behavior).
 */
trait CachesResult
{
    private ?int $staleAfterSeconds = null;

    /**
     * Enable cron-refreshed caching for this check. The cached result is
     * considered fresh for $staleAfterSeconds; older entries are still
     * returned but the status is downgraded to STATUS_WARNING with a
     * staleSince entry in meta. The console refresh command should run
     * more frequently than $staleAfterSeconds to keep entries fresh.
     */
    public function cachedViaCron(int $staleAfterSeconds): static
    {
        if ($staleAfterSeconds < 1) {
            throw new \InvalidArgumentException('staleAfterSeconds must be greater than 0.');
        }

        $this->staleAfterSeconds = $staleAfterSeconds;

        return $this;
    }

    final public function run(): CheckResult
    {
        if ($this->staleAfterSeconds === null) {
            try {
                return $this->compute();
            } catch (IndicatesTransientFailure $e) {
                return $this->couldNotRunResult($e);
            }
        }

        $cached = $this->getValidCachedEntry();

        if ($cached === null) {
            return $this->notYetComputedResult();
        }

        $computedAt = (int) ($cached['computedAt'] ?? 0);
        $ageSeconds = time() - $computedAt;
        $result = $cached['result'];

        if ($ageSeconds > $this->staleAfterSeconds) {
            return $this->markStale($result, $computedAt, $ageSeconds);
        }

        return $this->annotateWithComputedAt($result, $computedAt);
    }

    /**
     * @throws IndicatesTransientFailure when compute failed transiently. A
     * previously cached result is left in place (with its TTL renewed, so it
     * survives until the next refresh attempt); staleness is still measured
     * from the last successful compute, so persistent failures surface as a
     * stale warning after staleAfterSeconds.
     */
    public function refresh(): CheckResult
    {
        try {
            $result = $this->compute();
        } catch (IndicatesTransientFailure $e) {
            $cached = $this->getValidCachedEntry();

            if ($cached === null) {
                // Nothing to fall back on: cache the failure so the endpoint
                // reports "could not run" instead of "not yet computed".
                $this->storeInCache($this->couldNotRunResult($e), time());
            } else {
                $this->storeInCache($cached['result'], (int) ($cached['computedAt'] ?? 0));
            }

            throw $e;
        }

        $this->storeInCache($result, time());

        return $result;
    }

    abstract protected function compute(): CheckResult;

    /**
     * The `name` field set on the CheckResult returned by compute(). Used to
     * keep cache-miss/stale warnings consistent with successful results in
     * the Oh Dear UI.
     */
    abstract protected function checkResultName(): string;

    /**
     * The `label` field set on the CheckResult returned by compute().
     */
    abstract protected function checkResultLabel(): string;

    private function getCacheKey(): string
    {
        return 'ohdear-check-result:' . $this->checkResultName();
    }

    /**
     * @return array{result: CheckResult, computedAt: mixed}|null
     */
    private function getValidCachedEntry(): ?array
    {
        $cached = Craft::$app->getCache()->get($this->getCacheKey());

        if (!is_array($cached) || !($cached['result'] ?? null) instanceof CheckResult) {
            return null;
        }

        return $cached;
    }

    private function storeInCache(CheckResult $result, int $computedAt): void
    {
        Craft::$app->getCache()->set(
            $this->getCacheKey(),
            ['result' => $result, 'computedAt' => $computedAt],
            $this->staleAfterSeconds !== null ? $this->staleAfterSeconds * 2 : 86400,
        );
    }

    private function couldNotRunResult(IndicatesTransientFailure $e): CheckResult
    {
        return new CheckResult(
            name: $this->checkResultName(),
            label: $this->checkResultLabel(),
            notificationMessage: $e->getMessage(),
            shortSummary: 'Check could not run',
            status: CheckResult::STATUS_WARNING,
        );
    }

    private function notYetComputedResult(): CheckResult
    {
        return new CheckResult(
            name: $this->checkResultName(),
            label: $this->checkResultLabel(),
            notificationMessage: 'No cached result yet. Schedule `craft ohdear/health-check/refresh` on cron to populate this check.',
            shortSummary: 'Not yet computed',
            status: CheckResult::STATUS_WARNING,
        );
    }

    private function markStale(CheckResult $cached, int $computedAt, int $ageSeconds): CheckResult
    {
        $meta = $this->mergeMeta($cached, [
            'staleSince' => $this->formatTimestamp($computedAt),
            'ageSeconds' => $ageSeconds,
        ]);

        return new CheckResult(
            name: $cached->name,
            label: $cached->label,
            notificationMessage: sprintf(
                '%s (stale: last refreshed %s)',
                $cached->notificationMessage,
                $this->formatTimestamp($computedAt),
            ),
            shortSummary: $cached->shortSummary,
            status: CheckResult::STATUS_WARNING,
            meta: $meta,
        );
    }

    private function annotateWithComputedAt(CheckResult $cached, int $computedAt): CheckResult
    {
        return new CheckResult(
            name: $cached->name,
            label: $cached->label,
            notificationMessage: $cached->notificationMessage,
            shortSummary: $cached->shortSummary,
            status: $cached->status,
            meta: $this->mergeMeta($cached, [
                'lastRefreshedAt' => $this->formatTimestamp($computedAt),
            ]),
        );
    }

    private function mergeMeta(CheckResult $cached, array $extra): array
    {
        try {
            $meta = (array) ($cached->meta ?? []);
        } catch (Throwable) {
            $meta = [];
        }

        return array_merge($meta, $extra);
    }

    private function formatTimestamp(int $timestamp): string
    {
        return (new DateTimeImmutable('@' . $timestamp))
            ->setTimezone(new DateTimeZone(date_default_timezone_get()))
            ->format('Y-m-d H:i:s');
    }
}
