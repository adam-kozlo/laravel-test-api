<?php

namespace Tests\Unit;

use Tests\TestCase;

class HealthControllerTest extends TestCase {
    public function test_it_returns_ok_when_health_check_is_successful() {
        // Arrange
        $headers = ['Authorization' => 'Basic ' . base64_encode('test_user1@example.com:lorem25#')];

        // Act
        $response = $this->withHeaders($headers)->getJson(route('getHealth'));

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'OK']);
    }

}
