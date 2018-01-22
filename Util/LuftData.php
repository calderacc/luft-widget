<?php

class LuftData
{
    public function fetchData(string $stationCode): ?array
    {
        $apiUrl = sprintf('https://luft.jetzt/api/%s', $stationCode);

        $response = wp_remote_get($apiUrl);

        $responseCode = $response['response']['code'];

        if (200 !== $responseCode) {
            return null;
        }

        $data = json_decode($response['body']);

        return $data;
    }
}
