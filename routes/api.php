<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Meilisearch\Client;
use Meilisearch\Contracts\TasksQuery;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

Route::get('/', function (Request $request) {
    $client = app(Client::class);

    $query = (new TasksQuery())
        ->setIndexUids([$request->input('index', '*')])
        ->setTypes([$request->input('type', '*')])
        ->setStatuses([$request->input('status', '*')])
        ->setLimit(20);

    return $client->getTasks($query)->toArray();
});
