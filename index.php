<?php 

$_con = mysqli_connect("localhost","example","kYcM1XuFebgqftpm","example");
$sql ="SELECT * FROM `ex_works`";
$query = mysqli_query($_con, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>
<body>

<div class="container mb-3">
    <?php if(isset($_GET['result'])&&$_GET['result']=="success"){echo "<h3>新增成功!</h3>";} ?>
    <?php if(isset($_GET['result'])&&$_GET['result']=="failed"){echo "<h3>新增失敗!</h3>";} ?>
    <?php if(isset($_GET['result'])&&$_GET['result']=="nofile"){echo "<h3>未選擇檔案!</h3>";} ?>
    <div class="card mt-3 p-3">
        <div class="row">
            <div class="col-4">
                <h2>作品列表</h2>
            </div>
            <div class="col-2 offset-6">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                新增作品
            </button>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>作品分類</th>
                    <th>作品名稱</th>
                    <th>年份</th>
                    <th>材質</th>
                    <th>尺寸</th>
                    <th>收藏狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($query)){ ?>
                <tr>
                    <td><?php echo $row['wocategory']; ?></td>
                    <td><a href="index.php?id=<?php echo $row['woid']; ?>"><?php echo $row['wotitle']; ?></a></td>
                    <td><?php echo $row['woyear']; ?></td>
                    <td><?php echo $row['womaterial']; ?></td>
                    <td><?php echo $row['wosize']; ?></td>
                    <td><?php echo $row['wostatus']; ?></td>
                    <td>
                        <a href="index.php?edit=<?php echo $row['woid']; ?>" class="btn btn-sm btn-success">編輯</a>
                        <form action="process.php" method="POST">
                            <input type="hidden" name="woid" value="<?php echo $row['woid']; ?>">
                            <input type="submit" name="action" class="btn btn-sm btn-danger" value="刪除" onclick="return confirm('確認要刪除嗎？')">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if(isset($_GET['add'])){ ?>
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><?php echo $row['wotitle']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="uploadfiles/<?php echo $row['wocategory']."/".$row['woid'].".".$row['woext']; ?>" class="img-fluid" alt="">
            </div>
            <div class="modal-footer">
                <a href="uploadfiles/<?php echo $row['wocategory']."/".$row['woid'].".".$row['woext']; ?>" class="btn btn-primary" download="<?php echo $row['wotitle'].".".$row['woext']; ?>">下載</a>
            </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(isset($_GET['id'])){ 
        
        $sql ="SELECT * FROM `ex_works` WHERE `woid` = '".$_GET['id']."'";
        $query = mysqli_query($_con, $sql);
        $row = mysqli_fetch_assoc($query);
        
        ?>
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><?php echo $row['wotitle']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="uploadfiles/<?php echo $row['wocategory']."/".$row['woid'].".".$row['woext']; ?>" class="img-fluid" alt="">
            </div>
            <div class="modal-footer">
                <a href="uploadfiles/<?php echo $row['wocategory']."/".$row['woid'].".".$row['woext']; ?>" class="btn btn-primary" download="<?php echo $row['wotitle'].".".$row['woext']; ?>">下載</a>
            </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">新增作品</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                            <label for="">作品分類</label>
                            <select name="wocategory" id="wocategory" class="form-control">
                                <option value="分類一">分類一</option>
                                <option value="分類二">分類二</option>
                                <option value="分類三">分類三</option>
                            </select>
                            <label>作品名稱</label>
                            <input type="text" name="wotitle" id="wotitle" class="form-control" required>
                            <label>年份</label>
                            <input type="text" name="woyear" id="woyear" class="form-control"  required>
                            <label>材質</label>
                            <input type="text" name="womaterial" id="womaterial" class="form-control"  required>
                            <label>尺寸</label>
                            <input type="text" name="wosize" id="wosize" class="form-control"  required>
                            <label>收藏狀態</label>
                            <input type="text" name="wostatus" id="wostatus" class="form-control"  required>
                            <label>作品圖片</label>
                            <input type="file" name="wofile" id="wofile" class="form-control"  required>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" class="form-control btn-success" value="新增作品"  required>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if(isset($_GET['edit'])){ 
        
        $sql ="SELECT * FROM `ex_works` WHERE `woid` = '".$_GET['edit']."'";
        $query = mysqli_query($_con, $sql);
        $row = mysqli_fetch_assoc($query);
    
    ?>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">編輯「<?php echo $row['wotitle']; ?>」作品</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                            <label for="">作品分類</label>
                            <select name="wocategory" id="wocategory" class="form-control">
                                <option value="分類一" <?php if($row['wocategory']=="分類一"){echo "selected";} ?>>分類一</option>
                                <option value="分類二" <?php if($row['wocategory']=="分類二"){echo "selected";} ?>>分類二</option>
                                <option value="分類三" <?php if($row['wocategory']=="分類三"){echo "selected";} ?>>分類三</option>
                            </select>
                            <input type="hidden" name="wocatorigin" value="<?php echo $row['wocategory']; ?>">
                            <label>作品名稱</label>
                            <input type="text" name="wotitle" id="wotitle" class="form-control" value="<?php echo $row['wotitle']; ?>" required>
                            <label>年份</label>
                            <input type="text" name="woyear" id="woyear" class="form-control" value="<?php echo $row['woyear']; ?>"  required>
                            <label>材質</label>
                            <input type="text" name="womaterial" id="womaterial" class="form-control"  value="<?php echo $row['womaterial']; ?>" required>
                            <label>尺寸</label>
                            <input type="text" name="wosize" id="wosize" class="form-control" value="<?php echo $row['wosize']; ?>"  required>
                            <label>收藏狀態</label>
                            <input type="text" name="wostatus" id="wostatus" class="form-control" value="<?php echo $row['wostatus']; ?>"  required>
                            <label>作品圖片</label>
                            <img src="uploadfiles/<?php echo $row['wocategory']."/".$row['woid'].".".$row['woext'] ?>" class="img-fluid img-thumbnail">
                            <input type="file" name="wofile" id="wofile" class="form-control">
                            <input type="hidden" name="woid" value="<?php echo $row['woid']; ?>">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="action" class="form-control btn-success" value="編輯作品"  required>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script>
    <?php if(isset($_GET['id'])){ ?>
    var myModal = new bootstrap.Modal(document.getElementById('viewModal'), {
        keyboard: false
    });
    myModal.show();
    <?php } ?>
    <?php if(isset($_GET['edit'])){ ?>
    var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
        keyboard: false
    });
    editModal.show();
    <?php } ?>
</script>
</html>