<!DOCTYPE html>
<style type="text/css">	
body {
    font-family: "Lucida Console", Monaco, monospace;
    font-size: 14px;
    letter-spacing: 2px;
    background: #1A786F;
    color: #FFF;
}
h1 {
    font-size: 18px;
    font-weight: normal;
    padding: 0;
    margin: 0;
    border-bottom: 1px solid #B8AB6F;
    border-top: 1px solid #B8AB6F;
    padding: 10px;
    padding-bottom: 0;
    text-align: center;
    color: #B8AB6F;
}
section {
    background: #EDE1A8;
    border-radius: 10px;
    box-shadow: 0 0 1em #066158;
    padding: 10px 0;
    overflow: auto;
    width: 1000px;
    margin: auto;
}
.puncheys {
    margin-top: 5px;
    padding: 35px;
    padding-right: 0;
    border-bottom: 1px solid #B8AB6F;
    border-top: 1px dotted #B8AB6F;
    width: 760px;
    display: inline-block;
    float: left;
}
.tasks {
    margin-top: 5px;
    padding: 10px 5px;
    border-bottom: 1px solid #B8AB6F;
    border-top: 1px dotted #B8AB6F;
    border-left: 1px solid #B8AB6F;
    width: 190px;
    height: 100%;
    display: inline-block;
    float: right;
}
.week {
    width: 22px;
    margin-right: 16px;
    vertical-align: top;
    display: inline-block;
}
.day {
    height: 20px;
    width: 20px;
    margin-left: 5px;
    margin-bottom: 20px;
    border-radius: 2px;
    background: #EDE1A8;
}
.blank {
    height: 20px;
    width: 10px;
    opacity: 0.5;
    border: 1px solid #B8AB6F;
    border-radius: 2px;
    background: #EDE1A8;
    margin: 0 auto;
}
.hole {
    border: 0;
    border-radius: 11px;
    background: #1A786F;
    box-shadow: inset 0 0 1em #066158;
    position: relative;
    margin: 0 auto;
}
.hole.all {
    height: 22px;
    width: 22px;
}
.hole.part {
    top: 2px;
    height: 18px;
    width: 18px;
}
.hole.one {
    top: 5px;
    height: 12px;
    width: 12px;
}
</style>
<html>
<head>
	<title>Punch Me</title>
</head>
<body>
<?php 
$file = fopen("data", "r") or die("Unable to open file!");
$data = fread($file,filesize("data"));
fclose($file);

$data = json_decode( $data, true );
//var_dump($data);
$max = 0;
if( $_POST )
{
	//var_dump( $_POST );
	if( !empty( $_POST['task'] ) )
	{
		$data['tasks'][]['label'] = $_POST['task'];
	}
	echo json_encode($data,TRUE);
	file_put_contents("data", json_encode($data,TRUE));
}

$tasks = $data['tasks'];

$begin = new DateTime( date('Y-m-d', strtotime('sunday this week -20 weeks')) );
$today = new DateTime();
$cursor = $begin;
$week = 1;
//var_dump( $period );
?>
<section>
<h1>PUNCH ME</h1>
<div class="puncheys">
<?php 
while( $cursor < $today )
{
	echo "<div class=\"week\">";
	for( $i = 0; $i < 7; $i++ )
	{
		$cursor->add(new DateInterval('P1D'));
		//$value = rand(0,2) == 1;
		$value = $data['punchcard']['holes'][$week][$i];
		if( $cursor < $today )
		{
			echo "<div class=\"day\">";
			switch ($value) {
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
	}
	$week++;
	echo "</div>";
}


?>
</div>
<div class="tasks">
	<form method="post">
		<?php echo $today->format( "l dS m Y Y-m-d\n" ) ?>
		<br />
		<?php foreach ($tasks as $index => $task): ?>
			<input type="checkbox" name="<?= '.' ?>">
			<?= $task['label'] ?><br />
		<?php endforeach ?>
		<input type="submit" name="submit">
	</form>

	<form method="post">
		<label>New Task</label>
		<input type="text" name="task" placeholder="Task Name">
		<input type="submit" name="submit">
	</form>
</div>
</section>



</body>
</html>