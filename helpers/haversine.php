<?php
function hitungJarak($lat1, $lon1, $lat2, $lon2) {
    $lat1 = (float)$lat1; $lon1 = (float)$lon1;
    $lat2 = (float)$lat2; $lon2 = (float)$lon2;
    $earthRadius = 6371000;
    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;
    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($latFrom) * cos($latTo) *
         sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

function dalamRadius($latSiswa, $lonSiswa, $latPT, $lonPT, $radius) {
    $jarak = hitungJarak($latSiswa, $lonSiswa, $latPT, $lonPT);
    return $jarak <= $radius;
}
