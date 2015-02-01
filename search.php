<?php
require_once 'config.php';
$conn = dbConnect();
$conni = dbmsqli();
if (isset($_GET['barCode'])) {
    $data = $_GET['barCode'];
    $sql = "SELECT * FROM products WHERE barcode = '$data'";
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
                echo "<td colspan='4'>Producto no encontrado</td>";
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
            echo $row["id"]."-".$row["barcode"];
        }
    } else {
        echo "No item found";
    }
}

//---save changes of item------
if (isset($_GET['id'])){
    $id = $_GET['id'];
    $description = $_GET['newdescription'];
    $price = $_GET['newprice'];
    $code = $_GET['newcode'];

    $sql = "UPDATE products SET description='$description', price='$price', barcode='$code' WHERE id='$id'";

    if (mysqli_query($conni, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conni);
    }

    mysqli_close($conni);
}

//-------register new item----------
if (isset($_GET['registerdescription'])){
    $description = $_GET['registerdescription'];
    $price = $_GET['registerprice'];
    $code = $_GET['registercode'];

    $sql = "INSERT INTO products (description, price, barcode)
    VALUES ('$description', '$price', '$code')";

    if (mysqli_query($conni, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conni);
    }

    mysqli_close($conni);
}

//------delete product------------
if (isset($_GET['deleteid'])){
    $id = $_GET['deleteid'];

    // sql to delete a record
    $sql = "DELETE FROM products WHERE id='$id'";

    if (mysqli_query($conni, $sql)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conni);
    }

    mysqli_close($conni);
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