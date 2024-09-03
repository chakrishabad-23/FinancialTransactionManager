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

if (isset($_POST['add_expense'])) {
    $exp = $data->real_escape_string($_POST['exp']);
    $amount = $data->real_escape_string($_POST['amount']);
    $des = $data->real_escape_string($_POST['des']);
    $date = $data->real_escape_string($_POST['date']);

    $allowed_descriptions = [
        'Rent', 'Bill', 'EMI', 'Tax', 'Fees', 'Interest',
        'Salary', 'Stationary', 'Printing', 'ADs'
    ];
    if (!in_array($des, $allowed_descriptions)) {
        echo "Invalid description.";
        exit();
    }

    $stmt = $data->prepare("INSERT INTO expense (exp, amount, des, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $exp, $amount, $des, $date);

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
    <title>Add Expense</title>
</head>
<body>
    <h1>Expense Table</h1>
    <div class="content">
        <h1>Add Expense</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div>
                <label for="exp">Expense Head</label>
                <input type="text" name="exp" id="exp" required>
            </div>
            <div>
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div>
                <label for="des">Description</label>
                <select name="des" id="des" required>
                    <option value="">Select Description</option>
                    <option value="Rent">Rent</option>
                    <option value="Bill">Bill</option>
                    <option value="EMI">EMI</option>
                    <option value="Tax">Tax</option>
                    <option value="Fees">Fees</option>
                    <option value="Interest">Interest</option>
                    <option value="Salary">Salary</option>
                    <option value="Stationary">Stationary</option>
                    <option value="Printing">Printing</option>
                    <option value="ADs">ADs</option>
                </select>
            </div>
            <div>
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <input type="submit" name="add_expense" value="Add Expense">
            </div>
        </form>
    </div>
</body>
</html>
