<?php

/* 指定資料庫連線 sql_init */
$_con = mysqli_connect("localhost","example","kYcM1XuFebgqftpm","example");
$_con->query("SET NAMES utf8");
/* .指定資料庫連線 sql_init */

/* 作品編輯 work_create */
if(isset($_POST['action'])&&$_POST['action']=="新增作品"){
    if(isset($_FILES['wofile'])){
        //定義上傳路徑
        echo $upload_dir="uploadfiles/".$_POST['wocategory']."/";
        //確認路徑是否存在
        if(!is_dir($upload_dir)){
            //如果不存在，就新增並給予權限
            if(!mkdir($upload_dir,0755)){
                header("Location: index.php?result=mkdirfailed");
                exit;
            }
        }
        //宣告允許上傳的附檔名陣列
        $allow_ext=array("jpg","png","pdf","tif");
        //檔案上傳的檔名
        $filename=$_FILES['wofile']['name'];
        //透過檔名取得附檔名資訊
        $ext=strtolower(pathinfo($_FILES['wofile']['name'], PATHINFO_EXTENSION));
        //檢查是否為可上傳之副檔名
        if(in_array($ext,$allow_ext)){
            //插入一筆記錄，這裡會先插入一筆記錄是因為等等要用到這筆記錄的ID
            echo $sql ="INSERT INTO `ex_works` (`woid`, `wocategory`, `wotitle`, `woyear`, `womaterial`, `wosize`, `wostatus`, `woext`) VALUES (NULL, '".$_POST['wocategory']."', '".$_POST['wotitle']."', '".$_POST['woyear']."', '".$_POST['womaterial']."', '".$_POST['wosize']."', '".$_POST['wostatus']."', '".$ext."');";
            $query = mysqli_query($_con, $sql);
            if($query){
                //將上傳目的地、剛剛插入的紀錄ID跟附檔名合成成新的檔名
                $new_file_name=$upload_dir.mysqli_insert_id($_con).".".$ext;
                //搬移檔案至目的地，並確認有沒有完成
                if(move_uploaded_file($_FILES['wofile']['tmp_name'],$new_file_name)){
                    header("Location: index.php?result=success");
                    exit;
                }else{
                    header("Location: index.php?tab=list&result=unabletoupload");
                    exit;
                }
            }else{
                header("Location: index.php?tab=list&result=failed");
                exit;
            }
        }else{
            header("Location: index.php?tab=list&result=invailedext");
            exit;
        }
    }else{
        header("Location: index.php?tab=list&result=nofile");
        exit;
    }
}
/* .作品新增 work_create */

/* 作品刪除 work_delete */
if(isset($_POST['action'])&&$_POST['action']=="刪除"){
    echo $sql ="DELETE FROM `ex_works` WHERE `woid` = '".$_POST['woid']."'";
    $query = mysqli_query($_con, $sql);
    header("Location: index.php?tab=list&result=deleted");
    exit;
}
/* .作品刪除 work_delete */

