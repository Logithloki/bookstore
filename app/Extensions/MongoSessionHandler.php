<?php

namespace App\Extensions;

use SessionHandlerInterface;
use MongoDB\Client;
use Carbon\Carbon;

class MongoSessionHandler implements SessionHandlerInterface
{
    protected $collection;
    protected $minutes;

    public function __construct(Client $client, $database, $collection, $minutes)
    {
        $this->collection = $client->selectCollection($database, $collection);
        $this->minutes = $minutes;
    }

    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }    public function read(string $sessionId): string
    {
        // Ensure we're using a valid non-null session ID
        if (empty($sessionId)) {
            return '';
        }
        
        try {
            $session = $this->collection->findOne(['id' => $sessionId]);
            return $session['payload'] ?? '';
        } catch (\Exception $e) {
            error_log('MongoDB session read failed: ' . $e->getMessage());
            return '';
        }
    }public function write(string $sessionId, string $data): bool
    {
        // Don't write sessions with empty IDs
        if (empty($sessionId)) {
            return false;
        }
        
        $updateData = [
            'id' => $sessionId,
            'payload' => $data,
            'last_activity' => Carbon::now()->timestamp,
        ];
        
        // Try to parse session data to extract user information, but don't fail if it's invalid
        try {
            if (!empty($data)) {
                $sessionData = @unserialize($data);
                if ($sessionData !== false && is_array($sessionData)) {
                    $userId = null;
                    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'unknown');
                    
                    // Extract user ID from session data
                    foreach ($sessionData as $key => $value) {
                        if (strpos($key, 'login_web_') === 0) {
                            $userId = $value;
                            break;
                        }
                    }
                    
                    $updateData['user_agent'] = $userAgent;
                    $updateData['ip_address'] = $ipAddress;
                    
                    // Add user_id if we found one
                    if ($userId) {
                        $updateData['user_id'] = $userId;
                    }
                }
            }
        } catch (\Exception $e) {
            // If session data parsing fails, continue without user info
        }
        
        try {
            $this->collection->updateOne(
                ['id' => $sessionId],
                ['$set' => $updateData],
                ['upsert' => true]
            );
            return true;
        } catch (\Exception $e) {
            // Log the error but don't crash the application
            error_log('MongoDB session write failed: ' . $e->getMessage());
            return false;
        }
    }    public function destroy(string $sessionId): bool
    {
        if (empty($sessionId)) {
            return true;
        }
        
        try {
            $this->collection->deleteOne(['id' => $sessionId]);
            return true;
        } catch (\Exception $e) {
            error_log('MongoDB session destroy failed: ' . $e->getMessage());
            return false;
        }
    }    public function gc(int $lifetime): int|false
    {
        try {
            $past = Carbon::now()->subSeconds($lifetime)->timestamp;
            $result = $this->collection->deleteMany([
                'last_activity' => ['$lt' => $past],
                'id' => ['$ne' => null] // Only delete records with non-null IDs
            ]);
            return $result->getDeletedCount();
        } catch (\Exception $e) {
            error_log('MongoDB session garbage collection failed: ' . $e->getMessage());
            return false;
        }
    }
}
