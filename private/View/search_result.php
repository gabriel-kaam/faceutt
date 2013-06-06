<h1>Recherche de profils</h1>
<?php
$i = 0;
if(empty($result))
	echo '<div class="row-fluid show-grid"><div class="span12">Aucun résultat pour votre recherche</div></div>';
else
	foreach($result as $r):
		$user = $r instanceof ModelUser ? $r : $r->getUser2();

		if($i % 3 == 0)
			echo '<div class="row-fluid show-grid search_results">';
?>
	<div class="span4">
		<?php echo '&#149; <a href="/profile/'.$user->getLogin().'">'.$user->getLogin().'</a>'; ?>
		<div class="btn-group">
			<button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Relation <span class="caret"></span></button>
			<ul class="dropdown-menu">
<?php
foreach(ModelUser_has_user::$shortNames as $k => $v):
	$a = ServiceAuth::getInstance()->getUser()->getUser_has_user($user);
	$b = $a->checkType($k);
	$c = $b ? 'del' : 'add';
?>
				<li><a href="/relation/<?php echo $c.'/'.$user->getId().'/'.$k; ?>" class="btn-update-relation <?php if($b) echo 'btn-primary'; ?>" data-id="<?php echo $user->getId(); ?>" data-action="<?php echo $c;?>" data-type="<?php echo $k; ?>"><?php echo ServiceRenderHtml::$longNames[$v]; ?></a></li>
<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php
		if($i % 3 == 2)
			echo '</div>';
		$i ++;
	endforeach;
?>