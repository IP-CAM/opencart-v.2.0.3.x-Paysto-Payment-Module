<?php if (isset($error_warning)) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" value="<?php echo $x_login; ?>" name="LMI_MERCHANT_ID">
  <input type="hidden" value="<?php echo $amount; ?>" name="LMI_PAYMENT_AMOUNT">
  <input type="hidden" value="<?php echo $lmi_currency; ?>" name="LMI_CURRENCY">
  <input type="hidden" value="<?php echo $order_id; ?>" name="LMI_PAYMENT_NO">
  <input type="hidden" value="<?php echo $description; ?>" name="LMI_PAYMENT_DESC">
  <input type="hidden" value="<?php echo $email; ?>" name="LMI_PAYER_EMAIL">
  <input type="hidden" value="<?php echo $sign; ?>" name="SIGN">
  <?php foreach ($order_check as $check) { ?>
  <input type="hidden" value="<?php echo $check['name']; ?>" name="LMI_SHOPPINGCART.ITEMS[<?php echo $pos; ?>].NAME">
  <input type="hidden" value="<?php echo $check['quantity']; ?>" name="LMI_SHOPPINGCART.ITEMS[<?php echo $pos; ?>].QTY">
  <input type="hidden" value="<?php echo $check['price']; ?>" name="LMI_SHOPPINGCART.ITEMS[<?php echo $pos; ?>].PRICE">
  <input type="hidden" value="<?php echo $check['tax']; ?>" name="LMI_SHOPPINGCART.ITEMS[<?php echo $pos; ?>].TAX">
  <?php $pos++; } ?>
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>
