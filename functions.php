<?php

/**
 * Setup Child Theme Styles
 */
function km_oficina_enqueue_styles()
{
	wp_enqueue_style('km_oficina-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0');
}
// add_action( 'wp_enqueue_scripts', 'km_oficina_enqueue_styles', 20 );


/**
 * Setup Child Theme Palettes
 *
 * @param string $palettes registered palette json.
 * @return string
 */
function km_oficina_change_palette_defaults($palettes)
{
	$palettes = '{"palette":[{"color":"#c39a5f","slug":"palette1","name":"Palette Color 1"},{"color":"#b38944","slug":"palette2","name":"Palette Color 2"},{"color":"#291c0e","slug":"palette3","name":"Palette Color 3"},{"color":"#4f4f4f","slug":"palette4","name":"Palette Color 4"},{"color":"#5c5c5c","slug":"palette5","name":"Palette Color 5"},{"color":"#989898","slug":"palette6","name":"Palette Color 6"},{"color":"#ebe8e5","slug":"palette7","name":"Palette Color 7"},{"color":"#f5f5f5","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#dd6b20","slug":"palette1","name":"Palette Color 1"},{"color":"#cf3033","slug":"palette2","name":"Palette Color 2"},{"color":"#27241d","slug":"palette3","name":"Palette Color 3"},{"color":"#423d33","slug":"palette4","name":"Palette Color 4"},{"color":"#504a40","slug":"palette5","name":"Palette Color 5"},{"color":"#625d52","slug":"palette6","name":"Palette Color 6"},{"color":"#e8e6e1","slug":"palette7","name":"Palette Color 7"},{"color":"#faf9f7","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3296ff","slug":"palette1","name":"Palette Color 1"},{"color":"#003174","slug":"palette2","name":"Palette Color 2"},{"color":"#ffffff","slug":"palette3","name":"Palette Color 3"},{"color":"#f7fafc","slug":"palette4","name":"Palette Color 4"},{"color":"#edf2f7","slug":"palette5","name":"Palette Color 5"},{"color":"#cbd2d9","slug":"palette6","name":"Palette Color 6"},{"color":"#1A202C","slug":"palette7","name":"Palette Color 7"},{"color":"#252c39","slug":"palette8","name":"Palette Color 8"},{"color":"#2D3748","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}';
	return $palettes;
}
add_filter('kadence_global_palette_defaults', 'km_oficina_change_palette_defaults', 20);

/**
 * Setup Child Theme Defaults
 *
 * @param array $defaults registered option defaults with kadence theme.
 * @return array
 */
function km_oficina_change_option_defaults($defaults)
{
	$new_defaults = '{"heading_font":{"family":"Playfair Display","google":true,"variant":["regular","italic","500","500italic","600","600italic","700","700italic","800","800italic","900","900italic"]},"h1_font":{"size":{"desktop":32},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"normal","variant":"regualar","color":"palette3"},"h2_font":{"size":{"desktop":28},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"normal","variant":"regualar","color":"palette3"},"h3_font":{"size":{"desktop":24},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"normal","variant":"regualar","color":"palette3"},"h4_font":{"size":{"desktop":22},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"normal","variant":"regualar","color":"palette4"},"h5_font":{"size":{"desktop":20},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"normal","variant":"regualar","color":"palette4"},"base_font":{"size":{"desktop":17},"lineHeight":{"desktop":1.6000000000000001},"family":"Raleway","google":true,"weight":"400","variant":"regular","color":"palette4"},"h6_font":{"size":{"desktop":18},"lineHeight":{"desktop":1.5},"family":"inherit","google":false,"weight":"700","variant":"700","color":"palette5"},"title_above_font":{"size":{"desktop":""},"lineHeight":{"desktop":""},"family":"inherit","google":false,"weight":"","variant":"","color":""},"footer_html_content":"{copyright} {year} {site-title}","custom_logo":1655,"logo_layout":{"include":{"mobile":"","tablet":"","desktop":"logo_only"},"layout":{"mobile":"","tablet":"","desktop":"standard"},"flag":true}}';
	$new_defaults = json_decode($new_defaults, true);
	return wp_parse_args($new_defaults, $defaults);
}
add_filter('kadence_theme_options_defaults', 'km_oficina_change_option_defaults', 20);

function kaksi_subscription_product_string($subscription_string, $product, $include)
{
	if ($include['sign_up_fee']) {
		$subscription_string = str_replace('taxa de inscrição', 'caução', $subscription_string);
	}
	return $subscription_string;
}
add_filter('woocommerce_subscriptions_product_price_string', 'kaksi_subscription_product_string', 10, 3);



/**
 * @snippet       New Product Tab @ WooCommerce Single Product
 * @author        Kaksi Media
 * @testedwith    WordPress 6.3
 * @URL           https://woocommerce.com/document/editing-product-data-tabs/
 * */

add_filter('woocommerce_product_tabs', 'kaksi_add_terms_tab', 1);

