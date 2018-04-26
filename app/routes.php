<?php
// Home page
$app->get('/', function () use ($app) {
    $trainers = $app['dao.trainer']->findAll();
    return $app['twig']->render('index.html.twig', array('trainers' => $trainers));
})->bind('home');

$app->get('/trainers', function () use ($app) {
    $trainers = $app['dao.trainer']->findAll();
    return $app['twig']->render('trainers.html.twig', array('trainers' => $trainers));
})->bind('trainers');

// trainer details with pokemons
$app->get('/trainer/{id}', function ($id) use ($app) {
    $trainer = $app['dao.trainer']->find($id);
    $pokemons = $app['dao.pokemon']->findAllByTrainer($id);
    return $app['twig']->render('trainer.html.twig', array('trainer' => $trainer, 'pokemons' => $pokemons));
})->bind('trainer');

$app->get('/pokemons', function () use ($app) {
    $pokemons = $app['dao.pokemon']->findAll();
    return $app['twig']->render('pokemons.html.twig', array('pokemons' => $pokemons));
})->bind('pokemons');

$app->get('/pokemon/{id}', function ($id) use ($app) {
    $pokemon = $app['dao.pokemon']->find($id);
    //$trainer = $app['dao.trainer']->findTrainer($id);
    return $app['twig']->render('pokemon.html.twig', array('pokemon' => $pokemon));
})->bind('pokemon');
$app->get('/uppokemon/{id}', function ($id) use ($app) {
    $uppokemon = $app['dao.pokemon']->upgrade($id);
    //$trainer = $app['dao.trainer']->findTrainer($id);
    return $uppokemon;
})->bind('uppokemon');
