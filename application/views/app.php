<?php pear::extends('_templates/orange_default') ?>

<?php pear::section('page_js') ?>
<?php pear::parent() ?>
<script src="//cdn.jsdelivr.net/npm/tinybind@0.11.0/dist/tinybind.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/tinysort/3.1.4/tinysort.js"></script>
<script src="/compressed/combined.min.js"></script>
<?php pear::end() ?>

<?php pear::section('page_css') ?>
<?php pear::parent() ?>
<link href="/compressed/combined.min.css" rel="stylesheet">
<?php pear::end() ?>