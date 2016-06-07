<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['backend']], function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@getIndex']);
});

Route::group(['as' => 'api.dashboard.widget.', 'middleware' => ['backend']], function () {
    RouteAPI::get('dashboard.widget.available', ['as' => 'available', 'uses' => 'API\DashboardController@getAvailableWidgets']);
    RouteAPI::get('dashboard.widget.list', ['as' => 'list', 'uses' => 'API\DashboardController@getWidgetList']);
    RouteAPI::get('dashboard.widget', ['as' => 'settings', 'uses' => 'API\DashboardController@getWidgetSettings']);
    RouteAPI::put('dashboard.widget', ['as' => 'put', 'uses' => 'API\DashboardController@putWidget']);
    RouteAPI::post('dashboard.widget', ['as' => 'post', 'uses' => 'API\DashboardController@postWidget']);
    RouteAPI::delete('dashboard.widget', ['as' => 'delete', 'uses' => 'API\DashboardController@deleteWidget']);
});
