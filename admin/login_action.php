<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) {
  http_response_code(500);
  echo 'Koneksi database tidak tersedia';
  exit;
}

$username = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

if ($username === '' || $password === '') {
  header('Location: login.php?e=empty');
  exit;
}

try {
  $stmt = $pdo->prepare('SELECT id_admin, username, password, nama_admin FROM admin WHERE username = :u LIMIT 1');
  $stmt->execute([':u' => $username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $ok = false;
  if ($user) {
    // Prefer password_verify, fallback to plain text match if initial data not hashed yet
    if (password_verify($password, $user['password'])) {
      $ok = true;
    }
  }

  if ($ok) {
    $_SESSION['admin_id'] = (int)$user['id_admin'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_name'] = $user['nama_admin'];
    header('Location: dashboard.php');
    exit;
  } else {
    header('Location: login.php?e=invalid');
    exit;
  }
} catch (Exception $e) {
  http_response_code(500);
  echo 'Login error: ' . htmlspecialchars($e->getMessage());
}
