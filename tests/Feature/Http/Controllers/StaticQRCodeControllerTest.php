<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\StaticQRCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StaticQRCodeController
 */
final class StaticQRCodeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $staticQRCodes = StaticQRCode::factory()->count(3)->create();

        $response = $this->get(route('static-q-r-codes.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StaticQRCodeController::class,
            'store',
            \App\Http\Requests\StaticQRCodeStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $filename = fake()->word();
        $content_type = fake()->randomElement(/** enum_attributes **/);
        $content = fake()->paragraphs(3, true);
        $foreground_color = fake()->word();
        $background_color = fake()->word();
        $precision = fake()->randomElement(/** enum_attributes **/);
        $size = fake()->numberBetween(-10000, 10000);
        $format = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('static-q-r-codes.store'), [
            'user_id' => $user->id,
            'filename' => $filename,
            'content_type' => $content_type,
            'content' => $content,
            'foreground_color' => $foreground_color,
            'background_color' => $background_color,
            'precision' => $precision,
            'size' => $size,
            'format' => $format,
        ]);

        $staticQRCodes = StaticQRCode::query()
            ->where('user_id', $user->id)
            ->where('filename', $filename)
            ->where('content_type', $content_type)
            ->where('content', $content)
            ->where('foreground_color', $foreground_color)
            ->where('background_color', $background_color)
            ->where('precision', $precision)
            ->where('size', $size)
            ->where('format', $format)
            ->get();
        $this->assertCount(1, $staticQRCodes);
        $staticQRCode = $staticQRCodes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $staticQRCode = StaticQRCode::factory()->create();

        $response = $this->get(route('static-q-r-codes.show', $staticQRCode));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StaticQRCodeController::class,
            'update',
            \App\Http\Requests\StaticQRCodeUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $staticQRCode = StaticQRCode::factory()->create();
        $user = User::factory()->create();
        $filename = fake()->word();
        $content_type = fake()->randomElement(/** enum_attributes **/);
        $content = fake()->paragraphs(3, true);
        $foreground_color = fake()->word();
        $background_color = fake()->word();
        $precision = fake()->randomElement(/** enum_attributes **/);
        $size = fake()->numberBetween(-10000, 10000);
        $format = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('static-q-r-codes.update', $staticQRCode), [
            'user_id' => $user->id,
            'filename' => $filename,
            'content_type' => $content_type,
            'content' => $content,
            'foreground_color' => $foreground_color,
            'background_color' => $background_color,
            'precision' => $precision,
            'size' => $size,
            'format' => $format,
        ]);

        $staticQRCode->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $staticQRCode->user_id);
        $this->assertEquals($filename, $staticQRCode->filename);
        $this->assertEquals($content_type, $staticQRCode->content_type);
        $this->assertEquals($content, $staticQRCode->content);
        $this->assertEquals($foreground_color, $staticQRCode->foreground_color);
        $this->assertEquals($background_color, $staticQRCode->background_color);
        $this->assertEquals($precision, $staticQRCode->precision);
        $this->assertEquals($size, $staticQRCode->size);
        $this->assertEquals($format, $staticQRCode->format);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $staticQRCode = StaticQRCode::factory()->create();

        $response = $this->delete(route('static-q-r-codes.destroy', $staticQRCode));

        $response->assertNoContent();

        $this->assertSoftDeleted($staticQRCode);
    }
}
