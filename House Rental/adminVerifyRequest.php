<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) {
        header('Location: adminLogin.php');
        exit;
    }

    function renderUsers($result, $actions, $showHostStatus = true) {
        echo '<table class="listingTable">';
        $columns = '<tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Guest status</th>';
        if ($showHostStatus) {
            $columns .= '<th>Host status</th>';
        }
        $columns .= '<th>Account status</th><th>Options</th></tr>';
        echo $columns;
        while ($user = mysqli_fetch_assoc($result)) {
            $userId = (int)$user['UserID'];
            $guestStatus = $user['AccountStatus'] === 'Active' ? 'Verified' : ($user['GuestVerificationStatus'] ?: $user['AccountStatus']);
            $hostStatus = $user['HostVerificationStatus'] ?: 'Not verified';
            echo '<tr>';
            echo '<td>' . $userId . '</td>';
            echo '<td>' . htmlspecialchars($user['UserName']) . '</td>';
            echo '<td>' . htmlspecialchars($user['UserEmail']) . '</td>';
            echo '<td>' . htmlspecialchars($user['UserPh']) . '</td>';
            echo '<td>' . htmlspecialchars($guestStatus) . '</td>';
            if ($showHostStatus) {
                echo '<td>' . htmlspecialchars($hostStatus) . '</td>';
            }
            echo '<td>' . htmlspecialchars($user['AccountStatus']) . '</td>';
            echo '<td>';
            foreach ($actions as $action) {
                $confirm = isset($action['confirm']) ? " onclick=\"return confirm('" . $action['confirm'] . "');\"" : '';
                echo '<a href="' . $action['file'] . '?uID=' . $userId . '"' . $confirm . '>' . $action['label'] . '</a> ';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    $registeredUsers = mysqli_query($dbConnect, "SELECT * FROM users WHERE AccountStatus = 'Active' ORDER BY UserID");
    $bannedUsers = mysqli_query($dbConnect, "SELECT * FROM users WHERE AccountStatus = 'Banned' ORDER BY UserID");
    $deletedUsers = mysqli_query($dbConnect, "SELECT * FROM users WHERE AccountStatus = 'Deleted' ORDER BY UserID");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>User Accounts</title>
</head>
<body>
    <div class="homeSection">
        <a href="adminMain.php" class="homeBtn"><i class="fas fa-home"></i> Home</a>
    </div>
    <div class="tabs">
        <button class="tabButton" data-tab-target="tab1">Registered Users</button>
        <button class="tabButton" data-tab-target="tab2">Banned Guests</button>
        <button class="tabButton" data-tab-target="tab3">Deleted Accounts</button>

        <div id="tab1" class="tabContent">
            <h2 class="listingTitle scroll">Registered Users</h2><br>
            <?php renderUsers($registeredUsers, array(
                array('label' => 'Ban User', 'file' => 'adminUserBan.php', 'confirm' => 'Ban this user account?'),
                array('label' => 'Delete User', 'file' => 'adminUserDelete.php', 'confirm' => 'Delete this user account?')
            ), true); ?>
        </div>

        <div id="tab2" class="tabContent">
            <h2 class="listingTitle scroll">Banned Guests</h2><br>
            <?php renderUsers($bannedUsers, array(
                array('label' => 'Unban User', 'file' => 'adminUserUnban.php', 'confirm' => 'Unban this user account?')
            ), false); ?>
        </div>

        <div id="tab3" class="tabContent">
            <h2 class="listingTitle scroll">Deleted Accounts</h2><br>
            <?php renderUsers($deletedUsers, array(), false); ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tabButtons = document.querySelectorAll('.tabButton');
            var tabContents = document.querySelectorAll('.tabContent');
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    tabButtons.forEach(function(item) { item.classList.remove('active'); });
                    tabContents.forEach(function(item) { item.classList.remove('active'); });
                    button.classList.add('active');
                    document.getElementById(button.dataset.tabTarget).classList.add('active');
                });
            });
            tabButtons[0].click();
        });
    </script>
</body>
</html>
