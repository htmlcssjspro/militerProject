<?php foreach ($Model->getAsideNav() as $link) : ?>
    <a href="/<?= $link['page_uri'] ?>"><?= $link['label'] ?></a>
<?php endforeach; ?>
