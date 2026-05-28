<?php
session_start();
include "../config/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
  echo json_encode(["error" => "Not logged in"]);
  exit;
}

$user_id = $_SESSION['user_id'];
$name = strtolower(trim($_POST['name'] ?? ''));
$desc = strtolower(trim($_POST['desc'] ?? ''));

if ($desc === '') {
  echo json_encode(["error" => "Description required"]);
  exit;
}

/*
  STEP 1: Extract quantity
  "3 idli" → 3
*/
preg_match_all('/\d+/', $desc, $nums);
$quantity = array_sum($nums[0]);
if ($quantity <= 0) $quantity = 1;

/*
  STEP 2: Fetch ALL food items
*/
$foods = [];
$res = mysqli_query($conn, "SELECT * FROM food_master");

while ($row = mysqli_fetch_assoc($res)) {
  $foods[] = $row;
}

/*
  STEP 3: Match words & calculate
*/
$totalCalories = 0;
$totalProtein = 0;

foreach ($foods as $food) {
  if (strpos($desc, strtolower($food['food_name'])) !== false) {
    $totalCalories += $food['calories_per_unit'] * $quantity;
    $totalProtein  += $food['protein_per_unit'] * $quantity;
  }
}

if ($totalCalories <= 0) {
  echo json_encode(["error" => "Food items not recognized"]);
  exit;
}

/*
  STEP 4: Save meal
*/
$today = date('Y-m-d');

$insert = "INSERT INTO meals
(user_id, meal_name, description, calories, protein, meal_date)
VALUES
('$user_id', '$name', '$desc', '$totalCalories', '$totalProtein', '$today')";

mysqli_query($conn, $insert);

/*
  SUCCESS
*/
echo json_encode([
  "calories" => $totalCalories,
  "protein"  => round($totalProtein, 1)
]);
