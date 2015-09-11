<?php /*
This name of this field depends on the name of the vocabulary used for field values.
The file name should be modified accordingly.
*/ ?>

<span class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php
  $authors = array();
  foreach ($items as $delta => $item) {
    $authors[] = render($item);
  }
  print implode(',&nbsp;',$authors);
  ?>
</span>
