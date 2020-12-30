
<script>
var xhr = new XMLHttpRequest();
xhr.open('POST','http://localhost/api_test/items/receivepost');
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.withCredentials = true;
xhr.send('text=csrf%20test');
</script>




<form method="post">
  <input
    type="hidden" name="_csrfToken" autocomplete="off"
    value="<?= $this->request->getAttribute('csrfToken') ?>">
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
