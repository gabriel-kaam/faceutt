<form class="form-signin" action="/subscribe" method="post">
	<h2 class="form-signin-heading">Créer un profil</h2>
	<input type="text" class="input-block-level" required="required" placeholder="Pseudo" name="login" value="<?php echo @$_POST['login']; ?>" autocomplete="off" />
	<input type="password" class="input-block-level" required="required" placeholder="Mot de passe" name="pass1" value="" autocomplete="off" />
	<input type="password" class="input-block-level" required="required" placeholder="Confirmation" name="pass2" value="" autocomplete="off" />
	<div style="text-align:right">
		<input class="btn btn-large btn-primary" type="submit" value="Valider" />
	</div>
	<div>
		<a href="/login" style="font-size:.9em">Se connecter</a>
	</div> 
</form>
