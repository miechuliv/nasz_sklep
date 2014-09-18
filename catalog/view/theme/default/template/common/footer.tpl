</div></div>

<?/*
<div id="mychat"><a href="http://www.phpfreechat.net">Creating chat rooms everywhere - phpFreeChat</a></div>
*/?>

<?php if(Utilities::isController('checkout/checkout') || Utilities::isController('checkout/cart') || Utilities::isController('product/category')) { ?>

<?php } else { ?>
	<div id="menumobile" class="mobileshow">
		<h3>Menu</h3>
		<div>	
			<?php if ($categories) { ?>
				<div id="menu2">
				  <ul>
					<?php foreach ($categories as $category) { ?>
					<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
					  <?php /* if ($category['children']) { ?>
					  <div>
						<?php for ($i = 0; $i < count($category['children']);) { ?>
						<ul>
						  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
						  <?php for (; $i < $j; $i++) { ?>
						  <?php if (isset($category['children'][$i])) { ?>
						  <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
						  <?php } ?>
						  <?php } ?>
						</ul>
						<?php } ?>
					  </div>
					  <?php } */ ?>
					</li>
					<?php } ?>
					<?php foreach($informations as $information){ ?>
					  <li><a href="<?php echo $information['href']; ?>"><?php echo $information['name']; ?></a></li>
					<?php } ?>
				  </ul>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>

<?php /* if(!Utilities::isController('common/home')) { ?>
	<div id="searchmobile" class="mobileshow">
		<div>
			<div class="table"> 
				<div class="search-con ">
					<input type="text" id="szuk" class="borderb" name="search" placeholder="<?php echo $this->language->get('search_foo'); ?>" value="<?php echo $this->language->get('search_foo'); ?>" lang="pl" />
				</div>
				<div>
					<div class="button-search <?php if(Utilities::isController('checkout/cart') || Utilities::isController('product/product')) { ?>intense<?php }?>"><i style="font-size:30px; color:#fff" class="fa fa-search"></i></div>
				</div>
			</div>
		</div>
	</div>
<?php } */?>

<div id="footer">
<div class="poziom">
	<div>
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>" rel="nofollow"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
<div class="column">
	<h3>Facebook</h3>
</div>
  <div class="column">
	<?php echo $language; ?>
	<?php echo $currency; ?>
  </div>
</div>

</div>
</div>
<div id="back-top"></div>

<script src="catalog/view/javascript/jquery/livesearch.js"></script>
<script src="catalog/view/javascript/common.js"></script>
<script src="catalog/view/javascript/selecter/src/jquery.fs.selecter.js"></script>

<?php if(Utilities::isController('common/home')) { ?>
	<script type="text/javascript" src="catalog/view/javascript/jquery.cycle2.min.js"></script>
	<script>
		$('.cycle-slideshow').cycle({
			next: '.control > .cycle-next',
			prev: '.control > .cycle-prev',
			swipe: true
		});
	</script>
<?php } ?>

<?php if(Utilities::isController('product/product')) { ?>
	<script src="catalog/view/javascript/selecter/src/jquery.fs.selecter.js"></script>
	<script src="catalog/view/javascript/table.js"></script>
	<script src="catalog/view/javascript/gal/jquery.desoslide.min.js"></script>
	<script src="catalog/view/javascript/autocomplete.js"></script>
<?php } else if(Utilities::isController('checkout/cart') || Utilities::isController('checkout/checkout')) { ?>
	<script src="catalog/view/javascript/table.js"></script>
<?php } ?>

<!--[if gte IE 9]><link rel="stylesheet" href="catalog/view/theme/default/stylesheet/ie9.css" /><![endif]-->

<script>

    $('#button-cart').bind('click', function() {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea, input[name="kaucja"]'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            // $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
							$('#option-' + i).addClass('selecterror');
                        }
                    }
                }
                if (json['success']) {

                    html = cartNotify(json);

                    $('#notification').html(html);

                    $('.success').fadeIn('slow');

                    $('#cart-total').html(json['total']);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
            }
        });
    });
	
	$('.option select').change(function(){
		$(this).parent().parent().removeClass('selecterror');
	});
	
	function resize() {
		var okno = $(window).height();
		var stopa = $('#footer').height();
		var header = $('#header').height();
		var menu = $('#header-menu').height();
		var kontener = $('#container');
		kontener.css('min-height',okno-header-stopa-menu-85);	
	}
	
	function focus() {
		var wyszukiwarka = $('#szuk');
		wyszukiwarka.focus();
	}
	
	$(document).ready(function(){
		resize();
		focus();
		$("select").selecter();			
	});
	
	$(window).resize(function(){
		setTimeout(function(){
			resize();
		},500);
	});
	
	var formy = $('input');
	
		formy.on("keyup", function(){
			if(this.value.length > 3){
				$(this).addClass('jestok');
			} else {
				$(this).removeClass('jestok');
			}
		});
		
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('#back-top').fadeIn();
		} else {
			$('#back-top').fadeOut();
		}
	});
	
	$('#back-top').click(function(){
		$('html, body').animate({scrollTop : 0},500);
		return false;
	});
	

</script>


	
<?/*
<script type="text/javascript">
    $('#mychat').phpfreechat({ serverUrl: '/phpfreechat-2.1.0/server' });
</script>
*/?>

</body>
</html>