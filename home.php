    <?php
    session_start();
    require 'db.php';

    if (!isset($_SESSION['teacher_id'])) {
        header('Location: index.php');
        exit;
    }

    $teacher_id = $_SESSION['teacher_id'];
    $mysqli = getDBConnection();
    $stmt = $mysqli->prepare("SELECT * FROM students WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['subject'], $_POST['marks'])) {
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $marks = $_POST['marks'];

        $stmt = $mysqli->prepare("SELECT * FROM students WHERE teacher_id = ? AND name = ? AND subject = ?");
        $stmt->bind_param("iss", $teacher_id, $name, $subject);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student) {
            $stmt = $mysqli->prepare("UPDATE students SET marks = marks + ? WHERE id = ?");
            $stmt->bind_param("ii", $marks, $student['id']);
            $stmt->execute();
        } else {
            $stmt = $mysqli->prepare("INSERT INTO students (teacher_id, name, subject, marks) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $teacher_id, $name, $subject, $marks);
            $stmt->execute();
        }
        header("Location: home.php");
        exit;
    }
    ?>

<?php include('header.php'); ?>
<div class="container">
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Subject</th>
            <th>Marks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><span class="nameinitial"><?= substr(htmlspecialchars($student['name'][0]), -1) ?></span> <?= htmlspecialchars(ucfirst($student['name'])) ?></td>
            <td><?= htmlspecialchars(ucfirst($student['subject'])) ?></td>
            <td><?= htmlspecialchars($student['marks']) ?></td>
            <td class="action-dropdown">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="actionMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        •••
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionMenuButton">
                        <li><a class="dropdown-item" href="#" onclick="openEditModal(<?= $student['id'] ?>, '<?= htmlspecialchars($student['name']) ?>', '<?= htmlspecialchars($student['subject']) ?>', <?= $student['marks'] ?>)">Edit</a></li>
                        <li>
                            <form action="delete_student.php" method="POST" onsubmit="return confirmDeletion();">
                                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<button class="add-button" data-bs-toggle="modal" data-bs-target="#addStudentModal" >Add</button>
</div>

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="home.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="marks" class="form-label">Marks</label>
                        <input type="number" class="form-control" id="marks" name="marks" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="edit_student.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="edit-subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-marks" class="form-label">Marks</label>
                        <input type="number" class="form-control" id="edit-marks" name="marks" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openEditModal(id, name, subject, marks) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-subject').value = subject;
        document.getElementById('edit-marks').value = marks;
        new bootstrap.Modal(document.getElementById('editStudentModal')).show();
    }
</script>
<script>
function confirmDeletion() {
    return confirm("Are you sure you want to delete this record?");
}
</script>


</body>
</html>