function kaksi_add_terms_tab($tabs)
{
	$product = new WC_Product(get_the_ID());
	if (class_exists('WC_Subscriptions_Product') && WC_Subscriptions_Product::is_subscription($product->id)) {
		$tabs['terms'] = array(
			'title' => __('Termos e condições de aluguer', 'woocommerce'), // TAB TITLE
			'priority' => 50, // TAB SORTING (DESC 10, ADD INFO 20, REVIEWS 30)
			'callback' => 'kaksi_terms_product_tab_content', // TAB CONTENT CALLBACK
		);

		return $tabs;
	}
}

function kaksi_terms_product_tab_content()
{
	global $product;
	if (class_exists('WC_Subscriptions_Product') && WC_Subscriptions_Product::is_subscription($product->id)) {
		$post   = get_post(2090906);
		if (isset($post->post_content)) {
			$content = apply_filters('the_content', $post->post_content);
			echo $content;
		}
	}
}

/**
 * @snippet       New Product Tab @ WooCommerce Single Product
 * @author        Kaksi Media
 * @testedwith    WordPress 6.3
 * @URL           https://woocommerce.com/document/editing-product-data-tabs/
 * */

add_filter('woocommerce_product_tabs', 'kaksi_add_size_chart_tab', 2);

function kaksi_add_size_chart_tab($tabs)
{
	$product = new WC_Product(get_the_ID());
	if (has_term(array(595, 615, 593, 614, 594, 616), 'product_cat')) {
		$tabs['size_chart'] = array(
			'title' => __('Guia de tamanhos', 'woocommerce'), // TAB TITLE
			'priority' => 50, // TAB SORTING (DESC 10, ADD INFO 20, REVIEWS 30)
			'callback' => 'kaksi_size_chart_product_tab_content', // TAB CONTENT CALLBACK
		);

		return $tabs;
	}
}

function kaksi_size_chart_product_tab_content()
{
	global $product;
	if (has_term(array(595, 615), 'product_cat')) { // Violinos
		$post   = get_post(2100421);
		if (isset($post->post_content)) {
			$content = apply_filters('the_content', $post->post_content);
			echo $content;
		}
	} elseif (has_term(array(593, 614), 'product_cat')) { // Violas de arco
		$post   = get_post(2100422);
		if (isset($post->post_content)) {
			$content = apply_filters('the_content', $post->post_content);
			echo $content;
		}
	} elseif (has_term(array(594, 616), 'product_cat')) { // Violoncelos
		$post   = get_post(2100423);
		if (isset($post->post_content)) {
			$content = apply_filters('the_content', $post->post_content);
			echo $content;
		}
	}
}

/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function my_hide_shipping_when_free_is_available($rates)
{
	$free = array();
	foreach ($rates as $rate_id => $rate) {
		if ('free_shipping' === $rate->method_id) {
			$free[$rate_id] = $rate;
			break;
		}
	}
	return !empty($free) ? $free : $rates;
}
add_filter('woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100);

/**
 * Move o campo 'billing_email' para o topo na página de chekout
 */

add_filter('woocommerce_billing_fields', 'kaksi_move_checkout_email_field');

function kaksi_move_checkout_email_field($address_fields)
{
	$address_fields['billing_email']['priority'] = 1;
	return $address_fields;
}
//Info placeholder para formato do código postal
add_filter('woocommerce_checkout_fields', 'kaksi_override_checkout_fields');

function kaksi_override_checkout_fields($fields)

{

	$fields['billing']['billing_postcode']['placeholder'] = 'Indique 4+3 dígitos separados por -. Exemplo: 4050-610';

	$fields['shipping']['shipping_postcode']['placeholder'] = 'Indique 4+3 dígitos, separados por -. Exemplo: 4050-610';

	return $fields;
}

/**
 * @snippet       Disable Free Shipping if Cart has Shipping Class
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter('woocommerce_package_rates', 'bbloomer_hide_free_shipping_for_shipping_class', 9999, 2);

function bbloomer_hide_free_shipping_for_shipping_class($rates, $package)
{
	$shipping_class_target = 624; // shipping class ID (to find it, see screenshot below)
	$in_cart = false;
	foreach (WC()->cart->get_cart_contents() as $key => $values) {
		if ($values['data']->get_shipping_class_id() == $shipping_class_target) {
			$in_cart = true;
			break;
		}
	}
	if ($in_cart) {
		unset($rates['table_rate:14:30']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:3:1']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:17:40']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:11:25']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:8:19']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:18:43']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:10:23']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:9:21']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:15:34']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:6:15']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:5:13']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:16:37']); // shipping method with ID (to find it, see screenshot below)
		unset($rates['table_rate:7:17']); // shipping method with ID (to find it, see screenshot below)
	}
	return $rates;
}

add_action( 'woocommerce_single_product_summary', 'remove_add_cart_button' );
/**
 * Remove add to cart button Aluguer category products
 */
function remove_add_cart_button() { 
    
// Categories
$categories = array( 'aluguer' );

 if ( has_term( $categories, 'product_cat', get_the_id() ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
 }
}
