<?php
function showDropDown(ModelParameter $param) {
	$c = $param->getVisibility();
	$s = '<div class="btn-group btn-group-update-parameterV">
				<button class="btn btn-small dropdown-toggle btn-update-parameterV" data-toggle="dropdown">
				<span class="t">'.$c.'</span>&nbsp;<span class="caret"></span></button>
				<ul class="dropdown-menu">';
	foreach(ModelParameter::$validVisibilities as $v)
		$s .= "<li><a href='/parameter/updateV/{$param->getName()}/$v' class='btn-update-parameterV ".($c == $v ? 'btn-primary' : '')."' data-id='{$param->getName()}' data-value='$v'>$v</a></li>";
	$s .= '</ul>
</div>';
	return $s;
}
?>
<?php if($guest): ?>
<h1>Profil de <?php echo $user->getLogin(); ?></h1>
<section>
<div class="row-fluid show-grid relationsBtn">
<?php
foreach(ModelUser_has_user::$shortNames as $k => $v):
	$a = ServiceAuth::getInstance()->getUser()->getUser_has_user($user);
	$b = $a->checkType($k);
?>
	<div class="span3"><a href="/relation" class="btn-update-relation btn btn-large btn-block <?php if($b) echo 'btn-primary'; ?>" type="button" data-id="<?php echo $user->getId(); ?>" data-action="<?php echo $b ? 'del' : 'add';?>" data-type="<?php echo $k; ?>"><?php echo ServiceRenderHtml::$longNames[$v]; ?></a></div>
<?php endforeach; ?>
</div>
<?php else: ?>
<h1>Bonjour <?php echo $user->getLogin(); ?>,</h1>
<section id="myProfile">
<h2>Mon profil</h2>
<?php endif; ?>
<div class="row-fluid show-grid">
	<div class="span6">
		<dl class="dl-profile">
			<?php
			foreach($user->getProfile()->getParameters() as $param)
				if(!in_array($param->getName(), array('photos', 'skills')))
					if(!$guest || ServiceAuth::getInstance()->getUser()->isAllowedToSee($user, $param))
						require 'View/profile/param.php';
			?>
		</dl>
	</div>
	<div class="span6">
		<dl class="dl-profile">
<?php if(!$guest || ServiceAuth::getInstance()->getUser()->isAllowedToSee($user, 'skills')): ?>
			<dt class="visibility-<?php echo $user->getProfile()->getParameter('skills')->getVisibility(); ?>">Liste de compétences<?php if(!$guest) echo showDropDown($user->getProfile()->getParameter('skills')); ?></dt>
			<dd class="update-skill"><?php
			echo '<ul>';
			if($a = $user->getSkills())
				foreach($a as $v)
					echo '<li>'.$v->getName().'<a href="/parameter/skill/del/'.$v->getId().'" class="btn btn-danger btn-small delete">Supprimer</a></li>';
			else echo '<li class="empty">Pas de compétence</li>';
			echo '</ul>';
			if(!$guest)
				echo '<form  style="margin-top:10px" action="/parameter/skill/add" method="post" class="form-search"><div class="input-append"><input type="text" name="value" value="" class="btn-small search-query" placeholder="Ajouter une compétence" required="required" style="line-height:100%" /><input type="submit" value="Valider" class="btn btn-small" /></div></form>';
			?></dd>
<?php endif; ?>
<?php if(!$guest || ServiceAuth::getInstance()->getUser()->isAllowedToSee($user, 'photos')): ?>
			<dt class="visibility-<?php echo $user->getProfile()->getParameter('photos')->getVisibility(); ?>">Liste de photos<?php if(!$guest) echo showDropDown($user->getProfile()->getParameter('photos')); ?></dt>
			<dd class="update-photo"><?php
			echo '<ul>';
			if($a = $user->getPhotos())
				foreach($a as $v)
					echo '<li><a href="/uploads/'.$v->getId().'" target="_blank">Image #'.$v->getId().'</a><a href="/parameter/photo/del/'.$v->getId().'" class="btn btn-danger btn-small delete">Supprimer</a></li>';
			else echo '<li class="empty">Pas de photo</li>';
			echo '</ul>';
			if(!$guest)
				echo '<form style="margin-top:10px" action="/parameter/photo/add" enctype="multipart/form-data" method="post" class="form-search"><input type="file" name="value" class="btn btn-small" title="Ajouter une photo" required="required" /><input type="submit" value="Valider" class="btn btn-small" /></form>';
			?></dd>
<?php endif; ?>
		</dl>
	</div>
</div>
<div class="row-fluid show-grid relations">
	<div class="span12">
		<h3>Relations</h3>
		<div class="row-fluid show-grid">
			<?php
			if($b = $user->getUser_has_users())
				foreach($b as $relation) {
					echo '<div class="span2">&#149; <a href="/profile/'.$relation->getUser2()->getLogin().'">'.$relation->getUser2()->getLogin().'</a></div></div><div class="row-fluid show-grid">';
					foreach(ModelUser_has_user::$shortNames as $k => $v)
						if($relation->checkType($k))
							echo '<div class="span3" style=""><i class="icon-'.ModelUser_has_user::$icoNames[$v].'"></i> '.ServiceRenderHtml::$longNames[$v].'</div>';
					echo '</div><div class="row-fluid show-grid">';
				}
			else
				echo '<div class="span12">Aucune relation !</div>';
			?>
		</div>	
	</div>
</div>
</section>