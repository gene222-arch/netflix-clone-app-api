<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->actingAs(User::find(1), 'api');
        $this->withoutExceptionHandling();
    }

    protected function apiHeader()
    {
        return [
            'Accept' => 'application/json'
        ];
    }

    /**
     * 
     * @param [type] $response
     * @param integer @code
     * @return void
     */
    protected function assertResponse($response, int $code = 200)
    {
        $response
            ->assertStatus($code)
            ->assertJsonStructure([
                'status',
                'message',
                'data',
                'status_message'
            ]);
    }

}
