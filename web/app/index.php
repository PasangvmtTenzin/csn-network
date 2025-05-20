<?php
require_once 'db_connect.php';

$message = '';
$error = '';

// Handle form submission for new note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notes (title, content) VALUES (:title, :content)");
            $stmt->execute(['title' => $title, 'content' => $content]);
            $message = "Note added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding note: " . $e->getMessage();
        }
    } else {
        $error = "Title cannot be empty.";
    }
}

// Handle note deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $note_id = filter_input(INPUT_POST, 'note_id', FILTER_VALIDATE_INT);
    if ($note_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id");
            $stmt->execute(['id' => $note_id]);
            $message = "Note deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting note: " . $e->getMessage();
        }
    }
}


// Fetch all notes
$notes = [];
try {
    $stmt = $pdo->query("SELECT id, title, content, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as formatted_created_at FROM notes ORDER BY created_at DESC");
    $notes = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching notes: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSN Notes App - web.csn.local</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>CSN Enterprise Notes</h1>
        <p>Welcome to <strong>web.csn.local</strong>. Hostname: <?php echo gethostname(); ?></p>

        <?php if ($message): ?>
            <p class="message success"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="message error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="form-container">
            <h2>Add New Note</h2>
            <form action="index.php" method="POST">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="4"></textarea>
                </div>
                <button type="submit" name="add_note">Add Note</button>
            </form>
        </div>

        <div class="notes-container">
            <h2>Existing Notes</h2>
            <?php if (count($notes) > 0): ?>
                <ul>
                    <?php foreach ($notes as $note): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                            <small>Created: <?php echo $note['formatted_created_at']; ?></small>
                            <form action="index.php" method="POST" style="display:inline; margin-left: 10px;">
                                <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                <button type="submit" name="delete_note" class="delete-button" onclick="return confirm('Are you sure you want to delete this note?');">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No notes yet. Add one above!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>