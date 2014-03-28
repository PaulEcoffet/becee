	<footer>
	</footer>

<?php
	if(isset($scripts))
	{
		foreach($scripts as $elt)
		{
			if(substr($elt, 0, 4) != "http")
			{
				$elt = './script/'.$elt.'.js';
			}
?>
	<script type="text/javascript" src="<?php echo $elt; ?>"></script>
<?php
		}
	}
?>
</body>
</html>