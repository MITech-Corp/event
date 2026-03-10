<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Microsoft Graph (SharePoint Online / OneDrive for Business)
    |--------------------------------------------------------------------------
    | Untuk mengambil data karyawan langsung dari file Excel/CSV di SharePoint
    | atau OneDrive personal. Kosongkan MS_CLIENT_ID untuk menonaktifkan.
    |
    | - OneDrive personal (link ...-my.sharepoint.com/.../personal/...):
    |   Isi MS_USER_UPN (email pemilik file) dan MS_FILE_PATH (nama file atau path).
    | - SharePoint team site / document library:
    |   Isi MS_DRIVE_ID dan MS_FILE_PATH.
    */
    'ms' => [
        'tenant_id' => env('MS_TENANT_ID'),
        'client_id' => env('MS_CLIENT_ID'),
        'client_secret' => env('MS_CLIENT_SECRET'),
        'drive_id' => env('MS_DRIVE_ID'),
        'user_upn' => env('MS_USER_UPN'),
        'file_path' => env('MS_FILE_PATH', '/Karyawan.xlsx'),
    ],

];