/* 作品編輯 work_edit */
if(isset($_POST['action'])&&$_POST['action']=="編輯作品"){
    //先編輯文本
    $sql = "UPDATE `ex_works` SET `wocategory` = '".$_POST['wocategory']."', `wotitle` = '".$_POST['wotitle']."', `woyear` = '".$_POST['woyear']."', `womaterial` = '".$_POST['womaterial']."', `wosize` = '".$_POST['wosize']."', `wostatus` = '".$_POST['wostatus']."' WHERE `woid` = ".$_POST['woid'];
    $query = mysqli_query($_con, $sql);
    //判斷是否有改類別
    /* 這次的回家作業寫在這裡 */
    if($_POST['wocategory']!=$_POST['wocatorigin']){
        $origin_dir="uploadfiles/".$_POST['wocatorigin']."/";
        $new_dir="uploadfiles/".$_POST['wocategory']."/";
        $file_name = $_POST['woid'].".".$_POST['woext'];
        rename($origin_dir.$file_name,$new_dir.$file_name);
    }
    //再判斷是不是有重新上傳檔案
    if(isset($_FILES['wofile'])){
        //定義路徑
        echo $upload_dir="uploadfiles/".$_POST['wocategory']."/";
        //確認路徑是否存在
        if(!is_dir($upload_dir)){
            //如果不存在，就新增並給予權限
            if(!mkdir($upload_dir,0755)){
                header("Location: index.php?result=mkdirfailed");
                exit;
            }
        }
        //宣告允許上傳的附檔名陣列
        $allow_ext=array("jpg","png","pdf","tif");
        //檔案上傳的檔名
        $filename=$_FILES['wofile']['name'];
        //透過檔名取得附檔名資訊
        $ext=strtolower(pathinfo($_FILES['wofile']['name'], PATHINFO_EXTENSION));
        //檢查是否為可上傳之副檔名
        if(in_array($ext,$allow_ext)){
            //將上傳目的地、剛剛插入的紀錄ID跟附檔名合成成新的檔名
            $new_file_name=$upload_dir.$_POST['woid'].".".$ext;
            //搬移檔案至目的地，並確認有沒有完成
            if(move_uploaded_file($_FILES['wofile']['tmp_name'],$new_file_name)){
                $sql = "UPDATE `ex_works` SET `woext` = '".$ext."' WHERE `woid` = ".$_POST['woid'];
                $query = mysqli_query($_con, $sql);
                header("Location: index.php?tab=list&result=success");
                exit;
            }else{
                header("Location: index.php?tab=list&result=unabletoupload");
                exit;
            }
        }else{
            header("Location: index.php?tab=list&result=invailedext");
            exit;
        }
    }else{
        header("Location: index.php?tab=list&result=editsucandnofile");
        exit;
    }
}
/* .作品編輯 work_edit */

/* 新增分類 category_create */
if(isset($_POST['action'])&&$_POST['action']=="新增分類"){
    echo $sql = "INSERT INTO `ex_category` (`wcid`, `wcname`, `wcorder`, `wcbrief`) VALUES (NULL, '".$_POST['wcname']."', '".$_POST['wcorder']."', '".$_POST['wcbrief']."');";
    $query = mysqli_query($_con, $sql);
    header("Location: index.php?tab=category&result=catcreated");
    exit;
}
/* .新增分類 category_create */

/* 編輯分類 category_edit */
if(isset($_POST['action'])&&$_POST['action']=="編輯分類"){
    echo $sql = "UPDATE `ex_category` SET `wcname` = '".$_POST['wcname']."', `wcbrief` = '".$_POST['wcbrief']."' WHERE `wcid` = ".$_POST['wcid'];
    $query = mysqli_query($_con, $sql);
    header("Location: index.php?tab=category&result=catedited");
    exit;
}
/* .編輯分類 category_edit */

/* 分類刪除 category_delete */
if(isset($_POST['action'])&&$_POST['action']=="刪除分類"){
    echo $sql ="DELETE FROM `ex_category` WHERE `wcid` = '".$_POST['wcid']."'";
    $query = mysqli_query($_con, $sql);
    header("Location: index.php?tab=category&result=catdeleted");
    exit;
}
/* .分類刪除 category_delete */

/* 分類向上 */
if(isset($_GET['case_up'])){
    //先找出要交換的另一個順序
    $r=$_GET['case_up']-1;
    //把兩個要互換的順序的ID査出來
    $sql="SELECT `wcorder`, `wcid` FROM `ex_category` WHERE `wcorder` IN (".$_GET['case_up'].",".$r.")";
    //檢查一下叫出來的資料是不是正確的
    $result=mysqli_query($_con,$sql);
    //宣告一個空陣列看資料（其實可以省略）
    $a=array();
    //逐條讀取查詢出來的資料
    while($row = mysqli_fetch_assoc($result)){
        //將查詢到的資料存進名為$a的陣列中的一個新的值
        $a[]=$row;
    }
    //將$a的陣列以JSON格式呈現
    echo json_encode($a);
    /*
    如果是在順序2的膠彩點「向上」則會出現以下結果：
        [{"wcorder":"1","wcid":"1"},{"wcorder":"2","wcid":"2"}]
    可以檢查看看有沒有出現這樣的結果，有出現的話就是正確的。

    接下來要怎麼做呢？
    - 試試看交換調出來的兩筆資料的順序（wcorder）
    - 試試看怎麼判斷這個請求有沒有合法（例如，如果他排在第一個，沒有上面的值，就不是合法的）
        （提示：mysqli_num_rows($query)）
    */
}
/* .分類向上 */

/* 分類向下 */
if(isset($_GET['case_down'])){

}
/* .分類向下 */
?>