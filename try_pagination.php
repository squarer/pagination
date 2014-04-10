<?php
include "pagination.class.php";

$mysqli = new mysqli('localhost', 'user', 'pwd', 'database');
$table = 'table';
$result = $mysqli->query("select * from $table");

if(!$result){
	die("Query to show fields from table failed");
}

$page_size = 10;
$pager = new pagination($result, $page_size);
$result_per_page = $pager->get_result();
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Try Pagination</title>
</head>
<body>
<h1>Table: <?php echo $table ?></h1>
<table border="1">
<tr>
<?php foreach ($result->fetch_fields() as $field): ?>
	<td><?php echo $field->name; ?></td>
<?php endforeach; ?>
</tr>

<?php foreach ($result_per_page as $row): ?>
	<tr>
	<?php foreach ($row as $cell): ?>
		<td><?php echo $cell; ?></td>
	<?php endforeach; ?>
	</tr>
<?php endforeach; ?>

</table>

<?php $pager->render(10) ?>

</body>
</html>