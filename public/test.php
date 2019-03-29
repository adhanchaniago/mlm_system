<?php
$conn = new mysqli("samybot.ckm5vw93pg4g.us-east-1.rds.amazonaws.com", "samybot", "Ilovelu77!", "samybotdb");
//$qry = mysqli_query($conn,"TRUNCATE TABLE colorProduct");
//$qry = mysqli_query($conn,"TRUNCATE TABLE image");
//$qry = mysqli_query($conn,"TRUNCATE TABLE products");exit;
//$sql = mysqli_query($conn,"ALTER TABLE image ADD COLUMN color_id TEXT NULL");

//$qry = mysqli_query($conn,"SELECT * FROM orders  ORDER BY `id` DESC  LIMIT 1");
//while($res = mysqli_fetch_assoc($qry)){
//    echo "<pre>";
//    print_r($res);
//    echo "################################################################";
//}
//exit;
//$qry1 = mysqli_query($conn,"SELECT * FROM image  ORDER BY `id` DESC");
//while($res1 = mysqli_fetch_assoc($qry1)){
//    echo "<pre>";
//    print_r($res1);
//    echo "################################################################";
//}
//$qry2 = mysqli_query($conn,"SELECT * FROM colorProduct");
//while($res2 = mysqli_fetch_assoc($qry2)){
//    echo "<pre>";
//    print_r($res2);
//}
//exit;
//$sql="CREATE TABLE colorProduct (
//id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//ProductId TEXT  NULL,
//Color TEXT NULL,
//main_image TEXT NULL,
//type TEXT NULL,
//StrawInBag TEXT  NULL,
//StrawNormalPrice TEXT NULL,
//StrawDiscountPrice TEXT NULL,
//StrawStock TEXT NULL,
//BagInCarton TEXT  NULL,
//CartonNormalPrice TEXT NULL,
//CartonDiscountPrice TEXT NULL,
//CartonStock TEXT NULL,
//created_at TIMESTAMP NULL,
//updated_at TIMESTAMP NULL,
//deleted_at TIMESTAMP NULL
//)";
//$res=$conn->query($sql);
//exit;

//$sql = "DELETE FROM users WHERE email='test@swd-development.eu'";
//$sql = "UPDATE users
//SET status = 'admin'
//WHERE email='trinkhalme@admin.com';";
//if ($conn->query($sql) === TRUE) {
//    echo "Table galimage created successfully";
//} else {
//    echo "Error creating table: " . $conn->error;
//}
//$data_color =  quantproduct::distinct()->select('color')->whereProductid(37)->get();
//foreach ($data_color as $aaa){
//    echo '<pre>';print_r($aaa);
//}
//$sql = "SHOW COLUMNS FROM rating";
//$sql = "ALTER TABLE rating MODIFY  COLUMN user_id TEXT NULL";
$sql = "SELECT * FROM users ORDER BY id DESC";
//$sql = "SHOW tables";
$result = $conn->query($sql);
////
if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysqli_error();
    exit;
}
////
while ($row = mysqli_fetch_row($result)) {
    echo '<pre>';
    print_r($row);
    echo "Table: {$row[0]}\n";
}
//mysqli_free_result($result);

//$aa="TRUNCATE table subsubcategory";
//$result = $conn->query($aa);
//$seed = str_split('abcdefghijklmnopqrstuvwxyz'
//    .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
//    .'0123456789'); // and any other characters
//shuffle($seed); // probably optional since array_is randomized; this may be redundant
//$rand = '';
//foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
//echo $rand;
//*****************************mail***********************************