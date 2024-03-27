<?php

////// Write the Database connection code below (Q1)

$servername = "localhost";
$username = "root"; 
$password = ''; 
$database = "crosscache"; 

$link = mysqli_connect($servername, $username, $password, $database);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}


///////// (Q1 Ends)

$operation_val = '';
if (isset($_POST['operation'])) {
    $operation_val = $_POST["operation"];
}

function getId($link) {
    $queryMaxID = "SELECT MAX(id) FROM fooditems;";
    $resultMaxID = mysqli_query($link, $queryMaxID);
    $row = mysqli_fetch_array($resultMaxID, MYSQLI_NUM);
    return $row[0] + 1;
}

if (isset($_POST['updatebtn'])) {
    //// Write PHP Code below to update the record of your database (Hint: Use $_POST) (Q9)
    //// Make sure your code has an echo statement that says "Record Updated" or anything similar or an error message
    // Your PHP code for updating records here

    // Check if the required fields are set
    if (isset($_POST['update_id']) && isset($_POST['update_amount']) && isset($_POST['update_calories'])) {
        // Retrieve the values from the form
        $update_id = $_POST['update_id'];
        $update_amount = $_POST['update_amount'];
        $update_calories = $_POST['update_calories'];

        // Construct the SQL query for updating the record
        $query = "UPDATE fooditems SET amount = '$update_amount', calories = '$update_calories' WHERE id = '$update_id'";

        // Execute the query
        if (mysqli_query($link, $query)) {
            echo "Record Updated";
        } else {
            echo "Error updating record: " . mysqli_error($link);
        }
    } else {
        // Display an error message if the required fields are not set
        echo "Error: ID, amount, and calories must be provided.";
    }
    //// (Q9 Ends)
}

if (isset($_POST['insertbtn'])) {
    //// Write PHP Code below to insert the record into your database (Hint: Use $_POST and the getId() function from line 25, if needed) (Q10)
    //// Make sure your code has an echo statement that says "Record Saved" or anything similar or an error message
    // Your PHP code for inserting records here

    // Check if the required fields are set
    if (isset($_POST['insert_item']) && isset($_POST['insert_amount']) && isset($_POST['insert_unit']) && isset($_POST['insert_calories']) && isset($_POST['insert_protein']) && isset($_POST['insert_carbohydrate']) && isset($_POST['insert_fat'])) {
        // Retrieve the values from the form
        $insert_item = $_POST['insert_item'];
        $insert_amount = $_POST['insert_amount'];
        $insert_unit = $_POST['insert_unit'];
        $insert_calories = $_POST['insert_calories'];
        $insert_protein = $_POST['insert_protein'];
        $insert_carbohydrate = $_POST['insert_carbohydrate'];
        $insert_fat = $_POST['insert_fat'];

        // Construct the SQL query for updating the record
    $query = "UPDATE fooditems SET 
        item = '$insert_item', 
        amount = '$insert_amount', 
        unit = '$insert_unit', 
        calories = '$insert_calories', 
        protein = '$insert_protein', 
        carbohydrate = '$insert_carbohydrate', 
        fat = '$insert_fat' 
        WHERE id = '$update_id'";

        // Execute the query
        if (mysqli_query($link, $query)) {
            echo "Record Saved";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($link);
        }
    } else {
        // Display an error message if the required fields are not set
        echo "Error: All fields must be provided.";
    }
    //// (Q10 Ends)
}

if (isset($_POST['deletebtn'])) {
    //// Write PHP Code below to delete the record from your database (Hint: Use $_POST) (Q11)
    //// Make sure your code has an echo statement that says "Record Deleted" or anything similar or an error message
    // Your PHP code for deleting records here

    // Check if the required field (delete_id) is set
    if (isset($_POST['delete_id'])) {
        // Sanitize the input to prevent SQL injection
        $delete_id = mysqli_real_escape_string($link, $_POST['delete_id']);

        // Construct the SQL query for deleting the record
        $query = "DELETE FROM fooditems WHERE id = '$delete_id'";

        // Execute the query
        if (mysqli_query($link, $query)) {
            echo "Record Deleted";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($link);
        }
    } else {
        // Display an error message if the required field is not set
        echo "Error: Please provide the ID of the record to be deleted.";
    }
    //// (Q11 Ends)
}

?>


<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
    $("#testbtn").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: 'p3.php',
            type: 'POST',
            data: {
                'operation_val': $("#operation_val").val(),
            },
            success: function (data, status) {
                $("#test").html(data)
            }
        });
    });

    $("#insertbtn").click(function (e) {
        e.preventDefault();

        // Serialize form data
        var formData = $('form').serialize();

        // Send form data to the server
        $.ajax({
            url: 'p3.php',
            type: 'POST',
            data: formData,
            success: function (data, status) {
                console.log("Record Saved");
                // Reload the page after inserting a record
                location.reload();
            }
        });
    });
});


    </script>
    <link rel="stylesheet" href="p3.css">
