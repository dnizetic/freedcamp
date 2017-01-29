<div class="new_message" style="display: none">
    There <span class="num_new_comments"></span> 
    Click [<a class="show_new_comments" href="#!">here</a>] to view.
</div>

<table class="tg" id="comments_table">
    <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td class="tg-yw4l">#<?php echo $comment->commentid; ?></td>
                <td class="tg-yw4l"><?php echo $comment->description; ?></td>
                <td class="tg-yw4l"><?php echo $comment->date; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<script type="text/javascript">
    /**
     * Static - passed to ajax to check for new comments for current item
     * @type String
     */
    var itemId = '<?php echo $item_id; ?>';

    /**
     * This value will change. When user clicks
     * "Click here to view", this variable is
     * to latest comment's date so that polling
     * query seeks only comments after latestDate.
     * @type String
     */
    var latestDate = '<?php echo $latest_date; ?>';


    /**
     * Polling interval. Right now JS will query for new comments
     * each 5 seconds.
     * @type Number
     */
    var pollingInterval = 5000; //Move this to config

    /**
     * Holds the new rows to be added to the end of the table. This array
     * will be updated by the polling function and emptied when user
     * clicks to Display new rows.
     * @type Array
     */
    var queueAddRows = [];

    /**
     * Long polling method for finding out whether
     * there are new comments inserted in the DB for
     * this item. This method queries the server each 5 seconds to
     * check if there are new comments to be added to table.
     */
    setInterval(function () {
        $.ajax({
            url: "../ajax/check_new_comments", //Would use an absolute URL, but it's on localhost and VPS isn't setup, so this will work for now even though it's not good.
            type: 'POST',
            dataType: "json",
            data: {
                csrf_token: csrf_value,
                latest_date: latestDate,
                item_id: itemId
            },
            success: function (response) {
                if (response.status == 1) {
                    //New rows. Append to end of table.
                    $('.new_message').show();
                    
                    /**
                     * Add number of comments added.
                     */
                    var newMessage = (response.data.length > 1 ? 'are ' : 'is ') + response.data.length + " new comment" + (response.data.length > 1 ? 's ' : '') + ". ";
                    $('.num_new_comments').text(newMessage);

                    queueAddRows = response.data;
                }
            }
        });
    }, pollingInterval);



    /**
     * Appends queued rows to end of table and scrolls to first added row.
     */
    $('.show_new_comments').click(function () {

        var total = queueAddRows.length;

        /**
         * Iterate through rows in queue and append them to table.
         */
        $.each(queueAddRows, function (index, obj) {
            var newRow = "<tr id='" + obj.commentid + "'><td>#" + obj.commentid + "</td><td>" + obj.description + "</td><td>" + obj.date + "</td></tr>";
            $('#comments_table tbody').append(newRow);

            if (index == (total - 1)) {
                /**
                 *  Last row. Take date field and set the latest date.
                 *  This is to prevent further requests from 
                 *  showing the "New rows" message. 
                 */
                latestDate = obj.date;
            }

        });

        /**
         * Scroll to the first added row.
         */
        var scrollId = queueAddRows[0].commentid;
        $('html, body').animate({
            scrollTop: $("#" + scrollId).offset().top - 25
        }, 1000);


        /**
         * Empty the queue as the rows are appended to table.
         */
        queueAddRows = [];

        /**
         * Hide the message
         */
        $('.new_message').hide();
    });


</script>