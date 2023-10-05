<?php
echo "<p>hello, world in index.php thing</p>";
foreach ($data->getBikes() as $bike)
{
	echo '<p>' . $bike . '</p>';
}

if ($data->getPageIndex() > 0)
{
	// Display Left Arrow because if it is greater than 0, we have pages before it or sohmmteihntoh
	$newPage = $data->getPageIndex() - 1;
	echo '<form method="post" action="http://localhost/bikes?page' . $newPage . '=results=10">
		<input type="submit" value="<" />
	</form>';
}

// TODO - Give data a maximum amount of pages
if ($data->getPageIndex() < 10000)
{
	$newPage = $data->getPageIndex() + 1;
	echo '<form method="post" action="http://localhost/bikes?page' . $newPage . '=results=10">
	<input type="submit" value=">" />
	</form>';
}
?>
