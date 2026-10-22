<?php
/**
 * The HTML the game may output, for escaping with wp_kses().
 *
 * Game screens are built from small pieces of HTML (forms, buttons, icons, timers). Every
 * piece is escaped again with wp_kses( $html, dfmg_allowed_html() ) where it is echoed.
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

namespace DigiFalk\MafiaPBBGEngine;

defined( 'ABSPATH' ) || exit;

final class Html {

	/** @var array|null */
	private static $allowed = null;

	/**
	 * Allowed tags and attributes: what is allowed in posts, plus forms and inline SVG icons.
	 */
	public static function allowed(): array {
		if ( null !== self::$allowed ) {
			return self::$allowed;
		}
		$global = array(
			'class'            => true,
			'id'               => true,
			'style'            => true,
			'title'            => true,
			'role'             => true,
			'hidden'           => true,
			'tabindex'         => true,
			'data-*'           => true,
			'aria-hidden'      => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-describedby' => true,
			'aria-current'     => true,
			'aria-controls'    => true,
			'aria-expanded'    => true,
			'aria-pressed'     => true,
			'aria-live'        => true,
			'aria-valuenow'    => true,
			'aria-valuemin'    => true,
			'aria-valuemax'    => true,
		);
		$svg    = array(
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'opacity'         => true,
			'transform'       => true,
		);
		$extra  = array(
			'form'     => array(
				'action'     => true,
				'method'     => true,
				'enctype'    => true,
				'novalidate' => true,
				'target'     => true,
			),
			'input'    => array(
				'type'         => true,
				'name'         => true,
				'value'        => true,
				'checked'      => true,
				'disabled'     => true,
				'readonly'     => true,
				'required'     => true,
				'placeholder'  => true,
				'min'          => true,
				'max'          => true,
				'step'         => true,
				'maxlength'    => true,
				'minlength'    => true,
				'size'         => true,
				'pattern'      => true,
				'accept'       => true,
				'autocomplete' => true,
				'inputmode'    => true,
				'list'         => true,
				'form'         => true,
			),
			'select'   => array(
				'name'     => true,
				'multiple' => true,
				'disabled' => true,
				'required' => true,
				'size'     => true,
				'form'     => true,
			),
			'option'   => array(
				'value'    => true,
				'selected' => true,
				'disabled' => true,
				'label'    => true,
			),
			'optgroup' => array(
				'label'    => true,
				'disabled' => true,
			),
			'textarea' => array(
				'name'        => true,
				'rows'        => true,
				'cols'        => true,
				'maxlength'   => true,
				'placeholder' => true,
				'required'    => true,
				'disabled'    => true,
				'readonly'    => true,
			),
			'button'   => array(
				'type'           => true,
				'name'           => true,
				'value'          => true,
				'disabled'       => true,
				'form'           => true,
				'formnovalidate' => true,
			),
			'label'    => array( 'for' => true ),
			'fieldset' => array( 'disabled' => true ),
			'legend'   => array(),
			'output'   => array( 'for' => true ),
			'progress' => array(
				'value' => true,
				'max'   => true,
			),
			'meter'    => array(
				'value' => true,
				'min'   => true,
				'max'   => true,
			),
			'details'  => array( 'open' => true ),
			'summary'  => array(),
			'time'     => array( 'datetime' => true ),
			'svg'      => array(
				'width'     => true,
				'height'    => true,
				'viewbox'   => true,
				'focusable' => true,
				'xmlns'     => true,
			) + $svg,
			'g'        => $svg,
			'title'    => array(),
			'path'     => array( 'd' => true ) + $svg,
			'circle'   => array(
				'cx' => true,
				'cy' => true,
				'r'  => true,
			) + $svg,
			'ellipse'  => array(
				'cx' => true,
				'cy' => true,
				'rx' => true,
				'ry' => true,
			) + $svg,
			'rect'     => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'rx'     => true,
				'ry'     => true,
			) + $svg,
			'line'     => array(
				'x1' => true,
				'y1' => true,
				'x2' => true,
				'y2' => true,
			) + $svg,
			'polyline' => array( 'points' => true ) + $svg,
			'polygon'  => array( 'points' => true ) + $svg,
		);
		$allowed = wp_kses_allowed_html( 'post' );
		foreach ( $extra as $tag => $attributes ) {
			$allowed[ $tag ] = array_merge( $allowed[ $tag ] ?? array(), $global, $attributes );
		}
		foreach ( array( 'a', 'img', 'div', 'span', 'p', 'nav', 'header', 'footer', 'main', 'aside', 'section', 'ul', 'ol', 'li', 'table', 'td', 'th', 'tr', 'em', 'strong', 'small', 'hr', 'h2', 'h3', 'h4' ) as $tag ) {
			$allowed[ $tag ] = array_merge( $allowed[ $tag ] ?? array(), $global );
		}
		$allowed['img'] = array_merge(
			$allowed['img'],
			array(
				'srcset'   => true,
				'sizes'    => true,
				'loading'  => true,
				'decoding' => true,
			)
		);
		$allowed['a']['target'] = true;
		$allowed['a']['rel']    = true;

		/**
		 * Tags and attributes the game may output (see wp_kses()).
		 *
		 * @param array $allowed
		 */
		self::$allowed = (array) apply_filters( 'dfmg_allowed_html', $allowed );
		return self::$allowed;
	}
}
