<?php declare(strict_types=1);

namespace DCarbone\Go\HTTP\URL;

/**
 * Class Values
 * @package DCarbone\Go\HTTP\URL
 */
class Values implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    /** @var array<string, string[]> */
    private array $values = [];

    /**
     * Values constructor.
     * @param array $seed
     */
    public function __construct(array $seed = [])
    {
        foreach ($seed as $k => $v) {
            $this->add($k, $v);
        }

    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        if (isset($this->values[$key])) {
            return $this->values[$key][0];
        }
        return '';
    }

    /**
     * @param string $key
     * @return string[]
     */
    public function getAll(string $key): array
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
        return [];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value): void
    {
        $this->values[$key] = [$value];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function add(string $key, string $value): void
    {
        if (isset($this->values[$key])) {
            $this->values[$key][] = $value;
        } else {
            $this->values[$key] = [$value];
        }
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->values[$key]);
    }

    /**
     * @return array
     */
    public function toPsr7Array(): array
    {
        return $this->values;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * @return string|array
     */
    public function current(): mixed
    {
        return current($this->values);
    }

    public function next(): void
    {
        next($this->values);
    }

    /**
     * @return string
     */
    public function key(): mixed
    {
        return key($this->values);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return null !== key($this->values);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) {
            return false;
        }
        return isset($this->values[$offset]);
    }

    /**
     * @param string $offset
     * @return string
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get(self::assertStringOffset($offset));
    }

    /**
     * @param string $offset
     * @param string $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set(self::assertStringOffset($offset), self::assertStringValue($value));
    }

    /**
     * @param string $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->delete(self::assertStringOffset($offset));
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $str = '';
        foreach ($this->values as $k => $vs) {
            foreach ($vs as $v) {
                if ('' !== $str) {
                    $str .= '&';
                }
                if ('' === $v) {
                    $str .= $k;
                } else {
                    $str .= sprintf('%s=%s', $k, $this->encode($v));
                }
            }
        }
        return $str;
    }

    /**
     * @param string $v
     * @return string
     */
    protected function encode(string $v): string
    {
        return $v;
    }

    private static function assertStringOffset(mixed $offset): string
    {
        if (!is_string($offset)) {
            throw new \TypeError(sprintf('Expected offset to be string, got %s', get_debug_type($offset)));
        }

        return $offset;
    }

    private static function assertStringValue(mixed $value): string
    {
        if (!is_string($value)) {
            throw new \TypeError(sprintf('Expected value to be string, got %s', get_debug_type($value)));
        }

        return $value;
    }
}