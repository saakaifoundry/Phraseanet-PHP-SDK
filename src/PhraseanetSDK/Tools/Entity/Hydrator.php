<?php

namespace PhraseanetSDK\Tools\Entity;

use PhraseanetSDK\Tools\Entity\Manager;
use PhraseanetSDK\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Hydrator
{

    /**
     * Transform a string to CamelStyle pr pascalCase
     *
     * @param  string $string the string to transform
     * @return string
     */
    protected static function camelize($string)
    {
        $string = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $string)));

        return lcfirst($string);
    }

    /**
     * Hydrate an entity object from  a source
     *
     * @param  type                          $entity is the entity we want to populate
     * @param  \stdClass                     $object is the source of datas
     * @return \PhraseanetSDK\Tools\Entity\*
     */
    public static function hydrate(EntityInterface $entity, \stdClass $object, Manager $manager)
    {
        $className = get_class($entity);
        $reflectionClass = new \ReflectionClass($className);

        foreach (get_object_vars($object) as $propertyName => $propertyValue) {
            $methodName = self::camelize(sprintf('set%s', ucfirst($propertyName)));
            if ($reflectionClass->hasMethod($methodName)) {
                $reflectionMethod = new \ReflectionMethod($className, $methodName);

                if ($reflectionMethod->getNumberOfParameters() > 0) {
                    $parameters = $reflectionMethod->getParameters();

                    if (is_scalar($propertyValue)) {
                        foreach ($parameters as $parameter) {
                            /* @var $parameter \ReflectionParameter */
                            if (null === $parameter->getClass()) {
                                $entity->$methodName($propertyValue);
                            } elseif ('DateTime' === $parameter->getClass()->getName()) {
                                $date = \DateTime::createFromFormat(
                                        \DateTime::ATOM
                                        , $propertyValue
                                        , new \DateTimeZone(date_default_timezone_get())
                                );

                                $entity->$methodName($date);
                            }
                        }
                    } elseif (is_array($propertyValue)) {
                        $entityCollection = new ArrayCollection();

                        foreach ($propertyValue as $object) {
                            if (is_object($object)) {
                                $subEntity = self::hydrate(
                                        $manager->getEntity($propertyName)
                                        , $object
                                        , $manager
                                );

                                $entityCollection->add($subEntity);
                            } elseif (is_scalar($object)) {
                                $entityCollection->add($object);
                            }
                        }

                        foreach ($parameters as $parameter) {
                            /* @var $parameter \ReflectionParameter */
                            if ($parameter->getClass() && $parameter->getClass()->isInstance($entityCollection)) {
                                $entity->$methodName($entityCollection);
                            }
                        }
                    } elseif (is_object($propertyValue)) {
                        if ( ! ctype_digit($propertyName)) {
                            $subEntity = self::hydrate(
                                    $manager->getEntity($propertyName)
                                    , $propertyValue
                                    , $manager
                            );

                            foreach ($parameters as $parameter) {
                                /* @var $parameter \ReflectionParameter */
                                if ($parameter->getClass() && $parameter->getClass()->isInstance($subEntity)) {
                                    $entity->$methodName($subEntity);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $entity;
    }
}
