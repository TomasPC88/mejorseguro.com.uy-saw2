<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MixinServiceProvider extends ServiceProvider
{
    /** @psalm-var array<class-string, class-string> */
    protected $mixins = [
        //Mixins
        \Illuminate\Support\Collection::class => \App\Mixins\CollectionMixin::class
    ];

    /** @psalm-var array<class-string, class-string> */
    protected $testingMixins = [
        //Testing Mixins
    ];

    public function register()
    {
        foreach ($this->mixins as $class => $mixin) {
            $class::mixin(new $mixin);
        }

        if ($this->app->environment('testing')) {
            foreach ($this->testingMixins as $class => $mixin) {
                $class::mixin(new $mixin);
            }
        }
    }
}