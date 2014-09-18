<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="kasa"><?php echo $content_top; ?>

<div id="payments_types" class="right clearfix">
                <img alt="visa" src="image/data/payment icons/visa_straight_32px.png">
                      <img alt="mastercard" src="image/data/payment icons/mastercard_curved_32px.png">
                      <img alt="monebookers" src="image/data/payment icons/moneybookers_curved_32px.png">
                      <img alt="paypal" src="image/data/payment icons/paypal_curved_32px.png">
              </div>

  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

 <h1 style="color:#aaa;"><?php echo $heading_title; ?></h1> 

<div id="login" class="right">
    <h2><?php echo $text_returning_customer; ?></h2>
    <p><?php echo $text_i_am_returning_customer; ?></p>
    <b><?php echo $entry_email; ?></b><br />
    <input type="text" name="email" value="" />
    <br />
    <br />
    <b><?php echo $entry_password; ?></b><br />
    <input type="password" name="password" value="" />
    <br />
    <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
    <br />
    <input type="button" value="<?php echo $button_login; ?>" id="button-login" class="button" /><br />
    <br />
</div>

<script type="text/javascript"><!--
$('#checkout .checkout-content input[name=\'account\']').live('change', function() {
	if ($(this).attr('value') == 'register') {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_account; ?>');
	} else {
		$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
	}
});

$('.checkout-heading a').live('click', function() {
	$('.checkout-content').slideUp('slow');
	
	$(this).parent().parent().find('.checkout-content').slideDown('slow');
});



// Login
$('#button-login').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/login/validate',
		type: 'post',
		data: $('#login :input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-login').attr('disabled', true);
			$('#button-login').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-login').attr('disabled', false);
			$('.wait').remove();
		},				
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#checkout .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

