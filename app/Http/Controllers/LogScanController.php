<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LogScanRequest;
use App\Models\LogScan;
use Illuminate\Support\Str;

class LogScanController extends Controller
{
    public function store(LogScanRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $cleanName = Str::slug($originalName);

            $extension = $file->getClientOriginalExtension();

            $fileName = time() . '_' . $cleanName . '.' . $extension;

            $filePath = $file->storeAs('uploads', $fileName, 'public');

            $data['path'] = $filePath;
        }

        LogScan::create($data);

        return redirect()->back()->with('success', 'Dados e arquivo registrados com sucesso!');
    }

    public function show(?LogScan $log = null)
    {
        if($log) {
            $log->load('details');
        }

        return view('logs.show', compact('log'));
    }
}
