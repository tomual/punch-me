<!DOCTYPE html>

<html>
<head>
	<style type="text/css">	
	body {
		font-family: monospace;
		font-size: 14px;
		background: #99B2B7;
		color: #7A6A53;
	}
	h1 {
		font-size: 16px;
		height: 30px;
		letter-spacing: 2px;
		font-weight: normal;
		padding: 0;
		margin: 0;
		margin-top: 25px;
		padding-top: 10px;
		text-align: center;
		color: #7A6A53;
		border-top: 1px solid #948C75;
		border-bottom: 1px solid #948C75;
	}
	.left {
		width: 35px;
		height: 370px;
		float: left;
		border-left: 1px solid #948C75;
		border-right: 1px solid #948C75;
		margin: 0;
		margin-left: 10px;
		text-align: center;
		line-height: 42px;
		height: 400px;
	}
	.middle	{
		width: 725px;
		height: 370px;
		display: inline-block;
		float: left;
	}
	.right {
		background: #D9CEB2;
		position: absolute;
		width: 200px;
		height: 400px;
		display: inline-block;
		float: right;
		border-left: 1px solid #948C75;
		border-right: 1px solid #948C75;
	}
	section {
		margin-top: 200px;
		background: #D9CEB2;
		border-radius: 20px;
		box-shadow: 3px 3px 0 #899FA4;
		padding: 0;
		overflow: hidden;
		width: 1000px;
		height: 410px;
		margin: auto;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-top: -205px;
		margin-left: -500px;
	}
	.puncheys {

		border-bottom: 1px solid #948C75;
		margin: 20px 0;
		padding: 0 10px;
	}
	.tasks {
		margin-top: 5px;
		padding: 10px 15px;
	}
	.week {
		vertical-align: top;
		display: inline-block;
	}
	.day {
		height: 22px;
		width: 22px;
		margin-left: 13px;
		margin-bottom: 20px;
		border-radius: 2px;
		background: #D9CEB2;
	}
	.blank {
		height: 20px;
		width: 10px;
		opacity: 0.5;
		border: 1px solid #948C75;
		border-radius: 2px;
		background: #D9CEB2;
		margin: 0 auto;
	}
	.hole {
		border: 0;
		border-radius: 11px;
		background: #99B2B7;
		box-shadow: inset 3px 3px 0 #899FA4;
		position: relative;
		margin: 0 auto;
	}
	.hole.all {
		height: 20px;
		width: 20px;
	}
	.hole.part {
		top: 2px;
		height: 16px;
		width: 16px;
	}
	.hole.one {
		top: 5px;
		height: 12px;
		width: 12px;
	}
	.header-line {
		margin-top: 66px;
		margin-bottom: 10px;
		border-bottom: 1px solid #948C75;
	}
	input[type=submit] {
		background: #D9CEB2;
		border: 1px solid #948C75;
		color: #7A6A53;
		border-radius: 0;
		text-transform: uppercase;
		letter-spacing: 2px;
		font-size: 12px;
		padding: 5px 10px;
		margin: 10px 0;
	}

	input[type=submit]:hover {
		text-decoration: underline;
	}

	input[type=text] {
		background: #D9CEB2;
		border: none;
		border-bottom: 1px dotted #948C75;
		color: #7A6A53;
		border-radius: 0;
		font-size: 12px;
		padding: 5px 10px;
	}

	label {
		margin: 4px 0;
		display: inline-block;
		width: 140px;
	}


	a.delete {
		text-decoration: none;
		color: #948C75;
		float: right;
		opacity: 1;
	}

	label:hover a.delete {
		opacity: 1;
	}

	.new-task {
		margin-top: 15px;
		position: absolute;
		bottom: 8px;
	}

	.howto {
		font-size: 9px;
		position: absolute;
		top: 25px;
		left: 2px;
	}

	.author {
		font-size: 9px;
		position: relative;
		top: -20px;
	}
	</style>
	<title>Punch Me</title>
