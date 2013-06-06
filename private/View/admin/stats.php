<h1>Comportements atypiques</h1>
<h2>Nombres d'actions</h2>
<?php
if(!empty($res1)):
?>
<table class="table table-striped table-bordered">
	<thead>
		<tr><th>#</th><th>Login</th><th># of events</th></tr>
	</thead>
	<tbody>
<?php
iCpt(0);
foreach($res1 as $v)
	echo '<tr><td>'.iCpt().'</td><td>'.$v[0]->getUser()->getLogin().'</td><td>'.$v[1].'</td>';
?>
	</tbody>
</table>
<?php
else:
	echo '<div class="row-fluid show-grid"><div class="span12">Aucune donnée</div></div>';
endif;
?>

<h2>Indice de réputation</h2>
<?php
if(!empty($res2)):
?>
<table class="table table-striped table-bordered">
	<thead>
		<tr><th>#</th><th>Login</th><th>Indice</th><th>inBounds</th><th>outBounds</th></tr>
	</thead>
	<tbody>
<?php
iCpt(0);
foreach($res2 as $v)
	echo '<tr><td>'.iCpt().'</td><td>'.$v->getLogin().'</td><td>'.$v->getReputation().'</td><td>'.$v->getInBounds().'</td><td>'.$v->getOutBounds().'</td>';
?>
	</tbody>
</table>
<?php
else:
	echo '<div class="row-fluid show-grid"><div class="span12">Aucune donnée</div></div>';
endif;
?>

<h2>Bînomes de travail</h2>
<?php
if(!empty($res3)):
?>
<table class="table table-striped table-bordered">
	<thead>
		<tr><th>#</th><th>Login1</th><th>Login2</th></tr>
	</thead>
	<tbody>
<?php
iCpt(0);
foreach($res3 as $v)
	echo '<tr><td>'.iCpt().'</td><td>'.$v->getUser1()->getLogin().'</td><td>'.$v->getUser2()->getLogin().'</td>';
?>
	</tbody>
</table>
<?php
else:
	echo '<div class="row-fluid show-grid"><div class="span12">Aucune donnée</div></div>';
endif;
?>