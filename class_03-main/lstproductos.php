<?php
require_once('codes/conexion.inc');
session_start();

if ($_SESSION["autenticado"] != "SI") {
    header("Location:login.php");
    exit();
}

$CategoryID = $_GET['cod'];
$AuxSql = "SELECT p.ProductID, p.ProductName, p.QuantityPerUnit, p.UnitPrice, s.CompanyName 
           FROM products p 
           JOIN suppliers s ON p.SupplierID = s.SupplierID 
           WHERE p.CategoryID = $CategoryID";

$Regis = mysqli_query($conex,$AuxSql) or die(mysqli_error($conex));
$NunFilas = mysqli_num_rows($Regis);
?>
<!doctype html>
<html lang="en">
<head>
    <?php include_once ('sections/head.inc'); ?>
    <meta http-equiv="refresh" content="180;url=codes/salir.php">
    <title>Productos por Categoría</title>
    <script>
        function insprod() {
            location.href = "insproducto.php?cat=<?php echo $CategoryID; ?>";
        }
    </script>
</head>
<body class="container-fluid">
<header class="row">
    <?php include_once ('sections/header.inc'); ?>
</header>
<main class="row contenido">
    <div class="card tarjeta">
        <div class="card-header">
            <h4 class="card-title">Lista de Productos por Categoría</h4>
        </div>
        <div class="card-body">
            <?php
            echo '<img src="codes/imagen.php?cod='.$CategoryID.'" width="25%"><br><br>';
            if($NunFilas > 0){
                echo '<table class="table table-striped">';
                echo "<thead><tr>
                        <td><strong>ID</strong></td>
                        <td><strong>Nombre</strong></td>
                        <td><strong>Proveedor</strong></td>
                        <td><strong>Precio</strong></td>
                        <td><strong>Cantidad</strong></td>
                        <td colspan='2' align='center'><strong>Acciones</strong></td>
                      </tr></thead><tbody>";

                while($Tupla = mysqli_fetch_assoc($Regis)){
                    echo "<tr>";
                    echo "<td>".$Tupla['ProductID']."</td>";
                    echo "<td>".$Tupla['ProductName']."</td>";
                    echo "<td>".$Tupla['CompanyName']."</td>";
                    echo "<td>".$Tupla['UnitPrice']."</td>";
                    echo "<td>".$Tupla['QuantityPerUnit']."</td>";
                    echo "<td align='center'><a href='modproducto.php?id=".$Tupla['ProductID']."'>Editar</a></td>";
                    echo "<td align='center'><a href='codes/borproducto.php?id=".$Tupla['ProductID']."' onclick=\"return confirm('¿Seguro que desea eliminar este producto?');\">Borrar</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }else{
                echo "<h3>No hay productos disponibles</h3>";
            }
            ?>
            <button type="button" class="btn btn-sm btn-primary" onClick="insprod()">Agregar Producto</button>
        </div>
    </div>
</main>
<footer class="row pie">
    <?php include_once ('sections/foot.inc'); ?>
</footer>
</body>
</html>
<?php
if(isset($Regis)){
    mysqli_free_result($Regis);
}
?>
