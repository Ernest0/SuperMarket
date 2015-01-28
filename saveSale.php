<?php
require_once 'config.php';
$conn = dbConnect();
$conni = dbmsqli();

if (isset($_GET['values'])) {
    $data = $_GET['values'];
    //$array = (explode(",", $data));
    //echo "".$array[0];
    $level = 0;
    $index = 0;
    for ($i=0; $i<strlen($data); $i++){
        //echo $data[$i]."<br>";
        if ($data[$i]=="[" and $level==0){
            $level = 1;
        }
        elseif ($data[$i]=="[" and $level==1) {
            $level = 2;
            $index = $i;
        }
        elseif ($data[$i]=="]" and $level==1){
            $level = 0;
        }
        elseif ($data[$i]=="]" and $level==2){
            $substring = substr($data, ++$index, $i-$index);
            $array = (explode(",", $substring));
            
            $description = "%".substr($array[0],1,-1)."%";
            $barcode = 0;

            $sql = "SELECT * FROM products WHERE description like ?";
            $stmt = $conn->prepare($sql);
            $results = $stmt->execute(array($description));
            $rows = $stmt->fetchAll();

            if(empty($rows)) {

            }
            else{
                foreach ($rows as $row) {
                    $barcode = (int) $row['barcode'];
                }
            }

            $description = (string) substr($array[0], 1, -1);
            $quantity = substr($array[1], 1, -1);
            $price = $array[2];

            //echo "--".$description."--".$quantity."--".$price."--".$barcode."<br>";
            
            $sql = "INSERT INTO sales (description, barcode, quantity, total, fecha) 
            VALUES ('$description', $barcode, '$quantity', '$price', NOW())";


            if (mysqli_query($conni, $sql)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            //mysqli_close($conn);

            $level = 1;
        }

    }
}

?>