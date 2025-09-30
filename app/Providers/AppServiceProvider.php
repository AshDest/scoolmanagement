<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies\GradePolicy;
class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Grade::class => GradePolicy::class,
    ];

    public function boot(): void { /* */ }
}
