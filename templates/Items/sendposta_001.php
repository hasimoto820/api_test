



<?=$this->Form->create(null, ['url' => ['controller' => 'Items', 'action' => 'sendposta'], 'type' => 'post'])?>
<?=$this->Form->input('msg');?>
<?=$this->Form->submit('送信')?>
<?=$this->Form->end()?>




<?php

echo('JSON');
echo('*************************************************************');
echo('*************************************************************');
echo($html);
echo('*************************************************************');
?>


<h1>POSTa 送信テスト</h1>
