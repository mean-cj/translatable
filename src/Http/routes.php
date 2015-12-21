<?php

/**
 * Translatable Routes
 */

Route::get('languages', ['as' => 'languages', 'uses' => 'TranslatableController@index']); // done
Route::post('languages', 'TranslatableController@update');
Route::get('languages/load', ['as' => 'languages/load', 'uses' => 'TranslatableController@load']); // done

Route::get('languages/{lang}/{file?}', ['as' => 'languages/view', 'uses' => 'TranslatableController@show']);
Route::post('languages/{lang}', 'TranslatableController@update');
Route::post('languages/{lang}/{file}', 'TranslatableController@store');

Route::get('languages/{lang}/destroy', ['as' => 'languages/destroy', 'uses' => 'TranslatableController@destroy']);
Route::get('langauges/{lang}/{status}', ['as' => 'languages/status', 'uses' => 'TranslatableController@activeStatus']); // done

