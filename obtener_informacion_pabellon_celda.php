<?php
// Incluir el archivo de conexión a la base de datos
include 'db.php';

if(isset($_POST["query"])){
    $query = $_POST["query"];
    $sql = "SELECT p.nombre AS pabellon, c.numero AS celda FROM internos AS i
            INNER JOIN celdas AS c ON i.celda_id = c.id
            INNER JOIN pabellones AS p ON c.pabellon_id = p.id
            WHERE i.nombre LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pabellon = $row["pabellon"];
        $celda = $row["celda"];
        echo json_encode(array("pabellon" => $pabellon, "celda" => $celda));
    }
}
?>
