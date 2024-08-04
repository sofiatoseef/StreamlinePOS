<?php

$servername = "localhost";
$username = "root";
$password = "sofiatoseef";
$dbname = "streamline_pos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$categories_html = "";
$sql = "SELECT ID, Name FROM Categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $category_ID = $row["ID"];
    $category_name = $row["Name"];
    $categories_html .= '<tr onclick="show('.$category_ID.');"><td height="50px" valign="middle">'.$category_name.'</td></tr>';
    
    
    // find items which belong to this category
    $items_html .= '<div align="center" id="catTable'.$category_ID.'" style="display:none;"><table border="1" width="80%">';
    $sql2 = "SELECT Id, Name, Price FROM Items WHERE Category_id = $category_ID";
    $result2 = $conn->query($sql2);
    
    while($row2 = $result2->fetch_assoc()) {
        
        $item_Id = $row2["Id"];
        $item_Name = $row2["Name"];
        $item_Price = $row2["Price"];
        $item_Price = number_format((float)$item_Price, 2, '.', '');
        
        $items_html .= '<tr height="50px"><td width="80%">'.$item_Name.'</td><td width="20%">£'.$item_Price.'</td></tr>';
        
    }
    
    $items_html .= '</table></div>';
    
    
  }
} 


$conn->close();

?>



<!DOCTYPE html>
<html>
    <head>
        <title>Streamline POS</title>
        
        <script>
            
            function show(category_id) {
                
                var i = 1;
                var how_many_categories = 7;
                
                while (i <= how_many_categories) {

                    if (i == category_id) {
                        
                        document.getElementById("catTable" + i).style.display = "";
                        
                    } else {
                        
                        document.getElementById("catTable" + i).style.display = "none";
                        
                    }
                    
                    
                    i++;
                }
                
            }
            
            
            setTimeout(function () {
              show(1);
            }, 0.5 * 1000); 
            
        </script>
        
    </head>
    <body>
        
        <table width="100%" border="0">
            <tr>
                <td width="20%" valign="top">
                    
                    
                    <table border="1" width="100%">
                        
                        <?php echo($categories_html); ?>
                        
                    </table>
                    
                    
                </td>
                
                
                <td width="60%">
                
                    <?php echo($items_html); ?>
                
                </td>
                
                <td width="20%" valign="top">Order details will go here</td>
                
                
            </tr>
            
        
        </table>
        
    </body>
</html>
