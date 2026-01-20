<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\adapters\InMemoryEventRep;
use App\adapters\InMemoryPublisherAdapter;
use App\adapters\InMemoryStatisticsRep;
use App\adapters\SimpleMessageBus;
use App\adapters\TransactionalSrv;
use App\application\handlers\FoulEventHandler;
use App\application\handlers\GoalEventHandler;
use App\application\MatchEventHandler;
use App\domain\MatchEvent;

header('Content-Type: application/json');

// Simple routing
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'POST' && $path === '/event') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    // todo bootstrap real implementation

    // temp storages
    $store = new InMemoryEventRep();
    $statisticsRep = new InMemoryStatisticsRep();

    // temp notification publisher
    $publisher = new InMemoryPublisherAdapter();

    $transactionalSrv = new TransactionalSrv();

    // handlers
    $foulHandler = new FoulEventHandler($store, $statisticsRep);
    $goalHandler = new GoalEventHandler($store, $statisticsRep);
    $consumer = new MatchEventHandler([
        $foulHandler, $goalHandler
    ], $publisher, $transactionalSrv);

    $bus = new SimpleMessageBus([$consumer]);

    try {

        $event = MatchEvent::fromArray($data, time());
        $bus->dispatch($event);

        http_response_code(201);

        echo json_encode([
            'status' => 'success',
            'message' => 'Event saved successfully',
            'event' => $event->toArray(),
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'GET' && $path === '/statistics') {

    // todo bootstrap real implementation
    $statisticsRep = new InMemoryStatisticsRep();

    $matchId = $_GET['match_id'] ?? null;
    $teamId = $_GET['team_id'] ?? null;
    
    try {
        if ($matchId) {
            $criteria = [
                'match_id' => $matchId,
            ];

            // Get team statistics for specific match
            if ($teamId) {
                $criteria['team_id'] = $teamId;
            }

            $stats = $statisticsRep->findByCriteria($criteria);

            echo json_encode([
                'match_id' => $matchId,
                'statistics' => $stats
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'match_id is required']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}