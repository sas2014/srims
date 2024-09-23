<?php

namespace App\Tests\Entity;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Token;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{

    protected $excludedMethods = ['__get', '__set'];

    protected $typeValue = [];

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->typeValue = [
            'int' => 1,
            'float' => 1.1,
            'string' => 'string',
            'array' => [1],
            'bool' => true,
            'DateTime' => new \DateTime(),
            'DateTimeInterface' => new \DateTime(),
            'App\Entity\User' => (new User()),
            'App\Entity\Token' => (new Token()),
            'App\Entity\Recipe' => (new Recipe()),
            'App\Entity\Ingredient' => (new Ingredient()),
        ];
    }

    /**
     * @throws \ReflectionException
     * @testdox Entities test just for fun
     */
    public function testEntity(): void
    {
        $dirs = $this->getEntities();

        $errors = [];
        foreach($dirs as $dir) {
            foreach (glob($dir) as $file)
            {
                $file = realpath($file);
                //echo PHP_EOL . $file . PHP_EOL;
                $class = explode('Entity', $file);
                $class = trim(str_replace('.php', '', array_pop($class)), DIRECTORY_SEPARATOR);

                $class = str_replace('/', '\\', $class);

                $class = 'App\Entity\\' . $class;
                if (class_exists($class))
                {
                    $reflection = new \ReflectionClass($class);
                    $methods = $reflection->getMethods();
                    if(!empty($methods)) {
                        $obj = new $class;
                        /** @var $method \ReflectionMethod */
                        foreach($methods as $method) { // run setters first
                            if(!in_array($method->getName(), $this->excludedMethods)) {
                                $parameters = $method->getParameters();
                                $parameterValue = null;
                                if(!empty($parameters)) {
                                    //echo $method->getName() . PHP_EOL;
                                    /** @var $method \ReflectionParameter */
                                    $parameter = current($parameters);
                                    $type = $parameter->getType();
                                    if($type == null) {
                                        $method->invoke($obj, $this->typeValue['string']);
                                    } else {
                                        if(empty($this->typeValue[$type->getName()])){
                                            $errors[] = 'missed type: ' . $type->getName();
                                        } else {
                                            $parameterValue = $this->typeValue[$type->getName()];
                                            $method->invoke($obj, $parameterValue);
                                        }
                                    }
                                }
                            } else {
                                //echo 'skip ' . $method->getName() . PHP_EOL;
                                continue;
                            }
                        }
                        foreach($methods as $method) { // run getters next
                            if(!in_array($method->getName(), $this->excludedMethods)) {
                                $parameters = $method->getParameters();
                                if(empty($parameters)) {
                                    //echo $method->getName() . PHP_EOL;
                                    /** @var $method \ReflectionParameter */
                                    try {
                                        $method->invoke($obj);
                                    } catch (\TypeError $e) {
                                        $errors[] = $e->getMessage();
                                        //echo $e->getMessage() . PHP_EOL;
                                        //var_export($obj); die;
                                    }
                                }
                            } else {
                                //echo 'skip ' . $method->getName() . PHP_EOL;
                                continue;
                            }
                        }
                    }
                } else {
                    $errors[] = $class;
                }
            }
        }
        self::assertEquals([], $errors);
    }

    private function getEntities()
    {
        $ds = DIRECTORY_SEPARATOR;
        $path = __DIR__ . $ds . '..' . $ds . '..' . $ds . 'src' . $ds . 'Entity' . $ds;
        return [
            $path . '*.php',
        ];
    }
}
