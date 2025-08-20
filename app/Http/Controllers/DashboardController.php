<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LogScan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        $searchTerm = $request->query('search');

        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = LogScan::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('path', 'like', "%{$searchTerm}%");
            });
        }

        $allowedSortColumns = ['name', 'created_at', 'id'];
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $logScans = $query->paginate($perPage)->withQueryString();

        return view('dashboard', [
            'logScans' => $logScans,
            'searchTerm' => $searchTerm,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }
}
