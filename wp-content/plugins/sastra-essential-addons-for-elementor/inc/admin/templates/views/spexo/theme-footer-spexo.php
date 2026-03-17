<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Render TMPCODER Header
TMPCODER_Header_Footer_Elements::get_footer_content();
wp_footer();

// Sastra themes compatibility
echo '</div>'; // .page-content
echo '</div>'; // #page-wrap

?>

</body>
</html> 