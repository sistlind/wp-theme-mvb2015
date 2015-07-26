<?php

get_header();
global $options;

rewind_posts();
get_template_part('loop', 'archive');

get_footer();
