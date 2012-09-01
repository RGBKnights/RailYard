<?php echo $this->Html()->doc_type(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html()->charset($model->head->charset); ?>
	<title>
		<?php echo 'RailYard - The tracks of rapid development:'; ?>
		<?php echo $model->head->title; ?>
	</title>
	<?php echo $this->Html()->icon(); ?>
	<?php echo $this->Html()->css($model->head->references->css) ?>
	<?php echo $this->Html()->script($model->head->references->scripts) ?>
	
	<?php echo $this->Html()->style($model->head->styles) ?>
	<?php echo $this->Html()->script_block($model->head->scripts) ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<a href="http://venatiostudios.com/"><?php echo $model->body->header->title; ?></a>
			</h1>
		</div>
		<div id="content">
			<?php echo $this->Html()->flash(); ?>
			<?php echo $model->body->content; ?>
		</div>
		<div id="footer">
			<?php echo $model->body->footer; ?>
		</div>
	</div>
</body>
</html>

