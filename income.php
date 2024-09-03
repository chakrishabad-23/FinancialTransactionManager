<?php
error_reporting(E_ALL);
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "pbook";

$data = new mysqli($host, $user, $password, $db);

if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

if (isset($_POST['add_income'])) {
    $head = $data->real_escape_string($_POST['head']);
    $amount = $data->real_escape_string($_POST['amount']);
    $des = $data->real_escape_string($_POST['des']);
    $date = $data->real_escape_string($_POST['date']);

    $allowed_descriptions = [
        'Fee Receipt', 'Rent', 'Project'
    ];
    if (!in_array($des, $allowed_descriptions)) {
        echo "<script>alert('Invalid description.'); window.location.href = '" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    }

    $stmt = $data->prepare("INSERT INTO income (head, amount, des, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $head, $amount, $des, $date);

    if ($stmt->execute()) {
        $message = "Data uploaded";
    } else {
        $message = "Data not uploaded: " . $stmt->error;
    }

    $stmt->close();
    $data->close();

    echo "<script>
        window.onload = function() {
            alert('$message');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        }
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Income</title>
</head>
<body>
    <h1>Income Table</h1>
    <div class="content">
        <h1>Add Income</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div>
                <label for="head">Income Head</label>
                <input type="text" name="head" id="head" required>
            </div>
            <div>
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div>
                <label for="des">Description</label>
                <select name="des" id="des" required>
                    <option value="">Select Description</option>
                    <option value="Fee Receipt">Fee Receipt</option>
                    <option value="Rent">Rent</option>
                    <option value="Project">Project</option>
                </select>
            </div>
            <div>
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <input type="submit" name="add_income" value="Add Income">
            </div>
        </form>
    </div>
</body>
</html>
