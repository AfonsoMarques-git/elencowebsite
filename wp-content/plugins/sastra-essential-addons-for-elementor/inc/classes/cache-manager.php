<?php
namespace Spexo_Addons\Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

defined( 'ABSPATH' ) || die();

class Cache_Manager {

	private static $widgets_cache;

	public static function init() {
		add_action( 'elementor/editor/after_save', [ __CLASS__, 'cache_widgets' ], 10, 2 );
		add_action( 'after_delete_post', [ __CLASS__, 'delete_cache' ] );
	}

	public static function delete_cache( $post_id ) {
		// Delete to regenerate cache file
		$assets_cache = new Assets_Cache( $post_id );
		$assets_cache->delete();
	}

	public static function cache_widgets( $post_id, $data ) {
		if ( ! self::is_published( $post_id ) ) {
			return;
		}

		self::$widgets_cache = new Widgets_Cache( $post_id, $data );
		self::$widgets_cache->save();

		// Delete to regenerate cache file
		$assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
		$assets_cache->delete();
	}

	public static function is_published( $post_id ) {
		return get_post_status( $post_id ) === 'publish';
	}

	public static function is_editing_mode() {
		return (
			tmpcoder_elementor()->editor->is_edit_mode() ||
			tmpcoder_elementor()->preview->is_preview_mode() ||
			is_preview()
		);
	}

	public static function is_built_with_elementor( $post_id ) {
		$post = get_post($post_id);
		if(!empty($post) && isset($post->ID)) {
			$document = tmpcoder_elementor()->documents->get($post->ID);
			if (is_object($document) && method_exists($document, 'is_built_with_elementor')) {
				return $document->is_built_with_elementor();
			}
		}
		return false;
	}

	public static function should_enqueue( $post_id ) {
		return (
			tmpcoder_is_on_demand_cache_enabled() &&
			self::is_built_with_elementor( $post_id ) &&
			self::is_published( $post_id ) &&
			! self::is_editing_mode()
		);
	}
	
	public static function enqueue( $post_id ) {
		$assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
		$assets_cache->enqueue();
		do_action( 'spexoaddons_enqueue_assets', $is_cache = true, $post_id );
	}
}

Cache_Manager::init();
