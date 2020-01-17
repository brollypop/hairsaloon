<?php declare(strict_types=1);

namespace App\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StationScheduleTest extends WebTestCase
{
    private const STATION_1 = 1;
    private const STATION_2 = 2;
    private const DATE = '2020-01-10';

    public function test_viewing_station_schedule(): void
    {
        $client = static::createClient();

        $client->request('GET', sprintf('/api/stations/%d/schedule/%s', self::STATION_1, self::DATE));
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(
            48,
            json_decode($response->getContent(), true)['schedule']
        );
    }

    public function test_reserving_time_slots_for_station_schedule(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            sprintf('/api/stations/%d/schedule/%s', self::STATION_1, self::DATE),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['time-slots' => [5, 6, 7]])
        );
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $client->request('GET', sprintf('/api/stations/%d/schedule/%s', self::STATION_1, self::DATE));
        $response = $client->getResponse();
        $schedule = json_decode($response->getContent(), true)['schedule'];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($schedule[4]['available']);
        $this->assertFalse($schedule[5]['available']);
        $this->assertFalse($schedule[6]['available']);
        $this->assertFalse($schedule[7]['available']);
        $this->assertTrue($schedule[8]['available']);
    }

    public function test_reserving_unavailable_time_slot_results_in_error()
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            sprintf('/api/stations/%d/schedule/%s', self::STATION_2, self::DATE),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['time-slots' => [5, 6, 7]])
        );
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $client->request(
            'PUT',
            sprintf('/api/stations/%d/schedule/%s', self::STATION_2, self::DATE),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['time-slots' => [3, 4, 5]])
        );
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());

        $client->request('GET', sprintf('/api/stations/%d/schedule/%s', self::STATION_2, self::DATE));
        $response = $client->getResponse();
        $schedule = json_decode($response->getContent(), true)['schedule'];

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($schedule[3]['available']);
        $this->assertTrue($schedule[4]['available']);
        $this->assertFalse($schedule[5]['available']);
    }
}
