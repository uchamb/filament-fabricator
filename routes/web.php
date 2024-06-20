<?php

use Illuminate\Support\Facades\Route;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Http\Controllers\PageController;
use Illuminate\Routing\Router;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

if (config('filament-fabricator.routing.enabled')) {
  Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
  ], function (Router $router) {
    Route::middleware(config('filament-fabricator.middleware') ?? [])
      ->prefix(FilamentFabricator::getRoutingPrefix())
      ->group(function () {
        Route::get('/{filamentFabricatorPage?}', PageController::class)
          ->where('filamentFabricatorPage', '.*')
          ->fallback();
      });
  });
}
