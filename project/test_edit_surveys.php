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
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$name = $_POST["name"];
	$description = $_POST["description"];
	$visibility = $_POST["visibility"];
	$created = date('Y-m-d H:i:s');
	$modified = date('Y-m-d H:i:s');
	$user = get_user_id();
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE Surveys set name=:name, description=:description, visibility=:visibility, created=:created, modified=:modified,user_id =:user where id=:id");
		//$stmt = $db->prepare("INSERT INTO Survey (name, state, base_rate, mod_min, mod_max, next_stage_time, user_id) VALUES(:name, :state, :br, :min,:max,:nst,:user)");
		$r = $stmt->execute([
			":name"=>$name,
			":description"=>$description,
			":visibility"=>$visibility,
			":created"=>$created,
			":modified"=>$modified,
			":user"=>$user,
			"id"=>$id
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Surveys where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">
	<label>Title</label>
	<input name="name" placeholder="Title" value="<?php echo $result["name"];?>"/>
	<label>Description</label>
	<input name="description" placeholder="Description" value="<?php echo $result["description"];?>"/>
	<label>Visibility</label>
	<select name="visibility" value="<?php echo $result["visibility"];?>">
		<option value="0" <?php echo ($result["visibility"] == "0"?'selected="selected"':'');?>>Draft</option>
                <option value="1" <?php echo ($result["state"] == "1"?'selected="selected"':'');?>>Private</option>
                <option value="2" <?php echo ($result["state"] == "2"?'selected="selected"':'');?>>Public</option>
	</select>
	<input type="submit" name="save" value="Update"/>
</form>


<?php require(__DIR__ . "/partials/flash.php");