<?php
$question = 'این یک پرسش نمونه است';
$msg = 'این یک پاسخ نمونه است';
$en_name = 'hafez';
$fa_name = 'حافظ';
$question = '';
$question_label = '';

$peoplejson = file_get_contents("people.json");
$people_dictionary = json_decode($peoplejson, TRUE);

$messages_file = fopen("messages.txt", "r");
$messages = array();
while (! feof($messages_file))
{
    $line = fgets($messages_file);
    array_push($messages, $line);
}
fclose($messages_file);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $question = $_POST["question"];
    $question_label = 'پرسش:';

    $en_name = $_POST["person"];
    $fa_name = $people_dictionary["$en_name"];

    $msg = $messages[hexdec(hash('adler32', $question.$en_name)) % count($messages)];
    $firstChecker = "/^آیا/iu";
    $questionmarkChecker = "/\?$/i";
    $questionmarkChecker2 = "/؟$/u";
    if (!preg_match($firstChecker, $question))
    {
        $msg = "سوال درستی مطرح نشده است.";
    }
    if (!(preg_match($questionmarkChecker, $question) || preg_match($questionmarkChecker2, $question)))
    {
        $msg = "سوال درستی مطرح نشده است.";
    }
}
else
{
    $question_label = '';
    $msg = "سوال خود را بیرس";

    $people_english_list = array_keys($people_dictionary);
    $en_name = $people_english_list[array_rand($people_english_list)];
    $fa_name = $people_dictionary["$en_name"];
}

// if ($question == '')
// {
    
// }
// else
// {
    
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label"><?php echo $question_label ?></span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                /*
                 * Loop over people data and
                 * enter data inside `option` tag.
                 * E.g., <option value="hafez">حافظ</option>
                 */
                foreach ($people_dictionary as $key => $value)
                {
                    if ($key == $en_name)
                    {
                        echo "<option value='$key' selected>$value</option>\n";
                    }
                    else
                    {
                        echo "<option value='$key'>$value</option>\n";
                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>