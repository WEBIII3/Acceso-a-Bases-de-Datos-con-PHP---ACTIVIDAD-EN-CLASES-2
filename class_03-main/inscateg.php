<?php
require_once('codes/conexion.inc');
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location:login.php");
    exit();
}

if ((isset($_POST["OC_insertar"])) && ($_POST["OC_insertar"] == "formita")) {

    // Inserta nombre y descripción primero
    $auxSql = sprintf("INSERT INTO categories(CategoryName, Description) VALUES('%s', '%s')",
        $_POST['txtNombre'],
        $_POST['txtDescrip']
    );

    $Regis = mysqli_query($conex, $auxSql) or die(mysqli_error($conex));

    // Obtener el ID recién insertado
    $newID = mysqli_insert_id($conex);

    // Procesar imagen si se subió
    $archivo = $_FILES["txtArchi"]["tmp_name"];
    $tamanio = $_FILES["txtArchi"]["size"];
    $tipo    = $_FILES["txtArchi"]["type"];
    $nombre  = $_FILES["txtArchi"]["name"];

    if ($archivo != "") {
        $archi = fopen($archivo, "rb");
        $contenido = fread($archi, $tamanio);
        $contenido = addslashes($contenido);
        fclose($archi);

        $AuxSql = "UPDATE categories SET Imagen='$contenido', Mime='$tipo' WHERE CategoryID = $newID";
        mysqli_query($conex, $AuxSql) or die(mysqli_error($conex));
    }

    header("Location: categories.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php include_once('sections/head.inc'); ?>
    <meta http-equiv="refresh" content="180;url=codes/salir.php">
    <title>Insertar Categoría</title>
</head>
<body class="container-fluid">
<header class="row">
    <?php include_once('sections/header.inc'); ?>
</header>
<main class="row contenido">
    <div class="card tarjeta">
        <div class="card-header">
            <h4 class="card-title">Insertar Categoría</h4>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" name="formita" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Nombre</strong></td>
                        <td><input type="text" name="txtNombre" size="15" maxlength="15" required></td>
                    </tr>
                    <tr>
                        <td><strong>Descripción</strong></td>
                        <td><input type="text" name="txtDescrip" size="50" maxlength="50" required></td>
                    </tr>
                    <tr>
                        <td><strong>Imagen</strong></td>
                        <td><input type="file" name="txtArchi" id="txtArchi" accept="image/*"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" value="Aceptar" class="btn btn-primary">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="OC_insertar" value="formita">
            </form>
        </div>
    </div>
</main>
<footer class="row pie">
    <?php include_once('sections/foot.inc'); ?>
</footer>
</body>
</html>
