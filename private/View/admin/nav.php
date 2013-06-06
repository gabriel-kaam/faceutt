<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="/admin" rel="home">Administration</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li<?php if($this->getFile() == 'logs')		echo ' class="active"'; ?>><a href="/admin/logs">Traces</a></li>
					<li<?php if($this->getFile() == 'stats')	echo ' class="active"'; ?>><a href="/admin/stats">Comportements</a></li>
					<li><a href="/logout">Se déconnecter</a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>
