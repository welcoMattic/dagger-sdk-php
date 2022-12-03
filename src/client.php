<?php

namespace Welcomattic\DaggerPHP;

include 'vendor/autoload.php';

use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\Query;

try {
    $sessionUrl = getenv('DAGGER_SESSION_URL') or throw new \Exception("DAGGER_SESSION_URL doesn't exist");
    $sessionToken = getenv('DAGGER_SESSION_TOKEN') or throw new \Exception("DAGGER_SESSION_TOKEN doesn't exist");

    $client = new Client(
        $sessionUrl,
        ['Authorization' => 'Basic ' . base64_encode($sessionToken . ':')]
    );

    $dirQuery = (new Query('host'))
        ->setSelectionSet([
            (new Query('directory'))
                ->setOperationName('read')
                ->setArguments(['path' => '.'])
                ->setSelectionSet(['id'])
        ]);
    $dirId = $client->runQuery($dirQuery)->getData()->host->directory->id;

    $queryContainer = (new Query('container'))
        ->setSelectionSet([
            (new Query('build'))
                ->setArguments(['context' => $dirId, 'dockerfile' => 'php-base/Dockerfile'])
                ->setSelectionSet(['id'])
        ]);
    $containerId = $client->runQuery($queryContainer)->getData()->container->build->id;

    $query =  (new Query('container'))
        ->setArguments(['id' => $containerId])
        ->setSelectionSet([
            (new Query('withExec'))
                ->setArguments(['args' => ['php', '-v']])
                ->setSelectionSet(['stdout']),
        ]);

    $results = $client->runQuery($query, true);
    var_dump($results->getData());
} catch (QueryError $e) {
    var_dump($e->getErrorDetails());
    exit;
}
