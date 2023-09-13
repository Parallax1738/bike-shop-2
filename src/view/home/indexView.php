<h1>Holy shit it worked</h1>
<?php
//
	$lipsum = new joshtronic\LoremIpsum();
 
	echo '1 word: '  . $lipsum->word();
	echo '5 words: ' . $lipsum->words(5);
?>