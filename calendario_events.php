<?php
// calendario_events.php

function obterEventosCalendario($tipo_folga) {
    $eventos = [];

    // Lógica para calcular os eventos com base no tipo de folga
    // ...

    // Exemplo: Se o tipo de folga for 6 por 1
    $dias_trabalho = 6;
    $dias_folga = 1;

    $data_atual = date('Y-m-d');
    $data_final = date('Y-m-d', strtotime($data_atual . ' + 1 month'));

    $data = $data_atual;

    while ($data <= $data_final) {
        for ($i = 0; $i < $dias_trabalho; $i++) {
            $eventos[] = [
                'title' => 'Trabalho',
                'start' => $data,
                'color' => 'green',
            ];
            $data = date('Y-m-d', strtotime($data . ' + 1 day'));
        }

        for ($i = 0; $i < $dias_folga; $i++) {
            $eventos[] = [
                'title' => 'Folga',
                'start' => $data,
                'color' => 'yellow',
            ];
            $data = date('Y-m-d', strtotime($data . ' + 1 day'));
        }
    }

    return $eventos;
}
?>
