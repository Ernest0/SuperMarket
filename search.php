<?php
require_once 'config.php';
$conn = dbConnect();
$conni = dbmsqli();
if (isset($_GET['barCode'])) {
    $data = "%".$_GET['barCode']."%";
    $sql = 'SELECT * FROM products WHERE barcode like ?';
    $stmt = $conn->prepare($sql);
    $results = $stmt->execute(array($data));
    $rows = $stmt->fetchAll();

    if(empty($rows)) {
        echo "<tr>";
            echo "<td><button type='button' class='btn btn-link btn-xs' id='removeButton'>Borrar</button></td>";
            echo "<td colspan='4'>Item not registered</td>";
        echo "</tr>";
    }
    else {
        foreach ($rows as $row) {
        	setlocale(LC_MONETARY, 'es_MX');
            $precio = $_GET['quantity']*$row['price'];
        	$precio = sprintf('%.2f', $precio);
            echo "<tr>";
                echo "<td><button type='button' class='btn btn-link btn-xs' id='removeButton'>Borrar</button></td>";
                echo "<td>".$row['description']."</td>";
                echo "<td>".$_GET['quantity']."</td>";
                echo "<td class='sum'>$ ".$precio."</td>";
            echo "</tr>";
        }
    }
}

if (isset($_GET['keyword'])){
    if ($_GET['keyword'] != " " and $_GET['keyword'] != ""){
        $data = "%".$_GET['keyword']."%";

        $sql = "SELECT * FROM products where (description LIKE '$data' OR barcode LIKE '$data')";
        $stmt = $conn->prepare($sql);
        $results = $stmt->execute(array($data));
        $rows = $stmt->fetchAll();

        if(empty($rows)){
            echo "<tr>";
                //echo "<td><button type='button' class='btn btn-link btn-xs' id='removeButton'>Borrar</button></td>";
                echo "<td colspan='4'>Item not registered</td>";
            echo "</tr>";
        }
        else{
            foreach ($rows as $row) {
                $precio = sprintf('%.2f', $row['price']);
                echo "<tr>";
                    echo "<td>".$row['description']."</td>";
                    echo "<td>$ ".$precio."</td>";
                    echo "<td><button type='button' class='btn btn-link btn-xs' data-toggle='modal' data-target='#myModal' id='editItem'>Editar</button></td>";
                echo "</tr>";
            }
        }
    }
}

if (isset($_GET['description'])){
    $data = "%".$_GET['description']."%";
    $price = "%".$_GET['price']."%";
    $sql = "SELECT * FROM products WHERE (description LIKE '$data' AND price LIKE '$price')";
    $result = mysqli_query($conni, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "" . $row["barcode"];
        }
    } else {
        echo "No item found";
    }
}
/*
if (isset($_GET['getSales'])){

    $sql = 'SELECT * FROM sales';
    $stmt = $conn->prepare($sql);
    $results = $stmt->execute();
    $rows = $stmt->fetchAll();
    if(empty($rows)){
            echo "<tr>";
                //echo "<td><button type='button' class='btn btn-link btn-xs' id='removeButton'>Borrar</button></td>";
                echo "<td colspan='4'>Item not found</td>";
            echo "</tr>";
        }
        else{
            foreach ($rows as $row) {
                $total = sprintf('%.2f', $row['total']);
                echo "<tr>";
                    echo "<td>".$row['description']."</td>";
                    echo "<td>".$row['quantity']."</td>";
                    echo "<td>$ ".$total."</td>";
                    echo "<td>".$row['fecha']."</td>";
                    echo "<td>".$row['barcode']."</td>";
                echo "</tr>";
            }
        }
}
*/
?>