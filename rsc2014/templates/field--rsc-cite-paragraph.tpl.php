<?php

/**
 * Fields for rsc-cite-short content type
 */
$item = array_shift($items); // cardinality = 1
?>
<p class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php print render($item); ?>
</p>
