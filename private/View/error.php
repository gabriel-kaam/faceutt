<h1>Oops! Something went wrong :(</h1>
<pre><?php
if(isset($e) && $e instanceof Exception)
	echo $this->getData('e')->getMessage()."\n\n".$this->getData('e')->getTraceAsString();
else
	echo 'Erreur inconnue';
?>

			- Team #500</pre>