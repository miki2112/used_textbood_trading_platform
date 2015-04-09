<!DOCTYPE HTML>
<html>
<HEAD>
    <meta charset="utf-8">
    <TITLE>书本管理</TITLE>
</HEAD>
<body>

<?php
include_once("base-class.php");

$mysql = new SaeMysql();

$book_id=intval($_GET["id"]);

$action=$_GET["action"];
$action= string::un_script_code($action);
$action= string::un_html($action);

if($book_id)
{
    $value=$mysql->getLine("select book_name,book_course,book_edition,book_author,book_new_or_old,book_phone,book_wc,book_price,book_pic
                    							   from book
                                                  where book_id=$book_id");
    if(!$value)
    {
        echo "<script>alert('无此记录');;</Script>";
        exit;
    }
}

if($action=="del")
{

    $nowtime=date("Y/m/d H:i:s",time());
    // $mysql->runSql("update book set status=0,edittime='$nowtime' where book_id=$book_id");
    $mysql->runSql("delete from book where book_id = $book_id ");
    echo "<script>alert('操作成功！');</Script>";
    exit;
}

?>
<!--页面名称-->
<h3>书本管理 手机端赞不支持</h3>
<!--列表开始-->

<table border=1>
    <tr>
        <td>序号</td><td>书名</td><td>版本</td><td>作者</td>
    </tr>
    <?php
    

            echo "<tr>		<td>$book_id</td>
                          <td>$value[book_name]</td>
                          <td>$value[book_edition]</td>
                          <td>$value[book_author]</td>
                          <td>
                            <a href='book_manage.php?action=del&book_id=$book_id'>删除</a>
                          </td>
                          <tr>";
    
    ?>

</table>
<?php
echo $multi;
?>
</body>
</html>
