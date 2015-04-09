<?php
/** Created by fengjie ling
 * cssa webchat api interface
 */

include_once("wx_tpl.php");
include_once("base-class.php");
/******************************************************************/
define("TOKEN", "cssa");
$mysql = new SaeMysql();

//Memcache
$mc=memcache_init();

//Get message
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//help menu defined here
$help_menu="回复“ub”上传需要出售的教材信息\n回复“mb”查看你已经上传的所有二手教材\n回复“db”按书本序号删除指定你所上传的二手教材\n回复“pb”按课程查询相关二手教材\n回复“nb”按书名查询相关二手教材\n回复“ab”查看所有已上传的二手教材\n";

/******************************************************************/

//return message
if (!empty($postStr)){

    //analysis the message
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    //sender ID
    $fromUsername = $postObj->FromUserName;
    //receiver ID
    $toUsername = $postObj->ToUserName;
    //message type
    $form_MsgType = $postObj->MsgType;

    // determine the type of message
    if($form_MsgType=="text"){
        //get text message from user
        $form_Content = trim($postObj->Content);
        $form_Content = string::un_script_code($form_Content);


        if(!empty($form_Content)){
            //get previous action from cache
            $last_do=$mc->get($fromUsername."_do");
            //get previous data from cache
            $last_data=$mc->get($fromUsername."_data");


            //help menus
            if(strtolower($form_Content)=="二手书"){

                $help_str="二手书交易平台使用帮助：\n".$help_menu;

                //send text message back
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $help_str);
                echo $resultStr;
                exit;
            }


            //exit action by user
            if(strtolower($form_Content)=="exit"){
                //clear cache
                $mc->delete($fromUsername."_do");

                $mc->delete($fromUsername."_data");

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "你已经退出当前操作，进行其他操作请输入“二手书”");
                echo $resultStr;
                exit;
            }
            /******************************************************************/

            // upload book by user
            if(strtolower($form_Content)=="ub"){
                // store this operation
                $mc->set($fromUsername."_do", "ub_0", 0, 600);

                $msgType = "text";

                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入你想卖的书名，输入exit退出操作");
                echo $resultStr;
                exit;
            }

            if($last_do=="ub_0"){

                $mc->set($fromUsername."_do", "ub_1", 0, 600);

                //store data
                $mc->set($fromUsername."_data", $form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入使用该教材相关课程号（例如：eco100），输入exit退出操作");
                echo $resultStr;
                exit;
            }

            if($last_do=="ub_1"){

                $form_Content = preg_replace('/\s+/', '', $form_Content);
                $form_Content = str_replace(' ','',$form_Content);
                $mc->set($fromUsername."_do", "ub_2", 0, 600);

                //combine current data with previous data and use '||' to separate these two data
                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入该书的edition(若无，请输入N/A)，输入exit退出操作");
                echo $resultStr;
                exit;
            }

            if($last_do=="ub_2"){

                $mc->set($fromUsername."_do", "ub_3", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入该书的作者名，输入exit退出操作");
                echo $resultStr;
                exit;
            }
            if($last_do=="ub_3"){

                $mc->set($fromUsername."_do", "ub_4", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入该书的新旧程度，输入exit退出操作");
                echo $resultStr;
                exit;
            }
            if($last_do=="ub_4"){

                $mc->set($fromUsername."_do", "ub_5", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入手机号码作为联系方式(若不想提供，请输入N/A，但请保证手机和微信至少有一种联系方式)，输入exit退出操作！");
                echo $resultStr;
                exit;
            }
            if($last_do=="ub_5") {

                $mc->set($fromUsername."_do", "ub_6", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入微信，输入exit退出操作");
                echo $resultStr;
                exit;
            }
            if($last_do=="ub_6"){

                $mc->set($fromUsername."_do", "ub_7", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入该书的定价，输入exit退出操作");
                echo $resultStr;
                exit;
            }
            if($last_do=="ub_7"){

                $mc->set($fromUsername."_do", "ub_8", 0, 600);

                $mc->set($fromUsername."_data", $last_data."||".$form_Content, 0, 3600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请上传该书的照片，输入exit退出操作");
                echo $resultStr;
                exit;
            }

            if(strtolower($form_Content)=="pb") {

                $mc->set($fromUsername."_do", "pb_search", 0, 600);

                $mc->set($fromUsername."_data", "null||1", 0, 600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入课程编号查询(例如：eco 100)：");
                echo $resultStr;
                exit;
            }
            if($last_do=="pb_search"){

                $last_data=explode("||",$last_data);
                $search_content=($last_data[0]=="null")?$form_Content:$last_data[0];

                $search_content = preg_replace('/\s+/', '', $search_content);
                $count=$mysql->getVar("select COUNT(*) from book where book_course='$search_content'");

                if(!$count){

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无相关课程的书籍，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=$last_data[1];

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_id,book_name,book_pic,book_price
                                        from book where book_course='$search_content'
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>$search_content."搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php'); //!!!!!!!!!!!

                foreach($book_res as $value){

                    $book_list[]=array('title'=>"课程号：".$search_content." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_detail.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page) {
                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else{

                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');


                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value){

                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;
            }

            if(strtolower($form_Content)=="nb"){

                $mc->set($fromUsername."_do", "nb_search", 0, 600);

                $mc->set($fromUsername."_data", "null||1", 0, 600);

                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入书名查询：");
                echo $resultStr;
                exit;
            }
            if($last_do=="nb_search"){
                $last_data=explode("||",$last_data);
                $search_content=($last_data[0]=="null")?$form_Content:$last_data[0];
                $count=$mysql->getVar("select COUNT(*) from book where book_name='$search_content'");

                if(!$count){

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无相关课程的书籍，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=$last_data[1];

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_id,book_name,book_pic,book_price
                                        from book where book_name='$search_content'
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>$search_content."搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php'); //!!!!!!!!!!!

                foreach($book_res as $value){

                    $book_list[]=array('title'=>"课程号：".$search_content." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_detail.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page){

                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else{

                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');



                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value) {

                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;
            }

            if(strtolower($form_Content)=="mb"){

                $mc->set($fromUsername."_do", "mb_search", 0, 600);

                $search_content=$fromUsername;
                $count=$mysql->getVar("select COUNT(*) from book where book_up='$search_content'");

                if(!$count){

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无上传记录，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=1;

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_id,book_name,book_pic,book_price
                                        from book where book_up='$search_content'
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>"搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php'); //!!!!!!!!!!!

                foreach($book_res as $value){

                    $book_list[]=array('title'=>"序号（用于删除管理）：".$value["book_id"]." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_manage.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page){

                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else{

                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');


                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value){
                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;

                /* $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "输入任何字符查看该账户所有已上传的二手书");
                echo $resultStr;*/
                exit;
            }
            if($last_do=="mb_search"){
                $last_data=explode("||",$last_data);
                $search_content=$fromUsername;
                $count=$mysql->getVar("select COUNT(*) from book where book_up='$search_content'");

                if(!$count){

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无上传记录，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=$last_data[1];

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_id,book_name,book_pic,book_price
                                        from book where book_up='$search_content'
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>"搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');

                foreach($book_res as $value) {

                    $book_list[]=array('title'=>"序号（用于删除管理）：".$value["book_id"]." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_manage.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page){

                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else {

                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');


                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value){

                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;
            }


            if(strtolower($form_Content)=="db"){

                $mc->set($fromUsername."_do", "db_search", 0, 600);
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入想要删除的书本序号（请使用查询功能查看序号）：");
                echo $resultStr;
                exit;
            }
            if($last_do=="db_search"){
                //查询数据库

                /* $book_value = $mysql->getLine("select book_name
                    							   from book
                                                   where book_up='$fromUsername'");
                if (!$book_value) {
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂无此书1，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }
                */
                $book_value = $mysql->getLine("select book_name,book_pic
                    							   from book
                                                   where book_id='$form_Content'&&book_up='$fromUsername'");

                if (!$book_value) {
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂无此书或非本人上传，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }
                else{
                    $s = new SaeStorage();

                    $filename = explode("http://cssawebchat-cssawebchat.stor.sinaapp.com/","temp".$book_value["book_pic"]);
                    $s->delete( 'cssawebchat' ,$filename[1]);
                    $mysql->runSql("delete from book where book_id = '$form_Content' ");
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "删除成功，输入其他序号继续删除，退出查询状态请输入exit]");
                    echo $resultStr;
                    exit;
                }

            }

            if(strtolower($form_Content)=="ab"){

                $mc->set($fromUsername."_do", "ab_search", 0, 600);

                $search_content=$fromUsername;
                $count=$mysql->getVar("select COUNT(*) from book where status =1 ");

                if(!$count)
                {
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无上传记录，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=1;

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_course,book_id,book_name,book_pic,book_price
                                        from book where status =1
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>"搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php'); //!!!!!!!!!!!

                foreach($book_res as $value) {
                    $book_list[]=array('title'=>"课程号：".$value["book_course"]." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_detail.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page){

                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else{
                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');


                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value) {
                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;

                /* $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "输入任何字符查看该账户所有已上传的二手书");
                echo $resultStr;*/
                exit;
            }
            if($last_do=="ab_search"){
                $last_data=explode("||",$last_data);
                $search_content=$fromUsername;
                $count=$mysql->getVar("select COUNT(*) from book where status =1");

                if(!$count)
                {
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "暂时无上传记录，请重新输入，或者输入exit退出操作！");
                    echo $resultStr;
                    exit;
                }

                $page_num=4;

                $page=$last_data[1];

                $from_record = ($page - 1) * $page_num;

                $book_res=$mysql->getData("select book_course,book_id,book_name,book_pic,book_price
                                        from book where status =1
                                        order by book_id desc
                                        limit $from_record,$page_num");
                $book_list[]=array('title'=>"搜索结果(第".$page."页)：",
                    'doc'=>'',
                    'pic'=>'http://cssawebchat-cssawebchat.stor.sinaapp.com/book_cssa.jpg',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');

                foreach($book_res as $value){
                    $book_list[]=array('title'=>"课程号：".$value["book_course"]." 书名：".$value["book_name"]." 价格：".$value["book_price"]." ",
                        'doc'=>'',
                        'pic'=>$value["book_pic"],
                        'url'=>'http://cssawebchat.sinaapp.com/book_detail.php?id='.$value["book_id"]);

                }
                $book_next=0;
                $book_tip="";

                $real_page=@ceil($count / $page_num);

                if($page>=$real_page){
                    $book_next=1;
                    $book_tip="已经到最后一页，重复查询输入任何字符，退出请输入exit";
                }

                else{
                    $book_next=$page+1;
                    $book_tip="还有".($count-($page*$page_num))."条记录，输入任何字符查看下一页，退出请输入exit";
                }

                $mc->set($fromUsername."_data", $search_content."||".$book_next, 0, 600);
                $book_list[]=array('title'=>$book_tip,
                    'doc'=>'',
                    'url'=>'http://cssawebchat.sinaapp.com/list.php');


                $resultStr="<xml>\n
                                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>\n
                                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>\n
                                <CreateTime>".time()."</CreateTime>\n
                                <MsgType><![CDATA[news]]></MsgType>\n
                                <ArticleCount>".count($book_list)."</ArticleCount>\n
                                <Articles>\n";
                foreach($book_list as $key=>$value){
                    $resultStr.="<item>\n
                                    <Title><![CDATA[".$value["title"]."]]></Title> \n
                                    <Description><![CDATA[".$value["doc"]."]]></Description>\n
                                    <PicUrl><![CDATA[".$value["pic"]."]]></PicUrl>\n
                                    <Url><![CDATA[".$value["url"]."]]></Url>\n
                                    </item>\n";
                }
                $resultStr.="</Articles>\n
                                <FuncFlag>0</FuncFlag>\n
                                </xml>";
                echo $resultStr;
                exit;
            }

        }

    }
    else if(  $form_MsgType=="image"){
        $last_do=$mc->get($fromUsername."_do");
        //get previous data from cache
        $last_data=$mc->get($fromUsername."_data");
        if($last_do=="ub_8"){

            $from_PicUrl=$postObj->PicUrl;

            $filename=$fromUsername.date("YmdHis").".jpg";

            $f = new SaeFetchurl();

            $res = $f->fetch($from_PicUrl);

            $s = new SaeStorage();
            $s->write( 'cssawebchat' , $filename , $res );
            $pic = "http://cssawebchat-cssawebchat.stor.sinaapp.com/".$filename;



            $nowtime=date("Y/m/d H:i:s",time());
            list($book_name,$book_course,$book_edition,$book_author,$book_new_or_old,$book_phone,$book_wc,$book_price,$book_pic)=explode("||",$last_data."||".$pic);
            $sql = "insert into book
                (book_name,book_course,book_edition,book_author,book_new_or_old,book_phone,book_wc,book_price,book_pic,book_up,addtime,edittime,status)
                values
                ('$book_name','$book_course','$book_edition','$book_author','$book_new_or_old','$book_phone','$book_wc','$book_price','$pic','$fromUsername','$nowtime','$nowtime',1)";
            $mysql->runSql( $sql );

            $mc->delete($fromUsername."_do");
            $mc->delete($fromUsername."_data");
            $msgType = "text";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "上传成功");
            echo $resultStr;
            exit;

        }
    }

}
else
{
    echo "";
    exit;
}

?>