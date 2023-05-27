<?php
/*
Plugin Name: Product Custom Post Type Filter And Favorite
Description: A WordPress plugin to create a custom post type for products items and display them on the front end with the filter and favorite option.
Version: 1.0
Author: Zalak Patel
*/


// Register JS and CSS
add_action( 'init', 'plugin_register_script' );
function plugin_register_script() {
  wp_enqueue_script( 'bootstarp-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js', array('jquery') ); 
  wp_enqueue_style( 'bootstarp-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css'); 
  wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' );
  wp_enqueue_script( 'ajax_script', plugin_dir_url( __FILE__ ) . '/js/custom.js', array('jquery') ); 
  wp_localize_script( 'ajax_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'ajax_script' );
 
} 

// Register the "Product" custom post type
function product_post() {
  $labels = array(
    'name'               => _x( 'Product', 'post type general name' ),
    'singular_name'      => _x( 'Product', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Product' ),
    'add_new_item'       => __( 'Add New Product' ),
    'edit_item'          => __( 'Edit Product' ),
    'new_item'           => __( 'New Product' ),
    'all_items'          => __( 'All Product' ),
    'view_item'          => __( 'View Product' ),
    'search_items'       => __( 'Search Product' ),
    'not_found'          => __( 'No game found' ),
    'not_found_in_trash' => __( 'No game found in the Trash' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Products'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds Product and Product specific data',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
    'rewrite' => array('slug' => 'product'),
  );
  register_post_type( 'product', $args );
}
add_action( 'init', 'product_post' );

// Register the "Product Categories" taxonomy

function product_taxonomies_prodcut() {
  $labels = array(
    'name'              => _x( 'Product Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Product Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Product Categories' ),
    'all_items'         => __( 'All Product Categories' ),
    'parent_item'       => __( 'Parent Product Category' ),
    'parent_item_colon' => __( 'Parent Product Category:' ),
    'edit_item'         => __( 'Edit Product Category' ),
    'update_item'       => __( 'Update Product Category' ),
    'add_new_item'      => __( 'Add New Product Category' ),
    'new_item_name'     => __( 'New Product Category' ),
    'menu_name'         => __( 'Product Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'product-categories', 'product', $args );
}
add_action( 'init', 'product_taxonomies_prodcut', 0 );

/* Product size*/
function product_taxonomies_prodcut_size() {
  $labels = array(
    'name'              => _x( 'Product Size', 'taxonomy general name' ),
    'singular_name'     => _x( 'Product Size', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Product Size' ),
    'all_items'         => __( 'All Product Size' ),
    'parent_item'       => __( 'Parent Product Size' ),
    'parent_item_colon' => __( 'Parent Product Size:' ),
    'edit_item'         => __( 'Edit Product Size' ),
    'update_item'       => __( 'Update Product Size' ),
    'add_new_item'      => __( 'Add New Product Size' ),
    'new_item_name'     => __( 'New Product Size' ),
    'menu_name'         => __( 'Product Size' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'product-size', 'product', $args );
}
add_action( 'init', 'product_taxonomies_prodcut_size', 0 );

/* Product color*/
function product_taxonomies_prodcut_color() {
  $labels = array(
    'name'              => _x( 'Product Color', 'taxonomy general name' ),
    'singular_name'     => _x( 'Product Color', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Product Color' ),
    'all_items'         => __( 'All Product Color' ),
    'parent_item'       => __( 'Parent Product Color' ),
    'parent_item_colon' => __( 'Parent Product Color:' ),
    'edit_item'         => __( 'Edit Product Color' ),
    'update_item'       => __( 'Update Product Color' ),
    'add_new_item'      => __( 'Add New Product Color' ),
    'new_item_name'     => __( 'New Product Color' ),
    'menu_name'         => __( 'Product Color' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'product-color', 'product', $args );
}
add_action( 'init', 'product_taxonomies_prodcut_color', 0 );


/*Product Price*/
function wporg_add_pro_price()
{
    $screens = ['product'];
    foreach ($screens as $screen) {
        add_meta_box(
            'product_price',           // Unique ID
            'Add Price',  // Box title
            'wporg_product_price_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_pro_price');
function wporg_product_price_html($post)
{
    $product_price = get_post_meta($post->ID,'product_price',true); ?>
    <div class="product_price_text">
      <input type="hidden" name="page_id" id="page_id" value="<?php echo $post->ID; ?>">
      <input type="text" name="product_price_text" placeholder="price" value="<?php echo $product_price;?>">
    </div>
    <?php
}
add_action('save_post', 'wporg_product_price_save_meta');

function wporg_product_price_save_meta($post_id)
{
    if ( isset($_POST['product_price_text']) && $_POST['product_price_text'] != "") {       
      update_post_meta($post_id, 'product_price', $_POST['product_price_text']);      
    }
    else
    {
      update_post_meta($post_id, 'product_price', "");
    }
}

// Create a shortcode to display the product items
function product_shortcode( $atts ) {
    ob_start(); ?>
    <div class="container-fluid">
        <?php 

            $per_page = 9;
            $query = new WP_Query(
            array(
                'post_type' => 'product',  
                'posts_per_page' => $per_page,
                'post_status' => 'publish',
                
            )
        );

        ?>
        <?php if($query->have_posts()): ?>
            <section class="our-top-app-section margin-top-70">
                <h2 class="font-weight-bold color-dark-blue text-center">Products</h2>
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="row" id="response">
                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                    
                            <div class="col-lg-4 col-md-6 my-4">
                                <?php $img_url = get_post_meta(get_the_ID(), 'img_url', true);?>
                                <div class="shadow bg-white border-radius-10 h-100 p-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php $img_url = get_the_post_thumbnail(get_the_ID(), 'full');
                                             if($img_url != ''){
                                                  echo $img_url;
                                              }?>
                                            <div class="relative">
                                                <!-- <span class="font-weight-bold">Hootu.</span> -->
                                                <h2 class="mt-4 relative d-inline-block font-weight-bold p-0 mb-4 font-28"><?php echo get_the_title();?></h2>
                                            </div>
                                            
                                            <?php $price= get_post_meta(get_the_ID(), 'product_price', true); ?>
                                            <div>$ <?php echo $price;?></div>
                                            <div class="btn-light-blue">
                                                    <a href="<?php echo get_the_permalink(); ?>">Buy Now</a>
                                            </div>
                                            <?php if(in_array(get_the_ID(), favorite_id_array())){ ?>
                                               <div class="fv_<?php echo get_the_ID(); ?>" title="Already in favorite" ><i class="fas fa-heart text-danger"></i> In favorite</div>
                                               <?php } else { ?>
                                               <div class="fv_<?php echo get_the_ID(); ?>" >
                                                  <div class="add-favorite" title="Add to favorite" data-post_id="<?php echo get_the_ID(); ?>">
                                                     <i class="fas fa-heart"></i> Add to favorite
                                                  </div>
                                               </div>
                                           <?php } ?>

                                        </div>
                                        
                                    </div>
                                </div>
                                
                            </div>          
                        
                        <?php endwhile; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mt-4">
                        <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter" class="shadow p-3">
                            <h3 class="">Category</h3>
                            <?php
                                if( $terms = get_terms( array( 'taxonomy' => 'product-categories', 'orderby' => 'name' ) ) ) : 
                            
                                    
                                    foreach ( $terms as $term ) :
                                        echo '<div class="form-check"><input class="form-check-input" type="checkbox" name="categoryFilter" value="' . $term->term_id . '" /> <label class="form-check-label" for="">' . $term->name . '</label></div>';
                                    endforeach;
                                    
                                endif;
                            ?>
                            <h3 class="mt-4">Size</h3>
                            <?php
                                if( $terms = get_terms( array( 'taxonomy' => 'product-size', 'orderby' => 'name' ) ) ) : 
                            
                                    
                                    foreach ( $terms as $term ) :
                                        echo '<div class="form-check"><input class="form-check-input" type="checkbox" name="productSize" value="' . $term->term_id . '" /><label class="form-check-label" for="">' . $term->name . '</label></div>';
                                    endforeach;
                                    
                                endif;
                            ?>

                            <h3 class="mt-4">Color</h3>
                            <?php
                                if( $terms = get_terms( array( 'taxonomy' => 'product-color', 'orderby' => 'name' ) ) ) : 
                            
                                
                                    foreach ( $terms as $term ) :
                                        echo '<div class="form-check"><input class="form-check-input" type="checkbox" name="productColor" value="' . $term->term_id . '" /><label class="form-check-label" for="">' . $term->name . '</label></div>';
                                    endforeach;
                                    
                                endif;
                            ?>
                        
                        
                            <button class="mt-4">Apply filter</button>
                            <input type="hidden" name="action" value="myfilter">
                        </form>
                    </div>
                </div>
            </section>

        <?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?> 
        
    </div>
    <?php $output = ob_get_clean();
    return $output;
}
add_shortcode( 'products', 'product_shortcode' );

// Product Filter ajax function
add_action('wp_ajax_myfilter', 'product_filter_function'); 
add_action('wp_ajax_nopriv_myfilter', 'product_filter_function');

function product_filter_function(){
  $per_page = '9';
 
  if( isset( $_POST['categoryFilter'] ) ){
    $cat_filter = $_POST['categoryFilter'];
  }

  if( isset( $_POST['productSize'] ) ){
    $product_size = $_POST['productSize'];
  }

  if( isset( $_POST['productColor'] ) ){
    $product_color = $_POST['productColor'];
  }
  //echo "test";
  $args = array(
    'orderby' => 'date', // we will sort posts by date
    'order' => 'DESC', // ASC or DESC
    'post_type' => 'product',  
      'posts_per_page' => $per_page,
      'post_status' => 'publish',
      'tax_query' => array(
          'relation' => 'OR',
          array(
              'taxonomy' => 'product-categories',
              'field'    => 'id',
              'terms'    => array($cat_filter),
          ),
          array(
              'taxonomy' => 'product-size',
              'field'    => 'id',
              'terms'    => array($product_size),
          ),
          array(
              'taxonomy' => 'product-color',
              'field'    => 'id',
              'terms'    => array($product_color),
          ),
      ),
  );
 
  $query = new WP_Query( $args );
  
  if( $query->have_posts() ) :
    while( $query->have_posts() ): $query->the_post(); ?>
      <div class="col-lg-4 col-md-6 my-4">
          <?php $img_url = get_post_meta(get_the_ID(), 'img_url', true);?>
          <div class="shadow bg-white border-radius-10 h-100 p-4">
            <div class="row">
              <div class="col-md-12">
                <?php $img_url = get_the_post_thumbnail($post->ID, 'full');
                   if($img_url != ''){
                                        echo $img_url;
                                    }?>
                <div class="relative">
                  <!-- <span class="font-weight-bold">Hootu.</span> -->
                  <h2 class="mt-4 relative d-inline-block font-weight-bold p-0 mb-4 font-28"><?php echo get_the_title();?></h2>
                </div>
                
                <?php $price= get_post_meta(get_the_ID(), 'product_price', true); ?>
                <div>$ <?php echo $price;?></div>
                <div class="btn-light-blue">
                    <a href="<?php echo get_the_permalink(); ?>">Buy Now</a>
                </div>
                <?php if(in_array($post->ID, favorite_id_array())){ ?>
                   <div class="fv_<?php echo $post->ID; ?>" title="Already in favorite" ><img src="../wp-content/uploads/2022/04/heart.png" width="15px" height="15px"><a href="/favorite/"> In favorite</a></div>
                   <?php } else { ?>
                   <div class="fv_<?php echo $post->ID; ?>" >
                      <div class="add-favorite" title="Add to favorite" data-post_id="<?php echo $post->ID; ?>">
                         <img src="../wp-content/uploads/2022/04/heart.png" width="15px" height="15px"> Add to favorite
                      </div>
                   </div>
                 <?php } ?>

              </div>
              
            </div>
          </div>
          
        </div>  
    <?php endwhile;
    wp_reset_postdata();
  else :
    echo 'No posts found';
  endif;
  
  die();
}

// Product Favorite shortcode
function product_fav_shortcode( $atts ) {
    ob_start(); ?>
<div class="container">
    <?php
   $favorite_id_array = favorite_id_array();
   global $wp_query;
   $save_wpq = $wp_query;
   $args = array(
       'paged' => get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1,
       'post_type'   => 'product',
       'post__in'   => !empty($favorite_id_array) ? $favorite_id_array : array(0),
    );
   $wp_query = new WP_Query( $args ); 
   ?>
    <?php if ($wp_query->have_posts()) : ?>
        <h2 class="font-weight-bold color-dark-blue text-center">Favorite</h2>
        <div class="row justify-content-center">
            <div class="col-md-9">
               <div class="row" id="response">
               <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                        
                        <div class="col-lg-4 col-md-6 my-4">
                           <?php $img_url = get_post_meta(get_the_ID(), 'img_url', true);?>
                           <div class="shadow bg-white border-radius-10 h-100 p-4">
                              <div class="row">
                                 <div class="col-md-12">
                                    <?php $img_url = get_the_post_thumbnail($post->ID, 'full');
                                     if($img_url != ''){
                                                  echo $img_url;
                                              }?>
                                    <div class="relative">
                                       <!-- <span class="font-weight-bold">Hootu.</span> -->
                                       <h2 class="mt-4 relative d-inline-block font-weight-bold p-0 mb-4 font-28"><?php echo get_the_title();?></h2>
                                    </div>
                                    
                                    <?php $price= get_post_meta(get_the_ID(), 'product_price', true); ?>
                                    <div>$ <?php echo $price;?></div>
                                    <div class="btn-light-blue">
                                          <a href="<?php echo get_the_permalink(); ?>">Buy Now</a>
                                    </div>
                                       

                                 </div>
                                 
                              </div>
                           </div>
                           
                        </div>         
               
               <?php endwhile; ?>
               

               </div>
            </div>
                 
    <?php else : ?>
    <p>No posts in your favorite</p>
    <?php endif; wp_reset_postdata();?>
      
        </div>
</div>
<?php 
    $output = ob_get_clean();
    return $output;
 }
add_shortcode( 'products_fav', 'product_fav_shortcode' );


//favorite posts array
function favorite_id_array() { 
    if (!empty( $_COOKIE['favorite_post_ids'])) {
        return explode(',', $_COOKIE['favorite_post_ids']);
    }
    else {
        return array();
    }
}
    
//add to favorite function
function add_favorite() {
    $post_id = (int)$_POST['post_id'];
    if (!empty($post_id)) {
        $new_post_id = array(
            $post_id
        );
        $post_ids = array_merge($new_post_id, favorite_id_array());
        $post_ids = array_diff($post_ids, array(''));
        $post_ids = array_unique($post_ids);
        setcookie('favorite_post_ids', implode(',', $post_ids) , time() + 3600 * 24 * 365, '/');
        echo count($post_ids);
    }
    die();
}
add_action('wp_ajax_favorite', 'add_favorite');
add_action('wp_ajax_nopriv_favorite', 'add_favorite');