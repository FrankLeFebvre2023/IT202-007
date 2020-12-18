<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$page = 1;
$per_page = 10;
if(isset($_GET["page"])){
    try {
        $page = (int)$_GET["page"];
    }
    catch(Exception $e){

    }
}
//get latest 10 surveys we haven't take
$db = getDB();
$stmt = $db->prepare("SELECT id, name,user_id, visibility FROM Surveys WHERE (SELECT count(1) from Responses where user_id = :id and survey_id = Surveys.id) = 0 order by created desc");
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
$stmt = $db->prepare("SELECT id FROM Surveys WHERE (SELECT count(1) from Responses where user_id = :id and survey_id = Surveys.id)=0 order by RAND() LIMIT 1");
$a = $stmt->execute([":id" => get_user_id()]);
$random = $stmt->fetchALL(PDO::FETCH_ASSOC);
$total_pages = ceil($count/$per_page);
?>
<div class="container-fluid">
    <h3>Surveys (<?php echo $count; ?>)</h3>
    <?php if (isset($results) && $count > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $s): ?>
                <div class="list-group-item">
                    <?php if((!($s["visibility"] == 3) && !(has_role("Admin"))) || (has_role("Admin")) ): ?>
					<div class="row">
                        <div class="col-8"><?php safer_echo($s["name"]); ?></div>
                        <div class="col">
                            <a type="button" class="btn btn-success"
                               href="<?php echo getURL("survey.php?id=" . $s["id"]); ?>">
                                Take Survey
                            </a>
                        </div>
						<div class="col">
							<a type="button" class="btn btn-success" href="<?php echo getURL("view_profile.php?id=" . $s["user_id"]);?>">View Creator's Profile</a>
						</div>
						<?php if(has_role("Admin")): ?>	
							<div class="col">
								<a type="button" class="btn btn-success" href=<?php echo getURL("test_view_survey.php?id=" . $s["id"]); ?>"> View Survey</a>
							</div>
						<?php endif; ?>
                    </div>
					<?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
		<div class = "col">
			<a type="button" class="btn btn-success" href="<?php echo getURL("survey.php?id=".$random["id"]);?>"> Take Random Survey </a>
    <?php else: ?>
        <p>No surveys available</p>
    <?php endif; ?>
	</div>
        <nav aria-label="Surveys">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page-1) < 1?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 0; $i < $total_pages; $i++):?>
                <li class="page-item <?php echo ($page-1) == $i?"active":"";?>"><a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a></li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page) >= $total_pages?"disabled":"";?>">
                    <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
                </li>
            </ul>
        </nav>
</div>
<?php require(__DIR__ . "/partials/flash.php"); ?>