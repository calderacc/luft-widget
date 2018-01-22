<?php

class StationList
{
    public function __construct()
    {

    }

    public function getStationList(): array
    {
        $stationList = [];
        $stationDataList = $this->fetchStationList();

        foreach ($stationDataList as $stationData) {
            $stationList[$stationData->station_code] = sprintf('%s: %s', $stationData->station_code, $stationData->title);
        }

        ksort($stationList);

        return $stationList;
    }

    protected function fetchStationList()
    {
        $apiUrl = sprintf('https://luft.jetzt/api/station');

        $response = wp_remote_get($apiUrl);

        $responseCode = $response['response']['code'];

        if (200 !== $responseCode) {
            return null;
        }

        $data = json_decode($response['body']);

        return $data;
    }
}
