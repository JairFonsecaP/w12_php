<?php

$Candidats = [
    ['Pierre', 22, '123 rue A', 'aa@ser.com', ['programming' => 5, 'teaching' => 2]],
    ['Julie', 65, '123 rue B', 'bb@ser.com', ['electronics' => 46]],
    ['Martin', 45, '123 rue C', 'cc@ser.com', ['programming' => 21, 'teaching' => 1]],
    ['Mélanie', 41, '123 rue D', 'dd@ser.com', ['welding' => 12, 'nutrition' => 6, 'restoration' => 1]],
];

// background colors: black if age equal reference age, green when higher, blue when lower
const AGE_REFERENCE = 45;

// background yellow when years of experience higher or equal to MINIMUM_EXPERIENCE
const MINIMUM_EXPERIENCE = 5;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Exercice 7-4 - Job Candidates</title>

    <style>
        table,
        td,
        th {
            border: 1px solid black;
            margin: auto;
            padding: 5px;
        }

        ul {
            list-style-type: none;
            padding: 5px;
        }

        /* when egal age reference*/
        .age-reference {
            background-color: black;
            color: white;
        }

        /* when > age reference*/
        .age-over {
            background-color: green;
            color: white;
        }

        /* when < age reference */
        .age-under {
            background-color: blue;
            color: white;
        }

        /* when  < minimum experience */
        .experience-invalid {
            background-color: white;
            color: black;
        }

        /* when >= minimum experience */
        .experience-valid {
            background-color: yellow;
            color: black;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table>
        <caption>Exercise 7-4 Job candidates</caption>
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Address</th>
                <th>Email</th>
                <th>Experiences</th>
            </tr>
        </thead>

        <tbody>
            <?php
            // YOUR CODE HERE
            ?>
        <tfoot>
            <?php
            // YOUR CODE HERE
            ?>
        </tfoot>
    </table>

</body>

</html>