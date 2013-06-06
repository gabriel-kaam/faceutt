<?php
$progs = array('ISI','SRT','MTE','SI','SIT','SM');
?><h1>Recherche de profils</h1>
<div class="row-fluid show-grid search">
	<div class="span12">
		<form action="/search" method="post" class="input-prepend">
			<fieldset>
				<legend>Par Programme</legend>
				<select name="prog"><?php foreach($progs as $v) echo "<option>$v</option>"; ?></select>
				<input name="search1" class="btn" type="submit" />
			</fieldset>
		</form>
		<form action="" method="post">
			<fieldset>
				<legend>Par Programme &amp; Semestre</legend>
				<select name="prog"><?php foreach($progs as $v) echo "<option>$v</option>"; ?></select>
				<select name="semester"><?php for($i = 1; $i <= 8; $i++) echo "<option>$i</option>"; ?></select>
				<input name="search2" class="btn" type="submit" />
			</fieldset>
		</form>
		<form action="" method="post">
			<fieldset>
				<legend>Par Sexe</legend>
				<select name="sex"><option>homme</option><option>femme</option></select>
				<input name="search3" class="btn" type="submit" />
			</fieldset>
		</form>
		<form action="" method="post">
			<fieldset>
				<legend>En relation avec moi</legend>
				<input name="search4" class="btn" type="submit" />
			</fieldset>
		</form>
		<form action="" method="post">
			<fieldset>
				<legend>Tous</legend>
				<input name="search5" class="btn" type="submit" />
			</fieldset>
		</form>
	</div>
</div>