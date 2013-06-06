<form class="form-signin" action="/login" method="post">
	<h2 class="form-signin-heading">Se connecter</h2>
	<input type="text" class="input-block-level" placeholder="Pseudo" name="login" value="<?php echo @$_POST['login']; ?>" />
	<input type="password" class="input-block-level" placeholder="Mot de passe" name="password" value="" />
	<label class="checkbox">
		<input type="checkbox" name="remember_me" checked="checked" />
		Se souvenir de moi
	</label>
	<div style="text-align:right">
		<input class="btn btn-large btn-primary" type="submit" value="Valider" />
	</div>
	<div>
		<a href="/subscribe" style="font-size:.9em">Créer un profil</a> | <a href="/admin" style="font-size:.9em">Administrateur ?</a>
	</div> 
</form>
