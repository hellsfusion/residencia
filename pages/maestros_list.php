<?php
// este archivo se usa para tener una relación de campos o inputs maestros y su relación con la base de datos
// en cada formulario que use estos campos se debe agregar un include de este archivo
// la idea es crear inputs para ser usados desde el creador de formularios 
// se ajusta el creador de formularios para incluir estos campos

// array de maestros
$arrayMaestros = [
    [
        'nombre' => 'Edificios', // nombre para mostrar
        'tabla' => 'edificios', // tabla de la base de datos
        'icono' => 'fas fa-building', // icono
        'inputName' => 'Nombre', // nombre del input para formularios
        'selectValue' => 'id|/|nombre',
        'formulario' => 'Nombre>nombre:text|/|Conjunto Residencial>conjunto:text|/|Rif>rif:text|/|Direccion>direccion:text'
    ],
    [
        'nombre' => 'Monedas',
        'tabla' => 'monedas',
        'icono' => 'fas fa-coins',
        'inputName' => 'Moneda',
        'selectValue' => 'id|/|nombre',
        'formulario' => 'Nombre>nombre:text|/|Prefijo>prefijo:text|/|Principal>principal:number'
    ],
    [
        'nombre' => 'Categorias Gastos',
        'tabla' => 'cat_gastos',
        'icono' => 'fas fa-list',
        'inputName' => 'Categoria de Gasto',
        'selectValue' => 'id|/|nombre',
        'formulario' => 'Nombre>nombre:text'
    ],
    [
        'nombre' => 'Categorias Ingresos',
        'tabla' => 'cat_ingresos',
        'icono' => 'fas fa-list',
        'inputName' => 'Categoria de Ingreso',
        'selectValue' => 'id|/|nombre',
        'formulario' => 'Nombre>nombre:text'
    ],
    [
        'nombre' => 'comisiones',
        'tabla' => 'comisiones',
        'icono' => 'fas fa-list',
        'inputName' => 'Comisión',
        'selectValue' => 'id|/|nombre',
        'formulario' => 'Nombre>nombre:text|/|Porcentaje>porcentaje:number'
    ],
];