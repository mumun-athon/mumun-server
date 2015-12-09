<?php

get('/', 'HomeController@index');

post('/auth/login', [
    'as' => 'auth.login',
    'uses' => 'LoginController@login',
]);

get('/auth/logout', [
    'as' => 'auth.logout',
    'uses' => 'LoginController@logout',
]);

get('raids/check', 'RaidsController@check');

resource('raids.locations', 'RaidLocationsController');

resource('raids', 'RaidsController');

resource('ilegal-reports', 'IlegalReportsController');

get('/ilegalreporter', function() { return view('welcome'); });
