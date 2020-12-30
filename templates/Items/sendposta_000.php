
<?php

?>

<form method="post">

  <!-- ↓これを追加 -->
  <input
    type="hidden" name="_csrfToken" autocomplete="off"
    value="<?= $this->request->getAttribute('csrfToken') ?>">

</form>
<h1>POSTa 送信テスト</h1>
