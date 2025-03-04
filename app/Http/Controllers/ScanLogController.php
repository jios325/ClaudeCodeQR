<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanLogStoreRequest;
use App\Http\Requests\ScanLogUpdateRequest;
use App\Http\Resources\ScanLogCollection;
use App\Http\Resources\ScanLogResource;
use App\Models\ScanLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScanLogController extends Controller
{
    public function index(Request $request): ScanLogCollection
    {
        $scanLogs = ScanLog::all();

        return new ScanLogCollection($scanLogs);
    }

    public function store(ScanLogStoreRequest $request): ScanLogResource
    {
        $scanLog = ScanLog::create($request->validated());

        return new ScanLogResource($scanLog);
    }

    public function show(Request $request, ScanLog $scanLog): ScanLogResource
    {
        return new ScanLogResource($scanLog);
    }

    public function update(ScanLogUpdateRequest $request, ScanLog $scanLog): ScanLogResource
    {
        $scanLog->update($request->validated());

        return new ScanLogResource($scanLog);
    }

    public function destroy(Request $request, ScanLog $scanLog): Response
    {
        $scanLog->delete();

        return response()->noContent();
    }
}
