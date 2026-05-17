<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    public function getUsdToEurRate(): float
    {
        return Cache::remember('exchange_rate_usd_eur', now()->addMinutes(30), function () {
            try {
                $response = Http::timeout(10)
                    ->get(config('services.currency.exchange_rate_api_url'));

                if (! $response->successful()) {
                    Log::warning('Exchange rate API returned unsuccessful response', [
                        'status' => $response->status(),
                    ]);

                    return $this->fallbackRate();
                }

                return (float) data_get($response->json(), 'rates.EUR', $this->fallbackRate());
            } catch (\Throwable $exception) {
                Log::error('Exchange rate API request failed', [
                    'error' => $exception->getMessage(),
                ]);

                return $this->fallbackRate();
            }
        });
    }

    private function fallbackRate(): float
    {
        return (float) config('services.currency.exchange_rate', 0.85);
    }
}
