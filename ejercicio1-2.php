<?php
    function descuento($anios){
        $entrada=100;
        $devuelve=0;
        if(is_numeric($anios)){
            $anios=(int)$anios;
            if($anios<20){
                $devuelve=$entrada*10/100;
            }
            elseif($anios>=20 && $anios<50){
                $devuelve=0;
            }
            else{
                $devuelve=$entrada*5/100;
            }
        $devuelve=$entrada-$devuelve;
        }
        return $devuelve;
    }
    function obtenercolorfondo($anios){
        $color="white";
        if(is_numeric($anios)){
            $anios=(int)$anios;
            if($anios<20){
                $color="lightblue";
            }
            elseif($anios>=20 && $anios<50){
                $color="lightgreen";
            }
            else{
                $color="lightgray";
            }
        }
    return $color;
    }
    function mensajedescuentos($nombre,$anios){
        $mensaje = "<p>Por favor, ingrese un número válido en años.</p>";
        if(is_numeric($anios)){
            $anios=(int)$anios;
            if($anios>=20 && $anios<50){
                $mensaje = "<p>Usted $nombre no tiene descuentos.</p>";
            }
            elseif($anios>=50){
                $mensaje = "<p>Usted $nombre tiene un 5% de descuento.</p>";
            }
            else{
                $mensaje="<p> Usted $nombre tiene un 10% de descuento</p>";
            }
        }
    return $mensaje;
    }
    $backgroundColor="white";
    if(isset($_REQUEST["anios"])){
        $backgroundColor=obtenercolorfondo($_REQUEST["anios"]);
    }
    ?>

<html>
<head>
    <title>DESCUENTOS</title>
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
    echo mensajedescuentos($nombre,$anios);
    $entradatotal=descuento($anios);
    echo "<p>El valor de tu entrada es: $entradatotal</p>";
}
else{
    echo"<p>No se recibieron los datos</p>";
}
?>
</body>
</html>