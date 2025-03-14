<?php

declare(strict_types=1);

namespace PackageWizard\Installer\Core;

use PackageWizard\Installer\Exceptions\WizardLogicException;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function array_keys;
use function Laravel\Prompts\error;
use function Laravel\Prompts\table;

class Application extends BaseApplication
{
    protected function doRenderThrowable(Throwable $e, OutputInterface $output): void
    {
        try {
            if ($e instanceof WizardLogicException) {
                error($e->getMessage());

                table(array_keys($e->getErrors()[0]), $e->getErrors());

                return;
            }

            parent::doRenderThrowable($e, $output);
        }
        catch (Throwable) {
            parent::doRenderThrowable($e, $output);
        }
    }
}
