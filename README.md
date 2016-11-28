This repository is done for! All new commits should go to these repositories:

- https://github.com/refstudycentre/rsc2014
- https://github.com/refstudycentre/rsc2014_cl
- https://github.com/refstudycentre/rsc2014_rc

Move the issues in this repository over there before deleting this repository.
Don't delete this repository yet, if there are some sites still using the 7.x-1.x branch!

# 2014
Zen-based Drupal theme (2014 design) for RSC websites
Based on `Zen-7.x-5.6`

## Compiling

`compass _0.12.6_ watch`

or

`compass _0.12.6_ compile`

## Themes in this project

### rsc2014
Base theme for all websites using this design.
Subtheme of `Zen-7.x-5.6`.

### rsc2014_cl
Subtheme of `rsc2014`.
Used by [Christian Library](http://www.christianstudylibrary.org)

### rsc2014_rc
Subtheme of `rsc2014`.
Used by [Ressources Chrétiennes](http://www.ressourceschretiennes.com)

## Theme flavours
Different parts of the website use different color schemes and layouts. For this, there are three scss files:
- base.scss
- lib.scss (used at www.christianstudylibrary.org )
- pl.scss (used at www.christianstudylibrary.org/pl )
template.php contains some logic to decide which flavour should be used.
