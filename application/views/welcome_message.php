<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="webix/codebase/webix.css" type="text/css" charset="utf-8">
        <script src="webix/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
    </head>
    <body>
        <script type="text/javascript" charset="utf-8">
        	const baseUrl = '<?php echo base_url()?>';
         	webix.ready(function(){
         		const loginClick = function(){
         			var formData = new FormData();
         			formData.append('email', $$('email').getValue());
         			formData.append('password', $$('password').getValue());
         			var promise = webix.ajax().post(baseUrl + 'login/authentication', formData);
         			promise.then (function(data){
         				let user = data.json();
         				webix.message('Welcome ' + user.email);
         				if(user.isLoggedIn == true){
         					window.location.href = baseUrl + 'admin';
         				}
         			});
         			promise.fail(function(err){
         				console.log(err);
         				if(err.status == 404){
         					webix.message('Error 404');
         				} else if (err.status == 403){
         					webix.message('Error 403');
         				}
         			});
         		};

         		webix.ui({
         			view: 'form',
         			id: 'log_form',
         			align: 'center,middle',
         			width: 300,
         			elements: [
         				{
         					view: 'text',
         					label: 'Email',
         					name: 'email',
         					id:'email'
         				},
         				{
         					view: 'text',
         					type: 'password',
         					label: 'Password',
         					name: 'password',
         					id: 'password'
         				},
         				{
         					margin: 5, cols: [
         						{
         							view: 'button', value: 'Login', css: 'webix_primary', click: loginClick
         						},
         						{
         							view: 'button', value: 'Cancel'
         						}
         					]
         				}
         			]
         		});
         	});
        </script>
    </body> 
</html>