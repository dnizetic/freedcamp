<h2>Comments for item: <?= $item_id; ?> </h2>

<div class="new_message" style="display: none">
    There <span class="num_new_comments"></span> 
    Click [<a class="show_new_comments" href="#!">here</a>] to view.
</div>

<table class="tg" id="comments_table">
    <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td class="tg-yw4l">#<?= $comment->commentid; ?></td>
                <td class="tg-yw4l"><?= $comment->description; ?></td>
                <td class="tg-yw4l"><?= $comment->date; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br/> 

<div class="users_typing"></div>
<form>
    <textarea cols="45" rows="10" placeholder="Type a comment" name='description' class="description"></textarea>
    <br/>
    <button class="submit_comment">Submit</button>
</form>


<script type="text/javascript">
    /**
     * Static - passed to ajax to check for new comments for current item
     * @type String
     */
    var itemId = "<?= $item_id; ?>";

    /**
     * This value will change. When user clicks
     * "Click here to view", this variable is set
     * to latest comment's date so that polling
     * query seeks only comments after latestDate.
     * @type String
     */
    var latestDate = "<?= $latest_date; ?>";


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

                    /**
                     * Populate the queue with new rows, which will be
                     * appended if user clicks the show rows link.
                     */
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
        if (total > 0) {
            var scrollId = queueAddRows[0].commentid;
            $('html, body').animate({
                scrollTop: $("#" + scrollId).offset().top - 25
            }, 1000);
        }


        /**
         * Empty the queue as the rows are appended to table.
         */
        queueAddRows = [];

        /**
         * Hide the message
         */
        $('.new_message').hide();
    });



    /**
     * Query each 2s to check if other users are typing.
     * @type Number
     */
    var checkUsersTypingInterval = 2000;

    /**
     * Once user starts typing, we create a row in users_typing.
     * This can occur each 2 seconds.
     * @type Number    
     */
    var requestLimit = 2000;
    var $input = $('.description');
    var serverNotified = false;
    var typingTimer;

    $input.on('keyup', function () {

        if (!serverNotified) {

            /**
             * Insert a row to notify other users
             * of a typing user.
             */
            userIsTyping();

            /**
             * Make sure this cannot occure more than
             * once in 'requestLimit' seconds.
             * @returns {undefined}
             */
            serverNotified = true;

            setTimeout(function () {
                serverNotified = false;
            }, requestLimit);
        }

    });



    /**
     * Long polling method for finding out whether
     * there are users typing a new comment.
     */
    setInterval(function () {
        $.ajax({
            url: "../ajax/check_users_typing",
            type: 'POST',
            dataType: "json",
            data: {
                csrf_token: csrf_value,
            },
            success: function (response) {
                if (response.data.length > 0) {
                    var usersWriting = '';
                    $.each(response.data, function (index, obj) {
                        usersWriting += '<p>User (IP: ' + obj.ip + ') is currently writing!</p>';
                    });
                    $('.users_typing').html(usersWriting);
                } else {
                    $('.users_typing').html('');
                }
            }
        });
    }, checkUsersTypingInterval);


    /**
     * Insert a record in users_typing so that
     * other users can be notified.
     */
    function userIsTyping()
    {
        $.ajax({
            url: "../ajax/user_typing",
            type: 'POST',
            dataType: "json",
            data: {
                csrf_token: csrf_value,
                typing: 1
            },
        });
    }


    $('.submit_comment').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "../ajax/insert_comment",
            type: 'POST',
            dataType: "json",
            data: {
                csrf_token: csrf_value,
                description: $('.description').val(),
                item_id: itemId
            },
        });
    });


</script>