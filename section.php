<?php
/*
	Section: Flowy
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Demo: http://flowy.ahansson.com
	Version: 1.0
	Description: Flowy Slider is a responsive jQuery slider.
	Class Name: PageLinesFlowy
	Workswith: templates, main
	Cloning: true
*/

/**
 * PageLines Flowy
 *
 * @package PageLines Framework
 * @author Aleksander Hansson
 */



class PageLinesFlowy extends PageLinesSection {

	/*

function section_persistent(){

	add_filter( 'pless_vars', 'pl_counter_less');

	function pl_beefy_less( $constants ){

		$countdown_background_color = (ploption('countdown-background-color')) ? ploption('countdown-background-color') : '#1568AD';
		$countdown_label_color = (ploption('countdown-label-color')) ? ploption('countdown-label-color') : '#000000';
		$countdown_text_color = (ploption('countdown-text-color')) ? ploption('countdown-text-color') : '#ffffff';


		$newvars = array(

			'countdownbackgroundcolor' => $countdown_background_color ,
			'countdownlabelcolor' => $countdown_label_color ,
			'countdowntextcolor' => $countdown_text_color

		);

		$lessvars = array_merge($newvars, $constants);
		return $lessvars;
	}

}
*/

	var $default_limit = 4;

	function section_styles() {

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'coverscroll', $this->base_url.'/js/jquery.coverscroll.js' );

		wp_enqueue_script( 'iscroll', $this->base_url.'/js/iscroll.js' );

	}

	function section_head( $clone_id ) {

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

	function section_template( $clone_id ) {

		$prefix = ( $clone_id != '' ) ? 'Clone_'.$clone_id : '';

?>

		<div class="flowy-container">
			<div id="flowy<?php echo $prefix; ?>" style="height:300px;">
					<?php

		$flows = ( ploption( 'flowy_flows', $this->oset ) ) ? ploption( 'flowy_flows', $this->oset ) : $this->default_limit;

		$output = '';
		for ( $i = 1; $i <= $flows; $i++ ) {

			if ( ploption( 'flowy_image_'.$i, $this->oset ) ) {

				$the_desc = ploption( 'flowy_desc_'.$i, $this->tset );

				$img_alt = ploption( 'flowy_alt_'.$i, $this->tset );

				$img = sprintf( '<img desc=\'%s\' src="%s" alt="%s"/>', $the_desc, ploption( 'flowy_image_'.$i, $this->oset ), $img_alt );

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

	<?php }

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
			'inputlabel'  => __( 'Number of Images to Configure', 'pagelines' ),
			'title'   => __( 'Number of images', 'pagelines' ),
			'shortexp'   => __( 'Enter the number of Flowy images. <strong>Minimum is 4</strong>', 'pagelines' ),
			'exp'    => __( "This number will be used to generate slides and option setup.", 'pagelines' ),
		);

		global $post_ID;

		$oset = array( 'post_id' => $post_ID, 'clone_id' => $settings['clone_id'], 'type' => $settings['type'] );

		$slides = ( ploption( 'flowy_flows', $oset ) ) ? ploption( 'flowy_flows', $oset ) : $this->default_limit;

		for ( $i = 1; $i <= $slides; $i++ ) {

			$array['flowy_slide_'.$i] = array(
				'type'    => 'multi_option',
				'selectvalues' => array(
					'flowy_image_'.$i  => array(
						'inputlabel'  => __( 'Slide Image', 'pagelines' ),
						'type'   => 'image_upload'
					),
					'flowy_alt_'.$i  => array(
						'inputlabel' => __( 'Image ALT tag', 'pagelines' ),
						'type'   => 'text'
					),
					'flowy_desc_'.$i  => array(
						'inputlabel' => __( 'Slide Description', 'pagelines' ),
						'type'   => 'text'
					),
				),
				'title'   => __( 'Flowy Slide ', 'pagelines' ) . $i,
				'shortexp'   => __( 'Setup options for slide number ', 'pagelines' ) . $i,
				'exp'   => __( 'For best results all images in the slider should have the same dimensions.', 'pagelines' )
			);

		}

		$metatab_settings = array(
			'id'   => 'flowy_options',
			'name'   => __( 'Flowy', 'pagelines' ),
			'icon'   => $this->icon,
			'clone_id' => $settings['clone_id'],
			'active' => $settings['active']
		);

		register_metatab( $metatab_settings, $array );

	}

}