</head>

<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="cars">Choose an operation:</label>
        <select name="operation" id="operation" onchange="this.form.submit()">
            <option value="0" <?php print ($operation_val == 0) ? "selected" : ''; ?>><b>Select Operation</b></option>
            <option value="1" <?php print ($operation_val == 1) ? "selected" : ''; ?>>Show</option>
            <option value="2" <?php print ($operation_val == 2) ? "selected" : ''; ?>>Update</option>
            <option value="3" <?php print ($operation_val == 3) ? "selected" : ''; ?>>Insert</option>
            <option value="4" <?php print ($operation_val == 4) ? "selected" : ''; ?>>Delete</option>
        </select><br><br>
        <?php

        $query = "SELECT * FROM fooditems;";
        if ($operation_val == 1) {
            if ($result = mysqli_query($link, $query)) {
                $fields_num = mysqli_num_fields($result);
                echo "<table class=\"customTable\"><th>";
                for ($i = 0; $i < $fields_num; $i++) {
                    $field = mysqli_fetch_field($result);
                    if ($i > 0) {
                        echo "<th>{$field->name}</th>";
                    } else {
                        echo "id";
                    }
                }
                echo "</th>";
                if ($operation_val == 1) {
                    while ($row = mysqli_fetch_row($result)) {
                        ///// Finish the code for the table below using a loop (Q2)
                        // Your PHP code for displaying records in a table here

                        echo "<tr>";
                        // Loop through each field of the row
                        foreach ($row as $key => $value) {
                            echo "<td>{$value}</td>";
                        }
                        echo "</tr>";

                        ///////////// (Q2 Ends)
                    }
                }
                echo "</table>";
            }
        }

        ?>

        <div id="div_update" runat="server" class=<?php if ($operation_val == 2) {echo "display-block";} else {echo "display-none";} ?>>
            <!--Create an HTML table below to enter ID, amount, and calories in different text boxes. This table is used for updating records in your table. (Q3) --->
            <table>
                <tr>
                    <td>ID:</td>
                    <td><input type="text" name="update_id" id="update_id"></td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td><input type="text" name="update_amount" id="update_amount"></td>
                </tr>
                <tr>
                    <td>Calories:</td>
                    <td><input type="text" name="update_calories" id="update_calories"></td>
                </tr>
            </table>
            <!--(Q3) Ends --->

            <!--Create a button below to submit and update record. Set the name and id of the button to be "updatebtn"(Q4) --->
            <button type="submit" name="updatebtn" id="updatebtn">Update Record</button>
            <!--(Q4) Ends --->

        </div>

        <div id="div_insert" runat="server" class=<?php if ($operation_val == 3) {echo "display-block";} else {echo "display-none";} ?>>
            <!-- Create an HTML table below to enter item, amount, unit, calories, protein, carbohydrate, and fat in different text boxes. This table is used for inserting records in your table. (Q5) -->
            <table>
                <tr>
                    <td>Item:</td>
                    <td><input type="text" name="insert_item" id="insert_item"></td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td><input type="text" name="insert_amount" id="insert_amount"></td>
                </tr>
                <tr>
                    <td>Unit:</td>
                    <td><input type="text" name="insert_unit" id="insert_unit"></td>
                </tr>
                <tr>
                    <td>Calories:</td>
                    <td><input type="text" name="insert_calories" id="insert_calories"></td>
                </tr>
                <tr>
                    <td>Protein:</td>
                    <td><input type="text" name="insert_protein" id="insert_protein"></td>
                </tr>
                <tr>
                    <td>Carbohydrate:</td>
                    <td><input type="text" name="insert_carbohydrate" id="insert_carbohydrate"></td>
                </tr>
                <tr>
                    <td>Fat:</td>
                    <td><input type="text" name="insert_fat" id="insert_fat"></td>
                </tr>
            </table>
            <!-- (Q5) Ends -->

            <!-- Create a button below to submit and insert record. Set the name and id of the button to be "insertbtn" (Q6) -->
            <button type="submit" name="insertbtn" id="insertbtn">Insert Record</button>
            <!-- (Q6) Ends -->
        </div>


        <div id="div_delete" runat="server" class=<?php if ($operation_val == 4) {echo "display-block";} else {echo "display-none";} ?>>
            <!--Create an HTML table below to enter id in a text box. This table is used for deleting records from your table. (Q7) --->
            <table>
                <tr>
                    <td>ID:</td>
                    <td><input type="text" name="delete_id" id="delete_id"></td>
                </tr>
            </table>
            <!--(Q7) Ends--->

            <!--Create a button below to submit and insert record. Set the name and id of the button to be "deletebtn"(Q8) --->
            <button type="submit" name="deletebtn" id="deletebtn">Delete Record</button>
            <!--(Q8) Ends --->
        </div>

    </form>

</body>

</html>
