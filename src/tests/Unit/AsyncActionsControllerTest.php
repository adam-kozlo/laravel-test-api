<?php

namespace Tests\Unit;

use App\Models\AsyncAction;
use Tests\TestCase;

class AsyncActionsControllerTest extends TestCase {

    private function setAuthHeaders(): array {
        return ['Authorization' => 'Basic ' . base64_encode('test_user1@example.com:lorem25#')];
    }
    public function test_it_returns_async_action_details_when_get_check_status_is_called() {
        // Arrange
        $asyncAction = AsyncAction::create([
            'type' => 'upload_document',
            'status' => 'in_progress',
        ]);

        // Act
        $response = $this->withHeaders($this->setAuthHeaders())->getJson(route('async-action-status', ['asyncAction' => $asyncAction->id]));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'type' => $asyncAction->type,
            'status' => $asyncAction->status,
            'created_at' => $asyncAction->created_at->toJSON(),
            'updated_at' => $asyncAction->updated_at->toJSON(),
        ]);
    }

    public function test_it_returns_404_when_async_action_does_not_exist_in_get_check_status() {

        // Act
        $response = $this->withHeaders($this->setAuthHeaders())->getJson(route('async-action-status', ['asyncAction' => 999]));

        // Assert
        $response->assertStatus(404);
    }

}
