<?php

/**
 * Fields for rsc-cite-paragraph content type
 */
$item = array_shift($items); // cardinality = 1
?>
<span class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php print render($item); ?>
</span>
