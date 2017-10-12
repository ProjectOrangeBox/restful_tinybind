<? page::extends('_templates/orange_default') ?>

<? page::section('section_container') ?>
<h1>Welcome!</h1>
<p><a href="<?=site_url('{login}') ?>">Login</a></p>

<p>Username: <?=user::username() ?></p>

<? page::end() ?>