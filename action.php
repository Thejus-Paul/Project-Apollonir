<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, shrink-to-fit=no">
  <meta name="theme-color" content="#18BC9C">
  <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css"/>
	<title> Database Request | IRAPL</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <a class="navbar-brand" href="./index.php">IRAPL</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="./index.php">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./add.html">Add Client</a>
          </li>
        </ul>
      </div>
    </nav>
<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "IRAPL";


$conn = new mysqli($server,$user,$pass,$db);
if($conn) echo "<script>console.log('Connection to Database Successful!')</script>";

// For ID to increment normally (Auto-increment Reset)

  $id_count = 0;
  $sql  = "select id from feedback";
  $result = $conn->query($sql);
  $array = mysqli_fetch_all($result,MYSQLI_ASSOC);
  foreach($array as $arr) $id_count = $arr["id"];
  $sql = "ALTER TABLE feedback AUTO_INCREMENT = ".$id_count;
  if($conn->query($sql)) echo "<script>console.log('Auto_Increment Reset Successful!')</script>";

// Converting to Variables
$org_category = $_POST["org_category"];
$org_name = $_POST["org_name"];
$state = $_POST["state"];
$address = $_POST["address"];
$pincode = $_POST["pincode"];
$website = $_POST["website"];
$org_email = $_POST["org_email"];
$salutation = $_POST["salutation"];
$name = $_POST["name"];
$designation = $_POST["designation"];
$mobile = $_POST["mobile"];
$email = $_POST["email"];
$email2 = $_POST["email2"];
$ccode = $_POST['country_code'];
$acode = $_POST['area_code'];
$landline = $_POST["landline"];
$remarks = $_POST["remarks"];
$total_cash  = $_POST["total_cash"];
$payment_type = $_POST["payment_type"];
$date_of_full_pay = $_POST["date_of_full_pay"];
$date_of_adv_pay = $_POST["date_of_adv_pay"];
$adv_cash_paid = $_POST["adv_cash_paid"];
$pending_pay = 0;
$max = 2;

// Calculating the Pending Pay
if($payment_type == "Advanced") $pending_pay = $total_cash - $adv_cash_paid;

switch($org_category) {
  case "Corporate/Companies/BE": $org_category = "Companies";break;
  case "Educational Institution": $org_category = "Educational_Institution";break;
  case "Study Abroad": $org_category = "Study_Abroad";break;
}

$sql  = "select * from counter where ID = 1";
$result = $conn->query($sql);
$array = mysqli_fetch_all($result,MYSQLI_ASSOC);
foreach($array as $arr) $count = $arr[$org_category];

if($count < $max) {
  $sql = "INSERT INTO feedback (id, org_category, org_name, state, address, pincode, website, org_email, salutation, name, designation, mobile, email, email2, country_code, area_code, landline, remarks, total_cash, payment_type, date_of_full_pay, date_of_adv_pay, adv_cash_paid, pending_pay) 
  VALUES (NULL, '".$_POST["org_category"]."', '".$org_name."', '".$state."', '".$address."', ".$pincode.", '".$website."', '".$org_email."', '".$salutation."', '".$name."', '".$designation."', ".$mobile.", '".$email."','".$email2."', '".$ccode."', '".$acode."', ".$landline.", '".$remarks."', ".$total_cash.", '".$payment_type."', '".$date_of_full_pay."', '".$date_of_adv_pay."', '".$adv_cash_paid."', '".$pending_pay."');";

  if($conn->query($sql)){  
    echo '<br><br><div class="container"><div class="alert alert-success"><strong>Insertion Successful!</strong></div><br>';
    // Counter Incremetation

    $counter = "UPDATE counter SET ".$org_category." = ".$org_category." + 1 WHERE ID = 1";
      if($conn->query($counter))
        echo "<script>console.log('Incremented Counter!');</script>";
      else
        echo $conn->error;
  }
  // Code for displaying the eror
  else echo '<br><div class="container"><div class="alert alert-danger">
  <strong>Error :</strong>'.$conn->error.'</div></div>';
}
else echo '<br><div class="container"><div class="alert alert-danger">
  <strong>Error :</strong> Max limit of entries have been reached. No more entries can be made. Please delete entries from the same category to add more.</div></div>';

echo "<br><div class='container'><a class='btn btn-success' href='index.php'> Go Back</a></div></div>";
$conn->close();
?>
</body>
</html>
