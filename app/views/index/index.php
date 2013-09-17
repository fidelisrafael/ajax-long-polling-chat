<div class="messages-wrapper">
	<article class="container">
		<header><h1>Ajax Long Polling Chat</h1></header>
		<ul id="messages" class='messages-list'>
			<?php echo $messages ?>
		</ul>
	</article>
</div>

<div class="actions">
	<div class="new_message container">
		<form action="send-message/" id='new_message_form'>
			<input type="text" name='message[message]' id="message_text" class='input medium' />
			<button type='submit' class="button" id='new_message'><i class="icon envelope-alt">&nbsp;Enviar Mensagem</i></button>
		</form>
	</div>
</div>


<div class="audio-wrapper" style='display:none;position:absolute;'>
	<audio preload id='notification_sound'>
		<source src="public/files/sounds/notification.mp3"></source>
		<source src="public/files/sounds/notification.ogg"></source>
	</audio>
</div>

