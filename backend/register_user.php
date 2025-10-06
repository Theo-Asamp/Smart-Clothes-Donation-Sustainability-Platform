<?php

header("Content-Type: application/json");


try {
    $db = new PDO('sqlite:../database/Clothing_Donations.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]);
    exit();
}

// Get input data (from frontend or Postman)
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data["name"]) &&
    isset($data["email"]) &&
    isset($data["password"]) &&
    isset($data["role"])
) {
    $name = htmlspecialchars($data["name"]);
    $email = htmlspecialchars($data["email"]);
    $password = password_hash($data["password"], PASSWORD_BCRYPT);
    $role = htmlspecialchars($data["role"]);

    
    $validRoles = ["donor", "staff", "admin"];
    if (!in_array($role, $validRoles)) {
        echo json_encode(["success" => false, "message" => "Invalid role."]);
        exit();
    }

    try {
        
        $checkStmt = $db->prepare("SELECT * FROM user WHERE email = :email");
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();

        if ($checkStmt->fetch()) {
            echo json_encode(["success" => false, "message" => "Email already registered."]);
            exit();
        }

       
        $stmt = $db->prepare("INSERT INTO user (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "User registered successfully."]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
}
?>
