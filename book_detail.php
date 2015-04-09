<!DOCTYPE HTML>
<html>
<HEAD>
    <meta charset="utf-8">
    <TITLE>书目</TITLE>
</HEAD>
<body>

<?php
include_once("base-class.php");


$mysql = new SaeMysql();


$book_id=intval($_GET["id"]);

if($book_id)
{
    $book_value=$mysql->getLine("select book_name,book_course,book_edition,book_author,book_new_or_old,book_phone,book_wc,book_price,book_pic
                    							   from book
                                                  where book_id=$book_id");
    if(!$book_value)
    {
        echo "<script>alert('无此记录');;</Script>";
        exit;
    }
}


?>
<!--页面名称-->
<p>
    书名：<b><?php echo $book_value["book_name"];?></b>
</p>
<p>
    作者：<b><?php echo $book_value["book_author"];?></b>
</p>
<p>
    课程号：<b><?php echo $book_value["book_course"];?></b>
</p>
<p>
    照片：<?php echo "<br><img src=\"{$book_value[book_pic]}\" height=150>";?>
</p>
<p>
    版本：<b><?php echo $book_value["book_edition"]?></b>
</p>
<p>
    新旧程度：<b><?php echo $book_value["book_new_or_old"];?></b>
</p>
<p>
    价格：<b><?php echo $book_value["book_price"];?></b>
</p>
<p>
    手机：<b><?php echo $book_value["book_phone"];?></b>
</p>
<p>
    微信：<b><?php echo $book_value["book_wc"];?></b>
</p>
</body>
</html>
