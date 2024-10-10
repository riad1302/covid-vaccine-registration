<?php

namespace App\Services;

use App\Enums\RedisEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class VaccinationRedisService
{
    private $redis;

    private string $vaccineRedisDb;

    public function __construct()
    {
        $this->redis = Redis::connection();
        // Retrieve the vaccine Redis DB from config once during construction
        $this->vaccineRedisDb = config('database.redis.vaccine_date.database');
        $this->redis->select($this->vaccineRedisDb);
    }

    /**
     * Store vaccination date data in Redis.
     */
    public function storeVaccinationDate(string $nationalId, array $data): bool
    {
        $cacheKey = $this->getCacheKey($nationalId);
        $ttl = Carbon::now()->addMonths(2)->diffInSeconds();

        try {
            $this->redis->set($cacheKey, json_encode($data), $ttl);
        } catch (\Exception $e) {
            Log::error('VaccinationRedisService storeVaccinationDate: '.$e->getMessage());

            return false;
        }

        return true;
    }

    private function getCacheKey(string $identifier): string
    {
        return RedisEnum::VACCINATION_DATE_KEY->value.':'.$identifier;
    }

    /**
     * Retrieve vaccination date data from Redis.
     */
    public function fetchVaccinationDate(string $nationalId): ?array
    {
        $cacheKey = $this->getCacheKey($nationalId);
        $data = $this->redis->get($cacheKey);

        return $data ? json_decode($data, true) : null;
    }
}
