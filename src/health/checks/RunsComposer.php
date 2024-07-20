<?php

namespace webhubworks\ohdear\health\checks;

use Craft;
use craft\helpers\App;
use craft\helpers\FileHelper;
use Symfony\Component\Process\Process;
use yii\base\Exception;

trait RunsComposer
{
    /**
     * @throws Exception
     */
    private function getAuditResult(): array
    {
        $auditCommand = $this->runComposerCommand(
            Craft::$app->composer->getJsonPath(),
            ['--format=json', 'audit'],
        );

        return json_decode($auditCommand->getOutput(), true);
    }

    /**
     * @throws Exception
     */
    private function getPackageInformation(string $requiredByPackage): array
    {
        $showCommand = $this->runComposerCommand(
            Craft::$app->composer->getJsonPath(),
            ['--format=json', 'show', $requiredByPackage],
        );

        return json_decode($showCommand->getOutput(), true);
    }

    /**
     * @throws Exception
     */
    private function getWhyResult(string $packageName): array
    {
        $whyCommand = $this->runComposerCommand(
            Craft::$app->composer->getJsonPath(),
            ['why', $packageName],
        );

        $whyOutput = $whyCommand->getOutput();

        return explode(" ", $whyOutput);
    }

    /**
     * This is copied from Craft's code base. It uses a Composer binary that is
     * shipped with the CMS package.
     *
     * @param string $jsonPath
     * @param array $command
     * @return Process
     */
    private function runComposerCommand(string $jsonPath, array $command): Process
    {
        // Copy composer.phar into storage/
        $pharPath = sprintf('%s/composer.phar', Craft::$app->getPath()->getRuntimePath());
        copy(Craft::getAlias('@lib/composer.phar'), $pharPath);

        $command = array_merge([
            App::phpExecutable() ?? 'php',
            $pharPath,
        ], $command, [
            '--working-dir',
            dirname($jsonPath),
            '--no-scripts',
            '--no-ansi',
            '--no-interaction',
        ]);

        $homePath = Craft::$app->getPath()->getRuntimePath() . DIRECTORY_SEPARATOR . 'composer';
        FileHelper::createDirectory($homePath);

        $process = new Process($command, null, [
            'COMPOSER_HOME' => $homePath,
        ]);
        $process->setTimeout(null);

        try {
            $process->run();
            $process->wait();
            return $process;
        } finally {
            unlink($pharPath);
        }
    }
}
