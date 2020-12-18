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
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id,username,email,privacy FROM Users where id = :id");
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
            <div> Username: <?php safer_echo($result["username"]); ?></div>
        </div>
        <div class="card-body">
            <div>
                <?php if($result["privacy"] == 1) :?>
					<div>Email: <?php safer_echo($result["email"]); ?> </div>
				<?php endif; ?>
            </div>
			<div>
				<a type="button" href="my_surveys.php?id=<?php safer_echo($result['id']); ?>">View User's Surveys</a>
			</div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php");