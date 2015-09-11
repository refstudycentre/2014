<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($display_submitted || $unpublished): // no title ?>
    <header>
      <?php if ($display_submitted): ?>
        <p class="submitted">
          <?php print $user_picture; ?>
          <?php print $submitted; ?>
        </p>
      <?php endif; ?>

      <?php if ($unpublished): ?>
        <mark class="unpublished"><?php print t('Unpublished'); ?></mark>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php

  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);

  print render($content);

  // remove the 'read more' link, since there is nothing more to read!
  unset($content['links']['#links']['node-readmore']);

  // Make sure that the links are on a new line by wrapping it in a div
  print "<div>".render($content['links'])."</div>";

  print render($content['comments']);

  ?>

</article>
