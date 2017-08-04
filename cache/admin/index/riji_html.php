<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit">
<title></title>
    <link rel="stylesheet" href="public/admin/css/pintuer.css">
    <link rel="stylesheet" href="public/admin/css/admin.css">
    <script src="public/admin/js/jquery.js"></script>
    <script src="public/admin/js/pintuer.js"></script>  
    <link rel="stylesheet" href="public/admin/md/examples/css/style.css" />
    <link rel="stylesheet" href="public/admin/md/css/editormd.css" />
    <!-- <link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" /> -->
</head>
<body>
<div class="panel admin-panel">

  <div class="body-content">
   
      <div class="form-group">
      
      <div class="form-group">
        <div class="field">
        <form action="index.php?m=admin&c=index&a=post" method="post" enctype="multipart/form-data">
        <input type="text" name='title'style="width: 500px;height: 30px;margin-bottom: 10px;">
          <div id="test-editormd">
                  <textarea style="display:none;"></textarea>
          </div>
         <input type="file" name="file">
          <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
          <div class="tips"></div>
        </div>
      </div>
        <div class="field" style="margin-left: 200px;">
          <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
        </div>
      </div>
</form>
  </div>
</div>

</body>
<script src="public/admin/md/examples/js/jquery.min.js"></script>
<script src="public/admin/md/editormd.min.js"></script>
<script type="text/javascript">
    var testEditor;

    $(function() {
        testEditor = editormd("test-editormd", {
            width   : "90%",
            height  : 640,
            syncScrolling : "single",
            path    : "public/admin/md/lib/"
        });
    });
</script>
</html>