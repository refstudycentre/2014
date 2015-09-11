<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php
    print render($content['field_image']); // RC
    print render($content['field_featured_image']); // CL
  ?>
  <header>
    <?php print render($title_prefix); ?>
    <h3<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h3>
    <?php print render($title_suffix); ?>
  </header>
  <?php
    hide($content['comments']);
    hide($content['links']);
    print render($content);
  ?>
</article>
