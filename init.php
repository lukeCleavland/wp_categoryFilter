<?php
/*
Plugin Name: Category Filter
Plugin URI: https://github.com/lukeCleavland/wp_categoryFilter.git
Description: Add meta box for post-page category filter
Version: 1.0.0
Author: Luke Cleavland
*/

function custom_page_fields($object)
{
  wp_nonce_field(basename(__FILE__), "category-nonce");
?>
  <div>
    <label for="categories">Categorie(s)</label> <input name="categories" type="text"  value="<?php echo get_post_meta($object->ID, "_categories", true); ?>"/>

  </div>
            <?php

}

function save_custom_page($post_id, $post, $update)
{
    if (!isset($_POST["category-nonce"]) || !wp_verify_nonce($_POST["category-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";
    if($slug != $post->post_type)
        return $post_id;

    $categories = NULL;


    if(isset($_POST["categories"]))
    {
        $categories = $_POST["categories"];
    }
    update_post_meta($post_id, "_categories", $categories);



}

add_action("save_post", "save_custom_page", 10, 3);

function add_custom_page_fields()
{
    add_meta_box("category-filter", "Post Category Filter", "custom_page_fields", "page", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_page_fields");
?>
