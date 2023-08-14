<?php

namespace GoAroundCustomer;

class DotenvLoader
{
    protected string $path;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("File not found at path: {$path}");
        }

        $this->path = $path;
    }

    public function readDotenv(callable $callback): void
    {
        $variables = [];
        $contents = file($this->path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
        foreach ($contents as $string) {
            list($name, $value) = explode('=', $string);
            $this->variables[trim($name)] = trim($value);
        }
        // $callback($this->variables);
        $this->appendToServer($variables);
    }

    private function appendToServer(array $variables): void {
        foreach ($this->variables as $key => $value) {
            if(!array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $value;
            }
        }
    }
}