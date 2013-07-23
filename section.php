<?php
/*
	Section: Flowy
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Demo: http://flowy.ahansson.com
	Version: 1.2
	Description: Flowy Slider is a responsive jQuery slider, which is acting like iTunes Coverflow.
	Class Name: Flowy
	Workswith: templates, main
	Cloning: true
	v3: true
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

		?>

			<div class="flowy-container">
				<div id="flowy<?php echo $prefix; ?>" style="height:300px;">
					<?php

						$flows = ( $this->opt( 'flowy_flows', $this->oset ) ) ? $this->opt( 'flowy_flows', $this->oset ) : $this->default_limit;

						$output = '';

						for ( $i = 1; $i <= $flows; $i++ ) {

							if ( $this->opt( 'flowy_image_'.$i, $this->oset ) ) {

								$the_desc = $this->opt( 'flowy_desc_'.$i, $this->tset );

								$img_alt = $this->opt( 'flowy_alt_'.$i, $this->tset );

								$img = sprintf( '<img desc=\'%s\' src="%s" alt="%s"/>', $the_desc, $this->opt( 'flowy_image_'.$i, $this->oset ), $img_alt );

								$output .= $img;
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

	function section_optionator( $settings ) {
		$settings = wp_parse_args( $settings, $this->optionator_default );

		$array = array();

		$array['flowy_flows'] = array(
			'type'    => 'count_select',
			'count_start' => 4,
			'count_number' => 30,
			'default'  => '4',
			'inputlabel'  => __( 'Number of Images to Configure', 'Flowy' ),
			'title'   => __( 'Number of images', 'Flowy' ),
			'shortexp'   => __( 'Enter the number of Flowy images. <strong>Minimum is 4</strong>', 'Flowy' ),
			'exp'    => __( "This number will be used to generate slides and option setup.", 'Flowy' ),
		);

		global $post_ID;

		$oset = array( 'post_id' => $post_ID, 'clone_id' => $settings['clone_id'], 'type' => $settings['type'] );

		$slides = ( $this->opt( 'flowy_flows', $oset ) ) ? $this->opt( 'flowy_flows', $oset ) : $this->default_limit;

		for ( $i = 1; $i <= $slides; $i++ ) {

			$array['flowy_slide_'.$i] = array(
				'type'    => 'multi_option',
				'selectvalues' => array(
					'flowy_image_'.$i  => array(
						'inputlabel'  => __( 'Slide Image', 'Flowy' ),
						'type'   => 'image_upload'
					),
					'flowy_alt_'.$i  => array(
						'inputlabel' => __( 'Image ALT tag', 'Flowy' ),
						'type'   => 'text'
					),
					'flowy_desc_'.$i  => array(
						'inputlabel' => __( 'Slide Description', 'Flowy' ),
						'type'   => 'text'
					),
				),
				'title'   => __( 'Flowy Slide ', 'Flowy' ) . $i,
				'shortexp'   => __( 'Setup options for slide number ', 'Flowy' ) . $i,
				'exp'   => __( 'For best results all images in the slider should have the same dimensions.', 'Flowy' )
			);

		}

		$metatab_settings = array(
			'id'   => 'flowy_options',
			'name'   => 'Flowy',
			'icon'   => $this->icon,
			'clone_id' => $settings['clone_id'],
			'active' => $settings['active']
		);

		register_metatab( $metatab_settings, $array );

	}

}
