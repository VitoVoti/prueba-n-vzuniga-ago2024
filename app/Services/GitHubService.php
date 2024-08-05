<?php
namespace App\Services;

use GuzzleHttp;

class GitHubService
{

    public static function getInfoFromUsername(string $username): array | string
    {
        $endpoint = "https://api.github.com/users/$username";
        return self::executeGet($endpoint);
    }

    public static function getReposFromUsername(string $username): array | string
    {
        $endpoint = "https://api.github.com/users/$username/repos";
        return self::executeGet($endpoint);
    }

    private static function executeGet(string $endpoint): string | array {

        if( config('github_api.personal_access_token') != "" ) {
            $client = new GuzzleHttp\Client([
                'headers' => [
                    "Authorization" => "Bearer " . config('github_api.personal_access_token'),
                    "X-GitHub-Api-Version" => "2022-11-28",
                ],
            ]);
        } else {
            $client = new GuzzleHttp\Client();
        }
        
        $response = $client->request('GET', $endpoint)->getBody();
        if ($response === false) {
            return "";
        }
        $data = json_decode($response, true);
        return $data;
    }
}