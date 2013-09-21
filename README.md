Long Polling Ajax Chat
========================

### What's it

This is a very simple long polling chat demonstration.	
I made it just for fun , but maybe it can help someone in any place of the world :3

----
### How works

This chat works keeping a **single** connection open between `client` and `server` ,  this connection will be the responsible to detect any replies from server , and when it occurs, the connection is closed, and a new  `HTTP request` is opened to keep watching the server again. 	


This app runs over a **very** simple *framework* , or a kind of , that i've developed for this app. *(It just do basic URL matches, controllers logic and views rendering)* 	 
No jQuery dependency for `XHR` requests and DOM updates. 

----
### Setup

**1** - First of all, of course , clone this repository		
**2** - Create the file `app/config/db/db-config-development.php`(just rename the `db-config-sample` file and edit database access configurations)	
**3** - Access the app using your local apache installation, you will be prompted to setup things.	
**4** - Now setup the main database, and next go to `/setup` to finish the tables migration.	
**5** - Play it harder, make it better, do it faster, make us stronger :)

---
### Screenshots 

![Simple chat][1]

----
### TODO

- Better documentation
- User's  authentication(i'm not planning this, but who knows)
- Basic framework's docs(just go ahead and study this if you're curious, it's very very simple)
- Improve mysql perfomance
- Tests

----
### License
The project is licensed under the MIT license. See LICENSE file for details.

---
### How to Contribute

##### Pull Requests

**1** Fork this repository	
**2.** Create a new branch for each feature or improvement	
**3.** Send a pull request from each feature branch to the develop branch	


  [1]: http://i.imgur.com/6hXj1fX.png