<?php
//index.php
$connect = mysqli_connect("localhost", "root", "", "daily_product");
$message = '';

if(isset($_POST["upload"]))
{
 if($_FILES['product_file']['name'])
 {
  $filename = explode(".", $_FILES['product_file']['name']);
  if(end($filename) == "csv")
  {
   $handle = fopen($_FILES['product_file']['tmp_name'], "r");
   while($data = fgetcsv($handle))
   {
    $product_id = mysqli_real_escape_string($connect, $data[0]);
    $product_category = mysqli_real_escape_string($connect, $data[1]);  
                $product_name = mysqli_real_escape_string($connect, $data[2]);
    $product_price = mysqli_real_escape_string($connect, $data[3]);
    $query = "
     UPDATE daily_product 
     SET product_category = '$product_category', 
     product_name = '$product_name', 
     product_price = '$product_price' 
     WHERE product_id = '$product_id'
    ";
    mysqli_query($connect, $query);
   }
   fclose($handle);
   header("location: index.php?updation=1");
  }
  else
  {
   $message = '<label class="text-danger">Please Select CSV File of Your Products</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">Please Select Any CSV File</label>';
 }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">Product Updation Completed Successfully !!!</label>';
}

$query = "SELECT * FROM daily_product";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html>
 <head>
  <title>Web Application to Obtain Database through Uploading CSV File</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <h1 align="center">Grootan Technologies Coding Assignment<br> Web Application to Obtain Database through Uploading CSV File</a></h1><br /><br />
<h4 align="left">Welcome, to Process your Data Files into Database Tables :) </h4>
   <br />
   <form method="post" enctype='multipart/form-data'>
    <p><label>Kindly Select Your Products Data File (Only CSV Format)</label><br>
    <input type="file" name="product_file" /></p>
    <br />
    <input type="submit" name="upload" class="btn btn-info" value="Upload CSV File" />
   </form>
   <br />
   <?php echo $message; ?>
   <h3 align="center">Products Database for the Provided CSV File</h3>
   <br />
   <div class="table-responsive">
    <table class="table table-bordered table-striped">
     <tr>
      <th>Category</th>
      <th>Product Name</th>
      <th>Product Price</th>
     </tr>
     <?php
     while($row = mysqli_fetch_array($result))
     {
      echo '
      <tr>
       <td>'.$row["product_category"].'</td>
       <td>'.$row["product_name"].'</td>
       <td>'.$row["product_price"].'</td>
      </tr>
      ';
     }
     ?>
    </table>
   </div>
  </div>
 </body>
</html>
