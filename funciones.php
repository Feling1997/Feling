<?php

function calcularPromedio($notas) {
    $suma = array_sum($notas);
    $promedio = $suma / count($notas);
    return $promedio;
}