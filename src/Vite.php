<?php

namespace TTBooking\ViteManager;

use Illuminate\Foundation\Vite as BaseVite;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;
use TTBooking\ViteManager\Contracts\Vite as ViteContract;

class Vite extends BaseVite implements ViteContract
{
    /**
     * Apply configuration to the Vite instance.
     *
     * @param  array  $config
     * @return $this
     */
    public function configure($config)
    {
        $class = new ReflectionClass($this);
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        $currentMethod = __FUNCTION__;

        $methodKeyMap = [
            'useCspNonce' => 'nonce',
            'useScriptTagAttributes' => 'tag_attributes.script',
            'useStyleTagAttributes' => 'tag_attributes.style',
            'usePreloadTagAttributes' => 'tag_attributes.preload',
        ];

        collect($methods)->filter(fn (ReflectionMethod $method) => ! $method->isStatic()
            && $method->getName() !== $currentMethod
            && preg_match('/^(use|with)/', $method->getName())
        )->each(function (ReflectionMethod $method) use ($config, $methodKeyMap) {
            $key = $methodKeyMap[$method->getName()]
                ?? str($method->getName())->after('use')->after('with')->snake()->value();
            Arr::has($config, $key) && $method->invoke($this, Arr::get($config, $key));
        });

        return $this;
    }
}
