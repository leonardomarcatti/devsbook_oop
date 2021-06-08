<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base?>media/avatars/<?=$userInfo->avatar?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$userInfo->nome?></div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-photo">
                <img src="<?=$base?>assets/images/photo.png" />
                <input type="file" name="photo" id="photo" class="feed-new-file" accept="image/png, image/jpeg, image/jpg">
            </div>
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
    let feedPhoto = document.querySelector('.feed-new-photo');
    let feedFile = document.querySelector('.feed-new-file');

    feed_submit.addEventListener('click', function () {  
        let value = feed_input.innerText.trim();
        feed_form.querySelector('#body').value = value;
        feed_form.submit();
        window.location.href = window.location.href;
     });

    feedPhoto.addEventListener('click', function(){
        feedFile.click();
    });

    feedFile.addEventListener('change', async function(){
        let photo = feedFile.files[0];
        let formData = new FormData();

        formData.append('photo', photo);
        let req = await fetch('ajax_upload.php', {
            method: 'POST',
            body: formData
        });
        let json = await req.json();

        if(json.error != '') {
            alert(json.error);
        };
        console.log(photo);
        window.location.href = window.location.href;
    });


</script>