<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDatabase;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class UserDatabaseController extends Controller
{
    public function index()
    {
        $databases = UserDatabase::with('subdomain.user')->latest()->paginate(15);
        return view('admin.databases.index', compact('databases'));
    }

    public function create()
    {
        $subdomains = Subdomain::with('user')->orderBy('full_domain')->get();
        return view('admin.databases.create', compact('subdomains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'db_name' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_password' => 'required|string|max:255',
        ]);

        UserDatabase::create($validated);

        return redirect()->route('admin.databases.index')->with('success', 'Database credential created successfully.');
    }

    public function edit(UserDatabase $database)
    {
        $subdomains = Subdomain::with('user')->orderBy('full_domain')->get();
        return view('admin.databases.edit', compact('database', 'subdomains'));
    }

    public function update(Request $request, UserDatabase $database)
    {
        $validated = $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'db_name' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_password' => 'required|string|max:255',
        ]);

        $database->update($validated);

        return redirect()->route('admin.databases.index')->with('success', 'Database credential updated successfully.');
    }

    public function destroy(UserDatabase $database)
    {
        $database->delete();
        return redirect()->route('admin.databases.index')->with('success', 'Database credential deleted successfully.');
    }
}
