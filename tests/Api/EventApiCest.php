<?php

namespace Tests\Api;

use Tests\Support\ApiTester;

class EventApiCest
{

    public function testFoulEvent(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'team_id' => 'arsenal',
            'match_id' => 14,
            'minute' => 45,
            'second' => 34,
            'event_data' => [
                'affected_player' => 'Bruno Fernandes',
                'player_at_fault' => 'William Saliba',
                'card' => 'yellow'
            ]
        ]);
        
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'message' => 'Event saved successfully'
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.event.type', 'foul');
        $I->seeResponseJsonMatchesJsonPath('$.event.event_data.card', 'yellow');
    }


    public function testGoalEvent(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'goal',
            'team_id' => 'Real Madrid',
            'match_id' => 13,
            'minute' => 12,
            'second' => 34,
            'event_data' => [
                'scorer' => 'Vini Junior',
                'assisting_player' => 'Rodrygo Goes',
            ]
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'message' => 'Event saved successfully'
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.event.type', 'goal');
        $I->seeResponseJsonMatchesJsonPath('$.event.event_data.scorer', 'Vini Junior');
    }

    public function testFoulEventWithoutRequiredFields(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'William Saliba',
            'minute' => 45,
            'second' => 34
            // Missing team_id and match_id
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => 'match_id and team_id are required for foul events'
        ]);
    }

    public function testInvalidJson(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', 'invalid json');

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => 'Invalid JSON'
        ]);
    }

    public function testEventWithoutType(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'player' => 'John Doe',
            'minute' => 23,
            'second' => 34
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => 'Event type is required'
        ]);
    }
}
