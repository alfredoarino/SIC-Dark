<?php


    function set_breadcrumb($migas) {
        //Inicializamos las variables
        $i = 0;         //Contador de los elementos procesados
        $tira = '';     //Resultado de las tira de las migas de pan
        $elementos = count($migas);     //Número de elementos del array
        /*$activo = '';   //Variable para almacenar el texto de activo o no
        $ruta = '';     //Variable que almacena la ruta si la tiene definida
        $nombre = '';   //Variable que almacena el nombre de la miga (página)
        $inicio = '';   //Variable que almacena el texto inicial */

        //Nos recorremos las migas para ir montando el contenido
        foreach ($migas as $miga){
            //Si el elemento es el último, le ponemos el texto de active
            $activo = $i==$elementos-1 ? ' active" style="font-size: 18px;">' : '" style="font-size: 18px;">';
            //Si tiene ruta definida
            $ruta =  $miga['link'] != '' ? '<a href="'. Route($miga['link']) . '">' : '';
            //Nombre
            $nombre = $i!=$elementos-1 ? '<span>'. $miga['name'].'</span></a></li>' : '<a>'.$miga['name'].'</a></li>';
            //Si es el primer elemento tiene otro tratamiento
            $inicio = $i==0 ? '<li class="breadcrumb-item ml-3' : '<li class="breadcrumb-item';
            //Montamos la tira
            $tira = $tira . $inicio. $activo . $ruta . $nombre;
            //Aumentamos el índice
            $i++;
        }
        //Devolvemos al script el resultado de las migas de pan
        echo $tira;

//        Ejemplo de como quedaría
//            echo '<li class="breadcrumb-item ml-3"><a href="'. Route('home') . '"><span>Inicio</span></a></li>'.
//                 '<li class="breadcrumb-item"><a href="'. Route('rrhh') . '"><span>RRHH</span></a></li>'.
//                 '<li class="breadcrumb-item active"><a>Empleados</a></li>';
//        }
    }












