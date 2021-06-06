</section>
    <div class="modal">
            <div class="modal-inner">
                <a rel="modal:close">&times;</a>
                <div class="modal-content"></div>
            </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?=$base?>assets/js/script.js"></script>
    <script type="text/javascript" src="<?=$base?>assets/js/vanillaModal.js"></script>
    <script>
        document.querySelector('#setup_form').addEventListener('submit', function (e) { 
            let senha = document.querySelector('#senha').value;
            let senha2 = document.querySelector('#senha2').value;
            if (senha != senha2) {
                e.preventDefault();
                alert('As senhas são diferentes!');
                $('#senha').removeClass('input');
                $('#senha').addClass('input_error');
                $('#senha2').removeClass('input');
                $('#senha2').addClass('input_error');
            };
         });

         document.querySelector('#senha').addEventListener('focus', function(e){
            $('#senha').addClass('input');
            $('#senha').removeClass('input_error');
         })
         document.querySelector('#senha2').addEventListener('focus', function(e){
            $('#senha2').addClass('input');
            $('#senha2').removeClass('input_error');
         })
         setTimeout(() => {
            $('#flash').hide(250) 
         }, 1000);
    </script>
</body>
</html>