// Register
$('#button-register').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/register/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-register').attr('disabled', true);
			$('#button-register').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-register').attr('disabled', false); 
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
						
			if (json['redirect']) {
				location = json['redirect'];				
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
					
				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}	
				
				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}	
																		
				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}	
				
				if (json['error']['city']) {
					$('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
				}	
				
				if (json['error']['postcode']) {
					$('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}	
				
				if (json['error']['country']) {
					$('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
				
				if (json['error']['password']) {
					$('#payment-address input[name=\'password\'] + br').after('<span class="error">' + json['error']['password'] + '</span>');
				}	
				
				if (json['error']['confirm']) {
					$('#payment-address input[name=\'confirm\'] + br').after('<span class="error">' + json['error']['confirm'] + '</span>');
				}																																	
			} else {
				<?php if ($shipping_required) { ?>				
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');
				
				if (shipping_address) {
					$.ajax({
						url: 'index.php?route=checkout/shipping_method',
						dataType: 'html',
						success: function(html) {
							$('#shipping-method .checkout-content').html(html);
							
							$('#payment-address .checkout-content').slideUp('slow');
							
							$('#shipping-method .checkout-content').slideDown('slow');
							
							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();											
							
							$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');									
							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	

							$.ajax({
								url: 'index.php?route=checkout/shipping_address',
								dataType: 'html',
								success: function(html) {
									$('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});	
				} else {
					$.ajax({
						url: 'index.php?route=checkout/shipping_address',
						dataType: 'html',
						success: function(html) {
							$('#shipping-address .checkout-content').html(html);
							
							$('#payment-address .checkout-content').slideUp('slow');
							
							$('#shipping-address .checkout-content').slideDown('slow');
							
							$('#checkout .checkout-heading a').remove();
							$('#payment-address .checkout-heading a').remove();
							$('#shipping-address .checkout-heading a').remove();
							$('#shipping-method .checkout-heading a').remove();
							$('#payment-method .checkout-heading a').remove();							

							$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});			
				}
				<?php } else { ?>
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);
						
						$('#payment-address .checkout-content').slideUp('slow');
						
						$('#payment-method .checkout-content').slideDown('slow');
						
						$('#checkout .checkout-heading a').remove();
						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();								
						
						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});					
				<?php } ?>

				$.ajax({
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
							
						$('#payment-address .checkout-heading span').html('<?php echo $text_checkout_payment_address; ?>');
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}	 
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});











//--></script>
<div id="payment-address">
<h1>1. Adresse und Kontaktinformationen</h1>
<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>
<?/*
<div class="left">
    <h2><?php echo $text_your_details; ?></h2>
	<div class="lab"><span class="required">*</span> <?php echo $entry_firstname; ?></div>
    <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_lastname; ?></div>
    <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_email; ?></div>
    <input type="text" name="email" value="<?php echo $email; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_telephone; ?></div>
    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><?php echo $entry_fax; ?></div>
    <input type="text" name="fax" value="<?php echo $fax; ?>" class="large-field" />
    <br />
    <br />
</div>
<div class="right">
    <h2><?php echo $text_your_address; ?></h2>
    <div class="lab"><?php echo $entry_company; ?></div>
    <input type="text" name="company" value="<?php echo $company; ?>" class="large-field" />
    <br />
    <br />
    <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;"> <div class="lab"><?php echo $entry_customer_group; ?></div>
        <?php foreach ($customer_groups as $customer_group) { ?>
        <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
        <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
        <br />
        <?php } else { ?>
        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
        <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
        <br />
        <?php } ?>
        <?php } ?>
        <br />
    </div>
    <div id="company-id-display"><div class="lab"><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></div>
        <input type="text" name="company_id" value="<?php echo $company_id; ?>" class="large-field" />
        <br />
        <br />
    </div>
    <div id="tax-id-display"><div class="lab"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></div>
        <input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="large-field" />
        <br />
        <br />
    </div>
    <div class="lab"><span class="required">*</span> <?php echo $entry_address_1; ?></div>
    <input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><?php echo $entry_address_2; ?></div>
    <input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_city; ?></div>
    <input type="text" name="city" value="<?php echo $city; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></div>
    <input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_country; ?></div>
    <select name="country_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
    </select>
    <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_zone; ?></div>
    <select name="zone_id" class="large-field">
    </select>
    <br />
    <br />
    <br />
</div>
*/?>

<div class="left">
    <h2><?php echo $text_your_details; ?></h2>
	<div class="lab"><span class="required">*</span> <?php echo $entry_firstname; ?></div>
    <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" />
        <br />
    <br />
	    <div class="lab"><span class="required">*</span> <?php echo $entry_address_1; ?></div>
    <input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field" />
        <br />
    <br />

      <div class="lab"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></div>
    <input type="text" name="postcode" value="<?php echo $postcode; ?>" class="large-field" />
    <br />
    <br />
	    
    <div class="lab"><span class="required">*</span> <?php echo $entry_email; ?></div>
    <input type="text" name="email" value="<?php echo $email; ?>" class="large-field" />
    
        <br />
    <br />
    	
	<div class="lab"><span class="required">*</span><?php echo $entry_zone; ?></div>
    <select name="zone_id" class="large-field">
    </select>

  <?/*  <div class="lab"><?php echo $entry_fax; ?></div> */?>
    <input type="text" name="fax" style="oveflow:hidden; height:0; padding:0; border:0; margin:0; float:left;" value="<?php echo $fax; ?>" class="large-field" />
 

	
</div>
<div class="right">
    <h2><?php echo $text_your_address; ?></h2>

    
    
    <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;"> <div class="lab"><?php echo $entry_customer_group; ?></div>
        <?php foreach ($customer_groups as $customer_group) { ?>
        <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
        <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
        
        <?php } else { ?>
        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
        <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
        
        <?php } ?>
        <?php } ?>
        
    </div>
    <div id="tax-id-display"><div class="lab"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></div>
        <input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="large-field" />
          <br />
    <br />  
        
    </div>

        <div class="lab"><span class="required">*</span> <?php echo $entry_lastname; ?></div>
    <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" />
        <br />
    <br />
    <div class="lab"><?php echo $entry_address_2; ?></div>
    <input type="text" name="address_2" value="<?php echo $address_2; ?>" class="large-field" />
    
        <br />
    <br />
    <div class="lab"><span class="required">*</span> <?php echo $entry_city; ?></div>
    <input type="text" name="city" value="<?php echo $city; ?>" class="large-field" />
    <br />
    <br />
    
    <div class="lab"><span class="required">*</span> <?php echo $entry_telephone; ?></div>
    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field" />
    <br />
    <br />
	<div class="lab"><span class="required">*</span> <?php echo $entry_country; ?></div>
    <select name="country_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
    </select>
    
	
</div>


<?php if ($shipping_required) { ?>
<div style="float:left; width:100%; margin:20px 0">
    <?php if ($shipping_address) { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" onchange="toogleShipping()" style="margin-left:15px;"/>
    <?php } else { ?>
    <input type="checkbox" name="shipping_address" value="1" id="shipping" onchange="toogleShipping()" style="margin-left:15px;" />
    <?php } ?>
    <label for="shipping"><strong><?php echo $entry_shipping; ?></strong></label>
</div>

<?php } ?>


<div class="right" style="padding:0 0 20px; width:100% !important;">
<div style="float:left; width:320px; margin-right:10px; margin-left:20px;">
    <div class="lab"><?php echo $entry_company; ?></div>
    <input type="text" name="company" value="<?php echo $company; ?>" class="large-field" />
</div><div style="float:left; width:320px;">
	<div id="company-id-display" class="lab"><?php echo $entry_company_id; ?></div>
    <input type="text" name="company_id" value="<?php echo $company_id; ?>" class="large-field" />
</div>
</div>	

</div>

<script type="text/javascript"><!--
    function toogleShipping()
    {
        var state = $('input[type=\'checkbox\']').prop('checked');



        if(state)
        {
            $('#shipping-address').css('display','none');
        }
        else
        {
            $('#shipping-address').css('display','block');
        }

    }
    $('#payment-address input[name=\'customer_group_id\']:checked').live('change', function() {
        var customer_group = [];

        <?php foreach ($customer_groups as $customer_group) { ?>
            customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
            customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
        <?php } ?>

        if (customer_group[this.value]) {
            if (customer_group[this.value]['company_id_display'] == '1') {
                $('#company-id-display').show();
            } else {
                $('#company-id-display').hide();
            }

            if (customer_group[this.value]['company_id_required'] == '1') {
                $('#company-id-required').show();
            } else {
                $('#company-id-required').hide();
            }

            if (customer_group[this.value]['tax_id_display'] == '1') {
                $('#tax-id-display').show();
            } else {
                $('#tax-id-display').hide();
            }

            if (customer_group[this.value]['tax_id_required'] == '1') {
                $('#tax-id-required').show();
            } else {
                $('#tax-id-required').hide();
            }
        }
    });

    $('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
    //--></script>
<script type="text/javascript"><!--
    $('#payment-address select[name=\'country_id\']').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#payment-postcode-required').show();
                } else {
                    $('#payment-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#payment-address select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#payment-address select[name=\'country_id\']').trigger('change');
    //--></script>

<?php if ($shipping_address) { ?>
<div id="shipping-address" style="display:none;">
<?php }else{ ?>
<div id="shipping-address" >
<?php } ?>
<table class="form">
    <tr>
        <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
        <td><input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
        <td><input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><?php echo $entry_company; ?></td>
        <td><input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
        <td><input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><?php echo $entry_address_2; ?></td>
        <td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><span class="required">*</span> <?php echo $entry_city; ?></td>
        <td><input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
        <td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" class="large-field" /></td>
    </tr>
    <tr>
        <td><span class="required">*</span> <?php echo $entry_country; ?></td>
        <td><select name="shipping_country_id" class="large-field">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $shipping_country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select></td>
    </tr>
    <tr>
        <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
        <td><select name="shipping_zone_id" class="large-field">
            </select></td>
    </tr>
</table>
</div>
<br />

<script type="text/javascript"><!--
    $('#shipping-address select[name=\'shipping_country_id\']').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping-address select[name=\'shipping_country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#shipping-address select[name=\'shipping_zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#shipping-address select[name=\'shipping_country_id\']').trigger('change');
    //--></script>




<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<div id="shipping-methods">
<h1>2. Versand</h1>
<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>

<p><?php echo $text_shipping_method; ?></p>
<table class="radio">
    <?php foreach ($shipping_methods as $shipping_method) { ?>
    <tr>
        <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
    </tr>
    <?php if (!$shipping_method['error']) { ?>
    <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <tr class="highlight">
        <td><?php if ($quote['code'] == $code || !$code) { ?>
            <?php $code = $quote['code']; ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
            <?php } ?></td>
        <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
        <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
        <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>
<br />
</div>
<?php } ?>


<br />


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<div id="payment-methods">
<h1>3. Zahlungsart</h1>
<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>

<p><?php echo $text_payment_method; ?></p>
<table class="radio">
    <?php foreach ($payment_methods as $payment_method) { ?>
    <tr class="highlight">
        <td><?php if ($payment_method['code'] == $code || !$code) { ?>
            <?php $code = $payment_method['code']; ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
            <?php } ?></td>
        <td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
    </tr>
    <?php } ?>
</table>
<br />
</div>
<?php } ?>

<div class="divplus">
<div style="float:left; width:99%; margin-top:10px;"><b><?php echo $text_comments; ?></b></div>
<textarea name="comment" rows="3" style="width:99%; border:1px solid #ddd; float:left; margin-top:10px;"><?php echo $comment; ?></textarea>
</div>

<script type="text/javascript"><!--
    $('input[name=\'shipping_method\']').change(function(){

        reloadTotals();

    });

    function reloadTotals()
    {
        $.ajax({
            url: 'index.php?route=checkout/checkout/reloadTotals',
            type: 'post',
            data: $('#shipping-methods input[type=\'radio\']:checked'),

            dataType: 'json',
            success: function(json) {

                html='';

                $.each( json['totals'], function( key, value ) {
                    html+=   '<tr>';
                    html+=    '<td colspan="3" class="price"><b>'+value['title']+':</b></td>';
                    html+=    '<td class="total">'+value['text']+'</td>';
                    html+=   '</tr>';
                });



                $('.checkout-product tfoot').html(html);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    $('.colorbox').colorbox({
        width: 640,
        height: 480
    });
    //--></script>

<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
<h1>4. Zusammenfassung</h1>
<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>

    <table>
        <thead>
        <tr>
            <td class="name"><?php echo $column_name; ?></td>
            <td class="model"><?php echo $column_model; ?></td>
            <td class="quantity"><?php echo $column_quantity; ?></td>
            <td class="price"><?php echo $column_price; ?></td>
            <td class="total"><?php echo $column_total; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
            <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?>
                <?php if($product['kaucja']) { ?>
                <br />
                &nbsp;<small> - <?php echo $text_kaucja; ?>: <?php echo $product['kaucja']; ?> <?php echo $product['kaucja_cost']; ?></small>
                <?php } ?>
            </td>
            <td class="model"><?php echo $product['model']; ?></td>
            <td class="quantity"><?php echo $product['quantity']; ?></td>
            <td class="price"><?php echo $product['price']; ?></td>
            <td class="total"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
            <td class="name"><?php echo $voucher['description']; ?></td>
            <td class="model"></td>
            <td class="quantity">1</td>
            <td class="price"><?php echo $voucher['amount']; ?></td>
            <td class="total"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php foreach ($totals as $total) { ?>
        <tr>
            <td colspan="3" class="price"><b><?php echo $total['title']; ?>:</b></td>
            <td class="total"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
        </tfoot>
    </table>
	
	<?php if ($text_agree) { ?>
<div class="buttons" id="agree">
    <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" id="potw1"/>
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" id="potw1"/>
        <?php } ?>

    </div>
    <div class="right">Die <a href="<?php echo $this->config->get('config_url'); ?>index.php?route=information/information/info&information_id=7" alt="Widerrufsbelehrung" class="colorbox cboxElement"><b>Widerrufsbelehrung</b></a> habe ich zur Kenntnis genommen.
        <?php if ($agree2) { ?>
        <input type="checkbox" name="agree2" value="1" checked="checked" id="potw2"/>
        <?php } else { ?>
        <input type="checkbox" name="agree2" value="1" id="potw2"/>
        <?php } ?>

    </div>
</div>
<?php } ?>
	
	
    <div class="buttons">
        <div class="right">
            <input type="button" onclick="finalize()"  class="button action" value="<?php echo $text_order_confirm ?>"/>
        </div>
    </div>
</div>
<div class="payment" style="display:none;"></div>
<?php } else { ?>
<script type="text/javascript"><!--
    location = '<?php echo $redirect; ?>';
    //--></script>
<?php } ?>

<script>
     function finalize()
     {
	 
	var spr1 = $('#potw1').is(':checked');
	var spr2 = $('#potw2').is(':checked'); 	 
	 
	if (spr1) { $('#potw1').parent().removeClass('potwalert'); } else { $('#potw1').parent().addClass('potwalert'); }
	if (spr2) { $('#potw2').parent().removeClass('potwalert'); } else { $('#potw2').parent().addClass('potwalert'); }
	 	 
		 
         $.ajax({
             url: 'index.php?route=checkout/checkout/validate',
             type: 'post',
             data: $('input[type=\'text\'], textarea, select, input[type=\'checkbox\'], input[type=\'radio\']:checked, input[type=\'password\'], input[type=\'hidden\']'),
             dataType: 'json',
             success: function(json) {
			
                 $('.warning, .error').remove();

                 if (json['redirect']) {		
				                     
					 location = json['redirect'];
					 
                 } else if (json['error']) { 
                     // warningi
                     // guest
                     if (json['error']['warning']) {
                         $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                         $('.warning').fadeIn('slow');
                     }

                     if (json['error']['firstname']) { 
                         $('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
                     }

                     if (json['error']['lastname']) { 
                         $('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
                     }

                     if (json['error']['email']) {
                         $('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
                     }

                     if (json['error']['telephone']) {
                         $('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
                     }

                     if (json['error']['company_id']) {
                         $('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
                     }

                     if (json['error']['tax_id']) {
                         $('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                     }

                     if (json['error']['address_1']) {
                         $('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
                     }

                     if (json['error']['city']) {
                         $('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
                     }

                     if (json['error']['postcode']) {
                         $('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
                     }

                     if (json['error']['country']) {
                         $('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
                     }

                     if (json['error']['zone']) {
                         $('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
                     }

                     // guest shipping
                     if (json['error']['warning']) {
                         $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                         $('.warning').fadeIn('slow');
                     }

                     if (json['error']['shipping_firstname']) {
                         $('#shipping-address input[name=\'shipping_firstname\']').after('<span class="error">' + json['error']['shipping_firstname'] + '</span>');
                     }

                     if (json['error']['shipping_lastname']) {
                         $('#shipping-address input[name=\'shipping_lastname\']').after('<span class="error">' + json['error']['shipping_lastname'] + '</span>');
                     }

                     if (json['error']['shipping_email']) {
                         $('#shipping-address input[name=\'shipping_email\']').after('<span class="error">' + json['error']['shipping_email'] + '</span>');
                     }

                     if (json['error']['shipping_telephone']) {
                         $('#shipping-address input[name=\'shipping_telephone\']').after('<span class="error">' + json['error']['shipping_telephone'] + '</span>');
                     }

                     if (json['error']['shipping_address_1']) {
                         $('#shipping-address input[name=\'shipping_address_1\']').after('<span class="error">' + json['error']['shipping_address_1'] + '</span>');
                     }

                     if (json['error']['city']) {
                         $('#shipping-address input[name=\'shipping_city\']').after('<span class="error">' + json['error']['shipping_city'] + '</span>');
                     }

                     if (json['error']['postcode']) {
                         $('#shipping-address input[name=\'shipping_postcode\']').after('<span class="error">' + json['error']['shipping_postcode'] + '</span>');
                     }

                     if (json['error']['country']) {
                         $('#shipping-address select[name=\'shipping_country_id\']').after('<span class="error">' + json['error']['shipping_country'] + '</span>');
                     }

                     if (json['error']['zone']) {
                         $('#shipping-address select[name=\'shipping_zone_id\']').after('<span class="error">' + json['error']['shipping_zone'] + '</span>');
                     }
                     // end shipping

                     // shipping method
                     if (json['error']['shipping']) {
                         $('#shipping-methods').after('<span class="error">' + json['error']['shipping'] + '</span>');


                     }
                     // payment method
                     if (json['error']['payment']) {
                         $('#payment-methods').after('<span class="error">' + json['error']['payment'] + '</span>');


                     }
                     // end warnings
                     if (json['error']['agree']) {
                         $('#agree').after('<span class="error">' + json['error']['agree'] + '</span>');

                     }


                 } else {

				 if(spr1 && spr2) {
				 
                     $.ajax({
                         url: 'index.php?route=checkout/checkout/getPayment',
                         dataType: 'json',
                         success: function(json) {
                             if(json['error'])
                             {

                             }
                             else
                             {
							
							 
                                 $('.payment').append(json['payment']);

                                 $("#button-confirm").trigger("click");


                             }

                         },
                         error: function(xhr, ajaxOptions, thrownError) {
                             alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                         }
                     }); 
					 
					 } else { 
					 
						$(document.body).scrollTop($('#agree').offset().top);
						
					 }

                 }
             },
             error: function(xhr, ajaxOptions, thrownError) {
                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
             }
         })

     }
</script>
<script>
    $(document).ready(function(){
        reloadTotals();
    })
</script>

</div>
<?php echo $footer; ?>