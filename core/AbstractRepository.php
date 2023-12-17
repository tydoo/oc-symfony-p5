<?php

namespace Core;

use Core\Database;
use ReflectionClass;
use ReflectionMethod;
use DateTimeInterface;
use ReflectionParameter;
use Core\Attribute\Entity;

class AbstractRepository {
    protected $db;
    private Entity $entity;

    public function __construct() {
        $this->db = new Database();
        $entity = array_filter($this->db->entities, function ($entity) {
            return $entity->getRepository() === get_class($this);
        });
        $this->entity = $entity[array_key_first($entity)];
    }

    public function findAll(): array {
        $sql = "SELECT * FROM {$this->entity->getTable()}";
        $datas = $this->db->connection->query($sql);
        if (count($datas) > 0) {
            $class = $this->entity->getClass();
            $objects = [];
            foreach ($datas as $data) {
                $object = new $class();
                $objects[] = $this->hydrate($object, $data);
            }
            return $objects;
        } else {
            return [];
        }
    }

    public function find($id): ?object {
        $sql = "SELECT * FROM {$this->entity->getTable()} WHERE id = :id";
        $data = $this->db->connection->query($sql, ['id' => $id]);
        if (count($data) > 0) {
            $class = $this->entity->getClass();
            $object = new $class();
            return $this->hydrate($object, $data[0]);
        } else {
            return null;
        }
    }

    public function findBy(array $criteria): array {
        $sql = "SELECT * FROM {$this->entity->getTable()} WHERE ";
        foreach ($criteria as $key => $value) {
            $sql .= "{$key} = :{$key} AND ";
        }
        $sql = substr($sql, 0, -5);
        $data = $this->db->connection->query($sql, $criteria);
        if (count($data) > 0) {
            $class = $this->entity->getClass();
            $objects = [];
            foreach ($data as $data2) {
                $object = new $class();
                $objects[] = $this->hydrate($object, $data2);
            }
            return $objects;
        } else {
            return [];
        }
    }

    public function findOneBy(array $criteria): ?object {
        $data = $this->findBy($criteria);
        return count($data) > 0 ? $data[0] : null;
    }

    private function hydrate(object $object, array $data): object {
        foreach ($data as $key => $value) {
            $key = str_replace('_', '', lcfirst(ucwords($key, '_')));
            $key = substr($key, -2) === 'Id' ? substr($key, 0, -2) : $key;
            $method = 'set' . ucfirst($key);
            if (method_exists($object, $method)) {
                $reflection = new ReflectionMethod($object, $method);
                $params = $reflection->getParameters();
                /** @var ReflectionParameter $type */
                $type = $params[0]->getType();
                if (class_exists($type->getName())) {
                    $class = $type->getName();
                    $entity = array_filter($this->db->entities, function ($entity) use ($class) {
                        return $entity->getClass() === $class;
                    });
                    if (count($entity) > 0) {
                        $table = $entity[array_key_first($entity)]->getTable();
                        $sql = "SELECT * FROM {$table} WHERE id = :id";
                        $data2 = $this->db->connection->query($sql, ['id' => $value])[0];
                        $value = $this->hydrate(new $class(), $data2);
                    } else {
                        $value = new $class($value);
                    }
                }
                $object->$method($value);
            }
        }
        return $object;
    }

    public function delete(object $object): void {
        $class = get_class($object);
        $entity = array_filter($this->db->entities, function ($entity) use ($class) {
            return $entity->getClass() === $class;
        });
        $table = $entity[array_key_first($entity)]->getTable();
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $this->db->connection->query($sql, ['id' => $object->getId()]);
    }

    public function save(object $object): void {
        if ($object->getId() !== null) {
            $this->update($object);
        } else {
            $this->insert($object);
        }
    }

    private function insert(object $object): void {
        $class = get_class($object);
        $entity = array_filter($this->db->entities, function ($entity) use ($class) {
            return $entity->getClass() === $class;
        });
        $table = $entity[array_key_first($entity)]->getTable();
        $sql = "INSERT INTO {$table} (";

        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        $i = 1;
        $sqlPrepared = '';
        $valuesToSave = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $key = $property->getName();
            $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            $key = substr($key, -2) === 'Id' ? substr($key, 0, -2) : $key;
            if ($key !== 'id') {
                $value = $property->getValue($object);
                if (is_object($value)) {
                    $classValue = get_class($value);
                    $entity = array_filter($this->db->entities, function ($entity) use ($classValue) {
                        return $entity->getClass() === $classValue;
                    });
                    if (count($entity) > 0) {
                        $value = $value->getId();
                        $sql .= "{$key}_id";
                        $sqlPrepared .= ":{$key}_id";
                        $valuesToSave[$key . '_id'] = $value;
                    } elseif ($value instanceof DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                        $sql .= "{$key}";
                        $sqlPrepared .= ":{$key}";
                        $valuesToSave[$key] = $value;
                    }
                } else {
                    $sql .= "{$key}";
                    $sqlPrepared .= ":{$key}";
                    $valuesToSave[$key] = $value;
                }

                if ($i < count($properties)) {
                    $sql .= ', ';
                    $sqlPrepared .= ', ';
                }
            }
            $i++;
        }
        $sql .= ") VALUES ($sqlPrepared)";
        $this->db->connection->execute($sql, $valuesToSave);
    }

    private function update(object $object): void {
        $class = get_class($object);
        $entity = array_filter($this->db->entities, function ($entity) use ($class) {
            return $entity->getClass() === $class;
        });
        $table = $entity[array_key_first($entity)]->getTable();
        $sql = "UPDATE {$table} SET ";

        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();
        $i = 1;
        $valuesToSave = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $key = $property->getName();
            $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            $key = substr($key, -2) === 'Id' ? substr($key, 0, -2) : $key;
            if ($key !== 'id') {
                $value = $property->getValue($object);
                if (is_object($value)) {
                    $classValue = get_class($value);
                    $entity = array_filter($this->db->entities, function ($entity) use ($classValue) {
                        return $entity->getClass() === $classValue;
                    });
                    if (count($entity) > 0) {
                        $value = $value->getId();
                        $sql .= "{$key}_id = :{$key}_id";
                        $valuesToSave[$key . '_id'] = $value;
                    } elseif ($value instanceof DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                        $sql .= "{$key} = :{$key}";
                        $valuesToSave[$key] = $value;
                    }
                } else {
                    $sql .= "{$key} = :{$key}";
                    $valuesToSave[$key] = $value;
                }

                if ($i < count($properties)) {
                    $sql .= ', ';
                }
            }
            $i++;
        }
        $sql .= " WHERE id = :id";
        $valuesToSave['id'] = $object->getId();
        $this->db->connection->execute($sql, $valuesToSave);
    }
}
