<div class="btn-group btn-group-justified links" role="group" aria-label="...">
  <div class="btn-group" role="group">
  	<a href="<?php echo $prev; ?>" class="btn btn-default" role="btn">上一章</a>
  </div>
  <div class="btn-group" role="group">
  	<a href="index.php" class="btn btn-default" role="btn">返回搜索</a>
  </div>
  <div class="btn-group" role="group">
    <a href="<?php echo $list; ?>" class="btn btn-default" role="btn">章节目录</a>
  </div>
  <div class="btn-group" role="group">
    <a href="<?php echo $next; ?>" class="btn btn-default" role="btn">下一章</a>
  </div>
</div>
<div class="setting">
    <button id="bigger" type="button" class="btn btn-default btn-sm">
        文字 : <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    <button id="smaller" type="button" class="btn btn-default btn-sm">
        文字 : <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
    </button>
    <button id="green" type="button" class="btn btn-default btn-sm">
        护眼 : <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
    </button>
    <button id="lamp" type="button" class="btn btn-default btn-sm">
        关灯 : <span class="glyphicon glyphicon-lamp" aria-hidden="true"></span>
    </button>
</div>
<div>
<?php
echo "<h3>" . $title . "</h3>";
echo "<div class=\"content\">" . $content . "</div>";
?>
</div>

<div class="btn-group btn-group-justified links" role="group" aria-label="...">
  <div class="btn-group" role="group">
  	<a href="<?php echo $prev; ?>" class="btn btn-default" role="btn">上一章</a>
  </div>
  <div class="btn-group" role="group">
  	<a href="index.php" class="btn btn-default" role="btn">返回搜索</a>
  </div>
  <div class="btn-group" role="group">
    <a href="<?php echo $list; ?>" class="btn btn-default" role="btn">章节目录</a>
  </div>
  <div class="btn-group" role="group">
    <a href="<?php echo $next; ?>" class="btn btn-default" role="btn">下一章</a>
  </div>
</div>