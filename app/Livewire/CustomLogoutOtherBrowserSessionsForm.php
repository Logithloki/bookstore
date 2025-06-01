<?php

namespace App\Livewire;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Agent;
use Livewire\Component;
use MongoDB\Client as MongoClient;
use Illuminate\Support\Facades\Log;

class CustomLogoutOtherBrowserSessionsForm extends Component
{
    /**
     * Indicates if logout is being confirmed.
     *
     * @var bool
     */
    public $confirmingLogout = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Confirm that the user would like to log out from other browser sessions.
     *
     * @return void
     */
    public function confirmLogout()
    {
        $this->password = '';

        $this->dispatch('confirming-logout-other-browser-sessions');

        $this->confirmingLogout = true;
    }

    /**
     * Log out from other browser sessions.
     *
     * @param  \Illuminate\Auth\SessionGuard  $guard
     * @return void
     */
    public function logoutOtherBrowserSessions(\Illuminate\Auth\SessionGuard $guard)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $guard->logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        request()->session()->put([
            'password_hash_'.Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
        ]);

        $this->confirmingLogout = false;

        $this->dispatch('loggedOut');
    }

    /**
     * Delete the other browser session records from MongoDB.
     *
     * @return void
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'mongo') {
            return;
        }

        try {
            // Build MongoDB connection
            $connectionString = config('database.connections.mongodb.dsn');
            
            if (!empty($connectionString)) {
                // Use MongoDB Atlas connection string
                $dsn = $connectionString;
            } else {
                // Build connection string from individual config values
                $host = config('database.connections.mongodb.host', '127.0.0.1');
                $port = config('database.connections.mongodb.port', 27017);
                $username = config('database.connections.mongodb.username');
                $password = config('database.connections.mongodb.password');
                
                $dsn = 'mongodb://';
                if (!empty($username) && !empty($password)) {
                    $dsn .= "{$username}:{$password}@";
                }
                $dsn .= "{$host}:{$port}";
            }
            
            $mongo = new MongoClient($dsn);
            $database = config('database.connections.mongodb.database', 'laravel');
            $collection = $mongo->selectCollection($database, 'sessions');
            
            // Get current session ID
            $currentSessionId = request()->session()->getId();
            
            // Delete other sessions for this user
            $collection->deleteMany([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'id' => ['$ne' => $currentSessionId]
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail
            Log::error('Failed to delete other session records: ' . $e->getMessage());
        }
    }

    /**
     * Get the current sessions from MongoDB.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'mongo') {
            return collect();
        }

        try {
            // Build MongoDB connection
            $connectionString = config('database.connections.mongodb.dsn');
            
            if (!empty($connectionString)) {
                // Use MongoDB Atlas connection string
                $dsn = $connectionString;
            } else {
                // Build connection string from individual config values
                $host = config('database.connections.mongodb.host', '127.0.0.1');
                $port = config('database.connections.mongodb.port', 27017);
                $username = config('database.connections.mongodb.username');
                $password = config('database.connections.mongodb.password');
                
                $dsn = 'mongodb://';
                if (!empty($username) && !empty($password)) {
                    $dsn .= "{$username}:{$password}@";
                }
                $dsn .= "{$host}:{$port}";
            }
            
            $mongo = new MongoClient($dsn);
            $database = config('database.connections.mongodb.database', 'laravel');
            $collection = $mongo->selectCollection($database, 'sessions');
            
            // Find sessions for current user
            $sessions = $collection->find([
                'user_id' => Auth::user()->getAuthIdentifier()
            ], [
                'sort' => ['last_activity' => -1]
            ]);

            return collect(iterator_to_array($sessions))->map(function ($session) {
                return (object) [
                    'agent' => $this->createAgent($session),
                    'ip_address' => $session['ip_address'] ?? 'Unknown',
                    'is_current_device' => $session['id'] === request()->session()->getId(),
                    'last_active' => isset($session['last_activity']) 
                        ? Carbon::createFromTimestamp($session['last_activity'])->diffForHumans()
                        : 'Unknown',
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to get sessions: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Laravel\Jetstream\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent(), function ($agent) use ($session) {
            if (isset($session['user_agent'])) {
                $agent->setUserAgent($session['user_agent']);
            }
        });
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.custom-logout-other-browser-sessions-form');
    }
}
