<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\QrCode;
use App\Models\ScanLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ScanLogController
 */
final class ScanLogControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $scanLogs = ScanLog::factory()->count(3)->create();

        $response = $this->get(route('scan-logs.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ScanLogController::class,
            'store',
            \App\Http\Requests\ScanLogStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $qr_code = QrCode::factory()->create();
        $qr_code_type = fake()->randomElement(/** enum_attributes **/);
        $timestamp = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('scan-logs.store'), [
            'qr_code_id' => $qr_code->id,
            'qr_code_type' => $qr_code_type,
            'timestamp' => $timestamp->toDateTimeString(),
        ]);

        $scanLogs = ScanLog::query()
            ->where('qr_code_id', $qr_code->id)
            ->where('qr_code_type', $qr_code_type)
            ->where('timestamp', $timestamp)
            ->get();
        $this->assertCount(1, $scanLogs);
        $scanLog = $scanLogs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $scanLog = ScanLog::factory()->create();

        $response = $this->get(route('scan-logs.show', $scanLog));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ScanLogController::class,
            'update',
            \App\Http\Requests\ScanLogUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $scanLog = ScanLog::factory()->create();
        $qr_code = QrCode::factory()->create();
        $qr_code_type = fake()->randomElement(/** enum_attributes **/);
        $timestamp = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('scan-logs.update', $scanLog), [
            'qr_code_id' => $qr_code->id,
            'qr_code_type' => $qr_code_type,
            'timestamp' => $timestamp->toDateTimeString(),
        ]);

        $scanLog->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($qr_code->id, $scanLog->qr_code_id);
        $this->assertEquals($qr_code_type, $scanLog->qr_code_type);
        $this->assertEquals($timestamp->timestamp, $scanLog->timestamp);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $scanLog = ScanLog::factory()->create();

        $response = $this->delete(route('scan-logs.destroy', $scanLog));

        $response->assertNoContent();

        $this->assertModelMissing($scanLog);
    }
}
