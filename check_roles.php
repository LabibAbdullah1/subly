<?php
use App\Models\User;
echo "Roles: " . User::pluck('role')->unique()->implode(', ') . "\n";
echo "Clients Count: " . User::where('role', 'Client')->count() . "\n";
echo "admin Count: " . User::where('role', 'Admin')->count() . "\n";
echo "client Count: " . User::where('role', 'client')->count() . "\n";
