<!-- ZOBAER AHMED -->
 <?php 
    session_start(); 
    if(isset($_SESSION['email'])) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/_variable.css">
    <link rel="stylesheet" href="../assets/css/_global.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet">
</head>

<body>
    <?php
    include_once "../view/header.php";
    include_once "../view/adminSidebar.php";
    include_once "../model/users.php";
    $users = fetchAllUser();
    ?>
    <div class="all-users">
        <div class="all-users-title">
            <h1>ALL USERS</h1>
        </div>
        <div class="all-users-controls">
            <input type="text" id="search-reference"
                placeholder="Search Users">
            <a href="./addUsers.php" class="btn btn-add-user">Add New Users</a>
        </div>
        <div class="all-users-table-div">
            <table class="all-users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th>Status Control</th>
                    </tr>
                </thead>
                <?php if (!empty($users)) { ?>
                    <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td><?= $user['firstName'] . ' ' . $user['lastName'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?php if ($user['role'] !== NULL) {
                                        echo $user['role'];
                                    } else {
                                        echo "No Role Assigned";
                                    } ?>
                                </td>
                                <td class="active">Active</td>
                                <td>
                                    <a href="../controller/userManagementController.php?editUser=<?= $user['user_id']; ?>" class="edit">Edit</a>
                                    <a href="../controller/userManagementController.php?deleteUser=<?= $user['user_id']; ?>"
                                        class="delete"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>

                                </td>
                                <td>
                                    <a href="../controller/userManagementController.php?approveUser=<?= $user['user_id']; ?>" class="approve" 
                                    onclick="return confirm('Are you sure you want to approve this user?');">Approve</a>
                                    <a href="../controller/userManagementController.php?rejectUser=<?= $user['user_id']; ?>" class="reject"
                                    onclick="return confirm('Are you sure you want to reject this user?');">Reject</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php } else { ?>
                    <tbody>
                        <tr>
                            <td colspan="6">No users found.</td>
                        </tr>
                    </tbody>
                <?php } ?>

            </table>
        </div>
    </div>
</body>

</html>

<?php
    } else {
        header("Location: ../view/login.php");
        exit();
    }
?>