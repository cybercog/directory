
<!--<pre>
<?=print_r($directories, true);?>
</pre>-->

<?php if(count($directories) > 0) { ?>
<div class="directory-directory-list">
    <div class="directory-directory-list-count">
        <span><?=count($directories)?></span>
    </div>
    <div class="directory-directory-list-items-parent">
        <div class="directory-directory-list-arrow"></div>
        <div class="directory-directory-list-arrow-background"></div>
        <div class="directory-directory-list-items">
            <?php foreach ($directories as $directory) { ?>
            <div><nobr><?=$directory['name']?></nobr></div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="directory-emply-directory-list">
    <span>(нет)</span>
</div>
<?php } ?>
