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
        
        $items_html .= '<tr height="50px" onclick="add(\''.$item_Name.'\','.$item_Price.')"><td width="80%">'.$item_Name.'</td><td width="20%">£'.$item_Price.'</td></tr>';
        
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
    
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 50;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        /* Page layout */
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        
        /* Styling for category list */
        td {
            padding: 15px;
            text-align: center;
            cursor: pointer;
            background-color: #e0f7fa;
            border: 1px solid #ccc;
        }
        
        td:hover {
            background-color: #80deea;
        }
        
        /* Order table */
        #orderdetails {
            margin-top: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }
        
        #orderdetails td {
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        
        /* Items table styling */
        .items-table {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ccc;
        }
        
        .items-table td {
            padding: 10px;
            text-align: left;
            background-color: #e8f5e9;
        }
        
        .items-table td:hover {
            background-color: #81c784;
            cursor: pointer;
        }

        /* Title styling */
        h1 {
            text-align: center;
            color: #333;
            padding: 70px;
            background-color: #00796b;
            color: #fff;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            td {
                padding: 8px;
            }
            
            .items-table, #orderdetails {
                width: 100%;
            }
        }
    </style>
    

        <script>
    
    var total = 0;
            
            function show(category_id) {
                
                var i = 1;
                var how_many_categories = 8;
                
                while (i <= how_many_categories) {

                    if (i == category_id) {
                        
                        document.getElementById("catTable" + i).style.display = "";
                        
                    } else {
                        
                        document.getElementById("catTable" + i).style.display = "none";
                        
                    }
                    
                    
                    i++;
                }
                
                if (category_id == 8) { // when submit order is pressed
                    
                    document.getElementById("change").innerHTML = "0.00";
                    document.getElementById("tenderbox").value = "";
                    
                    document.getElementById("form_total").value = total;
                    
var table = document.getElementById("orderdetails");
var orderdata = "";
for (var i = 1, row; row = table.rows[i]; i++) { // skip the first row
   //iterate through rows
   //rows would be accessed using the "row" variable assigned in the for loop
   for (var j = 0, col; col = row.cells[j]; j++) {
     //iterate through columns
     //columns would be accessed using the "col" variable assigned in the for loop
     orderdata += col.innerHTML + "\n";
   }  
}

                    document.getElementById("form_orderdata").value = orderdata;
                    
                }
                
            }
            
            
            setTimeout(function () {
              show(1);
            }, 0.5 * 1000); 
            
            
            function add(item,price) {
                
// Find a <table> element with id="myTable":
var table = document.getElementById("orderdetails");

// Create an empty <tr> element and add it to the last position of the table:
var row = table.insertRow(-1);

// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);

// Add some text to the new cells:
cell1.innerHTML = item;
cell2.innerHTML = "£" + price.toFixed(2);

total = total + price;
document.getElementById("totalPrice").innerHTML = total.toFixed(2);

document.getElementById("totalPrice2").innerHTML = total.toFixed(2);
                
            }
            
            function tender(val) {
                
                document.getElementById("change").innerHTML = (val - total).toFixed(2);
                
                
            }
            
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
                
                
                <td width="60%" valign="top">
                
                    <?php echo($items_html); ?>
                    
<div align="center" id="catTable8" style="display:none;">
<form action="submit.php" method="post">
    <input type="hidden" value="" id="form_total" name="form_total"/>
    <input type="hidden" value="" id="form_orderdata" name="form_orderdata"/>
    
    <table border="1" width="80%">
        
    <tr>
    <td>Total </td><td> £<span id="totalPrice2">0.00</span> </td>
    </tr>
        
    <tr>
    <td>Tender </td><td> <input type="text" size="10" id="tenderbox" onkeyup="tender(this.value)" style="text-align:center;"> </td>
    </tr>
    
    <tr>
    <td>Change </td><td> £<span id="change">0.00</span></td>
    </tr>

    <tr>
    <td> </td><td> <input type="submit" value="Submit To Database"/> </td>
    </tr>
    
    </table>
</form>
</div>
                
                </td>
                
                <td width="20%" valign="top">
                
                                    <table id="orderdetails" border="1" width="100%">
                        
                                        <tr>
                                            <td>Your Order</td><td>Price</td>
                                        </tr>
                        
                                    </table>
                                    
                                    <table id="totalTable" border="1" width="100%">
                        
                                        <tr>
                                            <td>Total £<span id="totalPrice">0.00</span></td>
                                        </tr>
                                        
                                        <tr onclick="show(8)">
                                            <td>Submit Order</td>
                                        </tr>
                        
                                    </table>
                
                </td>
     
    
                
            </tr>
            
        
        </table>
        
    </body>
</html>
