<?= \kato\DropZone::widget([
       'options' => [
       		'url'=>'index.php?r=customer-upload/upload',
        	'maxFilesize' => '20',
       ],
       'clientEvents' => [
           'complete' => "function(file){console.log(file)}",
           'removedfile' => "function(file){alert(file.name + ' is removed')}"
       ],
   ]);
?>