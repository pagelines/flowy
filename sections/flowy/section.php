<?php
/*
	Section: Flowy
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Demo: http://flowy.ahansson.com
	Description: Flowy Slider is a responsive jQuery slider, which is acting like iTunes Coverflow.
	Class Name: Flowy
	Cloning: true
	v3: true
	Filter: slider
*/

class Flowy extends PageLinesSection {

	var $default_limit = 4;

	function section_styles() {

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'coverscroll', $this->base_url.'/js/jquery.coverscroll.js' );

		wp_enqueue_script( 'iscroll', $this->base_url.'/js/iscroll.js' );

	}

	function section_head() {

		$clone_id = $this->get_the_id();

		$prefix = ( $clone_id != '' ) ? 'Clone_'.$clone_id : '';

		?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$('#flowy<?php echo $prefix; ?>').coverscroll({
	    				titleclass:'itemTitle', // class name of the element containing the item title
	    				selectedclass:'selectedItem', // class name of the selected item
	    				scrollactive:false, // scroll functionality switch
	    				step:{ // compressed items on the side are steps
	    					limit:4, // how many steps should be shown on each side
	    					width:8, // how wide is the visible section of the step in pixels
	    					scale:true // scale down steps
	    				},
	    				bendamount:2, // amount of "bending" of the CoverScroll (values 0.1 to 1 bend down, -0.1 to -1 bend up, 2 is straight (no bending), 1.5 sligtly bends down)
	  					movecallback:function(item){} // callback function triggered after click on an item - parameter is the item's jQuery object
	  				});

					$(document.documentElement).keyup(function (event) {
					    var direction = null;

					    // handle cursor keys
					    if (event.keyCode == 37) {
					      	// go left
					      	direction = 'prev';
					    } else if (event.keyCode == 39) {
					      	// go right
					      	direction = 'next';
					    }

					    if (direction != null) {
					      	$('#flowy img.selectedItem')[direction]().click();
					    }
					});

				});
			</script>
		<?php

	}

	function section_template( ) {

		$clone_id = $this->get_the_id();

		$prefix = ( $clone_id != '' ) ? 'Clone_'.$clone_id : '';

		// The boxes
		$flowy_array = $this->opt('flowy_array');

		$format_upgrade_mapping = array(
			'image'	=> 'flowy_image_%s',
			'alt'	=> 'flowy_alt_%s',
			'desc'	=> 'flowy_desc_%s'
		);

		$flowy_array = $this->upgrade_to_array_format( 'flowy_array', $flowy_array, $format_upgrade_mapping, $this->opt('flowy_flows'));

		// must come after upgrade
		if( !$flowy_array || $flowy_array == 'false' || !is_array($flowy_array) ){
			$flowy_array = array( array(), array(), array(), array() );
		}

		?>

			<div class="flowy-container">
				<div id="flowy<?php echo $prefix; ?>" style="height:300px;">
					<?php

						$output = '';

						if( is_array($flowy_array) ){

							$slides = count( $flowy_array );

							foreach( $flowy_array as $slide ){

								if ( pl_array_get( 'image', $slide ) ) {

									$the_desc = pl_array_get( 'desc', $slide );
									$img_alt = pl_array_get( 'alt', $slide );
									$img = sprintf( '<img desc=\'%s\' src="%s" alt="%s"/>', $the_desc, pl_array_get( 'image', $slide ), $img_alt );

									$output .= $img;
								}
							}
						}

						if ( $output == '' ) {

							$this->do_defaults();

						} else {

							echo $output;
						}

					?>

				</div>

			</div>

		<?php
	}

	function do_defaults() {

		?>
			<div class="flowy-container">
				<div id="flowy" style="height:300px;">
					<?php printf( '<img desc=\'<a href="http://pagelines.com/store/sections">Title #1: This title have a link</a>\' src="%s"/>', $this->base_url.'/img/1.png' ); ?>
					<?php printf( '<img desc=\'Title #2: This title does not have a link\' src="%s"/>', $this->base_url.'/img/2.png' ); ?>
					<?php printf( '<img desc=\'Title #3: This title does not have a link, but it is a title and it is a very long title for an image\' src="%s"/>', $this->base_url.'/img/3.png' ); ?>
					<?php printf( '<img desc=\'Title #4: Try using your left and right key on your keyboard to control the slider!\' src="%s"/>', $this->base_url.'/img/4.png' ); ?>
					<?php printf( '<img desc=\'Title #5: This is the fifth image in the slider\' src="%s"/>', $this->base_url.'/img/5.png' ); ?>
					<?php printf( '<img desc=\'Title #6: My name is Aleksander Hansson and I am a PageLines developer\' src="%s"/>', $this->base_url.'/img/6.png' ); ?>
					<?php printf( '<img desc=\'Title #7: Last image in the slider does not have a describtion.\' src="%s"/>', $this->base_url.'/img/7.png' ); ?>
					<?php printf( '<img desc=\'\' src="%s"/>', $this->base_url.'/img/8.png' ); ?>
				</div>
			</div>
		<?php

	}

	function section_opts(){

		$options = array();

		$how_to_use = __( '
		<strong>Read the instructions below before asking for additional help:</strong>
		</br></br>
		<strong>1.</strong> In the frontend editor, drag the Flowy section to a template of your choice.
		</br></br>
		<strong>2.</strong> Edit settings for Flowy slides.
		</br></br>
		<strong>3.</strong> When you are done, hit "Publish" to see changes.
		</br></br>
		<div class="row zmb">
				<div class="span6 tac zmb">
					<a class="btn btn-info" href="http://forum.pagelines.com/71-products-by-aleksander-hansson/" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-ambulance"></i>          Forum</a>
				</div>
				<div class="span6 tac zmb">
					<a class="btn btn-info" href="http://betterdms.com" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-align-justify"></i>          Better DMS</a>
				</div>
			</div>
			<div class="row zmb" style="margin-top:4px;">
				<div class="span12 tac zmb">
					<a class="btn btn-success" href="http://shop.ahansson.com" target="_blank" style="padding:4px 0 4px;width:100%"><i class="icon-shopping-cart" ></i>          My Shop</a>
				</div>
			</div>
		', 'flowy' );

		$options[] = array(
			'key' => 'flowy_help',
			'type'     => 'template',
			'template'      => do_shortcode( $how_to_use ),
			'title' =>__( 'How to use:', 'flowy' ) ,
		);

		$options[] = array(
			'key'		=> 'flowy_array',
	    	'type'		=> 'accordion',
			'title'		=> __('Flowy Setup', 'flowy'),
			'post_type'	=> __('Flowy Slide', 'flowy'),
			'opts'	=> array(
				array(
					'key'	=> 'image',
					'label' => __( 'Slide Image', 'flowy' ),
					'type'  => 'image_upload'
				),
				array(
					'key'	=> 'alt',
					'label' => __( 'Image ALT tag', 'flowy' ),
					'type'   => 'text'
				),
				array(
					'key'	=> 'desc',
					'label' => __( 'Slide Description', 'flowy' ),
					'type'   => 'text'
				),
			)
		);

		return $options;

	}

}
