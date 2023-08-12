<?php

namespace GoAroundCustomer;

abstract class DotenvLoaderClassType
{
    protected string $path;

    public array $variables;

    public function loadDotenv(callable $callback) {}

    private function appendToServer () {}
}

class DotenvLoader extends DotenvLoaderClassType
{
    protected string $path;
    public array $variables;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("File not found at path: {$path}");
        }

        $this->path = $path;
    }

    public function readDotenv(callable $callback): void
    {
        $contents = file($this->path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

        foreach ($contents as $string) {
            list($name, $value) = explode('=', $string);
            $this->variables[trim($name)] = trim($value);
        }
        // $callback($this->variables);
    }

    private function appendToServer(): void {
        foreach ($this->variables as $key => $value) {
            if(!array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $value;
            }
        }
    }
}