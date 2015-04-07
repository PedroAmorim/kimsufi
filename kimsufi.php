<?php
$url = "http://ws.ovh.com/dedicated/r2/ws.dispatcher/getAvailability2";
while (1) {
    $exec = false;
    fwrite(STDOUT, " --- Start Kimsufi Availability Script --- ".date('d-m-Y H:i:s').PHP_EOL);
    // Create a curl handle to a non-existing location
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = '';
    if (($json = curl_exec($ch)) === false) {
        fwrite(STDOUT, 'Curl error: ' . curl_error($ch).PHP_EOL);
    } else {
        $a = json_decode($json);
        // Choose one or more server to check
        $ref = [
            '150sk10', // KS1
            // '150sk20', // KS2
            // '150sk22', // KS2 SSD
            // '150sk30', // KS3
            // '150sk40', // KS4
            // '150sk41', // KS4
            // '150sk42', // KS4
            // '150sk50', // KS5
            // '150sk60', // KS6
        ];
        $avail = $a->answer->availability;
        foreach ($avail as $s) {
            if (in_array($s->reference, $ref)) {
                $z = $s->zones;
                foreach ($z as $zone) {
                    if ($zone->availability!=='unavailable' &&  $zone->availability!=='unknown') {
                        // replace Firefox by your browser if necessary
                        shell_exec('/usr/bin/firefox --new-window https://www.kimsufi.com/fr/commande/kimsufi.xml?reference='.$s->reference);
                        fwrite(STDOUT, $s->reference.PHP_EOL);
                        $exec = true;
                        break;// Only get first available
                    }
                }
            }
        }
    }
    fwrite(STDOUT, " --- --------------------------------- --- ".PHP_EOL);
    ($exec) ? sleep(600) : sleep(60);
}
