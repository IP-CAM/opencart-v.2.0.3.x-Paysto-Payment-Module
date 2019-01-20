<?php if (isset($error_warning)) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" value="<?php echo $x_login; ?>" name="x_login">
  <input type="hidden" value="<?php echo $x_email; ?>" name="x_email">
  <input type="hidden" value="<?php echo $x_fp_sequence; ?>" name="x_fp_sequence">
  <input type="hidden" value="<?php echo $x_invoice_num; ?>" name="x_invoice_num">
  <input type="hidden" value="<?php echo $x_amount; ?>" name="x_amount">
  <input type="hidden" value="<?php echo $x_currency_code; ?>" name="x_currency_code">
  <input type="hidden" value="<?php echo $x_fp_timestamp; ?>" name="x_fp_timestamp">
  <input type="hidden" value="<?php echo $x_description; ?>" name="x_description">
  <input type="hidden" value="<?php echo $x_fp_hash; ?>" name="x_fp_hash">
  <input type="hidden" value="<?php echo $x_relay_response; ?>" name="x_relay_response">
  <input type="hidden" value="<?php echo $x_relay_url; ?>" name="x_relay_url">
  <input type="hidden" value="<?php echo $x_line_item; ?>" name="x_line_item">
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
