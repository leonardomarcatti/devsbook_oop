<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base?>media/avatars/<?=$userInfo->avatar?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$userInfo->nome?></div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-send">
                <img src="<?=$base?>assets/images/send.png"/>
            </div>
            <form class="feed-new-form" action="<?=$base?>feed_editor_action.php" method="post">
                <input type="hidden" name="body" id="body">
            </form>
        </div>
    </div>
</div>
<script>
    let feed_input = document.querySelector('.feed-new-input');
    let feed_submit = document.querySelector('.feed-new-send');
    let feed_form = document.querySelector('.feed-new-form');
    feed_submit.addEventListener('click', function () {  
        let value = feed_input.innerText.trim();
        feed_form.querySelector('#body').value = value;
        feed_form.submit();
     });
</script>