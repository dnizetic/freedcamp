
<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
    .tg .tg-yw4l{vertical-align:top}
</style>


<table class="tg">
    <?php foreach ($comments as $comment): ?>

        <tr>
            <th class="tg-yw4l">#<?php echo $comment->commentid; ?></th>
            <th class="tg-yw4l"><?php echo $comment->description; ?></th>
            <th class="tg-yw4l"><?php echo $comment->date; ?></th>
        </tr>
    <?php endforeach; ?>

</table>

