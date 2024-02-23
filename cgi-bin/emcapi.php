<?php
    function getNationInfo(string $nation){
        $json = file_get_contents("https://api.earthmc.net/v2/aurora/nations/".$nation);
        $contJson = json_decode($json);
        return [$contJson->stats->numResidents, $contJson->towns, $contJson->enemies];
    }
    function getTownInfo(string $town){
        $json = file_get_contents("https://api.earthmc.net/v2/aurora/towns/".$town);
        $contJson = json_decode($json);
        return [$contJson->stats->numResidents, $contJson->mayor, $contJson->status->isOpen];
    }