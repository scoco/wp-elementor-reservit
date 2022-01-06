<?php

/**
 * reservit class.
 *
 * @category   Class
 * @package    ElementorReservit
 * @subpackage WordPress
 * @author     Emilien Mojocom <emilien@mojocom.fr>
 * @copyright  2022 Emilien Mojocom
 * @link       link(https://www.mojocom.fr,Mojocom)
 */

namespace ElementorReservit\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

/**
 * reservit widget class.
 *
 * @since 1.0.0
 */
class reservit extends Widget_Base
{
	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct($data = array(), $args = null)
	{
		parent::__construct($data, $args);

		wp_register_style('reservit', plugins_url('/assets/css/reservit.css', ELEMENTOR_RESERVIT), array(), '1.0.0');

		wp_register_script('pickadate', plugins_url('/assets/js/pickadate.js', ELEMENTOR_RESERVIT), array('jquery'), '1.0.0', true);
		wp_register_script('reservit', plugins_url('/assets/js/reservit.js', ELEMENTOR_RESERVIT), array('jquery'), '1.0.0', true);
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'reservit';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('Reservit', 'elementor-reservit');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'fa fa-pencil';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return array('general');
	}

	/**
	 * Enqueue styles.
	 */
	public function get_style_depends()
	{
		return array('reservit');
	}

	public function get_script_depends()
	{
		return array('pickadate', 'reservit');
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __('Content', 'elementor-reservit'),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __('Title', 'elementor-reservit'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('Title', 'elementor-reservit'),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __('Description', 'elementor-reservit'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __('Description', 'elementor-reservit'),
			)
		);

		$this->add_control(
			'chaine',
			array(
				'label'   => __('Identifiant de la chaine', 'elementor-reservit'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('2', 'elementor-reservit'),
			)
		);
		$this->add_control(
			'hotel',
			array(
				'label'   => __('Identifiant de l’hôtel', 'elementor-reservit'),
				'type'    => Controls_Manager::TEXT,
				'default' => __('2916', 'elementor-reservit'),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('title', 'none');
		$this->add_inline_editing_attributes('description', 'basic');
?>
		<h2 <?php echo $this->get_render_attribute_string('title'); ?>><?php echo wp_kses($settings['title'], array()); ?></h2>
		<div <?php echo $this->get_render_attribute_string('description'); ?>><?php echo wp_kses($settings['description'], array()); ?></div>
		<form method="post" onsubmit="return false;" id="check_avail_home" autocomplete="off">
			<label>Date d'arrivée</label>
			<input class="datepicker form-control" type="text" id="check_in" name="fromdate" data-date-format="dd/mm/yyyy" placeholder="Arrivée"><br>
			<label>Nuits</label>
			<input class="datepicker form-control" type="text" id="nuits" name="nuits" data-date-format="dd/mm/yyyy" placeholder="Nuits"><br>
			<label>Chambres</label>
			<input class="form-control" type="text" id="chambres" name="chambres" placeholder="0"><br>
			<label>Adultes</label>
			<input type="text" name="adultes" id="adultes" value="" class="qty form-control" placeholder="0"><br>
			<label>Enfant (0 à 16 ans)</label>
			<input type="text" name="enfant" id="enfants" value="" class="qty form-control" placeholder="0">
			<input type="submit" value="Rechercher" name="dispos_submit" class="btn_1" id="submit-booking">
			<input type="hidden" name="id" id="id" value="<?php echo wp_kses($settings['chaine'], array()); ?>" />
			<input type="hidden" name="hotelid" id="hotelid" value="<?php echo wp_kses($settings['hotel'], array()); ?>" />
			<div id="ages"></div>
		</form>
	<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template()
	{
	?>
		<# view.addInlineEditingAttributes( 'title' , 'none' ); view.addInlineEditingAttributes( 'description' , 'basic' ); view.addInlineEditingAttributes( 'content' , 'advanced' ); #>
			<h2 {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</h2>
			<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
			<form method="post" onsubmit="return false;" id="check_avail_home" autocomplete="off">
				<label>Date d'arrivée</label>
				<input class="datepicker form-control" type="text" id="check_in" name="fromdate" data-date-format="dd/mm/yyyy" placeholder="Arrivée"><br>
				<label>Nuits</label>
				<input class="datepicker form-control" type="text" id="nuits" name="nuits" data-date-format="dd/mm/yyyy" placeholder="Nuits"><br>
				<label>Chambres</label>
				<input class="form-control" type="text" id="chambres" name="chambres" placeholder="0"><br>
				<label>Adultes</label>
				<input type="text" name="adultes" id="adultes" value="" class="qty form-control" placeholder="0"><br>
				<label>Enfant (0 à 16 ans)</label>
				<input type="text" name="enfant" id="enfants" value="" class="qty form-control" placeholder="0">
				<input type="submit" value="Rechercher" name="dispos_submit" class="btn_1" id="submit-booking">
				<input type="hidden" name="id" id="id" value="{{{ settings.chaine }}}" />
				<input type="hidden" name="hotelid" id="hotelid" value="{{{ settings.hotel }}}" />
				<div id="ages"></div>
			</form>
	<?php
	}
}
