<!DOCTYPE html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.min.css">
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