<?php declare(strict_types=1);

namespace Comquer\Reflection\ClassNamespace;

use ReflectionClass;

class ClassNamespace
{
    private $namespace;

    public function __construct(string $namespace)
    {
        self::validateNamespace($namespace);
        $this->namespace = $namespace;
    }

    public function getParents(): ClassNamespaceCollection
    {
        $parents = new ClassNamespaceCollection();
        foreach (class_parents((string) $this) as $parent) {
            $parents->add(new self($parent));
        }
        return $parents;
    }

    public function mustHaveMethod(string $methodName): void
    {
        if ((new ReflectionClass($this->namespace))->hasMethod($methodName) === false) {
            throw ClassNamespaceException::missingMethod($this->namespace, $methodName);
        }
    }

    public function equals(self $namespace): bool
    {
        return (string) $this === (string) $namespace;
    }

    public function __toString(): string
    {
        return $this->namespace;
    }

    private static function validateNamespace(string $namespace): void
    {
        if (class_exists($namespace) === false) {
            throw ClassNamespaceException::invalidNamespace($namespace);
        }
    }
}
