<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);

            foreach ($data as $row) {
                $row = array_combine($header, $row);
                User::create($row);
            }

            return response()->json(['message' => 'Data uploaded successfully'], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', "%{$request->first_name}%");
        }
        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', "%{$request->last_name}%");
        }
        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('mobile_number')) {
            $query->where('mobile_number', $request->mobile_number);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        if ($request->filled('login')) {
            $query->where('login', 'like', "%{$request->login}%");
        }
        if ($request->filled('car_model')) {
            $query->where('car_model', 'like', "%{$request->car_model}%");
        }
        if ($request->filled('salary')) {
            $query->where('salary', $request->salary);
        }

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->sort_order ?? 'asc');
        }

        $users = $query->paginate($request->get('limit', 15));

        return response()->json($users, 200);
    }
}

