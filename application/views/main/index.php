<? pear::extends('_templates/orange_default') ?>

<? pear::section('section_container') ?>

<h1>Welcome <small><?=user::username() ?></small></h1>

<p><a href="<?=site_url('{login}') ?>">Login</a></p>

<? pear::end() ?>
