<?php

function showTitle($title)
{
    echo "<h2>&#9830; $title</h2>";
    echo '<hr/>';
}

$sentence = 'Hello my friends! How are you today?';

showTitle('Exercise 1: number of characters with strlen()');
echo strlen($sentence);

showTitle('Exercise 2: word count with str_word_count()');
echo str_word_count($sentence);

showTitle('Exercise 3: uppercase with strtoupper()');
echo strtoupper($sentence);

showTitle('Exercise 4: First character of each word capitalized with ucwords()');
echo ucwords($sentence);

showTitle('Exercise 5 character count without whitespaces');
echo strlen($sentence) - substr_count($sentence, ' ');

showTitle('Exercise 6 change a for b, c for d, e for f with strtr()');
echo strtr($sentence, 'ace', 'bdf');
