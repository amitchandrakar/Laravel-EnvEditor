<?php

namespace GeoSot\EnvEditor\Helpers;

use Illuminate\Support\Arr;

class EntryObj
{
    public string $key;

    /**
     * @var int|string|null
     */
    protected $value;

    public int $group = 0;

    public int $index = 0;

    protected bool $isSeparator = false;

    /**
     * @param  string  $key
     * @param  int|string|null  $value
     * @param  int  $group
     * @param  int  $index
     * @param  bool  $isSeparator
     */
    public function __construct(string $key, $value, int $group, int $index, bool $isSeparator = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->group = $group;
        $this->index = $index;
        $this->isSeparator = $isSeparator;
    }

    public static function parseEnvLine(string $line, int $group, int $index): self
    {
        $entry = explode('=', $line, 2);
        $isSeparator = count($entry) === 1;

        return new self(Arr::get($entry, 0), Arr::get($entry, 1), $group, $index, $isSeparator);
    }

    public static function makeKeysSeparator(int $groupIndex, int $index): self
    {
        return new self('', '', $groupIndex, $index, true);
    }

    public function getAsEnvLine(): string
    {
        return $this->isSeparator() ? '' : "$this->key=$this->value";
    }

    /**
     * @return bool
     */
    public function isSeparator(): bool
    {
        return $this->isSeparator;
    }

    /**
     * @param  mixed  $default
     * @return int|string|null
     */
    public function getValue($default = null)
    {
        return $this->value ?: $default;
    }

    /**
     * @param  int|string|null  $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return array{key:string, value: int|string|null, group:int, index:int , isSeparator:bool}
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
