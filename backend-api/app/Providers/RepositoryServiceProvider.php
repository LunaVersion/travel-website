<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        //
    }
}
