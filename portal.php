<style>
	#chat_convo{
		max-height: 65vh;
	}
	#chat_convo .direct-chat-messages{
		min-height: 50vh;
		height: inherit;
	}
	#chat_convo .card-body {
		overflow: auto;
	}
	.conta{
		padding-top: 20px;
		overflow: auto;
		height: 70vh;
	}
	.conta-askme{
		height: 50vh;
	}
</style>
<div class="container-fluid conta">
	<div class="row">
		<div class="col-lg-10 <?php echo isMobileDevice() == false ?  "offset-1" : '' ?> conta-askme">
			<div class=""  id="chat_convo">
              <div class="card-header ui-sortable-handle" style="cursor: move;background-color:#724ae8">
                <h3 class="card-title" style="color: #fff;">Vidya</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg mr-4">
                    <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text" style="font-size: small;">
                      <?php echo $_settings->info('intro') ?>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  
                  <!-- /.contacts-list -->
                </div>
                <div class="end-convo"></div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              <div class="">
                <form id="send_chat" method="post">
                  <div class="input-group" style="padding-bottom: 10px;">
                    <textarea type="text" name="message" placeholder="Type Message ..." class="form-control" required="" rows="1"></textarea>
                    <span class="input-group-append">
                      <button type="submit" class="btn" style="background-color:#724ae8;color:#fff">Send</button>
                    </span>
                  </div>
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
		</div>
	</div>
</div>
<div class="d-none" id="user_chat">
	<div class="direct-chat-msg right  ml-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('user_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text" style="background-color: #724ae8;color:#fff;font-size:small"></div>
        <!-- /.direct-chat-text -->
    </div>
</div>
<div class="d-none" id="bot_chat">
	<div class="direct-chat-msg mr-4">
        <img class="direct-chat-img border-1 border-primary" src="<?php echo validate_image($_settings->info('bot_avatar')) ?>" alt="message user image">
        <!-- /.direct-chat-img -->
        <div class="direct-chat-text" style="font-size:small"></div>
        <!-- /.direct-chat-text -->
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('[name="message"]').keypress(function(e){
			console.log()
			if(e.which === 13 && e.originalEvent.shiftKey == false){
				$('#send_chat').submit()
				return false;
			}
		})
		$('#send_chat').submit(function(e){
			e.preventDefault();
			var message = $('[name="message"]').val();
			if(message == '' || message == null) return false;
			var uchat = $('#user_chat').clone();
			uchat.find('.direct-chat-text').html(message);
			$('#chat_convo .direct-chat-messages').append(uchat.html());
			$('[name="message"]').val('')
			$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");

			$.ajax({
				url:_base_url_+"classes/Master.php?f=get_response",
				method:'POST',
				data:{message:message},
				error: err=>{
					console.log(err)
					alert_toast("An error occured.",'error');
					end_loader();
				},
				success:function(resp){
					if(resp){
						resp = JSON.parse(resp)
						if(resp.status == 'success'){
							var bot_chat = $('#bot_chat').clone();
								bot_chat.find('.direct-chat-text').html(resp.message);
								$('#chat_convo .direct-chat-messages').append(bot_chat.html());
								$("#chat_convo .card-body").animate({ scrollTop: $("#chat_convo .card-body").prop('scrollHeight') }, "fast");
						}
					}
				}
			})
		})

	})
</script>
