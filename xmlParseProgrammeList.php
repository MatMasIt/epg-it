<?php
function ITDate($UNIX)
{
    $mesi = array(
        1 => 'gennaio', 'Febbraio', 'Marzo', 'Aprile',
        'Maggio', 'Giugno', 'Luglio', 'Agosto',
        'Settembre', 'Ottobre', 'Novembre', 'Dicembre'
    );

    $giorni = array(
        'Domenica', 'Lunedì', 'Martedì', 'Mercoledì',
        'Giovedì', 'Venerdì', 'Sabato'
    );

    list($sett, $giorno, $mese, $anno) = explode('-', date('w-d-n-Y', $UNIX));

    return  $giorni[$sett] . ' ' . $giorno . ' ' . $mesi[$mese] . ' ' . $anno;
}
function epgDateToUnix($date)
{
    return mktime($date[8] . $date[9], $date[10] . $date[11], $date[12] . $date[13], $date[4] . $date[5], $date[6] . $date[7], $date[0] . $date[1] . $date[2] . $date[3]);
}
function mdGen($channel)
{
    $fin = "# " . $channel["title"];
    $fin .= "\n> Programmazione di " . ITDate(time());
    $fin .= "\n\n||Programma|Inizio|Fine|Descrizione|\n|---|---|---|---|---|";
    foreach ($channel["programmes"] as $programma) {
        $fin .= "\n|![Icon](" . ($programma["icon"]) . ")|" . htmlentities($programma["title"]) . "|" . htmlentities(date("H:i:s", $programma["start"])) . "|" . htmlentities(date("H:i:s", $programma["end"])) . "|" . htmlentities($programma["description"]);
    }
    $fin .= "\n\n\n\n > epg-it 0.1.0, MatMasIt - Dati epg SKY";
    return $fin;
}

function xmlParseList($fileName)
{
    $res = [];
    if (file_exists($fileName)) {
        $xml = simplexml_load_file($fileName);
        foreach ($xml->channel as $channel) {
            $res["channels"][(string) $channel["id"]] = ["title" => (string) $channel->{'display-name'}];
        }
        foreach ($xml->programme as $programme) {
            $data = ["start" => epgDateToUnix((string)$programme["start"]), "end" => epgDateToUnix((string)$programme["stop"]), "title" => (string) $programme->title, "description" => (string) $programme->desc, "icon" => (string) $programme->icon["src"]];
            $res["channels"][(string) $programme["channel"]]["programmes"][] = $data;
        }
    } else {
        exit('Failed to open test.xml.');
    }
    return $res;
}

$data = xmlParseList("guidatv.sky.it.epg.xml");
file_put_contents("programmi/data.json", json_encode($data, JSON_PRETTY_PRINT));
foreach ($data["channels"] as $channel => $data) {
    file_put_contents("programmi/" . $channel . ".md", mdGen($data));
}