</head>
<body>
<?php
$begin = new DateTime(date('Y-m-d', strtotime('sunday this week -20 weeks')));
$today = new DateTime();
$cursor = $begin;
$file = fopen("data", "r") or die("Unable to open file!");
$data = fread($file, filesize("data"));
fclose($file);
$data = json_decode($data, true);
$max = 0;

if ($_POST)
{
	if (!empty($_POST['add']))
	{
		$data['tasks'][]['label'] = $_POST['task'];
		file_put_contents("data", json_encode($data, TRUE));
	}

	if (!empty($_POST['update']))
	{
		$week_begin = new DateTime(date('Y-m-d', strtotime('sunday last week')));
		$week_begin = $week_begin->format('Y-m-d');
		$data['today']['tasks']['keys'] = array();
		foreach($_POST as $key => $value)
		{
			if (is_numeric($key))
			{
				$data['today']['date'] = $today->format('Y-m-d');
				$data['today']['tasks']['keys'][] = $key;
			}
		}

		$day = $today->format('w');
		$data['punchcard']['holes']["$week_begin"][$day] = count($data['today']['tasks']['keys']);
		file_put_contents("data", json_encode($data, TRUE));
	}
}

if ($_GET)
{
	if (!empty($_GET['delete']))
	{
		foreach($data['tasks'] as $key => $task)
		{
			if ($task['label'] === $_GET['delete'])
			{
				unset($data['tasks'][$key]);
				file_put_contents("data", json_encode($data, TRUE));
				break;
			}
		}
	}
}

$tasks = $data['tasks'];
$selected = array();

if ($data['today']['date'] == $today->format('Y-m-d'))
{
	$selected = $data['today']['tasks']['keys'];
}

?>
<section>
<div class="left">
<div class="header-line"></div>
	S<br />
	M<br />
	T<br />
	W<br />
	T<br />
	F<br />
	S<br />
</div>

<div class="middle">
<h1>PUNCH ME</h1>
<div class="puncheys">
<?php

while ($cursor < $today)
{
	$week_begin = $cursor->format('Y-m-d');
	if (empty($data['punchcard']['holes']["$week_begin"]))
	{
		$data['punchcard']['holes']["$week_begin"] = array(0,0,0,0,0,0,0);
		file_put_contents("data", json_encode($data, TRUE));
	}

	echo "<div class=\"week\">";
	for ($i = 0; $i < 7; $i++)
	{
		$value = $data['punchcard']['holes']["$week_begin"][$i];
		if ($cursor < $today)
		{
			echo "<div class=\"day\">";
			switch ($value)
			{
			case 0:
				echo "<div class=\"blank\"></div>";
				break;

			case 1:
				echo "<div class=\"hole one\"></div>";
				break;

			case count($tasks):
				echo "<div class=\"hole all\"></div>";
				break;

			default:
				echo "<div class=\"hole part\"></div>";
				break;
			}

			echo "</div>";
		}

		$cursor->add(new DateInterval('P1D'));
	}

	echo "</div>";
}

?>
</div>
<div class="author">by tomual</div>
</div>	
<div class="right">

<div class="howto">
	Habit Punch Card<br />
	1. Enter up to 6 tasks<br />
	2. Every day, submit the tasks you have completed<br />
</div>
<div class="header-line"></div>
<div class="tasks">
	<form method="post">
		<div class="date"><?php echo $today->format("l dS M Y\n") ?></div>
		
		<br />
		<?php foreach($tasks as $index => $task): ?>
			<input type="checkbox" name="<?php echo $index ?>" id="<?php echo $index ?>" <?php echo in_array($index, $selected) ? 'checked' : '' ?>>
			<label for="<?php echo $index ?>"><?php echo $task['label'] ?><a href="?delete=<?php echo $task['label'] ?>" class="delete">&times;</a></label>
			<br />
		<?php endforeach ?>
		<input type="submit" name="update" value="Update">
	</form>

	<?php if (count($tasks) < 6): ?>
	<form method="post" class="new-task">
		<label>New Task</label>
		<input type="text" name="task" placeholder="Task Name">
		<input type="submit" name="add" value="Add">
	</form>
	<?php endif ?>
</div>
</div>
</section>



</body>
</html>