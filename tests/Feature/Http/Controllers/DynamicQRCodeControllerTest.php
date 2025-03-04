<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DynamicQRCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DynamicQRCodeController
 */
final class DynamicQRCodeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $dynamicQRCodes = DynamicQRCode::factory()->count(3)->create();

        $response = $this->get(route('dynamic-q-r-codes.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DynamicQRCodeController::class,
            'store',
            \App\Http\Requests\DynamicQRCodeStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $filename = fake()->word();
        $redirect_identifier = fake()->word();
        $url = fake()->url();
        $foreground_color = fake()->word();
        $background_color = fake()->word();
        $precision = fake()->randomElement(/** enum_attributes **/);
        $size = fake()->numberBetween(-10000, 10000);
        $scan_count = fake()->numberBetween(-10000, 10000);
        $status = fake()->boolean();

        $response = $this->post(route('dynamic-q-r-codes.store'), [
            'user_id' => $user->id,
            'filename' => $filename,
            'redirect_identifier' => $redirect_identifier,
            'url' => $url,
            'foreground_color' => $foreground_color,
            'background_color' => $background_color,
            'precision' => $precision,
            'size' => $size,
            'scan_count' => $scan_count,
            'status' => $status,
        ]);

        $dynamicQRCodes = DynamicQRCode::query()
            ->where('user_id', $user->id)
            ->where('filename', $filename)
            ->where('redirect_identifier', $redirect_identifier)
            ->where('url', $url)
            ->where('foreground_color', $foreground_color)
            ->where('background_color', $background_color)
            ->where('precision', $precision)
            ->where('size', $size)
            ->where('scan_count', $scan_count)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $dynamicQRCodes);
        $dynamicQRCode = $dynamicQRCodes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $dynamicQRCode = DynamicQRCode::factory()->create();

        $response = $this->get(route('dynamic-q-r-codes.show', $dynamicQRCode));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DynamicQRCodeController::class,
            'update',
            \App\Http\Requests\DynamicQRCodeUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $dynamicQRCode = DynamicQRCode::factory()->create();
        $user = User::factory()->create();
        $filename = fake()->word();
        $redirect_identifier = fake()->word();
        $url = fake()->url();
        $foreground_color = fake()->word();
        $background_color = fake()->word();
        $precision = fake()->randomElement(/** enum_attributes **/);
        $size = fake()->numberBetween(-10000, 10000);
        $scan_count = fake()->numberBetween(-10000, 10000);
        $status = fake()->boolean();

        $response = $this->put(route('dynamic-q-r-codes.update', $dynamicQRCode), [
            'user_id' => $user->id,
            'filename' => $filename,
            'redirect_identifier' => $redirect_identifier,
            'url' => $url,
            'foreground_color' => $foreground_color,
            'background_color' => $background_color,
            'precision' => $precision,
            'size' => $size,
            'scan_count' => $scan_count,
            'status' => $status,
        ]);

        $dynamicQRCode->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $dynamicQRCode->user_id);
        $this->assertEquals($filename, $dynamicQRCode->filename);
        $this->assertEquals($redirect_identifier, $dynamicQRCode->redirect_identifier);
        $this->assertEquals($url, $dynamicQRCode->url);
        $this->assertEquals($foreground_color, $dynamicQRCode->foreground_color);
        $this->assertEquals($background_color, $dynamicQRCode->background_color);
        $this->assertEquals($precision, $dynamicQRCode->precision);
        $this->assertEquals($size, $dynamicQRCode->size);
        $this->assertEquals($scan_count, $dynamicQRCode->scan_count);
        $this->assertEquals($status, $dynamicQRCode->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $dynamicQRCode = DynamicQRCode::factory()->create();

        $response = $this->delete(route('dynamic-q-r-codes.destroy', $dynamicQRCode));

        $response->assertNoContent();

        $this->assertSoftDeleted($dynamicQRCode);
    }
}
