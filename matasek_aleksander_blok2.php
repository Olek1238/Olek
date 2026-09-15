<?php

$zawodnicy = [
    ['nazwisko' => 'Anna Kowalska',    'okrazenia' => [312, 298, 305]],
    ['nazwisko' => 'Piotr Nowak',      'okrazenia' => [355, 430, 341]],
    ['nazwisko' => 'Marek Zieliński',  'okrazenia' => []],
    ['nazwisko' => 'Ewa Wiśniewska',   'okrazenia' => [402, 377, 395]],
];

function czasNaMinuty($sekundy)
{
    $minuty = floor($sekundy / 60);
    $sekundy = $sekundy % 60;

    return $minuty . ':' . str_pad($sekundy, 2, '0', STR_PAD_LEFT);
}

$liczbaZawodnikow = count($zawodnicy);
$liczbaElita = 0;
$najlepszeOkrazenieZawodow = null;
$lacznaLiczbaOkrazen = 0;

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <title>Wyniki zawodów</title>

    <style>
        body {
            font-family: sans-serif;
            margin: 2rem;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 0.4rem 0.8rem;
            text-align: left;
        }

        caption {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        tfoot td {
            font-weight: bold;
        }

        .elita {
            background: #d4f5d4;
        }

        .zaawansowany {
            background: #fff3c4;
        }

        .amator {
            background: #f5d4d4;
        }
    </style>
</head>

<body>

    <h1>Zawody biegowe: czasy okrążeń</h1>

    <table>

        <caption>Wyniki zawodników</caption>

        <thead>
            <tr>
                <th scope="col">Zawodnik</th>
                <th scope="col">Okrążenia</th>
                <th scope="col">Najlepsze</th>
                <th scope="col">Średnie</th>
                <th scope="col">Kategoria</th>
                <th scope="col">Uwagi</th>
            </tr>
        </thead>

        <tbody>

            <?php

            foreach ($zawodnicy as $zawodnik) {

                $nazwisko = $zawodnik['nazwisko'];
                $okrazenia = $zawodnik['okrazenia'];

                if (empty($okrazenia)) {
                    echo '<tr>';
                    echo '<td>' . $nazwisko . '</td>';
                    echo '<td colspan="5">brak ukończonych okrążeń</td>';
                    echo '</tr>';

                    continue;
                }

                $lacznaLiczbaOkrazen += count($okrazenia);

                $najlepsze = min($okrazenia);

                $srednia = round(
                    array_sum($okrazenia) / count($okrazenia)
                );

                if ($najlepsze < 300) {
                    $kategoria = 'elita';
                    $liczbaElita++;
                } elseif ($najlepsze < 360) {
                    $kategoria = 'zaawansowany';
                } else {
                    $kategoria = 'amator';
                }

                $slabeOkrazenie = false;

                foreach ($okrazenia as $okrazanie) {
                    if ($okrazanie >= 420) {
                        $slabeOkrazenie = true;
                        break;
                    }
                }

                $rowneTempo = true;

                foreach ($okrazenia as $okrazanie) {
                    if ($okrazanie > $najlepsze + 15) {
                        $rowneTempo = false;
                        break;
                    }
                }

                if ($slabeOkrazenie) {
                    $uwagi = 'słabe okrążenie';
                } elseif ($rowneTempo) {
                    $uwagi = 'równe tempo';
                } else {
                    $uwagi = '';
                }

                if (
                    $najlepszeOkrazenieZawodow === null ||
                    $najlepsze < $najlepszeOkrazenieZawodow
                ) {
                    $najlepszeOkrazenieZawodow = $najlepsze;
                }

                echo '<tr class="' . $kategoria . '">';

                echo '<td>';
                echo $nazwisko;
                echo '</td>';

                echo '<td>';

                $czasy = [];

                foreach ($okrazenia as $okrazanie) {
                    $czasy[] = czasNaMinuty($okrazanie);
                }

                echo implode(', ', $czasy);

                echo '</td>';

                echo '<td>';
                echo czasNaMinuty($najlepsze);
                echo '</td>';

                echo '<td>';
                echo czasNaMinuty($srednia);
                echo '</td>';

                echo '<td>';
                echo $kategoria;
                echo '</td>';

                echo '<td>';
                echo $uwagi;
                echo '</td>';

                echo '</tr>';
            }

            ?>

        </tbody>

        <tfoot>

            <tr>
                <td colspan="6">
                    Liczba zawodników:
                    <?= $liczbaZawodnikow ?>
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    Liczba zawodników w kategorii „elita”:
                    <?= $liczbaElita ?>
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    Najlepsze okrążenie zawodów:
                    <?= czasNaMinuty($najlepszeOkrazenieZawodow) ?>
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    Łączna liczba okrążeń:
                    <?= $lacznaLiczbaOkrazen ?>
                </td>
            </tr>

        </tfoot>

    </table>

</body>

</html>
