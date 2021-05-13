<?php

function Curso()
{
    $hoy = new Date();
    $ano = $hoy->format('Y');
    $mes = $hoy->format('m');
    $curso = $mes > '07' ? $ano : $ano - 1;
    $lcurso = $curso . '-' . ($curso + 1);
    return $lcurso;
}


function valorReal($elemento, $string)
{
    if (strpos($string, '->')) {
        $sub = explode('->', $string, 2);
        $sub1 = $sub[0];
        $sub2 = $sub[1];
        return $elemento->$sub1->$sub2;
    } else
        return $elemento->$string;
}

function hazArray($elementos, $campo1, $campo2=null, $separador = ' ')
{
    $todos = [];
    $campo2 = $campo2?$campo2:$campo1;
    foreach ($elementos as $elemento)
        if ($elemento) {
            if (is_string($campo1)) {
                $val = valorReal($elemento, $campo1);
            } else {
                $val = '';
                foreach ($campo1 as $sub) {
                    $val .= valorReal($elemento, $sub) . $separador;
                }
            }
            if (is_string($campo2)) {
                $res = valorReal($elemento, $campo2);
            } else {
                $res = '';
                foreach ($campo2 as $sub) {
                    $res .= valorReal($elemento, $sub) . $separador;
                }
            }
            $todos[$val] = $res;
        }
    return $todos;
}

function AuthUser()
{
    return auth()->user();
}

function selfAuth($id){
    if (AuthUser()->id == $id) {
        return true;
    }
    if (AuthUser()->isAdmin() || AuthUser()->isResponsable()) {
        return true;
    }
    return false;
}




