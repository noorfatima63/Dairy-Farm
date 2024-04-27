<?php
require('conn.php');

// Handle AJAX requests
if (isset($_GET["action"]) && !empty($_GET["action"])) {
    $action = $_GET['action'];
    // Perform the requested action
    switch ($action) {
        case 'getCattleProfiles':
            // Code to retrieve cattle profiles from the database or files
            getCattleProfiles();
            break;
        case 'addCattle':
            // Code to add cattle record to the database
            $result = addCattle();
            echo json_encode($result);
            break;
        case 'updateCattle':
            // Code to update cattle record in the database
            $result = updateCattle();
            echo json_encode($result);
            break;
        case 'deleteCattle':
            // Code to delete cattle record from the database
            $result = deleteCattle();
            echo json_encode($result);
            break;
        case 'updateDiet':
            // Code to update cattle's diet in the database
            $result = updateDiet();
            echo json_encode($result);
            break;
    }
}

function getCattleProfiles()
{
    global $conn;

    $sql = "SELECT * FROM cattle ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $cattleProfiles = array();
        while ($row = $result->fetch_assoc()) {
            $cattleProfiles[] = $row;
        }
        echo json_encode(array('status' => 'success', 'data' => $cattleProfiles));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No cattle profiles found.'));
    }
}

function addCattle()
{
    global $conn;

    // Extract form data
    $name = $_GET['name'];
    $breed = $_GET['breed'];
    $group = $_GET['group'];
    $weight = $_GET['weight'];
    $milkProduction = $_GET['milkProduction'];
    $diet = $_GET['diet'];
    $temperature = $_GET['temperature'];
    $medicalHistory = $_GET['medicalHistory'];
    $price = $_GET['price'];

    // Get current date
    $currentDate = date('Y-m-d');

    // Prepare the SQL statement
    $sql = "INSERT INTO cattle (name, breed, group_name, weight, milk_production, diet, temp, medical_history, price, diet_assigned_date) VALUES ('$name',  '$breed', '$group', $weight, $milkProduction, '$diet', $temperature, '$medicalHistory', $price, '$currentDate')";

    if ($conn->query($sql) === TRUE) {
        return array('status' => 'success', 'message' => 'Cattle record added successfully.');
    } else {
        return array('status' => 'error', 'message' => 'Error adding cattle record: ' . $conn->error);
    }

}


function updateCattle()
{
    global $conn;

    // Extract form data
    $cattleId = $_GET['cattleID'];
    $name = $_GET['name'];
    $breed = $_GET['breed'];
    $group = $_GET['group'];
    $weight = $_GET['weight'];
    $milkProduction = $_GET['milkProduction'];
    $diet = $_GET['diet'];
    $temperature = $_GET['temperature'];
    $medicalHistory = $_GET['medicalHistory'];
    $price = $_GET['price'];

    // Prepare the SQL statement
    $sql = "UPDATE cattle SET name = '$name', breed = '$breed', group_name = '$group', weight = $weight, milk_production = $milkProduction, diet = '$diet', temp = $temperature, medical_history = '$medicalHistory', price = $price WHERE id = $cattleId";

    if ($conn->query($sql) === TRUE) {
        return array('status' => 'success', 'message' => 'Cattle record updated successfully.');
    } else {
        return array('status' => 'error', 'message' => 'Error updating cattle record: ' . $conn->error);
    }


}

function deleteCattle()
{
    global $conn;

    // Extract form data
    $cattleId = $_GET['cattleID'];

    // Prepare the SQL statement
    $sql = "DELETE FROM cattle WHERE id = $cattleId";

    if ($conn->query($sql) === TRUE) {
        return array('status' => 'success', 'message' => 'Cattle record deleted successfully.');
    } else {
        return array('status' => 'error', 'message' => 'Error deleting cattle record: ' . $conn->error);
    }


}

function updateDiet()
{
    global $conn;

    // Extract form data
    $cattleId = $_GET['cattleId'];
    $diet = $_GET['diet'];

    $currentDate = date('Y-m-d');

    // Prepare the SQL statement
    $sql = "UPDATE cattle SET diet = '$diet' ,diet_assigned_date= '$currentDate' WHERE id = $cattleId";

    if ($conn->query($sql) === TRUE) {
        return array('status' => 'success', 'message' => 'Cattle diet updated successfully.');
    } else {
        return array('status' => 'error', 'message' => 'Error updating cattle diet: ' . $conn->error);
    }


}
?>