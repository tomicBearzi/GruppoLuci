<?php
session_start();
include 'db_connection.php';

// Verifica se l'utente è autenticato e ha il ruolo 'Totem'
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$conn = openConnection();
$stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['user_email']);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($role !== 'Secretary') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "tom_mat_db_4";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$today = date('Y-m-d');

// Today's visits
$sql_today = "SELECT * FROM visit WHERE date = '$today'";
$result_today = $conn->query($sql_today);

// Pending visits
$sql_pending = "SELECT * FROM visit WHERE visit_status = 'Pending'";
$result_pending = $conn->query($sql_pending);

// Closest upcoming visit (by date + time)
$sql_closest = "SELECT * FROM visit WHERE date >= CURDATE() ORDER BY date ASC, start_time ASC LIMIT 1";
$result_closest = $conn->query($sql_closest);
$closest_visit = $result_closest->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Secretary Dashboard</title>
  <style>
    body { font-family: sans-serif; margin: 30px; background: #f5f7fa; }
    h1 { color: #333; }

    .tabs { display: flex; margin-bottom: 20px; }
    .tab {
      padding: 10px 20px;
      background: #ddd;
      cursor: pointer;
      border-radius: 10px 10px 0 0;
      margin-right: 5px;
    }
    .tab.active { background: white; border-bottom: 2px solid white; }

    .tab-content {
      display: none;
      background: white;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 0 10px 10px 10px;
    }
    .tab-content.active { display: block; }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f0f0f0; }

    input, button {
      padding: 8px;
      margin-top: 10px;
      font-size: 16px;
    }
    .highlight-box {
      background: #e0f7e9;
      padding: 15px;
      margin-bottom: 20px;
      border-left: 5px solid #38a169;
    }
  </style>
</head>
<body>

<h1>📋 Secretary Visit Dashboard</h1>

<?php if ($closest_visit): ?>
  <div class="highlight-box">
    <strong>Upcoming Visit:</strong><br>
    <b><?php echo $closest_visit['email']; ?></b> on 
    <b><?php echo $closest_visit['date']; ?></b> at 
    <b><?php echo $closest_visit['start_time']; ?></b><br>
    <a href="edit_visit.php?code=<?php echo $closest_visit['code']; ?>">
      <button>✏️ Edit This Visit</button>
    </a>
  </div>
<?php endif; ?>

<div class="tabs">
  <div class="tab active" onclick="switchTab('today')">📅 Today's Visits</div>
  <div class="tab" onclick="switchTab('pending')">❗ Pending</div>
  <div class="tab" onclick="switchTab('search')">🔍 Search</div>
</div>

<div id="today" class="tab-content active">
  <h2>Today's Visits (<?php echo $today; ?>)</h2>
  <table>
    <tr>
      <th>Code</th><th>Email</th><th>Date</th><th>Start</th><th>End</th><th>Status</th><th>Description</th>
    </tr>
    <?php while($row = $result_today->fetch_assoc()): ?>
      <tr>
        <td><a href="edit_visit.php?code=<?php echo $row['code']; ?>"><?php echo $row['code']; ?></a></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['start_time']; ?></td>
        <td><?php echo $row['end_time']; ?></td>
        <td><?php echo $row['visit_status']; ?></td>
        <td><?php echo $row['description']; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="pending" class="tab-content">
  <h2>Visits Pending Confirmation</h2>
  <table>
    <tr>
      <th>Code</th><th>Email</th><th>Date</th><th>Start</th><th>End</th><th>Status</th><th>Description</th>
    </tr>
    <?php while($row = $result_pending->fetch_assoc()): ?>
      <tr>
        <td><a href="edit_visit.php?code=<?php echo $row['code']; ?>"><?php echo $row['code']; ?></a></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td><?php echo $row['start_time']; ?></td>
        <td><?php echo $row['end_time']; ?></td>
        <td><?php echo $row['visit_status']; ?></td>
        <td><?php echo $row['description']; ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="search" class="tab-content">
  <h2>Search Visit by Code</h2>
  <form action="edit_visit.php" method="get">
    <label for="code">Enter Visit Code:</label><br>
    <input type="text" name="code" id="code" required placeholder="e.g. A2M1DK...">
    <button type="submit">🔎 Search & Edit</button>
  </form>
</div>

<script>
  function switchTab(tabId) {
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
    document.querySelector(`.tab[onclick*="${tabId}"]`).classList.add('active');
    document.getElementById(tabId).classList.add('active');
  }
</script>

</body>
</html>

