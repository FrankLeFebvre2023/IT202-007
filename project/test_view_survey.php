<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
if(isset($_POST["saved"])){
	$db = getDB();
	$newVisibility = 3;
	$stmt = $db->prepare("UPDATE Surveys set visibility = :visibility where id = :id");
	$r = $stmt->execute([":visibility" => $newVisibility, ":id" => $id]);
	if ($r) {
            flash("Survey Deactivated");
    }
    else {
            flash("Error: Could not deactivate survey!");
    }
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Surveys.id,name,description,visibility, user_id, Users.username FROM Surveys as Surveys JOIN Users on Surveys.user_id = Users.id where Surveys.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-name">
            <?php safer_echo($result["name"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Stats</p>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Visibility: <?php getState($result["visibility"]); ?></div>
                <div>Owned by: <?php safer_echo($result["username"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<form method="POST">
	<input type="submit" name="saved" value="Deactivate Survey"/>
</form>
<?php require(__DIR__ . "/partials/flash.php");