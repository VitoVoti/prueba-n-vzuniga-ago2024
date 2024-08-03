<?php
namespace App\Services;

use GuzzleHttp;

class GitHubService
{

    public static function getInfoFromUsername(string $username): array | string
    {
        $client = new GuzzleHttp\Client();
        $endpoint = "https://api.github.com/users/$username";
        $response = $client->request('GET', $endpoint)->getBody();
        if ($response === false) {
            return "";
        }
        $data = json_decode($response, true);
        return $data;
    }
}