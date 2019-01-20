<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-paysto" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Paysto</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paysto" class="form-horizontal">

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-paysto_x_login"><?php echo $entry_x_login; ?></label>
						<div class="col-sm-10">
							<input type="text" name="paysto_x_login" value="<?php echo $paysto_x_login; ?>" placeholder="<?php echo $entry_x_login; ?>" id="input-paysto_x_login" class="form-control" />
						</div>
						<?php if ($error_x_login) { ?>
						<div class="text-danger"><?php echo $error_x_login; ?></div>
						<?php } ?>
					</div>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-paysto_secret_key"><?php echo $entry_secret_key; ?></label>
						<div class="col-sm-10">
							<input type="text" name="paysto_secret_key" value="<?php echo $paysto_secret_key; ?>" placeholder="<?php echo $entry_secret_key; ?>" id="input-paysto_secret_key" class="form-control" />
						</div>
						<?php if ($error_secret_key) { ?>
						<div class="text-danger"><?php echo $error_secret_key; ?></div>
						<?php } ?>
					</div>

					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-paysto_description"><?php echo $entry_description; ?></label>
						<div class="col-sm-10">
							<textarea name="paysto_description" placeholder="<?php echo $entry_description; ?>" id="input-paysto_description" class="form-control"><?php echo $paysto_description; ?></textarea>
						</div>
						<?php if ($error_description) { ?>
						<div class="text-danger"><?php echo $error_description; ?></div>
						<?php } ?>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_useOnlyList">

							<?php echo $entry_useOnlyList; ?>
						</label>
						<div class="col-sm-10">
							<input type="hidden" name="paysto_useOnlyList" value="0"/>
							<input type="checkbox" name="paysto_useOnlyList" id="input-paysto_useOnlyList" value="1" <?php if ($paysto_useOnlyList) { echo 'checked'; } ?>/>
						</div>
						<label class="col-sm-2 control-label" for="input-paysto_useOnlyList">

						</label>
						<?php if ($error_useOnlyList) { ?>
							<div class="text-danger"><?php echo $error_useOnlyList; ?></div>
						<?php } ?>
					</div>


					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_serversList"><?php echo $entry_serversList; ?></label>
						<div class="col-sm-10">
							<textarea name="paysto_serversList" placeholder="<?php echo $entry_serversList; ?>" id="input-paysto_serversList" class="form-control"><?php echo $paysto_serversList; ?></textarea>
						</div>
						<?php if ($error_serversList) { ?>
							<div class="text-danger"><?php echo $error_serversList; ?></div>
						<?php } ?>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_order_status_id"><?php echo $entry_order_status; ?></label>
						<div class="col-sm-10">
							<select name="paysto_order_status_id" id="input-paysto_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paysto_order_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="row">
							<div class="col-sm-3 text-center">
								<b><?php echo $entry_class_tax; ?></b>
							</div>
							<div class="col-sm-3 text-center">
								<b><?php echo $entry_text_tax; ?></b>
							</div>
						</div>

						<label class="col-sm-2 control-label"><?php echo $entry_tax; ?></label>
						<?php $class_row = 0; ?>
						<?php foreach ($paysto_classes as $class) { ?>
						<?php if ($class_row > 0) { ?>
						<label class="col-sm-2 control-label class-row<?php echo $class_row; ?>"></label>
						<?php } ?>
						<div class="row rule_tax class-row<?php echo $class_row; ?>">
							<div class="col-sm-3">
								<select name="paysto_classes[<?php echo $class_row; ?>][paysto_nalog]" class="form-control">
									<?php foreach ($tax_classes as $tax_class) { ?>
									<option <?php echo $tax_class['tax_class_id'] == $class['paysto_nalog'] ? 'selected' : ''; ?> value="<?php echo $tax_class['tax_class_id'];?>"><?php echo $tax_class['title'];?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-3">
								<select name="paysto_classes[<?php echo $class_row; ?>][paysto_tax_rule]" class="form-control">
									<?php foreach ($tax_rules as $tax) { ?>
									<option <?php echo $tax['id'] == $class['paysto_tax_rule'] ? 'selected' : ''; ?> value="<?php echo $tax['id'];?>"><?php echo $tax['name'];?></option>
									<?php } ?>
								</select>
							</div>
							<?php if ($class_row > 0) { ?>
							<div class="col-sm-1">
								<button type="button" onclick="$('.class-row<?php echo $class_row; ?>').remove();" class="btn btn-primary button_remove_rule_tax">Удалить</button>
							</div>
							<?php } ?>
							<?php $class_row++; ?>
						</div>
						<?php } ?>
						<label class="col-sm-2 control-label"></label>
						<div class="row">
							<div class="col-sm-2">
								<button type="button" id="button_add_taxt_rule" onclick="addClassRow()" class="btn btn-primary">Добавить</button>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
							<select name="paysto_geo_zone_id" id="input-paysto_geo_zone_id" class="form-control">
								<option value="0"><?php echo $text_all_zones; ?></option>
								<?php foreach ($geo_zones as $geo_zone) { ?>
								<?php if ($geo_zone['geo_zone_id'] == $paysto_geo_zone_id) { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="paysto_status" id="input-paysto_status" class="form-control">
								<?php if ($paysto_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_log"><?php echo $entry_log; ?></label>
						<div class="col-sm-10">
							<select name="paysto_log" id="input-paysto_log" class="form-control">
								<?php if ($paysto_log) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-paysto_sort_order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="paysto_sort_order" value="<?php echo $paysto_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-paysto_sort_order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
		<style>
			.row{
				padding: 5px;
			}
		</style>
		<script>
			var class_row = <?php echo $class_row; ?>;

			var addClassRow = function() {
				html = '<label class="col-sm-2 control-label class-row'+ class_row +'"></label>';
				html += '<div class="row class-row'+ class_row +'">';
				html += '<div class="col-sm-3">';
				html +=	'<select name="paysto_classes['+ class_row +'][paysto_nalog]" class="form-control">';
				html +=	'<?php foreach ($tax_classes as $tax_class) { ?>';
				html +=	'<option <?php echo $tax_class["tax_class_id"] == $class["paysto_nalog"] ? "selected" : ""; ?> value="<?php echo $tax_class["tax_class_id"];?>"><?php echo $tax_class["title"];?></option>';
				html +=	'<?php } ?>';
				html += '</select>';
				html += '</div>';
				html += '<div class="col-sm-3">';
				html +=	'<select name="paysto_classes['+ class_row +'][paysto_tax_rule]" class="form-control">';
				html += '<?php foreach ($tax_rules as $tax) { ?>';
				html += '<option <?php echo $tax["id"] == $class["paysto_tax_rule"] ? "selected" : ""; ?> value="<?php echo $tax["id"];?>"><?php echo $tax["name"];?></option>';
				html +=	'<?php } ?>';
				html += '</select>';
				html += '</div>';
				html += '<div class="col-sm-1">';
				html += '<button type="button" onclick="$(\'.class-row' + class_row + '\').remove();" class="btn btn-primary button_remove_rule_tax">Удалить</button>';
				html += '</div>';
				$('.rule_tax:last').after(html);

				class_row++;
			}
		</script>
		<?php echo $footer; ?>
