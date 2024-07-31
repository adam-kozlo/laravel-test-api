<?php

namespace Tests\Unit;

use App\Http\Requests\StoreDocument;
use App\Jobs\UploadDocument;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\Queue;
use Tests\TestCase;

class PatientControllerTest extends TestCase {

    public function test_it_returns_patients_for_authenticated_user() {
        // Arrange
        $headers = ['Authorization' => 'Basic ' . base64_encode('test_user1@example.com:lorem25#')];

        // Act
        $response = $this->withHeaders($headers)->getJson(route('getPatients'));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'patients');
    }

    public function test_it_does_not_return_patients_for_unauthenticated_user() {
        // Act
        $response = $this->getJson(route('getPatients'));

        // Assert
        $response->assertStatus(401);
    }


    public function test_it_creates_async_action_and_dispatches_upload_document_job_when_post_document_is_called() {
        // Arrange
        $patient = Patient::first();
        $storeDocument = new StoreDocument([
            'file_name' => 'test.pdf',
            'file_content' => UploadedFile::fake()->create('document.pdf'),
        ]);
        $storeDocument->setContainer($this->app);
        $storeDocument->setUserResolver(fn() => User::first());

        \Illuminate\Support\Facades\Queue::fake();

        // Act
        $headers = ['Authorization' => 'Basic ' . base64_encode('test_user1@example.com:lorem25#')];
        $response = $this->withHeaders($headers)->postJson(route('postDocument', ['patient' => $patient->id]), $storeDocument->validationData());

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure(['message', 'check_status_url']);
        $this->assertDatabaseHas('async_actions', [
            'user_id' => $storeDocument->user()->id,
            'type' => 'upload_document',
            'status' => 'in_progress',
        ]);
        \Illuminate\Support\Facades\Queue::assertPushed(UploadDocument::class);
    }


}
