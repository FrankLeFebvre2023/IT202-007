<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT id, survey_id,question_id,answer_id,user_id FROM Responses WHERE  user_id = :id LIMIT 10");
$r = $stmt->execute([":id" => get_user_id()]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching surveys: " . var_export($stmt->errorInfo(), true), "danger");
}
$count = 0;
if (isset($results)) {
    $count = count($results);
}
?>
<div class="container-fluid">
    <h3>Survey Results (<?php echo $count; ?>)</h3>
    <?php if (isset($results) && $count > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $s): ?>
                <div class="list-group-item">
				<div class="row">
				   <div>
                        <div>Survey ID:</div>
                        <div><?php safer_echo($s["survey_id"]); ?></div>
                    </div>
					<div>
                        <div>Question ID:</div>
                        <div><?php safer_echo($s["question_id"]); ?></div>
                    </div>
					<div>
                        <div>Answer ID:</div>
                        <div><?php safer_echo($s["answer_id"]); ?></div>
                    </div>
				</div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No surveys taken yet</p>
    <?php endif; ?>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>