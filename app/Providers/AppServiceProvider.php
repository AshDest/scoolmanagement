<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Policies\GradePolicy;
class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Grade::class => GradePolicy::class,
    ];

    public function boot(): void {
//        redirect all to https
        if (config('app.env') === 'production'){
            URL::forceScheme('https');
        }
    }
}
