<?php

class LuftData
{
    const TTL = 3600;

    public function fetchData($stationCode)
    {
        if ($this->isCached($stationCode)) {
            return $this->getCache($stationCode);
        }

        $apiUrl = sprintf('https://luft.jetzt/api/%s', $stationCode);

        $response = wp_remote_get($apiUrl);

        $responseCode = $response['response']['code'];

        if (200 !== $responseCode) {
            return null;
        }

        $data = json_decode($response['body']);

        $this->setCache($stationCode, $data);

        return $data;
    }

    protected function isCached($stationCode)
    {
        return get_transient($stationCode);
    }

    public function setCache($key, $data)
    {
        return set_transient($key, $data, self::TTL);
    }

    public function getCache($key)
    {
        return get_transient($key);
    }
}
