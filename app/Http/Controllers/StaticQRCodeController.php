<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaticQRCodeStoreRequest;
use App\Http\Requests\StaticQRCodeUpdateRequest;
use App\Http\Resources\StaticQRCodeCollection;
use App\Http\Resources\StaticQRCodeResource;
use App\Models\StaticQRCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StaticQRCodeController extends Controller
{
    public function index(Request $request): StaticQRCodeCollection
    {
        $staticQRCodes = StaticQRCode::all();

        return new StaticQRCodeCollection($staticQRCodes);
    }

    public function store(StaticQRCodeStoreRequest $request): StaticQRCodeResource
    {
        $staticQRCode = StaticQRCode::create($request->validated());

        return new StaticQRCodeResource($staticQRCode);
    }

    public function show(Request $request, StaticQRCode $staticQRCode): StaticQRCodeResource
    {
        return new StaticQRCodeResource($staticQRCode);
    }

    public function update(StaticQRCodeUpdateRequest $request, StaticQRCode $staticQRCode): StaticQRCodeResource
    {
        $staticQRCode->update($request->validated());

        return new StaticQRCodeResource($staticQRCode);
    }

    public function destroy(Request $request, StaticQRCode $staticQRCode): Response
    {
        $staticQRCode->delete();

        return response()->noContent();
    }
}
