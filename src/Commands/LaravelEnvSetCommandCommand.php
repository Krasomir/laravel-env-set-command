<?php

namespace Krasomir\LaravelEnvSetCommand\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LaravelEnvSetCommandCommand extends Command
{
    public const COMMAND_NAME = 'env:set';
    public const ARGUMENT_KEY = 'key';
    public const ARGUMENT_VALUE = 'value';
    public const ARGUMENT_ENV_FILE = 'env_file';

    protected $signature
        = self::COMMAND_NAME
        . ' {' . self::ARGUMENT_KEY . ' : Key or "key=value" pair}'
        . ' {' . self::ARGUMENT_VALUE . '? : Value}'
        . ' {' . self::ARGUMENT_ENV_FILE . '? : Optional path to the .env file}';

    protected $description = 'Set and save an environment variable in the .env file';

    public function handle(): int
    {
        try {
            // Parse key and value arguments.
            [$key, $value, $envFilePath] = $this->parseCommandArguments(
                $this->argument(self::ARGUMENT_KEY),
                $this->argument(self::ARGUMENT_VALUE),
                $this->argument(self::ARGUMENT_ENV_FILE)
            );

            // Use system env file path if the argument env file path is not provided.
            $envFilePath = $envFilePath ?? App::environmentFilePath();
            $this->info("The following environment file is used: '" . $envFilePath . "'");
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if (!$envFilePath) {
            $this->error("The environment file path (". $this->argument(self::ARGUMENT_ENV_FILE) .") is not valid.");
            return self::FAILURE;
        }

        $content = file_get_contents($envFilePath);
        [$newEnvFileContent, $isNewVariableSet] = $this->setEnvVariable($content, $key, $value);

        if ($isNewVariableSet) {
            $this->info("A new environment variable with key '$key' has been set to '$value'");
        } else {
            [$_, $oldValue] = explode('=', $this->readKeyValuePair($content, $key), 2);
            $this->info("Environment variable with key '$key' has been changed from '$oldValue' to '$value'");
        }

        $this->writeFile($envFilePath, $newEnvFileContent);

        $this->comment('All done');
        return self::SUCCESS;
    }

    private function parseCommandArguments(string $_key, ?string $_value, ?string $_envFilePath): array
    {
        $envFilePath = null;

        // Parse "key=value" key argument.
        if (preg_match('#^([^=]+)=(.*)$#umU', $_key, $matches)) {
            [1 => $key, 2 => $value] = $matches;

            // Use second argument as path to env file:
            if ($_value !== null) {
                $envFilePath = $_value;
            }
        } else {
            $key = $_key;
            $value = $_value;
        }

        // If the path to env file is not set, use third argument or return null (default system path).
        if ($envFilePath === null) {
            $envFilePath = $_envFilePath;
        }

        $this->assertKeyIsValid($key);

        // If the value contains spaces but not is not enclosed in quotes.
        if (preg_match('#^[^\'"].*\s+.*[^\'"]$#umU', $value)) {
            $value = '"' . $value . '"';
        }

        return [
            strtoupper($key),
            $value,
            ($envFilePath === null ? null : realpath($envFilePath))
        ];
    }

    private function assertKeyIsValid(string $key): void
    {
        if (Str::contains($key, '=')) {
            throw new InvalidArgumentException('Invalid environment key ' . $key
                . "! Environment key should not contain '='");
        }

        if (!preg_match('/^[a-zA-Z_]+$/', $key)) {
            throw new InvalidArgumentException('Invalid environment key ' . $key
                . '! Only use letters and underscores');
        }
    }

    private function setEnvVariable(string $envFileContent, string $key, string $value): array
    {
        $oldPair = $this->readKeyValuePair($envFileContent, $key);

        // Wrap values that have a space or equals in quotes to escape them
        if ((preg_match('/\\s/',$value) || str_contains($value, '=')) && !(preg_match('#^(["\']).*\1$#', $value))) {
            $value = '\"' . $value . '\"';
        }

        $newPair = $key . '=' . $value;

        // For existed key.
        if ($oldPair !== null) {
            $replaced = preg_replace(
                '/^' . preg_quote($oldPair, '/') . '$/uimU',
                $newPair,
                $envFileContent
            );

            return [$replaced, false];
        }

        // For a new key.
        return [$envFileContent . "\n" . $newPair . "\n", true];
    }

    private function readKeyValuePair(string $envFileContent, string $key): ?string
    {
        // Match the given key at the beginning of a line
        if (preg_match("#^ *{$key} *= *[^\r\n]*$#uimU", $envFileContent, $matches)) {
            return $matches[0];
        }

        return null;
    }

    private function writeFile(string $path, string $contents): void
    {
        file_put_contents($path, $contents, LOCK_EX);
    }
}
