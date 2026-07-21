<?php

namespace App\Experiments\Singleton;

class Container
{
    // where is the main problem for myself ???
    /**
     * @var array amar kache mone hocche - gap, not practice, loos kortechi topic-logic,
     * @var array connect korte parchi na.
     * @var string solution ? each day porte hobe - seta olpo hok - no problem but regularity rakte hobe
     * @var string laravel daily rakchi but hoi na. 30 min - study and 30 min practice
     * @var number tahole connect korar akta matro sujog ja hocche - regular and revision
     * kichu korar age obossoi jante hobe, ki korchi and keno korchi!!!
     */

    private array $bindings = [];
//
//array:1 [▼ // app/Experiments/Singleton/Container.php:23
//"mail" => array:2 [▼
//"class" => "App\Experiments\Singleton\MailService"
//"shared" => false
//]
//]

    private array $instances = [];

//array:1 [▼ // app/Experiments/Singleton/Container.php:23
//"mail" => array:2 [▼
//"class" => "App\Experiments\Singleton\MailService"
//"shared" => true
//]
//]

    public function bind($name, $class)
    {
        $this->bindings[$name] = [
            'class' => $class,
            'shared' => false
        ];
    }

    public function singleton($name, $class)
    {
        $this->bindings[$name] = [
            'class' => $class,
            'shared' => true
        ];
    }

    public function make($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        $binding = $this->bindings[$name];
        //here get - ['class' =>  , shared => ]

        //then pic class name
        $class = $binding['class'];

        //make object
        $object = new $class();

        if ($binding['shared']) {
            $this->instances[$name] = $object;
        }

        return $object;
    }
}
