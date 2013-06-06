<h1>Traces des actions</h1>
<?php
if(!empty($res)):
?>
<table class="table table-striped table-bordered">
	<thead>
		<tr><th>Heure</th><th>Login</th><th>Description</th></tr>
	</thead>
	<tbody>
<?php
foreach($res as $v)
	echo '<tr><td>'.$v->getWhen().'</td><td>'.$v->getUser()->getLogin().'</td><td>'.$v->getDescription().'</td>';
?>
	</tbody>
</table>
<?php
else:
	echo '<div class="row-fluid show-grid"><div class="span12">Aucune donnée</div></div>';
endif;
?>