<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Profile;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeGuardPostAuth();
        $this->composeSideBar();
    }

    private function composeSideBar(){
      view()->composer('partials.nav.sidebar', function ($view){
        $permissions = \App\Models\Permission::all();
        $view->with(compact('permissions'));
      });
    }

    private function composeGuardPostAuth(){
      view()->composer('dashboards.guardpost.auth', function ($view){
        $post = auth()->user()->get_guard_post();
        $enters = $post->enters;
        $view->with([
          'post' => $post,
          'enters' => $enters,
        ]);
      });
    }
}
