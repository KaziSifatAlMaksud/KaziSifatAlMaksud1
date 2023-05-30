<?php
include 'components/connect.php';

if (isset($_POST['send'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Sanitize the form inputs
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $subject = filter_var($subject, FILTER_SANITIZE_STRING);
  $message = filter_var($message, FILTER_SANITIZE_STRING);

  // Prepare and execute the SQL query to check if the message already exists
  $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND subject = ? AND message = ?");
  $select_message->execute(array($name, $email, $subject, $message));

  if ($select_message->rowCount() > 0) {
    $message = 'Already sent message!';
  } else {
    // Prepare and execute the SQL query to insert the message into the database
    $insert_message = $conn->prepare("INSERT INTO `messages` (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $insert_message->execute(array($name, $email, $subject, $message));

    $message = 'Sent message successfully!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Submission</title>
</head>

<body>
  <div class="message">
    <?php if (!empty($message)) : ?>
      <p><?php echo $message; ?></p>
    <?php endif; ?>
  </div>
</body>

</html>
