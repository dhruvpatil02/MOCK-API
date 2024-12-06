
<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
header('Content-Type: application/json; charset=utf-8');

// Replace these with your database credentials
$host = "localhost";
$username = "<replate username>";
$password = "<password>";
$database = "<db>";

// Establish database connection
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $endpoint = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
    $select_query = "SELECT id,json_data FROM user_data WHERE endpoint = ? ORDER BY id DESC";
    $stmt = $mysqli->prepare($select_query);
    $stmt->bind_param("s", $endpoint);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            //$data[] = $row;
            $decoded_data = json_decode($row['json_data'], true);
            $decoded_data = array_merge(['_id' => $row['id']], $decoded_data);
            $data[] = $decoded_data;

        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'No data found at this endpoint'));
    }

    $stmt->close();
    die();
}
///check if data is valid json
if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false ) {

// Check if JSON data is sent via POST, PUT, or DELETE request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json_data = json_decode(file_get_contents("php://input"), true);
    $jsonInput = file_get_contents('php://input');
    $jsonData = json_decode(file_get_contents('php://input'), true);
        
        // Check for JSON decoding errors
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid JSON data.'));
            exit;
        }

// Decode JSON string to an associative array
$data = json_decode($jsonInput, true);

// Check if $data is an array
if (!is_array($data) || empty($data) || !is_array($data[0])) {
    // Wrap the associative array in another array
   // echo "Data was single";
    $json_data = [$json_data];
} 
    
    
    // Extract endpoint and ID from the URL path
    $endpoint = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
    $id = isset($json_data['id']) ? $json_data['id'] : null;

    if ($id === null) {
        // If ID is not provided, treat it as a POST request (insert new data)
        foreach ($json_data as $single_json_data) {
            $insert_query = "INSERT INTO user_data (endpoint, json_data) VALUES (?, ?)";
            $stmt = $mysqli->prepare($insert_query);
            $stmt->bind_param("ss", $endpoint, json_encode($single_json_data));
            $stmt->execute();
            $stmt->close();
        }

        echo json_encode(array('success' => 'Data saved successfully'));
    } else {
        // If ID is provided, treat it as a PUT or DELETE request
        if ($_SERVER["REQUEST_METHOD"] === "PUT") {
             // If ID is provided, treat it as a PUT request (update existing data)
        foreach ($json_data as $single_json_data) {
        $update_id = isset($single_json_data['id']) ? $single_json_data['id'] : null;

        if ($update_id !== null) {
            // Check if the record with the given ID already exists
            $check_existing_query = "SELECT id FROM user_data WHERE id = ?";
            $stmt_check_existing = $mysqli->prepare($check_existing_query);
            $stmt_check_existing->bind_param("i", $update_id);
            $stmt_check_existing->execute();
            $stmt_check_existing->store_result();

            if ($stmt_check_existing->num_rows > 0) {
                // Retrieve existing JSON data from the database
                $select_query = "SELECT json_data FROM user_data WHERE id = ?";
                $stmt_select = $mysqli->prepare($select_query);
                $stmt_select->bind_param("i", $update_id);
                $stmt_select->execute();
                $stmt_select->bind_result($existing_json_data);
                $stmt_select->fetch();
                $stmt_select->close();

                // If data with the given ID exists, update it
                $existing_data = json_decode($existing_json_data, true);

                // Merge the existing data with the current JSON element
                $merged_data = array_merge($existing_data, $single_json_data);

                // Update the data in the database
                $update_query = "UPDATE user_data SET json_data = ? WHERE id = ?";
                $stmt_update = $mysqli->prepare($update_query);
                $stmt_update->bind_param("si", json_encode($merged_data), $update_id);
                $stmt_update->execute();
                $stmt_update->close();

                echo "Data with ID $update_id successfully updated!\n";
            } else {
                echo "No data found for the specified ID $update_id.\n";
            }

            $stmt_check_existing->close();
        } else {
            echo "ID is required for updating data.\n";
        }
    }

        } elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
            echo "dscrecre3c 43c xdf fg";
			//echo $_GET["q"];
            // Check if $data is an array
            if (!is_array($data) || empty($data) || !is_array($data[0])) {
                // Wrap the associative array in another array
            // echo "Data was single";
                $json_data = [$json_data];
            } 
                $json_data = json_decode(file_get_contents("php://input"), true);

            foreach ($json_data as $single_json_data) {
            echo $single_json_data;
            }
            // Delete data from the database based on the provided ID
            $delete_query = "DELETE FROM user_data WHERE id = ?";
            $stmt_delete = $mysqli->prepare($delete_query);
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            $stmt_delete->close();        

            echo "Data successfully deleted!";
        }
    }
}
        if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
			//echo $_GET["q"];
                $data = json_decode(file_get_contents("php://input"), true);
                $json_data = json_decode(file_get_contents("php://input"), true);

                // Check if $data is an array
                if (!is_array($data) || empty($data) || !is_array($data[0])) {
                    // Wrap the associative array in another array
                // echo "Data was single";
                    $json_data = [$json_data];
                } 
            foreach ($json_data as $single_json_data) {
           echo json_encode($single_json_data);
          $id = isset($single_json_data['id']) ? $single_json_data['id'] : null;
                  echo $delete_query = "DELETE FROM user_data WHERE id = ?";
            $stmt_delete = $mysqli->prepare($delete_query);
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            $stmt_delete->close();        

           // echo json_encode("Entry Deleted"=>$id);

            }
            // Delete data from the database based on the provided ID
            
        }


 if ($_SERVER["REQUEST_METHOD"] === "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);
        $json_data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data) || empty($data) || !is_array($data[0])) {
                    // Wrap the associative array in another array
                // echo "Data was single";
                    $json_data = [$json_data];
                } 
    // Extract endpoint and ID from the URL path
    $endpoint = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');    
    $id = isset($json_data['id']) ? $json_data['id'] : null;
             // If ID is provided, treat it as a PUT request (update existing data)
        foreach ($json_data as $single_json_data) {
        $update_id = isset($single_json_data['id']) ? $single_json_data['id'] : null;

        if ($update_id !== null) {
            // Check if the record with the given ID already exists
            $check_existing_query = "SELECT id FROM user_data WHERE id = ?";
            $stmt_check_existing = $mysqli->prepare($check_existing_query);
            $stmt_check_existing->bind_param("i", $update_id);
            $stmt_check_existing->execute();
            $stmt_check_existing->store_result();

            if ($stmt_check_existing->num_rows > 0) {
                // Retrieve existing JSON data from the database
                $select_query = "SELECT json_data FROM user_data WHERE id = ?";
                $stmt_select = $mysqli->prepare($select_query);
                $stmt_select->bind_param("i", $update_id);
                $stmt_select->execute();
                $stmt_select->bind_result($existing_json_data);
                $stmt_select->fetch();
                $stmt_select->close();

                // If data with the given ID exists, update it
                $existing_data = json_decode($existing_json_data, true);

                // Merge the existing data with the current JSON element
                $merged_data = array_merge($existing_data, $single_json_data);

                // Update the data in the database
                $update_query = "UPDATE user_data SET json_data = ? WHERE id = ?";
                $stmt_update = $mysqli->prepare($update_query);
                $stmt_update->bind_param("si", json_encode($merged_data), $update_id);
                $stmt_update->execute();
                $stmt_update->close();

                echo "Data with ID $update_id successfully updated!\n";
            } else {
                echo "No data found for the specified ID $update_id.\n";
            }

            $stmt_check_existing->close();
        } else {
            echo "ID is required for updating data.\n";
        }
    }

        }

}else{
    die("Only json data is accepted");
}
// Endpoint to retrieve data based on the requested endpoint

// Close the database connection
$mysqli->close();
?>

