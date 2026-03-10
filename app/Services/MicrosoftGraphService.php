<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MicrosoftGraphService
{
    public function getAccessToken(): ?string
    {
        $tenantId = config('services.ms.tenant_id');
        $clientId = config('services.ms.client_id');
        $clientSecret = config('services.ms.client_secret');

        if (! $tenantId || ! $clientId || ! $clientSecret) {
            Log::warning('Microsoft Graph: MS_TENANT_ID, MS_CLIENT_ID, or MS_CLIENT_SECRET is missing.');

            return null;
        }

        $url = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        $response = Http::asForm()->post($url, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ]);

        if (! $response->successful()) {
            Log::error('Microsoft Graph token error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    /**
     * Cek apakah integrasi SharePoint/OneDrive sudah dikonfigurasi.
     * Cukup salah satu: MS_DRIVE_ID (SharePoint) atau MS_USER_UPN (OneDrive personal).
     */
    public function isConfigured(): bool
    {
        $base = ! empty(config('services.ms.client_id'))
            && ! empty(config('services.ms.tenant_id'))
            && ! empty(config('services.ms.client_secret'))
            && ! empty(config('services.ms.file_path'));

        $hasDrive = ! empty(config('services.ms.drive_id'));
        $hasUser = ! empty(config('services.ms.user_upn'));

        return $base && ($hasDrive || $hasUser);
    }
}
