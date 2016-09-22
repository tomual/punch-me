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
    width: 10px;
    margin-left: 5px;
    margin-bottom: 20px;
    border: 1px solid #B8AB6F;
    border-radius: 2px;
    background: #EDE1A8;
}
.day-punched {
    margin-bottom: 20px;
    background: #1A786F;
    box-shadow: inset 0 0 1em #066158;
    height: 22px;
    width: 22px;
    border: 0;
    border-radius: 11px;
}
</style>
<html>
<head>
	<title>Punch Me</title>
</head>
<body>
<?php 
$myfile = fopen("data", "r") or die("Unable to open file!");
$data = fread($myfile,filesize("data"));
fclose($myfile);

echo $data;
$data = json_decode( $data );
var_dump($data);
$max = 0;
$tasks = array();
if( $_POST )
{
	var_dump( $_POST );
	if( !empty( $_POST['task'] ) )
	{
		$tasks[$max] = $_POST['task'];
	}
}

$begin = new DateTime( date('Y-m-d', strtotime('sunday this week -20 weeks')) );
$today = new DateTime();
$cursor = $begin;
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
		$value = rand(0,2) == 1;
		if( $cursor < $today )
		{
			if( $value )
			{
		echo "<div class=\"day-punched\"></div>";

			}
			else
			{

		echo "<div class=\"day\"></div>";
			}
		}
	}
	echo "</div>";
}


?>
</div>
<div class="tasks">
	<form method="post">
		<?php echo $today->format( "l dS m Y Y-m-d\n" ) ?>
		<?php foreach ($tasks as $index => $task): ?>
			<input type="checkbox" name="<?= $index ?>">
			<?= $task ?>
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