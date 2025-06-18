<html>
<head>
    <title>Respuesta</title>
    <?php
    function obtenercolorfondo($anios){
        $color="white";
        if(is_numeric($anios)){
            $anios=(int)$anios;
            if($anios<=12){
                $color="lightblue";
            }
            elseif($anios>12 && $anios<=19){
                $color="lightgreen";
            }
            else{
                $color="lightgray";
            }
        }
    return $color;
    }
    function mensajeadolescente($nombre,$anios){
        $mensaje = "<p>Por favor, ingrese un número válido en años.</p>";
        if(is_numeric($anios)){
            $anios=(int)$anios;
            if($anios>12 && $anios<20){
                $mensaje = "<p>Usted $nombre es un adolescente.</p>";
            }
            else{
                $mensaje = "<p>Usted $nombre no es un adolescente.</p>";
            }
        }
    return $mensaje;
    }
    $backgroundColor="white";
    if(isset($_REQUEST["anios"])){
        $backgroundColor=obtenercolorfondo($_REQUEST["anios"]);
    }
    ?>
    <style>
        body{
            background-color: <?php echo $backgroundColor;?>;
        }
    </style>
</head>
<body>
<?php
if(isset($_REQUEST["nombre"]) && isset($_REQUEST["anios"])){
    $nombre=$_REQUEST["nombre"];
    $anios=$_REQUEST["anios"];
    echo"<p>Nombre recibido: $nombre</p>";
    echo"<p>Edad recibida: $anios</p>";
    $resultado=obtenercolorfondo($anios);
    echo mensajeadolescente($nombre,$anios);
}
else{
    echo"<p>No se recibieron los datos</p>";
}
?>
</body>
</html>