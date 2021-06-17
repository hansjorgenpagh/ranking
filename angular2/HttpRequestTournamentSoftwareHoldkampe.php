<?php
include 'output.php';
//include 'match.php';

class Player
{
    public $license = '';
    public $name;

}

class Match
{

public $dateStr = '';
public $player1Lic = '';
    public $player1Name = '';
    public $player2Lic = '';
    public $player2Name = '';
    public $resultat = '';

}


//Open league/
parse_str($_SERVER['QUERY_STRING']);
$opts = array(
    'http' => array(
        'method' => "GET",
        'header' => implode("\n", array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Host: dtf.tournamentsoftware.com',
            'cookie: _ga=GA1.2.1664734092.1565977025; lvt=uF50ODlhh0aXRfUYsnGHVATucUd5KpocYoWVolKIOLoC2iPQch5EWZcnTV9+z0uLEZz91VOhMNk=; .ASPX_TOURNAMENT_WEBSITE=CDE700289AA143C13104CAA71DAEEB3D33B1C296E46B6ACE3C58BED7D298A9DB8B21703D245E20B271D5EDD2FE5479B3F29D0F381FD78C7D8C0CDF70599F4111F0E9DA9180B703A6C48059C4B8E7331A87825D8DD41E09348E25DEEAAF04EC34994FDB92; ASP.NET_SessionId=lbhzy2knkuknbece1hukrfuk; _gid=GA1.2.906509092.1597317565; _gat=1; st=l=1030&exp=44421.7159161227&s=0&c=1&cp=16'
        ))
    )
);
echo ('<table>');
for ($x = $min;$x <= $max;$x++)
{
    outputTdColspan($x, 4);
    $context = stream_context_create($opts);
    $fileDraw = file_get_contents('https://dtf.tournamentsoftware.com/league/' . $league . '/draw/' . $x, false, $context);
    $teamMatchStartPos = strpos($fileDraw, '<div class="match--team-match match">', 0);
    while ($teamMatchStartPos != false)
    {
        $teamMatchTagStart = strpos($fileDraw, '<a class="team-match__wrapper" href="/league/', $teamMatchStartPos);
        $teamMatchTagEnd = strpos($fileDraw, '</a>', $teamMatchTagStart);
        $teamMatchTagHtml = substr($fileDraw, $teamMatchTagStart, $teamMatchTagEnd - $teamMatchTagStart + strlen('</a>'));
        $dom = new DOMDocument;
        $dom->loadHTML($teamMatchTagHtml);
        $tags = $dom->getElementsByTagName('a');
        foreach ($tags as $teamMatchTag)
        {
            //output('a tag');
            
        }
        parse_str($_SERVER['QUERY_STRING']);
        $context = stream_context_create($opts);
        outputTdColspan('https://dtf.tournamentsoftware.com' . $teamMatchTag->getAttribute('href') , 4);
        $file = file_get_contents('https://dtf.tournamentsoftware.com' . $teamMatchTag->getAttribute('href') , false, $context);
        $timePosStart = strpos($file, '<time datetime="', 0);
        $timePosEnd = strpos($file, '>', $timePosStart + strlen('<time datetime="'));
        $timeHtml = substr($file, $timePosStart, $timePosEnd - $timePosStart + 1);
        $dom = new DOMDocument;
        $dom->loadHTML($timeHtml);
        $tags = $dom->getElementsByTagName('time');
        foreach ($tags as $tag)
        {
            $dato2 = $tag->getAttribute('datetime');
            $dato3 = new DateTime($dato2);
        }
        $matchStartPos = 0;
        do
        {
            $matchStartPos = strpos($file, '<li class="match-group__item">', $matchStartPos + 1);
            if ($matchStartPos != false)
            {
                $matchNextStartPos = strpos($file, '<li class="match-group__item">', $matchStartPos + 1);
                if ($matchNextStartPos == false)
                {
                    $matchNextStartPos = strlen($file);
                }
                $player1Pos = strpos($file, '<div class="match__row ', $matchStartPos);
                $player2Pos = strpos($file, '<div class="match__row ', $player1Pos + 1);
                if ($player1Pos < $matchNextStartPos and $player2Pos < $matchNextStartPos)
                {
                    if (substr($file, $player1Pos + strlen('<div class="match__row ') , 7) == 'has-won')
                    {
                        $player1Won = true;
                    }
                    else
                    {
                        $player1Won = false;
                    }
                    $player1TagStart = strpos($file, '<a href="/league/', $player1Pos + 1);
                    if ($player1TagStart != false)
                    {
                        if ($player1TagStart < $matchNextStartPos)
                        {
                            $player1TagEnd = strpos($file, '</a>', $player1TagStart + 1);
                            $player1TagHtml = substr($file, $player1TagStart, $player1TagEnd - $player1TagStart + strlen('</a>'));
                            $player2TagStart = strpos($file, '<a href="/league/', $player2Pos + 1);
                            if ($player2TagStart != false)
                            {
                                if ($player2TagStart < $matchNextStartPos)
                                {
                                    $player2TagEnd = strpos($file, '</a>', $player2TagStart + 1);
                                    $player2TagHtml = substr($file, $player2TagStart, $player2TagEnd - $player2TagStart + strlen('</a>'));
                                    $resultat1Start = strpos($file, '<ul class="points">', $player1TagStart);
                                    if ($resultat1Start != false)
                                    {
                                        if ($resultat1Start < $player2Pos)
                                        {
                                            $player1DoublePos = strpos($file, '<a href="/league/', $player1TagStart + 1);
                                            if ($player1DoublePos != false)
                                            {
                                                if ($player1DoublePos >= $player2Pos)
                                                {
                                                    $dom = new DOMDocument;
                                                    $dom->loadHTML($player1TagHtml);
                                                    $tags = $dom->getElementsByTagName('a');
                                                    foreach ($tags as $playerTag)
                                                    {
                                                        break;
                                                    }
                                                    $context = stream_context_create($opts);
                                                    $player1File = file_get_contents('https://dtf.tournamentsoftware.com' . $playerTag->getAttribute('href') , false, $context);
                                                    $player1Name = $playerTag->nodeValue;
                                                    $player1LicensTagStart = strpos($player1File, '<span class="media__title-aside"', 0);
                                                    $player1LicensTagEnd = strpos($player1File, '</span>', $player1LicensTagStart);
                                                    $player1LicensTagHtml = substr($player1File, $player1LicensTagStart, $player1LicensTagEnd - $player1LicensTagStart + strlen('</span>'));
                                                    $dom = new DOMDocument;
                                                    $dom->loadHTML($player1LicensTagHtml);
                                                    $tags = $dom->getElementsByTagName('span');
                                                    foreach ($tags as $player1LicensTag)
                                                    {
                                                        break;
                                                    }
                                                    $player1Licens = substr($player1LicensTag->nodeValue, 1, strlen($player1LicensTag->nodeValue) - 2);
                                                    $dom = new DOMDocument;
                                                    $dom->loadHTML($player2TagHtml);
                                                    $tags = $dom->getElementsByTagName('a');
                                                    foreach ($tags as $playerTag)
                                                    {
                                                        break;
                                                    }
                                                    $player2Name = $playerTag->nodeValue;
                                                    echo ('<tr>');
                                                    if ($player1Won)
                                                    {
                                                        outputTd($player1Name);
                                                        outputTd($player2Name);
                                                    }
                                                    else
                                                    {
                                                        outputTd($player2Name);
                                                        outputTd($player1Name);
                                                    }
                                                    $player2File = file_get_contents('https://dtf.tournamentsoftware.com' . $playerTag->getAttribute('href') , false, $context);
                                                    $player2LicensTagStart = strpos($player2File, '<span class="media__title-aside"', 0);
                                                    $player2LicensTagEnd = strpos($player2File, '</span>', $player2LicensTagStart);
                                                    $player2LicensTagHtml = substr($player2File, $player2LicensTagStart, $player2LicensTagEnd - $player2LicensTagStart + strlen('</span>'));
                                                    $dom = new DOMDocument;
                                                    $dom->loadHTML($player2LicensTagHtml);
                                                    $tags = $dom->getElementsByTagName('span');
                                                    foreach ($tags as $player2LicensTag)
                                                    {
                                                        break;
                                                    }
                                                    $player2Licens = substr($player2LicensTag->nodeValue, 1, strlen($player2LicensTag->nodeValue) - 2);
													outputTd($player1Licens);
													outputTd($player2Licens);
                                                    $resultat1End = strpos($file, '</ul>', $resultat1Start);
                                                    $resultat2Start = strpos($file, '<ul class="points">', $player2Pos);
                                                    $resultat2End = strpos($file, '</ul>', $resultat2Start);
                                                    $resultat = '';
                                                    $resultat1SetStart = $resultat1Start;
                                                    $resultat2SetStart = $resultat2Start;
                                                    do
                                                    {
                                                        $resultat1SetStart = strpos($file, '<li class="points__cell', $resultat1SetStart + 1);
                                                        if ($resultat1SetStart != false)
                                                        {
                                                            if ($resultat1SetStart < $player2Pos)
                                                            {
                                                                $resultat1SetEnd = strpos($file, '</li>', $resultat1SetStart);
                                                                $resultat1 = substr(strrchr(substr($file, $resultat1SetStart, $resultat1SetEnd - $resultat1SetStart) , '>') , 1);
                                                                $resultat2SetStart = strpos($file, '<li class="points__cell', $resultat2SetStart + 1);
                                                                $resultat2SetEnd = strpos($file, '</li>', $resultat2SetStart);
                                                                $resultat2 = substr(strrchr(substr($file, $resultat2SetStart, $resultat2SetEnd - $resultat2SetStart) , '>') , 1);
                                                                if ($resultat <> '')
                                                                {
                                                                    $resultat = $resultat . ' ';
                                                                }
                                                                if ($player1Won)
                                                                {
                                                                    $resultat = $resultat . $resultat1 . '-' . $resultat2;
                                                                }
                                                                else
                                                                {
                                                                    $resultat = $resultat . $resultat2 . '-' . $resultat1;
                                                                }
                                                            }
                                                            else
                                                            {
                                                                break;
                                                            }
                                                        }
                                                        else
                                                        {
                                                            break;
                                                        }
                                                    }
                                                    while (true);
                                                    outputTd($resultat);
                                                    //Check for rtd or wo
                                                    $matchOk = false;
                                                    $resultCheckPosWo = strpos($file, '<span class="tag tag--warning match__message">w.o.</span>', $matchStartPos + 1);
                                                    if ($resultCheckPosWo == false)
                                                    {
                                                        $resultCheckPosWo = strpos($file, '<span class="match__message tag tag--warning">w.o.</span>', $matchStartPos + 1);
                                                    }
                                                    $resultCheckPosRtd = strpos($file, '<span class="tag--warning tag match__message">Rtd.</span>', $matchStartPos + 1);
                                                    if ($resultCheckPosRtd == false)
                                                    {
                                                        $resultCheckPosRtd = strpos($file, '<span class="match__message tag tag--warning">Rtd.</span>', $matchStartPos + 1);
                                                    }
                                                    if ($resultCheckPosRtd == false)
                                                    {
                                                        $resultCheckPosRtd = strpos($file, '<span class="match__message tag tag--warning">Opgave</span>', $matchStartPos + 1);
                                                    }
                                                    $matchNextStartPos = strpos($file, '<li class="match-group__item">', $matchStartPos + 1);
                                                    if ($matchNextStartPos == false)
                                                    {
                                                        if ($resultCheckPosWo == false)
                                                        {
                                                            if ($resultCheckPosRtd == false)
                                                            {
                                                                $matchOk = false; //must be true
                                                                
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        if ($resultCheckPosWo == false)
                                                        {
                                                            if ($resultCheckPosRtd == false)
                                                            {
                                                                $matchOk = true;
                                                            }
                                                            else
                                                            {
                                                                if ($resultCheckPosRtd > $matchNextStartPos)
                                                                {
                                                                    $matchOk = true;
                                                                }
                                                            }
                                                        }
                                                        else
                                                        {
                                                            if ($resultCheckPosWo > $matchNextStartPos)
                                                            {
                                                                if ($resultCheckPosRtd == false)
                                                                {
                                                                    $matchOk = true;
                                                                }
                                                                else
                                                                {
                                                                    if ($resultCheckPosRtd > $matchNextStartPos)
                                                                    {
                                                                        $matchOk = true;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($matchOk)
                                                    {
                                                        if ($player1Won)
                                                        {
                                                            $data = array(
                                                                'database' => 'U12',
                                                                'Dato' => $dato3->format('Ymd') ,
                                                                'player1' => $player1Licens,
                                                                'player2' => $player2Licens,
                                                                'point1' => '2',
                                                                'point2' => '0',
                                                                'resultat' => $resultat,
                                                                'comment' => 'https://dtf.tournamentsoftware.com/league/' . $league . '/draw/' . $x
                                                            );
                                                        }
                                                        else
                                                        {
                                                            $data = array(
                                                                'database' => 'U12',
                                                                'Dato' => $dato3->format('Ymd') ,
                                                                'player1' => $player2Licens,
                                                                'player2' => $player1Licens,
                                                                'point1' => '2',
                                                                'point2' => '0',
                                                                'resultat' => $resultat,
                                                                'comment' => 'https://dtf.tournamentsoftware.com/league/' . $league . '/draw/' . $x
                                                            );
                                                        }
                                                        $match = new Match;
                                                        $url = 'http://192.168.0.2/u8/angular2/processNewMatch.php';
                                                        $opts2 = array(
                                                            'http' => array(
                                                                'method' => "POST",
                                                                'header' => "Accept-language: en\r\n" . "Content-Type: application/x-www-form-urlencoded\r\n" . "Cookie: __utma=256282518.1557752900.1487681509.1495025600.1506591136.2; _ga=GA1.2.1557752900.1487681509; ASP.NET_SessionId=hwjbt2jsuo0s2ixbr4kv5yth",
                                                                'content' => http_build_query($data)
                                                            )
                                                        );
                                                        $context2 = stream_context_create($opts2);
                                                        $file2 = file_get_contents($url, false, $context2);
                                                        $match->dateStr = '2018-10-17';
                                                        $match->player1Lic = $player1Licens;
                                                        $match->player2Lic = $player2Licens;
                                                        $match->resultat = $resultat;
                                                        $result = json_decode($file2);
                                                        if ($result->{'result'} === true)
                                                        {
                                                            //Success
                                                            outputTd('Success');
                                                            array_push($success, $match);
                                                        }
                                                        else
                                                        {
                                                            //Error
                                                            outputTd('Error: ' . $result->{'errors3'});
                                                            array_push($failed, $match);
                                                        }
                                                    }
                                                    else
                                                    {
                                                        outputTd('Skipped');
                                                    }
                                                    echo ('</tr>');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                break;

            }
        }
        while (true);
        $teamMatchStartPos = strpos($fileDraw, '<div class="match--team-match match">', $teamMatchStartPos + 1);
    }
}
outputTdColspan('Finished', 4);
echo ('</table>');
?>
