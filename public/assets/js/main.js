var simpleChat ;

(function() {
	"use strict";

	Element.prototype.on = function(listener, callback) { 
	 this.addEventListener(listener, callback, true);
	}

	// HTTP Client
	var AjaxClient = (function() {
		
		var sendRequest = function(method, url , data, callback) {
			var HTTPclient = new XMLHttpRequest();
			
			HTTPclient.open(method, url, true); 
			
			if(method == 'POST') {
				HTTPclient.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			};

			HTTPclient.onreadystatechange = function(progress) {						
				if(this.readyState == this.DONE && this.status == 200) {
					callback.call(this, this.responseText);
				}
			}
			HTTPclient.send(data);
		};

		return {
			post: function(url , data, callback) {
				sendRequest("POST", url,data,callback);
			},

			get: function(url, data, callback) {
				sendRequest("GET", url,data,callback);
			}
		}
	})();

	simpleChat = {

		__init: function() {
			var self 				= this;
			this.ajaxClient 		= AjaxClient;

			this.newMessageForm 	= document.querySelector('#new_message_form');
			this.newMessageBtn 		= this.newMessageForm.querySelector('#new_message');
			this.newMessageText 	= this.newMessageForm.querySelector("#message_text");
			this.messagesWrapper    = document.querySelector('#messages');

			this.can_send_message	= true;

			this.newMessageForm.on('submit', function(ev) {
				ev.preventDefault();
				// use HTML5 FormData
				self.sendMessage(self.newMessageText.value)
			});
			

			this.promptUserName() ;
			self.waitForNewMessages(self.getTimestamp());
		},

		getTimestamp: function(timestamp) {
			var d 			= timestamp != undefined ? new Date(timestamp) : new Date(),
				date 		= d.toISOString().slice(0,10),
				time 		= d.toTimeString().slice(0,8),
				timestamp   = (date + " " + time).trim();

			return timestamp;
		},

		promptUserName: function() {
			do {
				this.username = prompt("Hey wassup! Tell us your name/nickname ?\nYour nickname must have at least 4 characters.")
			} while(typeof this.username == 'undefined' || this.username == null || this.username.length < 4);
		},

		toggleEnabled: function(elements) {
			for(var i in elements) {
				var element 			= elements[i] ;
				if(element && element instanceof HTMLElement)
					element.disabled 	= !element.disabled;
			};
			return elements;
		},

		sendMessage: function(message) {
			var self 	= this;
			if(!self.can_send_message) return alert('Heeyyyy, wait...') ;

			if(self.isValidMessage(message)) {
				var toDisable = [this.newMessageBtn, this.newMessageText];
				self.toggleEnabled(toDisable);

				self.ajaxClient.post(this.newMessageForm.getAttribute('action'), self.encodeURLData({message: message.trim() ,  username:  self.username}), function(data) {

					data 	= JSON.parse(data);

					// toogle enabled anyway
					self.toggleEnabled(toDisable);

					if(data.success) {
						self.newMessageText.value 	= ''	; // clear message value
						self.can_send_message 		= false	; 
						setTimeout(function() {  self.can_send_message 	= true; }, 1500);
					}
				});
				
			} else {
				alert('Your message must have at least 4 characters');
				return false;
			}
		},

		isValidMessage: function(message) {
			return (message && typeof message == 'string' && message.trim().length > 3)
		},

		generateMessageHTML: function(message) {
			var date =  this.getTimestamp();
			return ["<li>" , "<span class='message-date icon time'>&nbsp;" , date , "&nbsp; | &nbsp;</span>" , "<span class='message-owner icon user'>&nbsp;" , message.username , "</span>"  , "<span class='message'>" , message.message , "</span>" , "</li>" ].join('');	
		},

		generateHTML: function(messages) {
			var totalMessages 	= messages.length,
				html 			= '' ;

			for(var i = 0; i < totalMessages ; i++ ) {
				var message = messages[i] ;
				if(message)
					html += this.generateMessageHTML(message);
			}

			return html ;
		},

		encodeURLData: function(data) {
		    return Object.keys(data).map(function(key) {
		        return [key, data[key]].map(encodeURIComponent).join("=");
		    }).join("&");
		},
		
		appendMessage: function(html) {
			this.messagesWrapper.innerHTML += html ; 
			var lastMessage = this.messagesWrapper.querySelector("li:last-child");
			window.scrollTo(0,lastMessage.offsetTop || 0);
		},
		
		playNotificationSound: function() {
			document.querySelector("#notification_sound").play();
		},

		waitForNewMessages: function(timestamp) {
			var self = this;
			this.ajaxClient.post('get-new-messages/' , this.encodeURLData({timestamp: timestamp}) , function(data) {
				
				data 		= JSON.parse(data);

				console.log(data);

				var html 	= self.generateHTML(data.messages);

				self.appendMessage(html);
				if(!data.timeout && (data.username != self.username)) 
					self.playNotificationSound();
				
				self.waitForNewMessages(data.timestamp);
			});
		}
	}
	simpleChat.__init();
})();