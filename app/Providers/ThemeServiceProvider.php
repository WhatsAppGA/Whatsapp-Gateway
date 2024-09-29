<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class ThemeServiceProvider extends ServiceProvider
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
		$theme = env('THEME_NAME') ?? 'mpwa';

		$componentsPath = resource_path("themes/{$theme}/views/components");

		foreach (glob($componentsPath . '/*.blade.php') as $component) {
			$componentName = basename($component, '.blade.php');
			Blade::component('theme::components.' . $componentName, $componentName);
		}

		View::addNamespace('theme', resource_path('themes/' . $theme . '/views'));

	}
}
