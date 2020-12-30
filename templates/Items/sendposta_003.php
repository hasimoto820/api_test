
<script src='./js/csrf.js'>
</script>


<form method="post">
  <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
  <input type="submit" value="送信する">
</form>




<?php

echo('JSON');
echo('*************************************************************');
echo('*************************************************************');
echo($html);
echo('*************************************************************');
?>


<h1>POSTa 送信テスト</h1>
